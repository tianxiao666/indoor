<?php
/**
 * ¥����ͼ������������
 * @author:mary 
 * @create_time:2013-07-31 
 */
class CDAODM_DRAW_MAP extends CCDAODM_DRAW_MAP {
	var $_table = 'DM_DRAW_MAP'; // associated database table
	/**
	 * ͨ��¥���ʶ�õ���¥�����е�������Ϣ
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
	 * ��ȡָ����ѯ�����£�ָ��ҳ��ţ�ָ��ҳ��size�������б�
	 *
	 * @param string $whereSql
	 *        	where��䣬ָ����ѯ����
	 * @param number $pageNum
	 *        	ҳ���
	 * @param number $pageSize
	 *        	ҳ��size
	 * @return array �����б� | boolean false��ʾ��ȡ����ʧ��
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
	 * ͨ����ʶID��ȡ¥��ID
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
	 * ͨ��¥��ID��ȡΨһ��ʶID
	 *
	 * @param number $FLOOR_ID
	 *        	¥��ID
	 * @return number Ψһ��ʶID
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
	 * ͨ��¥��ID��ȡƽ��ͼ��Ϣ
	 *
	 * @param number $FLOOR_ID
	 *        	¥��ID
	 * @return array ƽ��ͼ��Ϣ����
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
	 * ͨ��Ψһ��ʶID��ȡ�������е�����
	 *
	 * @param number $SeqId
	 *        	Ψһ��ʶID
	 * @return array Ψһ��ʶID�����е�����
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
	/**
	 * Ӧ��ƽ��ͼ
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
	 * ��ѯ�����޸�״̬��¥��
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
	 * �޸�״̬
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
	 * �޸�״̬
	 */
	function doStatus($SeqID, $TOSTATUS) {
		$sql = "update {$this->_table} set STATUS ='{$TOSTATUS}' where {$this->_table_seq_name}='{$SeqID}'";
		return ($this->DB ()->Execute ( $sql ));
	}
	/**
	 * ͨ��¥��ID��ȡƽ��ͼ״̬Ϊ������Ψһ��ʶID
	 *
	 * @param number $FLOOR_ID
	 *        	¥��ID
	 * @return number Ψһ��ʶID
	 */
	function getNormalSeqIdByFloorId($FLOOR_ID) {
		return ($this->getSeqIdByFloorIdAndStatus ( $FLOOR_ID, "A" ));
	}
	/**
	 * ͨ��¥��ID��ȡƽ��ͼ״̬Ϊ�༩�е�Ψһ��ʶID
	 *
	 * @param number $FLOOR_ID
	 *        	¥��ID
	 * @return number Ψһ��ʶID
	 */
	function getEditableSeqIdByFloorId($FLOOR_ID) {
		return ($this->getSeqIdByFloorIdAndStatus ( $FLOOR_ID, "E" ));
	}
	/**
	 * ͨ��¥��ID��״̬��ȡ�õ�һ��Ψһ��ʶID
	 *
	 * @param unknown $FLOOR_ID        	
	 * @param unknown $STATUS        	
	 * @return number Ψһ��ʶID
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