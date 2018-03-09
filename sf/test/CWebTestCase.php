<?php
/**
 * This file contains the CWebTestCase class.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 SF Software LLC
 * @license http://www.yiiframework.com/license/
 */

SF::import('system.library.*');
require_once('PHPUnit/Extensions/SeleniumTestCase.php');

/**
 * CWebTestCase is the base class for Web-based functional test case classes.
 *
 * It extends PHPUnit_Extensions_SeleniumTestCase and provides the database
 * fixture management feature like {@link CDbTestCase}.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CWebTestCase.php,v 1.1 2010/03/25 06:20:13 liujz Exp $
 * @package system.test
 * @since 1.1
 */
abstract class CWebTestCase extends PHPUnit_Extensions_SeleniumTestCase
{
	/**
	 * @var array a list of fixtures that should be loaded before each test method executes.
	 * The array keys are fixture names, and the array values are either AR class names
	 * or table names. If table names, they must begin with a colon character (e.g. 'Post'
	 * means an AR class, while ':Post' means a table name).
	 * Defaults to false, meaning fixtures will not be used at all.
	 */
	protected $fixtures=false;

	/**
	 * PHP magic method.
	 * This method is overridden so that named fixture data can be accessed like a normal property.
	 * @param string the property name
	 * @return mixed the property value
	 */
	public function __get($name)
	{
		if(is_array($this->fixtures) && ($rows=$this->getFixtureManager()->getRows($name))!==false)
			return $rows;
		else
			throw new Exception("Unknown property '$name' for class '".get_class($this)."'.");
	}

	/**
	 * PHP magic method.
	 * This method is overridden so that named fixture ActiveRecord instances can be accessed in terms of a method call.
	 * @param string method name
	 * @param string method parameters
	 * @return mixed the property value
	 */
	public function __call($name,$params)
	{
		if(is_array($this->fixtures) && isset($params[0]) && ($record=$this->getFixtureManager()->getRecord($name,$params[0]))!==false)
			return $record;
		else
			return parent::__call($name,$params);
	}

	/**
	 * @return CDbFixtureManager the database fixture manager
	 */
	public function getFixtureManager()
	{
		return SF::app()->getComponent('fixture');
	}

	/**
	 * @param string the fixture name (the key value in {@link fixtures}).
	 * @return array the named fixture data
	 */
	public function getFixtureData($name)
	{
		return $this->getFixtureManager()->getRows($name);
	}

	/**
	 * @param string the fixture name (the key value in {@link fixtures}).
	 * @param string the alias of the fixture data row
	 * @return CActiveRecord the ActiveRecord instance corresponding to the specified alias in the named fixture.
	 * False is returned if there is no such fixture or the record cannot be found.
	 */
	public function getFixtureRecord($name,$alias)
	{
		return $this->getFixtureManager()->getRecord($name,$alias);
	}

	/**
	 * Sets up the fixture before executing a test method.
	 * If you override this method, make sure the parent implementation is invoked.
	 * Otherwise, the database fixtures will not be managed properly.
	 */
	protected function setUp()
	{
		parent::setUp();
		if(is_array($this->fixtures))
			$this->getFixtureManager()->load($this->fixtures);
	}
}
