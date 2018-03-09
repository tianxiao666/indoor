<?php
/**
 * CController class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.SFframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.SFframework.com/license/
 */


/**
 * CController manages a set of actions which deal with the corresponding user requests.
 *  CONTROLLER FOR WEB
 * Through the actions, CController coordinates the data flow between models and views.
 *
 * When a user requests an action 'XYZ', CController will do one of the following:
 * 1. Method-based action: call method 'actionXYZ' if it exists;
 * 2. Class-based action: create an instance of class 'XYZ' if the class is found in the action class map
 *    (specified via {@link actions()}, and execute the action;
 * 3. Call {@link missingAction()}, which by default will raise a 404 HTTP exception.
 *
 * If the user does not specify an action, CController will run the action specified by
 * {@link defaultAction}, instead.
 *
 * CController may be configured to execute filters before and after running actions.
 * Filters preprocess/postprocess the user request/response and may quit executing actions
 * if needed. They are executed in the order they are specified. If during the execution,
 * any of the filters returns true, the rest filters and the action will no longer get executed.
 *
 * Filters can be individual objects, or methods defined in the controller class.
 * They are specified by overriding {@link filters()} method. The following is an example
 * of the filter specification:
 * <pre>
 * array(
 *     'accessControl - login',
 *     'ajaxOnly + search',
 *     array(
 *         'COutputCache + list',
 *         'duration'=>300,
 *     ),
 * )
 * </pre>
 * The above example declares three filters: accessControl, ajaxOnly, COutputCache. The first two
 * are method-based filters (defined in CController), which refer to filtering methods in the controller class;
 * while the last refers to a object-based filter whose class is 'system.web.widgets.COutputCache' and
 * the 'duration' property is initialized as 300 (s).
 *
 * For method-based filters, a method named 'filterXYZ($filterChain)' in the controller class
 * will be executed, where 'XYZ' stands for the filter name as specified in {@link filters()}.
 * Note, inside the filter method, you must call <code>$filterChain->run()</code> if the action should
 * be executed. Otherwise, the filtering process would stop at this filter.
 *
 * Filters can be specified so that they are executed only when running certain actions.
 * For method-based filters, this is done by using '+' and '-' operators in the filter specification.
 * The '+' operator means the filter runs only when the specified actions are requested;
 * while the '-' operator means the filter runs only when the requested action is not among those actions.
 * For object-based filters, the '+' and '-' operators are following the class name.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CController.php,v 1.5 2011/06/28 05:37:40 yangzb Exp $
 * @package system.web
 * @since 1.0
 */
class CController extends CBaseController
{
	/**
	 * Name of the hidden field storing persistent page states.
	 */
	const STATE_INPUT_NAME='SF_PAGE_STATE';

	/**
	 * @var mixed the name of the layout to be applied to this controller's views.
	 * Defaults to null, meaning the {@link CWebApplication::layout application layout}
	 * is used. If it is an empty string, no layout will be applied.
	 * Since version 1.0.3, the {@link CWebModule::layout module layout} will be used
	 * if the controller belongs to a module and this layout property is null.
	 */
	public $layout;
	/**
	 * @var string the name of the default action. Defaults to 'index'.
	 */
	public $defaultAction='index';
	/**
	 * @var boolean whether page caching is enabled for the current action.
	 * Do not modify the value of this property.
	 * @since 1.0.4
	 */
	public $usePageCaching=false;

	/**
	  * @var oViewRenderer the view render ...
	  */
	public $oViewRenderer ;

	private $_id;
	private $_action;
	private $_cachingStack;
	private $_module;


	/**
	 * @param string id of this controller
	 * @param CWebModule the module that this controller belongs to. This parameter
	 * has been available since version 1.0.3.
	 */
	public function __construct($id,$module=null)
	{
		$this->_id=$id;
		$this->_module=$module;
	}

	/**
	 * Initializes the controller.
	 * This method is called by the application before the controller starts to execute.
	 * You may override this method to perform the needed initialization for the controller.
	 * @since 1.0.1
	 */
	public function init()
	{
	}

	/**
	 * Returns the filter configurations.
	 *
	 * By overriding this method, child classes can specify filters to be applied to actions.
	 *
	 * This method returns an array of filter specifications. Each array element specify a single filter.
	 *
	 * For a method-based filter (called inline filter), it is specified as 'FilterName[ +|- Action1, Action2, ...]',
	 * where the '+' ('-') operators describe which actions should be (should not be) applied with the filter.
	 *
	 * For a class-based filter, it is specified as an array like the following:
	 * <pre>
	 * array(
	 *     'FilterClass[ +|- Action1, Action2, ...]',
	 *     'name1'=>'value1',
	 *     'name2'=>'value2',
	 *     ...
	 * )
	 * </pre>
	 * where the name-value pairs will be used to initialize the properties of the filter.
	 *
	 * Note, in order to inherit filters defined in the parent class, a child class needs to
	 * merge the parent filters with child filters using functions like array_merge().
	 *
	 * @return array a list of filter configurations.
	 * @see CFilter
	 */
	public function filters()
	{
		return array();
	}

	/**
	 * Returns a list of external action classes.
	 * Array keys are action IDs, and array values are the corresponding
	 * action class in dot syntax (e.g. 'edit'=>'application.controllers.article.EditArticle')
	 * or arrays representing the configuration of the actions, such as the following,
	 * <pre>
	 * return array(
	 *     'action1'=>'path.to.Action1Class',
	 *     'action2'=>array(
	 *         'class'=>'path.to.Action2Class',
	 *         'property1'=>'value1',
	 *         'property2'=>'value2',
	 *     ),
	 * );
	 * </pre>
	 * Derived classes may override this method to declare external actions.
	 *
	 * Note, in order to inherit actions defined in the parent class, a child class needs to
	 * merge the parent actions with child actions using functions like array_merge().
	 *
	 * Since version 1.0.1, you may import actions from an action provider
	 * (such as a widget, see {@link CWidget::actions}), like the following:
	 * <pre>
	 * return array(
	 *     ...other actions...
	 *     // import actions declared in ProviderClass::actions()
	 *     // the action IDs will be prefixed with 'pro.'
	 *     'pro.'=>'path.to.ProviderClass',
	 *     // similar as above except that the imported actions are
	 *     // configured with the specified initial property values
	 *     'pro2.'=>array(
	 *         'class'=>'path.to.ProviderClass',
	 *         'action1'=>array(
	 *             'property1'=>'value1',
	 *         ),
	 *         'action2'=>array(
	 *             'property2'=>'value2',
	 *         ),
	 *     ),
	 * )
	 * </pre>
	 *
	 * In the above, we differentiate action providers from other action
	 * declarations by the array keys. For action providers, the array keys
	 * must contain a dot. As a result, an action ID 'pro2.action1' will
	 * be resolved as the 'action1' action declared in the 'ProviderClass'.
	 *
	 * @return array list of external action classes
	 * @see createAction
	 */
	public function actions()
	{
		return array();
	}

	/**
	 * Returns the access rules for this controller.
	 * Override this method if you use the {@link filterAccessControl accessControl} filter.
	 * @return array list of access rules. See {@link CAccessControlFilter} for details about rule specification.
	 */
	public function accessRules()
	{
		return array();
	}

	/**
	 * Runs the named action.
	 * Filters specified via {@link filters()} will be applied.
	 * @param string action ID
	 * @throws CHttpException if the action does not exist or the action name is not proper.
	 * @see filters
	 * @see createAction
	 * @see runAction
	 */
	public function run($actionID)
	{
		if(($action=$this->createAction($actionID))!==null)
		{
			if(($parent=$this->getModule())===null)
				$parent=SF::app();
			if($parent->beforeControllerAction($this,$action))
			{
				$this->runActionWithFilters($action,$this->filters());
				$parent->afterControllerAction($this,$action);
			}
		}
		else
			$this->missingAction($actionID);
	}

	/**
	 * Runs an action with the specified filters.
	 * A filter chain will be created based on the specified filters
	 * and the action will be executed then.
	 * @param CAction the action to be executed.
	 * @param array list of filters to be applied to the action.
	 * @see filters
	 * @see createAction
	 * @see runAction
	 */
	public function runActionWithFilters($action,$filters)
	{
		if(empty($filters))
			$this->runAction($action);
		else
		{
			$priorAction=$this->_action;
			$this->_action=$action;
			CFilterChain::create($this,$action,$filters)->run();
			$this->_action=$priorAction;
		}
	}

	/**
	 * Runs the action after passing through all filters.
	 * This method is invoked by {@link runActionWithFilters} after all possible filters have been executed
	 * and the action starts to run.
	 * @param CAction action to run
	 */
	public function runAction($action)
	{
		$priorAction=$this->_action;
		$this->_action=$action;
		if($this->beforeAction($action))
		{
			$action->run();
			$this->afterAction($action);
		}
		$this->_action=$priorAction;
	}

	/**
	 * Postprocesses the output generated by {@link render()}.
	 * This method is invoked at the end of {@link render()} and {@link renderText()}.
	 * If there are registered client scripts, this method will insert them into the output
	 * at appropriate places. If there are dynamic contents, they will also be inserted.
	 * This method may also save the persistent page states in hidden fields of
	 * stateful forms in the page.
	 * @param string the output generated by the current action
	 * @return string the output that has been processed.
	 */
	public function processOutput($output)
	{
		SF::app()->getClientScript()->render($output);

		// if using page caching, we should delay dynamic output replacement
		if(!$this->usePageCaching && $this->_dynamicOutput)
			$output=$this->processDynamicOutput($output);

		if($this->_pageStates!==null || isset($_POST[self::STATE_INPUT_NAME]))
		{
			$states=$this->savePageStates();
			$output=str_replace(CHtml::pageStateField(''),CHtml::pageStateField($states),$output);
		}

		return $output;
	}


	/**
	 * Creates the action instance based on the action name.
	 * The action can be either an inline action or an object.
	 * The latter is created by looking up the action map specified in {@link actions}.
	 * @param string ID of the action. If empty, the {@link defaultAction default action} will be used.
	 * @return CAction the action instance, null if the action does not exist.
	 * @see actions
	 */
	public function createAction($actionID)
	{
		if($actionID==='')
			$actionID=$this->defaultAction;
		if(method_exists($this,'action'.$actionID) && strcasecmp($actionID,'s')) // we have actions method
			return new CInlineAction($this,$actionID);
		else
			return $this->createActionFromMap($this->actions(),$actionID,$actionID);
	}

	/**
	 * Creates the action instance based on the action map.
	 * This method will check to see if the action ID appears in the given
	 * action map. If so, the corresponding configuration will be used to
	 * create the action instance.
	 * @param array the action map
	 * @param string the action ID that has its prefix stripped off
	 * @param string the originally requested action ID
	 * @param array the action configuration that should be applied on top of the configuration specified in the map
	 * @return CAction the action instance, null if the action does not exist.
	 * @since 1.0.1
	 */
	protected function createActionFromMap($actionMap,$actionID,$requestActionID,$config=array())
	{
		if(($pos=strpos($actionID,'.'))===false && isset($actionMap[$actionID]))
		{
			$baseConfig=is_array($actionMap[$actionID]) ? $actionMap[$actionID] : array('class'=>$actionMap[$actionID]);
			return SF::createComponent(empty($config)?$baseConfig:array_merge($baseConfig,$config),$this,$requestActionID);
		}
		else if($pos===false)
			return null;

		// the action is defined in a provider
		$prefix=substr($actionID,0,$pos+1);
		if(!isset($actionMap[$prefix]))
			return null;
		$actionID=(string)substr($actionID,$pos+1);

		$provider=$actionMap[$prefix];
		if(is_string($provider))
			$providerType=$provider;
		else if(is_array($provider) && isset($provider['class']))
		{
			$providerType=$provider['class'];
			if(isset($provider[$actionID]))
			{
				if(is_string($provider[$actionID]))
					$config=array_merge(array('class'=>$provider[$actionID]),$config);
				else
					$config=array_merge($provider[$actionID],$config);
			}
		}
		else
			throw new CException(SF::t('SF','Object configuration must be an array containing a "class" element.'));

		$class=SF::import($providerType,true);
		$map=call_user_func(array($class,'actions'));

		return $this->createActionFromMap($map,$actionID,$requestActionID,$config);
	}

	/**
	 * Handles the request whose action is not recognized.
	 * This method is invoked when the controller cannot find the requested action.
	 * The default implementation simply throws an exception.
	 * @param string the missing action name
	 * @throws CHttpException whenever this method is invoked
	 */
	public function missingAction($actionID)
	{
		throw new CHttpException(404,SF::t('SF','The system is unable to find the requested action "{action}".',
			array('{action}'=>$actionID==''?$this->defaultAction:$actionID)));
	}

	/**
	 * @return CAction the action currently being executed, null if no active action.
	 */
	public function getAction()
	{
		return $this->_action;
	}

	/**
	 * @param CAction the action currently being executed.
	 */
	public function setAction($value)
	{
		$this->_action=$value;
	}

	/**
	 * @return string ID of the controller
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * @return string the controller ID that is prefixed with the module ID (if any).
	 * @since 1.0.3
	 */
	public function getUniqueId()
	{
		return $this->_module ? $this->_module->getId().'/'.$this->_id : $this->_id;
	}

	/**
	 * @return CWebModule the module that this controller belongs to. It returns null
	 * if the controller does not belong to any module
	 * @since 1.0.3
	 */
	public function getModule()
	{
		return $this->_module;
	}



	/**
	 * Renders a view with a layout.
	 *
	 * This method first calls {@link renderPartial} to render the view (called content view).
	 * It then renders the layout view which may embed the content view at appropriate place.
	 * In the layout view, the content view rendering result can be accessed via variable
	 * <code>$content</code>. At the end, it calls {@link processOutput} to insert scripts
	 * and dynamic contents if they are available.
	 *
	 * By default, the layout view script is "protected/views/layouts/main.php".
	 * This may be customized by changing {@link layout}.
	 *
	 * @param string name of the view to be rendered. See {@link getViewFile} for details
	 * about how the view script is resolved.
	 * @param array data to be extracted into PHP variables and made available to the view script
	 * @param boolean whether the rendering result should be returned instead of being displayed to end users.
	 * @return string the rendering result. Null if the rendering result is not required.
	 * @see renderPartial
	 * @see getLayoutFile
	 */
	public function render($view,$data=null,$return=false)
	{
		//we use smarty instead,here.by haka
		if(($this->oViewRenderer=SF::app()->getViewRenderer())!==null){
			$content=$this->oViewRenderer->renderFile($this,$view,$data,$return);
		}
			
		//clean all output before echo content;
		if ($this->oViewRenderer-> debug == 'false')
            
			ob_clean();
                
		if ($this->oViewRenderer->zip == 'false'  )

			ob_start();

		else
            
			ob_start('ob_gzhandler');
		
		
		if($return)
			return $content;
		else
		{
			header('content-type:text/html;charset=gb2312');
			echo $content; 
		}
	
		ob_end_flush();
	}

	/**
	 * Redirects the browser to the specified URL or route (controller/action).
	 * @param mixed the URL to be redirected to. If the parameter is an array,
	 * the first element must be a route to a controller action and the rest
	 * are GET parameters in name-value pairs.
	 * @param boolean whether to terminate the current application after calling this method
	 * @param integer the HTTP status code. Defaults to 302. See {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html}
	 * for details about HTTP status code. This parameter has been available since version 1.0.4.
	 */
	public function redirect($url,$terminate=true,$statusCode=302)
	{
		if(is_array($url))
		{
			$route=isset($url[0]) ? $url[0] : '';
			$url=$this->createUrl($route,array_splice($url,1));
		}
		SF::app()->getRequest()->redirect($url,$terminate,$statusCode);
	}

	/**
	 * Refreshes the current page.
	 * The effect of this method call is the same as user pressing the
	 * refresh button on the browser (without post data).
	 * @param boolean whether to terminate the current application after calling this method
	 **/
	public function refresh($terminate=true)
	{
		$this->redirect(SF::app()->getRequest()->getUrl(),$terminate);
	}

	/**
	 * Records a method call when an output cache is in effect.
	 * When the content is served from the output cache, the recorded
	 * method will be re-invoked.
	 * @param string a property name of the controller. It refers to an object
	 * whose method is being called. If empty it means the controller itself.
	 * @param string the method name
	 * @param array parameters passed to the method
	 * @see COutputCache
	 */
	public function recordCachingAction($context,$method,$params)
	{
		if($this->_cachingStack) // record only when there is an active output cache
		{
			foreach($this->_cachingStack as $cache)
				$cache->recordAction($context,$method,$params);
		}
	}

	/**
	 * @return CStack stack of {@link COutputCache} objects
	 */
	public function getCachingStack()
	{
		if(!$this->_cachingStack)
			$this->_cachingStack=new CStack;
		return $this->_cachingStack;
	}

	/**
	 * This method is invoked right before an action is to be executed (after all possible filters.)
	 * You may override this method to do last-minute preparation for the action.
	 * @param CAction the action to be executed.
	 * @return boolean whether the action should be executed.
	 */
	protected function beforeAction($action)
	{
		return true;
	}

	/**
	 * This method is invoked right after an action is executed.
	 * You may override this method to do some postprocessing for the action.
	 * @param CAction the action just executed.
	 */
	protected function afterAction($action)
	{
	}

	/**
	 * The filter method for 'postOnly' filter.
	 * This filter reports an error if the applied action is receiving a non-POST request.
	 * @param CFilterChain the filter chain that the filter is on.
	 * @throws CHttpException if the current request is not a POST request
	 */
	public function filterPostOnly($filterChain)
	{
		if(SF::app()->getRequest()->getIsPostRequest())
			$filterChain->run();
		else
			throw new CHttpException(400,SF::t('SF','Your request is not valid.'));
	}

	/**
	 * The filter method for 'ajaxOnly' filter.
	 * This filter reports an error if the applied action is receiving a non-AJAX request.
	 * @param CFilterChain the filter chain that the filter is on.
	 * @throws CHttpException if the current request is not an AJAX request.
	 */
	public function filterAjaxOnly($filterChain)
	{
		if(SF::app()->getRequest()->getIsAjaxRequest())
			$filterChain->run();
		else
			throw new CHttpException(400,SF::t('SF','Your request is not valid.'));
	}

	/**
	 * The filter method for 'accessControl' filter.
	 * This filter is a wrapper of {@link CAccessControlFilter}.
	 * To use this filter, you must override {@link accessRules} method.
	 * @param CFilterChain the filter chain that the filter is on.
	 */
	public function filterAccessControl($filterChain)
	{
		$filter=new CAccessControlFilter;
		$filter->setRules($this->accessRules());
		$filter->filter($filterChain);
	}

	/**
	 * Creates a relative URL for the specified action defined in this controller.
	 * @param string the URL route. This should be in the format of 'ControllerID/ActionID'.
	 * If the ControllerID is not present, the current controller ID will be prefixed to the route.
	 * If the route is empty, it is assumed to be the current action.
	 * Since version 1.0.3, if the controller belongs to a module, the {@link CWebModule::getId module ID}
	 * will be prefixed to the route. (If you do not want the module ID prefix, the route should start with a slash '/'.)
	 * @param array additional GET parameters (name=>value). Both the name and value will be URL-encoded.
	 * If the name is '#', the corresponding value will be treated as an anchor
	 * and will be appended at the end of the URL. This anchor feature has been available since version 1.0.1.
	 * @param string the token separating name-value pairs in the URL.
	 * @return string the constructed URL
	 */
	public function createUrl($route,$params=array(),$ampersand='&')
	{
		if($route==='')
			$route=$this->getId().'/'.$this->getAction()->getId();
		else if(strpos($route,'/')===false)
			$route=$this->getId().'/'.$route;
		if($route[0]!=='/' && ($module=$this->getModule())!==null)
			$route=$module->getId().'/'.$route;
		return SF::app()->createUrl(trim($route,'/'),$params,$ampersand);
	}

}
