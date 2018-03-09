<?php
/**
 * @author tangqian
 */


//class CDAOSYS_OPTION_CODE extends CActiveRecord
class CDAOSYS_OPTION_CODE extends CCDAOSYS_OPTION_CODE 
{
	var $_table = "SYS_OPTION_CODE";
	var $_cs_option_sequence = "SEQ_SYS_OPTION_CODE_ID";
	
    //构造函数
    function __construct()
    {
        parent::__construct();
    }
    
    
    function getCodePageData($condition=array(),$pageSize = 10, $pageNum = 1)
    {
    	$sql 	= " SELECT * FROM $this->_table WHERE 1=1 ";
    	if ($condition['CODE_TYPE']) {
    		$code_type = TextFilter::encodeQuote($condition['CODE_TYPE']);
    		$sql .= " AND CODE_TYPE = '$code_type' ";
    	}
    	$sql	.= " ORDER BY WEIGHT DESC";

    	$result = $this->DB()->PageExecute($sql, $pageSize, $pageNum);
        return $result->_array;
    }
    
    
    function getCodeCount($condition=array())
    {
    	$sql 	= " SELECT COUNT(*) AS COUNTS FROM  $this->_table WHERE 1=1 ";
    	if ($condition['CODE_TYPE']) {
    		$code_type = TextFilter::encodeQuote($condition['CODE_TYPE']);
    		$sql .= " AND CODE_TYPE = '$code_type' ";
    	}
    	$result = $this->DB()->Execute($sql);
    	return $result->_array[0]['COUNTS'];
    }
    
    function getAllData(){
    	$sql = "SELECT * FROM {$this->_table}";
    	return $this->DB()->getAll($sql);
    }
    
    
     /**
	 * 
	 */
	function addCode($modelData)
	{
		$modelData['CREATE_TIME'] = date('Y-m-d H:i:s');
		$modelData['OPTION_CODE_ID']  = $this->DB()->nextId($this->_cs_option_sequence);
		SF::log($modelData);
		$this->setFrom($modelData);
		if($this->Save()){
			return true;
		}
		return false;
	}
    
	/**
	 * 新增数据字典
	 * @author yzb
	 */
	function insertCode($data){
		$data['CREATE_TIME'] = date('Y-m-d H:i:s');
		$data['OPTION_CODE_ID'] = $this->DB()->nextId($this->_cs_option_sequence);
		SF::log($data);
    	$sql = "INSERT INTO SYS_OPTION_CODE(OPTION_VALUE,OPTION_NAME,CODE_TYPE,OPTION_NOTE,CREATE_TIME,PARENT_OPTION_VALUE,WEIGHT,OPTION_CODE_ID,PARENT_TYPE_VALUE) VALUES(
    	'".$data['OPTION_VALUE']."',
    	'".$data['OPTION_NAME']."',
    	'".$data['CODE_TYPE']."',
    	'".$data['OPTION_NOTE']."',
    	'".$data['CREATE_TIME']."',
    	'".$data['PARENT_OPTION_VALUE']."',
    	'".$data['WEIGHT']."',
    	'".$data['OPTION_CODE_ID']."',
    	'".$data['PARENT_TYPE_VALUE']."')";
		return $this->DB()->Execute($sql);
    }
	
    
    function editCode($modelData)
    {
//    	$code_type		= TextFilter::encodeQuote($modelData['CODE_TYPE']);
//    	$option_value	= TextFilter::encodeQuote($modelData['OPTION_VALUE']);
        $option_code_id = $modelData['OPTION_CODE_ID'];
//    	if ($code_type && $option_value && $this->Load(" OPTION_VALUE = '$option_value' AND CODE_TYPE = '$code_type' ")) {
         if($option_code_id && $this->Load(" OPTION_CODE_ID ='$option_code_id'")){
    		$this->setFrom($modelData);
			if($this->Save()){
				return true;
			}
    	}
		return false;
    }
    
    
    function codeExists($option_value,$option_type)
    {
    	if (empty($option_value) || empty($option_type))
    	{
    		return FALSE;
    	}
    	$option_value	= TextFilter::encodeQuote($option_value);
    	$option_type	= TextFilter::encodeQuote($option_type);
    	if ($this->Load(" OPTION_VALUE = '$option_value' AND CODE_TYPE = '$option_type' ")) {
    		return $this->toArray();
    	}
    	return false;
    }
    
    
    function codeIdExists($code_id)
    {
    	if(empty($code_id))
    	{
    		return false;
    	}
    	if ($this->Load(" OPTION_CODE_ID = '$code_id'")) {
    		return $this->toArray();
    	}
    	return false;
    }
    
    function deleteCode($option_code_id)
    {
//    	$code_type		= TextFilter::encodeQuote($modelData['CODE_TYPE']);
//    	$option_value	= TextFilter::encodeQuote($modelData['OPTION_VALUE']);
//    	if ($code_type && $option_value && $this->Load(" OPTION_VALUE = '$option_value' AND CODE_TYPE = '$code_type' ")) {
        if($option_code_id && $this->Load(" OPTION_CODE_ID = '$option_code_id'")){
    		return $this->Delete();
    	}
    	return false;
    }
    
    function getOptionByCode($codeType)
    {
    	$codeType		= TextFilter::encodeQuote(trim($codeType));
    	if ($codeType) {
    		return $this->getAll(" CODE_TYPE = '$codeType' ORDER BY WEIGHT DESC ");
    	}
    	return array();
    }
  
    function findByCodeName($code_type)
    {
    	$sql = " SELECT TY.CODE_NAME FROM SYS_OPTION_CODE OP,SYS_TYPE_CODE TY WHERE OP.CODE_TYPE = TY.CODE_TYPE  AND OP.OPTION_VALUE='$code_type'";
    	
    	return $this->DB()->getOne($sql);
    }
    
    function findByCodeType($code_type)
    {
    	$sql = " SELECT OPTION_NAME FROM SYS_OPTION_CODE WHERE OPTION_VALUE = '$code_type'";
    	return $this->DB()->getOne($sql);
    }
    /**
     * 根据分类编码和选项编码查询该选项下的数据
     *
     * @param  $option_value  选项编码
     * @param  $code_type 分类编码
     * @return array
     */
    function findTypeNameByValue($option_value,$code_type){
//    	$sql    = "SELECT OPTION_NAME FROM {$this->_table} WHERE OPTION_VALUE='{$option_value}' AND CODE_TYPE='{$code_type}'";
//		$result = $this->DB()->getAll($sql);
    	$sql    = "SELECT OPTION_NAME FROM {$this->_table} WHERE OPTION_VALUE=:option_value AND CODE_TYPE=:code_type";
$result = $this->DB()->getAll($sql,array('option_value' => $option_value,'code_type' => $code_type));
		return  $result[0];
    }
       
	function findTypeByCode($type="",$parent="")
	{
		$sql = "  SELECT * FROM SYS_OPTION_CODE WHERE 1=1";
	
		if($parent == 'PARENT')
		{
			$sql.="  AND PARENT_OPTION_VALUE = '$type'";
			
		}else{
			if(!empty($type))
			{
			   $sql.=" AND CODE_TYPE = '$type'";
			}
		}
		$sql.="  ORDER BY WEIGHT DESC,nvl(length(trim(OPTION_NAME)),0) ASC";
//		$result = $this->DB()->getAll($sql);
//		return $result;
	    return $result = $this->DB ()->CacheExecute (24*3600, $sql )->_array;
	}
	
	/**
	 * 根据CODE_ID查找子选项
	 * @param OPTION_CODE_ID $_id
	 * @param CODE_TYPE $CODE_TYPE
	 */
	public function findByCodeID($_id,$CODE_TYPE)
	{
		if (!is_numeric($_id) || $_id<1) return array();
		$SQL = "SELECT * 
		FROM ".$this->_table." A
		WHERE  A.PARENT_OPTION_VALUE = (
			SELECT OPTION_VALUE FROM ".$this->_table." B WHERE B.OPTION_CODE_ID=".$_id."
		)
		AND CODE_TYPE = '".$CODE_TYPE."'
		ORDER BY WEIGHT
		";
		
		$result = $this->DB ()->CacheExecute (24*3600, $SQL );
		return empty($result->_array)?array():$result->_array;
	}
	public function getfindByOptionValue($option_val,$code_type)
	{
		if($option_val && $code_type)
		{
			
			$sql = "select OPTION_NAME,OPTION_VALUE from {$this->_table} where OPTION_VALUE in ( '".implode('\',\'',$option_val)."') and CODE_TYPE='{$code_type}'";
			return $this->DB()->getAll($sql);
		}
	}
	/**
	 * 根据option_value查找记录
	 */
	public function findByOptionValue($_option_value, $_code_type)
	{
		if (empty($_option_value) || empty($_code_type))
		{ return array();
		}
		
		$where = " OPTION_VALUE='".$_option_value."' and CODE_TYPE='".$_code_type."' ";
		
		$result = $this->getInfo($where );
		return empty($result)?array():$result;
	}
	/**
	 * 根据option_value查找记录
	 */
	public function findByOptionName($_option_name, $_code_type)
	{
		if (empty($_option_name) || empty($_code_type)) return array();
		
		$where = " OPTION_NAME='".$_option_name."' and CODE_TYPE='".$_code_type."' ";
		
		$result = $this->getInfo($where );
		return empty($result)?array():$result;
	}
	
	/**
	 * 根据查询id主键列表查询字典
	 * @param array $option_code_ids
	 * @return array 字典列表
	 * @author huanggz
	 */
	public function fndByOptionCodeIds($option_code_ids)
	{
		if (!is_array($option_code_ids) || empty($option_code_ids)) return array();
		$n_ids = array();
		//剔除非数字型
		foreach ($option_code_ids as $k=>$v)
		{
			is_numeric($v) && ($n_ids[$v] = $v);
		}
		if (empty($n_ids)) {
			return array();
		}
		//查询条件
		$where = " OPTION_CODE_ID IN (".implode(',', $n_ids).")) ";
		$result = $this->getAll($where);
		return empty($result)?array():$result;
	}
	
	/**
	 * 根据查询id主键列表查询字典
	 * @param array $option_code_id
	 * @return array 字典数据
	 * @author huanggz
	 */
	public function fndByOptionCodeId($option_code_id)
	{
		if (!is_int($option_code_id)) return array();
		
		//查询条件
		$where = " OPTION_CODE_ID =".$option_code_id." ";
		$result = $this->getInfo($where);
		return empty($result)?array():$result;
	}
	
	
	/**
	 * 获取数据字典编码，以键['OPTION_VALUE']值['OPTION_NAME']对数组返回
	 * @author lisc
	 * @param unknown_type $codeType
	 * @return array(OPTION_VALUE,OPTION_NAME)
	 */
	public function getOptionCodeKeyValues($codeType=""){
		$options = self::getOptionByCode($codeType);
		if ($options)
		{
			$new = array();
			foreach ($options as $k=>$v)
			{
				$new[$v['OPTION_VALUE']]=$v['OPTION_NAME'];
			}
			$options=$new;
		}
		return $options;
	}
	
	/**
	 * 获取数据字典编码，以键['OPTION_CODE_ID']值['OPTION_NAME']对数组返回
	 * @author lisc
	 * @param unknown_type $codeType
	 * @return array(OPTION_CODE_ID,OPTION_NAME)
	 */
	public function getOptionCodeKeyId($codeType=""){
		$options = self::getOptionByCode($codeType);
		if ($options)
		{
			$new = array();
			foreach ($options as $k=>$v)
			{
			//     $new[$v['OPTION_VALUE']] = $v['OPTION_NAME'];
                        	$new[$v['OPTION_CODE_ID']]=$v['OPTION_NAME'];
			}
			$options=$new;
		}
		return $options;
	}
	
	public function getOptionByValue($codeType = '' , $val = ''){
		if(!$codeType && !$val) return false;
		$sql = "SELECT * FROM {$this->_table} WHERE CODE_TYPE = '{$codeType}' AND OPTION_VALUE LIKE '%{$val}%'";
		$result = $this->DB()->Execute($sql);
		return $result->_array[0];
	}
}

?>
