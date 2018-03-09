<?php
/**
 * POI��������
 * @author liang.jf
 */
class CDAOCB_POI extends CCDAOCB_POI {
	
	/**
	 * ���캯��
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * ��ȡ��ҳ��Ϣ
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
	 * ��ȡPOI��Ϣ
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
	 * ��ȡPOI��Ϣ
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
	 * �������POIλ���Ƿ��ѽ���POI
	 */
	function getTheAdd_INSTALL_LOCAT($LOCAT_X, $LOCAT_Y, $FLOOR_ID, $BUILDING_ID) {
		$sql = "SELECT  STATUS FROM {$this->_table} WHERE BUILDING_ID = '{$BUILDING_ID}' and FLOOR_ID = '{$FLOOR_ID}' and LOCAT_Y between {$LOCAT_Y}-5 and {$LOCAT_Y}+5 and LOCAT_X between {$LOCAT_X}-5 and {$LOCAT_X}+5";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * ����޸ĵ�POIλ���Ƿ��ѽ���POI
	 */
	function getThe_INSTALL_LOCAT($LOCAT_X, $LOCAT_Y, $FLOOR_ID, $BUILDING_ID, $POI_ID) {
		$sql = "SELECT  STATUS FROM {$this->_table} WHERE BUILDING_ID = '{$BUILDING_ID}' and FLOOR_ID = '{$FLOOR_ID}' and LOCAT_Y between {$LOCAT_Y}-5 and {$LOCAT_Y}+5 and LOCAT_X between {$LOCAT_X}-5 and {$LOCAT_X}+5 and POI_ID!={$POI_ID}";
		$result = $this->DB ()->getAll ( $sql );
		if ($result) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * ����POI��Ϣ
	 */
	function addPoi_list($PoiInfo) {
		$PoiInfo ['POI_ID'] = $this->DB ()->nextId ( $this->_cb_poi_sequence );
		$PoiInfo ["CREATE_TIME"] = date ( 'Y-m-d H:i:s' );
		$PoiInfo ["MOD_TIME"] = date ( 'Y-m-d H:i:s' );
		$this->setFrom ( $PoiInfo );
		if ($this->Insert ()) {
			return $PoiInfo;
		}
		return false;
	}
	
	/**
	 * ����POI_ID��ȡPOI��Ϣ
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
	 * �����޸�POI��Ϣ
	 */
	function updatePoiInfo($POI_LIST, $POI_ID) {
		$sql = "update {$this->_table} set ";
		foreach ( $POI_LIST as $k => $v ) {
			$sql = $sql . $k . "='" . $v . "',";
		}
		$POI_LIST ['MOD_TIME'] = date ( 'Y/m/d H:i:s' );
		$sql = $sql . "MOD_TIME='" . $POI_LIST ['MOD_TIME'] . "'";
		$sql .= " where POI_ID ={$POI_ID}";
		if ($this->DB ()->Execute ( $sql )) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * ��ѯ�����޸�״̬��POI
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
	 * �޸�POI״̬
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
}
?>