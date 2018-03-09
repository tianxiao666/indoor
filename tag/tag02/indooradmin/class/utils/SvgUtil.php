<?php
/**
 * SVG
 * @author xiang.zc
 *
 */
class SvgUtil {
	/**
	 * 获取XMLParseStruct关闭tag的index
	 *
	 * @param unknown $XMLParseStruct_Value        	
	 * @param unknown $OpenIndex        	
	 * @return number unknown NULL
	 */
	public static function getXMLParseStructCloseTagIndex($XMLParseStruct_Value, $OpenIndex) {
		if (! empty ( $XMLParseStruct_Value )) {
			$svgTagValue = $XMLParseStruct_Value [$OpenIndex];
			if ($svgTagValue ["type"] == "open") {
				$svgTagLevel = $svgTagValue ["level"];
				$i = $OpenIndex + 1;
				$count = count ( $XMLParseStruct_Value );
				while ( $i < $count ) {
					if (($XMLParseStruct_Value [$i] ["type"] == "close") && ($svgTagLevel == $XMLParseStruct_Value [$i] ["level"])) {
						return ($i);
					}
					++ $i;
				}
			} else {
				if ($svgTagValue ["type"] == "complete") {
					return ($OpenIndex);
				}
			}
		}
		return (null);
	}
	/**
	 * 获取XMLParseStruct的结构信息
	 *
	 * @param unknown $XMLParseStruct_Value        	
	 * @param unknown $OpenIndex        	
	 * @param unknown $SvgInfo        	
	 * @return multitype:NULL unknown multitype: Ambigous <number, NULL, unknown>
	 */
	public static function getXMLParseStructInfo($XMLParseStruct_Value, $OpenIndex, $SvgInfo = array()) {
		$closeIndex = SvgUtil::getXMLParseStructCloseTagIndex ( $XMLParseStruct_Value, $OpenIndex );
		if (! empty ( $closeIndex )) {
			$SvgInfo = array ();
			$SvgInfo ["open"] = $OpenIndex;
			$SvgInfo ["close"] = $closeIndex;
			$SvgInfo ["tag"] = $XMLParseStruct_Value [$OpenIndex] ["tag"];
			$SvgInfo ["attributes"] = $XMLParseStruct_Value [$OpenIndex] ["attributes"];
			$SvgInfo ["value"] = $XMLParseStruct_Value [$OpenIndex] ["value"];
			$elements = array ();
			$i = $OpenIndex + 1;
			while ( $i < $closeIndex ) {
				$element = SvgUtil::getXMLParseStructInfo ( $XMLParseStruct_Value, $i );
				if (empty ( $element )) {
					++ $i;
				} else {
					array_push ( $elements, $element );
					$i = $element ["close"] + 1;
				}
			}
			if (! empty ( $elements )) {
				$SvgInfo ["elements"] = $elements;
			}
		}
		return ($SvgInfo);
	}
	/**
	 * 获取SVG结构信息
	 *
	 * @param unknown $XMLParseStruct_Index        	
	 * @param unknown $XMLParseStruct_Value        	
	 * @return multitype:unknown Ambigous <unknown> |NULL
	 */
	public static function getSvgInfo($SvgSrc) {
		$xml_parser = xml_parser_create ();
		xml_parse_into_struct ( $xml_parser, $SvgSrc, $XMLParseStruct_Value );
		xml_parser_free ( $xml_parser );
		return (SvgUtil::getXMLParseStructInfo ( $XMLParseStruct_Value, 0 ));
	}
	/**
	 * 获取SVG图层指定Tag的Value
	 *
	 * @param unknown $XMLParseStruct_Index        	
	 * @param unknown $XMLParseStruct_Value        	
	 * @return unknown
	 */
	public static function getSvgLayerValue($LayerInfo, $Tag) {
		$ElementInfos = $LayerInfo ["elements"];
		if (! empty ( $ElementInfos )) {
			foreach ( $ElementInfos as $ElementInfo ) {
				if ($ElementInfo ["tag"] == $Tag) {
					return ($ElementInfo ["value"]);
				}
			}
		}
		return (null);
	}
	/**
	 * 获取限制字节数字的GBK字符串
	 *
	 * @param unknown $Str        	
	 * @param unknown $LimitBytes        	
	 * @return string unknown
	 */
	public static function limitGbkStr($GbkStr, $LimitBytes) {
		$Strlen = strlen ( $GbkStr );
		if ($Strlen > $LimitBytes) {
			if ($LimitBytes > 0) {
				$start = floor ( $LimitBytes / 2 );
				$end = $LimitBytes;
				$limitStr = "";
				$limitStrlen = 0;
				while ( $start <= $end ) {
					$now = floor ( ($start + $end) / 2 );
					$limitStr = iconv_substr ( $GbkStr, 0, $now, "GBK" );
					$limitStrlen = strlen ( $limitStr );
					if ($limitStrlen > $LimitBytes) {
						$end = $now - 1;
					} else {
						if ($limitStrlen < $LimitBytes) {
							$start = $now + 1;
						} else {
							break;
						}
					}
				}
				if ($limitStrlen > $LimitBytes) {
					$limitStr = iconv_substr ( $GbkStr, 0, $now - 1, "GBK" );
				}
				return ($limitStr);
			}
			return ("");
		}
		return ($GbkStr);
	}
	/**
	 * rgb颜色转rgb
	 *
	 * @param unknown $color        	
	 */
	public static function ColorToRGB($color) {
		$rgb = array (
				"r" => 0,
				"g" => 0,
				"b" => 0 
		);
		$color = "" . $color;
		$colorlen = strlen ( $color );
		switch ($colorlen) {
			case 7 :
				$rgb ["r"] = hexdec ( substr ( $color, 1, 2 ) ) / 255 * 255;
				$rgb ["g"] = hexdec ( substr ( $color, 3, 2 ) ) / 255 * 255;
				$rgb ["b"] = hexdec ( substr ( $color, 5, 2 ) ) / 255 * 255;
				break;
			case 4 :
				$rgb ["r"] = hexdec ( substr ( $color, 1, 1 ) ) / 15 * 255;
				$rgb ["g"] = hexdec ( substr ( $color, 2, 1 ) ) / 15 * 255;
				$rgb ["b"] = hexdec ( substr ( $color, 3, 1 ) ) / 15 * 255;
				break;
		}
		return ($rgb);
	}
	/**
	 * 保存png图片
	 *
	 * @param unknown $PngSrc        	
	 * @param unknown $bgc        	
	 * @param unknown $path        	
	 * @param unknown $s        	
	 * @return boolean
	 */
	public static function savePngSrcToPath($PngSrc, $bgc, $s, $path) {
		if (! empty ( $PngSrc )) {
			$srcimg = imagecreatefromstring ( $PngSrc );
			$srcimag_w = imagesx ( $srcimg );
			$srcimag_h = imagesy ( $srcimg );
			$dstimg = imagecreatetruecolor ( $srcimag_w, $srcimag_h );
			$rgb = SvgUtil::ColorToRGB ( $bgc );
			$bg = imagecolorallocate ( $srcimg, $rgb ["r"], $rgb ["g"], $rgb ["b"] );
			if ($bg != - 1) {
				imagefilledrectangle ( $dstimg, 0, 0, $srcimag_w, $srcimag_h, $bg );
			}
			imagecopy ( $dstimg, $srcimg, 0, 0, 0, 0, $srcimag_w, $srcimag_h );
			$srcimg = $dstimg;
			if (! empty ( $s )) {
				$m = ($srcimag_w > $srcimag_h) ? $srcimag_w : $srcimag_h;
				$scale = $s / $m;
				$dstimag_w = $srcimag_w * $scale;
				$dstimag_h = $srcimag_h * $scale;
				$dstimg = imagecreatetruecolor ( $dstimag_w, $dstimag_h );
				imagecopyresized ( $dstimg, $srcimg, 0, 0, 0, 0, $dstimag_w, $dstimag_h, $srcimag_w, $srcimag_h );
				imagedestroy ( $srcimg );
			}
			imagepng ( $dstimg, $path );
			imagedestroy ( $dstimg );
			return (true);
		}
		return (false);
	}
	/**
	 * SVG形状ID
	 *
	 * @var unknown
	 */
	const SVG_ELEMENT_ID_RECT = 0;
	const SVG_ELEMENT_ID_CIRCLE = 1;
	const SVG_ELEMENT_ID_ELLIPSE = 2;
	const SVG_ELEMENT_ID_LINE = 3;
	const SVG_ELEMENT_ID_POLYGON = 4;
	const SVG_ELEMENT_ID_POLYLINE = 5;
	const SVG_ELEMENT_ID_PATH = 6;
	const SVG_ELEMENT_ID_TEXT = 7;
	const SVG_ELEMENT_ID_IMAGE = 8;
	/**
	 * SVG Path属性
	 *
	 * @var unknown
	 */
	const SVG_ELEMENT_PATH_ID_M = 0;
	const SVG_ELEMENT_PATH_ID_L = 1;
	const SVG_ELEMENT_PATH_ID_H = 2;
	const SVG_ELEMENT_PATH_ID_V = 3;
	const SVG_ELEMENT_PATH_ID_C = 4;
	const SVG_ELEMENT_PATH_ID_S = 5;
	const SVG_ELEMENT_PATH_ID_Q = 6;
	const SVG_ELEMENT_PATH_ID_T = 7;
	const SVG_ELEMENT_PATH_ID_A = 8;
	const SVG_ELEMENT_PATH_ID_Z = 9;
	const SVG_ELEMENT_PATH_ID_X = 10;
	/**
	 * 获取SVG形状ID
	 *
	 * @param unknown $SvgElementTagName        	
	 * @return Ambigous <number>
	 */
	public static function getSvgElementId($SvgElementTagName) {
		$SVG_ELEMENT_LIST = array (
				"RECT" => SvgUtil::SVG_ELEMENT_ID_RECT,
				"CIRCLE" => SvgUtil::SVG_ELEMENT_ID_CIRCLE,
				"ELLIPSE" => SvgUtil::SVG_ELEMENT_ID_ELLIPSE,
				"LINE" => SvgUtil::SVG_ELEMENT_ID_LINE,
				"POLYGON" => SvgUtil::SVG_ELEMENT_ID_POLYGON,
				"POLYLINE" => SvgUtil::SVG_ELEMENT_ID_POLYLINE,
				"PATH" => SvgUtil::SVG_ELEMENT_ID_PATH,
				"TEXT" => SvgUtil::SVG_ELEMENT_ID_TEXT,
				"IMAGE" => SvgUtil::SVG_ELEMENT_ID_IMAGE 
		);
		return ($SVG_ELEMENT_LIST [strtoupper ( $SvgElementTagName )]);
	}
	/**
	 * 获取SVG Path属性ID
	 *
	 * @param unknown $SvgElementPathAttrName        	
	 * @return Ambigous <number>
	 */
	public static function getSvgElementPathAttrId($SvgElementPathAttrName) {
		$SVG_ELEMENT_PATH_ATTR_LIST = array (
				"M" => SvgUtil::SVG_ELEMENT_PATH_ID_M,
				"L" => SvgUtil::SVG_ELEMENT_PATH_ID_L,
				"H" => SvgUtil::SVG_ELEMENT_PATH_ID_H,
				"V" => SvgUtil::SVG_ELEMENT_PATH_ID_V,
				"C" => SvgUtil::SVG_ELEMENT_PATH_ID_C,
				"S" => SvgUtil::SVG_ELEMENT_PATH_ID_S,
				"Q" => SvgUtil::SVG_ELEMENT_PATH_ID_Q,
				"T" => SvgUtil::SVG_ELEMENT_PATH_ID_T,
				"A" => SvgUtil::SVG_ELEMENT_PATH_ID_A,
				"Z" => SvgUtil::SVG_ELEMENT_PATH_ID_Z,
				" " => SvgUtil::SVG_ELEMENT_PATH_ID_X 
		);
		return ($SVG_ELEMENT_PATH_ATTR_LIST [strtoupper ( $SvgElementPathAttrName )]);
	}
	/**
	 * 获取SVG元素中心坐标
	 *
	 * @param unknown $ElementInfo        	
	 * @return multitype:
	 */
	public static function getSvgElementPosition($ElementInfo) {
		$position = array ();
		if (! empty ( $ElementInfo )) {
			$ElementId = SvgUtil::getSvgElementId ( $ElementInfo ["tag"] );
			$ElementAttr = $ElementInfo ["attributes"];
			switch ($ElementId) {
				case SvgUtil::SVG_ELEMENT_ID_RECT :
				case SvgUtil::SVG_ELEMENT_ID_IMAGE :
					$position ["X"] = $ElementAttr ["X"] + ($ElementAttr ["WIDTH"] / 2);
					$position ["Y"] = $ElementAttr ["Y"] + ($ElementAttr ["HEIGHT"] / 2);
					break;
				case SvgUtil::SVG_ELEMENT_ID_CIRCLE :
				case SvgUtil::SVG_ELEMENT_ID_ELLIPSE :
					$position ["X"] = $ElementAttr ["CX"];
					$position ["Y"] = $ElementAttr ["CY"];
					break;
				case SvgUtil::SVG_ELEMENT_ID_LINE :
					$position ["X"] = ($ElementAttr ["X1"] + $ElementAttr ["X2"]) / 2;
					$position ["Y"] = ($ElementAttr ["Y1"] + $ElementAttr ["Y2"]) / 2;
					break;
				case SvgUtil::SVG_ELEMENT_ID_POLYGON :
				case SvgUtil::SVG_ELEMENT_ID_POLYLINE :
				case SvgUtil::SVG_ELEMENT_ID_PATH :
					$POINTS = trim ( $ElementAttr [($ElementId == SvgUtil::SVG_ELEMENT_ID_PATH) ? "D" : "POINTS"] );
					$replacecount = 1;
					while ( $replacecount > 0 ) {
						$POINTS = str_replace ( "  ", " ", $POINTS, $replacecount );
					}
					$replacecount = 1;
					while ( $replacecount > 0 ) {
						$POINTS = str_replace ( " ,", ",", $POINTS, $replacecount );
					}
					$replacecount = 1;
					while ( $replacecount > 0 ) {
						$POINTS = str_replace ( ", ", ",", $POINTS, $replacecount );
					}
					$sumx = 0;
					$sumy = 0;
					$pointCount = 0;
					switch ($ElementId) {
						default :
							$POINTS_LIST = explode ( " ", $POINTS );
							foreach ( $POINTS_LIST as $coord ) {
								if (! empty ( $coord )) {
									$POSITION = explode ( ",", $coord );
									if (count ( $POSITION ) == 2) {
										$sumx = $sumx + $POSITION [0];
										$sumy = $sumy + $POSITION [1];
										++ $pointCount;
									}
								}
							}
							break;
						case SvgUtil::SVG_ELEMENT_ID_PATH :
							$POSITION_X = "";
							$POSITION_Y = null;
							$PATH_TAG = null;
							$POINTS = $POINTS . " ";
							$POINTS_LEN = strlen ( $POINTS );
							$LAST_X = 0;
							$LAST_Y = 0;
							$i = 0;
							while ( $i < $POINTS_LEN ) {
								$ch = $POINTS [$i];
								$ID = SvgUtil::getSvgElementPathAttrId ( strtoupper ( $ch ) );
								if ($ID !== null) {
									if ($PATH_TAG !== null) {
										if ($PATH_TAG != strtoupper ( $PATH_TAG )) {
											$POSITION_X = $LAST_X + $POSITION_X;
											$POSITION_Y = $LAST_Y + $POSITION_Y;
										}
										$sumx = $sumx + $POSITION_X;
										$sumy = $sumy + $POSITION_Y;
										++ $pointCount;
									}
									if ($ID != SvgUtil::SVG_ELEMENT_PATH_ID_X) {
										$PATH_TAG = $ch;
										$LAST_X = $POSITION_X;
										$LAST_Y = $POSITION_Y;
									}
									$POSITION_X = "";
									$POSITION_Y = null;
								} else {
									if ($PATH_TAG !== null) {
										if ($ch == ",") {
											$POSITION_Y = "";
										} else {
											if ($POSITION_Y === null) {
												$POSITION_X = $POSITION_X . $ch;
											} else {
												$POSITION_Y = $POSITION_Y . $ch;
											}
										}
									}
								}
								++ $i;
							}
							break;
					}
					if ($pointCount > 0) {
						$position ["X"] = $sumx / $pointCount;
						$position ["Y"] = $sumy / $pointCount;
					}
					break;
				case SvgUtil::SVG_ELEMENT_ID_TEXT :
					$position ["X"] = $ElementAttr ["X"];
					$position ["Y"] = $ElementAttr ["Y"];
					break;
			}
		}
		return ($position);
	}
	/**
	 * 赤道半径
	 *
	 * @var unknown
	 */
	const EARTH_EQUATORIAL_RADIUS = 6378.1370;
	/**
	 * 极半径
	 *
	 * @var unknown
	 */
	const EARTH_POLAR_RADIUS = 6356.7523;
	/**
	 * 角度转弧度
	 *
	 * @param unknown $angle        	
	 * @return number
	 */
	public static function radian($angle) {
		return ($angle * pi () / 180);
	}
	/**
	 * 根据两点间的经纬度计算距离
	 *
	 * @param unknown $lat1        	
	 * @param unknown $lng1        	
	 * @param unknown $lat2        	
	 * @param unknown $lng2        	
	 * @return number
	 */
	public static function getLatLngDistance($lat1, $lng1, $lat2, $lng2) {
		$radLat1 = SvgUtil::radian ( $lat1 );
		$radLat2 = SvgUtil::radian ( $lat2 );
		$a = $radLat1 - $radLat2;
		$b = SvgUtil::radian ( $lng1 ) - SvgUtil::radian ( $lng2 );
		$stepOne = pow ( sin ( $a / 2 ), 2 ) + cos ( $radLat1 ) * cos ( $radLat2 ) * pow ( sin ( $b / 2 ), 2 );
		$s = 2 * asin ( sqrt ( $stepOne ) );
		$s = $s * (SvgUtil::EARTH_POLAR_RADIUS + SvgUtil::EARTH_EQUATORIAL_RADIUS) / 2;
		return round ( $s * 100000 ) / 100;
	}
	public static function getEncodeLatLng($LatOrLng) {
		return ($LatOrLng * 1e16);
	}
	public static function getDecodeLatLng($LatOrLng) {
		return ($LatOrLng / 1e16);
	}
	/**
	 * 获取POI PIC 路径
	 *
	 * @return string
	 */
	public static function getPoiPicPath() {
		return ("medialib/PIC/");
	}
	/**
	 * 保存PIC信息返回新增PIC_ID
	 * Enter description here .
	 */
	public static function savePic($FILE_NAME, $POI_ID, $file, $FLOOR_ID, $BUILDING_ID, $DRAW_MAP_ID) {
		$type = explode ( '/', $file ["type"] );
		$result = SvgUtil::createPicPathByBuilding ( MEDIALIB_PATH, $BUILDING_ID );
		$FILE_PATH = $result ["path"];
		$PATH = $result ["dirs"];
		$FILE_NAME_all = $FILE_PATH . '/' . $FILE_NAME . "." . strtoupper ( $type [1] );
		move_uploaded_file ( $file ["tmp_name"], $FILE_NAME_all );
		$Cdao_pic = new CDAOCB_PIC ();
		if ($FLOOR_ID == '') {
			$where = " BUILDING_ID=" . $BUILDING_ID . " and FLOOR_ID is null";
		} elseif ($DRAW_MAP_ID == '') {
			$where = " FLOOR_ID=" . $FLOOR_ID . " and DRAW_MAP_ID is null";
		} else {
			$where = "POI_ID=" . $POI_ID;
		}
		$picinfo = $Cdao_pic->getAllWhere ( "PIC_ID,STATUS", $where );
		if ($picinfo) {
			$picAdd ["PIC_ID"] = $picinfo [0] ['PIC_ID'];
		} else {
			$picAdd ["PIC_ID"] = $Cdao_pic->getNextSeqId ();
			$picAdd ['CREATE_TIME'] = date ( "Y-m-d H:i:s" );
		}
		if ($DRAW_MAP_ID) {
			$picAdd ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
		}
		if ($POI_ID) {
			$picAdd ["POI_ID"] = $POI_ID;
		}
		if ($FLOOR_ID) {
			$picAdd ["FLOOR_ID"] = $FLOOR_ID;
		}
		
		$picAdd ["PIC_TYPE"] = strtoupper ( $type [1] );
		$picAdd ['BUILDING_ID'] = $BUILDING_ID;
		$PIC_TOPIC = $file ["name"];
		$picAdd ['PIC_TOPIC'] = $PIC_TOPIC;
		$picAdd ['PATH'] = $PATH;
		$picAdd ['FILENAME'] = $FILE_NAME;
		$picAdd ['MIME_TYPE'] = $file ["type"];
		$picAdd ['FILESIZE'] = $file ["size"];
		$picAdd ['STATUS'] = "A";
		$updatePic = $Cdao_pic->doEdit ( $picAdd );
		return $picAdd ["PIC_ID"];
	}
	const POITYPECODE = "POI";
	const APTYPECODE = "AP";
	
	/**
	 * 动态生成sql插入语句
	 *
	 * @param unknown $FormList        	
	 * @param unknown $tablename        	
	 * @return NULL
	 */
	public static function getInsertSqlViaFormList($FormList, $tablename, $needCreateTime = true, $needModifyTime = true) {
		if (! empty ( $FormList )) {
			$insertSql = null;
			foreach ( $FormList as $k => $Form ) {
				$insertSqlKey = null;
				$insertSqlValue = null;
				foreach ( $Form as $k => $v ) {
					$v = str_replace ( "'", "''", $v );
					if ($insertSqlKey === null) {
						$insertSqlKey = $k;
						$insertSqlValue = "'{$v}'";
					} else {
						$insertSqlKey = $insertSqlKey . "," . $k;
						$insertSqlValue = $insertSqlValue . ",'{$v}'";
					}
				}
				$currentTime = date ( "Y/m/d H:i:s" );
				if ($needCreateTime) {
					if ($insertSqlKey == null) {
						$insertSqlKey = "CREATE_TIME";
						$insertSqlValue = "'{$currentTime}'";
					} else {
						$insertSqlKey = $insertSqlKey . ",CREATE_TIME";
						$insertSqlValue = $insertSqlValue . ",'{$currentTime}'";
					}
				}
				if ($needModifyTime) {
					if ($insertSqlKey == null) {
						$insertSqlKey = "MOD_TIME";
						$insertSqlValue = "'{$currentTime}'";
					} else {
						$insertSqlKey = $insertSqlKey . ",MOD_TIME";
						$insertSqlValue = $insertSqlValue . ",'{$currentTime}'";
					}
				}
				if ($insertSql == null) {
					$insertSql = "INSERT INTO {$tablename} ({$insertSqlKey}) VALUES ({$insertSqlValue});";
				} else {
					$insertSql = $insertSql . " INSERT INTO {$tablename} ({$insertSqlKey}) VALUES ({$insertSqlValue});";
				}
			}
			return ($insertSql);
		}
		return ("");
	}
	/**
	 * 创建补0的随机数
	 *
	 * @param number $min        	
	 * @param number $max        	
	 * @return string
	 */
	public static function randomAlignNumStr($min = 0, $max = 99) {
		$num = rand ( $min, $max );
		$maxlen = strlen ( $max );
		$numlen = strlen ( $num );
		$count = $maxlen - $numlen;
		$i = 0;
		$zeroStr = "";
		while ( $i < $count ) {
			$zeroStr = $zeroStr . "0";
			++ $i;
		}
		return ($zeroStr . $num);
	}
	/**
	 * 创建随机目录
	 *
	 * @param number $Level        	
	 * @return string
	 */
	public static function createLevelPath($Level = 3) {
		$Path = "";
		$i = 0;
		while ( $i < $Level ) {
			$dir = SvgUtil::randomAlignNumStr ();
			$Path = ($Path == "") ? $dir : ($Path . "/" . $dir);
			++ $i;
		}
		return ($Path);
	}
	/**
	 * 获取场所PIC PATH
	 *
	 * @param unknown $BUILDING_ID        	
	 * @return Ambigous <NULL, string, Ambigous, unknown>
	 */
	public static function getPicPathByBuilding($BUILDING_ID = 0) {
		$CDAO_Pic = new CDAOCB_PIC ();
		$PATH = $CDAO_Pic->getPicPathByWhere ( "BUILDING_ID={$BUILDING_ID}" );
		while ( empty ( $PATH ) ) {
			$PATH = SvgUtil::createLevelPath ();
			if ($CDAO_Pic->isPicPathExist ( $PATH )) {
				$PATH = null;
			}
		}
		return ($PATH);
	}
	/**
	 * 创建多级目录
	 *
	 * @param unknown $path        	
	 */
	public static function mkdirs($root, $dirs) {
		$dir_exists = file_exists ( $root );
		if (! $dir_exists) {
			try {
				$dir_exists = mkdir ( $root );
			} catch ( Exception $e ) {
				$errormsg = "创建目录\"" . $root . "\"失败！";
				if (! empty ( $e )) {
					$errormsg = $errormsg . $e->getMessage ();
				}
				return (array (
						"error" => $errormsg 
				));
			}
		}
		if ($dir_exists) {
			$root = dirname ( $root . DIRECTORY_SEPARATOR . "null" );
			$pathList = explode ( DIRECTORY_SEPARATOR, $dirs );
			foreach ( $pathList as $k => $v ) {
				$root = $root . DIRECTORY_SEPARATOR . $v;
				if (! file_exists ( $root )) {
					try {
						$dir_exists = mkdir ( $root );
					} catch ( Exception $e ) {
						$errormsg = "创建目录\"" . $root . "\"失败！";
						if (! empty ( $e )) {
							$errormsg = $errormsg . $e->getMessage ();
						}
						return (array (
								"error" => $errormsg 
						));
					}
				}
			}
			return (array (
					"path" => $root,
					"dirs" => $dirs 
			));
		} else {
			return (array (
					"error" => "目录\"" . $root . "\"不存在！" 
			));
		}
	}
	/**
	 * 创建场所PIC PATH
	 *
	 * @param unknown $BUILDING_ID        	
	 * @return Ambigous <NULL, string, Ambigous, unknown>
	 */
	public static function createPicPathByBuilding($MedialibPath, $BUILDING_ID) {
		$Dirs = SvgUtil::getPicPathByBuilding ( $BUILDING_ID );
		if (! empty ( $Dirs )) {
			return (SvgUtil::mkdirs ( $MedialibPath, $Dirs ));
		} else {
			return (array (
					"error" => "获取存储取路径失败！" 
			));
		}
	}
	const STATUS_NORMAL = "E";
	const STATUS_CANCEL = "X";
	/**
	 * 下载SVG源代码
	 *
	 * @return string json 平面图属性信息，出错信息
	 */
	public static function getSvg($BUILDING_ID, $FLOOR_ID, $DRAW_MAP_ID = null) {
		$response = array ();
		$error = null;
		{
			$Cdao_Floor = new CDAOCB_FLOOR ();
			if (empty ( $FLOOR_ID )) {
				if (empty ( $BUILDING_ID )) {
					$error = "没有指定场所或楼层！";
				} else {
					$FLOOR_ID = $Cdao_Floor->getFloorIdByBuildId ( $BUILDING_ID );
					if (empty ( $FLOOR_ID )) {
						$error = "指定场所的楼层为空！";
					}
				}
			} else {
				if (empty ( $BUILDING_ID )) {
					$BUILDING_ID = $Cdao_Floor->getBuildIdByFloorId ( $FLOOR_ID );
					if (empty ( $BUILDING_ID )) {
						$error = "找不到指定楼层所在的场所！";
					}
				}
			}
		}
		if (empty ( $error )) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			$DRAW_MAP_ID = $_POST ["DRAW_MAP_ID"];
			if (empty ( $DRAW_MAP_ID )) {
				$DRAW_MAP_ID = $Cdao_DrawMap->getSvgDrawMapId ( $FLOOR_ID );
			}
			if (! empty ( $DRAW_MAP_ID )) {
				$row = $Cdao_DrawMap->getRow ( $DRAW_MAP_ID );
				$response ["FLOOR_ID"] = $FLOOR_ID;
				$response ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
				$response ["DW_UNIT"] = $row ["DW_UNIT"];
				$response ["DW_SCALE"] = $row ["DW_SCALE"];
				$response ["STATUS"] = $row ["STATUS"];
				$response ["DM_TOPIC"] = $row ["DM_TOPIC"];
				$cdict = new CDict ();
				$response ["STATUSNAME"] = $cdict->BUILD_STATUS [$response ["STATUS"]];
				$dom = new DomDocument ();
				$svg = $dom->createElement ( "svg" );
				$dom->appendChild ( $svg );
				$width = $dom->createAttribute ( "width" );
				$svg->appendChild ( $width );
				$width_value = $dom->createTextNode ( ($row ["DW_UNIT"] == "px") ? $row ["WIDTH"] : ($row ["WIDTH"] . $row ["DW_UNIT"]) );
				$width->appendChild ( $width_value );
				$height = $dom->createAttribute ( "height" );
				$svg->appendChild ( $height );
				$height_value = $dom->createTextNode ( ($row ["DW_UNIT"] == "px") ? $row ["HEIGHT"] : ($row ["HEIGHT"] . $row ["DW_UNIT"]) );
				$height->appendChild ( $height_value );
				$xmlns = $dom->createAttribute ( "xmlns" );
				$svg->appendChild ( $xmlns );
				$xmlns_value = $dom->createTextNode ( "http://www.w3.org/2000/svg" );
				$xmlns->appendChild ( $xmlns_value );
				$xmlns = $dom->createAttribute ( "xmlns:xlink" );
				$svg->appendChild ( $xmlns );
				$xmlns_value = $dom->createTextNode ( "http://www.w3.org/1999/xlink" );
				$xmlns->appendChild ( $xmlns_value );
				$Cdao_PlaneLayer = new CDAODM_PLANE_LAYER ();
				$Cdao_PlaneLayerElement = new CDAODM_LAYER_ELEMENT ();
				$Cdao_PlaneLayerElementAttr = new CDAODM_LAYER_ELEMENT_ATTR ();
				$LayerFormList = $Cdao_PlaneLayer->getList ( "{$Cdao_DrawMap->_table_seq_name} ={$DRAW_MAP_ID} AND STATUS='" . SvgUtil::STATUS_NORMAL . "' ORDER BY L_ORDER" );
				$ElementFormList = $Cdao_PlaneLayerElement->getList ( "{$Cdao_DrawMap->_table_seq_name} ={$DRAW_MAP_ID} AND STATUS='" . SvgUtil::STATUS_NORMAL . "' ORDER BY ELEMENT_ID" );
				$LayerElementFormList = array ();
				foreach ( $ElementFormList as $ElementForm ) {
					if ($LayerElementFormList [$ElementForm ["LAYER_ID"]] === null) {
						$LayerElementFormList [$ElementForm ["LAYER_ID"]] = array ();
					}
					array_push ( $LayerElementFormList [$ElementForm ["LAYER_ID"]], $ElementForm );
				}
				$ElementAttrFormList = $Cdao_PlaneLayerElementAttr->getList ( "{$Cdao_DrawMap->_table_seq_name} ={$DRAW_MAP_ID}" );
				$LayerElementAttrFormList = array ();
				if (! empty ( $ElementAttrFormList )) {
					foreach ( $ElementAttrFormList as $ElementAttrForm ) {
						if ($LayerElementAttrFormList [$ElementAttrForm ["ELEMENT_ID"]] === null) {
							$LayerElementAttrFormList [$ElementAttrForm ["ELEMENT_ID"]] = array ();
						}
						array_push ( $LayerElementAttrFormList [$ElementAttrForm ["ELEMENT_ID"]], $ElementAttrForm );
					}
				}
				$LayerTypeList = array ();
				if (! empty ( $LayerFormList )) {
					foreach ( $LayerFormList as $LayerForm ) {
						$layerId = $LayerForm [$Cdao_PlaneLayer->_table_seq_name];
						$g = $dom->createElement ( "g" );
						$svg->appendChild ( $g );
						$title = $dom->createElement ( "title" );
						$g->appendChild ( $title );
						$title_value = $dom->createTextNode ( iconv ( 'GBK', 'UTF-8', $LayerForm ["LAYER_TOPIC"] ) );
						$title->appendChild ( $title_value );
						$type = $dom->createElement ( "desc" );
						$g->appendChild ( $type );
						$type_value = $dom->createTextNode ( $LayerForm ["LAYER_TYPE"] );
						$type->appendChild ( $type_value );
						$LayerIdElementFormList = $LayerElementFormList [$LayerForm ["LAYER_ID"]];
						if (! empty ( $LayerIdElementFormList )) {
							foreach ( $LayerIdElementFormList as $LayerElementForm ) {
								$element = $dom->createElement ( strtolower ( $LayerElementForm ["ELEMENT_TYPE"] ) );
								$g->appendChild ( $element );
								if (! empty ( $LayerElementForm ["ELEMENT_TEXT"] )) {
									$element_html = $dom->createTextNode ( iconv ( 'GBK', 'UTF-8', $LayerElementForm ["ELEMENT_TEXT"] ) );
									$element->appendChild ( $element_html );
								}
								$LayerElementIdAttrFormList = $LayerElementAttrFormList [$LayerElementForm ["ELEMENT_ID"]];
								if (! empty ( $LayerElementIdAttrFormList )) {
									foreach ( $LayerElementIdAttrFormList as $LayerElementAttrForm ) {
										$element_attr = $dom->createAttribute ( strtolower ( $LayerElementAttrForm ["ATTR_NAME"] ) );
										$element->appendChild ( $element_attr );
										$element_attr_value = $dom->createTextNode ( $LayerElementAttrForm ["ATTR_VALUE"] );
										$element_attr->appendChild ( $element_attr_value );
									}
								}
							}
						}
						$LayerTypeList [$LayerForm ["LAYER_TYPE"]] = "1";
					}
				}
				$SVGSRC = $dom->saveXML ();
				$response ["SVGSRC"] = iconv ( 'UTF-8', 'GBK', $SVGSRC );
				// 取图层
				$response ["LayerList"] = $LayerTypeList;
			} else {
				$error = "此楼层不存在SVG图片！";
			}
		}
		if (! empty ( $error )) {
			$response ["error"] = $error;
		}
		return ($response);
	}
}
?>