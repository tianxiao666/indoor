<?php
/**
 * 内部员工表操作类
 * @author panyf 2011.11.1
 */

class CDAOSYS_SUBS extends CCDAOSYS_SUBS
{
	var $_table = "SYS_SUBS";
	var $_sys_role_table = "SYS_ROLE";  //系统角色表
	var $_sys_subs_sequence = "SEQ_SYS_SUBS_SYS_SUBS_ID";
	
    //构造函数
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     *start SYS_SUBS登陆用的方法 
     *login by username and password
     */
    function userLogin($userName, $userPassword)
	{
		if (empty($userName) || empty($userPassword))
		{
			return false;
		}
		$passwd = md5($userPassword);
		if ($result = $this->Load(" NAME = '$userName' AND PASSWD = '$passwd' AND STATE='A' "))
		{
			$userData['SYS_SUBS_ID'] = $this->SYS_SUBS_ID;
			$userData['NAME'] 		 = $this->NAME;
			$userData['PASSWD'] 	 = $this->PASSWD;
			$roles = $this->getRolesBySubsId($this->SYS_SUBS_ID);
			SF::log($roles);
			$roleArr = array();
			if($roles){
				foreach ($roles as $ro){
					$roleArr[$ro['OBJ_CODE']] = $ro['OBJ_CODE'];
				}
			}
			$userData['ROLE'] 		= $roleArr;
			$userData['REAL_NAME'] 	= $this->REAL_NAME;
			return $userData;
		}

		return false;
	}
	
	/**
	 * 获取用户角色
	 */
    public function getRolesBySubsId($subsId = 0){
		if(!$subsId) return false;
		$sql = "SELECT * FROM SYS_ADMIN_PRIV WHERE SUBS_ID ={$subsId}";
		$result = $this->DB()->getAll($sql);
		return $result;
	}
	
    function isAdmin($roles)
	{
		if (empty($roles))
		{
			return false;
		}
		$roleStr = '';
		$key = 1;
		foreach ($roles as $role){
			if($key<count($roles)){
				$roleStr.="'{$role}'".",";
			}else{
				$roleStr.="'{$role}'";
			}
			$key++;
		}
		$sql 	= "SELECT COUNT(*) AS COUNTS FROM $this->_sys_role_table WHERE ROLE_CODE IN ($roleStr)";
		$result = $this->DB()->Execute($sql);
		return $result->_array[0]['COUNTS'];
	}
	/**
	 * 获取用户权限
	 * Enter description here ...
	 * @param unknown_type $subsId
	 * @param unknown_type $role
	 */
    public function getPrivBySubsId($subsId = 0,$role){
		if(!$subsId) return false;
		// amin has all permission, so it needn't get the permission from db
		if (empty($role) || in_array('ADMIN', $role))
		{
			return array();
		}
		$sql = "SELECT PRI.SUBS_ID,PRI.OBJ_TYPE,PRI.CREATE_TIME,OBJ_ID,ROL.PRIV_CODE FROM SYS_ADMIN_PRIV PRI LEFT JOIN SYS_ROLE_PRIV ROL ON PRI.OBJ_CODE=ROL.ROLE_CODE WHERE SUBS_ID={$subsId} AND OBJ_TYPE='R'";
		$roles = $this->DB()->getAll($sql);
		$role_priv = array();
		if($roles){
			foreach($roles as $key=>$role){
				if($role['OBJ_ID'] && $role['PRIV_CODE']){
					$role_priv[$role['PRIV_CODE']] = $role['OBJ_ID'];
				}elseif($role['PRIV_CODE']){
					$role_priv[$role['PRIV_CODE']] = $role['PRIV_CODE'];
				}
			}
		}
		return $role_priv;
		
	}
    /**
     *end SYS_SUBS登陆用的方法 
     */
	
	
    /**
     * 新增员工，返回员工ID
     */
    public function addUser($arrUserInfo) {
		if (!trim($arrUserInfo['NAME']) || !trim($arrUserInfo['EMAIL'])) {
			return FALSE;
		}
		$arrUserInfo['SYS_SUBS_ID'] = $this->DB()->nextId($this->_sys_subs_sequence);
		if (!$arrUserInfo['SYS_SUBS_ID']) {
			return FALSE;
		}

		$this->setFrom($arrUserInfo);
		if(TRUE===$this->Save()){
			return  $arrUserInfo['SYS_SUBS_ID'];
		}else{
			return FALSE;
		}
	}
	
	/**
	 * 获取员工分页信息
	 */
    function getUserPageData($pageSize = 10, $pageNum = 1, $whereSql='')
    {
       $sql = "SELECT distinct T1.SYS_SUBS_ID, T1.NAME, T1.REAL_NAME, T1.EMAIL,T1.STATE,T1.CREATE_TIME,T1.VISIT_TIME FROM SYS_SUBS T1 LEFT JOIN SYS_DEPT_SUBS T2 on T1.SYS_SUBS_ID = T2.SYS_SUBS_ID LEFT JOIN SYS_DEPT T3 on T2.DEPT_ID = T3.DEPT_ID where ";
    	if($whereSql){
       		$sql.=$whereSql;
       	}
       	$result = $this->DB()->PageExecute($sql, $pageSize, $pageNum);
    	return $result;
    }
    /**
     * 获取景区合作平台用户
     */
    function getPageAdmin($pageSize = 10, $pageNum = 1,$whereSql = '')
    {
       	$sql 	= "SELECT T1.SYS_SUBS_ID, T1.NAME, T1.REAL_NAME, T1.EMAIL,T1.STATE,T2.OBJ_ID,T3.ROLE_NAME,T4.AREA_NAME  FROM SYS_SUBS  T1 LEFT JOIN  SYS_ADMIN_PRIV T2 ON T1.SYS_SUBS_ID = T2.SUBS_ID
         LEFT JOIN SYS_ROLE T3 ON T2.OBJ_CODE = T3.ROLE_CODE LEFT JOIN LS_AREA T4 ON T2.OBJ_ID=T4.AREA_ID WHERE 1=1";
       	if($whereSql){
       		$sql.=$whereSql;
       	}
       	$sql.= "  ORDER BY T2.OBJ_CODE,T1.CREATE_TIME DESC";
       	$result = $this->DB()->PageExecute($sql, $pageSize, $pageNum);
    	if (empty($result->_array))
		{
			return array();
		}
        
		$adminList	= array();
       	$CDict 		= new CDict();
      	foreach ($result->_array as $key => $record)
       	{
       		$adminList[$key]		 = $record;
       		$adminList[$key]['NICK_NAME'] = StringUtils::handleUserName($record['NAME'],$record['NICK_NAME']);
        	$adminList[$key]['AREA'] = $CDict->AREA_CODE_GD_NAME[$record['AREA_CODE']];
      	}
      	
        return $adminList;
    }
    /**
     * 获取景区合作平台用户数量
     */
    function getAdminCount($whereSql = '')
    {
    	$sql 	= "SELECT COUNT(*) AS COUNTS FROM SYS_SUBS  T1 LEFT JOIN  SYS_ADMIN_PRIV T2 ON T1.SYS_SUBS_ID = T2.SUBS_ID
 				LEFT JOIN SYS_ROLE T3 ON T2.OBJ_CODE = T3.ROLE_CODE LEFT JOIN LS_AREA T4 ON T2.OBJ_ID=T4.AREA_ID WHERE 1=1";
    	if($whereSql){
       		$sql.=$whereSql;
       	}
    	$result = $this->DB()->Execute($sql);

    	return $result->_array[0]['COUNTS'];
    }

    /**
     * 根据员工ID获取员工信息
     */
    function getUserById($subsid = ''){
		if(empty($subsid)) return false;
		$sql = "SELECT * FROM {$this->_table} WHERE SYS_SUBS_ID = '{$subsid}' ";
		$result = $this->DB()->getAll($sql);
		if ($result) {
	   		return $result[0];
	   	}else{
		 	 return false;
		}
	}
	
	/**
	 * 根据用户角色取用户
	 * Enter description here ...
	 * @param unknown_type $OBJ_CODE
	 */
    public function getByuserCounseLingInfo($OBJ_CODE)
	{
		$sql = "select * from SYS_ADMIN_PRIV sa,{$this->_table} cs where sa.SUBS_ID=cs.SYS_SUBS_ID AND OBJ_CODE=:OBJ_CODE";
		return $this->DB()->getAll($sql,array('OBJ_CODE' => $OBJ_CODE));
	}
	
	/**
	 * 保存员工信息
	 */
    function updateUserInfo($subs_id, $user_info)
	{
		if (empty($subs_id) || !$this->Load("SYS_SUBS_ID = '$subs_id'"))
		{
			return false;
		}
		$user_info['MOD_TIME'] = date('Y-m-d H:i:s');

		$this->setFrom($user_info);
		return $this->Save();
	}
	/**
	 * 更新用户密码
	 */
    function updatePwd($userid,$password)
	{
		if (empty($userid) || empty($password))
		{
			return false;
		}
		$passwd = $password;
		if ($result = $this->Load(" SYS_SUBS_ID = '$userid'"))
		{
			$this->PASSWD= $passwd;
			if($this->Save()){
				return true;
			}else {
				return false;
			}
		}
		return false;
	}
    
    /**
     * 判断账号是否存在
     */
    function adminExists($name)
    {
    	if (empty($name))
    	{
    		return FALSE;
    	}
    	return $this->Load(" NAME = '$name' ");
    }
	
    /**
     * 判断账号是否唯一
     */
    function ExitName($name = '',$subs_id){
		if(empty($name)) return false;
		$sql = "SELECT * FROM {$this->_table} WHERE NAME = '{$name}' AND SYS_SUBS_ID != '{$subs_id}' AND STATE='A'";
		$result = $this->DB()->getAll($sql);
		if ($result) {
	   		return $result[0];
	   	}else{
		 	 return false;
		}
	}
    
    /**
     * 判断邮箱是否唯一
     */
    function ExitEmail($email = '',$subs_id){
		if(empty($email)) return false;
		$sql = "SELECT * FROM {$this->_table} WHERE EMAIL = '{$email}' AND SYS_SUBS_ID != '{$subs_id}' AND STATE='A'";
		$result = $this->DB()->getAll($sql);
		if ($result) {
	   		return $result[0];
	   	}else{
		 	 return false;
		}
	}
	/**
	 * 判断邮箱是否存在
	 */
    function emailExists($email)
    {
    	if (empty($email))
    	{
    		return false;
    	}
    	return $this->Load(" EMAIL = '$email' ");
    }
    
    /**
     * 判断手机号是否唯一
     */
    function editMobileExist($mobile,$subsid){
		if(empty($mobile)) return false;
		$sql = "SELECT * FROM {$this->_table} WHERE MOBILE = '{$mobile}' AND SYS_SUBS_ID != '{$subsid}' AND STATE='A'";
		$result = $this->DB()->getAll($sql);
		if ($result) {
	   		return $result[0];
	   	}else{
		 	 return false;
		}
	}
	/**
	 * 判断手机号是否存在
	 */
    function MobileExist($mobile)
    {
    	if (empty($mobile))
    	{
    		return false;
    	}
    	return $this->Load(" MOBILE = '$mobile' ");
    }
    /**
     * 判断是否当前用户密码
     */
    function isPWD($name, $userPassword)
	{

		if (empty($name) || empty($userPassword))
		{
			return false;
		}
		$passwd = md5($userPassword);
		if ($result = $this->Load(" NAME = '$name' AND PASSWD = '$passwd' "))
		{
			return true;
		}
		return false;
	}
    
    /**
     * 修改状态
     */
    function ChangeState($subs_id,$state){
        if (!$subs_id && !$state){
    		return FALSE;
    	}
        if($state == 'A'){
    	    $sql = "update SYS_SUBS set STATE ='A' where SYS_SUBS_ID ='{$subs_id}'";
        }else{
        	$sql = "update SYS_SUBS set STATE ='X' where SYS_SUBS_ID ='{$subs_id}'";
        }
    	if($this->DB()->Execute($sql)){
    		return TRUE;
    	}
    	else{
    		return FALSE;
    	}
    	
    }

    /**
     * 根据角色获取用户
     * @author yzb
     */
    function getSubsByRole($OBJ_CODE){
    	$sql ="SELECT SS.SYS_SUBS_ID,SS.REAL_NAME,SS.NAME  FROM {$this->_table} SS 
   				LEFT JOIN SYS_ADMIN_PRIV SAP ON SS.SYS_SUBS_ID = SAP.SUBS_ID ";
   		$sql.=" WHERE 1=1";
   		if($OBJ_CODE){
			$sql.=" AND OBJ_CODE='{$OBJ_CODE}'";
		}
		$sql.=" AND OBJ_TYPE='R' AND STATE='A' ORDER BY SS.CREATE_TIME";
   		$result = $this->DB()->Execute($sql);
   		return $result->_array;
    }
}
?>