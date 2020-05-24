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

try {
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');
	
	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}
	
	ajax::init();
	
	if (init('action') == 'addDiscoverCam') {
		camera::addDiscoverCam(json_decode(init('config'), true));
		ajax::success();
	}
	
	if (init('action') == 'removeRecord') {
		$file = init('file');
		$file = str_replace('..', '', $file);
		$record_dir = calculPath(config::byKey('recordDir', 'camera'));
		shell_exec('rm -rf ' . $record_dir . '/' . $file);
		ajax::success();
	}
	
	if (init('action') == 'removeAllSnapshot') {
		$camera = camera::byId(init('id'));
		if (!is_object($camera)) {
			throw new Exception(__('Impossible de trouver la caméra : ' . init('id'), __FILE__));
		}
		$camera->removeAllSnapshot();
		ajax::success();
	}
	
	if (init('action') == 'getCamera') {
		if (init('object_id') == '') {
			$object = jeeObject::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
		} else {
			$object = jeeObject::byId(init('object_id'));
		}
		if (!is_object($object)) {
			$object = jeeObject::rootObject();
		}
		$return = array();
		$return['eqLogics'] = array();
		if (init('object_id') == '') {
			foreach (jeeObject::all() as $object) {
				foreach ($object->getEqLogic(true, false, 'camera') as $camera) {
					$return['eqLogics'][] = $camera->toHtml(init('version'));
				}
			}
		} else {
			foreach ($object->getEqLogic(true, false, 'camera') as $camera) {
				$return['eqLogics'][] = $camera->toHtml(init('version'));
			}
			foreach (jeeObject::buildTree($object) as $child) {
				$cameras = $child->getEqLogic(true, false, 'camera');
				if (count($cameras) > 0) {
					foreach ($cameras as $camera) {
						$return['eqLogics'][] = $camera->toHtml(init('version'));
					}
				}
			}
		}
		ajax::success($return);
	}
	
	if (init('action') == 'stream') {
		$camera = camera::byId(init('id'));
		if(!is_object($camera)){
			throw new \Exception(__('Impossible de trouver la camera : ',__FILE__).init('id'));
		}
		if(count(system::ps('rtsp-to-hls.sh.*'.$camera->getConfiguration('localApiKey'))) == 0){
			shell_exec('(ps ax || ps w) | grep ffmpeg.*'.$camera->getConfiguration('localApiKey').' | awk \'{print $2}\' |  xargs sudo kill -9');
			$replace = array(
				'#username#' => urlencode($camera->getConfiguration('username')),
				'#password#' => urlencode($camera->getConfiguration('password')),
				'#ip#' => urlencode($camera->getConfiguration('ip')),
				'#port#' => urlencode($camera->getConfiguration('port')),
			);
			$engine = config::byKey('rtsp::engine','camera','avconv');
			if (!file_exists(dirname(__FILE__) . '/../../data/segments')) {
				mkdir(dirname(__FILE__) . '/../../data/segments', 0777, true);
			}
			log::add('camera', 'debug', 'nohup '.dirname(__FILE__) . '/../../3rdparty/rtsp-to-hls.sh ' . trim(str_replace(array_keys($replace), $replace, $camera->getConfiguration('cameraStreamAccessUrl'))).' "' . $camera->getConfiguration('localApiKey') . '" > /dev/null 2>&1 &');
			exec('nohup ' .dirname(__FILE__) . '/../../3rdparty/rtsp-to-hls.sh ' . trim(str_replace(array_keys($replace), $replace, $camera->getConfiguration('cameraStreamAccessUrl'))).' "' . $camera->getConfiguration('localApiKey') . '" > /dev/null 2>&1 &');
			sleep(5);
		}
		$camera->setCache('lastStreamCall',strtotime('now'));
		ajax::success();
	}
	
	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
