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
		$floor_id=$_GET ["FLOOR_ID"];
		if($_POST['FLOOR_ID']!=''){
			$floor_id=$_POST['FLOOR_ID'];
		}
		if ($Flag == 1) {
			$pageData ['whereSql'] = $_POST;
			CUserSession::removeSessionValue ( 'POIconditions' );
			CUserSession::setSessionValue ( 'POIconditions', $_POST );
		}
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
		if($floor_id==''){
			$whereSql = $this->genWhereSqlSearch ( $_POST );
			$Poi_list = $cdaocb_poi->getPoi_list ( $this->_count, $this->_page, $whereSql, $_GET ['BUILDING_ID'] ); // 取当前场所所有楼层POI数据
		}else{
			$FLOOR_id=$cdaocb_floor->getFloor_name($floor_id);
			$pageData ['FLOOR_NAME']=$FLOOR_id[0]['FLOOR_NAME'];
			$pageData ['FLOOR_ID']=$floor_id;
			$Poi_list = $cdaocb_poi->get_floor_poi_list ( $this->_count, $this->_page,$floor_id ); // 取当前楼层POI数据
		}
		$FLOOR_ID=$cdaocb_floor->getFloor_id($_GET ['BUILDING_ID']);
		$floor_id_sel = "<option value='' selected=''>-请选择-</option>";
		if ($FLOOR_ID) {
			foreach ( $FLOOR_ID as $k => $v ) {
				if ($v ['FLOOR_ID'] == $floor_id && $floor_id != '') {
					$floor_id_sel = $floor_id_sel . "<option value='" . $v ['FLOOR_ID'] . "' selected=''>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
				} else {
					$floor_id_sel = $floor_id_sel . "<option value='" . $v ['FLOOR_ID'] . "'>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
				}
			}
		}
		$pageData ['FLOOR_ID_LIST'] = $floor_id_sel;
		$total = $Poi_list->_maxRecordCount;
		$p = new page ( $total );
		$baseUrlFlag = $_GET ["page"];
		$p->baseUrl = 'ea.php?r=Poi&BUILDING_ID=' . $_GET ['BUILDING_ID'] . '&BUILDING_NAME=' . $_GET ['BUILDING_NAME'] . '&flag=2';
		$p->pagesize = $this->_count;
		$page = $p->generate ();
		$cdict = new CDict ();
		$pageData ["POI_TYPE"] = $cdict->POI_TYPE;
		$pageData ["POI_STATUS"] = $cdict->POI_STATUS;
		$pageData ['total'] = $total;
		$pageData ['page'] = $page;
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['poi_list'] = $Poi_list->_array;
		// 时间显示
		if ($pageData ['poi_list'] !== null) {
			foreach ( $pageData ['poi_list'] as $k => $v ) {
				if ($FLOOR_ID){
					foreach ( $FLOOR_ID as $k_floor => $v_floor ) {
						if ($pageData ['poi_list'] [$k] ['FLOOR_ID'] == $FLOOR_ID [$k_floor] ['FLOOR_ID']) {
							$pageData ['poi_list'] [$k] ['FLOOR_ID'] = $FLOOR_ID [$k_floor] ['FLOOR_NAME'] . "(" . $FLOOR_ID [$k_floor] ['PHYSICAL_FLOOR'] . "层)";
						}
					}
				}
				$OPEN_START = explode ( " ", $v ['OPEN_START'] );
				$OPEN_END = explode ( " ", $v ['OPEN_END'] );
				if (strtotime ( $pageData ['poi_list'] [$k] ['OPEN_START'] ) < strtotime ( $pageData ['poi_list'] [$k] ['OPEN_END'] )) {
					$OPEN_END [1] = "当天：" . $OPEN_END [1];
				} else {
					$OPEN_END [1] = "第二天：" . $OPEN_END [1];
				}
				$OPEN_START [1] = "当天：" . $OPEN_START [1];
				$pageData ['poi_list'] [$k] ['OPEN_START'] = $OPEN_START [1];
				$pageData ['poi_list'] [$k] ['OPEN_END'] = $OPEN_END [1];
			}
		}
		// 限制字符串显示长度
		if ($pageData ['poi_list'] !== null) {
			foreach ( $pageData ['poi_list'] as $k => $v ) {
				if (strlen ( $pageData ['poi_list'] [$k] ['POI_NAME'] ) > 16) {
					$pageData ['poi_list'] [$k] ['POI_NAME'] = iconv_substr( $pageData ['poi_list'] [$k] ['POI_NAME'], 0, 6,"GBK" ) . "...";
				}
				if (strlen ( $pageData ['poi_list'] [$k] ['POI_ADDRESS'] ) > 36) {
					$pageData ['poi_list'] [$k] ['POI_ADDRESS'] = iconv_substr ( $pageData ['poi_list'] [$k] ['POI_ADDRESS'], 0, 16,"GBK") . "...";
				}
			}
		}
		$this->render ( "poi_list", $pageData );
	}
	
	/**
	 * 新增POI页面
	 * Enter description here .
	 */
	public function actionAddPoi() {
		$floor_id=$_GET['FLOOR_ID'];
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
		$cdict = new CDict ();
		$pageData ["POI_BRANDS"] = $cdict->POI_BRANDS;
		$pageData ["POI_STATUS"] = $cdict->POI_STATUS;
		$pageData ["POI_TYPE"] = $cdict->POI_TYPE;
		$pageData ['FLOOR_ID'] = $FLOOR_ID;
		$pageData ['floor_id_sel'] = $floor_id;
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$this->render ( "poi_add", $pageData );
	}
	
	
	/**
	 * 获取POI相应的二级类别
	 * Enter description here .
	 */
	public function actionCODE_TYPE_list() {
		$POI_TYPE = $_GET ['POI_TYPE'];
		$cdict = new CDict ();
		$CODE_TYPE_list = $cdict->CODE_TYPE;
		$CODE_TYPE = "<option value='' select='selected'>--请选择--</option>";
		if ($CODE_TYPE_list) {
			foreach ( $CODE_TYPE_list as $k => $v ) {
				if ($k == $POI_TYPE) {
					foreach ( $v as $k_1 => $v_1 ) {
						$CODE_TYPE = $CODE_TYPE . "<option value='" . $v_1 . "'>" . $v_1 . "</option>";
					}
				}
			}
		}
		$CODE_TYPE = iconv ( 'GBK', 'UTF-8', $CODE_TYPE );
		$array = array (
				"html" => $CODE_TYPE 
		);
		echo json_encode ( $array );
	}
	
	/**
	 * 新增POI数据
	 * Enter description here .
	 */
	public function actionPoiAdd() {
		$floor_id=$_GET['FLOOR_ID'];
		$CdaoPoi = new CDAOCB_POI ();
		$INSTALL_LOCAT = $CdaoPoi->getTheAdd_INSTALL_LOCAT ( $_POST ['LOCAT_X'], $_POST ['LOCAT_Y'], $_POST ['FLOOR_ID'], $_GET ['BUILDING_ID'] );
		if (! $INSTALL_LOCAT) {
			$equt = "此处X，Y轴前后5米内已有POI点！";
			echo iconv ( 'GB2312', 'UTF-8', $equt );
			return false;
		}
		$Cdao_Building = new CDAOCB_BUILDING ();
		$location = $Cdao_Building->infoBuilding ( $_GET ['BUILDING_ID'] );
		$data ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$data ['FLOOR_ID'] = ( TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_ID'] ) ) );
		$data ['POI_NAME'] = ( TextFilter::filterAllHTML ( trim ( $_POST ['POI_NAME'] ) ) );
		$data ['CONTINENT'] =$location['CONTINENT'];
		$data ['BRANDS'] = ( TextFilter::filterAllHTML ( trim ( $_POST ['BRANDS'] ) ) );
		$data ['COUNTRY'] =  $location['COUNTRY'];
		$data ['STATUS'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['STATUS'] ) ) );
		$data ['PROV'] =  $location['CONTINENT'];
		$data ['POI_TYPE'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['POI_TYPE'] ) ) );
		$data ['CITY'] = $location['CITY'];
		$data ['CODE_TYPE'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['CODE_TYPE'] ) ) );
		$data ['DISTRICT'] =  $location['DISTRICT'];
		$data ['OPEN_START'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['OPEN_START'] ) ) );
		$data ['LOCAT_X'] = ( TextFilter::filterAllHTML ( trim ( $_POST ['LOCAT_X'] ) ) );
		$data ['OPEN_END'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['OPEN_END'] ) ) );
		$data ['LOCAT_Y'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['LOCAT_Y'] ) ) );
		$data ['POI_ADDRESS'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['POI_ADDRESS'] ) ) );
		$data ['POI_NOTE'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['POI_NOTE'] ) ) );
		$data ['NOTE'] =  ( TextFilter::filterAllHTML ( trim ( $_POST ['NOTE'] ) ) );
		$data ['POI_NAME'] = $data ['POI_NAME'] ? iconv ( 'UTF-8', 'GBK', $data ['POI_NAME'] ) : $data ['POI_NAME'];
		$data ['POI_ADDRESS'] = $data ['POI_ADDRESS'] ? iconv ( 'UTF-8', 'GBK', $data ['POI_ADDRESS'] ) : $data ['POI_ADDRESS'];
		$data ['POI_NOTE'] = $data ['POI_NOTE'] ? iconv ( 'UTF-8', 'GBK', $data ['POI_NOTE'] ) : $data ['POI_NOTE'];
		$data ['NOTE'] = $data ['NOTE'] ? iconv ( 'UTF-8', 'GBK', $data ['NOTE'] ) : $data ['NOTE'];
		$data ['BRANDS'] = $data ['BRANDS'] ? iconv ( 'UTF-8', 'GBK', $data ['BRANDS'] ) : $data ['BRANDS'];
		$data ['STATUS'] = $data ['STATUS'] ? iconv ( 'UTF-8', 'GBK', $data ['STATUS'] ) : $data ['STATUS'];
		$data ['CODE_TYPE'] = $data ['CODE_TYPE'] ? iconv ( 'UTF-8', 'GBK', $data ['CODE_TYPE'] ) : $data ['CODE_TYPE'];
		$data ['POI_TYPE'] = $data ['POI_TYPE'] ? iconv ( 'UTF-8', 'GBK', $data ['POI_TYPE'] ) : $data ['POI_TYPE'];
		// 给营业开始和结束时间附加一个固定的日期，用于保存数据库的date类型
		$data ['OPEN_START'] = "2013-10-1 " . $data ['OPEN_START'];
		$data ['OPEN_END'] = "2013-10-1 " . $data ['OPEN_END'];
		$PoiData = $CdaoPoi->addPoi_list ( $data );
		if ($PoiData) {
			print ("添加成功!") ;
			if($floor_id){
				$this->jsCall ( "parent.location.href = 'ea.php?r=Poi&FLOOR_ID={$_GET['FLOOR_ID']}&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}else{
				$this->jsCall ( "parent.location.href = 'ea.php?r=Poi&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}
		} else {
			print ("添加失败!") ;
			$this->jsJumpBack ();
			return false;
		}
	}
	
	/**
	 * 编辑POI页面
	 * Enter description here .
	 */
	public function actionEditPoi() {
		$floor_id=$_GET['FLOOR_ID'];
		$cdaocb_FLOOR_ID = new CDAOCB_FLOOR ();
		$FLOOR_ID_LIST = $cdaocb_FLOOR_ID->getFloor_id ( $_GET ['BUILDING_ID'] );
		$CdaoPoi_list = new CDAOCB_POI ();
		$PoiInfo = $CdaoPoi_list->getPoiData ( $_GET ['POI_ID'] );
		$PoiInfo[0]['POI_NAME']=str_replace('"', '&quot', $PoiInfo[0]['POI_NAME']);
		$PoiInfo[0]['POI_ADDRESS']=str_replace('"', '&quot', $PoiInfo[0]['POI_ADDRESS']);
		// 生成所属楼层的下拉选项
		$FLOOR_ID = "";
		if ($FLOOR_ID_LIST) {
			foreach ( $FLOOR_ID_LIST as $k => $v ) {
				if ($v ['FLOOR_ID'] == $PoiInfo [0] ['FLOOR_ID']) {
					$FLOOR_ID = $FLOOR_ID . "<option value='" . $v ['FLOOR_ID'] . "' selected=''>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
				} else {
					$FLOOR_ID = $FLOOR_ID . "<option value='" . $v ['FLOOR_ID'] . "'>" . $v ['FLOOR_NAME'] . "(" . $v ['PHYSICAL_FLOOR'] . "层)</option>";
				}
			}
		}
		$cdict = new CDict ();
		$pageData ["POI_BRANDS"] = $cdict->POI_BRANDS;
		$pageData ["POI_STATUS"] = $cdict->POI_STATUS;
		$pageData ["POI_TYPE"] = $cdict->POI_TYPE;
		$pageData ['FLOOR_ID'] = $FLOOR_ID;
		$pageData ['floor_id_sel'] =$floor_id;
		$cdict = new CDict ();
		$CODE_TYPE_list = $cdict->CODE_TYPE;
		$CODE_TYPE = array ();
		// 取出相应的二级类别
		if ($CODE_TYPE_list) {
			foreach ( $CODE_TYPE_list as $k => $v ) {
				if ($k == $PoiInfo [0] ["POI_TYPE"]) {
					$CODE_TYPE = $CODE_TYPE_list [$k];
				}
			}
		}
		$pageData ['CODE_TYPE'] = $CODE_TYPE;
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['poi_list'] = $PoiInfo;
		// 只取营业开始和结束时间的时间部分
		$OPEN_START=array();
		$OPEN_END=array();
		if ($pageData ['poi_list']) {
			foreach ( $pageData ['poi_list'] as $k => $v ) {
				$OPEN_START_list = explode ( " ", $v ['OPEN_START'] );
				$OPEN_START_list_time = explode ( ":", $OPEN_START_list [1] );
				$OPEN_START ['open_hour'] = $OPEN_START_list_time [0];
				$OPEN_START ['open_minute'] = $OPEN_START_list_time [1];
				$OPEN_START ['open_second'] = $OPEN_START_list_time [2];
				$OPEN_END_list = explode ( " ", $v ['OPEN_END'] );
				$OPEN_END_list_time = explode ( ":", $OPEN_END_list [1] );
				$OPEN_END ['end_hour'] = $OPEN_END_list_time [0];
				$OPEN_END ['end_minute'] = $OPEN_END_list_time [1];
				$OPEN_END ['end_second'] = $OPEN_END_list_time [2];
				$pageData ['OPEN_START'] = $OPEN_START;
				$pageData ['OPEN_END'] = $OPEN_END;
			}
		}
		$this->render ( "poi_change", $pageData );
	}
	
	/**
	 * 保存编辑POI
	 * Enter description here .
	 */
	public function actionSavePoi() {
		$floor_id_sel=$_GET['FLOOR_ID'];
		$CdaoPoi = new CDAOCB_POI ();
		// 判断POI位置是否已有POI
		$INSTALL_LOCAT = $CdaoPoi->getThe_INSTALL_LOCAT ( $_POST ['LOCAT_X'], $_POST ['LOCAT_Y'], $_POST ['FLOOR_ID'], $_GET ['BUILDING_ID'], $_GET ['POI_ID'] );
		if (! $INSTALL_LOCAT) {
			$equt = "此处X，Y轴前后5米内已有POI点！";
			echo iconv ( 'GB2312', 'UTF-8', $equt );
			return false;
		}
		$Cdao_Building = new CDAOCB_BUILDING ();
		$location = $Cdao_Building->infoBuilding ( $_GET ['BUILDING_ID'] );
		if ($_GET ['POI_ID']) {
			$data ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
			$data ['FLOOR_ID'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_ID'] ) ) );
			$data ['POI_NAME'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['POI_NAME'] ) ) );
			$data ['CONTINENT'] = $location['CONTINENT'];
			$data ['BRANDS'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['BRANDS'] ) ) );
			$data ['COUNTRY'] = $location['COUNTRY'];
			$data ['STATUS'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['STATUS'] ) ) );
			$data ['PROV'] = $location['PROV'];
			$data ['POI_TYPE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['POI_TYPE'] ) ) );
			$data ['CITY'] = $location['CITY'];
			$data ['CODE_TYPE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['CODE_TYPE'] ) ) );
			$data ['DISTRICT'] = $location['DISTRICT'];
			$data ['OPEN_START'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['OPEN_START'] ) ) );
			$data ['LOCAT_X'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['LOCAT_X'] ) ) );
			$data ['OPEN_END'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['OPEN_END'] ) ) );
			$data ['LOCAT_Y'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['LOCAT_Y'] ) ) );
			$data ['POI_ADDRESS'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['POI_ADDRESS'] ) ) );
			$data ['POI_NOTE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['POI_NOTE'] ) ) );
			$data ['NOTE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['NOTE'] ) ) );
			$data ['POI_NAME'] = $data ['POI_NAME'] ? iconv ( 'UTF-8', 'GBK', $data ['POI_NAME'] ) : $data ['POI_NAME'];
			$data ['POI_ADDRESS'] = $data ['POI_ADDRESS'] ? iconv ( 'UTF-8', 'GBK', $data ['POI_ADDRESS'] ) : $data ['POI_ADDRESS'];
			$data ['POI_NOTE'] = $data ['POI_NOTE'] ? iconv ( 'UTF-8', 'GBK', $data ['POI_NOTE'] ) : $data ['POI_NOTE'];
			$data ['NOTE'] = $data ['NOTE'] ? iconv ( 'UTF-8', 'GBK', $data ['NOTE'] ) : $data ['NOTE'];
			$data ['BRANDS'] = $data ['BRANDS'] ? iconv ( 'UTF-8', 'GBK', $data ['BRANDS'] ) : $data ['BRANDS'];
			$data ['STATUS'] = $data ['STATUS'] ? iconv ( 'UTF-8', 'GBK', $data ['STATUS'] ) : $data ['STATUS'];
			$data ['CODE_TYPE'] = $data ['CODE_TYPE'] ? iconv ( 'UTF-8', 'GBK', $data ['CODE_TYPE'] ) : $data ['CODE_TYPE'];
			$data ['POI_TYPE'] = $data ['POI_TYPE'] ? iconv ( 'UTF-8', 'GBK', $data ['POI_TYPE'] ) : $data ['POI_TYPE'];
			$data ['OPEN_START'] = "2013-10-1 " . $data ['OPEN_START'];
			$data ['OPEN_END'] = "2013-10-1 " . $data ['OPEN_END'];
		}
		$result = $CdaoPoi->updatePoiInfo ( $data, $_GET ['POI_ID'] );
		if ($result) {
			print ("修改成功") ;
			if($floor_id_sel){
				$this->jsCall ( "parent.location.href = 'ea.php?r=Poi&FLOOR_ID={$_GET['FLOOR_ID']}&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}else{
				$this->jsCall ( "parent.location.href = 'ea.php?r=Poi&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
			}
		} else {
			print ("修改失败") ;
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
		$POST ['NAME'] =strtolower($POST ['NAME']);
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