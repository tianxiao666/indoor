<?php
/**
 * CApplication class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.SFframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.SFframework.com/license/
 */

/**
 * CApplication is the base class for all application classes.
 *
 * An application serves as the global context that the user request
 * is being processed. It manages a set of application components that
 * provide specific functionalities to the whole application.
 *
 * The core application components provided by CApplication are the following:
 * <ul>
 * <li>{@link getErrorHandler errorHandler}: handles PHP errors and
 *   uncaught exceptions. This application component is dynamically loaded when needed.</li>
 * <li>{@link getSecurityManager securityManager}: provides security-related
 *   services, such as hashing, encryption. This application component is dynamically
 *   loaded when needed.</li>
 * <li>{@link getStatePersister statePersister}: provides global state
 *   persistence method. This application component is dynamically loaded when needed.</li>
 * <li>{@link getCache cache}: provides caching feature. This application component is
 *   disabled by default.</li>
 * <li>{@link getMessages messages}: provides the message source for translating
 *   application messages. This application component is dynamically loaded when needed.</li>
 * <li>{@link getCoreMessages coreMessages}: provides the message source for translating
 *   Yii framework messages. This application component is dynamically loaded when needed.</li>
 * </ul>
 *
 * CApplication will undergo the following lifecycles when processing a user request:
 * <ol>
 * <li>load application configuration;</li>
 * <li>set up class autoloader and error handling;</li>
 * <li>load static application components;</li>
 * <li>{@link onBeginRequest}: preprocess the user request;</li>
 * <li>{@link processRequest}: process the user request;</li>
 * <li>{@link onEndRequest}: postprocess the user request;</li>
 * </ol>
 *
 * Starting from lifecycle 3, if a PHP error or an uncaught exception occurs,
 * the application will switch to its error handling logic and jump to step 6 afterwards.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CApplication.php,v 1.7 2010/04/27 06:15:17 liujz Exp $
 * @package system.base
 * @since 1.0
 */
abstract class CApplication extends CModule
{
	/**
	 * @var string the application name. Defaults to 'My Application'.
	 */
	public $name='SF Application';
	/**
	 * @var string the charset currently used for the application. Defaults to 'UTF-8'.
	 */
	public $charset = SF_CHARSET;
	/**
	 * @var string the language that the application is written in. This mainly refers to
	 * the language that the messages and view files are in. Defaults to 'en_us' (US English).
	 */
	public $sourceLanguage	=	SF_LANG;

	private $_id;
	private $_basePath;
	private $_runtimePath;
	private $_extensionPath;
	private $_globalState;
	private $_stateChanged;
	private $_ended=false;
	private $_language;

	/**
	 * Processes the request.
	 * This is the place where the actual request processing work is done.
	 * Derived classes should override this method.
	 */
	abstract public function processRequest();

	/**
	 * Constructor.
	 * @param mixed application configuration.
	 * If a string, it is treated as the path of the file that contains the configuration;
	 * If an array or CConfiguration, it is the actual configuration information.
	 * Please make sure you specify the {@link getBasePath basePath} property in the configuration,
	 * which should point to the directory containing all application logic, template and data.
	 * If not, the directory will be defaulted to 'protected'.
	 * MODIFY BY HAKA,2009 05
	 */
	public function __construct($config=null)
	{
		SF::setApplication($this);

		// set basePath at early as possible to avoid trouble
		if(is_string($config)){
			//by haka,2009 05
			require_once($config);
			$config=$g_app_default_config;
		}
		if(isset($config['basePath']))
		{
			$this->setBasePath($config['basePath']);
			unset($config['basePath']);
		}
		else
			$this->setBasePath(SF_BASE_PATH);

		SF::setPathOfAlias('application',$this->getBasePath());
		SF::setPathOfAlias('webroot',dirname($_SERVER['SCRIPT_FILENAME']));

		/**
		 * add a public path for application
		 * xiang.zc 2013-12-30
		 */
		if(isset($config['publicbasePath'])) {
			SF::setPathOfAlias('publicbasePath',$config['publicbasePath']);
			unset($config['publicbasePath']);
		}

		$this->preinit();

		$this->initSystemHandlers();
		$this->registerCoreComponents();

		$this->configure($config);
		$this->attachBehaviors($this->behaviors);
		$this->preloadComponents();

		$this->init();
	}


	/**
	 * Runs the application.
	 * This method loads static application components. Derived classes usually overrides this
	 * method to do more application-specific tasks.
	 * Remember to call the parent implementation so that static application components are loaded.
	 */
	public function run()
	{
		$this->onBeginRequest(new CEvent($this));
		$this->processRequest();
		$this->onEndRequest(new CEvent($this));
	}

	/**
	 * Terminates the application.
	 * This method replaces PHP's exit() function by calling
	 * {@link onEndRequest} before exiting.
	 * @param integer exit status (value 0 means normal exit while other values mean abnormal exit).
	 */
	public function end($status=0)
	{
		$this->onEndRequest(new CEvent($this));
		exit($status);
	}

	/**
	 * Raised right BEFORE the application processes the request.
	 * @param CEvent the event parameter
	 */
	public function onBeginRequest($event)
	{
		$this->raiseEvent('onBeginRequest',$event);
	}

	/**
	 * Raised right AFTER the application processes the request.
	 * @param CEvent the event parameter
	 */
	public function onEndRequest($event)
	{
		if(!$this->_ended)
		{
			$this->_ended=true;
			$this->raiseEvent('onEndRequest',$event);
		}
	}

	/**
	 * @return string a unique identifier for the application.
	 */
	public function getId()
	{
		if($this->_id!==null)
			return $this->_id;
		else
			return $this->_id=md5($this->getBasePath().$this->name);
	}

	/**
	 * @param string a unique identifier for the application.
	 */
	public function setId($id)
	{
		$this->_id=$id;
	}

	/**
	 * @return string the root directory of the application. Defaults to 'protected'.
	 */
	public function getBasePath()
	{
		return $this->_basePath;
	}

	/**
	 * Sets the root directory of the application.
	 * This method can only be invoked at the begin of the constructor.
	 * @param string the root directory of the application.
	 * @throws CException if the directory does not exist.
	 */
	public function setBasePath($path)
	{
		if(($this->_basePath=realpath($path))===false || !is_dir($this->_basePath))
			throw new CException(SF::t('SF','Application base path "{path}" is not a valid directory.',
				array('{path}'=>$path)));
	}

	/**
	 * @return string the directory that stores runtime files. Defaults to 'protected/runtime'.
	 */
	public function getRuntimePath()
	{
		if($this->_runtimePath!==null)
			return $this->_runtimePath;
		else
		{
			$this->setRuntimePath($this->getBasePath().DIRECTORY_SEPARATOR.'runtime');
			return $this->_runtimePath;
		}
	}

	/**
	 * @param string the directory that stores runtime files.
	 * @throws CException if the directory does not exist or is not writable
	 */
	public function setRuntimePath($path)
	{
          //echo realpath($path);die();
		if(($runtimePath=realpath($path))===false || !is_dir($runtimePath) || !is_writable($runtimePath))
			throw new CException(SF::t('SF','Application runtime path "{path}" is not valid. Please make sure it is a directory writable by the Web server process.',
				array('{path}'=>$path)));
		$this->_runtimePath=$runtimePath;
	}

	/**
	 * Returns the root directory that holds all third-party extensions.
	 * Note, this property cannot be changed or overridden. It is always 'AppBasePath/extensions'.
	 * @return string the directory that contains all extensions.
	 */
	final public function getExtensionPath()
	{
		if($this->_extensionPath!==null)
			return $this->_extensionPath;
		else
			return $this->_extensionPath=$this->getBasePath().DIRECTORY_SEPARATOR.'extensions';
	}

	/**
	 * @return string the language that the user is using and the application should be targeted to.
	 * Defaults to the {@link sourceLanguage source language}.
	 */
	public function getLanguage()
	{
		return $this->_language===null ? $this->sourceLanguage : $this->_language;
	}

	/**
	 * Specifies which language the application is targeted to.
	 *
	 * This is the language that the application displays to end users.
	 * If set null, it uses the {@link sourceLanguage source language}.
	 *
	 * Unless your application needs to support multiple languages, you should always
	 * set this language to null to maximize the application's performance.
	 * @param string the user language (e.g. 'en_US', 'zh_CN').
	 * If it is null, the {@link sourceLanguage} will be used.
	 */
	public function setLanguage($language)
	{
		$this->_language=$language;
	}

	/**
	 * Returns the localized version of a specified file.
	 *
	 * The searching is based on the specified language code. In particular,
	 * a file with the same name will be looked for under the subdirectory
	 * named as the locale ID. For example, given the file "path/to/view.php"
	 * and locale ID "zh_cn", the localized file will be looked for as
	 * "path/to/zh_cn/view.php". If the file is not found, the original file
	 * will be returned.
	 *
	 * For consistency, it is recommended that the locale ID is given
	 * in lower case and in the format of LanguageID_RegionID (e.g. "en_us").
	 *
	 * @param string the original file
	 * @param string the language that the original file is in. If null, the application {@link sourceLanguage source language} is used.
	 * @param string the desired language that the file should be localized to. If null, the {@link getLanguage application language} will be used.
	 * @return string the matching localized file. The original file is returned if no localized version is found
	 * or if source language is the same as the desired language.
	 */
	public function findLocalizedFile($srcFile,$srcLanguage=null,$language=null)
	{
		if($srcLanguage===null)
			$srcLanguage=$this->sourceLanguage;
		if($language===null)
			$language=$this->getLanguage();
		if($language===$srcLanguage)
			return $srcFile;
		$desiredFile=dirname($srcFile).DIRECTORY_SEPARATOR.$language.DIRECTORY_SEPARATOR.basename($srcFile);
		return is_file($desiredFile) ? $desiredFile : $srcFile;
	}

	/**
	 * @param string locale ID (e.g. en_US). If null, the {@link getLanguage application language ID} will be used.
	 * @return CLocale the locale instance
	 */
	public function getLocale($localeID=null)
	{
		return CLocale::getInstance($localeID===null?$this->getLanguage():$localeID);
	}

	/**
	 * @return CNumberFormatter the locale-dependent number formatter.
	 * The current {@link getLocale application locale} will be used.
	 */
	public function getNumberFormatter()
	{
		return $this->getLocale()->getNumberFormatter();
	}

	/**
	 * @return CDateFormatter the locale-dependent date formatter.
	 * The current {@link getLocale application locale} will be used.
	 */
	public function getDateFormatter()
	{
		return $this->getLocale()->getDateFormatter();
	}

	/**
	 * @return CDbConnection the database connection
	 */
	public function getDb()
	{
		$db = $this->getComponent('db');
		return $db->getDBConnection();
	}

	/**
	 * @return CErrorHandler the error handler application component.
	 */
	public function getErrorHandler()
	{
		return $this->getComponent('errorHandler');
	}
	
	/**
	 * @return CErrorFileLog the error handler application component.
	 */
	public function getErrorFileLog()
	{
		return $this->getComponent('errorFileLoger');
	}

	/**
	 * @return CSecurityManager the security manager application component.
	 */
	public function getSecurityManager()
	{
		return $this->getComponent('securityManager');
	}

	/**
	 * @return CCache the cache application component. Null if the component is not enabled.
	 */
	public function getCache()
	{
		return $this->getComponent('cache');
	}


	/**
	 * Handles uncaught PHP exceptions.
	 *
	 * This method is implemented as a PHP exception handler. It requires
	 * that constant SF_ENABLE_EXCEPTION_HANDLER be defined true.
	 *
	 * This method will first raise an {@link onException} event.
	 * If the exception is not handled by any event handler, it will call
	 * {@link getErrorHandler errorHandler} to process the exception.
	 *
	 * The application will be terminated by this method.
	 *
	 * @param Exception exception that is not caught
	 */
	public function handleException($exception)
	{
		// add error log when the application run exception, liujz 2010-02-26
		$message  = get_class($exception)."\n".$exception->getMessage().' ('.$exception->getFile().':'.$exception->getLine().')'.$exception->getTraceAsString();
		$message .= "\n SCRIPT_FILENAME={$_SERVER['SCRIPT_FILENAME']}\n REQUEST_URI={$_SERVER['REQUEST_URI']}\n HTTP_REFERER={$_SERVER['HTTP_REFERER']}\n PHP_SELF={$_SERVER['PHP_SELF']}";
		$oErrFile = SF::app()->getErrorFileLog();
		$oErrFile->processErrorLogs($message);
		// add error log end
		
		// disable error capturing to avoid recursive errors
		restore_error_handler();
		restore_exception_handler();

		$category='exception.'.get_class($exception);
		if($exception instanceof CHttpException)
			$category.='.'.$exception->statusCode;
		$message=(string)$exception;
		if(isset($_SERVER['REQUEST_URI']))
			$message.=' REQUEST_URI='.$_SERVER['REQUEST_URI'];
		SF::log($message,CLogger::LEVEL_ERROR,$category);

		$event=new CExceptionEvent($this,$exception);
		$this->onException($event);
		if(!$event->handled)
		{
			// try an error handler
			if(($handler=$this->getErrorHandler())!==null)
				$handler->handle($event);
			else
				$this->displayException($exception);
		}
		$this->end(1);
	}

	/**
	 * Handles PHP execution errors such as warnings, notices.
	 *
	 * This method is implemented as a PHP error handler. It requires
	 * that constant SF_ENABLE_ERROR_HANDLER be defined true.
	 *
	 * This method will first raise an {@link onError} event.
	 * If the error is not handled by any event handler, it will call
	 * {@link getErrorHandler errorHandler} to process the error.
	 *
	 * The application will be terminated by this method.
	 *
	 * @param integer the level of the error raised
	 * @param string the error message
	 * @param string the filename that the error was raised in
	 * @param integer the line number the error was raised at
	 */
	public function handleError($code,$message,$file,$line)
	{
		// add error log when the application run error, liujz 2010-02-26
		$message  = "PHP Error [$code]\n $message ($file:$line)\n";
		$message .= "\n SCRIPT_FILENAME={$_SERVER['SCRIPT_FILENAME']}\n REQUEST_URI={$_SERVER['REQUEST_URI']}\n HTTP_REFERER={$_SERVER['HTTP_REFERER']}\n PHP_SELF={$_SERVER['PHP_SELF']}";
		$oErrFile = SF::app()->getErrorFileLog();
		$oErrFile->processErrorLogs($message);
		// add error log end
		
		if($code & error_reporting())
		{
			// disable error capturing to avoid recursive errors
			restore_error_handler();
			restore_exception_handler();

			$log="$message ($file:$line)";
			if(isset($_SERVER['REQUEST_URI']))
				$log.=' REQUEST_URI='.$_SERVER['REQUEST_URI'];
			SF::log($log,CLogger::LEVEL_ERROR,'php');

			$event=new CErrorEvent($this,$code,$message,$file,$line);
			$this->onError($event);
			if(!$event->handled)
			{
				// try an error handler
				if(($handler=$this->getErrorHandler())!==null)
					$handler->handle($event);
				else
					$this->displayError($code,$message,$file,$line);
			}
			$this->end(1);
		}
	}

	/**
	 * Raised when an uncaught PHP exception occurs.
	 *
	 * An event handler can set the {@link CExceptionEvent::handled handled}
	 * property of the event parameter to be true to indicate no further error
	 * handling is needed. Otherwise, the {@link getErrorHandler errorHandler}
	 * application component will continue processing the error.
	 *
	 * @param CExceptionEvent event parameter
	 */
	public function onException($event)
	{
		$this->raiseEvent('onException',$event);
	}

	/**
	 * Raised when a PHP execution error occurs.
	 *
	 * An event handler can set the {@link CErrorEvent::handled handled}
	 * property of the event parameter to be true to indicate no further error
	 * handling is needed. Otherwise, the {@link getErrorHandler errorHandler}
	 * application component will continue processing the error.
	 *
	 * @param CErrorEvent event parameter
	 */
	public function onError($event)
	{
		$this->raiseEvent('onError',$event);
	}

	/**
	 * Displays the captured PHP error.
	 * This method displays the error in HTML when there is
	 * no active error handler.
	 * @param integer error code
	 * @param string error message
	 * @param string error file
	 * @param string error line
	 */
	public function displayError($code,$message,$file,$line)
	{
		if(SF_DEBUG)
		{
			echo "<h1>PHP Error [$code]</h1>\n";
			echo "<p>$message ($file:$line)</p>\n";
			echo '<pre>';
			debug_print_backtrace();
			echo '</pre>';
		}
		else
		{
			echo "<h1>PHP Error [$code]</h1>\n";
			echo "<p>$message</p>\n";
		}
	}

	/**
	 * Displays the uncaught PHP exception.
	 * This method displays the exception in HTML when there is
	 * no active error handler.
	 * @param Exception the uncaught exception
	 */
	public function displayException($exception)
	{
		if(SF_DEBUG)
		{
			echo '<h1>'.get_class($exception)."</h1>\n";
			echo '<p>'.$exception->getMessage().' ('.$exception->getFile().':'.$exception->getLine().')</p>';
			echo '<pre>'.$exception->getTraceAsString().'</pre>';
		}
		else
		{
			echo '<h1>'.get_class($exception)."</h1>\n";
			echo '<p>'.$exception->getMessage().'</p>';
		}
	}

	/**
	 * Initializes the class autoloader and error handlers.
	 */
	protected function initSystemHandlers()
	{
		if(SF_ENABLE_EXCEPTION_HANDLER)
			set_exception_handler(array($this,'handleException'));
		if(SF_ENABLE_ERROR_HANDLER)
			set_error_handler(array($this,'handleError'),error_reporting());
	}

	/**
	 * Registers the core application components.
	 * @see setComponents
	 */
	protected function registerCoreComponents()
	{
		$components=array(
			'db'=>array(
				'class'=>'CADOModel',
			),
			'errorHandler'=>array(
				'class'=>'CErrorHandler',
			),
			'securityManager'=>array(
				'class'=>'CSecurityManager',
			),
			'errorFileLoger'=>array(
				'class'=>'CFileLogRoute',
			),
		);

		$this->setComponents($components);
	}
}
