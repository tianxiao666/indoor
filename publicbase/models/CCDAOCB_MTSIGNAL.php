<?php 
/**
 *  理想AP表操作类
 *  @author chao.xj
 *  
 */
class CCDAOCB_MTSIGNAL extends CActiveRecord{
	var $_table = "CB_MT_SIGNAL_MEA_DATA";
	var $_bu_table = "CB_BUILDING";
	var $_fl_table = "CB_FLOOR";
	var $_dm_table = "DM_DRAW_MAP";
	var $_re_table = "SYS_REGION";
	var $_cb_mt_signal_sequence = "SEQ_MT_SIGNAL_ID";
	function __construct()
	{
		parent::__construct();
	}
}
?>