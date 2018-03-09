<?php
/**
 * 获取楼层列表
*
*/
class GetBuildingFloorListController extends ApiController {
	function actionIndex() {
		$message = "";
		$jsonArrayObj = $_GET ["jsonArrayObj"];
		//测试数据数组
// 		$jsonArrayObj = json_encode ( array (
// 				"BUILDING_ID" => 1 
// 		) );
		if (! empty ( $jsonArrayObj )) {
			try {
				$jsonArrayObj = json_decode ( $jsonArrayObj );
			} catch ( Exception $e ) {
				$message = "解析参数出错！";
				if (! empty ( $e )) {
					$message = $message . "catch error:" . $e->getMessage ();
				}
			}
			if (empty ( $message )) {
				//判断是否有场所ID传入
				if ($jsonArrayObj->BUILDING_ID) {
					$cdaocb_floor = new CDAOCB_FLOOR ();
					//查询该场所ID的所有楼层的信息
					$BuildingFloorList = $cdaocb_floor->getFloor_id ( $jsonArrayObj->BUILDING_ID );
					if (! empty ( $BuildingFloorList )) {
						$cdaocb_draw_map = new CDAODM_DRAW_MAP ();
						//生成取当前场所楼层的平面图ID的条件
						$where = "";
						foreach ( $BuildingFloorList as $k => $v ) {
							if ($where == "") {
								$where = "(FLOOR_ID=" . $v ["FLOOR_ID"];
							}
							$where = $where . " or FLOOR_ID=" . $v ["FLOOR_ID"];
						}
						$where = $where . ") and STATUS='A'";
						//取当前场所楼层的平面图ID
						$DRAW_MAP_ID_LIST = $cdaocb_draw_map->getByWhere ( "FLOOR_ID,DRAW_MAP_ID", $where );
						//匹配楼层和其对应的平面图ID
						if ($DRAW_MAP_ID_LIST) {
							foreach ( $BuildingFloorList as $floorK => $floorV ) {
								foreach ( $DRAW_MAP_ID_LIST as $draw_mapK => $draw_mapV ) {
									if ($floorV ["FLOOR_ID"] == $draw_mapV ["FLOOR_ID"]) {
										$BuildingFloorList [$floorK] ["DRAW_MAP_ID"] = $draw_mapV ["DRAW_MAP_ID"];
									}
								}
							}
						}
						//生成返回数据
						$content = $this->arrayToJson ( array (
								"BuildingFloorList" => $this->arrayToJson ( $BuildingFloorList ) 
						) );
					} else {
						$content = "{}";
					}
					$this->renderSuccessJson ( $content );
					return;
				} else {
					$message = "信息错误！";
				}
			}
		} else {
			$message = "参数为空！";
		}
		$this->renderErrorJson ( $message );
	}
}
?>