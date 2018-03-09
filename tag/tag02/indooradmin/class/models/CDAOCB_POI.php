<?php
/**
 * POI表操作类
 * @author liang.jf
 */
class CDAOCB_POI extends CCDAOCB_POI {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	function getNextSeqId() {
		return ($this->DB ()->nextId ( $this->_cb_poi_sequence ));
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
	 * 获取POI信息
	 */
	function getPoi_list($pageSize, $pageNum, $whereSql, $BUILDING_ID) {
		$sql = "select * from {$this->_table} WHERE ";
		$sql .= "$whereSql and BUILDING_ID= '{$BUILDING_ID}'";
		$Poi_list = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		if ($Poi_list) {
			return $Poi_list;
		} else {
			return false;
		}
	}
	
	/**
	 * 获取POI信息
	 */
	function get_floor_poi_list($pageSize, $pageNum, $FLOOR_ID) {
		$sql = "select * from {$this->_table} WHERE ";
		$sql .= "FLOOR_ID= '{$FLOOR_ID}'";
		$Poi_list = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		if ($Poi_list) {
			return $Poi_list;
		} else {
			return false;
		}
	}
	/**
	 * 检测添加POI位置是否已建立POI
	 */
	function getTheAdd_INSTALL_LOCAT($POSITION_X, $POSITION_Y, $FLOOR_ID, $BUILDING_ID) {
		$sql = "SELECT  STATUS FROM {$this->_table} WHERE BUILDING_ID = '{$BUILDING_ID}' and FLOOR_ID = '{$FLOOR_ID}' and POSITION_Y between {$POSITION_Y}-5 and {$POSITION_Y}+5 and POSITION_X between {$POSITION_X}-5 and {$POSITION_X}+5";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * 检测修改的POI位置是否已建立POI
	 */
	function getThe_INSTALL_LOCAT($POSITION_X, $POSITION_Y, $FLOOR_ID, $BUILDING_ID, $POI_ID) {
		$sql = "SELECT  STATUS FROM {$this->_table} WHERE BUILDING_ID = '{$BUILDING_ID}' and FLOOR_ID = '{$FLOOR_ID}' and POSITION_Y between {$POSITION_Y}-5 and {$POSITION_Y}+5 and POSITION_X between {$POSITION_X}-5 and {$POSITION_X}+5 and POI_ID!={$POI_ID}";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * 新增POI信息
	 */
	function addPoi_list($PoiInfo) {
		$PoiInfo ["CREATE_TIME"] = date ( 'Y-m-d H:i:s' );
		$PoiInfo ["MOD_TIME"] = date ( 'Y-m-d H:i:s' );
		$this->setFrom ( $PoiInfo );
		if ($this->Insert ()) {
			return $PoiInfo ["POI_ID"];
		}
		return false;
	}
	
	/**
	 * 根据POI_ID获取POI信息
	 */
	function getPoiData($POI_ID) {
		if (empty ( $POI_ID ))
			return false;
		$sql = "SELECT * FROM {$this->_table} WHERE POI_ID = '{$POI_ID}' ";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}
	
	/**
	 * 保存修改POI信息
	 */
	function updatePoiInfo($POI_LIST) {
		$POI_LIST ['MOD_TIME'] = date ( 'Y/m/d H:i:s' );
		$this->setFrom ( $POI_LIST );
		if ($this->update ()) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 查询无需修改状态的POI
	 */
	function SaveStatus($where, $status) {
		$sql = "SELECT POI_ID from {$this->_table} ";
		$sql .= "where ($where) and STATUS <> '{$status}'";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return true;
		} else
			return false;
	}
	
	/**
	 * 修改POI状态
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
	 * 按where条件查询所有数据
	 *
	 * @param unknown $where        	
	 */
	function getAllWhere($selection, $where) {
		$sql = "SELECT {$selection} FROM {$this->_table} WHERE " . $where;
		return ($this->DB ()->getAll ( $sql ));
	}
}
?>