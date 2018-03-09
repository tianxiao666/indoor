
<?php
/**
 * 获取室内地图
 * 
 */
class GetBuildingFloorMapController extends ApiController {
	function actionIndex() {
		$message = "";
		$jsonArrayObj = $_GET ["jsonArrayObj"];
		//测试数据数组
// 		$jsonArrayObj = json_encode ( array (
// 				"BUILDING_ID" => 1,
// 				"FLOOR_ID" => 11111 
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
				//根据场所ID和楼层ID来获取楼层平面图信息
				$response = SvgUtil::getSvg ( $jsonArrayObj->BUILDING_ID, $jsonArrayObj->FLOOR_ID );
				if (empty ( $response ["error"] )) {
					//返回数据
					$content = $this->arrayToJson ( array (
							"FLOOR_ID" => $response ["FLOOR_ID"],
							"DRAW_MAP_ID" => $response ["DRAW_MAP_ID"],
							"LayerList" => json_encode ( $response ["LayerList"] ),
							"SVGSRC" => $response ["SVGSRC"] 
					) );
					$this->renderSuccessJson ( $content );
					return;
				} else {
					$message = $response ["error"];
				}
			}
		} else {
			$message = "参数为空！";
		}
		$this->renderErrorJson ( $message );
	}
}
?>