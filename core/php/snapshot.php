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
ob_clean();
header('Content-Type: image/jpeg');
if ($camera->getConfiguration('doNotCompressImage', 0) == 1 || !function_exists('imagecreatefromstring')) {
	echo $camera->getSnapshot();
	exit();
}
if (init('mobile', 0) == 0) {
	$compress = (init('thumbnail') == 1) ? $camera->getConfiguration('thumbnail::compress', null) : $camera->getConfiguration('normal::compress', null);
	$resize = (init('thumbnail') == 1) ? $camera->getConfiguration('thumbnail::resize', null) : $camera->getConfiguration('normal::resize', null);
} else {
	$compress = (init('thumbnail') == 1) ? $camera->getConfiguration('thumbnail::mobilecompress', null) : $camera->getConfiguration('normal::mobilecompress', null);
	$resize = (init('thumbnail') == 1) ? $camera->getConfiguration('thumbnail::mobileresize', null) : $camera->getConfiguration('normal::mobileresize', null);
}
$data = $camera->getSnapshot();
if (init('width', 0) == 0 && ($compress == null || $compress >= 100) && ($resize == null || $resize >= 100)) {
	echo $data;
	exit();
}
if (empty($data) || $data == false || $data == '') {
	echo $data;
	exit();
}
$askWidth = init('width', 0);
if ($askWidth == 0 && init('thumbnail') == 1) {
	$askWidth = 360;
}
$source = @imagecreatefromstring($data);
if ($source === false) {
	echo $data;
	exit();
}
if ($resize == null || $resize == 0) {
	if ($askWidth == 0) {
		imagejpeg($source, null, $compress);
		exit();
	}
	$width = imagesx($source);
	if ($width < $askWidth) {
		if ($compress == null || $compress >= 100) {
			echo $data;
			exit();
		}
		imagejpeg($source, null, $compress);
		exit();
	}
	if ($compress == null || $compress > 100) {
		$compress = 100;
	}
	$height = imagesy($source);
	$ratio = $width / $height;
	$newwidth = $askWidth;
	$newheight = $newwidth / $ratio;
	$result = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresized($result, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	if ($compress < 100) {
		imagejpeg($result, null, $compress);
		exit();
	}
	echo $data;
	exit();
} else {
	if ($compress == null || $compress >= 100) {
		$compress = 100;
	}
	if ($resize > 100) {
		$resize = 100;
	}
	$width = imagesx($source);
	$height = imagesy($source);
	$ratio = $width / $height;
	$newwidth = $width * $resize / 100;
	$newheight = $newwidth / $ratio;
	$result = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresized($result, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	if ($compress < 100) {
		imagejpeg($result, null, $compress);
		exit();
	}
	echo $data;
	exit();
}
