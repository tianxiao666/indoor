<?php
/**
 * 楼层平面图管理
 * @author xiang.zc
 *
 */
class BuildingFloorPlanegraphMgrController extends AdminController {
	protected $pageSize = 15;
	public function __construct() {
		parent::__construct ();
	}
	/**
	 * (non-PHPdoc)
	 *
	 * @see AdminController::actionIndex()
	 */
	public function actionIndex() {
		$BUILDING_ID = $_GET ["BUILDING_ID"];
		$FLOOR_ID = $_GET ["FLOOR_ID"];
		$BUILDING_NAME = $_GET ["BUILDING_NAME"];
		$flag = $_GET ["flag"];
		$BUILDINGPLANEGRAPHSEARCHINFO = array ();
		if ($flag == 3) {
			$BUILDINGPLANEGRAPHSEARCHINFO = $_POST ["BUILDINGPLANEGRAPHSEARCHINFO"];
			if (empty ( $BUILDINGPLANEGRAPHSEARCHINFO )) {
				$BUILDINGPLANEGRAPHSEARCHINFO = CUserSession::getSessionValue ( "BUILDINGPLANEGRAPHSEARCHINFO" );
			} else {
				CUserSession::setSessionValue ( "BUILDINGPLANEGRAPHSEARCHINFO", $BUILDINGPLANEGRAPHSEARCHINFO );
			}
		} else {
			CUserSession::removeSessionValue ( "BUILDINGPLANEGRAPHSEARCHINFO" );
		}
		$whereSql = "BUILDING_ID={$BUILDING_ID}";
		$baseUrlFlag = "&{$whereSql}";
		if (! empty ( $FLOOR_ID )) {
			$whereSql = $whereSql . " AND FLOOR_ID={$FLOOR_ID}";
			$baseUrlFlag = $baseUrlFlag . "&FLOOR_ID={$FLOOR_ID}";
		}
		$baseUrlFlag = $baseUrlFlag . "&BUILDING_NAME={$BUILDING_NAME}";
		$pageNum = $_GET ["page"];
		if (empty ( $pageNum )) {
			$pageNum = 1;
		}
		if (! empty ( $BUILDINGPLANEGRAPHSEARCHINFO )) {
			$baseUrlFlag = $baseUrlFlag . "&flag=3";
			$KEYWORD = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $BUILDINGPLANEGRAPHSEARCHINFO ["KEYWORD"] ) ) );
			$STATUS = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $BUILDINGPLANEGRAPHSEARCHINFO ["STATUS"] ) ) );
			$FLOOR_ID = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $BUILDINGPLANEGRAPHSEARCHINFO ["FLOOR"] ) ) );
			if (! empty ( $KEYWORD )) {
				$KEYWORD_LOWER = strtolower ( $KEYWORD );
				$whereSql = $whereSql . " AND LOWER(DM_TOPIC) LIKE '%{$KEYWORD_LOWER}%'";
			}
			if (! empty ( $STATUS )) {
				$whereSql = $whereSql . " AND STATUS = '{$STATUS}'";
			}
			if (! empty ( $FLOOR_ID )) {
				$whereSql = $whereSql . " AND FLOOR_ID = '{$FLOOR_ID}'";
			}
			$pageData ["BUILDINGPLANEGRAPHSEARCHINFO"] = $BUILDINGPLANEGRAPHSEARCHINFO;
		}
		$whereSql = $whereSql . " ORDER BY MOD_TIME DESC";
		$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
		$result = $Cdao_DrawMap->listPageData ( $whereSql, $pageNum, $this->pageSize );
		$planegraph_list = $result->_array;
		$Cdao_Floor = new CDAOCB_FLOOR ();
		$belongfloorList = array ();
		$ThumbnailPathList = array ();
		if (! empty ( $planegraph_list )) {
			$where = "";
			$where_ids = "";
			foreach ( $planegraph_list as $k => $v ) {
				$DRAW_MAP_ID_V = $v ["DRAW_MAP_ID"];
				$FLOOR_ID_V = $v ["FLOOR_ID"];
				if ($where == "") {
					$where = "FLOOR_ID={$FLOOR_ID_V}";
				} else {
					$where = $where . " OR FLOOR_ID={$FLOOR_ID_V}";
				}
				if ($where_ids == "") {
					$where_ids = "DRAW_MAP_ID={$DRAW_MAP_ID_V}";
				} else {
					$where_ids = $where_ids . " OR DRAW_MAP_ID={$DRAW_MAP_ID_V}";
				}
			}
			$belongfloorList = $Cdao_Floor->getFloorByWhere ( $where );
			$CDAO_Media = new CDAOCB_PLANE_MEDIA ();
			$where_thm = "";
			if (! empty ( $where_ids )) {
				$where_thm = "(" . $where_ids . ") AND MEDIA_TYPE='{$CDAO_Media->FILE_THM}'";
			}
			$ThumbnailPathList = $CDAO_Media->getMediaPathByWhere ( $where_thm );
			$pageData ["ThumbnailSize"] = $CDAO_Media->FILE_THM_SIZE;
		}
		$pageData ["planegraph_list"] = $planegraph_list;
		$pageData ["belongfloorList"] = $belongfloorList;
		$pageData ["ThumbnailPathList"] = $ThumbnailPathList;
		$total = $result->_maxRecordCount;
		$p = new page ( $total );
		$p->baseUrl = "ea.php?r=BuildingFloorPlanegraphMgr" . $baseUrlFlag;
		$p->pagesize = $this->pageSize;
		$page = $p->generate ();
		$pageData ["page"] = $page;
		$cdict = new CDict ();
		$pageData ["BUILD_STATUS"] = $cdict->BUILD_STATUS;
		$pageData ["PLANEGRAPH_UNIT"] = $cdict->PLANEGRAPH_UNIT;
		// $Cdao_Floor = new CDAOCB_FLOOR ();
		$floorList = $Cdao_Floor->getAllFloor ( $BUILDING_ID );
		$pageData ["floorList"] = $floorList;
		$pageData ["BUILDING_ID"] = $BUILDING_ID;
		if (! empty ( $FLOOR_ID )) {
			$pageData ["FLOOR_ID"] = $FLOOR_ID;
			$row = $Cdao_Floor->getFloor_name ( $FLOOR_ID );
			$pageData ["FLOOR_NAME"] = empty ( $row ) ? "未知" : $row [0] ["FLOOR_NAME"];
		}
		$pageData ["BUILDING_NAME"] = $BUILDING_NAME;
		$this->render ( "building_planegraph_list", $pageData );
	}
	/**
	 * 显示编缉/增加平面图信息界面
	 */
	public function actionViEdit() {
		$BUILDING_ID = $_GET ["BUILDING_ID"];
		$BUILDING_NAME = $_GET ["BUILDING_NAME"];
		if (! empty ( $BUILDING_ID )) {
			$pageData ["BUILDING_ID"] = $BUILDING_ID;
			$pageData ["BUILDING_NAME"] = $BUILDING_NAME;
			$cdict = new CDict ();
			$pageData ["BUILD_STATUS"] = $cdict->BUILD_STATUS;
			$Cdao_Floor = new CDAOCB_FLOOR ();
			$floorList = $Cdao_Floor->getAllFloor ( $BUILDING_ID );
			$pageData ["floorList"] = $floorList;
			$this->render ( "building_planegraph_edit", $pageData );
		} else {
			$this->jsAlert ( "非法请求！" );
			$this->jsJumpBack ();
		}
	}
	/**
	 * 执行保存平面图信息后跳转后绘制平面图界面
	 */
	public function actionDoEdit() {
		$FORM_INFO = $_POST ["FORM_INFO"];
		if (! empty ( $FORM_INFO )) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			$DRAW_MAP_ID = $FORM_INFO ["DRAW_MAP_ID"];
			if (empty ( $DRAW_MAP_ID )) {
				$DRAW_MAP_ID = $Cdao_DrawMap->getNextSeqId ();
				$FORM_INFO [$Cdao_DrawMap->_table_seq_name] = $DRAW_MAP_ID;
			}
			$result = $Cdao_DrawMap->doEdit ( $FORM_INFO );
			if ($result === false) {
				$this->jsAlert ( "保存失败！" );
				$this->jsJumpBack ();
			} else {
				$BUILDING_ID = $_GET ["BUILDING_ID"];
				if (empty ( $BUILDING_ID )) {
					$BUILDING_ID = $FORM_INFO ["BUILDING_ID"];
				}
				if (! empty ( $BUILDING_ID )) {
					$BUILDING_NAME = $_GET ["BUILDING_NAME"];
					$FLOOR_ID = $FORM_INFO ["FLOOR_ID"];
					$this->jsJumpUrl ( "ea.php?r=SvgMgr&BUILDING_ID={$BUILDING_ID}&BUILDING_NAME={$BUILDING_NAME}&FLOOR_ID={$FLOOR_ID}&DRAW_MAP_ID={$DRAW_MAP_ID}" );
				} else {
					$this->jsAlert ( "跳转绘制平面图页面失败！" );
					$this->jsJumpBack ();
				}
			}
		} else {
			$this->jsAlert ( "非法请求！" );
			$this->jsJumpBack ();
		}
	}
	/**
	 * 在显示编缉/增加平面图信息界面building_planegraph_edit.html中通过ajax来获取平面图信息
	 */
	public function actionAjaxInfoPlanegraph() {
		$BUILDING_ID = $_POST ["BUILDING_ID"];
		$FLOOR_ID = $_POST ["FLOOR_ID"];
		$array = array ();
		if (! empty ( $BUILDING_ID ) && ! empty ( $FLOOR_ID )) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			$array = $Cdao_DrawMap->getPlanegraphInfoByFloorId ( $BUILDING_ID, $FLOOR_ID );
			foreach ( $array as $k => $v ) {
				$array [$k] = iconv ( 'GBK', 'UTF-8', $v );
			}
		} else {
			$array ["error"] = iconv ( 'GBK', 'UTF-8', "建筑ID和楼层ID不能为空！" );
		}
		echo (json_encode ( $array ));
	}
	/**
	 * 修改状态
	 */
	public function actionAjaxDoStatus() {
		$DRAW_MAP_ID = $_POST ["DRAW_MAP_ID"];
		$TOSTATUS = $_POST ["TOSTATUS"];
		$respond = array ();
		if (! empty ( $DRAW_MAP_ID ) && ! empty ( $TOSTATUS )) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			$result = $Cdao_DrawMap->doStatus ( $DRAW_MAP_ID, $TOSTATUS );
			$respond ["error"] = ($result === false);
		}
		echo (json_encode ( $respond ));
	}
	/**
	 * 判断平面图是否有状态为正常的
	 */
	public function actionAjaxHasApp() {
		$FLOOR_ID = $_POST ["FLOOR_ID"];
		$DRAW_MAP_ID = null;
		$array = array ();
		if (! empty ( $FLOOR_ID )) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			try {
				$DRAW_MAP_ID = $Cdao_DrawMap->getNormalSeqIdByFloorId ( $FLOOR_ID );
			} catch ( Exception $e ) {
				$json ["error"] = $e->getMessage ();
			}
			if (empty ( $DRAW_MAP_ID )) {
				$DRAW_MAP_ID = null;
			}
		}
		$array ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
		echo (json_encode ( $array ));
	}
	
	/**
	 * 修改楼层状态
	 * Enter description here .
	 */
	public function actionChangeStateAll() {
		$draw_map_id_list = explode ( ",", $_POST ['draw_map_id_list'] );
		$length = count ( $draw_map_id_list );
		$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
		$where = null;
		for($i = 0; $i < $length - 1; $i ++) {
			if ($where == null) {
				$where = 'DRAW_MAP_ID=' . $draw_map_id_list [$i];
			} else {
				$where = $where . ' or DRAW_MAP_ID=' . $draw_map_id_list [$i];
			}
		}
		$result = $Cdao_DrawMap->SaveStatus ( $where, $_POST ['status'] );
		if (! $result) {
			$status = "状态无需修改!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
			return false;
		}
		$result = $Cdao_DrawMap->ChangeStatus ( $where, $_POST ['status'] );
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
	 * ajax修改状态
	 */
	public function actionAjaxAppStatus() {
		$DRAW_MAP_ID_APP = $_POST ["DRAW_MAP_ID_APP"];
		$DRAW_MAP_ID = $_POST ["DRAW_MAP_ID"];
		$json = array ();
		if (! empty ( $DRAW_MAP_ID )) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			try {
				$result = $Cdao_DrawMap->appStatus ( $DRAW_MAP_ID_APP, $DRAW_MAP_ID );
				if ($result === false) {
					$json ["error"] = iconv ( 'GBK', 'UTF-8', "操作失败！" );
				}
			} catch ( Exception $e ) {
				$json ["error"] = $e->getMessage ();
			}
		}
		echo (json_encode ( $json ));
	}
}
?>