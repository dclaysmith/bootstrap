<?php
class CUrlFormatter {
	


	// implement a version of the singleton pattern
    static function getInstance () {
        static $instance;
        if (!isset($instance)) {
            $c = __CLASS__;
            $instance = new $c(LANGUAGE);
        }
        return $instance;
    }

	public static function SEOEncode($sString) {
		$sString = preg_replace("/[^a-z0-9\s-]/i","",$sString);
		$sString = str_replace(" ","-",$sString);
		return strtolower(str_replace("--","-",$sString));
	}
	
	public static function twitterEncode($sString) {
		$sString = str_replace("'","",$sString);
		$sString = urlencode($sString);
		return $sString;
	}

	public static function appendQueryStringVariables($sUrl, $aNameValuePairs) {
		
		if (false != strpos($sUrl,"?")) {
			$aUrl 			= explode("?",$sUrl);
			$sUrl 			= $aUrl[0];
			$sQueryString 	= $aUrl[1];			
		} else {
			$sQueryString	= "";
		}
		
		
		$aQueryString 	= explode("&",$sQueryString);
		foreach ($aNameValuePairs as $name => $value) {
			$aQueryString[] = $name."=".$value;
		}
		$sUrl .= "?";
		foreach ($aQueryString as $sString) {
			if ($sString == "") continue;
			$sUrl .= "&".$sString;
		}

		return $sUrl;
	}
		
	/**
	 * @desc If we are using a CDN/want to use far-forwards expires on assets (js, css, img) then
	 * we will need to format with a date.
	 */
	public static function formatImageUrl($sPath) {
		return IMG_URL."/".$sPath;
	}




	public function formatCssUrl($bRequireSSL) {
		if ($bRequireSSL) {
			return RACKSPACE_URL_ASSETS_SECURE."/".REVISION.".css";
		} else {
			return RACKSPACE_URL_ASSETS."/".REVISION.".css";
		}
	}
	public function formatJsUrl($bRequireSSL) {
		if ($bRequireSSL) {
			return RACKSPACE_URL_ASSETS_SECURE."/".REVISION.".js";
		} else {
			return RACKSPACE_URL_ASSETS."/".REVISION.".js";
		}
	}



	/**************************************************************************
	 * 
	 * 
	 * ALL SITES
	 * 
	 * 
	 *************************************************************************/
	public static function formatPageUrl($iPageId, $aNameValuePairs=null, $bEncodeAmpersand=true) {

		// format the query string
		$sQueryString = (!is_null($aNameValuePairs)) ? CFunctions::arrayToNameValuePairs($aNameValuePairs,$bEncodeAmpersand) : "";

		if (!$oPageTranslation 	= PageTranslation_Eng::getByLanguageIdPageId(LANGUAGE_ID, $iPageId)) {
			throw new exception("Unable to retrieve page id: ".$iPageId);
		}

		if ($sQueryString) {
			return $oPageTranslation->getUrlFull()."?".$sQueryString;
		} else {
			return $oPageTranslation->getUrlFull();
		}
		
	}

	public static function formatEmailVerificationUrl($iUserId, $sCode) {
		
		$aNameValuePairs = array(
								"ve" 			=> "true",
								"c"				=> $sCode,
								"x"				=> $iUserId
							);
		
		$sQueryString = (!is_null($aNameValuePairs)) ? CFunctions::arrayToNameValuePairs($aNameValuePairs,false) : "";
		
		return CUrlFormatter::formatPageUrl(5, $aNameValuePairs, true);
	}


	public static function formatGravatarUrl( $email, $s = 30, $d = 'identicon', $r = 'g', $img = false, $atts = array() ) {
		$url = 'https://secure.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ( $img ) {
			$url = '<img src="' . $url . '"';
			foreach ( $atts as $key => $val ) {
	            $url .= ' ' . $key . '="' . $val . '"';
			}
        	$url .= ' />';
		}
		return $url;
	}
	
	 	
	public static function formatUploadFilename(Story $oStory, $sFilename) {			
		
		$iStoryId	= $oStory->getId();
		$iProjectId	= $oStory->getProjectId();
		$iAccountId	= $oStory->getProject()->getAccountId();
		return substr(md5($iAccountId.SALT),0,16)."/".
				substr(md5($iProjectId.SALT),0,16)."/".
				substr(md5($iStoryId.SALT),0,16)."/".
				substr(md5($sFilename.time().SALT),0,16)."/".
				$sFilename;
	} 	
	 	
		

	/**************************************************************************
	 * 
	 * 
	 * PUBLIC SITE
	 * 
	 * 
	 *************************************************************************/
	
	public static function formatResourceArchiveUrl($dDate) {

		return SITE_URL."/blog/".date("Y",strtotime($dDate))."/".date("m",strtotime($dDate));
	}
	public static function formatResourceTagUrl($iTagId) {
		if (!$oTag = Tag_Eng::get($iTagId)) {
			throw new exception("Unable to retrieve tag id: ".$iTagId);
		}
		return SITE_URL."/blog/".CUrlFormatter::SEOEncode($oTag->getName())."?t=".$oTag->getId();
	}
	public static function formatResourceItemUrl($iResourceId) {
		if (!$oResource = Resource_Eng::get($iResourceId)) {
			throw new exception("Unable to retrieve resource id: ".$iResourceId);
		}
		return SITE_URL."/blog/".CUrlFormatter::SEOEncode($oResource->getTitle())."?r=".$iResourceId;
	}	
		
	
	public static function formatGoogleSitemapUrl($aNameValuePairs=null,$bEncodeUrl=true) {
		$sNameValuePairs = (is_null($aNameValuePairs)) ? "" : CFunctions::arrayToNameValuePairs($aNameValuePairs,$bEncodeUrl);
		$sNameValuePairs = preg_replace("/&amp;/", "", $sNameValuePairs, 1);
		return SITE_URL."/GoogleSitemap.php?".$sNameValuePairs;
	}	








	/**************************************************************************
	 * 
	 * 
	 * APPLICATION
	 * 
	 * 
	 *************************************************************************/
	public static function appBaseUrl($iAccountId) {
		if (!$oAccount = Account_Eng::get((int) $iAccountId)) {
			throw new exception("Unable to retrieve the account.");
		}

		switch (ENVIRONMENT) {
			case ENVIRONMENT_LOCAL_DEVELOPMENT:
				return "http://dev.app.akitacrm.com";
				break;
			case ENVIRONMENT_STAGING:
				return "https://staging.app.akitacrm.com";
				break;
			case ENVIRONMENT_PRODUCTION:
				return "https://".strtolower($oAccount->getDomain()).".akitacrm.com";
				break;
		}
	}
	public static function appPageUrl($iAccountId, $iPageId, $aNameValuePairs=null, $bEncodeAmpersand=true) {

		// format the query string
		$sQueryString = (!is_null($aNameValuePairs)) ? CFunctions::arrayToNameValuePairs($aNameValuePairs,$bEncodeAmpersand) : "";

		if (!$oPageTranslation 	= PageTranslation_Eng::getByLanguageIdPageId(LANGUAGE_ID, $iPageId)) {
			throw new exception("Unable to retrieve page id: ".$iPageId);
		}

		if ($sQueryString) {
			return CUrlFormatter::appBaseUrl($iAccountId).$oPageTranslation->getUrl()."?".$sQueryString;
		} else {
			return CUrlFormatter::appBaseUrl($iAccountId).$oPageTranslation->getUrl();
		}
	}
	public static function appPasswordUpdateUrl($iAccountId, $sEmailAddress, $sCode) {
		$aNameValuePairs = array(
								"c"				=> $sCode
								);
		
		$sQueryString = (!is_null($aNameValuePairs)) ? CFunctions::arrayToNameValuePairs($aNameValuePairs,false) : "";
		
		return CUrlFormatter::appPageUrl($iAccountId, 30, $aNameValuePairs, true);
	}	
	public static function appUserSignupUrl($iAccountId, $sAccessCode) {
		$aNameValuePairs = array(
								"c"	=> $sAccessCode
								);
		
		$sQueryString = (!is_null($aNameValuePairs)) ? CFunctions::arrayToNameValuePairs($aNameValuePairs,false) : "";
		
		return CUrlFormatter::appPageUrl($iAccountId, 32, $aNameValuePairs, true);
	}	
	public static function appContextIoCallbackUrl($iAccountId) {
		return CUrlFormatter::appPageUrl($iAccountId, 33, $aNameValuePairs, true);
	}

















	/**
	 * Account
	 * Desc: Root of API
	 */
	public static function apiAccountsUrl() {
		return "/v1";
	}


	public static function apiDealFieldsUrl($sDealUuid, $sFieldUuid = null) {
		if ($sFieldUuid) {
			return "/api/v1/deals/".$sDealUuid."/fields/".$sFieldUuid;
		} else {
			return "/api/v1/deals/".$sDealUuid."/fields";
		}		
	}

	/**
	 * Account/DealStatuses
	 */
	public static function apiDealStatusesUrl($sDealStatusUuid = null) {
		if ($sDealStatusUuid) {
			return "/api/v1/dealstatuses/".$sDealStatusUuid;
		} else {
			return "/api/v1/dealstatuses";
		}		
	}	

	/**
	 * Account/Entities
	 */
	public static function apiEntitiesUrl($sEntityUuid = null) {
		if ($sFieldUuid) {
			return "/api/v1/entities/".$sEntityUuid;
		} else {
			return "/api/v1/entities";
		}		
	}	
	public static function apiEntityActivitiesUrl($sEntityUuid, $sActivityUuid = null) {
		if ($sActivityUuid) {
			return "/api/v1/entities/".$sEntityUuid."/activities/".$sActivityUuid;
		} else {
			return "/api/v1/entities/".$sEntityUuid."/activities";
		}		
	}
	public static function apiEntityDealsUrl($sEntityUuid, $sDealUuid = null) {
		if ($sDealUuid) {
			return "/api/v1/entities/".$sEntityUuid."/deals/".$sDealUuid;
		} else {
			return "/api/v1/entities/".$sEntityUuid."/deals";
		}		
	}	
	public static function apiEntityMethodsOfContactUrl($sEntityUuid, $sMethodOfContactUuid = null) {
		if ($sDealUuid) {
			return "/api/v1/entities/".$sEntityUuid."/methodsofcontact/".$sMethodOfContactUuid;
		} else {
			return "/api/v1/entities/".$sEntityUuid."/methodsofcontact";
		}		
	}	
	public static function apiEntityPersonsUrl($sEntityUuid, $sPersonUuid = null) {
		if ($sPersonUuid) {
			return "/api/v1/entities/".$sEntityUuid."/persons/".$sPersonUuid;
		} else {
			return "/api/v1/entities/".$sEntityUuid."/persons";
		}		
	}
	public static function apiEntityTasksUrl($sEntityUuid, $sTaskUuid = null) {
		if ($sTaskUuid) {
			return "/api/v1/entities/".$sEntityUuid."/tasks/".$sTaskUuid;
		} else {
			return "/api/v1/entities/".$sEntityUuid."/tasks";
		}		
	}

	/**
	 * Account/EntityClassifications
	 */
	public static function apiEntityClassificationsUrl($sEntityClassificationsUuid = null) {
		if ($sEntityClassificationsUuid) {
			return "/api/v1/entityclassifications/".$sEntityClassificationsUuid;
		} else {
			return "/api/v1/entityclassifications";
		}		
	}	

	public static function apiEntityFieldsUrl($sEntityUuid, $sFieldUuid = null) {
		if ($sFieldUuid) {
			return "/api/v1/entities/".$sEntityUuid."/fields/".$sFieldUuid;
		} else {
			return "/api/v1/entities/".$sEntityUuid."/fields";
		}		
	}

	/**
	 * Account/Fields
	 */
	public static function apiFieldsUrl($sFieldUuid = null) {
		if ($sFieldUuid) {
			return "/api/v1/fields/".$sFieldUuid;
		} else {
			return "/api/v1/fields";
		}		
	}	
	public static function apiFieldFieldOptionsUrl($sFieldId, $sFieldOptionUuid = null) {
		if ($sFieldOptionUuid) {
			return "/api/v1/fields/".$sFieldId."/fieldoptions/".$sFieldOptionUuid;
		} else {
			return "/api/v1/fields/".$sFieldId."/fieldoptions";
		}		
	}	

	/**
	 * Account/Invitations
	 */
	public static function apiInvitationsUrl($sInvitationUuid = null) {
		if ($sInvitationUuid) {
			return "/api/v1/invitation/".$sInvitationUuid;
		} else {
			return "/api/v1/invitation";
		}		
	}	



	/**
	 * Account/LeadStatuses
	 */
	public static function apiLeadStatusesUrl($sLeadStatusUuid = null) {
		if ($sLeadStatusUuid) {
			return "/api/v1/leadstatuses/".$sLeadStatusUuid;
		} else {
			return "/api/v1/leadstatuses";
		}		
	}	


	/**
	 * Account/Products
	 */
	public static function apiProductsUrl($sProductUuid = null) {
		if ($sProductUuid) {
			return "/api/v1/products/".$sProductUuid;
		} else {
			return "/api/v1/products";
		}		
	}	
	public static function apiProductProductVariationsUrl($sProductUuid, $sProductVariationUuid = null) {
		if ($sProductVariationUuid) {
			return "/api/v1/products/".$sProductUuid."/productvariations/".$sProductVariationUuid;
		} else {
			return "/api/v1/products/".$sProductUuid."/productvariations";
		}		
	}	

	/**
	 * Account/Roles
	 */
	public static function apiRolesUrl($sRoleUuid = null) {
		if ($sRoleUuid) {
			return "/api/v1/roles/".$sRoleUuid;
		} else {
			return "/api/v1/roles";
		}		
	}	
	public static function apiRolePermissionsUrl($sRoleUuid, $sPermissionUuid = null) {
		if ($sPermissionUuid) {
			return "/api/v1/roles/".$sRoleUuid."/permissions/".$sPermissionUuid;
		} else {
			return "/api/v1/roles/".$sRoleUuid."/permissions";
		}		
	}	
	public static function apiRoleUsersUrl($sRoleUuid, $sUserUuid = null) {
		if ($sUserUuid) {
			return "/api/v1/roles/".$sRoleUuid."/users/".$sUserUuid;
		} else {
			return "/api/v1/roles/".$sRoleUuid."/users";
		}		
	}		

	/**
	 * Account/Tasks
	 */
	public static function apiTasksUrl($sTaskUuid = null) {
		if ($sTeamUuid) {
			return "/api/v1/tasks/".$sTaskUuid;
		} else {
			return "/api/v1/tasks";
		}		
	}	

	/**
	 * Account/Teams
	 */
	public static function apiTeamsUrl($sTeamUuid = null) {
		if ($sTeamUuid) {
			return "/api/v1/teams/".$sTeamUuid;
		} else {
			return "/api/v1/teams";
		}		
	}	
	public static function apiTeamUsersUrl($sTeamUuid, $sUserUuid = null) {
		if ($sUserUuid) {
			return "/api/v1/teams/".$sTeamUuid."/users/".$sUserUuid;
		} else {
			return "/api/v1/teams/".$sTeamUuid."/users";
		}		
	}	


	/**
	 * Account/Users
	 */
	public static function apiUsersUrl($sUserUuid=null) {
		if ($sUserUuid) {
			return "/api/v1/users/".$sUserUuid;
		} else {
			return "/api/v1/users";
		}
	}
	public static function apiUserActivitiesUrl($sUserUuid, $sActivityUuid = null) {
		if ($sActivityUuid) {
			return "/api/v1/users/".$sUserUuid."/activities/".$sActivityUuid;
		} else {
			return "/api/v1/users/".$sUserUuid."/activities";
		}		
	}
	public static function apiUserPermissionsUrl($sUserUuid, $sPermissionUuid = null) {
		if ($sPermissionUuid) {
			return "/api/v1/users/".$sUserUuid."/permissions/".$sPermissionUuid;
		} else {
			return "/api/v1/users/".$sUserUuid."/permissions";
		}		
	}
	public static function apiUserTokensUrl($sUserUuid, $sTokenUuid = null) {
		if ($sTokenUuid) {
			return "/api/v1/users/".$sUserUuid."/tokens/".$sTokenUuid;
		} else {
			return "/api/v1/users/".$sUserUuid."/tokens";
		}		
	}	


	/**
	 * System Permissions (Should this really be available via API?)
	 */
	public static function apiPermissionsUrl($sPermissionUuid = null) {
		if ($sPermissionUuid) {
			return "/api/v1/permissions/".$sPermissionUuid;
		} else {
			return "/api/v1/permissions";
		}		
	}	
}
?>