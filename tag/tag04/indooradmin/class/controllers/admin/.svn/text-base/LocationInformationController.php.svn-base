<?php
/**
 * 定位信息管理类
 * @author liang.jf
 */
class LocationInformationController extends AdminController {
	public $_page = 1; // 当前页
	public $_count = 15; // 每页个数
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 定位信息列表
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
			CUserSession::removeSessionValue ( 'euqtconditions' );
			CUserSession::setSessionValue ( 'euqtconditions', $_POST );
		}
		// 非搜索查
		if ($Flag == 2) {
			$_POST = CUserSession::getSessionValue ( 'euqtconditions' );
			$pageData ['whereSql'] = $_POST;
		}
		$cdaocb_location = new CDAOCB_LOCATION ();
		if ($_GET ['page']) {
			$this->_page = $_GET ['page']; // 获取分页
		}
		if ($_GET ['NAME']) {
			$_POST ['NAME'] = $_GET ['NAME'];
		}
		$cdaocb_floor = new CDAOCB_FLOOR ();
		if ($floor_id == '') {
			$whereSql = $this->genWhereSqlSearch ( $_POST );
			$Equt_list = $cdaocb_location->getEqut_list ( $this->_count, $this->_page, $whereSql, $_GET ['BUILDING_ID'] ); // 取相应楼层数据
		} else {
			$FLOOR_id = $cdaocb_floor->getFloor_name ( $floor_id );
			$pageData ['FLOOR_NAME'] = $FLOOR_id [0] ['FLOOR_NAME'];
			$pageData ['FLOOR_ID'] = $floor_id;
			$Equt_list = $cdaocb_location->get_floor_equt_list ( $this->_count, $this->_page, $floor_id ); // 取当前楼层AP数据
		}
		$FLOOR_ID = $cdaocb_floor->getFloor_id ( $_GET ['BUILDING_ID'] );
		// 生成楼层选择下拉框
		$pageData ['FLOOR_ID_LIST'] = $this->getFloor_sel_list ( $floor_id, $_GET ['BUILDING_ID'], "" );
		$total = $Equt_list->_maxRecordCount;
		$p = new page ( $total );
		$baseUrlFlag = $_GET ["page"];
		$p->baseUrl = 'ea.php?r=LocationInformation&BUILDING_ID=' . $_GET ['BUILDING_ID'] . '&BUILDING_NAME=' . $_GET ['BUILDING_NAME'] . '&whereSql.EQUT_TYPE=' . $_POST ['EQUT_TYPE'] . '&flag=2';
		$p->pagesize = $this->_count;
		$page = $p->generate ();
		$cdict = new CDict ();
		// 取ap的类型和状态
		$pageData ["EQUT_TYPE"] = $cdict->EQUT_TYPE;
		$pageData ["EQUT_STATUS"] = $cdict->EQUT_STATUS;
		$pageData ['total'] = $total;
		$pageData ['page'] = $page;
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		// 生成显示的所在楼层，用楼层名字和物理楼层
		$pageData ['Equt_list'] = $Equt_list->_array;
		if ($pageData ['Equt_list'] !== null) {
			foreach ( $pageData ['Equt_list'] as $k => $v ) {
				if ($FLOOR_ID) {
					foreach ( $FLOOR_ID as $k_floor => $v_floor ) {
						if ($pageData ['Equt_list'] [$k] ['FLOOR_ID'] == $FLOOR_ID [$k_floor] ['FLOOR_ID']) {
							$pageData ['Equt_list'] [$k] ['FLOOR_ID'] = $FLOOR_ID [$k_floor] ['FLOOR_NAME'] . "(" . $FLOOR_ID [$k_floor] ['PHYSICAL_FLOOR'] . "层)";
						}
					}
				}
			}
		}
		$this->render ( "locationInformation_list", $pageData );
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
	 * 新增设备页面
	 * Enter description here .
	 */
	public function actionAddEqut() {
		$floor_id = $_GET ['FLOOR_ID'];
		// 查询确定floor_id的平面图下拉选项
		$pageData ["DRAW_MAP_ID_LIST"] = $this->getFloorDraw_Map ( $floor_id, "", true );
		$cdict = new CDict ();
		$pageData ["EQUT_FACTORY"] = $cdict->EQUT_FACTORY;
		$pageData ["EQUT_BRANDS"] = $cdict->EQUT_BRANDS;
		$pageData ["EQUT_TYPE"] = $cdict->EQUT_TYPE;
		$pageData ["EQUT_STATUS"] = $cdict->EQUT_STATUS;
		if ($floor_id) {
			$value = true;
		} else {
			$value = false;
		}
		// 生成该场所的楼层选择项
		$pageData ['FLOOR_ID'] = $this->getFloor_sel_list ( $floor_id, $_GET ['BUILDING_ID'], $value );
		$pageData ['floor_id_sel'] = $floor_id;
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$this->render ( "locationInformation_add", $pageData );
	}
	/**
	 * 根据FLOOR_ID取相应的DRAW_MAP_ID
	 */
	public function actionchoose_floor_id() {
		$DRAW_MAP_ID_LIST = $this->getFloorDraw_Map ( $_GET ['FLOOR_ID'], "", true );
		echo iconv ( 'GB2312', 'UTF-8', $DRAW_MAP_ID_LIST );
	}
	/**
	 * 根据DRAW_MAP_ID取相应的AP图层再取出相应的SVG_ID
	 */
	public function actionchoose_DRAW_MAP_ID() {
		$DRAW_MAP_ID = $_GET ['DRAW_MAP_ID'];
		$sql_layer_id = "DRAW_MAP_ID=" . $DRAW_MAP_ID . "and LAYER_TYPE='AP' and STATUS<>'X'";
		$cdaocb_LAYER_ID = new CDAODM_PLANE_LAYER ();
		// 取指定平面图中AP图层的图层ID
		$LAYER_ID = $cdaocb_LAYER_ID->sel_layer_id ( $sql_layer_id );
		if ($LAYER_ID) {
			// 匹配AP图层的图层ID
			$AP_LAYER = "<option value='" . $LAYER_ID [0] ['LAYER_ID'] . "'>-AP设备图层-</option>";
			$sql_svg_id = "LAYER_ID=" . $LAYER_ID [0] ['LAYER_ID'];
			$cdaocb_LAYER_ELEMENT = new CDAODM_LAYER_ELEMENT ();
			// 取指定平面图并且为AP图层的图层元素ID
			$SVG_ID_LIST = $this->selSVG_ID ( "", $LAYER_ID [0] ['LAYER_ID'] );
		} else {
			$AP_LAYER = "<option value=''>-无AP设备图层-</option>";
			$SVG_ID_LIST = "<option value=''>-请选择-</option>";
		}
		$result = array (
				"AP_LAYER" => iconv ( 'GB2312', 'UTF-8', $AP_LAYER ),
				"SVG_ID_LIST" => iconv ( 'GB2312', 'UTF-8', $SVG_ID_LIST ) 
		);
		echo json_encode ( $result );
	}
	/**
	 * 保存新增设备数据
	 * Enter description here .
	 */
	public function actionEqutAdd() {
		$_POST = CDAOCB_BUILDING::stripForm ( $_POST );//去掉多余的空格
		$data = $_POST;
		$floor_id = $_GET ['FLOOR_ID'];
		$CdaoEqut = new CDAOCB_LOCATION ();
		// 检测元素是否已建立AP
		$where_svg_id = " SVG_ID='" . $_POST ['SVG_ID'] . "'";
		$aleryBuild = $CdaoEqut->getAllWhere ( "SVG_ID", $where_svg_id );
		if ($aleryBuild) {
			$this->jsAlert ( "该元素已有建立AP!" );
			$this->jsJumpBack ();
			return false;
		}
		$CdaoEqut_INSTALL_LOCAT = new CDAOCB_LOCATION ();
		$data ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$data ['MAC_BSSID'] = strtoupper ( $data ['MAC_BSSID'] );
		$data ['MAC_BSSID'] = str_replace ( "-", ":", $data ['MAC_BSSID'] );
		$EqutData = $CdaoEqut->addEqut_list ( $data );
		if ($EqutData) {
			$this->jsAlert ( "添加成功" );
			if ($floor_id) {
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&FLOOR_ID={$_GET['FLOOR_ID']}&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			} else {
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}
		} else {
			$this->jsAlert ( "添加失败!" );
			$this->jsJumpBack ();
			return false;
		}
	}
	/**
	 * 获取楼层平面图
	 * 
	 * @param unknown $floor_id        	
	 * @param unknown $DRAW_MAP_ID        	
	 * @param unknown $key        	
	 * @return string
	 */
	public function getFloorDraw_Map($floor_id, $DRAW_MAP_ID, $key) {
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
					if ($v ['DRAW_MAP_ID'] == $DRAW_MAP_ID) {
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
	 * 编辑设备页面
	 * Enter description here .
	 */
	public function actionEditEqut() {
		$floor_id = $_GET ['FLOOR_ID'];
		$cdict = new CDict ();
		$pageData ["EQUT_FACTORY"] = $cdict->EQUT_FACTORY;
		$pageData ["EQUT_BRANDS"] = $cdict->EQUT_BRANDS;
		$pageData ["EQUT_TYPE"] = $cdict->EQUT_TYPE;
		$pageData ["EQUT_STATUS"] = $cdict->EQUT_STATUS;
		$CdaoEqut_list = new CDAOCB_LOCATION ();
		$EqutInfo = $CdaoEqut_list->getEqutData ( $_GET ['AP_ID'] ); // 获取相应AP的信息
		// 查询该ap所在楼层的楼层平面图
		$pageData ['DRAW_MAP_ID_LIST'] = $this->getFloorDraw_Map ( $EqutInfo [0] ['FLOOR_ID'], $EqutInfo [0] ['DRAW_MAP_ID'], false );
		// 查找该ap所在平面图ap图层的信息
		$pageData ['LAYER_ID_LIST'] = $this->getApLayer ( $EqutInfo [0] ['DRAW_MAP_ID'] );
		// 查找该ap所在图层的元素信息
		$SVG_ID_LIST = $this->selSVG_ID ( $EqutInfo [0] ['SVG_ID'], $EqutInfo [0] ['LAYER_ID'] );
		$pageData ['SVG_ID_LIST'] = $SVG_ID_LIST;
		$EqutInfo [0] ['EQUT_SSID'] = str_replace ( '"', '&quot', $EqutInfo [0] ['EQUT_SSID'] );
		$EqutInfo [0] ['EQUT_MODEL'] = str_replace ( '"', '&quot', $EqutInfo [0] ['EQUT_MODEL'] );
		$EqutInfo [0] ['INSTALL_LOCAT'] = str_replace ( '"', '&quot', $EqutInfo [0] ['INSTALL_LOCAT'] );
		// 生成楼层选择项
		$pageData ['FLOOR_ID'] = $this->getFloor_sel_list ( $EqutInfo [0] ['FLOOR_ID'], $_GET ['BUILDING_ID'], true );
		$pageData ['floor_id_sel'] = $floor_id;
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['Equt_list'] = $EqutInfo;
		$this->render ( "locationInformation_change", $pageData );
	}
	/**
	 * 获取平面图AP图层
	 * 
	 * @param unknown $DRAW_MAP_ID        	
	 * @return string
	 */
	public function getApLayer($DRAW_MAP_ID) {
		if ($DRAW_MAP_ID) {
			$sql_layer_id = " DRAW_MAP_ID=" . $DRAW_MAP_ID . " and LAYER_TYPE='AP'";
			$cdaocb_LAYER_ID = new CDAODM_PLANE_LAYER ();
			$LAYER_ID = $cdaocb_LAYER_ID->sel_layer_id ( $sql_layer_id );
			$LAYER_ID_LIST = "";
			// 匹配AP图层的图层ID
			if ($LAYER_ID) {
				$LAYER_ID_LIST = $LAYER_ID_LIST . "<option value='" . $LAYER_ID [0] ['LAYER_ID'] . "' selected=''>-AP设备图层-</option>";
			} else {
				$LAYER_ID_LIST = $LAYER_ID_LIST . "<option value=''>-无AP设备图层-</option>";
			}
		} else {
			$LAYER_ID_LIST = "<option value=''>-无AP设备图层-</option>";
		}
		return $LAYER_ID_LIST;
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
	 * 保存编辑设备
	 * Enter description here .
	 */
	public function actionSaveEqut() {
		$_POST = CDAOCB_BUILDING::stripForm ( $_POST );//去掉多余的空格
		$data = $_POST;
		$floor_id = $_GET ['FLOOR_ID'];
		$CdaoEqut = new CDAOCB_LOCATION ();
		// 检测元素是否已建立AP
		if ($_POST ['SVG_ID'] != $_POST ['OLD_SVG_ID']) {
			$where_svg_id = " SVG_ID='" . $_POST ['SVG_ID'] . "'";
			$aleryBuild = $CdaoEqut->getAllWhere ( "SVG_ID", $where_svg_id );
			if ($aleryBuild) {
				$this->jsAlert ( "该元素已有建立AP!" );
				$this->jsJumpBack ();
				return false;
			}
		}
		if ($_POST ['AP_ID']) {
			$data ['AP_ID'] = $_POST ['AP_ID'];
			$data ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
			$data ['MAC_BSSID'] = strtoupper ( $data ['MAC_BSSID'] );
			$data ['MAC_BSSID'] = str_replace ( "-", ":", $data ['MAC_BSSID'] );
		}
		$result = $CdaoEqut->updateEqutInfo ( $data );
		if ($result) {
			$this->jsAlert ( "修改成功" );
			if ($floor_id) {
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&FLOOR_ID={$_GET['FLOOR_ID']}&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			} else {
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}
		} else {
			$this->jsAlert ( "修改失败" );
			return false;
		}
	}
	
	/**
	 * 修改设备状态
	 * Enter description here .
	 */
	public function actionChangeStateAll() {
		$equt_id_list = explode ( ",", $_POST ['equt_id_list'] ); // 生成设备ID数组
		$length = count ( $equt_id_list );
		$CdaoEqut = new CDAOCB_LOCATION ();
		$_POST ['status'] = $_POST ['status'] ? iconv ( 'UTF-8', 'GBK', $_POST ['status'] ) : $_POST ['status'];
		$where = null;
		for($i = 0; $i < $length - 1; $i ++) {
			if ($where == null) {
				$where = 'AP_ID=' . $equt_id_list [$i];
			} else {
				$where = $where . ' or AP_ID=' . $equt_id_list [$i];
			}
		}
		$result = $CdaoEqut->SaveStatus ( $where, $_POST ['status'] );
		if (! $result) {
			$status = "状态无需修改!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
			return false;
		}
		$result = $CdaoEqut->ChangeStatus ( $where, $_POST ['status'] );
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
	 * 搜索定位信息SQL
	 * Enter description here .
	 */
	public function genWhereSqlSearch($POST) {
		$POST ['NAME'] = strtolower ( $POST ['NAME'] );
		$whereSql = " 1=1 ";
		if ($POST ['NAME']) {
			$POST ['NAME'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $POST ['NAME'] ) ) );
			$whereSql .= " AND lower(EQUT_SSID) like '%{$POST['NAME']}%'";
		}
		if ($POST ['EQUT_TYPE']) {
			$POST ['EQUT_TYPE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $POST ['EQUT_TYPE'] ) ) );
			$whereSql .= " AND EQUT_TYPE = '{$POST['EQUT_TYPE']}' ";
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