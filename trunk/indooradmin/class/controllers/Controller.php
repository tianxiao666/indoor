<?php
/**
 * parrent controller in this project ,as an interface in this project to the sf framework
 *
 */
include_once(SF_CLASS_PATH.'common/SafetyFilter.php');


class Controller extends CController
{
	function render($view, $data=null, $return=false ,$is_encrypt=false)
	{		
		if ($return === 'json')
		{
			array_walk_recursive($data, array($this, 'jsonGbkToUtf8'));
			$strJson = json_encode($data);
			//output json string.
			header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
			if($is_encrypt && INTERFACE_DATA_ENCRYPT){
				$safe = new SafetyFilter();
				$strJson = $safe->getEncryptReDate($strJson,$_SESSION['code']);//加密
			}
			echo $strJson;
			return true;
		} 
		
		if($return === 'ajax')
		{
			header('Content-Type:text/html;charset=GB2312');
			$return = FALSE;
		}
		if(CUserSession::getSessionAdmin()){
			$userinfo = CUserSession::getSessionAdmin();
			$data['userinfo'] = $userinfo;
		}
		//默认亚洲region_id
		$asia = AdminController::getInstance('CDAOSYS_REGION')->findByName('亚洲');
		$data['DEFAULT_ASIA_REGION_ID'] 	= $asia['REGION_ID'];	
		//默认中国region_id
		$china = AdminController::getInstance('CDAOSYS_REGION')->findByName('中国');
		$data['DEFAULT_CHINA_REGION_ID'] 	= $china['REGION_ID'];	
		
		$data['mediaServer'] 	= MEDIA_SERVER;	
		$data['SFBaseUrl']	= SF_BASE_URL;
		$data['phpSelf']		= $this->getPhpSelf();
		$data['webUrl'] = WEB_URL;
		$data['wapUrl'] = WAP_URL;
		$data['javaurl']= JAVA_URL;
		return parent::render($view, $data, $return);
	}
	
	
	/**
	 * \brief used by json render
	 * @param string $value
	 * @param $key
	 */
	protected function jsonGbkToUtf8(&$value, $key) 
	{
		$value = iconv("gbk", "UTF-8", $value); 
	}
	
	
	//format request data:
	function checkRequestFormat($type = 'post')
	{
		if (strtolower($type) == 'post') 
			$_data = &$_POST;
		else
			$_data = &$_GET;

		foreach ($_data as  $key => $param)
		{
			if (is_int($param)) $_data[$key] = intval($param);
			else if (is_string($param)) $_data[$key] = $this->qstr($param);
		}
		
		return $_data;
	}
	
	
	/**
	 * \brief deal the magic quote
	 * @param $str
	 */
	function qstr($str)
    {
        if (get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }
    
    /**
     * \brief get the default poto url
     */
	function getDefaultPhotoUrl()
	{
		return "images/default_notice.gif";
	}
	
	
	/**
	 * \brief get the exp count time
	 * @param int $rows
	 * @param int $limitnum
	 */
	protected function getExpCount($rows, $limitnum)
    {
     	if ($rows < $limitnum) 
		{
			return 1;
		}
		
      	if (0 == $rows % $limitnum) 
		{
            return $rows/$limitnum;
       	}
            
       	return ($rows-$rows%$limitnum)/$limitnum +1;
   	}
    
   	/**
   	 * \brief return the php self
   	 */
   	protected function getPhpSelf()
   	{
   		return basename($_SERVER['PHP_SELF']);
   	}
   	
   	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=SF::app()->errorHandler->error)
	    {
	    	if(SF::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}
}
?>
