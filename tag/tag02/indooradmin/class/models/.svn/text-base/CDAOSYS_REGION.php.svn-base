<?php 
class CDAOSYS_REGION extends CCDAOSYS_REGION
{
	var $_table = "SYS_REGION";
	var $_sys_region_seqence = "SEQ_REGION_ID";
	
    //构造函数
    function __construct()
    {
        parent::__construct();
    }
	/**
     * 新增数据
     *
     * @param unknown_type $data
     * @return unknown
     */
	function insertSYS_REGIONData($data){
		$this->setFrom($data);
		if ($this->Insert())
		return $this->DB()->Insert_id();
	}
	
	/**
     * 新增数据
     * @author yzb
     */
	function addRegionData($data){
		$this->doUpdate($data,$this->_sys_region_seqence);
		return $this->DB()->Insert_id();
	}
	
	/**
     * 修改父ID数据
     * @author yzb
     */
	function updateRegionData($wheresql,$parent_id){
		$sql="update {$this->_table} set PARENT_ID = {$parent_id} where {$wheresql}";
		SF::log($sql);
		return $this->DB()->Execute($sql);
	}
	
	/**
	 * 根据字段/值 键值对方式的条件查询所有区域<br />
	 * @return regions on success or false on failure
	 * @author huanggz
	 * @param array $_data
	 */
	public function getRegions($_data)
	{
		$where = self::genRegionsWhereSql($_data);
		$where .= ' ORDER BY REGION_ID';
		return $this->getAll($where);
	}

	/**
	 * 利用getRegions方法查询，并返回格式化的键（）值（）对数组
	 * @param unknown_type $_data
	 */
	public function findOptions($_data)
	{
		$result = self::getRegions($_data);
		$return = array();
		if (is_array($result))
		foreach ($result as $k=>$v)
		{
			$return[$v['REGION_ID']] = $v['REGION_NAME'];
		}
		return $return;
	}
	
	
	/**
	 * 根据父ID取子ID列表
	 * Enter description here ...
	 * @param unknown_type $_parent
	 */
	public function getRegionIDs($_parent)
	{
		if(!is_numeric($_parent)) return array();
		
		return $this->DB()->GetAll('select REGION_ID,REGION_NAME,REGION_GRADE from '.$this->_table.' a where a.PARENT_ID='.$_parent);
	}
	/**
	 * 根据父ID列表取子ID列表
	 * Enter description here ...
	 * @param unknown_type $_parent
	 */
	public function getRegionIDsByParents($_parents)
	{
		if(!is_array($_parents) || empty($_parents)) return array();
		$new_parents = array();
		foreach ($_parents as $k=>$v)
		{
			is_numeric($v) && ($new_parents[$v] = $v);
		}
		if(empty($new_parents)) return array();
		
		return $this->DB()->GetAll('select REGION_ID from '.$this->_table.' a where a.PARENT_ID IN ('.implode(',',$_parents).')');
	}
	
	/**
	 * 通过区域名称查询区域，
	 * @author huanggz
	 * @param string $_name Region_name
	 * @return array regions on success or false on failure
	 */
	public function getRegionByName($_name)
	{
		if(empty($_name)) return false;
		$where = " REGION_NAME LIKE '%".$_name."%' ORDER BY REGION_ID";
		return $this->getAll($where);
	}
	
	/**
	 * 生成条件查询语句
	 * @author huanggz
	 * @param array $_data
	 */
	public function genRegionsWhereSql($_data)
	{
		if (!is_array($_data)) return '';
		
		$where = " 1=1 ";
		$where .= isset($_data['REGION_GRADE']) && is_numeric($_data['REGION_GRADE'])?" AND REGION_GRADE=".$_data['REGION_GRADE']:"";
		$where .= isset($_data['PARENT_ID']) && is_numeric($_data['PARENT_ID'])?" AND PARENT_ID=".$_data['PARENT_ID']:"";
		$where .= isset($_data['REGION_ID']) && is_numeric($_data['REGION_ID'])?" AND REGION_ID=".$_data['REGION_ID']:"";
		$where .= !empty($_data['REGION_TYPE_NULL']) ? " AND REGION_TYPE IS NULL " : " ";
		$where .= !empty($_data['REGION_TYPE']) ? " AND REGION_TYPE ='".$_data['REGION_TYPE']."'" : " ";
		
		return $where;
	}
	
    function getPageRegionDataCount($condition = '')
    {
    	
    	$sql = "select count(*) as COUNTS from SYS_REGION";
    	if($condition){
       		$sql.=$condition;
       	}
    	$result = $this->DB()->Execute($sql);
    	return $result->_array[0]['COUNTS'];
    }
    
	function getPageRegionData($pageSize, $pageNum, $condition=''){
        $sql = "select * from SYS_REGION";
	    if($condition){
       		$sql.=$condition;
       	}
        $sql.= "  order by region_id desc";
        $region_res = $this->DB()->PageExecute($sql, $pageSize, $pageNum);
        return $region_res->_array;
    }
    
	function AddRegion($data){
		$data['REGION_ID'] = $this->DB()->nextId($this->_sys_region_seqence);
    	$this->setFrom($data);
		if ($this->Insert()) {
			return $data['REGION_ID'];	
		}
		return false;
    }
    
	function EditRegion($data){
        if ($data['REGION_ID']=='' || $data['REGION_NAME']=='' || $data['REGION_GRADE']==''){
    		return FALSE;
    	}
		$sql = "update SYS_REGION set REGION_NAME = '{$data['REGION_NAME']}',REGION_GRADE = {$data['REGION_GRADE']}";
		if($data['E_LONGITUDE']!=''){
			$sql .=",E_LONGITUDE = {$data['E_LONGITUDE']}";
		}
		if($data['E_LONGITUDE']==''){
			$sql .=",E_LONGITUDE = ''";
		}
		if($data['N_LATITUDE']!=''){
			$sql .=",N_LATITUDE = {$data['N_LATITUDE']}";
		}
		if($data['N_LATITUDE']==''){
			$sql .=",N_LATITUDE = ''";
		}
		if($data['W_LONGITUDE']!=''){
			$sql .=",W_LONGITUDE = {$data['W_LONGITUDE']}";
		}
		if($data['W_LONGITUDE']==''){
			$sql .=",W_LONGITUDE = ''";
		}
		if($data['S_LATITUDE']!=''){
			$sql .=",S_LATITUDE = {$data['S_LATITUDE']}";
		}
		if($data['S_LATITUDE']==''){
			$sql .=",S_LATITUDE = ''";
		}
		if($data['LONGITUDE']!=''){
			$sql .=",LONGITUDE = {$data['LONGITUDE']}";
		}
		if($data['LONGITUDE']==''){
			$sql .=",LONGITUDE = ''";
		}
		if($data['LATITUDE']!=''){
			$sql .=",LATITUDE = {$data['LATITUDE']}";
		}
		if($data['LATITUDE']==''){
			$sql .=",LATITUDE = ''";
		}
		if($data['ACREAGE']!=''){
			$sql .=",ACREAGE = {$data['ACREAGE']}";
		}
		if($data['ACREAGE']==''){
			$sql .=",ACREAGE = ''";
		}
    	$sql .=" where REGION_ID ={$data['REGION_ID']}";
    	
    	if($this->DB()->Execute($sql)){
    		return TRUE;
    	}
    	else{
    		return FALSE;
    	} 
	}
	
	/**
	 * 根据id获取区域信息
	 * @author huanggz
	 * @param unknown_type $_region_id 区域id
	 */
	public function getRegionById($_region_id)
	{
		if (!is_numeric($_region_id)){return array();}
		$where = " REGION_ID=".$_region_id;
		$result = $this->getInfo($where);
		return empty($result)?array():$result;
	}

	/**
	 * 根据区域名称查询区域信息
	 * @param unknown_type $_name
	 */
	public function findByName($_name)
	{
		if (empty($_name)) return array();
		$where = " region_name='".$_name."'";
		$rs = $this->getInfo($where);
		return empty($rs)?array():$rs;
	}
	
	/*
	 * @author:duhw
	 * 通过父标识得到下一级的行政区域列表信息(例如通过中国的标识,得到中国所有的省份信息)
	 * $id:区域标识
	 */
	public function getRegionListByParentId($id){
		$id = $id?intval($id):0;
		if($id == 0)
		return;
		$sql = "SELECT * FROM SYS_REGION WHERE PARENT_ID=$id AND REGION_TYPE='N'";
		
		$result = $this->DB()->getAll($sql);
		return $result;
	}
	/*
	 * @author:duhw
	 * 通过区域的级别找到对应的行政区域
	 * $grade:级别标识
	 */
	public function getRegionListByGrade($grade){
		if($grade == null || $grade == '')
		return;
		$grade = intval($grade);
		$sql = "SELECT * FROM SYS_REGION WHERE REGION_GRADE=$grade AND REGION_TYPE='N'";
		$result = $this->DB()->getAll($sql);
		return $result;
		
	}
	/*
	 * @author:duhw
	 * 通过区域标识得到当前级别的同级区域列表(例如:通过广州市得到广州市所在的省下所有城市的信息)
	 * $regionid:区域标识
	 */
	public function getRegionListById($regionid){
		$regionid = $regionid?intval($regionid):0;
		if($regionid == 0)
		return;
		$sql = "SELECT A.* FROM SYS_REGION A
		        INNER JOIN SYS_REGION B ON A.PARENT_ID=B.PARENT_ID AND B.REGION_ID = $regionid 
		        AND A.REGION_TYPE = B.REGION_TYPE";
		$result = $this->DB()->getAll($sql);
		return $result;
		
		
	}
	
	/**
	 * 更新区域信息
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	function UpdateRegionByData($data){
        if ($data['REGION_ID']=='' || $data['REGION_NAME']==''){
    		return FALSE;
    	}
		$sql = "update SYS_REGION set REGION_NAME = '{$data['REGION_NAME']}' ";
    	$sql .=" where REGION_ID ={$data['REGION_ID']}";
    	if($this->DB()->Execute($sql)){
    		return TRUE;
    	}
    	else{
    		return FALSE;
    	} 
	}
      /**
      *@author:duhw
      *通过区标识得到所有区的父级关系
      */
     function getAllParentInfo($district_id){
       $district_id = $district_id?intval($district_id):0;
      if($district_id == 0)
        return false;
      $sql = "select a.region_id continent_id,a.region_name continent_name,
       b.region_id country_id,b.region_name country_name,
       c.region_id prov_id,c.region_name prov_name,
       d.region_id city_id,d.region_name city_name,
       e.region_id district_id,e.region_name district_name from sys_region a 
       inner join sys_region b on a.region_id=b.parent_id 
        inner join sys_region c on b.region_id=c.parent_id 
         inner join sys_region d on c.region_id=d.parent_id 
          inner join sys_region e on d.region_id=e.parent_id  and e.region_id=$district_id";
     $result = $this->DB()->getAll($sql);
    if($result)
       return $result[0];
   else
        return false;




    }
     

}

?>
