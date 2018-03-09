<?php
/**
 * ��λ��Ϣ������
 * @author liang.jf
 */
class LocationInformationController extends AdminController {
	public $_page = 1; // ��ǰҳ
	public $_count = 15; // ÿҳ����
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * ��λ��Ϣ�б�
	 * Enter description here .
	 */
	public function actionIndex() {
		$Flag = $_GET ["flag"];
		$floor_id = $_GET ["FLOOR_ID"];
		if ($_POST ['FLOOR_ID'] != '') {
			$floor_id = $_POST ['FLOOR_ID'];
		}
		// ������ѯ
		if ($Flag == 1) {
			$pageData ['whereSql'] = $_POST;
			CUserSession::removeSessionValue ( 'euqtconditions' );
			CUserSession::setSessionValue ( 'euqtconditions', $_POST );
		}
		// ��������
		if ($Flag == 2) {
			$_POST = CUserSession::getSessionValue ( 'euqtconditions' );
			$pageData ['whereSql'] = $_POST;
		}
		$cdaocb_location = new CDAOCB_LOCATION ();
		if ($_GET ['page']) {
			$this->_page = $_GET ['page']; // ��ȡ��ҳ
		}
		if ($_GET ['NAME']) {
			$_POST ['NAME'] = $_GET ['NAME'];
		}
		$cdaocb_floor = new CDAOCB_FLOOR ();
		if ($floor_id == '') {
			$whereSql = $this->genWhereSqlSearch ( $_POST );
			$Equt_list = $cdaocb_location->getEqut_list ( $this->_count, $this->_page, $whereSql, $_GET ['BUILDING_ID'] ); // ȡ��Ӧ¥������
		} else {
			$FLOOR_id = $cdaocb_floor->getFloor_name ( $floor_id );
			$pageData ['FLOOR_NAME'] = $FLOOR_id [0] ['FLOOR_NAME'];
			$pageData ['FLOOR_ID'] = $floor_id;
			$Equt_list = $cdaocb_location->get_floor_equt_list ( $this->_count, $this->_page, $floor_id ); // ȡ��ǰ¥��AP����
		}
		$FLOOR_ID = $cdaocb_floor->getFloor_id ( $_GET ['BUILDING_ID'] );
		// ����¥��ѡ��������
		$pageData ['FLOOR_ID_LIST'] = $this->getFloor_sel_list ( $floor_id, $_GET ['BUILDING_ID'], "" );
		$total = $Equt_list->_maxRecordCount;
		$p = new page ( $total );
		$baseUrlFlag = $_GET ["page"];
		$p->baseUrl = 'ea.php?r=LocationInformation&BUILDING_ID=' . $_GET ['BUILDING_ID'] . '&BUILDING_NAME=' . $_GET ['BUILDING_NAME'] . '&whereSql.EQUT_TYPE=' . $_POST ['EQUT_TYPE'] . '&flag=2';
		$p->pagesize = $this->_count;
		$page = $p->generate ();
		$cdict = new CDict ();
		// ȡap�����ͺ�״̬
		$pageData ["EQUT_TYPE"] = $cdict->EQUT_TYPE;
		$pageData ["EQUT_STATUS"] = $cdict->EQUT_STATUS;
		$pageData ['total'] = $total;
		$pageData ['page'] = $page;
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		// ������ʾ������¥�㣬��¥�����ֺ�����¥��
		$pageData ['Equt_list'] = $Equt_list->_array;
		if ($pageData ['Equt_list'] !== null) {
			foreach ( $pageData ['Equt_list'] as $k => $v ) {
				if ($FLOOR_ID) {
					foreach ( $FLOOR_ID as $k_floor => $v_floor ) {
						if ($pageData ['Equt_list'] [$k] ['FLOOR_ID'] == $FLOOR_ID [$k_floor] ['FLOOR_ID']) {
							$pageData ['Equt_list'] [$k] ['FLOOR_ID'] = $FLOOR_ID [$k_floor] ['FLOOR_NAME'] . "(" . $FLOOR_ID [$k_floor] ['PHYSICAL_FLOOR'] . "��)";
						}
					}
				}
			}
		}
		$this->render ( "locationInformation_list", $pageData );
	}
	/**
	 * ¥������ѡ��,$valueΪ�ж��Ƿ�ָ��¥��
	 *
	 * @param unknown $floor_id        	
	 * @param unknown $BUILDING_ID        	
	 * @return string
	 */
	public function getFloor_sel_list($floor_id, $BUILDING_ID, $value) {
		$cdaocb_floor = new CDAOCB_FLOOR ();
		$FLOOR_ID_LIST = $cdaocb_floor->getFloor_id ( $BUILDING_ID );
		// ����¥��ѡ��������
		if ($value) {
			$floor_sel_list = "";
		} else {
			$floor_sel_list = "<option value='' selected=''>-��ѡ��-</option>";
		}
		if ($FLOOR_ID_LIST) {
			foreach ( $FLOOR_ID_LIST as $k => $v ) {
				if ($v ['FLOOR_ID'] == $floor_id && $floor_id != '') {
					$floor_sel_list = $floor_sel_list . "<option value='" . $v ['FLOOR_ID'] . "' selected=''>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "��)</option>";
				} else {
					$floor_sel_list = $floor_sel_list . "<option value='" . $v ['FLOOR_ID'] . "'>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "��)</option>";
				}
			}
		}
		return $floor_sel_list;
	}
	/**
	 * �����豸ҳ��
	 * Enter description here .
	 */
	public function actionAddEqut() {
		$floor_id = $_GET ['FLOOR_ID'];
		// ��ѯȷ��floor_id��ƽ��ͼ����ѡ��
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
		// ���ɸó�����¥��ѡ����
		$pageData ['FLOOR_ID'] = $this->getFloor_sel_list ( $floor_id, $_GET ['BUILDING_ID'], $value );
		$pageData ['floor_id_sel'] = $floor_id;
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$this->render ( "locationInformation_add", $pageData );
	}
	/**
	 * ����FLOOR_IDȡ��Ӧ��DRAW_MAP_ID
	 */
	public function actionchoose_floor_id() {
		$DRAW_MAP_ID_LIST = $this->getFloorDraw_Map ( $_GET ['FLOOR_ID'], "", true );
		echo iconv ( 'GB2312', 'UTF-8', $DRAW_MAP_ID_LIST );
	}
	/**
	 * ����DRAW_MAP_IDȡ��Ӧ��APͼ����ȡ����Ӧ��SVG_ID
	 */
	public function actionchoose_DRAW_MAP_ID() {
		$DRAW_MAP_ID = $_GET ['DRAW_MAP_ID'];
		$sql_layer_id = "DRAW_MAP_ID=" . $DRAW_MAP_ID . "and LAYER_TYPE='AP' and STATUS<>'X'";
		$cdaocb_LAYER_ID = new CDAODM_PLANE_LAYER ();
		// ȡָ��ƽ��ͼ��APͼ���ͼ��ID
		$LAYER_ID = $cdaocb_LAYER_ID->sel_layer_id ( $sql_layer_id );
		if ($LAYER_ID) {
			// ƥ��APͼ���ͼ��ID
			$AP_LAYER = "<option value='" . $LAYER_ID [0] ['LAYER_ID'] . "'>-AP�豸ͼ��-</option>";
			$sql_svg_id = "LAYER_ID=" . $LAYER_ID [0] ['LAYER_ID'];
			$cdaocb_LAYER_ELEMENT = new CDAODM_LAYER_ELEMENT ();
			// ȡָ��ƽ��ͼ����ΪAPͼ���ͼ��Ԫ��ID
			$SVG_ID_LIST = $this->selSVG_ID ( "", $LAYER_ID [0] ['LAYER_ID'] );
		} else {
			$AP_LAYER = "<option value=''>-��AP�豸ͼ��-</option>";
			$SVG_ID_LIST = "<option value=''>-��ѡ��-</option>";
		}
		$result = array (
				"AP_LAYER" => iconv ( 'GB2312', 'UTF-8', $AP_LAYER ),
				"SVG_ID_LIST" => iconv ( 'GB2312', 'UTF-8', $SVG_ID_LIST ) 
		);
		echo json_encode ( $result );
	}
	/**
	 * ���������豸����
	 * Enter description here .
	 */
	public function actionEqutAdd() {
		$_POST = CDAOCB_BUILDING::stripForm ( $_POST );//ȥ������Ŀո�
		$data = $_POST;
		$floor_id = $_GET ['FLOOR_ID'];
		$CdaoEqut = new CDAOCB_LOCATION ();
		// ���Ԫ���Ƿ��ѽ���AP
		$where_svg_id = " SVG_ID='" . $_POST ['SVG_ID'] . "'";
		$aleryBuild = $CdaoEqut->getAllWhere ( "SVG_ID", $where_svg_id );
		if ($aleryBuild) {
			$this->jsAlert ( "��Ԫ�����н���AP!" );
			$this->jsJumpBack ();
			return false;
		}
		$CdaoEqut_INSTALL_LOCAT = new CDAOCB_LOCATION ();
		$data ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$data ['MAC_BSSID'] = strtoupper ( $data ['MAC_BSSID'] );
		$data ['MAC_BSSID'] = str_replace ( "-", ":", $data ['MAC_BSSID'] );
		$EqutData = $CdaoEqut->addEqut_list ( $data );
		if ($EqutData) {
			$this->jsAlert ( "��ӳɹ�" );
			if ($floor_id) {
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&FLOOR_ID={$_GET['FLOOR_ID']}&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			} else {
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}
		} else {
			$this->jsAlert ( "���ʧ��!" );
			$this->jsJumpBack ();
			return false;
		}
	}
	/**
	 * ��ȡ¥��ƽ��ͼ
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
			// ���ɸ�¥��ƽ��ͼ����ѡ�Ĭ��ѡ���豸����ƽ��ͼ
			if ($DRAW_MAP_ID) {
				if ($key) {
					$DRAW_MAP_ID_LIST = "<option value=''>-��ѡ��-</option>";
				}
				foreach ( $DRAW_MAP_ID as $k => $v ) {
					if ($v ['DRAW_MAP_ID'] == $DRAW_MAP_ID) {
						$DRAW_MAP_ID_LIST = $DRAW_MAP_ID_LIST . "<option value='" . $v ['DRAW_MAP_ID'] . "' selected=''>" . $v ['DM_TOPIC'] . "</option>";
					} else {
						$DRAW_MAP_ID_LIST = $DRAW_MAP_ID_LIST . "<option value='" . $v ['DRAW_MAP_ID'] . "'>" . $v ['DM_TOPIC'] . "</option>";
					}
				}
			} else {
				$DRAW_MAP_ID_LIST = $DRAW_MAP_ID_LIST . "<option value=''>-��¥��ƽ��ͼ-</option>";
			}
		} else {
			$DRAW_MAP_ID_LIST = "<option value=''>-��ѡ������¥��-</option>";
		}
		return $DRAW_MAP_ID_LIST;
	}
	/**
	 * �༭�豸ҳ��
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
		$EqutInfo = $CdaoEqut_list->getEqutData ( $_GET ['AP_ID'] ); // ��ȡ��ӦAP����Ϣ
		// ��ѯ��ap����¥���¥��ƽ��ͼ
		$pageData ['DRAW_MAP_ID_LIST'] = $this->getFloorDraw_Map ( $EqutInfo [0] ['FLOOR_ID'], $EqutInfo [0] ['DRAW_MAP_ID'], false );
		// ���Ҹ�ap����ƽ��ͼapͼ�����Ϣ
		$pageData ['LAYER_ID_LIST'] = $this->getApLayer ( $EqutInfo [0] ['DRAW_MAP_ID'] );
		// ���Ҹ�ap����ͼ���Ԫ����Ϣ
		$SVG_ID_LIST = $this->selSVG_ID ( $EqutInfo [0] ['SVG_ID'], $EqutInfo [0] ['LAYER_ID'] );
		$pageData ['SVG_ID_LIST'] = $SVG_ID_LIST;
		$EqutInfo [0] ['EQUT_SSID'] = str_replace ( '"', '&quot', $EqutInfo [0] ['EQUT_SSID'] );
		$EqutInfo [0] ['EQUT_MODEL'] = str_replace ( '"', '&quot', $EqutInfo [0] ['EQUT_MODEL'] );
		$EqutInfo [0] ['INSTALL_LOCAT'] = str_replace ( '"', '&quot', $EqutInfo [0] ['INSTALL_LOCAT'] );
		// ����¥��ѡ����
		$pageData ['FLOOR_ID'] = $this->getFloor_sel_list ( $EqutInfo [0] ['FLOOR_ID'], $_GET ['BUILDING_ID'], true );
		$pageData ['floor_id_sel'] = $floor_id;
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['Equt_list'] = $EqutInfo;
		$this->render ( "locationInformation_change", $pageData );
	}
	/**
	 * ��ȡƽ��ͼAPͼ��
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
			// ƥ��APͼ���ͼ��ID
			if ($LAYER_ID) {
				$LAYER_ID_LIST = $LAYER_ID_LIST . "<option value='" . $LAYER_ID [0] ['LAYER_ID'] . "' selected=''>-AP�豸ͼ��-</option>";
			} else {
				$LAYER_ID_LIST = $LAYER_ID_LIST . "<option value=''>-��AP�豸ͼ��-</option>";
			}
		} else {
			$LAYER_ID_LIST = "<option value=''>-��AP�豸ͼ��-</option>";
		}
		return $LAYER_ID_LIST;
	}
	/**
	 * ��ȡԪ������ѡ��
	 * Enter description here .
	 */
	public function selSVG_ID($svg_id, $layer_id) {
		if ($layer_id) {
			$sql_svg_id = " LAYER_ID=" . $layer_id;
			$cdaocb_LAYER_ELEMENT = new CDAODM_LAYER_ELEMENT ();
			$SVG_ID = $cdaocb_LAYER_ELEMENT->sel_svg_id ( $sql_svg_id );
			$SVG_ID_LIST = "";
			// ���ɸ�APͼ���ͼ��Ԫ������ѡ�Ĭ��ѡ���豸������ͼ��Ԫ��
			if ($SVG_ID) {
				if ($svg_id == '') {
					$SVG_ID_LIST = "<option value=''>-��ѡ��-</option>";
				}
				foreach ( $SVG_ID as $k => $v ) {
					if ($v ['SVG_ID'] == $svg_id) {
						$SVG_ID_LIST = $SVG_ID_LIST . "<option value='" . $v ['SVG_ID'] . "' selected=''>" . $v ['ELEMENT_TYPE'] . "(" . $v ['SVG_ID'] . ")</option>";
					} else {
						$SVG_ID_LIST = $SVG_ID_LIST . "<option value='" . $v ['SVG_ID'] . "'>" . $v ['ELEMENT_TYPE'] . "(" . $v ['SVG_ID'] . ")</option>";
					}
				}
			} else {
				$SVG_ID_LIST = $SVG_ID_LIST . "<option value=''>-��ѡ��-</option>";
			}
		} else {
			$SVG_ID_LIST = "<option value=''>-��ѡ��-</option>";
		}
		return $SVG_ID_LIST;
	}
	
	/**
	 * ����༭�豸
	 * Enter description here .
	 */
	public function actionSaveEqut() {
		$_POST = CDAOCB_BUILDING::stripForm ( $_POST );//ȥ������Ŀո�
		$data = $_POST;
		$floor_id = $_GET ['FLOOR_ID'];
		$CdaoEqut = new CDAOCB_LOCATION ();
		// ���Ԫ���Ƿ��ѽ���AP
		if ($_POST ['SVG_ID'] != $_POST ['OLD_SVG_ID']) {
			$where_svg_id = " SVG_ID='" . $_POST ['SVG_ID'] . "'";
			$aleryBuild = $CdaoEqut->getAllWhere ( "SVG_ID", $where_svg_id );
			if ($aleryBuild) {
				$this->jsAlert ( "��Ԫ�����н���AP!" );
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
			$this->jsAlert ( "�޸ĳɹ�" );
			if ($floor_id) {
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&FLOOR_ID={$_GET['FLOOR_ID']}&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			} else {
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}
		} else {
			$this->jsAlert ( "�޸�ʧ��" );
			return false;
		}
	}
	
	/**
	 * �޸��豸״̬
	 * Enter description here .
	 */
	public function actionChangeStateAll() {
		$equt_id_list = explode ( ",", $_POST ['equt_id_list'] ); // �����豸ID����
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
			$status = "״̬�����޸�!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
			return false;
		}
		$result = $CdaoEqut->ChangeStatus ( $where, $_POST ['status'] );
		if ($result) {
			$status = "�޸ĳɹ�!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
		} else {
			$status = "�޸�ʧ��!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
			return false;
		}
		return;
	}
	/**
	 * ������λ��ϢSQL
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