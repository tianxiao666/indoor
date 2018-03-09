<?php
/**
 * ƽ��ͼͼ�������
 * @author:xiang.zc 
 * @create_time:2013-10-10
 */
class CDAODM_LAYER_POINT extends CCDAODM_LAYER_POINT {
	var $_table = 'DM_LAYER_POINT'; // associated database table
	/*
	 * @author:mary @create_time:2013-07-31 ͨ��ͼ����Ϣȡ����Ӧ�������
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
	 * ��ȡָ������������ƽ��ͼͼ�������б�
	 *
	 * @param string $where
	 *        	where���
	 * @return array ָ��ͨ��¥�������ƽ��ͼͼ�������б�
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
