<?php
/**
 * class CDictionary
*
 * PHP version 5
 *
 * @author     D CLAY SMITH <clay@franchisedirect.com>
 * @copyright  2007 Franchise Direct
 *
 */
class CDictionary {

  	/**
   	 * Variables
     */
	private $_iLanguageId;
	private $_aDictionary;

	/**
	 * Constructor
	 */
	function __construct($iLanguageId) {
		$this->_iLanguageId = $iLanguageId;
	}

	function translateReturnArray($key, $aSubstitutions=null) {
		
		if ($this->_aDictionary == null) {
			$this->_aDictionary = $this->getDictionary();
		}
		
		// we only want the numerical portion
		$key = (strpos($key,":")) ? substr($key,0,strpos($key,":")) : $key;

		if (!array_key_exists(strtoupper($key), $this->_aDictionary)) {
			return false; //  array("message" => "WORD NOT FOUND");
		}

		$sMessage	= CDictionary::substitute($this->_aDictionary[strtoupper($key)]["translation"], $aSubstitutions);
		$iType		= $this->_aDictionary[strtoupper($key)]["type"];

		return array("type" => $iType, "message" => $sMessage);
	}

	function translate($key, $aSubstitutions=null) {
		
		if ($this->_aDictionary == null) {
			$this->_aDictionary = $this->getDictionary();
		}
		
		// we only want the numerical portion
		$key = (strpos($key,":")) ? substr($key,0,strpos($key,":")) : $key;

		if (!array_key_exists(strtoupper($key), $this->_aDictionary)) {
			return false; //  array("message" => "WORD NOT FOUND");
		}

		$sMessage	= CDictionary::substitute($this->_aDictionary[strtoupper($key)]["translation"], $aSubstitutions);
		$iType		= $this->_aDictionary[strtoupper($key)]["type"];

		return $sMessage;
	}	
	

	public static function substitute($sTemplate, $aSubstitutions=null) {
		
		if (is_array($aSubstitutions)) {
			return str_replace(array_keys($aSubstitutions), 
								array_values($aSubstitutions),
								$sTemplate);			
		} else {
			return $sTemplate;	
		}

	}	

	/**
	 * grabs the dictionary for the desired Site
	 */
	function getDictionary() {
		if ($this->_aDictionary == null) {
			
			if (!$colDictionaryTranslations = DictionaryTranslation_Eng::searchCache()) {
				throw new exception('Unable to retreive the dictionary.', EXCEPTION_CODE_TRAPPED);
			}

			/**
			 * loop through the collection and create the array
			 */
			foreach ($colDictionaryTranslations as $oDictionaryTranslation) {
				
				// only care about the current language
				if ($oDictionaryTranslation->getLanguageId() == $this->_iLanguageId) {
					$this->_aDictionary[$oDictionaryTranslation->getDictionaryPhraseId()] = array(
												"translation" => $oDictionaryTranslation->getTranslation(),
												"type" => $oDictionaryTranslation->getDictionaryPhrase()->getDictionaryPhraseTypeId()
												);
				}
			}
		}
		
		
		if ($this->_aDictionary == null) $this->_aDictionary = array();
		return $this->_aDictionary;
	}

	// implement a version of the singleton pattern
    static function getInstance () {
        static $instance;
        if (!defined("LANGUAGE")) define("LANGUAGE",1);
        if (!isset($instance)) {
            $c = __CLASS__;
            $instance = new $c(LANGUAGE);
        }
        return $instance;
    }
}
?>
