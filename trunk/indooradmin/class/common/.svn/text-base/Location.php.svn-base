<?php
class Location {
	public function getLocation($aplist, $BUILDING_ID, $FLOOR_ID, $DRAW_MAP_ID) {
		if (! empty ( $aplist )) {
			$ap1 = null;
			$ap2 = null;
			$ap3 = null;
			$sql = "";
			if (! empty ( $DRAW_MAP_ID )) {
				$sql = "DRAW_MAP_ID={$DRAW_MAP_ID}";
			} else {
				if (! empty ( $FLOOR_ID )) {
					$sql = "FLOOR_ID={$FLOOR_ID}";
				} else {
					if (empty ( $BUILDING_ID )) {
						$sql = "BUILDING_ID={$BUILDING_ID}";
					}
				}
			}
			$apsql = "";
			foreach ( $aplist as $k => $ap ) {
				if (empty ( $apsql )) {
					$apsql = "MAC_BSSID='{$ap->MAC_BSSID}'";
				} else {
					$apsql = $apsql . " OR MAC_BSSID='{$ap->MAC_BSSID}'";
				}
			}
			if (empty ( $sql )) {
				$sql = $apsql;
			} else {
				$sql = $sql . " AND ({$apsql})";
			}
			$sql = strtoupper ( $sql );
			$cdaocb_location = new CDAOCB_LOCATION ();
			$apinfolist = $cdaocb_location->getAllWhere ( "*", $sql );
			if (! empty ( $apinfolist )) {
				foreach ( $apinfolist as $k => $apinfo ) {
					$apinfolist [$apinfo ["MAC_BSSID"]] = $apinfo;
					unset ( $apinfolist [$k] );
				}
			}
			$ap1info = null;
			$ap2info = null;
			$ap3info = null;
			if (! empty ( $apinfolist )) {
				foreach ( $aplist as $k => $ap ) {
					$apinfo = $apinfolist [strtoupper ( $ap->MAC_BSSID )];
					if ((! empty ( $apinfo )) && (! $this->isCloseToAp ( $apinfo, $ap1info, $ap2info, $ap3info ))) {
						if ($ap1 === null) {
							$ap1 = $ap;
							$ap1info = $apinfo;
						} else {
							if ($ap->LEVEL > $ap1->LEVEL) {
								$ap3 = $ap2;
								$ap2 = $ap1;
								$ap1 = $ap;
								$ap3info = $ap2info;
								$ap2info = $ap1info;
								$ap1info = $apinfo;
							} else {
								if ($ap2 === null) {
									$ap2 = $ap;
									$ap2info = $apinfo;
								} else {
									if ($ap->LEVEL > $ap2->LEVEL) {
										$ap3 = $ap2;
										$ap2 = $ap;
										$ap3info = $ap2info;
										$ap2info = $apinfo;
									} else {
										if ($ap3 === null) {
											$ap3 = $ap;
											$ap3info = $apinfo;
										} else {
											if ($ap->LEVEL > $ap3->LEVEL) {
												$ap3 = $ap;
												$ap3info = $apinfo;
											}
										}
									}
								}
							}
						}
					}
				}
				$location = $this->calcLocationByAp ( $apinfolist, $ap1, $ap2, $ap3 );
				return ($location);
			}
		}
		return (null);
	}
	/**
	 * 算一下是否和ap1,ap2,ap3太近，在1米之内
	 */
	private function isCloseToAp($apinfo, $ap1info, $ap2info, $ap3info) {
		$closeMeter = 1;
		if ($ap1info != null) {
			if ($this->getDistanceForTwoAp ( $apinfo, $ap1info ) < $closeMeter) {
				return (true);
			}
		}
		if ($ap2info != null) {
			if ($this->getDistanceForTwoAp ( $apinfo, $ap2info ) < $closeMeter) {
				return (true);
			}
		}
		if ($ap3info != null) {
			if ($this->getDistanceForTwoAp ( $apinfo, $ap3info ) < $closeMeter) {
				return (true);
			}
		}
		return (false);
	}
	/**
	 * 获取两ap间的距离（米）
	 */
	private function getDistanceForTwoAp($ap1info, $ap2info) {
		return ($this->toMeter ( (new MathUtil ())->getDistanceForTwoPoint ( $ap1info ["POSITION_X"], $ap1info ["POSITION_Y"], $ap2info ["POSITION_X"], $ap2info ["POSITION_Y"] ) ));
	}
	public function toMeter($px) {
		$scale = (new CDict ())->DEFAULT_PLANEGRAPH ["DW_SCALE"];
		return ($px * $scale);
	}
	public function toPx($m) {
		$scale = (new CDict ())->DEFAULT_PLANEGRAPH ["DW_SCALE"];
		return ($m / $scale);
	}
	private function calcLocationByAp($apinfolist, $ap1 = null, $ap2 = null, $ap3 = null) {
		if ($ap1 !== null) {
			$ap1info = $apinfolist [strtoupper ( $ap1->MAC_BSSID )];
			$ap1_x = $this->toMeter ( $ap1info ["POSITION_X"] );
			$ap1_y = $this->toMeter ( $ap1info ["POSITION_Y"] );
			$ap1_r = $this->getRadiusForSingal ( $ap1->LEVEL );
			if ($ap2 !== null) {
				$ap2info = $apinfolist [strtoupper ( $ap2->MAC_BSSID )];
				$ap2_x = $this->toMeter ( $ap2info ["POSITION_X"] );
				$ap2_y = $this->toMeter ( $ap2info ["POSITION_Y"] );
				$ap2_r = $this->getRadiusForSingal ( $ap2->LEVEL );
				$mathUtil = new MathUtil ();
				$in12 = $mathUtil->getIntersectionForTwoCircle ( $ap1_r, $ap1_x, $ap1_y, $ap2_r, $ap2_x, $ap2_y );
				if ($ap3 !== null) {
					$ap3info = $apinfolist [strtoupper ( $ap3->MAC_BSSID )];
					$ap3_x = $this->toMeter ( $ap3info ["POSITION_X"] );
					$ap3_y = $this->toMeter ( $ap3info ["POSITION_Y"] );
					$ap3_r = $this->getRadiusForSingal ( $ap3->LEVEL );
					$in13 = $mathUtil->getIntersectionForTwoCircle ( $ap1_r, $ap1_x, $ap1_y, $ap3_r, $ap3_x, $ap3_y );
					$in23 = $mathUtil->getIntersectionForTwoCircle ( $ap2_r, $ap2_x, $ap2_y, $ap3_r, $ap3_x, $ap3_y );
					$location12_3 = $this->computeLocation ( $ap3_r, $ap3_x, $ap3_y, $in12 );
					$location13_2 = $this->computeLocation ( $ap2_r, $ap2_x, $ap2_y, $in13 );
					$location23_1 = $this->computeLocation ( $ap1_r, $ap1_x, $ap1_y, $in23 );
					if ($location12_3 != null) {
						$location12_3 ["x"] = $this->toPx ( $location12_3 ["x"] );
						$location12_3 ["y"] = $this->toPx ( $location12_3 ["y"] );
						return ($location12_3);
					} else {
						if ($location13_2 != null) {
							$location13_2 ["x"] = $this->toPx ( $location13_2 ["x"] );
							$location13_2 ["y"] = $this->toPx ( $location13_2 ["y"] );
							return ($location13_2);
						} else {
							if ($location23_1 != null) {
								$location23_1 ["x"] = $this->toPx ( $location23_1 ["x"] );
								$location23_1 ["y"] = $this->toPx ( $location23_1 ["y"] );
								return ($location23_1);
							}
						}
					}
				} else {
					if ($in12 != null) {
						return (array (
								"x" => $this->toPx ( $in12 [0] ["x"] ),
								"y" => $this->toPx ( $in12 [0] ["y"] ) 
						));
					}
				}
			}
			return (array (
					"x" => $this->toPx ( $ap1_x ),
					"y" => $this->toPx ( $ap1_y ) 
			));
		}
		return (null);
	}
	/**
	 *
	 * @param unknown $pd
	 *        	测量点的信号值
	 * @param number $nW
	 *        	测量点与BS间的墙壁数量
	 * @param number $C
	 *        	最大墙壁数，超过C个墙壁则会对信号造成影响
	 * @param number $WAF
	 *        	墙壁衰减因子
	 * @param number $n
	 *        	信号值因距离衰减的系数
	 * @param unknown $pdo
	 *        	测量基准点的信号值
	 * @param number $do
	 *        	测量基准点距BS的距离
	 * @return number 信号圆的半径（米）
	 */
	public function getRadiusForSingal($pd, $nW = 0, $C = 1, $WAF = 10, $n = 2, $pdo = -35, $do = 4) {
		$E = 2.7182818284590452354;
		$W = 0;
		if ($nW < $C) {
			$W = $nW * $WAF;
		} else {
			$W = $C * $WAF;
		}
		$d = $do * pow ( $E, ($pdo - $pd - $W) / ($n * 10) );
		return ($d);
	}
	private function computeLocation($r, $r_x, $r_y, $intersections) {
		if ($intersections !== null) {
			$x0 = $intersections [0] ["x"];
			$y0 = $intersections [0] ["y"];
			$x1 = $x0;
			$y1 = $y0;
			if ($intersections [1] !== null) {
				$x1 = $intersections [1] ["x"];
				$y1 = $intersections [1] ["y"];
			}
			return ($this->computeLineCircle ( $r, $r_x, $r_y, $x0, $y0, $x1, $y1 ));
		}
		return (null);
	}
	
	/**
	 * 计算两个圆的交点组成的直线，与另外一个圆的交点
	 *
	 * @param
	 *        	r
	 *        	第三个圆的半径
	 * @param
	 *        	r_x
	 *        	第三个圆坐标x
	 * @param
	 *        	r_y
	 *        	第三个圆坐标y
	 * @param
	 *        	x0
	 *        	直线的两个点x
	 * @param
	 *        	y0
	 *        	直线的两个点y
	 * @param
	 *        	x1
	 *        	直线的两个点x
	 * @param
	 *        	y1
	 *        	直线的两个点y
	 */
	private function computeLineCircle($r, $r_x, $r_y, $x0, $y0, $x1, $y1) {
		// 两个圆有交点,开始计算交点的直线公式
		// 第三个圆与第一个交点的距离
		$mathUtil = new MathUtil ();
		$distance0 = $mathUtil->getDistanceForTwoPoint ( $r_x, $r_y, $x0, $y0 );
		// 第三个圆与第二个交点的距离
		$distance1 = $mathUtil->getDistanceForTwoPoint ( $r_x, $r_y, $x1, $y1 );
		// System.out.println(distance0+"||"+distance1+"||"+r);
		$p = array ();
		if ($r < $distance0 && $r <= $distance1) { // 两个圆的直线与第三个圆没有交点，且距离大于第三个圆的半径
			if ($distance0 < $distance1) {
				$p ["x"] = $x0;
				$p ["y"] = $y0;
			} else {
				$p ["x"] = $x1;
				$p ["y"] = $y1;
			}
		} else if ($r >= $distance0 && $r >= $distance1) { // 两个圆的直线与第三个圆没有交点，且距离小于第三个圆的半径
			if ($distance0 < $distance1) {
				$p ["x"] = $x1;
				$p ["y"] = $y1;
			} else {
				$p ["x"] = $x0;
				$p ["y"] = $y0;
			}
		} else {
			$intersections = $mathUtil->getIntersectionForCircleAndLine ( $r, $r_x, $r_y, $x0, $y0, $x1, $y1 );
			if ($intersections != null) {
				$p = $intersections [0];
				if ($intersections [1] != null) {
					$min = ($x0 > $x1) ? $x1 : $x0;
					$max = ($x0 > $x1) ? $x0 : $x1;
					if (($min <= $intersections [1] ["x"]) && ($intersections [1] ["x"] <= $max)) {
						$p = $intersections [1];
					}
				}
			}
		}
		if (empty ( $p )) {
			$p = null;
		}
		return ($p);
	}
}
?>