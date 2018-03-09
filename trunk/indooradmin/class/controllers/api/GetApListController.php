<?php
/**
 * ��ȡap��Ϣ�б�
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
				$message = "������������";
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
						$message = "û�в��ҵ����ϵ�ap��";
					}
				} else {
					$message = "����ap��������";
				}
			}
		} else {
			$message = "����Ϊ�գ�";
		}
		$this->renderErrorJson ( $message );
	}
}
?>