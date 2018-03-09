<?php
/**
 * 获取ap信息列表
 * @author xiang.zc
 */
class GetApListController extends ApiController {
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
				if (! empty ( $jsonArrayObj->AP_ID )) {
					$pair = "AP_ID=" . $jsonArrayObj->AP_ID;
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
					$Cdao_Ap = new CDAOCB_LOCATION ();
					$result = $Cdao_Ap->getAllWhere ( "*", $sql . " AND STATUS<>'X'" );
					if (! empty ( $result )) {
						$this->renderSuccessJson ( $this->arrayToJson ( $result ) );
					} else {
						$message = "没有查找到符合的ap！";
					}
				} else {
					$message = "查找ap条件错误！";
				}
			}
		} else {
			$message = "参数为空！";
		}
		$this->renderErrorJson ( $message );
	}
}
?>