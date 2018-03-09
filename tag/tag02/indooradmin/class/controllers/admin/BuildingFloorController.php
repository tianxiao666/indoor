<?php
/**
 * 楼层管理类
 * @author liang.jf
 * 
 */
class BuildingFloorController extends AdminController {
	public $_page = 1; // 当前页
	public $_count =15; // 每页个数
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 楼层列表
	 * Enter description here .
	 */
	public function actionIndex() {
		$Flag = $_GET ["flag"];
		//搜索查询
		if ($Flag == 1) {
			$pageData ['whereSql'] = $_POST;
			CUserSession::removeSessionValue ( 'POIconditions' );
			CUserSession::setSessionValue ( 'POIconditions', $_POST );
		}
		//非搜索查询
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
		$whereSql = $this->genWhereSqlSearch ( $_POST );
		$cdaocb_floor = new CDAOCB_FLOOR ();
		$floorlist = $cdaocb_floor->getFloor_list ( $this->_count, $this->_page, $whereSql,$_GET ['BUILDING_ID']); // 取所在场所全部的楼层数据
		$total = $floorlist->_maxRecordCount;
		$p = new page ( $total );
		$p->baseUrl = 'ea.php?r=BuildingFloor&BUILDING_ID=' . $_GET ['BUILDING_ID'] . '&BUILDING_NAME=' . $_GET ['BUILDING_NAME'] . '&NAME=' . $_POST ['NAME'];
		$p->pagesize = $this->_count;
		$page = $p->generate ();
		//获取楼层类型和状态
		$cdict = new CDict ();
		$pageData ["FLOOR_STATUS"] = $cdict->FLOOR_STATUS;
		$pageData ["FLOOR_TYPE"] = $cdict->FLOOR_TYPE;
		$pageData ['total'] = $total;
		$pageData ['page'] = $page;
		$pageData ['whereSql'] = $_POST;
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['floor_list'] = $floorlist->_array;
		//限制楼层说明的显示长度
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
	 * 新增楼层页面
	 * Enter description here .
	 * ..
	 */
	public function actionAddFloor() {
		$cdict = new CDict ();
		$pageData ["FLOOR_TYPE"] = $cdict->FLOOR_TYPE;
		$pageData ["BASEMENT"] = $cdict->BASEMENT;
		$pageData ['NAME'] = $_GET ['NAME'];
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$this->render ( "buildingFloor_add", $pageData );
	}
	
	/**
	 * 保存新增楼层数据
	 * Enter description here .
	 * ..
	 */
	public function actionFloorAdd() {
		$_POST = CDAOCB_BUILDING::stripForm ( $_POST );//去掉多余的空格
		$data = $_POST;
		$Cdaofloor_PHYSICAL_FLOOR = new CDAOCB_FLOOR ();
		//检测楼层是否存在
		$PHYSICAL_FLOOR = $Cdaofloor_PHYSICAL_FLOOR->getThe_PHYSICAL_FLOOR ( $_GET ['BUILDING_ID'], $_POST ['PHYSICAL_FLOOR'],$_POST ['FLOOR_NAME'] );
		if (! $PHYSICAL_FLOOR) {
			$this->jsAlert("该楼层已存在，如需修改请点击该楼层修改选项！");
			return false;
		}
		$FLOOR_ID = $Cdaofloor_PHYSICAL_FLOOR->getNextSeqId ();
		// 保存楼层图片信息
		if ($_FILES ["file"] ['name']) {
			$FILE_NAME=$_GET['BUILDING_NAME']."_".$_POST ['FLOOR_NAME']."_".date ( "YmdHis" );
			$picAdd_returnPic_id = SvgUtil::savePic ( $FILE_NAME, '', $_FILES ["file"], $FLOOR_ID, $_GET ['BUILDING_ID'], '' ); // 返回PIC_ID
			if ($picAdd_returnPic_id == '') {
				$this->jsAlert ( "保存失败！" );
				$this->jsJumpBack ();
			}
			$data ['PIC_ID'] = $picAdd_returnPic_id;
		}
		$data ['FLOOR_ID'] = $FLOOR_ID;
		$data ['BUILDING_ID'] = $_GET ['BUILDING_ID'];
		$data ['STATUS'] = "A";
		$Cdaofloor = new CDAOCB_FLOOR ();
		$FloorData = $Cdaofloor->AddFloor_list ( $data ); // 返回楼层信息
		if ($FloorData) {
			$this->jsAlert("添加成功");
			$this->jsCall ( "parent.location.href = 'ea.php?r=BuildingFloor&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
		} else {
			$this->jsAlert("添加失败!");
			$this->jsJumpBack ();
			return false;
		}
	}
	
	/**
	 * 编辑楼层页面
	 * Enter description here .
	 * ..
	 */
	public function actionEditFloor() {
		$CdaoFloor_list = new CDAOCB_FLOOR ();
		//获取楼层信息
		$FloorInfo = $CdaoFloor_list->getFloorData ( $_GET ['FLOOR_ID'] );
		//引号显示的处理
		$FloorInfo[0]['FLOOR_NAME']=str_replace('"', '&quot', $FloorInfo[0]['FLOOR_NAME']);
		$cdict = new CDict ();
		$pageData ["FLOOR_TYPE"] = $cdict->FLOOR_TYPE;
		$pageData ["BASEMENT"] = $cdict->BASEMENT;
		$pageData ['NAME'] = $_GET ['NAME'];
		$pageData ['BUILDING_NAME'] = $_GET ['BUILDING_NAME'];
		$pageData ['floor_list'] = $FloorInfo;
		$this->render ( "buildingFloor_change", $pageData );
	}
	
	/**
	 * 保存编辑楼层
	 * Enter description here .
	 * ..
	 */
	public function actionSaveFloor() {
		$_POST = CDAOCB_BUILDING::stripForm ( $_POST );//去掉多余的空格
		$data = $_POST;
		//检测楼层是否存在
		$Cdaofloor_PHYSICAL_FLOOR = new CDAOCB_FLOOR ();
		if ($_POST ['PHYSICAL_FLOOR'] != $_POST ['PHYSICAL_FLOOR_1']) {
			$PHYSICAL_FLOOR = $Cdaofloor_PHYSICAL_FLOOR->getThe_PHYSICAL_FLOOR ( $_GET ['BUILDING_ID'], $_POST ['PHYSICAL_FLOOR'],$_POST ['FLOOR_NAME']);
			if (! $PHYSICAL_FLOOR) {
				$this->jsAlert("该楼层已存在，如需修改请点击该楼层修改选项！");
				return false;
			}
		}
		// 保存图片信息
		if ($_FILES ["file"] ['name']) {
			$FILE_NAME=$_GET['BUILDING_NAME']."_".$_POST ['FLOOR_NAME']."_".date ( "YmdHis" );
			$picAdd_returnPic_id = SvgUtil::savePic ( $FILE_NAME, '', $_FILES ["file"], $_POST ['FLOOR_ID'], $_GET ['BUILDING_ID'], '' ); // 返回PIC_ID
			if ($picAdd_returnPic_id == '') {
				$this->jsAlert ( "保存失败！" );
				$this->jsJumpBack ();
			}
			$data ['PIC_ID'] = $picAdd_returnPic_id;
		}
		if ($data ['FLOOR_ID']) {
			$data ['STATUS'] =$_POST['STATUS'];
			$data ['BUILDING_ID'] =$_GET['BUILDING_ID'];
		}
		$Cdaofloor = new CDAOCB_FLOOR ();
		$result = $Cdaofloor->updateFloorInfo ( $data );
		if ($result) {
			$this->jsAlert("修改成功");
			$this->jsCall ( "parent.location.href = 'ea.php?r=BuildingFloor&BUILDING_ID={$_GET['BUILDING_ID']}&BUILDING_NAME={$_GET['BUILDING_NAME']}&flag=2';" );
		} else {
			$this->jsAlert("修改失败");
			return false;
		}
	}
	/**
	 * 修改楼层状态
	 * Enter description here .
	 * ..
	 */
	public function actionChangeStateAll() {
		$floor_id_list = explode ( ",", $_POST ['floor_id_list'] );
		$length = count ( $floor_id_list );
		//生成修改楼层状态的sql条件
		$where = null;
		for($i = 0; $i < $length - 1; $i ++) {
			if ($where == null) {
				$where = 'FLOOR_ID=' . $floor_id_list [$i];
			} else {
				$where = $where . ' or FLOOR_ID=' . $floor_id_list [$i];
			}
		}
		$CdaoFloor = new CDAOCB_FLOOR ();
		//判断需要修改状态的楼层是否为0
		$result = $CdaoFloor->SaveStatus ( $where, $_POST ['status'] );
		if (! $result) {
			$status = "状态无需修改!";
			echo iconv ( 'GB2312', 'UTF-8', $status );
			return false;
		}
		//修改楼层状态
		$result = $CdaoFloor->ChangeStatus ( $where, $_POST ['status'] );
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
	 * 搜索楼层SQL条件
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