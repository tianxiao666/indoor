<?php
/**
 * 日志查询
 * 
 * @author 
 */


class LogMgrController extends AdminController
{
	const PAGE_SIZE= 25;
	public function __construct()
	{
		parent::__construct();
		$this->checkPermission('NOT');
	}
	
  
    function actionIndex()
    {
		$condition = CUserSession::getSessionValue('log_search_condition');
		$condDanger = TextFilter::filterDangerWords($condition);		
        //分页显示日志列表
        $DAOLog 		= new CDAOLog();
        $p 				= new page($DAOLog->getLogCount($this->user, $condDanger));
        $p->baseUrl 	= 'ea.php?r=LogMgr/index';
        $p->pagesize 	= self::PAGE_SIZE;
        $page 			= $p->generate();
        
        $CDict 						= new CDict();
        $pageData['sys_log_type'] 	= $CDict->SYS_LOG_TYPE;
        
        $sys_log = $DAOLog->getPageLog($this->user, $condDanger, $p->pagesize, $page['page']);
       	if ($sys_log)
         {
	          foreach ($sys_log as $key => $log)
	         {
	          	 $sys_log[$key]['LOG_TYPE'] = $CDict->SYS_LOG_TYPE[$log['LOG_TYPE']];
	          }
         }
         
        $pageData['condition']	= $condition;
	   	$pageData['sys_log']	= $sys_log;
	   	$pageData['page'] 		= $page;	
	 	$pageData['adminRole'] 	= $this->user['ROLE'];
	    
	    $this->render("logmgr_index", $pageData);	
    }

    
   	function actionSearchLog()
   	{
   		$condition = $this->checkRequestFormat();
   		CUserSession::setSessionValue('log_search_condition', $condition);			
    	$this->jsCall("parent.location.href = 'ea.php?r=LogMgr';");	
    }



    //导出日志方法
    function actionExportLog()
    {
    	$condition	= $this->checkRequestFormat();
      	$DAOLog 	= new CDAOLog();
		$cdaosubs   = new CDAOCS_SUBS();
		$cdict      = new CDict();
		//取得要导出的数据的总行数
		$rows =  $DAOLog->getLogCount($this->user, $condition);
		if (empty($rows))
		{
			$this->jsAlert('找不到相关记录！');
			return;	
		}
		
		//随机取得文件名
	    $strRank  = date("YmdHis");
	    $strRank .= rand(1000,10000);
       	$csvfile  = SF_ROOT.'tmp/'.$strRank.'.csv';

       	$limitnum 	= 6000;
       	$times		= $this->getExportFileCount($rows, $limitnum);
	    $index 		= 0;
	    while($index < $times)
		{
			$sys_log 	= $DAOLog->exportLog($this->user, $condition, $limitnum , $index+1);
			
			$csv = '"用户名","IP地址","日志类型","日志内容","记录时间"'. "\n";
		
			foreach ($sys_log as $log)
			{
				$subsname = $cdaosubs->getUserById($log['SUBS_ID']);
				foreach ($subsname as $subsnameKey)
				{
					$csv .= '"' . $subsnameKey['NAME'] . '",';
				}
				$csv .= '"' . $log['LOG_IP'] . '",';
				$csv .= '"' . $cdict->SYS_LOG_TYPE[$log['LOG_TYPE']] . '",';
				$csv .= '"' . $log['LOG_TEXT'] . '",';
				$csv .= '"' . $log['CREATE_TIME'] . '"' . "\n";
			}
			
			$fcsv = fopen($csvfile,"a+");
			fwrite($fcsv,$csv);
			fclose($fcsv);
			$index++;					
		}
		
    	$filename = 'SYS_LOG_' . date('YmdHis') . '.csv';
       	$this->writeAdminLog('LOG', 'SYS', "管理员 {$this->user['NAME']} 导出日志");
      	header("content-type:text/comma-separated-values;filename={$filename}");
     	header("content-disposition:attachment;filename={$filename}");

		readfile($csvfile);		//读取文件		
		unlink($csvfile); //删除文件
   	}
}
?>