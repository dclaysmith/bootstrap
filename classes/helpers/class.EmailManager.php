<?php
class CEmailManager {

	public static function getWaitingListRequestEmail(User $oUser) {

		$sFirstName 	= $oUser->getFirstName();
		$sLastName 		= $oUser->getLastName();
		$sEmailAddress	= $oUser->getEmailAddress();	
		
    	// open the email template from disk
    	$sBodyHtml = CDictionary::getInstance()->translate("8:waitinglist-request-received-html",
                          array(
                            "%%first_name%%" => $sFirstName
                          ));

	    // translate the email template - text version
    	$sBodyText = CDictionary::getInstance()->translate("9:waitinglist-request-received-text",
                          array(
                            "%%first_name%%" => $sFirstName
                          ));

		// get the subject translation
		$sSubject	= CDictionary::getInstance()->translate("7:waitinglist-request-received");

		$sBodyHtml	= CEmailManager::wrapHTMLBody($sBodyHtml);                        
		
		// create the email
		$oEmail = CEmail::getInstance_Mixed($sSubject, $sBodyText, $sBodyHtml);
		$oEmail->addRecipient($sEmailAddress, $sFirstName." ".$sLastName);

		// return the email
		return $oEmail;		
	}

	public static function getInvitationEmail(Invitation $oInvitation) {

		$iAccountId		= $oInvitation->getAccountId();
		$sUuid 			= $oInvitation->getUuid();
		$sEmailAddress	= $oInvitation->getEmailAddress();

		// format the sign up url
		$sUrl 			= CUrlFormatter::appUserSignupUrl($iAccountId, $sUuid);
		
    	// retrieve the template
    	$sBodyHtml = CDictionary::getInstance()->translate("24:invitation-html",
                          array(
                            "%%url%%" => $sUrl
                          ));

	    // translate the email template - text version
    	$sBodyText = CDictionary::getInstance()->translate("25:invitation-received-text",
                          array(
                            "%%url%%" => $sUrl
                          ));

		// get the subject translation
		$sSubject	= CDictionary::getInstance()->translate("26:invitation-received");

		$sBodyHtml	= CEmailManager::wrapHTMLBody($sBodyHtml);                        
		
		// create the email
		$oEmail = CEmail::getInstance_Mixed($sSubject, $sBodyText, $sBodyHtml);
		$oEmail->addRecipient($sEmailAddress);

		// return the email
		return $oEmail;		
	}


	// public static function getRegistrationEmail(User $oUser) {

	// 	$sFirstName 		= $oUser->getFirstName();
	// 	$sEmailAddress	= $oUser->getEmailAddress();
	// 	$sUrl			= CUrlFormatter::formatEmailVerificationUrl($oUser->getId(), $oUser->getCode()); 

 //    	// open the email template from disk
 //    	$sBodyHtml = CDictionary::getInstance()->translate("11:registration-confirmation-html",
 //                          array(
 //                            "%%first_name%%" => $sFirstName,
 //                            "%%verify_url%%" => $sUrl
 //                          ));

	//     // translate the email template - text version
 //    	$sBodyText = CDictionary::getInstance()->translate("12:registration-confirmation-text",
 //                          array(
 //                            "%%first_name%%" => $sFirstName,
 //                            "%%verify_url%%" => $sUrl
 //                          ));

	// 	// get the subject translation
	// 	$sSubject	= CDictionary::getInstance()->translate("10:registration-confirmation");

	// 	$sBodyHtml	= CEmailManager::wrapHTMLBody($sBodyHtml);                        
		
	// 	// create the email
	// 	$oEmail = CEmail::getInstance_Mixed($sSubject, $sBodyText, $sBodyHtml);
	// 	$oEmail->addRecipient($sEmailAddress, $sFirstName);

	// 	// return the email
	// 	return $oEmail;		
	// }


	// public static function getEmailReverificationEmail(User $oUser) {

	// 	$sFirstName 	= $oUser->getFirstName();
	// 	$sEmailAddress	= $oUser->getEmailAddress();
	// 	$sUrl			= CUrlFormatter::formatEmailVerificationUrl($oUser->getId(), $oUser->getCode()); 

 //    	// open the email template from disk
 //    	$sBodyHtml = CDictionary::getInstance()->translate("10:email-reverification-html",
 //                          array(
 //                            "%%first_name%%" => $sFirstName,
 //                            "%%verify_url%%" => $sUrl
 //                          ));

	//     // translate the email template - text version
 //    	$sBodyText = CDictionary::getInstance()->translate("11:email-reverification-text",
 //                          array(
 //                            "%%first_name%%" => $sFirstName,
 //                            "%%verify_url%%" => $sUrl
 //                          ));

	// 	// get the subject translation
	// 	$sSubject	= CDictionary::getInstance()->translate("16:email-reverification");

	// 	$sBodyHtml	= CEmailManager::wrapHTMLBody($sBodyHtml);                        
		
	// 	// create the email
	// 	$oEmail = CEmail::getInstance_Mixed($sSubject, $sBodyText, $sBodyHtml);
	// 	$oEmail->addRecipient($sEmailAddress, $sFirstName);

	// 	// return the email
	// 	return $oEmail;		
	// }

	public static function getPasswordResetEmail(User $oUser) {
			
		$sFirstName 	= $oUser->getFirstName();
		$sEmailAddress	= $oUser->getEmailAddress();
		$sCode			= $oUser->getAccessCode();
		$sUrl			= CUrlFormatter::appPasswordUpdateUrl($oUser->getAccountId(), $sEmailAddress, $sCode);

	    // translate the email template
    	$sBodyHtml = CDictionary::getInstance()->translate("12:password-reset-link-html",
                          array(
                            "%%first_name%%" 	=> $sFirstName,
                            "%%url%%" 			=> $sUrl
                          ));

    	$sBodyText = CDictionary::getInstance()->translate("13:password-reset-link-text",
                          array(
                            "%%first_name%%" 	=> $sFirstName,
                            "%%url%%" 			=> $sUrl
                          ));

		// get the subject translation
		$sSubject	= CDictionary::getInstance()->translate("17:password-reset-request");

		$sBodyHtml	= CEmailManager::wrapHTMLBody($sBodyHtml);                        

		// create the email
		$oEmail = CEmail::getInstance_Mixed($sSubject, $sBodyText, $sBodyHtml);
		$oEmail->addRecipient($sEmailAddress, $sFirstName);

		// send it
		return $oEmail;
	}

  // 	public static function getNewPasswordEmail(User $oUser, $sPassword) {

		// $sEmailAddress 	= $oUser->getEmailAddress();
		// $sFirstName		= $oUser->getFirstName();
		// $sUrl			= CUrlFormatter::formatPageUrl(2);

	 //    // translate the email template
  //   	$sBodyHtml = CDictionary::getInstance()->translate("14:password-reset-temporary-html",
  //                         array(
  //                           "%%first_name%%" 	=> $sFirstName,
  //                           "%%password%%" 	=> $sPassword,
  //                           "%%url%%"		=> $sUrl
  //                         ));

  //   	$sBodyText = CDictionary::getInstance()->translate("15:password-reset-temporary-text",
  //                         array(
  //                           "%%first_name%%" 	=> $sFirstName,
  //                           "%%password%%" 	=> $sPassword,
  //                           "%%url%%"		=> $sUrl
		// 				));

		// // get the subject translation
		// $sSubject = CDictionary::getInstance()->translate("18:password-reset-temp");

		// $sBodyHtml = CEmailManager::wrapHTMLBody($sBodyHtml);
		
		// // create the email
		// $oEmail = CEmail::getInstance_Mixed($sSubject, $sBodyText, $sBodyHtml);
		// $oEmail->addRecipient($sEmailAddress, $sFirstName);							

		// // send it
		// return $oEmail;
  // 	}
  	  
  	public static function wrapHTMLBody($sBody) {
  				
  		$sTagline = ""; // CDictionary::getInstance()->translate("13:email-html-tagline");
  		
  		$sWrapper = <<<EOF

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>AkitaCRM Email</title>
		<style type="text/css">
			a:hover { text-decoration: none !important; }
			p {
				font-size: 16px;
				font-family: helvetica, arial, sans-serif;
			}
		</style>
	</head>
	<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #E6E6E6;" bgcolor="#E6E6E6" leftmargin="0">
		<!--100% body table-->
		<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#E6E6E6" style="padding: 20px">
	  		<tr>
	    		<td>
					<!--email container-->
	    			<table bgcolor="#fffdf9" cellspacing="0" border="0" align="center" cellpadding="30" width="100%">
	  					<tr>
							<td>
	    						<!--email content-->
	    						<table cellspacing="0" border="0" id="email-content" cellpadding="0" width="100%">
	    							<tr>
	    								<td align="center" style="text-align: center;">
	    									<p style="margin: 0px;">
	    										<a href="http://www.AkitaCRM.com" style="font-size: 48px; height: 74px; width: 438px; font-weight: bold; letter-spacing: -1px; text-decoration: none; font-family: helvetica, arial, sans-serif; color: #404040">
	    											<img src="http://320b2f6d02d540a24a02-c66f03ee07d3ba899f33d46bb7de0501.r61.cf2.rackcdn.com/logo.png" height="35" width="219" alt="AkitaCRM" />
	    										</a>
	    									</p>
	    									<p style="margin: 0px; font-size: 16px; font-family: helvetica, arial, sans-serif; color: gray">{$sTagline}</p>
	    								</td>
	    							</tr>
									<tr>
										<td valign="bottom" height="25"><hr /><br /></td>
									</tr>	    							
  									<tr>
    									<td valign="top" align="left" style="text-align: left; font-size: 16px; line-height: 22px; font-family: Helvetica, Arial, Verdana, san-serif; color: #333; margin: 0px;">
											{$sBody}											
 										</td>
									</tr>
									<tr>
										<td valign="bottom" height="50"><hr /></td>
									</tr>
									<tr>
										<td align="center"><a href="http://www.twitter.com/AkitaCRM" title="Follow AkitaCRM.com on Twitter"><img src="http://c24541.r41.cf2.rackcdn.com/follow_me-a.png" width="160" height="27" alt="Follow Us on Twitter" border="0"></a></td>
									</td>
								</table><!--/email content-->
	    					</td>
	  					</tr>
					</table><!--/email container-->
					<!--footer-->
					<table width="100%" border="0" align="center" cellpadding="30" cellspacing="0">
						<tr>
							<td valign="top"><p style="font-size: 14px; line-height: 24px; font-family: Helvetica, Arial, Verdana, san-serif; color: gray; margin: 0px;">
								This email was sent via AkitaCRM.com. If you think you should not have received it, please contact us at <a mailto="support@AkitaCRM.com">support@akitacrm.com</a>.</p>
							</td>
			  			</tr>
					</table><!--/footer-->
	    		</td>
	  		</tr>
		</table><!--/100% body table-->
	</body>
</html>
EOF;
		return $sWrapper;
  	}

}
?>