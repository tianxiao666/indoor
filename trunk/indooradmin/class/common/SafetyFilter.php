<?php 
	
	class SafetyFilter{
		
		
		public function __construct()
		{
			
		}
		
		public function getSeed($rand){
			if(!$rand) $rand = mt_rand();
			if($rand<1000000000){
				$rand = $rand + 1000000000;
			}
			$seed = intval($rand/8) * 3 - 38483801;
			return $seed;
		}
		
		//返回效验码
		public function getEfficacyCode($rand=0){
			
			if(!$rand)
				$seed = $this->getSeed($rand);
			$vc = md5($seed);
			
			return $vc;
		}
		
		//
		public function getEncryptReDate($text,$seed=""){
			
			if(!$seed)$seed="1234567890";
			$vc = $this->getEfficacyCode($seed);
			
			$c3des = new C3des($vc,SF_C3DES_IV);
			$newtext = $c3des->encode($text);
			
			$newtext = base64_encode($newtext);//二进制转码base64
			return $newtext;
		}
		
		//
		public function getDecryptReDate($newtext,$seed=""){
			
			if(!$seed)$seed="1234567890";
			
			$vc = $this->getEfficacyCode($seed);
			$newtext = base64_decode($newtext);//base64转回二进制密码
			
			$c3des = new C3des($vc,SF_C3DES_IV);
			$text = $c3des->decode($newtext);
			return $text;
		}
		
		public function setSessionTime($lifeTime=7200){
			session_start();
			// 保存2小时
//			$lifeTime = 2 * 3600;
			setcookie(session_name(), session_id(), time() + $lifeTime, "/"); 
		}
	}

?>