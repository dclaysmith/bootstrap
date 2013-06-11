<?php 

class CCookies {
	
	// set a cookie value
	static public function setCookie($name, $value, $iExpiresMinutes=0) {
		if ($iExpiresMinutes == 0) {
			$iTime = 0;
		} else {
			$iTime = time() + (60 * $iExpiresMinutes);
		}

		setcookie($name, $value, $iTime, "/", ".".TLD_URL);
	}
	
	// return a cookie value
 	static public function getCookie($sName) {
 		return (isset($_COOKIE[$sName])) ? $_COOKIE[$sName] : false;
 	}
 	
 	// clear cookies
 	static public function clearCookies() {
 		foreach ($_COOKIE as $key => $sValue) {
 			CCookies::setCookie($key,"");
 		}
 	}
}
?>