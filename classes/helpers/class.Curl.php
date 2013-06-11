<?php
/******************************************************************************
 * Curl Wrapper
 ******************************************************************************
 * 
 ******************************************************************************/
class CCurl {
	
	public static function doPost($sUrl, $sHttpAuthentication, $sContentType, $sData) {

		// Initialize the session
		$session = curl_init($sUrl); 
		
		curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($session, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($session, CURLOPT_SSL_VERIFYPEER, 0); 
		    
		curl_setopt($session, CURLOPT_USERPWD, $sHttpAuthentication);
		curl_setopt($session, CURLOPT_POST, 1);
		curl_setopt($session, CURLOPT_POSTFIELDS , $sData);
		curl_setopt($session, CURLOPT_HTTPHEADER, array($sContentType));
		curl_setopt($session, CURLOPT_HEADER, false); // Do not return headers
		curl_setopt($session, CURLOPT_RETURNTRANSFER, 1); 
		
		$response = curl_exec($session);
		
		//echo $response;
		curl_close($session);
		
		return $response;

	}

}
?>