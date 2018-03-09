<?php
/**
 * String Tools
 * @author huanggz
 *
 */
class StringTools
{
	/**
	 * get a serial random chars ,can be use by session_validate_image 
	 * @param int $wordNum
	 */
	public static function getRandomWords($wordNum=6)
	{
		$chars=array_merge(range(2,9),range('a','n'),range('p','z'),range('A','H'),range('J','N'),range('P','Z'));//��ȡ�ַ�����
		shuffle($chars);//��������
		$randomChars=join("",array_slice($chars,0,$wordNum));//��ȡ4������ַ�
		//���Ҫ����������г�ȡһ��Ԫ��,ֻҪ��array_rand������
		return $randomChars;
	}
	/**
	 * escape and addslashes
	 * 
	 * @param mixed $theValue
	 * @param string $theType, text,long,int,double,date,defined
	 * @param unknown_type $theDefinedValue
	 * @param unknown_type $theNotDefinedValue
	 * @return mixed escaped value
	 */
	public static function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
	{
		$theValue = trim($theValue);
		if (empty($theValue))
		{
			return false;
		}
		$theValue = !get_magic_quotes_gpc() ? addslashes($theValue) : $theValue;
		$theValue = mysql_real_escape_string($theValue);
		switch ($theType) {
		case "text":
		  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		  break;    
		case "long":
		case "int":
		  $theValue = ($theValue != "") ? intval($theValue) : "NULL";
		  break;
		case "double":
		  $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
		  break;
		case "date":
		  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
		  break;
		case "defined":
		  $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
		  break;
	  }
	  return $theValue;
	}
	/**
	 * Returns true if $string is valid UTF-8 and false otherwise.
	 * @param string $string
	 */
	public static function is_utf8($string) 
	{
		
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
	/*
	����˵�����ж��ļ��ǲ�����ָ�����ļ�������
	������$file Ҫ�жϵ��ļ�����
	������$type ��ȷ���ļ����ͣ�����������"|"�ֿ�
	*/
	
	public static function file_type($file,$type)
	{
		return preg_match("/\.($type)$/i",$file);
	}
	
	/**
	 * ����������ת��Ϊutf-8�ַ�����ʽ
	 * @param array $_array
	 * @return array $_array
	 */
	public static function convertArrayToUtf8($_array)
	{
		if (!is_array($_array)) return StringTools::is_utf8($_array)?$_array:iconv('gbk', 'utf-8', $_array);
		
		foreach ($_array as $k=>$v)
		{
			if (is_array($v))
			{
				$_array[$k] = StringTools::convertArrayToUtf8($v);
			}
			elseif (!StringTools::is_utf8($v))//����UTF8��ת��
			{
				$_array[$k] = iconv('gbk', 'utf-8', $v);
			}
		}
		return $_array;
	}

	/**
	 * get a serial random chars ,can be use by session_validate_image 
	 * @param int $wordNum
	 */
	public static function getRandomSEQ($_num=6)
	{
		//������
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
		
//		//��ȡ�ַ�����
//		$chars_start=range(1,9);
//		$chars=range(0,9);
//		
//		//���Ҫ����������г�ȡһ��Ԫ��,ֻҪ��array_rand������
//		//��������
//		shuffle($chars_start);
//		shuffle($chars);
//		//��ȡ��ʼ�ַ�
//		$randomChars .= array_rand($chars_start);
//		//��ȡ$_num-1������ַ�
//		$randomChars .= join("",array_slice($chars,0,$_num-1));
		
		return $time.$seq;
	}
	
	
	function cut_str($string,$sublen,$start=0,$code='UTF-8')
		{//֧��UTF-8��GB2312���ַ�����ȡ
		 if($code=='UTF-8')
		 {
			  $pa="/[x01-x7f]|[xc2-xdf][x80-xbf]|xe0[xa0-xbf][x80-xbf]|[xe1-xef][x80-xbf][x80-xbf]|xf0[x90-xbf][x80-xbf][x80-xbf]|[xf1-xf7][x80-xbf][x80-xbf][x80-xbf]/";
			  preg_match_all($pa,$string,$t_string);
			
			  if(count($t_string[0])-$start>$sublen) return join('',array_slice($t_string[0],$start,$sublen))."...";
			  return join('',array_slice($t_string[0],$start,$sublen));
		 }
		 else
		 {
			  $start=$start*2;
			  $sublen=$sublen*2;
			  $strlen=strlen($string);
			  $tmpstr='';
			
			  for($i=0;$i<$strlen;$i++)
			  {
				   if($i>=$start&&$i<($start+$sublen))
				   {
					    if(ord(substr($string,$i,1))>129)
					    {
					     $tmpstr.=substr($string,$i,2);
					    }
					    else
					    {
					     $tmpstr.=substr($string,$i,1);
					    }
				   }
				   if(ord(substr($string,$i,1))>129) $i++;
			  }
			  if(strlen($tmpstr)<$strlen ) $tmpstr.="...";
			    return $tmpstr;
		 }
}
	
}
?>