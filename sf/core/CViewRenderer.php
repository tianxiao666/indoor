<?php
/**
 * CViewRenderer class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.SFframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.SFframework.com/license/
 */

/**
 * CViewRenderer is the base class for view renderer classes.
 *
 * A view renderer is an application component that renders views written
 * in a customized syntax.
 *
 * Once installing a view renderer as a 'viewRenderer' application component,
 * the normal view rendering process will be intercepted by the renderer.
 * The renderer will first parse the source view file and then render the
 * the resulting view file.
 *
 * Parsing results are saved as temporary files that may be stored
 * under the application runtime directory or together with the source view file.
 *
 * @author Steve Heyns http://customgothic.com/
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CViewRenderer.php,v 1.1 2009/05/13 06:31:11 haka Exp $
 * @package system.web.renderers
 * @since 1.0
 */
abstract class CViewRenderer extends CApplicationComponent 
{

	/**
	 * Renders ,just render
	 * This method is required by {@link IViewRenderer}.
	 * @param CBaseController the controller or widget who is rendering the view file.
	 * @param string the view file path
	 * @param mixed the data to be passed to the view
	 * @param boolean whether the rendering result should be returned
	 * @return mixed the rendering result, or null if the rendering result is not needed.
	 */
	public function render($context,$sourceFile,$data,$return)
	{
	}

}
