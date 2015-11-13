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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class camera extends eqLogic {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	public static function event() {
		$cmd = cameraCmd::byId(init('id'));
		if (!is_object($cmd)) {
			throw new Exception('Commande ID camera inconnu : ' . init('id'));
		}
		$cmd->event(init('value'));
	}

	public static function cronHourly() {
		$record_dir = calculPath(config::byKey('recordDir', 'camera'));
		$max_size = config::byKey('maxSizeRecordDir', 'camera') * 1024 * 1024;
		$i = 0;
		while (getDirectorySize($record_dir) > $max_size) {
			$older = array('file' => null, 'datetime' => null);
			foreach (ls($record_dir, '*') as $dir) {
				foreach (ls($record_dir . '/' . $dir, '*') as $file) {
					if ($older['datetime'] == null) {
						$older['file'] = $record_dir . '/' . $dir . '/' . $file;
						$older['datetime'] = filemtime($record_dir . '/' . $dir . '/' . $file);
					}
					if ($older['datetime'] > filemtime($record_dir . '/' . $dir . '/' . $file)) {
						$older['file'] = $record_dir . '/' . $dir . '/' . $file;
						$older['datetime'] = filemtime($record_dir . '/' . $dir . '/' . $file);
					}
				}
			}
			if ($older['file'] == null) {
				throw new Exception(__('Erreur aucun fichier trouvé à supprimer alors que le répertoire fait : ' . getDirectorySize($record_dir), __FILE__));
			}
			unlink($older['file']);
			$i++;
			if ($i > 500) {
				throw new Exception(__('Plus de 500 enregistrements videos supprimés. Je m\'arrête', __FILE__));
			}
		}

	}

	public static function devicesParameters($_device = '') {
		$path = dirname(__FILE__) . '/../config/devices';
		if (isset($_device) && $_device != '') {
			$files = ls($path, $_device . '.json', false, array('files', 'quiet'));
			if (count($files) == 1) {
				try {
					$content = file_get_contents($path . '/' . $files[0]);
					if (is_json($content)) {
						$deviceConfiguration = json_decode($content, true);
						return $deviceConfiguration[$_device];
					}
				} catch (Exception $e) {
					return array();
				}
			}
		}
		$files = ls($path, '*.json', false, array('files', 'quiet'));
		$return = array();
		foreach ($files as $file) {
			try {
				$content = file_get_contents($path . '/' . $file);
				if (is_json($content)) {
					$return = array_merge($return, json_decode($content, true));
				}
			} catch (Exception $e) {

			}
		}
		if (isset($_device) && $_device != '') {
			if (isset($return[$_device])) {
				return $return[$_device];
			}
			return array();
		}
		return $return;
	}

	/*     * *********************Methode d'instance************************* */

	public function preSave() {
		if ($this->getConfiguration('alertMessageCommand') != '') {
			$cmd = cmd::byId(str_replace('#', '', $this->getConfiguration('alertMessageCommand')));
			if (is_object($cmd) && $cmd->getEqType_name() == 'camera') {
				throw new Exception(__('La "Commande d\'alerte" ne peut être de type caméra', __FILE__));
			}
		}
	}

	public function preUpdate() {
		$this->setCategory('security', 1);
		if ($this->getConfiguration('ip') == '') {
			throw new Exception(__('L\'adresse IP de la camera ne peut être vide', __FILE__));
		}
	}

	public function postSave() {
		if ($this->getConfiguration('applyDevice') != $this->getConfiguration('device')) {
			$this->applyModuleConfiguration();
		}
		if ($this->getConfiguration('refreshDelay') == '') {
			$this->setConfiguration('refreshDelay', 2);
		}
		$browseRecord = $this->getCmd(null, 'urlFlux');
		if (!is_object($browseRecord)) {
			$browseRecord = new cameraCmd();
		}
		$browseRecord->setName(__('Flux video', __FILE__));
		$browseRecord->setEventOnly(1);
		$browseRecord->setConfiguration('request', '-');
		$browseRecord->setType('info');
		$browseRecord->setLogicalId('urlFlux');
		$browseRecord->setIsVisible(0);
		$browseRecord->setEqLogic_id($this->getId());
		$browseRecord->setSubType('string');
		$browseRecord->save();

		$browseRecord = $this->getCmd(null, 'browseRecord');
		if (is_object($browseRecord)) {
			$browseRecord->remove();
		}

		$recordState = $this->getCmd(null, 'recordState');
		if (!is_object($recordState)) {
			$recordState = new cameraCmd();
		}
		$recordState->setName(__('Status enregistrement', __FILE__));
		$recordState->setConfiguration('recordState', 1);
		$recordState->setEventOnly(1);
		$recordState->setConfiguration('request', '-');
		$recordState->setType('info');
		$recordState->setLogicalId('recordState');
		$recordState->setIsVisible(0);
		$recordState->setEqLogic_id($this->getId());
		$recordState->setSubType('binary');
		$recordState->save();

		$recordCmd = $this->getCmd(null, 'recordCmd');
		if (!is_object($recordCmd)) {
			$recordCmd = new cameraCmd();
		}
		$recordCmd->setName(__('Lancer enregistrement', __FILE__));
		$recordCmd->setConfiguration('request', '-');
		$recordCmd->setType('action');
		$recordCmd->setLogicalId('recordCmd');
		$recordCmd->setEqLogic_id($this->getId());
		$recordCmd->setSubType('slider');
		$recordCmd->setOrder(999);
		$recordCmd->setDisplay('slider_placeholder', __('Durée enregistrement (s)', __FILE__));
		$recordCmd->setDisplay('icon', '<i class="fa fa-circle"></i>');
		$recordCmd->save();

		$stopRecordCmd = $this->getCmd(null, 'stopRecordCmd');
		if (!is_object($stopRecordCmd)) {
			$stopRecordCmd = new cameraCmd();
		}
		$stopRecordCmd->setName(__('Arrêter enregistrement', __FILE__));
		$stopRecordCmd->setConfiguration('request', '-');
		$stopRecordCmd->setType('action');
		$stopRecordCmd->setLogicalId('stopRecordCmd');
		$stopRecordCmd->setEqLogic_id($this->getId());
		$stopRecordCmd->setSubType('other');
		$stopRecordCmd->setOrder(999);
		$stopRecordCmd->setDisplay('icon', '<i class="fa fa-stop"></i>');
		$stopRecordCmd->save();

		$takeSnapshot = $this->getCmd(null, 'takeSnapshot');
		if (!is_object($takeSnapshot)) {
			$takeSnapshot = new cameraCmd();
		}
		$takeSnapshot->setName(__('Capture', __FILE__));
		$takeSnapshot->setConfiguration('request', '-');
		$takeSnapshot->setType('action');
		$takeSnapshot->setLogicalId('takeSnapshot');
		$takeSnapshot->setEqLogic_id($this->getId());
		$takeSnapshot->setSubType('other');
		$takeSnapshot->setOrder(999);
		$takeSnapshot->setDisplay('icon', '<i class="fa fa-picture-o"></i>');
		$takeSnapshot->save();

		$sendSnapshot = $this->getCmd(null, 'sendSnapshot');
		if (!is_object($sendSnapshot)) {
			$sendSnapshot = new cameraCmd();
		}
		$sendSnapshot->setName(__('Envoyer une capture', __FILE__));
		$sendSnapshot->setConfiguration('request', '-');
		$sendSnapshot->setType('action');
		$sendSnapshot->setLogicalId('sendSnapshot');
		$sendSnapshot->setEqLogic_id($this->getId());
		$sendSnapshot->setSubType('message');
		$sendSnapshot->setIsVisible(0);
		$sendSnapshot->save();
	}

	public function toHtml($_version = 'dashboard') {
		if ($this->getIsEnable() != 1) {
			return '';
		}
		if (!$this->hasRight('r')) {
			return '';
		}
		$version = jeedom::versionAlias($_version);
		if ($this->getDisplay('hideOn' . $version) == 1) {
			return '';
		}
		$vcolor = 'cmdColor';
		if ($version == 'mobile') {
			$vcolor = 'mcmdColor';
		}
		$cmdColor = ($this->getPrimaryCategory() == '') ? '' : jeedom::getConfiguration('eqLogic:category:' . $this->getPrimaryCategory() . ':' . $vcolor);
		$action = '';
		foreach ($this->getCmd() as $cmd) {
			if ($cmd->getIsVisible() == 1) {
				if ($cmd->getLogicalId() != 'urlFlux' && $cmd->getLogicalId() != 'stopRecordCmd' && $cmd->getLogicalId() != 'recordCmd' && $cmd->getLogicalId() != 'recordState' && $cmd->getConfiguration('stopCmd') != 1) {
					if ($cmd->getDisplay('hideOn' . $version) == 1) {
						continue;
					}
					if ($cmd->getDisplay('forceReturnLineBefore', 0) == 1) {
						$action .= '<br/>';
					}
					if ($cmd->getType() == 'action' && $cmd->getSubType() == 'other') {
						$replace = array(
							'#id#' => $cmd->getId(),
							'#stopCmd#' => ($cmd->getConfiguration('stopCmdUrl') != '') ? 1 : 0,
							'#name#' => ($cmd->getDisplay('icon') != '') ? $cmd->getDisplay('icon') : $cmd->getName(),
							'#cmdColor#' => $cmdColor,
						);
						$action .= template_replace($replace, getTemplate('core', jeedom::versionAlias($version), 'camera_action', 'camera')) . ' ';
					} else {
						$action .= $cmd->toHtml($_version, $cmdColor);
					}

					if ($cmd->getDisplay('forceReturnLineAfter', 0) == 1) {
						$action .= '<br/>';
					}
				}
			}
		}
		$replace_eqLogic = array(
			'#id#' => $this->getId(),
			'#url#' => $this->getUrl($this->getConfiguration('urlStream'), true),
			'#refreshDelay#' => $this->getConfiguration('refreshDelay', 1) * 1000,
			'#background_color#' => $this->getBackgroundColor(jeedom::versionAlias($_version)),
			'#humanname#' => $this->getHumanName(),
			'#name#' => $this->getName(),
			'#eqLink#' => ($this->hasRight('w')) ? $this->getLinkToConfiguration() : '#',
			'#height#' => $this->getDisplay('height', 'auto'),
			'#width#' => $this->getDisplay('width', 'auto'),
			'#cmdColor#' => $cmdColor,
		);
		$stopRecord = $this->getCmd(null, 'stopRecordCmd');
		$record = $this->getCmd(null, 'recordCmd');
		$recordState = $this->getCmd(null, 'recordState');
		$replace = array(
			'#record_id#' => $record->getId(),
			'#stopRecord_id#' => $stopRecord->getId(),
			'#recordState#' => $recordState->execCmd(null, 2),
			'#recordState_id#' => $recordState->getId(),
			'#cmdColor#' => $cmdColor,
		);
		$action .= template_replace($replace, getTemplate('core', jeedom::versionAlias($_version), 'camera_record', 'camera'));

		$replace_eqLogic['#action#'] = $action;
		if ($_version == 'dview' || $_version == 'mview') {
			$object = $this->getObject();
			$replace_eqLogic['#name#'] = (is_object($object)) ? $object->getName() . ' - ' . $replace_eqLogic['#name#'] : $replace['#name#'];
		}

		$parameters = $this->getDisplay('parameters');
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace_eqLogic['#' . $key . '#'] = $value;
			}
		}
		return template_replace($replace_eqLogic, getTemplate('core', jeedom::versionAlias($version), 'camera', 'camera'));
	}

	public function getUrl($_complement = '', $_flux = false) {
		$replace = array(
			'#username#' => urlencode($this->getConfiguration('username')),
			'#password#' => urlencode($this->getConfiguration('password')),
		);
		if ($_flux) {
			return 'plugins/camera/core/php/snapshot.php?id=' . $this->getId() . '&apikey=' . config::byKey('api');
		}
		$url = $this->getConfiguration('protocole', 'http');
		$url .= '://';
		if ($this->getConfiguration('username') != '') {
			$url .= urlencode($this->getConfiguration('username')) . ':' . urlencode($this->getConfiguration('password')) . '@';
		}
		$port = $this->getConfiguration('port');
		$url .= $this->getConfiguration('ip');
		if ($port != '') {
			$url .= ':' . $port;
		}
		$url = str_replace(array_keys($replace), $replace, $url);
		$complement = str_replace(array_keys($replace), $replace, $_complement);
		if (strpos($complement, '/') !== 0) {
			$complement = '/' . $complement;
		}
		return $url . $complement;
	}

	public function getSnapshot() {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->getUrl($this->getConfiguration('urlStream')));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		if ($this->getConfiguration('username') != '') {
			$userpwd = $this->getConfiguration('username') . ':' . $this->getConfiguration('password');
			curl_setopt($ch, CURLOPT_USERPWD, $userpwd);
			$headers = array(
				'Content-Type:application/json',
				'Authorization: Basic ' . base64_encode($userpwd),
			);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public function applyModuleConfiguration() {
		$this->setConfiguration('applyDevice', $this->getConfiguration('device'));
		if ($this->getConfiguration('device') == '') {
			$this->save();
			return true;
		}
		$device = self::devicesParameters($this->getConfiguration('device'));
		if (!is_array($device) || !isset($device['commands'])) {
			return true;
		}
		if (isset($device['configuration'])) {
			foreach ($device['configuration'] as $key => $value) {
				$this->setConfiguration($key, $value);
			}
		}
		$cmd_order = 0;
		$link_cmds = array();
		foreach ($device['commands'] as $command) {
			if (isset($device['commands']['logicalId'])) {
				continue;
			}
			$cmd = null;
			foreach ($this->getCmd() as $liste_cmd) {
				if (isset($command['name']) && $liste_cmd->getName() == $command['name']) {
					$cmd = $liste_cmd;
					break;
				}
			}
			try {
				if ($cmd == null || !is_object($cmd)) {
					$cmd = new cameraCmd();
					$cmd->setOrder($cmd_order);
					$cmd->setEqLogic_id($this->getId());
				} else {
					$command['name'] = $cmd->getName();
				}
				utils::a2o($cmd, $command);
				if (isset($command['value'])) {
					$cmd->setValue(null);
				}
				$cmd->save();
				$cmd_order++;
			} catch (Exception $exc) {
				error_log($exc->getMessage());
			}
		}
		$this->save();
	}

	public function recordCam($_recordTime = 300) {
		$cmd = ' php ' . dirname(__FILE__) . '/../../core/php/record.php';
		$cmd .= ' id=' . $this->getId();
		$result = shell_exec('ps ax | grep "core/php/record.php id=' . $this->getId() . ' recordTime" | grep -v "grep" | wc -l');
		if ($result > 0) {
			return true;
		}
		$cmd .= ' recordTime=' . $_recordTime;
		$cmd .= ' >> ' . log::getPathToLog('camera_record') . ' 2>&1 &';
		log::add('camera', 'debug', $cmd);
		shell_exec('nohup ' . $cmd);
	}

	public function stopRecord() {
		$result = shell_exec('ps ax | grep "core/php/record.php id=' . $this->getId() . ' recordTime" | grep -v "grep" | wc -l');
		if ($result > 0) {
			$pid = shell_exec('ps ax | grep "core/php/record.php id=' . $this->getId() . ' recordTime" | grep -v "grep" | awk \'{print $1}\'');
			exec('kill -9 ' . $pid . ' > /dev/null 2>&1');
		}
		$process = $this->getUrl($this->getConfiguration('urlStream'));
		$pid = shell_exec("ps -ef | grep '" . $process . "' | grep -v grep | awk '{print $2}' | xargs kill -SIGINT");
		$recordState = $this->getCmd(null, 'recordState');
		$recordState->event(0);
		$this->refreshWidget();
		return true;
	}

	public function takeSnapshot() {
		$output_dir = calculPath(config::byKey('recordDir', 'camera'));
		if (!file_exists($output_dir)) {
			if (!mkdir($output_dir, 0777, true)) {
				throw new Exception(__('Impossible de creer le dossier : ', __FILE__) . $output_dir);
			}
		}
		if (!is_writable($output_dir)) {
			throw new Exception(__('Impossible d\'écrire dans le dossier : ', __FILE__) . $output_dir);
		}
		$output_dir .= '/' . $this->getId();
		if (!file_exists($output_dir)) {
			if (!mkdir($output_dir, 0777, true)) {
				throw new Exception(__('Impossible de creer le dossier : ', __FILE__) . $output_dir);
			}
		}
		if (!is_writable($output_dir)) {
			throw new Exception(__('Impossible d\'écrire dans le dossier : ', __FILE__) . $output_dir);
		}
		$output_file = $output_dir . '/' . date('Y-m-d_H:i:s') . '.jpg';
		file_put_contents($output_file, $this->getSnapshot());
		return $output_file;
	}

	public function export($_withCmd = true) {
		if ($this->getConfiguration('device') != '') {
			return array(
				$this->getConfiguration('device') => self::devicesParameters($this->getConfiguration('device')),
			);
		} else {
			$export = parent::export();
			if (isset($export['configuration']['device'])) {
				unset($export['configuration']['device']);
			}
			if (isset($export['configuration']['ip'])) {
				unset($export['configuration']['ip']);
			}
			if (isset($export['configuration']['port'])) {
				unset($export['configuration']['port']);
			}
			if (isset($export['configuration']['username'])) {
				unset($export['configuration']['username']);
			}
			if (isset($export['configuration']['password'])) {
				unset($export['configuration']['password']);
			}
			if (isset($export['configuration']['applyDevice'])) {
				unset($export['configuration']['applyDevice']);
			}
			if (isset($export['configuration']) && count($export['configuration']) == 0) {
				unset($export['configuration']);
			}
			if (isset($export['_object'])) {
				unset($export['_object']);
			}
			if (isset($export['cmd'])) {
				$export['commands'] = $export['cmd'];
				unset($export['cmd']);
			}
			return array(
				'todo.todo' => $export,
			);
		}
	}

	/*     * **********************Getteur Setteur*************************** */

}

class cameraCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function imperihomeGenerate($ISSStructure) {
		$eqLogic = $this->getEqLogic();
		$object = $eqLogic->getObject();
		$type = 'DevCamera';
		$info_device = array(
			'id' => $this->getId(),
			'name' => $eqLogic->getName(),
			'room' => (is_object($object)) ? $object->getId() : 99999,
			'type' => $type,
			'params' => array(),
		);
		$info_device['params'] = $ISSStructure[$info_device['type']]['params'];
		$info_device['params'][0]['value'] = $eqLogic->getConfiguration('username');
		$info_device['params'][1]['value'] = $eqLogic->getConfiguration('password');
		$info_device['params'][2]['value'] = $eqLogic->getUrl($eqLogic->getConfiguration('urlStream'));
		$info_device['params'][3]['value'] = '';
		$info_device['params'][4]['value'] = network::getNetworkAccess('external') . '/' . $eqLogic->getUrl($eqLogic->getConfiguration('urlStream'), true);
		$info_device['params'][5]['value'] = '';
		return $info_device;
	}

	public function imperihomeAction($_action, $_value) {
		return;
	}

	public function imperihomeCmd() {
		if ($this->getLogicalId() == 'urlFlux') {
			return true;
		}
		return false;
	}

	public function dontRemoveCmd() {
		if ($this->getLogicalId() == 'recordCmd') {
			return true;
		}
		if ($this->getLogicalId() == 'stopRecordCmd') {
			return true;
		}
		if ($this->getLogicalId() == 'recordState') {
			return true;
		}
		if ($this->getLogicalId() == 'browseRecord') {
			return true;
		}
		if ($this->getLogicalId() == 'takeSnapshot') {
			return true;
		}
		if ($this->getLogicalId() == 'sendSnapshot') {
			return true;
		}
		if ($this->getLogicalId() == 'urlFlux') {
			return true;
		}
		return false;
	}

	public function preSave() {
		if ($this->getConfiguration('request') == '' && $this->getType() == 'action') {
			throw new Exception(__('Le champs requête ne peut etre vide', __FILE__));
		}
		if ($this->getType() == 'info') {
			$this->setEventOnly(1);
		}
	}

	public function execute($_options = null) {
		if (isset($_options['stopCmd']) && $_options['stopCmd'] == 1) {
			$request = $this->getConfiguration('stopCmdUrl');
		} else {
			$request = $this->getConfiguration('request');
		}
		switch ($this->getType()) {
			case 'action':
				switch ($this->getSubType()) {
					case 'slider':
						$request = str_replace('#slider#', $_options['slider'], $request);
						break;
					case 'color':
						$request = str_replace('#color#', $_options['color'], $request);
				}
				break;
		}
		$eqLogic = $this->getEqLogic();
		if ($this->getLogicalId() == 'recordCmd') {
			$eqLogic->recordCam($_options['slider']);
			return true;
		}
		if ($this->getLogicalId() == 'stopRecordCmd') {
			$eqLogic->stopRecord();
			return true;
		}
		if ($this->getLogicalId() == 'takeSnapshot') {
			$eqLogic->takeSnapshot();
			return true;
		}
		if ($this->getLogicalId() == 'sendSnapshot') {
			$_options['files'] = array();
			$nb = $eqLogic->getConfiguration('alertMessageNbSnapshot', 1);
			for ($i = 0; $i < $nb; $i++) {
				$_options['files'][] = $eqLogic->takeSnapshot();
				sleep(1);
			}
			$cmds = explode('&&', $eqLogic->getConfiguration('alertMessageCommand'));
			foreach ($cmds as $id) {
				$cmd = cmd::byId(str_replace('#', '', $id));
				if (is_object(!$cmd)) {
					continue;
				}
				$cmd->execCmd($_options);
			}
			return true;
		}
		$url = $eqLogic->getUrl($request);
		$http = new com_http($url, $eqLogic->getConfiguration('username'), $eqLogic->getConfiguration('password'));
		$http->setNoReportError(true);
		$http->exec(2);
		return true;
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
