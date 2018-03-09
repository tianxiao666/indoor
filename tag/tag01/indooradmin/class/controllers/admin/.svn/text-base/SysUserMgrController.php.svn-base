<?php
/**
 * 用户管理类
 * @author pyf
 *
 */ 
class SysUserMgrController extends AdminController{
	public $_page = 1;//当前页
	public $_count = 15;//每页个数
	public function __construct(){
		parent::__construct();
		
	}
	
	/**
	 * 员工列表
	 * Enter description here ...
	 */
	public function actionIndex(){
	    if(!$_GET['flag']){
			CUserSession::removeSessionValue('userconditions'); //如果不是搜索则去除搜索条件
		}
		$condition = CUserSession::getSessionValue('userconditions');
		$Cdaosubs  = new CDAOSYS_SUBS();
		$Cdaodept  = new CDAOSYS_DEPT();
		$Ctree     = new CTreeData();
		$cdict     = new CDict();
		$whereSql  = $this->genWhereSqlSearch($condition);
	    		
	    $deptlist  = $Cdaodept->getAllDept();
		$dept_list = $Ctree->getTreeData($deptlist,'DEPT_ID','P_DEPT_ID');
		
	    if($_GET['page']){
			$this->_page = $_GET['page'];    //获取分页
		}
		$result   = $Cdaosubs->getUserPageData ($this->_count,$this->_page,$whereSql);
		$total    = $result->_maxRecordCount;
		$p = new page ($total); 
		$p->baseUrl  = 'ea.php?r=SysUserMgr&flag=1';
		$p->pagesize = $this->_count;
		$page        = $p->generate ();
		$userlist = $result->_array;
		
		//取员工角色和部门
		if($userlist){
			foreach ($userlist as $key=>$u){
				$role      = new CDAORole();
				$roles = $role->getRoleByid($u['SYS_SUBS_ID']);
				$userlist[$key]['ROLES'] = $roles;         //获取员工角色，可多个
				$dept = $Cdaodept->getDeptBysubsid($u['SYS_SUBS_ID']);
				$userlist[$key]['DEPTS'] = $dept;          //获取员工所属部门，可多个
			}
		}
		
		$pageData['STATE']      = $cdict->SYS_SUBS_STATE;    //员工状态
		$pageData['dept_list']  = $dept_list;
		$pageData ['user_list'] = $userlist;
		$pageData['page']       = $page;
		$pageData['condition']	= $condition;
		$pageData['user_role'] 	= $this->user['ROLE'];
		$this->render("sysuser_list",$pageData);
	}
	//搜索条件
    function actionSearchUser(){
    	$condition = $this->checkRequestFormat();
    	$id = $_GET['id'];
    	if($id){
    		$condition['DEPT'] = $id;
    	}
    	CUserSession::setSessionValue('userconditions', $condition);			
    	$this->jsCall("parent.location.href = 'ea.php?r=SysUserMgr&flag=1';");	
    }
	
    /**
     * 新增员工页面
     * Enter description here ...
     */
    public function actionAddUser(){
    	
    	$role      = new CDAORole();
    	$Cdaodept  = new CDAOSYS_DEPT();
		$Ctree     = new CTreeData();
    	
		//部门树形结构
    	$deptlist  = $Cdaodept->getAllDept();
		$dept_list = $Ctree->getTreeData($deptlist,'DEPT_ID','P_DEPT_ID');
    	
        //所有角色
		$rolelist  = $role->getAllRole();
	    
       	$pageData['dept_list'] = $dept_list;
       	$pageData['role_list'] = $rolelist;
    	$this->render("sysuser_add",$pageData);
    }
    
    /**
     * 新增员工
     * Enter description here ...
     */
    public function actionUserAdd()
    {
    	$this->checkAdminPermission();
     	$data = $this->checkRequestFormat();
     	$Cdaosubs  = new CDAOSYS_SUBS();
        $Deptsubs  = new CDAOSYS_DEPT_SUBS();
        $role      = new CDAORole();
    	
    	$data['NAME']      = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['NAME'])));
    	$data['REAL_NAME'] = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['REAL_NAME'])));
		$data['EMAIL']     = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['EMAIL'])));
		$data['POSITION']  = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['POSITION'])));
    	$data['PASSWD'] 		= md5($data['PASSWD']);
    	$data['STATE'] 		    = 'A';
        $data['CREATE_TIME']    = $data['MOD_TIME'] = $data['VISIT_TIME'] = date("Y-m-d H:i:s");
        $userData = $Cdaosubs->addUser($data);   //返回用户ID
        
        //更新用户角色
		if($data['ROLE']){
			foreach ($data['ROLE'] as $r){
				$role->SetUserRole($userData,$r);
			}
		}
		
		//更新用户部门
		if($data['DEPT']){
			foreach ($data['DEPT'] as $deptid){
			    $Deptsubs->AddUserDept($userData,$deptid,$data['POSITION']);
			}
		}
		
    	if($userData){
	        $this->jsAlert('添加成功！');
	        $this->jsCall("parent.location.href = 'ea.php?r=SysUserMgr';");
   	 	}else{
   	 		$this->jsAlert('添加失败！');
   	 		$this->jsJumpBack();
   	 		return false;
   	 	}
   	 	
    }
    
    /**
     * 编辑员工页面
     * Enter description here ...
     */
	public function actionEditUser(){
		$this->checkAdminPermission();
		$subsid = $_GET['id'];
		$Cdaosubs  = new CDAOSYS_SUBS();
		$role      = new CDAORole();
		$Cdaodept  = new CDAOSYS_DEPT();
		$Ctree     = new CTreeData();
		$Deptsubs  = new CDAOSYS_DEPT_SUBS();
		$userInfo  = $Cdaosubs->getUserById($subsid);
				
		//用户角色
		$roleInfo  = $role->getRoleByid($subsid);
		
		//所有角色
		$rolelist  = $role->getAllRole();
	    $deptlist  = $Cdaodept->getAllDept();
		$dept_list = $Ctree->getTreeData($deptlist,'DEPT_ID','P_DEPT_ID');
		$userdept  = $Deptsubs->getUserDeptByid($subsid);    //获取用户部门
		if($userdept){
			foreach ($userdept as $ud){
				$userInfo['POSITION'] = $ud['POSITION'];
			}
		}
		$pageData['userrole']  = $this->user['ROLE'];
		$pageData['dept_list'] = $dept_list;
		$pageData['depts']     = $userdept;
		$pageData['role']      = $roleInfo;
		$pageData['user_info'] = $userInfo;
		$pageData['role_list'] = $rolelist;
		$this->render("sysuser_edit",$pageData);
	}
	
	/**
	 * 保存编辑用户
	 * Enter description here ...
	 */
	public function actionSaveUser(){
		$data = $this->checkRequestFormat();
		if($data['subs_id'] != $this->user['SYS_SUBS_ID']) $this->checkAdminPermission();
		$Cdaosubs  = new CDAOSYS_SUBS();
		$role      = new CDAORole();
		$Cdaodept  = new CDAOSYS_DEPT();
		$Deptsubs  = new CDAOSYS_DEPT_SUBS();
		$Ctree     = new CTreeData();
		
		//更新用户角色
		if($data['ROLE']){
			if($this->user['ROLE']['admin']){
			    $role->deleteAllUserRole($data['subs_id']);   //删除用户角色，包括管理员角色
		    }else{
		        $role->deleteUserRole($data['subs_id']);      //删除用户角色，不包括管理员
		    }
		    foreach ($data['ROLE'] as $r){
				$role->SetUserRole($data['subs_id'],$r);
			}
		}
		
		//更新用户部门
		if($data['DEPT']){
			$Deptsubs->deleteAllUserDept($data['subs_id']);
			$data['POSITION']  = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['POSITION'])));
			foreach ($data['DEPT'] as $dept){
				$Deptsubs->AddUserDept($data['subs_id'],$dept,$data['POSITION']);
			}
		}
		//更新用户资料
		if($data['PASSWD']){
			$user['PASSWD'] = md5($data['PASSWD']);
		}
		$user['NAME']      = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['NAME'])));
		$user['REAL_NAME'] = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['REAL_NAME'])));
		$user['EMAIL']     = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['EMAIL'])));
		$user['MOBILE']    = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['MOBILE'])));
		$user['MSN']       = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['MSN'])));
		$user['QQ']        = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['QQ'])));
		$user['GENDER']    = $data['GENDER'];
		
		$result = $Cdaosubs->updateUserInfo($data['subs_id'],$user);
		if($result){
			$this->jsAlert("修改成功！");
			$this->jsCall("parent.location.href = 'ea.php?r=SysUserMgr';");
		}else{
			$this->jsAlert("修改失败");
			$this->jsJumpBack ();
          	return false;
		}
	}
	
	public function actionChangeState(){
		$subsid = $_GET['id'];
		$state  = $_GET['state'];
		$Cdaosubs  = new CDAOSYS_SUBS();
		$result = $Cdaosubs->ChangeState($subsid,$state);
	
		if($result){
			$this->jsAlert("操作成功！");
			$this->jsCall("parent.location.href = 'ea.php?r=SysUserMgr';");
		}else{
			$this->jsAlert("操作失败");
			$this->jsJumpBack ();
          	return false;
		}
	}
	
    /**
	 * ajax验证用户名是否存在
	 */
	public function actionAjaxCheckNAME(){
		$data = $this->checkRequestFormat();
		$name = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['name'])));
		$subs_id = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['subs_id'])));
		$Cdaosubs = new CDAOSYS_SUBS();
		if($subs_id){
			$result = $Cdaosubs->ExitName($name,$subs_id);
		}else{
		    $result = $Cdaosubs->adminExists($name);
		}
		ob_clean();
		ob_start();
		if($result){
			echo true;
		}else{
			echo false;
		}
		ob_end_flush();
		
	}
	
    /**
	 * ajax验证邮箱是否唯一
	 */
	public function actionAjaxCheckEMAIL(){
		$data = $this->checkRequestFormat();
		$email = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['email'])));
		$subs_id = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['subs_id'])));
		$Cdaosubs = new CDAOSYS_SUBS();
		if($subs_id){
		    $result = $Cdaosubs->ExitEmail($email,$subs_id);
		}else{
			$result = $Cdaosubs->emailExists($email);
		}
		ob_clean();
		ob_start();
		if($result){
			echo true;
		}else{
			echo false;
		}
		ob_end_flush();
		
	}
	
    /**
	 * ajax验证手机号唯一
	 */
    public function actionAjaxCheckMobile(){
		$data = $this->checkRequestFormat();
		$mobile = $data['mobile'];
		$subsid = $data['subsid'];
        $Cdaosubs = new CDAOSYS_SUBS();
		if($subsid){
		    $result = $Cdaosubs->editMobileExist($mobile,$subsid);
		}else{
			$result = $Cdaosubs->MobileExist($mobile);
		}
		ob_clean();
		ob_start();
		if($result){
			echo true;
		}else{
			echo false;
		}
		ob_end_flush();
		
	}
	
	/**
	 * 搜索用户SQL
	 * Enter description here ...
	 * @param unknown_type $condition
	 */
    public function genWhereSqlSearch($condition){
		$whereSql = " 1=1 ";
		if ($condition['NAME'])
		{
			$condition['NAME'] = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($condition['NAME'])));
		    $whereSql.=" AND (NAME like '%{$condition['NAME']}%' OR REAL_NAME like '%{$condition['NAME']}%')";
		}
        if ($condition['EMAIL'])
		{
			$condition['EMAIL'] = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($condition['EMAIL'])));
		    $whereSql.=" AND EMAIL = '{$condition['EMAIL']}' ";
		}
        if ($condition['U_STATUS'])
		{
			$condition['U_STATUS'] = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($condition['U_STATUS'])));
		    $whereSql.=" AND STATE = '{$condition['U_STATUS']}' ";
		}
		if($condition['DEPT']){
			$Cdaodept  = new CDAOSYS_DEPT();
			$dept = $Cdaodept->getAllDeptByid($condition['DEPT']);
			$whereSql.= "AND (T2.DEPT_ID='{$dept['DEPT_ID']}'";
			if($dept['DEPT_LEVEL']=='1'){
				$deptchild = $Cdaodept->getChildDeptByid($dept['DEPT_ID']);
				
				if($deptchild){
					$whereSql.= " OR P_DEPT_ID='{$dept['DEPT_ID']}'";
					foreach ($deptchild as $d){
						$whereSql.= " OR P_DEPT_ID='{$d['DEPT_ID']}'";
					}
					
				}
			}elseif ($dept['DEPT_LEVEL']=='2'){
				$whereSql.= " OR P_DEPT_ID='{$dept['DEPT_ID']}'";
			}
			$whereSql.= ")";			
		}
		
		return $whereSql;
	}
	
}
?>