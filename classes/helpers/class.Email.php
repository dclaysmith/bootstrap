<?php
/******************************************************************************
 * CEmail()
 * 
 * Wrapper for emails, provides:
 *****************************************************************************/
class CEmail {

 	/**
 	 * public
 	 */ 	
 	public $_sSubject = "";
 	public $_sTextBody = "";
 	public $_sHTMLBody = "";
 	
 	public $_aTo = array();
	public $_aCC = array();
	public $_aBCC;
	public $_aAttachments = array(); 
	
	public $_sFrom = "";
	public $_sReplyTo = "";
	public $_sReturnPath = "";
	public $_sCharset = "UTF-8";
	 	 
	public $_iPriority = -1;
	

 	/**
 	 * methods
 	 */
 	public function addRecipient($sEmailAddress, $sName = "") {		
 		if (!array_key_exists($sEmailAddress, $this->_aTo)) {
 			$this->_aTo[] = array("name" => $sName, "email_address" => $sEmailAddress);	
 		}	
 	}
 	public function addCC($sCC) {
 		if (!in_array($sCC, $this->_aCC)) {
 			$this->_aCC[] = $sCC;
 		}
 	} 	
 	public function addBCC($sBCC) {
 		if (!in_array($sBCC, $this->_aBCC)) {
 			$this->_aBCC[] = $sBCC;
 		}
 	} 	
 	public function addAttachment(CEmailAttachment $oAttachment) {
		$this->_aAttachments[] = $oAttachment;
 	} 	
 	
	public static function getInstance_Mixed($sSubject, $sTextBody, $sHTMLBody) {
		$oEmail = new CEmail();
		$oEmail->_sFrom 		= EMAIL_DEFAULT_SENDER;
		$oEmail->_sReplyTo 		= EMAIL_DEFAULT_SENDER;
		$oEmail->_sReturnPath 	= EMAIL_RETURN_PATH;
		$oEmail->_sSubject 		= $sSubject;
		$oEmail->_sHTMLBody		= $sHTMLBody;
		$oEmail->_sTextBody 	= $sTextBody;
		return $oEmail;
	}
	

	public static function getInstance($sEmailAddress, $sSubject, $sTextBody) {
		$oEmail = new CEmail();
		$oEmail->_sFrom 		= EMAIL_DEFAULT_SENDER;
		$oEmail->_sReplyTo 		= EMAIL_DEFAULT_SENDER;
		$oEmail->_sReturnPath 	= EMAIL_RETURN_PATH;
		$oEmail->_sSubject 		= $sSubject;
		$oEmail->_sTextBody 	= $sTextBody;
		$oEmail->addRecipient($sEmailAddress);
		return $oEmail;
	}	
	
   	/**
   	 * send()
   	 */
   	public function send() {
   		
   			/**
   			 * set the subject header
   			 */
   			$aHeaders = array();
   			
   			/**
   			 * set the subject: header
   			 */
			$aHeaders['Subject'] = trim($this->_sSubject);

			/**
			 * set the to: header to the first recipient
			 */	
			// $aHeaders['To'] = implode(",",$this->_aTo);

			/**
			 * set the from: header
			 */
			$aHeaders['From'] = $this->_sFrom;
			
			/**
			 * set the Reply-To: header
			 */
			$aHeaders['Reply-To'] = $this->_sReplyTo;
	
			/**
			 * set the Return-Path: header
			 */
			$aHeaders['Return-Path'] = ($this->_sReturnPath == "") ? $this->_sReplyTo : $this->_sReturnPath;
			
			/**
			 * set the priority header
			 */
			if ($this->_iPriority > 0) {
				switch ($this->_iPriority) {
					case 1:
			        	$aHeaders["X-Priority"] 		= "1 (Highest)";
			        	$aHeaders["X-MSMail-Priority"]	= "Highest";
			        	$aHeaders["Importance"] 		= "Highest"; 	
						break;
					case 2:
			        	$aHeaders["X-Priority"] 		= "2 (High)";
			        	$aHeaders["X-MSMail-Priority"]	= "High";
			        	$aHeaders["Importance"] 		= "High"; 							
						break;
					case 3:
			        	$aHeaders["X-Priority"] 		= "3 (Normal)";
			        	$aHeaders["X-MSMail-Priority"]	= "Normal";
			        	$aHeaders["Importance"] 		= "Normal"; 							
						break;
					case 4:
			        	$aHeaders["X-Priority"] 		= "4 (Low)";
			        	$aHeaders["X-MSMail-Priority"]	= "Low";
			        	$aHeaders["Importance"] 		= "Low"; 							
						break;
					case 5:
			        	$aHeaders["X-Priority"] 		= "5 (Lowest)";
			        	$aHeaders["X-MSMail-Priority"]	= "Lowest";
			        	$aHeaders["Importance"] 		= "Lowest"; 							
						break;		
				}
			}
						
			/**
			 * set the CC: header (if applicable)
			 */
			if (count($this->_aCC) > 0) {
				$aHeaders['CC'] = "";
				foreach ($this->_aCC as $sCC) {
					$aHeaders['CC'] .= $sCC.",";
				}
			}

			/**
			 * set the BCC: header (if applicable)
			 */
			if (count($this->_aBCC) > 0) {
				$aHeaders['BCC'] = "";
				foreach ($this->_aBCC as $sBCC) {
					$aHeaders['BCC'] .= $sBCC.",";
				}
			}						 			
			
			if (!EMAIL_SEND_EMAILS) return;
			
			if (EMAIL_METHOD == "SMTP") {
				require_once("Mail.php");
				require_once("Mail/mail.php");
				require_once("Mail/mime.php");

				$oMime = new Mail_mime();
				$oMime->setSubject(utf8_decode($this->_sSubject));
				$oMime->setTXTBody($this->_sTextBody);
				$oMime->setHTMLBody($this->_sHTMLBody);
	
				foreach ($this->_aAttachments as $oAttachment) {
					$oMime->addAttachment($oAttachment->getFile(),
										$oAttachment->getContentType(),
										$oAttachment->getName(),
										$oAttachment->getIsFile(),
										$oAttachment->getEncoding());
				}			
	
				
				/* order important here */
				$aEncodingParams = array("text_encoding" => "quoted-printable",
										"html_encoding" => "quoted-printable",
										"head_charset" => "iso-8859-1",
										"text_charset" => "utf-8",
										"html_charset" => "utf-8");			
	
				/* order important here */
				$sBody 		= $oMime->get($aEncodingParams);
				$aHeaders 	= $oMime->headers($aHeaders); 
	
				$aParams = array ("host" => EMAIL_SMTP_SERVER,
								     "auth" => EMAIL_SMTP_AUTH_REQUIRED,
								     "username" => EMAIL_SMTP_USERNAME,
									 "debug" => false,
								     "password" => EMAIL_SMTP_PASSWORD);			
	
	
				/**
				 * Create the mail object using the Mail::factory method
				 */
				$oMail =& Mail::factory("smtp", $aParams);
	
				/**
				 * send the message
				 */
				if (EMAIL_SEND_EMAILS === true) {		
					return $oMail->send($this->_aTo, $aHeaders, $sBody);
				} elseif (EMAIL_SEND_EMAILS != false) {
					$aHeaders['To'] = EMAIL_SEND_EMAILS;
					return $oMail->send(EMAIL_SEND_EMAILS, $aHeaders, $sBody);				
				} else {
					return true;
				}		
	
				if (PEAR::isError($result)) {
				   return false;
				} else {
				   return true;
				}				
				
			} elseif (EMAIL_METHOD == "POSTMARK") {
				require_once("lib/postmark/Postmark.php");
		
				define('POSTMARKAPP_API_KEY', EMAIL_SMTP_USERNAME);
				define('POSTMARKAPP_MAIL_FROM_ADDRESS', EMAIL_DEFAULT_SENDER);
				define('POSTMARKAPP_MAIL_FROM_NAME', EMAIL_DEFAULT_SENDER_NAME); // stored in constants
				
				$email = new Mail_Postmark();
				$email->subject(trim($this->_sSubject));
				$email->messagePlain($this->_sTextBody);
				$email->messageHtml($this->_sHTMLBody);
				
				// override the reply to
				if ($this->_sReplyTo != "") $email->replyTo($this->_sReplyTo);
				
				if (EMAIL_SEND_EMAILS === true) {
					
					// add in recipients		
					foreach ($this->_aTo as $aRecipient) {
						$email->addTo($aRecipient["email_address"], $aRecipient["name"]);
					}
					
					
				} else {
					
					// send to test recipient				
					$email->addTo(EMAIL_SEND_EMAILS, 'Test Recipient');
				}
				
				$email->send();	
				return;					
			}			
   	}
   	
	function utf8decode($sString) {
		return html_entity_decode(htmlentities($sString, ENT_COMPAT, 'UTF-8'));	
	}   	
	
	
}

class CEmailAttachment {
	
 	/**
 	 * public
 	 */ 	
 	public $_file = ""; 			// filelocation or data
 	public $_sContentType = "";	// content type
 	public $_sName = "";			// name of file if data was provided
 	public $_bIsFile = "";			// true if $file is a file location
 	public $_sEncoding = "";		// encoding to use "base64" default
	 	 
 	/**
 	 * constructor
 	 */ 	
   	function __construct($mixedFile, $sContentType = 'application/octet-stream', $sName='', $bIsFile=true, $sEncoding='base64') {
   		$this->_mixedFile 		= $mixedFile;
   		$this->_sContentType 	= $sContentType;
   		$this->_sName 			= $sName;
   		$this->_bIsFile 		= $bIsFile;
   		$this->_sEncoding 		= $sEncoding;
   	} 
   			
	/**
	 * properties
	 */
  	public function getFile() {
		return $this->_mixedFile;	
 	}
 	public function setFile($value) {
 		$this->_mixedFile = $value;
		return true;	
 	}		
  	public function getContentType() {
		return $this->_sContentType;
 	}
 	public function setContentType($value) {
		$this->_sContentType = $value;
		return true;	
 	} 	
   	public function getName() {
		return $this->_sName;
 	}
 	public function setName($value) {
 		$this->_sName = $value;
		return true;	
 	} 			
   	public function getIsFile() {
		return $this->_bIsFile;
 	}
 	public function setIsFile($value) {
 		$this->_bIsFile = $value;
		return true;	
 	} 			
   	public function getEncoding() {
		return $this->_sEncoding;
 	}
 	public function setEncoding($value) {
 		$this->_sEncoding = $value;
		return true;
 	} 	 	 	 	
}
?>