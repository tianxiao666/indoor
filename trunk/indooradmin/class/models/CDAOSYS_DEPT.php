<?php
/**
 * 部门表操作类
 * @author panyf 2011.11.1
 */

class CDAOSYS_DEPT extends CCDAOSYS_DEPT
{
	var $_table = "SYS_DEPT";
	var $_sys_dept_sequence = "SEQ_SYS_DEPT_DEPT_ID";
	
    //构造函数
    function __construct()
    {
        parent::__construct();
    }
    
    //获取分页信息
    function getDeptPageData($pageSize = 20, $pageNum = 1, $whereSql='')
    {
       $sql = "SELECT * FROM $this->_table where";
    	if($whereSql){
       		$sql.=$whereSql;
       	}
       	$result = $this->DB()->PageExecute($sql, $pageSize, $pageNum);
    	return $result->_array;
    }
    function getDeptCount($whereSql = '')
    {
    	$sql = "SELECT COUNT(*) AS COUNTS FROM $this->_table where";
        if($whereSql){
       		$sql.=$whereSql;
       	}
    	$result = $this->DB()->getAll($sql);
		return $result[0]['COUNTS'];
    }
    
    //获取所有的正常状态的部门
    function getAllDept(){
		$sql = "select DEPT_ID, DEPT_NAME, P_DEPT_ID, 'LEVEL' from {$this->_table} WHERE STATUS='A'";
		$result = $this->DB()->getAll($sql);
		if ($result) {
	   		return $result;
	   	}else{
		 	 return false;
		}
	}
    
	//通过$dept_id获取部门信息
    function getAllDeptByid($dept_id){
		$sql = "select * from {$this->_table} where DEPT_ID = '{$dept_id}'";
		$result = $this->DB()->getAll($sql);
		if ($result) {
	   		return $result[0];
	   	}else{
		 	 return false;
		}
	}
	
	//保存编辑部门信息
    function SaveEditDept($deptid,$deptinfo){
	    if (empty($deptid) || !$this->Load("DEPT_ID = '$deptid'"))
		{
			return false;
		}
		$deptinfo['MOD_TIME'] = date('Y-m-d H:i:s');
        $this->setFrom($deptinfo);
		return $this->Save();
	}
	
	//新增部门
	function AddDept($deptinfo){
		$deptinfo['DEPT_ID'] = $this->DB()->nextId($this->_sys_dept_sequence);
		$this->setFrom($deptinfo);
		if($this->Insert()){
			$deptData['DEPT_ID'] 	 = $this->DEPT_ID;
			$deptData['DEPT_NAME'] 	 = $this->DEPT_NAME;
			$deptData['SYS_SUBS_ID'] = $this->SYS_SUBS_ID;
			$deptData['STATUS']      = $this->STATUS;
			$deptData['DEPT_LEVEL']  = $this->DEPT_LEVEL;
			$deptData['CREATE_TIME'] = $this->CREATE_TIME;
			$deptData['MOD_TIME']    = $this->MOD_TIME;
			$deptData['P_DEPT_ID']   = $this->P_DEPT_ID;
			return $deptData;
    	}
    	return false;
	}
		
	//通过员工ID获取员工所在部门
	function getDeptBysubsid($subsid){
		if(intval($subsid)){
			$sql = "select T1.DEPT_ID,T1.DEPT_NAME from {$this->_table} T1 left join SYS_DEPT_SUBS T2 on T1.DEPT_ID = T2.DEPT_ID where T2.SYS_SUBS_ID = '{$subsid}' ";
		    $result = $this->DB()->Execute($sql);
			if($result->_array){
				return $result->_array;
			}else{
				return array();
			}
		}
		return false;
	}
	
	//根据父ID获取子部门
    function getChildDeptByid($dept_id){
		$sql = "select * from {$this->_table} where P_DEPT_ID = '{$dept_id}'";
		$result = $this->DB()->getAll($sql);
		if ($result) {
	   		return $result;
	   	}else{
		 	 return false;
		}
	}
	
	//判断是否存在部门名
    function ExitDeptName($dept_name){
		if(empty($dept_name)){
			return false;
		}
		return $this->Load("DEPT_NAME = '{$dept_name}'");
	}
    
	//判断部门名是否唯一
    function ExitDeptEdit($dept_name,$dept_id){
		if(empty($dept_name) && empty($dept_id)){
			return false;
		}
		return $this->Load("DEPT_NAME = '{$dept_name}' AND DEPT_ID <> '{$dept_id}'");
	}
	
}
?>