<?php
/**
 * @author lin
 */


//class CDAORole extends CActiveRecord
class CDAORole extends CCDAOSYS_ROLE 
{
	var $_table = "SYS_ROLE";
	var $_sys_role_priv = "SYS_ROLE_PRIV";
	var $_sys_priv = "SYS_PRIV";
	var $_cs_subs = "CS_SUBS";
	var $_sys_admin_priv = "SYS_ADMIN_PRIV";
	
    //构造函数
    function __construct()
    {
        parent::__construct();
    }
    
    
     //方法
    function getAllRole()
    {
        $sql = "SELECT T1.*, T2.NAME AS OWNER_NAME FROM $this->_table  T1 JOIN SYS_SUBS  T2 ON T1.OWNER = T2.SYS_SUBS_ID";
        $sys_role = $this->DB()->Execute($sql);
        $role_array = $sys_role->_array;
		
        if ($role_array)
        {
            $CDict = new CDict();
            foreach ($role_array as $key => $role)
            {
                $role_array[$key]['ROLE_TYPE_NAME'] = $CDict->SYS_ROLE_TYPE[$role['ROLE_TYPE']];
            }
        }
        return $role_array ? $role_array : array();
    }
    
 function getPageRoleData($pageSize, $pageNum, $whereSql=FALSE,$secs2cache=0)
    {
        $sql = "SELECT T1.*, T2.NAME AS OWNER_NAME FROM $this->_table  T1 JOIN SYS_SUBS  T2 ON T1.OWNER = T2.SYS_SUBS_ID";
        $sys_role = $this->DB()->PageExecute($sql, $pageSize, $pageNum, false, $secs2cache);
        $role_array = $sys_role->_array;
		
        if ($role_array)
        {
            $CDict = new CDict();
            foreach ($role_array as $key => $role)
            {
                $role_array[$key]['ROLE_TYPE_NAME'] = $CDict->SYS_ROLE_TYPE[$role['ROLE_TYPE']];
            }
        }
        return $role_array ? $role_array : array();
    }
    
    
    function getRoleUsers($role_code)
    {
    	// check role code
    	if (empty($role_code))
    	{
    		return FALSE;
    	}
    	
    	// search
    	$CDAOSubs	= new CDAOCS_SUBS();
    	$roleUser	= $CDAOSubs->Find(" ROLE = '$role_code'");
    	if (empty($roleUser))
    	{
    		return FALSE;
    	}
    	
    	// get data
    	$roleUserList	= array();
    	foreach ($roleUser as $key => $aUser)
    	{
    		 array_push($roleUserList, $aUser['SUBS_ID']);
    	}
    	    	
    	return $roleUserList;
    }
    
    
  	function addRole($role_info, $role_priv = array())
    {
    	// add role
        $this->setFrom($role_info);
    	if(!$this->Save())
    	{
    		return false;
    	}
    	
    	// add role premission
    	if (empty($role_priv))
        {
        	return TRUE;
        }
                
        foreach ($role_priv as $priv_code)
        {
        	$rolePriv				= new CActiveRecord('SYS_ROLE_PRIV');
        	$rolePriv->ROLE_CODE	= $role_info['ROLE_CODE'];
        	$rolePriv->PRIV_CODE		= $priv_code;
        	$rolePriv->CREATE_TIME	= date('Y-m-d H:i:s');
        	$rolePriv->Save();
      	}

    	return true;
    }
    
          
    function RoleCodeExists($role_code)
    {
    	if (empty($role_code))
    	{
    		return FALSE;
    	}
        return $this->Find(" ROLE_CODE = '$role_code'");
    }
  
    
    function getRoleInfo($role_code)
    {
    	if ($this->Load(" ROLE_CODE = '$role_code' "))
    	{
    		return $this->toArray();
    	}
    	return FALSE;
    }
    
    /**
     * start pyf
     */
    
    /**
     * 获取用户角色
     */
    function getRoleByid($subsid){
        if (empty($subsid))
    	{
    		return false;
    	}
    	$sql = "SELECT T1.OBJ_CODE,T2.ROLE_NAME FROM $this->_sys_admin_priv T1 LEFT JOIN $this->_table T2 on T1.OBJ_CODE = T2.ROLE_CODE WHERE T1.SUBS_ID = '$subsid' AND T1.OBJ_TYPE = 'R'";
        $result = $this->DB()->getAll($sql);
		if ($result) {
	   		return $result;
	   	}else{
		 	 return false;
		}
    }
    /**
     * 删除用户非管理员角色
     */
    function deleteUserRole($sbus_id){
    	$spAdmin = new CActiveRecord($this->_sys_admin_priv);
    	$roles   = $spAdmin->Find(" SUBS_ID = '{$sbus_id}' AND OBJ_TYPE = 'R' AND OBJ_CODE != 'admin' ");
    	if($roles){
    		foreach ($roles as $role){
    			$role->Delete();
    		}
    	}
    }
    /**
     * 删除用户所有角色
     */
    function deleteAllUserRole($sbus_id){
    	$spAdmin = new CActiveRecord($this->_sys_admin_priv);
    	$roles   = $spAdmin->Find(" SUBS_ID = '{$sbus_id}' AND OBJ_TYPE = 'R' ");
    	if($roles){
    		foreach ($roles as $role){
    			$role->Delete();
    		}
    	}
    }
    /**
     * 新增用户角色
     */
    function SetUserRole($subsid,$rolecode){
        if(!$subsid && !$rolecode){ return false; }
        $spAdmin = new CActiveRecord($this->_sys_admin_priv);
        $spAdmin->OBJ_ID = $this->DB ()->nextId ( $this->SEQ_OBJ_ID );
    	$spAdmin->SUBS_ID	    = $subsid; 
    	$spAdmin->OBJ_CODE		= $rolecode;
    	$spAdmin->OBJ_TYPE		= 'R';
    	$spAdmin->CREATE_TIME	= date ( 'Y-m-d H:i:s' );
    	return $spAdmin->Save();

    }
    /**
     * end pyf
     */
    
    function getRolePriv($role_code)
    {
    	if (empty($role_code))
    	{
    		return array();
    	}
    	
        $sql 	= "SELECT T2.*, T3.NAME AS OWNER_NAME FROM $this->_sys_role_priv  T1 JOIN $this->_sys_priv  T2 ON T1.ROLE_CODE = '$role_code' AND T1.PRIV_CODE = T2.PRIV_CODE JOIN SYS_SUBS T3 ON T2.OWNER = T3.SYS_SUBS_ID";
        $privs 	= $this->DB()->execute($sql);

        if ($privs->_array)
        {
            $CDict = new CDict();
            foreach ($privs->_array as $key => $priv)
            {
                $privs->_array[$key]['PRIV_TYPE_NAME'] = $CDict->SYS_PRIV_TYPE[$priv->_array['PRIV_TYPE']];
            }
        }
        return $privs->_array ? $privs->_array : array();
    }

    
    function updateRole($role_code, $role_info, $role_priv = array())
    {
    	if (empty($role_code))
    	{
    		return FALSE;
    	}
    	// update role info
        if (!$this->Load("ROLE_CODE = '$role_code'"))
        {
        	return FALSE;
        }
    	$this->setFrom($role_info);
    	if($this->Save()){
	    	 if ($role_code != 'ADMIN')
	         {
	            $this->updateRolePriv($role_code, $role_priv);
	         }
	         return true;
    	}
    	// update role priv
       
    }
    
    
    private function updateRolePriv($roleCode, $privList)
    {
    	$rolePriv	= new CActiveRecord($this->_sys_role_priv); 
    	$dbRolePriv	= $rolePriv->Find(" ROLE_CODE = '$roleCode'");
    	
   	 	if ($dbRolePriv)
    	{    	
	    	foreach ($dbRolePriv as $aRolePriv)
	    	{
	    		$privCode	= $aRolePriv->PRIV_CODE;
	    		if (is_array($privList) && in_array($privCode, $privList))
	    		{
	    			unset($privList[$privCode]);
	    		}
	    		else
	    		{
	    			$aRolePriv->delete();
	    		}
	    	}
    	}
    	
    	// add new
    	if ($privList)
    	{
    		foreach ( $privList as $aPriv)
    		{
    			$rolePrivTmp				= new CActiveRecord($this->_sys_role_priv); 
    			$rolePrivTmp->ROLE_CODE		= $roleCode;
    			$rolePrivTmp->PRIV_CODE		= $aPriv;
    			$rolePrivTmp->CREATE_TIME	= date('Y-m-d H:i:s');
    			$rolePrivTmp->Save();
    		}
    	}
    }
    
    
    function deleteRole($role_code)
    {
    	if (empty($role_code))
    	{
    		return false;
    	}
    	
    	// delete the role user and role priv
    	$this->deleteRoleUser($role_code);
    	$this->deleteRolePriv($role_code);
        
        // delete auth_role
        if ($this->Load(" ROLE_CODE = '$role_code' "))
        {        
	        $this->Delete();
        }
    }
    
    private function deleteRoleUser($role_code)
    {    	
    	$CDAOSubs	= new CDAOCS_SUBS();
    	$roleUser	= $CDAOSubs->Find(" ROLE = '$role_code'");
    	if (empty($roleUser))
    	{
    		return FALSE;
    	}
    	
    	foreach ($roleUser as $aUser)
    	{
    		$spAdmin = new CActiveRecord($this->_sys_admin_priv);
    		if ($spAdmin->Load(" SUBS_ID = {$aUser->SUBS_ID} "))
    		{
    			$spAdmin->Delete();
    		}
    		$aUser->Delete();
    	}
    }
    
    private function deleteRolePriv($role_code)
    {
    	$rolePriv	= new CActiveRecord($this->_sys_role_priv); 
    	$dbRolePriv	= $rolePriv->Find(" ROLE_CODE = '$role_code'");
    	if (empty($dbRolePriv))
    	{
    		return FALSE;
    	}
    	
    	foreach ($dbRolePriv as $aRolePriv)
    	{
    		$aRolePriv->Delete();
    	}
    }
    
}

?>
