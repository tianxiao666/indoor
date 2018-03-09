<?php
/**
 * 楼层表操作类
 * @author liang.jf
 */
class CDAOCB_FLOOR extends CCDAOCB_FLOOR {
	
	// 构造函数
	function __construct() {
		parent::__construct ();
	}
	
	// 获取分页信息
	function getFloorPageData($pageSize, $pageNum, $whereSql) {
		$sql = "SELECT FLOOR_NAME, BUILDING_ID, FLOOR_ID, ACREAGE, STATUS,FLOOR_NOTE FROM {$this->_table} where";
		if ($whereSql) {
			$sql .= $whereSql;
		}
		$result = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		return $result->_array;
	}
	
	// 获取楼层信息
	function getAllFloor_list($pageSize, $pageNum, $BUILDING_ID) {
		$sql = "select FLOOR_NAME, BUILDING_ID, FLOOR_ID, ACREAGE, STATUS,FLOOR_NOTE,PHYSICAL_FLOOR  from {$this->_table} WHERE BUILDING_ID= '{$BUILDING_ID}'";
		$floor_list = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		if ($floor_list) {
			return $floor_list;
		} else {
			return false;
		}
	}
	
	// 获取楼层信息
	function getFloor_list($pageSize, $pageNum, $whereSql, $BUILDING_ID) {
		$sql = "select FLOOR_NAME, BUILDING_ID, FLOOR_ID, ACREAGE, STATUS,FLOOR_NOTE,PHYSICAL_FLOOR  from {$this->_table} WHERE "; // FLOOR_NAME= '{$FLOOR_NAME}' and BUILDING_ID= '{$BUILDING_ID}'";
		$sql .= "$whereSql and BUILDING_ID= '{$BUILDING_ID}'";
		$floor_list = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		if ($floor_list) {
			return $floor_list;
		} else {
			return false;
		}
	}
	
	/**
	 * 检测物理楼层是否已建立
	 */
	function getThe_PHYSICAL_FLOOR($BUILDING_ID, $PHYSICAL_FLOOR,$FLOOR_NAME) {
		$sql = "SELECT  PHYSICAL_FLOOR FROM {$this->_table} WHERE BUILDING_ID = '{$BUILDING_ID}' and PHYSICAL_FLOOR = '{$PHYSICAL_FLOOR}' and FLOOR_NAME = '{$FLOOR_NAME}'";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return false;
		} else {
			return true;
		}
	}
	
	// 新增楼层信息
	function AddFloor_list($Floorinfo) {
		$Floorinfo ['FLOOR_ID'] = $this->DB ()->nextId ( $this->_cb_floor_sequence );
		$Floorinfo ["CREATE_TIME"] = date ( 'Y-m-d H:i:s' );
		$Floorinfo ["MOD_TIME"] = date ( 'Y-m-d H:i:s' );
		$this->setFrom ( $Floorinfo );
		if ($this->Insert ()) {
			return $Floorinfo;
		}
		return false;
	}
	
	/**
	 * 根据楼层ID获取楼层信息
	 */
	function getFloorData($FLOOR_ID) {
		if (empty ( $FLOOR_ID ))
			return false;
		$sql = "SELECT * FROM {$this->_table} WHERE FLOOR_ID = '{$FLOOR_ID}' ";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}
	
	/**
	 * 保存修改楼层信息
	 */
	function updateFloorInfo($FLOOR_LIST) {
		$FLOOR_LIST ['MOD_TIME'] = date ( 'Y/m/d H:i:s' );
		$sql = "update {$this->_table} set MOD_TIME='{$FLOOR_LIST['MOD_TIME']}',FLOOR_NAME='{$FLOOR_LIST['FLOOR_NAME']}', PHYSICAL_FLOOR='{$FLOOR_LIST['PHYSICAL_FLOOR']}', ACREAGE='{$FLOOR_LIST['ACREAGE']}',FLOOR_NOTE='{$FLOOR_LIST['FLOOR_NOTE']}', FLOOR_WIDTH='{$FLOOR_LIST['FLOOR_WIDTH']}',FLOOR_HEIGHT='{$FLOOR_LIST['FLOOR_HEIGHT']}' ";
		$sql .= "where FLOOR_ID ={$FLOOR_LIST['FLOOR_ID']}";
		if ($this->DB ()->Execute ( $sql )) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * 查询物理楼层是否存在
	 */
	function floor_PHYSICAL_FLOOR($PHYSICAL_FLOOR) {
		if (empty ( $PHYSICAL_FLOOR ))
			return false;
		$sql = "SELECT  PHYSICAL_FLOOR  FROM {$this->_table} WHERE PHYSICAL_FLOOR = '{$PHYSICAL_FLOOR}' ";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * 查询无需修改状态的楼层
	 */
	function SaveStatus($where, $status) {
		$sql = "select FLOOR_ID from {$this->_table} ";
		$sql .= "where ($where) and STATUS <> '{$status}'";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return true;
		} else
			return false;
	}
	
	/**
	 * 修改状态
	 */
	function ChangeStatus($where, $status) {
		if (! $where && ! $status) {
			return FALSE;
		}
		$sql = "update {$this->_table} set STATUS ='{$status}'";
		$sql .= "where $where";
		if ($this->DB ()->Execute ( $sql )) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	/**
	 * 根据场所ID获取相应的楼层名称和物理楼层
	 */
	function getFloor_id($building_id) {
		$sql = "SELECT distinct PHYSICAL_FLOOR,FLOOR_ID,FLOOR_NAME FROM {$this->_table} where BUILDING_ID={$building_id}";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}
	
	/**
	 * 根据楼层ID获取相应的楼层名称和物理楼层
	 */
	function getFloor_name($wheresql) {
		$sql = "SELECT distinct FLOOR_ID,PHYSICAL_FLOOR,FLOOR_NAME FROM {$this->_table} where FLOOR_ID={$wheresql}";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return $result;
		} else {
			return false;
		}
	}
	
	/**
	 * 通过楼层ID获取场所ID
	 */
	function getBuildIdByFloorId($FLOOR_ID) {
		$sql = "SELECT BUILDING_ID FROM {$this->_table}" . " WHERE FLOOR_ID={$FLOOR_ID}";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result [0] ["BUILDING_ID"]);
		}
		return (0);
	}
	/**
	 * 获取楼层列表
	 */
	function getAllFloor($BUILDING_ID) {
		$where = "BUILDING_ID={$BUILDING_ID} ORDER BY PHYSICAL_FLOOR DESC";
		return ($this->getFloorByWhere ( $where ));
	}
	/**
	 * 获取楼层列表
	 */
	function getFloorByWhere($where) {
		$sql = "SELECT distinct FLOOR_ID,PHYSICAL_FLOOR,FLOOR_NAME FROM {$this->_table} where " . $where;
		$result = $this->DB ()->getAll ( $sql );
		$floorList = array ();
		foreach ( $result as $val ) {
			$floorList [$val ['FLOOR_ID']] = "(" . $val ['PHYSICAL_FLOOR'] . "层)  " . $val ['FLOOR_NAME'];
		}
		return ($floorList);
	}
	/**
	 * 获取唯一标识ID的内容
	 *
	 * @param unknown $SEQ_ID        	
	 * @return NULL
	 */
	function getSeqNameBySeqId($SEQ_ID) {
		$sql = "SELECT FLOOR_NAME FROM {$this->_table} WHERE FLOOR_ID={$SEQ_ID}";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result [0] ["FLOOR_NAME"]);
		}
		return (null);
	}
}
?>