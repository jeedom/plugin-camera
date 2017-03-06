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

function compare($_folder, $_image1, $_image2) {
	$cmd = 'python ' . dirname(__FILE__) . '/../../resources/compare.py "' . $_folder . '/' . $_image1 . '" "' . $_folder . '/' . $_image2 . '"';
	return shell_exec($cmd);
}

if (init('id') == '') {
	log::add('camera', 'error', __('[camera/detectChange] L\'id ne peut etre vide', __FILE__));
	die();
}
$camera = camera::byId(init('id'));
if (!is_object($camera)) {
	log::add('camera', 'error', __('[camera/detectChange] L\'équipement est introuvable : ', __FILE__) . init('id'));
	die();
}
if ($camera->getEqType_name() != 'camera') {
	log::add('camera', 'error', __('[camera/detectChange] Cet équipement n\'est pas de type camera : ', __FILE__) . $camera->getEqType_name());
	die();
}

$folder = init('folder');

$continue = true;
$last = null;
$numcontinue = 0;
$threshold = $camera->getConfiguration('moveThreshold', 10);

while ($continue) {
	$file = ls(init('folder'), '*.jpg');
	if (count($file) < 2) {
		$numcontinue++;
		if ($numcontinue > 4) {
			$continue = false;
		}
	} else {
		$image1 = $file[count($file) - 2];
		$image2 = $file[count($file) - 1];
		if ($image2 == $last) {
			$numcontinue++;
			if ($numcontinue > 4) {
				$continue = false;
			}
		} else {
			$last = $image2;
			$numcontinue = 0;
			$diff = trim(compare($folder, $image1, $image2));
			log::add('camera', 'debug', __('Image difference : ', __FILE__) . $diff . __(' sur ', __FILE__) . $camera->getHumanName());
			if ($diff > $threshold) {
				$camera->sendSnap(array($folder . '/' . $image2), false);
			}
		}
	}
	if ($SIG) {
		break;
	}
	sleep(1);
}