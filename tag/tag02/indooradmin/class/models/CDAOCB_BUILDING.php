<?php
/**
 * 建筑物场所表操作类
 * @author panyf 2011.11.2
 */
class CDAOCB_BUILDING extends CCDAOCB_BUILDING {
	function __construct() {
		parent::__construct ();
	}
	static function stripForm($form) {
		foreach ( $form as $k => $v ) {
			if (is_array ( $v )) {
			} else {
				$form [$k] = trim ( TextFilter::filterAllHTML ( $v ) );
			}
		}
		return ($form);
	}
	function listPageData($whereSql = '', $pageNum = 1, $pageSize = 10) {
		$sql = "SELECT 
			* FROM {$this->_table}";
		if (! empty ( $whereSql )) {
			$sql = $sql . " WHERE " . $whereSql;
		}
		$result = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		return ($result);
	}
	function infoBuilding($buildingID) {
		if (! empty ( $buildingID )) {
			$sql = "SELECT * FROM {$this->_table} WHERE BUILDING_ID = '{$buildingID}' ";
			$result = $this->DB ()->Execute ( $sql );
			if ($result->_array) {
				return ($result->_array [0]);
			}
		}
		return (array ());
	}
	function statusBuilding($buildingID, $tostatus) {
		$sql = "update {$this->_table} set STATUS ='{$tostatus}' where BUILDING_ID ='{$buildingID}'";
		return ($this->DB ()->Execute ( $sql ));
	}
	/**
	 * 获取唯一标识ID的内容
	 *
	 * @param unknown $SEQ_ID        	
	 * @return NULL
	 */
	function getSeqNameBySeqId($SEQ_ID) {
		$sql = "SELECT {$this->_table_seq_name_name} FROM {$this->_table} WHERE {$this->_table_seq_name}={$SEQ_ID}";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result [0] [$this->_table_seq_name_name]);
		}
		return (null);
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
	function getAllBuilding() {
		$sql = "SELECT
		distinct BUILDING_ID,
		BUILDING_NAME FROM {$this->_table}";
		$result = $this->DB ()->getAll ( $sql );
		$buildingList = array ();
		foreach ( $result as $val ) {
			$buildingList [$val ['BUILDING_ID']] = $val ['BUILDING_NAME'];
		}
		return ($buildingList);
	}
	function getNextSeqId() {
		return ($this->DB ()->nextId ( $this->_table_seq ));
	}
	function doEdit($FORM_INFO) {
		$SEQ_ID = $FORM_INFO ["{$this->_table_seq_name}"];
		if (empty ( $SEQ_ID )) {
			$SEQ_ID = $this->getNextSeqId ();
			$FORM_INFO ["{$this->_table_seq_name}"] = $SEQ_ID;
			$FORM_INFO ["CREATE_TIME"] = date ( "Y-m-d H:i:s" );
		}
		return ($this->doSave ( $FORM_INFO ));
	}
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
	 * 查询无需修改状态的楼层
	 */
	function SaveStatus($where, $status) {
		$sql = "select BUILDING_ID from {$this->_table} ";
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
	function getInfoByWhere($where) {
		$sql = "SELECT	* FROM {$this->_table}" . " WHERE " . $where;
		$result = $this->DB ()->getAll ( $sql );
		if ($this->DB ()->Execute ( $sql )) {
			return $result;
		} else {
			return FALSE;
		}
	}
}
?>