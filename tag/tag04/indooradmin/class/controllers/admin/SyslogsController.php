<?php
/**
 * 
 * @author 
 */


class SyslogsController extends AdminController
{
	public $_page = 1;//当前页
	public $_count = 15;//每页个数
	
	public function __construct()
	{
		parent::__construct();
		$this->checkPermission('STAT');
	}
	
	function actionIndex()
	{
	    $condition = $this->checkRequestFormat('POST');
	    if(!$condition){
	    	$condition = $this->checkRequestFormat('GET');
	    }
		//$condition = $condition?$condition:TextFilter::formatRequest($_REQUEST);
		
		if(!$condition['startDate']){
			$condition['startDate'] = date('Y-m-d',strtotime(date('Y-m-d'))-60*60*24*1);//默认取前一天
		}
	    if(!$condition['endDate']){
			$condition['endDate']  = date('Y-m-d',strtotime(date('Y-m-d'))-60*60*24*1);
		}
		$condition['count'] && ($this->_count = $condition['count']);
		$condition['page'] && ($this->_page = $condition['page']);
		$Cdaosyslog = new CDAOWEB_SYS_LOG();
		$syslog_list = $Cdaosyslog->getPageData($this->_count,$this->_page, $condition);
		$total = $syslog_list->_maxRecordCount;
		$p = new page ($total);
		$p->baseUrl = 'ea.php?r=Syslogs';
		$condition['startDate'] && ($p->baseUrl .= '&startDate='.$condition['startDate']);
		$condition['endDate'] && ($p->baseUrl .= '&endDate='.$condition['endDate']);
		$condition['APP'] && ($p->baseUrl .= '&APP='.$condition['APP']);
		$condition['REFER'] && ($p->baseUrl .= '&REFER='.urlencode($condition['REFER']));
		$condition['GET_URL'] && ($p->baseUrl .= '&GET_URL='.urlencode($condition['GET_URL']));
		$condition['NAME'] && ($p->baseUrl .= '&NAME='.$condition['NAME']);
		$condition['LOG_IP'] && ($p->baseUrl .= '&LOG_IP='.$condition['LOG_IP']);
		$condition['ID'] && ($p->baseUrl .= '&ID='.$condition['ID']);
		$condition['LIKE'] && ($p->baseUrl .= '&LIKE='.$condition['LIKE']);
		$p->pagesize = $this->_count;
		$page = $p->generate();
		$log_list = $syslog_list->_array;
		if($log_list){
			foreach ($log_list as $key=>$l){
				$log_list[$key]['REFER']   = htmlspecialchars($l['REFER']);
				$log_list[$key]['GET_URL'] = htmlspecialchars($l['GET_URL']);
				$log_list[$key]['APP']     = strtoupper($l['APP']);
			}
		}
		
		if($condition['NAME'])  { $condition['NAME'] = htmlspecialchars($condition['NAME']);}
		if($condition['LOG_IP']) { $condition['LOG_IP'] = htmlspecialchars($condition['LOG_IP']);}
		if($condition['REFER'])  { $condition['REFER'] = htmlspecialchars($condition['REFER']);}
		if($condition['GET_URL']) { $condition['GET_URL'] = htmlspecialchars($condition['GET_URL']);}

		$pageData['syslog_list'] = $log_list;
		$pageData['page'] = $page;
		$pageData['condition']  = $condition;
		$this->render('syslogs_list',$pageData);
	}
	
    //导出excel
	public function actionExport() {
		$Cdaosyslog = new CDAOWEB_SYS_LOG();
	    $condition = $this->checkRequestFormat('POST');
	    if(!$condition){
	    	$condition = $this->checkRequestFormat('GET');
	    }
		if(!$condition['startDate']){
			$condition['startDate'] = date('Y-m-d',strtotime(date('Y-m-d'))-60*60*24*1);//默认取前一天
		}
	    if(!$condition['endDate']){
			$condition['endDate']  = date('Y-m-d',strtotime(date('Y-m-d'))-60*60*24*1);
		}
		$syslog_list = $Cdaosyslog->getPageData($this->_count,$this->_page, $condition);
		$syslog_list = $syslog_list->_array;
		
		$csv .= '"应用台平",';
		$csv .= '"用户ID",';
		$csv .= '"用户名",';
		$csv .= '"IP",';
		$csv .= '"访问URL",';
		$csv .= '"来源URL",';
		$csv .= '"访问时间",';
		$csv .= "\n";
		if (! $syslog_list)
			return false;
		
		foreach ( $syslog_list as $aModel ) {
			$csv .= '"' . $aModel ['APP'] . "\t" . '",';
			$csv .= '"' . $aModel ['SUBS_ID'] . "\t" . '",';
			$csv .= '"' . $aModel ['NAME'] . "\t" . '",';
			$csv .= '"' . $aModel ['LOG_IP'] . "\t" . '",';
			$csv .= '"' . $aModel ['GET_URL'] . "\t" . '",';
			$csv .= '"' . $aModel ['REFER'] . "\t" . '",';
			$csv .= '"' . $aModel ['CREATE_TIME'] . "\t" . '",';
			$csv .= "\n";
		}
		$fileName = "日志详情" . date ( 'YmdHis' ) . ".csv";
		header ( "Content-type: text/html; charset=gb2312" );
		header ( "content-type:text/comma-separated-values;filename={$fileName}" );
		header ( "content-disposition:attachment;filename={$fileName}" );
		echo $csv;
	}
	
}
?>