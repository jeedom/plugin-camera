<?php
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
$camera = camera::byId(init('id'));
if (!is_object($camera)) {
	die();
}
if ($camera->getEqType_name() != 'camera') {
	die();
}
if ($camera->getConfiguration('localApiKey') != init('apikey')) {
	die();
}
header('Content-Type: image/jpeg');
echo $camera->getSnapshot();
exit;