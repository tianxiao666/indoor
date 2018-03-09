<?php
/**
 * 定位信息表操作类
 * @author liang.jf
 */
class CDAOCB_LOCATION extends CCDAOCB_LOCATION {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取分页信息 
	 */
	function getEqutPageData($pageSize, $pageNum, $whereSql) {
		$sql = "SELECT * FROM {$this->_table} where";
		if ($whereSql) {
			$sql .= $whereSql;
		}
		$result = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		return $result->_array;
	}
	
	/**
	 * 获取定位信息，有搜索条件
	 */ 
	function getEqut_list($pageSize, $pageNum, $whereSql, $BUILDING_ID) {
		$sql = "select * from {$this->_table} WHERE ";
		$sql .= "$whereSql and BUILDING_ID= '{$BUILDING_ID}'";
		$Equt_list = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		if ($Equt_list) {
			return $Equt_list;
		} else {
			return false;
		}
	}

	/**
	 * 获取AP信息
	 */
	function getApList($where) {
		$sql = "select * from {$this->_table} ".$where;
		return ($this->DB ()->getAll ( $sql ));
	}

	/**
	 * 获取定位信息，有搜索条件
	 */
	function get_floor_equt_list($pageSize, $pageNum, $FLOOR_ID) {
		$sql = "select * from {$this->_table} WHERE ";
		$sql .= "FLOOR_ID= '{$FLOOR_ID}'";
		$Equt_list = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		if ($Equt_list) {
			return $Equt_list;
		} else {
			return false;
		}
	}
	
	/**
	 * 检测添加设备此处设备是否已建立
	 */
	/*function getTheAdd_INSTALL_LOCAT($POSITION_X, $POSITION_Y, $FLOOR_ID, $BUILDING_ID) {
		$sql = "SELECT  STATUS FROM {$this->_table} WHERE BUILDING_ID = '{$BUILDING_ID}' and FLOOR_ID = '{$FLOOR_ID}' and POSITION_Y between {$POSITION_Y}-5 and {$POSITION_Y}+5 and POSITION_X between {$POSITION_X}-5 and {$POSITION_X}+5";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return false;
		} else {
			return true;
		}
	}*/
	
	/**
	 * 检测修改设备此处设备是否已建立
	 */
	/*
	function getThe_INSTALL_LOCAT($POSITION_X, $POSITION_Y, $FLOOR_ID, $BUILDING_ID,$EQUT_ID) {
		$sql = "SELECT  STATUS FROM {$this->_table} WHERE BUILDING_ID = '{$BUILDING_ID}' and FLOOR_ID = '{$FLOOR_ID}' and POSITION_Y between {$POSITION_Y}-5 and {$POSITION_Y}+5 and POSITION_X between {$POSITION_X}-5 and {$POSITION_X}+5 and EQUT_ID!={$EQUT_ID}";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return false;
		} else {
			return true;
		}
	}*/
	
	/**
	 * 新增定位设备信息
	 */
	function addEqut_list($EqutInfo) {
		$EqutInfo ['EQUT_ID'] = $this->DB ()->nextId ( $this->_ap_equt_sequence );
		$EqutInfo ["CREATE_TIME"] = date ( 'Y-m-d H:i:s' );
		$EqutInfo ["MOD_TIME"] = date ( 'Y-m-d H:i:s' );
		$this->setFrom ( $EqutInfo );
		if ($this->Insert ()) {
			return $EqutInfo;
		}
		return false;
	}
	
	/**
	 * 根据设备ID获取楼层信息
	 */
	function getEqutData($EQUT_ID) {
		if (empty ( $EQUT_ID ))
			return false;
		$sql = "SELECT * FROM {$this->_table} WHERE EQUT_ID = '{$EQUT_ID}' ";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}
	
	/**
	 * 保存修改设备信息
	 */
	function updateEqutInfo($EQUT_LIST) {
		$EQUT_LIST ['MOD_TIME'] = date ( 'Y/m/d H:i:s' );
		$sql = "update {$this->_table} set MOD_TIME='{$EQUT_LIST['MOD_TIME']}',FLOOR_ID='{$EQUT_LIST['FLOOR_ID']}', EQUT_SSID='{$EQUT_LIST['EQUT_SSID']}', FACTORY='{$EQUT_LIST['FACTORY']}',BRANDS='{$EQUT_LIST['BRANDS']}',EQUT_TYPE='{$EQUT_LIST['EQUT_TYPE']}',EQUT_MODEL='{$EQUT_LIST['EQUT_MODEL']}', MAC_BSSID='{$EQUT_LIST['MAC_BSSID']}', POSITION_Y='{$EQUT_LIST['POSITION_Y']}',POSITION_X='{$EQUT_LIST['POSITION_X']}',INSTALL_LOCAT='{$EQUT_LIST['INSTALL_LOCAT']}',RATE='{$EQUT_LIST['RATE']}', CHANNEL='{$EQUT_LIST['CHANNEL']}', EQUT_NOTE='{$EQUT_LIST['EQUT_NOTE']}',STATUS='{$EQUT_LIST['STATUS']}'";
		$sql .= "where EQUT_ID ={$EQUT_LIST['EQUT_ID']}";
		if ($this->DB ()->Execute ( $sql )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 查询无需修改状态的设备
	 */
	function SaveStatus($where, $status) {
		$sql = "SELECT EQUT_ID from {$this->_table} ";
		$sql .= "where ($where) and STATUS <> '{$status}'";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return true;
		} else
			return false;
	}
	
	/**
	 * 修改设备状态
	 */
	function ChangeStatus($where, $status) {
		if (! $where && ! $status) {
			return FALSE;
		}
			$sql = "update {$this->_table} set STATUS ='{$status}' ";
			$sql .= "where $where";
		if ($this->DB ()->Execute ( $sql )) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>