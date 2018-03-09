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
		$Flag=$_GET ["flag"];
		$floor_id=$_GET ["FLOOR_ID"];
		if($_POST['FLOOR_ID']!=''){
			$floor_id=$_POST['FLOOR_ID'];
		}
		if($Flag==1){
			$pageData ['whereSql'] = $_POST;
			CUserSession::removeSessionValue( 'euqtconditions');
			CUserSession::setSessionValue ( 'euqtconditions', $_POST);
		}
		if($Flag==2){
			$_POST=CUserSession::getSessionValue ( 'euqtconditions' );
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
		if($floor_id==''){
			$whereSql = $this->genWhereSqlSearch ( $_POST );
			$Equt_list = $cdaocb_location->getEqut_list ( $this->_count, $this->_page, $whereSql, $_GET ['BUILDING_ID'] ); // 取相应楼层数据
		}else{
			$FLOOR_id=$cdaocb_floor->getFloor_name($floor_id);
			$pageData ['FLOOR_NAME']=$FLOOR_id[0]['FLOOR_NAME'];
			$pageData ['FLOOR_ID']=$floor_id;
			$Equt_list = $cdaocb_location->get_floor_equt_list ( $this->_count, $this->_page,$floor_id ); // 取当前楼层POI数据
		}
		$FLOOR_ID = $cdaocb_floor->getFloor_id ( $_GET ['BUILDING_ID'] );
		$floor_id_sel = "<option value='' selected=''>-请选择-</option>";
		if($FLOOR_ID){
			foreach ( $FLOOR_ID as $k => $v ) {
				if ($v ['FLOOR_ID'] == $floor_id && $floor_id != '') {
					$floor_id_sel = $floor_id_sel . "<option value='" . $v ['FLOOR_ID'] . "' selected=''>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
				} else {
					$floor_id_sel = $floor_id_sel . "<option value='" . $v ['FLOOR_ID'] . "'>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
				}
			}
		}
		$pageData ['FLOOR_ID_LIST'] = $floor_id_sel;
		$total = $Equt_list->_maxRecordCount;
		$p = new page ( $total );
		$baseUrlFlag=$_GET ["page"];
		$p->baseUrl = 'ea.php?r=LocationInformation&BUILDING_ID=' . $_GET ['BUILDING_ID'] . '&BUILDING_NAME=' . $_GET ['BUILDING_NAME'].'&whereSql.EQUT_TYPE='.$_POST['EQUT_TYPE'].'&flag=2';
		$p->pagesize = $this->_count;
		$page = $p->generate ();
		$cdict = new CDict ();
		$pageData ["EQUT_TYPE"] = $cdict->EQUT_TYPE;
		$pageData ["EQUT_STATUS"] = $cdict->EQUT_STATUS;
		$pageData ['total'] = $total;
		$pageData ['page'] = $page;
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
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
	 * 新增设备页面
	 * Enter description here .
	 */
	public function actionAddEqut() {
		$floor_id=$_GET['FLOOR_ID'];
		$cdict = new CDict ();
		$pageData ["EQUT_FACTORY"] = $cdict->EQUT_FACTORY;
		$pageData ["EQUT_BRANDS"] = $cdict->EQUT_BRANDS;
		$pageData ["EQUT_TYPE"] = $cdict->EQUT_TYPE;
		$pageData ["EQUT_STATUS"] = $cdict->EQUT_STATUS;
		$cdaocb_FLOOR_ID = new CDAOCB_FLOOR ();
		$FLOOR_ID_LIST = $cdaocb_FLOOR_ID->getFloor_id ( $_GET ['BUILDING_ID'] );
		$value=true;
		if ($FLOOR_ID_LIST) {
			foreach ( $FLOOR_ID_LIST as $k => $v ) {
				if ($v ['FLOOR_ID'] == $floor_id && $floor_id != '') {
					$FLOOR_ID = $FLOOR_ID . "<option value='" . $v ['FLOOR_ID'] . "' selected=''>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
					$value = false;
				} else {
					$FLOOR_ID = $FLOOR_ID . "<option value='" . $v ['FLOOR_ID'] . "'>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
				}
			}
		}
		if($value){
			$FLOOR_ID="<option value='' selected=''>--请选择--</option>".$FLOOR_ID;
		}
		$pageData ['FLOOR_ID'] = $FLOOR_ID;
		$pageData ['floor_id_sel'] = $floor_id;
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$this->render ( "locationInformation_add", $pageData );
	}
	
	/**
	 * 新增设备数据
	 * Enter description here .
	 */
	public function actionEqutAdd() {
		$floor_id=$_GET['FLOOR_ID'];
		$CdaoEqut = new CDAOCB_LOCATION ();
		$CdaoEqut_INSTALL_LOCAT = new CDAOCB_LOCATION ();
		$data ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$data ['FLOOR_ID'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_ID'] ) ) );
		$data ['EQUT_SSID'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['EQUT_SSID'] ) ) );
		$data ['FACTORY'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['FACTORY'] ) ) );
		$data ['BRANDS'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['BRANDS'] ) ) );
		$data ['EQUT_TYPE'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['EQUT_TYPE'] ) ) );
		$data ['EQUT_MODEL'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['EQUT_MODEL'] ) ) );
		$data ['MAC_BSSID'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['MAC_BSSID'] ) ) );
		$data ['POSITION_Y'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['POSITION_Y'] ) ) );
		$data ['POSITION_X'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['POSITION_X'] ) ) );
		$data ['INSTALL_LOCAT'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['INSTALL_LOCAT'] ) ) );
		$data ['RATE'] = ( TextFilter::filterAllHTML ( trim ( $_POST ['RATE'] ) ) );
		$data ['CHANNEL'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['CHANNEL'] ) ) );
		$data ['EQUT_NOTE'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['EQUT_NOTE'] ) ) );
		$data ['STATUS'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['STATUS'] ) ) );
		$data ['EQUT_SSID'] = $data ['EQUT_SSID'] ? iconv ( 'UTF-8', 'GBK', $data ['EQUT_SSID'] ) : $data ['EQUT_SSID'];
		$data ['MAC_BSSID'] = $data ['MAC_BSSID'] ? iconv ( 'UTF-8', 'GBK', $data ['MAC_BSSID'] ) : $data ['MAC_BSSID'];
		$data ['EQUT_NOTE'] = $data ['EQUT_NOTE'] ? iconv ( 'UTF-8', 'GBK', $data ['EQUT_NOTE'] ) : $data ['EQUT_NOTE'];
		$data ['EQUT_TYPE'] = $data ['EQUT_TYPE'] ? iconv ( 'UTF-8', 'GBK', $data ['EQUT_TYPE'] ) : $data ['EQUT_TYPE'];
		$data ['FACTORY'] = $data ['FACTORY'] ? iconv ( 'UTF-8', 'GBK', $data ['FACTORY'] ) : $data ['FACTORY'];
		$data ['BRANDS'] = $data ['BRANDS'] ? iconv ( 'UTF-8', 'GBK', $data ['BRANDS'] ) : $data ['BRANDS'];
		$data ['EQUT_MODEL'] = $data ['EQUT_MODEL'] ? iconv ( 'UTF-8', 'GBK', $data ['EQUT_MODEL'] ) : $data ['EQUT_MODEL'];
		$data ['INSTALL_LOCAT'] = $data ['INSTALL_LOCAT'] ? iconv ( 'UTF-8', 'GBK', $data ['INSTALL_LOCAT'] ) : $data ['INSTALL_LOCAT'];
		$data ['STATUS'] = $data ['STATUS'] ? iconv ( 'UTF-8', 'GBK', $data ['STATUS'] ) : $data ['STATUS'];
		$EqutData = $CdaoEqut->addEqut_list ( $data );
		if ($EqutData) {
			print ("添加成功!") ;
			if($floor_id){
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&FLOOR_ID={$_GET['FLOOR_ID']}&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}else{
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}
		} else {
			print ("添加失败!") ;
			$this->jsJumpBack ();
			return false;
		}
	}
	
	/**
	 * 编辑设备页面
	 * Enter description here .
	 */
	public function actionEditEqut() {
		$floor_id=$_GET['FLOOR_ID'];
		$cdict = new CDict ();
		$pageData ["EQUT_FACTORY"] = $cdict->EQUT_FACTORY;
		$pageData ["EQUT_BRANDS"] = $cdict->EQUT_BRANDS;
		$pageData ["EQUT_TYPE"] = $cdict->EQUT_TYPE;
		$pageData ["EQUT_STATUS"] = $cdict->EQUT_STATUS;
		$cdaocb_FLOOR_ID = new CDAOCB_FLOOR ();
		$FLOOR_ID_LIST = $cdaocb_FLOOR_ID->getFloor_id ( $_GET ['BUILDING_ID'] );
		$CdaoEqut_list = new CDAOCB_LOCATION ();
		$EqutInfo = $CdaoEqut_list->getEqutData ( $_GET ['EQUT_ID'] );
		$EqutInfo[0]['EQUT_SSID']=str_replace('"', '&quot', $EqutInfo[0]['EQUT_SSID']);
		$EqutInfo[0]['EQUT_MODEL']=str_replace('"', '&quot', $EqutInfo[0]['EQUT_MODEL']);
		$EqutInfo[0]['INSTALL_LOCAT']=str_replace('"', '&quot', $EqutInfo[0]['INSTALL_LOCAT']);
		$FLOOR_ID = "";
		if ($FLOOR_ID_LIST) {
			foreach ( $FLOOR_ID_LIST as $k => $v ) {
				if ($v ['FLOOR_ID'] == $EqutInfo [0] ['FLOOR_ID']) {
					$FLOOR_ID = $FLOOR_ID . "<option value='" . $v ['FLOOR_ID'] . "' selected=''>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
				} else {
					$FLOOR_ID = $FLOOR_ID . "<option value='" . $v ['FLOOR_ID'] . "'>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
				}
			}
		}
		$pageData ['FLOOR_ID'] = $FLOOR_ID;
		$pageData ['floor_id_sel'] = $floor_id;
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['Equt_list'] = $EqutInfo;
		$this->render ( "locationInformation_change", $pageData );
	}
	
	/**
	 * 保存编辑设备
	 * Enter description here .
	 */
	public function actionSaveEqut() {
		$floor_id=$_GET['FLOOR_ID'];
		$CdaoEqut = new CDAOCB_LOCATION ();
		$CdaoEqut_INSTALL_LOCAT = new CDAOCB_LOCATION ();
		if ($_GET ['EQUT_ID']) {
			$data ['EQUT_ID'] = $_GET ['EQUT_ID'];
			$data ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
			$data ['FLOOR_ID'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_ID'] ) ) );
			$data ['EQUT_SSID'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['EQUT_SSID'] ) ) );
			$data ['FACTORY'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['FACTORY'] ) ) );
			$data ['BRANDS'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['BRANDS'] ) ) );
			$data ['EQUT_TYPE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['EQUT_TYPE'] ) ) );
			$data ['EQUT_MODEL'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['EQUT_MODEL'] ) ) );
			$data ['MAC_BSSID'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['MAC_BSSID'] ) ) );
			$data ['POSITION_Y'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['POSITION_Y'] ) ) );
			$data ['POSITION_X'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['POSITION_X'] ) ) );
			$data ['INSTALL_LOCAT'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['INSTALL_LOCAT'] ) ) );
			$data ['RATE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['RATE'] ) ) );
			$data ['CHANNEL'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['CHANNEL'] ) ) );
			$data ['EQUT_NOTE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['EQUT_NOTE'] ) ) );
			$data ['STATUS'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['STATUS'] ) ) );
			$data ['EQUT_SSID'] = $data ['EQUT_SSID'] ? iconv ( 'UTF-8', 'GBK', $data ['EQUT_SSID'] ) : $data ['EQUT_SSID'];
			$data ['MAC_BSSID'] = $data ['MAC_BSSID'] ? iconv ( 'UTF-8', 'GBK', $data ['MAC_BSSID'] ) : $data ['MAC_BSSID'];
			$data ['EQUT_NOTE'] = $data ['EQUT_NOTE'] ? iconv ( 'UTF-8', 'GBK', $data ['EQUT_NOTE'] ) : $data ['EQUT_NOTE'];
			$data ['EQUT_TYPE'] = $data ['EQUT_TYPE'] ? iconv ( 'UTF-8', 'GBK', $data ['EQUT_TYPE'] ) : $data ['EQUT_TYPE'];
			$data ['FACTORY'] = $data ['FACTORY'] ? iconv ( 'UTF-8', 'GBK', $data ['FACTORY'] ) : $data ['FACTORY'];
			$data ['BRANDS'] = $data ['BRANDS'] ? iconv ( 'UTF-8', 'GBK', $data ['BRANDS'] ) : $data ['BRANDS'];
			$data ['EQUT_MODEL'] = $data ['EQUT_MODEL'] ? iconv ( 'UTF-8', 'GBK', $data ['EQUT_MODEL'] ) : $data ['EQUT_MODEL'];
			$data ['INSTALL_LOCAT'] = $data ['INSTALL_LOCAT'] ? iconv ( 'UTF-8', 'GBK', $data ['INSTALL_LOCAT'] ) : $data ['INSTALL_LOCAT'];
			$data ['STATUS'] = $data ['STATUS'] ? iconv ( 'UTF-8', 'GBK', $data ['STATUS'] ) : $data ['STATUS'];
		}
		$result = $CdaoEqut->updateEqutInfo ( $data );
		if ($result) {
			print ("修改成功") ;
			if($floor_id){
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&FLOOR_ID={$_GET['FLOOR_ID']}&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}else{
				$this->jsCall ( "parent.location.href = 'ea.php?r=LocationInformation&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}
		} else {
			print ("修改失败") ;
			return false;
		}
	}
	
	/**
	 * 修改设备状态
	 * Enter description here .
	 */
	public function actionChangeStateAll() {
		$equt_id_list = explode ( ",", $_POST ['equt_id_list'] );//生成设备ID数组
		$length = count ( $equt_id_list );
		$CdaoEqut = new CDAOCB_LOCATION ();
		$_POST ['status'] = $_POST ['status'] ? iconv ( 'UTF-8', 'GBK', $_POST ['status'] ) : $_POST ['status'];
		$where = null;
		for($i = 0; $i < $length - 1; $i ++) {
			if ($where == null) {
				$where = 'EQUT_ID=' . $equt_id_list [$i];
			} else {
				$where = $where . ' or EQUT_ID=' . $equt_id_list [$i];
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
		$POST ['NAME']=strtolower($POST ['NAME']);
		$whereSql = " 1=1 ";
		if ($POST ['NAME']){
			$POST ['NAME'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $POST ['NAME'] ) ) );
			$whereSql .= " AND lower(EQUT_SSID) like '%{$POST['NAME']}%'";
		}
		if ($POST ['EQUT_TYPE']){
			$POST ['EQUT_TYPE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $POST ['EQUT_TYPE'] ) ) );
			$whereSql .= " AND EQUT_TYPE = '{$POST['EQUT_TYPE']}' ";
		}
		if ($POST ['STATUS']){
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