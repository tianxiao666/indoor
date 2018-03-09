<?php
/**
 * 楼层媒体文件表
 * @author:xiang.zc 
 * @CreatedTime 2013-10-17
 */
class CDAOCB_PLANE_MEDIA extends CCDAOCB_PLANE_MEDIA {
	var $_table = "CB_PLANE_MEDIA";
	var $SVG = "SVG";
	var $FILE_PNG = "PNG";
	var $FILE_SVG = "SVG";
	var $FILE_THM = "THM";
	var $FILE_THM_SIZE = 100;
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
	/**
	 * 判断楼层平面图存储路径是否存在
	 *
	 * @param unknown $PATH_FILE        	
	 * @return boolean
	 */
	function isMediaPathExist($PATH_FILE) {
		$sql = "SELECT distinct PATH_FILE FROM {$this->_table}" . " WHERE PATH_FILE = '{$PATH_FILE}'";
		$result = $this->DB ()->getAll ( $sql );
		return (! empty ( $result ));
	}
	/**
	 * 根据楼层ID，获取楼层平面图存储路径
	 *
	 * @param unknown $FLOOR_ID        	
	 * @return Ambigous <NULL, unknown>
	 */
	function getMediaPathByFloor($FLOOR_ID) {
		$sql = "SELECT distinct PATH_FILE FROM {$this->_table}" . " WHERE FLOOR_ID = {$FLOOR_ID} AND PATH_FILE is not null";
		$result = $this->DB ()->getAll ( $sql );
		$PATH_FILE = null;
		foreach ( $result as $val ) {
			if (! empty ( $val ["PATH_FILE"] )) {
				$PATH_FILE = $val ["PATH_FILE"];
			}
		}
		return ($PATH_FILE);
	}
	/**
	 * 取得唯一标识
	 *
	 * @param unknown $DRAW_MAP_ID        	
	 * @param unknown $STATUS        	
	 * @return NULL
	 */
	function getSeqIdByType($DRAW_MAP_ID, $MEDIA_TYPE) {
		$sql = "SELECT {$this->_table_seq_name} FROM {$this->_table}" . " WHERE DRAW_MAP_ID='{$DRAW_MAP_ID}' AND MEDIA_TYPE='{$MEDIA_TYPE}'";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result [0] [$this->_table_seq_name]);
		}
		return (null);
	}
	/**
	 * 获取png,svg,thm源码路径
	 *
	 * @param unknown $DRAW_MAP_ID        	
	 * @param unknown $MEDIA_TYPE        	
	 * @return multitype:
	 */
	function getMediaPathByWhere($where) {
		$sql = "SELECT DRAW_MAP_ID,MEDIA_TYPE,PATH_FILE,FILENAME FROM {$this->_table}  WHERE {$where}";
		$result = $this->DB ()->getAll ( $sql );
		$MediaPathList = array ();
		foreach ( $result as $k => $v ) {
			$MEDIA_TYPE = $v ["MEDIA_TYPE"];
			if ($MEDIA_TYPE == $this->FILE_THM) {
				$MEDIA_TYPE = $this->FILE_PNG;
			}
			$MediaPathList [$v ["DRAW_MAP_ID"]] = "medialib/" . $this->SVG . "/" . $v ["MEDIA_TYPE"] . "/" . $v ["PATH_FILE"] . "/" . $v ["FILENAME"] . "." . $MEDIA_TYPE;
			// $MediaPathList [$v ["DRAW_MAP_ID"]] = urlencode( $MediaPathList [$v ["DRAW_MAP_ID"]]);
		}
		return ($MediaPathList);
	}
}
?>
