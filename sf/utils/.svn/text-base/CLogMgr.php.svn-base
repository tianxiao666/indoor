<?php
/**
 * 
 *
 */
class CLogMgr {
	//写入日志信息到文件。
	public function toLogFile($subs_id, $log_type, $log_pri, $op, $app,$startTime=0, $log_text = 0) {
		if ($subs_id && $log_type && $log_pri) {
			$textfilter = new TextFilter ( );
			$DAOCS_SUBS = new CCDAOCS_SUBS ( );
			$subs_info = $DAOCS_SUBS->getInfo ( 'SUBS_ID=' . $subs_id );
			$startTime = $startTime?$startTime:$this->getCurrentTime ();
			$aSysLog ['SUBS_ID'] = $subs_id;
			$aSysLog ['LOG_TYPE'] = $log_type;
			$aSysLog ['LOG_PRI'] = $log_pri;
			$aSysLog ['OP'] = $op;
			
			$aSysLog ['VERSION'] = '';
			$aSysLog ['OS'] = $this->get_os();
			$aSysLog ['OSVER'] = '';
			$aSysLog ['FUNC'] = '';
			
			$aSysLog ['COST'] = $this->getCurrentTime () - $startTime;
			$aSysLog ['RESULT'] = '';
			$aSysLog ['LOG_IP'] = $_SERVER ['REMOTE_ADDR'];
			
			$aSysLog ['REFER'] = $_SERVER ['HTTP_REFERER'];
			$arr_refer = preg_split ( "/\//", $aSysLog ['REFER'] );
			$aSysLog ['REFER_SITE'] = $arr_refer [2];
			$aSysLog ['GET_URL'] = 'http://'.$_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
			$aSysLog ['CREATE_TIME'] = date ( 'Y-m-d H:i:s' );
			$aSysLog ['INSTALL_ID'] = '';
			$aSysLog ['CLI_TYPE'] = CGetAgent::GetAgent ();
			
			if (! $log_text)
				$log_text = $subs_info ['NAME'] . '访问了' . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
			$aSysLog ['LOG_TEXT'] = $log_text;
			
			$aSysLog ['APP'] = strtoupper($app);
			//缓存  
			if (file_put_contents ( LOG_PATH .date ( 'Ymd' ).".access", implode (" | ", $aSysLog ) . "\n", FILE_APPEND )) //写入缓存 
				return true;
			else
				return false;
		} else
			return false;
	}
	
	//保存日志缓存文件中的日志信息到数据库
	public static function readLogFile() {
		if( $_SERVER['argv'][1])
			$file = LOG_PATH."LOG_".$_SERVER['argv'][1].".log";
		else{
			$day = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
			$file = LOG_PATH."LOG_".date ( 'Ymd' ,$day).".log";
		}
		echo $file."\n";
		$DAOSYS_LOG = new CCDAOSYS_LOG ( );
		if (is_file ( $file )) {
			$content = explode ( '//,//', file_get_contents ( $file ) );
			$array = array ();
			$backArry = array ();
			if ($content) {
				foreach ( $content as $key => $val ) {
					if ($val)
						$array [$key] = unserialize ( $val );
				}
				if (is_array ( $array )) {
					foreach ( $array as $key => $val ) {
						$backArry [$key] = $DAOSYS_LOG->doInsert ( $val, 'SEQ_SYS_LOG_LOG_ID' );
					}
					$cnt = count ( $backArry );
					echo "插入{$cnt}条记录！\n";
				}
//				if (count ( $backArry )) //清空缓存 
//					file_put_contents ( $file, '' );
			}
		} else
			return false;
	}
	
	/**
	 * 获得客户端的操作系统
	 *
	 * @access   private
	 * @return   void
	 */
	protected function get_os() {
		$agent = $_SERVER ['HTTP_USER_AGENT'];
		$os = false;
		if (preg_match ( '/win/i', $agent ) && strpos ( $agent, '95' )) {
			$os = 'Windows 95';
		} else if (preg_match ( '/win 9x/i', $agent ) && strpos ( $agent, '4.90' )) {
			$os = 'Windows ME';
		} else if (preg_match ( '/win/i', $agent ) && preg_match ( '/98/i', $agent )) {
			$os = 'Windows 98';
		} else if (preg_match ( '/win/i', $agent ) && preg_match ( '/nt 5.1/i', $agent )) {
			$os = 'Windows XP';
		} else if (preg_match ( '/win/i', $agent ) && preg_match ( '/nt 5/i', $agent )) {
			$os = 'Windows 2000';
		} else if (preg_match ( '/win/i', $agent ) && preg_match ( '/nt/i', $agent )) {
			$os = 'Windows NT';
		} else if (preg_match ( '/win/i', $agent ) && preg_match ( '/32/i', $agent )) {
			$os = 'Windows 32';
		} else if (preg_match ( '/linux/i', $agent )) {
			$os = 'Linux';
		} else if (preg_match ( '/unix/i', $agent )) {
			$os = 'Unix';
		} else if (preg_match ( '/sun/i', $agent ) && preg_match ( '/os/i', $agent )) {
			$os = 'SunOS';
		} else if (preg_match ( '/ibm/i', $agent ) && preg_match ( '/os/i', $agent )) {
			$os = 'IBM OS/2';
		} else if (preg_match ( '/Mac/i', $agent ) && preg_match ( '/PC/i', $agent )) {
			$os = 'Macintosh';
		} else if (preg_match ( '/PowerPC/i', $agent )) {
			$os = 'PowerPC';
		} else if (preg_match ( '/AIX/i', $agent )) {
			$os = 'AIX';
		} else if (preg_match ( '/HPUX/i', $agent )) {
			$os = 'HPUX';
		} else if (preg_match ( '/NetBSD/i', $agent )) {
			$os = 'NetBSD';
		} else if (preg_match ( '/BSD/i', $agent )) {
			$os = 'BSD';
		} else if (preg_match ( '/OSF1/i', $agent )) {
			$os = 'OSF1';
		} else if (preg_match ( '/IRIX/i', $agent )) {
			$os = 'IRIX';
		} else if (preg_match ( '/FreeBSD/i', $agent )) {
			$os = 'FreeBSD';
		} else if (preg_match ( '/teleport/i', $agent )) {
			$os = 'teleport';
		} else if (preg_match ( '/flashget/i', $agent )) {
			$os = 'flashget';
		} else if (preg_match ( '/webzip/i', $agent )) {
			$os = 'webzip';
		} else if (preg_match ( '/offline/i', $agent )) {
			$os = 'offline';
		} else {
			$os = 'Unknown';
		}
		return $os;
	}
	
	protected function getCurrentTime() {
		list ( $msec, $sec ) = explode ( ' ', microtime () );
		return ( float ) $msec + ( float ) $sec;
	}

}
