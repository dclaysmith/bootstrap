<?php
class CClientMessage {

	public static function send($iProjectId, $iUserId, $sEvent, $aData = array()) {
			
		require_once("Predis/Autoloader.php");

		Predis\Autoloader::register();
	
		$redis = new Predis\Client();
		
		$aMessage = array(
						"project_id" 	=> $iProjectId,
						"user_id"		=> $iUserId,
						"message"		=> array(						
							"event" 		=> $sEvent,
							"data"			=> $aData
						)
					);		
		
		$redis->publish(REDIS_CHANNEL, CJSON::jsonEncode($aMessage));
		
	}
	
}
?>