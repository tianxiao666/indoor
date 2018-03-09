<?php
/**
 *  图片信息
 *  图片类型为平面图缩略图，POI照片;建筑物照片,楼层照片等几种。
 *  @author xiang.zc
 *  @CreatedTime 2013-12-05
 */
class CDAOCB_PIC extends CCDAOCB_PIC {
	var $_table = "CB_PIC";
	var $FILE_PNG = "PNG";
	var $FILE_SVG = "SVG";
	var $FILE_THM = "THM";
	var $FILE_THM_SIZE = 100;
	public static function getPicMimeType($file) {
		$MIME_TYPE = null;
		try {
			$finfo = finfo_open ( FILEINFO_MIME_TYPE );
			$MIME_TYPE = finfo_file ( $finfo, $file );
			finfo_close ( $finfo );
		} catch ( Exception $e ) {
		}
		return ($MIME_TYPE);
	}
	/**
	 * 获取下一个唯一标识ID
	 */
	function getNextSeqId() {
		return ($this->DB ()->nextId ( $this->_table_seq ));
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
	function isPicPathExist($PATH) {
		$sql = "SELECT distinct PATH FROM {$this->_table}" . " WHERE PATH = '{$PATH}'";
		$result = $this->DB ()->getAll ( $sql );
		return (! empty ( $result ));
	}
	/**
	 * 根据楼层ID，获取楼层平面图存储路径
	 *
	 * @param unknown $FLOOR_ID        	
	 * @return Ambigous <NULL, unknown>
	 */
	function getPicPathByWhere($Where) {
		$sql = "SELECT distinct PATH FROM {$this->_table}" . " WHERE ({$Where}) AND PATH is not null";
		$result = $this->DB ()->getAll ( $sql );
		$PATH = null;
		foreach ( $result as $val ) {
			if (! empty ( $val ["PATH"] )) {
				$PATH = $val ["PATH"];
			}
		}
		return ($PATH);
	}
	/**
	 * 取得唯一标识
	 *
	 * @param unknown $DRAW_MAP_ID        	
	 * @param unknown $STATUS        	
	 * @return NULL
	 */
	function getSeqIdByWhere($Where) {
		$sql = "SELECT {$this->_table_seq_name} FROM {$this->_table}" . " WHERE {$Where}";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result [0] [$this->_table_seq_name]);
		}
		return (null);
	}
	/**
	 * 获取PIC中如何判断PIC图片为场所，楼层，平面图，POI的where 语句
	 *
	 * @param unknown $ID_TYPE        	
	 * @return string
	 */
	public function getPicClassSqlWhere($ID_TYPE) {
		$ID_TYPE_LIST = array (
				"POI_ID",
				"DRAW_MAP_ID",
				"FLOOR_ID",
				"BUILDING_ID" 
		);
		$where = "";
		foreach ( $ID_TYPE_LIST as $type ) {
			if ($ID_TYPE == $type) {
				break;
			} else {
				$where = $where . " AND ({$type} is null)";
			}
		}
		return ($where);
	}
	/**
	 * 获取png,svg,thm源码路径
	 *
	 * @param unknown $DRAW_MAP_ID        	
	 * @param unknown $MEDIA_TYPE        	
	 * @return multitype:
	 */
	function getSvgPicPathListByWhere($where = "1=0") {
		$index = "DRAW_MAP_ID";
		$sql = "SELECT {$index} ,PIC_TYPE,PATH,FILENAME FROM {$this->_table} WHERE {$where}";
		$result = $this->DB ()->getAll ( $sql );
		$MediaPathList = array ();
		foreach ( $result as $k => $v ) {
			$MEDIA_TYPE = $v ["PIC_TYPE"];
			if ($MEDIA_TYPE == $this->FILE_THM) {
				$MEDIA_TYPE = $this->FILE_PNG;
			}
			$MediaPathList [$v [$index]] = "medialib/" . $v ["PATH"] . "/" . $v ["FILENAME"] . "." . $MEDIA_TYPE;
		}
		return ($MediaPathList);
	}
	/**
	 * 查询条件
	 *
	 * @param unknown $selection        	
	 * @param unknown $where        	
	 */
	function getAllWhere($selection, $where) {
		$sql = "SELECT {$selection} FROM {$this->_table} WHERE " . $where;
		return ($this->DB ()->getAll ( $sql ));
	}
}
?>