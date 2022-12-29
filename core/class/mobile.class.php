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

	public static $_pluginSuported = array('mobile', 'openzwave', 'rfxcom', 'edisio', 'mpower', 'mySensors', 'Zibasedom', 'virtual', 'camera', 'weather', 'philipsHue', 'enocean', 'wifipower', 'alarm', 'mode', 'apcupsd', 'btsniffer', 'dsc', 'rflink', 'mysensors', 'relaynet', 'remora', 'unipi', 'eibd', 'thermostat', 'netatmoThermostat', 'espeasy', 'jeelink', 'teleinfo', 'tahoma', 'protexiom','boilerThermostat', 'lifx', 'wattlet', 'rfplayer', 'openenocean', 'netatmoWeather', 'Volets', 'vmczehnder','zigbee');

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
    require_once dirname(__FILE__) . '/../../3rdparty/phpqrcode/qrlib.php';
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
		ob_start();
		QRcode::png(json_encode($request_qrcode));
		$imageString = base64_encode( ob_get_contents() );
		ob_end_clean();
		return $imageString;
	}

	public static function jsonPublish($os, $titre, $message, $badge = 'null', $type, $idNotif, $answer, $timeout, $token, $photo, $version, $optionsNotif=[], $critical = false){
		$dateNotif = date("Y-m-d H:i:s");
		$badge = 0;
		if ($timeout != 'nok') {
			$timeout = date('Y-m-d H:i:s', strtotime("$dateNotif + $timeout SECONDS"));
		}
		$addAsk = '';
		if ($type == 'ask_Text') {
			$addAsk = '\"category\":\"TEXT_CATEGORY\",\"answer\":\"' . $answer . '\",\"timeout\":\"' . $timeout . '\",';
		}

      	if($token == null){
	  $message = preg_replace("# {2,}#", " ", preg_replace("#(\r\n|\n\r|\n|\r)#", "\\\\\\n", $message));
          if ($os == 'ios') {
              if ($badge == 'null') {
                  $publish = '{"default": "test", "APNS": "{\"aps\":{\"content-available\":\"1\",' . $addAsk . '\"alert\": {\"title\":\"' . $titre . '\",\"body\":\"' . $message . '\"},\"badge\":\"0\",\"sound\":\"silence.caf\",\"date\":\"' . $dateNotif . '\",\"idNotif\":\"' . $idNotif . '\"}}"}';
              } else {
                  $publish = '{"default": "test", "APNS": "{\"aps\":{\"content-available\":\"1\",' . $addAsk . '\"alert\": {\"title\":\"' . $titre . '\",\"body\":\"' . $message . '\"},\"badge\":\"'. $badge .'\",\"sound\":\"silence.caf\",\"date\":\"' . $dateNotif . '\",\"idNotif\":\"' . $idNotif . '\"}}"}';
              }
          } else if ($os == 'android') {
              $publish = '{"default": "Erreur de texte de notification", "GCM": "{ \"notification\": {\"e\":0,\"title\":\"test\",\"body\":\"NotficationTEST\"},\"data\":{\"ticker\":\"test\",\"android_channel_id\":\"JEEDOM_CHANNEL\",\"notificationId\":\"' . $idNotif . '\",\"title\":\"' . $titre . '\",\"text\":\"' . $message . '\",' . $addAsk . '\"sound\":\"default\",\"idNotif\":\"' . $idNotif . '\",\"date\":\"' . $dateNotif . '\",\"smallIcon\":\"notificon\",\"largeIcon\":\"appicon.png\"}}"}';
          } else if ($os == 'microsoft') {

          }
        }else{
            if ($os == 'android' && $version == 1) {
                $android = [
                	'notification' => [
                		'title' => $titre,
                		'body' => $message,
                		'channel_id' => 'default',
                  		'color' => '#0000FF'
               		 ]
              	];

              	$data = [
              	'title' => $titre,
                'text' => $message,
                'idNotif' => strval($idNotif),
                'channelId' => 'default',
                'date' => $dateNotif

              ];

              if($photo != null){
                $notification = [
              		'title' => $titre,
               		'body' => $message,
                  	'image' => $photo
              	];
              }else{
              	$notification = [
              	'title' => $titre,
               	'body' => $message
              ];
              }

              $publish = [
              	'token' => $token,
                'notification' => $notification,
                'android' => $android,
                'data' => $data
              ];

            }
	    if($version == 2){


	   // log::add('mobile','debug','ANSWERS :'.$answer);

            if($addAsk != ''){
              $askParams = [ 'choices' => $answer,
                             'idVariable' => $optionsNotif['askVariable'],
                             'boxName' => config::byKey('name'),
                             'hwKey' => jeedom::getHardwareKey(),
                             'timeout' => (strtotime($timeout) - time())*1000,
                             'isBack' => false
                             ];
              $askParams = json_encode($askParams);
            }else{

              $askParams = 'noAsk';
              $optionsNotif['askVariable'] = 'rien';
            }

            $optionsNotif['askParams'] = $askParams;

			$channelId = "default";
			if($os == 'android' && $critical == true){
				$channelId = "critical";
			}

            $customData = [
              'title' => $titre,
              'body' => $message,
              'idNotif' => strval($idNotif),
              'channelId' => $channelId,
              'date' => $dateNotif ,
              'boxName' => config::byKey('name'),
              'boxApiKey' => jeedom::getHardwareKey()
            ];

            $data = array_merge($customData, $optionsNotif);

          	$android = [
               'data' => $data,
               'priority' => 'high',
              	];


             $apns = [
             	'payload' => [
                	'aps' => [
                    	'mutuable-content' => 1,
                      	'contentAvailable' => true,
                      	'sound' => 'default'
                      ],
                      'notifee_options' => [
                         'ios' => [
							'critical' => $critical,
                            'foregroundPresentationOptions' => [
                               'alert' => true,
                               'badge' => true,
                               'sound' => true
                            ]
                         ]
                      ]
                  ]
               ];


              if($photo != null){
                 $android['data']['image'] = $photo;
                 $apns['payload']['notifee_options']['image'] = $photo;
                 $apns['payload']['notifee_options']['ios']['attachments'] = [
                   [
                     'url' => $photo,
                     'typeHint' => $optionsNotif['typeHint']
                 	]
                 ];
              }

              $publish = [
              	'token' => $token,
                'android' => $android,
                'data' => $data,
                'apns' => $apns
              ];

	     }
        }
      log::add('mobile', 'debug', 'JSON publish >  : ' . json_encode($publish));
		return $publish;
	}

	public static function notification($arn, $os, $titre, $message, $badge = 'null', $type, $idNotif, $answer,  $timeout, $token, $photo, $version=1, $optionsNotif=[], $critical = false) {
		log::add('mobile', 'debug', 'notification en cours !');
		$publish = ($badge == 'null') ? mobile::jsonPublish($os, $titre, $message, $badge, $type, $idNotif, $answer,  $timeout, $token, $photo,$version,$optionsNotif,$critical) : mobile::jsonPublish($os, $titre, $message, $badge, $type, $idNotif, $answer,  $timeout, $token, $photo, $version,$optionsNotif,$critical);
      	log::add('mobile', 'debug', 'JSON publish >  : ' . json_encode($publish));
      	if($token != null){
            if($token == 'notifsBGDisabled'){
              log::add('mobile', 'debug', 'NOTIFICATION NON ENVOYEE : SERVICES NOTIF DESACTIVE SUR VOTRE TELEPHONE : ');
              message::removeAll(__CLASS__, 'alertNotifsSend');
              message::add(__CLASS__, 'Echec envoie de notification : service desactive dans les parametres de votre telephone', 'notifsbgSend', 'alertNotifsSend');
              return;

            }
          	$url = config::byKey('service::cloud::url','core','https://cloud.jeedom.com').'/service/fcm';
            $post = ['message' => $publish];
          	log::add('mobile', 'debug', 'JSON envoyé en mode FCM : ' . json_encode($post));
        }elseif($token == null && $version == 2){
              log::add('mobile', 'debug', 'NOTIFICATION NON ENVOYEE : PAS DE TOKEN ENREGISTRE SUR VOTRE TELEPHONE :  ' );
              message::removeAll(__CLASS__, 'noValidToken');
              message::add(__CLASS__, 'NOTIFICATION NON ENVOYEE : PAS DE TOKEN ENREGISTRE SUR VOTRE TELEPHONE :', 'noValidTok', 'noValidToken');
              return;
        }else{
          	log::add('mobile', 'debug', 'JSON envoyé : APN' . $publish);
        	$post = [
				'arn' => $arn,
				'text' => $publish,
			];
            $url = config::byKey('service::cloud::url','core','https://cloud.jeedom.com').'/service/notif';
        }

		$request_http = new com_http($url);
        $request_http->setHeader(array(
        	'Content-Type: application/json',
        	'Autorization: '.sha512(strtolower(config::byKey('market::username')).':'.config::byKey('market::password'))
        ));
        $request_http->setPost(json_encode($post));
		$result = json_decode($request_http->exec(3,5),true);
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
			$cmdgeoloc->event($geoloc['value']);
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
      	if($this->getLogicalId() == ''){
			$key = config::genKey(32);
			$this->setLogicalId($key);
			$this->save();
        }
	}


 public static function saveMenuEqLogics($eqId, $arrayMenus, $checkDefault, $nbIcones){
	 $eqLogic = eqLogic::byId(intval($eqId));
	 if(is_object($eqLogic)){
			$i = 1;
			//log::add('mobile','debug','ELEMENTSMENUS : '.json_encode($arrayMenus));
			 $eqLogic->setConfiguration('nbIcones', $nbIcones);
			foreach($arrayMenus as $menu){
              						if($menu[0] == 'none'){
                                       $eqLogic->setConfiguration('selectNameMenu'.$i, 'none');
                                    }else{
                                      	$result = explode('_',$menu[0]);
									    $objectId = intval($result[0]);
									    $typeObject = $result[1];

                                        $eqLogic->setConfiguration('selectNameMenu'.$i, $menu[0]);
                                    }
                                        $nameUser = $menu[1];
                                        if($nameUser != ''){
                                          $eqLogic->setConfiguration('renameIcon'.$i, $nameUser);

                                        }else{
                                          $eqLogic->setConfiguration('renameIcon'.$i, 'none');

                                        }
									    $iconName = $menu[2];
				/*	log::add('mobile','debug','SPANICON : '.$iconName);
					log::add('mobile','debug','RENAMEICON : '.$nameUser);
					log::add('mobile','debug','SELECTNAME : '.$objectId);*/
										 $eqLogic->setConfiguration('spanIcon'.$i, $iconName);
                                      if($menu[3] != ''){
                                         $eqLogic->setConfiguration('urlUser'.$i, $menu[3]);

                                      }else{
                                         $eqLogic->setConfiguration('urlUser'.$i, 'none');

                                      }
								 $i++;
			 }
             $eqLogic->save();
			 if($checkDefault == 'true'){
				//	$eqLogic->setConfiguration('checkdefaultID', 'yes');
					config::save('checkdefaultID', $eqId, 'mobile');
					self::handleDefaultMenu($eqId);
			 }else{
				  config::save('checkdefaultID', 'noActivMobile', 'mobile');
			 }
	 }
 }

 public static function handleDefaultMenu($mobileActiveDefault){
			 $mobileActive = eqLogic::byId(intval($mobileActiveDefault));
			 if(is_object($mobileActive)){
					 $eqlogics = eqLogic::byType('mobile');
                     $nbIcons = $mobileActive->getConfiguration('nbIcones', 4);
					 foreach($eqlogics as $eqlogic){
							 for($i=1; $i<5; $i++){
								 ${ 'selectNameMenu' . $i} = $mobileActive->getConfiguration('selectNameMenu'.$i, 'none');
								 ${ 'renameIcon' . $i} = $mobileActive->getConfiguration('renameIcon'.$i, '');
								 ${ 'spanIcon' . $i} = $mobileActive->getConfiguration('spanIcon'.$i, 'none');
								 ${ 'urlUser' . $i} = $mobileActive->getConfiguration('urlUser'.$i, 'none');
								 $eqlogic->setConfiguration('selectNameMenu'.$i, ${ 'selectNameMenu' . $i});
								 $eqlogic->setConfiguration('renameIcon'.$i, ${ 'renameIcon' . $i});
								 $eqlogic->setConfiguration('spanIcon'.$i, ${ 'spanIcon' . $i});
								 $eqlogic->setConfiguration('urlUser'.$i, ${ 'urlUser' . $i});

							 }
                           $eqlogic->setConfiguration('nbIcones', $nbIcons);
                           $eqlogic->save();
					 }
			 }
 }

	public static function configMenuCustom($eqId){
	  $eqLogic = eqLogic::byId($eqId);
		if(is_object($eqLogic)){
			$nbIcones = $eqLogic->getConfiguration('nbIcones', 4);
			$arrayElements = array();
			$j = 0;
            $count = 1;
			for($i=1;$i<5; $i++){
				    $isActive = true;
                    ${ 'tabIconName' . $i} = $eqLogic->getConfiguration('spanIcon'.$i , 'none');
                    config::save('icon'.$i.'NoCut', ${ 'tabIconName' . $i} , 'mobile');
                    if(${ 'tabIconName' . $i} != 'none'){
                      $arrayIcon = explode(' ', ${ 'tabIconName' . $i});
                      ${ 'tabIconName' . $i} = substr(strstr($arrayIcon[1], '-'), 1);
                      ${ 'tabLibName' . $i} = strstr($arrayIcon[1], '-', true);
                      if(${ 'tabLibName' . $i} == 'mdi'){
                        ${ 'tabLibName' . $i} = 'MaterialCommunityIcons';
                      }
                    }else{
                      ${ 'tabIconName' . $i} = 'home';
                    }
                    ${ '$tabRenameInput' . $i} = $eqLogic->getConfiguration('renameIcon'.$i , 'none');
                    if(${ '$tabRenameInput' . $i} == 'none'){
                      ${ '$tabRenameInput' . $i} = 'Accueil';
                    }
                    $objectId = $eqLogic->getConfiguration('selectNameMenu'.$i);
                    if($objectId && $objectId != -1 && $objectId != 'none'){
                      $arrayObjects = explode('_', $objectId);
                      $objectId = intval($arrayObjects[0]);
                      $typeObject = $arrayObjects[1];
                      ${ '$tabUrl' . $i} = "/index.php?v=m&app_mode=1&p={$typeObject}&object_id={$objectId}";
                    }else if($objectId == 'none' && $eqLogic->getConfiguration('urlUser'.$i) != ''){
                      ${ '$tabUrl' . $i} = $eqLogic->getConfiguration('urlUser'.$i);
                    }else{
                      ${ '$tabUrl' . $i} = "/index.php?v=m&app_mode=1";
                    }
				   if($count > intval($nbIcones)){
						 $isActive = false;
					 }
                    $jsonTemplate = array('active' => $isActive,
                                          'icon' => ['name' =>${ 'tabIconName' . $i},
                                                     'type' => ${ 'tabLibName' . $i}],
                                          'name' => ${ '$tabRenameInput' . $i} ,
                                          'options' => ['uri' => ${ '$tabUrl' . $i} ],
                                          'type' =>  strpos(${ '$tabUrl' . $i}, 'www') !== false ? 'urlwww' : 'WebviewApp' );
                    $arrayElements['tab'.$j] =  $jsonTemplate;
                    $j++;
                    $count++;
			}
		  log::add('mobile','debug','JSONTEMPLATEARRAY :'.json_encode($arrayElements));
          return $arrayElements;
		}else{
         return 'undefined';
        }
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

		if($this->getConfiguration('appVersion', 1) == 2){
			$cmdNotif = $this->getCmd(null, 'notifCritical');
			if (!is_object($cmdNotif)) {
				$cmdNotif = new mobileCmd();
			}
			$cmdNotif->setLogicalId('notifCritical');
			$cmdNotif->setName(__('Notification Critique', __FILE__));
			$cmdNotif->setOrder(0);
			$cmdNotif->setEqLogic_id($this->getId());
			$cmdNotif->setDisplay('generic_type', 'GENERIC_ACTION');
			$cmdNotif->setType('action');
			$cmdNotif->setSubType('message');
			$cmdNotif->setIsVisible(1);
			$cmdNotif->save();
		}

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
      	$optionsNotif = [];
		$eqLogic = $this->getEqLogic();

		if ($this->getLogicalId() == 'notif' || $this->getLogicalId() == 'notifCritical') {

			if ($_options['title'] == '' || $_options['title'] == $_options['message'] || $_options['title'] == ' ') {
				$_options['title'] = config::byKey('product_name');
			}

			$critical = false;

			if($this->getLogicalId() == 'notifCritical'){
				$critical = true;
			}

			$answer = ($_options['answer']) ? join(';', $_options['answer']) : null;
            $askVariable = $_options['variable'];
			$askType = ($_options['answer']) ? 'ask_Text' : 'notif';
			$timeout = ($_options['timeout']) ? $_options['timeout'] : 'nok';
            $optionsNotif['askVariable'] = $askVariable;

			log::add('mobile', 'debug', 'Commande de notification ' . $askType, 'config');
			if (($eqLogic->getConfiguration('notificationArn', null) != null || $eqLogic->getConfiguration('notificationRegistrationToken', null) != null ) && $eqLogic->getConfiguration('type_mobile', null) != null) {
				$idNotif = $eqLogic->getConfiguration('idNotif', 0);
				$idNotif = $idNotif + 1;
				$eqLogic->setConfiguration('idNotif', $idNotif);
				$eqLogic->save();

log::add('mobile', 'debug', 'Notif > ' . json_encode($_options) . ' / ' . $eqLogic->getId() . ' / ' . $this->getLogicalId() . ' / idNotif =' . $idNotif, 'config');
              	if (isset($options['file'])) {
            log::add('mobile', 'debug', 'FILE');
			unset($data['file']);
			$_options['files'] = explode(',', $options['file']);
		}
          if (isset($_options['files']) && is_array($_options['files'])) {
            log::add('mobile', 'debug', 'FILES');
			foreach ($_options['files'] as $file) {
              	log::add('mobile', 'debug', 'FILES as FILE');
             	if (trim($file) == '') {
					continue;
				}
              	log::add('mobile', 'debug', 'Continue');
              	$ext = pathinfo($file, PATHINFO_EXTENSION);
              	log::add('mobile', 'debug', $ext.' pour > '.$file);
				if (in_array($ext, array('gif', 'jpeg', 'jpg', 'png'))) {
                  	log::add('mobile', 'debug', 'type photo !');
					if($ext == "gif"){
                      	$typeHint = "com.compuserve.gif";
                    }else if($ext == "jpeg"){
                      	$typeHint = "public.jpeg";
                    }else if($ext == "jpg"){
                    	$typeHint = "public.jpeg";
                    }else if($ext == "png"){
                    	$typeHint = "public.png";
                    }else{
                    	$typeHint = "public.jpeg";
                    }
                  	$optionsNotif['typeHint'] = $typeHint;
                  	$url = network::getNetworkAccess('external');
                  	$url .= '/plugins/mobile/core/php/image.php?';
                  	$nameFile = base64_encode($file).'.'.$ext;
                  	$path = dirname(__FILE__) .'/../../data/images';
                  	$newfile = $path.'/'.$nameFile;
                  log::add('mobile','debug','copie sur > '.$newfile);
                  	if (!file_exists($path)) {
    					mkdir($path);
					}
                  	if (!copy($file, $newfile)) {
    					log::add('mobile', 'error', 'la copie de l\'image a echoué');
					}
                  	$keyFile = md5_file($newfile);
                  	$url .= 'key='.$keyFile.'&name='.$nameFile;
                  	log::add('mobile', 'debug', 'url > '.$url);
                  	mobile::notification($eqLogic->getConfiguration('notificationArn', null), $eqLogic->getConfiguration('type_mobile', null), $_options['title'], $_options['message'], null, $askType, $idNotif, $answer, $timeout, $eqLogic->getConfiguration('notificationRegistrationToken', null), $url, $eqLogic->getConfiguration('appVersion', 1), $optionsNotif, $critical);
                }else{
                	mobile::notification($eqLogic->getConfiguration('notificationArn', null), $eqLogic->getConfiguration('type_mobile', null), $_options['title'], $_options['message'], null, $askType, $idNotif, $answer, $timeout, $eqLogic->getConfiguration('notificationRegistrationToken', null), null, $eqLogic->getConfiguration('appVersion', 1), $optionsNotif, $critical);
                }
            }
          }else{
            mobile::notification($eqLogic->getConfiguration('notificationArn', null), $eqLogic->getConfiguration('type_mobile', null), $_options['title'], $_options['message'], null, $askType, $idNotif, $answer,  $timeout, $eqLogic->getConfiguration('notificationRegistrationToken', null), null, $eqLogic->getConfiguration('appVersion', 1), $optionsNotif, $critical);
          }

				log::add('mobile', 'debug', 'Action : Envoi d\'une configuration ', 'config');
			} else {
				log::add('mobile', 'debug', 'ARN non configuré ', 'config');
			}
		}
	}

	/*     * **********************Getteur Setteur*************************** */
}
