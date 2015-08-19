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

	if (init('action') == 'uploadConfCam') {
		$uploaddir = dirname(__FILE__) . '/../config';
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir);
		}
		$uploaddir .= '/devices/';
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir);
		}
		if (!file_exists($uploaddir)) {
			throw new Exception(__('Répertoire d\'upload non trouvé : ', __FILE__) . $uploaddir);
		}
		if (!isset($_FILES['file'])) {
			throw new Exception(__('Aucun fichier trouvé. Vérifié parametre PHP (post size limit)', __FILE__));
		}
		if (filesize($_FILES['file']['tmp_name']) > 2000000) {
			throw new Exception(__('Le fichier est trop gros (miximum 2mo)', __FILE__));
		}
		if (!is_json(file_get_contents($_FILES['file']['tmp_name']))) {
			throw new Exception(__('Le fichier json est invalide', __FILE__));
		}
		if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . '/' . $_FILES['file']['name'])) {
			throw new Exception(__('Impossible de déplacer le fichier temporaire', __FILE__));
		}
		ajax::success();
	}

	if (init('action') == 'removeRecord') {
		$file = init('file');
		$record_dir = calculPath(config::byKey('recordDir', 'camera'));
		shell_exec('rm -rf ' . $record_dir . '/' . $file);
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
