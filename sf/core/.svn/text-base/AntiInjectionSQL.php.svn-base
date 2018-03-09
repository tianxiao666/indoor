<?php

	/**
	 * ¼ì²é·ÀÖ¹SQL×¢Èë
	 *
	 * @author mary <mary@tripdata.com>
	 * @version $Id: AntiInjectionSQL.php,v 1.2 2011/07/25 09:44:49 mary Exp $
	 * @package system
	 * @since 1.0
	 */
	class AntiInjectionSQL extends CApplicationComponent
	{
		
		public static $instance;

		private $_request;

		private $_ref;

		private $escapekeys=array(
					"Passwd"
					);

		private $keywords=array(
					"/select /i"=>"selec£ô ",
					"/ and /i"=>" an£ä ",
					"/;-- /i"=>";£­£­ ",
					"/update /i"=>"updat£å ",
					"/delete /i"=>"delet£å ",
					"/ or /i"=>" o£ò ",
					"/insert /i"=>"in   ser£ô ",
					"/ union /i"=>"unio£î"
					);



		public function __construct()
		{
			$this->_request=$_REQUEST;
			$this->_ref=&$_REQUEST;	
		}
		
		/**
		 * Initializes the application component.
		 * This method overrides the parent implementation by preprocessing
		 * the user request data.
		 */
		public function init()
		{
			parent::init();
			$this->run();
			//SF::log($_REQUEST);
		}
		
		//handle the request data;

		public function handle()
		{
			if(!$this->_request)
				return true;
			//SF::log($this->_ref);
			foreach($this->_request as $key=>$val)
			{
				if (is_array($val)){
					$this->_ref[$key]=is_array($val)?array_map(array($this,'replace'),$val):$this->replace($val);
					SF::log($this->_ref[$key]);
				}else{
					if(!in_array($key,$this->escapekeys))
						$this->_ref[$key]=$this->replace($val);
				}	
			}
			
			
			SF::log($this->_ref);
			

			return true;
		}

		//replace some sql keyword;

		private function replaceSQL($aVal)
		{
			if(!$aVal)
				return $aVal;
			$result=preg_replace(array_keys($this->keywords),array_values($this->keywords),$aVal);
			$result=$result?$result:$aVal;
			return $result;
		}

		//return the raw;

		public function getRaw()
		{
			return $this->_request;
		}

		//static get;

		public static function run()
		{
			if(!isset(AntiInjectionSQL::$instance))
				AntiInjectionSQL::$instance=new AntiInjectionSQL();
			return AntiInjectionSQL::$instance->handle();
		}

		//get raw request array;

		public static function getRawRequest()
		{
			if(isset(AntiInjectionSQL::$instance))
				return AntiInjectionSQL::$instance->getRaw();
			return $_REQUEST;
		}

		

	}
?>