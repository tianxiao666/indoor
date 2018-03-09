<?php
/**
 * ControllerCommand class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 SF Software LLC
 * @license http://www.yiiframework.com/license/
 * @version $Id: ControllerCommand.php,v 1.1 2010/03/16 08:05:37 liujz Exp $
 */

/**
 * ControllerCommand generates a controller class.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: ControllerCommand.php,v 1.1 2010/03/16 08:05:37 liujz Exp $
 * @package system.cli.commands.shell
 * @since 1.0
 */
class ControllerCommand extends CConsoleCommand
{
	/**
	 * @var string the directory that contains templates for the model command.
	 * Defaults to null, meaning using 'framework/cli/views/shell/controller'.
	 * If you set this path and some views are missing in the directory,
	 * the default views will be used.
	 */
	public $templatePath;

	public function getHelp()
	{
		return <<<EOD
USAGE
  controller <controller-ID> [action-ID] ...

DESCRIPTION
  This command generates a controller and views associated with
  the specified actions.

PARAMETERS
 * controller-ID: required, controller ID, e.g., 'post'.
   If the controller should be located under a subdirectory,
   please specify the controller ID as 'path/to/ControllerID',
   e.g., 'admin/user'.

   If the controller belongs to a module, please specify
   the controller ID as 'ModuleID/ControllerID' or
   'ModuleID/path/to/Controller' (assuming the controller is
   under a subdirectory of that module).

 * action-ID: optional, action ID. You may supply one or several
   action IDs. A default 'index' action will always be generated.

EXAMPLES
 * Generates the 'post' controller:
        controller post

 * Generates the 'post' controller with additional actions 'contact'
   and 'about':
        controller post contact about

 * Generates the 'post' controller which should be located under
   the 'admin' subdirectory of the base controller path:
        controller admin/post

 * Generates the 'post' controller which should belong to
   the 'admin' module:
        controller admin/post

NOTE: in the last two examples, the commands are the same, but
the generated controller file is located under different directories.
SF is able to detect whether 'admin' refers to a module or a subdirectory.

EOD;
	}

	/**
	 * Execute the action.
	 * @param array command line parameters specific for this command
	 */
	public function run($args)
	{
		if(!isset($args[0]))
		{
			echo "Error: controller name is required.\n";
			echo $this->getHelp();
			return;
		}

		$module=SF::app();
		$controllerID=$args[0];
		if(($pos=strrpos($controllerID,'/'))===false)
		{
			$controllerClass=ucfirst($controllerID).'Controller';
			$controllerFile=$module->controllerPath.DIRECTORY_SEPARATOR.$controllerClass.'.php';
			$controllerID[0]=strtolower($controllerID[0]);
		}
		else
		{
			$last=substr($controllerID,$pos+1);
			$last[0]=strtolower($last);
			$pos2=strpos($controllerID,'/');
			$first=substr($controllerID,0,$pos2);
			$middle=$pos===$pos2?'':substr($controllerID,$pos2+1,$pos-$pos2);

			$controllerClass=ucfirst($last).'Controller';
			$controllerFile=($middle===''?'':$middle.'/').$controllerClass.'.php';
			$controllerID=$middle===''?$last:$middle.'/'.$last;
			if(($m=SF::app()->getModule($first))!==null)
				$module=$m;
			else
			{
				$controllerFile=$first.'/'.$controllerClass.'.php';
				$controllerID=$first.'/'.$controllerID;
			}

			$controllerFile=$module->controllerPath.DIRECTORY_SEPARATOR.str_replace('/',DIRECTORY_SEPARATOR,$controllerFile);
		}

		$args[]='index';
		$actions=array_unique(array_splice($args,1));

		$entry_name = strtolower(basename($module->controllerPath));
		$parentClass=ucfirst($entry_name).'Controller';
		$templatePath=$this->templatePath===null?SF_PATH.'/cli/views/shell/controller':$this->templatePath;

		$list=array(
			basename($controllerFile)=>array(
				'source'=>$templatePath.DIRECTORY_SEPARATOR.'controller.php',
				'target'=>$controllerFile,
				'callback'=>array($this,'generateController'),
				'params'=>array($controllerClass, $actions, $parentClass),
			),
		);

		$viewPath=SF::app()->getViewRenderer()->template_dir;
		foreach($actions as $name)
		{
			$list[$name.'.html']=array(
				'source'=>$templatePath.DIRECTORY_SEPARATOR.'view.php',
				'target'=>$viewPath.DIRECTORY_SEPARATOR.$controllerID.'_'.$name.'.html',
				'callback'=>array($this,'generateAction'),
				'params'=>array('controller'=>$controllerClass, 'action'=>$name),
			);
		}

		$this->copyFiles($list);

		if($module instanceof CWebModule)
			$moduleID=$module->id.'/';
		else
			$moduleID='';

		echo <<<EOD

Controller '{$controllerID}' has been created in the following file:
    $controllerFile

You may access it in the browser using the following URL:
    http://hostname/path/to/{$entry_name}.php?r={$moduleID}{$controllerID}

EOD;
	}

	public function generateController($source,$params)
	{
		if(!is_file($source))  // fall back to default ones
			$source=SF_PATH.'/cli/views/shell/controller/'.basename($source);
		return $this->renderFile($source,array('className'=>$params[0],'actions'=>$params[1],'parentClass'=>$params[2]),true);
	}

	public function generateAction($source,$params)
	{
		if(!is_file($source))  // fall back to default ones
			$source=SF_PATH.'/cli/views/shell/controller/'.basename($source);
		return $this->renderFile($source,$params,true);
	}
}