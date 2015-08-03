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
if ($camera->getConfiguration('username') != '') {
	$context = stream_context_create(array(
		'http' => array(
			'header' => "Authorization: Basic " . base64_encode($camera->getConfiguration('username') . ":" . $camera->getConfiguration('password')),
		),
	));
	$data = file_get_contents($camera->getUrl($camera->getConfiguration('urlStream')), false, $context);
} else {
	$data = file_get_contents($camera->getUrl($camera->getConfiguration('urlStream')));
}
header('Content-Type: image/jpeg');
echo $data;
exit;