<?php
class CJSON {

	private $_bExpire = true;
	private $_aData = array();
	private $_sCallback;
	public $_aHeaders = array();
	
	public function __construct($sCallback="") {
		$this->setCallback($sCallback);
	}
	
	public function setCallback($value) {
		$this->_sCallback = $value;
	}
	
	public function setExpire($value) {
		$this->_bExpire = $value;
	}
	
	public function addKeyPair($key, $value) {
		if (!is_array($this->_aData)) $this->_aData = array(); 
		$this->_aData[$key] = $value;
	}
	
	public function addItem($value) {
		if (!is_array($this->_aData)) $this->_aData = array(); 
		$this->_aData[] = $value;
	}
	
	public function setData($aValue) {
		$this->_aData = $aValue;
	}
	
	public static function jsonEncode($aData) {
		if (function_exists("json_encode")) {
			return json_encode($aData);
		} else {
			throw new exception("No json encode method.");
		}
	}
	
	public function toJSON() {
		if (!is_array($this->_aData)) $this->_aData = array(); 
		return $this->jsonEncode($this->_aData);
	}
	
	public static function fromJSON($json) {
		if (function_exists("json_decode")) {
			return json_decode($json);
		} else {
			throw new exception("No json decode method.");
		}
	}
	
	public function send($sData="") {
		if ($this->_bExpire) {
			header("Cache-Control: no-cache, must-revalidate");	// HTTP/1.1
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");	// Date in the past
		}
		header("Content-type: application/json");
		
		if (is_array($this->_aHeaders)) {
			foreach ($this->_aHeaders as $sHeader) {
				header($sHeader);
			}
		}
		
		if ($sData) {
			echo $sData;	
		} else {
			echo $this->toJSON();
		}
		die;		
	}
}
?>