<?php
/**
 * CIconv class, brief use iconv to change the charset
 */
class CIconv
{
    /**
     * translate utf8 to gbk
     * @param string $value
     * @return string
     */
    public static function utf8ToGBK($value) 
    { 
    	return iconv("UTF-8", "gbk", $value); 
    }
    
    
    /**
     * translate gbk to utf8
     * @param string $value
     * @return string
     */
	public static function gbkToUtf8($value) 
	{
		return iconv("gbk", "UTF-8", $value); 
	}
	
	
	/**
     * translate gbk to utf8
     * @param string/array $value
     * @return string/array
     */
	public static function gbksToUtf8s($array) 
	{
		if(is_array($array))
		{
			foreach( $array as $key => $value ) 
			{
				$array[$key]=self::gbksToUtf8s($value);
			}
		}elseif ($array!='')
		{			
			$array = self::gbkToUtf8($array);
		}		
		return $array;		
	}
}
?>