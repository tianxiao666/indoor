<?php
/**
 * @author lin
 */


//class CDAOPriv extends CActiveRecord
class CDAOPriv extends CCDAOSYS_PRIV
{
	var $_table = "SYS_PRIV";
	var $_sys_role_priv = "SYS_ROLE_PRIV";
	var $_sys_priv = "SYS_PRIV";
	var $_sys_admin_priv = "SYS_ADMIN_PRIV";
	
    //¹¹Ôìº¯Êý
    function __construct()
    {
        parent::__construct();
    }

    
  	function getAllPriv()
    {
        $sql = "SELECT T1.*, T2.NAME AS OWNER_NAME FROM $this->_table  T1 JOIN SYS_SUBS  T2 ON T1.OWNER = T2.SYS_SUBS_ID";
        $privs = $this->DB()->Execute($sql);
        if ($privs->_array)
        {
            $CDict = new CDict();
            foreach ($privs->_array as $key => $priv)
            {
                $privs->_array[$key]['PRIV_TYPE_NAME'] = $CDict->SYS_PRIV_TYPE[$priv['PRIV_TYPE']];
            }
        }
        return $privs->_array ? $privs->_array : array();
    }
    
	function getPagePrivData($pageSize, $pageNum, $whereSql=FALSE,$secs2cache=0)
    {
        $sql = "SELECT T1.*, T2.NAME AS OWNER_NAME FROM $this->_table  T1 JOIN SYS_SUBS  T2 ON T1.OWNER = T2.SYS_SUBS_ID";
        $privs = $this->DB()->PageExecute($sql, $pageSize, $pageNum, false, $secs2cache);
        if ($privs->_array)
        {
            $CDict = new CDict();
            foreach ($privs->_array as $key => $priv)
            {
                $privs->_array[$key]['PRIV_TYPE_NAME'] = $CDict->SYS_PRIV_TYPE[$priv['PRIV_TYPE']];
            }
        }
        return $privs->_array ? $privs->_array : array();
    }
 
    
    function addPriv($priv_info, $priv_role = array())
    {
    	// add priv info
    	$this->setFrom($priv_info);
    	if(!$this->Save())
    	{
    		return FALSE;
    	}
    	
    	// add priv role
    	if (empty($priv_role))
    	{
    		return;
    	}
    	

      	foreach ($priv_role as $role_code)
        {
        	if ($role_code == 'ADMIN')
        	{
        		continue;
        	}
        	
        	$rolePrivTmp				= new CActiveRecord($this->_sys_role_priv); 
    		$rolePrivTmp->ROLE_CODE		= $role_code;
    		$rolePrivTmp->PRIV_CODE		= $priv_info['PRIV_CODE'];
    		$rolePrivTmp->CREATE_TIME	= date('Y-m-d H:i:s');
    		$rolePrivTmp->Save();  
    	}
    }

    
    function PrivCodeExists($priv_code)
    {
    	if ($priv_code)
    	{
    		return $this->Load(" PRIV_CODE= '$priv_code' ");
    	}
    	return FALSE;
    }
    
    
    function getPrivInfo($priv_code)
    {
    	if ($priv_code && $this->Load(" PRIV_CODE = '$priv_code' "));
    	{
    		return $this->toArray();
    	}
    	return FALSE;
    }
    
    function getPrivRole($priv_code)
    {
    	if (empty($priv_code))
    	{
    		return FALSE;	
    	}
    	
    	
    	$privRoleTmp	= new CActiveRecord($this->_sys_role_priv);
    	$dbPrivRole		= $privRoleTmp->Find(" PRIV_CODE = '$priv_code' ");
        if (empty($dbPrivRole))
    	{
    		return FALSE;
    	}
    	
    	$roleList	= array();
    	foreach ($dbPrivRole as $aPrivRole)
    	{
    		array_push($roleList, $aPrivRole->ROLE_CODE);
    	}
    	
    	return $roleList;
    }
 
    
    function updatePriv($priv_code, $priv_info, $priv_roles)
    {
    	if (empty($priv_code) || !$this->Load(" PRIV_CODE = '$priv_code' "))
    	{
    		return FALSE;
    	}
    	
		$this->setFrom($priv_info);
		$this->Save();
		
		// update priv roles
		$dbPrivRoleTmp	= new CActiveRecord($this->_sys_role_priv); 
		$dbPrivRole		= $dbPrivRoleTmp->Find(" PRIV_CODE = '$priv_code' ");
		if ($dbPrivRole)
		{
			foreach ($dbPrivRole as $aRole)
			{
				$role_code	= $aRole->ROLE_CODE;
				if (is_array($priv_roles) && in_array($role_code, $priv_roles))
				{
					unset($priv_roles[$role_code]);
				}
				else 
				{
					$aRole->Delete();
				}
			}
		}
		
		// add new role
		if (empty($priv_roles))
		{
			return;
		}
		
		foreach ($priv_roles as $aRoleCode)
		{
			if ($aRoleCode == 'ADMIN')
			{
				continue;
			}
			
			$rolePrivTmp				= new CActiveRecord($this->_sys_role_priv); 
    		$rolePrivTmp->ROLE_CODE		= $aRoleCode;
    		$rolePrivTmp->PRIV_CODE		= $priv_code;
    		$rolePrivTmp->CREATE_TIME	= date('Y-m-d H:i:s');
    		$rolePrivTmp->Save();  
		}
    }
    
    
    function deletePriv($priv_code)
    {
    	if (empty($priv_code))
    	{
    		return FALSE;
    	}
    	
    	$this->DB()->Execute("delete from $this->_sys_priv WHERE PRIV_CODE = '$priv_code'");
    	$this->DB()->Execute("delete from $this->_sys_role_priv WHERE PRIV_CODE = '$priv_code'");
    	$this->DB()->Execute("delete from $this->_sys_admin_priv WHERE OBJ_CODE = '$priv_code'");
    }
    
}

?>
