<?php
/**
 *  ͼƬ��Ϣ
 *  ͼƬ����Ϊƽ��ͼ����ͼ��POI��Ƭ;��������Ƭ,¥����Ƭ�ȼ��֡�
 *  @author xiang.zc
 *  @CreatedTime 2013-12-05
 */
class CCDAOCB_PIC extends CActiveRecord {
	var $_table = 'CB_PIC';
	var $_table_seq = "SEQ_PIC_ID";
	var $_table_seq_name = "PIC_ID";
	function __construct() {
		parent::__construct ();
	}
}
?>