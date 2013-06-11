<?php
require_once("Validate.php");

/*
 * Created on 15 Mar 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class CValidation {

	/**
	 * isValidDatetime
	 *
	 * using ISO 8601 YYYY-MM-DD HH:MM:SS
	 */
	public static function isValidDatetime($value) {
		if ($value == "0000-00-00 00:00:00") return true; // allow zero dates to pass
		$pattern = "/^([0-9]{4}-[0-9]{2}-[0-9]{2})\s?([0-9]{1,2}:[0-9]{2}:[0-9]{2})?$/";
		if (preg_match($pattern, trim($value), $matches)) {
			if (isset($matches[2])) {
				return (CValidation::isValidDate($matches[1]) && CValidation::isValidTime($matches[2]));
			} else {
				return (CValidation::isValidDate($matches[1]));
			}
		} else {
			return false;
		}
	}
	
	/**
	 * MAKE SURE THAT IF YOU CHANGE THIS YOU ALSO CHANGE THE JAVASCRIPT VERSION
	 */
	public static function isValidUsername($sUsername) {
		return (preg_match("/^[A-Za-z0-9\-_]{3,20}$/",trim($sUsername))) ? true : false;
	}	

	/**
	 * isValidPassword
	 * @TODO: complete password validation
	 */
	public static function isValidPassword($value) {
		return (preg_match("/^.{8,}$/",trim($value))) ? true : false;
	}	

	/**
	 * isValidDate
	 *
	 * using ISO 8601 YYYY-MM-DD
	 */
	function isValidDate($value) {
		$pattern = "/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/";
		if (preg_match($pattern, $value, $matches)) {
			$days = array(31,28,31,30,31,30,31,31,30,31,30,31);
			$yyyy = $matches[1];
			$mm = $matches[2];
			$dd = $matches[3];
            if (1 <= $mm && $mm <= 12) {
                if ($mm == 2) {
                    if ($yyyy % 4 != 0 ? false : ( $yyyy % 100 != 0? true: ($yyyy % 1000 != 0? false : true))) {
                        return (1 <= $dd && $dd <= 29);
                    } else {
                        return (1 <= $dd && $dd <= 28);
                    }
                } else {
                    return (1 <= $dd && $dd <= $days[$mm-1]);
                }
            }else {
                return false;
            }
		} else {
			return false;
		}
	}

	function isValidTime($value) {
		$pattern = "/^([0-9]{1,2}):([0-9]{2}):([0-9]{2})$/";
		if (preg_match($pattern, $value, $matches)) {
			$hh = $matches[1];
			$mm = $matches[2];
			$ss = $matches[3];
			return (($hh <= 24) && ($mm < 60) && ($ss < 60));
		} else {
			return false;
		}
	}

	public static function isValidTimestamp($timestamp){
		return ( is_numeric($timestamp) && (int)$timestamp == $timestamp );
	}

	/**
	 * isValidEmail
	 */
	public static function isValidEmail($str,$bDomainCheck=false){
		return Validate::email($str,$bDomainCheck);
	}

	// isValidCurrency
	public static function isValidCurrency($value,$bAllowZero=false) {
		
		if (!$bAllowZero && str_replace("$","",$value) == 0) return false; 
		
		// remove any commas
		$value = str_replace(",","",$value);
		if (preg_match("/^\\$?\\d+(?:\\.\\d{0,2})?$/",$value)) {
			return true;
		} else {
			return (preg_match("/^\\$?\\.\\d{2}$/",$value)) ? true : false;
		}
	}
	
	/**
	 * isValidURL
	 */
	function isValidURL($str){
		return Validate::uri($str);
	}
	function singleLetterString($sString) {
		return (preg_match("/^([a-zA-Z]{1})\\1+$/", $sString) > 0) ? true : false;
	}

	// isValidTelephoneBasic
	public static function isValidTelephoneBasic($sTelephone){
			
		// numeric?
		$sTelephoneCheck = preg_replace('/[\+\(\)\.\-\s]/', '', $sTelephone);
		
		if (!is_numeric($sTelephoneCheck)) return false;
		
		// too short?
		if (strlen($sTelephoneCheck) < 6) return false;

		// all one digit?
		$bFakeNumber = true;
		$aDigits = preg_split('//', $sTelephoneCheck, -1, PREG_SPLIT_NO_EMPTY);
		$i = 0;
		foreach($aDigits as $iDigit) {
			// skip compare on first pass
			if ($i > 0) {
				if($iPreviousDigit != $iDigit) {
					$bFakeNumber = false;
					break;
				}
			}
			$iPreviousDigit = $iDigit;
			$i++;
		}

		if ($bFakeNumber) {
			return false;
		} else {
			// passes basic test
			return true;
		}

	}

	public static function isValidCurrencyCode($sCurrencyCode){
		if (strlen($sCurrencyCode) != 3) return false;
		return true;
	}
}
?>
