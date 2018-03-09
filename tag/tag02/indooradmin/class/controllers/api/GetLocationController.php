<?php
/**
 * 定位接口
 *
 */
class GetLocationController extends ApiController {
	function actionIndex() {
		$message = "";
		$jsonArrayObj = $_GET ["jsonArrayObj"];
		//测试数据数组
// 		$jsonArrayObj = "{\"LONGITUDE\":\"113.353127\",\"LATITUDE\":\"23.149633\",\"APLIST\":\"[{\\\"LEVEL\\\":\\\"-62\\\",\\\"CHANNEL\\\":\\\"8\\\",\\\"MAC_BSSID\\\":\\\"05:04:03:01\\\",\\\"FREQUENCY\\\":\\\"1\\\",\\\"EQUT_SSID\\\":\\\"4444\\\"},{\\\"LEVEL\\\":\\\"-65\\\",\\\"CHANNEL\\\":\\\"11\\\",\\\"MAC_BSSID\\\":\\\"38:22:d6:ad:95:b0\\\",\\\"FREQUENCY\\\":\\\"2462\\\",\\\"EQUT_SSID\\\":\\\"CMCC\\\"},{\\\"LEVEL\\\":\\\"-67\\\",\\\"CHANNEL\\\":\\\"11\\\",\\\"MAC_BSSID\\\":\\\"38:22:d6:ad:95:b2\\\",\\\"FREQUENCY\\\":\\\"2462\\\",\\\"EQUT_SSID\\\":\\\"CMCC-AUTO\\\"},{\\\"LEVEL\\\":\\\"-73\\\",\\\"CHANNEL\\\":\\\"1\\\",\\\"MAC_BSSID\\\":\\\"00:23:89:e3:3a:31\\\",\\\"FREQUENCY\\\":\\\"2412\\\",\\\"EQUT_SSID\\\":\\\"Iswlan2\\\"},{\\\"LEVEL\\\":\\\"-92\\\",\\\"CHANNEL\\\":\\\"6\\\",\\\"MAC_BSSID\\\":\\\"00:26:5a:b0:e1:74\\\",\\\"FREQUENCY\\\":\\\"2437\\\",\\\"EQUT_SSID\\\":\\\"2707\\\"}]\",\"MoveSteps\":\"1\",\"MoveStepsMillis\":\"5189\",\"Derection\":\"67.625\",\"BUILDING_ID\":\"\",\"FLOOR_ID\":\"\"}";
// 		$jsonArrayObj = json_encode ( array (
// 				"BUILDING_ID" => 143,
// 				"DRAW_MAP_ID"=> 1020,
// 				"FLOOR_ID" => 76,
// 				"LONGITUDE" => 113.35292694444,
// 				"LATITUDE" => 23.150033055556,
// 				"APLIST" => json_encode ( array (
// 						array (
// 								"EQUT_SSID" => 544,
// 								"LEVEL" => 20 
// 						),
// 						array (
// 								"EQUT_SSID" => 4,
// 								"LEVEL" => 10 
// 						),
// 						array (
// 								"EQUT_SSID" => 4444,
// 								"MAC_BSSID" => "05:04:03:01",
// 								"FREQUENCY" => 1,
// 								"CHANNEL" => 8,
// 								"LEVEL" => 30 
// 						) 
// 				) ) 
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
				if (! empty ( $jsonArrayObj->APLIST )) {
					try {
						$jsonArrayObj->APLIST = json_decode ( $jsonArrayObj->APLIST );
					} catch ( Exception $e ) {
						$message = "解析AP参数列表出错！";
						if (! empty ( $e )) {
							$message = $message . "catch error:" . $e->getMessage ();
						}
					}
				}
				if (empty ( $message )) {
					if ($jsonArrayObj->LONGITUDE != "" && $jsonArrayObj->LATITUDE != "") {//判断是否有当前坐标
						//坐标值处理，让它与数据库相匹配
						$jsonArrayObj->LONGITUDE = SvgUtil::getEncodeLatLng ( $jsonArrayObj->LONGITUDE );
						$jsonArrayObj->LATITUDE = SvgUtil::getEncodeLatLng ( $jsonArrayObj->LATITUDE );
						$Cdao_Building = new CDAOCB_BUILDING ();
						//生成场所查询条件
						if ($jsonArrayObj->BUILDING_ID) {
							$buildingsql = "BUILDING_ID=" . $jsonArrayObj->BUILDING_ID;//有场所ID传入的时候
						} else {
							$buildingsql = "LT_LONGITUDEL < " . $jsonArrayObj->LONGITUDE . " AND RB_LONGITUDEL > " . $jsonArrayObj->LONGITUDE . " AND LT_LATITUDEL > " . $jsonArrayObj->LATITUDE . " AND RB_LATITUDEL < " . $jsonArrayObj->LATITUDE;
						}
						//定位的场所数据
						$building_list = $Cdao_Building->getAll ( $buildingsql );
						if (! empty ( $building_list )) {
							$Cdao_Building = new CDAOCB_BUILDING ();
							//通过场所ID获取场所名称
							$BUILDING_NAME = $Cdao_Building->getSeqNameBySeqId ( $building_list [0] ['BUILDING_ID'] );
							$LOCATION = $BUILDING_NAME;
							$maxAp = null;
							if ($jsonArrayObj->APLIST) {
								//获取AP信号最强的AP信息
								foreach ( $jsonArrayObj->APLIST as $k=>$v ) {
									if ($maxAp === null) {
										$maxAp = $v;
									} else {
										if ($maxAp->LEVEL < $v->LEVEL) {
											$maxAp = $v;
										}
									}
								}
							}
							//生成AP查询条件
							$apSql = "EQUT_SSID='" . $maxAp->EQUT_SSID . "' and MAC_BSSID='" . $maxAp->MAC_BSSID . "' and BUILDING_ID='" . $building_list [0] ['BUILDING_ID'] . "'";
							if ($maxAp->FREQUENCY) {
								$apSql = $apSql . " and FREQUENCY=" . $maxAp->FREQUENCY;
							}
							if ($maxAp->CHANNEL) {
								$apSql = $apSql . " and CHANNEL=" . $maxAp->CHANNEL;
							}
							if ($jsonArrayObj->FLOOR_ID) {
								$apSql = $apSql . " and FLOOR_ID=" . $jsonArrayObj->FLOOR_ID;
							}
							$cdaocb_location = new CDAOCB_LOCATION ();
							$ap_list = $cdaocb_location->getAllWhere ( "*", $apSql );//匹配的AP信息
							if (! empty ( $ap_list )) {
								$Cdao_Floor = new CDAOCB_FLOOR ();
								// 通过楼层ID获取楼层名称
								$FLOOR_NAME = $Cdao_Floor->getSeqNameBySeqId ( $ap_list [0] ['FLOOR_ID'] );
								$floorid = $ap_list [0] ['FLOOR_ID'];
								$LOCATION = $LOCATION . "_" . $FLOOR_NAME;
								//获取平面图信息
								$floorSvgInfo=$this->getFloorSvg($jsonArrayObj->DRAW_MAP_ID,$building_list [0] ['BUILDING_ID'],$ap_list [0] ['FLOOR_ID']);
							} else {//匹配AP不成功
								$Cdao_Floor = new CDAOCB_FLOOR ();
								if ($jsonArrayObj->FLOOR_ID) {//传入数据有楼层ID
									$floorlist = $Cdao_Floor->getFloorData ( $jsonArrayObj->FLOOR_ID );//楼层信息
								}
								if ($floorlist == '') {//传入信息没有楼层ID
									$floorlist = $Cdao_Floor->getFloor_id ( $building_list [0] ['BUILDING_ID'] );//去场所数据库的第一个楼层信息
								}
								//获取平面图信息
								$floorSvgInfo=$this->getFloorSvg($jsonArrayObj->DRAW_MAP_ID,$building_list [0] ['BUILDING_ID'],$floorlist [0] ['FLOOR_ID']);
								if ($floorlist [0] ['FLOOR_ID']) {
									$FLOOR_NAME = $Cdao_Floor->getSeqNameBySeqId ( $floorlist [0] ['FLOOR_ID'] ); // 获取楼层名称
									$LOCATION = $LOCATION . "_" . $FLOOR_NAME;
								}
								$floorid = $floorlist [0] ['FLOOR_ID'];
							}
							if($floorSvgInfo['message']){//获取楼层平面图错误信息
								$message = $floorSvgInfo['message'];
							}
							//生成返回数据
							$content = $this->arrayToJson ( array (
									"X" => $ap_list [0] ['POSITION_X'],
									"Y" => $ap_list [0] ['POSITION_Y'],
									"LOCATION" => $LOCATION,
									"BUILDING_ID" => $building_list [0] ['BUILDING_ID'],
									"BUILDING_NAME" => $building_list [0] ['BUILDING_NAME'],
									"FLOOR_ID" => $floorid,
									"DRAW_MAP_ID" => $floorSvgInfo['DRAW_MAP_ID'],
									"LayerList" => $this->arrayToJson ( $floorSvgInfo['LayerList'] ) ,
									"SVGSRC" => $floorSvgInfo['SVGSRC'] ,
							) );
						} else {
							$content = "{}";
						}
						$this->renderSuccessJson ( $content );
						return;
					} else {
						$message = "获取当前位置失败！";
					}
				}
			}
		} else {
			$message = $message . "参数为空！";
		}
		$this->renderErrorJson ( $message );
	}
	/**
	 * 获取楼层平面图数据
	 * @param unknown $building_id
	 * @param unknown $floor_id
	 */
	private function getFloorSvg($DRAW_MAP_ID="",$building_id,$floor_id){
		if (! empty ($DRAW_MAP_ID)) {
			$result['DRAW_MAP_ID'] = $DRAW_MAP_ID;
			$result['SVGSRC'] = "";
			$result['LayerList'] = array();
		} else {
			//通过场所ID和楼层ID获取楼层平面图信息
			$response = SvgUtil::getSvg ( $building_id, $floor_id );
			$result['DRAW_MAP_ID'] = $response ["DRAW_MAP_ID"];
			if($response ["SVGSRC"]==""){
				$result['LayerList'] = array();
			}else{
				$result['LayerList'] = $response ["LayerList"];
			}
		}
		if (empty ( $response ["error"] )) {
		} else {
			$result['message'] = $response ["error"];
		}
		return $result;
	}
}
?>