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
	function getNextSeqId() {
		return ($this->DB ()->nextId ( $this->_ap_sequence ));
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
	 * 新增定位设备信息
	 */
	function addEqut_list($EqutInfo) {
		$EqutInfo ['AP_ID'] = $this->DB ()->nextId ( $this->_ap_sequence );
		$EqutInfo ["CREATE_TIME"] = date ( 'Y-m-d H:i:s' );
		$EqutInfo ["MOD_TIME"] = date ( 'Y-m-d H:i:s' );
		$this->setFrom ( $EqutInfo );
		if ($this->Insert ()) {
			return $EqutInfo ['AP_ID'];
		}
		return false;
	}
	
	/**
	 * 根据设备ID获取楼层信息
	 */
	function getEqutData($AP_ID) {
		if (empty ( $AP_ID ))
			return false;
		$sql = "SELECT * FROM {$this->_table} WHERE AP_ID = '{$AP_ID}' ";
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
	function updateEqutInfo($AP_LIST) {
		$AP_LIST ['MOD_TIME'] = date ( 'Y/m/d H:i:s' );
		$this->setFrom ( $AP_LIST );
		if ($this->update ( )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 查询无需修改状态的设备
	 */
	function SaveStatus($where, $status) {
		$sql = "SELECT AP_ID from {$this->_table} ";
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
	/**
	 * 查询条件
	 *
	 * @param unknown $selection
	 * @param unknown $where
	 */
	function getAllWhere($selection, $where) {
		$sql = "SELECT {$selection} FROM {$this->_table} WHERE " . $where;
		return ($this->DB ()->getAll ( $sql ));
	}
}
?>