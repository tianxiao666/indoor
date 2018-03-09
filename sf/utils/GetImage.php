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
//		   define( "SF_GETIMG_SHOW_CHINESE", "��,Ǯ,��,��,��,��,֣,��,��,��,��,��,��,��,��,��,��,��,��,��,��,��,ʩ,��,��,��,��,��,��,��,л,��,��,ˮ,��,��,��,��,��,³,Τ,��,��,��,��,��,��,��,��,��,ʷ,��,�,��,��,��,��,��,��,��,��,��,ʱ,Ƥ,��,��,��,Ԫ,��,ƽ,��,��,��,��,��,��,ë,��,��,��,��,��,��,̸,��,��,��,��,��,ף,��,��,��,ϯ,��,��,ǿ,��,·,Σ,��,��,÷,ʢ,��,��,��,��,��,��,��,��,��,��,��,��,֧,��,��,¬,Ī,��,��,��,��,Ӧ,��,��,��,��,��,��,ʯ,��,ť,��,��,½,��,��,��,��,��,��,��,��,��,��,��,��,��,��,ɽ,��,��,ȫ,��,��,��,��,��,��,��,��,��,��,��,��,��,��,��,Ҷ,��,˾,��,��,��,��,ۢ,��,��,��,׿,��,��,��,��,��,��,��,˫,��,��,��,��,��,��,��,��,ȴ,��,ţ,��,ͨ,��,��,��,ũ,��,��,ׯ,��,��,��,��,ϰ,��,��,��,��,��,��,��,��,��,��,��,��,��,��,��,ŷ,��,ʦ,��,��,��,��,��,��,��,��,ɳ,��,��,��,��,��,��,��,��,��,Ȩ,��,��,��");
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
			//����ɫ
			$back=imagecolorallocate($im,0xFF,0xFF,0xFF);
			//ģ������ɫ
			$pix=imagecolorallocate($im,0xAA,0xA7,0xC9);
			//����ɫ
			$font=imagecolorallocate($im,0x00,0x00,0x00);
			//��ɫ
			$line=imagecolorallocate($im,0x78,0x90,0xC0);
			
			
			//��ģ�����õĵ�
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

			//д��,ѡ��ComicSansMS����
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
			//����ɫ
			$back=imagecolorallocate($im,0xFF,0xFF,0xFF);
			//ģ������ɫ
			$pix=imagecolorallocate($im,0xAA,0xA7,0xC9);
			//����ɫ
			$font=imagecolorallocate($im,0x00,0x00,0x00);
			//��ɫ
			$line=imagecolorallocate($im,0x78,0x90,0xC0);


			//��ģ�����õĵ�
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

			//д��,ѡ��ComicSansMS����
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
