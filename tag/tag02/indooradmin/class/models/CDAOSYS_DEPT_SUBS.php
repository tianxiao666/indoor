<?php
/**
 * �����û��������
 * @author panyf 2011.11.2
 */

class CDAOSYS_DEPT_SUBS extends CCDAOSYS_DEPT_SUBS
{
	var $_table = "SYS_DEPT_SUBS";
	
    //���캯��
    function __construct()
    {
        parent::__construct();
    }
    
    //����Ա��������Ϣ
    function AddUserDept($subsid,$deptid,$position=''){
		if(!$subsid && !$deptid){ 
			return false; 
		}
    	$Dept['SYS_SUBS_ID']	    = $subsid; 
    	$Dept['DEPT_ID']		= $deptid;
    	$Dept['POSITION']		= $position;
    	$Dept['CREATE_TIME']	= date('Y-m-d H:i:s');
    	$Dept['MOD_TIME']	    = date('Y-m-d H:i:s');
    	$this->setFrom($Dept);
    	if($this->Insert()){
    		$deptData['DEPT_ID'] 	= $this->DEPT_ID;
    		$deptData['SYS_SUBS_ID']    = $this->SYS_SUBS_ID;
    		$deptData['POSITION']    = $this->POSITION;
    		return $deptData;
    	}
    	return false;
	}
	
	//ͨ���û�ID��ȡ������Ϣ
    function getUserDeptByid($subs_id){
		$sql = "select T1.DEPT_ID,T1.POSITION, T2.DEPT_NAME from {$this->_table} T1  left join SYS_DEPT T2 on T1.DEPT_ID = T2.DEPT_ID where T1.SYS_SUBS_ID = '{$subs_id}'";
		$result = $this->DB()->getAll($sql);
		if ($result) {
	   		return $result;
	   	}else{
		 	 return false;
		}
	}
	
	//ɾ���û����Ź���
    function deleteAllUserDept($sbus_id){
    	if(!$sbus_id){
    		return false;
    	}
    	$depts   = $this->Find(" SYS_SUBS_ID = '{$sbus_id}' ");
    	if($depts){
    		foreach ($depts as $dept){
    			$dept->Delete();
    		}
    	}
    }
    
    //���ݲ���ID��ȡ���ڸò��ŵ�����Ա��,�����޸�
    function getUserByDeptId($dept_id){
    	if(!$dept_id)return false;
   		$sql = "select SDS.SYS_SUBS_ID,SS.REAL_NAME��SS.NAME from {$this->_table} SDS  
   		left join SYS_SUBS SS on SDS.SYS_SUBS_ID = SS.SYS_SUBS_ID 
   		where SDS.DEPT_ID = '{$dept_id}' and ss.STATE !='X'";
		return $this->DB()->getAll($sql);
    }
}
?>