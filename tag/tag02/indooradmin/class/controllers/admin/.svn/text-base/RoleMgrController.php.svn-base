<?php
/**
 * 
 * @author 
 */


class RoleMgrController extends AdminController
{
	const PAGE_SIZE = 15;
	public function __construct()
	{
		parent::__construct();
		$this->checkPermission('NOT');
	}
	
	
	function actionIndex()
    {
    	$DAORole 		= new CDAORole();
        $p 				= new page($DAORole->count());
        $p->baseUrl 	= 'ea.php?r=RoleMgr/index';
        $p->pagesize 	= self::PAGE_SIZE;
        $page 			= $p->generate();
        $pageData['page'] 		= $page;
    	$pageData['user_role'] 	= $this->user['ROLE'];
		$pageData['sys_role'] 	= $DAORole->getPageRoleData($p->pagesize, $page['page']);
        $this->render("rolemgr_index", $pageData);
    }
    
    
    function actionAddView()
    {
    	$this->checkAdminPermission();
    	
    	$CDit						= new CDict();
    	$DAOPriv 					= new CDAOPriv();
    	$pageData['priv_list'] 		= $DAOPriv->getAllPriv();
       	$pageData['sys_role_type'] 	= $CDit->SYS_ROLE_TYPE;
       	$this->render('rolemgr_addview', $pageData);
    }
    
    
	function actionDoAdd()
    {
    	
    	$this->checkAdminPermission();
    	
    	$role			= $this->checkRequestFormat();
    	$role['OWNER'] 	= $this->user['SYS_SUBS_ID'];
     	$DAORole = new CDAORole(); 
      	if ($DAORole->RoleCodeExists($role['ROLE_CODE']))
       	{
        	$this->jsAlert('角色编码已经存在！');
          	return $this->jsCall("parent.location.href = 'ea.php?r=RoleMgr';");
      	} 
        $DAORole->addRole($role, $role['role_priv']);
        
        $this->writeAdminLog('PRV', 'SYS', "管理员 {$this->user['NAME']} 添加角色“{$role['ROLE_NAME']}”");
        $this->jsAlert('添加成功！');
       	$this->jsCall("parent.location.href = 'ea.php?r=RoleMgr';"); 	
    }
    
    
	function actionEditView()
	{
		$this->checkAdminPermission();
		
		$roleCode = $this->qstr($_GET['role_code']);
		
		$CDict 					= new CDict();
     	$DAORole 				= new CDAORole();
        $role 					= $DAORole->getRoleInfo($roleCode);
        $role['ROLE_TYPE_NAME'] = $CDict->SYS_ROLE_TYPE[$role['ROLE_TYPE']];
        $role_priv 				= $DAORole->getRolePriv($roleCode);
        
        $pageData['role']			= $role;
        $pageData['role_code']		= $roleCode;
        $pageData['sys_role_type']	= $CDict->SYS_ROLE_TYPE;
        $pageData['sys_priv']		= $this->getSelectPriv($roleCode, $role_priv);

    	$this->render("rolemgr_editview", $pageData);
    }
    
    private function getSelectPriv($roleCode, $rolePriv)
    {
    	$DAOPriv	= new CDAOPriv();
        $sysPriv 	= $DAOPriv->getAllPriv();
        
        
    	if (empty($sysPriv))
    	{
    		return array();
    	}
    	
    	// adim priv
    	if ($roleCode == 'ADMIN')
    	{
    		foreach ($sysPriv as $key => $aSysPriv)
    		{
    			$sysPriv[$key]['checked'] = ' checked';
    		}
    		
    		return $sysPriv;
    	}
    	
    	// role priv
	    if (empty($rolePriv))
	    {
	    	return $sysPriv;
	    }
	    
	   	
	    foreach ($rolePriv as $aRolePriv)
	    {
	    	foreach ($sysPriv as $key => $aSysPriv)
	    	{
	    		if ($aRolePriv['PRIV_CODE'] == $aSysPriv['PRIV_CODE'])
	    		{
	    			$sysPriv[$key]['checked'] = ' checked';
	    			break;
	    		}
	    	}
	  	}
                          
    	return $sysPriv;
    }
    
    
	function actionDoEdit()
	{
		$this->checkAdminPermission();
		
		$aRole	= $this->checkRequestFormat();
		$aRole['old_role_code'] = trim($_POST["code"]);
    	$DAORole 		= new CDAORole();
        $old_role_info 	= $DAORole->getRoleInfo($aRole['old_role_code']);
        if($DAORole->updateRole($old_role_info['ROLE_CODE'], $aRole, $aRole['role_priv'])){
       	 	$this->writeAdminLog('PRV', 'SYS', "管理员 {$this->user['NAME']} 修改角色“{$old_role_info['ROLE_NAME']}”");
       	 	$this->jsAlert('修改成功！');
        	$this->jsCall("parent.location.href = 'ea.php?r=RoleMgr';");
        }else{
        	$this->jsAlert('修改失败！');
        	$this->jsCall("parent.location.href = 'ea.php?r=RoleMgr';");
        }
    }
    
    
    function actionDeleteRole()
    {
    	$this->checkAdminPermission();
    	
        $DAORole 	= new CDAORole();
        $role 		= $DAORole->getRoleInfo($this->qstr($_GET['role_code']));
        if ($role['ROLE_TYPE'] == 'SYS')
        {
            $this->jsAlert('操作失败！可能的原因如下：\n\n不能删除系统预设的角色。');
            return;
        }

        $DAORole->deleteRole($role['ROLE_CODE']);
        $this->writeAdminLog('PRV', 'SYS', "管理员 {$this->user['NAME']} 删除角色“{$role['ROLE_NAME']}”");
        $this->jsAlert('删除成功！');
        $this->jsCall("parent.location.href = 'ea.php?r=RoleMgr';");
    }	
    
    function actionAjaxEditTpl(){
    	$pageData['price'] = 10;
    	$this->render("price_tpl", $pageData, 'ajax');
    }
}
?>