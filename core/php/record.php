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
declare (ticks = 1);

global $SIG;
$SIG = false;

function sig_handler($signo) {
	global $SIG;
	$SIG = true;
}

pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP, "sig_handler");

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
	log::add('camera', 'error', __('[camera/reccord] L\'id ne peut etre vide', __FILE__));
	die();
}
$camera = camera::byId(init('id'));
if (!is_object($camera)) {
	log::add('camera', 'error', __('[camera/reccord] L\'équipement est introuvable : ', __FILE__) . init('id'));
	die();
}
if ($camera->getEqType_name() != 'camera') {
	log::add('camera', 'error', __('[camera/reccord] Cet équipement n\'est pas de type camera : ', __FILE__) . $camera->getEqType_name());
	die();
}

function sendSnap($_files, $_camera) {
	if (init('sendTo') == '' || count($_files) == 0) {
		return;
	}
	$options = array();
	$options['files'] = $_files;
	$options['title'] = __('Alerte sur la camera : ', __FILE__) . $_camera->getName();
	$options['message'] = __('Alerte sur la camera : ', __FILE__) . $_camera->getName() . __(' à ', __FILE__) . date('Y-m-d H:i:s');
	$cmds = explode('&&', init('sendTo'));
	foreach ($cmds as $id) {
		$cmd = cmd::byId(str_replace('#', '', $id));
		if (is_object(!$cmd)) {
			continue;
		}
		try {
			$cmd->execCmd($options);
		} catch (Exception $e) {
			log::add('camera', 'error', __('[camera/reccord] Erreur lors de l\'envoi des images : ', __FILE__) . $cmd->getHumanName() . ' => ' . log::exception($e));
		}
	}
}

$recordState = $camera->getCmd(null, 'recordState');
$nbSnap = -1;
$wait = 0;
$delay = 1;
$i = 1;
$sendPacket = -1;

if (is_numeric(init('nbSnap')) && init('nbSnap') > 0) {
	$nbSnap = init('nbSnap');
}
if (is_numeric(init('wait')) && init('wait') > 0) {
	$wait = init('wait');
}
if (is_numeric(init('delay')) && init('delay') > 0) {
	$delay = init('delay');
}
if (is_numeric(init('sendPacket')) && init('sendPacket') > 0) {
	$sendPacket = init('sendPacket');
}

$recordState->event(1);
$camera->refreshWidget();

if ($wait !== 0) {
	sleep($wait);
}
$files = array();
while (true) {
	$cycleStartTime = getmicrotime();
	$i++;
	try {
		$files[] = $camera->takeSnapshot();
	} catch (Exception $e) {

	}
	if ($SIG) {
		break;
	}
	if ($nbSnap > 0 && $i > $nbSnap) {
		break;
	}
	if ($sendPacket > 1 && count($files) >= $sendPacket) {
		sendSnap($files, $camera);
		$files = array();
	}
	$cycleDuration = getmicrotime() - $cycleStartTime;
	if ($cycleDuration < $delay) {
		usleep(round(($delay - $cycleDuration) * 1000000));
	}
	if ($SIG) {
		break;
	}
}
if (count($files) > 0) {
	sendSnap($files, $camera);
}
$recordState->event(0);
$camera->refreshWidget();
die();