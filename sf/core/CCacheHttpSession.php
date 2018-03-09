<?php
/**
 * CCacheHttpSession class
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.SFframework.com/
 * @copyright Copyright &copy; 2008-2009 SF Software LLC
 * @license http://www.SFframework.com/license/
 */


/**
 * CCacheHttpSession implements a session component using cache as storage medium.
 *
 * The cache being used can be any cache application component implementing {@link ICache} interface.
 * The ID of the cache application component is specified via {@link cacheID}, which defaults to 'cache'.
 *
 * Beware, by definition cache storage are volatile, which means the data stored on them
 * may be swapped out and get lost. Therefore, you must make sure the cache used by this component
 * is NOT volatile. If you want to use {@link CDbCache} as storage medium, use {@link CDbHttpSession}
 * is a better choice.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CCacheHttpSession.php,v 1.2 2009/08/10 10:46:43 liujz Exp $
 * @package system.web
 * @since 1.0
 */
class CCacheHttpSession extends CHttpSession
{
	/**
	 * Prefix to the keys for storing cached data
	 */
	const CACHE_KEY_PREFIX='SF.CCacheHttpSession.';
	/**
	 * @var string the ID of the cache application component. Defaults to 'cache' (the primary cache application component.)
	 */
	public $cacheID='cache';

	/**
	 * @var ICache the cache component
	 */
	private $_cache;

	/**
	 * Initializes the application component.
	 * This method overrides the parent implementation by checking if cache is available.
	 */
	public function init()
	{		
//		parent::init();
		$this->_cache=SF::app()->getComponent($this->cacheID);		
		if(!($this->_cache instanceof ICache))
			throw new CException(SF::t('SF','CCacheHttpSession.cacheID is invalid. Please make sure "{id}" refers to a valid cache application component.',
				array('{id}'=>$this->cacheID)));
		
		// put the parent init here because the session start function will call readSession function, liujz
		parent::init();
	}

	/**
	 * Returns a value indicating whether to use custom session storage.
	 * This method overrides the parent implementation and always returns true.
	 * @return boolean whether to use custom storage.
	 */
	public function getUseCustomStorage()
	{
		return true;
	}

	/**
	 * Session read handler.
	 * Do not call this method directly.
	 * @param string session ID
	 * @return string the session data
	 */
	public function readSession($id)
	{
		$data=$this->_cache->get($this->calculateKey($id));
		return $data===false?'':$data;
	}

	/**
	 * Session write handler.
	 * Do not call this method directly.
	 * @param string session ID
	 * @param string session data
	 * @return boolean whether session write is successful
	 */
	public function writeSession($id,$data)
	{
		return $this->_cache->set($this->calculateKey($id),$data,$this->getTimeout());
	}

	/**
	 * Session destroy handler.
	 * Do not call this method directly.
	 * @param string session ID
	 * @return boolean whether session is destroyed successfully
	 */
	public function destroySession($id)
	{
	    return $this->_cache->delete($this->calculateKey($id));
	}

	/**
	 * Generates a unique key used for storing session data in cache.
	 * @param string session variable name
	 * @return string a safe cache key associated with the session variable name
	 */
	protected function calculateKey($id)
	{
	    return self::CACHE_KEY_PREFIX.$id;
	}
}
