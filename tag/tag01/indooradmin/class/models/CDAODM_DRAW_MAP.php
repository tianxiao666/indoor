<?php
/**
 * 楼层制图画布基本属性
 * @author:mary 
 * @create_time:2013-07-31 
 */
class CDAODM_DRAW_MAP extends CCDAODM_DRAW_MAP {
	var $_table = 'DM_DRAW_MAP'; // associated database table
	/**
	 * 通过楼层标识得到该楼层所有的数据信息
	 */
	function getdrawMapInfoByFloorId($floor_id) {
		$floor_id = $floor_id ? intval ( $floor_id ) : 0;
		if ($floor_id > 0) {
			$sql = "select * from {$this->_table} where floor_id = {$floor_id}";
			$result = $this->DB ()->Execute ( $sql );
			if ($result->_array) {
				return $result->_array [0];
			} else {
				return false;
			}
		}
		
		return false;
	}
	/**
	 * 获取指定查询条件下，指定页面号，指定页面size的数据列表
	 *
	 * @param string $whereSql
	 *        	where语句，指定查询条件
	 * @param number $pageNum
	 *        	页面号
	 * @param number $pageSize
	 *        	页面size
	 * @return array 数据列表 | boolean false表示获取数据失败
	 */
	function listPageData($whereSql = '', $pageNum = 1, $pageSize = 10) {
		$sql = "SELECT
		distinct {$this->_table_seq_name},
		FLOOR_ID,
		DM_TOPIC,
		DM_NOTE,
		STATUS,
		VIEWBOX_WIDTH,
		VIEWBOX_HEIGHT,
		FLOOR_WIDTH,
		FLOOR_HEIGHT,
		DW_SCALE,
		DW_UNIT,
		BACKGROUD_COLOR FROM {$this->_table}";
		if (! empty ( $whereSql )) {
			$sql = $sql . " WHERE " . $whereSql;
		}
		$result = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		return ($result);
	}
	/**
	 * 通过标识ID获取楼层ID
	 *
	 * @param unknown $SeqId        	
	 * @return number
	 */
	function getFloorIdBySeqId($SeqId) {
		$sql = "SELECT FLOOR_ID FROM {$this->_table}" . " WHERE {$this->_table_seq_name}={$SeqId}";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result [0] ["FLOOR_ID"]);
		}
		return (0);
	}
	/**
	 * 通过楼层ID获取唯一标识ID
	 *
	 * @param number $FLOOR_ID
	 *        	楼层ID
	 * @return number 唯一标识ID
	 */
	function getSeqIdByFloorId($FLOOR_ID) {
		$sql = "SELECT {$this->_table_seq_name} FROM {$this->_table}" . " WHERE FLOOR_ID={$FLOOR_ID}";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result [0] [$this->_table_seq_name]);
		}
		return (0);
	}
	/**
	 * 通过楼层ID获取平面图信息
	 *
	 * @param number $FLOOR_ID
	 *        	楼层ID
	 * @return array 平面图信息数据
	 */
	function getPlanegraphInfoByFloorId($FLOOR_ID) {
		$sql = "SELECT
		distinct {$this->_table_seq_name},
		DM_TOPIC,
		DM_NOTE,
		STATUS,
		VIEWBOX_WIDTH,
		VIEWBOX_HEIGHT,
		FLOOR_WIDTH,
		FLOOR_HEIGHT,
		DW_SCALE,
		DW_UNIT,
		BACKGROUD_COLOR FROM {$this->_table}" . " WHERE FLOOR_ID={$FLOOR_ID}";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result [0]);
		}
		return (array ());
	}
	/**
	 * 通过唯一标识ID获取此行所有的数据
	 *
	 * @param number $SeqId
	 *        	唯一标识ID
	 * @return array 唯一标识ID所在行的数据
	 */
	function getRow($SeqId) {
		$sql = "SELECT	* FROM {$this->_table}" . " WHERE {$this->_table_seq_name} = {$SeqId}";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result [0]);
		}
		return (array ());
	}
	/**
	 * 修改指定楼层的STATUS为X
	 *
	 * @param number $FLOOR_ID
	 *        	楼层ID
	 * @return boolean false则sql执行失败
	 */
	function invalidByFloorId($FLOOR_ID) {
		$sql = "UPDATE {$this->_table}" . "SET STATUS='X' WHERE FLOOR_ID={$FLOOR_ID}";
		$result = $this->DB ()->Execute ( $sql );
		return ($result);
	}
	/**
	 * 获取下一个唯一标识ID
	 */
	function getNextSeqId() {
		return ($this->DB ()->nextId ( $this->_table_seq ));
	}
	/**
	 * 执行修改或新增操作
	 *
	 * @param array $FORM_INFO
	 *        	数据库字段数据数组
	 * @return boolean false则sql执行失败
	 */
	function doEdit($FORM_INFO) {
		$SEQ_ID = $FORM_INFO ["{$this->_table_seq_name}"];
		if (empty ( $SEQ_ID )) {
			$SEQ_ID = $this->getNextSeqId ();
			$FORM_INFO ["{$this->_table_seq_name}"] = $SEQ_ID;
			$FORM_INFO ["CREATE_TIME"] = date ( "Y-m-d H:i:s" );
		}
		return ($this->doSave ( $FORM_INFO ));
	}
	/**
	 * 执行修改或新增操作
	 *
	 * @param array $FORM_INFO
	 *        	数据库字段数据数组
	 * @return boolean false则sql执行失败
	 */
	function doSave($FORM_INFO) {
		$FORM_INFO = CDAOCB_BUILDING::stripForm ( $FORM_INFO );
		$SEQ_ID = $FORM_INFO ["{$this->_table_seq_name}"];
		$this->Load ( "{$this->_table_seq_name} = '$SEQ_ID'" );
		// if (empty ( $FORM_INFO ["CREATE_TIME"] )) {
		// $FORM_INFO ["CREATE_TIME"] = date ( "Y-m-d H:i:s" );
		// }
		$FORM_INFO ["MOD_TIME"] = date ( "Y-m-d H:i:s" );
		$this->setFrom ( $FORM_INFO );
		return ($this->Save ());
	}
	/**
	 * 应用平面图
	 */
	function appStatus($SeqID_APP, $SeqID) {
		$sql = "Begin";
		if (! empty ( $SeqID_APP )) {
			$sql = $sql . " update {$this->_table} set STATUS ='E' where {$this->_table_seq_name}='{$SeqID_APP}';";
		}
		$sql = $sql . " update {$this->_table} set STATUS ='A' where {$this->_table_seq_name}='{$SeqID}';";
		$sql = $sql . " END;";
		return ($this->DB ()->Execute ( $sql ));
	}
	
	/**
	 * 查询无需修改状态的楼层
	 */
	function SaveStatus($where, $status) {
		$sql = "select DRAW_MAP_ID from {$this->_table} ";
		$sql .= "where (" . $where . ") and STATUS <> '" . $status . "'";
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
	 * 修改状态
	 */
	function doStatus($SeqID, $TOSTATUS) {
		$sql = "update {$this->_table} set STATUS ='{$TOSTATUS}' where {$this->_table_seq_name}='{$SeqID}'";
		return ($this->DB ()->Execute ( $sql ));
	}
	/**
	 * 通过楼层ID获取平面图状态为正常的唯一标识ID
	 *
	 * @param number $FLOOR_ID
	 *        	楼层ID
	 * @return number 唯一标识ID
	 */
	function getNormalSeqIdByFloorId($FLOOR_ID) {
		return ($this->getSeqIdByFloorIdAndStatus ( $FLOOR_ID, "A" ));
	}
	/**
	 * 通过楼层ID获取平面图状态为编缉中的唯一标识ID
	 *
	 * @param number $FLOOR_ID
	 *        	楼层ID
	 * @return number 唯一标识ID
	 */
	function getEditableSeqIdByFloorId($FLOOR_ID) {
		return ($this->getSeqIdByFloorIdAndStatus ( $FLOOR_ID, "E" ));
	}
	/**
	 * 通过楼层ID和状态来取得第一个唯一标识ID
	 *
	 * @param unknown $FLOOR_ID        	
	 * @param unknown $STATUS        	
	 * @return number 唯一标识ID
	 */
	function getSeqIdByFloorIdAndStatus($FLOOR_ID, $STATUS) {
		$sql = "SELECT {$this->_table_seq_name} FROM {$this->_table}" . " WHERE FLOOR_ID={$FLOOR_ID} AND STATUS='{$STATUS}'";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result [0] [$this->_table_seq_name]);
		}
		return (0);
	}
}
?>