<?php
/**
 * \brief all data models's parent class, privite base functions
 * @author Administrator
 *
 */
class CActiveRecord extends ADODB_Active_Record
{
	var $_table = '';
	
	/**
	 * \brief construct a ative record object
	 * @param string $table
	 */
	public function __construct($table = false)
	{
		$this->_table = $table ? $table : $this->_table;
		parent::__construct($this->_table);
	}
	
	
	/**
	 * \brief translate the ative record object to an array
	 */
	public function toArray()
	{
		$dataArray = array();
		foreach ($this->GetAttributeNames() as $value)
		{
			$dataArray[$value] = $this->$value;
		}
		
		return $dataArray;
	}

	
	/**
	 * \brief set the array to the ative record attributes
	 * @param array $dataArray
	 */
	public function setFrom($dataArray)
	{
		if (empty($dataArray) || !is_array($dataArray))
		{
			return false;
		}
		
		foreach ($this->GetAttributeNames() as $value)
		{
			if (key_exists($value, $dataArray))
			{
				$this->$value = $dataArray[$value];
			}
		}
	}
	
	   
	/**
	 * \brief insert a record
	 * @param array $data, record data, the array keys must the table column name.
	 * @example array('column_name1' => value1);
	 * @param string $sequence, prive key data, only need in oracle. 
	 */
	public function doInsert($data, $sequence=true)
	{
		$this->setFrom($data);
		
		// get the prive key from sequence when the db is oracle
		if ($sequence)
		{
			$primaryKey 		= $this->getThePrimaryKey();
			$this->$primaryKey	= $this->DB()->nextId($sequence);
			return $this->Insert() ? $this->$primaryKey : FALSE;
		}
		
		return $this->Insert();
	}
	
	
	/**
	 * \brief update a record
	 * @param string $whereSql, the where sql that identity the record
	 * @param array $data, the data that will be updated
	 */
	public function doUpdate($whereSql, $data)
	{
		if ($whereSql && $data && $this->Load($whereSql))
		{
			$this->setFrom($data);
			return $this->Update();
		}
	}
	
	
	/**
	 * \brief delete a record
	 * @param string $whereSql, the where sql that identity the record
	 */
	public function doDelete($whereSql)
	{
		if ($whereSql && $this->Load($whereSql))
		{
			return $this->Delete();
		}
	}
	
	
	/**
	 * \brief get a record data
	 * @param string $whereSql, the where sql that identity the record
	 */
	public function getInfo($whereSql)
	{
		if ($whereSql && $this->Load($whereSql))
		{
			return $this->toArray();
		}
	}
	
	
	/**
	 * \brief get all records contain the condition where sql
	 * @param string $whereSql, the where sql that identity the record
	 */
	public function getAll($whereSql=false)
	{
		$sql = " select * from {$this->_table} ";
		if ($whereSql)
		{
			$sql .= " where $whereSql ";
		}
		return $this->DB()->GetAll($sql);
	}
	
	
	/**
	 * \brief count how many record
	 * @param string $whereSql, the where sql
	 */
	public function count($whereSql=FALSE)
	{
		$sql = " select count(*) as counts from {$this->_table} ";
		if ($whereSql)
		{
			$sql .= " where $whereSql ";
		}
		return $this->DB()->GetOne($sql);
	}
	
	
	/**
	 * \brief get one page data
	 * @param int $pageSize, the count per page
	 * @param int $pageNum, the number of the page
	 * @param string $whereSql, the condition where sql
	 * @param int $secs2cache, the time of cache.
	 */
	public function getPageData($pageSize, $pageNum, $whereSql=FALSE, $secs2cache=0)
	{
		$sql = " select * from {$this->_table} ";
		if ($whereSql)
		{
			$sql .= " where $whereSql ";
		}
		$result = $this->DB()->PageExecute($sql, $pageSize, $pageNum, false, $secs2cache);
		return $result->_array;
	}
	
	
	/**
	 * \brief get the table primary key
	 */
	public function getThePrimaryKey()
	{
		$primaryKey = $this->DB()->MetaPrimaryKeys($this->_table);
		if (count($primaryKey) == 1)
			return $primaryKey[0];
		else
			throw new CException(SF::t('SF', "{$this->_table}'s primaryKey is null or not unique."));
	}
	
	
	/**
	 * \brief get the primary key value
	 */
	public function getPrimaryKeyValue()
	{
		$primaryKey = $this->getThePrimaryKey();
		return $this->$primaryKey;
	}
	
	
	/**
	 * \brief load the model by pk
	 */
	public function loadModelByPk($id)
	{
		if ($id)
		{
			$whereSql = $this->getThePrimaryKey().'='.$id;
			return $this->Load($whereSql);
		}
		else
		  throw new CException(SF::t('SF', "the input id is not validate"));
	}
}

?>