<?php
/**
 * 获取poi信息列表
 * @author xiang.zc
 */
class GetPoiListController extends ApiController {
	function actionIndex() {
		$message = "";
		$jsonArrayObj = $_GET ["jsonArrayObj"];
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
				$sql = "";
				if (! empty ( $jsonArrayObj->BUILDING_ID )) {
					$pair = "BUILDING_ID=" . $jsonArrayObj->BUILDING_ID;
					if (empty ( $sql )) {
						$sql = $pair;
					} else {
						$sql = $sql . " AND " . $pair;
					}
				}
				if (! empty ( $jsonArrayObj->FLOOR_ID )) {
					$pair = "FLOOR_ID=" . $jsonArrayObj->FLOOR_ID;
					if (empty ( $sql )) {
						$sql = $pair;
					} else {
						$sql = $sql . " AND " . $pair;
					}
				}
				if (! empty ( $jsonArrayObj->DRAW_MAP_ID )) {
					$pair = "DRAW_MAP_ID=" . $jsonArrayObj->DRAW_MAP_ID;
					if (empty ( $sql )) {
						$sql = $pair;
					} else {
						$sql = $sql . " AND " . $pair;
					}
				}
				if (! empty ( $jsonArrayObj->POI_TYPE )) {
					$pair = "POI_TYPE='{$jsonArrayObj->POI_TYPE}'";
					if (empty ( $sql )) {
						$sql = $pair;
					} else {
						$sql = $sql . " AND " . $pair;
					}
				}
				if (! empty ( $jsonArrayObj->POI_ID )) {
					$pair = "POI_ID=" . $jsonArrayObj->POI_ID;
					if (empty ( $sql )) {
						$sql = $pair;
					} else {
						$sql = $sql . " AND " . $pair;
					}
				}
				if (! empty ( $jsonArrayObj->SVG_ID )) {
					$pair = "SVG_ID=" . $jsonArrayObj->SVG_ID;
					if (empty ( $sql )) {
						$sql = $pair;
					} else {
						$sql = $sql . " AND " . $pair;
					}
				}
				if (! empty ( $sql )) {
					$cdaocb_poi = new CDAOCB_POI ();
					$result = $cdaocb_poi->getAllWhere ( "*", $sql . " AND STATUS<>'X'" );
					if (! empty ( $result )) {
						foreach ( $result as $k => $v ) {
							if (! empty ( $v ["ANT_FREQUENCY"] )) {
								$v ["FREQUENCY"] = $v ["ANT_FREQUENCY"];
							}
							if (! empty ( $v ["ANT_POWER"] )) {
								$v ["POWER"] = $v ["ANT_POWER"];
							}
							$result [$k] = $v;
						}
						$this->renderSuccessJson ( $this->arrayToJson ( $result ) );
					} else {
						$message = "没有查找到符合的poi！";
					}
				} else {
					$message = "查找poi条件错误！";
				}
			}
		} else {
			$message = "参数为空！";
		}
		$this->renderErrorJson ( $message );
	}
}
?>