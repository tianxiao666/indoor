<?php

	/**
	 * �����ʵ�ַ�Ƿ��xss��������
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
		 * �ⲿ���õķ���
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
		 * ������
		 *
		 * @param nothing.
		 * @return result of handle
		 * @throws nothing.
		 */
		public function handle(){
		
			if(!$this->keyArray)
				return false;
			
			//��֤keyArray����Ԫ��
			foreach($this->keyArray as $source){
				if($_SERVER[$source]){
					$this->filterUrlXss($_SERVER[$source]);
				}
			}
			
			return true;
			 
		}

		/**
		 * ���ָ���������Ƿ��зǷ�����,������ʾ�ǷǷ�����ֹͣ����
		 *
		 * @param (String)sourceVal Ҫ����url.
		 * @return result of handle
		 * @throws nothing.
		 */
		public function filterUrlXss($sourceVal){
			if(!empty($sourceVal)) {
				$temp = urldecode($sourceVal);//ת��
				if(strpos($temp, '<') !== false || strpos($temp, '"') !== false){
					/*echo "<br><br><b>������ʷǷ���ַ</b><br>";
					die(); */
					$oErrFile = SF::app()->getErrorFileLog();
					$oErrFile->processErrorLogs("xss ���� , application end");
					SF::app()->end();
				}
			}
		}
				
}
	
?>
