<?php
/**
 * 
 *  @author xiang.zc
 *  @CreatedTime 2013-10-17
 */
class CCDAOCB_PLANE_MEDIA extends CActiveRecord {
	var $_table = "CB_PLANE_MEDIA";
	var $_table_seq = "SEQ_MEDIA_ID";
	var $_table_seq_name = "MEDIA_ID";
	function __construct() {
		parent::__construct ();
	}
}
?>