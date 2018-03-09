<?php
/**
 * 
 *
 */

class CPositionMgr {
	public function formatPostion(&$data) {
		$data ['LONGITUDE'] = $this->getTransformValue ( $data ['LONGITUDE'] );
		$data ['LATITUDE'] = $this->getTransformValue ( $data ['LATITUDE'] );
		$data ['EN_LONGITUDE'] = $this->getTransformValue ( $data ['EN_LONGITUDE'] );
		$data ['EN_LATITUDE'] = $this->getTransformValue ( $data ['EN_LATITUDE'] );
		$data ['WS_LONGITUDE'] = $this->getTransformValue ( $data ['WS_LONGITUDE'] );
		$data ['WS_LATITUDE'] = $this->getTransformValue ( $data ['WS_LATITUDE'] );
		$data ['ENT_LONGITUDE'] = $this->getTransformValue ( $data ['ENT_LONGITUDE'] );
		$data ['ENT_LATITUDE'] = $this->getTransformValue ( $data ['ENT_LATITUDE'] );
		$data ['JP_ENT_LATITUDE'] = $this->getTransformValue ( $data ['JP_ENT_LATITUDE'] );
		$data ['JP_ENT_LONGITUDE'] = $this->getTransformValue ( $data ['JP_ENT_LONGITUDE'] );
		$data ['JP_LATITUDE'] = $this->getTransformValue ( $data ['JP_LATITUDE'] );
		$data ['JP_LONGITUDE'] = $this->getTransformValue ( $data ['JP_LONGITUDE'] );
		$data ['JP_C_LATITUDE'] = $this->getTransformValue ( $data ['JP_C_LATITUDE'] );
		$data ['JP_C_LONGITUDE'] = $this->getTransformValue ( $data ['JP_C_LONGITUDE'] );
		if ($data ['ALTITUDE'])
			$data ['ALTITUDE'] = floatval ( $data ['ALTITUDE'] );
	}
	
	/**
	 * 对设施点坐标作处理
	 *
	 * @param unknown_type $data
	 */
	public function formatFPonitPosition(&$data) {
		$data ['LONGITUDE'] = $this->getTransformValue ( $data ['LONGITUDE'] );
		$data ['LATITUDE'] = $this->getTransformValue ( $data ['LATITUDE'] );
		$data ['JP_LONGITUDE'] = $this->getTransformValue ( $data ['JP_LONGITUDE'] );
		$data ['JP_LATITUDE'] = $this->getTransformValue ( $data ['JP_LATITUDE'] );
	}
	
	public function formatFPonitViewPosition(&$data) {
		$data ['LONGITUDE'] = $this->getTransformViewValue ( $data ['LONGITUDE'] );
		$data ['LATITUDE'] = $this->getTransformViewValue ( $data ['LATITUDE'] );
	}
	
	public function formatSpotMapPostion(&$data) {
		$data ['P1_LONGITUDE'] = $this->getTransformValue ( $data ['P1_LONGITUDE'] );
		$data ['P1_LATITUDE'] = $this->getTransformValue ( $data ['P1_LATITUDE'] );
		$data ['P2_LONGITUDE'] = $this->getTransformValue ( $data ['P2_LONGITUDE'] );
		$data ['P2_LATITUDE'] = $this->getTransformValue ( $data ['P2_LATITUDE'] );
		$data ['P3_LONGITUDE'] = $this->getTransformValue ( $data ['P3_LONGITUDE'] );
		$data ['P3_LATITUDE'] = $this->getTransformValue ( $data ['P3_LATITUDE'] );
		$data ['P4_LONGITUDE'] = $this->getTransformValue ( $data ['P4_LONGITUDE'] );
		$data ['P4_LATITUDE'] = $this->getTransformValue ( $data ['P4_LATITUDE'] );
	}
	
	public function formatSpotMapViewPostion(&$data) {
		$data ['P1_LONGITUDE'] = $this->getTransformViewValue ( $data ['P1_LONGITUDE'] );
		$data ['P1_LATITUDE'] = $this->getTransformViewValue ( $data ['P1_LATITUDE'] );
		$data ['P2_LONGITUDE'] = $this->getTransformViewValue ( $data ['P2_LONGITUDE'] );
		$data ['P2_LATITUDE'] = $this->getTransformViewValue ( $data ['P2_LATITUDE'] );
		$data ['P3_LONGITUDE'] = $this->getTransformViewValue ( $data ['P3_LONGITUDE'] );
		$data ['P3_LATITUDE'] = $this->getTransformViewValue ( $data ['P3_LATITUDE'] );
		$data ['P4_LONGITUDE'] = $this->getTransformViewValue ( $data ['P4_LONGITUDE'] );
		$data ['P4_LATITUDE'] = $this->getTransformViewValue ( $data ['P4_LATITUDE'] );
	}
	
	/**
	 * 对景区范围坐标作处理
	 *
	 * @param unknown_type $data
	 */
	public function formatRangePosition(&$data) {
		$data ['E_LONGITUDE'] = $this->getTransformValue ( $data ['E_LONGITUDE'] );
		$data ['N_LATITUDE'] = $this->getTransformValue ( $data ['N_LATITUDE'] );
		$data ['W_LONGITUDE'] = $this->getTransformValue ( $data ['W_LONGITUDE'] );
		$data ['S_LATITUDE'] = $this->getTransformValue ( $data ['S_LATITUDE'] );
	}
	
	public function formatRangeViewPosition(&$data) {
		$data ['E_LONGITUDE'] = $this->getTransformViewValue ( $data ['E_LONGITUDE'] );
		$data ['N_LATITUDE'] = $this->getTransformViewValue ( $data ['N_LATITUDE'] );
		$data ['W_LONGITUDE'] = $this->getTransformViewValue ( $data ['W_LONGITUDE'] );
		$data ['S_LATITUDE'] = $this->getTransformViewValue ( $data ['S_LATITUDE'] );
	}
	
	//转换经纬度  （经纬度  转 毫°）
	public function formatAreaMapPostion(&$data) {
		$data ['C_LONGITUDE'] = $this->getTransformValue ( $data ['C_LONGITUDE'] );
		$data ['C_LATITUDE'] = $this->getTransformValue ( $data ['C_LATITUDE'] );
		$data ['E_LONGITUDE'] = $this->getTransformValue ( $data ['E_LONGITUDE'] );
		$data ['N_LATITUDE'] = $this->getTransformValue ( $data ['N_LATITUDE'] );
		$data ['W_LONGITUDE'] = $this->getTransformValue ( $data ['W_LONGITUDE'] );
		$data ['S_LATITUDE'] = $this->getTransformValue ( $data ['S_LATITUDE'] );
	}
	
	//转换经纬度（ 毫° 转  经纬度  ）
	public function formatAreaMapViewPostion(&$data) {
		$data ['C_LONGITUDE'] = $this->getTransformViewValue ( $data ['C_LONGITUDE'] );
		$data ['C_LATITUDE'] = $this->getTransformViewValue ( $data ['C_LATITUDE'] );
		$data ['E_LONGITUDE'] = $this->getTransformViewValue ( $data ['E_LONGITUDE'] );
		$data ['N_LATITUDE'] = $this->getTransformViewValue ( $data ['N_LATITUDE'] );
		$data ['W_LONGITUDE'] = $this->getTransformViewValue ( $data ['W_LONGITUDE'] );
		$data ['S_LATITUDE'] = $this->getTransformViewValue ( $data ['S_LATITUDE'] );
	}
	
	//  MSE 默认转换为毫秒 ， LAL 经纬度 
	public function getTransformValue($number, $type = "MSE") {
		if(!$number)return false;
		if ($type == "MSE") {
			$number = floatval ( $number );
			
			/* 只保留小数点后5位*/
			$position_before = strpos ( $number, '.' );
			if ($position_before !== false) {
				$number = substr ( $number, 0, $position_before + 6 );
			}
			
			$tmp = $number * 3600 * 1000;
			$position = strpos ( $tmp, '.' );
			if ($position !== false) {
				$tmp = substr ( $tmp, 0, $position );
			}
			$val = substr ( $tmp, 0, 10 );
		} else if ($type == "LAL") {
			$val = floatval ( $number / 3600000 );
		
		}
		return $val;
	}
	
	public function formatViewPostion(&$data) {
		$data ['EN_LONGITUDE'] = $this->getTransformViewValue ( $data ['EN_LONGITUDE'] );
		$data ['EN_LATITUDE'] = $this->getTransformViewValue ( $data ['EN_LATITUDE'] );
		$data ['WS_LONGITUDE'] = $this->getTransformViewValue ( $data ['WS_LONGITUDE'] );
		$data ['WS_LATITUDE'] = $this->getTransformViewValue ( $data ['WS_LATITUDE'] );
		$data ['ENT_LONGITUDE'] = $this->getTransformViewValue ( $data ['ENT_LONGITUDE'] );
		$data ['ENT_LATITUDE'] = $this->getTransformViewValue ( $data ['ENT_LATITUDE'] );
		$data ['ALTITUDE'] = floatval ( $data ['ALTITUDE'] ) == 0 ? '' : floatval ( $data ['ALTITUDE'] );
	}
	
	public function getTransformViewValue($number) {
		if(!$number)return false;
		$number = intval ( $number );
		if ($number == 0) {
			return 0;
		} else {
			return floatval ( ($number / 3600) / 1000 );
		}
	}
	
	/**
	 * 对POI坐标作处理
	 *
	 * @param unknown_type $data
	 */
	public function formatPoiPostion(&$data) {
		$data ['LONGITUDE'] = $this->getTransformValue ( $data ['LONGITUDE'] );
		$data ['LATITUDE'] = $this->getTransformValue ( $data ['LATITUDE'] );
	}
	
	public function formatPoiViewPostion(&$data) {
		$data ['LONGITUDE'] = $this->getTransformViewValue ( $data ['LONGITUDE'] );
		$data ['LATITUDE'] = $this->getTransformViewValue ( $data ['LATITUDE'] );
	}
	
	/**
	 * 对图片坐标作处理
	 *
	 * @param unknown_type $data
	 */
	public function formatPicPostion(&$data) {
		$data ['LONGITUDE'] = $this->getTransformValue ( $data ['LONGITUDE'] );
		$data ['LATITUDE'] = $this->getTransformValue ( $data ['LATITUDE'] );
	}
	
	public function formatPicViewPostion(&$data) {
		$data ['LONGITUDE'] = $this->getTransformViewValue ( $data ['LONGITUDE'] );
		$data ['LATITUDE'] = $this->getTransformViewValue ( $data ['LATITUDE'] );
	}
	
	//返回1毫秒距离
	public function getMsecByLngLat() {
		$LngLatM = $this->GetDistance ( 1 ); //米
		//		echo $LngLatM;
		return $LngLatM / 3600000;
	}
	
	/**
	 * 度1°=111.31949079327
	 * @lat1 $lat2 纬度
	 * @lng1 $lng2 经度
	 * @return $val @type m
	 */
	public function GetDistance($lng1 = 0, $lat1 = 0, $lng2 = 0, $lat2 = 0) {
		if ((abs ( $lat1 ) > 90) || (abs ( $lat2 ) > 90)) {
			return false;
		}
		if ((abs ( $lng1 ) > 180) || (abs ( $lng2 ) > 180)) {
			return false;
		}
		$radLat1 = $this->rad ( $lat1 );
		$radLat2 = $this->rad ( $lat2 );
		$a = $this->rad ( $lat1 ) - $this->rad ( $lat2 );
		$b = $this->rad ( $lng1 ) - $this->rad ( $lng2 );
		$val = 2 * asin ( sqrt ( pow ( sin ( $a / 2 ), 2 ) + cos ( $radLat1 ) * cos ( $radLat2 ) * pow ( sin ( $b / 2 ), 2 ) ) );
		$val = $val * 6378.137; // EARTH_RADIUS;
		//		$val = round($val * 10000) / 10000;
		return $val * 1000; //Unit:m
	}
	/**
	 * 度1°=111.31949079327
	 * @lat1 $lat2 纬度
	 * @lng1 $lng2 经度
	 * @return $val @type m
	 */
	public function GetSpotDistance($lng1 = 0, $lat1 = 0, $lng2 = 0, $lat2 = 0) {
		if ((abs ( $lat1 ) > 90) || (abs ( $lat2 ) > 90)) {
			return false;
		}
		if ((abs ( $lng1 ) > 180) || (abs ( $lng2 ) > 180)) {
			return false;
		}
		$radLat1 = $this->rad ( $lat1 );
		$radLat2 = $this->rad ( $lat2 );
		$a = $this->rad ( $lat1 ) - $this->rad ( $lat2 );
		$b = $this->rad ( $lng1 ) - $this->rad ( $lng2 );
		$val = 2 * asin ( sqrt ( pow ( sin ( $a / 2 ), 2 ) + cos ( $radLat1 ) * cos ( $radLat2 ) * pow ( sin ( $b / 2 ), 2 ) ) );
		$val = $val * 6378137.0; // EARTH_RADIUS;
		$val = round ( $val * 10000 ) / 10000;
		return $val;
	}
	//pi()返回圆周率
	public function rad($num) {
		return $num * pi () / 180.0;
	}
	
	//给定两个经纬度和一个小数（记录从第一个点开始的，占整个连线的比例），返回经纬度。
	/**
	 * Enter description here...
	 *
	 * @param 经度1 $lng1
	 * @param 纬度1 $lat1
	 * @param 经度2 $lng2
	 * @param 纬度2 $lat2
	 * @param float $ratio 在经纬度线上的比例。
	 * @return array 比例点的 经纬度
	 */
	public function getRatioLngLat($lng1, $lat1, $lng2, $lat2, $ratio) {
		
		if ($ratio < 0 || $ratio > 1)
			return false;
		$distance = $this->GetDistance ( $lng1, $lat1, $lng2, $lat2 ); //2点距离米
		$oppside = $this->GetDistance ( $lng2, $lat1, $lng2, $lat2 ); //对边距离
		$rad = asin ( $oppside / $distance );
		
		//距离转换为度数
		$LngLatM = $this->GetDistance ( 1 ); //1度的距离。米
		$adis = $distance * $ratio; //距离A.米..
		

		$lngside = sin ( $rad ) * $adis; //距离对边
		$latside = cos ( $rad ) * $adis; //斜边
		

		$lngside = $lngside / $LngLatM; //距离变回度数.
		$latside = $latside / $LngLatM;
		if ($lng1 >= $lng2 && $lat1 >= $lat2) {
			$newlng = $lng1 - $latside;
			$newlat = $lat1 - $lngside;
		} else if ($lng1 >= $lng2 && $lat1 <= $lat2) {
			$newlng = $lng1 - $latside;
			$newlat = $lat1 + $lngside;
		} else if ($lng1 <= $lng2 && $lat1 >= $lat2) {
			$newlng = $lng1 + $latside;
			$newlat = $lat1 - $lngside;
		} else if ($lng1 <= $lng2 && $lat1 <= $lat2) {
			$newlng = $lng1 + $latside;
			$newlat = $lat1 + $lngside;
		}
		
		return array ($newlng, $newlat );
	}
	
	/**
	 * 给定一组经纬度，返回能包括此经纬度的以正北为上的经纬度范围 ,就是返回无旋转的长方形4个经纬度
	 *
	 * @param Array $arrLngLat //array(array(123,41),array(12,6)....)
	 * @return unknown
	 */
	public function getRectangleArea($arrLngLat) {
		if (! is_array ( $arrLngLat ))
			return false;
		
		$arrLngLat = $this->getMaxMinArr ( $arrLngLat );
		
		$maxLng = $arrLngLat [0];
		$minLng = $arrLngLat [1];
		$maxLat = $arrLngLat [2];
		$minLat = $arrLngLat [3];
		return array (array ($maxLng, $maxLat ), array ($maxLng, $minLat ), array ($minLng, $maxLat ), array ($minLng, $minLat ) );
	}
	
	/**
	 * 根据地图旋转度返回出旋转后的长方形区域4个经纬度点...
	 *
	 * @param array $arrLngLat 经纬度数组 array(array($lng,$alt),array($lng,$alt).....)
	 * @param int $heading 偏转度
	 * @param boolean $is_4 是否返回的是 4个经纬度正方形
	 * @return array
	 */
	public function getHeadingRectArea($arrLngLat, $heading = 0, $is_4 = true) {
		
		if (! is_array ( $arrLngLat ))
			return false;
			//		$heading = intval($heading);
		if (abs ( $heading ) > 180)
			return false;
			//		$this->getRectangleArea($arrLngLat);
		//处理 偏转度。
		if ($heading < 0 && $heading > - 180) {
			$heading = 180 + $heading;
		}
		
		$LngLatArr = array ();
		if (($heading == 0 || $heading == 180 || $heading == 90) && $is_4) {
			$LngLatArr = $this->getRectangleArea ( $arrLngLat );
			return $LngLatArr;
		}
		
		$hd = $this->rad ( $heading );
		$hd1 = $this->rad ( 90 );
		$llnum = count ( $arrLngLat );
		$LngLatM = $this->GetDistance ( 1 ); //1度的距离。米
		if ($llnum) {
			for($i = 0; $i < $llnum; $i ++) {
				$lng = $arrLngLat [$i] [0] * $LngLatM; //经度转换为长度
				$lat = $arrLngLat [$i] [1] * $LngLatM; //纬度转换为长度
				if ($heading > 0 && $heading < 90) {
					$newlat = cos ( $hd ) * ($lat - tan ( $hd ) * $lng);
					$newlng = $lng / cos ( $hd ) + tan ( $hd ) * $newlat;
				} else if ($heading > 90 && $heading < 180) {
					//					$newlat = cos($hd) * ($lng - tan($hd) * $lat);
					//					$newlng = $lat/cos($hd) + tan($hd) * $newlng;
					$newlng = cos ( $hd - $hd1 ) * ($lng - tan ( $hd - $hd1 ) * - $lat);
					$newlat = - $lat / cos ( $hd - $hd1 ) + tan ( $hd - $hd1 ) * $newlng;
				}
				array_push ( $LngLatArr, array ($newlng / $LngLatM, $newlat / $LngLatM ) );
			}
		} else {
			$newLngLatArr = $arrLngLat;
		}
		
		$newLngLatArr = $this->getRectangleArea ( $LngLatArr );
		
		//		print_r($newLngLatArr);
		

		$newAreaOrdinate = array ();
		if (count ( $newLngLatArr ) > 0) {
			$newhd = $this->rad ( 360 - $heading );
			
			if (! $newLngLatArr)
				return false;
			foreach ( $newLngLatArr as $nlla ) {
				$lng1 = $nlla [0] * $LngLatM;
				$lat1 = $nlla [1] * $LngLatM;
				if ($heading > 0 && $heading < 90) {
					$newlat = cos ( $newhd ) * ($lat1 - tan ( $newhd ) * $lng1);
					$newlng = $lng1 / cos ( $newhd ) + tan ( $newhd ) * $newlat;
				} else if ($heading > 90 && $heading < 180) {
					$newlng = cos ( $newhd + $hd1 ) * ($lng1 - tan ( $newhd + $hd1 ) * $lat1);
					$newlat = - ($lat1 / cos ( $newhd + $hd1 ) + tan ( $newhd + $hd1 ) * $newlng);
				} else {
					$newlng = $lng;
					$newlat = $lat;
				}
				array_push ( $newAreaOrdinate, array ($newlng / $LngLatM, $newlat / $LngLatM ) );
			}
		} else {
			$newAreaOrdinate = $newLngLatArr;
		}
		
		return $newAreaOrdinate;
	}
	
	/**
	 * 在给定范威区域的 一组经纬度比例
	 *
	 * @param array $picList 景区地图经纬度数组array(array($lng,$alt),array($lng,$alt).....)
	 * @param number $height 地图高
	 * @param number $width 地图宽
	 * @param array $LngLatArr 需要定位的数组经纬度 array(array($lng,$alt),array($lng,$alt).....)
	 * @param int $heading 地图偏转度
	 * @return array 坐标  //返回flase则失败。
	 * 提示：点最好在景区范威.否则旋转某些阶段会丢失。 
	 */
	public function getPicturePosition($picList, $height, $width, $LngLatArr, $heading = 0) {
		
		if (! $picList || ! $LngLatArr)
			return false;
		if (abs ( $heading ) > 180)
			return false;
		if ($heading == 0 || abs ( $heading ) == 180 || abs ( $heading ) == 90) {
			$picList = $this->getMaxMinArr ( $picList );
			$maxLng = $picList [0];
			$minLng = $picList [1];
			$maxLat = $picList [2];
			$minLat = $picList [3];
		} else {
			$picList = $this->getHeadingRectArea ( $picList, $heading );
			$maxLng = $picList [1] [0];
			$minLng = $picList [1] [1];
			$maxLat = $picList [2] [0];
			$minLat = $picList [2] [1];
			$lng3 = $picList [3] [0];
			$lat3 = $picList [3] [1];
		}
		//		print_r($picList);
		

		$lng = $this->GetDistance ( $maxLng, $maxLat, $maxLng, $minLat );
		$lat = $this->GetDistance ( $maxLng, $maxLat, $minLng, $maxLat );
		
		if (! $lng || ! $lat)
			return false;
		
		$newPositionArr = array ();
		foreach ( $LngLatArr as $nlla ) {
			$x = - 1;
			$y = - 1;
			if (($nlla [0] >= $minLng && $nlla [0] <= $maxLng) && ($nlla [1] >= $minLat && $nlla [1] <= $maxLat) && ($heading == 0 || abs ( $heading ) == 90 || abs ( $heading ) == 180)) {
				$nwid = $this->GetDistance ( $nlla [0], $maxLat, $minLng, $maxLat );
				$nhei = $this->GetDistance ( $maxLng, $nlla [1], $maxLng, $maxLat );
				$rnwid = $this->GetDistance ( $nlla [0], $maxLat, $maxLng, $maxLat );
				$xnhei = $this->GetDistance ( $maxLng, $nlla [1], $maxLng, $minLat );
				if ($heading == 0) {
					$x = ($rnwid / $lat) * $width;
					$y = ($xnhei / $lng) * $height;
				} elseif (abs ( $heading ) == 180) {
					$x = ($nwid / $lat) * $width;
					$y = ($nhei / $lng) * $height;
				} elseif ($heading == - 90) {
					$x = ($nwid / $lat) * $width;
					$y = ($xnhei / $lng) * $height;
				} elseif ($heading == 90) {
					$x = ($nhei / $lng) * $height;
					$y = ($rnwid / $lat) * $width;
				}
			
			}
			
			if (($heading > 0 && $heading < 90) || ($heading > 90 && $heading < 180) || ($heading > - 180 && $heading < - 90) || ($heading > - 90 && $heading < 0)) { //0到90度
				

				$resarr = $this->getCoordinate ( $nlla [0], $nlla [1], $width, $height, $maxLng, $minLat, $minLng, $maxLat, $lng3, $lat3 );
				if ($resarr) {
					$x = $resarr ["x"];
					$y = $resarr ["y"];
				}
			}
			
			$arr = array ();
			if ($x >= 0 && $y >= 0)
				$arr = array ($x, $y );
			array_push ( $newPositionArr, $arr ); //x:left ,y:top
		}
		return $newPositionArr;
	}
	
	//数据字典通过分类编码查找
	public function searchAllType($data = "", $parent = "") {
		$codeType = trim ( $data );
		$DaoOption = new CDAOSYS_OPTION_CODE ( );
		$allEvel = $DaoOption->findTypeByCode ( $codeType, $parent );
		return $allEvel;
	}
	
	/**
	 * 获取数据字典编码，以键['OPTION_VALUE']值['OPTION_NAME']对数组返回
	 * Enter description here ...
	 * @param unknown_type $codeType
	 * @param unknown_type $parent
	 */
	public function getOptionCodeKeyValues($codeType = "", $parent = "") {
		$options = self::searchAllType ( $codeType, $parent );
		if ($options) {
			$new = array ();
			foreach ( $options as $k => $v ) {
				$new [$v ['OPTION_VALUE']] = $v ['OPTION_NAME'];
			}
			$options = $new;
		}
		return $options;
	}
	
	/**
	 * 获取数据字典编码，返回以键['OPTION_CODE_ID']值['OPTION_NAME']对数组
	 * @param unknown_type $codeType
	 * @param unknown_type $parent
	 */
	public function getOptionIdName($codeType = "", $parent = "") {
		$options = self::searchAllType ( $codeType, $parent );
		if ($options) {
			$new = array ();
			foreach ( $options as $k => $v ) {
				$new [$v ['OPTION_CODE_ID']] = $v ['OPTION_NAME'];
			}
			$options = $new;
		}
		return $options;
	}
	
	//拿数组里面的最大最小经纬度
	public function getMaxMinArr($picList) {
		if (! is_array ( $picList ))
			return false;
		foreach ( $picList as $pl ) {
			$listLng [] = $pl [0];
			$listLat [] = $pl [1];
		}
		$maxLng = max ( $listLng );
		$minLng = min ( $listLng );
		$maxLat = max ( $listLat );
		$minLat = min ( $listLat );
		
		return array ($maxLng, $minLng, $maxLat, $minLat );
	
	}
	/**
	 * 根据经纬度获取所在地图
	 *先查询景点地图，找出最小的，若存在返回景点地图
	 * 不存在则查询景区地图
	 * 
	 * @param unknown_type $lng
	 * @param unknown_type $lat
	 * @param unknown_type $vc
	 * @return unknown
	 */
	function getMapBylnglat($lng, $lat) {
		
		//先验证值是否存在
		if (empty ( $lng ) || empty ( $lat )) {
			$data = array ("RESULT" => "FAILED", "LIST" => array (), "REASON" => "参数lat或参数lng不存在", 'REASONCODE' => '-2001' );
			return $data;
		}
		
		$return_arr = array ();
		$lnglatdiff = 0;
		
		$DAOTravel_spot_map = new CDAOLS_SPOTMAP ( );
		
		$mapInfo_spot = $DAOTravel_spot_map->getSpotMapBylnglat ( $lng, $lat );
		//		print_r($mapInfo_spot);die();
		foreach ( $mapInfo_spot as $k => $mapSpot_v ) {
			//获取地图经纬度数组
			$mapLngLat = array (array (GPTools::ms2d ( $mapSpot_v ['P1_LONGITUDE'] ), GPTools::ms2d ( $mapSpot_v ['P1_LATITUDE'] ) ), array (GPTools::ms2d ( $mapSpot_v ['P2_LONGITUDE'] ), GPTools::ms2d ( $mapSpot_v ['P2_LATITUDE'] ) ), array (GPTools::ms2d ( $mapSpot_v ['P3_LONGITUDE'] ), GPTools::ms2d ( $mapSpot_v ['P3_LATITUDE'] ) ), array (GPTools::ms2d ( $mapSpot_v ['P4_LONGITUDE'] ), GPTools::ms2d ( $mapSpot_v ['P4_LATITUDE'] ) ) );
			
			//地图的长，宽
			$map_height = $mapSpot_v ['HEIGHT'];
			$map_width = $mapSpot_v ['WIDTH'];
			
			$lnglat = array (array (GPTools::ms2d ( $lng ), GPTools::ms2d ( $lat ) ) );
			//获得相对地图的X,Y
			$spot_position = self::getPicturePosition ( $mapLngLat, $map_height, $map_width, $lnglat );
			$mapSpot_v ['POSITION'] = array ('X' => $spot_position [0] [0], 'Y' => $spot_position [0] [1] );
			//如果坐标为都为0，不在地图上,去除掉
			if (empty ( $spot_position [0] )) {
				unset ( $mapInfo_spot [$k] );
				continue;
			}
			
			//查找范围最小的地图
			$lng_arr = array ($mapSpot_v ['P1_LONGITUDE'], $mapSpot_v ['P2_LONGITUDE'], $mapSpot_v ['P2_LONGITUDE'], $mapSpot_v ['P4_LONGITUDE'] );
			$lat_arr = array ($mapSpot_v ['P1_LATITUDE'], $mapSpot_v ['P2_LATITUDE'], $mapSpot_v ['P3_LATITUDE'], $mapSpot_v ['P4_LATITUDE'] );
			//求经纬度差最小的
			

			if ($lnglatdiff > min ( array (max ( $lng_arr ) - min ( $lng_arr ), max ( $lat_arr ) - min ( $lat_arr ) ) ) || $lnglatdiff == 0) {
				$lnglatdiff = min ( array (max ( $lng_arr ) - min ( $lng_arr ), max ( $lat_arr ) - min ( $lat_arr ) ) );
				
				$return_arr ['MAP_ID'] = $mapSpot_v ['MAP_ID'];
				$return_arr ['SPOT'] = $mapSpot_v ['SPOT_ID'];
				$return_arr ['MAPTYPE'] = 'SPOT';
				$return_arr ['MAP'] = SF_BASE_URL . $mapSpot_v ['MAP_URL'];
				if ($spot_position [0]) {
					$return_arr ['POSITION'] = array ('X' => $spot_position [0] [0], 'Y' => $spot_position [0] [1] );
				} else {
					$return_arr ['POSITION'] = array ('X' => - 1, 'Y' => - 1 );
				}
			
			}
		
		}
		
		//若返回的景点地图为空如果景点为空取景区地图
		//		print_r($return_arr);die();
		$DAOTravel_area_map=new CDAOLS_AREAMAP();
		if (empty ( $return_arr )) {
			$mapInfo_area = $DAOTravel_area_map->getAreaMapBylnglat ( $lng, $lat );
			
			//			print_r($mapInfo_area);die();
			if (empty ( $mapInfo_area )) {
				$data = array ("RESULT" => "FAILED", "LIST" => array (), "REASON" => "无地图信息", "REASONCODE" => "-1003" );
				return $data;
			} else {
				foreach ( $mapInfo_area as $k => $mapArea_v ) {
					
					//查找范围最小的地图
					$lng_arr = array ($mapArea_v ['E_LONGITUDE'], $mapArea_v ['W_LONGITUDE'] );
					$lat_arr = array ($mapArea_v ['N_LATITUDE'], $mapArea_v ['S_LATITUDE'] );
					//求经纬度差最小的
					

					if ($lnglatdiff > min ( array (max ( $lng_arr ) - min ( $lng_arr ), max ( $lat_arr ) - min ( $lat_arr ) ) ) || $lnglatdiff == 0) {
						$lnglatdiff = min ( array (max ( $lng_arr ) - min ( $lng_arr ), max ( $lat_arr ) - min ( $lat_arr ) ) );
						
						//获取地图经纬度数组
						$mapLngLat = array (array (GPTools::ms2d ( $mapArea_v ['E_LONGITUDE'] ), GPTools::ms2d ( $mapArea_v ['N_LATITUDE'] ) ), array (GPTools::ms2d ( $mapArea_v ['E_LONGITUDE'] ), GPTools::ms2d ( $mapArea_v ['S_LATITUDE'] ) ), array (GPTools::ms2d ( $mapArea_v ['W_LONGITUDE'] ), GPTools::ms2d ( $mapArea_v ['N_LATITUDE'] ) ), array (GPTools::ms2d ( $mapArea_v ['W_LONGITUDE'] ), GPTools::ms2d ( $mapArea_v ['S_LATITUDE'] ) ) );
						
						//地图的长，宽
						$map_height = $mapArea_v ['HEIGHT'];
						$map_width = $mapArea_v ['WIDTH'];
						
						$lnglat = array (array (GPTools::ms2d ( $lng ), GPTools::ms2d ( $lat ) ) );
						//获得相对地图的X,Y
						$spot_position = self::getPicturePosition ( $mapLngLat, $map_height, $map_width, $lnglat );
						
						$return_arr ['MAP_ID'] = $mapArea_v ['MAP_ID'];
						$return_arr ['AREA'] = $mapArea_v ['AREA_ID'];
						$return_arr ['MAPTYPE'] = 'AREA';
						$return_arr ['MAP_URL'] = SF_BASE_URL . $mapArea_v ['MAP_URL'];
						if ($spot_position [0]) {
							$return_arr ['POSITION'] = array ('X' => $spot_position [0] [0], 'Y' => $spot_position [0] [1] );
						} else {
							$return_arr ['POSITION'] = array ('X' => - 1, 'Y' => - 1 );
						}
					
					}
				
				}
			}
		
		}
		return $return_arr;
		
	//		print_r($return_arr);
	

	}
	
	/**
	 * 返回定点的x,y坐标
	 *
	 * @param 定点的lng $coolng
	 * @param 定点的lat $coolat
	 * @param 地图宽 $width
	 * @param 地图的 $height
	 * @param 东边 $lng1
	 * @param 东边 $lat1
	 * @param 西边 $lng2
	 * @param 西边 $lat2
	 * @param 最小 $lng3
	 * @param 最小 $lat3
	 * @return x,y 数组
	 */
	public function getCoordinate($coolng, $coolat, $width, $height, $lng1, $lat1, $lng2, $lat2, $lng3 = false, $lat3 = false) {
		
		if (! $lng3 && ! $lat3) {
			$lng3 = $lng2;
			$lat3 = $lat1;
		}
		$ratioW = $this->GetDistance ( $lng3, $lat3, $lng1, $lat1 ) / $width; //宽的1 = 多少长度
		$ratioH = $this->GetDistance ( $lng3, $lat3, $lng2, $lat2 ) / $height; //高的1 = 多少长度 
		

		$a = $this->GetDistance ( $lng3, $lat3, $coolng, $coolat );
		$b = $this->GetDistance ( $lng1, $lat1, $coolng, $coolat );
		$c = $this->GetDistance ( $lng1, $lat1, $lng3, $lat3 );
		//		$f*$f - $c*$f = ($b*$b-$a*$a+$c*$c)/2;//一元二次方程式
		$res = (pow ( $b, 2 ) - pow ( $a, 2 ) + pow ( $c, 2 )) / 2;
		
		if ($res >= 0) {
			$x = abs ( ($c + sqrt ( pow ( $c, 2 ) + 4 * $res )) / 2 );
			if ($x >= $c) {
				$x = abs ( ($c - sqrt ( pow ( $c, 2 ) + 4 * $res )) / 2 ) / $ratioW;
			}
			$y = sin ( cosh ( $f / $b ) ) * $b / $ratioH;
		}
		return array ("x" => $x, "y" => $y );
	}

}
