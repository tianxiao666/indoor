<?php
/**
 * CBaseController class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author haka
 * @link http://www.SFframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.SFframework.com/license/
 */


/**
 * CBaseController is the base class for {@link CController} and {@link CWidget}.
 * BY HAKA,2009 05,for we using smarty for our template engine,so we will not support widget and clip and cache here.
 *
 * It provides the common functionalities shared by controllers who need to render views.
 *
 * CBaseController also implements the support for the following features:
 * <ul>
 * <li>{@link CClipWidget Clips} : a clip is a piece of captured output that can be inserted elsewhere.</li>
 * <li>{@link CWidget Widgets} : a widget is a self-contained sub-controller with its own view and model.</li>
 * <li>{@link COutputCache Fragment cache} : fragment cache selectively caches a portion of the output.</li>
 * </ul>
 *
 * To use a widget in a view, use the following in the view:
 * <pre>
 * $this->widget('path.to.widgetClass',array('property1'=>'value1',...));
 * </pre>
 * or
 * <pre>
 * $this->beginWidget('path.to.widgetClass',array('property1'=>'value1',...));
 * // ... display other contents here
 * $this->endWidget();
 * </pre>
 *
 * To create a clip, use the following:
 * <pre>
 * $this->beginClip('clipID');
 * // ... display the clip contents
 * $this->endClip();
 * </pre>
 * Then, in a different view or place, the captured clip can be inserted as:
 * <pre>
 * echo $this->clips['clipID'];
 * </pre>
 *
 * To use fragment cache, do as follows,
 * <pre>
 * if($this->beginCache('cacheID',array('property1'=>'value1',...))
 * {
 *     // ... display the content to be cached here
 *    $this->endCache();
 * }
 * </pre>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CBaseController.php,v 1.1 2009/05/13 06:31:11 haka Exp $
 * @package system.web
 * @since 1.0
 */
abstract class CBaseController extends CComponent
{

	/**
	  *  get the actions the controller have.
	  */
	public function actions()
	{
		return array();
	}


	/**
	  *  Init the controller.
	  */
	public function init()
	{
	}


	/**
	  *  run the action
	  */
	public function run($actionID)
	{
	}



}
