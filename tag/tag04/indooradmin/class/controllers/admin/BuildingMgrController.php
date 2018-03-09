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
		// if($buildinginfo['PIC_ID']){
		// $Cdao_pic = new CDAOCB_PIC ();
		// $where="PIC_ID=".$buildinginfo['PIC_ID'];
		// $picinfo = $Cdao_pic->getAllByWhere ( $where );
		// $buildinginfo['FILENAME']=$picinfo[0]['FILENAME'];
		// }
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
			if (! empty ( $buildinginfo ["LT_LONGITUDEL"] )) {
				$buildinginfo ["LT_LONGITUDEL"] = SvgUtil::getDecodeLatLng ( $buildinginfo ["LT_LONGITUDEL"] );
			}
			if (! empty ( $buildinginfo ["LT_LATITUDEL"] )) {
				$buildinginfo ["LT_LATITUDEL"] = SvgUtil::getDecodeLatLng ( $buildinginfo ["LT_LATITUDEL"] );
			}
			if (! empty ( $buildinginfo ["RB_LONGITUDEL"] )) {
				$buildinginfo ["RB_LONGITUDEL"] = SvgUtil::getDecodeLatLng ( $buildinginfo ["RB_LONGITUDEL"] );
			}
			if (! empty ( $buildinginfo ["RB_LATITUDEL"] )) {
				$buildinginfo ["RB_LATITUDEL"] = SvgUtil::getDecodeLatLng ( $buildinginfo ["RB_LATITUDEL"] );
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
		$Cdao_Building = new CDAOCB_BUILDING ();
		$BUILDING_ID = $FORM_BUILDINGINFO ["{$Cdao_Building->_table_seq_name}"];
		if (empty ( $BUILDING_ID )) {
			$BUILDING_ID = $Cdao_Building->getNextSeqId ();
			$FORM_BUILDINGINFO ["{$Cdao_Building->_table_seq_name}"] = $BUILDING_ID;
			$FORM_BUILDINGINFO ["CREATE_TIME"] = date ( "Y-m-d H:i:s" );
		}
		// 保存图片信息
		if ($_FILES ["file"] ['name']) {
			$FILE_NAME=$FORM_BUILDINGINFO ["BUILDING_NAME"]."_".date ( "YmdHis" );
			$picAdd_returnPic_id = SvgUtil::savePic ( $FILE_NAME, '', $_FILES ["file"], '', $FORM_BUILDINGINFO ["BUILDING_ID"], '' ); // 返回PIC_ID
			if ($picAdd_returnPic_id == '') {
				$this->jsAlert ( "保存失败！" );
				$this->jsJumpBack ();
			}
			$FORM_BUILDINGINFO ['PIC_ID'] = $picAdd_returnPic_id;
		}
		if (! empty ( $FORM_BUILDINGINFO )) {
			if (! empty ( $FORM_BUILDINGINFO ["LT_LONGITUDEL"] )) {
				$FORM_BUILDINGINFO ["LT_LONGITUDEL"] = SvgUtil::getEncodeLatLng ( $FORM_BUILDINGINFO ["LT_LONGITUDEL"] );
			}
			if (! empty ( $FORM_BUILDINGINFO ["LT_LATITUDEL"] )) {
				$FORM_BUILDINGINFO ["LT_LATITUDEL"] = SvgUtil::getEncodeLatLng ( $FORM_BUILDINGINFO ["LT_LATITUDEL"] );
			}
			if (! empty ( $FORM_BUILDINGINFO ["RB_LONGITUDEL"] )) {
				$FORM_BUILDINGINFO ["RB_LONGITUDEL"] = SvgUtil::getEncodeLatLng ( $FORM_BUILDINGINFO ["RB_LONGITUDEL"] );
			}
			if (! empty ( $FORM_BUILDINGINFO ["RB_LATITUDEL"] )) {
				$FORM_BUILDINGINFO ["RB_LATITUDEL"] = SvgUtil::getEncodeLatLng ( $FORM_BUILDINGINFO ["RB_LATITUDEL"] );
			}
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
	 *
	 * @return void boolean
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