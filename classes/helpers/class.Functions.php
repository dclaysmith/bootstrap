<?php
/*
 * Created on 15 Mar 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class CFunctions {

	public static function abbreviateNumber($number) {
			
			
			if ($number > 9999) {
				$number = Math.floor($number / 1000);
				$number = $number + "K";
			}
			
			return $number;		
	}

	public static function booleanToBinary($value) {
		return ($value) ? 1 : 0;
	}

	public static function formatCreditCardNumber($sLast4, $sType) {
		switch ($sType) {
			case "Amex": // 15
				return str_repeat("*",11).$sLast4;
				break;
			case "Visa": // 16
			case "Mastercard":
			default:
				return str_repeat("*",12).$sLast4;
				break;
		}
	}
	
	// http://amix.dk/blog/post/19588
	public static function getConfidence($iUps, $iDowns) {
		
		$iVoteCount = $iUps + $iDowns;
		
		if ($iVoteCount == 0) return 0;
		
		$z 		= 1.0;  // 1.0 = 85%, 1.6 = 95%
		
		$phat 	= (float) $iUps / $iVoteCount;		
		
		return sqrt($phat+$z*$z/(2*$iVoteCount)-$z*(($phat*(1-$phat)+$z*$z/(4*$iVoteCount))/$iVoteCount))/(1+$z*$z/$iVoteCount);		
	}
	
	/**
	 * redirect
	 */
	public static function redirect($path, $sStatus="") {
		if ($sStatus=="301") {
			header("HTTP/1.1 301 Moved Permanently");
			header("location: ".$path);
			header("Connection: close");
		} else {
			header("location: ".$path);
		}
	}
	
	public static function plainTextToParagraphs($sString) {
		$aString = explode("\n", $sString);
		$sString = "";
		foreach ($aString as $sParagraph) {
			$sString .= "<p>".$sParagraph."</p>";
		}
		return $sString;
	}
	
	function right($str, $length)
	{
	  $strLen = strlen ($str);
	  return substr ($str, $strLen - $length, $strLen);
	}
	public static function formatCurrency($number,$iDecimals=2,$bShowCurrencySymbol=true) {
		if ($bShowCurrencySymbol) {
			return "$".number_format ($number, $iDecimals, ".", ",");
		} else {
			return number_format ($number, $iDecimals, ".", ",");
		}
	}
	
	public static function getUniqueFilename($sOriginalFilename) {
		// first get the extension
		return strtolower(CFunctions::getGuid(true).".".substr($sOriginalFilename, strrpos($sOriginalFilename, '.') + 1));
	}
	
	function moveUploadedFile($sFrom, $sTo) {
		if (IS_TESTING) {
			return copy($sFrom, $sTo);
		} else {
			return move_uploaded_file($sFrom, $sTo);
		}
	}
	
// creates an array containing the letters of the alphabet
	public static function getAlphabetArray() {
		$aAlphabet = array();
		for ($i=97;$i<123;$i++) {
			$aAlphabet[] = strtolower(chr($i));
		}
		return $aAlphabet;
	}	
	
	public static function arrayToNameValuePairs($aAssociativeArray,$bEncodeAmpersand=true) {
		$sNVPString = "";
		$sAmpersand = ($bEncodeAmpersand) ? "&amp;" : "&";
		foreach ($aAssociativeArray as $sName => $sValue) {
			$sNVPString .= $sAmpersand.$sName."=".urlencode($sValue);
		}	
		return $sNVPString;		
	}
	
	/**
	 * getGuid()
	 */
	public static function getGuid($bNumeric=false){
	    if (function_exists('com_create_guid')){
	    	if ($bNumeric) {
	    		return strtolower(preg_replace("/[\{\}-]/", "", com_create_guid()));
	    	} else {
	        	return strtolower(preg_replace("/[-]/", "", com_create_guid()));
	    	}
	    }else{
	        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	        $charid = strtoupper(md5(uniqid(rand(), true)));
	        $hyphen = ($bNumeric) ? "" : chr(45);// "-"
	        $uuid = substr($charid, 0, 8).$hyphen
	                .substr($charid, 8, 4).$hyphen
	                .substr($charid,12, 4).$hyphen
	                .substr($charid,16, 4).$hyphen
	                .substr($charid,20,12);
	    	if ($bNumeric) {
	    		return strtolower($uuid);
	    	} else {
	        	return strtolower($uuid);
	    	}		      
	    }
	}

	public static function getUuid($sObject) {
		return substr($sObject,0,3)."-".CFunctions::getGuid();
	}
	
	public static function getMimeTypeFromFilename($sFilename) {
		switch (CFunctions::getFileExtension($sFilename)) {
			case ("gif"):
				return "image/gif";
				break;
			case ("jpg"):
			case ("jpeg"):
			case ("jpe"):
			case ("jfif"):
			case ("pjpeg"):
			case ("pjp"):
				return "image/jpeg";
				break;
			case ("png"):
				return "image/png";
				break;
			case ("css"):
				return "text/css";
				break;
			case "mov":
				return "video/quicktime";
				break;
			case "mpeg4":
			case "mpegps":
				return "video/mpeg";
				break;
			case "avi":
				return "video/x-msvideo";
				break;
			case "wmv":
				return "audio/x-ms-wmv";
				break;
			case "flv":
				return "video/x-flv";
				break;
			case "svg":
				return "image/svg+xml";
				break;
			case "webm":
				return "video/webm";
				break;
			case "eot":
				return "application/vnd.ms-fontobject";
				break;
			default:
				return "application/octet-stream";
				break;
		}
	}	
	
	public static function abbreviateText($string, $max_length){
	    if (strlen($string) > $max_length){
	        $string = trim(substr($string, 0, $max_length));
	        $pos = strrpos($string, " ");
	        if ($pos === false) {
	               return $string;
			}
			if ($pos > ($max_length - 3)) {
	        	$pos2 = strrpos(trim(substr($string, 0, $pos)), " ");
				if ($pos2 === false) {
	               return trim(substr($string, 0, $pos));
				}
				return trim(substr($string, 0, $pos2))."&#8230;";
			}
	        return trim(substr($string, 0, $pos))."&#8230;";
	    }else{
	        return $string;
	    }
	} 
	
	static function getFileExtension($sOriginalName) {
		if (!strpos($sOriginalName,".")) return null;
		return strtolower(substr($sOriginalName,strrpos($sOriginalName,".")+1));
	}
	
	public static function getFilename($sOriginalName) {
		if (!strpos($sOriginalName,".")) return null;
		return substr($sOriginalName,0,strrpos($sOriginalName,"."));
	}
	

  public static function transverseFileArray($array1){
    if (is_array($array1)){
      $array2 = array();    
      for ($i=0; $i<count($array1['name']); $i++){
        $array2[$i]['name'] = $array1['name'][$i];
        $array2[$i]['type'] = $array1['type'][$i];
        $array2[$i]['tmp_name'] = $array1['tmp_name'][$i];
        $array2[$i]['error'] = $array1['error'][$i];
        $array2[$i]['size'] = $array1['size'][$i];
      }	  
      return $array2;
    }else{
      return false;
    } 
  }
  
	function nicetime($date) {
		
	    if (empty($date)) {
	        return "";
	    }
	   
	    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	    $lengths         = array("60","60","24","7","4.35","12","10");
	   
	    $now             = time();
	    $unix_date         = strtotime($date);
	   
	       // check validity of date
	    if(empty($unix_date)) {   
	        return "Bad date";
	    }
	
	    // is it future date or past date
	    if($now > $unix_date) {   
	        $difference     = $now - $unix_date;
	        $tense         = "ago";
	       
	    } else {
	        $difference     = $unix_date - $now;
	        $tense         = "from now";
	    }
	   
	    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	        $difference /= $lengths[$j];
	    }
	   
	    $difference = round($difference);
	   
	    if($difference != 1) {
	        $periods[$j].= "s";
	    }
	   
	    return "$difference $periods[$j] {$tense}";
	}	
###########################################################################
# pluralize()
###########################################################################
# pluralize the word. first look for exceptions, then apply accepted rules
###########################################################################
function pluralize($string,$aPluralForms=null) {
	
	###############################################################
	# if we are talking about a 2 letter word, just add an s
	###############################################################
	if (strlen($string) < 3) {
		return $string;
	}

	###############################################################
	# if there were no exceptions named then apply standard rules
	###############################################################
	# check two letter endings
	###############################################################
	$sLastTwoCharacters = substr($string,0,2);
	switch ($sLastTwoCharacters) {
		#######################################################
		# Where a noun ends in a sibilant sound
		#######################################################
		case "ss":
		case "sh":
		case "ch":
		case "se":
		case "ge":
			return substr($string,0,strlen($string)-1)."es";
			break;
		case "lf";
			return substr($string,0,strlen($string)-1)."ves";
			break;
		case "th";
			return $string."es";
			break;
		case "ry";
			return substr($string,0,strlen($string)-1)."ies";
			break;
	}

	###############################################################
	# there are a couple of cases where we are looking for a
	# consonant then another letter. use regex for this.
	###############################################################
	$pattern = "/(.*?[^aeiou])(\w{1})$/i";
	if (preg_match($pattern,$string,$matches)) {
		switch ($matches[2]) {
			case("s"):
				return $matches[0]."es";
				break;
			case("y"):
				return $matches[1]."ies";
				break;
			case("o"):
				return $matches[1]."oes";
				break;
		}
	}

	###############################################################
	# if we got here then i give up, just ad an "s"
	###############################################################
	return $string."s";
}	


	public static function zeroFill($number,$n) {
		return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
	}

	public static function prettify_json($json, $html = false)
	{
		$tabcount = 0; 
		$result = ''; 
		$inquote = false; 
		$ignorenext = false; 
 
		if ($html) { 
		    $tab = "&nbsp;&nbsp;&nbsp;"; 
		    $newline = "<br/>"; 
		} else { 
		    $tab = "\t"; 
		    $newline = "\n"; 
		} 
 
		for($i = 0; $i < strlen($json); $i++) { 
		    $char = $json[$i]; 
 
		    if ($ignorenext) { 
		        $result .= $char; 
		        $ignorenext = false; 
		    } else { 
		        switch($char) { 
		            case '{': 
		                $tabcount++; 
		                $result .= $char . $newline . str_repeat($tab, $tabcount); 
		                break; 
		            case '}': 
		                $tabcount--; 
		                $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char; 
		                break; 
		            case ',': 
		                $result .= $char . $newline . str_repeat($tab, $tabcount); 
		                break; 
		            case '"': 
		                $inquote = !$inquote; 
		                $result .= $char; 
		                break; 
		            case '\\': 
		                if ($inquote) $ignorenext = true; 
		                $result .= $char; 
		                break; 
		            default: 
		                $result .= $char; 
		        } 
		    } 
		} 
 
		return $result; 
	}

}		
?>