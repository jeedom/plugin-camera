<?php
function foscam_R2M_update($_eqLogic) {
	$url = $_eqLogic->getConfiguration('ip') . ':' . $_eqLogic->getConfiguration('port');
	$url .= '/cgi-bin/CGIProxy.fcgi?cmd=getDevState&usr=' . $_eqLogic->getConfiguration('username') . '&pwd=' . $_eqLogic->getConfiguration('password');
	$request_http = new com_http($url);
	$result = $request_http->exec();
	$xml = simplexml_load_string($result);
	$data = json_decode(json_encode(simplexml_load_string($result)), true);
	foreach ($_eqLogic->getCmd('info') as $cmd) {
		if (isset($data[$cmd->getLogicalId()])) {
			if (in_array($cmd->getLogicalId(), array('motionDetectAlarm', 'soundAlarm','humanDetectAlarmState'))) {
				$data[$cmd->getLogicalId()] = $data[$cmd->getLogicalId()] - 1;
			}
			$_eqLogic->checkAndUpdateCmd($cmd, $data[$cmd->getLogicalId()]);
		}
	}
}

?>
