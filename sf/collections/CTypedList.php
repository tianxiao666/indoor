<?php
/**
 * This file contains CTypedList class.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.SFframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.SFframework.com/license/
 */

/**
 * CTypedList represents a list whose items are of the certain type.
 *
 * CTypedList extends {@link CList} by making sure that the elements to be
 * added to the list is of certain class type.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CTypedList.php,v 1.1.1.1 2009/05/13 06:26:42 haka Exp $
 * @package system.collections
 * @since 1.0
 */
class CTypedList extends CList
{
	private $_type;

	/**
	 * Constructor.
	 * @param string class type
	 */
	public function __construct($type)
	{
		$this->_type=$type;
	}

	/**
	 * Inserts an item at the specified position.
	 * This method overrides the parent implementation by
	 * checking the item to be inserted is of certain type.
	 * @param integer the specified position.
	 * @param mixed new item
	 * @throws CException If the index specified exceeds the bound,
	 * the list is read-only or the element is not of the expected type.
	 */
	public function insertAt($index,$item)
	{
		if($item instanceof $this->_type)
			parent::insertAt($index,$item);
		else
			throw new CException(SF::t('SF','CTypedList<{type}> can only hold objects of {type} class.',
				array('{type}'=>$this->_type)));
	}
}
