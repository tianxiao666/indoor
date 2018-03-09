<?php
/**
 * Manager cache,as an interface in this project to the sf framework
 *
 */

class CCacheManager
{
	/**
	 * @var object, store the cache object
	 */
	private $oCache;	
	
	const RECORD_CNT = 20;
	
	
	/**
	 * CCacheManager constructor
	 *
	 * @param string $keyPrefix, to use to create the unique id
	 */
	public function __construct($keyPrefix = '')
	{
		if (empty($keyPrefix))
		{
			$this->oCache 			 = SF::app()->cache;
		}
		else 
		{
			$this->oCache 			 = clone (SF::app()->cache);
			$this->oCache->keyPrefix = $keyPrefix;
		}
	}
	
	
	/**
	 * get the cache by id
	 *
	 * @param string $id
	 */
	public function get($id)
	{
		return $this->oCache->get($id);
	}
	
	
	/**
	 * set cache data
	 *
	 * @param string $id
	 * @param mixed $value
	 * @param fload $expire, as second
	 */
	public function set($id, $value, $expire=0)
	{
		$this->oCache->set($id, $value, $expire);
	}
	
	
	/**
	 * delete cache data by id
	 *
	 * @param string $id
	 */
	public function delete($id)
	{
		$this->oCache->delete($id);
	}
	
	
	/**
	 * Deletes all values from cache
	 */
	public function flush()
	{
		$this->oCache->flush();
	}
	
	
	/**
	 * Store table area datas,this data is used to insert db
	 *
	 * @param string $tableName
	 * @param array $data
	 */
	public function addTableDynamic($tableName, $data)
	{
		// get cache data
		$record	= array();
		$cache	= $this->get($tableName);
		if ($cache)
		{
			$record = $cache;
		}
		
		// set cache data
		array_push($record, $data);
		return $this->set($tableName, $record);
	}
	
	
	/**
	 * Store user datas, this data is used to show in the page
	 *
	 * @param string $subs_id
	 * @param array $data
	 */
	public function addUserDynamic($subs_id, $data)
	{
		// get cache data
		$record	= array();
		$cache	= $this->get($subs_id);
		if ($cache)
		{
			$record = $cache;
		}
		
		// set cache data
		array_unshift($record, $data);
		if (count($record) > self::RECORD_CNT)
		{
			array_pop($record);
		}
		return $this->set($subs_id, $record);
	}
}
?>