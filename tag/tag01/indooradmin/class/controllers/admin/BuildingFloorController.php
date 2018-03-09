<?php
/**
 * ¥�������
 * @author liang.jf
 * 
 */
class BuildingFloorController extends AdminController {
	public $_page = 1; // ��ǰҳ
	public $_count =15; // ÿҳ����
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * ¥���б�
	 * Enter description here .
	 */
	public function actionIndex() {
		$Flag = $_GET ["flag"];
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
			$this->_page = $_GET ['page']; // ��ȡ��ҳ
		}
		if ($_GET ['NAME']) {
			$_POST ['NAME'] = $_GET ['NAME'];
		}
		$whereSql = $this->genWhereSqlSearch ( $_POST );
		$cdaocb_floor = new CDAOCB_FLOOR ();
		$floorlist = $cdaocb_floor->getFloor_list ( $this->_count, $this->_page, $whereSql,$_GET ['BUILDING_ID']); // ȡ���ڳ���ȫ����¥������
		$total = $floorlist->_maxRecordCount;
		$p = new page ( $total );
		$p->baseUrl = 'ea.php?r=BuildingFloor&BUILDING_ID=' . $_GET ['BUILDING_ID'] . '&BUILDING_NAME=' . $_GET ['BUILDING_NAME'] . '&NAME=' . $_POST ['NAME'];
		$p->pagesize = $this->_count;
		$page = $p->generate ();
		$cdict = new CDict ();
		$pageData ["FLOOR_STATUS"] = $cdict->FLOOR_STATUS;
		$pageData ['total'] = $total;
		$pageData ['page'] = $page;
		$pageData ['whereSql'] = $_POST;
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['floor_list'] = $floorlist->_array;
		//����¥��˵������ʾ����
		if ($pageData ['floor_list']) {
			foreach ( $pageData ['floor_list'] as $k => $v ) {
				if (strlen ( $pageData ['floor_list'] [$k] ['FLOOR_NOTE'] ) > 38) {
					$pageData ['floor_list'] [$k] ['FLOOR_NOTE'] = iconv_substr ( $pageData ['floor_list'] [$k] ['FLOOR_NOTE'], 0, 17,"GBK" ) . "...";
				}
			}
		}
		$this->render ( "buildingfloor_list", $pageData );
	}
	
	/**
	 * ����¥��ҳ��
	 * Enter description here .
	 * ..
	 */
	public function actionAddFloor() {
		$pageData ['NAME'] = $_GET ['NAME'];
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$this->render ( "buildingFloor_add", $pageData );
	}
	
	/**
	 * ����¥������
	 * Enter description here .
	 * ..
	 */
	public function actionFloorAdd() {
		$_POST ['FLOOR_NAME']=iconv('UTF-8', 'GBK', $_POST ['FLOOR_NAME']);
		$Cdaofloor_PHYSICAL_FLOOR = new CDAOCB_FLOOR ();
		$PHYSICAL_FLOOR = $Cdaofloor_PHYSICAL_FLOOR->getThe_PHYSICAL_FLOOR ( $_GET ['BUILDING_ID'], $_POST ['PHYSICAL_FLOOR'],$_POST ['FLOOR_NAME'] );
		if (! $PHYSICAL_FLOOR) {
			$floor="��¥���Ѵ��ڣ������޸�������¥���޸�ѡ�";
			echo iconv('GB2312', 'UTF-8', $floor);
			return false;
		}
		$data ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$data ['FLOOR_NOTE'] =  TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_NOTE'] ) ) ;
		$data ['FLOOR_NAME'] =  TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_NAME'] ) ) ;
		$data ['PHYSICAL_FLOOR'] =  TextFilter::filterAllHTML ( trim ( $_POST ['PHYSICAL_FLOOR'] ) ) ;
		$data ['ACREAGE'] =  TextFilter::filterAllHTML ( trim ( $_POST ['ACREAGE'] ) );
		$data ['FLOOR_WIDTH'] =  TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_WIDTH'] ) );
		$data ['FLOOR_HEIGHT'] =  TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_HEIGHT'] ) );
		$data ['STATUS'] = "A";
		$data ['FLOOR_NOTE'] =$data ['FLOOR_NOTE']?iconv('UTF-8', 'GBK', $data ['FLOOR_NOTE']):$data ['FLOOR_NOTE'];
		$Cdaofloor = new CDAOCB_FLOOR ();
		$FloorData = $Cdaofloor->addFloor_list ( $data ); // ����¥����Ϣ
		if ($FloorData) {
			print("��ӳɹ�!");
			$this->jsCall ( "parent.location.href = 'ea.php?r=BuildingFloor&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
		} else {
			print("���ʧ��!");
			$this->jsJumpBack ();
			return false;
		}
	}
	
	/**
	 * �༭¥��ҳ��
	 * Enter description here .
	 * ..
	 */
	public function actionEditFloor() {
		$CdaoFloor_list = new CDAOCB_FLOOR ();
		$FloorInfo = $CdaoFloor_list->getFloorData ( $_GET ['FLOOR_ID'] );
		$FloorInfo[0]['FLOOR_NAME']=str_replace('"', '&quot', $FloorInfo[0]['FLOOR_NAME']);
		$pageData ['NAME'] = $_GET ['NAME'];
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['floor_list'] = $FloorInfo;
		$this->render ( "buildingFloor_change", $pageData );
	}
	
	/**
	 * ����༭¥��
	 * Enter description here .
	 * ..
	 */
	public function actionSaveFloor() {
		$_POST ['FLOOR_NAME']=iconv('UTF-8', 'GBK', $_POST ['FLOOR_NAME']);
		$Cdaofloor_PHYSICAL_FLOOR = new CDAOCB_FLOOR ();
		if ($_POST ['PHYSICAL_FLOOR'] != $_POST ['PHYSICAL_FLOOR_1']) {
			$PHYSICAL_FLOOR = $Cdaofloor_PHYSICAL_FLOOR->getThe_PHYSICAL_FLOOR ( $_GET ['BUILDING_ID'], $_POST ['PHYSICAL_FLOOR'],$_POST ['FLOOR_NAME']);
			if (! $PHYSICAL_FLOOR) {
				$floor="��¥���Ѵ��ڣ������޸�������¥���޸�ѡ�";
				echo iconv('GB2312', 'UTF-8', $floor);
				return false;
			}
		}	
		if ($_POST ['FLOOR_ID']) {
			$floor ['FLOOR_ID'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_ID'] ) ) );
			$floor ['FLOOR_NOTE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_NOTE'] ) ) );
			$floor ['PHYSICAL_FLOOR'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['PHYSICAL_FLOOR'] ) ) );
			$floor ['FLOOR_NAME'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_NAME'] ) ) );
			$floor ['ACREAGE'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $_POST ['ACREAGE'] ) ) );
			$floor ['FLOOR_WIDTH'] =  TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_WIDTH'] ) );
			$floor ['FLOOR_HEIGHT'] =  TextFilter::filterAllHTML ( trim ( $_POST ['FLOOR_HEIGHT'] ) );
			$floor ['FLOOR_NOTE'] =$floor ['FLOOR_NOTE']?iconv('UTF-8', 'GBK', $floor ['FLOOR_NOTE']):$floor ['FLOOR_NOTE'];			
		}
		$Cdaofloor = new CDAOCB_FLOOR ();
		$result = $Cdaofloor->updateFloorInfo ( $floor );
		if ($result) {
			echo("�޸ĳɹ�");
			$this->jsCall ( "parent.location.href = 'ea.php?r=BuildingFloor&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
		} else {
			echo("�޸�ʧ��");
			return false;
		}
	}
	
	
	/**
	 * �޸�¥��״̬
	 * Enter description here .
	 * ..
	 */
	public function actionChangeStateAll() {
		$floor_id_list = explode ( ",", $_POST ['floor_id_list'] );
		$length = count ( $floor_id_list );
		$where = null;
		for($i = 0; $i < $length - 1; $i ++) {
			if ($where == null) {
				$where = 'FLOOR_ID=' . $floor_id_list [$i];
			} else {
				$where = $where . ' or FLOOR_ID=' . $floor_id_list [$i];
			}
		}
		$CdaoFloor = new CDAOCB_FLOOR ();
		$result = $CdaoFloor->SaveStatus ( $where, $_POST ['status'] );
		if (! $result) {
			$status = "״̬�����޸�!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
			return false;
		}
		$result = $CdaoFloor->ChangeStatus ( $where, $_POST ['status'] );
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
	 * ����¥��SQL
	 * Enter description here .
	 * ..
	 */ 
	public function genWhereSqlSearch($POST) {
		$whereSql = "1=1";
		 $POST ['NAME']=strtolower( $_POST ['NAME']);
		if ( $POST ['NAME']) {
			 $POST ['NAME'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim (  $POST ['NAME'] ) ) );
			$whereSql .= " AND lower(FLOOR_NAME) like '%". $POST ['NAME']."%'";
		}
		if ($POST ['STATUS']) {
			$POST ['STATUS'] = TextFilter::encodeQuote ( TextFilter::filterAllHTML ( trim ( $POST ['STATUS'] ) ) );
			$whereSql .= " AND STATUS = '{$POST['STATUS']}' ";
		}
		return $whereSql;
	}
}