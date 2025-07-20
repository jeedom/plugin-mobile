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
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
include_file('core', 'bellaMobile', 'class', 'mobile');

class mobile extends eqLogic
{

	public static $_pluginSuported = array('mobile', 'openzwave', 'rfxcom', 'edisio', 'mpower', 'mySensors', 'Zibasedom', 'virtual', 'camera', 'weather', 'philipsHue', 'enocean', 'wifipower', 'alarm', 'mode', 'apcupsd', 'btsniffer', 'dsc', 'rflink', 'mysensors', 'relaynet', 'remora', 'unipi', 'eibd', 'thermostat', 'netatmoThermostat', 'espeasy', 'jeelink', 'teleinfo', 'tahoma', 'protexiom', 'boilerThermostat', 'lifx', 'wattlet', 'rfplayer', 'openenocean', 'netatmoWeather', 'Volets', 'vmczehnder', 'zigbee');

	public static $_pluginWidget = array('alarm', 'camera', 'thermostat', 'netatmoThermostat', 'weather', 'mode', 'mobile');

	public static $_pluginMulti = array('LIGHT_STATE', 'ENERGY_STATE', 'FLAP_STATE', 'HEATING_STATE', 'SIREN_STATE', 'LOCK_STATE');

	public static $_listenEvents = array('cmd::update', 'scenario::update', 'jeeObject::summary::update');

	/*     * ***********************Methode static*************************** */

	public static function cronDaily()
	{
		foreach (eqLogic::byType('mobile') as $mobile) {
			if ($mobile->getConfiguration('appVersion', 1) == 2) {
				$mobile->cleaningNotifications();
			}
		}
		// App V1
		mobile::makeTemplateJson();
	}

	public static function getWizardSteps(): array
	{
		$wizard['doc1'] =  __('Accueil', __FILE__);
		$wizard['doc2'] =  __('Page2', __FILE__);
		$wizard['doc3'] =  __('Page3', __FILE__);
		return $wizard;
	}

	/**
	 * Core callback to provide additional information for a new Community post
	 *
	 * @return string
	 */
	public static function getConfigForCommunity()
	{
		$hw = jeedom::getHardwareName();
		if ($hw == 'diy') $hw = trim(shell_exec('systemd-detect-virt'));
		if ($hw == 'none') $hw = 'diy';
		$distrib = system::getDistrib() . ' ' . system::getOsVersion();
		$res = '<br>```<br>';
		$res .= 'OS : ' . $distrib . ' on ' . $hw;
		$res .= ' - PHP : ' . phpversion();
		$res .= '<br/>';
		$res .= 'Etat Clé Api Core : ' . config::byKey('api::core::mode');
		$res .= '<br/>';
		$res .= 'Etat Clé Api Mobile : ' . config::byKey('api::mobile::mode') . '<br>```';
		$res .= '<br/><br/>';
		$res .= '[details="Equipement(s)"]<br/>';
		$res .= '|Id | LogicalId | Type | App | defaultIdMobile | Profil | hideMenuGeoloc | hideMenuCustom|<br>';
		$res .= '|--- | --- | --- | --- | --- | --- | --- | ---|<br>';
		foreach (eqLogic::byType('mobile') as $mobile) {
			$userId = $mobile->getConfiguration('affect_user');
			$userType = user::byId($userId);
			$profil = 'Inconnu';
			if (is_object($userType)) {
				$profil = $userType->getProfils();
			}
			$res .= '|' . $mobile->getId() . ' | ' . $mobile->getLogicalId() . ' | ' . $mobile->getConfiguration('type_mobile') . ' | v' . $mobile->getConfiguration('appVersion', 1) . ' | ' . $mobile->getConfiguration('defaultIdMobile', 'none') . ' | ' . $profil . ' | ' . $mobile->getConfiguration('hideMenuGeoloc', 0) . ' | ' . $mobile->getConfiguration('hideMenuCustom', 0) . '|<br>';
		}
		$res .= '[/details]';
		return $res;
	}

	/******************** APP V1  *************************/


	/**
	 * find eq based on iq
	 *
	 * @return string
	 */
	public static function whoIsIq($iq)
	{
		$search = eqLogic::byLogicalId($iq, 'mobile');
		if (is_object($search)) {
			return $search->getName();
		} else {
			return 'mobile non detecte';
		}
	}

	public static function pluginToSend()
	{
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

	public static function makeTemplateJson()
	{
		log::add(__CLASS__, 'debug', '┌─────▶︎ makeTemplateJson ───────────────');
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
		log::add(__CLASS__, 'debug', '| mobile.json > ' . json_encode($data));
		$event_cmd = array();
		foreach ($data['cmds'] as $cmd) {
			$event_cmd[] = $cmd['id'];
		}
		cache::set('mobile::event', $event_cmd);
		log::add(__CLASS__, 'debug', '| cache set mobile::event > ' . json_encode($event_cmd));
		log::add(__CLASS__, 'debug', '└───────────────────────────────────────────');
	}

	public static function getTemplateJson()
	{
		if (!file_exists(dirname(__FILE__) . '/../../data/mobile.json')) {
			self::makeTemplateJson();
		}
		return json_decode(cmd::cmdToValue(file_get_contents(dirname(__FILE__) . '/../../data/mobile.json')), true);
	}

	public static function makeSaveJson($mobileID, $_data = array(), $type = 'dashboard')
	{
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
		file_put_contents(dirname(__FILE__) . '/../../data/' . $mobileID . '/' . $type . '.json', json_encode($_data));
	}

	public static function getSaveJson($mobileID, $type = 'dashboard')
	{
		if (!file_exists(dirname(__FILE__) . '/../../data/' . $mobileID . '/' . $type . '.json')) {
			self::makeSaveJson($mobileID, array(), $type);
		}
		return json_decode(file_get_contents(dirname(__FILE__) . '/../../data/' . $mobileID . '/' . $type . '.json'), true);
	}

	public static function discovery_eqLogic($plugin = array(), $hash = null)
	{
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
					if (!is_object($objectNow)) {
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
				if ($eqLogic_array['eqType_name'] == 'jeelink') {
					if (isset($eqLogic_array['configuration']['real_eqType'])) {
						$eqLogic_array['eqType_name'] = $eqLogic_array['configuration']['real_eqType'];
					}
				}
				unset($eqLogic_array['eqReal_id'], $eqLogic_array['comment'], $eqLogic_array['configuration'], $eqLogic_array['specificCapatibilities'], $eqLogic_array['timeout'], $eqLogic_array['category'], $eqLogic_array['display']);
				unset($eqLogic_array['status']);
				unset($eqLogic_array['generic_type']);
				unset($eqLogic_array['logicalId']);
				unset($eqLogic_array['isVisible']);
				unset($eqLogic_array['isEnable']);
				if (!in_array($eqLogic_array['eqType_name'], self::$_pluginWidget)) {
					unset($eqLogic_array['eqType_name']);
				}
				$return[] = $eqLogic_array;
			}
		}
		return $return;
	}

	public static function discovery_cmd($plugin = array(), $eqLogics = null, $_withValue = false)
	{
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
					if (strstr($info['display']['icon'], ' icon_')) {
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

	public static function discovery_multi($cmds)
	{
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

	public static function change_cmdAndeqLogic($_cmds, $_eqLogics)
	{
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
				if (isset($_cmds[$key]['value']) && $_cmds[$key]['value'] == $_cmds[$plage_cmd]['id'] && $_cmds[$key]['type'] == 'action') {
					$_cmds[$key]['eqLogic_id'] = $new_eqLogic_id;
				}
			}
			$eqLogic_array[] = array($eqLogic_id, $new_eqLogic_id, $eqLogic_name);
			//$i++;
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

	public static function discovery_object()
	{
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
					foreach ($explodes as $explode) {
						if (substr($explode, 0, 5) != 'icon_') {
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

	public static function discovery_scenario()
	{
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

	public static function discovery_message()
	{
		//return utils::o2a(message::all());
		return array();
	}

	public static function discovery_plan()
	{
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

	public static function discovery_summary()
	{
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
					foreach ($explodes as $explode) {
						if (substr($explode, 0, 5) != 'icon_') {
							$tableEx[] = $explode;
						}
					}
					$value['icon'] = implode(' ', $tableEx);
				}
			}
		}
		return $def;
	}

	public static function discovery_summaryValue($jeeObjectEnvoi)
	{
		$def = config::byKey('object:summary');
		$tableKey = array();
		foreach ($def as $key => $value) {
			$tableKey[] = $key;
		}
		$table = array();
		foreach ($jeeObjectEnvoi as $jeeobject) {
			$object = jeeObject::byId($jeeobject['id']);
			if (is_object($object)) {
				foreach ($tableKey as $key) {
					if ($object->getSummary($key) != null) {
						$tableObject = array();
						$tableObject['object_id'] = $object->getId();
						$tableObject['key'] = $key;
						$tableObject['value'] = $object->getSummary($key);
						$table[] = $tableObject;
					}
				}
			}
		}
		foreach ($tableKey as $key) {
			if (jeeObject::getGlobalSummary($key) != null) {
				$tableObject = array();
				$tableObject['object_id'] = 'global';
				$tableObject['key'] = $key;
				$tableObject['value'] = jeeObject::getGlobalSummary($key);
				$table[] = $tableObject;
			}
		}

		return $table;
	}

	public static function delete_object_eqlogic_null($objects, $eqLogics)
	{
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


	public static function SaveGeoloc($geoloc)
	{
		$eqLogicMobile = eqLogic::byLogicalId($geoloc['Iq'], 'mobile');
		if (is_object($eqLogicMobile)) {
			log::add('mobile', 'debug', '┌──:fg-success: SaveGeoloc - V1 :/fg:──');
			log::add('mobile', 'debug', '| Iq = ' . $geoloc['Iq']);
			log::add('mobile', 'debug', '| Mobile bien trouvé dans cette Jeedom');
			log::add('mobile', 'debug', '| Objet = ' . $eqLogicMobile->getId());
			log::add('mobile', 'debug', '└────────────────────');
			$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($eqLogicMobile->getId(), 'geoId_' . $geoloc['id']);
			if (!is_object($cmdgeoloc)) {
				$cmdgeoloc = new mobileCmd();
				$cmdgeoloc->setLogicalId('geoId_' . $geoloc['id']);
				$cmdgeoloc->setEqLogic_id($eqLogicMobile->getId());
				$cmdgeoloc->setType('info');
				$cmdgeoloc->setSubType('binary');
				$cmdgeoloc->setGeneric_type('PRESENCE');
				$cmdgeoloc->setIsVisible(1);
			}
			$cmdgeoloc->setName(__($geoloc['id'] . '-' . $geoloc['name'], __FILE__));
			$cmdgeoloc->setConfiguration('latitude', $geoloc['latitude']);
			$cmdgeoloc->setConfiguration('longitude', $geoloc['longitude']);
			$cmdgeoloc->setConfiguration('subtitle', $geoloc['subtitle']);
			$cmdgeoloc->setConfiguration('radius', $geoloc['radius']);
			$cmdgeoloc->save();
			$cmdgeoloc->event($geoloc['value']);
		}
	}

	public static function delGeoloc($geoloc)
	{
		log::add('mobile', 'debug', '|┌──:fg-success: Lancement DEL du mobile- V1 :/fg:──');
		log::add('mobile', 'debug', '| Mobile ' . $geoloc['Iq'] . ' pour ' . $geoloc['id']);
		$eqLogicMobile = eqLogic::byLogicalId($geoloc['Iq'], 'mobile');
		$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($eqLogicMobile->getId(), 'geoId_' . $geoloc['id']);
		if (isset($cmdgeoloc)) {
			$cmdgeoloc->remove();
		}
		log::add('mobile', 'debug', '||└────────────────────');
	}


	public static function EventGeoloc($geoloc)
	{
		/******************** APP V1  *************************/
		log::add('mobile', 'debug', '|┌──:fg-success: jsonPublish :/fg:──');
		log::add('mobile', 'debug', '| Geoloc Event du mobile > ' . $geoloc['Iq'] . ' pour ' . $geoloc['id']);
		$eqLogicMobile = eqLogic::byLogicalId($geoloc['Iq'], 'mobile');
		$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($eqLogicMobile->getId(), 'geoId_' . $geoloc['id']);
		$cmdgeolocv2 = cmd::byEqLogicIdAndLogicalId($eqLogicMobile->getId(), 'geoloc_' . $geoloc['id']);
		if (is_object($cmdgeoloc)) {
			log::add('mobile', 'debug', 'commande trouvé');
			if ($geoloc['value'] !== $cmdgeoloc->execCmd()) {
				log::add('mobile', 'debug', 'Valeur non pareille.');
				$cmdgeoloc->event($geoloc['value']);
			} else {
				log::add('mobile', 'debug', '| Valeur pareille. ─▶︎ ' . $geoloc['value'] . ' / ' . $cmdgeoloc->execCmd());
			}
		}
		if (is_object($cmdgeolocv2)) {
			log::add('mobile', 'debug', 'commande trouvé');
			if ($geoloc['value'] !== $cmdgeolocv2->execCmd()) {
				log::add('mobile', 'debug', 'Valeur non pareille.');
				$cmdgeolocv2->event($geoloc['value']);
			} else {
				log::add('mobile', 'debug', '| Valeur pareille. ─▶︎ ' . $geoloc['value'] . ' / ' . $cmdgeolocv2->execCmd());
			}
		}
		log::add('mobile', 'debug', '|└────────────────────');
	}


	public static function deleteFileImg()
	{
		$directory = dirname(__FILE__) . '/../../data/images'; // Chemin vers le répertoire contenant les fichiers
		// Récupérer la liste des fichiers dans le répertoire
		$files = glob($directory . '/*');
		// Date actuelle
		$currentDate = time();
		// Parcourir tous les fichiers
		foreach ($files as $file) {
			// Vérifier la date de modification du fichier
			$modifiedDate = filemtime($file);
			$differenceInDays = floor(($currentDate - $modifiedDate) / (60 * 60 * 24));
			// Vérifier si le fichier a plus de 30 jours
			if ($differenceInDays > 30) {
				// Supprimer le fichier
				unlink($file);
			}
		}
	}





	/******************** APP V2  *************************/

	/**
	 * get json for notification
	 * Call by class notification
	 * @return array
	 */
	public static function jsonPublish($os, $titre, $message, $type, $idNotif, $answer, $timeout, $token, $photo, $version, $optionsNotif = [], $critical = false, $Iq = null, $specific = false, $silent = false)
	{
		log::add('mobile', 'debug', '||┌──:fg-success: jsonPublish :/fg:──');
		log::add('mobile', 'debug', '||┌──:fg-success: PARAMS :/fg:──' . $silent);
		if (isset($Iq)) log::add('mobile', 'debug', '||| IQ for jsonPublish > ' . $Iq);

		$dateNotif = date("Y-m-d H:i:s");
		$newDate = date("Y-m-d");
		$horaireFormat = date("H:i");
		$badge = 0;
		$defaultName = empty(config::byKey('name')) ? config::byKey('product_name') : config::byKey('name');
		if ($timeout != 'nok') {
			$timeout = date('Y-m-d H:i:s', strtotime("$dateNotif + $timeout SECONDS"));
		}
		$addAsk = '';
		if ($type == 'ask_Text') {
			$addAsk = '\"category\":\"TEXT_CATEGORY\",\"answer\":\"' . $answer . '\",\"timeout\":\"' . $timeout . '\",';
		}

		if ($token == null) {
			$message = preg_replace("# {2,}#", " ", preg_replace("#(\r\n|\n\r|\n|\r)#", "\\\\\\n", $message));
			if ($os == 'ios') {
				if ($badge == 'null') {
					$publish = '{"default": "test", "APNS": "{\"aps\":{\"content-available\":\"1\",' . $addAsk . '\"alert\": {\"title\":\"' . $titre . '\",\"body\":\"' . $message . '\"},\"badge\":\"0\",\"sound\":\"silence.caf\",\"date\":\"' . $dateNotif . '\",\"idNotif\":\"' . $idNotif . '\"}}"}';
				} else {
					$publish = '{"default": "test", "APNS": "{\"aps\":{\"content-available\":\"1\",' . $addAsk . '\"alert\": {\"title\":\"' . $titre . '\",\"body\":\"' . $message . '\"},\"badge\":\"' . $badge . '\",\"sound\":\"silence.caf\",\"date\":\"' . $dateNotif . '\",\"idNotif\":\"' . $idNotif . '\"}}"}';
				}
			} else if ($os == 'android') {
				$publish = '{"default": "Erreur de texte de notification", "GCM": "{ \"notification\": {\"e\":0,\"title\":\"test\",\"body\":\"NotficationTEST\"},\"data\":{\"ticker\":\"test\",\"android_channel_id\":\"JEEDOM_CHANNEL\",\"notificationId\":\"' . $idNotif . '\",\"title\":\"' . $titre . '\",\"text\":\"' . $message . '\",' . $addAsk . '\"sound\":\"default\",\"idNotif\":\"' . $idNotif . '\",\"date\":\"' . $dateNotif . '\",\"smallIcon\":\"notificon\",\"largeIcon\":\"appicon.png\"}}"}';
			} else if ($os == 'microsoft') {
			}
		} else {
			//App V2
			if ($version == 2) {
				if ($addAsk != '') {
					$askParams = [
						'choices' => $answer,
						'idVariable' => $optionsNotif['askVariable'],
						'boxName' => $defaultName,
						'hwKey' => jeedom::getHardwareKey(),
						'timeout' => (strtotime($timeout) - time()) * 1000,
						'isBack' => false
					];
					$askParams = json_encode($askParams);
				} else {
					$askParams = 'noAsk';
					$optionsNotif['askVariable'] = 'rien';
				}

				$optionsNotif['askParams'] = $askParams;

				$channelId = $specific ? "specificChannel" : "default";
				if ($os == 'android' && $critical == true) {
					$channelId = "critical";
				}
				if ($critical == true) {
					$criticalString = 'true';
				} else {
					$criticalString = 'false';
				}
				// $customData = [
				// 	'title' => $titre,
				// 	'body' => $message,
				// 	'idNotif' => strval($idNotif),
				// 	'channelId' => $channelId,
				// 	'date' => $dateNotif,
				// 	'critical' => $criticalString,
				// 	'boxName' => $defaultName,
				// 	'boxApiKey' => jeedom::getHardwareKey(),
				// 	"askParams" => $askParams,
				// 	'textToDisplay' => 'none',
				// 	'newDate' => $newDate,
				// 	'horaireFormat' => $horaireFormat
				// ];
				$customData = [
					'title' => $titre,
					'body' => $message,
					'idNotif' => strval($idNotif),
					'channelId' => $channelId,
					'date' => $dateNotif,
					'critical' => $criticalString,
					'boxName' => $defaultName,
					'boxApiKey' => jeedom::getHardwareKey(),
					"askParams" => $askParams,
					'textToDisplay' => 'none',
					'newDate' => $newDate,
					'horaireFormat' => $horaireFormat,
					'silent' => $silent ? 'true' : 'false'
				];

				$notification = [
					'title' => $titre,
					'body' => $message,
				];

				$data = array_merge($customData, $optionsNotif);

				$android = [
					'data' => $data,
					'priority' => 'high'
				];

				$apns = [
					'headers' => [
						'apns-priority' => $silent ? '5' : '10',
						'apns-collapse-id' => strval($idNotif),
						'apns-push-type' => $silent ? 'background' : 'alert',
						'apns-topic' => 'com.jeedom.jeedomobile'
					],
					'payload' => [
						'aps' => array_merge([
							'content-available' => true,
						], $silent ? [] : [
							'sound' => [
								'name' => 'default',
								'critical' => $critical
							],
							'alert' => [
								'subtitle' => config::byKey('name'),
								'title' => $titre,
								'body' => $message
							]
						]),
						'notifee_options' => [
							'ios' => array_merge([
								'critical' => $critical,
								'foregroundPresentationOptions' => [
									'alert' => $silent ? false : true,
									'badge' => !$silent,
									'sound' => !$silent
								]
							], $silent ? [] : ['sound' => 'default'])
						]
					]
				];



				if ($photo != null) {
					$data['image'] = $photo;
					$apns['payload']['notifee_options']['image'] = $photo;
					$apns['payload']['notifee_options']['ios']['attachments'] = [
						[
							'url' => $photo,
							'typeHint' => $optionsNotif['typeHint']
						]
					];
				}

				if ($os == 'android') {
					$publish = [
						'token' => $token,
						'android' => $channelId == 'specificChannel' ? ['priority' => 'high'] : $android,
						'data' => $data,
					];
				} else {
					$publish = [
						'token' => $token,
						'data' => $data,
						'apns' => $apns
					];
				}

				$publishJson = [
					'token' => $token,
					'data' => $data,
				];


				if (isset($Iq) && !$specific) {
					// SAVE NOTIFS IN JSON
					$pathNotificationData = '/../data/notifications';
					if (!is_dir(dirname(__FILE__) . $pathNotificationData)) {
						mkdir(dirname(__FILE__) . $pathNotificationData, 0775, true);
					}
					$filePath = dirname(__FILE__) . $pathNotificationData . '/' . $Iq . '.json';

					if (!file_exists($filePath)) {
						file_put_contents($filePath, '');
					}
					$notificationsContent = file_get_contents($filePath);
					$notifications = json_decode($notificationsContent, true);

					if ($notifications === null) {
						$notifications = array();
					}

					foreach ($notifications as &$notification) {
						if (isset($notification['data']['askParams'])) {
							$askParams = json_decode($notification['data']['askParams'], true);
							if ($askParams !== null && isset($askParams['timeout'])) {
								//log::add('mobile', 'debug', 'Timeout Ask remis à zero');
								$askParams['timeout'] = 0;
								$notification['data']['askParams'] = json_encode($askParams);
							}
						}
					}
					$notifications[$idNotif] = $publishJson;
					log::add('mobile', 'debug', '||| [INFO] Notification enregistrée : ' . json_encode($notifications));
					file_put_contents($filePath, json_encode($notifications));
				}
			} else {
				// APP V1
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

					if ($photo != null) {
						$notification = [
							'title' => $titre,
							'body' => $message,
							'image' => $photo
						];
					} else {
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
			}
		}
		log::add('mobile', 'debug', '||| [INFO] JSON publish > ' . json_encode($publish));
		log::add('mobile', 'debug', '||└────────────────────');
		return $publish;
	}

	/**
	 * send notification to app
	 * Call by class execute
	 * @return array
	 */
	public static function notification($arn, $os, $titre, $message, $type, $idNotif, $answer,  $timeout, $token, $photo, $version = 1, $optionsNotif = [], $critical = false, $Iq = null, $specific = false, $silent = false)
	{
		log::add('mobile', 'debug', '|┌──:fg-success: Notification en cours ! :/fg:──');
		//if ($version == 2) {
		$publish = mobile::jsonPublish($os, $titre, $message, $type, $idNotif, $answer, $timeout, $token, $photo, $version, $optionsNotif, $critical, $Iq, $specific, $silent);
		if ($token != null) {
			if ($token == 'notifsBGDisabled') {
				log::add('mobile', 'debug', '|| [ERROR] NOTIFICATION NON ENVOYEE : LE SERVICE NOTIF EST DESACTIVE SUR LE TELEPHONE');
				message::add(__CLASS__, 'Échec de l\'envoie de notification : le service est désactivé dans les paramètres du téléphone', 'notifsbgSend', 'alertNotifsSend');
				return;
			}
			if ($token == 'desactivate') {
				log::add('mobile', 'debug', '|| [ERROR] NOTIFICATION NON ENVOYEE : LES NOTIFICATIONS SONT DESACTIVEES DANS L\'APP : ');
				message::add(__CLASS__, 'Échec de l\'envoie de notification : le service est désactivé dans les paramètres de l\'application', 'notifsbgSend', 'alertNotifsSend');
				return;
			}
			$url = config::byKey('service::cloud::url', 'core', 'https://cloud.jeedom.com') . '/service/fcm';
			$options = [
				'contentAvailable' => true,
				'mutableContent' => true,
				'priority' => 'high',
				'collapseKey' => strval($publish['data']['idNotif'])
			];

			$post = ['message' => $publish, 'options' => $options];
			log::add('mobile', 'debug', '|| [INFO] JSON envoyé en mode FCM > ' . json_encode($post));
		} elseif ($token == null && $version == 2) {
			log::add('mobile', 'debug', '|| [ERROR] NOTIFICATION NON ENVOYEE : PAS DE TOKEN ENREGISTRE SUR LE TELEPHONE :  ');
			//message::removeAll(__CLASS__, 'noValidToken');
			message::add(__CLASS__, '| NOTIFICATION NON ENVOYÉE : PAS DE TOKEN ENREGISTRE SUR LE TÉLÉPHONE :', 'noValidTok', 'noValidToken');
			return;
		} else {
			log::add('mobile', 'debug', '|| [INFO] JSON envoyé : APN' . $publish);
			$post = [
				'arn' => $arn,
				'text' => $publish,
			];
			$url = config::byKey('service::cloud::url', 'core', 'https://cloud.jeedom.com') . '/service/notif';
		}

		$request_http = new com_http($url);
		$request_http->setHeader(array(
			'Content-Type: application/json',
			'Autorization: ' . sha512(strtolower(config::byKey('market::username')) . ':' . config::byKey('market::password'))
		));
		//$request_http->setLogError(true);
		$request_http->setPost(json_encode($post));
		$result = json_decode($request_http->exec(30, 3), true);
		if (!isset($result['state']) || $result['state'] != 'ok') {
			log::add('mobile', 'info', '|| [WARNING] Echec Première Tentative d\'envoi de la notification');
			log::add('mobile', 'info', '|| Nouvelle tentative ....');
			sleep(rand(1, 10));
			$result = json_decode($request_http->exec(30, 3), true);
		}
		if (!isset($result['state']) || $result['state'] != 'ok') {
			if (isset($result['error']) && strpos($result['error'], 'Quotas exceeded') !== false) {
				log::add('mobile', 'error', __("Les quotas pour fcm sont dépassés. Le maximum autorisé est de 5 requêtes par minute.", __FILE__));
				log::add('mobile', 'debug', __('Echec de l\'envoi de la notification :', __FILE__) . json_encode($result));
			} else {
				throw new Exception(__('Echec de l\'envoi de la notification :', __FILE__) . json_encode($result));
			}
		}
		//} else {
		//log::add('mobile', 'error', __("Échec de l'envoi de notification : la version 1 de l'app n'est plus prise en charge !", __FILE__));
		//}
		log::add('mobile', 'debug', '|└────────────────────');
	}

	/**
	 * Create and update cmd geoloc
	 * Call By api : setConfigs
	 */
	public static function createCmdGeoLocV2($Iq, $geolocs)
	{
		log::add('mobile', 'debug', '|┌──:fg-success: GeoLocV2 :/fg:──');
		$mobile = eqLogic::byLogicalId($Iq, 'mobile');
		if (is_object($mobile)) {
			log::add('mobile', 'debug', '||  OK  Mobile existant > ' . $mobile->getName());
			log::add('mobile', 'debug', '|| [INFO] GEOLOCS > ' . $geolocs);

			$order = count($mobile->getCmd());
			$noExistCmd = 0;
			$decodedGeolocs = json_decode($geolocs, true);
			foreach ($decodedGeolocs as $index => $geoloc) {
				if (!isset($geoloc['name'])) continue;
				log::add('mobile', 'debug', '|| geoloc_' . $index . ' > ' . $geoloc['name']);
				$cmd = cmd::byEqLogicIdAndLogicalId($mobile->getId(), 'geoloc_' . $index);
				$logicalId = 'geoloc_' . $index;
				if (!is_object($cmd)) {
					$noExistCmd = 1;
					$cmd = new mobileCmd();
					$cmd->setLogicalId($logicalId);
					$cmd->setEqLogic_id($mobile->getId());
					$cmd->setIsVisible(1);
					$cmd->setGeneric_type('PRESENCE');
					$cmd->setTemplate('dashboard', 'core::presence');
					$cmd->setTemplate('mobile', 'core::presence');
					$cmd->setDisplay('icon', '<i class="icon fas fa-location-arrow"></i>');
					$cmd->setDisplay('showIconAndNamedashboard', 1);
					$cmd->setIsHistorized(1);
					$cmd->setOrder($order);
					$order++;
					log::add('mobile', 'debug', '|| Ajout geofencing > ' . $geoloc['name']);
				}
				$cmd->setName($geoloc['name']);
				$cmd->setType('info');
				$cmd->setSubType('binary');
				$cmd->setConfiguration('latitude', $geoloc['latitude']);
				$cmd->setConfiguration('longitude', $geoloc['longitude']);
				$cmd->setConfiguration('radius', $geoloc['radius']);
				if ($cmd->getChanged() === true) $cmd->save();
				if ($noExistCmd == 1) {
					$mobile->checkAndUpdateCmd($logicalId, $geoloc['value']);
					log::add('mobile', 'debug', '|| Valeur enregistrée > ' . $geoloc['value']);
				}
				$noExistCmd = 0;
			}
		} else {
			log::add('mobile', 'debug', '| [ERROR] Mobile inexistant !');
		}
		log::add('mobile', 'debug', '|└────────────────────');
	}

	/**
	 * Get menu default whitout "tab"
	 * @return array
	 */
	public static function getMenuDefaultV2($nbIcones = 3)
	{
		$namesMenus =  ['home', 'overview', 'health', 'home', 'home', 'home', 'home', 'home'];
		$renamesIcons =  ['Accueil', 'Synthese', 'Santé', 'Accueil', 'Accueil', 'Accueil', 'Accueil', 'Accueil'];
		$spanIcons =  ['icon jeedomapp-in', 'fab fa-hubspot', 'fas fa-medkit', 'icon jeedomapp-in', 'icon jeedomapp-in', 'icon jeedomapp-in', 'icon jeedomapp-in', 'icon jeedomapp-in'];
		$urlUsers =  ['none', 'none', 'none', 'none', 'none', 'none', 'none', 'none'];
		$j = 0;
		$menuCustomArray = [];
		for ($i = 1; $i <= $nbIcones; $i++) {
			$menuCustomArray[$i]['selectNameMenu'] = $namesMenus[$j];
			$menuCustomArray[$i]['renameIcon'] = $renamesIcons[$j];
			$menuCustomArray[$i]['spanIcon'] = $spanIcons[$j];
			$menuCustomArray[$i]['urlUser'] = $urlUsers[$j];
			$j++;
		}
		return $menuCustomArray;
	}

	/**
	 * Get menu default with "tab"
	 * @return array
	 */
	public static function getMenuDefaultTab()
	{
		$defaultMenuJson = '{"tab0":{"active":true,"icon":{"name":"in","type":"jeedomapp"},"name":"Accueil","options":{"uri":"\/index.php?v=m&p=home"},"type":"WebviewApp"},
						"tab1":{"active":true,"icon":{"name":"hubspot","type":"fa"},"name":"Synthese","options":{"uri":"\/index.php?v=m&p=overview"},"type":"WebviewApp"},
						"tab2":{"active":true"icon":{"name":"medkit","type":"fa"},"name":"Sant\u00e9","options":{"uri":"\/index.php?v=m&p=health"},"type":"WebviewApp"},
						"tab3":{"active":false,"icon":{"name":"in","type":"jeedomapp"},"name":"Accueil","options":{"uri":"\/index.php?v=m&app_mode=1"},"type":"WebviewApp"}},
						"tab4":{"active":false,"icon":{"name":"in","type":"jeedomapp"},"name":"Accueil","options":{"uri":"\/index.php?v=m&app_mode=1"},"type":"WebviewApp"}},
						"tab5":{"active":false,"icon":{"name":"in","type":"jeedomapp"},"name":"Accueil","options":{"uri":"\/index.php?v=m&app_mode=1"},"type":"WebviewApp"}},
						"tab6":{"active":false,"icon":{"name":"in","type":"jeedomapp"},"name":"Accueil","options":{"uri":"\/index.php?v=m&app_mode=1"},"type":"WebviewApp"}},
						"tab7":{"active":false,"icon":{"name":"in","type":"jeedomapp"},"name":"Accueil","options":{"uri":"\/index.php?v=m&app_mode=1"},"type":"WebviewApp"}}';
		return json_encode($defaultMenuJson);
	}

	/**
	 * menu assignment
	 * Call by ajax menuDefault for modal.menuCustom
	 */
	public static function handleMenuDefaultBySelect($eqId, $eqDefault)
	{
		if (!is_object($mobile = eqLogic::byId($eqId, 'mobile'))) return;
		log::add('mobile', 'debug', '┌──:fg-success: handleMenuDefaultBySelect( ' . $eqId . ', ' . $eqDefault . ') :/fg:──');
		// ATTRIBUTION D'UN MENU AU MOBILE
		if ($eqDefault == 'default') { //reset menuCustom
			log::add('mobile', 'debug', '| menu souce : default');
			$menuCustomArray = mobile::getMenuDefaultV2();
			$mobile->setConfiguration('menuCustomArray', $menuCustomArray);
			$mobile->setConfiguration('nbIcones', 3);
			$mobile->setConfiguration('defaultIdMobile', 'default');
			$mobile->save();
		} else if (is_object($mobileDefault = eqLogic::byId($eqDefault, 'mobile'))) {
			log::add('mobile', 'debug', '| menu souce : ' . $mobileDefault->getHumanName());
			// ATTRIBUTION DU MENU DUN AUTRE MOBILE
			$mobile->setConfiguration('defaultIdMobile', $eqDefault);
			if ($mobile->getId() == $mobileDefault->getId()) {
				log::add('mobile', 'debug', '| souce et cible identique ');
				//$mobile->save();
				//return;
			}
			$nbIcones = $mobileDefault->getConfiguration('nbIcones', 3);
			$menuCustomArray = $mobileDefault->getConfiguration('menuCustomArray');
			$mobile->setConfiguration('nbIcones', $nbIcones);
			$mobile->setConfiguration('menuCustomArray', $menuCustomArray);
			$mobile->save();
		}
		log::add('mobile', 'debug', '└───────────────────────────────────────────');
	}

	/**
	 * Call by configMenuCustom()
	 * @return array
	 */
	public static function generateTabIcon($menuCustomArray, $i)
	{
		$result = array();

		$tabIconName = isset($menuCustomArray[$i]['spanIcon']) ? $menuCustomArray[$i]['spanIcon'] : 'none';

		if ($tabIconName != 'none') {
			$arrayIcon = explode(' ', $tabIconName);
			$tabIconName = substr(strstr($arrayIcon[1], '-'), 1);
			$tabLibName = strstr($arrayIcon[1], '-', true);
			if ($tabLibName == 'mdi') {
				$tabLibName = 'Mdi';
			}
		} else {
			$tabIconName = 'in';
			$tabLibName = 'jeedomapp';
		}

		$tabRenameInput = (isset($menuCustomArray[$i]['renameIcon']) && $menuCustomArray[$i]['renameIcon'] != 'none') ? $menuCustomArray[$i]['renameIcon'] : 'Accueil';

		$result['tabIconName'] = $tabIconName;
		$result['tabLibName'] = $tabLibName;
		$result['tabRenameInput'] = $tabRenameInput;

		return $result;
	}

	/**
	 * Call by configMenuCustom()
	 * @return array
	 */
	public static function generateTypeObject($pluginPanelMobile, $menuCustomArray)
	{
		$webviewUrl = 'd';
		$objectId = isset($menuCustomArray['selectNameMenu']) ? $menuCustomArray['selectNameMenu'] : '';
		$result = array();
		if ($objectId && $objectId != -1 && $objectId != 'none' && $objectId != 'url') {
			// SPECIFIC OBJETS FOR URL
			$excludedRefs = array('overview', 'health', 'home', 'timeline');
			if (!in_array($objectId, $excludedRefs)) {
				$arrayObjects = explode('_', $objectId);
				$objectId = $arrayObjects[0];
				$typeObject = $arrayObjects[1];
				$typewebviewurl = $webviewUrl;
				$typeobjectId = $objectId;

				switch ($typeObject) {
					case 'views':
						$tabUrl = "/index.php?v={$webviewUrl}&p=view&view_id={$objectId}";
						break;
					case 'dashboard':
						$tabUrl = "/index.php?v={$webviewUrl}&p=dashboard&object_id={$objectId}";
						break;
					case 'plan':
						$tabUrl = "/index.php?v={$webviewUrl}&p=plan&fullscreen=1&plan_id={$objectId}";
						break;
					case 'panel':
						$tabUrl = (isset($pluginPanelMobile[$objectId]) && $pluginPanelMobile[$objectId] == $objectId) ? "/index.php?v=m&p={$objectId}" : "/index.php?v=m&p={$objectId}&app_mode=1";
						break;
					default:
						break;
				}
			} else {
				$typeObject = $objectId;
				$typewebviewurl = $webviewUrl;
				$typeobjectId = '';

				switch ($objectId) {
					case 'overview':
						$tabUrl = "/index.php?v=m&p=overview";
						break;
					case 'home':
						$tabUrl = "/index.php?v=m&p=home";
						break;
					case 'health':
						$tabUrl = "/index.php?v=m&p=health";
						break;
					case 'timeline':
						$tabUrl = "/index.php?v=m&p=timeline";
						break;
					default:
						$typeObject = $objectId;
						$typewebviewurl = 'm';
						$typeobjectId = '';
						$tabUrl = '/index.php?v=m&app_mode=1';
						break;
				}
			}
		} elseif ($objectId == 'url') {
			$typeObject = $objectId;
			$typewebviewurl = $webviewUrl;
			$typeobjectId = 'url';
			$tabUrl = $menuCustomArray['urlUser'];
		} else {
			$typeObject = $objectId;
			$typewebviewurl = 'm';
			$typeobjectId = '';
			$tabUrl = '/index.php?v=m&app_mode=1';
		}

		$result['typeObject'] = $typeObject;
		$result['typewebviewurl'] = $typewebviewurl;
		$result['typeobjectId'] = $typeobjectId;
		$result['tabUrl'] = $tabUrl;

		return $result;
	}

	/**
	 * Create and update cmd
	 * Call by Api : nfc && qrcodemethod
	 */
	public static function cmdForApi($Iq, $logicalId, $value, $name = "", $subtype = "string", $_visible = 0)
	{
		$mobile = eqLogic::byLogicalId($Iq, 'mobile');
		if (is_object($mobile)) {
			$order = count($mobile->getCmd());
			$cmd = $mobile->getCmd(null, $logicalId);
			if (!is_object($cmd)) {
				if ($name == "") {
					$name = $logicalId;
				}
				$cmd = new mobileCmd();
				$cmd->setLogicalId($logicalId);
				$cmd->setName($name);
				$cmd->setOrder($order);
				$cmd->setDisplay('icon', '<i class="icon  fas fa-qrcode"></i>');
				$cmd->setIsVisible($_visible);
				if (in_array($logicalId, array('barrecodemethod', 'nfcPayload', 'nfcId'))) {
					$cmd->setConfiguration('repeatEventManagement', 'always');
				}
			}
			$cmd->setEqLogic_id($mobile->getId());
			$cmd->setType('info');
			$cmd->setSubType($subtype);
			if ($cmd->getChanged() === true) $cmd->save();
			$mobile->checkAndUpdateCmd($logicalId, $value);
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	/**
	 * Call by Api : getJson && getCustomMenu
	 *
	 * @return array
	 */
	public function configMenuCustom()
	{
		log::add('mobile', 'debug', '|┌──:fg-success: CONFIGMENU CUSTOM JEEDOM ' . jeedom::version() . ' :/fg:──');
		$menuCustomArray = mobile::getMenuDefaultV2();
		$pluginPanelMobile = config::byKey('pluginPanelMobile', 'mobile');
		$defaultIdMobile = $this->getConfiguration('defaultIdMobile');

		if ($defaultIdMobile == 'default') {
			log::add('mobile', 'debug', '|| [WARNING] Envoi menu par défaut');
			$this->setConfiguration('menuCustomArray', $menuCustomArray);
			$this->save();
		} else if (is_object($eqDefault = eqLogic::byId($defaultIdMobile)) && $this->getId() != $defaultIdMobile) {
			log::add('mobile', 'debug', '|| [WARNING] Envoi menu de ' . $eqDefault->getHumanName());
			$menuCustomArray = $eqDefault->getConfiguration('menuCustomArray');
			$this->setConfiguration('menuCustomArray', $menuCustomArray);
			$this->save();
		} else {
			log::add('mobile', 'debug', '|| [INFO] Envoi menu de ' . $this->getHumanName());
			$menuCustomArray = $this->getConfiguration('menuCustomArray');
		}
		if (empty($menuCustomArray)) {
			$menuCustomArray = mobile::getMenuDefaultV2();
		}
		$nbIcones = count($menuCustomArray);
		// ATTRIBUTION MOBILE PAR DEFAUT A TOUS LES MOBILES
		$eqLogics = eqLogic::byType('mobile');
		foreach ($eqLogics as $mobile) {
			if ($mobile->getConfiguration('defaultIdMobile') == $this->getId()) {
				$mobile->setConfiguration('menuCustomArray', $menuCustomArray);
				$mobile->setConfiguration('nbIcones', $nbIcones);
				$mobile->save();
			};
		}

		$arrayElements = array();
		$j = 0;
		$count = 1;
		for ($i = 1; $i < 9; $i++) {

			// GENERATE TAB ICON LIBRARY AND RENAME BY USER
			$resultTabIcon = self::generateTabIcon($menuCustomArray, $i);
			$tabIconName = $resultTabIcon['tabIconName'];
			$tabLibName = $resultTabIcon['tabLibName'];
			$tabRenameInput = $resultTabIcon['tabRenameInput'];
			//$objectId = $menuCustomArray[$i]['selectNameMenu'];
			//$objectId = isset($menuCustomArray[$i]['selectNameMenu']) ? $menuCustomArray[$i]['selectNameMenu'] : '';
			$isActive = true;
			//$webviewUrl = 'd';
			//if (!empty($objectId)) log::add('mobile', 'debug', '|| - objectId > ' . $objectId);

			// GENERATE URLS FOR MENU CUSTOM 
			//$result = self::generateTypeObject($objectId, $i, $webviewUrl, $pluginPanelMobile);
			$result = self::generateTypeObject($pluginPanelMobile, isset($menuCustomArray[$i]) ? $menuCustomArray[$i] : '');
			$typeObject = $result['typeObject'];
			$typewebviewurl = $result['typewebviewurl'];
			$typeobjectId = $result['typeobjectId'];
			$tabUrl = $result['tabUrl'];

			if ($count > intval($nbIcones)) {
				$isActive = false;
			}

			$jsonTemplate = array(
				'active' => $isActive,
				'icon' => [
					'name' => $tabIconName,
					'type' => $tabLibName
				],
				'name' => $tabRenameInput,
				'options' => [
					'uri' => $tabUrl,
					'objectType' => $typeObject,
					'mobile' => $typewebviewurl,
					'objectId' => $typeobjectId
				],
				'type' =>  strpos($tabUrl, 'www') !== false ? 'urlwww' : 'WebviewApp'
			);
			$arrayElements['tab' . $j] =  $jsonTemplate;
			$j++;
			$count++;
		}
		log::add('mobile', 'debug', '|| [INFO] arrayElements > ' . json_encode($arrayElements));
		log::add('mobile', 'debug', '|└────────────────────');
		return $arrayElements;
	}

	/**
	 * get QrCode base64 for a user
	 * use lib phpqrcode
	 * Call by ajax getQrCode for printEqLogic
	 * @return string
	 */
	public function getQrCode()
	{
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
		$imageString = base64_encode(ob_get_contents());
		ob_end_clean();
		return $imageString;
	}

	/**
	 * get QrCode base64 for a user
	 * use lib phpqrcode
	 * Call by ajax getQrCodeV2 for modal.qrcodev2
	 * @return string
	 */
	public static function getQrCodeV2($userId)
	{
		require_once dirname(__FILE__) . '/../../3rdparty/phpqrcode/qrlib.php';
		$interne = network::getNetworkAccess('internal');
		$externe = network::getNetworkAccess('external');
		if ($interne == null || $interne == 'http://:80' || $interne == 'https://:80') {
			return 'internalError';
		}
		if ($externe == null || $externe == 'http://:80' || $externe == 'https://:80') {
			return 'externalError';
		}
		if (!is_object(user::byId($userId))) {
			return 'UserError';
		}
		$request_qrcode = array(
			'url_internal' => $interne,
			'url_external' => $externe
		);
		if (is_object($user = user::byId($userId))) {
			$request_qrcode['username'] = $user->getLogin();
			$request_qrcode['apikey'] = $user->getHash();
		}
		ob_start();
		QRcode::png(json_encode($request_qrcode));
		$imageString = base64_encode(ob_get_contents());
		ob_end_clean();
		return $imageString;
	}

	/**
	 * Create and update cmd for SpecificChannel
	 * Call by Api : mobile::geoloc && methodeForSpecificChannel
	 */
	public function cmdForSpecificChannel($params = array(), $_trigger = 'location')
	{
		if (isset($params['Iq'])) {
			if (isset($params[$_trigger])) {
				$order = count($this->getCmd());
				// Battery
				if (isset($params[$_trigger]['battery'])) {
					// level
					if (isset($params[$_trigger]['battery']['level'])) {
						$cmd = $this->getCmd(null, 'phoneBattery');
						if (!is_object($cmd)) {
							$cmd = new mobileCmd();
							$cmd->setLogicalId('phoneBattery');
							$cmd->setName(__('Batterie du téléphone', __FILE__));
							$cmd->setDisplay('icon', '<i class="icon fas fa-battery-three-quarters"></i>');
							$cmd->setDisplay('showIconAndNamedashboard', 1);
							$cmd->setDisplay('showIconAndNamemobile', 1);
							$cmd->setDisplay('forceReturnLineAfter', 1);
							$cmd->setConfiguration('historizeRound', 2);
							$cmd->setConfiguration('minValue', 0);
							$cmd->setConfiguration('maxValue', 100);
							$cmd->setUnite('%');
							$cmd->setIsVisible(0);
							$cmd->setOrder($order);
							$order++;
							log::add('mobile', 'debug', 'Creation commande Batterie du téléphone');
						}
						$cmd->setEqLogic_id($this->getId());
						$cmd->setType('info');
						$cmd->setSubType('numeric');
						if ($cmd->getChanged() === true) $cmd->save();
						log::add('mobile', 'debug', '|  OK  phoneBattery = ' . $params[$_trigger]['battery']['level'] * 100);
						$this->checkAndUpdateCmd('phoneBattery', $params[$_trigger]['battery']['level'] * 100);
					}
					// charging
					if (isset($params[$_trigger]['battery']['is_charging'])) {
						$cmd = $this->getCmd(null, 'phoneCharging');
						if (!is_object($cmd)) {
							$cmd = new mobileCmd();
							$cmd->setLogicalId('phoneCharging');
							$cmd->setName(__('En charge', __FILE__));
							$cmd->setDisplay('icon', '<i class="icon techno-charging"></i>');
							$cmd->setDisplay('showIconAndNamedashboard', 1);
							$cmd->setDisplay('showIconAndNamemobile', 1);
							$cmd->setDisplay('forceReturnLineAfter', 1);
							$cmd->setTemplate('dashboard', 'core::line');
							$cmd->setTemplate('mobile', 'core::line');
							$cmd->setIsVisible(0);
							$cmd->setOrder($order);
							$order++;
							log::add('mobile', 'debug', 'Creation commande En charge');
						}
						$cmd->setEqLogic_id($this->getId());
						$cmd->setType('info');
						$cmd->setSubType('binary');
						if ($cmd->getChanged() === true) $cmd->save();
						log::add('mobile', 'debug', '|  OK  phoneCharging = ' . intval($params[$_trigger]['battery']['is_charging']));
						$this->checkAndUpdateCmd('phoneCharging', intval($params[$_trigger]['battery']['is_charging']));
					}
				}
				// coords
				if (isset($params[$_trigger]['coords'])) {
					if (isset($params[$_trigger]['coords']['latitude']) && isset($params[$_trigger]['coords']['longitude'])) {
						$cmd = $this->getCmd(null, 'coords');
						if (!is_object($cmd)) {
							$cmd = new mobileCmd();
							$cmd->setLogicalId('coords');
							$cmd->setName(__('Coordonnées', __FILE__));
							$cmd->setDisplay('icon', '<i class="icon fas fa-map-marker-alt"></i>');
							$cmd->setDisplay('showIconAndNamedashboard', 1);
							$cmd->setDisplay('showIconAndNamemobile', 1);
							$cmd->setDisplay('forceReturnLineAfter', 1);
							$cmd->setTemplate('dashboard', 'core::line');
							$cmd->setTemplate('mobile', 'core::line');
							$cmd->setIsVisible(0);
							$cmd->setOrder($order);
							$order++;
							log::add('mobile', 'debug', 'Create cmd for coords');
						}
						$cmd->setEqLogic_id($this->getId());
						$cmd->setType('info');
						$cmd->setSubType('string');
						if ($cmd->getChanged() === true) $cmd->save();
						log::add('mobile', 'debug', '|  OK  coords = ' . $params[$_trigger]['coords']['latitude'] . ',' . $params[$_trigger]['coords']['longitude']);
						$this->checkAndUpdateCmd('coords', $params[$_trigger]['coords']['latitude'] . ',' . $params[$_trigger]['coords']['longitude']);
					}
				}
			}
		}
	}

	/**
	 * cleaning notifications based on time retention
	 * Call by cronDaily
	 */
	public function cleaningNotifications()
	{
		$notifsTime = $this->getConfiguration('notifsTime', 30);
		log::add('mobile', 'debug', '┌──────────▶︎ :fg-warning: Nettoyage des Notifications et Images :/fg: ──────────');
		log::add('mobile', 'debug', '| Durée de retention actuelle : ' . $notifsTime . ' jours');
		// Images
		$retentionSeconds = intVal($notifsTime) * 24 * 60 * 60;
		$currentTime = time();
		$pathImages = dirname(__FILE__) . '/../../data/images/';
		if (is_dir($pathImages)) {
			$images = glob($pathImages . '*.jpg');
			foreach ($images as $image) {
				if (strpos($image, $this->getLogicalId()) !== false) {
					$fileCreationTime = filemtime($image);
					if ($fileCreationTime < ($currentTime - $retentionSeconds)) {
						if (!unlink($image)) {
							log::add('mobile', 'error', 'Erreur lors de la suppression de: ' . $image);
						} else {
							log::add('mobile', 'debug', '| ─▶︎ :fg-danger:suppression image:/fg: > ' . $image);
						}
					}
				}
			}
		}
		// Notifications
		$filePath = dirname(__FILE__) . '/../data/notifications/' . $this->getLogicalId() . '.json';
		$notifications = 'noNotifications';
		if (file_exists($filePath)) {
			$notifications = file_get_contents($filePath);
			if ($notifications) {
				$notifications = json_decode($notifications, true);
				if (json_last_error() !== JSON_ERROR_NONE) {
					log::add('mobile', 'error', 'Erreur decodage du JSON : ' . json_last_error_msg());
					return;
				}
				$notificationsModified = false;

				foreach ($notifications as $id => $value) {
					$notificationDate = strtotime($value['data']['date']);
					if (($currentTime - $notificationDate) > $retentionSeconds) {
						log::add('mobile', 'debug', '| ─▶︎ :fg-danger:suppression notification:/fg: > N°' . $id);
						unset($notifications[$id]);
						$notificationsModified = true;
					}
				}
				$notifications = json_encode($notifications);
				if ($notificationsModified) {
					file_put_contents($filePath, $notifications);
				}
			}
		}
		log::add('mobile', 'debug', '└───────────────────────────────────────────');
	}

	/**
	 * Call by core after insert into bdd
	 */
	public function postInsert()
	{
		if ($this->getLogicalId() == '') {
			$key = config::genKey(32);
			$this->setLogicalId($key);
		}
		if ($this->getConfiguration('appVersion', 1) == 2) {
			$this->setConfiguration('defaultIdMobile', $this->getId());
		}
		$this->save();
	}

	/**
	 * Call by core after save into bdd
	 */
	public function postSave()
	{
		$order = count($this->getCmd());

		// Commande notification
		$cmd = $this->getCmd(null, 'notif');
		if (!is_object($cmd)) {
			$cmd = new mobileCmd();
			$cmd->setIsVisible(1);
			$cmd->setName(__('Notification', __FILE__));
			$cmd->setLogicalId('notif');
			$cmd->setGeneric_type('GENERIC_ACTION');
			$cmd->setDisplay('icon', '<i class="icon far fa-comment"></i>');
			$cmd->setDisplay('forceReturnLineAfter', 1);
			$cmd->setDisplay('showIconAndNamedashboard', 1);
			$cmd->setDisplay('showIconAndNamemobile', 1);
			$cmd->setOrder($order);
			$order++;
		}
		$cmd->setEqLogic_id($this->getId());
		$cmd->setType('action');
		$cmd->setSubType('message');
		if ($cmd->getChanged() === true) $cmd->save();

		if ($this->getConfiguration('appVersion', 1) == 2) {
			// Commande notification Critique
			$cmd = $this->getCmd(null, 'notifCritical');
			if (!is_object($cmd)) {
				$cmd = new mobileCmd();
				$cmd->setIsVisible(1);
				$cmd->setName(__('Notification Critique', __FILE__));
				$cmd->setLogicalId('notifCritical');
				$cmd->setGeneric_type('GENERIC_ACTION');
				$cmd->setDisplay('icon', '<i class="icon far fa-comment"></i>');
				$cmd->setDisplay('forceReturnLineAfter', 1);
				$cmd->setDisplay('showIconAndNamedashboard', 1);
				$cmd->setDisplay('showIconAndNamemobile', 1);
				$cmd->setOrder($order);
				$order++;
			}
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('action');
			$cmd->setSubType('message');
			if ($cmd->getChanged() === true) $cmd->save();

			$cmd = $this->getCmd(null, 'notifSilent');
			if (!is_object($cmd)) {
				$cmd = new mobileCmd();
				$cmd->setIsVisible(1);
				$cmd->setName(__('Notification Silencieuse', __FILE__));
				$cmd->setLogicalId('notifSilent');
				$cmd->setGeneric_type('GENERIC_ACTION');
				$cmd->setDisplay('icon', '<i class="icon far fa-comment"></i>');
				$cmd->setDisplay('forceReturnLineAfter', 1);
				$cmd->setDisplay('showIconAndNamedashboard', 1);
				$cmd->setDisplay('showIconAndNamemobile', 1);
				$cmd->setOrder($order);
				$order++;
			}
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('action');
			$cmd->setSubType('message');
			if ($cmd->getChanged() === true) $cmd->save();

			// Commande récupération infos du téléphone
			$cmd = $this->getCmd(null, 'notifSpecific');
			if (!is_object($cmd)) {
				$cmd = new mobileCmd();
				$cmd->setIsVisible(0);
				$cmd->setName(__('Récupérer les informations du téléphone', __FILE__));
				$cmd->setLogicalId('notifSpecific');
				$cmd->setGeneric_type('GENERIC_ACTION');
				$cmd->setDisplay('icon', '<i class="icon jeedomapp-reload"></i>');
				$cmd->setDisplay('forceReturnLineAfter', 1);
				$cmd->setDisplay('showIconAndNamedashboard', 1);
				$cmd->setDisplay('showIconAndNamemobile', 1);
				$cmd->setOrder($order);
				$order++;
			}
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('action');
			$cmd->setSubType('other');
			if ($cmd->getChanged() === true) $cmd->save();

			// Commande suppression des nodifications
			$cmd = $this->getCmd(null, 'removeNotifs');
			if (!is_object($cmd)) {
				$cmd = new mobileCmd();
				$cmd->setIsVisible(1);
				$cmd->setName(__('Supprimer les Notifications', __FILE__));
				$cmd->setLogicalId('removeNotifs');
				$cmd->setGeneric_type('GENERIC_ACTION');
				$cmd->setDisplay('icon', '<i class="icon fas fa-trash icon_red"></i>');
				$cmd->setDisplay('forceReturnLineAfter', 1);
				$cmd->setDisplay('showIconAndNamedashboard', 1);
				$cmd->setDisplay('showIconAndNamemobile', 1);
				$cmd->setOrder($order);
				$order++;
			}
			$cmd->setEqLogic_id($this->getId());
			$cmd->setType('action');
			$cmd->setSubType('select');
			$listValue = "1|" . __('Supprimer toutes les Notifications', __FILE__) . ";2|" . __('Supprimer les Asks expirés', __FILE__) . ";3|" . __('Supprimer les Asks répondus', __FILE__);
			$cmd->setConfiguration('listValue', $listValue);
			if ($cmd->getChanged() === true) $cmd->save();
		}

		if ($this->getConfiguration('appVersion', 1) != 2) {
			$cmdaskText = $this->getCmd(null, 'ask_Text');
			if (is_object($cmdaskText)) {
				$cmdaskText->remove();
			}
			$cmdaskYN = $this->getCmd(null, 'ask_YN');
			if (is_object($cmdaskYN)) {
				$cmdaskYN->remove();
			}
		}
	}

	/**
	 * Call by core before remove eqLogic
	 */
	public function preRemove()
	{
		log::add('mobile', 'debug', '┌──:fg-success: preRemove() :/fg:──');
		$Iq = $this->getId();
		/* App V2 */
		foreach (eqLogic::byType('mobile') as $mobile) {
			if ($Iq == $mobile->getId()) continue;
			if ($mobile->getConfiguration('defaultIdMobile', 'none') == $Iq) {
				$mobile->setConfiguration('defaultIdMobile', $mobile->getId());
				$mobile->save();
				log::add('mobile', 'debug', '| Modification du defaultIdMobile pour le mobile ' . $mobile->getHumanName(false) . ' ( ' . $mobile->getId() . ' ) ');
			}
		}
		$fileNotif = dirname(__FILE__) . '/../data/notifications/' . $this->getLogicalId() . '.json';
		if (file_exists($fileNotif)) {
			log::add('mobile', 'debug', '| Suppression du fichier des notifications : ' . $fileNotif);
			shell_exec('rm ' . $fileNotif);
		}
		/* App V1 */
		$path = dirname(__FILE__) . '/../../data/' . $this->getLogicalId();
		if (file_exists($path)) {
			log::add('mobile', 'debug', '| Suppression du dossier : ' . $path);
			shell_exec('rm -rf ' . $path);
		}
		log::add('mobile', 'debug', '└────────────────────');
	}

	/**
	 * Call by core after remove eqLogic
	 */
	/*
	public function postRemove() {

	}
	*/

	/*     * **********************Getteur Setteur*************************** */
}

class mobileCmd extends cmd
{
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	/*public function dontRemoveCmd() {
		return true;
	}*/

	public static function fileInMessage($data)
	{
		log::add('mobile', 'debug', '|┌──:fg-success: fileInMessage :/fg:──');
		$dataArray = explode('|', $data);
		$result = array();
		foreach ($dataArray as $item) {

			$arg = explode('=', trim($item), 2);
			if (count($arg) == 2) {
				$result[trim($arg[0])] = trim($arg[1]);
			}
		}
		$result['message'] = $dataArray[0];
		$decodedMessage = json_decode($result['message']);
		if (json_last_error() === JSON_ERROR_NONE) {
			log::add('mobile', 'DEBUG', '|| [INFO] Message : ' . $decodedMessage);
		} else {
			log::add('mobile', 'DEBUG', '|| [INFO] Message : ' . $result['message']);
		}
		if (array_key_exists('file', $result)) {
			log::add('mobile', 'debug', '|| file > ' . $result['file']);
			log::add('mobile', 'debug', '|└────────────────────');
			return $result;
		} else {
			log::add('mobile', 'debug', '|└────────────────────');
			return null;
		}
	}

	public function execute($_options = array())
	{
		if ($this->getType() != 'action') {
			return;
		}
		log::add('mobile', 'debug', '┌──:fg-success: execute :/fg:──');
		$optionsNotif = [];
		$eqLogic = $this->getEqLogic();
		$Iq = $eqLogic->getLogicalId();

		if ($this->getLogicalId() == 'removeNotifs') {
			$filePath = dirname(__FILE__) . '/../data/notifications/' . $Iq . '.json';
			if (!file_exists($filePath)) log::add('mobile', 'info', '| Fichier de notifications non trouvé : ' . $filePath);
			$valueUser = $_options['select'];
			switch ($valueUser) {
				case 1:
					file_put_contents($filePath, '');
					log::add('mobile', 'info', '| Suppression des notifications effectuée');
					break;
				case 2:
					$notifs = json_decode(file_get_contents($filePath), true);
					$notifs = array_filter($notifs, function ($notif) {
						$askParams = json_decode($notif['data']['askParams'], true);
						$notifTime = strtotime($notif['data']['date']);
						$currentTime = time();
						$timeout = $askParams['timeout'] / 1000;
						return $notif['data']['askVariable'] == 'rien' || ($currentTime - $notifTime) < $timeout;
					});
					file_put_contents($filePath, json_encode($notifs));
					log::add('mobile', 'info', '| Suppression des asks expirés effectuée');
					break;
				case 3:
					$notifs = json_decode(file_get_contents($filePath), true);
					$notifs = array_filter($notifs, function ($notif) {
						return $notif['data']['choiceAsk'] == '';
					});
					file_put_contents($filePath, json_encode($notifs));
					log::add('mobile', 'info', '| Suppression des asks répondus effectuée');
					break;
			}

			log::add('mobile', 'debug', '└────────────────────');
		}

		if ($this->getLogicalId() == 'notif' || $this->getLogicalId() == 'notifCritical' || $this->getLogicalId() == 'notifSpecific' || $this->getLogicalId() == 'notifSilent') {
			$critical = false;
			$silent = false;
			$specific = false;
			$defaultName = empty(config::byKey('name')) ? config::byKey('product_name') : config::byKey('name');
			if ($this->getLogicalId() == 'notifCritical') {
				$critical = true;
			}
			if ($this->getLogicalId() == 'notifSilent') {
				$silent = true;
			}
			if ($this->getLogicalId() == 'notifSpecific') {
				$specific = true;
				$_options['title'] = 'getDeviceInformations';
				$_options['message'] = 'getDeviceInformations';
			}
			if (trim($_options['title']) == '') $_options['title'] = $defaultName;
			$file = mobileCmd::fileInMessage($_options['message']);
			if (!isset($_options['files']) && $file != null) {
				$_options['files'] = array();
				array_push($_options['files'], $file['file']);
				$_options['message'] = $file['message'];
				log::add('mobile', 'debug', '| file detected ' . json_encode($file));
			}
			log::add('mobile', 'DEBUG', '| [INFO] Title : ' . $_options['title']);
			log::add('mobile', 'DEBUG', '| [INFO] Message : ' . $_options['message']);
			if ($eqLogic->getConfiguration('type_mobile') == 'android') $_options['message'] = nl2br($_options['message']);
			$answer = (isset($_options['answer']) && $_options['answer']) ? join(';', $_options['answer']) : null;
			$askVariable = isset($_options['variable']) ? $_options['variable'] : null;
			$askType = isset($_options['answer']) && $_options['answer'] ? 'ask_Text' : 'notif';
			if ($askType == 'ask_Text') $_options['title'] = $defaultName;
			$timeout = isset($_options['timeout']) && $_options['timeout'] ? $_options['timeout'] : 'nok';
			$optionsNotif['askVariable'] = $askVariable;
			//log::add('mobile', 'debug', '|-----------------------------------');
			log::add('mobile', 'debug', '| Commande de notification : ' . $askType);
			if (($eqLogic->getConfiguration('notificationArn', null) != null || $eqLogic->getConfiguration('notificationRegistrationToken', null) != null) && $eqLogic->getConfiguration('type_mobile', null) != null) {
				$idNotif = $eqLogic->getConfiguration('idNotif', 0);
				$idNotif = $idNotif + 1;
				$eqLogic->setConfiguration('idNotif', $idNotif);
				$eqLogic->save();

				log::add('mobile', 'debug', '| [INFO] Notif > ' . json_encode($_options));
				log::add('mobile', 'debug', '| eqLogic > ' . $eqLogic->getId() . ' | LogicalId > ' . $this->getLogicalId() . ' | idNotif > ' . $idNotif);
				if (isset($options['file'])) {
					//log::add('mobile', 'debug', '| [NOTICE] FILE');
					//unset($data['file']);
					$_options['files'] = explode(',', $options['file']);
				}
				if (isset($_options['files']) && is_array($_options['files'])) {
					log::add('mobile', 'debug', '| [NOTICE] FILE');
					foreach ($_options['files'] as $file) {
						log::add('mobile', 'debug', '| FILES as FILE');
						if (trim($file) == '') {
							continue;
						}
						$ext = pathinfo($file, PATHINFO_EXTENSION);
						log::add('mobile', 'debug', '| ' . $ext . ' pour > ' . $file);
						if (in_array($ext, array('gif', 'jpeg', 'jpg', 'png'))) {
							log::add('mobile', 'debug', '| type photo !');
							if ($ext == "gif") {
								$typeHint = "com.compuserve.gif";
							} else if ($ext == "jpeg") {
								$typeHint = "public.jpeg";
							} else if ($ext == "jpg") {
								$typeHint = "public.jpeg";
							} else if ($ext == "png") {
								$typeHint = "public.png";
							} else {
								$typeHint = "public.jpeg";
							}
							$optionsNotif['typeHint'] = $typeHint;
							$url = network::getNetworkAccess('external');
							$url .= '/plugins/mobile/core/php/image.php?';
							$nameFile = $Iq . '__' . base64_encode($file) . '.' . $ext;
							$path = dirname(__FILE__) . '/../../data/images';
							$newfile = $path . '/' . $nameFile;
							log::add('mobile', 'debug', '| copie sur > ' . $newfile);
							if (!file_exists($path)) {
								mkdir($path);
							}
							if (!copy($file, $newfile)) {
								log::add('mobile', 'error', 'la copie de l\'image a echoué');
							}
							$keyFile = md5_file($newfile);
							$url .= 'key=' . $keyFile . '&name=' . $nameFile;
							log::add('mobile', 'debug', '| url > ' . $url);
							mobile::notification($eqLogic->getConfiguration('notificationArn', null), $eqLogic->getConfiguration('type_mobile', null), $_options['title'], $_options['message'], $askType, $idNotif, $answer, $timeout, $eqLogic->getConfiguration('notificationRegistrationToken', null), $url, $eqLogic->getConfiguration('appVersion', 1), $optionsNotif, $critical, $eqLogic->getLogicalId(), $specific, $silent);
						} else {
							mobile::notification($eqLogic->getConfiguration('notificationArn', null), $eqLogic->getConfiguration('type_mobile', null), $_options['title'], $_options['message'], $askType, $idNotif, $answer, $timeout, $eqLogic->getConfiguration('notificationRegistrationToken', null), null, $eqLogic->getConfiguration('appVersion', 1), $optionsNotif, $critical, $eqLogic->getLogicalId(), $specific, $silent);
						}
					}
				} else {
					mobile::notification($eqLogic->getConfiguration('notificationArn', null), $eqLogic->getConfiguration('type_mobile', null), $_options['title'], $_options['message'], $askType, $idNotif, $answer,  $timeout, $eqLogic->getConfiguration('notificationRegistrationToken', null), null, $eqLogic->getConfiguration('appVersion', 1), $optionsNotif, $critical, $eqLogic->getLogicalId(), $specific, $silent);
				}
			} else {
				if ($eqLogic->getConfiguration('type_mobile', null) == null) log::add('mobile', 'debug', '| [ERROR] Type de Mobile non configuré ');
				else log::add('mobile', 'debug', '| [ERROR] ARN non configuré ');
			}
			log::add('mobile', 'debug', '└────────────────────');
		}
	}

	/*     * **********************Getteur Setteur*************************** */
}
