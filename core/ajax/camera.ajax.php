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
			$object = object::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
		} else {
			$object = object::byId(init('object_id'));
		}
		if (!is_object($object)) {
			$object = object::rootObject();
		}
		$return = array();
		$return['eqLogics'] = array();
		if (init('object_id') == '') {
			foreach (object::all() as $object) {
				foreach ($object->getEqLogic(true, false, 'camera') as $camera) {
					$return['eqLogics'][] = $camera->toHtml(init('version'));
				}
			}
		} else {
			foreach ($object->getEqLogic(true, false, 'camera') as $camera) {
				$return['eqLogics'][] = $camera->toHtml(init('version'));
			}
			foreach (object::buildTree($object) as $child) {
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

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
