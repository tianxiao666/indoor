<?php
/**
 * @author lin
 */


//class CDAOCode extends CActiveRecord
class CDAOCode extends CCDAOSYS_CODE
{
	var $_table = "SYS_CODE";
	
    //นนิ์บฏสý
    function __construct()
    {
        parent::__construct();
    }
    
    
    function getCodePageData($pageSize = 10, $pageNum = 1)
    {
       	$sql 	= "SELECT * FROM $this->_table ORDER BY CREATE_TIME DESC";
    	$result = $this->DB()->PageExecute($sql, $pageSize, $pageNum);
        return $result->_array;
    }
    
    
    function getCodeCount()
    {
    	$sql 	= "SELECT COUNT(*) AS COUNTS FROM $this->_table";
    	$result = $this->DB()->Execute($sql);
    	return $result->_array[0]['COUNTS'];
    }
    
    
    function addCode($code_info)
    {
    	$sql = "INSERT INTO SYS_CODE VALUES('".$code_info['CODE_NAME']."','".$code_info['CODE_TYPE']."','".$code_info['CODE_NOTE']."',CURRENT_TIMESTAMP)";
		return $this->DB()->Execute($sql);
    }
    
 function editCode($code_info)
    {
    	$sql = "UPDATE SYS_CODE SET CODE_TYPE = '".$code_info['CODE_TYPE']."',CODE_NOTE = '".$code_info['CODE_NOTE']."',CREATE_TIME =CURRENT_TIMESTAMP WHERE CODE_NAME= '".$code_info['CODE_NAME']."'";   	
    	return $this->DB()->Execute($sql);
    }
    
    
    function codeExists($code_name)
    {
    	if (empty($code_name))
    	{
    		return FALSE;
    	}
    	
    	$CDAOCode	= new CDAOCode();
        return $CDAOCode->Load(" CODE_NAME = '$code_name' ");
    }
    
    function deleteCode($code_name)
    {
    	if (empty($code_name))
    	{
    		return FALSE;
    	}
    	
    	return $this->DB()->Execute("delete from $this->_table where CODE_NAME = '$code_name' ");
    }
  
}

?>