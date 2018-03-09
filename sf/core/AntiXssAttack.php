<?php

	/**
	 * 检查访问地址是否带xss攻击代码
	 *
	 * @author mary <mary@tripdata.com>
	 * @version $Id: AntiXssAttack.php,v 1.1 2011/07/23 11:27:39 mary Exp $
	 * @package system
	 * @since 1.0
	 */
	class AntiXssAttack extends CApplicationComponent
	{
		//single instance
		public static $instance;
		
		
		//$_SERVER key to filter
		private $keyArray=array(
					"REQUEST_URI"
					);

		//Contructor
		public function AntiXssAttack(){
			
		}

		/**
		 * 外部调用的方法
		 *
		 * @param nothing.
		 * @return result of handle
		 * @throws nothing.
		 */
		public static function init(){
			
			parent::init();
			//new single instance
			if(!isset(AntiXssAttack::$instance))
				AntiXssAttack::$instance=new AntiXssAttack();

			return AntiXssAttack::$instance->handle();
			
		}

		/**
		 * 处理方法
		 *
		 * @param nothing.
		 * @return result of handle
		 * @throws nothing.
		 */
		public function handle(){
		
			if(!$this->keyArray)
				return false;
			
			//验证keyArray所有元素
			foreach($this->keyArray as $source){
				if($_SERVER[$source]){
					$this->filterUrlXss($_SERVER[$source]);
				}
			}
			
			return true;
			 
		}

		/**
		 * 检查指定参数中是否含有非法代码,有则提示是非法请求并停止程序
		 *
		 * @param (String)sourceVal 要检查的url.
		 * @return result of handle
		 * @throws nothing.
		 */
		public function filterUrlXss($sourceVal){
			if(!empty($sourceVal)) {
				$temp = urldecode($sourceVal);//转码
				if(strpos($temp, '<') !== false || strpos($temp, '"') !== false){
					/*echo "<br><br><b>请勿访问非法地址</b><br>";
					die(); */
					$oErrFile = SF::app()->getErrorFileLog();
					$oErrFile->processErrorLogs("xss 攻击 , application end");
					SF::app()->end();
				}
			}
		}
				
}
	
?>
