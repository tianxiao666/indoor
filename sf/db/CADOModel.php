<?php 
//define('ADODB_ASSOC_CASE' 1);
SF::import('system.library.adodb.*');
require_once ('adodb.inc.php');
require_once ('adodb-exceptions.inc.php');

/** adodb setting **/
global $ADODB_FETCH_MODE;

// ADODB_FETCH_ASSOC is for return result as array(['col1']=>'v0',['col2'] =>'v1')
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

/**
* Represents a data model according to the MVC architecture.
*
* This class provides all the classes extending it with a database
* connection so that classes don't have to worry about that. Later on, the CADOModel
* classes will be used by the corresponding action object.
*/

class CADOModel extends CApplicationComponent
{
	public $dsn;
	public $memCacheHost;
	public $isDebug = false;
	static $dbConn;
	const TRY_CONN_TIME = 2;


	/**
	* So far, it only initializes the connection to the database, using the ADOdb API.
	*/
	function CADOModel()
	{
	}

	
	/***************private function ***************/
	public function &getDBConnection()
	{
		if (self::$dbConn === null)
		{
			$this->initDbConn();
		}
		return self::$dbConn;
	}
	
	private function initDbConn()
	{
		// try the db connect exception.
//		try{
			$dbConn = ADONewConnection($this->dsn);
//		}catch (Exception $e)
//		{
//			$_ENV['tryTime'] = intval($_ENV['tryTime']);
//			$_ENV['tryTime'] = $_ENV['tryTime']++;
//			
//			if ($_ENV['tryTime'] < self::TRY_CONN_TIME && is_file($_SERVER['SCRIPT_FILENAME']))
//			{
//				$oErrFile = SF::app()->getErrorFileLog();
//				$oErrFile->processErrorLogs('db connect exception, try to connect again at catch');
//				//$dbConn = ADONewConnection($this->dsn);
//				include $_SERVER['SCRIPT_FILENAME'];
//				SF::app()->end(1);
//			}
//		}
		if(!$dbConn) {
			throw(new CException( "Fatal error: could not connect to the database!" ));
		}

		// if oracle, change the date format
		if (substr($this->dsn, 0, 4) == 'oci8')
		{
			$dbConn->NLS_DATE_FORMAT =  'RRRR-MM-DD HH24:MI:SS';
			$sql = "ALTER SESSION SET NLS_DATE_FORMAT = 'RRRR-MM-DD HH24:MI:SS'";
			$dbConn->Execute($sql);
		}
		elseif (substr($this->dsn, 0, 5) == 'mysql')
		{
			$dbConn->Execute('SET NAMES GBK;');
		}
		
		// set the db connnect in this application
		if ($this->memCacheHost)
		{
			$dbConn->memCache 		= true;
			$dbConn->memCacheHost	= $this->memCacheHost;
		}
		
		// set the debug config
		if ($this->isDebug)
		{
			$dbConn->debug = 1;
			$GLOBALS['ADODB_OUTP'] = 'logSqlSFLog';
		}
		
		self::$dbConn = $dbConn;
	}
	
	
	public function init()
	{
		parent::init();
//		set_error_handler(array($this,'handleError'),error_reporting());
	}
	
	
	/**
	 * Handles the db connect error, try again when the error happen.
	 *
	 * @param integer the level of the error raised
	 * @param string the error message
	 * @param string the filename that the error was raised in
	 * @param integer the line number the error was raised at
	 */
//	public function handleError($code, $message, $file, $line)
//	{
//		$_ENV['tryTime'] = intval($_ENV['tryTime']);
//		$_ENV['tryTime'] = $_ENV['tryTime']++;
//		$isTryAgain		 = $code && $_ENV['tryTime'] < self::TRY_CONN_TIME 
//							&& strpos("ocilogon() [<a href='function.ocilogon'>function.ocilogon</a>]", $message) !== false;
//		
//		if ($isTryAgain && is_file($_SERVER['SCRIPT_FILENAME']))	// try again
//		{
//			$oErrFile = SF::app()->getErrorFileLog();
//			$oErrFile->processErrorLogs("db connect error, try to connect again at the {$_ENV['tryTime']} time");
//			include $_SERVER['SCRIPT_FILENAME'];
//			SF::app()->end(1);
//		}
//	}
	
}

/**
 * Recover the ADOdb log function.
 */
function logSqlSFLog($message, $newline = true)
{
    SF::log('ADOdb LOG: ' . html_entity_decode($message) );
}
?>

