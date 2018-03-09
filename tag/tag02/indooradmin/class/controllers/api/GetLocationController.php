<?php
/**
 * ��λ�ӿ�
 *
 */
class GetLocationController extends ApiController {
	function actionIndex() {
		$message = "";
		$jsonArrayObj = $_GET ["jsonArrayObj"];
		//������������
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
				$message = "������������";
				if (! empty ( $e )) {
					$message = $message . "catch error:" . $e->getMessage ();
				}
			}
			if (empty ( $message )) {
				if (! empty ( $jsonArrayObj->APLIST )) {
					try {
						$jsonArrayObj->APLIST = json_decode ( $jsonArrayObj->APLIST );
					} catch ( Exception $e ) {
						$message = "����AP�����б����";
						if (! empty ( $e )) {
							$message = $message . "catch error:" . $e->getMessage ();
						}
					}
				}
				if (empty ( $message )) {
					if ($jsonArrayObj->LONGITUDE != "" && $jsonArrayObj->LATITUDE != "") {//�ж��Ƿ��е�ǰ����
						//����ֵ�������������ݿ���ƥ��
						$jsonArrayObj->LONGITUDE = SvgUtil::getEncodeLatLng ( $jsonArrayObj->LONGITUDE );
						$jsonArrayObj->LATITUDE = SvgUtil::getEncodeLatLng ( $jsonArrayObj->LATITUDE );
						$Cdao_Building = new CDAOCB_BUILDING ();
						//���ɳ�����ѯ����
						if ($jsonArrayObj->BUILDING_ID) {
							$buildingsql = "BUILDING_ID=" . $jsonArrayObj->BUILDING_ID;//�г���ID�����ʱ��
						} else {
							$buildingsql = "LT_LONGITUDEL < " . $jsonArrayObj->LONGITUDE . " AND RB_LONGITUDEL > " . $jsonArrayObj->LONGITUDE . " AND LT_LATITUDEL > " . $jsonArrayObj->LATITUDE . " AND RB_LATITUDEL < " . $jsonArrayObj->LATITUDE;
						}
						//��λ�ĳ�������
						$building_list = $Cdao_Building->getAll ( $buildingsql );
						if (! empty ( $building_list )) {
							$Cdao_Building = new CDAOCB_BUILDING ();
							//ͨ������ID��ȡ��������
							$BUILDING_NAME = $Cdao_Building->getSeqNameBySeqId ( $building_list [0] ['BUILDING_ID'] );
							$LOCATION = $BUILDING_NAME;
							$maxAp = null;
							if ($jsonArrayObj->APLIST) {
								//��ȡAP�ź���ǿ��AP��Ϣ
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
							//����AP��ѯ����
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
							$ap_list = $cdaocb_location->getAllWhere ( "*", $apSql );//ƥ���AP��Ϣ
							if (! empty ( $ap_list )) {
								$Cdao_Floor = new CDAOCB_FLOOR ();
								// ͨ��¥��ID��ȡ¥������
								$FLOOR_NAME = $Cdao_Floor->getSeqNameBySeqId ( $ap_list [0] ['FLOOR_ID'] );
								$floorid = $ap_list [0] ['FLOOR_ID'];
								$LOCATION = $LOCATION . "_" . $FLOOR_NAME;
								//��ȡƽ��ͼ��Ϣ
								$floorSvgInfo=$this->getFloorSvg($jsonArrayObj->DRAW_MAP_ID,$building_list [0] ['BUILDING_ID'],$ap_list [0] ['FLOOR_ID']);
							} else {//ƥ��AP���ɹ�
								$Cdao_Floor = new CDAOCB_FLOOR ();
								if ($jsonArrayObj->FLOOR_ID) {//����������¥��ID
									$floorlist = $Cdao_Floor->getFloorData ( $jsonArrayObj->FLOOR_ID );//¥����Ϣ
								}
								if ($floorlist == '') {//������Ϣû��¥��ID
									$floorlist = $Cdao_Floor->getFloor_id ( $building_list [0] ['BUILDING_ID'] );//ȥ�������ݿ�ĵ�һ��¥����Ϣ
								}
								//��ȡƽ��ͼ��Ϣ
								$floorSvgInfo=$this->getFloorSvg($jsonArrayObj->DRAW_MAP_ID,$building_list [0] ['BUILDING_ID'],$floorlist [0] ['FLOOR_ID']);
								if ($floorlist [0] ['FLOOR_ID']) {
									$FLOOR_NAME = $Cdao_Floor->getSeqNameBySeqId ( $floorlist [0] ['FLOOR_ID'] ); // ��ȡ¥������
									$LOCATION = $LOCATION . "_" . $FLOOR_NAME;
								}
								$floorid = $floorlist [0] ['FLOOR_ID'];
							}
							if($floorSvgInfo['message']){//��ȡ¥��ƽ��ͼ������Ϣ
								$message = $floorSvgInfo['message'];
							}
							//���ɷ�������
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
						$message = "��ȡ��ǰλ��ʧ�ܣ�";
					}
				}
			}
		} else {
			$message = $message . "����Ϊ�գ�";
		}
		$this->renderErrorJson ( $message );
	}
	/**
	 * ��ȡ¥��ƽ��ͼ����
	 * @param unknown $building_id
	 * @param unknown $floor_id
	 */
	private function getFloorSvg($DRAW_MAP_ID="",$building_id,$floor_id){
		if (! empty ($DRAW_MAP_ID)) {
			$result['DRAW_MAP_ID'] = $DRAW_MAP_ID;
			$result['SVGSRC'] = "";
			$result['LayerList'] = array();
		} else {
			//ͨ������ID��¥��ID��ȡ¥��ƽ��ͼ��Ϣ
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