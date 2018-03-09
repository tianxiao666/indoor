<?php
/**
 * ��ͼͼ��Ԫ������
 * @author:xiang.zc 
 * @create_time:2013-10-10
 */
class CDAODM_LAYER_ELEMENT extends CCDAODM_LAYER_ELEMENT {
	var $_table = 'DM_LAYER_ELEMENT';
	/**
	 * ��ȡָ��ͨ��¥�������ͼ��Ԫ�������б�
	 *
	 * @param number $floor_id        	
	 * @return array ָ��ͨ��¥�������ͼ��Ԫ�������б�
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
	 * ��ȡָ������������ͼ��Ԫ�������б�
	 *
	 * @param string $where
	 *        	where���
	 * @return array ָ��ͨ��¥�������ͼ��Ԫ�������б�
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
	 * �޸�ָ��¥���STATUSΪX
	 *
	 * @param number $FLOOR_ID
	 *        	¥��ID
	 * @return boolean false��sqlִ��ʧ��
	 */
	function invalidByFloorId($FLOOR_ID) {
		$sql = "UPDATE {$this->_table}" . "SET STATUS='X' WHERE FLOOR_ID={$FLOOR_ID}";
		$result = $this->DB ()->Execute ( $sql );
		return ($result);
	}
	/**
	 * ��ȡ��һ��Ψһ��ʶID
	 */
	function getNextSeqId() {
		return ($this->DB ()->nextId ( $this->_table_seq ));
	}
	/**
	 * ִ���޸Ļ���������
	 *
	 * @param array $FORM_INFO
	 *        	���ݿ��ֶ���������
	 * @return boolean false��sqlִ��ʧ��
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
	 * ִ���޸Ļ���������
	 *
	 * @param array $FORM_INFO
	 *        	���ݿ��ֶ���������
	 * @return boolean false��sqlִ��ʧ��
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