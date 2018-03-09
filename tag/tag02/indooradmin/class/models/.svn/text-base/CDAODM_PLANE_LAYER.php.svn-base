<?php
/**
 * 制图图层属性
 * @author:xiang.zc
 * @create_time:2013-10-10
 */
class CDAODM_PLANE_LAYER extends CCDAODM_PLANE_LAYER {
	var $_table = 'DM_PLANE_LAYER'; // associated database table
	/*
	 * @author:mary @create_time:2013-07-31 通过楼层标识得到该楼层所有的数据信息
	 */
	function getPlaneLayerListByFloorId($floor_id) {
		$floor_id = $floor_id ? intval ( $floor_id ) : 0;
		if ($floor_id > 0) {
			$sql = "select * from {$this->_table} where floor_id = {$floor_id} order by layer_id";
			return $this->DB ()->getAll ( $sql );
		}
		
		return empty ( $result ) ? array () : $result;
	}
	/**
	 * 获取指定条件的所有制图图层属性列表
	 *
	 * @param string $where
	 *        	where语句
	 * @return array 指定通过楼层的所有制图图层属性列表
	 */
	function getList($where) {
		$sql = "SELECT	* FROM {$this->_table}" . " WHERE {$where}";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result);
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
	 * 查询相应的图层id
	 * @param string $whereSql
	 * @param number $pageNum
	 * @param number $pageSize
	 * @return unknown
	 */
	function sel_layer_id($whereSql) {
		$sql = "SELECT LAYER_ID,LAYER_TOPIC,LAYER_TYPE FROM {$this->_table}";
		if (! empty ( $whereSql )) {
			$sql = $sql . " WHERE " . $whereSql;
		}
		$result = $this->DB ()->getAll ( $sql );
		return ($result);
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
		// s
		// }
		$FORM_INFO ["MOD_TIME"] = date ( "Y-m-d H:i:s" );
		$this->setFrom ( $FORM_INFO );
		return ($this->Save ());
	}
}
?>