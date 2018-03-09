<?php
/**
 * SF command line script file.
 *
 * This script is meant to be run on command line to execute
 * one of the pre-defined console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 SF Software LLC
 * @license http://www.yiiframework.com/license/
 * @version $Id: SFc.php,v 1.1 2010/03/12 09:57:37 liujz Exp $
 */

// fix for fcgi
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

defined('SF_DEBUG') or define('SF_DEBUG',true);
error_reporting(E_ALL ^ E_NOTICE);

require_once(dirname(__FILE__).'/SF.php');

if(isset($config))
{
	$app=SF::createConsoleApplication($config);
	$app->commandRunner->addCommands(SF_PATH.'/cli/commands');
}
else
	$app=SF::createConsoleApplication(array('basePath'=>dirname(__FILE__).'/cli'));

$app->run();