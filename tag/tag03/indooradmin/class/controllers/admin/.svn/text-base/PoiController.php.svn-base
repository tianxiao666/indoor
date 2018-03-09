<?php
/**
 * POI管理类
 * @author liang.jf
 */
class PoiController extends AdminController {
	public $_page = 1; // 当前页
	public $_count = 15; // 每页个数
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * POI信息列表
	 * Enter description here .
	 */
	public function actionIndex() {
		$Flag = $_GET ["flag"];
		$floor_id = $_GET ["FLOOR_ID"];
		if ($_POST ['FLOOR_ID'] != '') {
			$floor_id = $_POST ['FLOOR_ID'];
		}
		// 搜索查询
		if ($Flag == 1) {
			$pageData ['whereSql'] = $_POST;
			CUserSession::removeSessionValue ( 'POIconditions' );
			CUserSession::setSessionValue ( 'POIconditions', $_POST );
		}
		// 非搜索查询
		if ($Flag == 2) {
			$_POST = CUserSession::getSessionValue ( 'POIconditions' );
			$pageData ['whereSql'] = $_POST;
		}
		if ($_GET ['page']) {
			$this->_page = $_GET ['page']; // 获取分页
		}
		if ($_GET ['NAME']) {
			$_POST ['NAME'] = $_GET ['NAME'];
		}
		$cdaocb_poi = new CDAOCB_POI ();
		$cdaocb_floor = new CDAOCB_FLOOR ();
		if ($floor_id == '') {
			$whereSql = $this->genWhereSqlSearch ( $_POST );
			$Poi_list = $cdaocb_poi->getPoi_list ( $this->_count, $this->_page, $whereSql, $_GET ['BUILDING_ID'] ); // 取当前场所所有楼层POI数据
		} else {
			$FLOOR_id = $cdaocb_floor->getFloor_name ( $floor_id );
			$pageData ['FLOOR_NAME'] = $FLOOR_id [0] ['FLOOR_NAME'];
			$pageData ['FLOOR_ID'] = $floor_id;
			$Poi_list = $cdaocb_poi->get_floor_poi_list ( $this->_count, $this->_page, $floor_id ); // 取当前楼层POI数据
		}
		// 生成该场所楼层选择项
		$FLOOR_ID = $cdaocb_floor->getFloor_id ( $_GET ['BUILDING_ID'] );
		$pageData ['FLOOR_ID_LIST'] = $this->getFloor_sel_list ( $floor_id, $_GET ['BUILDING_ID'], false );
		$total = $Poi_list->_maxRecordCount;
		$p = new page ( $total );
		$baseUrlFlag = $_GET ["page"];
		$p->baseUrl = 'ea.php?r=Poi&BUILDING_ID=' . $_GET ['BUILDING_ID'] . '&BUILDING_NAME=' . $_GET ['BUILDING_NAME'] . '&flag=2';
		$p->pagesize = $this->_count;
		$page = $p->generate ();
		$cdict = new CDict ();
		// 取POI的类型和状态
		$pageData ["POI_TYPE"] = $cdict->POI_TYPE;
		$pageData ["POI_STATUS"] = $cdict->POI_STATUS;
		$pageData ['total'] = $total;
		$pageData ['page'] = $page;
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['poi_list'] = $Poi_list->_array;
		// 所在楼层显示
		if ($pageData ['poi_list'] !== null) {
			foreach ( $pageData ['poi_list'] as $k => $v ) {
				if ($FLOOR_ID) {
					foreach ( $FLOOR_ID as $k_floor => $v_floor ) {
						if ($pageData ['poi_list'] [$k] ['FLOOR_ID'] == $FLOOR_ID [$k_floor] ['FLOOR_ID']) {
							$pageData ['poi_list'] [$k] ['FLOOR_ID'] = $FLOOR_ID [$k_floor] ['FLOOR_NAME'] . "(" . $FLOOR_ID [$k_floor] ['PHYSICAL_FLOOR'] . "层)";
						}
					}
				}
			}
		}
		// 限制字符串显示长度
		if ($pageData ['poi_list'] !== null) {
			foreach ( $pageData ['poi_list'] as $k => $v ) {
				if (strlen ( $pageData ['poi_list'] [$k] ['POI_NAME'] ) > 16) {
					$pageData ['poi_list'] [$k] ['POI_NAME'] = iconv_substr ( $pageData ['poi_list'] [$k] ['POI_NAME'], 0, 6, "GBK" ) . "...";
				}
				if (strlen ( $pageData ['poi_list'] [$k] ['ADDRESS'] ) > 48) {
					$pageData ['poi_list'] [$k] ['ADDRESS'] = iconv_substr ( $pageData ['poi_list'] [$k] ['ADDRESS'], 0, 22, "GBK" ) . "...";
				}
			}
		}
		$this->render ( "poi_list", $pageData );
	}
	
	/**
	 * 楼层下拉选项,$value为判断是否指定楼层
	 *
	 * @param unknown $floor_id        	
	 * @param unknown $BUILDING_ID        	
	 * @return string
	 */
	public function getFloor_sel_list($floor_id, $BUILDING_ID, $value) {
		$cdaocb_floor = new CDAOCB_FLOOR ();
		$FLOOR_ID_LIST = $cdaocb_floor->getFloor_id ( $BUILDING_ID );
		// 生成楼层选择下拉框
		if ($value) {
			$floor_sel_list = "";
		} else {
			$floor_sel_list = "<option value='' selected=''>-请选择-</option>";
		}
		if ($FLOOR_ID_LIST) {
			foreach ( $FLOOR_ID_LIST as $k => $v ) {
				if ($v ['FLOOR_ID'] == $floor_id && $floor_id != '') {
					$floor_sel_list = $floor_sel_list . "<option value='" . $v ['FLOOR_ID'] . "' selected=''>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
				} else {
					$floor_sel_list = $floor_sel_list . "<option value='" . $v ['FLOOR_ID'] . "'>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
				}
			}
		}
		return $floor_sel_list;
	}
	
	/**
	 * 新增POI页面
	 * Enter description here .
	 */
	public function actionAddPoi() {
		$floor_id = $_GET ['FLOOR_ID'];
		// 查询确定floor_id的平面图下拉选项
		$pageData ["DRAW_MAP_ID_LIST"] = $this->getFloorDraw_Map ( $floor_id, "", true );
		if ($floor_id) {
			$value = true;
		} else {
			$value = false;
		}
		// 生成该场所的楼层选择项
		$pageData ['FLOOR_ID'] = $this->getFloor_sel_list ( $floor_id, $_GET ['BUILDING_ID'], $value );
		// 获取POI类型和状态数据
		$cdict = new CDict ();
		$pageData ["POI_STATUS"] = $cdict->POI_STATUS;
		$pageData ["POI_TYPE"] = $cdict->POI_TYPE;
		$pageData ['floor_id_sel'] = $floor_id;
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$this->render ( "poi_add", $pageData );
	}
	
	/**
	 * 根据FLOOR_ID取相应的DRAW_MAP_ID
	 */
	public function actionchoose_floor_id() {
		$DRAW_MAP_ID_LIST = $this->getFloorDraw_Map ( $_GET ['FLOOR_ID'], "", true );
		echo iconv ( 'GB2312', 'UTF-8', $DRAW_MAP_ID_LIST );
	}
	/**
	 * 获取楼层平面图
	 * 
	 * @param unknown $floor_id        	
	 * @param unknown $DRAW_MAP_ID        	
	 * @param unknown $key        	
	 * @return string
	 */
	public function getFloorDraw_Map($floor_id, $DRAW_MAP_id, $key) {
		if ($floor_id) {
			$cdaocb_DRAW_MAP_ID = new CDAODM_DRAW_MAP ();
			$sel_draw_map_sql = "FLOOR_ID=" . $floor_id;
			$DRAW_MAP_ID = $cdaocb_DRAW_MAP_ID->sel_draw_map ( $sel_draw_map_sql );
			$DRAW_MAP_ID_LIST = "";
			// 生成该楼层平面图下拉选项，默认选中设备已在平面图
			if ($DRAW_MAP_ID) {
				if ($key) {
					$DRAW_MAP_ID_LIST = "<option value=''>-请选择-</option>";
				}
				foreach ( $DRAW_MAP_ID as $k => $v ) {
					if ($v ['DRAW_MAP_ID'] == $DRAW_MAP_id) {
						$DRAW_MAP_ID_LIST = $DRAW_MAP_ID_LIST . "<option value='" . $v ['DRAW_MAP_ID'] . "' selected=''>" . $v ['DM_TOPIC'] . "</option>";
					} else {
						$DRAW_MAP_ID_LIST = $DRAW_MAP_ID_LIST . "<option value='" . $v ['DRAW_MAP_ID'] . "'>" . $v ['DM_TOPIC'] . "</option>";
					}
				}
			} else {
				$DRAW_MAP_ID_LIST = $DRAW_MAP_ID_LIST . "<option value=''>-无楼层平面图-</option>";
			}
		} else {
			$DRAW_MAP_ID_LIST = "<option value=''>-请选择所属楼层-</option>";
		}
		return $DRAW_MAP_ID_LIST;
	}
	/**
	 * 根据DRAW_MAP_ID取相应的POI_LAYER再取SVG_ID
	 */
	public function actionchoose_DRAW_MAP_ID() {
		$DRAW_MAP_ID = $_GET ['DRAW_MAP_ID'];
		$sql_layer_id = " DRAW_MAP_ID=" . $DRAW_MAP_ID . " and LAYER_TYPE='POI' and STATUS<>'X'";
		$cdaocb_LAYER_ID = new CDAODM_PLANE_LAYER ();
		$LAYER_ID = $cdaocb_LAYER_ID->sel_layer_id ( $sql_layer_id );
		if ($LAYER_ID) {
			$POI_LAYER = "<option value='" . $LAYER_ID [0] ['LAYER_ID'] . "'>-POI图层-</option>";
			// 根据图层id查找该图层的元素列表
			$SVG_ID_LIST = $this->selSVG_ID ( "", $LAYER_ID [0] ['LAYER_ID'] );
		} else {
			$POI_LAYER = "<option value=''>-无POI图层-</option>";
			$SVG_ID_LIST = "<option value=''>-请选择-</option>";
		}
		$result = array (
				"POI_LAYER" => iconv ( 'GB2312', 'UTF-8', $POI_LAYER ),
				"SVG_ID_LIST" => iconv ( 'GB2312', 'UTF-8', $SVG_ID_LIST ) 
		);
		echo json_encode ( $result );
	}
	
	/**
	 * 获取元素下拉选项
	 * Enter description here .
	 */
	public function selSVG_ID($svg_id, $layer_id) {
		if ($layer_id) {
			$sql_svg_id = " LAYER_ID=" . $layer_id;
			$cdaocb_LAYER_ELEMENT = new CDAODM_LAYER_ELEMENT ();
			$SVG_ID = $cdaocb_LAYER_ELEMENT->sel_svg_id ( $sql_svg_id );
			$SVG_ID_LIST = "";
			// 生成该AP图层的图层元素下拉选项，默认选中设备所属的图层元素
			if ($SVG_ID) {
				if ($svg_id == '') {
					$SVG_ID_LIST = "<option value=''>-请选择-</option>";
				}
				foreach ( $SVG_ID as $k => $v ) {
					if ($v ['SVG_ID'] == $svg_id) {
						$SVG_ID_LIST = $SVG_ID_LIST . "<option value='" . $v ['SVG_ID'] . "' selected=''>" . $v ['ELEMENT_TYPE'] . "(" . $v ['SVG_ID'] . ")</option>";
					} else {
						$SVG_ID_LIST = $SVG_ID_LIST . "<option value='" . $v ['SVG_ID'] . "'>" . $v ['ELEMENT_TYPE'] . "(" . $v ['SVG_ID'] . ")</option>";
					}
				}
			} else {
				$SVG_ID_LIST = $SVG_ID_LIST . "<option value=''>-请选择-</option>";
			}
		} else {
			$SVG_ID_LIST = "<option value=''>-请选择-</option>";
		}
		return $SVG_ID_LIST;
	}
	
	/**
	 * 保存新增POI数据
	 * Enter description here .
	 */
	public function actionPoiAdd() {
		$_POST = CDAOCB_BUILDING::stripForm ( $_POST );//去掉多余的空格
		$data = $_POST;
		$floor_id = $_GET ['FLOOR_ID'];
		$CdaoPoi = new CDAOCB_POI ();
		$where_svg_id = " SVG_ID='" . $_POST ['SVG_ID'] . "'";
		$aleryBuild = $CdaoPoi->getAllWhere ( "SVG_ID", $where_svg_id );
		if ($aleryBuild) {
			$this->jsAlert ( "该元素已有建立POI!" );
			$this->jsJumpBack ();
			return false;
		}
		$POI_ID = $CdaoPoi->getNextSeqId ();
		$data ['POI_ID'] = $POI_ID;
		// 保存图片信息
		if ($_FILES ["file"] ['name']) {
			$Cdao_Floor = new CDAOCB_FLOOR ();
			$FLOOR_NAME = $Cdao_Floor->getSeqNameBySeqId ( $_POST ['FLOOR_ID'] );
			$Cdao_DRAW_MAP = new CDAODM_DRAW_MAP ();
			$where = " DRAW_MAP_ID=" . $_POST ['DRAW_MAP_ID'];
			$DRAW_MAP = $Cdao_DRAW_MAP->sel_draw_map ( $where );
			$FILE_NAME = $_GET ['BUILDING_NAME'] . "_" . $FLOOR_NAME . "_" . $DRAW_MAP [0] ['DM_TOPIC'] . "_" . $_POST ['POI_NAME'] . "_" . date ( "YmdHis" );
			$picAdd_returnPic_id = SvgUtil::savePic ( $FILE_NAME, $POI_ID, $_FILES ["file"], $_POST ['FLOOR_ID'], $_GET ['BUILDING_ID'], $_POST ['DRAW_MAP_ID'] ); // 返回PIC_ID
			if ($picAdd_returnPic_id == '') {
				$this->jsAlert ( "保存失败！" );
				$this->jsJumpBack ();
			}
			$data ['PIC_ID'] = $picAdd_returnPic_id;
		}
		$Cdao_Building = new CDAOCB_BUILDING ();
		$location = $Cdao_Building->infoBuilding ( $_GET ['BUILDING_ID'] );
		$data ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$data ['COUNTRY'] = $location ['COUNTRY'];
		$data ['PROV'] = $location ['CONTINENT'];
		$data ['CITY'] = $location ['CITY'];
		$data ['DISTRICT'] = $location ['DISTRICT'];
		$PoiData = $CdaoPoi->addPoi_list ( $data );
		if ($PoiData) {
			$this->jsAlert ( "添加成功" );
			if ($floor_id) {
				$this->jsCall ( "parent.location.href = 'ea.php?r=Poi&FLOOR_ID={$_GET['FLOOR_ID']}&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			} else {
				$this->jsCall ( "parent.location.href = 'ea.php?r=Poi&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}
		} else {
			$this->jsAlert ( "添加失败!" );
			$this->jsJumpBack ();
			return false;
		}
	}
	
	/**
	 * 编辑POI页面
	 * Enter description here .
	 */
	public function actionEditPoi() {
		$floor_id = $_GET ['FLOOR_ID'];
		$CdaoPoi_list = new CDAOCB_POI ();
		$PoiInfo = $CdaoPoi_list->getPoiData ( $_GET ['POI_ID'] );
		$PoiInfo [0] ['POI_NAME'] = str_replace ( '"', '&quot', $PoiInfo [0] ['POI_NAME'] );
		$PoiInfo [0] ['ADDRESS'] = str_replace ( '"', '&quot', $PoiInfo [0] ['ADDRESS'] );
		// 生成该场所的楼层选择项
		$pageData ['FLOOR_ID'] = $this->getFloor_sel_list ( $PoiInfo [0] ['FLOOR_ID'], $_GET ['BUILDING_ID'], true );
		// 查询该POI所属楼层的楼层平面图信息
		$pageData ['DRAW_MAP_ID_LIST'] = $this->getFloorDraw_Map ( $PoiInfo [0] ['FLOOR_ID'], $PoiInfo [0] ['DRAW_MAP_ID'], false );
		// 查找该POI所属平面图的POI图层
		$pageData ['LAYER_ID_LIST'] = $this->getPoiLayer ( $PoiInfo [0] ['DRAW_MAP_ID'] );
		// 查找该POI所在的POI图层的元素信息
		$SVG_ID_LIST = $this->selSVG_ID ( $PoiInfo [0] ['SVG_ID'], $PoiInfo [0] ['LAYER_ID'] );
		$pageData ['SVG_ID_LIST'] = $SVG_ID_LIST;
		$cdict = new CDict ();
		$pageData ["POI_STATUS"] = $cdict->POI_STATUS;
		$pageData ["POI_TYPE"] = $cdict->POI_TYPE;
		$pageData ['floor_id_sel'] = $floor_id;
		$CODE_TYPE = array ();
		$pageData ['CODE_TYPE'] = $CODE_TYPE;
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['poi_list'] = $PoiInfo;
		$this->render ( "poi_change", $pageData );
	}
	/**
	 * 获取平面图POI图层
	 *
	 * @param unknown $DRAW_MAP_ID        	
	 * @return string
	 */
	public function getPoiLayer($DRAW_MAP_ID) {
		if ($DRAW_MAP_ID) {
			$sql_layer_id = "DRAW_MAP_ID=" . $DRAW_MAP_ID . "and LAYER_TYPE='POI'";
			$cdaocb_LAYER_ID = new CDAODM_PLANE_LAYER ();
			$LAYER_ID = $cdaocb_LAYER_ID->sel_layer_id ( $sql_layer_id );
			$LAYER_ID_LIST = "";
			if ($LAYER_ID) {
				$LAYER_ID_LIST = $LAYER_ID_LIST . "<option value='" . $LAYER_ID [0] ['LAYER_ID'] . "' selected=''>-POI图层-</option>";
			} else {
				$LAYER_ID_LIST = $LAYER_ID_LIST . "<option value=''>-无POI图层-</option>";
			}
		} else {
			$LAYER_ID_LIST = "<option value=''>-无POI图层-</option>";
		}
		return $LAYER_ID_LIST;
	}
	/**
	 * 保存编辑POI
	 * Enter description here .
	 */
	public function actionSavePoi() {
		$_POST = CDAOCB_BUILDING::stripForm ( $_POST );//去掉多余的空格
		$data = $_POST;
		$floor_id_sel = $_GET ['FLOOR_ID'];
		$CdaoPoi = new CDAOCB_POI ();
		if ($_POST ['OLD_SVG_ID'] != $_POST ['SVG_ID']) {
			$where_svg_id = " SVG_ID='" . $_POST ['SVG_ID'] . "'";
			$aleryBuild = $CdaoPoi->getAllWhere ( "SVG_ID", $where_svg_id );
			if ($aleryBuild) {
				$this->jsAlert ( "该元素已有建立POI!" );
				$this->jsJumpBack ();
				return false;
			}
		}
		$Cdao_Building = new CDAOCB_BUILDING ();
		// 保存图片数据
		if ($_FILES ["file"] ['name']) {
			$Cdao_Floor = new CDAOCB_FLOOR ();
			$FLOOR_NAME = $Cdao_Floor->getSeqNameBySeqId ( $_POST ['FLOOR_ID'] );
			$Cdao_DRAW_MAP = new CDAODM_DRAW_MAP ();
			$where = " DRAW_MAP_ID=" . $_POST ['DRAW_MAP_ID'];
			$DRAW_MAP = $Cdao_DRAW_MAP->sel_draw_map ( $where );
			$FILE_NAME = $_GET ['BUILDING_NAME'] . "_" . $FLOOR_NAME . "_" . $DRAW_MAP [0] ['DM_TOPIC'] . "_" . $_POST ['POI_NAME'] . "_" . date ( "YmdHis" );
			$picAdd_returnPic_id = SvgUtil::savePic ( $FILE_NAME, $_POST ['POI_ID'], $_FILES ["file"], $_POST ['FLOOR_ID'], $_GET ['BUILDING_ID'], $_POST ['DRAW_MAP_ID'] ); // 返回PIC_ID
			if ($picAdd_returnPic_id == '') {
				$this->jsAlert ( "保存失败！" );
				$this->jsJumpBack ();
			}
			$data ['PIC_ID'] = $picAdd_returnPic_id;
		}
		$location = $Cdao_Building->infoBuilding ( $_GET ['BUILDING_ID'] );
		if ($_POST ['POI_ID']) {
			$data ['POI_ID'] = $_POST ['POI_ID'];
			$data ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
			$data ['COUNTRY'] = $location ['COUNTRY'];
			$data ['PROV'] = $location ['CONTINENT'];
			$data ['CITY'] = $location ['CITY'];
			$data ['DISTRICT'] = $location ['DISTRICT'];
		}
		$result = $CdaoPoi->updatePoiInfo ( $data );
		if ($result) {
			$this->jsAlert ( "修改成功" );
			if ($floor_id_sel) {
				$this->jsCall ( "parent.location.href = 'ea.php?r=Poi&FLOOR_ID={$_GET['FLOOR_ID']}&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			} else {
				$this->jsCall ( "parent.location.href = 'ea.php?r=Poi&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}
		} else {
			$this->jsAlert ( "修改失败!" );
			return false;
		}
	}
	
	/**
	 * 修改POI状态
	 * Enter description here .
	 */
	public function actionChangeStateAll() {
		$poi_id_list = explode ( ",", $_POST ['poi_id_list'] ); // 生成POI_ID数组
		$length = count ( $poi_id_list );
		$CdaoPoi = new CDAOCB_POI ();
		$_POST ['status'] = $_POST ['status'] ? iconv ( 'UTF-8', 'GBK', $_POST ['status'] ) : $_POST ['status'];
		$where = null;
		for($i = 0; $i < $length - 1; $i ++) {
			if ($where == null) {
				$where = 'POI_ID=' . $poi_id_list [$i];
			} else {
				$where = $where . ' or POI_ID=' . $poi_id_list [$i];
			}
		}
		$result = $CdaoPoi->SaveStatus ( $where, $_POST ['status'] );
		if (! $result) {
			$status = "状态无需修改!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
			return false;
		}
		$result = $CdaoPoi->ChangeStatus ( $where, $_POST ['status'] );
		if ($result) {
			$status = "修改成功!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
		} else {
			$status = "修改失败!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
			return false;
		}
		return;
	}
	/**
	 * 搜索POI信息SQL
	 * Enter description here .
	 */
	public function genWhereSqlSearch($POST) {
		$POST ['NAME'] = strtolower ( $POST ['NAME'] );
		$whereSql = " 1=1 ";
		if ($POST ['NAME']) {
			$POST ['NAME'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $POST ['NAME'] ) ) );
			$whereSql .= " AND lower(POI_NAME) like '%{$POST['NAME']}%'";
		}
		if ($POST ['POI_TYPE']) {
			$POST ['POI_TYPE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $POST ['POI_TYPE'] ) ) );
			$whereSql .= " AND POI_TYPE = '{$POST['POI_TYPE']}' ";
		}
		if ($POST ['STATUS']) {
			$POST ['STATUS'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $POST ['STATUS'] ) ) );
			$whereSql .= " AND STATUS = '{$POST['STATUS']}' ";
		}
		if ($POST ['FLOOR_ID']) {
			$POST ['FLOOR_ID'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $POST ['FLOOR_ID'] ) ) );
			$whereSql .= " AND FLOOR_ID = '{$POST['FLOOR_ID']}' ";
		}
		return $whereSql;
	}
}