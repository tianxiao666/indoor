<?php
/**
 *  制图图层元素属性
 *  PATH类型的值会非常大。
 *  @author xiang.zc
 *  @CreatedTime 2013-12-05
 */
class CDAODM_LAYER_ELEMENT_ATTR extends CCDAODM_LAYER_ELEMENT_ATTR {
	var $_table = 'DM_LAYER_ELEMENT_ATTR';
	function __construct() {
		parent::__construct ();
	}
	/**
	 * 获取指定条件的所有图层元素属性列表
	 *
	 * @param string $where
	 *        	where语句
	 * @return array 指定通过楼层的所有图层元素属性列表
	 */
	function getList($where) {
		$sql = "SELECT	* FROM {$this->_table}" . " WHERE {$where}";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return ($result);
		}
		return (array ());
	}
}
?>