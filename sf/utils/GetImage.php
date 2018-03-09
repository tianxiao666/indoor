<?php
	if (!defined( "SF_GETIMG_PASS" )) {
		   define( "SF_GETIMG_PASS", SF_ROOT."font/SIMYOU.TTF");
	}
  
//	if (!defined( "SF_GETIMG_SESSION_NAME" )) {
//		   define( "SF_GETIMG_SESSION_NAME", "GETIMG_SESSION_NAME");
//	}
//	if (!defined( "SF_GETIMG_SHOW_N" )) {
//		   define( "SF_GETIMG_SHOW_N", "0123456789");
//	}
//	if (!defined( "SF_GETIMG_SHOW_C" )) {
//		   define( "SF_GETIMG_SHOW_C", "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ");
//	}
//	if (!defined( "SF_GETIMG_SHOW_CN" )) {
//		   define( "SF_GETIMG_SHOW_CN", "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");
//	}
//	if (!defined( "SF_GETIMG_SHOW_CHINESE" )) {
//		   define( "SF_GETIMG_SHOW_CHINESE", "ÕÔ,Ç®,Ëï,Àî,ÖÜ,Îâ,Ö£,Íõ,·ë,³Â,ñÒ,ÎÀ,½¯,Éò,º«,Ñî,Öì,ÇØ,ÓÈ,Ðí,ºÎ,ÂÀ,Ê©,ÕÅ,¿×,²Ü,ÑÏ,»ª,½ð,ÆÝ,Ð»,Ó÷,°Ø,Ë®,ÔÆ,ËÕ,·¶,Åí,ÀÉ,Â³,Î¤,²ý,Âí,Ãç,·ï,»¨,·½,Óá,ÈÎ,Áø,Ê·,ÌÆ,á¯,À×,ºØ,ÌÀ,ÂÞ,±Ï,°²,³£,ÀÖ,ÓÚ,Ê±,Æ¤,Æë,Îé,Óà,Ôª,ÃÏ,Æ½,»Æ,ºÍ,ÄÂ,Òü,Íô,Æî,Ã«,Ã×,±´,Ã÷,¼Æ,·ü,³É,Ì¸,ËÎ,¼Í,Êæ,Çü,Ïî,×£,Áº,¶Å,À¶,Ï¯,¼¾,Âé,Ç¿,¼Ö,Â·,Î£,½­,¹ù,Ã·,Ê¢,ÁÖ,ÖÓ,Ðì,Çñ,Âæ,¸ß,ÏÄ,²Ì,Ìï,ºú,Áè,Íò,Ö§,¿Â,¹Ü,Â¬,Äª,¿Â,·¿,¸É,½â,Ó¦,×Ú,µË,º¼,ºé,°ü,×ó,Ê¯,¼ª,Å¥,³Ì,»¬,Â½,ÈÙ,Ñò,ÓÚ,»Ý,Çú,¼Ò,·â,ËÉ,¾®,¶Î,¸»,½¹,°Í,ÄÁ,É½,¹È,³µ,È«,°à,Ñö,Çï,ÖÙ,ÒÁ,¹¬,Äþ,³ð,¸Ê,î×,Àú,×æ,Îä,Áõ,Áú,Ò¶,ÐÒ,Ë¾,ÉØ,ËÞ,°×,»³,Û¢,´Ó,Ë÷,Àµ,×¿,ÃÉ,³Ø,ÇÇ,Ñô,Óô,ÄÜ,²Ô,Ë«,ÎÅ,µ³,¹±,ÀÍ,åÌ,Éê,·ö,¶Â,È´,¹ð,Å£,ÊÙ,Í¨,±ß,ÆÖ,ÉÐ,Å©,ÎÂ,±ð,×¯,²ñ,³ä,Á¬,Èã,Ï°,°¬,Óã,ÈÝ,Ïò,¹Å,Ò×,ÖÕ,¾Ó,²½,¶¼,ºë,¹ú,ÎÄ,¹ã,¶«,Å·,Àû,Ê¦,¹®,ØÇ,Àä,ÐÁ,ÄÇ,¼ò,¿Õ,Ôø,É³,Ðë,·á,¹Ø,Ïà,²é,ºó,¾£,ºì,ÓÎ,È¨,Òæ,»¸,¹«");
//	}
  /**
	*	Input string, the same output for the string Photo
	*/
	class GetImage
	{
		static $showN = '0123456789';
		
//	  /**
//		*	getfull,Extract characters(A..Z,0..9)
//		*	@param $len is Output string length;
//		*	@return String;		
//		*/
//		function getCapitalsAndNumber($len=4)
//		{
//			if($len<=0) return false;
//			$srcstr=SF_GETIMG_SHOW_C;
//			mt_srand();
//			$str="";
//			for($i=0;$i<$len;$i++){
//				$str.=$srcstr[mt_rand(0,35)];
//			}
//			CSessionMgr::SetSessionValue(SF_GETIMG_SESSION_NAME,$str);
//			return $str;
//		} 
	  /**
		*	getfull,Extract characters(0..9)
		*	@param $len is Output string length;
		*	@return String;		
		*/
		public static function getNumber($len=4)
		{
			if($len<=0) return false;
			
			$srcstr = self::$showN;
//			mt_srand();
			$str="";
			for($i = 0; $i < $len; $i++)
			{
				$str.=$srcstr[mt_rand(0,9)];
			}
			
			//CSessionMgr::SetSessionValue(SF_GETIMG_SESSION_NAME,trim($str));
			CUserSession::setSessionValue('authcode', trim($str));
			$str = iconv("GB2312","UTF-8",$str);
			return $str;
		} 
//		
//	  /**
//		*	getfull,Extract full characters(a..z,A..Z,0..9)
//		*	@param $len is Output string length;
//		*	@return String;		
//		*/
//		function getfull($len=4)
//		{
//			if($len<=0) return false;
//			$srcstr=SF_GETIMG_SHOW_CN; 
//			mt_srand();
//			$str="";
//			for($i=0;$i<$len;$i++){
//				$str.=$srcstr[mt_rand(0,61)];
//			}
//			CSessionMgr::SetSessionValue(SF_GETIMG_SESSION_NAME,$str);
//			return $str;
//		} 
//	  /**
//		*	getfull,Extract full characters(a..z,A..Z,0..9)
//		*	@param $len is Output string length;
//		*	@return String;		
//		*/
//		function getChinese($len=3,$type = 2)
//		{
//			if($len<=0) return false;
//			$srcstr = SF_GETIMG_SHOW_CHINESE;
//			
//			$asrcstr = split (",", $srcstr);
//			mt_srand();
//			$str="";
//			if ($type == 2){
//				$sLen = mt_rand(1,3);
//			}else{
//				$sLen = $len;
//			}
//			if ($len-$sLen){
//				$Llen = mt_rand(0,$len-$sLen);
//				$Rlen = $len-$sLen-$Llen;
//				for($i=0;$i<$Llen;$i++){
//					$str.= " ";
//				}
//			}
//			for($i=0;$i<$sLen;$i++){
//				$str.=$asrcstr[mt_rand(0,256)];
//			}
//			if ($Rlen){
//				for($i=0;$i<$Rlen;$i++){
//					$str.= " ";
//				}
//			}
//			CSessionMgr::setSessionValue('authcode', trim($str));
//			$str = iconv("GB2312","UTF-8",$str);
//			return $str;
//		} 
		
	  /**
		*	GetImg,Photo generating function 
		*	@return png image;		
		*/
		public static function GetImg($str = "",$width = 60,$height = 22,$fontSize = 12,$fontX = 5,$fontY = 18,$font_pass = "",$fontAngle = 0)
		{
			//if (!$str) return false;
			if (!$str) 
				$str = self::getNumber();

				$im=imagecreate($width,$height);
			//±³¾°É«
			$back=imagecolorallocate($im,0xFF,0xFF,0xFF);
			//Ä£ºýµãÑÕÉ«
			$pix=imagecolorallocate($im,0xAA,0xA7,0xC9);
			//×ÖÌåÉ«
			$font=imagecolorallocate($im,0x00,0x00,0x00);
			//ÏßÉ«
			$line=imagecolorallocate($im,0x78,0x90,0xC0);
			
			
			//»æÄ£ºý×÷ÓÃµÄµã
//			mt_srand();
			for($i=0;$i<100;$i++){
				imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$pix);
			} 
			
			imageline($im,mt_rand(0,$width/4),mt_rand(0,$height),mt_rand($width/4,$width/2),mt_rand(0,$height-1),$line);
			for($i=0;$i<2;$i++){
				imageline($im,mt_rand(0,$width/2),mt_rand(0,$height),mt_rand($width/2,$width-1),mt_rand(0,$height-1),$line);
			} 
			imageline($im,mt_rand($width/2,$width-1),mt_rand(0,$height),mt_rand($width/2,($width*3)/4),mt_rand(0,$height-1),$line);
			
			if (!$font_pass){
				$font_pass = SF_GETIMG_PASS;
			}

			//Ð´×Ö,Ñ¡ÔñComicSansMS×ÖÌå
			imagettftext($im,$fontSize,$fontAngle,$fontX,$fontY,$font,$font_pass,$str);
			//imagestring($im,5,3,3,$str,$font);
			
			//imagerectangle($im,0,0,$width-1,$height-1,$font);
			
			ob_clean();
			header("Content-Type:image/png");
			imagepng($im);
			//return($im);
			imagedestroy($im);
		} 
//		function GetImgSession($session_name = "")
//		{
//			//CSessionMgr::init();
//			if ($session_name == "")
//				$session_name = MY_SESSION_NAME;
//			if (!isset($_COOKIE[$session_name])){
//				CSessionMgr::SessionStart($session_name);
//			}
//			$returnImg = CSessionMgr::GetSessionValue(SF_GETIMG_SESSION_NAME);
//			
//			//CSessionMgr::SetSessionValue(SF_GETIMG_SESSION_NAME,"*&");
//			return ($returnImg);
//		}
		
		public static function getAuthcode()
		{
		    return CUserSession::getSessionValue('authcode');
		}

		public static function getTwoNumber($len=4)
		{
			if($len<=0) return false;

			$srcstr = self::$showN;
			//			mt_srand();
			$str="";
			for($i = 0; $i < $len; $i++)
			{
				$str.=$srcstr[mt_rand(0,9)];
			}

			//CSessionMgr::SetSessionValue(SF_GETIMG_SESSION_NAME,trim($str));
			CUserSession::setSessionValue('twocode', trim($str));
			$str = iconv("GB2312","UTF-8",$str);
			return $str;
		}
	  
		public static function GetTwoImg($str = "",$width = 60,$height = 22,$fontSize = 12,$fontX = 5,$fontY = 18,$font_pass = "",$fontAngle = 0)
		{
			//if (!$str) return false;
			if (!$str)
			$str = self::getTwoNumber();

			$im=imagecreate($width,$height);
			//±³¾°É«
			$back=imagecolorallocate($im,0xFF,0xFF,0xFF);
			//Ä£ºýµãÑÕÉ«
			$pix=imagecolorallocate($im,0xAA,0xA7,0xC9);
			//×ÖÌåÉ«
			$font=imagecolorallocate($im,0x00,0x00,0x00);
			//ÏßÉ«
			$line=imagecolorallocate($im,0x78,0x90,0xC0);


			//»æÄ£ºý×÷ÓÃµÄµã
			//			mt_srand();
			for($i=0;$i<100;$i++){
				imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$pix);
			}

			imageline($im,mt_rand(0,$width/4),mt_rand(0,$height),mt_rand($width/4,$width/2),mt_rand(0,$height-1),$line);
			for($i=0;$i<2;$i++){
				imageline($im,mt_rand(0,$width/2),mt_rand(0,$height),mt_rand($width/2,$width-1),mt_rand(0,$height-1),$line);
			}
			imageline($im,mt_rand($width/2,$width-1),mt_rand(0,$height),mt_rand($width/2,($width*3)/4),mt_rand(0,$height-1),$line);

			if (!$font_pass){
				$font_pass = SF_GETIMG_PASS;
			}

			//Ð´×Ö,Ñ¡ÔñComicSansMS×ÖÌå
			imagettftext($im,$fontSize,$fontAngle,$fontX,$fontY,$font,$font_pass,$str);
			//imagestring($im,5,3,3,$str,$font);

			//imagerectangle($im,0,0,$width-1,$height-1,$font);

			ob_clean();
			header("Content-Type:image/png");
			imagepng($im);
			//return($im);
			imagedestroy($im);
		}
		
	}
?>
