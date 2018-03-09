<?php
/**
 * @author tangqian
 */

// class CDAOSYS_TYPE_CODE extends CActiveRecord
class CDAOSYS_TYPE_CODE extends CCDAOSYS_TYPE_CODE {
	var $_table = "SYS_TYPE_CODE";
	// 构造函数
	function __construct() {
		parent::__construct ();
	}
	function getCodePageData($pageSize = 10, $pageNum = 1) {
		$sql = "SELECT * FROM $this->_table ORDER BY CREATE_TIME DESC";
		$result = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		return $result->_array;
	}
	function getCodeCount() {
		$sql = "SELECT COUNT(*) AS COUNTS FROM $this->_table";
		$result = $this->DB ()->Execute ( $sql );
		return $result->_array [0] ['COUNTS'];
	}
	/**
	 */
	function addCode($modelData) {
		$modelData ['CREATE_TIME'] = date ( 'Y-m-d H:i:s' );
		$this->setFrom ( $modelData );
		if ($this->Save ()) {
			return true;
		}
		return false;
	}
	function editCode($modelData) {
		$code_type = TextFilter::encodeQuote ( $modelData ['CODE_TYPE'] );
		if ($code_type && $this->Load ( " CODE_TYPE = '$code_type' " )) {
			unset ( $modelData ['CODE_TYPE'] );
			$this->setFrom ( $modelData );
			if ($this->Save ()) {
				return true;
			}
		}
		return false;
	}
	function codeExists($code_type) {
		$code_type = TextFilter::encodeQuote ( $code_type );
		if ($code_type && $this->Load ( " CODE_TYPE = '$code_type' " )) {
			return $this->toArray ();
		}
		return false;
	}
	function deleteCode($code_type) {
		$code_type = TextFilter::encodeQuote ( $code_type );
		if ($code_type && $this->Load ( " CODE_TYPE = '$code_type' " )) {
			return $this->Delete ();
		}
		return false;
	}
	// 景区管理查询所有景区分类
	function findAllType() {
		$sql = "SELECT * FROM $this->_table ";
		$result = $this->DB ()->getAll ( $sql );
		$parentVal = array ();
		foreach ( $result as $val ) {
			$parentVal [$val ['CODE_TYPE']] = $val ['CODE_NAME'];
		}
		return $parentVal;
	}
	function getAllType() {
		$sql = "SELECT CODE_TYPE,CODE_NAME FROM $this->_table ";
		$result = $this->DB ()->getAll ( $sql );
		return ($result);
	}
}
?>