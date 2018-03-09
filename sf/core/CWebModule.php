<?php
/**
 * CWebModule class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.SFframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.SFframework.com/license/
 */

/**
 * CWebModule represents an application module.
 *
 * An application module may be considered as a self-contained sub-application
 * that has its own controllers, models and views and can be reused in a different
 * project as a whole. Controllers inside a module must be accessed with routes
 * that are prefixed with the module ID.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CWebModule.php,v 1.1 2009/05/13 06:31:11 haka Exp $
 * @package system.web
 * @since 1.0.3
 */
class CWebModule extends CModule
{
	/**
	 * @var string the ID of the default controller for this module. Defaults to 'default'.
	 */
	public $defaultController='default';
	/**
	 * @var mixed the layout that is shared by the controllers inside this module.
	 * If a controller has explicitly declared its own {@link CController::layout layout},
	 * this property will be ignored.
	 * If this is null (default), the application's layout or the parent module's layout (if available)
	 * will be used. If this is false, then no layout will be used.
	 */
	public $layout;
	/**
	 * @var array mapping from controller ID to controller configurations.
	 * Pleaser refer to {@link CWebApplication::controllerMap} for more details.
	 */
	public $controllerMap=array();

	private $_controllerPath;
	private $_viewPath;
	private $_layoutPath;


	/**
	 * Returns the name of this module.
	 * The default implementation simply returns {@link id}.
	 * You may override this method to customize the name of this module.
	 * @return string the name of this module.
	 */
	public function getName()
	{
		return basename($this->getId());
	}

	/**
	 * Returns the description of this module.
	 * The default implementation returns an empty string.
	 * You may override this method to customize the description of this module.
	 * @return string the description of this module.
	 */
	public function getDescription()
	{
		return '';
	}

	/**
	 * Returns the version of this module.
	 * The default implementation returns '1.0'.
	 * You may override this method to customize the version of this module.
	 * @return string the version of this module.
	 */
	public function getVersion()
	{
		return '1.0';
	}

	/**
	 * @return string the directory that contains the controller classes. Defaults to 'protected/controllers'.
	 */
	public function getControllerPath()
	{
		if($this->_controllerPath!==null)
			return $this->_controllerPath;
		else
			return $this->_controllerPath=$this->getBasePath().DIRECTORY_SEPARATOR.'controllers';
	}

	/**
	 * @param string the directory that contains the controller classes.
	 * @throws CException if the directory is invalid
	 */
	public function setControllerPath($value)
	{
		if(($this->_controllerPath=realpath($value))===false || !is_dir($this->_controllerPath))
			throw new CException(SF::t('SF','The controller path "{path}" is not a valid directory.',
				array('{path}'=>$value)));
	}

	/**
	 * @return string the root directory of view files. Defaults to 'protected/views'.
	 */
	public function getViewPath()
	{
		if($this->_viewPath!==null)
			return $this->_viewPath;
		else
			return $this->_viewPath=$this->getBasePath().DIRECTORY_SEPARATOR.'views';
	}

	/**
	 * @param string the root directory of view files.
	 * @throws CException if the directory does not exist.
	 */
	public function setViewPath($path)
	{
		if(($this->_viewPath=realpath($path))===false || !is_dir($this->_viewPath))
			throw new CException(SF::t('SF','The view path "{path}" is not a valid directory.',
				array('{path}'=>$path)));
	}

	/**
	 * @return string the root directory of layout files. Defaults to 'protected/views/layouts'.
	 */
	public function getLayoutPath()
	{
		if($this->_layoutPath!==null)
			return $this->_layoutPath;
		else
			return $this->_layoutPath=$this->getViewPath().DIRECTORY_SEPARATOR.'layouts';
	}

	/**
	 * @param string the root directory of layout files.
	 * @throws CException if the directory does not exist.
	 */
	public function setLayoutPath($path)
	{
		if(($this->_layoutPath=realpath($path))===false || !is_dir($this->_layoutPath))
			throw new CException(SF::t('SF','The layout path "{path}" is not a valid directory.',
				array('{path}'=>$path)));
	}

	/**
	 * The pre-filter for controller actions.
	 * This method is invoked before the currently requested controller action and all its filters
	 * are executed. You may override this method in the following way:
	 * <pre>
	 * if(parent::beforeControllerAction($controller,$action))
	 * {
	 *     // your code
	 *     return true;
	 * }
	 * else
	 *     return false;
	 * </pre>
	 * @param CController the controller
	 * @param CAction the action
	 * @return boolean whether the action should be executed.
	 * @since 1.0.4
	 */
	public function beforeControllerAction($controller,$action)
	{
		if(($parent=$this->getParentModule())===null)
			$parent=SF::app();
		return $parent->beforeControllerAction($controller,$action);
	}

	/**
	 * The post-filter for controller actions.
	 * This method is invoked after the currently requested controller action and all its filters
	 * are executed. If you override this method, make sure you call the parent implementation at the end.
	 * @param CController the controller
	 * @param CAction the action
	 * @since 1.0.4
	 */
	public function afterControllerAction($controller,$action)
	{
		if(($parent=$this->getParentModule())===null)
			$parent=SF::app();
		$parent->afterControllerAction($controller,$action);
	}
}