<?php
/**
 * CNumberValidator class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 SF Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CNumberValidator validates that the attribute value is a number.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CNumberValidator.php,v 1.2 2010/03/31 08:32:39 liujz Exp $
 * @package system.validators
 * @since 1.0
 */
class CNumberValidator extends CValidator
{
	/**
	 * @var boolean whether the attribute value can only be an integer. Defaults to false.
	 */
	public $integerOnly=false;
	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;
	/**
	 * @var integer|double upper limit of the number. Defaults to null, meaning no upper limit.
	 */
	public $max;
	/**
	 * @var integer|double lower limit of the number. Defaults to null, meaning no lower limit.
	 */
	public $min;
	/**
	 * @var string user-defined error message used when the value is too big.
	 */
	public $tooBig;
	/**
	 * @var string user-defined error message used when the value is too small.
	 */
	public $tooSmall;


	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel the object being validated
	 * @param string the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$value=$object->$attribute;
		if($this->allowEmpty && $this->isEmpty($value))
			return;
		if($this->integerOnly)
		{
			if(!preg_match('/^\s*[+-]?\d+\s*$/',"$value"))
			{
				$message=$this->message!==null?$this->message:SF::t('SF','{attribute} must be an integer.');
				$this->addError($object,$attribute,$message);
			}
		}
		else
		{
			if(!preg_match('/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/',"$value"))
			{
				$message=$this->message!==null?$this->message:SF::t('SF','{attribute} must be a number.');
				$this->addError($object,$attribute,$message);
			}
		}
		if($this->min!==null && $value<$this->min)
		{
			$message=$this->tooSmall!==null?$this->tooSmall:SF::t('SF','{attribute} is too small (minimum is {min}).');
			$this->addError($object,$attribute,$message,array('{min}'=>$this->min));
		}
		if($this->max!==null && $value>$this->max)
		{
			$message=$this->tooBig!==null?$this->tooBig:SF::t('SF','{attribute} is too big (maximum is {max}).');
			$this->addError($object,$attribute,$message,array('{max}'=>$this->max));
		}
	}
}