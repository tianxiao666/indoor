<?php
/**
 * SVG管理:显示svg.html界面，从数据库获取数据和提交数据到数据库
 * @author xiang.zc
 *
 */
class SvgMgrController extends AdminController {
	var $STATUS_NORMAL = "E";
	var $STATUS_CANCEL = "X";
	public function __construct() {
		parent::__construct ();
	}
	/**
	 * 显示绘制平面图界面
	 *
	 * @see AdminController::actionIndex()
	 */
	public function actionIndex() {
		$BUILDING_ID = $_GET ["BUILDING_ID"];
		$BUILDING_NAME = $_GET ["BUILDING_NAME"];
		$FLOOR_ID = $_GET ["FLOOR_ID"];
		$DRAW_MAP_ID = $_GET ["DRAW_MAP_ID"];
		
		$Cdao_Type = new CDAOSYS_TYPE_CODE ();
		$typeList = $Cdao_Type->getAllType ();
		$typeOption = "";
		$typeArray = array ();
		if (! empty ( $typeList )) {
			foreach ( $typeList as $k => $v ) {
				$typeOption = $typeOption . "<option id=\\'typecode_" . $v ['CODE_TYPE'] . "\\' value=\\'" . $v ['CODE_TYPE'] . "\\'>" . $v ['CODE_NAME'] . "</option>";
				$typeArray [$v ['CODE_TYPE']] = iconv ( 'GBK', 'UTF-8', $v ['CODE_NAME'] );
			}
		} else {
			$typeOption = "<option value=\\'\\'>无类别</option>";
		}
		$a = "-请选择-";
		$layerList = "<li onmouseover=\\'showSecondMenu(this);\\' class=\\'\\' id=\\'\\' onchange=\\'newLayer(this);\\'><select><option id=\\'\\' value=\\'\\'>-请选择-</option>" . $typeOption . "</select><div class=\\'tool_sep\\' style=\\'float:right;\\'></div><div class=\\'\\' style=\\'color:#000000;float:right;\\'><a href=\\'#\\' onclick=\\'delLayer(this);\\'>-</a></div></li>";
		$layerSel = "toilet";
		$pageData ["layerSel"] = $layerSel;
		$pageData ["layerList"] = $layerList;
		$pageData ["typeOption"] = $typeOption;
		$pageData ["typeList"] = $typeList;
		$pageData ["typeJson"] = json_encode ( $typeArray );
		$Cdao_Building = new CDAOCB_BUILDING ();
		$buildingList = $Cdao_Building->getAllBuilding ();
		$pageData ["buildingList"] = $buildingList;
		
		if (! empty ( $BUILDING_ID )) {
			$Cdao_Floor = new CDAOCB_FLOOR ();
			$floorList = $Cdao_Floor->getAllFloor ( $BUILDING_ID );
			$pageData ["floorList"] = $floorList;
			$pageData ["BUILDING_ID"] = $BUILDING_ID;
			
			if (empty ( $BUILDING_NAME )) {
				$BUILDING_NAME = $Cdao_Building->getSeqNameBySeqId ( $BUILDING_ID );
				if (empty ( $BUILDING_NAME )) {
					$BUILDING_NAME = "无法显示场所名(ID:{$BUILDING_ID})";
				}
			}
			$pageData ["BUILDING_NAME"] = $BUILDING_NAME;
		}
		
		if (! empty ( $DRAW_MAP_ID )) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			$PlanegraphInfo = $Cdao_DrawMap->getRow ( $DRAW_MAP_ID );
			$pageData ["PlanegraphInfo"] = $PlanegraphInfo;
			$pageData ["FLOOR_ID"] = $PlanegraphInfo ["FLOOR_ID"];
			$pageData ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
		}
		if (! empty ( $FLOOR_ID )) {
			$CDAO_Ap = new CDAOCB_LOCATION ();
			$AP_List = $CDAO_Ap->getApList ( "where FLOOR_ID={$FLOOR_ID}" );
			$pageData ["FLOOR_WIDTH"] = 0;
			$pageData ["FLOOR_HEIGHT"] = 0;
			$Cdao_Floor = new CDAOCB_FLOOR ();
			$row = $Cdao_Floor->getFloorData ( $FLOOR_ID );
			if (! empty ( $row )) {
				$pageData ["FLOOR_WIDTH"] = $row [0] ["FLOOR_WIDTH"];
				$pageData ["FLOOR_HEIGHT"] = $row [0] ["FLOOR_HEIGHT"];
			}
			$pageData ["FLOOR_ID"] = $FLOOR_ID;
		}
		$cdict = new CDict ();
		$pageData ["DEFAULT_PLANEGRAPH"] = json_encode ( $cdict->DEFAULT_PLANEGRAPH );
		$pageData ["BUILD_STATUS"] = $cdict->BUILD_STATUS;
		$pageData ["PLANEGRAPH_UNIT"] = $cdict->PLANEGRAPH_UNIT;
		$this->render ( "svg", $pageData );
	}
	/**
	 * 获取楼层列表
	 *
	 * @return string 含有楼层列表的html
	 */
	public function actionAjaxFloorList() {
		$BUILDING_ID = $_POST ["BUILDING_ID"];
		$floorOption = "";
		if (! empty ( $BUILDING_ID )) {
			$Cdao_Floor = new CDAOCB_FLOOR ();
			$floorList = $Cdao_Floor->getAllFloor ( $BUILDING_ID );
			if (! empty ( $floorList )) {
				$floorOption = "<option value=''>--请选择--</option>";
				foreach ( $floorList as $k => $v ) {
					$floorOption = $floorOption . "<option value=$k>$v</option>";
				}
			} else {
				$floorOption = "<option value=''>无楼层</option>";
			}
		} else {
			$floorOption = "<option value=''>无楼层</option>";
		}
		$floorOption = iconv ( 'GBK', 'UTF-8', $floorOption );
		$array = array (
				"html" => $floorOption 
		);
		echo json_encode ( $array );
	}
	/**
	 * 生成一个数组，其元素为库表DM_LAYER_POINT里的元素
	 *
	 * @param number $PlaneLayerElementPointSeqId        	
	 * @param number $PlaneLayerSeqId        	
	 * @param number $FLOOR_ID        	
	 * @param number $PlaneLayerElementSeqId        	
	 * @param number $POSITION_X        	
	 * @param number $POSITION_Y        	
	 * @param number $L_ORDER        	
	 * @param string $POSITION        	
	 * @return array
	 */
	public function genPLANE_LAYER_ELEMENT_POINT_LIST($DRAW_MAP_ID, $PlaneLayerElementPointSeqId, $PlaneLayerSeqId, $FLOOR_ID, $PlaneLayerElementSeqId, $POSITION_X, $POSITION_Y, $L_ORDER, $POSITION) {
		$PLANE_LAYER_ELEMENT_POINT_LIST ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
		$PLANE_LAYER_ELEMENT_POINT_LIST ["DM_POINT_ID"] = $PlaneLayerElementPointSeqId;
		$PLANE_LAYER_ELEMENT_POINT_LIST ["LAYER_ID"] = $PlaneLayerSeqId;
		$PLANE_LAYER_ELEMENT_POINT_LIST ["FLOOR_ID"] = $FLOOR_ID;
		$PLANE_LAYER_ELEMENT_POINT_LIST ["ELEMENT_ID"] = $PlaneLayerElementSeqId;
		$PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_X"] = $POSITION_X;
		$PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_Y"] = $POSITION_Y;
		if (empty ( $PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_X"] )) {
			$PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_X"] = 0;
		}
		if (empty ( $PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_Y"] )) {
			$PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_Y"] = 0;
		}
		$PLANE_LAYER_ELEMENT_POINT_LIST ["L_ORDER"] = $L_ORDER;
		$PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION"] = $POSITION;
		$PLANE_LAYER_ELEMENT_POINT_LIST ["STATUS"] = $this->STATUS_NORMAL;
		return ($PLANE_LAYER_ELEMENT_POINT_LIST);
	}
	/**
	 * 上传SVG源代码
	 *
	 * @return string json 出错信息
	 */
	public function actionAjaxUploadSvgAndSavePng() {
		$BUILDING_ID = $_POST ["BUILDING_ID"];
		$FLOOR_ID = $_POST ["FLOOR_ID"];
		$DRAW_MAP_ID = $_POST ["DRAW_MAP_ID"];
		$FLOOR_WIDTH = $_POST ["FLOOR_WIDTH"];
		$FLOOR_HEIGHT = $_POST ["FLOOR_HEIGHT"];
		$DW_SCALE = $_POST ["DW_SCALE"];
		$BACKGROUD_COLOR = $_POST ["BACKGROUD_COLOR"];
		$SVGSRC = $_POST ["SVGSRC"];
		$PNGBASE64 = $_POST ["PNGBASE64"];
		$json = array ();
		if (! empty ( $FLOOR_ID ) && ! empty ( $SVGSRC )) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			$existSvg = true;
			if (empty ( $DRAW_MAP_ID )) {
				$DRAW_MAP_ID = $Cdao_DrawMap->getNextSeqId ();
				$existSvg = false;
			}
			if (empty ( $BUILDING_ID ) || empty ( $FLOOR_WIDTH ) || empty ( $FLOOR_HEIGHT )) {
				$Cdao_Floor = new CDAOCB_FLOOR ();
				$row = $Cdao_Floor->getFloorData ( $FLOOR_ID );
				if (empty ( $row )) {
					$BUILDING_ID = 0;
					$FLOOR_WIDTH = 0;
					$FLOOR_HEIGHT = 0;
				} else {
					$BUILDING_ID = $row [0] ["BUILDING_ID"];
					$FLOOR_WIDTH = $row [0] ["FLOOR_WIDTH"];
					$FLOOR_HEIGHT = $row [0] ["FLOOR_HEIGHT"];
				}
			}
			$xml_parser = xml_parser_create ();
			xml_parse_into_struct ( $xml_parser, $SVGSRC, $values, $indexs );
			xml_parser_free ( $xml_parser );
			$DRAW_MAP_LIST = array ();
			$svgtag = "SVG";
			$svgindexlist = $indexs [$svgtag];
			foreach ( $svgindexlist as $k => $v ) {
				$svgAttr = $values [$v] ["attributes"];
				if (! empty ( $svgAttr )) {
					$DRAW_MAP_LIST ["VIEWBOX_WIDTH"] = $svgAttr ["WIDTH"];
					$DRAW_MAP_LIST ["VIEWBOX_HEIGHT"] = $svgAttr ["HEIGHT"];
					break;
				}
			}
			$DRAW_MAP_LIST ["BUILDING_ID"] = $BUILDING_ID;
			$DRAW_MAP_LIST ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
			$DRAW_MAP_LIST ["FLOOR_ID"] = $FLOOR_ID;
			$DM_TOPIC = $_POST ["DM_TOPIC"];
			if (! empty ( $DM_TOPIC )) {
				$DM_TOPIC = iconv ( 'UTF-8', 'GBK', $DM_TOPIC );
			}
			$DRAW_MAP_LIST ["DM_TOPIC"] = empty ( $DM_TOPIC ) ? "SVGTOPIC" : $DM_TOPIC;
			$DRAW_MAP_LIST ["DM_NOTE"] = "NOTE";
			
			if (empty ( $DRAW_MAP_LIST ["VIEWBOX_WIDTH"] )) {
				$DRAW_MAP_LIST ["VIEWBOX_WIDTH"] = 0;
			}
			if (empty ( $DRAW_MAP_LIST ["VIEWBOX_HEIGHT"] )) {
				$DRAW_MAP_LIST ["VIEWBOX_HEIGHT"] = 0;
			}
			$cdict = new CDict ();
			// further
			$STATUS = $_POST ["STATUS"];
			if (empty ( $STATUS )) {
				$STATUS = $cdict->DEFAULT_PLANEGRAPH ["STATUS"];
			}
			if (empty ( $BACKGROUD_COLOR )) {
				$BACKGROUD_COLOR = $cdict->DEFAULT_PLANEGRAPH ["BACKGROUD_COLOR"];
			}
			if (empty ( $DW_SCALE )) {
				$DW_SCALE = $cdict->DEFAULT_PLANEGRAPH ["DW_SCALE"];
			}
			$DW_UNIT = $_POST ["DW_UNIT"];
			if (empty ( $DW_UNIT )) {
				$DW_UNIT = $cdict->DEFAULT_PLANEGRAPH ["DW_UNIT"];
			}
			$DRAW_MAP_LIST ["VIEWBOX_WIDTH"] = str_replace ( $DW_UNIT, "", $DRAW_MAP_LIST ["VIEWBOX_WIDTH"] );
			$DRAW_MAP_LIST ["VIEWBOX_HEIGHT"] = str_replace ( $DW_UNIT, "", $DRAW_MAP_LIST ["VIEWBOX_HEIGHT"] );
			$DRAW_MAP_LIST ["BACKGROUD_COLOR"] = $BACKGROUD_COLOR;
			$DRAW_MAP_LIST ["STATUS"] = $STATUS;
			$DRAW_MAP_LIST ["FLOOR_WIDTH"] = $FLOOR_WIDTH;
			$DRAW_MAP_LIST ["FLOOR_HEIGHT"] = $FLOOR_HEIGHT;
			$DRAW_MAP_LIST ["DW_SCALE"] = $DW_SCALE;
			$DRAW_MAP_LIST ["DW_UNIT"] = $DW_UNIT;
			
			$PLANE_LAYER_LIST_LIST = array ();
			$PLANE_LAYER_LIST = null;
			$layertag = "G";
			$layerindexlist = $indexs [$layertag];
			foreach ( $layerindexlist as $k => $v ) {
				$layer = $values [$v];
				if ($layer ["type"] == "open") {
					$PLANE_LAYER_LIST = array ();
					$PLANE_LAYER_LIST ["INDEX_START"] = $v;
				} else {
					if ($layer ["type"] == "close") {
						if ($PLANE_LAYER_LIST != null) {
							$PLANE_LAYER_LIST ["INDEX_STOP"] = $v;
							array_push ( $PLANE_LAYER_LIST_LIST, $PLANE_LAYER_LIST );
						}
						$PLANE_LAYER_LIST = null;
					}
				}
			}
			
			$PLANE_LAYER_ELEMENT_LIST_LIST = array ();
			$PLANE_LAYER_ELEMENT_LIST = null;
			$PLANE_LAYER_ELEMENT_POINT_LIST_LIST = array ();
			$PLANE_LAYER_ELEMENT_POINT_LIST = null;
			
			$Cdao_PlaneLayer = new CDAODM_PLANE_LAYER ();
			$Cdao_PlaneLayerElement = new CDAODM_LAYER_ELEMENT ();
			$Cdao_PlaneLayerElementPoint = new CDAODM_LAYER_POINT ();
			
			$layerorder = 0;
			foreach ( $PLANE_LAYER_LIST_LIST as $index => $PLANE_LAYER_LIST ) {
				$layerstart = $PLANE_LAYER_LIST ["INDEX_START"];
				unset ( $PLANE_LAYER_LIST ["INDEX_START"] );
				$layerstop = $PLANE_LAYER_LIST ["INDEX_STOP"];
				unset ( $PLANE_LAYER_LIST ["INDEX_STOP"] );
				$PlaneLayerSeqId = $Cdao_PlaneLayer->getNextSeqId ();
				++ $layerstart;
				while ( $layerstart < $layerstop ) {
					if (! array_search ( $layerstart, $layerindexlist )) {
						$layer = $values [$layerstart];
						if (($layer ["tag"] == "TITLE") || ($layer ["tag"] == "DESC")) {
							if (($layer ["type"] == "complete") || ($layer ["type"] == "open")) {
								if ($layer ["tag"] == "TITLE") {
									$PLANE_LAYER_LIST ["LAYER_TOPIC"] = iconv ( 'UTF-8', 'GBK', $layer ["value"] );
								} else {
									$PLANE_LAYER_LIST ["LAYER_TYPE"] = $layer ["value"];
								}
							}
						} else {
							if ($layer ["type"] == "complete") {
								$layerAttributes = $layer ["attributes"];
								if (! empty ( $layerAttributes )) {
									$PlaneLayerElementSeqId = $Cdao_PlaneLayerElement->getNextSeqId ();
									$PLANE_LAYER_ELEMENT_LIST ["ELEMENT_ID"] = $PlaneLayerElementSeqId;
									$PLANE_LAYER_ELEMENT_LIST ["LAYER_ID"] = $PlaneLayerSeqId;
									$PLANE_LAYER_ELEMENT_LIST ["BUILDING_ID"] = $BUILDING_ID;
									$PLANE_LAYER_ELEMENT_LIST ["FLOOR_ID"] = $FLOOR_ID;
									$PLANE_LAYER_ELEMENT_LIST ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
									$PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TOPIC"] = $layerAttributes ["ID"];
									$PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TYPE"] = $layer ["tag"];
									$PLANE_LAYER_ELEMENT_LIST ["STROKE_WIDTH"] = ($layerAttributes ["STROKE-WIDTH"] === "null") ? 1 : $layerAttributes ["STROKE-WIDTH"];
									$PLANE_LAYER_ELEMENT_LIST ["STROKE_COLOR"] = $layerAttributes ["STROKE"];
									$PLANE_LAYER_ELEMENT_LIST ["FILL_COLOR"] = $layerAttributes ["FILL"];
									$PLANE_LAYER_ELEMENT_LIST ["ELEMENT_OPACITY"] = $layerAttributes ["OPACITY"];
									$PLANE_LAYER_ELEMENT_LIST ["FILL_OPACITY"] = $layerAttributes ["FILL-OPACITY"];
									$PLANE_LAYER_ELEMENT_LIST ["STROKE_OPACITY"] = $layerAttributes ["STROKE-OPACITY"];
									if (! empty ( $PLANE_LAYER_ELEMENT_LIST ["ELEMENT_OPACITY"] )) {
										$PLANE_LAYER_ELEMENT_LIST ["ELEMENT_OPACITY"] = $PLANE_LAYER_ELEMENT_LIST ["ELEMENT_OPACITY"] * 100;
									}
									if (! empty ( $PLANE_LAYER_ELEMENT_LIST ["FILL_OPACITY"] )) {
										$PLANE_LAYER_ELEMENT_LIST ["FILL_OPACITY"] = $PLANE_LAYER_ELEMENT_LIST ["FILL_OPACITY"] * 100;
									}
									if (! empty ( $PLANE_LAYER_ELEMENT_LIST ["STROKE_OPACITY"] )) {
										$PLANE_LAYER_ELEMENT_LIST ["STROKE_OPACITY"] = $PLANE_LAYER_ELEMENT_LIST ["STROKE_OPACITY"] * 100;
									}
									$PLANE_LAYER_ELEMENT_LIST ["STATUS"] = $this->STATUS_NORMAL;
									$PLANE_LAYER_ELEMENT_LIST ["FONT_SIZE"] = 0;
									$PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TEXT"] = "";
									$ELEMENT_ID_LIST_ID = SvgUtil::getSvgElementId ( $layer ["tag"] );
									if ($ELEMENT_ID_LIST_ID !== null) {
										$layerelementpointorder = 0;
										$SvgElementCoordAttrList = SvgUtil::getSvgElementCoordAttrList ( $ELEMENT_ID_LIST_ID );
										if (! empty ( $SvgElementCoordAttrList )) {
											foreach ( $SvgElementCoordAttrList as $X => $Y ) {
												$PLANE_LAYER_ELEMENT_POINT_LIST = $this->genPLANE_LAYER_ELEMENT_POINT_LIST ( $DRAW_MAP_ID, $Cdao_PlaneLayerElementPoint->getNextSeqId (), $PlaneLayerSeqId, $FLOOR_ID, $PlaneLayerElementSeqId, $layerAttributes [$X], $layerAttributes [$Y], $layerelementpointorder, "" );
												array_push ( $PLANE_LAYER_ELEMENT_POINT_LIST_LIST, $PLANE_LAYER_ELEMENT_POINT_LIST );
												++ $layerelementpointorder;
											}
										}
										switch ($ELEMENT_ID_LIST_ID) {
											case SvgUtil::SVG_ELEMENT_ID_POLYGON :
											case SvgUtil::SVG_ELEMENT_ID_POLYLINE :
												$POINTS = trim ( $layerAttributes ["POINTS"] );
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
												$POINTS_LIST = explode ( " ", $POINTS );
												$layerelementpointorder = 0;
												foreach ( $POINTS_LIST as $k => $v ) {
													if (! empty ( $v )) {
														$POSITION = explode ( ",", $v );
														if (count ( $POSITION ) == 2) {
															$PLANE_LAYER_ELEMENT_POINT_LIST = $this->genPLANE_LAYER_ELEMENT_POINT_LIST ( $DRAW_MAP_ID, $Cdao_PlaneLayerElementPoint->getNextSeqId (), $PlaneLayerSeqId, $FLOOR_ID, $PlaneLayerElementSeqId, $POSITION [0], $POSITION [1], $layerelementpointorder, "" );
															array_push ( $PLANE_LAYER_ELEMENT_POINT_LIST_LIST, $PLANE_LAYER_ELEMENT_POINT_LIST );
															++ $layerelementpointorder;
														}
													}
												}
												break;
											case SvgUtil::SVG_ELEMENT_ID_PATH :
												// M = moveto(M X,Y) ：将画笔移动到指定的坐标位置
												// L = lineto(L X,Y) ：画直线到指定的坐标位置
												// H = horizontal lineto(H X)：画水平线到指定的X坐标位置
												// V = vertical lineto(V Y)：画垂直线到指定的Y坐标位置
												// C = curveto(C X1,Y1,X2,Y2,ENDX,ENDY)：三次贝赛曲线
												// S = smooth curveto(S X2,Y2,ENDX,ENDY)
												// Q = quadratic Belzier curve(Q X,Y,ENDX,ENDY)：二次贝赛曲线
												// T = smooth quadratic Belzier curveto(T ENDX,ENDY)：映射
												// A = elliptical Arc(A RX,RY,XROTATION,FLAG1,FLAG2,X,Y)：弧线
												// Z = closepath()：关闭路径
												$POINTS = trim ( $layerAttributes ["D"] );
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
												$POSITION_X = "";
												$POSITION_Y = null;
												$PATH_TAG = null;
												$layerelementpointorder = 0;
												$POINTS = $POINTS . " ";
												$POINTS_LEN = strlen ( $POINTS );
												$i = 0;
												while ( $i < $POINTS_LEN ) {
													$ch = $POINTS [$i];
													$ID = SvgUtil::getSvgElementPathAttrId ( strtoupper ( $ch ) );
													if ($ID !== null) {
														if ($PATH_TAG !== null) {
															$PLANE_LAYER_ELEMENT_POINT_LIST = $this->genPLANE_LAYER_ELEMENT_POINT_LIST ( $DRAW_MAP_ID, $Cdao_PlaneLayerElementPoint->getNextSeqId (), $PlaneLayerSeqId, $FLOOR_ID, $PlaneLayerElementSeqId, $POSITION_X, $POSITION_Y, $layerelementpointorder, $PATH_TAG );
															array_push ( $PLANE_LAYER_ELEMENT_POINT_LIST_LIST, $PLANE_LAYER_ELEMENT_POINT_LIST );
															++ $layerelementpointorder;
														}
														$POSITION_X = "";
														$POSITION_Y = null;
														if ($ID == SvgUtil::SVG_ELEMENT_PATH_ID_X) {
															$PATH_TAG = "X";
														} else {
															$PATH_TAG = $ch;
														}
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
											case SvgUtil::SVG_ELEMENT_ID_TEXT :
												$PLANE_LAYER_ELEMENT_LIST ["FONT_SIZE"] = $layerAttributes ["FONT-SIZE"];
												$PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TEXT"] = iconv ( 'UTF-8', 'GBK', $layer ["value"] );
												$maxbytes = 300;
												if (strlen ( $PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TEXT"] ) > $maxbytes) {
													$PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TEXT"] = iconv_substr ( $PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TEXT"], 0, $maxbytes / 2, "GBK" );
												}
												break;
											case SvgUtil::SVG_ELEMENT_ID_IMAGE :
												$PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TEXT"] = urlencode ( $layerAttributes ["XLINK:HREF"] );
												break;
										}
									}
									array_push ( $PLANE_LAYER_ELEMENT_LIST_LIST, $PLANE_LAYER_ELEMENT_LIST );
								}
							}
						}
					}
					++ $layerstart;
				}
				$PLANE_LAYER_LIST [$Cdao_PlaneLayer->_table_seq_name] = $PlaneLayerSeqId;
				$PLANE_LAYER_LIST ["BUILDING_ID"] = $BUILDING_ID;
				$PLANE_LAYER_LIST ["FLOOR_ID"] = $FLOOR_ID;
				$PLANE_LAYER_LIST ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
				if (empty ( $PLANE_LAYER_LIST ["LAYER_TOPIC"] )) {
					$PLANE_LAYER_LIST ["LAYER_TOPIC"] = "UNKNOW" . $layerorder;
				}
				$PLANE_LAYER_LIST ["LAYER_NOTE"] = "UNKNOW" . $layerorder;
				if (empty ( $PLANE_LAYER_LIST ["LAYER_TYPE"] )) {
					$PLANE_LAYER_LIST ["LAYER_TYPE"] = "UNKNOW";
				}
				$PLANE_LAYER_LIST ["STATUS"] = $this->STATUS_NORMAL;
				$PLANE_LAYER_LIST ["L_ORDER"] = $layerorder;
				$PLANE_LAYER_LIST_LIST [$layerorder] = $PLANE_LAYER_LIST;
				++ $layerorder;
			}
			$sql = "Begin";
			$insertsqlkey = null;
			$insertsqlvalue = null;
			$updatesqldata = null;
			foreach ( $DRAW_MAP_LIST as $k => $v ) {
				$v = str_replace ( "'", "''", $v );
				if ($updatesqldata === null) {
					if ($Cdao_DrawMap->_table_seq_name != $k) {
						$updatesqldata = $k . "='{$v}'";
					}
					$insertsqlkey = $k;
					$insertsqlvalue = "'{$v}'";
				} else {
					if ($Cdao_DrawMap->_table_seq_name != $k) {
						$updatesqldata = $updatesqldata . "," . $k . "='{$v}'";
					}
					$insertsqlkey = $insertsqlkey . "," . $k;
					$insertsqlvalue = $insertsqlvalue . ",'{$v}'";
				}
			}
			$currentTime = date ( "Y/m/d H:i:s" );
			if ($existSvg) {
				$sql = $sql . " UPDATE {$Cdao_DrawMap->_table} SET {$updatesqldata},MOD_TIME='{$currentTime}' WHERE DRAW_MAP_ID={$DRAW_MAP_ID};";
			} else {
				$sql = $sql . " INSERT INTO {$Cdao_DrawMap->_table} ({$insertsqlkey},CREATE_TIME,MOD_TIME) VALUES ({$insertsqlvalue},'{$currentTime}','{$currentTime}');";
			}
			$sql = $sql . " UPDATE {$Cdao_PlaneLayer->_table} SET STATUS='{$this->STATUS_CANCEL}' WHERE DRAW_MAP_ID={$DRAW_MAP_ID} AND STATUS='{$this->STATUS_NORMAL}';";
			foreach ( $PLANE_LAYER_LIST_LIST as $k => $PLANE_LAYER_LIST ) {
				$insertsqlkey = null;
				$insertsqlvalue = null;
				foreach ( $PLANE_LAYER_LIST as $k => $v ) {
					$v = str_replace ( "'", "''", $v );
					if ($insertsqlkey === null) {
						$insertsqlkey = $k;
						$insertsqlvalue = "'{$v}'";
					} else {
						$insertsqlkey = $insertsqlkey . "," . $k;
						$insertsqlvalue = $insertsqlvalue . ",'{$v}'";
					}
				}
				$currentTime = date ( "Y/m/d H:i:s" );
				$sql = $sql . " INSERT INTO {$Cdao_PlaneLayer->_table} ({$insertsqlkey},CREATE_TIME,MOD_TIME) VALUES ({$insertsqlvalue},'{$currentTime}','{$currentTime}');";
			}
			$sql = $sql . " UPDATE {$Cdao_PlaneLayerElement->_table} SET STATUS='{$this->STATUS_CANCEL}' WHERE DRAW_MAP_ID={$DRAW_MAP_ID} AND STATUS='{$this->STATUS_NORMAL}';";
			foreach ( $PLANE_LAYER_ELEMENT_LIST_LIST as $k => $PLANE_LAYER_ELEMENT_LIST ) {
				$insertsqlkey = null;
				$insertsqlvalue = null;
				foreach ( $PLANE_LAYER_ELEMENT_LIST as $k => $v ) {
					$v = str_replace ( "'", "''", $v );
					if ($insertsqlkey === null) {
						$insertsqlkey = $k;
						$insertsqlvalue = "'{$v}'";
					} else {
						$insertsqlkey = $insertsqlkey . "," . $k;
						$insertsqlvalue = $insertsqlvalue . ",'{$v}'";
					}
				}
				$currentTime = date ( "Y/m/d H:i:s" );
				$sql = $sql . " INSERT INTO {$Cdao_PlaneLayerElement->_table} ({$insertsqlkey},CREATE_TIME,MOD_TIME) VALUES ({$insertsqlvalue},'{$currentTime}','{$currentTime}');";
			}
			$sql = $sql . " UPDATE {$Cdao_PlaneLayerElementPoint->_table} SET STATUS='{$this->STATUS_CANCEL}' WHERE DRAW_MAP_ID={$DRAW_MAP_ID} AND STATUS='{$this->STATUS_NORMAL}';";
			foreach ( $PLANE_LAYER_ELEMENT_POINT_LIST_LIST as $k => $PLANE_LAYER_ELEMENT_POINT_LIST ) {
				$insertsqlkey = null;
				$insertsqlvalue = null;
				foreach ( $PLANE_LAYER_ELEMENT_POINT_LIST as $k => $v ) {
					$v = str_replace ( "'", "''", $v );
					if ($insertsqlkey === null) {
						$insertsqlkey = $k;
						$insertsqlvalue = "'{$v}'";
					} else {
						$insertsqlkey = $insertsqlkey . "," . $k;
						$insertsqlvalue = $insertsqlvalue . ",'{$v}'";
					}
				}
				$currentTime = date ( "Y/m/d H:i:s" );
				$sql = $sql . " INSERT INTO {$Cdao_PlaneLayerElementPoint->_table} ({$insertsqlkey}) VALUES ({$insertsqlvalue});";
			}
			$sql = $sql . " End;";
			try {
				$result = $Cdao_DrawMap->DB ()->Execute ( $sql );
			} catch ( Exception $e ) {
				$json ["error"] = iconv ( 'GBK', 'UTF-8', "执行数据库错误：" );
				$json ["error"] = $json ["error"] . iconv ( 'GBK', 'UTF-8', $e->getMessage () );
			}
			if ($result === false) {
				$json ["error"] = iconv ( 'GBK', 'UTF-8', "插入数据库出错!" );
			}
		} else {
			$json ["error"] = iconv ( 'GBK', 'UTF-8', "参数不正确！" );
		}
		if (empty ( $json ["error"] )) {
			$errormsg = $this->saveSvgSrcAndPng ( $BUILDING_ID, $FLOOR_ID, $DRAW_MAP_ID, $SVGSRC, $PNGBASE64, $BACKGROUD_COLOR );
			if (! empty ( $errormsg )) {
				$json ["error"] = iconv ( 'GBK', 'UTF-8', $errormsg );
			}
			$json ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
		}
		echo (json_encode ( $json ));
	}
	/**
	 * 下载SVG源代码
	 *
	 * @return string json 平面图属性信息，出错信息
	 */
	public function actionAjaxDownLoadSvg() {
		$BUILDING_ID = $_POST ["BUILDING_ID"];
		$FLOOR_ID = $_POST ["FLOOR_ID"];
		$array = array ();
		if (! empty ( $FLOOR_ID )) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			$DRAW_MAP_ID = $_POST ["DRAW_MAP_ID"];
			if (empty ( $DRAW_MAP_ID )) {
				$DRAW_MAP_ID = $this->getSvgDrawMapId ( $FLOOR_ID );
			}
			if (! empty ( $DRAW_MAP_ID )) {
				if (empty ( $BUILDING_ID )) {
					$Cdao_Floor = new CDAOCB_FLOOR ();
					$BUILDING_ID = $Cdao_Floor->getBuildIdByFloorId ( $FLOOR_ID );
				}
				$row = $Cdao_DrawMap->getRow ( $DRAW_MAP_ID );
				$array ["VIEWBOX_WIDTH"] = $row ["VIEWBOX_WIDTH"];
				$array ["VIEWBOX_HEIGHT"] = $row ["VIEWBOX_HEIGHT"];
				$array ["FLOOR_WIDTH"] = $row ["FLOOR_WIDTH"];
				$array ["FLOOR_HEIGHT"] = $row ["FLOOR_HEIGHT"];
				$array ["BACKGROUD_COLOR"] = $row ["BACKGROUD_COLOR"];
				$array ["DW_UNIT"] = $row ["DW_UNIT"];
				$array ["DW_SCALE"] = $row ["DW_SCALE"];
				$array ["STATUS"] = $row ["STATUS"];
				$array ["DM_TOPIC"] = $DM_TOPIC = iconv ( 'GBK', 'UTF-8', $row ["DM_TOPIC"] );
				$cdict = new CDict ();
				$array ["STATUSNAME"] = iconv ( 'GBK', 'UTF-8', $cdict->BUILD_STATUS [$array ["STATUS"]] );
				$dom = new DomDocument ();
				$svg = $dom->createElement ( "svg" );
				$dom->appendChild ( $svg );
				$width = $dom->createAttribute ( "width" );
				$svg->appendChild ( $width );
				$width_value = $dom->createTextNode ( ($row ["DW_UNIT"] == "px") ? $row ["VIEWBOX_WIDTH"] : ($row ["VIEWBOX_WIDTH"] . $row ["DW_UNIT"]) );
				$width->appendChild ( $width_value );
				$height = $dom->createAttribute ( "height" );
				$svg->appendChild ( $height );
				$height_value = $dom->createTextNode ( ($row ["DW_UNIT"] == "px") ? $row ["VIEWBOX_HEIGHT"] : ($row ["VIEWBOX_HEIGHT"] . $row ["DW_UNIT"]) );
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
				$Cdao_PlaneLayerElementPoint = new CDAODM_LAYER_POINT ();
				$PLANE_LAYER_LIST_LIST = $Cdao_PlaneLayer->getList ( "{$Cdao_DrawMap->_table_seq_name} ={$DRAW_MAP_ID} AND STATUS='{$this->STATUS_NORMAL}' ORDER BY L_ORDER" );
				foreach ( $PLANE_LAYER_LIST_LIST as $k => $PLANE_LAYER_LIST ) {
					$layerId = $PLANE_LAYER_LIST [$Cdao_PlaneLayer->_table_seq_name];
					$g = $dom->createElement ( "g" );
					$svg->appendChild ( $g );
					$title = $dom->createElement ( "title" );
					$g->appendChild ( $title );
					$PLANE_LAYER_LIST ["LAYER_TOPIC"] = iconv ( 'GBK', 'UTF-8', $PLANE_LAYER_LIST ["LAYER_TOPIC"] );
					$title_value = $dom->createTextNode ( $PLANE_LAYER_LIST ["LAYER_TOPIC"] );
					$title->appendChild ( $title_value );
					
					$type = $dom->createElement ( "desc" );
					$g->appendChild ( $type );
					$type_value = $dom->createTextNode ( $PLANE_LAYER_LIST ["LAYER_TYPE"] );
					$type->appendChild ( $type_value );
					$PLANE_LAYER_ELEMENT_LIST_LIST = $Cdao_PlaneLayerElement->getList ( "{$Cdao_PlaneLayer->_table_seq_name} ={$layerId}  AND STATUS='{$this->STATUS_NORMAL}'" );
					foreach ( $PLANE_LAYER_ELEMENT_LIST_LIST as $k => $PLANE_LAYER_ELEMENT_LIST ) {
						$ELEMENT_TYPE = $PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TYPE"];
						$element = $dom->createElement ( strtolower ( $ELEMENT_TYPE ) );
						$g->appendChild ( $element );
						$element_attr = $dom->createAttribute ( "id" );
						$element->appendChild ( $element_attr );
						$element_attr_value = $dom->createTextNode ( "svg_" . $PLANE_LAYER_ELEMENT_LIST ["ELEMENT_ID"] );
						$element_attr->appendChild ( $element_attr_value );
						
						if ($PLANE_LAYER_ELEMENT_LIST ["STROKE_WIDTH"] !== null) {
							$element_attr = $dom->createAttribute ( "stroke-width" );
							$element->appendChild ( $element_attr );
							$element_attr_value = $dom->createTextNode ( $PLANE_LAYER_ELEMENT_LIST ["STROKE_WIDTH"] );
							$element_attr->appendChild ( $element_attr_value );
						}
						if ($PLANE_LAYER_ELEMENT_LIST ["STROKE_COLOR"] !== null) {
							$element_attr = $dom->createAttribute ( "stroke" );
							$element->appendChild ( $element_attr );
							$element_attr_value = $dom->createTextNode ( $PLANE_LAYER_ELEMENT_LIST ["STROKE_COLOR"] );
							$element_attr->appendChild ( $element_attr_value );
						}
						if ($PLANE_LAYER_ELEMENT_LIST ["FILL_COLOR"] !== null) {
							$element_attr = $dom->createAttribute ( "fill" );
							$element->appendChild ( $element_attr );
							$element_attr_value = $dom->createTextNode ( $PLANE_LAYER_ELEMENT_LIST ["FILL_COLOR"] );
							$element_attr->appendChild ( $element_attr_value );
						}
						if ($PLANE_LAYER_ELEMENT_LIST ["ELEMENT_OPACITY"] !== null) {
							$element_attr = $dom->createAttribute ( "opacity" );
							$element->appendChild ( $element_attr );
							$element_attr_value = $dom->createTextNode ( $PLANE_LAYER_ELEMENT_LIST ["ELEMENT_OPACITY"] / 100 );
							$element_attr->appendChild ( $element_attr_value );
						}
						if ($PLANE_LAYER_ELEMENT_LIST ["FILL_OPACITY"] !== null) {
							$element_attr = $dom->createAttribute ( "fill-opacity" );
							$element->appendChild ( $element_attr );
							$element_attr_value = $dom->createTextNode ( $PLANE_LAYER_ELEMENT_LIST ["FILL_OPACITY"] / 100 );
							$element_attr->appendChild ( $element_attr_value );
						}
						if ($PLANE_LAYER_ELEMENT_LIST ["STROKE_OPACITY"] !== null) {
							$element_attr = $dom->createAttribute ( "stroke-opacity" );
							$element->appendChild ( $element_attr );
							$element_attr_value = $dom->createTextNode ( $PLANE_LAYER_ELEMENT_LIST ["STROKE_OPACITY"] / 100 );
							$element_attr->appendChild ( $element_attr_value );
						}
						$ELEMENT_ID = $PLANE_LAYER_ELEMENT_LIST ["ELEMENT_ID"];
						$PLANE_LAYER_ELEMENT_POINT_LIST_LIST = $Cdao_PlaneLayerElementPoint->getList ( "{$Cdao_PlaneLayerElement->_table_seq_name}={$ELEMENT_ID}  AND STATUS='{$this->STATUS_NORMAL}' ORDER BY L_ORDER" );
						$ELEMENT_ID_LIST_ID = SvgUtil::getSvgElementId ( $ELEMENT_TYPE );
						if ($ELEMENT_ID_LIST_ID !== null) {
							$layerelementpointorder = 0;
							$SvgElementCoordAttrList = SvgUtil::getSvgElementCoordAttrList ( $ELEMENT_ID_LIST_ID );
							if (! empty ( $SvgElementCoordAttrList )) {
								foreach ( $SvgElementCoordAttrList as $X => $Y ) {
									$PLANE_LAYER_ELEMENT_POINT_LIST = $PLANE_LAYER_ELEMENT_POINT_LIST_LIST [$layerelementpointorder];
									if ($PLANE_LAYER_ELEMENT_POINT_LIST !== null) {
										if (($X !== null) && ($PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_X"] !== null)) {
											$element_attr = $dom->createAttribute ( strtolower ( $X ) );
											$element->appendChild ( $element_attr );
											$element_attr_value = $dom->createTextNode ( $PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_X"] );
											$element_attr->appendChild ( $element_attr_value );
										}
										if (($Y != null) && $PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_Y"] !== null) {
											$element_attr = $dom->createAttribute ( strtolower ( $Y ) );
											$element->appendChild ( $element_attr );
											$element_attr_value = $dom->createTextNode ( $PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_Y"] );
											$element_attr->appendChild ( $element_attr_value );
										}
									}
									++ $layerelementpointorder;
								}
							}
							switch ($ELEMENT_ID_LIST_ID) {
								case SvgUtil::SVG_ELEMENT_ID_POLYGON :
								case SvgUtil::SVG_ELEMENT_ID_POLYLINE :
									$POINTS = null;
									foreach ( $PLANE_LAYER_ELEMENT_POINT_LIST_LIST as $k => $PLANE_LAYER_ELEMENT_POINT_LIST ) {
										$x = $PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_X"];
										$y = $PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_Y"];
										if ($POINTS === null) {
											$POINTS = "{$x},{$y}";
										} else {
											$POINTS = $POINTS . " {$x},{$y}";
										}
									}
									if (! empty ( $POINTS )) {
										$element_attr = $dom->createAttribute ( "points" );
										$element->appendChild ( $element_attr );
										$element_attr_value = $dom->createTextNode ( $POINTS );
										$element_attr->appendChild ( $element_attr_value );
									}
									break;
								case SvgUtil::SVG_ELEMENT_ID_PATH :
									foreach ( $PLANE_LAYER_ELEMENT_POINT_LIST_LIST as $k => $PLANE_LAYER_ELEMENT_POINT_LIST ) {
										$POINTS = null;
										foreach ( $PLANE_LAYER_ELEMENT_POINT_LIST_LIST as $k => $PLANE_LAYER_ELEMENT_POINT_LIST ) {
											$x = $PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_X"];
											$y = $PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION_Y"];
											$POSITION = strtolower ( $PLANE_LAYER_ELEMENT_POINT_LIST ["POSITION"] );
											if ($POSITION !== null) {
												if ($POSITION == "x") {
													$POSITION = " ";
												}
												$Coordinate = ($POSITION == "z") ? "" : "{$x},{$y}";
												$POINTS = ($POINTS === null) ? "{$POSITION}{$Coordinate}" : $POINTS . "{$POSITION}{$Coordinate}";
											}
										}
										if (! empty ( $POINTS )) {
											$element_attr = $dom->createAttribute ( "d" );
											$element->appendChild ( $element_attr );
											$element_attr_value = $dom->createTextNode ( $POINTS );
											$element_attr->appendChild ( $element_attr_value );
										}
									}
									break;
								case SvgUtil::SVG_ELEMENT_ID_TEXT :
									if ($PLANE_LAYER_ELEMENT_LIST ["FONT_SIZE"] !== null) {
										$element_attr = $dom->createAttribute ( "font-size" );
										$element->appendChild ( $element_attr );
										$element_attr_value = $dom->createTextNode ( $PLANE_LAYER_ELEMENT_LIST ["FONT_SIZE"] );
										$element_attr->appendChild ( $element_attr_value );
									}
									if ($PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TEXT"] !== null) {
										$element_html = $dom->createTextNode ( iconv ( 'GBK', 'UTF-8', $PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TEXT"] ) );
										$element->appendChild ( $element_html );
									}
									break;
								case SvgUtil::SVG_ELEMENT_ID_IMAGE :
									if ($PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TEXT"] !== null) {
										$element_attr = $dom->createAttribute ( "xlink:href" );
										$element->appendChild ( $element_attr );
										$element_attr_value = $dom->createTextNode ( urldecode ( $PLANE_LAYER_ELEMENT_LIST ["ELEMENT_TEXT"] ) );
										$element_attr->appendChild ( $element_attr_value );
									}
									break;
							}
						}
					}
				}
				$SVGSRC = $dom->saveXML ();
				$array ["SVGSRC"] = iconv ( 'GBK', 'UTF-8', $SVGSRC );
			} else {
				$array ["error"] = iconv ( 'GBK', 'UTF-8', "此楼层不存在SVG图片！" );
			}
		} else {
			$array ["error"] = iconv ( 'GBK', 'UTF-8', "楼层为空，无法找到相应的SVG图片！" );
		}
		echo (json_encode ( $array ));
	}
	public function getSvgDrawMapId($FLOOR_ID) {
		$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
		return ($Cdao_DrawMap->getEditableSeqIdByFloorId ( $FLOOR_ID ));
	}
	public function actionAjaxGetFloorSize() {
		$FLOOR_ID = $_POST ["FLOOR_ID"];
		$array = array ();
		if (! empty ( $FLOOR_ID )) {
			$Cdao_Floor = new CDAOCB_FLOOR ();
			$row = $Cdao_Floor->getFloorData ( $FLOOR_ID );
			if (! empty ( $row )) {
				$array ["FLOOR_WIDTH"] = $row [0] ["FLOOR_WIDTH"];
				$array ["FLOOR_HEIGHT"] = $row [0] ["FLOOR_HEIGHT"];
			}
		}
		echo (json_encode ( $array ));
	}
	/**
	 * 判断楼层是否存在平面图
	 */
	public function actionAjaxHasSvg() {
		$FLOOR_ID = $_POST ["FLOOR_ID"];
		$DRAW_MAP_ID = null;
		$array = array ();
		if (! empty ( $FLOOR_ID )) {
			$DRAW_MAP_ID = $this->getSvgDrawMapId ( $FLOOR_ID );
			if (empty ( $DRAW_MAP_ID )) {
				$DRAW_MAP_ID = null;
			}
		}
		$array ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
		echo (json_encode ( $array ));
	}
	public function getRandNumStr() {
		$num = rand ( 0, 99 );
		if ($num < 10) {
			$num = "0" . $num;
		} else {
			$num = "" . $num;
		}
		return ($num);
	}
	public function createMediaPath() {
		$F = $this->getRandNumStr ();
		$S = $this->getRandNumStr ();
		$T = $this->getRandNumStr ();
		$PATH_FILE = $F . "/" . $S . "/" . $T;
		return ($PATH_FILE);
	}
	public function getMediaPathByFloor($FLOOR_ID) {
		$CDAO_Media = new CDAOCB_PLANE_MEDIA ();
		$PATH_FILE = $CDAO_Media->getMediaPathByFloor ( $FLOOR_ID );
		while ( empty ( $PATH_FILE ) ) {
			$PATH_FILE = $this->createMediaPath ();
			if ($CDAO_Media->isMediaPathExist ( $PATH_FILE )) {
				$PATH_FILE = null;
			}
		}
		return ($PATH_FILE);
	}
	// /**
	// * split字符串
	// */
	// public function strSplit($str, $split) {
	// $strlist = array ();
	// if (! empty ( $str ) && ! empty ( $split )) {
	// $strlen = strlen ( $str );
	// $splitlen = strlen ( $split );
	// while ( $pos = strpos ( $str, $split ) ) {
	// $strfind = substr ( $str, 0, $pos );
	// array_push ( $strlist, $strfind );
	// $str = substr ( $str, $pos + $splitlen, $strlen - $splitlen - strlen ( $strfind ) );
	// $strlen = strlen ( $str );
	// }
	// array_push ( $strlist, $str );
	// }
	// return ($strlist);
	// }
	/**
	 * rgb颜色转rgb
	 *
	 * @param unknown $color        	
	 */
	public function ColorToRGB($color) {
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
	 * @param unknown $src        	
	 * @param unknown $BACKGROUD_COLOR        	
	 * @param unknown $path        	
	 * @param unknown $s        	
	 * @return boolean
	 */
	public function saveSrcToPng($src, $BACKGROUD_COLOR, $path, $s) {
		if (! empty ( $src )) {
			$srcimg = imagecreatefromstring ( $src );
			$srcimag_w = imagesx ( $srcimg );
			$srcimag_h = imagesy ( $srcimg );
			$dstimg = imagecreatetruecolor ( $srcimag_w, $srcimag_h );
			$rgb = $this->ColorToRGB ( $BACKGROUD_COLOR );
			$bg = imagecolorallocate ( $srcimg, $rgb ["r"], $rgb ["g"], $rgb ["b"] );
			if ($bg != - 1) {
				imagefilledrectangle ( $dstimg, 0, 0, $srcimag_w, $srcimag_h, $bg );
				// return imagefill ( $srcimg, 0, 0, $bg );
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
	 * 保存svg源码和png图片
	 */
	public function saveSvgSrcAndPng($BUILDING_ID, $FLOOR_ID, $DRAW_MAP_ID, $SVGSRC, $PNGBASE64, $BACKGROUD_COLOR) {
		$errormsg = null;
		$PATH_FILE = $this->getMediaPathByFloor ( $FLOOR_ID );
		if (! empty ( $PATH_FILE )) {
			$PATH_FILE_LIST = explode ( "/", $PATH_FILE );
			if (! empty ( $PATH_FILE_LIST )) {
				$tag = "data:image/png;base64,";
				$PNGSRC = base64_decode ( substr ( $PNGBASE64, strlen ( $tag ) ) );
				// $currentTime = date ( "Y/m/d H:i:s" );
				$CDAO_Media = new CDAOCB_PLANE_MEDIA ();
				$TYPELIST = array ();
				$TYPELIST [$CDAO_Media->FILE_PNG] = $PNGSRC;
				$TYPELIST [$CDAO_Media->FILE_SVG] = $SVGSRC;
				$TYPELIST [$CDAO_Media->FILE_THM] = $PNGSRC;
				$FILE_PATH_LIST = array ();
				foreach ( $TYPELIST as $MEDIA_TYPE => $SRC ) {
					// MEDIA_SERVER
					$FILE_PATH = MEDIALIB_PATH;
					if (! file_exists ( $FILE_PATH )) {
						try {
							$dir_exists = mkdir ( $FILE_PATH );
						} catch ( Exception $e ) {
							$errormsg = $errormsg . $FILE_PATH . "目录不存在，无法保存" . $MEDIA_TYPE . "文件！";
							continue;
						}
					}
					
					$FILE_PATH = $FILE_PATH . $CDAO_Media->SVG;
					if (! file_exists ( $FILE_PATH )) {
						try {
							$dir_exists = mkdir ( $FILE_PATH );
						} catch ( Exception $e ) {
							$errormsg = $errormsg . $FILE_PATH . "目录不存在，无法保存" . $MEDIA_TYPE . "文件！";
							continue;
						}
					}
					
					$FILE_PATH = $FILE_PATH . "/" . $MEDIA_TYPE;
					if (! file_exists ( $FILE_PATH )) {
						try {
							$dir_exists = mkdir ( $FILE_PATH );
						} catch ( Exception $e ) {
							$errormsg = $errormsg . $FILE_PATH . "目录不存在，无法保存" . $MEDIA_TYPE . "文件！";
							continue;
						}
					}
					$isbreak = false;
					foreach ( $PATH_FILE_LIST as $k => $v ) {
						$FILE_PATH = $FILE_PATH . "/" . $v;
						if (! file_exists ( $FILE_PATH )) {
							try {
								$dir_exists = mkdir ( $FILE_PATH );
							} catch ( Exception $e ) {
								$errormsg = $errormsg . $FILE_PATH . "目录不存在，无法保存" . $MEDIA_TYPE . "文件！";
								$isbreak = true;
								break;
							}
						}
					}
					if ($isbreak) {
						continue;
					}
					$FILE_PATH_LIST [$MEDIA_TYPE] = $FILE_PATH;
				}
				
				$Cdao_Building = new CDAOCB_BUILDING ();
				$BUILDING_NAME = $Cdao_Building->getSeqNameBySeqId ( $BUILDING_ID );
				$Cdao_Floor = new CDAOCB_FLOOR ();
				$FLOOR_NAME = $Cdao_Floor->getSeqNameBySeqId ( $FLOOR_ID );
				$FILE_NAME = $BUILDING_NAME . "_" . $FLOOR_NAME . "_" . date ( "YmdHis" );
				// $FILE_NAME = iconv ( 'GBK', 'UTF-8', $FILE_NAME );
				$FORM_INFO = array ();
				// $FORM_INFO ["MEDIA_ID"] = $MEDIA_ID_PNG;
				$FORM_INFO ["BUILDING_ID"] = $BUILDING_ID;
				$FORM_INFO ["FLOOR_ID"] = $FLOOR_ID;
				$FORM_INFO ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
				$FORM_INFO ["MEDIA_TOPIC"] = "";
				$FORM_INFO ["MEDIA_NOTE"] = "";
				// $FORM_INFO ["MIME_TYPE"] = "";
				// $FORM_INFO ["MEDIA_TYPE"] = $CDAO_Media->FILE_PNG;
				// $FORM_INFO ["MEDIA_SIZE"] = "0";
				// $FORM_INFO ["PATH_FILE"] = "0";
				$FORM_INFO ["FILENAME"] = $FILE_NAME;
				$FORM_INFO ["STATUS"] = "A";
				$FORM_INFO ["PATH_FILE"] = $PATH_FILE;
				foreach ( $FILE_PATH_LIST as $MEDIA_TYPE => $FILE_PATH ) {
					if ($CDAO_Media->FILE_THM === $MEDIA_TYPE) {
						$FILE_FULLPATH = $FILE_PATH . "/" . $FILE_NAME . "." . $CDAO_Media->FILE_PNG;
					} else {
						$FILE_FULLPATH = $FILE_PATH . "/" . $FILE_NAME . "." . $MEDIA_TYPE;
					}
					$FILE_FULLPATH = iconv ( 'GBK', 'UTF-8', $FILE_FULLPATH );
					$SRC = $TYPELIST [$MEDIA_TYPE];
					$fp = false;
					if ($CDAO_Media->FILE_THM === $MEDIA_TYPE) {
						$fp = $this->saveSrcToPng ( $SRC, $BACKGROUD_COLOR, $FILE_FULLPATH, $CDAO_Media->FILE_THM_SIZE );
					} else {
						if ($CDAO_Media->FILE_PNG === $MEDIA_TYPE) {
							$fp = $this->saveSrcToPng ( $SRC, $BACKGROUD_COLOR, $FILE_FULLPATH, null );
						} else {
							$fp = fopen ( $FILE_FULLPATH, "w" );
							if ($fp) {
								try {
									fwrite ( $fp, $SRC );
									fclose ( $fp );
								} catch ( Exception $e ) {
									$fp = false;
								}
							}
						}
					}
					if ($fp && file_exists ( $FILE_FULLPATH )) {
						$MIME_TYPE = "";
						try {
							$finfo = finfo_open ( FILEINFO_MIME );
							$MIME_TYPE = finfo_file ( $finfo, $FILE_FULLPATH );
							finfo_close ( $finfo );
						} catch ( Exception $e ) {
							$errormsg = $errormsg . "获取" . $MEDIA_TYPE . "文件的MIME类型失败！";
						}
						$MEDIA_ID = $CDAO_Media->getSeqIdByType ( $DRAW_MAP_ID, $MEDIA_TYPE );
						// $FORM_INFO ["MEDIA_ID"] = $MEDIA_ID;
						// if (empty ( $MEDIA_ID )) {
						// $MEDIA_ID = $CDAO_Media->getNextSeqId ();
						// }
						$FORM_INFO ["MIME_TYPE"] = $MIME_TYPE;
						$FORM_INFO ["MEDIA_ID"] = $MEDIA_ID;
						$FORM_INFO ["MEDIA_TYPE"] = $MEDIA_TYPE;
						$FORM_INFO ["MEDIA_SIZE"] = strlen ( $SRC );
						try {
							$CDAO_Media->doEdit ( $FORM_INFO );
						} catch ( Exception $e ) {
							$errormsg = $errormsg . "保存" . $MEDIA_TYPE . "文件时，更新数库库出错！";
						}
					} else {
						$errormsg = $errormsg . "保存" . $MEDIA_TYPE . "文件失败！";
					}
				}
			} else {
				$errormsg = "存储svg源代码和png图片时，解析存储取路径失败！";
			}
		} else {
			$errormsg = "存储svg源代码和png图片时，获取存储取路径失败！";
		}
		return ($errormsg);
	}
}
?>