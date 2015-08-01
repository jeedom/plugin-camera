<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
if (php_sapi_name() != 'cli' || isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['argc'])) {
	header("Status: 404 Not Found");
	header('HTTP/1.0 404 Not Found');
	$_SERVER['REDIRECT_STATUS'] = 404;
	echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();
}
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
if (isset($argv)) {
	foreach ($argv as $arg) {
		$argList = explode('=', $arg);
		if (isset($argList[0]) && isset($argList[1])) {
			$_GET[$argList[0]] = $argList[1];
		}
	}
}
if (init('id') == '') {
	throw new Exception(__('L\'id ne peut etre vide', __FILE__));
}
$camera = camera::byId(init('id'));
if (!is_object($camera)) {
	throw new Exception(__('L\'équipement est introuvable : ', __FILE__) . init('id'));
}
if ($camera->getEqType_name() != 'camera') {
	throw new Exception(__('Cet équipement n\'est pas de type camera : ', __FILE__) . $camera->getEqType_name());
}
$output_dir = calculPath(config::byKey('recordDir', 'camera'));
if (!file_exists($output_dir)) {
	if (!mkdir($output_dir, 0777, true)) {
		throw new Exception(__('Impossible de creer le dossier : ', __FILE__) . $output_dir);
	}
}
if (!is_writable($output_dir)) {
	throw new Exception(__('Impossible d\'écrire dans le dossier : ', __FILE__) . $output_dir);
}
if ($camera->getConfiguration('displayProtocol') == 'snapshot') {
	$limit = 1800;
	if (is_object($recordCmd) && is_numeric($recordCmd->getConfiguration('request', 1800))) {
		$limit = $recordCmd->getConfiguration('request', 1800);
	}
	$continue = true;
	$i = 0;
	$recordState = $camera->getCmd(null, 'recordState');
	$recordState->event(1);
	$camera->refreshWidget();
	while ($continue) {
		$i++;
		$camera->takeSnapshot();
		sleep(1);
		if ($i > $limit) {
			$continue = false;
		}
	}
	$recordState->event(0);
	$camera->refreshWidget();
	die();
}

$url = $camera->getUrl($camera->getConfiguration('urlStream'), 'internal');

$output_file = $camera->getId() . '_' . str_replace('/', '\/', $camera->getHumanName()) . '_' . date('Y-m-d_H:i:s') . '.avi';
$fp = popen("which ffmpeg", "r");
$result = fgets($fp, 255);
$exists = !empty($result);
pclose($fp);
if ($exists) {
	if ($camera->getConfiguration('protocole') == 'rtsp') {
		$cmd = 'ffmpeg';
	} else {
		if ($camera->getConfiguration('cmdRecordOption') != '') {
			$cmd = 'ffmpeg -f mjpeg ';
		} else {
			$cmd = 'ffmpeg -f mjpeg -r ' . $camera->getConfiguration('record::fps', 8);
		}
	}
} else {
	if ($camera->getConfiguration('protocole') == 'rtsp') {
		$cmd = 'avconv';
	} else {
		if ($camera->getConfiguration('cmdRecordOption') != '') {
			$cmd = 'avconv -f mjpeg ';
		} else {
			$cmd = 'avconv -f mjpeg -r ' . $camera->getConfiguration('record::fps', 8);
		}
	}
}

if ($camera->getConfiguration('cmdRecordOption') != '') {
	$cmd .= ' ' . $camera->getConfiguration('cmdRecordOption');
}

$recordCmd = $camera->getCmd(null, 'recordCmd');
$cmd .= ' -i "' . $url . '"';

if (is_object($recordCmd) && is_numeric($recordCmd->getConfiguration('request', 1800))) {
	$cmd .= ' -t ' . $recordCmd->getConfiguration('request', 1800);
} else {
	$cmd .= ' -t 1800';
}
if ($camera->getConfiguration('cmdRecordOption') != '') {
	$cmd .= ' -vcodec mpeg4 -y -b:v ' . $camera->getConfiguration('record::bitrate', 1000000) . ' ' . escapeshellarg($output_dir . '/' . $output_file);
} else {
	$cmd .= ' -vcodec mpeg4 -y -b:v ' . $camera->getConfiguration('record::bitrate', 1000000) . ' -r ' . $camera->getConfiguration('record::fps', 8) . ' ' . escapeshellarg($output_dir . '/' . $output_file);
}

log::add('camera', 'debug', 'Record command : ' . $cmd);
$recordState = $camera->getCmd(null, 'recordState');
$recordState->event(1);
$camera->refreshWidget();
shell_exec($cmd);
$recordState->event(0);
$camera->refreshWidget();
