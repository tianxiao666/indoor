<?php
/**
 * this file is for admin, it is the father controller of all admin
 */

class AdminController extends Controller
{
	protected $user;
	protected $startTime;
	
	
	/**
	 * \brief construct function
	 */
	public function __construct()
	{
		$this->user 		= CUserSession::getSessionAdmin();
		$this->startTime 	= $this->getCurrentTime();
	}
		
	
	/**
	 * \bref default action
	 */
	public function actionIndex()
	{
		print_r("This is the default action actionIndex, please change it if you need.");
	}
	
	
	/**
	 * \brief check the user wether login
	 */
	protected function checkLogin()
	{
        if (empty($this->user)) 
        {
        	CJsCall::jsAlert("温馨提示：请您登录后再执行操作。");
        	CJsCall::jsJumpUrl('admin.php');
            SF::app()->end();        
        }
	}
	
	
	/**
	 * \brief check the admin permision
	 */
	protected function checkAdminPermission()
	{
        if (empty($this->user) || ($this->user['ROLE']!='ADMIN'))
        {
        	CJsCall::jsAlert('温馨提示：只有超级管理员才有权限做此操作。');
        	CJsCall::jsCall('admin.php');
            SF::app()->end();          
        }
	}
	
	
	/**
	 * \brief check wether the user is super admin
	 */
	protected function isSuperAdmin()
	{
        return $this->user['ROLE'] == 'ADMIN' ? true : false;
	}
	
	
    /**
     * \brief write the admin log
     * @param string $log_type
     * @param string $log_pri
     * @param string $log_text
     */
    protected function writeAdminLog($log_type, $log_pri, $log_text)
    {
        $aSysLog['LOG_TYPE']    = $log_type;
        $aSysLog['LOG_PRI']     = $log_pri;
        $aSysLog['LOG_TEXT']    = $log_text;
        $aSysLog['SUBS_ID']     = $this->user['SUBS_ID'];
                
        $aSysLog['OWNER_ID']    = 0;
        $aSysLog['OWNER_NAME']  = '';
        $aSysLog['SERV_ID']     = 0;
        $aSysLog['SERV_TYPE']   = '';
        $aSysLog['ENTITY_ID']   = 0;

        $aSysLog['OP'] 		= $this->getOperation();
     	$aSysLog['COST']	= $this->getCurrentTime() - $this->startTime;
                
        $aSysLog['LOG_IP'] 	= $_SERVER['REMOTE_ADDR'];
        $aSysLog['GET_URL'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $aSysLog['REFER'] 	= $_SERVER['HTTP_REFERER'];
        
        $arr_refer 				= split ("\/", $log['REFER']);
        $aSysLog['REFER_SITE'] 	= $arr_refer[2];
        $aSysLog['USER_AGENT'] 	= $_SERVER['HTTP_USER_AGENT'];
        
        $aSysLog['AGENT_TYPE'] 	= CGetAgent::GetAgent(1);
        $aSysLog['SUB_SITE'] 	= $_SERVER['SERVER_NAME'];
        $aSysLog['CREATE_TIME'] = date('Y-m-d H:i:s');
        
        $DAOLog = new CDAOLog();
        $DAOLog->addAdminSysLog($aSysLog);
    }
    
    
    /**
     * \brief get the op
     */
    protected function getOperation()
    {
    	$ref 			= new ReflectionClass($this);
    	$controllerId 	= str_replace('Controller', '', $ref->name);
    	return $controllerId.'->'.$this->getAction()->getId();
    }
    
    
    /**
     * \brief get current time
     */
    protected function getCurrentTime()
    {
    	list($msec, $sec) = explode(' ', microtime());
		return (float)$msec + (float)$sec;
    }

}
