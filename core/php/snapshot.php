<?php
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
if (config::byKey('api') != init('apikey')) {
	exit();
}
$camera = camera::byId(init('id'));
if (!is_object($camera)) {
	die();
}
if ($camera->getEqType_name() != 'camera') {
	die();
}
header('Content-Type: image/jpeg');
echo $camera->getSnapshot();
exit;