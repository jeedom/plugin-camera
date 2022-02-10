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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function camera_install() {
	$cron = cron::byClassAndFunction('camera', 'pull');
	if (!is_object($cron)) {
		$cron = new cron();
		$cron->setClass('camera');
		$cron->setFunction('pull');
		$cron->setEnable(1);
		$cron->setDeamon(1);
		$cron->setDeamonSleepTime(1);
		$cron->setSchedule('* * * * *');
		$cron->setTimeout(1440);
		$cron->save();
	}
	if(!com_shell::commandExists('avconv')){
		config::save('rtsp::engine','ffmpeg','camera');
	}
	exec(system::getCmdSudo().' chmod +x '.__DIR__.'/../3rdparty/rtsp-to-hls.sh');
	jeedom::getApiKey('camera');
}

function camera_update() {
	$cron = cron::byClassAndFunction('camera', 'pull');
	if (!is_object($cron)) {
		$cron = new cron();
		$cron->setClass('camera');
		$cron->setFunction('pull');
		$cron->setEnable(1);
		$cron->setDeamon(1);
		$cron->setDeamonSleepTime(1);
		$cron->setSchedule('* * * * *');
		$cron->setTimeout(1440);
		$cron->save();
	}
	foreach (camera::byType('camera') as $camera) {
		$dir = calculPath(config::byKey('recordDir', 'camera')) . '/' . $camera->getId();
		foreach (ls($dir, '*') as $file) {
			if ($file == 'movie_temp/' || strpos($file, '.mkv')) {
				continue;
			}
			rename($dir.'/'.$file, $dir.'/'.str_replace(':','-', $file));
		}
		$camera->save();
	}
	if(!com_shell::commandExists('avconv')){
		config::save('rtsp::engine','ffmpeg','camera');
	}
	exec(system::getCmdSudo().' chmod +x '.__DIR__.'/../3rdparty/rtsp-to-hls.sh');
	jeedom::getApiKey('camera');
}

function camera_remove() {
	$cron = cron::byClassAndFunction('camera', 'pull');
	if (is_object($cron)) {
		$cron->remove();
	}
}
