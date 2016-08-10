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

$recordState = $camera->getCmd(null, 'recordState');
$nbSnap = -1;
$wait = 0;
$delay = 1;
$i = 1;
$sendPacket = -1;
$isMovie == 0;
$sendFirstSnap == 0;

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
if (null !== init('movie') && init('movie') != 0) {
	$isMovie = 1;
}
if (null !== init('sendFirstSnap') && init('sendFirstSnap') != 0) {
	$sendFirstSnap = 1;
}

$recordState->event(1);
$camera->refreshWidget();

if ($wait !== 0) {
	sleep($wait);
}
$files = array();
$starttime = strtotime('now');
while (true) {
	$cycleStartTime = getmicrotime();
	$i++;
	try {
		if ($isMovie == 1) {
			$files[] = $camera->takeSnapshot($_forVideo = 1, $_number = $i);
			if ($i == 2 && $sendFirstSnap == 1) {
				$camera->sendSnap($files);
			}
		} else {
			$files[] = $camera->takeSnapshot();
		}
	} catch (Exception $e) {

	}
	if ($SIG) {
		break;
	}
	if ($nbSnap > 0 && $i > $nbSnap) {
		break;
	}
	if ($sendPacket > 1 && count($files) >= $sendPacket) {
		if ($isMovie == 1) {
			$files = array();
			$files[] = $camera->convertMovie();
			$camera->sendSnap($files, true);
		} else {
			$camera->sendSnap($files, true);
		}
		$files = array();
	}
	$cycleDuration = getmicrotime() - $cycleStartTime;
	if ($cycleDuration < $delay) {
		usleep(round(($delay - $cycleDuration) * 1000000));
	}
	if ($SIG) {
		break;
	}
	if ((strtotime('now') - $starttime) > $camera->getConfiguration('maxReccordTime', 600)) {
		break;
	}
}
if (count($files) > 0) {
	if ($isMovie == 1) {
		$files = array();
		$files[] = $camera->convertMovie();
		$camera->sendSnap($files);
	} else {
		$camera->sendSnap($files);
	}
}
$recordState->event(0);
$camera->refreshWidget();
die();