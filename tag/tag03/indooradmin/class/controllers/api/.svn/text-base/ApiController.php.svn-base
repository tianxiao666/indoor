<?php
/**
 * this file is for api, it is the father controller of all api
 */
class ApiController extends Controller {
	private $log = "";
	public function __construct($action = "noaction") {
		$this->log = "\naction start:" . $action;
	}
	/**
	 * bref default action
	 */
	public function actionIndex() {
		print_r ( "This is the default action actionIndex, please change it if you need." );
	}
	/**
	 * create the render data template
	 *
	 * @param booean $isSuccess        	
	 * @param array $content        	
	 * @param string $message        	
	 * @return array
	 */
	protected function createRenderTemplate($isSuccess, $content, $message) {
		return array (
				'success' => $isSuccess,
				'content' => $content,
				'message' => $message 
		);
	}
	/**
	 * render the error json format message when some error happen
	 *
	 * @param string $message        	
	 */
	public function renderErrorJson($message) {
		$this->log = $this->log . "\nsuccess: 0";
		$this->log = $this->log . "\nmessage: " . $message;
		$rederData = $this->createRenderTemplate ( 0, '', $message );
		$this->renderJson ( $rederData );
	}
	/**
	 * render the success result(in json format)
	 *
	 * @param array $content        	
	 */
	public function renderSuccessJson($content) {
		$this->log = $this->log . "\nsuccess:1";
		$rederData = $this->createRenderTemplate ( 1, $content, '' );
		$this->renderJson ( $rederData );
	}
	/**
	 * json ecode and render
	 *
	 * @param array $data        	
	 */
	private function renderJson($data = null) {
		array_walk_recursive ( $data, array (
				$this,
				'jsonGbkToUtf8' 
		) );
		$strJson = json_encode ( $data );
		ob_clean ();
		header ( "Cache-Control: no-cache, must-revalidate" ); // HTTP/1.1
		echo $strJson;
		$this->log = $this->log . "\ncontent: " . $strJson;
		$this->log = $this->log . "\naction end\n";
		SF::log ( $this->log );
		$this->log = null;
		SF::app ()->end ();
	}
	/**
	 * Êý×é×ªJSON
	 *
	 * @param unknown $array        	
	 * @return string
	 */
	protected function arrayToJson($array) {
		array_walk_recursive ( $array, array (
				$this,
				'jsonGbkToUtf8' 
		) );
		return (json_encode ( $array ));
	}
}
