<?php

abstract class CLoggable {
	 	 
	// System is unusable
 	public function emerg($value) {
 		$oLogger = new CLogger();
 		$oLogger->logEvent($value, PEAR_LOG_EMERG);	
 	} 	 
 	
 	// Immediate action required
 	public function alert($value) {
 		$oLogger = new CLogger();
 		$oLogger->logEvent($value, PEAR_LOG_ALERT);	
 	} 	  
 	
 	// Critical conditions
 	public function crit($value) {
 		$oLogger = new CLogger();
 		$oLogger->logEvent($value, PEAR_LOG_CRIT);	
 	} 	
 	
 	// Error conditions
 	public function err($value) {
 		$oLogger = new CLogger();
 		$oLogger->logEvent($value, PEAR_LOG_ERR);	
 	}
 	
	// Warning conditions
 	public function warning($value) {
 		$oLogger = new CLogger();
 		$oLogger->logEvent($value, PEAR_LOG_WARNING);	
 	} 
 	
  	// Normal but significant
 	public function notice($value) {
 		$oLogger = new CLogger();
 		$oLogger->logEvent($value, PEAR_LOG_NOTICE);	
 	} 	
 	
 	// Informational
  	public function info($value) {
 		$oLogger = new CLogger();
 		$oLogger->logEvent($value, PEAR_LOG_INFO);	
 	} 	
 	
 	// Debug-level messages
 	public function debug($value) {
 		$oLogger = new CLogger();
 		$oLogger->logEvent($value, PEAR_LOG_DEBUG);	
 	} 	
 	 	 	 			
}
?>