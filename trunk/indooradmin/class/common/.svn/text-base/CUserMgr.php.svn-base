<?php 
class CUserMgr extends CComponent{
	
	
	
	public function getUserCommonData($pageSize = 10,$url = "",$roleList = ''){
		$DaoAdmin 		= new CDAOCS_SUBS();
		$DAOADMIN_PRIV = new CDAOADMIN_PRIV();
		$p 				= new page($DaoAdmin->getAdminCount());
        $p->baseUrl 	= $url;
        $p->pagesize 	= $pageSize;
        $page 			= $p->generate();
		$adminInfo = $DaoAdmin->getPageAdmin($p->pagesize, $page['page']);
		$pageData['admin_list'] = $adminInfo;
		$pageData['page'] 			= $page;
		return $pageData;
		
	}
	
	public function getUserCommonInfo($subsId){
		
	}
	

	public function addUser(){
		
	}
  
}
?>