<?php
Class C3des{
	//var $key = '0E617C7A4C8FCDE90B199DA1D6D323CB04A7FB4AA80BBC7F';
	var $key;
	var $iv;

	public function __construct($key = '', $iv = ''){
		$this->setKey($key);
		$this->setIV($iv);
	}

	function pad($text) {
		$text_add = strlen($text) % 8;

		for($i = $text_add; $i < 8; $i++) {
			$text .= chr(8 - $text_add);
		} 
		return $text;
	} 

	function unpad($text) {

		$pad = ord($text{strlen($text)-1});

		if ($pad > strlen($text)) {
			return false;
		} 
		if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
			return false;
		} 
		return substr($text, 0, -1 * $pad);
	} 

	function encrypt($key, $iv, $text) {

		$key_add = 24 - strlen($key);
		$key .= substr($key, 0, $key_add);

		$text = $this -> pad($text);
		$td = mcrypt_module_open (MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');

		mcrypt_generic_init ($td, $key, $iv);

		$encrypt_text = mcrypt_generic ($td, $text);

		mcrypt_generic_deinit($td);

		mcrypt_module_close($td);

		return $encrypt_text;
	} 

	function decrypt($key, $iv, $text) {
		$key_add = 24 - strlen($key);

		$key .= substr($key, 0, $key_add);

		$td = mcrypt_module_open (MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');

		mcrypt_generic_init ($td, $key, $iv);

		$text = mdecrypt_generic ($td, $text);

		mcrypt_generic_deinit($td);

		mcrypt_module_close($td);

		return $this -> unpad($text);
	} 

	function encode($text){
		$key = pack('H*',$this->key);
		$iv  = pack('H*',$this->iv);
		return $this->encrypt($key, $iv, $text);

	}

	function decode($text){
		$key = pack('H*',$this->key);
		$iv  = pack('H*',$this->iv);
		return $this->decrypt($key, $iv, $text);
	}

	public function setKey($key){
		$this->key = $key;
	}

	public function getKey(){
		return $this->key;
	}

	public function setIV($iv){
		$this->iv = $iv;
	}

	public function getIV(){
		return $this->iv;
	}
} 

?>
