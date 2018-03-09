<?php
/**
 * 平面图图层坐标表
 * @author:xiang.zc 
 * @create_time:2013-10-10
 */
class CDAODM_LAYER_POINT extends CCDAODM_LAYER_POINT {
	var $_table = 'DM_LAYER_POINT'; // associated database table
	/*
	 * @author:mary @create_time:2013-07-31 通过图层信息取得相应的坐标点
	 */
	function getLayerPointByLayerId($layer_id) {
		$layer_id = $layer_id ? intval ( $layer_id ) : 0;
		if ($layer_id > 0) {
			$sql = "select * from {$this->_table} where layer_id = {$layer_id} order by L_ORDER";
			return $this->DB ()->getAll ( $sql );
		}
		return empty ( $result ) ? array () : $result;
	}
	/**
	 * 获取指定条件的所有平面图图层坐标列表
	 *
	 * @param string $where
	 *        	where语句
	 * @return array 指定通过楼层的所有平面图图层坐标列表
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
}
?>
