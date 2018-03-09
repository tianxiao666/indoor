<?php

class LoginController extends AdminController
{
	var $_go_url = 'ea.php'; //登录成功和退出时跳转地址 ?r=LsArea/AreaList
	protected $needLogin = FALSE;
	
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		if (empty($this->user))
		{
			$this->render('login');
		}
		else
		{
			$pageData['miyao']= GOOGEL_MAP_MIYAO;
			$this->render('index',$pageData);
		}
	}


	public function actionAuthImage()
	{
		GetImage::GetImg();
	}


	/**
	 * 
	 */
	public function actionLogin()
	{
		$cdit = new CDict();
		$session_pass = GetImage::getAuthcode();
		if ($_POST['pass'] == '' || $_POST['pass'] != $session_pass)
		{
			$error_msg = '验证码错误，请重新输入！';
		}elseif(strstr($_POST['userName'],"'") || strstr($_POST['userName'],";") || strstr($_POST['userName'],"--")
                         || strstr($_POST['userName'],"Xp_") || strstr($_POST['userName'],"*") ||strstr($_POST['userName'],"/")){
                        $error_msg = '用户名中不能包含特殊字符！';
                }
		else
		{
			$CDAOSubs 	= new CDAOSYS_SUBS();
			$userData	= $this->checkRequestFormat();
			$userInfo 	= $CDAOSubs->userLogin($userData['userName'], $userData['userPassword']);
			if (empty($userInfo))
			{
				$error_msg = '登录帐号或登录密码错误！';
			}
			else if ((array_key_exists('ADMIN', $userInfo['ROLE']) || $CDAOSubs->isAdmin($userInfo['ROLE'])))
			{
				
				$userInfo['per'] = $CDAOSubs->getPrivBySubsId($userInfo['SYS_SUBS_ID'],$userInfo['ROLE']);
				$oAdminLog				= new CDAOLog();
				$userInfo['lastLogin']	= $oAdminLog->getAdminLastLoginTime($userInfo['SYS_SUBS_ID']);
				//$userInfo['SUBS_ID'] = $userInfo['SYS_SUBS_ID']; //先暂时加多SUBS_ID，因为很多地方还没改
				
				CUserSession::setSessionAdmin($userInfo);
				$this->user	= $userInfo;
				$this->writeAdminLog('ADM', 'SYS', "管理员 {$userInfo['NAME']} 登录后台");
			}
			else
			{
				$error_msg = '您不具备访问权限！';
			}
		}
		if ($error_msg)
		{
                          $pageData['error_msg'] = $error_msg;	
                          $this->render('login',$pageData);
		}
		else
		{
                        $pageData['miyao']= GOOGEL_MAP_MIYAO;
                        $this->jsCall("parent.location.href='ea.php?r=BuildingMgr'");
                        //$this->render('building_list',$pageData);
                        // $this->render($this->_go_url);
		//	$js = "parent.location.href='{$this->_go_url}';";
		}
		//$this->jsCall($js);
		/*$lastdate = date("Y-m-d H:i:s", time() - 90*3600*24);
		$log  = new CDAOLog();
		$result = $log->getSubPwdLog($userInfo['SUBS_ID'],$lastdate);
		if($result == 0 && !empty($userInfo))
		{
			CUserSession::setSessionValue('user_login_condition', true);
			$this->jsCall("parent.location.href='ea.php?r=Login/EdView&func=1'");
		}else
		{
			$this->jsCall($js);
		}*/
	}

	public function actionLogout($return_url = false)
	{
		$this->writeAdminLog('OAD', 'SYS', "管理员 {$this->user['NAME']} 退出登录");
		CUserSession::destroy();
		$return_url = $_GET['return_url'] ? $_GET['return_url'] : $this->_go_url;
		$this->jsJumpUrl($return_url);
	}
	function actionEdView()
	{
		$pageDate['USERNAME'] = $this->user['NAME'];
		$pageDate['SUBS_ID'] = $this->user['SUBS_ID'];
		$data = $this->checkRequestFormat('get');
		$pageDate['func'] = isset($data['func'])?$data['func']:"";
		$this->render('EditPassword',$pageDate);
	}
	function actionEdit()
	{
		$CDAOSubs = new CDAOCS_SUBS();
		$data = $this->checkRequestFormat('post');
		$PASSWD = $data['PASSWD'];
		if(intval($data['notoldpwd']) != 1)
		{
			$subs = $CDAOSubs->isPWD($this->user['NAME'],$PASSWD);
			if(!$subs){
				$this->jsAlert('原密码错误！');
				$this->jsCall("parent.location.href='ea.php?r=Login/EdView'");

			}
		}
		$newPwd = $data['PASSWD1'];
		$old_user_info 	= $CDAOSubs->getUserInfoById($data['SUBS_ID']);
		if(intval($data['notoldpwd']) == 1)
		{
			$CDAOSubs->updatePwd($this->user['NAME'],$newPwd);
		}else{
			$CDAOSubs->updateSub($this->user['NAME'],$PASSWD,$newPwd);
		}
		$this->writeAdminLog('PWD', 'SYS', "管理员 {$this->user['NAME']} 修改用户“{$old_user_info['NAME']}”的资料");
		$this->jsAlert('修改成功,请重新登录！');
		CUserSession::removeSessionValue('user_login_condition');
		$this->jsCall("parent.location.href = 'ea.php?r=Login/Logout';");
	}

}
