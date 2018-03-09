<?php

class AdminController extends Controller {
	protected $user;
	protected $startTime;
	protected $needLogin = TRUE;
	public static $m = array ();
	
	public function __construct() {
		$this->user = CUserSession::getSessionAdmin ();
		$this->setCookieValue ();
		$this->startTime = $this->getCurrentTime ();
		if (! $this->user && $this->needLogin) {
			$this->redirect ( 'ea.php?r=Login/index' );
		}
		
	}
	public static function setCookieValue() {
		$cookie_life_time = time () + 315360000;
		setcookie ( session_name (), session_id (), $cookie_life_time );
	}
	
	function checkPermission($permission, $id = "") {
		if (empty ( $this->user )) {
			$this->jsAlert ( "对不起，请您登录后在执行操作！" );
			$this->jsCall ( "top.location.href = 'ea.php?r=login';" );
			die ();
		}
		if (! $id && is_array ( $this->user ['ROLE'] ) && ! in_array ( 'ADMIN', $this->user ['ROLE'] ) && is_array ( $this->user ['per'] ) && ! array_key_exists ( $permission, $this->user ['per'] )) {
			
			$this->jsAlert ( "对不起,您暂时无此权限!" );
			$this->jsCall ( "top.location.href = 'ea.php';" );
			die ();
		}
		if ($id && is_array ( $this->user ['ROLE'] ) && ! in_array ( 'ADMIN', $this->user ['ROLE'] ) && $this->user ['per'] ['AADMIN'] != $id) {
			$this->jsAlert ( "对不起,您暂时无此权限!" );
			$this->jsCall ( "top.location.href = 'ea.php';" );
			die ();
		}
	}
	
	function checkAdminPermission() {
		if (empty ( $this->user ) || ! in_array ( 'ADMIN', $this->user ['ROLE'] )) {
			$this->jsAlert ( '对不起，只有超级管理员才有权限做此操作！' );
			$this->jsCall ( "top.location.href = 'ea.php?r=login';" );
			die ();
		}
	}
	function isSuperAdmin() {
		return $this->user ['ROLE'] == 'ADMIN' ? true : false;
	}
	
	function jsCall($js) {
		header ( "Content-type: text/html; charset=gb2312" );
		echo "<script language=\"javascript\" type=\"text/javascript\">\r\n{$js}\r\n</script>";
	}
	
	function jsAlert($str) {
		$this->jsCall ( "alert('{$str}');" );
	}
	
	function jsJumpUrl($url) {
		$this->jsCall ( "location.href = '{$url}';" );
	}
	
	function jsJumpBack() {
		$this->jsCall ( 'history.go(-1);' );
	}
	
	/**
	 * 保存图片
	 *
	 * @param  $field_name 文件名
	 * @param  $path  存储路径
	 * @param  $entry 入口
	 * @return unknown
	 */
	function savePhoto($field_name, $path, $entry = "") {
		$picInfo = $_FILES [$field_name];
		if ($picInfo ['tmp_name']) {
			$picWidth = getimagesize ( $picInfo ['tmp_name'] );
			if (($picWidth [0] < 800 || $picWidth [1] < 400) && $entry == 'manual') {
				return array ('error' => '宽度和高度不符合规格，应上传宽度大于800高度大于400的图片', 'filename' => $picInfo ['name'] );
			} elseif (($picWidth [0] < 150 || $picWidth [1] < 150) && $entry == 'head') {
				return array ('error' => '宽度和高度不符合规格，应上传宽度大于150高度大于150的图片', 'filename' => $picInfo ['name'] );
			} elseif (($picWidth [0] < 400 || $picWidth [1] < 300) && $entry == 'new_type') {
				return array ('error' => '宽度和高度不符合规格，应上传宽度大于400高度大于300的图片', 'filename' => $picInfo ['name'] );
			} else {
				$upload = new CPhotoUpload ( $path, $field_name );
				
				$upload_result = $upload->save ();
				if (is_array ( $upload_result )) {
					//生成缩略图
					if ($entry == 'manual') {
						$array_path_size = array (PREVIEWS_PATH => (array (710, 350, Master_Dimension_EQUAL )), PREVIEWS_MEDIUMS_PATH => (array (650, 650, Master_Dimension_AUTO )), CEL_PATH => (array (220, 220, Master_Dimension_WIDTH )), EQUAL_PATH => (array (150, 150, Master_Dimension_EQUAL )), MIN_PATH => (array (50, 50, Master_Dimension_EQUAL )) );
					} elseif ($entry == 'head') {
						$array_path_size = array (EQUAL_PATH => (array (150, 150, Master_Dimension_EQUAL )), MIN_PATH => (array (50, 50, Master_Dimension_EQUAL )) );
					} elseif ($entry == 'new_type') {
						if ($picWidth [0] >= 650 && $picWidth [1] >= 650) {
							$array_path_size = array (PREVIEWS_MEDIUMS_PATH => (array (650, 650, Master_Dimension_AUTO )), CEL_PATH => (array (220, 220, Master_Dimension_WIDTH )), EQUAL_PATH => (array (150, 150, Master_Dimension_EQUAL )), MIN_PATH => (array (50, 50, Master_Dimension_EQUAL )) );
						} elseif ($picWidth [0] >= 710 && $picWidth [1] >= 350) {
							$array_path_size = array (PREVIEWS_PATH => (array (710, 350, Master_Dimension_EQUAL )), PREVIEWS_MEDIUMS_PATH => (array (650, 650, Master_Dimension_AUTO )), CEL_PATH => (array (220, 220, Master_Dimension_WIDTH )), EQUAL_PATH => (array (150, 150, Master_Dimension_EQUAL )), MIN_PATH => (array (50, 50, Master_Dimension_EQUAL )) );
						} else {
							$array_path_size = array (CEL_PATH => (array (220, 220, Master_Dimension_WIDTH )), EQUAL_PATH => (array (150, 150, Master_Dimension_EQUAL )), MIN_PATH => (array (50, 50, Master_Dimension_EQUAL )) );
						}
					} else {
						$array_path_size = array (PREVIEWS_PATH => (array (710, 350, Master_Dimension_EQUAL )), PREVIEWS_MEDIUMS_PATH => (array (650, 650, Master_Dimension_AUTO )), CEL_PATH => (array (220, 220, Master_Dimension_WIDTH )), EQUAL_PATH => (array (150, 150, Master_Dimension_EQUAL )), MIN_PATH => (array (50, 50, Master_Dimension_EQUAL )) );
					}
					
					$upload->saveThumbnail ( $upload_result, $array_path_size );
					
					$upload_result = is_array ( $upload_result ) ? $upload_result [0] : $upload_result;
					
					$dest_file = $upload_result ['dest_file'];
					
					$mime_type = $upload_result ['mime_type'];
					$fileExt = '.' . $upload->getFileExtension ( $dest_file );
					if ($upload_result ['error']) {
						return $upload_result ['error'];
					}
					
					return array ('dest_file' => $dest_file, 'mime_type' => $mime_type );
				}
			}
		}
	}
	function writeAdminLog($log_type, $log_pri, $log_text) {
		//$logmgr = new CLogMgr();   //由于后台用户表变为SYS_SUBS表，而框架写日志的方法是用CS_SUBS表，因此这里用回以前的方法
		$userinfo = $this->user ['SYS_SUBS_ID'];
		if ($userinfo)
			//$logmgr->toLogFile($userinfo['SYS_SUBS_ID'],$log_type,'SYS',$this->getOperation (),'poi',$this->startTime,$log_text);
			$aSysLog ['LOG_TYPE'] = $log_type;
		$aSysLog ['LOG_PRI'] = $log_pri;
		$aSysLog ['LOG_TEXT'] = $log_text;
		$aSysLog ['SUBS_ID'] = $this->user ['SYS_SUBS_ID'];
		
		$aSysLog ['OWNER_ID'] = 0;
		$aSysLog ['OWNER_NAME'] = '';
		$aSysLog ['SERV_ID'] = 0;
		$aSysLog ['SERV_TYPE'] = '';
		$aSysLog ['ENTITY_ID'] = 0;
		$aSysLog ['OP'] = $this->getOperation ();
		$aSysLog ['COST'] = $this->getCurrentTime () - $this->startTime;
		
		$aSysLog ['LOG_IP'] = $_SERVER ['REMOTE_ADDR'];
		$aSysLog ['GET_URL'] = $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
		$aSysLog ['REFER'] = $_SERVER ['HTTP_REFERER'];
		
		$arr_refer = preg_split ( "/\//", $log ['REFER'] );
		$aSysLog ['REFER_SITE'] = $arr_refer [2];
		$aSysLog ['USER_AGENT'] = substr ( $_SERVER ['HTTP_USER_AGENT'], 0, 254 );
		$aSysLog ['AGENT_TYPE'] = CGetAgent::GetAgent ( 1 );
		$aSysLog ['SUB_SITE'] = $_SERVER ['SERVER_NAME'];
		$aSysLog ['CREATE_TIME'] = date ( 'Y-m-d H:i:s' );
		
		$DAOLog = new CDAOLog ( );
		try {
			$DAOLog->addAdminSysLog ( $aSysLog );
		} catch ( Exception $e ) {
		}
	}
	
	protected function getOperation() {
		$ref = new ReflectionClass ( $this );
		$controllerId = str_replace ( 'Controller', '', $ref->name );
		return $controllerId . '->' . $this->getAction ()->getId ();
	}
	
	protected function getCurrentTime() {
		list ( $msec, $sec ) = explode ( ' ', microtime () );
		return ( float ) $msec + ( float ) $sec;
	}
	
	//format request data:
	function checkRequestFormat($type = 'post') {
		if (strtolower ( $type ) == 'post')
			$_data = &$_POST;
		else
			$_data = &$_GET;
		
		foreach ( $_data as $key => $param ) {
			if (is_int ( $param ))
				$_data [$key] = intval ( $param );
			else if (is_string ( $param ))
				$_data [$key] = $this->qstr ( $param );
		}
		
		return $_data;
	}
	
	public function qstr($str) {
		if (get_magic_quotes_gpc ()) {
			$str = stripslashes ( $str );
		}
		return $str;
	}
	
	public function inject_check($str) {
		$tmp = preg_match ( '/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/', $str ); // 进行过滤
		if ($tmp) {
			return "====================";
		} else {
			return $str;
		}
	}
	
	function getDefaultPhotoUrl() {
		return "images/default_notice.gif";
	}
	
	protected function getExportFileCount($rows, $limitnum) {
		if ($rows < $limitnum) {
			return 1;
		}
		
		if (0 == $rows % $limitnum) {
			return $rows / $limitnum;
		}
		
		return ($rows - $rows % $limitnum) / $limitnum + 1;
	}
	
	public static function getModel($_m_name) {
		if (isset(self::$m[$_m_name]) == false )
			return false;
		else
			return self::$m [$_m_name];
		//change by haka
		//return self::$m [$_m_name];
	}
	
	public static function setModel($_m_name) {
		self::$m [$_m_name] = new $_m_name ( );
	}
	
	public static function getInstance($_class_name) {
		if (! self::getModel ( $_class_name )) {
			self::setModel ( $_class_name );
		}
		return self::getModel ( $_class_name );
	}
	/*
     * @author:yzb
     * 根据景区、景点、POI、设施点计算的范围保存到相应的景区范围表
     * $areaid:景区标识
     */
	public function setAreaLngLat($area_id) {
		if (! $area_id)
			return false;
		$DAOLS_AREA = new CDAOLS_AREA ( );
		$DAOLS_SPOT = new CDAOLS_SPOT ( );
		$DAOLS_POI = new CDAOLS_POI ( );
		$DAOLS_FPOINT = new CDAOLS_FPOINT ( );
		$DAOLS_RANGE = new CDAOLS_RANGE();
		
		$areainfo = $DAOLS_AREA->getAreaById ( $area_id ); //景区信息
		$spotlist = $DAOLS_SPOT->getAll ( "AREA_ID = {$area_id} AND STATUS='A'" ); //全部景点列表
		$poilist = $DAOLS_POI->getPoisByAreaId ( $area_id ); //全部POI列表
		$fpointlist = $DAOLS_FPOINT->getAll ( "AREA_ID = {$area_id} AND STATUS='A'" ); //全部设施点列表
		
		$longitude = array (); //记录所有点的经度值
		$latitude = array (); //记录所有点的维度值
		if ($areainfo ['ENT_LONGITUDE'] > 0 && $areainfo ['ENT_LATITUDE'] > 0) {
			array_push ( $longitude, $areainfo ['ENT_LONGITUDE'] );
			array_push ( $latitude, $areainfo ['ENT_LATITUDE'] );
		}
		if ($spotlist) {
			foreach ( $spotlist as $key => $value ) {
				if ($value ['ENT_LONGITUDE'] > 0 && $value ['ENT_LATITUDE'] > 0) {
					array_push ( $longitude, $value ['ENT_LONGITUDE'] );
					array_push ( $latitude, $value ['ENT_LATITUDE'] );
				}
			}
		}
		if ($poilist) {
			foreach ( $poilist as $key => $value ) {
				if ($value ['LONGITUDE'] > 0 && $value ['LATITUDE'] > 0) {
					array_push ( $longitude, $value ['LONGITUDE'] );
					array_push ( $latitude, $value ['LATITUDE'] );
				}
			}
		}
		if ($fpointlist) {
			foreach ( $fpointlist as $key => $value ) {
				if ($value ['LONGITUDE'] > 0 && $value ['LATITUDE'] > 0) {
					array_push ( $longitude, $value ['LONGITUDE'] );
					array_push ( $latitude, $value ['LATITUDE'] );
				}
			}
		}
		sort ( $longitude ); //经度排序
		sort ( $latitude ); //维度排序
		if (count ( $longitude ) > 0) {
			$data ['W_LONGITUDE'] = $longitude [0]; //经度最小值
			$data ['E_LONGITUDE'] = $longitude [count ( $longitude ) - 1]; //经度最大值
		}
		if (count ( $latitude ) > 0) {
			$data ['S_LATITUDE'] = $latitude [0]; //纬度最小值
			$data ['N_LATITUDE'] = $latitude [count ( $latitude ) - 1]; //纬度最大值
		}
		if($data['E_LONGITUDE']&&$data['W_LONGITUDE']&&$data['N_LATITUDE']&&$data['S_LATITUDE']){
			$data['C_LONGITUDE'] = ($data['E_LONGITUDE']+$data['W_LONGITUDE'])/2;
			$data['C_LATITUDE'] = ($data['N_LATITUDE']+$data['S_LATITUDE'])/2;
			$data['MOD_TIME'] = date("Y-m-d H:i:s");
			if($DAOLS_RANGE->load("AREA_ID ={$area_id} AND RANGE_TYPE='AREA'")){
				$DAOLS_RANGE->doUpdate("AREA_ID={$area_id} AND RANGE_TYPE='AREA'",$data);
			}else{
				$data['AREA_ID'] = $area_id;
				$data['RANGE_TYPE'] = 'AREA';
				$data['CREATE_TIME'] = date("Y-m-d H:i:s");
				$DAOLS_RANGE->doInsert($data,'SEQ_RANGE_ID');
			}
		}
	}
}
