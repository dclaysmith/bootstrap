<?php
/*
 * Created on 11 Jan 2007
 *
 */
require_once("classes/helpers/class.Email.php");

class CExceptionPublisher {

	// publish the exception
	static function publishException(exception $e) {

		if (LOG_TO_FILE) {
			
			CLogger::logEventStatic($e->getFile()." (".$e->getLine().") ".$e->getMessage());
		}

		if (LOG_TO_DB || LOG_TO_EMAIL) {

		    $colExceptionData = ExceptionData_Eng::search(
		    									array(
                                                    array("","message","=",$e->getMessage()),
                                                    array("","line","=",$e->getLine()),
                                                    array("","location","=",$e->getFile())
                                                ));

		    if ($colExceptionData->count() > 0) {
		        $iCount = $colExceptionData->first()->getCount();
		    } else {
		        $iCount = 1;
		    }

	    	// format the body
	    	$aBody = array();
	    	$aBody[] = "-----------------------------------------------------";
	    	$aBody[] = " An Exception Has Occurred";
	    	$aBody[] = "-----------------------------------------------------";
	    	$aBody[] = "Date: ".date("Y-m-d");
	    	$aBody[] = "Occurences: ".$iCount;
	    	$aBody[] = "-----------------------------------------------------";
	    	$aBody[] = "Message: ".$e->getMessage();
	    	$aBody[] = "Code: ".$e->getCode();
	    	$aBody[] = "File: ".$e->getFile();
	    	$aBody[] = "Line #: ".$e->getLine();
	    	$aBody[] = "-----------------------------------------------------";
	    	$aBody[] = "Trace:";
	    	$aBody[] = "-----------------------------------------------------";
	    	$aBody[] = CExceptionPublisher::getStackTraceAsText($e);
	    	$aBody[] = "-----------------------------------------------------";


		    if (LOG_TO_EMAIL) {
				$oEmail = CEmail::getInstance(LOG_TO_EMAIL_ADDRESS, "THETABOARD EXCEPTION", implode("\n",$aBody));
				$oEmail->send();
		    }

		    if (LOG_TO_DB) {

				// add the request and server information
		    	$aBody[] = "Server:";
		    	$aBody[] = "-----------------------------------------------------";
		    	$aBody[] = print_r($_SERVER, true);
		    	$aBody[] = "-----------------------------------------------------";
		    	$aBody[] = "Request:";
		    	$aBody[] = "-----------------------------------------------------";
		    	$aBody[] = print_r($_REQUEST, true);
		    	$aBody[] = "-----------------------------------------------------";

		        if ($colExceptionData->count() > 0) {

		        	// increment
		            $iCount = $colExceptionData->first()->getCount();
		            $colExceptionData->first()->setCount($iCount + 1);
		            ExceptionData_Eng::save($colExceptionData->first());

		        } else {

		            $iCount = 1;
		            $oExceptionData = ExceptionData_Eng::newExceptionData();
		            $oExceptionData->setMessage($e->getMessage());
		            $oExceptionData->setLine($e->getLine());
		            $oExceptionData->setLocation($e->getFile());
		            $oExceptionData->setData(implode("\n",$aBody));
		            $oExceptionData->setCount($iCount);


		            ExceptionData_Eng::save($oExceptionData);
		        }
		    }
		}

		if (strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false) {
				$oJSON = new CJSON();
				if ($e->getMessage()) {
					$oJSON->addKeyPair("message", $e->getMessage());
				} else {
					$oJSON->addKeyPair("message", CExceptionPublisher::getErrorMessage($e->getCode()));
				}
				$oJSON->_aHeaders[] = CExceptionPublisher::getErrorHeader($e);
				$oJSON->send();
				die;
		} elseif (isset($_REQUEST["callback"])) {
				$oJSON = new CJSON();
				$oJSON->_aHeaders[] = CExceptionPublisher::getErrorHeader($e);
				$oJSON->send($_REQUEST["callback"].'({message: "'.CExceptionPublisher::getErrorMessage($e->getCode()).'"})');	
				die;
		} elseif (LOG_TO_SCREEN) {

			$aOutput = array();
			$aOutput[] = "<h1>".$e->getMessage()."</h1>";
			$aOutput[] = "<b>".$e->getFile()."</b>: Line # ".$e->getLine()."<br />";
			
			$aOutput[] = "<h2>Trace</h2>";
			$aTrace = $e->getTrace();
			for ($i=0;$i<count($aTrace);$i++) {
				$aOutput[] = "<div style=\"width: 100%; margin-left: ".(50 * $i)."px;\">";
				$aItem = $aTrace[$i];
				$aOutput[] =  "<h3>".$aItem["class"].$aItem["type"].$aItem["function"]."(".CExceptionPublisher::formatParams($aItem["args"]).")</h3>";
				$aOutput[] =  "<b>".$aItem["file"]."</b>: Line # ".$aItem["line"]."<br />";
				$aOutput[] =  "</div>";
			}

			// add the request and server information
	    	$aOutput[] =  "Server:<br />";
	    	$aOutput[] =  "-----------------------------------------------------<br />";
	    	$aOutput[] =  "<pre>";
	    	$aOutput[] = print_r($_SERVER, true);
	    	$aOutput[] =  "</pre>";
	    	$aOutput[] =  "Request:";
	    	$aOutput[] =  "-----------------------------------------------------<br />";
	    	$aOutput[] =  "<pre>";
	    	$aOutput[] = print_r($_REQUEST, true);
	    	$aOutput[] =  "</pre>";
			
		
			
			echo implode("\n", $aOutput);
		} else {
			CFunctions::redirect("/Error.html");			
		}

	}

	public static function getTabs($iCount=1){
		$sReturn = "";
		for ($i=0;$i<$iCount;$i++) {
			$sReturn .= "\t";
		}
		return $sReturn;
	}
   	public static function getStackTraceAsText(exception $e) {
   		$aOutput	= array();
		$aTrace 	= $e->getTrace();
		for ($i=0;$i<count($aTrace);$i++) {
			$aItem 		= $aTrace[$i];
			$aOutput[]	= CExceptionPublisher::getTabs($i).$aItem["class"].$aItem["type"].$aItem["function"]."(".CExceptionPublisher::formatParams($aItem["args"]).")";
			$aOutput[]	= CExceptionPublisher::getTabs($i).$aItem["file"].": Line # ".$aItem["line"];
		}
		return implode("\n",$aOutput);
	}

	// publish an ajax exception
	static function publishAJAXException(exception $e) {

		// configure a json response
		$oJSONResponse = new CAJAXResponse();

		// send a failure message
		$oJSONResponse->setReturnValue(AJAX_FAILURE);

		// send a failure message
		$oJSONResponse->addMessage("We're sorry. An error has occurred.");

		// send the response
		$oJSONResponse->send();

		// publish the exception
		CExceptionPublisher::publishException($e);
	}

	static function formatParams($aParams) {
		$aTmp = array();
		foreach ($aParams as $param) {
			if (is_array($param)) {
				$aTmp[] = "array";
			} elseif (is_object($param)) {
				$aTmp[] = "object";
			} else {
				$aTmp[] = $param;
			}
		}
		return implode(", ",$aTmp);
	}

	public function getErrorHeader($exception) {
				// WWW-Authenticate: Basic realm="insert realm"
		$iCode = (!$exception->getCode() || !is_numeric($exception->getCode())) ? 500 : (int) $exception->getCode();
		$sText = CExceptionPublisher::getErrorMessage($iCode);
		$sHeader = "HTTP/1.1 ".$iCode." ".$sText;
		return $sHeader;
	}


	public function getErrorMessage($iCode = 500) {
				
		switch ($iCode) {			
            case 401: $text = 'Unauthorized';
				$text 	= 'You are no longer logged in..';
				break;
            case 400: $text = 'Bad Request'; break;
            case 402: $text = 'Payment Required'; break;
            case 403: $text = 'Forbidden'; break;
            case 404: $text = 'Not Found'; break;
            case 405: $text = 'Method Not Allowed'; break;
            case 406: $text = 'Not Acceptable'; break;
            case 407: $text = 'Proxy Authentication Required'; break;
            case 408: $text = 'Request Time-out'; break;
            case 409: $text = 'Conflict'; break;
            case 410: $text = 'Gone'; break;
            case 411: $text = 'Length Required'; break;
            case 412: $text = 'Precondition Failed'; break;
            case 413: $text = 'Request Entity Too Large'; break;
            case 414: $text = 'Request-URI Too Large'; break;
            case 415: $text = 'Unsupported Media Type'; break;
            case 501: $text = 'Not Implemented'; break;
            case 503: $text = 'Service Unavailable'; break;			
            case 500: 
            default:
				$text 	= 'Internal Server Error';
				break;
        }

		return $text;
	}


}


?>