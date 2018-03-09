<?php
/**
 * 部门管理类
 * @author panyf	2011.11.1
 */


class DeptMgrController extends AdminController
{
	const PAGE_SIZE = 30;
	public function __construct()
	{
		parent::__construct();
		$this->checkPermission('NOT');
	}
	
	/**
	 * 部门列表
	 */
	public function actionIndex(){
	    
		if(!$_GET['flag']){
			CUserSession::removeSessionValue('deptconditions');
		}
		$condition = CUserSession::getSessionValue('deptconditions');
		$Cdaodept  = new CDAOSYS_DEPT();
		$Ctree    = new CTreeData();
		
		$whereSql    = $this->genWhereSqlSearch($condition);
		$p           = new page($Cdaodept->getDeptCount($whereSql));
		$p->baseUrl  = 'ea.php?r=DeptMgr';
		$p->pagesize = self::PAGE_SIZE;
		$page        = $p->generate ();
		$dept_list   = $Cdaodept->getDeptPageData ( $p->pagesize, $page ['page'] ,$whereSql);
		
		if($dept_list){
			foreach ($dept_list as $v=>$k){
				if($k['P_DEPT_ID']){
					$Pdept = $Cdaodept->getAllDeptByid($k['P_DEPT_ID']);
					$dept_list[$v]['pname'] = $Pdept['DEPT_NAME'];   //获取父部门名
				}
			}
		}
		
		if(!$condition['D_NAME']){
		    $dept_list = $Ctree->getTreeData($dept_list,'DEPT_ID','P_DEPT_ID');   //部门树形结构
		}
		
		$pageData['user_role'] 	= $this->user['ROLE'];
		$pageData ['dept_list'] = $dept_list;
		$pageData['page']       = $page;
		$pageData['condition']	= $condition;
		$this->render("sysdept_list",$pageData);
	}
	
    function actionSearchDept(){
    	$condition = $this->checkRequestFormat();
    	CUserSession::setSessionValue('deptconditions', $condition);			
    	$this->jsCall("parent.location.href = 'ea.php?r=DeptMgr&flag=1';");	
    }
    
    /**
     * 编辑部门页面
     * Enter description here ...
     */
    public function actionEditDept(){
		$deptid = $_GET['id'];
		$Cdaodept = new CDAOSYS_DEPT();
		$Ctree    = new CTreeData();
		
		$deptinfo  = $Cdaodept->getAllDeptByid($deptid);
		$deptlist  = $Cdaodept->getAllDept();
		$dept_list = $Ctree->getTreeData($deptlist,'DEPT_ID','P_DEPT_ID');//获取部门树形结构

		$pageData['dept_list'] = $dept_list;
		$pageData['deptinfo']  = $deptinfo;
		$this->render("sysdept_edit",$pageData);
	}
	
	/**
	 * 新增部门页面
	 * Enter description here ...
	 */
	public function actionAddDept(){
		$Cdaosubs = new CDAOSYS_SUBS();
		$Cdaodept = new CDAOSYS_DEPT();
		$Ctree    = new CTreeData();
		
		$result = $Cdaosubs->getAll("STATE = 'A'"); //获取正常状态的员工
		
		//员工数组整理
	    $user_list 	= array();
        if ($result)
        {
        	foreach ($result as $record)
           	{
           		if($record['REAL_NAME']){
            	    $user_list[$record['SYS_SUBS_ID']] = $record['REAL_NAME'];
           		}else{
           			$user_list[$record['SYS_SUBS_ID']] = $record['NAME'];
           		}
           	}
       	}
        
       	//获取部门树形结构
		$deptlist  = $Cdaodept->getAllDept();
		$dept_list = $Ctree->getTreeData($deptlist,'DEPT_ID','P_DEPT_ID');

		$pageData['dept_list'] = $dept_list;
		$pageData['userlist']  = $user_list;
		$this->render("sysdept_add",$pageData);
	}
	
	/**
	 * 保存编辑部门
	 * Enter description here ...
	 */
	function actionDoEdit(){
		$data = $this->checkRequestFormat();
		$Cdaodept = new CDAOSYS_DEPT();
				
		$deptinfo['DEPT_NAME'] = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['DEPT_NAME'])));
		$deptinfo['P_DEPT_ID'] = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['P_DEPT_ID'])));
		/*
		//判断所属部门等级是否高于该部门
		$dept  = $Cdaodept->getAllDeptByid($data['DEPT_ID']);
		$pdept = $Cdaodept->getAllDeptByid($deptinfo['P_DEPT_ID']);
		if($pdept && $pdept['LEVEL'] >= $dept['LEVEL']){
		    $this->jsAlert("所属部门等级必须高于该部门");
			$this->jsJumpBack ();
          	return false;
		}*/
		$result = $Cdaodept->SaveEditDept($data['DEPT_ID'],$deptinfo);
		if($result){
			$this->jsAlert("修改成功！");
			$this->jsCall("parent.location.href = 'ea.php?r=DeptMgr';");
		}else{
			$this->jsAlert("修改失败");
			$this->jsJumpBack ();
          	return false;
		}
	}
	
	/**
	 * 保存新增部门
	 * Enter description here ...
	 */
	function actionAjaxDoAdd(){
		$data = $this->checkRequestFormat();
		$user = CUserSession::getSessionAdmin();
		$Cdaodept = new CDAOSYS_DEPT();
		$data['DEPT_NAME'] = iconv("UTF-8", "GBK", trim($data['DEPT_NAME']));
		
		$deptinfo['DEPT_NAME']   = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['DEPT_NAME'])));
		$deptinfo['STATUS']      = 'A';
		$deptinfo['P_DEPT_ID']   = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['P_DEPT_ID'])));
		$deptinfo['CREATE_TIME'] = date('Y-m-d');
		$deptinfo['MOD_TIME']    = date('Y-m-d');
		$deptinfo['SYS_SUBS_ID'] = $user['SYS_SUBS_ID'];
		
		ob_clean();
		ob_start();
		//根据所属部门定义等级,暂为3级
		if($data['P_DEPT_ID']){
			$result = $Cdaodept->getAllDeptByid($data['P_DEPT_ID']);
			if($result['DEPT_LEVEL']=='1'){
			    $deptinfo['DEPT_LEVEL'] = '2';
			}else{
				$deptinfo['DEPT_LEVEL'] = '3';
			}
		}else{
			$deptinfo['DEPT_LEVEL'] = '1';
		}
		//保存部门
		if($deptinfo){
			$result = $Cdaodept->AddDept($deptinfo);
						
		    //保存部门成员
		    if($data['SYS_SUBS_ID'] && $result){
		    	$data['SYS_SUBS_ID'] = explode(',', $data['SYS_SUBS_ID']);
				foreach ($data['SYS_SUBS_ID'] as $id){
					$Cdaodeptsubs = new CDAOSYS_DEPT_SUBS();
					$Cdaodeptsubs->AddUserDept($id,$result['DEPT_ID']);
				}	
		    }
			echo true;
		}else{
			echo false;
		}
		ob_end_flush();
	}
	
	/**
	 * 验证部门名是否唯一
	 * Enter description here ...
	 */
	function actionAjaxCheckDept(){
		$data = $this->checkRequestFormat();
		$data['dept_name'] = iconv("UTF-8", "GBK", trim($data['dept_name']));
		
		$Cdaodept = new CDAOSYS_DEPT();
		$deptname = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($data['dept_name'])));
		if($data['dept_id']){
			$result = $Cdaodept->ExitDeptEdit($deptname,$data['dept_id']);
		}else{
		    $result = $Cdaodept->ExitDeptName($deptname);
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
    
    public function genWhereSqlSearch($condition){
		$whereSql = " 1=1 ";
		if ($condition['D_NAME'])
		{
			$condition['D_NAME'] = TextFilter::encodeQuote(TextFilter::filterAllHTML(trim($condition['D_NAME'])));
		    $whereSql.=" AND DEPT_NAME like '%{$condition['D_NAME']}%'";
		}
		return $whereSql;
	}

}
?>