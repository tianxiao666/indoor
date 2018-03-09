<?php
/**
 * 建筑物场所管理
 * @author xiang.zc
 *
 */
class BuildingMgrController extends AdminController {
	protected $pageSize = 15;
	public function __construct() {
		parent::__construct ();
	}
	public function actionIndex() {
		$flag = $_GET ["flag"];
		$BUILDINGSEARCHINFO = array ();
		if ($flag == 2) {
			$BUILDINGSEARCHINFO = $_POST ["BUILDINGSEARCHINFO"];
			if (empty ( $BUILDINGSEARCHINFO )) {
				$BUILDINGSEARCHINFO = CUserSession::getSessionValue ( "BUILDINGSEARCHINFO" );
			} else {
				CUserSession::setSessionValue ( "BUILDINGSEARCHINFO", $BUILDINGSEARCHINFO );
			}
		} else {
			CUserSession::removeSessionValue ( "BUILDINGSEARCHINFO" );
		}
		$whereSql = " 1=1";
		$pageNum = $_GET ["page"];
		$baseUrlFlag = "";
		if (empty ( $pageNum )) {
			$pageNum = 1;
		}
		$Cdao_Building = new CDAOCB_BUILDING ();
		if (! empty ( $BUILDINGSEARCHINFO )) {
			$baseUrlFlag = "&flag=2";
			$KEYWORD = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $BUILDINGSEARCHINFO ["KEYWORD"] ) ) );
			$TYPE = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $BUILDINGSEARCHINFO ["TYPE"] ) ) );
			if (! empty ( $KEYWORD )) {
				$KEYWORD_LOWER = strtolower ( $KEYWORD );
				$whereSql = $whereSql . " AND LOWER(BUILDING_NAME) LIKE '%{$KEYWORD_LOWER}%'";
			}
			if (! empty ( $TYPE )) {
				$whereSql = $whereSql . " AND BUILD_TYPE = '{$TYPE}'";
			}
			$pageData ["BUILDINGSEARCHINFO"] = $BUILDINGSEARCHINFO;
		}
		$result = $Cdao_Building->listPageData ( $whereSql, $pageNum, $this->pageSize );
		$total = $result->_maxRecordCount;
		$p = new page ( $total );
		$p->baseUrl = "ea.php?r=BuildingMgr" . $baseUrlFlag;
		$p->pagesize = $this->pageSize;
		$page = $p->generate ();
		$pageData ["page"] = $page;
		$building_list = $result->_array;
		$cdaosys_region = new CDAOSYS_REGION ();
		if ($total > 0) {
			foreach ( $building_list as $k => $v ) {
				$COUNTRY = $cdaosys_region->getRegionById ( $v ["COUNTRY"] );
				if (! empty ( $COUNTRY )) {
					$v ["COUNTRY"] = $COUNTRY ["REGION_NAME"];
				}
				$PROV = $cdaosys_region->getRegionById ( $v ["PROV"] );
				if (! empty ( $PROV )) {
					$v ["PROV"] = $PROV ["REGION_NAME"];
				}
				$building_list [$k] = $v;
			}
		}
		$cdict = new CDict ();
		$pageData ["BUILD_TYPE"] = $cdict->BUILD_TYPE;
		$pageData ["BUILD_STATUS"] = $cdict->BUILD_STATUS;
		$pageData ["building_list"] = $building_list;
		$this->render ( "building_list", $pageData );
	}
	public function actionViNew() {
		$cdaosys_region = new CDAOSYS_REGION ();
		$coninentList = $cdaosys_region->getRegionListByGrade ( '-1' );
		$pageData ["coninentList"] = $coninentList;
		$cdict = new CDict ();
		$pageData ["BUILD_TYPE"] = $cdict->BUILD_TYPE;
		$pageData ["BUILD_STATUS"] = $cdict->BUILD_STATUS;
		$this->render ( "building_edit", $pageData );
	}
	public function actionViEdit() {
		$BUILDING_NAME = $_GET ["BUILDING_NAME"];
		$buildingID = $_GET ["buildingID"];
		$Cdao_Building = new CDAOCB_BUILDING ();
		$buildinginfo = $Cdao_Building->infoBuilding ( $buildingID );
		if (! empty ( $buildinginfo )) {
			$cdaosys_region = new CDAOSYS_REGION ();
			$coninentList = $cdaosys_region->getRegionListByGrade ( '-1' );
			$pageData ["coninentList"] = $coninentList;
			$cdict = new CDict ();
			if (! empty ( $buildinginfo ["CONTINENT"] )) {
				$countryList = $cdaosys_region->getRegionListByParentId ( $buildinginfo ["CONTINENT"] );
				$pageData ["countryList"] = $countryList;
				if (! empty ( $buildinginfo ["COUNTRY"] )) {
					$provList = $cdaosys_region->getRegionListByParentId ( $buildinginfo ["COUNTRY"] );
					$pageData ["provList"] = $provList;
					if (! empty ( $buildinginfo ["PROV"] )) {
						$cityList = $cdaosys_region->getRegionListByParentId ( $buildinginfo ["PROV"] );
						$pageData ["cityList"] = $cityList;
						if (! empty ( $buildinginfo ["CITY"] )) {
							$districtList = $cdaosys_region->getRegionListByParentId ( $buildinginfo ["CITY"] );
							$pageData ["districtList"] = $districtList;
						}
					}
				}
			}
			if (! empty ( $buildinginfo ["OPEN_START"] )) {
				$timestamp = strtotime ( $buildinginfo ["OPEN_START"] );
				$buildinginfo ["OPEN_START"] = Date ( "H:i", $timestamp );
			}
			if (! empty ( $buildinginfo ["OPEN_END"] )) {
				$timestamp = strtotime ( $buildinginfo ["OPEN_END"] );
				$buildinginfo ["OPEN_END"] = Date ( "H:i", $timestamp );
			}
			if (! empty ( $buildinginfo ["LONGITUDEL"] )) {
				$buildinginfo ["LONGITUDEL"] = $buildinginfo ["LONGITUDEL"] / 1000 / 3600;
			}
			if (! empty ( $buildinginfo ["LATITUDEL"] )) {
				$buildinginfo ["LATITUDEL"] = $buildinginfo ["LATITUDEL"] / 1000 / 3600;
			}
			$pageData ["BUILDING_NAME"] = $BUILDING_NAME;
			$pageData ["BUILDING_ID"] = $buildingID;
			$pageData ["buildinginfo"] = $buildinginfo;
			$pageData ["BUILD_TYPE"] = $cdict->BUILD_TYPE;
			$pageData ["BUILD_STATUS"] = $cdict->BUILD_STATUS;
			$this->render ( "building_edit", $pageData );
		} else {
			$this->jsAlert ( "获取建筑物信息失败！" );
			$this->jsJumpBack ();
		}
	}
	public function actionDoEdit() {
		$FORM_BUILDINGINFO = $_POST ["FORM_BUILDINGINFO"];
		if (! empty ( $FORM_BUILDINGINFO )) {
			if (! empty ( $FORM_BUILDINGINFO ["OPEN_START"] )) {
				$FORM_BUILDINGINFO ["OPEN_START"] = "2013-09-10 " . $FORM_BUILDINGINFO ["OPEN_START"];
			}
			if (! empty ( $FORM_BUILDINGINFO ["OPEN_END"] )) {
				$FORM_BUILDINGINFO ["OPEN_END"] = "2013-09-10 " . $FORM_BUILDINGINFO ["OPEN_END"];
			}
			if (! empty ( $FORM_BUILDINGINFO ["LONGITUDEL"] )) {
				$FORM_BUILDINGINFO ["LONGITUDEL"] = $FORM_BUILDINGINFO ["LONGITUDEL"] * 1000 * 3600;
			}
			if (! empty ( $FORM_BUILDINGINFO ["LATITUDEL"] )) {
				$FORM_BUILDINGINFO ["LATITUDEL"] = $FORM_BUILDINGINFO ["LATITUDEL"] * 1000 * 3600;
			}
			$Cdao_Building = new CDAOCB_BUILDING ();
			$result = $Cdao_Building->doEdit ( $FORM_BUILDINGINFO );
			if ($result === false) {
				$this->jsAlert ( "保存失败！" );
				$this->jsJumpBack ();
			} else {
				$this->jsAlert ( "保存成功！" );
				$this->jsJumpUrl ( "ea.php?r=BuildingMgr" );
			}
		} else {
			$this->jsAlert ( "非法请求！" );
			$this->jsJumpBack ();
		}
	}
	
	/**
	 * 修改楼层状态
	 * Enter description here .
	 * ..
	 */
	public function actionChangeStateAll() {
		$building_id_list = explode ( ",", $_POST ['building_id_list'] );
		$length = count ( $building_id_list );
		$Cdao_Building = new CDAOCB_BUILDING ();
		$where = null;
		for($i = 0; $i < $length - 1; $i ++) {
			if ($where == null) {
				$where = 'BUILDING_ID=' . $building_id_list [$i];
			} else {
				$where = $where . ' or BUILDING_ID=' . $building_id_list [$i];
			}
		}
		$result = $Cdao_Building->SaveStatus ( $where, $_POST ['status'] );
		if (! $result) {
			$status = "状态无需修改!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
			return false;
		}
		$result = $Cdao_Building->ChangeStatus ( $where, $_POST ['status'] );
		if ($result) {
			$status = "修改成功!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
		} else {
			$status = "修改失败!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
			return false;
		}
		return;
	}
	
	/**
	 * 修改状态
	 */
	public function actionDoStatus() {
		$buildingID = $_GET ["buildingID"];
		$tostatus = $_GET ["tostatus"];
		if (! empty ( $buildingID ) && ! empty ( $tostatus )) {
			$Cdao_Building = new CDAOCB_BUILDING ();
			$result = $Cdao_Building->statusBuilding ( $buildingID, $tostatus );
			if ($result === false) {
				$this->jsAlert ( "操作失败！" );
			} else {
				$this->jsAlert ( "操作成功！" );
			}
		}
		$this->jsJumpBack ();
	}
}
?>