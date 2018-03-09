<?php
/**
 * 
 * @author 
 */


class PrivMgrController extends AdminController
{
	const PAGE_SIZE = 15;
	public function __construct()
	{
		parent::__construct();
		$this->checkPermission('NOT');
	}
	  
    
	function actionIndex()
    {
        $DAOPriv 				= new CDAOPriv();
        $p 				= new page($DAOPriv->count());
        $p->baseUrl 	= 'ea.php?r=privMgr/index';
        $p->pagesize 	= self::PAGE_SIZE;;
        $page 			= $p->generate();
        $pageData['page'] 		= $page;
        $pageData['user_role'] 	= $this->user['ROLE'];
		$pageData['sys_priv']	= $DAOPriv->getPagePrivData($p->pagesize, $page['page']);
        $this->render("privmgr_index", $pageData);	
    }
    
    
    function actionAddView()
    { 
    	$this->checkAdminPermission();
    	
   		$DAORole 				= new CDAORole();
    	$pageData['sys_role'] 	= $DAORole->getAllRole();
      	
      	$CDict 						= new CDict();
       	$pageData['sys_priv_type'] 	= $CDict->SYS_PRIV_TYPE;
            
      	$this->render("privmgr_addview", $pageData);
    }
    
    
	function actionDoAdd()
    {
    	$this->checkAdminPermission();
    	
    	$priv			= $this->checkRequestFormat();
    	$priv['OWNER'] 	= $this->user['SYS_SUBS_ID'];
    	 
     	$DAOPriv = new CDAOPriv();
       	if ($DAOPriv->PrivCodeExists($priv['PRIV_CODE']))
       	{
        	$this->jsAlert('权限编码已经存在！');
           	return false;
      	}
      	
     	$DAOPriv->addPriv($priv, $priv['priv_role']);
        $this->writeAdminLog('PRV', 'SYS', "管理员 {$this->user['NAME']} 添加权限“{$priv['PRIV_NAME']}”");
        $this->jsAlert('添加成功！');
        $this->jsCall("parent.location.href = 'ea.php?r=PrivMgr';");	
    }
    
           
    function actionEditView()
    {
    	$this->checkAdminPermission();

    	$privCode	= $this->qstr($_GET['priv_code']);
    	
       	$DAOPriv 	= new CDAOPriv();
       	$priv 		= $DAOPriv->getPrivInfo($privCode);
       	
       	$DAORole	= new CDAORole();
       	$sys_role 	= $DAORole->getAllRole();
            
       	if ($sys_role)
       	{
        	$priv_roles = $DAOPriv->getPrivRole($privCode);
        	foreach ($sys_role as $key => $role)
          	{
            	if ($role['ROLE_CODE'] == 'ADMIN')
               	{
                	$sys_role[$key]['checked'] = ' checked';
               	}
               	else if ($priv_roles && in_array($role['ROLE_CODE'], $priv_roles))
              	{
                	$sys_role[$key]['checked'] = ' checked';
               	}
              	else
              	{
                	$sys_role[$key]['checked'] = '';
              	}
           	}
       	}
            
       	$CDict 					= new CDict();
        $priv['PRIV_TYPE_NAME'] = $CDict->SYS_PRIV_TYPE[$priv['PRIV_TYPE']];
        
        $pageData['priv_code']		= $privCode;
        $pageData['priv']			= $priv;
        $pageData['sys_priv_type']	= $CDict->SYS_PRIV_TYPE;
        $pageData['sys_role']		= $sys_role;

      	$this->render("privmgr_editview",$pageData);
    }
    	
    
 	function actionDoEdit()
    {
    	$this->checkAdminPermission();
    	
    	$privData	= $this->checkRequestFormat();   
       	$DAOPriv 		= new CDAOPriv();
       	$old_priv_info 	= $DAOPriv->getPrivInfo($privData['PRIV_CODE']);    
       	$DAOPriv->updatePriv($old_priv_info['PRIV_CODE'], $privData, $privData['priv_role']);
       	$this->writeAdminLog('PRV', 'SYS', "管理员 {$this->user['NAME']} 修改权限“{$old_priv_info['PRIV_NAME']}”");
       	$this->jsAlert('修改成功！');
       	$this->jsCall("parent.location.href = 'ea.php?r=PrivMgr';");
    }
    
    
    function actionDeletePriv()
    {
    	$this->checkAdminPermission();
    	
    	$privCode	= $this->qstr($_GET['priv_code']);
        $DAOPriv 	= new CDAOPriv();
        $priv 		= $DAOPriv->getPrivInfo($privCode);
        if ($priv['PRIV_TYPE'] == 'SYS')
        {
            $this->jsAlert('操作失败！可能的原因如下：\n\n不能删除系统预设的权限。');
            return;
        }
        
        $DAOPriv->deletePriv($privCode);
        $this->writeAdminLog('PRV', 'SYS', "管理员 {$this->user['NAME']} 删除权限“{$priv['PRIV_NAME']}”");
        $this->jsAlert('删除成功！');
        $this->jsCall("parent.location.href = 'ea.php?r=PrivMgr';");
    }
	
}
?>