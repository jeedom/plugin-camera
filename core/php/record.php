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

$url = camera::formatIp($camera->getConfiguration('ip'), $camera->getConfiguration('protocole', 'http'));
if ($eqLogic->getConfiguration('port') != '') {
    $url .= ':' . $eqLogic->getConfiguration('port');
}
$url.=  $camera->getConfiguration('urlStream');
$url = str_replace(array('#username#', '#password#'), array($camera->getConfiguration('username'), $camera->getConfiguration('password')), $url);

$output_dir = calculPath(config::byKey('recordDir', 'camera'));
if (!file_exists($output_dir)) {
    if (!mkdir($output_dir, 0777, true)) {
        throw new Exception(__('Impossible de creer le dossier : ', __FILE__) . $output_dir);
    }
}
if (!is_writable($output_dir)) {
    throw new Exception(__('Impossible d\'écrire dans le dossier : ', __FILE__) . $output_dir);
}
$output_file = $camera->getId() . '_' . $camera->getHumanName() . '_' . date('Y-m-d_H:i:s') . '.avi';

$fp = popen("which ffmpeg", "r");
$result = fgets($fp, 255);
$exists = !empty($result);
pclose($fp);

if ($exists) {
    $cmd = 'ffmpeg -f mjpeg -r 8';
} else {
    $cmd = 'avconv -f mjpeg -r 8';
}

$cmd .= ' -i "' . $url . '"';
if (init('recordTime') == '' || !is_numeric(init('recordTime')) || init('recordTime') > 300) {
    $recordTime = 300;
} else {
    $recordTime = init('recordTime');
}
$cmd .= ' -t ' . $recordTime;
$cmd .= ' -vcodec mpeg4 -y -b:v 1000000 -r 8 ' . escapeshellarg($output_dir . '/' . $output_file);
$recordState = $camera->getCmd(null, 'recordState');
if (is_object($recordState)) {
    $recordState->event(1);
}
log::add('camera','debug','Commande d\'enregistement : '.$cmd);
shell_exec($cmd);
if (is_object($recordState)) {
    $recordState->event(0);
}

