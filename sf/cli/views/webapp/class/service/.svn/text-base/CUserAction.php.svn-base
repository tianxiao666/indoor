 <?php
 /**
  * \brief write the user action
  * @author Administrator
  */
class CUserAction 
{
	/**
	 * \brief write the user action log
	 * @param $userData
	 * @param $pageTitle
	 * @param $act_type
	 * @param $act_text
	 */
    public static function writeUserActionLog($userData, $pageTitle, $act_type, $act_text)
    {
		if ($userData && $pageTitle && $act_type && $act_text) 
		{
			$clientData['SITE']		= $pageTitle;
			$clientData['PageUrl']	= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$clientData['Referrer']	= $_SERVER['HTTP_REFERER'];
			$clientData['SITE_ID']	= 'ewish';
			
			self::addUserAction($userData, $clientData, $act_type, $act_text);
		}//end if
    }
    
    /**
     * \brief write the user action fro wap
     * @param $userData
     * @param $pageTitle
     * @param $act_type
     * @param $act_text
     */
    public static function writeUserActionLogForWap($userData, $pageTitle, $act_type, $act_text)
    {
		if ($userData && $pageTitle && $act_type && $act_text) 
		{
			$clientData['SITE']		= $pageTitle;
			$clientData['PageUrl']	= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$clientData['Referrer']	= $_SERVER['HTTP_REFERER'];
			$clientData['SITE_ID']	= 'wap';
			
			self::addUserAction($userData, $clientData, $act_type, $act_text);
		}//end if
    }
    
    
    /**
     * \brief add user action to database
     * @param unknown_type $userData
     * @param unknown_type $clientData
     * @param unknown_type $act_type
     * @param unknown_type $act_text
     */
    public static function addUserAction($userData, $clientData, $act_type, $act_text)
    {
    	try
    	{
			$oUserAction = new CDAOUserAction();
			$oUserAction->writeUserActionLog($userData, $clientData, $act_type, $act_text);
		}
		catch (ADODB_Exception $ae)
		{
			SF::log('insert user action error'.__LINE__, CLogger::LEVEL_ERROR);
			SF::log($ae->getMessage(), CLogger::LEVEL_ERROR);
		}
    }
    
}
 ?>