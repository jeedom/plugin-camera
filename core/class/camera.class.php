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

	private $_height = 110;
	private $_width = 147;

	/*     * ***********************Methode static*************************** */

	public static function cronHourly() {
		if (config::byKey('keycam') == '') {
			config::save('keycam', config::genKey());
		}
		$c = new Cron\CronExpression('00 * * * *', new Cron\FieldFactory);
		if ($c->isDue()) {
			config::save('keycam', config::genKey());
			$record_dir = calculPath(config::byKey('recordDir', 'camera'));
			$max_size = config::byKey('maxSizeRecordDir', 'camera') * 1024 * 1024;
			$i = 0;
			while (getDirectorySize($record_dir) > $max_size) {
				$older = array('file' => null, 'datetime' => null);
				foreach (ls($record_dir, '*') as $file) {
					if ($older['datetime'] == null) {
						$older['file'] = $record_dir . '/' . $file;
						$older['datetime'] = filemtime($record_dir . '/' . $file);
					}
					if ($older['datetime'] > filemtime($record_dir . '/' . $file)) {
						$older['file'] = $record_dir . '/' . $file;
						$older['datetime'] = filemtime($record_dir . '/' . $file);
					}
				}
				if ($older['file'] == null) {
					throw new Exception(__('Erreur aucun fichier trouvé à supprimer alors que le répertoire fait : ' . getDirectorySize($record_dir), __FILE__));
				}
				unlink($older['file']);
				$i++;
				if ($i > 50) {
					throw new Exception(__('Plus de 50 enregistrements videos supprimés. Je m\'arrête', __FILE__));
				}
			}
		}
	}

	public static function shareOnMarket(&$market) {
		$moduleFile = dirname(__FILE__) . '/../config/devices/' . $market->getLogicalId() . '.json';
		if (!file_exists($moduleFile)) {
			throw new Exception('Impossible de trouver le widget ' . $moduleFile);
		}
		$tmp = dirname(__FILE__) . '/../../../../tmp/' . $market->getLogicalId() . '.zip';
		if (file_exists($tmp)) {
			if (!unlink($tmp)) {
				throw new Exception(__('Impossible de supprimer : ', __FILE__) . $tmp . __('. Vérifiez les droits', __FILE__));
			}
		}
		if (!create_zip($moduleFile, $tmp)) {
			throw new Exception(__('Echec de création du zip. Répertoire source : ', __FILE__) . $moduleFile . __(' / Répertoire cible : ', __FILE__) . $tmp);
		}
		return $tmp;
	}

	public static function getFromMarket(&$market, $_path) {
		$cibDir = dirname(__FILE__) . '/../config/devices/';
		if (!file_exists($cibDir)) {
			throw new Exception(__('Impossible d\'installer la configuration du module le repertoire n\éxiste pas : ', __FILE__) . $cibDir);
		}
		$zip = new ZipArchive;
		if ($zip->open($_path) === TRUE) {
			$zip->extractTo($cibDir . '/');
			$zip->close();
		} else {
			throw new Exception('Impossible de décompresser le zip : ' . $_path);
		}
		$moduleFile = dirname(__FILE__) . '/../config/devices/' . $market->getLogicalId() . '.json';
		if (!file_exists($moduleFile)) {
			throw new Exception(__('Echec de l\'installation. Impossible de trouver le module ', __FILE__) . $moduleFile);
		}

		foreach (eqLogic::byTypeAndSearhConfiguration('camera', $market->getLogicalId()) as $eqLogic) {
			$eqLogic->applyModuleConfiguration();
		}
	}

	public static function removeFromMarket(&$market) {
		$moduleFile = dirname(__FILE__) . '/../config/devices/' . $market->getLogicalId() . '.json';
		if (!file_exists($moduleFile)) {
			throw new Exception(__('Echec lors de la suppression. Impossible de trouver le module ', __FILE__) . $moduleFile);
		}
		unlink($moduleFile);
	}

	public static function listMarketObject() {
		$return = array();
		foreach (camera::devicesParameters() as $logical_id => $info) {
			$return[] = $logical_id;
		}
		return $return;
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

	public static function formatIp($_ip, $_protocole = false) {
		$_ip = str_replace('http://', '', $_ip);
		$_ip = str_replace('https://', '', $_ip);
		$_ip = str_replace('rtsp://', '', $_ip);
		if ($_protocole == 'rtsp') {
			return 'rtsp://' . $_ip;
		}
		if ($_protocole == 'https') {
			return 'https://' . $_ip;
		}
		return 'http://' . $_ip;
	}

	/*     * *********************Methode d'instance************************* */

	public function preSave() {
		$this->setCategory('security', 1);
		if ($this->getConfiguration('proxy_mode') == 'nginx' || $this->getConfiguration('proxy_mode') == 'apache') {
			if ($this->getConfiguration('ip_cam') == '') {
				throw new Exception(__('L\'adresse IP de la camera ne peut être vide', __FILE__));
			}
			$this->setConfiguration('ip_ext', '#username#:#password#@#ip#/cam' . $this->getId());
			$this->setConfiguration('ip', '#username#:#password#@#ip#/cam' . $this->getId());
		} else {
			if ($this->getConfiguration('username') != '' && strpos($this->getConfiguration('urlStream'), '#username#') === false) {
				if (strpos($this->getConfiguration('ip_ext'), '#username#') === false) {
					$ip = explode('://', $this->getConfiguration('ip_ext'));
					if (count($ip) == 2) {
						$this->setConfiguration('ip_ext', $ip[0] . '#username#:#password#@' . $ip[1]);
					} else {
						$this->setConfiguration('ip_ext', '#username#:#password#@' . $ip[0]);
					}
				}
				if (strpos($this->getConfiguration('ip'), '#username#') === false) {
					$ip = explode('://', $this->getConfiguration('ip'));
					if (count($ip) == 2) {
						$this->setConfiguration('ip', $ip[0] . '#username#:#password#@' . $ip[1]);
					} else {
						$this->setConfiguration('ip', '#username#:#password#@' . $ip[0]);
					}
				}
			}
		}
	}

	public function postSave() {
		if ($this->getConfiguration('applyDevice') != $this->getConfiguration('device')) {
			$this->applyModuleConfiguration();
		}
		$browseRecord = $this->getCmd(null, 'browseRecord');
		if (!is_object($browseRecord)) {
			$browseRecord = new cameraCmd();
		}
		$browseRecord->setName(__('Parcourir les video', __FILE__));
		$browseRecord->setEventOnly(1);
		$browseRecord->setConfiguration('request', '-');
		$browseRecord->setType('info');
		$browseRecord->setLogicalId('browseRecord');
		$browseRecord->setIsVisible(0);
		$browseRecord->setEqLogic_id($this->getId());
		$browseRecord->setSubType('binary');
		$browseRecord->save();

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
		$recordCmd->setSubType('other');
		$recordCmd->setOrder(0);
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
		$stopRecordCmd->setOrder(0);
		$stopRecordCmd->setDisplay('icon', '<i class="fa fa-stop"></i>');
		$stopRecordCmd->save();

		if ($this->getConfiguration('proxy_mode') == 'nginx') {
			$ip = $this->getConfiguration('ip_cam');
			if (trim($this->getConfiguration('port_cam')) != '') {
				$ip .= ':' . $this->getConfiguration('port_cam');
			}
			$rules = array(
				"location /cam" . $this->getId() . "/ {\n" .
				"proxy_pass http://" . $ip . "/;\n" .
				"proxy_redirect off;\n" .
				"proxy_set_header Host \$host:\$server_port;\n" .
				"proxy_set_header X-Real-IP \$remote_addr;\n" .
				"}",
			);
			network::nginx_saveRule($rules);
		} else if ($this->getConfiguration('proxy_mode') == 'apache') {
			$ip = $this->getConfiguration('ip_cam');
			if (trim($this->getConfiguration('port_cam')) != '') {
				$ip .= ':' . $this->getConfiguration('port_cam');
			}
			$rules = array(
				"ProxyPass /cam" . $this->getId() . "/ http://" . $ip . "/",
				"ProxyPassReverse /cam" . $this->getId() . "/ http://" . $ip . "/",
				"<Location /cam" . $this->getId() . "/>\n" .
				"\tOrder allow,deny\n" .
				"\tAllow from all\n" .
				"</Location>",
			);
			network::apache_saveRule($rules);

			$rules = array(
				"location /cam" . $this->getId() . "/ {\n",
			);
			network::nginx_removeRule($rules);

		} else {
			try {
				$rules = array(
					"location /cam" . $this->getId() . "/ {\n",
				);
				network::nginx_removeRule($rules);

				$rules = array(
					"!.*ProxyPass /cam" . $this->getId() . "/.*!",
					"!.*ProxyPassReverse /cam" . $this->getId() . "/.*!",
					"!<Location /cam" . $this->getId() . "/>[^<]*</Location>!s",
				);
				network::apache_removeRule($rules);
			} catch (Exception $e) {

			}

		}
	}

	public function preRemove() {
		if ($this->getConfiguration('proxy_mode') == 'nginx') {
			$rules = array(
				"location /cam" . $this->getId() . "/ {\n",
			);
			network::nginx_removeRule($rules);
		}
		if ($this->getConfiguration('proxy_mode') == 'apache') {
			$rules = array(
				"!.*ProxyPass /cam" . $this->getId() . "/.*!",
				"!.*ProxyPassReverse /cam" . $this->getId() . "/.*!",
				"!<Location /cam" . $this->getId() . "/>[^<]*</Location>!s",
			);
			network::apache_removeRule($rules);
		}
	}

	public function toHtml($_version = 'dashboard') {
		if ($this->getIsEnable() != 1) {
			return '';
		}
		if (!$this->hasRight('r')) {
			return '';
		}
		$stopCmd_id = '';
		foreach ($this->getCmd() as $cmd) {
			if ($cmd->getConfiguration('stopCmd') == 1) {
				$stopCmd_id = $cmd->getId();
			}
		}
		$action = '';
		foreach ($this->getCmd('action') as $cmd) {
			if ($cmd->getIsVisible() == 1 && $cmd->getLogicalId() != 'stopRecordCmd' && $cmd->getLogicalId() != 'recordCmd' && $cmd->getLogicalId() != 'recordState') {
				if ($cmd->getDisplay('forceReturnLineBefore', 0) == 1) {
					$action .= '<br/>';
				}
				if ($cmd->getSubType() == 'other') {
					$replace = array(
						'#id#' => $cmd->getId(),
						'#stopCmd_id#' => $stopCmd_id,
						'#name#' => ($cmd->getDisplay('icon') != '') ? $cmd->getDisplay('icon') : $cmd->getName(),
					);
					$action .= template_replace($replace, getTemplate('core', jeedom::versionAlias($_version), 'camera_action', 'camera')) . ' ';
				} else {
					$action .= $cmd->toHtml($_version);
				}
				if ($cmd->getDisplay('forceReturnLineAfter', 0) == 1) {
					$action .= '<br/>';
				}
			}
		}
		if (netMatch('192.168.*.*', getClientIp())) {
			$protocole = 'protocole';
		} else {
			$protocole = 'protocoleExt';
		}
		$replace_eqLogic = array(
			'#id#' => $this->getId(),
			'#url#' => $this->getUrl($this->getConfiguration('urlStream'), '', $protocole),
			'#width#' => $this->getWidth(),
			'#height#' => $this->getHeight(),
			'#password#' => $this->getConfiguration('password'),
			'#username#' => $this->getConfiguration('username'),
			'#background_color#' => $this->getBackgroundColor(jeedom::versionAlias($_version)),
			'#humanname#' => $this->getHumanName(),
			'#name#' => $this->getName(),
			'#eqLink#' => $this->getLinkToConfiguration(),
			'#displayProtocol#' => $this->getConfiguration('displayProtocol', 'image'),
			'#jpegRefreshTime#' => $this->getConfiguration('jpegRefreshTime', 1),
			'#hideFolder#' => 0,
		);

		$stopRecord = $this->getCmd(null, 'stopRecordCmd');
		$record = $this->getCmd(null, 'recordCmd');
		if ($stopRecord->getIsVisible() == 1 && $record->getIsVisible() == 1) {
			$recordState = $this->getCmd(null, 'recordState');
			$replace = array(
				'#record_id#' => $record->getId(),
				'#stopRecord_id#' => $stopRecord->getId(),
				'#recordState#' => $recordState->execCmd(),
				'#recordState_id#' => $recordState->getId(),
			);
			if ($stopRecord->getDisplay('forceReturnLineBefore', 0) == 1) {
				$action .= '<br/>';
			}
			$action .= template_replace($replace, getTemplate('core', jeedom::versionAlias($_version), 'camera_record', 'camera'));
			if ($stopRecord->getDisplay('forceReturnLineAfter', 0) == 1) {
				$action .= '<br/>';
			}
			$replace_eqLogic['#hideFolder#'] = 1;
		}
		$replace_eqLogic['#action#'] = $action;

		$browseRecord = $this->getCmd(null, 'browseRecord');
		if (is_object($browseRecord)) {
			$replace_eqLogic['#browseRecord_id#'] = $browseRecord->getId();
		}

		if ($_version == 'dview') {
			$object = $this->getObject();
			$replace_eqLogic['#name#'] = (is_object($object)) ? $object->getName() . ' - ' . $replace_eqLogic['#name#'] : $replace['#name#'];
		}
		if ($_version == 'mview') {
			$object = $this->getObject();
			$replace_eqLogic['#name#'] = (is_object($object)) ? $object->getName() . ' - ' . $replace_eqLogic['#name#'] : $replace['#name#'];
		}

		$parameters = $this->getDisplay('parameters');
		if (is_array($parameters)) {
			foreach ($parameters as $key => $value) {
				$replace_eqLogic['#' . $key . '#'] = $value;
			}
		}
		return template_replace($replace_eqLogic, getTemplate('core', jeedom::versionAlias($_version), 'camera', 'camera'));
	}

	public function getUrl($_complement = '', $_auto = '', $_protocole = 'protocole') {
		$replace = array(
			'#username#' => $this->getConfiguration('username'),
			'#password#' => $this->getConfiguration('password'),
		);
		if (((netMatch('192.168.*.*', getClientIp()) || netMatch('10.0.*.*', getClientIp())) && $_auto == '') || $_auto == 'internal') {
			$replace['#ip#'] = network::getNetworkAccess('internal', 'ip:port');
			$url = self::formatIp($this->getConfiguration('ip'), $this->getConfiguration($_protocole, 'http'));
			if ($this->getConfiguration('port') != '' && $this->getConfiguration('proxy_mode') != 'nginx' && $this->getConfiguration('proxy_mode') != 'apache') {
				$url .= ':' . $this->getConfiguration('port');
			}
		} else {
			$replace['#ip#'] = network::getNetworkAccess('external', 'dns:port');
			$url = self::formatIp($this->getConfiguration('ip_ext'), $this->getConfiguration($_protocole, 'http'));
			if ($this->getConfiguration('proxy_mode') == 'nginx' || $this->getConfiguration('proxy_mode') == 'apache') {
				$url = str_replace(array('http://', 'https://'), '', $url);
				$url = network::getNetworkAccess('external', 'proto') . $url;
			}
			if ($this->getConfiguration('port_ext') != '' && $this->getConfiguration('proxy_mode') != 'nginx' && $this->getConfiguration('proxy_mode') != 'apache') {
				$url .= ':' . $this->getConfiguration('port_ext');
			}
		}
		$url = str_replace(array_keys($replace), $replace, $url);
		$complement = str_replace(array_keys($replace), $replace, $_complement);
		if (strpos($complement, '/') !== 0) {
			$complement = '/' . $complement;
		}
		return $url . $complement;
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
		$process = $this->getUrl($this->getConfiguration('urlStream'), 'internal');
		$pid = shell_exec("ps -ef | grep '" . $process . "' | grep -v grep | awk '{print $2}' | xargs kill -SIGINT");
		$recordState = $this->getCmd(null, 'recordState');
		$recordState->event(0);
		$this->refreshWidget();
		return true;
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
			if (isset($export['configuration']['ip_cam'])) {
				unset($export['configuration']['ip_cam']);
			}
			if (isset($export['configuration']['port_cam'])) {
				unset($export['configuration']['port_cam']);
			}
			if (isset($export['configuration']['ip_ext'])) {
				unset($export['configuration']['ip_ext']);
			}
			if (isset($export['configuration']['port_ext'])) {
				unset($export['configuration']['port_ext']);
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

	function getHeight() {
		return $this->_height;
	}

	function getWidth() {
		return $this->_width;
	}

	function setHeight($_height) {
		$this->_height = $_height;
	}

	function setWidth($_width) {
		$this->_width = $_width;
	}

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
		$info_device['params'][2]['value'] = $camera->getUrl($camera->getConfiguration('urlStream'), '', 'protocole');
		$info_device['params'][3]['value'] = $camera->getUrl($camera->getConfiguration('urlStream'), '', 'protocole');
		$info_device['params'][4]['value'] = $camera->getUrl($camera->getConfiguration('urlStream'), '', 'protocoleExt');
		$info_device['params'][5]['value'] = $camera->getUrl($camera->getConfiguration('urlStream'), '', 'protocoleExt');
		return $info_device;
	}

	public function imperihomeAction($_action, $_value) {
		return;
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
		return false;
	}

	public function preSave() {
		if ($this->getConfiguration('request') == '') {
			throw new Exception(__('Le champs requête ne peut etre vide', __FILE__));
		}
	}

	public function execute($_options = null) {
		$request = $this->getConfiguration('request');
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
			$eqLogic->recordCam();
		} elseif ($this->getLogicalId() == 'stopRecordCmd') {
			$eqLogic->stopRecord();
		} else {
			$url = $eqLogic->getUrl($request, 'internal', 'protocoleCommande');
			log::add('camera', 'debug', 'Command : ' . $url);
			$http = new com_http($url, $eqLogic->getConfiguration('username'), $eqLogic->getConfiguration('password'));
			$http->setNoReportError(true);
			if ($this->getConfiguration('useCurlDigest') == 1) {
				$http->setCURLOPT_HTTPAUTH(CURLAUTH_DIGEST);
			}
			$http->exec($this->getConfiguration('timeout', 2));
		}
		return true;
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
