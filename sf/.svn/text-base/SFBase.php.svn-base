<?php
/**
 * SFBase class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 SF Software LLC
 * @license http://www.yiiframework.com/license/
 * @version $Id: SFBase.php,v 1.28 2012/01/19 01:36:07 huanggz Exp $
 * @package system
 * @since 1.0
 */

/**
 * Gets the application start timestamp.
 */
defined('SF_BEGIN_TIME') or define('SF_BEGIN_TIME',microtime(true));
/**
 * This constant defines whether the application should be in debug mode or not. Defaults to false.
 */
defined('SF_DEBUG') or define('SF_DEBUG',false);
/**
 * This constant defines how much call stack information (file name and line number) should be logged by SF::trace().
 * Defaults to 0, meaning no backtrace information. If it is greater than 0,
 * at most that number of call stacks will be logged. Note, only user application call stacks are considered.
 */
defined('SF_TRACE_LEVEL') or define('SF_TRACE_LEVEL',0);
/**
 * This constant defines whether exception handling should be enabled. Defaults to true.
 */
defined('SF_ENABLE_EXCEPTION_HANDLER') or define('SF_ENABLE_EXCEPTION_HANDLER',true);
/**
 * This constant defines whether error handling should be enabled. Defaults to true.
 */
defined('SF_ENABLE_ERROR_HANDLER') or define('SF_ENABLE_ERROR_HANDLER',true);
/**
 * Defines the SF framework installation path.
 */
defined('SF_PATH') or define('SF_PATH',dirname(__FILE__));

defined('SF_CHARSET') or define('SF_CHARSET','gbk');

defined('SF_LANG ') or define('SF_LANG','zh_cn');

defined('SF_BASE_PATH') or define('SF_BASE_PATH','class');
/**
 * Defines the Zii library installation path.
 */
defined('SF_ZII_PATH') or define('SF_ZII_PATH',SF_PATH.DIRECTORY_SEPARATOR.'zii');

/**
 * SFBase is a helper class serving common framework functionalities.
 *
 * Do not use SFBase directly. Instead, use its child class {@link SF} where
 * you can customize methods of SFBase.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: SFBase.php,v 1.28 2012/01/19 01:36:07 huanggz Exp $
 * @package system
 * @since 1.0
 */
class SFBase
{
	private static $_aliases=array('system'=>SF_PATH,'zii'=>SF_ZII_PATH); // alias => path
	private static $_imports=array();					// alias => class name or directory
	private static $_classes=array();
	private static $_includePaths;						// list of include paths
	private static $_app;
	private static $_logger;


	/**
	 * @return string the version of SF framework
	 */
	public static function getVersion()
	{
		return '1.1.0';
	}

	/**
	 * Creates a Web application instance.
	 * @param mixed application configuration.
	 * If a string, it is treated as the path of the file that contains the configuration;
	 * If an array, it is the actual configuration information.
	 * Please make sure you specify the {@link CApplication::basePath basePath} property in the configuration,
	 * which should point to the directory containing all application logic, template and data.
	 * If not, the directory will be defaulted to 'protected'.
	 */
	public static function createWebApplication($config=null)
	{
		self::$_app = self::createApplication('CWebApplication',$config);
		ADODB_Active_Record::SetDatabaseAdapter(self::$_app->getDb());
		return self::$_app;
	}

	/**
	 * Creates a console application instance.
	 * @param mixed application configuration.
	 * If a string, it is treated as the path of the file that contains the configuration;
	 * If an array, it is the actual configuration information.
	 * Please make sure you specify the {@link CApplication::basePath basePath} property in the configuration,
	 * which should point to the directory containing all application logic, template and data.
	 * If not, the directory will be defaulted to 'protected'.
	 */
	public static function createConsoleApplication($config=null)
	{
		return self::createApplication('CConsoleApplication',$config);
	}

	/**
	 * Creates an application of the specified class.
	 * @param string the application class name
	 * @param mixed application configuration. This parameter will be passed as the parameter
	 * to the constructor of the application class.
	 * @return mixed the application instance
	 * @since 1.0.10
	 */
	public static function createApplication($class,$config=null)
	{
		return new $class($config);
	}

	/**
	 * @return CApplication the application singleton, null if the singleton has not been created yet.
	 */
	public static function app()
	{
		return self::$_app;
	}

	/**
	 * Stores the application instance in the class static member.
	 * This method helps implement a singleton pattern for CApplication.
	 * Repeated invocation of this method or the CApplication constructor
	 * will cause the throw of an exception.
	 * To retrieve the application instance, use {@link app()}.
	 * @param CApplication the application instance. If this is null, the existing
	 * application singleton will be removed.
	 * @throws CException if multiple application instances are registered.
	 */
	public static function setApplication($app)
	{
		if(self::$_app===null || $app===null)
			self::$_app=$app;
		else
			throw new CException(SF::t('SF','SF application can only be created once.'));
	}

	/**
	 * @return string the path of the framework
	 */
	public static function getFrameworkPath()
	{
		return SF_PATH;
	}

	/**
	 * Creates an object and initializes it based on the given configuration.
	 *
	 * The specified configuration can be either a string or an array.
	 * If the former, the string is treated as the object type which can
	 * be either the class name or {@link SFBase::getPathOfAlias class path alias}.
	 * If the latter, the 'class' element is treated as the object type,
	 * and the rest name-value pairs in the array are used to initialize
	 * the corresponding object properties.
	 *
	 * Any additional parameters passed to this method will be
	 * passed to the constructor of the object being created.
	 *
	 * NOTE: the array-typed configuration has been supported since version 1.0.1.
	 *
	 * @param mixed the configuration. It can be either a string or an array.
	 * @return mixed the created object
	 * @throws CException if the configuration does not have a 'class' element.
	 */
	public static function createComponent($config)
	{
		//get class name first.
		if(is_string($config))
		{
			$type=$config;
			$config=array();
		}
		else if(isset($config['class']))
		{
			$type=$config['class'];
			unset($config['class']);
		}
		else
			throw new CException(SF::t('SF','Object configuration must be an array containing a "class" element.'));

		//then import class
		if(!class_exists($type,false))
			$type=SF::import($type,true);

		//new the object.
		if(($n=func_num_args())>1)
		{
			$args=func_get_args();
			if($n===2)
				$object=new $type($args[1]);
			else if($n===3)
				$object=new $type($args[1],$args[2]);
			else if($n===4)
				$object=new $type($args[1],$args[2],$args[3]);
			else
			{
				unset($args[0]);
				$class=new ReflectionClass($type);
				// Note: ReflectionClass::newInstanceArgs() is available for PHP 5.1.3+
				// $object=$class->newInstanceArgs($args);
				$object=call_user_func_array(array($class,'newInstance'),$args);
			}
		}
		else
		{
			//normal ,just 1 param.
			$object=new $type;
		}
		//init the object's property.
		foreach($config as $key=>$value)
			$object->$key=$value;

		return $object;
	}

	/**
	 * Imports the definition of a class or a directory of class files.
	 *
	 * Path aliases are used to refer to the class file or directory being imported.
	 * If importing a path alias ending with '.*', the alias is considered as a directory
	 * which will be added to the PHP include paths; Otherwise, the alias is translated
	 * to the path of a class file which is included when needed.
	 *
	 * For example, importing 'system.web.*' will add the 'web' directory of the framework
	 * to the PHP include paths; while importing 'system.web.CController' will include
	 * the class file 'web/CController.php' when needed.
	 *
	 * The same alias can be imported multiple times, but only the first time is effective.
	 *
	 * @param string path alias to be imported
	 * @param boolean whether to include the class file immediately. If false, the class file
	 * will be included only when the class is being used.
	 * @return string the class name or the directory that this alias refers to
	 * @throws CException if the alias is invalid
	 */
	public static function import($alias,$forceInclude=false)
	{
		if(isset(self::$_imports[$alias]))  // previously imported
			return self::$_imports[$alias];

		if(class_exists($alias,false) || interface_exists($alias,false))
			return self::$_imports[$alias]=$alias;

		if(($pos=strrpos($alias,'.'))===false)  // a simple class name
		{
			if($forceInclude && self::autoload($alias))
				self::$_imports[$alias]=$alias;
			return $alias;
		}

		if(($className=(string)substr($alias,$pos+1))!=='*' && (class_exists($className,false) || interface_exists($className,false)))
			return self::$_imports[$alias]=$className;

		if(($path=self::getPathOfAlias($alias))!==false)
		{
			if($className!=='*')
			{
				if($forceInclude)
				{
					require($path.'.php');
					self::$_imports[$alias]=$className;
				}
				else
					self::$_classes[$className]=$path.'.php';
				return $className;
			}
			else  // a directory
			{
				if(self::$_includePaths===null)
				{
					self::$_includePaths=array_unique(explode(PATH_SEPARATOR,get_include_path()));
					if(($pos=array_search('.',self::$_includePaths,true))!==false)
						unset(self::$_includePaths[$pos]);
				}
				array_unshift(self::$_includePaths,$path);
				if(set_include_path('.'.PATH_SEPARATOR.implode(PATH_SEPARATOR,self::$_includePaths))===false)
					throw new CException(SF::t('SF','Unable to import "{alias}". Please check your server configuration to make sure you are allowed to change PHP include_path.',array('{alias}'=>$alias)));
				return self::$_imports[$alias]=$path;
			}
		}
		else
			throw new CException(SF::t('SF','Alias "{alias}" is invalid. Make sure it points to an existing directory or file.',
				array('{alias}'=>$alias)));
	}

	/**
	 * Translates an alias into a file path.
	 * Note, this method does not ensure the existence of the resulting file path.
	 * It only checks if the root alias is valid or not.
	 * @param string alias (e.g. system.web.CController)
	 * @return mixed file path corresponding to the alias, false if the alias is invalid.
	 */
	public static function getPathOfAlias($alias)
	{
		if(isset(self::$_aliases[$alias]))
			return self::$_aliases[$alias];
		else if(($pos=strpos($alias,'.'))!==false)
		{
			$rootAlias=substr($alias,0,$pos);
			if(isset(self::$_aliases[$rootAlias]))
				return self::$_aliases[$alias]=rtrim(self::$_aliases[$rootAlias].DIRECTORY_SEPARATOR.str_replace('.',DIRECTORY_SEPARATOR,substr($alias,$pos+1)),'*'.DIRECTORY_SEPARATOR);
			else if(self::$_app instanceof CWebApplication)
			{
				if(self::$_app->findModule($rootAlias)!==null)
					return self::getPathOfAlias($alias);
			}
		}
		return false;
	}

	/**
	 * Create a path alias.
	 * Note, this method neither checks the existence of the path nor normalizes the path.
	 * @param string alias to the path
	 * @param string the path corresponding to the alias. If this is null, the corresponding
	 * path alias will be removed.
	 */
	public static function setPathOfAlias($alias,$path)
	{
		if(empty($path))
			unset(self::$_aliases[$alias]);
		else
			self::$_aliases[$alias]=rtrim($path,'\\/');
	}

	/**
	 * Class autoload loader.
	 * This method is provided to be invoked within an __autoload() magic method.
	 * @param string class name
	 * @return boolean whether the class has been loaded successfully
	 */
	public static function autoload($className)
	{
		// use include so that the error PHP file may appear
		if(isset(self::$_coreClasses[$className]))
			include_once(SF_PATH.self::$_coreClasses[$className]);
		else if(isset(self::$_classes[$className]))
			include_once(self::$_classes[$className]);
		else
		{
			$_class = strtolower($className);
			if (substr($_class, 0, 16) === 'smarty_internal_' || $_class == 'smarty_security')
				include_once SMARTY_SYSPLUGINS_DIR . $_class . '.php'; 
			else
				include_once($className.'.php');
			return class_exists($className,false) || interface_exists($className,false);
		}
		return true;
	}

	/**
	 * Writes a trace message.
	 * This method will only log a message when the application is in debug mode.
	 * @param string message to be logged
	 * @param string category of the message
	 * @see log
	 */
	public static function trace($msg,$category='application')
	{
		if(SF_DEBUG)
		{
			if(SF_TRACE_LEVEL>0)
			{
				$traces=debug_backtrace();
				$count=0;
				foreach($traces as $trace)
				{
					if(isset($trace['file'],$trace['line']))
					{
						$className=substr(basename($trace['file']),0,-4);
						if(!isset(self::$_coreClasses[$className]) && $className!=='SFBase')
						{
							$msg.="\nin ".$trace['file'].' ('.$trace['line'].')';
							if(++$count>=SF_TRACE_LEVEL)
								break;
						}
					}
				}
			}
			self::log($msg,CLogger::LEVEL_TRACE,$category);
		}
	}

	/**
	 * Logs a message.
	 * Messages logged by this method may be retrieved via {@link CLogger::getLogs}
	 * and may be recorded in different media, such as file, email, database, using
	 * {@link CLogAppCompo}.
	 * @param string message to be logged
	 * @param string level of the message (e.g. 'trace', 'warning', 'error'). It is case-insensitive.
	 * @param string category of the message (e.g. 'system.web'). It is case-insensitive.
	 */
	public static function log($msg,$level=CLogger::LEVEL_INFO,$category='application')
	{
		if(self::$_logger===null)
			self::$_logger=new CLogger;
		self::$_logger->log($msg,$level,$category);
	}

	/**
	 * Marks the begin of a code block for profiling.
	 * This has to be matched with a call to {@link endProfile()} with the same token.
	 * The begin- and end- calls must also be properly nested, e.g.,
	 * <pre>
	 * SF::beginProfile('block1');
	 * SF::beginProfile('block2');
	 * SF::endProfile('block2');
	 * SF::endProfile('block1');
	 * </pre>
	 * The following sequence is not valid:
	 * <pre>
	 * SF::beginProfile('block1');
	 * SF::beginProfile('block2');
	 * SF::endProfile('block1');
	 * SF::endProfile('block2');
	 * </pre>
	 * @param string token for the code block
	 * @param string the category of this log message
	 * @see endProfile
	 */
	public static function beginProfile($token,$category='application')
	{
		self::log('begin:'.$token,CLogger::LEVEL_PROFILE,$category);
	}

	/**
	 * Marks the end of a code block for profiling.
	 * This has to be matched with a previous call to {@link beginProfile()} with the same token.
	 * @param string token for the code block
	 * @param string the category of this log message
	 * @see beginProfile
	 */
	public static function endProfile($token,$category='application')
	{
		self::log('end:'.$token,CLogger::LEVEL_PROFILE,$category);
	}

	/**
	 * @return CLogger message logger
	 */
	public static function getLogger()
	{
		if(self::$_logger!==null)
			return self::$_logger;
		else
			return self::$_logger=new CLogger;
	}

	/**
	 * @return string a string that can be displayed on your Web page showing Powered-by-SF information
	 */
	public static function powered()
	{
		return 'Powered by SF Framework.';
	}

	/**
	 * Translates a message to the specified language.
	 * Starting from version 1.0.2, this method supports choice format (see {@link CChoiceFormat}),
	 * i.e., the message returned will be chosen from a few candidates according to the given
	 * number value. This feature is mainly used to solve plural format issue in case
	 * a message has different plural forms in some languages.
	 * @param string message category. Please use only word letters. Note, category 'SF' is
	 * reserved for SF framework core code use. See {@link CPhpMessageSource} for
	 * more interpretation about message category.
	 * @param string the original message
	 * @param array parameters to be applied to the message using <code>strtr</code>.
	 * Starting from version 1.0.2, the first parameter can be a number without key.
	 * And in this case, the method will call {@link CChoiceFormat::format} to choose
	 * an appropriate message translation.
	 * @param string which message source application component to use.
	 * Defaults to null, meaning using 'coreMessages' for messages belonging to
	 * the 'SF' category and using 'messages' for the rest messages.
	 * @param string the target language. If null (default), the {@link CApplication::getLanguage application language} will be used.
	 * This parameter has been available since version 1.0.3.
	 * @return string the translated message
	 * @see CMessageSource
	 */
	public static function t($category,$message,$params=array(),$source=null,$language=null)
	{
		//modify by haka,20090509
		//just not support i18n,safe return message.	
		if ( $params === array () )
			$varStr = " NULL. ";
		else
			$varStr = print_r($params,true);
		
		return $category."::".$message." Vars=".$varStr;
	}

	/**
	 * Registers a new class autoloader.
	 * The new autoloader will be placed before {@link autoload} and after
	 * any other existing autoloaders.
	 * @param callback a valid PHP callback (function name or array($className,$methodName)).
	 * @since 1.0.10
	 */
	public static function registerAutoloader($callback)
	{
		spl_autoload_unregister(array('SFBase','autoload'));
		spl_autoload_register($callback);
		spl_autoload_register(array('SFBase','autoload'));
	}

	/**
	 * @var array class map for core SF classes.
	 * NOTE, DO NOT MODIFY THIS ARRAY MANUALLY. IF YOU CHANGE OR ADD SOME CORE CLASSES,
	 * PLEASE RUN 'build autoload' COMMAND TO UPDATE THIS ARRAY.
	 */
	private static $_coreClasses=array(
		// core
		'CApplication' 				=> '/core/CApplication.php',
		'CApplicationComponent' 	=> '/core/CApplicationComponent.php',
		'CBehavior' 				=> '/core/CBehavior.php',
		'CComponent' 				=> '/core/CComponent.php',
		'CDataModel' 				=> '/core/CDataModel.php',
		'CModule' 					=> '/core/CModule.php',
		'CBaseController' 			=> '/core/CBaseController.php',
		'CController' 				=> '/core/CController.php',
		'CFormModel' 				=> '/core/CFormModel.php',
		'CHttpCookie' 				=> '/core/CHttpCookie.php',
		'CHttpRequest' 				=> '/core/CHttpRequest.php',
		'CHttpSession' 				=> '/core/CHttpSession.php',
		'CCacheHttpSession' 		=> '/core/CCacheHttpSession.php',
		'CUrlManager' 				=> '/core/CUrlManager.php',
		'CWebApplication' 			=> '/core/CWebApplication.php',
		'CWebModule' 				=> '/core/CWebModule.php',
		'CAction' 					=> '/core/CAction.php',
		'CInlineAction' 			=> '/core/CInlineAction.php',
		'CViewRenderer' 			=> '/core/CViewRenderer.php',
		'CSmartyViewRenderer' 		=> '/core/CSmartyViewRenderer.php',
		'CImageComponent' 		    => '/core/CImageComponent.php',
		'CFileUpload' 		        => '/core/CFileUpload.php', 
		'CPhotoUpload' 		        => '/core/CPhotoUpload.php',
		'CExtController' 		    => '/core/CExtController.php',
		'CFilterChain' 		        => '/core/CFilterChain.php',
		'CInlineFilter' 		    => '/core/CInlineFilter.php',
		'CFilter'					=> '/core/CFilter.php',
		
		// exception
		'CErrorEvent' 				=> '/exception/CErrorEvent.php',
		'CErrorHandler' 			=> '/exception/CErrorHandler.php',
		'CException' 				=> '/exception/CException.php',
		'CExceptionEvent' 			=> '/exception/CExceptionEvent.php',
		'CHttpException' 			=> '/exception/CHttpException.php',
	
		// caching
		'CCache' 					=> '/caching/CCache.php',
		'CMemCache' 				=> '/caching/CMemCache.php',
	
		// collections
		'CAttributeCollection' 		=> '/collections/CAttributeCollection.php',
		'CConfiguration' 			=> '/collections/CConfiguration.php',
		'CList' 					=> '/collections/CList.php',
		'CListIterator' 			=> '/collections/CListIterator.php',
		'CMap' 						=> '/collections/CMap.php',
		'CMapIterator' 				=> '/collections/CMapIterator.php',
		'CQueue' 					=> '/collections/CQueue.php',
		'CQueueIterator' 			=> '/collections/CQueueIterator.php',
		'CStack' 					=> '/collections/CStack.php',
		'CStackIterator' 			=> '/collections/CStackIterator.php',
		'CTypedList' 				=> '/collections/CTypedList.php',
	
		// logging
		'CFileLogRoute' 			=> '/logging/CFileLogRoute.php',
		'CLogRoute' 				=> '/logging/CLogRoute.php',
		'CLogAppCompo' 				=> '/logging/CLogAppCompo.php',
		'CLogger' 					=> '/logging/CLogger.php',
		'CProfileLogRoute' 			=> '/logging/CProfileLogRoute.php',
	
		// utils
		'CDateTimeParser' 			=> '/utils/CDateTimeParser.php',
		'CFileHelper' 				=> '/utils/CFileHelper.php',
		'CFormatter' 				=> '/utils/CFormatter.php',
		'CMarkdownParser' 			=> '/utils/CMarkdownParser.php',
		'CPropertyValue' 			=> '/utils/CPropertyValue.php',
		'CTimestamp' 				=> '/utils/CTimestamp.php',
		'CVarDumper' 				=> '/utils/CVarDumper.php',

		// validators
		'CJfValidate' 				=> '/core/CJfValidate.php',
		'CBooleanValidator' 		=> '/validators/CBooleanValidator.php',
		'CCaptchaValidator' 		=> '/validators/CCaptchaValidator.php',
		'CCompareValidator' 		=> '/validators/CCompareValidator.php',
		'CDefaultValueValidator'	=> '/validators/CDefaultValueValidator.php',
		'CEmailValidator' 			=> '/validators/CEmailValidator.php',
		'CExistValidator' 			=> '/validators/CExistValidator.php',
		'CFileValidator' 			=> '/validators/CFileValidator.php',
		'CFilterValidator' 			=> '/validators/CFilterValidator.php',
		'CInlineValidator' 			=> '/validators/CInlineValidator.php',
		'CNumberValidator' 			=> '/validators/CNumberValidator.php',
		'CRangeValidator' 			=> '/validators/CRangeValidator.php',
		'CRegularExpressionValidator' => '/validators/CRegularExpressionValidator.php',
		'CRequiredValidator' 		=> '/validators/CRequiredValidator.php',
		'CSafeValidator' 			=> '/validators/CSafeValidator.php',
		'CStringValidator' 			=> '/validators/CStringValidator.php',
		'CTypeValidator' 			=> '/validators/CTypeValidator.php',
		'CUniqueValidator' 			=> '/validators/CUniqueValidator.php',
		'CUnsafeValidator' 			=> '/validators/CUnsafeValidator.php',
		'CUrlValidator' 			=> '/validators/CUrlValidator.php',
		'CValidator' 				=> '/validators/CValidator.php',
		'CustomJsValidator' 		=> '/validators/CustomJsValidator.php',


		//busi
		'CCard'						=> '/busi/CCard.php',
		
		// library
		'ADODB_Active_Record'		=> '/library/adodb/adodb-active-record.inc.php',
		'Smarty' 					=> '/library/smarty/Smarty.class.php',
		'Image' 					=> '/library/image/Image.php',
	
		// db
		'CADOModel' 				=> '/db/CADOModel.php',
		'CActiveRecord'				=> '/db/CActiveRecord.php',
	
		//SQL·À×¢Èë
		'AntiInjectionSQL'			=> '/core/AntiInjectionSQL.php',
		
		//xss¹¥»÷
		'AntiXssAttack'				=> '/core/AntiXssAttack.php',
		
		
	);
}

spl_autoload_register(array('SFBase','autoload'));
require(SF_PATH.'/interface/interfaces.php');
