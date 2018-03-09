<?php
/**
 * \brief call js function 
 * @author Administrator
 */
class CJsCall
{
	/**
	 * \brief call js script
	 * @param unknown_type $js
	 */
    public static function jsCall($js)
    {
        echo "<script language=\"javascript\" type=\"text/javascript\">\r\n{$js}\r\n</script>";
    }

    
    /**
     * \brief alert the given string
     * @param $str
     */
    public static function jsAlert($str)
    {
        self::jsCall("alert('{$str}');");
    }

    
    /**
     * \brief jump to the given url
     * @param unknown_type $url
     */
    public static function jsJumpUrl($url)
    {
        self::jsCall("location.href = '{$url}';");
    }

    
    /**
     * \brief jump back to previous page
     */
    public static function jsJumpBack()
    {
        self::jsCall('history.go(-1);');
    }
}
