<?php
/**
 * CSafeValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 SF Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CSafeValidator marks the associated attributes to be safe for massive assignments.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CSafeValidator.php,v 1.1 2010/03/31 08:35:24 liujz Exp $
 * @package system.validators
 * @since 1.1
 */
class CSafeValidator extends CValidator
{
	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel the object being validated
	 * @param string the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
	}
}

