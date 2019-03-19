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
require_once dirname(__FILE__) . '/../../3rdparty/ponvif.php';

class camera extends eqLogic {
	/*     * *************************Attributs****************************** */
	
	public static $_widgetPossibility = array('custom' => true, 'custom::layout' => false);
	private static $_eqLogics = null;
	
	/*     * ***********************Methode static*************************** */
	
	public static function deamon_info() {
		$return = array();
		$return['log'] = '';
		$return['state'] = 'nok';
		$cron = cron::byClassAndFunction('camera', 'pull');
		if (is_object($cron) && $cron->running()) {
			$return['state'] = 'ok';
		}
		$return['launchable'] = 'ok';
		return $return;
	}
	
	public static function deamon_start() {
		self::deamon_stop();
		$deamon_info = self::deamon_info();
		if ($deamon_info['launchable'] != 'ok') {
			throw new Exception(__('Veuillez vérifier la configuration', __FILE__));
		}
		$cron = cron::byClassAndFunction('camera', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tache cron introuvable', __FILE__));
		}
		$cron->run();
	}
	
	public static function deamon_stop() {
		$cron = cron::byClassAndFunction('camera', 'pull');
		if (!is_object($cron)) {
			throw new Exception(__('Tache cron introuvable', __FILE__));
		}
		$cron->halt();
	}
	
	public function pull() {
		if (self::$_eqLogics == null) {
			self::$_eqLogics = self::byType('camera');
		}
		foreach (self::$_eqLogics as $eqLogic) {
			if ($eqLogic->getIsEnable() == 0 || $eqLogic->getConfiguration('hasPullFunction', 0) == 0) {
				continue;
			}
			try {
				require_once dirname(__FILE__) . '/../config/devices/' . $eqLogic->getConfiguration('device') . '.php';
				$function = str_replace('.', '_', $eqLogic->getConfiguration('device')) . '_update';
				if (function_exists($function)) {
					$function($eqLogic);
				}
			} catch (Exception $e) {
				
			}
		}
	}
	
	public static function dependancy_info() {
		$return = array();
		$return['log'] = 'camera_update';
		$return['state'] = 'ok';
		$return['progress_file'] = jeedom::getTmpFolder('camera') . '/dependance';
		if (exec('which avconv | wc -l') == 0) {
			$return['state'] = 'nok';
		}
		return $return;
	}
	
	public static function dependancy_install() {
		log::remove(__CLASS__ . '_update');
		return array('script' => dirname(__FILE__) . '/../../resources/install_#stype#.sh ' . jeedom::getTmpFolder('camera') . '/dependance', 'log' => log::getPathToLog(__CLASS__ . '_update'));
	}
	
	public static function event() {
		$cmd = cameraCmd::byId(init('id'));
		if (!is_object($cmd)) {
			throw new Exception('Commande ID camera inconnu : ' . init('id'));
		}
		$cmd->event(init('value'));
	}
	
	public static function interact($_query, $_parameters = array()) {
		$ok = false;
		$files = array();
		$matchs = explode("\n", str_replace('\n', "\n", config::byKey('interact::sentence', 'camera')));
		if (count($matchs) == 0) {
			return null;
		}
		$query = strtolower(sanitizeAccent($_query));
		foreach ($matchs as $match) {
			if (preg_match_all('/' . $match . '/', $query)) {
				$ok = true;
			}
		}
		if (!$ok) {
			return null;
		}
		$data = interactQuery::findInQuery('object', $_query);
		if (is_object($data['object'])) {
			foreach ($data['object']->getEqLogic(true, false, 'camera') as $camera) {
				try {
					$files[] = $camera->takeSnapshot();
				} catch (Exception $e) {
					log::add('camera', 'warning', $e->getMessage());
				}
			}
			foreach ($data['object']->getChilds() as $object) {
				foreach ($object->getEqLogic(true, false, 'camera') as $camera) {
					try {
						$files[] = $camera->takeSnapshot();
					} catch (Exception $e) {
						log::add('camera', 'warning', $e->getMessage());
					}
				}
			}
		}
		if (count($files) == 0) {
			return null;
		}
		return array('reply' => 'Ok', 'file' => $files);
	}
	
	public static function cronHourly() {
		$record_dir = calculPath(config::byKey('recordDir', 'camera'));
		if (!file_exists($record_dir)) {
			mkdir($record_dir, 0777, true);
		}
		$max_size = config::byKey('maxSizeRecordDir', 'camera') * 1024 * 1024;
		while (getDirectorySize($record_dir) > $max_size) {
			$older = array('file' => null, 'datetime' => null);
			foreach (ls($record_dir, '*') as $dir) {
				foreach (ls($record_dir . '/' . $dir, '*') as $file) {
					if ($older['datetime'] == null || $older['datetime'] > filemtime($record_dir . '/' . $dir . '/' . $file)) {
						$older['file'] = $record_dir . '/' . $dir . '/' . $file;
						$older['datetime'] = filemtime($record_dir . '/' . $dir . '/' . $file);
					}
				}
			}
			if ($older['file'] == null) {
				throw new Exception(__('Erreur aucun fichier trouvé à supprimer alors que le répertoire fait : ', __FILE__) . getDirectorySize($record_dir));
			}
			unlink($older['file']);
		}
		foreach (camera::byType('camera') as $camera) {
			try {
				$processes = system::ps('core/php/record.php id=' . $camera->getId());
				foreach ($processes as $process) {
					$duration = shell_exec('ps -p ' . $process['pid'] . ' -o etimes -h');
					if ($duration > $camera->getConfiguration('maxReccordTime', 600)) {
						$camera->stopRecord();
					}
				}
			} catch (Exception $e) {
				
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
					$deviceConfiguration = is_json($content, array());
					return $deviceConfiguration[$_device];
				} catch (Exception $e) {
					return array();
				}
			}
		}
		$files = ls($path, '*.json', false, array('files', 'quiet'));
		$return = array();
		foreach ($files as $file) {
			try {
				$return = array_merge($return, is_json(file_get_contents($path . '/' . $file), array()));
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
	
	public static function deadCmd() {
		$return = array();
		foreach (eqLogic::byType('camera') as $camera) {
			if ($camera->getConfiguration('commandOn') != '' && strpos($camera->getConfiguration('commandOn'), '#') !== false) {
				if (!cmd::byId(str_replace('#', '', $camera->getConfiguration('commandOn')))) {
					$return[] = array('detail' => 'Camera ' . $camera->getHumanName(), 'help' => 'Commande On', 'who' => $camera->getConfiguration('commandOn'));
				}
			}
			if ($camera->getConfiguration('commandOff') != '' && strpos($camera->getConfiguration('commandOff'), '#') !== false) {
				if (!cmd::byId(str_replace('#', '', $camera->getConfiguration('commandOn')))) {
					$return[] = array('detail' => 'Camera ' . $camera->getHumanName(), 'help' => 'Commande Off', 'who' => $camera->getConfiguration('commandOff'));
				}
			}
		}
		return $return;
	}
	
	public static function discoverCam() {
		$return = array();
		$onvif = new Ponvif();
		$onvif->setDiscoveryTimeout(10);
		$result = $onvif->discover();
		if (count($result) > 0) {
			foreach ($result as $cam) {
				$return[] = array(
					'ip' => $cam['IPAddr'],
					'type' => $cam['Types'],
					'discover' => 'onvif',
					'exist' => false,
				);
			}
		}
		$cameras = self::byType('camera');
		foreach ($return as &$cam) {
			foreach ($cameras as $camera) {
				if ($cam['ip'] == $camera->getConfiguration('ip')) {
					$cam['exist'] = true;
					break;
				}
			}
		}
		return $return;
	}
	
	public static function addDiscoverCam($_config) {
		$eqLogic = new self();
		$eqLogic->setName($_config['ip']);
		$eqLogic->setConfiguration('username', $_config['username']);
		$eqLogic->setConfiguration('password', $_config['password']);
		$eqLogic->setConfiguration('ip', $_config['ip']);
		$eqLogic->setEqType_name('camera');
		$eqLogic->setIsVisible(1);
		$eqLogic->setIsEnable(1);
		$eqLogic->setConfiguration('device', 'onvif');
		$eqLogic->save();
	}
	
	/*     * *********************Methode d'instance************************* */
	
	public function configOnvif() {
		$onvif = new Ponvif();
		$onvif->setUsername($this->getConfiguration('username'));
		$onvif->setPassword($this->getConfiguration('password'));
		$onvif->setIPAddress($this->getConfiguration('ip') . ':' . $this->getConfiguration('onvif_port', 80));
		$onvif->initialize();
		$sources = $onvif->getSources();
		$mediaUri = preg_replace('/(([0-9]{1,3}\.){3}[0-9]{1,3})/m', '#username#:#password#@#ip#', $onvif->media_GetStreamUri($sources[0][0]['profiletoken']));
		$this->setConfiguration('cameraStreamAccessUrl', $mediaUri);
		$this->save();
	}
	
	public function preSave() {
		if ($this->getConfiguration('alertMessageCommand') != '') {
			$cmd = cmd::byId(str_replace('#', '', $this->getConfiguration('alertMessageCommand')));
			if (is_object($cmd) && $cmd->getEqType_name() == 'camera') {
				throw new Exception(__('La "Commande d\'alerte" ne peut être de type caméra', __FILE__));
			}
		}
		if ($this->getConfiguration('localApiKey') == '') {
			$this->setConfiguration('localApiKey', config::genKey());
		}
		if ($this->getConfiguration('normal::refresh') == '') {
			$this->setConfiguration('normal::refresh', 1);
		}
		if ($this->getConfiguration('thumbnail::refresh') == '') {
			$this->setConfiguration('thumbnail::refresh', 5);
		}
		if ($this->getConfiguration('normal::compress') == '') {
			$this->setConfiguration('normal::compress', 70);
		}
		if ($this->getConfiguration('thumbnail::compress') == '') {
			$this->setConfiguration('thumbnail::compress', 30);
		}
		if ($this->getConfiguration('maxReccordTime') == '') {
			$this->setConfiguration('maxReccordTime', 600);
		}
		if ($this->getConfiguration('moveThreshold') == '') {
			$this->setConfiguration('moveThreshold', 2);
		}
		if (file_exists(dirname(__FILE__) . '/../config/devices/' . $this->getConfiguration('device') . '.php')) {
			$this->setConfiguration('hasPullFunction', 1);
		} else {
			$this->setConfiguration('hasPullFunction', 0);
		}
		if($this->getIsEnable() == 0){
			try {
				if (is_object($this->getCmd(null, 'recordState')) && $this->getCmd(null, 'recordState')->execCmd() == 1) {
					$this->stopRecord();
				}
			} catch (Exception $e) {
				
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
			self::deamon_start();
		}
		$urlFlux = $this->getCmd(null, 'urlFlux');
		if (!is_object($urlFlux)) {
			$urlFlux = new cameraCmd();
		}
		$urlFlux->setName(__('Flux video', __FILE__));
		$urlFlux->setConfiguration('request', '-');
		$urlFlux->setType('info');
		$urlFlux->setLogicalId('urlFlux');
		$urlFlux->setIsVisible(0);
		$urlFlux->setEqLogic_id($this->getId());
		$urlFlux->setSubType('string');
		$urlFlux->setDisplay('generic_type', 'CAMERA_URL');
		$urlFlux->save();
		
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
		$recordState->setConfiguration('request', '-');
		$recordState->setType('info');
		$recordState->setLogicalId('recordState');
		$recordState->setIsVisible(0);
		$recordState->setEqLogic_id($this->getId());
		$recordState->setSubType('binary');
		$recordState->setDisplay('generic_type', 'CAMERA_RECORD_STATE');
		$recordState->save();
		
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
		$stopRecordCmd->setDisplay('generic_type', 'CAMERA_STOP');
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
		$takeSnapshot->setDisplay('icon', '<i class="far fa-image"></i>');
		$takeSnapshot->setDisplay('generic_type', 'CAMERA_TAKE');
		$takeSnapshot->save();
		
		$sendSnapshot = $this->getCmd(null, 'sendSnapshot');
		if (!is_object($sendSnapshot)) {
			$sendSnapshot = new cameraCmd();
		}
		$sendSnapshot->setName(__('Enregistrer', __FILE__));
		$sendSnapshot->setConfiguration('request', '-');
		$sendSnapshot->setType('action');
		$sendSnapshot->setLogicalId('sendSnapshot');
		$sendSnapshot->setEqLogic_id($this->getId());
		$sendSnapshot->setSubType('message');
		$sendSnapshot->setIsVisible(0);
		$sendSnapshot->setDisplay('title_placeholder', __('Nombre captures ou options', __FILE__));
		$sendSnapshot->setDisplay('message_placeholder', __('Commande message d\'envoi des captures', __FILE__));
		$sendSnapshot->setDisplay('message_cmd_type', 'action');
		$sendSnapshot->setDisplay('message_cmd_subtype', 'message');
		$takeSnapshot->setDisplay('generic_type', 'CAMERA_RECORD');
		$sendSnapshot->save();
		$urlFlux->event($this->getUrl($this->getConfiguration('urlStream'), true));
		
		if ($this->getConfiguration('commandOn') != '' && $this->getConfiguration('commandOff') != '') {
			$on = $this->getCmd(null, 'on');
			if (!is_object($on)) {
				$on = new cameraCmd();
			}
			$on->setName(__('On', __FILE__));
			$on->setOrder(-1);
			$on->setType('action');
			$on->setLogicalId('on');
			$on->setEqLogic_id($this->getId());
			$on->setDisplay('generic_type', 'CAMERA_PRESET');
			$on->setSubType('other');
			$on->setDisplay('icon', '<i class="fa fa-check"></i>');
			$on->setConfiguration('request', '-');
			$on->save();
			
			$off = $this->getCmd(null, 'off');
			if (!is_object($off)) {
				$off = new cameraCmd();
			}
			$off->setName(__('Off', __FILE__));
			$off->setOrder(-1);
			$off->setType('action');
			$off->setLogicalId('off');
			$off->setEqLogic_id($this->getId());
			$off->setSubType('other');
			$off->setDisplay('generic_type', 'CAMERA_PRESET');
			$off->setDisplay('icon', '<i class="fa fa-times"></i>');
			$off->setConfiguration('request', '-');
			$off->save();
		} else {
			$on = $this->getCmd(null, 'on');
			if (is_object($on)) {
				$on->remove();
			}
			$off = $this->getCmd(null, 'off');
			if (is_object($off)) {
				$off->remove();
			}
		}
	}
	
	public function toHtml($_version = 'dashboard', $_fluxOnly = false) {
		if ($_fluxOnly) {
			$replace = $this->preToHtml($_version, array(), true);
		} else {
			$replace = $this->preToHtml($_version);
		}
		if (!is_array($replace)) {
			return $replace;
		}
		$version = jeedom::versionAlias($_version);
		$version2 = jeedom::versionAlias($_version, false);
		$action = '';
		$info = '';
		if (!$_fluxOnly) {
			foreach ($this->getCmd() as $cmd) {
				if ($cmd->getIsVisible() == 1) {
					if ($cmd->getLogicalId() != 'urlFlux' && $cmd->getLogicalId() != 'stopRecordCmd' && $cmd->getLogicalId() != 'recordCmd' && $cmd->getLogicalId() != 'recordState' && $cmd->getConfiguration('stopCmd') != 1) {
						if ($cmd->getDisplay('hideOn' . $version2) == 1) {
							continue;
						}
						if ($cmd->getDisplay('forceReturnLineBefore', 0) == 1) {
							$action .= '<br/>';
						}
						if ($cmd->getType() == 'action' && $cmd->getSubType() == 'other') {
							$replaceCmd = array(
								'#id#' => $cmd->getId(),
								'#stopCmd#' => ($cmd->getConfiguration('stopCmdUrl') != '') ? 1 : 0,
								'#name#' => ($cmd->getDisplay('icon') != '') ? $cmd->getDisplay('icon') : $cmd->getName(),
							);
							$action .= template_replace($replaceCmd, getTemplate('core', jeedom::versionAlias($version), 'camera_action', 'camera')) . ' ';
						} else {
							if ($cmd->getType() == 'info') {
								$info .= $cmd->toHtml($_version, $replace['#cmd-background-color#']);
							} else {
								$action .= $cmd->toHtml($_version, $replace['#cmd-background-color#']);
							}
						}
						
						if ($cmd->getDisplay('forceReturnLineAfter', 0) == 1) {
							$action .= '<br/>';
						}
					}
				}
			}
			$stopRecord = $this->getCmd(null, 'stopRecordCmd');
			$sendSnapshot = $this->getCmd(null, 'sendSnapshot');
			$recordState = $this->getCmd(null, 'recordState');
			$replace_action = array(
				'#record_id#' => $sendSnapshot->getId(),
				'#stopRecord_id#' => $stopRecord->getId(),
				'#recordState#' => $recordState->execCmd(),
				'#recordState_id#' => $recordState->getId(),
			);
			$on = $this->getCmd(null, 'on');
			$off = $this->getCmd(null, 'off');
			if (is_object($on) && is_object($off)) {
				$replace['#cmd_on_id#'] = $on->getId();
				$replace['#cmd_off_id#'] = $off->getId();
			} else {
				$replace['#cmd_on_id#'] = '""';
				$replace['#cmd_off_id#'] = '""';
			}
			$action .= template_replace($replace_action, getTemplate('core', jeedom::versionAlias($_version), 'camera_record', 'camera'));
		}
		$replace['#action#'] = $action;
		$replace['#info#'] = $info;
		$replace['#url#'] = $this->getUrl($this->getConfiguration('urlStream'), true);
		$replace['#refreshDelaySlow#'] = $this->getConfiguration('thumbnail::refresh', 1) * 1000;
		$replace['#refreshDelayFast#'] = $this->getConfiguration('normal::refresh', 5) * 1000;
		if ($version == 'mobile') {
			$replace['#refreshDelaySlow#'] = $this->getConfiguration('thumbnail::mobilerefresh', 1) * 1000;
			$replace['#refreshDelayFast#'] = $this->getConfiguration('normal::mobilerefresh', 5) * 1000;
		}
		if (!$_fluxOnly) {
			return $this->postToHtml($_version, template_replace($replace, getTemplate('core', jeedom::versionAlias($version), 'camera', 'camera')));
		} else {
			return template_replace($replace, getTemplate('core', jeedom::versionAlias($version), 'camera_flux_only', 'camera'));
		}
	}
	
	public function getUrl($_complement = '', $_flux = false) {
		$replace = array(
			'#username#' => urlencode($this->getConfiguration('username')),
			'#password#' => urlencode($this->getConfiguration('password')),
		);
		if ($_flux) {
			return 'plugins/camera/core/php/snapshot.php?id=' . $this->getId() . '&apikey=' . $this->getConfiguration('localApiKey');
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
	
	public function applyModuleConfiguration() {
		$this->setConfiguration('applyDevice', $this->getConfiguration('device'));
		if ($this->getConfiguration('device') == '') {
			$this->save();
			return true;
		}
		if ($this->getConfiguration('device') == 'onvif') {
			$this->configOnvif();
			return;
		}
		$device = self::devicesParameters($this->getConfiguration('device'));
		if (!is_array($device) || !isset($device['commands'])) {
			return true;
		}
		$this->import($device);
	}
	
	public function recordCam($_args = 300, $_sendTo = null) {
		if (count(system::ps('core/php/record.php id=' . $this->getId())) > 0) {
			return true;
		}
		$cmd = 'php ' . dirname(__FILE__) . '/../../core/php/record.php id=' . $this->getId();
		if (!is_numeric($_args)) {
			$cmd .= ' ' . $_args;
		} else {
			$cmd .= ' nbSnap=' . escapeshellarg($_args);
		}
		if ($_sendTo != null) {
			$cmd .= ' sendTo=' . escapeshellarg($_sendTo);
		}
		$cmd .= ' >> ' . log::getPathToLog('camera_record') . ' 2>&1 &';
		shell_exec($cmd);
	}
	
	public function detectMove($_sendTo, $_forVideo = 0) {
		if (count(system::ps('core/php/detectChange.php id=' . $this->getId())) > 0) {
			return true;
		}
		$folder = calculPath(config::byKey('recordDir', 'camera'));
		$folder .= '/' . $this->getId();
		if ($_forVideo) {
			$folder .= '/movie_temp';
		}
		$cmd = 'php ' . dirname(__FILE__) . '/../../core/php/detectChange.php id=' . $this->getId();
		$cmd .= ' "folder=' . $folder . '"';
		$cmd .= ' sendTo=' . escapeshellarg($_sendTo);
		$cmd .= ' >> ' . log::getPathToLog('camera_detectChange') . ' 2>&1 &';
		shell_exec($cmd);
	}
	
	public function stopRecord() {
		if (count(system::ps('core/php/record.php id=' . $this->getId())) > 0) {
			system::kill('core/php/record.php id=' . $this->getId(), false);
			sleep(1);
			if (count(system::ps('core/php/record.php id=' . $this->getId())) > 0) {
				sleep(1);
				system::kill('core/php/record.php id=' . $this->getId(), true);
			}
		}
		if (count(system::ps('core/php/record.php id=' . $this->getId())) == 0 && $this->getCmd(null, 'recordState')->execCmd() == 1) {
			$this->getCmd(null, 'recordState')->event(0);
			$this->refreshWidget();
		}
		return true;
	}
	
	public function sendSnap($_files, $_background = false, $_part = '') {
		if (init('sendTo') == '' || count($_files) == 0) {
			return;
		}
		if ($_background) {
			$this->setCache('fileToSend', $_files);
			$cmd = 'php ' . dirname(__FILE__) . '/../../core/php/sendSnapshot.php id=' . $this->getId();
			$cmd .= ' sendTo="' . init('sendTo') . '"';
			$cmd .= ' title="' . init('title') . '"';
			$cmd .= ' message="' . init('message') . '"';
			$cmd .= ' part="' . $_part . '"';
			$cmd .= ' >> ' . log::getPathToLog('camera_record') . ' 2>&1 &';
			log::add('camera', 'debug', $cmd);
			shell_exec($cmd);
			return;
		}
		$options = array();
		$options['files'] = $_files;
		if (null !== init('title') && init('title') != '') {
			$options['title'] = init('title');
		} else {
			$options['title'] = __('Alerte sur la camera : ', __FILE__) . $this->getName() . __(' à ', __FILE__) . date('Y-m-d H:i:s') . $_part;
		}
		if (null !== init('message') && init('message') != '') {
			$options['message'] = init('message') . $_part;
		} else {
			$options['message'] = __('Alerte sur la camera : ', __FILE__) . $this->getName() . __(' à ', __FILE__) . date('Y-m-d H:i:s') . $_part;
		}
		$cmds = explode('&&', init('sendTo'));
		foreach ($cmds as $id) {
			$cmd = cmd::byId(str_replace('#', '', $id));
			if (!is_object($cmd)) {
				continue;
			}
			try {
				$cmd->execCmd($options);
			} catch (Exception $e) {
				log::add('camera', 'error', __('[camera/sendSnap] Erreur lors de l\'envoi des images : ', __FILE__) . $cmd->getHumanName() . ' => ' . log::exception($e));
			}
		}
	}
	
	public function convertMovie() {
		$output_dir = calculPath(config::byKey('recordDir', 'camera'));
		$output_dir .= '/' . $this->getId();
		$output_file = '';
		$start = '';
		if (file_exists($output_dir . '/movie_temp')) {
			$output_file = $output_dir . '/' . str_replace(' ', '-', $this->getName()) . '_' . date('Y-m-d_H-i-s') . '.mp4';
			$files = scandir($output_dir . '/movie_temp');
			if (count($files) > 1) {
				$first_number = substr($files[2], 0, 6);
				$start = '-start_number ' . $first_number . ' ';
			}
			$framerate = $this->getConfiguration('videoFramerate', 1);
			shell_exec('avconv -framerate ' . $framerate . ' ' . $start . ' -f image2 -i ' . $output_dir . '/movie_temp/%06d.' . str_replace(' ', '-', $this->getName()) . '.jpg -pix_fmt yuv420p ' . $output_file);
			shell_exec(system::getCmdSudo() . 'rm -rf ' . $output_dir . '/movie_temp');
			return $output_file;
		}
		return $output_file;
	}
	
	public function stopCam() {
		if (count(system::ps('core/php/stopCam.php id=' . $this->getId())) > 0) {
			return true;
		}
		$this->stopRecord();
		$cmd = ' php ' . dirname(__FILE__) . '/../../core/php/stopCam.php id=' . $this->getId();
		$cmd .= ' >> ' . log::getPathToLog('camera_record') . ' 2>&1 &';
		shell_exec($cmd);
	}
	
	public function getSnapshot($_takesnapshot = false) {
		$inprogress = cache::bykey('camera' . $this->getId() . 'inprogress');
		$info = $inprogress->getValue(array('state' => 0, 'datetime' => strtotime('now')));
		if ($info['state'] == 1 && (strtotime('now') - 2) <= $info['datetime']) {
			$cahe = cache::bykey('camera' . $this->getId() . 'cache');
			if ($cahe->getValue() != '') {
				return $cahe->getValue();
			}
		}
		cache::set('camera' . $this->getId() . 'inprogress', array('state' => 1, 'datetime' => strtotime('now')));
		if ($this->getConfiguration('urlStream') == '' && $this->getConfiguration('cameraStreamAccessUrl') != '') {
			$replace = array(
				'#username#' => urlencode($this->getConfiguration('username')),
				'#password#' => urlencode($this->getConfiguration('password')),
				'#ip#' => urlencode($this->getConfiguration('ip')),
				'#port#' => urlencode($this->getConfiguration('port')),
			);
			//	$datetime = 'drawtext=fontfile=/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf: text="' . date('Y-m-d H:i:s') . '": fontcolor=black@0.8: x=50: y=60';
			shell_exec('avconv -i "' . str_replace(array_keys($replace), $replace, $this->getConfiguration('cameraStreamAccessUrl')) . '" -frames:v 1 -y -r 1 -vsync 1 -qscale 1 -f image2 ' . jeedom::getTmpFolder('camera') . '/' . $this->getId() . '.jpeg 2>&1 >> /dev/null');
			$data = file_get_contents(jeedom::getTmpFolder('camera') . '/' . $this->getId() . '.jpeg');
			unlink(jeedom::getTmpFolder('camera') . '/' . $this->getId() . '.jpeg');
		} else {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->getUrl($this->getConfiguration('urlStream')));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
			if (curl_error($ch)) {
				log::add('camera','debug',__('Erreur sur ',__FILE__).$this->getHumanName().' : '.curl_error($ch));
			}
			curl_close($ch);
		}
		cache::set('camera' . $this->getId() . 'cache', $data);
		cache::set('camera' . $this->getId() . 'inprogress', array('state' => 0, 'datetime' => ''));
		return $data;
	}
	
	public function takeSnapshot($_forVideo = 0, $_number = 0) {
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
		$snapshot = $this->getSnapshot(true);
		if (empty($snapshot)) {
			throw new Exception(__('Le fichier est vide : ', __FILE__) . $output_dir);
		}
		if ($_forVideo == 1) {
			$output_dir .= '/movie_temp';
			if (!file_exists($output_dir)) {
				if (!mkdir($output_dir, 0777, true)) {
					throw new Exception(__('Impossible de creer le dossier : ', __FILE__) . $output_dir);
				}
			}
			if ($_number == 2) {
				shell_exec(system::getCmdSudo() . 'rm ' . $output_dir . '/* > /dev/null 2>&1');
			}
			$number = str_pad($_number, 6, '0', STR_PAD_LEFT);
			$output_file = $output_dir . '/' . $number . '.' . str_replace(' ', '-', $this->getName()) . '.jpg';
		} else {
			$output_file = $output_dir . '/' . str_replace(' ', '-', $this->getName()) . '_' . date('Y-m-d_H-i-s') . '.jpg';
		}
		file_put_contents($output_file, $snapshot);
		return $output_file;
	}
	
	public function removeAllSnapshot() {
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
		shell_exec(system::getCmdSudo() . 'rm -rf ' . $output_dir);
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
	
	public function getImage() {
		return 'plugins/camera/core/config/devices/' . $this->getConfiguration('device') . '.jpg';
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
		$info_device['params'][2]['value'] = network::getNetworkAccess('internal') . '/' . $eqLogic->getUrl($eqLogic->getConfiguration('urlStream'), true);
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
		if ($this->getLogicalId() == 'on') {
			return true;
		}
		if ($this->getLogicalId() == 'off') {
			return true;
		}
		return false;
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
				if (!isset($_options['slider'])) {
					$_options['slider'] = 0;
				}
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
		if ($this->getLogicalId() == 'on') {
			$cmd = cmd::byId(str_replace('#', '', $eqLogic->getConfiguration('commandOn')));
			if (is_object(!$cmd)) {
				throw new Exception(__('Impossible de trouver la commande ON', __FILE__));
			}
			$cmd->execCmd();
			return true;
		}
		if ($this->getLogicalId() == 'off') {
			if ($eqLogic->getCmd(null, 'recordState')->execCmd() == 1) {
				$eqLogic->stopCam();
				return true;
			}
			$eqLogic->stopRecord();
			$cmd = cmd::byId(str_replace('#', '', $eqLogic->getConfiguration('commandOff')));
			if (is_object(!$cmd)) {
				throw new Exception(__('Impossible de trouver la commande OFF', __FILE__));
			}
			$cmd->execCmd();
			return true;
		}
		if ($this->getLogicalId() == 'sendSnapshot') {
			if (!isset($_options['title'])) {
				$_options['title'] = '';
			}
			if (!isset($_options['message'])) {
				$_options['message'] = '';
			}
			$eqLogic->recordCam($_options['title'], $_options['message']);
			return true;
		}
		$url = $eqLogic->getUrl($request);
		if (strpos($request, 'curl ') !== false) {
			log::add('camera', 'debug', 'Executing ' . $url);
			shell_exec($request);
		} else {
			$http = new com_http($url, $eqLogic->getConfiguration('username'), $eqLogic->getConfiguration('password'));
			$http->setNoReportError(true);
			$http->setCURLOPT_HTTPAUTH(CURLAUTH_ANY);
			$headers = array(
				'User-Agent: Jeedom',
			);
			$http->setHeader($headers);
			$http->exec(2);
		}
		return true;
		
	}
	
	/*     * **********************Getteur Setteur*************************** */
}

?>
