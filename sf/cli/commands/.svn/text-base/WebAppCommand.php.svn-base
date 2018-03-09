<?php
/**
 * WebAppCommand class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 SF Software LLC
 * @license http://www.yiiframework.com/license/
 * @version $Id: WebAppCommand.php,v 1.1 2010/03/16 08:05:37 liujz Exp $
 */

/**
 * WebAppCommand creates an SF Web application at the specified location.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: WebAppCommand.php,v 1.1 2010/03/16 08:05:37 liujz Exp $
 * @package system.cli.commands
 * @since 1.0
 */
class WebAppCommand extends CConsoleCommand
{
	private $_rootPath;

	public function getHelp()
	{
		return <<<EOD
USAGE
  SFc webapp <app-path>

DESCRIPTION
  This command generates an SF Web Application at the specified location.

PARAMETERS
 * app-path: required, the directory where the new application will be created.
   If the directory does not exist, it will be created. After the application
   is created, please make sure the directory can be accessed by Web users.

EOD;
	}

	/**
	 * Execute the action.
	 * @param array command line parameters specific for this command
	 */
	public function run($args)
	{
		if(!isset($args[0]))
			$this->usageError('the Web application location is not specified.');
		$path=strtr($args[0],'/\\',DIRECTORY_SEPARATOR);
		if(strpos($path,DIRECTORY_SEPARATOR)===false)
			$path='.'.DIRECTORY_SEPARATOR.$path;
		$dir=rtrim(realpath(dirname($path)),'\\/');
		if($dir===false || !is_dir($dir))
			$this->usageError("The directory '$path' is not valid. Please make sure the parent directory exists.");
		if(basename($path)==='.')
			$this->_rootPath=$path=$dir;
		else
			$this->_rootPath=$path=$dir.DIRECTORY_SEPARATOR.basename($path);
		echo "Create a Web application under '$path'? [Yes|No] ";
		if(!strncasecmp(trim(fgets(STDIN)),'y',1))
		{
			$sourceDir=realpath(dirname(__FILE__).'/../views/webapp');
			if($sourceDir===false)
				die('Unable to locate the source directory.');
			$list=$this->buildFileList($sourceDir,$path);
			
			/** not need by now **/
//			$list['index.php']['callback']=array($this,'generateIndex');
//			$list['index-test.php']['callback']=array($this,'generateIndex');
//			$list['tests/bootstrap.php']['callback']=array($this,'generateTestBoostrap');
//			$list['SFc.php']['callback']=array($this,'generateSFc');
			$this->copyFiles($list);
			@chmod($path.'/adodb_cache',0777);
			@chmod($path.'/autorun',0777);
			@chmod($path.'/session_file',0777);
			@chmod($path.'/tmp/templateCompile',0777);
			@chmod($path.'/tmp/cache',0777);
			@chmod($path.'/SFc',0755);
			echo "\nYour application has been created successfully under {$path}.\n";
		}
	}

	public function generateIndex($source,$params)
	{
		$content=file_get_contents($source);
		$SF=realpath(dirname(__FILE__).'/../../SF.php');
		$SF=$this->getRelativePath($SF,$this->_rootPath.DIRECTORY_SEPARATOR.'index.php');
		$SF=str_replace('\\','\\\\',$SF);
		return preg_replace('/\$SF\s*=(.*?);/',"\$SF=$SF;",$content);
	}

	public function generateTestBoostrap($source,$params)
	{
		$content=file_get_contents($source);
		$SF=realpath(dirname(__FILE__).'/../../SFt.php');
		$SF=$this->getRelativePath($SF,$this->_rootPath.DIRECTORY_SEPARATOR.'protected'.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'bootstrap.php');
		$SF=str_replace('\\','\\\\',$SF);
		return preg_replace('/\$SFt\s*=(.*?);/',"\$SFt=$SF;",$content);
	}

	public function generateSFc($source,$params)
	{
		$content=file_get_contents($source);
		$SFc=realpath(dirname(__FILE__).'/../../SFc.php');
		$SFc=$this->getRelativePath($SFc,$this->_rootPath.DIRECTORY_SEPARATOR.'protected'.DIRECTORY_SEPARATOR.'SFc.php');
		$SFc=str_replace('\\','\\\\',$SFc);
		return preg_replace('/\$SFc\s*=(.*?);/',"\$SFc=$SFc;",$content);
	}

	protected function getRelativePath($path1,$path2)
	{
		$segs1=explode(DIRECTORY_SEPARATOR,$path1);
		$segs2=explode(DIRECTORY_SEPARATOR,$path2);
		$n1=count($segs1);
		$n2=count($segs2);
		$common='';
		for($i=0;$i<$n1 && $i<$n2;++$i)
		{
			if($segs1[$i]===$segs2[$i])
				$common.=($i?DIRECTORY_SEPARATOR:'').$segs1[$i];
			else
				break;
		}
		if($i===0)
			return "'".$path1."'";
		$up='';
		for($j=$i;$j<$n2-1;++$j)
			$up.='/..';
		for(;$i<$n1-1;++$i)
			$up.='/'.$segs1[$i];

		return 'dirname(__FILE__).\''.$up.'/'.basename($path1).'\'';
	}
}