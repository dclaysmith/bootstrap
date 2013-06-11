<?php 
require_once("Log.php");
require_once("Log/file.php");
require_once("Log/display.php");
require_once("Log/composite.php");

class CLogger {
	
	private $_oLogger = null;
	
   	function __construct($sLogfile = LOG_FILE_PATH, $aConf = array(), $bLogToDisplay = false) {		
		
		if (LOG_TO_SCREEN) {
			$this->_oLogger	= &Log::singleton('composite');
			$oTmp			= &Log::singleton('file', $sLogfile, 'ident', $aConf);
			$oTmp2			= &Log::singleton('display', '', '', array('<font color="#ff0000"><tt>','</tt></font>'), PEAR_LOG_DEBUG);
			$this->_oLogger->addChild($oTmp);
			if ($bLogToDisplay) $this->_oLogger->addChild($oTmp2);
		} elseif ($sLogfile) {
			$this->_oLogger  = &Log::singleton('file', $sLogfile, 'ident', $aConf);
		}
		
   	}

   	function logEvent($sMessage, $iLevel = PEAR_LOG_INFO) {
	   	$this->_oLogger->log($sMessage, $iLevel);
 	}
 	
 	static function logEventStatic($sMessage, $sLogFile = LOG_FILE_PATH) {
		$oLogger = CLogger::getInstance($sLogFile);
		$oLogger->logEvent($sMessage);
 	}
 	
	// implement a version of the singleton pattern
    static function getInstance ($sLogfile = LOG_FILE_PATH, $aConf = array(), $bLogToDisplay = false) {
        static $instance;
        if (!isset($instance)) {
            $c = __CLASS__;
            $instance = new $c();
        }
        return $instance;
    } 	
}
?>