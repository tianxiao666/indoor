<?php
/**
 * 客户端口接口
 * @author xiang.zc
 *
 */
class ServiceClientInterfaceController extends AdminController {
	var $STATUS_NORMAL = "E";
	var $STATUS_CANCEL = "X";
	public function __construct() {
		parent::__construct();
	}

	public function actionIndex() {
	}

	/**
	 * 获取场所列表
	 */
	public function actiongetNearbyIndoormapBuildingList() {
		$jsonArrayObj = json_decode($_POST["jsonArrayObj"]);
		//$jsonArrayObj->BUILDING_TYPE_ID;
		//$jsonArrayObj->LONGITUDE;
		//$jsonArrayObj->LATITUDE;
		//$jsonArrayObj->RANGE;
		$Cdao_Building = new CDAOCB_BUILDING();
		$buildinginfoList = $Cdao_Building -> getAllBuilding();
		$response = array();
		$distance = 0;
		foreach ($buildinginfoList as $k => $v) {
			++$distance;
			$buildinginfo = array();
			$buildinginfo["BUILDING_ID"] = $k;
			$buildinginfo["BUILDING_NAME"] = iconv('GBK', 'UTF-8', $v);
			$buildinginfo["DISTANCE"] = $distance;
			array_push($response, $buildinginfo);
		}
		echo json_encode($response);
	}

	/**
	 * 获取楼层列表
	 */
	public function actiongetIndoormapBuildingFloorList() {
		$jsonArrayObj = json_decode($_POST["jsonArrayObj"]);
		$BUILDING_ID = $jsonArrayObj -> BUILDING_ID;
		$Cdao_Floor = new CDAOCB_FLOOR();
		$FloorList = $Cdao_Floor -> getAllFloor($BUILDING_ID);
		$response = array();
		foreach ($FloorList as $key => $value) {
			$floorinfo = array();
			$floorinfo["FLOOR_ID"] = $key;
			$floorinfo["FLOOR_NAME"] = iconv('GBK', 'UTF-8', $value);
			array_push($response, $floorinfo);
		}
		echo(json_encode($response));
	}

	/**
	 *获取楼层SVG
	 */
	public function actiongetIndoormapBuildingFloorSvg() {
		$jsonArrayObj = json_decode($_POST["jsonArrayObj"]);
		$BUILDING_ID = $jsonArrayObj -> BUILDING_ID;
		$FLOOR_ID = $jsonArrayObj -> FLOOR_ID;
		$Cdao_DrawMap = new CDAODM_DRAW_MAP();
		$DRAW_MAP_ID = $Cdao_DrawMap -> getEditableSeqIdByFloorId($FLOOR_ID);
		echo($this -> getSvg($BUILDING_ID, $FLOOR_ID, $DRAW_MAP_ID));
	}

	public function actiongetLocation() {
		$BUILDING_ID = $_POST["BUILDING_ID"];

		$return = array();
		$return["x"] = 3;
		$return["y"] = 3;
		$return["width"] = "629";
		$return["height"] = "503";
		$return["region"] = "region";
		$return["photo_url"] = "http://192.168.243.185:8838/indoor/trunk/indooradmin/medialib/SVG/PNG/01/89/95/%E9%87%91%E5%B1%B1%E5%A4%A7%E5%8E%A6_%E5%8D%97%E5%A1%941F_20131128173619.PNG";
		$response["return"] = $return;
		echo json_encode($response);
	}

	public function actiongetIndoormapBuildingFloorList1() {
		$Cdao_Building = new CDAOCB_BUILDING();
		$buildinginfoList = $Cdao_Building -> getAllBuildingUtf8();
		$response = array();
		foreach ($buildinginfoList as $k => $v) {
			$buildinginfo = array();
			$buildinginfo["BULDING_ID"] = $k;
			$buildinginfo["BULDING_NAME"] = $v;
			$buildinginfo["DISTANCE"] = "30";
			array_push($response, $buildinginfo);
		}
		echo json_encode($response);
	}

	public function actiongetSvg() {
		$jsonArrayObj = json_decode($_POST["jsonArrayObj"]);
		$BUILDING_ID = $jsonArrayObj -> BUILDING_ID;
		$FLOOR_ID = $jsonArrayObj -> FLOOR_ID;
		$DRAW_MAP_ID = $jsonArrayObj -> DRAW_MAP_ID;
		echo($this -> getSvg($BUILDING_ID, $FLOOR_ID, $DRAW_MAP_ID));
	}

	public function getSvgDrawMapId($FLOOR_ID) {
		$Cdao_DrawMap = new CDAODM_DRAW_MAP();
		return ($Cdao_DrawMap -> getNormalSeqIdByFloorId($FLOOR_ID));
	}

	public function getSvg($BUILDING_ID, $FLOOR_ID, $DRAW_MAP_ID) {
		$array = array();
		if (!empty($FLOOR_ID)) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP();
			if (empty($DRAW_MAP_ID)) {
				$DRAW_MAP_ID = $this -> getSvgDrawMapId($FLOOR_ID);
			}
			if (!empty($DRAW_MAP_ID)) {
				if (empty($BUILDING_ID)) {
					$Cdao_Floor = new CDAOCB_FLOOR();
					$BUILDING_ID = $Cdao_Floor -> getBuildIdByFloorId($FLOOR_ID);
				}
				$row = $Cdao_DrawMap -> getRow($DRAW_MAP_ID);
				$array["VIEWBOX_WIDTH"] = $row["VIEWBOX_WIDTH"];
				$array["VIEWBOX_HEIGHT"] = $row["VIEWBOX_HEIGHT"];
				$array["FLOOR_WIDTH"] = $row["FLOOR_WIDTH"];
				$array["FLOOR_HEIGHT"] = $row["FLOOR_HEIGHT"];
				$array["BACKGROUD_COLOR"] = $row["BACKGROUD_COLOR"];
				$array["DW_UNIT"] = $row["DW_UNIT"];
				$array["DW_SCALE"] = $row["DW_SCALE"];
				$array["STATUS"] = $row["STATUS"];
				$array["DM_TOPIC"] = $DM_TOPIC = iconv('GBK', 'UTF-8', $row["DM_TOPIC"]);
				$cdict = new CDict();
				$array["STATUSNAME"] = iconv('GBK', 'UTF-8', $cdict -> BUILD_STATUS[$array["STATUS"]]);
				$dom = new DomDocument();
				$svg = $dom -> createElement("svg");
				$dom -> appendChild($svg);
				$width = $dom -> createAttribute("width");
				$svg -> appendChild($width);
				$width_value = $dom -> createTextNode(($row["DW_UNIT"] == "px") ? $row["VIEWBOX_WIDTH"] : ($row["VIEWBOX_WIDTH"] . $row["DW_UNIT"]));
				$width -> appendChild($width_value);
				$height = $dom -> createAttribute("height");
				$svg -> appendChild($height);
				$height_value = $dom -> createTextNode(($row["DW_UNIT"] == "px") ? $row["VIEWBOX_HEIGHT"] : ($row["VIEWBOX_HEIGHT"] . $row["DW_UNIT"]));
				$height -> appendChild($height_value);
				$xmlns = $dom -> createAttribute("xmlns");
				$svg -> appendChild($xmlns);
				$xmlns_value = $dom -> createTextNode("http://www.w3.org/2000/svg");
				$xmlns -> appendChild($xmlns_value);
				$xmlns = $dom -> createAttribute("xmlns:xlink");
				$svg -> appendChild($xmlns);
				$xmlns_value = $dom -> createTextNode("http://www.w3.org/1999/xlink");
				$xmlns -> appendChild($xmlns_value);

				$Cdao_PlaneLayer = new CDAODM_PLANE_LAYER();
				$Cdao_PlaneLayerElement = new CDAODM_LAYER_ELEMENT();
				$Cdao_PlaneLayerElementPoint = new CDAODM_LAYER_POINT();
				$PLANE_LAYER_LIST_LIST = $Cdao_PlaneLayer -> getList("{$Cdao_DrawMap->_table_seq_name} ={$DRAW_MAP_ID} AND STATUS='{$this->STATUS_NORMAL}' ORDER BY L_ORDER");
				foreach ($PLANE_LAYER_LIST_LIST as $k => $PLANE_LAYER_LIST) {
					$layerId = $PLANE_LAYER_LIST[$Cdao_PlaneLayer -> _table_seq_name];
					$g = $dom -> createElement("g");
					$svg -> appendChild($g);
					$title = $dom -> createElement("title");
					$g -> appendChild($title);
					$PLANE_LAYER_LIST["LAYER_TOPIC"] = iconv('GBK', 'UTF-8', $PLANE_LAYER_LIST["LAYER_TOPIC"]);
					$title_value = $dom -> createTextNode($PLANE_LAYER_LIST["LAYER_TOPIC"]);
					$title -> appendChild($title_value);

					$type = $dom -> createElement("desc");
					$g -> appendChild($type);
					$type_value = $dom -> createTextNode($PLANE_LAYER_LIST["LAYER_TYPE"]);
					$type -> appendChild($type_value);
					$PLANE_LAYER_ELEMENT_LIST_LIST = $Cdao_PlaneLayerElement -> getList("{$Cdao_PlaneLayer->_table_seq_name} ={$layerId}  AND STATUS='{$this->STATUS_NORMAL}'");
					foreach ($PLANE_LAYER_ELEMENT_LIST_LIST as $k => $PLANE_LAYER_ELEMENT_LIST) {
						$ELEMENT_TYPE = $PLANE_LAYER_ELEMENT_LIST["ELEMENT_TYPE"];
						$element = $dom -> createElement(strtolower($ELEMENT_TYPE));
						$g -> appendChild($element);
						$element_attr = $dom -> createAttribute("id");
						$element -> appendChild($element_attr);
						$element_attr_value = $dom -> createTextNode("svg_" . $PLANE_LAYER_ELEMENT_LIST["ELEMENT_ID"]);
						$element_attr -> appendChild($element_attr_value);

						if ($PLANE_LAYER_ELEMENT_LIST["STROKE_WIDTH"] !== null) {
							$element_attr = $dom -> createAttribute("stroke-width");
							$element -> appendChild($element_attr);
							$element_attr_value = $dom -> createTextNode($PLANE_LAYER_ELEMENT_LIST["STROKE_WIDTH"]);
							$element_attr -> appendChild($element_attr_value);
						}
						if ($PLANE_LAYER_ELEMENT_LIST["STROKE_COLOR"] !== null) {
							$element_attr = $dom -> createAttribute("stroke");
							$element -> appendChild($element_attr);
							$element_attr_value = $dom -> createTextNode($PLANE_LAYER_ELEMENT_LIST["STROKE_COLOR"]);
							$element_attr -> appendChild($element_attr_value);
						}
						if ($PLANE_LAYER_ELEMENT_LIST["FILL_COLOR"] !== null) {
							$element_attr = $dom -> createAttribute("fill");
							$element -> appendChild($element_attr);
							$element_attr_value = $dom -> createTextNode($PLANE_LAYER_ELEMENT_LIST["FILL_COLOR"]);
							$element_attr -> appendChild($element_attr_value);
						}
						if ($PLANE_LAYER_ELEMENT_LIST["ELEMENT_OPACITY"] !== null) {
							$element_attr = $dom -> createAttribute("opacity");
							$element -> appendChild($element_attr);
							$element_attr_value = $dom -> createTextNode($PLANE_LAYER_ELEMENT_LIST["ELEMENT_OPACITY"] / 100);
							$element_attr -> appendChild($element_attr_value);
						}
						if ($PLANE_LAYER_ELEMENT_LIST["FILL_OPACITY"] !== null) {
							$element_attr = $dom -> createAttribute("fill-opacity");
							$element -> appendChild($element_attr);
							$element_attr_value = $dom -> createTextNode($PLANE_LAYER_ELEMENT_LIST["FILL_OPACITY"] / 100);
							$element_attr -> appendChild($element_attr_value);
						}
						if ($PLANE_LAYER_ELEMENT_LIST["STROKE_OPACITY"] !== null) {
							$element_attr = $dom -> createAttribute("stroke-opacity");
							$element -> appendChild($element_attr);
							$element_attr_value = $dom -> createTextNode($PLANE_LAYER_ELEMENT_LIST["STROKE_OPACITY"] / 100);
							$element_attr -> appendChild($element_attr_value);
						}
						$ELEMENT_ID = $PLANE_LAYER_ELEMENT_LIST["ELEMENT_ID"];
						$PLANE_LAYER_ELEMENT_POINT_LIST_LIST = $Cdao_PlaneLayerElementPoint -> getList("{$Cdao_PlaneLayerElement->_table_seq_name}={$ELEMENT_ID}  AND STATUS='{$this->STATUS_NORMAL}' ORDER BY L_ORDER");
						$ELEMENT_ID_LIST_ID = SvgUtil::getSvgElementId($ELEMENT_TYPE);
						if ($ELEMENT_ID_LIST_ID !== null) {
							$layerelementpointorder = 0;
							$SvgElementCoordAttrList = SvgUtil::getSvgElementCoordAttrList($ELEMENT_ID_LIST_ID);
							if (!empty($SvgElementCoordAttrList)) {
								foreach ($SvgElementCoordAttrList as $X => $Y) {
									$PLANE_LAYER_ELEMENT_POINT_LIST = $PLANE_LAYER_ELEMENT_POINT_LIST_LIST[$layerelementpointorder];
									if ($PLANE_LAYER_ELEMENT_POINT_LIST !== null) {
										if (($X !== null) && ($PLANE_LAYER_ELEMENT_POINT_LIST["POSITION_X"] !== null)) {
											$element_attr = $dom -> createAttribute(strtolower($X));
											$element -> appendChild($element_attr);
											$element_attr_value = $dom -> createTextNode($PLANE_LAYER_ELEMENT_POINT_LIST["POSITION_X"]);
											$element_attr -> appendChild($element_attr_value);
										}
										if (($Y != null) && $PLANE_LAYER_ELEMENT_POINT_LIST["POSITION_Y"] !== null) {
											$element_attr = $dom -> createAttribute(strtolower($Y));
											$element -> appendChild($element_attr);
											$element_attr_value = $dom -> createTextNode($PLANE_LAYER_ELEMENT_POINT_LIST["POSITION_Y"]);
											$element_attr -> appendChild($element_attr_value);
										}
									}
									++$layerelementpointorder;
								}
							}
							switch ($ELEMENT_ID_LIST_ID) {
								case SvgUtil::SVG_ELEMENT_ID_POLYGON :
								case SvgUtil::SVG_ELEMENT_ID_POLYLINE :
									$POINTS = null;
									foreach ($PLANE_LAYER_ELEMENT_POINT_LIST_LIST as $k => $PLANE_LAYER_ELEMENT_POINT_LIST) {
										$x = $PLANE_LAYER_ELEMENT_POINT_LIST["POSITION_X"];
										$y = $PLANE_LAYER_ELEMENT_POINT_LIST["POSITION_Y"];
										if ($POINTS === null) {
											$POINTS = "{$x},{$y}";
										} else {
											$POINTS = $POINTS . " {$x},{$y}";
										}
									}
									if (!empty($POINTS)) {
										$element_attr = $dom -> createAttribute("points");
										$element -> appendChild($element_attr);
										$element_attr_value = $dom -> createTextNode($POINTS);
										$element_attr -> appendChild($element_attr_value);
									}
									break;
								case SvgUtil::SVG_ELEMENT_ID_PATH :
									foreach ($PLANE_LAYER_ELEMENT_POINT_LIST_LIST as $k => $PLANE_LAYER_ELEMENT_POINT_LIST) {
										$POINTS = null;
										foreach ($PLANE_LAYER_ELEMENT_POINT_LIST_LIST as $k => $PLANE_LAYER_ELEMENT_POINT_LIST) {
											$x = $PLANE_LAYER_ELEMENT_POINT_LIST["POSITION_X"];
											$y = $PLANE_LAYER_ELEMENT_POINT_LIST["POSITION_Y"];
											$POSITION = strtolower($PLANE_LAYER_ELEMENT_POINT_LIST["POSITION"]);
											if ($POSITION !== null) {
												if ($POSITION == "x") {
													$POSITION = " ";
												}
												$Coordinate = ($POSITION == "z") ? "" : "{$x},{$y}";
												$POINTS = ($POINTS === null) ? "{$POSITION}{$Coordinate}" : $POINTS . "{$POSITION}{$Coordinate}";
											}
										}
										if (!empty($POINTS)) {
											$element_attr = $dom -> createAttribute("d");
											$element -> appendChild($element_attr);
											$element_attr_value = $dom -> createTextNode($POINTS);
											$element_attr -> appendChild($element_attr_value);
										}
									}
									break;
								case SvgUtil::SVG_ELEMENT_ID_TEXT :
									if ($PLANE_LAYER_ELEMENT_LIST["FONT_SIZE"] !== null) {
										$element_attr = $dom -> createAttribute("font-size");
										$element -> appendChild($element_attr);
										$element_attr_value = $dom -> createTextNode($PLANE_LAYER_ELEMENT_LIST["FONT_SIZE"]);
										$element_attr -> appendChild($element_attr_value);
									}
									if ($PLANE_LAYER_ELEMENT_LIST["ELEMENT_TEXT"] !== null) {
										$element_html = $dom -> createTextNode(iconv('GBK', 'UTF-8', $PLANE_LAYER_ELEMENT_LIST["ELEMENT_TEXT"]));
										$element -> appendChild($element_html);
									}
									break;
								case SvgUtil::SVG_ELEMENT_ID_IMAGE :
									if ($PLANE_LAYER_ELEMENT_LIST["ELEMENT_TEXT"] !== null) {
										$element_attr = $dom -> createAttribute("xlink:href");
										$element -> appendChild($element_attr);
										$element_attr_value = $dom -> createTextNode(urldecode($PLANE_LAYER_ELEMENT_LIST["ELEMENT_TEXT"]));
										$element_attr -> appendChild($element_attr_value);
									}
									break;
							}
						}
					}
				}
				$SVGSRC = $dom -> saveXML();
				$array["SVGSRC"] = iconv('GBK', 'UTF-8', $SVGSRC);
			} else {
				$array["error"] = iconv('GBK', 'UTF-8', "此楼层不存在SVG图片！");
			}
		} else {
			$array["error"] = iconv('GBK', 'UTF-8', "楼层为空，无法找到相应的SVG图片！");
		}
		return (json_encode($array));
	}

}
?>