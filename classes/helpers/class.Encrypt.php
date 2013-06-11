<?php
class CEncrypt {

	const ENCRYPT_SALT = "There once was a man from nantucket.";

	public static function encrypt($string) {
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(CEncrypt::ENCRYPT_SALT), $string, MCRYPT_MODE_CBC, md5(md5(CEncrypt::ENCRYPT_SALT))));		
	}

	public static function decrypt($string) {
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(CEncrypt::ENCRYPT_SALT), base64_decode($string), MCRYPT_MODE_CBC, md5(md5(CEncrypt::ENCRYPT_SALT))), "\0");		
	}
	
}
?>