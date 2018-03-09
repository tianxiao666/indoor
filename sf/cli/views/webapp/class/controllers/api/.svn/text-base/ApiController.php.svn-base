<?php
/**
 * this file is for api, it is the father controller of all api
 */

class APIController extends Controller
{
	const EXPIRE_TIME = 30;		// the request expire time, 10 means 10 sencond
	
	/**
	 * \bref default action
	 */
	public function actionIndex()
	{
		print_r("This is the default action actionIndex, please change it if you need.");
	}
	
	
	/**
	 * validate the request data:prod_code call_time and sig
	 * @return array, the validated data in gbk encoding
	 */
	public function validateData($type = 'get')
	{
		$curtime	= time();
		$incomeData = '';
				
		SF::log('==========API income data ==========');
		if ($type == 'get')
		{
			SF::log($_GET);
			
			$incomeData = $_GET;
			unset($incomeData[ROUT_VAR]);
		}
		else 
		{
			SF::log($_POST);
			$incomeData = $_POST;
		}
				
		$data 	 = $this->checkRequestFormat($type);	
		$between = ($curtime - $data['call_time']);
		SF::log('========== translate data ==========');
		SF::log($data);
		SF::log("--current time = $curtime call time = {$data['call_time']} between = $between EXPIRE_TIME=".self::EXPIRE_TIME);
		
		// check call_time
		if ($curtime - $data['call_time'] > self::EXPIRE_TIME)
		{
			$this->renderErrorJson("请求时间超时");
		}
		
		// 验证 sp_code
		if (empty($data['sp_code']))
		{
			$this->renderErrorJson('活动公司编码不正确');
		}
		
		// 验证，md5(requestData(get method) + spScrectKey) = sig
		$sig = trim($incomeData['sig']);
		unset($incomeData['sig']);
		ksort($incomeData);
		
		$oDict 		= new CDict();
		$spKey 		= $oDict->SP_COMP[$data['sp_code']];
		$md5_val 	= trim($incomeData['call_time']).trim($incomeData['sp_code'])."$spKey";
		$valid 		= md5($md5_val);
		SF::log("md5_val===".$md5_val);
		SF::log($valid);
		if ($sig != $valid)
		{
			$this->renderErrorJson('验证码不正确');
		}
		
		return $data;
	}
	
	
	/**
	 * create the render data template
	 * @param booean $isSuccess
	 * @param array $content
	 * @param string $message
	 * @return array
	 */
	protected function createRenderTemplate($isSuccess, $content, $message)
	{
		return array(	'success' 	=> $isSuccess,
						'content'	=> $content,
						'message'	=> $message
					);
	}
	
	
	/**
	 * render the error json format message when some error happen
	 * @param string $message
	 */
	public function renderErrorJson($message)
	{
		SF::log("Error message: $message");
		$rederData = $this->createRenderTemplate(0, '', $message);
		$this->renderJson($rederData);
	}
	
	
	/**
	 * render the success result(in json format)
	 * @param array $content
	 */
	public function renderSuccessJson($content)
	{		
		$rederData = $this->createRenderTemplate(1, $content, '');
		$this->renderJson($rederData);
	}
	
	
	/**
	 * json ecode and render 
	 * @param array $data
	 */
	protected function renderJson($data = null)
	{
		array_walk_recursive(&$data, array($this, 'jsonGbkToUtf8'));
		$strJson = json_encode ($data);
		
		//output json string.
		ob_clean();
		header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
		echo $strJson;
		SF::log('==========API outcome data ==========');
		SF::log($strJson);
		
		SF::app()->end();
	}

	
	/**
	 * check the request data, protect from sql insert and translate utf8 to gbk
	 * @param emun $type ('post' or  'get')
	 * @return array
	 */
	function checkRequestFormat($type = 'get')
	{
		if (strtolower($type) == 'post') 
			$_data = $_POST;
		else
			$_data = $_GET;

		// data utf8 to gbk
		foreach ($_data as  $key => $param)
		{
			if (is_int($param)) 
			{
				$_data[$key] = intval($param);
			}
			else if (is_string($param)) 
			{
				$_data[$key] = CIconv::utf8ToGBK($param);
			}
		}
		
		return $_data;
	}
	
}
