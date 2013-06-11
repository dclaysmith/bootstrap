<?php

if (!defined("MEMCACHE_ENABLED")) define("MEMCACHE_ENABLED",false);
  
class CMemcache {

	public static function get($key) {
		if (!MEMCACHE_ENABLED) return false;
		
		$oMemcache = new Memcache;
		$oMemcache->connect(MEMCACHE_HOST, MEMCACHE_PORT);
		$var = $oMemcache->get($key);
		$oMemcache->close();
		return $var;
		unset($oMemcache);
	}
	
	public static function set($key, $value, $iTimeout=0) {
		if (!MEMCACHE_ENABLED) return false;
		
		$oMemcache = new Memcache;
		$oMemcache->connect(MEMCACHE_HOST, MEMCACHE_PORT);
		if (!$oMemcache->replace($key, $value, 0, $iTimeout)) {
			$oMemcache->set($key, $value, 0, $iTimeout);
		}	
		$oMemcache->close();
		unset($oMemcache);
	}
	
	public static function delete($key) {
		if (!MEMCACHE_ENABLED) return false;
		
		$oMemcache = new Memcache;
		$oMemcache->connect(MEMCACHE_HOST, MEMCACHE_PORT);
		$oMemcache->delete($key,0);	
		$oMemcache->close();
		unset($oMemcache);
	}
	
	public static function getStats() {
		if (!MEMCACHE_ENABLED) return false;
		
		$oMemcache = new Memcache;
		$oMemcache->connect(MEMCACHE_HOST, MEMCACHE_PORT);
		return $oMemcache->getExtendedStats();	
		$oMemcache->close();
		unset($oMemcache);		
	}
	
	public static function flush() {
		if (!MEMCACHE_ENABLED) return false;
		
		$oMemcache = new Memcache;
		$oMemcache->connect(MEMCACHE_HOST, MEMCACHE_PORT);
		$oMemcache->flush();	
		$oMemcache->close();
		unset($oMemcache);		
	}	
}
?>