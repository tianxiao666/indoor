<?php


    class StringUtils
	{

        function htmlTranslate( $string )
        {
        	return htmlspecialchars( $string );
        }

        function cutString( $string, $n )
        {
        	return substr( $string, 0, $n );
        }

        /**
         * Returns an array with all the links in a string.
         *
         * @param string The string
         * @return An array with the links in the string.
         */
        function getLinks( $string )
        {
		$regexp = "|<a[^>]+href=[\"']{0,1}([^'\"]+?)[\"']{0,1}[^>]*>(.+)</a>|iU";
            $result = Array();

            if( preg_match_all( $regexp, $string, $out, PREG_PATTERN_ORDER )) {
            	foreach( $out[1] as $link ) {
             		array_push( $result, $link );
            	}
            }

            return $result;
        }
		
		/**
		 * Returns a size formatted and with its unit: "bytes", "KB", "MB" or "GB"
		 *
		 * @param size The amount
		 * @return A string with the formatted size.
		 */
		function formatSize( $size )
		{
			if ($size < pow(2,10)) return $size." 字节";
			if ($size >= pow(2,10) && $size < pow(2,20)) return round($size / pow(2,10), 0)." KB";
			if ($size >= pow(2,20) && $size < pow(2,30)) return round($size /pow(2,20), 3)." MB";
			if ($size >= pow(2,30)) return round($size / pow(2,30), 2)." GB"; 
			
		}

        /**
         * Returns a string in a readable and url-compliant format.
         *
         * @param string The string
         * @return A string ready to use in urls.
         */
        function text2url( $string )
        {
		    // remove unnecessary spaces and make everything lower case
		    $string = preg_replace( "/ +/", " ", strtolower($string) );

            // special rule for dashes, I think it looks nicer :-p
            $string = str_replace(' - ', '-', $string);

            // removing a set of reserved characters (rfc2396: ; / ? : @ & = + $ ,)
            $string = str_replace(array(';','/','?',':','@','&','=','+','$',','), '', $string);

            // replace some characters to similar ones (more readable uris)
           // $search  = array(' ', '?, '?, '?,'?,'?,'?,'?,'?,'?,);
            $replace = array('_','ae','oe','ue','e','i','e','e','a','c');
            $string = str_replace($search, $replace, $string);

            // remove everything we didn't so far...
            $string = preg_replace("/[^a-z0-9_-]/", "", $string);
            
            // urlencode everything, in case we missed something ;-)
            return urlencode($string);
        }
		
		/**
		 * extremely lame function. I'm sure there are better ways to do this in php
		 * but I can't think of any myself at the moment :-)
		 *
		 * @param count how many times we'd like to repeat the character
		 * @param char The character (or string) we'd like to repeat
		 * @return The resulting string
		 */
 		function pad( $count, $char = " ")
		{
			$i=0;
			$result = "";
			while( $i < $count ) {
				$result .= $char;
				$i++;
			}
			
			return $result;
		}		
		
		static function handleUserName($name,$nickName)
		{
			if($name === $nickName)
			{
				$length = strlen($nickName);
				switch (true)
				{
					case $length > 0 && $length <= 6	:  $nickName = substr($nickName,0,$length-1).'...'; break;
					case $length > 6 && $length <= 8 	:  $nickName = substr($nickName,0,$length-2).'...'; break;
					case $length > 8 && $length <= 10	:  $nickName = substr($nickName,0,$length-3).'...'; break;
					case $length >10 && $length <= 12 	:  $nickName = substr($nickName,0,$length-4).'...'; break;
					case $length >12					:  $nickName = substr($nickName,0,$length-5).'...'; break;
				}
			}
			return $nickName;
		}
		
		/**
		 * get a serial random chars ,can be use by session_validate_image 
		 * @param int $wordNum
		 */
		public static function getRandomWords($wordNum=6)
		{
			$chars=array_merge(range(2,9),range('a','n'),range('p','z'),range('A','H'),range('J','N'),range('P','Z'));//获取字符数组
			shuffle($chars);//打乱数组
			$randomChars=join("",array_slice($chars,0,$wordNum));//获取4个随机字符
			//如果要随机从数组中抽取一个元素,只要用array_rand就行了
			return $randomChars;
		}
	
		/**
		 * get a serial random chars ,can be use by session_validate_image 
		 * @param int $wordNum
		 */
		public static function getRandomSEQ($_num=6)
		{
			//年月日
			$time = date("ymd",time());
			//get database's sequence 
			$CCDAOCW_TICKET = new CCDAOCW_TICKET();
			$seq = $CCDAOCW_TICKET->DB()->nextId('SEQ_SEQ_NUM');
			
			//set 0
			while(strlen($seq) < $_num)
			{
				$seq = '0'.$seq;
			}
			
			//make sure only 6 charaters in $seq
			$seq = substr($seq,0,6);
			
//			//获取字符数组
//			$chars_start=range(1,9);
//			$chars=range(0,9);
//			
//			//如果要随机从数组中抽取一个元素,只要用array_rand就行了
//			//打乱数组
//			shuffle($chars_start);
//			shuffle($chars);
//			//获取开始字符
//			$randomChars .= array_rand($chars_start);
//			//获取$_num-1个随机字符
//			$randomChars .= join("",array_slice($chars,0,$_num-1));
			
			return $time.$seq;
		}
		
		/**
	 * Returns true if $string is valid UTF-8 and false otherwise.
	 * @param string $string
	 */
	public static function is_utf8($string) 
	{
		if (!is_string($string)) return false;
		// From http://w3.org/International/questions/qa-forms-utf-8.html
		return preg_match('%^(?:
		[\x09\x0A\x0D\x20-\x7E] # ASCII
		| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
		| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
		| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
		| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
		| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
		| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
		| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
		)*$%xs', $string);
		
	} // function is_utf8
	
	public static function str_utf8_to_gbk($string)
	{
		if (StringUtils::is_utf8($string))
		{
			return iconv('utf-8','gbk',$string);
		}
		return $string;
	}
	
	/**
	 * 将数组内容转换为utf-8字符集格式
	 * @param array $_array
	 * @return array $_array
	 */
	public static function convertArrayToUtf8($_array)
	{
		if (!is_array($_array)) return StringUtils::is_utf8($_array)?$_array:iconv('gbk', 'utf-8', $_array);
		
		foreach ($_array as $k=>$v)
		{
			if (is_array($v))
			{
				$_array[$k] = StringUtils::convertArrayToUtf8($v);
			}
			elseif (!StringUtils::is_utf8($v))//不是UTF8则转换
			{
				$_array[$k] = iconv('gbk', 'utf-8', $v);
			}
		}
		return $_array;
	}
	/**
	 * 将数组或字符串或StrClass对象内容转换为utf-8字符集格式
	 * @param mixed $_data
	 * @return mixed $_data
	 */
	public static function convertToUtf8($_data)
	{
		if (is_string($_data)) $_data = self::is_utf8($_data)?$_data:iconv('gbk', 'utf-8', $_data);
		
		if (is_object($_data))
		{
			$_data_as_array = (array)$_data;
			$_data_as_array = self::convertToUtf8($_data_as_array);
			foreach ($_data_as_array as $k=>$v)
			{
				$_data->$k = $v;
			}
		}
		
		if (is_array($_data))
		foreach ($_data as $k=>$v)
		{
			$_data[$k] = self::convertToUtf8($v);
		}
		return $_data;
	}
	
	/**
	 * 转换数组或者字符串为GBK格式的并返回
	 * Enter description here ...
	 * @param mixed $_data
	 */
	public static function convertToGbk($_data)
	{
		if (is_string($_data))
		{
			$_data = self::is_utf8($_data)?iconv('utf-8', 'gbk', $_data):$_data;
		}
		
		if (is_object($_data))
		{
			$_data_as_array = (array)$_data;
			$_data_as_array = self::convertToGbk($_data_as_array);
			foreach ($_data_as_array as $k=>$v)
			{
				$_data->$k = $v;
			}
		}
		
		if (is_array($_data))
		foreach ($_data as $k=>$v)
		{
			$_data[$k] = self::convertToGbk($v);
		}
		
		
		return $_data;
	}
	
	/**
	 * 转换成数组，如果是字符串返回字符串
	 * Enter description here ...
	 * @param unknown_type $_obj
	 */
	public static function objectToArray($_obj)
	{
		if (is_string($_obj) || is_numeric($_obj)) return $_obj;
		
		if (is_array($_obj))
		{
			foreach ($_obj as $k=>$v)
			{
				$_obj[$k]=self::objectToArray($v);
			}
			return $_obj;
		}
		
		if (is_object($_obj)) 
		{
			$_obj = (array)$_obj;
			$_obj = self::objectToArray($_obj);
			return $_obj;
		}
		
		return $_obj;
	}
	
	/**
	 * 将所有格式返回数组形式，如果单是字符串（数字、bool）则返回只有一个值的数组。
	 * 
	 * Enter description here ...
	 * @param unknown_type $_all
	 */
	public static function allToArray($_all)
	{
		if (is_array($_all) || is_object($_all)) return self::objectToArray($_all);
		
		return array($_all);
	}
	
}
?>