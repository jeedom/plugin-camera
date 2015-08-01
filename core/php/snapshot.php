<?php
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
if (config::byKey('api') != init('apikey')) {
	exit();
}
$camera = camera::byId(init('id'));
if (!is_object($camera)) {
	die();
}
$url = $camera->getConfiguration('protocoleFlux', 'http');
$url .= '://';
if ($camera->getConfiguration('username') != '') {
	$url .= $camera->getConfiguration('username') . ':' . $camera->getConfiguration('password') . '@';
}
$url .= $camera->getConfiguration('ip');
$url .= ':' . $camera->getConfiguration('portFlux', 80);
$url .= $camera->getConfiguration('urlStream');
$replace = array(
	'#username#' => $camera->getConfiguration('username'),
	'#password#' => $camera->getConfiguration('password'),
);
$url = str_replace(array_keys($replace), $replace, $url);
$data = file_get_contents($url);
header('Content-Type: image/jpeg');
echo $data;
exit;