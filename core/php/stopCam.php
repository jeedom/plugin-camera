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

$limit = 1800;
$record_state = $camera->getCmd(null, 'recordState');
$stop = $camera->getCmd(null, 'off');
$i = 0;
while (true) {
	if ($record_state->execCmd() == 0) {
		if (is_object($stop)) {
			$cmd = cmd::byId(str_replace('#', '', $camera->getConfiguration('commandOff')));
			if (is_object(!$cmd)) {
				throw new Exception(__('Impossible de trouver la commande OFF', __FILE__));
			}
			$cmd->execCmd();
		}
		die();
	}
	sleep(1);
	$i++;
	if ($i > $limit) {
		$camera->stopRecord();
		if (is_object($stop)) {
			$cmd = cmd::byId(str_replace('#', '', $camera->getConfiguration('commandOff')));
			if (is_object(!$cmd)) {
				throw new Exception(__('Impossible de trouver la commande OFF', __FILE__));
			}
			$cmd->execCmd();
		}
		die();
	}
}
die();