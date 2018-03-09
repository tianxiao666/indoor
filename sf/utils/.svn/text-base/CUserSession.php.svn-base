<?php
/**
 * Session ¹ÜÀíÀà
 *
 */


class CUserSession
{
    public static function getSessionValue($key)
    {
        return $_SESSION[$key];
    }

    public static function  setSessionValue($key, $value)
    {
        $_SESSION[$key] = $value;
    }

	public static function  removeSessionValue($key)
	{
		unset($_SESSION[$key]);
	}


    public static function  getSessionUser()
    {
	SF::log('===========mark by Reaven # file:CUserSession.php # line:'.__LINE__.' ==========');
	SF::log(SESS_KEY_USER);
        if ($_SESSION[SESS_KEY_USER]) return $_SESSION[SESS_KEY_USER];
        return false;
    }

    public static function  setSessionUser($userInfo)
    {
        self::setSessionValue(SESS_KEY_USER, $userInfo);
    }
    
    public static function  getSessionAdmin()
    {
        if (isset($_SESSION[SESS_KEY_ADMIN])) return $_SESSION[SESS_KEY_ADMIN];
        return false;
    }

    public static function  setSessionAdmin($userInfo)
    {
        self::setSessionValue(SESS_KEY_ADMIN, $userInfo);
    }
    
    
    public static function destroy()
    {
    	SF::app()->getSession()->destroy();
    }
}
?>
