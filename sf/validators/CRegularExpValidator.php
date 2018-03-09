<?php
/**
 * CRegularExpressionValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.SFframework.com/
 * @copyright Copyright &copy; 2008-2009 SF Software LLC
 * @license http://www.SFframework.com/license/
 */

/**
 * CRegularExpressionValidator validates that the attribute value matches to the specified {@link pattern regular expression}.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CRegularExpValidator.php,v 1.2 2010/03/31 08:32:39 liujz Exp $
 * @package system.validators
 * @since 1.0
 */
class CRegularExpValidator extends CValidator
{
	/**
	 * @var string the regular expression to be matched with
	 */
	public $pattern;
	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel the object being validated
	 * @param string the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$value=$object->$attribute;
		if($this->allowEmpty && ($value===null || $value===''))
			return;
		if($this->pattern===null)
			throw new CException(SF::t('SF','The "pattern" property must be specified with a valid regular expression.'));
		if(!preg_match($this->pattern,$value))
		{
			$message=$this->message!==null?$this->message:SF::t('SF','{attribute} is invalid.');
			$this->addError($object,$attribute,$message);
		}
	}
}
