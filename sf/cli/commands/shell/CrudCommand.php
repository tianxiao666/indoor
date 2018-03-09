<?php
/**
 * CrudCommand class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 SF Software LLC
 * @license http://www.yiiframework.com/license/
 * @version $Id: CrudCommand.php,v 1.4 2010/05/07 09:37:42 liujz Exp $
 */

/**
 * CrudCommand generates code implementing CRUD operations.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CrudCommand.php,v 1.4 2010/05/07 09:37:42 liujz Exp $
 * @package system.cli.commands.shell
 * @since 1.0
 */
class CrudCommand extends CConsoleCommand
{
	/**
	 * @var string the directory that contains templates for crud commands.
	 * Defaults to null, meaning using 'framework/cli/views/shell/crud'.
	 * If you set this path and some views are missing in the directory,
	 * the default views will be used.
	 */
	public $templatePath;
	/**
	 * @var string the directory that contains functional test classes.
	 * Defaults to null, meaning using 'protected/tests/functional'.
	 * If this is false, it means functional test file should NOT be generated.
	 */
	public $functionalTestPath;
	/**
	 * @var array list of actions to be created. Each action must be associated with a template file with the same name.
	 */
//	public $actions=array('create','update','index','view','admin','_form','_view');
	public $actions=array('index','create','update');

	public function getHelp()
	{
		return <<<EOD
USAGE
  crud <model-class> [controller-ID] [tplname]...

DESCRIPTION
  This command generates a controller and views that accomplish
  CRUD operations for the specified data model.

PARAMETERS
 * model-class: required, the name of the data model class. This can
   also be specified as a path alias (e.g. application.models.Post).
   If the model class belongs to a module, it should be specified
   as 'ModuleID.models.ClassName'.

 * controller-ID: optional, the controller ID (e.g. 'post').
   If this is not specified, the model class name will be used
   as the controller ID. In this case, if the model belongs to
   a module, the controller will also be created under the same
   module.

   If the controller should be located under a subdirectory,
   please specify the controller ID as 'path/to/ControllerID'
   (e.g. 'admin/user').

   If the controller belongs to a module (different from the module
   that the model belongs to), please specify the controller ID
   as 'ModuleID/ControllerID' or 'ModuleID/path/to/Controller'.
   
 * tplname: optional, the template name (e.g. 'post').
   If this is not specified, the cli/views/shell/crud template will be used

EXAMPLES
 * Generates CRUD for the Post model:
        crud Post

 * Generates CRUD for the Post model which belongs to module 'admin':
        crud admin.models.Post

 * Generates CRUD for the Post model. The generated controller should
   belong to module 'admin', but not the model class:
        crud Post admin/post vatpl

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
			echo "Error: data model class is required.\n";
			echo $this->getHelp();
			return;
		}
		$module=SF::app();
		$modelClass=$args[0];
		if(($pos=strpos($modelClass,'.'))===false)
			$modelClass='application.models.'.$modelClass;
		else
		{
			$id=substr($modelClass,0,$pos);
			if(($m=SF::app()->getModule($id))!==null)
				$module=$m;
		}
		$modelClass=SF::import($modelClass);

		if(isset($args[1]))
		{
			$controllerID=$args[1];
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
					$controllerFile=$first.'/'.$controllerFile;
					$controllerID=$first.'/'.$controllerID;
				}

				$controllerFile=$module->controllerPath.DIRECTORY_SEPARATOR.str_replace('/',DIRECTORY_SEPARATOR,$controllerFile);
			}
		}
		else
		{
			$controllerID=$modelClass;
			$controllerClass=ucfirst($controllerID).'Controller';
			$controllerFile=$module->controllerPath.DIRECTORY_SEPARATOR.$controllerClass.'.php';
			$controllerID[0]=strtolower($controllerID[0]);
		}

		$entry_name 	= strtolower(basename($module->controllerPath));
		$parentClass	= ucfirst($entry_name).'Controller';
		$templatePath	= $this->templatePath===null?SF_PATH.'/cli/views/shell/crud':$this->templatePath;
//		$functionalTestPath=$this->functionalTestPath===null?SF::getPathOfAlias('application.tests.functional'):$this->functionalTestPath;

//		$viewPath=$module->viewPath.DIRECTORY_SEPARATOR.str_replace('.',DIRECTORY_SEPARATOR,$controllerID);
		$viewPath=SF::app()->getViewRenderer()->template_dir;
//		$fixtureName=$this->pluralize($modelClass);
//		$fixtureName[0]=strtolower($fixtureName);
		$list=array(
			basename($controllerFile)=>array(
				'source'=>$templatePath.'/controller.php',
				'target'=>$controllerFile,
				'callback'=>array($this,'generateController'),
				'params'=>array($controllerClass, $modelClass, $parentClass),
			),
		);

//		if($functionalTestPath!==false)
//		{
//			$list[$modelClass.'Test.php']=array(
//				'source'=>$templatePath.'/test.php',
//				'target'=>$functionalTestPath.DIRECTORY_SEPARATOR.$modelClass.'Test.php',
//				'callback'=>array($this,'generateTest'),
//				'params'=>array($controllerID,$fixtureName,$modelClass),
//			);
//		}

		$templateViewPath = $args[2] ? $templatePath.DIRECTORY_SEPARATOR.$args[2] : $templatePath;
		foreach($this->actions as $action)
		{
			$list[$action.'.html']=array(
				'source'=>$templateViewPath.DIRECTORY_SEPARATOR.$action.'.php',
				'target'=>$viewPath.DIRECTORY_SEPARATOR.strtolower($controllerID).'_'.$action.'.html',
				'callback'=>array($this,'generateView'),
				'params'=>array($modelClass,$controllerID) ,
			);
		}

		$this->copyFiles($list);

		if($module instanceof CWebModule)
			$moduleID=$module->id.'/';
		else
			$moduleID='';

		echo "\nCrud '{$controllerID}' has been successfully created. You may access it via:\n";
		echo "http://hostname/path/to/index.php?r={$moduleID}{$controllerID}\n";
	}

	public function generateController($source,$params)
	{
		$model 	 = new $params[1]();
		$id		 = $model->getThePrimaryKey();
		$columns = $model->GetAttributeNames();
		
		if(!is_file($source))  // fall back to default ones
			$source=SF_PATH.'/cli/views/shell/crud/'.basename($source);
		return $this->renderFile($source,array(
			'controllerClass'	=> $params[0],
			'modelClass'		=> $params[1],
			'parentClass'		=> $params[2],
			'columns'			=> $columns,
			'ID'				=> $id),true);
	}

	public function generateView($source,$params)
	{
		$model 	 = new $params[0]();
		$id		 = $model->getThePrimaryKey();
		$columns = $model->GetAttributeNames();
		
		if(!is_file($source))  // fall back to default ones
			$source=SF_PATH.'/cli/views/shell/crud/'.basename($source);
		return $this->renderFile($source,array(
			'ID'			=> $id ? $id : 'ID',
			'modelClass'	=> $params[0],
			'controllerId'	=> $params[1],
			'columns'		=> $columns),true);
	}

//	public function generateTest($source,$params)
//	{
//		list($controllerID,$fixtureName,$modelClass)=$params;
//		if(!is_file($source))  // fall back to default ones
//			$source=SF_PATH.'/cli/views/shell/crud/'.basename($source);
//		return $this->renderFile($source, array(
//			'controllerID'=>$controllerID,
//			'fixtureName'=>$fixtureName,
//			'modelClass'=>$modelClass,
//		),true);
//	}
//
//	public function generateInputLabel($modelClass,$column)
//	{
//		return "CHtml::activeLabelEx(\$model,'{$column->name}')";
//	}

//	public function generateInputField($modelClass,$column)
//	{
//		if($column->type==='boolean')
//			return "CHtml::activeCheckBox(\$model,'{$column->name}')";
//		else if(stripos($column->dbType,'text')!==false)
//			return "CHtml::activeTextArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50))";
//		else
//		{
//			if(preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
//				$inputField='activePasswordField';
//			else
//				$inputField='activeTextField';
//
//			if($column->type!=='string' || $column->size===null)
//				return "CHtml::{$inputField}(\$model,'{$column->name}')";
//			else
//			{
//				if(($size=$maxLength=$column->size)>60)
//					$size=60;
//				return "CHtml::{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
//			}
//		}
//	}

//	public function guessNameColumn($columns)
//	{
//		foreach($columns as $column)
//		{
//			if(!strcasecmp($column->name,'name'))
//				return $column->name;
//		}
//		foreach($columns as $column)
//		{
//			if(!strcasecmp($column->name,'title'))
//				return $column->name;
//		}
//		foreach($columns as $column)
//		{
//			if($column->isPrimaryKey)
//				return $column->name;
//		}
//		return 'id';
//	}
//
//	public function class2name($className,$pluralize=false)
//	{
//		if($pluralize)
//			$className=$this->pluralize($className);
//		return ucwords(trim(strtolower(str_replace(array('-','_'),' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $className)))));
//	}
}
