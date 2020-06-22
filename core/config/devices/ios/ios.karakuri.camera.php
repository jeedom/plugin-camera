<?php
function ios_karakuri_camera_update($_eqLogic) {
	$url = $_eqLogic->getConfiguration('protocole', 'http');
	$url .= '://';
	$url .= $_eqLogic->getConfiguration('ip');
	$url .= '/preview.json';

	$request_http = new com_http($url);
	$result = $request_http->exec();
	$data = json_decode($result, true);

	foreach ($_eqLogic->getCmd('info') as $cmd) {
		if (isset($data[$cmd->getLogicalId()])) {
			if (in_array($cmd->getLogicalId(), array('Time','Width','Height','Diff','Saving','Recording','Memory','Battery'))) {
				$_eqLogic->checkAndUpdateCmd($cmd, $data[$cmd->getLogicalId()]);
			}
		}
	}
}
?>