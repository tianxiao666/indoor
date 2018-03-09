<?php
/**
 * This extension allows client side form validation. It is based on the JQuery plugin validation.
 *  
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * @author Raoul
 * @version 1.0.9
 * 
 */

class CJfValidate extends CFormModel 
{
	const  CUSTOM_JS_VALIDATOR = 'CustomJsValidator';
	
	// @var boolean enable/disable the JS form validation. 
	public $enable = true;
	
	// @var array list of supported built-in validators.
	private $_supportedValidators = array (
		self::CUSTOM_JS_VALIDATOR,
		'required',
		'length',
		'email',
		'url',
		'compare',
		'numerical',
		'match'
	);
	
	// @var array JQuery alidation plugin initialisation options that can be set with method setOptions
	private $_defaultOptions = array();
	private $_defaultMessage = array();
	
	private $jQrules;
	private $jQmessages;
	private $jQoptions;
	private $model;			// @var object of the form model.
	
	
	/**
	 * Constructor.
	 * @param object of formModel.
	 */
	public function __construct(&$model)
	{
		$model 		 = is_object($model) ? $model : new stdClass();
		$this->model = &$model;
		$this->setScenario($model->formId);
		$this->init();
		$this->attachBehaviors($this->behaviors());
	}
	

	public function init()
	{
		if( $this->enable === true)
		{		
			$this->_defaultMessage = array(
				'minlength' 	=> '{attribute} is too small (minimum is {min}).',
				'maxlength' 	=> '{attribute} is too big (maximum is {max}).',
				'required' 		=> '{attribute} cannot be blank.',
				'email' 		=> '{attribute} is not a valid email address.',
				'equalTo' 		=> '{attribute} must be repeated exactly.',
				'tooBig' 		=> '{attribute} is too big (maximum is {max}).',
				'tooSmall' 		=> '{attribute} is too small (minimum is {min}).',
				'notInt' 		=> '{attribute} must be an integer.',
				'numerical' 	=> '{attribute} must be a number.',
				'match'			=> '{attribute} is invalid.',
				'equalToConst'	=> '{attribute} must be {value}.'
			);
		}
	}
	
	
	/**
	 * \rewrite the CComponet function to pass the value
	 */
	function __set($name, $value)
	{
		return $this->$name = $value;
	}
	
	
	/**
	 * \rewrite the CComponet function to pass the value
	 */
	function __get($name)
	{
		return $this->$name;
	}
	
	/**
	 * Options set here will overload options that have been set when the extension was loaded (configuration file).
	 * @param array opt JQuery Validate Plugin options
	 * example, for more see jQuery validate:
	 * 1.array('errorElement'=>'span', 'errorClass'=>'invalid')
	 * 2.array('errorContainer'=>'div.errorSummary', 'wrapper'=>'li', 
	 *   'errorLabelContainer'=>'div.errorSummary ul', 'errorClass'=>'invalid')
	 */
	public function setOptions($opt)
	{
		if( count($opt))
			$this->_defaultOptions = $opt;		
	}
	
	
	public function isEnabled()
	{
		return $this->enable;
	}

	
	/**
	 * \brief the for the sf validate
	 */
	public function rules()
	{
		return $this->model->rules;
	}
	
	
	public function validate($data)
	{
		if (is_array($data))
		{
			foreach ($data as $key => $value)
			{
				$this->$key = $value;
			}	
			$result = parent::validate();
			
			// log the errors
			$errorMsg = $this->getErrors();
			if (!empty($errorMsg))
			{
				foreach ($errorMsg as $aMsg)
					SF::log(urldecode($aMsg[0]), CLogger::LEVEL_ERROR);
			}
			
			return $result;
		}
		return true;
	}
	
	
	/**
	 * This method is called when it is time to generate the JS code to
	 * invoke the JQuery Validate Plugin. Constants options are jsonEconded but
	 * this can't be done for handler options (that cannot be JSonEncoded because
	 * they are not strings).
	 * If an option value begins with 'function' then it is assumed to contain an 
	 * anonymous function.
	 * 
	 * @return string js initialisation code
	 */
	public function getJqueryValidate()
	{
		$this->prepareOptions();
		
		// split options in 2 arrays :
		// $optionsHandler : contains calls to anonymous functions (e.g. handler)
		// $optionsReady   : contains options with a constant value.
		
		$options = $this->jQoptions;
		$optionsHandler = array();
		$optionsReady 	= array();
		foreach ( $options as $optName => $optValue ) 
		{
       		if( preg_match('/[ \t\r\n\v\f]*function\(/', $optValue)){
       			$optionsHandler[] = $optName.':'.$optValue;
       		} else {
       			$optionsReady[$optName] = $optValue;
       		}
		}

		// build jQuery options, rules, messages data
		$jsOptionsReady = 	json_encode((count($optionsReady) != 0 ? $optionsReady : array()) +
							array('rules' => $this->jQrules)+
							(count($this->jQmessages) != 0 ? array('messages'=>$this->jQmessages) : array()));
			
		$hasHandler 	= (count($optionsHandler) != 0);
		$hasConstOption = (strlen(trim($jsOptionsReady)) != 0);

		
		// build js
		$js = '$("#'.$this->model->formId.'").validate('
			.'{'
			. ($hasHandler ? implode($optionsHandler,',') : '')
			. ( ($hasHandler and $hasConstOption) ? ',':'')
			. ($hasConstOption ? substr($jsOptionsReady,1) : '')	// remove the first opening brace
		.');';	
		return "jQuery(function($) {\n". urldecode($js) ."\n});";	// get back the GBK Chinese
	}

	
	protected function prepareOptions()
	{
		// prepare options
		$model 	 = &$this->model;
		$options = count($model->options) ? $model->options : array();
		$this->jQoptions = array_merge($this->_defaultOptions, $options);
		
		// prepare rules and message
		foreach($model->rules as $rule)
		{
			if(isset($rule[0], $rule[1]) && in_array($rule[1], $this->_supportedValidators))
			{
				$attributes = $rule[0];
				if(is_string($attributes))
					$attributes=preg_split('/[\s,]+/',$attributes,-1,PREG_SPLIT_NO_EMPTY);
				
				foreach ( $attributes as $attributeName ) 
				{
       				$ruleName   = $rule[1];
       				$ruleParams = array_slice($rule, 2);
					$this->addValidatorRule($model->formId, $attributeName, $ruleName, $ruleParams);
				}				
			}
		}
		
	}
	
	
	/**
	 * @param string attrName attribute name as defined in the model rules
	 * @param string ruleName rule name as defined in the model rules
	 * @param array ruleParams rule parameters
	 * @param const inputType type of input element the validator refers to (default is NULL)
	 * @return boolean true if rule could be added, false if not
	 */
	protected function addValidatorRule($formId, $attrName, $ruleName, $ruleParams)
	{
		SF::trace('adding rule '.$ruleName.' to attribute '.$attrName);
		$bRuleAdded 	= true;
		$attrActiveName	= $this->buildAttrName($formId, $attrName);

		// convert supported validators into their equivalent for the JQuery plugin
		if( isset($ruleParams['allowEmpty']) and $ruleParams['allowEmpty']===false) 
		{
			$this->jQrules[$attrActiveName]['required'] = true;
			$this->addValidatorMessage($attrName, $attrActiveName, 'required', $ruleParams);
		}
			
		switch ($ruleName)
		{
			case 'required':
				if(isset($ruleParams['requiredValue']))
				{
					$this->jQrules[$attrActiveName]['equalToConst'] = $ruleParams['requiredValue'];
					$this->addValidatorMessage($attrName, $attrActiveName, 'equalToConst', $ruleParams);				
				}
//				else 
//				{
					$this->jQrules[$attrActiveName]['required'] = true;
					$this->addValidatorMessage($attrName, $attrActiveName, 'required', $ruleParams);								
//				}
			break;
			
			case 'length':
				// This rule has no direct equivalent in the plugin, it must be splited into 2 JS rules : min and max
				
				if( isset($ruleParams['min']) ) 
				{
					$this->jQrules[$attrActiveName]['minlength'] = $ruleParams['min'];
					$this->addValidatorMessage($attrName, $attrActiveName, 'minlength', $ruleParams);
				}
				
				if( isset($ruleParams['max'])) 
				{
					$this->jQrules[$attrActiveName]['maxlength'] = $ruleParams['max'];
					$this->addValidatorMessage($attrName, $attrActiveName, 'maxlength', $ruleParams);
				}
			break;
			
			case 'email':
				$this->jQrules[$attrActiveName]['email'] = true;
				$this->addValidatorMessage($attrName, $attrActiveName, 'email', $ruleParams);	
			break;
			
			case 'url':
				$this->jQrules[$attrActiveName]['url'] = true;
				$this->addValidatorMessage($attrName, $attrActiveName, 'url', $ruleParams);
			break;
			
			case 'numerical':
				// This validator is not implemented in the JQuery Validate plugin, it is part of the
				// additional-method.js library.
				// Note that it doesn't set any message in the separate _message array : all messages are
				// part of the option object, passed to this JS validator.			 
				
				$numParams = array();
				$numParams['integerOnly'] = ( isset($ruleParams['integerOnly']) ? (bool)$ruleParams['integerOnly'] : false);
				
				if( isset($ruleParams['max']))
				{
					$numParams['max'] 	 = $ruleParams['max'];	
					$errorMsg 		  	 = (isset($ruleParams['tooBig']) ? $ruleParams['tooBig'] : null);			
					$numParams['tooBig'] = $this->addValidatorMessage($attrName, $attrActiveName, 'tooBig', $ruleParams, $errorMsg, true); 
				}
				
				if( isset($ruleParams['min']))
				{
					$numParams['min'] = $ruleParams['min'];
					$errorMsg = (isset($ruleParams['tooSmall']) ? $ruleParams['tooSmall'] : null);
					$numParams['tooSmall'] = $this->addValidatorMessage($attrName, $attrActiveName, 'tooSmall', $ruleParams, $errorMsg, true); 
				}
				
				if( isset($numParams['integerOnly']) && $numParams['integerOnly']===true )
				{
					$numParams['notInt'] = $this->addValidatorMessage($attrName, $attrActiveName, 'notInt', $ruleParams, null, true);
				}
							
				$errorMsg = (isset($ruleParams['message']) ? $ruleParams['message'] : null);			
				$numParams['msg'] = $this->addValidatorMessage($attrName, $attrActiveName, 'numerical', $ruleParams, $errorMsg, true);
							
				$this->jQrules[$attrActiveName]['numerical'] = $numParams;
			break;
			
			case 'compare':
				// The sf built-in COMPARE validator can be converted into 2 different
				// JS validator : equalToConst (compare with a constant value) and equalTo
				// (compare with another editbox value)
				
				if( isset($ruleParams['compareValue']))
				{
					$this->jQrules[$attrActiveName]['equalToConst'] = $ruleParams['compareValue'];
					$this->addValidatorMessage($attrName, $attrActiveName, 'equalToConst', $ruleParams);
				}
				else 
				{
					$this->jQrules[$attrActiveName]['equalTo'] = '#'.$ruleParams['compareAttribute'];
					$this->addValidatorMessage($attrName, $attrActiveName, 'equalTo', $ruleParams);
				}
			break;
			
			case 'match':
				if( isset($ruleParams['pattern']))
				{
					$this->jQrules[$attrActiveName]['match'] = $ruleParams['pattern'];
					$this->addValidatorMessage($attrName, $attrActiveName, 'match', $ruleParams);
				}
			break;
			
			case self::CUSTOM_JS_VALIDATOR:
				// this validator is provided with the extension. It does not perform any server side validation, 
				// but it is used as a wrapper for client-side-only js validators.
					
				if( isset($ruleParams['rules']) ) 
				{
					foreach ( $ruleParams['rules'] as $name => $param ) 
					{
	       				$this->jQrules[$attrActiveName][$name] = $param;
					}
	
					// only the {attribute} placeholder is replaced by its value
					$replace['{attribute}'] = $attrName; 
					foreach ( $ruleParams['messages'] as $name => $msg ) 
					{
	       				$this->jQmessages[$attrActiveName][$name] = strtr($msg, $replace);
					}															
				}
			break;
			
			default:
				$bRuleAdded = false;
		}
		return $bRuleAdded;
	}
	
	
	/**
	 * Set the error message for this validator. The error message is retrieved in the
	 * following order :
	 * 
	 * 1. the 'message' parameter from the rule array defined by the model
	 * 2. the defaultMsg argument
	 * 3. the default sf validator error message. These messages are defined
	 *    in the class (init) and refer to hardcoded messages as they are defined
	 *    by the sf built-in validator classes.
	 * 4. the message defined by the JQuery plugin
	 * 
	 * Messages from 1,2 and 3. may contain special fields which are replaces by their
	 * value. If $returnMsg is FALSE, the result message is stored in _message and will be 
	 * outputed in the 'messages' option of the JQuery Validate plugin. Otherwise the message
	 * is returned by the method.
	 * 
	 * @param attrName 			attribute name as it is send to client for js validation initialization
	 * @param attrActiveName 	displayed name for the attribute
	 * @param ruleName 			the js rule or parameter rule name (e.g. minlength, required, ...)
	 * @param ruleParams 		parameters for the yii rules (defined by the model)
	 * @param string defaultMsg if no msg is set in the ruleParams, use this message
	 */
	protected function addValidatorMessage($attrName, $attrActiveName, $ruleName, $ruleParams, $defaultMsg=null, $returnMsg = false)
	{
		$msg = '';
		if( isset($ruleParams['message']))
			$msg = $ruleParams['message'];
		elseif($defaultMsg != null)
			$msg = $defaultMsg;
		elseif( isset($this->_defaultMessage[$ruleName]) )
			$msg = $this->_defaultMessage[$ruleName];
			
		if( $msg != '')
		{
			foreach ( $ruleParams as $key => $value ) {
       			$params['{'.$key.'}'] = $value;
			}
			$params['{attribute}'] = $attrName;
				
			if(isset($ruleParams['compareValue']))
				$params['{value}'] = $ruleParams['compareValue'];
			elseif(isset($ruleParams['requiredValue']))
				$params['{value}'] = $ruleParams['requiredValue'];

			if($returnMsg === true)
				return strtr($msg,$params);
			else
				$this->jQmessages[$attrActiveName][$ruleName] = strtr($msg,$params);
		}
	}
	
	
	/**
   	 * \brief build the jQuery validate form attribute name
   	 */
	protected function buildAttrName($fromId, $attrName)
	{
		return $fromId.'['.$attrName.']';
	}
}
?>
