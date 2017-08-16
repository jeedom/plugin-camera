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
$compress = (init('thumbnail') == 1) ? $camera->getConfiguration('thumbnail::compress', null) : $camera->getConfiguration('normal::compress', null);
$data = $camera->getSnapshot();
if (init('width') == '' && $compress == null) {
	echo $data;
	exit();
}
$source = imagecreatefromstring($data);
if ($source === false) {
	echo $data;
	exit();
}
if (init('width') == '') {
	imagejpeg($source, null, $compress);
	exit();
}
$width = imagesx($source);
if ($width < init('width')) {
	if ($compress == null) {
		echo $data;
		exit();
	}
	imagejpeg($source, null, $compress);
	exit();
}
if ($compress == null) {
	$compress = 100;
}
$height = imagesy($source);
$ratio = $width / $height;
$newwidth = init('width');
$newheight = $newwidth / $ratio;
$result = imagecreatetruecolor($newwidth, $newheight);
imagecopyresized($result, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
imagejpeg($result, null, $compress);
exit;