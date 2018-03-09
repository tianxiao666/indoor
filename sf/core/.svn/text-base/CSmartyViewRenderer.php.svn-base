<?php

/**
 * CSmartyViewrenderer is the class for view renderer classes using SMARTY.
 *
 * @author haka
 * @package system.web.renderers
 * @since 1.0
 */
class CSmartyViewRenderer extends CViewRenderer
{
	public $tmpl_suffix;
	public $template_dir;
	public $compile_dir;
	public $caching = true;
	public $cache_dir;
	public $oSmarty;
	public $outBuffer;
	public $debug;
	public $zip;

	/**
	 *	Constructor.Just do some init job.
	 */
	public function __construct()
	{
	}

	public function init()
	{
		$this->oSmarty = new Smarty;

		$this->oSmarty->caching			= $this->caching;
		$this->oSmarty->cache_dir    	= $this->cache_dir;
		$this->oSmarty->template_dir 	= $this->template_dir;
		$this->oSmarty->compile_dir  	= $this->compile_dir;
		//$this->oSmarty->tmpl_suffix		= $this->tmpl_suffix;
		// enable the security settings
		$this->oSmarty->php_handling 	= false;
		$this->oSmarty->compile_check 	= true;
		$this->oSmarty->use_sub_dirs 	= true;
		$this->oSmarty->force_compile	= '1';
	}



	/**
	 * Generates the resulting view file path.
	 * @param string source view file path
	 * @return string resulting view file path
	 */
	protected function getViewFile($file)
	{
		return $this->template_dir."/".$file.".".$this->tmpl_suffix;
	}


	/**
	 * Renders a view file.must impl it.
	 * This method is inherit from parent class and define in the IViewRenderer
	 * This method is required by {@link IViewRenderer}.
	 * @param CBaseController the controller or widget who is rendering the view file.
	 * @param string the view file path
	 * @param mixed the data to be passed to the view
	 * @param boolean whether the rendering result should be returned
	 * @return mixed the rendering result, or null if the rendering result is not needed.
	 */
	public function renderFile($context,$sourceFile,$data,$return)
	{
		//assign value
		
		$this->oSmarty->assign($data);

		//fetch it.
		return $this->oSmarty->fetch( $this->getViewFile($sourceFile));
	}


}

?>
