<?php
/**
 * SVG管理:显示svg.html界面，从数据库获取数据和提交数据到数据库
 * @author xiang.zc
 *
 */
class SvgMgrController extends AdminController {
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
			$Cdao_Building = new CDAOCB_BUILDING ();
			$buildingInfo = $Cdao_Building->getRow ( $BUILDING_ID );
			if (! empty ( $buildingInfo )) {
				$lt_lat = SvgUtil::getDecodeLatLng ( $buildingInfo ["LT_LATITUDEL"] );
				$lt_lng = SvgUtil::getDecodeLatLng ( $buildingInfo ["LT_LONGITUDEL"] );
				$rb_lat = SvgUtil::getDecodeLatLng ( $buildingInfo ["RB_LATITUDEL"] );
				$rb_lng = SvgUtil::getDecodeLatLng ( $buildingInfo ["RB_LONGITUDEL"] );
				$pageData ["FLOOR_WIDTH"] = SvgUtil::getLatLngDistance ( $lt_lat, $lt_lng, $lt_lat, $rb_lng );
				$pageData ["FLOOR_HEIGHT"] = SvgUtil::getLatLngDistance ( $lt_lat, $lt_lng, $rb_lat, $lt_lng );
			}
		}
		if (! empty ( $DRAW_MAP_ID )) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			$PlanegraphInfo = $Cdao_DrawMap->getRow ( $DRAW_MAP_ID );
			$pageData ["PlanegraphInfo"] = $PlanegraphInfo;
			$pageData ["FLOOR_ID"] = $PlanegraphInfo ["FLOOR_ID"];
			$pageData ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
			$Cdao_Poi = new CDAOCB_POI ();
			$result = $Cdao_Poi->getAllWhere ( "POI_ID,POI_NAME,POI_TYPE,POI_NOTE,NOTE,STATUS,ADDRESS,PHONE,SVG_ID,ANT_LAC,ANT_CID,ANT_POWER,ANT_FREQUENCY", "DRAW_MAP_ID={$DRAW_MAP_ID}" );
			if (! empty ( $result )) {
				$PoiFormList = array ();
				$PoiSvgList = array ();
				$where = "";
				foreach ( $result as $PoiForm ) {
					$SVG_ID = $PoiForm ["SVG_ID"];
					$POI_ID = $PoiForm ["POI_ID"];
					$PoiSvgList [$POI_ID] = $SVG_ID;
					$w = "(POI_ID={$POI_ID} AND STATUS<>'X')";
					if (empty ( $where )) {
						$where = $w;
					} else {
						$where = $where . " OR " . $w;
					}
					unset ( $PoiForm ["SVG_ID"] );
					unset ( $PoiForm ["POI_ID"] );
					$PoiForm ["POI_NAME"] = iconv ( 'GBK', 'UTF-8', $PoiForm ["POI_NAME"] );
					$PoiForm ["POI_NOTE"] = iconv ( 'GBK', 'UTF-8', $PoiForm ["POI_NOTE"] );
					$PoiForm ["ADDRESS"] = iconv ( 'GBK', 'UTF-8', $PoiForm ["ADDRESS"] );
					$PoiForm ["NOTE"] = iconv ( 'GBK', 'UTF-8', $PoiForm ["NOTE"] );
					$PoiFormList [$SVG_ID] = $PoiForm;
				}
				if (! empty ( $PoiFormList )) {
					$pageData ["POIFORMLISTJSONSTR"] = json_encode ( $PoiFormList );
				}
				$CDAO_Pic = new CDAOCB_PIC ();
				$result = $CDAO_Pic->getAllWhere ( "PIC_ID,POI_ID", $where );
				if (! empty ( $result )) {
					$SvgPicList = array ();
					foreach ( $result as $PoiPic ) {
						$SvgPicList [$PoiSvgList [$PoiPic ["POI_ID"]]] = array (
								"PIC_ID" => $PoiPic ["PIC_ID"] 
						);
					}
					if (! empty ( $SvgPicList )) {
						$pageData ["SVGPICLISTJSONSTR"] = json_encode ( $SvgPicList );
					}
				}
			}
			$Cdao_Ap = new CDAOCB_LOCATION ();
			$result = $Cdao_Ap->getAllWhere ( "AP_ID,SVG_ID,EQUT_SSID,MAC_BSSID,EQUT_TYPE,FREQUENCY,CHANNEL,FACTORY,BRANDS,EQUT_MODEL,NOTE,STATUS", "DRAW_MAP_ID={$DRAW_MAP_ID}" );
			if (! empty ( $result )) {
				$ApFormList = array ();
				// $ApSvgList = array ();
				$where = "";
				foreach ( $result as $ApForm ) {
					$SVG_ID = $ApForm ["SVG_ID"];
					// $AP_ID = $ApForm ["AP_ID"];
					// $ApSvgList [$AP_ID] = $SVG_ID;
					// $w = "(AP_ID={$AP_ID} AND STATUS<>'X')";
					// if (empty ( $where )) {
					// $where = $w;
					// } else {
					// $where = $where . " OR " . $w;
					// }
					unset ( $ApForm ["SVG_ID"] );
					unset ( $ApForm ["AP_ID"] );
					$ApForm ["EQUT_SSID"] = iconv ( 'GBK', 'UTF-8', $ApForm ["EQUT_SSID"] );
					$ApForm ["EQUT_MODEL"] = iconv ( 'GBK', 'UTF-8', $ApForm ["EQUT_MODEL"] );
					$ApForm ["NOTE"] = iconv ( 'GBK', 'UTF-8', $ApForm ["NOTE"] );
					$ApFormList [$SVG_ID] = $ApForm;
				}
				if (! empty ( $ApFormList )) {
					$pageData ["APFORMLISTJSONSTR"] = json_encode ( $ApFormList );
				}
				// $CDAO_Pic = new CDAOCB_PIC ();
				// $result = $CDAO_Pic->getAllWhere ( "PIC_ID,AP_ID", $where );
				// if (! empty ( $result )) {
				// $SvgPicList = array ();
				// foreach ( $result as $ApPic ) {
				// $SvgPicList [$ApSvgList [$ApPic ["AP_ID"]]] = array (
				// "PIC_ID" => $ApPic ["PIC_ID"]
				// );
				// }
				// $pageData ["SVGPICLISTJSONSTR"] = json_encode ( $SvgPicList );
				// }
			}
		}
		if (! empty ( $FLOOR_ID )) {
			// $CDAO_Ap = new CDAOCB_LOCATION ();
			// $AP_List = $CDAO_Ap->getApList ( "where FLOOR_ID={$FLOOR_ID}" );
			// $pageData ["FLOOR_WIDTH"] = 0;
			// $pageData ["FLOOR_HEIGHT"] = 0;
			// $Cdao_Floor = new CDAOCB_FLOOR ();
			// $row = $Cdao_Floor->getFloorData ( $FLOOR_ID );
			// if (! empty ( $row )) {
			// $pageData ["FLOOR_WIDTH"] = $row [0] ["FLOOR_WIDTH"];
			// $pageData ["FLOOR_HEIGHT"] = $row [0] ["FLOOR_HEIGHT"];
			// }
			$pageData ["FLOOR_ID"] = $FLOOR_ID;
		}
		$cdict = new CDict ();
		$pageData ["DEFAULT_PLANEGRAPH"] = json_encode ( $cdict->DEFAULT_PLANEGRAPH );
		$pageData ["BUILD_STATUS"] = $cdict->BUILD_STATUS;
		$pageData ["PLANEGRAPH_UNIT"] = $cdict->PLANEGRAPH_UNIT;
		$pageData ["POI_STATUS"] = $cdict->POI_STATUS;
		$pageData ["POI_TYPE"] = $cdict->POI_TYPE;
		$pageData ["EQUT_STATUS"] = $cdict->EQUT_STATUS;
		$pageData ["EQUT_TYPE"] = $cdict->EQUT_TYPE;
		$pageData ["EQUT_BRANDS"] = $cdict->EQUT_BRANDS;
		$pageData ["EQUT_FACTORY"] = $cdict->EQUT_FACTORY;
		$pageData ["ANT_FREQUENCYS"] = $cdict->ANT_FREQUENCYS;
		$pageData ["SVG_LAYER_TYPE"] = json_encode ( $cdict->SVG_LAYER_TYPE );
		// $pageData ["LAYERTYPE_AP"] = $cdict->SVG_LAYER_TYPE ["AP"];
		// $pageData ["LAYERTYPE_POI"] = $cdict->SVG_LAYER_TYPE ["POI"];
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
	 * 上传SVG源代码
	 *
	 * @return string json 出错信息
	 */
	public function actionAjaxUploadSvgAndSavePng() {
		$DATA = $_POST ["PARAMSJSON"];
		if (! empty ( $_FILES )) {
			$DATA = iconv ( 'GBK', 'UTF-8', $DATA );
		}
		$DATA = json_decode ( $DATA );
		$DATA = get_object_vars ( $DATA );
		$BUILDING_ID = $DATA ["BUILDING_ID"];
		$FLOOR_ID = $DATA ["FLOOR_ID"];
		$DRAW_MAP_ID = $DATA ["DRAW_MAP_ID"];
		$DW_SCALE = $DATA ["DW_SCALE"];
		$BACKGROUD_COLOR = $DATA ["BACKGROUD_COLOR"];
		$SVGSRC = $DATA ["SVGSRC"];
		$PNGBASE64 = $DATA ["PNGBASE64"];
		$APFORMLISTJSONSTR = $DATA ["APFORMLISTJSONSTR"];
		$POIFORMLISTJSONSTR = $DATA ["POIFORMLISTJSONSTR"];
		$SVGPICLISTJSONSTR = $DATA ["SVGPICLISTJSONSTR"];
		$APFORMLIST = array ();
		if (! empty ( $APFORMLISTJSONSTR )) {
			$APFORMLIST = json_decode ( $APFORMLISTJSONSTR );
			$APFORMLIST = get_object_vars ( $APFORMLIST );
		}
		$POIFORMLIST = array ();
		if (! empty ( $POIFORMLISTJSONSTR )) {
			$POIFORMLIST = json_decode ( $POIFORMLISTJSONSTR );
			$POIFORMLIST = get_object_vars ( $POIFORMLIST );
		}
		$SVGPICLIST = array ();
		if (! empty ( $SVGPICLISTJSONSTR )) {
			$SVGPICLISTTMP = json_decode ( $SVGPICLISTJSONSTR );
			$SVGPICLISTTMP = get_object_vars ( $SVGPICLISTTMP );
			foreach ( $SVGPICLISTTMP as $k => $v ) {
				$SVGPICLIST [$k] = get_object_vars ( $v );
			}
		}
		$error = "";
		$warning = "";
		if (! empty ( $FLOOR_ID ) && ! empty ( $SVGSRC )) {
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			$existSvg = true;
			if (empty ( $DRAW_MAP_ID )) {
				$DRAW_MAP_ID = $Cdao_DrawMap->getNextSeqId ();
				$existSvg = false;
			}
			if (empty ( $BUILDING_ID )) {
				$Cdao_Floor = new CDAOCB_FLOOR ();
				$row = $Cdao_Floor->getFloorData ( $FLOOR_ID );
				if (empty ( $row )) {
					$BUILDING_ID = 0;
				} else {
					$BUILDING_ID = $row [0] ["BUILDING_ID"];
				}
			}
			$DM_TOPIC = $DATA ["DM_TOPIC"];
			if (! empty ( $DM_TOPIC )) {
				$DM_TOPIC = iconv ( 'UTF-8', 'GBK', $DM_TOPIC );
			}
			$cdict = new CDict ();
			$STATUS = $DATA ["STATUS"];
			if (empty ( $STATUS )) {
				$STATUS = $cdict->DEFAULT_PLANEGRAPH ["STATUS"];
			}
			if (empty ( $BACKGROUD_COLOR )) {
				$BACKGROUD_COLOR = $cdict->DEFAULT_PLANEGRAPH ["BACKGROUD_COLOR"];
			}
			if (empty ( $DW_SCALE )) {
				$DW_SCALE = $cdict->DEFAULT_PLANEGRAPH ["DW_SCALE"];
			}
			$DW_UNIT = $DATA ["DW_UNIT"];
			if (empty ( $DW_UNIT )) {
				$DW_UNIT = $cdict->DEFAULT_PLANEGRAPH ["DW_UNIT"];
			}
			$SvgInfo = SvgUtil::getSvgInfo ( $SVGSRC );
			$SvgForm = array ();
			$LayerFormList = array ();
			$ElementFormList = array ();
			$ElementAttrFormList = array ();
			$ApFormList = array ();
			$PoiFormList = array ();
			$Cdao_PlaneLayer = new CDAODM_PLANE_LAYER ();
			$Cdao_PlaneLayerElement = new CDAODM_LAYER_ELEMENT ();
			$Cdao_Poi = new CDAOCB_POI ();
			$Cdao_Ap = new CDAOCB_LOCATION ();
			$Cdao_Building = new CDAOCB_BUILDING ();
			$BuildingInfo = $Cdao_Building->getRow ( $BUILDING_ID );
			// $Cdao_PlaneLayerElementPoint = new CDAODM_LAYER_POINT ();
			$SvgAttr = $SvgInfo ["attributes"];
			$SvgForm ["BUILDING_ID"] = $BUILDING_ID;
			$SvgForm ["FLOOR_ID"] = $FLOOR_ID;
			$SvgForm ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
			$SvgForm ["DM_TOPIC"] = $DM_TOPIC;
			$SvgForm ["DW_SCALE"] = $DW_SCALE;
			$SvgForm ["DW_UNIT"] = $DW_UNIT;
			$SvgForm ["HEIGHT"] = $SvgAttr ["HEIGHT"];
			$SvgForm ["WIDTH"] = $SvgAttr ["WIDTH"];
			$SvgForm ["PIC_ID"] = 0; //
			$SvgForm ["DM_NOTE"] = ""; //
			$SvgForm ["STATUS"] = $STATUS;
			// $SvgForm ["CREATE_TIME"] = date ( "Y/m/d H:i:s" );
			// $SvgForm ["MOD_TIME"] = date ( "Y/m/d H:i:s" );
			$SVG_LAYER_TYPE = json_decode ( json_encode ( (new CDict ())->SVG_LAYER_TYPE ) );
			$LayerInfos = $SvgInfo ["elements"];
			$LayerOrder = 0;
			if (! empty ( $LayerInfos )) {
				foreach ( $LayerInfos as $LayerInfo ) {
					$LayerID = $Cdao_PlaneLayer->getNextSeqId ();
					$LayerAttr = $LayerInfo ["attributes"];
					$LayerForm = array ();
					$LayerForm ["BUILDING_ID"] = $BUILDING_ID;
					$LayerForm ["FLOOR_ID"] = $FLOOR_ID;
					$LayerForm ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
					$LayerForm ["LAYER_ID"] = $LayerID;
					$LayerForm ["LAYER_NOTE"] = ""; //
					$LayerForm ["L_ORDER"] = $LayerOrder;
					$LayerForm ["LAYER_TOPIC"] = iconv ( 'UTF-8', 'GBK', SvgUtil::getSvgLayerValue ( $LayerInfo, "TITLE" ) );
					$LayerForm ["LAYER_TYPE"] = SvgUtil::getSvgLayerValue ( $LayerInfo, "DESC" );
					if (empty ( $LayerForm ["LAYER_TOPIC"] )) {
						$LayerForm ["LAYER_TOPIC"] = "NULL";
					}
					if (empty ( $LayerForm ["LAYER_TYPE"] )) {
						$LayerForm ["LAYER_TYPE"] = "NULL";
					}
					++ $LayerOrder;
					$LayerForm ["STATUS"] = "E";
					// $LayerForm ["CREATE_TIME"] = date ( "Y/m/d H:i:s" );
					// $LayerForm ["MOD_TIME"] = date ( "Y/m/d H:i:s" );
					$ElementInfos = $LayerInfo ["elements"];
					if (! empty ( $ElementInfos )) {
						foreach ( $ElementInfos as $ElementInfo ) {
							if (($ElementInfo ["tag"] != "TITLE") && ($ElementInfo ["tag"] != "DESC")) {
								$ElementID = $Cdao_PlaneLayerElement->getNextSeqId ();
								$ElementAttr = $ElementInfo ["attributes"];
								$ElementForm = array ();
								$ElementForm ["BUILDING_ID"] = $BUILDING_ID;
								$ElementForm ["FLOOR_ID"] = $FLOOR_ID;
								$ElementForm ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
								$ElementForm ["LAYER_ID"] = $LayerID;
								$ElementForm ["ELEMENT_ID"] = $ElementID;
								$ElementForm ["SVG_ID"] = $ElementAttr ["ID"];
								$ElementForm ["ELEMENT_TOPIC"] = $ElementAttr ["ID"];
								$ElementForm ["ELEMENT_TYPE"] = $ElementInfo ["tag"];
								$ElementForm ["POI_TYPE"] = "";
								$ElementForm ["POI_ID"] = "";
								$position = SvgUtil::getSvgElementPosition ( $ElementInfo );
								if (empty ( $position )) {
									$position = array (
											"X" => 0,
											"Y" => 0 
									);
								}
								$ElementForm ["POSITION_X"] = $position ["X"];
								$ElementForm ["POSITION_Y"] = $position ["Y"];
								$ElementForm ["STATUS"] = "E"; //
								$ElementForm ["ELEMENT_TEXT"] = iconv ( 'UTF-8', 'GBK', $ElementInfo ["value"] );
								$oldlen = strlen ( $ElementForm ["ELEMENT_TEXT"] );
								$maxbytes = 256;
								$ElementForm ["ELEMENT_TEXT"] = SvgUtil::limitGbkStr ( $ElementForm ["ELEMENT_TEXT"], $maxbytes );
								$newlen = strlen ( $ElementForm ["ELEMENT_TEXT"] );
								if ($newlen < $oldlen) {
									$warning = $warning . "\n图层“" . $LayerForm ["LAYER_TOPIC"] . "”中" . $ElementForm ["ELEMENT_TYPE"] . "元素值有" . $oldlen . "字节,只保存了" . $newlen . "字节！";
								}
								if (($LayerForm ["LAYER_TYPE"] == $SVG_LAYER_TYPE->AP) && ! empty ( $APFORMLIST )) {
									$ApForm = $APFORMLIST [$ElementForm ["SVG_ID"]];
									if (! empty ( $ApForm )) {
										$ApForm = get_object_vars ( $ApForm );
										$ElementForm ["POI_TYPE"] = $SVG_LAYER_TYPE->AP;
										$ElementForm ["POI_ID"] = $Cdao_Ap->getNextSeqId ();
										$ApForm ["AP_ID"] = $ElementForm ["POI_ID"];
										$ApForm ["LAYER_ID"] = $LayerID;
										$ApForm ["BUILDING_ID"] = $BUILDING_ID;
										$ApForm ["FLOOR_ID"] = $FLOOR_ID;
										$ApForm ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
										$ApForm ["SVG_ID"] = $ElementForm ["SVG_ID"];
										$ApForm ["ELEMENT_ID"] = $ElementID;
										$ApForm ["POSITION_X"] = $ElementForm ["POSITION_X"];
										$ApForm ["POSITION_Y"] = $ElementForm ["POSITION_Y"];
										// $ApForm["EQUT_SSID"]="";
										// $ApForm["MAC_BSSID"]="";
										// $ApForm["EQUT_TYPE"]="";
										// $ApForm["FREQUENCY"]="";
										// $ApForm["CHANNEL"]="";
										// $ApForm["FACTORY"]="";
										// $ApForm["BRANDS"]="";
										// $ApForm["EQUT_MODEL"]="";
										// $ApForm["NOTE"]="";
										// $ApForm["STATUS"]="";
										$ApForm ["EQUT_SSID"] = iconv ( 'UTF-8', 'GBK', $ApForm ["EQUT_SSID"] );
										$ApForm ["EQUT_MODEL"] = iconv ( 'UTF-8', 'GBK', $ApForm ["EQUT_MODEL"] );
										$ApForm ["NOTE"] = iconv ( 'UTF-8', 'GBK', $ApForm ["NOTE"] );
										$ApForm ["MAC_BSSID"] = strtoupper ( $ApForm ["MAC_BSSID"] );
										array_push ( $ApFormList, $ApForm );
									}
								}
								// $ElementForm ["CREATE_TIME"] = date ( "Y/m/d H:i:s" );
								// $ElementForm ["MOD_TIME"] = date ( "Y/m/d H:i:s" );
								if (($LayerForm ["LAYER_TYPE"] == $SVG_LAYER_TYPE->POI) && ! empty ( $POIFORMLIST )) {
									$PoiForm = $POIFORMLIST [$ElementForm ["SVG_ID"]];
									if (! empty ( $PoiForm )) {
										$PoiForm = get_object_vars ( $PoiForm );
										$ElementForm ["POI_TYPE"] = $PoiForm ["POI_TYPE"];
										$ElementForm ["POI_ID"] = $Cdao_Poi->getNextSeqId ();
										$PoiForm ["POI_ID"] = $ElementForm ["POI_ID"];
										$PoiForm ["BUILDING_ID"] = $BUILDING_ID;
										$PoiForm ["FLOOR_ID"] = $FLOOR_ID;
										$PoiForm ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
										$PoiForm ["PIC_ID"] = 0;
										$PoiForm ["LAYER_ID"] = $LayerID;
										$PoiForm ["SVG_ID"] = $ElementForm ["SVG_ID"];
										$PoiForm ["ELEMENT_ID"] = $ElementID;
										$PoiForm ["POI_NAME"] = iconv ( 'UTF-8', 'GBK', $PoiForm ["POI_NAME"] );
										// $PoiForm ["POI_TYPE"] = iconv ( 'UTF-8', 'GBK', $PoiForm ["POI_TYPE"] );
										$PoiForm ["POI_NOTE"] = iconv ( 'UTF-8', 'GBK', $PoiForm ["POI_NOTE"] );
										$PoiForm ["COUNTRY"] = $BuildingInfo ["COUNTRY"];
										$PoiForm ["PROV"] = $BuildingInfo ["PROV"];
										$PoiForm ["CITY"] = $BuildingInfo ["CITY"];
										$PoiForm ["DISTRICT"] = $BuildingInfo ["DISTRICT"];
										$PoiForm ["ADDRESS"] = iconv ( 'UTF-8', 'GBK', $PoiForm ["ADDRESS"] );
										$PoiForm ["POSITION_X"] = $ElementForm ["POSITION_X"];
										$PoiForm ["POSITION_Y"] = $ElementForm ["POSITION_Y"];
										// $PoiForm["PHONE"]=;//
										$PoiForm ["NOTE"] = iconv ( 'UTF-8', 'GBK', $PoiForm ["NOTE"] );
										// $PoiForm["STATUS"]=;//
										// $PoiForm["CREATE_TIME"]=date("Y/m/dH:i:s");
										// $PoiForm["MOD_TIME"]=date("Y/m/dH:i:s");
										// unset ( $PoiForm ["id"] );
										if (! empty ( $SVGPICLIST )) {
											$PicInfo = $SVGPICLIST [$ElementForm ["SVG_ID"]];
											if ($PicInfo != null) {
												$PicForm = array ();
												$PicForm ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
												$PicForm ["POI_ID"] = $PoiForm ["POI_ID"];
												if ($PicInfo ["NEW_PIC"] != null) {
													if (! empty ( $_FILES )) {
														$Poi_file = $_FILES [$ElementForm ["SVG_ID"]];
														if (! empty ( $Poi_file )) {
															$Cdao_Building = new CDAOCB_BUILDING ();
															$BUILDING_NAME = $Cdao_Building->getSeqNameBySeqId ( $BUILDING_ID );
															$Cdao_Floor = new CDAOCB_FLOOR ();
															$FLOOR_NAME = $Cdao_Floor->getSeqNameBySeqId ( $FLOOR_ID );
															$Cdao_DRAW_MAP = new CDAODM_DRAW_MAP ();
															$where = " DRAW_MAP_ID=" . $DRAW_MAP_ID;
															$DRAW_MAP = $Cdao_DRAW_MAP->sel_draw_map ( $where );
															$FILE_NAME = $BUILDING_NAME . "_" . $FLOOR_NAME . "_" . $DRAW_MAP [0] ['DM_TOPIC'] . "_" . $PoiForm ["POI_NAME"] . "_" . date ( "YmdHis" );
															$PoiForm ["PIC_ID"] = SvgUtil::savePic ( $FILE_NAME, $PoiForm ["POI_ID"], $Poi_file, $FLOOR_ID, $BUILDING_ID, $DRAW_MAP_ID );
															$PicForm ["PIC_ID"] = $PoiForm ["PIC_ID"];
														}
													}
												}
												$PicInfo ["NEWPICFORM"] = $PicForm;
												$SVGPICLIST [$ElementForm ["SVG_ID"]] = $PicInfo;
											}
										}
										array_push ( $PoiFormList, $PoiForm );
									}
								}
								array_push ( $ElementFormList, $ElementForm );
								if (! empty ( $ElementAttr )) {
									foreach ( $ElementAttr as $ATTR_NAME => $ATTR_VALUE ) {
										$ATTR_VALUE = SvgUtil::getSvgElementAttrValue ( $ElementForm ["ELEMENT_TYPE"], $ATTR_NAME, $ATTR_VALUE );
										if ($ATTR_VALUE !== null) {
											$ElementAttrForm = array ();
											$ElementAttrForm ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
											$ElementAttrForm ["LAYER_ID"] = $LayerID;
											$ElementAttrForm ["ELEMENT_ID"] = $ElementID;
											$ElementAttrForm ["ATTR_NAME"] = $ATTR_NAME;
											$oldlen = strlen ( $ATTR_VALUE );
											$maxbytes = 4000;
											$ElementAttrForm ["ATTR_VALUE"] = SvgUtil::limitGbkStr ( $ATTR_VALUE, $maxbytes );
											$newlen = strlen ( $ElementAttrForm ["ATTR_VALUE"] );
											if ($newlen < $oldlen) {
												$warning = $warning . "\n图层“" . $LayerForm ["LAYER_TOPIC"] . "”中" . $ElementForm ["ELEMENT_TYPE"] . "元素的" . $ATTR_NAME . "属性值有" . $oldlen . "字节,只保存了" . $newlen . "字节！";
											}
											// $ElementAttrForm ["CREATE_TIME"] = date ( "Y/m/d H:i:s" );
											// $ElementAttrForm ["MOD_TIME"] = date ( "Y/m/d H:i:s" );
											array_push ( $ElementAttrFormList, $ElementAttrForm );
										}
									}
								}
							}
						}
					}
					array_push ( $LayerFormList, $LayerForm );
				}
			}
			$Cdao_pic = new CDAOCB_PIC ();
			$currentTime = date ( "Y/m/d H:i:s" );
			$SvgPicList = array ();
			$sql = "Begin";
			foreach ( $SVGPICLIST as $SvgId => $PicInfo ) {
				$PicId = null;
				$PicForm = $PicInfo ["NEWPICFORM"];
				if ($PicForm == null) { // 删除的元素
					if ($PicInfo ["PIC_ID"] != null) { // 修改原来图片信息
						$sql = $sql . " UPDATE {$Cdao_pic->_table} SET STATUS='X',MOD_TIME='{$currentTime}' WHERE PIC_ID=" . $PicInfo ["PIC_ID"] . ";";
					}
				} else {
					if ($PicForm ["PIC_ID"] == null) {
						$PicId = $PicInfo ["PIC_ID"];
						if ($PicId != null) { // 新增POI图片失败 或 更新原来图片信息,保留原来的,更新一些修改
							$sql = $sql . " UPDATE {$Cdao_pic->_table} SET POI_ID=" . $PicForm ["POI_ID"] . ",MOD_TIME='{$currentTime}' WHERE PIC_ID=" . $PicInfo ["PIC_ID"] . ";";
						}
					} else { // 新增POI图片成功
						$PicId = $PicForm ["PIC_ID"];
					}
				}
				if ($PicId != null) {
					$SvgPicList [$SvgId] = array (
							"PIC_ID" => $PicId 
					);
				}
			}
			$sql = $sql . " End;";
			if ($sql != "Begin End;") {
				$errormsgupdatepic = null;
				try {
					$result = $Cdao_pic->DB ()->Execute ( $sql );
				} catch ( Exception $e ) {
					$errormsgupdatepic = "更新PIC数据库错误：";
					$errormsgupdatepic = $errormsgupdatepic . $e->getMessage ();
				}
			}
			$sql = "Begin";
			$insertsqlkey = null;
			$insertsqlvalue = null;
			$updatesqldata = null;
			foreach ( $SvgForm as $k => $v ) {
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
			$sql = $sql . " UPDATE {$Cdao_PlaneLayer->_table} SET STATUS='" . SvgUtil::STATUS_CANCEL . "' WHERE DRAW_MAP_ID={$DRAW_MAP_ID} AND STATUS='" . SvgUtil::STATUS_NORMAL . "';";
			$sql = $sql . " " . SvgUtil::getInsertSqlViaFormList ( $LayerFormList, $Cdao_PlaneLayer->_table );
			$sql = $sql . " UPDATE {$Cdao_PlaneLayerElement->_table} SET STATUS='" . SvgUtil::STATUS_CANCEL . "' WHERE DRAW_MAP_ID={$DRAW_MAP_ID} AND STATUS='" . SvgUtil::STATUS_NORMAL . "';";
			$sql = $sql . " " . SvgUtil::getInsertSqlViaFormList ( $ElementFormList, $Cdao_PlaneLayerElement->_table );
			$Cdao_PlaneLayerElementAttr = new CDAODM_LAYER_ELEMENT_ATTR ();
			$sql = $sql . " DELETE {$Cdao_PlaneLayerElementAttr->_table} WHERE DRAW_MAP_ID={$DRAW_MAP_ID};";
			$sql = $sql . " " . SvgUtil::getInsertSqlViaFormList ( $ElementAttrFormList, $Cdao_PlaneLayerElementAttr->_table, false, false );
			$sql = $sql . " DELETE {$Cdao_Ap->_table} WHERE DRAW_MAP_ID={$DRAW_MAP_ID};";
			$sql = $sql . " " . SvgUtil::getInsertSqlViaFormList ( $ApFormList, $Cdao_Ap->_table );
			$sql = $sql . " DELETE {$Cdao_Poi->_table} WHERE DRAW_MAP_ID={$DRAW_MAP_ID};";
			$sql = $sql . " " . SvgUtil::getInsertSqlViaFormList ( $PoiFormList, $Cdao_Poi->_table );
			$sql = $sql . " End;";
			if ($sql != "Begin End;") {
				try {
					$result = $Cdao_DrawMap->DB ()->Execute ( $sql );
				} catch ( Exception $e ) {
					$error = "执行数据库错误：";
					$error = $error . $e->getMessage ();
				}
				if ($result === false) {
					$error = "插入数据库出错!";
				}
			}
		} else {
			$error = "参数不正确！";
		}
		$response = array ();
		if (empty ( $error )) {
			$error = $this->saveSvgSrcAndPng ( $BUILDING_ID, $FLOOR_ID, $DRAW_MAP_ID, $DM_TOPIC, $SVGSRC, $PNGBASE64, $BACKGROUD_COLOR );
			$response ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
			$response ["SVGPICLISTJSONSTR"] = empty ( $SvgPicList ) ? "{}" : json_encode ( $SvgPicList );
		}
		if (! empty ( $errormsgupdatepic )) {
			$error = ($error === null) ? $errormsgupdatepic : ($error . $errormsgupdatepic);
		}
		if (! empty ( $error )) {
			$response ["error"] = iconv ( 'GBK', 'UTF-8', $error );
		}
		if (! empty ( $warning )) {
			$response ["warning"] = iconv ( 'GBK', 'UTF-8', $warning );
		}
		echo (json_encode ( $response ));
	}
	/**
	 * 下载SVG源代码
	 *
	 * @return string json 平面图属性信息，出错信息
	 */
	public function actionAjaxDownLoadSvg() {
		$BUILDING_ID = $_POST ["BUILDING_ID"];
		$FLOOR_ID = $_POST ["FLOOR_ID"];
		$DRAW_MAP_ID = $_POST ["DRAW_MAP_ID"];
		$response = SvgUtil::getSvg ( $BUILDING_ID, $FLOOR_ID, $DRAW_MAP_ID );
		$response = CIconv::gbksToUtf8s ( $response );
		echo (json_encode ( $response ));
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
			$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
			$DRAW_MAP_ID = $Cdao_DrawMap->getSvgDrawMapId ( $FLOOR_ID );
			if (empty ( $DRAW_MAP_ID )) {
				$DRAW_MAP_ID = null;
			}
		}
		$array ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
		echo (json_encode ( $array ));
	}
	/**
	 * 保存svg源码和png图片
	 */
	public function saveSvgSrcAndPng($BUILDING_ID, $FLOOR_ID, $DRAW_MAP_ID, $DM_TOPIC, $SVGSRC, $PNGBASE64, $BACKGROUD_COLOR) {
		$result = SvgUtil::createPicPathByBuilding ( MEDIALIB_PATH, $BUILDING_ID );
		$FILE_PATH = $result ["path"];
		if ($FILE_PATH !== null) {
			$PATH = $result ["dirs"];
			$errormsg = "";
			$tag = "data:image/png;base64,";
			$PNGSRC = base64_decode ( substr ( $PNGBASE64, strlen ( $tag ) ) );
			$CDAO_Pic = new CDAOCB_PIC ();
			$TYPELIST = array ();
			$TYPELIST [$CDAO_Pic->FILE_PNG] = $PNGSRC;
			$TYPELIST [$CDAO_Pic->FILE_SVG] = $SVGSRC;
			$TYPELIST [$CDAO_Pic->FILE_THM] = $PNGSRC;
			$Cdao_Building = new CDAOCB_BUILDING ();
			$BUILDING_NAME = $Cdao_Building->getSeqNameBySeqId ( $BUILDING_ID );
			$Cdao_Floor = new CDAOCB_FLOOR ();
			$FLOOR_NAME = $Cdao_Floor->getSeqNameBySeqId ( $FLOOR_ID );
			$FORM_INFO = array ();
			$FORM_INFO ["BUILDING_ID"] = $BUILDING_ID;
			$FORM_INFO ["FLOOR_ID"] = $FLOOR_ID;
			$FORM_INFO ["DRAW_MAP_ID"] = $DRAW_MAP_ID;
			$FORM_INFO ["POI_ID"] = ""; //
			$FORM_INFO ["PIC_TOPIC"] = $DM_TOPIC;
			$FORM_INFO ["PIC_NOTE"] = "";
			$FORM_INFO ["STATUS"] = "A";
			$FORM_INFO ["PATH"] = $PATH;
			$FILE_NAME = $BUILDING_NAME . "_" . $FLOOR_NAME . "_" . $DM_TOPIC . "_" . date ( "YmdHis" );
			$FILE_NAME_LIST [$CDAO_Pic->FILE_PNG] = $FILE_NAME . "_原图";
			$FILE_NAME_LIST [$CDAO_Pic->FILE_SVG] = $FILE_NAME . "_SVG";
			$FILE_NAME_LIST [$CDAO_Pic->FILE_THM] = $FILE_NAME . "_缩略图";
			foreach ( $TYPELIST as $PIC_TYPE => $SRC ) {
				$FORM_INFO ["FILENAME"] = $FILE_NAME_LIST [$PIC_TYPE];
				$FILE_FULLPATH = $FILE_PATH . "/" . $FILE_NAME_LIST [$PIC_TYPE] . ("." . (($CDAO_Pic->FILE_THM === $PIC_TYPE) ? $CDAO_Pic->FILE_PNG : $PIC_TYPE));
				$FILE_FULLPATH = iconv ( 'GBK', 'UTF-8', $FILE_FULLPATH );
				$fp = false;
				if ($CDAO_Pic->FILE_THM === $PIC_TYPE) {
					$fp = SvgUtil::savePngSrcToPath ( $SRC, $BACKGROUD_COLOR, $CDAO_Pic->FILE_THM_SIZE, $FILE_FULLPATH );
				} else {
					if ($CDAO_Pic->FILE_PNG === $PIC_TYPE) {
						$fp = SvgUtil::savePngSrcToPath ( $SRC, $BACKGROUD_COLOR, null, $FILE_FULLPATH );
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
					$MIME_TYPE = CDAOCB_PIC::getPicMimeType ( $FILE_FULLPATH );
					if (empty ( $MIME_TYPE )) {
						$MIME_TYPE = "NULL";
						$errormsg = $errormsg . "获取" . $PIC_TYPE . "文件的MIME类型失败！";
					}
					$PIC_ID = $CDAO_Pic->getSeqIdByWhere ( "DRAW_MAP_ID='{$DRAW_MAP_ID}' AND PIC_TYPE='{$PIC_TYPE}'" . $CDAO_Pic->getPicClassSqlWhere ( "DRAW_MAP_ID" ) );
					$FORM_INFO ["MIME_TYPE"] = $MIME_TYPE;
					$FORM_INFO ["PIC_ID"] = $PIC_ID;
					if (empty ( $PIC_ID )) {
						$PIC_ID = $CDAO_Pic->getNextSeqId ();
						$FORM_INFO ["{$CDAO_Pic->_table_seq_name}"] = $PIC_ID;
						$FORM_INFO ["CREATE_TIME"] = date ( "Y-m-d H:i:s" );
					}
					$FORM_INFO ["PIC_TYPE"] = $PIC_TYPE;
					$FORM_INFO ["FILESIZE"] = strlen ( $SRC );
					$updatesql = null;
					try {
						$CDAO_Pic->doEdit ( $FORM_INFO );
						if ($PIC_TYPE == $CDAO_Pic->FILE_SVG) {
							$Cdao_DrawMap = new CDAODM_DRAW_MAP ();
							$updatesql = "UPDATE {$Cdao_DrawMap->_table} SET PIC_ID='{$PIC_ID}' WHERE DRAW_MAP_ID={$DRAW_MAP_ID}";
						}
						// if ($PIC_TYPE == $CDAO_Pic->FILE_PNG) {
						// $Cdao_Poi = new CDAOCB_POI ();
						// $updatepoisql = "UPDATE {$Cdao_Poi->_table} SET PIC_ID='{$PIC_ID}' WHERE DRAW_MAP_ID={$DRAW_MAP_ID}";
						// $updatesql = empty ( $updatesql ) ? $updatepoisql : $updatesql . " " . $updatepoisql;
						// }
					} catch ( Exception $e ) {
						$errormsg = $errormsg . "保存" . $PIC_TYPE . "文件时，更新数库库出错！";
						if (! empty ( $e )) {
							$errormsg = $errormsg . $e->getMessage ();
						}
					}
					if (! empty ( $updatesql )) {
						try {
							$result = $CDAO_Pic->DB ()->Execute ( $updatesql );
						} catch ( Exception $e ) {
							$errormsg = $errormsg . "更新PIC_ID时，执行数据库错误：";
							if (! empty ( $e )) {
								$errormsg = $errormsg . $e->getMessage ();
							}
						}
					}
				} else {
					$errormsg = $errormsg . "保存" . $PIC_TYPE . "文件失败！";
					if (! empty ( $e )) {
						$errormsg = $errormsg . $e->getMessage ();
					}
				}
			}
			return ($errormsg);
		} else {
			return ("存储svg源代码和png图片时，" . $result ["error"]);
		}
	}
	/**
	 * 查看svg原图
	 */
	public function actionlookBigMap() {
		// svg原图文件路径
		$filename = SF_ROOT . $_GET ["svgPath"];
		$filename = iconv ( "GBK", "UTF-8", $filename );
		$fp = fopen ( $filename, "rb" );
		$filetxt = fread ( $fp, filesize ( $filename ) );
		$filetxt = iconv ( "UTF-8", "GBK", $filetxt );
		fclose ( $fp );
		// svg图层类型
		$Cdao_Type = new CDAOSYS_TYPE_CODE ();
		$typeList = $Cdao_Type->getAllType ();
		$typeArray = array ();
		if (! empty ( $typeList )) {
			foreach ( $typeList as $k => $v ) {
				$typeArray [$v ['CODE_TYPE']] = iconv ( 'GBK', 'UTF-8', $v ['CODE_NAME'] );
			}
		}
		// svg弹出原图窗口的宽和高
		$pageData ["width"] = $_GET ["width"];
		$pageData ["height"] = $_GET ["height"];
		$pageData ["typeJson"] = json_encode ( $typeArray );
		$pageData ["filetxt"] = $filetxt;
		$this->render ( "svg_big", $pageData );
	}
}
?>