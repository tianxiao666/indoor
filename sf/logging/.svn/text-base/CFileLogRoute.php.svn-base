<?php
/**
 * CFileLogRoute class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.SFframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.SFframework.com/license/
 */

/**
 * CFileLogRoute records log messages in files.
 *
 * The log files are stored under {@link setLogPath logPath} and the file name
 * is specified by {@link setLogFile logFile}. If the size of the log file is
 * greater than {@link setMaxFileSize maxFileSize} (in kilo-bytes), a rotation
 * is performed, which renames the current log file by suffixing the file name
 * with '.1'. All existing log files are moved backwards one place, i.e., '.2'
 * to '.3', '.1' to '.2'. The property {@link setMaxLogFiles maxLogFiles}
 * specifies how many files to be kept.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CFileLogRoute.php,v 1.5 2011/11/19 08:02:10 yangzb Exp $
 * @package system.logging
 * @since 1.0
 */
class CFileLogRoute extends CLogRoute
{
	/**
	 * @var integer maximum log file size
	 */
	private $_maxFileSize=102400; // in KB
	/**
	 * @var integer number of log files used for rotation
	 */
	private $_maxLogFiles=50;
	/**
	 * @var string directory storing log files
	 */
	private $_logPath;
	/**
	 * @var string log file name
	 */
	private $_logFile='application.log';
	private $_configs=array();

	/**
	 * Initializes the route.
	 * This method is invoked after the route is created by the route manager.
	 */
	public function init()
	{
		parent::init();
		if($this->getLogPath()===null)
			$this->setLogPath(SF::app()->getRuntimePath());
	}

	/**
	 * @return array the currently initialized configs
	 */
	public function getConfigs()
	{
		return SF::app()->getComponent('log_config')->_configs;
	}
	
	/**
	 * @param array list of log_config configurations. Each array element represents
	 */
	public function setConfigs($config)
	{
		foreach($config as $c)
		{
			if(is_string($c))
				$c=array('class'=>$c);
			$this->_configs=$c;
		}
	}
	
	/**
	 * @return string directory storing log files. Defaults to application runtime path.
	 */
	public function getLogPath()
	{
		return $this->_logPath;
	}

	/**
	 * @param string directory for storing log files.
	 * @throws CException if the path is invalid
	 */
	public function setLogPath($value)
	{
		$this->_logPath=realpath($value);
		if($this->_logPath===false || !is_dir($this->_logPath) || !is_writable($this->_logPath))
			throw new CException(SF::t('SF','CFileLogRoute.logPath "{path}" does not point to a valid directory. Make sure the directory exists and is writable by the Web server process.',
				array('{path}'=>$value)));
	}

	/**
	 * @return string log file name. Defaults to 'application.log'.
	 */
	public function getLogFile()
	{
		return $this->_logFile;
	}

	/**
	 * @param string log file name
	 */
	public function setLogFile($value)
	{
		$this->_logFile=$value;
	}

	/**
	 * @return integer maximum log file size in kilo-bytes (KB). Defaults to 1024 (1MB).
	 */
	public function getMaxFileSize()
	{
		return $this->_maxFileSize;
	}

	/**
	 * @param integer maximum log file size in kilo-bytes (KB).
	 */
	public function setMaxFileSize($value)
	{
		if(($this->_maxFileSize=(int)$value)<1)
			$this->_maxFileSize=1;
	}

	/**
	 * @return integer number of files used for rotation. Defaults to 5.
	 */
	public function getMaxLogFiles()
	{
		return $this->_maxLogFiles;
	}

	/**
	 * @param integer number of files used for rotation.
	 */
	public function setMaxLogFiles($value)
	{
		if(($this->_maxLogFiles=(int)$value)<1)
			$this->_maxLogFiles=1;
	}

	/**
	 * Saves log messages in files.
	 * @param array list of log messages
	 */
	protected function processLogs($logs)
	{
		//根据配置项，自定义php日志输出方式
		$configs = $this->getConfigs();
		if($configs['isSplit']&&$configs['log_name'])
			$this->setLogFile($configs['log_name']."_".date ( 'Ymd').".log");
		elseif($configs['isSplit'])
			$this->setLogFile("application_".date ( 'Ymd').".log");
		elseif($configs['log_name'])
			$this->setLogFile($configs['log_name'].".log");
			
		$logFile=$this->getLogPath().DIRECTORY_SEPARATOR.$this->getLogFile();
		if(@filesize($logFile)>$this->getMaxFileSize()*1024)
			$this->rotateFiles();
		foreach($logs as $log)
			error_log($this->formatLogMessage($log[0],$log[1],$log[2],$log[3]),3,$logFile);
	}

	/**
	 * Rotates log files.
	 */
	protected function rotateFiles($file=FALSE)
	{
		// check the file
		if (empty($file) || !is_file($file))
		{
			$file = $this->getLogPath().DIRECTORY_SEPARATOR.$this->getLogFile();
		}
		
		$max  = $this->getMaxLogFiles();
		for($i=$max;$i>0;--$i)
		{
			$rotateFile=$file.'.'.$i;
			if(is_file($rotateFile))
			{
				if($i===$max)
					unlink($rotateFile);
				else
					rename($rotateFile,$file.'.'.($i+1));
			}
		}
		if(is_file($file))
			rename($file,$file.'.1');
	}
	
	
	/**
	 * Saves log error messages in files.
	 * @param array list of log messages
	 */
	public function processErrorLogs($message, $category='application', $errlogFile='error.application')
	{
		//根据配置项，自定义错误日志输出方式
		$configs = $this->getConfigs();
		if($configs['isSplit']&&$configs['err_name'])
			$errlogFile = $configs['err_name'].'_'.date ( 'Ymd').'.application';
		elseif($configs['isSplit'])
			$errlogFile = 'error_'.date ( 'Ymd').'.application';
		elseif($configs['err_name'])
			$errlogFile = $configs['err_name'].".application";
		
		$logFile = $this->getLogPath().DIRECTORY_SEPARATOR.$errlogFile;
		
		// check the size of error file
		if(@filesize($logFile)>$this->getMaxFileSize()*1024)
		{
			$this->rotateFiles($logFile);
		}
			
		// write the error message
		error_log($this->formatLogMessage($message, 'error', $category, time()),3,$logFile);
	}
	
}
