<?php
function reolink_trackMix_update($_eqLogic) {
	$url = $_eqLogic->getConfiguration('ip') . ':' . $_eqLogic->getConfiguration('port',80);
	$url .= '/cgi-bin/api.cgi?cmd=GetMdState&user=' . $_eqLogic->getConfiguration('username') . '&password=' . $_eqLogic->getConfiguration('password');
	$request_http = new com_http($url);
	try {
		$result = $request_http->exec();
		$data = json_decode($result, true);
		$cmd = $_eqLogic->getCmd('info', 'motionDetectAlarm');
		if (is_object($cmd)){
			if (isset($data[0])) {
				if(array_key_exists("value", $data[0])) {
					if(array_key_exists("state", $data[0]['value'])) {
						$_eqLogic->checkAndUpdateCmd($cmd, $data[0]['value']['state']);
					}
				}
			}
		}
	} catch (Exception $e) { 
		log::add('camera', 'debug', 'Erreur com_http ! (GetMdState)');
	}
  
	$url = $_eqLogic->getConfiguration('ip') . ':' . $_eqLogic->getConfiguration('port',80);
	$url .= '/cgi-bin/api.cgi?cmd=GetAiState&user=' . $_eqLogic->getConfiguration('username') . '&password=' . $_eqLogic->getConfiguration('password');
	$request_http = new com_http($url);
	try {
		$result = $request_http->exec();
		$data = json_decode($result, true);
		if (isset($data[0])) {
			if(array_key_exists("value", $data[0])) {
				foreach ($data[0]['value'] as $key => $value) {
					if(is_array($value) && array_key_exists("support", $value) && $value['support'] == 1) { // return support or not
						$cmd = $_eqLogic->getCmd('info', 'motion' . ucfirst(str_replace('_', '', $key)) . 'Alarm');
						if (is_object($cmd) && array_key_exists("alarm_state", $value)) {
							$_eqLogic->checkAndUpdateCmd($cmd, $value['alarm_state']);
						}
					}
				}
			}
		}
	} catch (Exception $e) { 
		log::add('camera', 'debug', 'Erreur com_http ! (GetAiState)');
	}
    
}
?>