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

class mobile extends eqLogic {
	/*     * *************************Attributs****************************** */

	public static $_pluginSuported = array('mobile', 'openzwave', 'rfxcom', 'edisio', 'mpower', 'mySensors', 'Zibasedom', 'virtual', 'camera', 'weather', 'philipsHue', 'enocean', 'wifipower', 'alarm', 'mode', 'apcupsd', 'btsniffer', 'dsc', 'rflink', 'mysensors', 'relaynet', 'remora', 'unipi', 'eibd', 'thermostat', 'netatmoThermostat', 'espeasy', 'jeelink', 'teleinfo', 'tahoma', 'protexiom','boilerThermostat', 'lifx', 'wattlet', 'rfplayer', 'openenocean', 'netatmoWeather', 'Volets', 'vmczehnder');

	public static $_pluginWidget = array('alarm', 'camera', 'thermostat', 'netatmoThermostat', 'weather', 'mode', 'mobile');

	public static $_pluginMulti = array('LIGHT_STATE', 'ENERGY_STATE', 'FLAP_STATE', 'HEATING_STATE', 'SIREN_STATE', 'LOCK_STATE');

	//public static $_urlAws = 'https://api-notif.jeedom.com/notif/';
	
	public static $_listenEvents = array('cmd::update', 'scenario::update','jeeObject::summary::update');

	/*     * ***********************Methode static*************************** */

	public static function whoIsIq($iq){
		$search = eqLogic::byLogicalId($iq, 'mobile');
		if(is_object($search)){
			return $search->getName();
		}else{
			return 'mobile non detecte';
		}
	}

	public static function pluginToSend() {
		$return = [];
		$plugins = plugin::listPlugin(true);
		foreach ($plugins as $plugin) {
			$plugId = $plugin->getId();
			//if ($plugId == 'mobile') {
			//	continue;
			//} else if (in_array($plugId, self::$_pluginWidget)) {
			if (in_array($plugId, self::$_pluginWidget)) {
				$return[] = $plugId;
			} else if (in_array($plugId, self::$_pluginSuported) && !in_array($plugId, self::$_pluginWidget) && config::byKey('sendToApp', $plugId, 1) == 1) {
				$return[] = $plugId;
			} else if (!in_array($plugId, self::$_pluginSuported) && config::byKey('sendToApp', $plugId, 0) == 1) {
				$subClasses = config::byKey('subClass', $plugId, '');
				if ($subClasses != '') {
					$subClassesList = explode(';', $subClasses);
					foreach ($subClassesList as $subClass) {
						$return[] = $subClass;
					}
				}
				$return[] = $plugId;
			}
		}
		return $return;
	}

	public static function makeTemplateJson() {
		$pluginToSend = mobile::pluginToSend();
		$discover_eqLogic = mobile::discovery_eqLogic($pluginToSend);
		$sync_new = mobile::change_cmdAndeqLogic(mobile::discovery_cmd($pluginToSend, $discover_eqLogic), $discover_eqLogic);
		$config = array(
			'url_internal' => network::getNetworkAccess('internal'),
			'url_external' => network::getNetworkAccess('external'),
		);
      	$objectReturn = mobile::delete_object_eqlogic_null(mobile::discovery_object(), $sync_new['eqLogics']);
		$data = array(
			'eqLogics' => $sync_new['eqLogics'],
			'cmds' => $sync_new['cmds'],
			'objects' => mobile::delete_object_eqlogic_null(mobile::discovery_object(), $sync_new['eqLogics']),
			'scenarios' => mobile::discovery_scenario(),
			'plans' => mobile::discovery_plan(),
			'summary' => mobile::discovery_summary(),
			'config' => $config,
		);
		$path = dirname(__FILE__) . '/../../data/mobile.json';
		if (!file_exists(dirname(__FILE__) . '/../../data')) {
			mkdir(dirname(__FILE__) . '/../../data');
		}
		if (file_exists(dirname(__FILE__) . '/../../data/mobile.json')) {
			unlink(dirname(__FILE__) . '/../../data/mobile.json');
		}
		file_put_contents(dirname(__FILE__) . '/../../data/mobile.json', json_encode($data));
		$event_cmd = array();
		foreach ($data['cmds'] as $cmd) {
			$event_cmd[] = $cmd['id'];
		}
		cache::set('mobile::event', $event_cmd);
	}

	public static function getTemplateJson() {
		if (!file_exists(dirname(__FILE__) . '/../../data/mobile.json')) {
			self::makeTemplateJson();
		}
		return json_decode(cmd::cmdToValue(file_get_contents(dirname(__FILE__) . '/../../data/mobile.json')), true);
	}

	public static function makeSaveJson($data = array(), $mobileID, $type = 'dashboard') {
		$path = dirname(__FILE__) . '/../../data/' . $mobileID . '/' . $type . '.json';
		if (!file_exists(dirname(__FILE__) . '/../../data')) {
			mkdir(dirname(__FILE__) . '/../../data');
		}
		if (!file_exists(dirname(__FILE__) . '/../../data/' . $mobileID)) {
			mkdir(dirname(__FILE__) . '/../../data/' . $mobileID);
		}
		if (file_exists(dirname(__FILE__) . '/../../data/' . $mobileID . '/' . $type . '.json')) {
			unlink(dirname(__FILE__) . '/../../data/' . $mobileID . '/' . $type . '.json');
		}
		file_put_contents(dirname(__FILE__) . '/../../data/' . $mobileID . '/' . $type . '.json', json_encode($data));
	}

	public static function getSaveJson($mobileID, $type = 'dashboard') {
		if (!file_exists(dirname(__FILE__) . '/../../data/' . $mobileID . '/' . $type . '.json')) {
			self::makeSaveJson(array(), $mobileID, $type);
		}
		return json_decode(file_get_contents(dirname(__FILE__) . '/../../data/' . $mobileID . '/' . $type . '.json'), true);
	}

	public static function discovery_eqLogic($plugin = array(), $hash = null) {
		$return = array();
		foreach ($plugin as $plugin_type) {
			$eqLogics = eqLogic::byType($plugin_type, true);
			if (!is_array($eqLogics)) {
				continue;
			}
			foreach ($eqLogics as $eqLogic) {
				if ($eqLogic->getEqType_name() != 'mobile') {
					if ($eqLogic->getIsEnable() != 1) {
						continue;
					}
					if ($eqLogic->getObject_id() == null) {
						continue;
					}
                  	$objectNow = $eqLogic->getObject();
                  	if(!is_object($objectNow)){
                      continue;
                    }
					if (($eqLogic->getIsVisible() != 1 && (!in_array($eqLogic->getEqType_name(), self::$_pluginWidget)) || $objectNow->getDisplay('sendToApp', 1) != 1)) {
						continue;
					}
				}
				$eqLogic_array = utils::o2a($eqLogic);
				if ($eqLogic->getEqType_name() == 'mobile') {
					if (isset($eqLogic_array["logicalId"])) {
						$eqLogic_array["localApiKey"] = $eqLogic_array["logicalId"];
					}
				}
				if (isset($eqLogic_array["configuration"]["localApiKey"])) {
					$eqLogic_array["localApiKey"] = $eqLogic_array["configuration"]["localApiKey"];
				}
				if ($eqLogic_array['eqType_name'] == 'jeelink'){
					if(isset($eqLogic_array['configuration']['real_eqType'])){
						$eqLogic_array['eqType_name'] = $eqLogic_array['configuration']['real_eqType'];
					}
				}
				unset($eqLogic_array['eqReal_id'], $eqLogic_array['comment'], $eqLogic_array['configuration'], $eqLogic_array['specificCapatibilities'], $eqLogic_array['timeout'], $eqLogic_array['category'], $eqLogic_array['display']);
				unset($eqLogic_array['status']);
				unset($eqLogic_array['generic_type']);
				unset($eqLogic_array['logicalId']);
				unset($eqLogic_array['isVisible']);
				unset($eqLogic_array['isEnable']);
				if(!in_array($eqLogic_array['eqType_name'], self::$_pluginWidget)){
					unset($eqLogic_array['eqType_name']);
				}
				$return[] = $eqLogic_array;
			}
		}
		return $return;
	}

	public static function discovery_cmd($plugin = array(), $eqLogics = null, $_withValue = false) {
		$return = array();
		$genericisvisible = array();
		foreach (jeedom::getConfiguration('cmd::generic_type') as $key => $info) {
			if ($info['family'] !== 'Generic') {
				$genericisvisible[] = $key;
			}
		}
		if ($eqLogics == null) {
			$eqLogics = self::discovery_eqLogic($plugin);
		}
		$eqLogics_id = array();
		foreach ($eqLogics as $eqLogic) {
			$eqLogics_id[] = $eqLogic['id'];
		}
		if (count($eqLogics_id) > 0) {
			foreach (cmd::byEqLogicId($eqLogics_id, null, null, null, true) as $cmd) {
				if (in_array($cmd->getGeneric_type(), ['GENERIC_ERROR', 'DONT'])) {
					continue;
				}
				if (!isset($eqLogic['eqType_name'])) {
					$eqLogic['eqType_name'] = '';
				}
				if ($cmd->getIsVisible() != 1 && !in_array($cmd->getGeneric_type(), $genericisvisible) && !in_array($eqLogic['eqType_name'], self::$_pluginWidget)) {
					continue;
				}
				$info = $cmd->exportApi();
				unset($info['isHistorized']);
				unset($info['template']);
				unset($info['html']);
				unset($info['alert']);
				unset($info['isVisible']);
				unset($info['logicalId']);
				unset($info['eqType']);
				unset($info['order']);
				$info['configuration'] = array();
				$info['configuration']['actionCodeAccess'] = $cmd->getConfiguration('actionCodeAccess');
				$info['configuration']['actionConfirm'] = $cmd->getConfiguration('actionConfirm');
				$info['configuration']['maxValue'] = $cmd->getConfiguration('maxValue');
				$info['configuration']['minValue'] = $cmd->getConfiguration('minValue');
				$info['display'] = array();
				$info['display']['invertBinary'] = $cmd->getDisplay('invertBinary');
				$info['display']['icon'] = $cmd->getDisplay('icon');
				$info['display']['title_disable'] = $cmd->getDisplay('title_disable');
				$info['display']['title_placeholder'] = $cmd->getDisplay('title_placeholder');
				$info['display']['message_placeholder'] = $cmd->getDisplay('message_placeholder');
				if (!in_array($cmd->getGeneric_type(), ['GENERIC_INFO', 'GENERIC_ACTION', 'HEATING_ON', 'HEATING_OTHER', 'MODE_SET_STATE']) && isset($info['display']['icon'])) {
					unset($info['display']['icon']);
				}
				if (isset($info['display']['icon'])) {
					$info['display']['icon'] = str_replace(array('<i class="', '"></i>'), '', $info['display']['icon']);
					if (strstr($info['display']['icon'], ' icon_')){
						$info['display']['icon'] = strstr($info['display']['icon'], ' icon_', true);
                    			}
				}
				foreach ($info['display'] as $key => $value) {
					if (trim($value) == '') {
						unset($info['display'][$key]);
					}
				}
				if (count($info['display']) == 0) {
					unset($info['display']);
				}
				foreach ($info['configuration'] as $key => $value) {
					if (trim($value) == '') {
						unset($info['configuration'][$key]);
					}
				}
				if (count($info['configuration']) == 0) {
					unset($info['configuration']);
				}
				if ($info['type'] == 'action') {
					unset($info['currentValue']);
				} else if (!$_withValue) {
					$info['currentValue'] = '#' . $info['id'] . '#';
				}
				if ($info['value'] == null) {
					unset($info['value']);
				}
				if ($info['unite'] == '') {
					unset($info['unite']);
				}
				$return[] = $info;
			}
		}
		return $return;
	}

	public static function discovery_multi($cmds) {
		$array_final = array();
		$tableData = self::$_pluginMulti;
		foreach ($cmds as $cmd) {
			if (in_array($cmd['generic_type'], $tableData)) {
				$result = array_intersect(
					array_keys(array_column($cmds, 'eqLogic_id'), $cmd['eqLogic_id']),
					array_keys(array_column($cmds, 'generic_type'), $cmd['generic_type'])
				);
				if (count($result) > 1) {
					$array_final = array_merge_recursive($array_final, $result);
				}
			}
		}
		$dif = array();
		$array_cmd_multi = array();
		foreach ($array_final as $array_fi) {
			if (!in_array($array_fi, $dif)) {
				array_push($dif, $array_fi);
				array_push($array_cmd_multi, $array_fi);
			}
		}
		return $array_cmd_multi;
	}

	public static function change_cmdAndeqLogic($_cmds, $_eqLogics) {
		$findEqLogic = array();
		foreach ($_cmds as $cmd) {
			$findEqLogic[$cmd['eqLogic_id']] = $cmd['eqLogic_id'];
		}
		$eqLogics = array();
		foreach ($_eqLogics as $eqLogic) {
			if (!isset($findEqLogic[$eqLogic['id']])) {
				continue;
			}
			$eqLogics[] = $eqLogic;
		}
		$plage_cmds = mobile::discovery_multi($_cmds);
		if (count($plage_cmds) == 0) {
			return array('cmds' => $_cmds, 'eqLogics' => $eqLogics);
		}
		$eqLogic_array = array();
		foreach ($plage_cmds as $plage_cmd) {
			$eqLogic_id = $_cmds[$plage_cmd]['eqLogic_id'];
			$name_cmd = $_cmds[$plage_cmd]['name'];
			foreach ($eqLogics as $eqLogic) {
				if ($eqLogic['id'] == $eqLogic_id) {
					$eqLogic_name = $eqLogic['name'] . ' / ' . $name_cmd;
				}
			}
			$id = $_cmds[$plage_cmd]['id'];
			$new_eqLogic_id = '999' . $eqLogic_id . '' . $id;
			$_cmds[$plage_cmd]['eqLogic_id'] = $new_eqLogic_id;
			$keys = array_keys(array_column($_cmds, 'eqLogic_id'), $eqLogic_id);
			foreach ($keys as $key) {
				if ($_cmds[$key]['value'] == $_cmds[$plage_cmd]['id'] && $_cmds[$key]['type'] == 'action') {
					$_cmds[$key]['eqLogic_id'] = $new_eqLogic_id;
				}
			}
			$eqLogic_array[] = array($eqLogic_id, $new_eqLogic_id, $eqLogic_name);
			$i++;
		}
		$column_eqlogic = array_column($eqLogics, 'id');
		foreach ($eqLogic_array as $eqlogic_array_one) {
			$keys = array_keys($column_eqlogic, $eqlogic_array_one[0]);
			$new_eqLogic = $eqLogics[$keys[0]];
			$new_eqLogic['id'] = $eqlogic_array_one[1];
			$new_eqLogic['name'] = $eqlogic_array_one[2];
			$eqLogics[] = $new_eqLogic;
		}
		return array('cmds' => $_cmds, 'eqLogics' => $eqLogics);
	}

	public static function discovery_object() {
		$all = utils::o2a(jeeObject::all());
		$return = array();
		foreach ($all as $object) {
			if (isset($object['display']['sendToApp']) && $object['display']['sendToApp'] == "0") {
				continue;
			}
			unset($object['configuration']);
			unset($object['display']['tagColor']);
			unset($object['display']['tagTextColor']);
			unset($object['display']['desktop::summaryTextColor']);
			unset($object['display']['dashboard::size']);
			unset($object['display']['summaryTextColor']);
          	unset($object['image']);
          	unset($object['img']);
			unset($object['father_id']);
			if (isset($object['display']['icon'])) {
				if ($object['display']['icon'] == '') {
					unset($object['display']['icon']);
				} else {
					$object['display']['icon'] = str_replace(array('<i class="', '"></i>'), '', $object['display']['icon']);
                  	$tableEx = array();
                  	$explodes = explode(' ', $object['display']['icon']);
                  	foreach ($explodes as $explode){
                    	if(substr($explode, 0, 5) != 'icon_'){
                          $tableEx[] = $explode;
                        }
                    }
                  	$object['display']['icon'] = implode(' ', $tableEx);
				}
			}
			$return[] = $object;
		}
		return $return;
	}

	public static function discovery_scenario() {
		$all = utils::o2a(scenario::all());
		$return = array();
		foreach ($all as &$scenario) {
			if (isset($scenario['display']['sendToApp']) && $scenario['display']['sendToApp'] == "0") {
				continue;
			}
			if (!isset($scenario['display']['sendToApp'])) {
				continue;
			}
			if ($scenario['display']['name'] != '') {
				$scenario['name'] = $scenario['display']['name'];
			}
			if (isset($scenario['display'])) {
				unset($scenario['display']);
			}
			unset($scenario['mode'], $scenario['schedule'], $scenario['scenarioElement'], $scenario['trigger'], $scenario['timeout'], $scenario['description'], $scenario['configuration'], $scenario['type'], $scenario['display']['name']);
			$return[] = $scenario;
		}
		return $return;
	}

	public static function discovery_message() {
		//return utils::o2a(message::all());
		 return array();
	}

	public static function discovery_plan() {
		$plans = utils::o2a(planHeader::all());
		foreach ($plans as &$plan) {
			if (isset($plan['image'])) {
				unset($plan['image']);
			}
			if (isset($plan['configuration'])) {
				if (!isset($plan['configuration']['simee'])) {
					unset($plan['configuration']);
				}
			}
		}
		return $plans;
	}
	
	public static function discovery_summary() {
		$return = array();
		$def = config::byKey('object:summary');
		foreach ($def as $key => &$value) {
			//$value['value'] = jeeObject::getGlobalSummary($key);
			if (isset($value['icon'])) {
				if ($value['icon'] == '') {
					unset($value['icon']);
				} else {
					$value['icon'] = str_replace(array('<i class="', '"></i>'), '', $value['icon']);
                  			$tableEx = array();
                  			$explodes = explode(' ', $value['icon']);
                  			foreach ($explodes as $explode){
                    				if(substr($explode, 0, 5) != 'icon_'){
                          				$tableEx[] = $explode;
                        			}
                    			}
                  			$value['icon'] = implode(' ', $tableEx);
				}
			}
		}
		return $def;
	}
	
	public static function discovery_summaryValue($jeeObjectEnvoi){
      	$def = config::byKey('object:summary');
      	$tableKey = array();
		foreach ($def as $key => $value) {
          $tableKey[] = $key;
        }
		$table = array();
		foreach ($jeeObjectEnvoi as $jeeobject){
			$object = jeeObject::byId($jeeobject['id']);
			if (is_object($object)) {
              	foreach ($tableKey as $key){
                  if($object->getSummary($key) != null){
                    $tableObject = array();
                    $tableObject['object_id'] = $object->getId();
                    $tableObject['key'] = $key;
                    $tableObject['value'] = $object->getSummary($key);
                    $table[] = $tableObject;
                  }
                }
			}
		}
      	foreach ($tableKey as $key){
          	if(jeeObject::getGlobalSummary($key) != null){
              $tableObject = array();
              $tableObject['object_id'] = 'global';
              $tableObject['key'] = $key;
              $tableObject['value'] = jeeObject::getGlobalSummary($key);
              $table[] = $tableObject;
            }
        }
      	
		return $table;
	}

	public static function delete_object_eqlogic_null($objects, $eqLogics) {
		$return = array();
		$object_id = array();
		foreach ($eqLogics as $eqLogic) {
			$object_id[$eqLogic['object_id']] = $eqLogic['object_id'];
		}
		foreach ($objects as $object) {
			if (!isset($object_id[$object['id']])) {
				continue;
			}
			$return[] = $object;
		}
		return $return;
	}

	public function getQrCode() {
		$interne = network::getNetworkAccess('internal');
		$externe = network::getNetworkAccess('external');
		if ($interne == null || $interne == 'http://:80' || $interne == 'https://:80') {
			return 'internalError';
		}
		if ($externe == null || $externe == 'http://:80' || $externe == 'https://:80') {
			return 'externalError';
		}
		if ($this->getConfiguration('affect_user') == '') {
			return 'UserError';
		}
		$key = $this->getLogicalId();
		$request_qrcode = array(
			'eqLogic_id' => $this->getId(),
			'url_internal' => $interne,
			'url_external' => $externe,
			'Iq' => $key,
		);
		if ($this->getConfiguration('affect_user') != '') {
			$username = user::byId($this->getConfiguration('affect_user'));
			if (is_object($username)) {
				$request_qrcode['username'] = $username->getLogin();
				$request_qrcode['apikey'] = $username->getHash();
			}
		}
		$retour = 'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=' . json_encode($request_qrcode);
		return $retour;
	}

	public static function jsonPublish($os, $titre, $message, $badge = 'null', $type, $idNotif, $answer, $timeout) {
		$dateNotif = date("Y-m-d H:i:s");
		$badge = 0;
		$message = preg_replace("# {2,}#", " ", preg_replace("#(\r\n|\n\r|\n|\r)#", "\\\\\\n", $message));
		if ($timeout != 'nok') {
			$timeout = date('Y-m-d H:i:s', strtotime("$dateNotif + $timeout SECONDS"));
		}
		$addAsk = '';
		if ($type == 'ask_Text') {
			$addAsk = '\"category\":\"TEXT_CATEGORY\",\"answer\":\"' . $answer . '\",\"timeout\":\"' . $timeout . '\",';
		}
		if ($os == 'ios') {
			if ($badge == 'null') {
				$publish = '{"default": "test", "APNS": "{\"aps\":{\"content-available\":\"1\",' . $addAsk . '\"alert\": {\"title\":\"' . $titre . '\",\"body\":\"' . $message . '\"},\"badge\":\"0\",\"sound\":\"silence.caf\",\"date\":\"' . $dateNotif . '\",\"idNotif\":\"' . $idNotif . '\"}}"}';
			} else {
				$publish = '{"default": "test", "APNS": "{\"aps\":{\"content-available\":\"1\",' . $addAsk . '\"alert\": {\"title\":\"' . $titre . '\",\"body\":\"' . $message . '\"},\"badge\":\"'. $badge .'\",\"sound\":\"silence.caf\",\"date\":\"' . $dateNotif . '\",\"idNotif\":\"' . $idNotif . '\"}}"}';
			}
		} else if ($os == 'android') {
			$publish = '{"default": "Erreur de texte de notification", "GCM": "{ \"notification\":{\"notificationId\":\"' . rand(3, 5) . '\",\"title\":\"' . $titre . '\",\"text\":\"' . $message . '\",' . $addAsk . '\"vibrate\":\"true\",\"largeIcon\":\"/appicon.png\",\"smallIcon\":\"notificon\",\"lights\":\"true\",\"sound\":\"default\",\"idNotif\":\"' . $idNotif . '\",\"date\":\"' . $dateNotif . '\"}, \"data\": {\"notificationId\":\"' . rand(3, 5) . '\",\"title\":\"' . $titre . '\",\"text\":\"' . $message . '\",' . $addAsk . '\"vibrate\":\"true\",\"largeIcon\":\"/appicon.png\",\"smallIcon\":\"notificon\",\"lights\":\"true\",\"sound\":\"default\",\"idNotif\":\"' . $idNotif . '\",\"date\":\"' . $dateNotif . '\"}}"}';
		} else if ($os == 'microsoft') {

		}
		return $publish;
	}

	public static function notification($arn, $os, $titre, $message, $badge = 'null', $type, $idNotif, $answer, $timeout) {
		log::add('mobile', 'debug', 'notification en cours !');
		$publish = ($badge == 'null') ? mobile::jsonPublish($os, $titre, $message, $badge, $type, $idNotif, $answer, $timeout) : mobile::jsonPublish($os, $titre, $message, $badge, $type, $idNotif, $answer, $timeout);
		log::add('mobile', 'debug', 'JSON envoyé : ' . $publish);
		$post = [
			'arn' => $arn,
			'text' => $publish,
		];
		
		$url = config::byKey('service::cloud::url','core','https://cloud.jeedom.com').'/service/notif';
		$request_http = new com_http($url);
		$request_http->setHeader(array(
		      'Content-Type: application/json',
		      'Autorization: '.sha512(strtolower(config::byKey('market::username')).':'.config::byKey('market::password'))
		));
		$request_http->setPost(json_encode($post));
		$result = json_decode($request_http->exec(10,1),true);
		if(!isset($result['state']) || $result['state'] != 'ok'){
			throw new Exception(__('Echec de l\'envoi de la notification :', __FILE__) . json_encode($result));	
		}
	}

	public function SaveGeoloc($geoloc) {
		log::add('mobile', 'debug', '|-----------------------------------');
		log::add('mobile', 'debug', '|--debut de la fonction SaveGeoLoc--');
		log::add('mobile', 'debug', '|-----------------------------------');
		log::add('mobile', 'debug', '|');
		$eqLogicMobile = eqLogic::byLogicalId($geoloc['Iq'], 'mobile');
		log::add('mobile', 'debug', '| Iq = ' . $geoloc['Iq']);
		if (is_object($eqLogicMobile)) {
			log::add('mobile', 'debug', '| Mobile bien trouvé dans cette Jeedom');
			log::add('mobile', 'debug', '| Objet > ' . $eqLogicMobile->getId());
			$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($eqLogicMobile->getId(), 'geoId_' . $geoloc['id']);
			if (!is_object($cmdgeoloc)) {
				$cmdgeoloc = new mobileCmd();
				$cmdgeoloc->setLogicalId('geoId_' . $geoloc['id']);
				$cmdgeoloc->setEqLogic_id($eqLogicMobile->getId());
				$cmdgeoloc->setType('info');
				$cmdgeoloc->setSubType('binary');
				$cmdgeoloc->setIsVisible(1);
			}
			$cmdgeoloc->setName(__($geoloc['id'].'-'.$geoloc['name'], __FILE__));
			$cmdgeoloc->setConfiguration('latitude', $geoloc['latitude']);
			$cmdgeoloc->setConfiguration('longitude', $geoloc['longitude']);
			$cmdgeoloc->setConfiguration('subtitle', $geoloc['subtitle']);
			$cmdgeoloc->setConfiguration('radius', $geoloc['radius']);
			$cmdgeoloc->save();
		}
	}

	public function delGeoloc($geoloc) {
      	log::add('mobile', 'debug', 'Geoloc lancement DEL du mobile > '.$geoloc['Iq'].' pour '.$geoloc['id']);
		$eqLogicMobile = eqLogic::byLogicalId($geoloc['Iq'], 'mobile');
		$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($eqLogicMobile->getId(), 'geoId_' . $geoloc['id']);
		if(isset($cmdgeoloc)) {
			$cmdgeoloc->remove();
		}
	}

	public function EventGeoloc($geoloc) {
		log::add('mobile', 'debug', 'Geoloc Event du mobile > '.$geoloc['Iq'].' pour '.$geoloc['id']);
		$eqLogicMobile = eqLogic::byLogicalId($geoloc['Iq'], 'mobile');
		$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($eqLogicMobile->getId(), 'geoId_' . $geoloc['id']);
		if (isset($cmdgeoloc)) {
          	log::add('mobile', 'debug', 'commande trouvé');
			if($geoloc['value'] !== $cmdgeoloc->execCmd()){
              	log::add('mobile', 'debug', 'Valeur non pareil.');
				$cmdgeoloc->event($geoloc['value']);
			}else{
            	log::add('mobile', 'debug', 'Valeur pareil. >'.$geoloc['value'].' / '.$cmdgeoloc->execCmd());
            }
		}
	}

	public function postInsert() {
		$key = config::genKey(32);
		$this->setLogicalId($key);
		$this->save();
	}

	public function postSave() {
		$cmdNotif = $this->getCmd(null, 'notif');
		if (!is_object($cmdNotif)) {
			$cmdNotif = new mobileCmd();
		}
		$cmdNotif->setLogicalId('notif');
		$cmdNotif->setName(__('Notification', __FILE__));
		$cmdNotif->setOrder(0);
		$cmdNotif->setEqLogic_id($this->getId());
		$cmdNotif->setDisplay('generic_type', 'GENERIC_ACTION');
		$cmdNotif->setType('action');
		$cmdNotif->setSubType('message');
		$cmdNotif->setIsVisible(1);
		$cmdNotif->save();

		$cmdaskText = $this->getCmd(null, 'ask_Text');
		if (is_object($cmdaskText)) {
			$cmdaskText->remove();
		}
		$cmdaskYN = $this->getCmd(null, 'ask_YN');
		if (is_object($cmdaskYN)) {
			$cmdaskYN->remove();
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
	public static function cronDaily() {
      		mobile::makeTemplateJson();
    	}
}

class mobileCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function dontRemoveCmd() {
		return true;
	}

	public function execute($_options = array()) {
		if ($this->getType() != 'action') {
			return;
		}
		$eqLogic = $this->getEqLogic();
		log::add('mobile', 'debug', 'Notif > ' . json_encode($_options) . ' / ' . $eqLogic->getId() . ' / ' . $this->getLogicalId() . ' / idNotif =' . $idNotif, 'config');
		if ($this->getLogicalId() == 'notif') {
			if ($_options['title'] == '' || $_options['title'] == $_options['message'] || $_options['title'] == ' ') {
				$_options['title'] = 'Jeedom';
			}
			$answer = ($_options['answer']) ? join(';', $_options['answer']) : null;
			$askType = ($_options['answer']) ? 'ask_Text' : 'notif';
			$timeout = ($_options['timeout']) ? $_options['timeout'] : 'nok';
			log::add('mobile', 'debug', 'Commande de notification ' . $askType, 'config');
			if ($eqLogic->getConfiguration('notificationArn', null) != null && $eqLogic->getConfiguration('type_mobile', null) != null) {
				$idNotif = $eqLogic->getConfiguration('idNotif', 0);
				$idNotif = $idNotif + 1;
				$eqLogic->setConfiguration('idNotif', $idNotif);
				$eqLogic->save();
				mobile::notification($eqLogic->getConfiguration('notificationArn', null), $eqLogic->getConfiguration('type_mobile', null), $_options['title'], $_options['message'], null, $askType, $idNotif, $answer, $timeout);
				log::add('mobile', 'debug', 'Action : Envoi d\'une configuration ', 'config');
			} else {
				log::add('mobile', 'debug', 'ARN non configuré ', 'config');
			}
		}
	}

	/*     * **********************Getteur Setteur*************************** */
}
