<?php
/**
 * @author samlinye <samlinye@hotmail.com>
 */


class CDAOLog extends CCDAOSYS_LOG 
{
	var $_table = "SYS_LOG";
	var $_tabel_seqence = "SEQ_SYS_LOG_LOG_ID";
	var $_cs_subs = "CS_SUBS";
	
    //构造函数
    function __construct()
    {
        parent::__construct();
    }
    
    function getLogCount($admin, $conditon = array())
	{
    	$sql 	= "SELECT COUNT(*) AS COUNTS FROM $this->_table T1 , SYS_SUBS T2  " . $this->genWhereSql($admin, $conditon);
    	$result = $this->DB()->Execute($sql);
    	
    	return $result->_array[0]['COUNTS'];
  	}
   	 
    private function genWhereSql($admin, $condition = array())
    {
    	$whereSql = " WHERE T1.SUBS_ID = T2.SYS_SUBS_ID and T1.LOG_PRI = 'SYS' ";
    	// user name
    	if (in_array('ADMIN',$admin['ROLE']))
	   	{
		   	if($condition['log_user'] )
	        {
	        	$whereSql .= "  and T2.name like '%{$condition['log_user']}%'";
	        }
	   	}
	  	else
	  	{
	      	$whereSql .= " AND T1.SUBS_ID = '{$admin['SYS_SUBS_ID']}'";
	   	}
	   	        
        // log type
        if($condition['log_type'])
        {
        	 $whereSql .= " and T1.log_type  ='{$condition['log_type']}'";
        }
        
        // start date
    	if ($condition['startDate'])
    	{
	    	 $whereSql .= " and T1.CREATE_TIME >= TO_DATE('{$condition['startDate']}','YYYY-MM-DD') ";
    	}
    	
    	// end date
    	if ($condition['endDate'])
    	{
    		$condition['endDate'] = date('Y-m-d', strtotime('+1 day', strtotime($condition['endDate'])));
    		$whereSql .= "  and T1.CREATE_TIME < TO_DATE('{$condition['endDate']}','YYYY-MM-DD') ";
    	}
    	
    	return  $whereSql;
    }
    
    
    function getPageLog($admin, $condition = Array(), $pageSize = 10, $pageNum = 1)
    {
	    $sql 	 = "SELECT T2.NAME,T1.LOG_IP,T1.LOG_TYPE,T1.LOG_TEXT,T1.CREATE_TIME FROM $this->_table  T1 , SYS_SUBS  T2";
	    $sql 	.= $this->genWhereSql($admin, $condition);
	    $sql	.= "  ORDER BY T1.CREATE_TIME DESC";
	    $dbLog 	 = $this->DB()->PageExecute($sql, $pageSize, $pageNum);

        return $dbLog->_array;
  	}
  	
  	
    function exportLog($admin, $condition = Array(), $pageSize = 10, $pageNum = 1)
    {
	    $sql 	 = "SELECT T1.* FROM $this->_table T1, SYS_SUBS T2 ";
	    $sql 	.= $this->genWhereSql($admin, $condition);
	    $sql	.= "  ORDER BY T1.CREATE_TIME DESC";
	    $dbLog 	 = $this->DB()->PageExecute($sql, $pageSize, $pageNum);

        return $dbLog->_array;
  	}
  	
  	function getSubPwdLog($subsId,$create_time) {
  		$sql = "select count(*) as cnt from $this->_table where log_type='PWD' and create_time >'$create_time' and subs_id='$subsId'";
     	$result=$this->DB()->GetOne($sql);
     	return $result;
  	}
    
    function getLogInfoById($blog_id)
    {
    	if ($this->Load(" BLOG_ID = '$blog_id'"))
    	{
    		return $this->toArray();
    	}
    	return false;
    }
    

//方法
    
	function addAdminSysLog($syslog)
   	{
   		$syslog['LOG_ID'] = $this->DB()->nextId($this->_tabel_seqence);
        $this->setFrom($syslog);
        if ($this->Save())
        {
        	return $syslog['LOG_ID'];
        }
        
        return FALSE;
    }
    
    
   function SysLogInsert($syslog, $cost, $define_pri)
   {
        $log['COST'] = $cost;

        $params 	= CController::getParams();
        $log['OP'] 	= $params['mod'] . '->' . $params['act'];
        
        $log['LOG_IP'] 	= $_SERVER['REMOTE_ADDR'];
        $log['GET_URL'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $log['REFER'] 	= $_SERVER['HTTP_REFERER'];
        
        $arr_refer 			= preg_split ("/\//", $log['REFER']);
        $log['REFER_SITE'] 	= $arr_refer[2];
        $log['USER_AGENT'] 	= $_SERVER['HTTP_USER_AGENT'];
        $log['AGENT_TYPE'] 	= CController::getAgent(1);
        $log['SUB_SITE'] 	= $_SERVER['SERVER_NAME'];
        $log['CREATE_TIME'] = 'noquote(NOW())';
        
        if ($syslog)
        {
            foreach ($syslog as $aSysLog)
            {
                $log['LOG_TYPE'] = $aSysLog['LOG_TYPE'];
                $log['LOG_PRI'] = $aSysLog['LOG_PRI'];
                $log['LOG_TEXT'] = $aSysLog['LOG_TEXT'];
                $log['OWNER_ID'] = $aSysLog['OWNER_ID'];
                $log['OWNER_NAME'] = $aSysLog['OWNER_NAME'];
                $log['SERV_ID'] = $aSysLog['SERV_ID'];
                $log['SERV_TYPE'] = $aSysLog['SERV_TYPE'];
                $log['ENTITY_ID'] = $aSysLog['ENTITY_ID'];
                $log['SUBS_ID'] = $aSysLog['SUBS_ID'];
                $this->insert('SYS_LOG', $log);
            }
        }
        else
        {
            $aUser = CSessionMgr::getSessionUser();
            $subs_id = $aUser ? $aUser['SUBS_ID'] : 0;
            $log['LOG_TYPE'] = 'VIW';
            $log['LOG_PRI'] = $define_pri;
            $log['LOG_TEXT'] = '用户浏览';
            $log['OWNER_ID'] = 0;
            $log['OWNER_NAME'] = '';
            $log['SERV_ID'] = 0;
            $log['SERV_TYPE'] = '';
            $log['ENTITY_ID'] = 0;
            $log['SUBS_ID'] = $subs_id;
            $this->insert('SYS_LOG', $log);
        }
    }
    
    
    public function getAdminLastLoginTime($adminId)
    {
    	if($adminId)
    	{
    		$this->Load("SUBS_ID='$adminId' and LOG_TYPE='ADM' order by LOG_ID desc");
    	}
		return $this->CREATE_TIME;
    }
  
}
?>
