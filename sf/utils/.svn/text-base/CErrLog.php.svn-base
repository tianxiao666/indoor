<?php
/**
 * @author reaven
 * @created 2009-12-19
 * @desc 用于写严重的错误的日志
 */ 

class CErrLog{
	public static function log() {
		$logPath = SF_ROOT.'tmp/error.log';
		
		$strlog ="\n\n===================== ".date('Y-m-d H:i:s')." start log ====================================\n";
		$strlog .= "                      request from IP: ".$_SERVER['REMOTE_ADDR']."\n---------------------------------------------------------\n";
		$numargs = func_num_args();
		for($i=0;$i<$numargs;$i++){
			$strlog .= print_r(func_get_arg($i),TRUE);
			$strlog .= "\n\n";
		}
		$strlog .= "\n===================== ".date('Y-m-d H:i:s')." end log ======================================\n";
		
		$fp = fopen($logPath,"a+");
		@fwrite($fp,$strlog);
		@fclose($fp);
		unset($fp);
	}
}
?>