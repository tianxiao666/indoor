<?php
/**
 * 获取附近场所接口
 */
class GetNearbyBuildingListController extends ApiController {
	public function actionIndex() {
		$jsonArrayObj = $_GET ["jsonArrayObj"];
		//测试数据数组
// 		$jsonArrayObj = json_encode ( array (
// 				"LONGITUDE" => 113.5863127,
// 				"LATITUDE" => 23.0089633,
// 				"RANGE" => 1000
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
				$BUILDING_TYPE = $jsonArrayObj->BUILDING_TYPE;
				$LONGITUDE = $jsonArrayObj->LONGITUDE;
				$LATITUDE = $jsonArrayObj->LATITUDE;
				if ($LONGITUDE != "" && $LATITUDE != "") {//判断是否有当前坐标
					$RANGE = $jsonArrayObj->RANGE;
					//坐标值处理，让它与数据库相匹配
					$LONGITUDE = SvgUtil::getEncodeLatLng ( $LONGITUDE );
					$LATITUDE = SvgUtil::getEncodeLatLng ( $LATITUDE );
					//组合查询条件
					$where = "1=1";
					if ($BUILDING_TYPE) {
						$where = $where . " AND BUILD_TYPE=" . $BUILDING_TYPE;
					}
					if (! empty ( $RANGE )) {//有范围值时的查询条件
						$RANGE = SvgUtil::getEncodeLatLng ( ($RANGE / 111.2) );
						$where = $where . " AND (((LT_LONGITUDEL between " . ($LONGITUDE - $RANGE) . " and " . ($LONGITUDE + $RANGE) . ") and (LT_LATITUDEL between " . ($LATITUDE - $RANGE) . " AND " . ($LATITUDE + $RANGE) . ")) or ((RB_LONGITUDEL between " . ($LONGITUDE - $RANGE) . " and " . ($LONGITUDE + $RANGE) . ") and (RB_LATITUDEL between " . ($LATITUDE - $RANGE) . " AND " . ($LATITUDE + $RANGE) . ")))";
					}
					$Cdao_Building = new CDAOCB_BUILDING ();
					//附近场所的数据
					$BuildingList = $Cdao_Building->getInfoByWhere ( $where );
					//把数据库的坐标值转成真实坐标值
					$LONGITUDE = SvgUtil::getDecodeLatLng ( $LONGITUDE );
					$LATITUDE = SvgUtil::getDecodeLatLng ( $LATITUDE );
					if ($BuildingList) {
						$picWhere = "";
						//场所图片信息的查询条件
						foreach ( $BuildingList as $k => $v ) {
							if ($picWhere == "") {
								$picWhere = $picWhere . " (BUILDING_ID=" . $v ["BUILDING_ID"];
							} else {
								$picWhere = $picWhere . " or BUILDING_ID=" . $v ["BUILDING_ID"];
							}
						}
						$picWhere = $picWhere . ") and FLOOR_ID is null";
						//查询场所图片信息
						$BUILDING_ICON_LIST = $this->getPic ( $picWhere );
						foreach ( $BuildingList as $k => $v ) {
							//返回数据的生成
							$BuildingList [$k] ['LT_LONGITUDEL'] = SvgUtil::getDecodeLatLng ( $BuildingList [$k] ['LT_LONGITUDEL'] );
							$BuildingList [$k] ['LT_LATITUDEL'] = SvgUtil::getDecodeLatLng ( $BuildingList [$k] ['LT_LATITUDEL'] );
							$BuildingList [$k] ['RB_LONGITUDEL'] = SvgUtil::getDecodeLatLng ( $BuildingList [$k] ['RB_LONGITUDEL'] );
							$BuildingList [$k] ['RB_LATITUDEL'] = SvgUtil::getDecodeLatLng ( $BuildingList [$k] ['RB_LATITUDEL'] );
							$buildingLONGITUDEL = ($BuildingList [$k] ['LT_LONGITUDEL'] + $BuildingList [$k] ['RB_LONGITUDEL']) / 2;
							$buildingLATITUDEL = ($BuildingList [$k] ['LT_LATITUDEL'] + $BuildingList [$k] ['RB_LATITUDEL']) / 2;
							$BuildingList [$k] ['DISTANCE'] = SvgUtil::getLatLngDistance ( $LATITUDE, $LONGITUDE, $buildingLATITUDEL, $buildingLONGITUDEL );
							$BuildingList [$k] ['DISTANCE'] = $BuildingList [$k] ['DISTANCE'] / 1000;
							if ($BUILDING_ICON_LIST) {
								//场所与图片信息相匹配
								foreach ( $BUILDING_ICON_LIST as $PicK => $PicV ) {
									if ($BUILDING_ICON_LIST [$PicK] ['BUILDING_ID'] == $BuildingList [$k] ['BUILDING_ID']) {
										$filePath = MEDIALIB_PATH . $BUILDING_ICON_LIST [$PicK] ['PATH'] . "/" . $BUILDING_ICON_LIST [$PicK] ['FILENAME'] . "." . $BUILDING_ICON_LIST [$PicK] ['PIC_TYPE'];
										if (file_exists ( $filePath )) {
											$BuildingList [$k] ['BUILDING_ICON'] = SF_BASE_URL . 'medialib/' . $BUILDING_ICON_LIST [$PicK] ['PATH'] . "/" . $BUILDING_ICON_LIST [$PicK] ['FILENAME'] . "." . $BUILDING_ICON_LIST [$PicK] ['PIC_TYPE'];
										}
									}
								}
							}
						}
						//按DISTANCE从小到大排序
						$BuildingList = $this->smallToBig ( $BuildingList, "DISTANCE" );
						//生成返回数据
						$content = $this->arrayToJson ( array (
								"TOTALCOUNT" => count ( $BuildingList ),
								"NearbyBuildingList" => $this->arrayToJson ( $BuildingList ) 
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
		} else {
			$message = "参数为空！";
		}
		$this->renderErrorJson ( $message );
	}
	/**
	 * 小到大排序
	 *
	 * @param unknown $data        	
	 * @param unknown $key        	
	 * @return Ambigous <string, unknown>
	 */
	public function smallToBig($data, $key) {
		$value = "";
		if ($data) {
			for($i = 0; $i < count ( $data ); $i ++) {
				for($j = $i + 1; $j < count ( $data ); $j ++) {
					if ($data [$i] [$key] > $data [$j] [$key]) {
						$value = $data [$i];
						$data [$i] = $data [$j];
						$data [$j] = $value;
					}
				}
			}
		}
		return $data;
	}
	/**
	 * 取图片信息
	 *
	 * @param unknown $where        	
	 * @return unknown
	 */
	public function getPic($where) {
		$Cdao_pic = new CDAOCB_PIC ();
		$picinfo = $Cdao_pic->getAllWhere ( "*", $where );
		return $picinfo;
	}
}
?>