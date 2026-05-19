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

header('Content-Type: application/json');

require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
global $jsonrpc;
global $_USER_GLOBAL;
if (!is_object($jsonrpc)) {
	throw new Exception(__('JSONRPC object not defined', __FILE__), -32699);
}

$params = $jsonrpc->getParams();
log::add('mobile', 'debug', '┌──────────▶︎:fg-warning: Call API :/fg:◀︎─────────────');
log::add('mobile', 'debug', '| Method ─▶︎ ' . $jsonrpc->getMethod());
$secureApikeyLog = $params;
if (isset($secureApikeyLog['apikey'])) $secureApikeyLog['apikey'] = substr($secureApikeyLog['apikey'], 0, 10) . '...';
log::add('mobile', 'debug', '| Parameters ─▶︎ ' . json_encode($secureApikeyLog));
if ($params['Iq']) {
	if (mobile::whoIsIq($params['Iq']) == 'Mobile not detected') {
		log::add('mobile', 'debug', '| [WARNING] Mobile not detected !');
	} else {
		log::add('mobile', 'debug', '| Mobile : ' . mobile::whoIsIq($params['Iq']));
	}
} else {
	log::add('mobile', 'debug', '| [WARNING] Parameter Iq does not exist !');
}
log::add('mobile', 'debug', '└───────────────────────────────────────────');

// APP V2 //
/**
 * Create a new equipment
 * Call by api setConfigs
 * @param array 
 * @return new object
 */
function createMobileV2($params, $nbIcones = 3)
{
	log::add('mobile', 'debug', '|┌──:fg-success: createMobileV2 :/fg:──');
	$configs = $params['configs'];
	$notification = $configs['notification'];
	$user = user::byHash($params['apikey']);
	$userId = $user->getId();
	$mobile = new mobile();
	$mobile->setEqType_name('mobile');
	$mobile->setName($notification['platform'] . '-' . $params['Iq']);
	log::add('mobile', 'debug', '|| [NOTICE] Create new mobile with ' . $nbIcones . ' icons ----');
	$mobile->setConfiguration('menuCustomArray', mobile::getMenuDefaultV2($nbIcones));
	$mobile->setConfiguration('nbIcones', intval($nbIcones));
	$mobile->setConfiguration('type_mobile', $notification['platform']);
	$mobile->setConfiguration('affect_user', $userId);
	$mobile->setConfiguration('validate', 'no');
	$mobile->setConfiguration('appVersion', '2');
	$mobile->setLogicalId($params['Iq']);
	$mobile->setIsEnable(1);
	$mobile->save();
	log::add('mobile', 'debug', '|└───────────────────');
	return $mobile;
}

/**
 * Save menu from app
 * Call by setCustomMenu
 * @param array
 */
function saveMenuFromAppV2($menu, $mobile)
{
	log::add('mobile', 'debug', '||┌──:fg-success: saveMenuFromAppV2 :/fg:──');
	log::add('mobile', 'debug', '||| [INFO] Menu > ' . json_encode($menu));
	if (is_object($mobile)) {
		$menuCustomArray = [];
		$count = 0;
		$i = 1;
		foreach ($menu as $key => $value) {
			if (isset($value['active']) && $value['active'] === true) {
				$count++;
				$excludedArray = ['dashboard', 'views', 'plan', 'panel'];
				if (!in_array($value['options']['objectType'], $excludedArray)) {
					$menuCustomArray[$i]['selectNameMenu'] = $value['options']['objectType'];
					if ($value['options']['objectType'] == 'url') {
						if ($value['options']['objectId'] != '') {
							$menuCustomArray[$i]['urlUser'] = $value['options']['objectId'];
						} else {
							$menuCustomArray[$i]['urlUser'] = 'https://www.jeedom.com/fr/';
						}
					}
				} else {
					$menuCustomArray[$i]['selectNameMenu'] =  $value['options']['objectId'] . '_' . $value['options']['objectType'];
				}
				$menuCustomArray[$i]['renameIcon'] =  $value['name'];
				$menuCustomArray[$i]['spanIcon'] = 'icon ' . $value['icon']['type'] . '-' . $value['icon']['name'];
			}
			$i++;
		}
		$mobile->setConfiguration('menuCustomArray', $menuCustomArray);
		$mobile->setConfiguration('nbIcones', $count);
		$mobile->setConfiguration('defaultIdMobile', $mobile->getId());
		$mobile->save();
	}
	log::add('mobile', 'debug', '||└───────────────────');
}

/**
 * Create equipment if no exist
 * Create/update cmd for geoloc if no exist
 * Save notificationRegistrationToken
 * 
 * @return string ok makeSuccess
 */
if ($jsonrpc->getMethod() == 'setConfigs') {
	log::add('mobile', 'debug', '┌──────────▶︎ setConfigs ──────────────');
	$configs = $params['configs'];
	$geolocs = $params['geolocs'];
	$menu = $configs['menu'];
	$notification = $configs['notification'];
	log::add('mobile', 'debug', '| [INFO] Configs ─▶︎ ' . json_encode($configs));
	log::add('mobile', 'debug', '| [INFO] Geolocs ─▶︎ ' . json_encode($geolocs));
	//log::add('mobile', 'debug', '| [INFO] Menu ─▶︎ ' . json_encode($menu));
	log::add('mobile', 'debug', '| [INFO] Notification ─▶︎ ' . json_encode($notification));
	$mobile = null;
	if (isset($params['Iq'])) {
		$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
		if (!is_object($mobile)) {
			$mobile = createMobileV2($params, 3);
		}
	}
	if (is_object($mobile)) {
		$mobile->setConfiguration('type_mobile', $notification['platform']);
		if (isset($notification['token'])) {
			if ($notification['token'] != '') {
				log::add('mobile', 'debug', '| Token to add ─▶︎ ' . $notification['token']);
				$mobile->setConfiguration('notificationRegistrationToken', $notification['token']);
				if ($notification['token'] == 'notifsBGDisabled') {
					message::removeAll("mobile", 'alertNotifs');
					message::add('mobile', __('Les notifications sur votre mobile sont desactivées', __FILE__) . ' ─▶︎ ' . $mobile->getName(), 'notifsbg', 'alertNotifs');
				}
			} else {
				log::add('mobile', 'debug', '| [NOTICE] Empty token');
			}
		}
		if (isset($notification['notifsTime'])) {
			$notifsTime = intval($notification['notifsTime']);
			if ($mobile->getConfiguration('notifsTime', 30) != $notifsTime) {
				$mobile->setConfiguration('notifsTime', $notifsTime);
				log::add('mobile', 'debug', '| [INFO] New notifsTime ─▶︎ ' . intval($notification['notifsTime']));
				$mobile->cleaningNotifications();
			}
		}
		if (is_object($user = user::byHash($params['apikey']))) {
			log::add('mobile', 'debug', '| [INFO] affect_user ─▶︎ ' . $user->getLogin() . ' (' . $user->getId() . ')');
			$mobile->setConfiguration('affect_user', $user->getId());
		}
		$mobile->save();

		if ($geolocs) {
			if ($geolocs != [] && !(is_object($geolocs) && empty((array)$geolocs)) && !(is_string($geolocs) && $geolocs == "{}")) {
				mobile::createCmdGeoLocV2($params['Iq'], $params['geolocs']);
			} else {
				log::add('mobile', 'debug', '| Geolocs empty, previous commands deleted');
				$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
				if (is_object($mobile)) {
					$cmds = $mobile->getCmd();
					foreach ($cmds as $cmd) {
						if (strpos($cmd->getLogicalId(), 'geoloc_') !== false) {
							$cmd->remove();
						}
					}
				}
			}
		}
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess('ok');
}

/**
 * Save menu from app
 * 
 * @return string ok makeSuccess
 */
if ($jsonrpc->getMethod() == 'setCustomMenu') {
	log::add('mobile', 'debug', '┌──────────▶︎ AppV2 setCustomMenu ───────────');
	$configs = $params['configs'];
	$menu = $configs['menu'];
	log::add('mobile', 'debug', '| [INFO] Configs ─▶︎ ' . json_encode($configs));
	log::add('mobile', 'debug', '| [INFO] Menu ─▶︎ ' . json_encode($menu));
	$mobile = null;
	if (isset($params['Iq'])) {
		if (is_object($mobile = eqLogic::byLogicalId($params['Iq'], 'mobile'))) {
			saveMenuFromAppV2($menu, $mobile);
		} else {
			log::add('mobile', 'debug', '| [WARNING] A required parameter Iq does not exist !');
		}
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess('ok');
}


/**
 * getPlugins
 * 
 * @return array makeSuccess
 */
if ($jsonrpc->getMethod() == 'getPlugins') {
	log::add('mobile', 'debug', '┌──────────◀︎ AppV2 getPlugins ────────');
	$idBox = jeedom::getHardwareKey();
	$return = [];
	$arrayPlugins = [];
	$changeLogs = [];
	$deamons_infos = [];
	$objectsPanel = [];
	$pluginPanelMobile = [];
	$coreData = [];
	foreach ((plugin::listPlugin(true)) as $plugin) {
		$obArray = utils::o2a($plugin);
		$obArray['displayMobilePanel'] = config::byKey('displayMobilePanel', $plugin->getId(), 0);
		$objectId = $obArray['id'];
		$objectName = $obArray['name'];
		if ($plugin->getMobile() != '' && $obArray['displayMobilePanel'] != 0) {
			$objectsPanel[$objectId] =  $objectName;
			$pluginPanelMobile[$objectId] = $plugin->getMobile();
		}
		$update = $plugin->getUpdate();
		if (is_object($update)) {
			$pluginUpdateArray = utils::o2a($update);
			$arrayDataPlugins = utils::o2a($plugin);
			if ($plugin->getHasOwnDeamon() == 1) {
				$deamons_infos[$plugin->getId()] = $plugin->deamon_info();
			} else {
				$deamons_infos[$plugin->getId()] = array('launchable_message' => 'nodemon', 'launchable' => 'nodemon', 'state' => 'nodemon', 'log' => 'nodemon', 'auto' => 0);
			}
			$changeLogs[$arrayDataPlugins['id']]['changelog'] = $arrayDataPlugins['changelog'];
			$changeLogs[$arrayDataPlugins['id']]['changelog_beta'] = $arrayDataPlugins['changelog_beta'];
			array_push($arrayPlugins, $pluginUpdateArray);
		}
	}
	config::save('pluginPanelMobile', $pluginPanelMobile, 'mobile');
	$resultCore = utils::o2a(update::byLogicalId('jeedom'));
	array_push($coreData, $resultCore);
	$return[$idBox]['informations']['objects']['panel'] = $objectsPanel;
	$return[$idBox]['informations']['coreBranch'] = config::byKey('core::branch');
	$return[$idBox]['informations']['coreData'] = $coreData;
	$return[$idBox]['informations']['plugins'] = $arrayPlugins;
	$return[$idBox]['informations']['changelog'] = $changeLogs;
	$return[$idBox]['informations']['infosDemon'] = $deamons_infos;
	log::add('mobile', 'debug', '| [INFO] Sent to app ─▶︎ ' . json_encode($return));
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess($return);
}

/**
 * getJson
 * 
 * @return array makeSuccess
 */

if ($jsonrpc->getMethod() == 'getJson') {
	log::add('mobile', 'debug', '┌──────────◀︎ AppV2 getJson ──────────────────');
	$registerDevice = $_USER_GLOBAL->getOptions('registerDevice', array());
	if (!is_array($registerDevice)) {
		$registerDevice = array();
	}
	$rdk = (!isset($params['rdk']) || !isset($registerDevice[sha512($params['rdk'])])) ? config::genKey() : $params['rdk'];
	$registerDevice[sha512($rdk)] = array();
	$registerDevice[sha512($rdk)]['datetime'] = date('Y-m-d H:i:s');
	$registerDevice[sha512($rdk)]['ip'] = getClientIp();
	$registerDevice[sha512($rdk)]['session_id'] = session_id();
	$_USER_GLOBAL->setOptions('registerDevice', $registerDevice);
	$_USER_GLOBAL->save();
	log::add('mobile', 'debug', '| RDK ─▶︎ ' . $rdk);
	$idBox = jeedom::getHardwareKey();
	$return = array();
	/* -------- MOBILE FIRST ------- */
	$objectsDashboard = [];
	foreach (jeeObject::all() as $object) {
		$obArray = utils::o2a($object);
		$objectId = $obArray['id'];
		$objectName = $obArray['name'];
		$objectsDashboard[$objectId] =  $objectName;
	}
	$return[$idBox]['informations']['objects']['dashboard'] = $objectsDashboard;
	$objectsViews = [];
	foreach (view::all() as $object) {
		$obArray = utils::o2a($object);
		$objectId = $obArray['id'];
		$objectName = $obArray['name'];
		$objectsViews[$objectId] =  $objectName;
	}
	$return[$idBox]['informations']['objects']['views'] = $objectsViews;

	$objectsPlan = [];
	foreach (planHeader::all() as $object) {
		$obArray = utils::o2a($object);
		$objectId = $obArray['id'];
		$objectName = $obArray['name'];
		$objectsPlan[$objectId] =  $objectName;
	}
	$return[$idBox]['informations']['objects']['plan'] = $objectsPlan;
	$return[$idBox]['userRights'] = $_USER_GLOBAL->getProfils();
	$return[$idBox]['apikeyUser'] = $_USER_GLOBAL->getHash();
	$return[$idBox]['configs'] = 'undefined';
	$return[$idBox]['externalIp'] = network::getNetworkAccess('external');
	$return[$idBox]['localIp'] = network::getNetworkAccess('internal');
	$return[$idBox]['hardware'] = jeedom::getHardwareName();
	$return[$idBox]['hwkey'] = jeedom::getHardwareKey();
	$return[$idBox]['appMobile'] = '0.4';
	$return[$idBox]['ping'] = true;
	$return[$idBox]['informations']['userRights'] = $_USER_GLOBAL->getProfils();
	$return[$idBox]['informations']['hardware'] = jeedom::getHardwareName();
	$return[$idBox]['informations']['language'] = config::byKey('language');
	//$return[$idBox]['informations']['nbMessage'] = message::nbMessage();
	$userConnected = user::byHash($_USER_GLOBAL->getHash());
	if (is_object($userConnected)) {
		$return[$idBox]['informations']['userConnected'] = $userConnected->getLogin();
	}

	$categories = [];
	foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
		$categories[$value['icon']] =  $value['name'];
	}
	$return[$idBox]['informations']['objects']['categories'] = $categories;
	$return[$idBox]['informations']['nbUpdate'] = update::nbNeedUpdate();
	$return[$idBox]['informations']['uname'] = system::getDistrib() . ' ' . method_exists('system', 'getOsVersion') ? system::getOsVersion() : 'UnknownVersion';
	$return[$idBox]['jeedom_version'] = jeedom::version();
	$return[$idBox]['rdk'] = $rdk;
	$return[$idBox]['name'] = config::byKey('name') == '' ? 'Jeedom' : config::byKey('name');
	$return[$idBox]['configs'] = array();
	$return[$idBox]['miscellanousParams'] = array();
	if (is_object($mobile = eqLogic::byLogicalId($params['Iq'], 'mobile'))) {
		log::add('mobile', 'debug', '|  OK  Mobile found ─▶︎ ' . $mobile->getName());
		$return[$idBox]['configs']['menu'] = $mobile->configMenuCustom();
		$return[$idBox]['miscellanousParams']['hideMenuCustom'] = intval($mobile->getConfiguration('hideMenuCustom', 0));
		$return[$idBox]['miscellanousParams']['hideMenuGeoloc'] = intval($mobile->getConfiguration('hideMenuGeoloc', 0));
		$return[$idBox]['miscellanousParams']['sendNFCDirectly'] = intval($mobile->getConfiguration('sendNFCDirectly', 1));
	} else {
		log::add('mobile', 'debug', '| [WARNING] Mobile not found.');
		$return[$idBox]['configs']['menu'] = mobile::getMenuDefaultTab();
		$return[$idBox]['miscellanousParams']['hideMenuCustom'] = 0;
		$return[$idBox]['miscellanousParams']['hideMenuGeoloc'] = 0;
		$return[$idBox]['miscellanousParams']['sendNFCDirectly'] = 1;
	}
	log::add('mobile', 'debug', '| [INFO] Menu custom ─▶︎ ' . json_encode($return[$idBox]['configs']));
	log::add('mobile', 'debug', '| [INFO] Sent to app ─▶︎ ' . json_encode($return));
	log::add('mobile', 'debug', '└───────────────────────────────────────────');

	$jsonrpc->makeSuccess($return);
}

/**
 * return menu custom in app
 * 
 * @return array makeSuccess
 */
if ($jsonrpc->getMethod() == 'getCustomMenu') {
	log::add('mobile', 'debug', '┌──────────◀︎ AppV2 getCustomMenu ───────────');
	if (is_object($mobile = eqLogic::byLogicalId($params['Iq'], 'mobile'))) {
		log::add('mobile', 'debug', '|  OK  Mobile found ─▶︎ ' . $mobile->getName());
		$menu = $mobile->configMenuCustom();
	} else {
		log::add('mobile', 'debug', '| [WARNING] Mobile not found.');
		$menu = mobile::getMenuDefaultTab();
	}
	log::add('mobile', 'debug', '| [INFO] Sent to app ─▶︎ ' . json_encode($menu));
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess($menu);
}

/**
 * delete message jeedom by type
 * 
 * @return bool makeSuccess
 */
if ($jsonrpc->getMethod() == 'deleteAllMessageByType') {
	log::add('mobile', 'debug', '┌──────────▶︎ AppV2 deleteAllMessageByType ───────────');
	$return = "false";
	if (isset($params['appInfos']['messageType'])) {
		log::add('mobile', 'debug', '| deleting of all messages of the type "' . $params['appInfos']['messageType'] . '"');
		if ($params['appInfos']['messageType'] == 'jeedom') {
			message::removeAll('jeedom');
			$return = "true";
		} elseif ($params['appInfos']['messageType'] == 'plugins') {
			$messages = message::all();
			foreach ($messages as $message) {
				if (is_object($message)) {
					if ($message->getPlugin() != 'jeedom') {
						$message->remove();
					}
				}
			}
			$return = "true";
		}
	} else log::add('mobile', 'debug', '| A required parameter "messageType" does not exist !');
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess($return);
}

/* V1 ?
if ($jsonrpc->getMethod() == 'version') {
	$mobile_update = update::byLogicalId('mobile');
	$jsonrpc->makeSuccess($mobile_update->getLocalVersion());
}
*/

/* V1 ?
if ($jsonrpc->getMethod() == 'event') {
	$eqLogic = eqLogic::byId($params['eqLogic_id']);
	if (!is_object($eqLogic)) {
		throw new Exception(__('EqLogic inconnu : ', __FILE__) . $params['eqLogic_id']);
	}
	$cmd = $eqLogic->getCmd(null, $params['cmd_logicalId']);
	if (!is_object($cmd)) {
		throw new Exception(__('Cmd inconnu : ', __FILE__) . $params['cmd_logicalId']);
	}
	$cmd->event($params['value']);
	$jsonrpc->makeSuccess();
}
*/

/**
 * Ask
 * 
 * @return makeSuccess
 */
if ($jsonrpc->getMethod() == 'askText') {
	log::add('mobile', 'debug', '┌──────────▶︎ ASK ───────────────────────────');
	if (is_object($mobile = eqLogic::byLogicalId($params['Iq'], 'mobile'))) {
		$askCasse = config::byKey('askCasse', 'mobile', false);
		$textCasse = $params['text'];
		if ($askCasse == false) {
			$textCasse = strtolower($params['text']);
		}
		if (is_object($cmd = $mobile->getCmd(null, 'notif'))) {
			log::add('mobile', 'debug', '| Response ─▶︎ ' . $textCasse);
			log::add('mobile', 'debug', '| Cmd ─▶︎ ' . $cmd->getHumanName() . ' (' . $cmd->getId() . ')');
			if ($cmd->askResponse($textCasse)) {
				log::add('mobile', 'debug', '|  OK  Response confirmed');
			} else {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $cmd->generateAskResponseLink($params['text']));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($ch);
				curl_close($ch);
				if (!empty($output)) log::add('mobile', 'debug', '| ' . $output);
			}
		} else log::add('mobile', 'debug', '| [ERROR] Cmd notification not found');
	} else log::add('mobile', 'debug', '| [ERROR] EqLogic unknown ─▶︎ ' . $params['Iq']);
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

/**
 * get Ask Response
 * 
 * @return string ok
 */
if ($jsonrpc->getMethod() == 'getAskResponse') {
	log::add('mobile', 'debug', '┌──────────▶︎ getAskResponse ────────────────');
	if (is_object($mobile = eqLogic::byLogicalId($params['Iq'], 'mobile'))) {
		$Iq = $params['Iq'];
		$filePath = dirname(__FILE__) . '/../data/notifications/' . $Iq . '.json';
		$idNotif = $params['idNotif'];
		$choiceAsk = $params['choiceAsk'];
		log::add('mobile', 'debug', '| Id notif ─▶︎ ' . $idNotif);
		log::add('mobile', 'debug', '| Response ─▶︎ ' . $choiceAsk);
		if (file_exists($filePath)) {
			$notifications = file_get_contents($filePath);
			$notificationsArray = json_decode($notifications, true);
			foreach ($notificationsArray as $key => $notif) {
				if ($notif['data']['idNotif'] == $idNotif) {
					$notificationsArray[$key]['data']['choiceAsk'] = $choiceAsk;
					break;
				}
			}
			$updatedNotifications = json_encode($notificationsArray);
			file_put_contents($filePath, $updatedNotifications);
		} else log::add('mobile', 'debug', '| [ERROR] Notification file does not exist');
	} else log::add('mobile', 'debug', '| [ERROR] EqLogic unknown ─▶︎ ' . $params['Iq']);
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess('ok');
}

/**
 * save event coming from geofencing and methodeForSpecificChannel
 * 
 * @return makeSuccess
 */
if ($jsonrpc->getMethod() == 'mobile::geoloc') {
	log::add('mobile', 'debug', '┌──────────▶︎ GeoLocV2 geofencing ───────────');
	$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
	if (is_object($mobile)) {
		log::add('mobile', 'debug', '|  OK  Mobile found ─▶︎ ' . $mobile->getName() . ' (' . $params['Iq'] . ')');
		if (isset($params['transmition'])) {
			if (isset($params['transmition']['event']) && $params['transmition']['event'] == 'geofence') {
				log::add('mobile', 'debug', '| Event ─▶︎ ' . $params['transmition']['event']);
				$geofence = $params['transmition']['geofence'];
				log::add('mobile', 'debug', '| Geofence ─▶︎ ' . json_encode($geofence));
				$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($mobile->getId(), 'geoloc_' . $geofence['identifier']);
				if (is_object($cmdgeoloc)) {
					if ($geofence['action'] == 'ENTER' || $geofence['action'] == 'EXIT') {
						$eventAge = time() - intval(strtotime($geofence['timestamp']));
						if ($eventAge > 1800) {
							log::add('mobile', 'debug', '| [WARNING] SKIP stale event (' . round($eventAge / 60) . 'min)');
						} else {
							$value = ($geofence['action'] == 'ENTER') ? 1 : 0;
							log::add('mobile', 'debug', '|  OK  Command "' . $cmdgeoloc->getName() . '" ( geoloc_' . $geofence['identifier'] . ' ) ─▶︎ ' . $value . ' ( eventAge = ' . $eventAge . 'sec )');
							$mobile->checkAndUpdateCmd('geoloc_' . $geofence['identifier'], $value);
						}
					} else log::add('mobile', 'debug', '| [WARNING] Action unknown ─▶︎ ' . $geofence['action']);
				} else log::add('mobile', 'debug', '| [ERROR] geofencing command unknown ─▶︎ ' . 'geoloc_' . $geofence['identifier']);
				$mobile->cmdForSpecificChannel($params, 'transmition');
			} else if (is_array($transmitions = $params['transmition'])) {
				log::add('mobile', 'debug', '| [INFO] transmition is array');
				$nbTransmition = count($transmitions);
				foreach ($transmitions as $key => $transmition) {
					log::add('mobile', 'debug', '|┌────────── [' . $key . '] ──────────');
					if (isset($transmition['event']) && $transmition['event'] == 'geofence') {
						log::add('mobile', 'debug', '|| Event ─▶︎ ' . $transmition['event']);
						$geofence = $transmition['geofence'];
						log::add('mobile', 'debug', '|| Geofence ─▶︎ ' . json_encode($geofence));
						$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($mobile->getId(), 'geoloc_' . $geofence['identifier']);
						if (is_object($cmdgeoloc)) {
							if ($geofence['action'] == 'ENTER' || $geofence['action'] == 'EXIT') {
								$eventAge = time() - intval(strtotime($geofence['timestamp']));
								if ($eventAge > 1800) {
									log::add('mobile', 'debug', '|| [WARNING] SKIP stale event (' . round($eventAge / 60) . 'min)');
								} else {
									$value = ($geofence['action'] == 'ENTER') ? 1 : 0;
									log::add('mobile', 'debug', '||  OK  Command "' . $cmdgeoloc->getName() . '" ( geoloc_' . $geofence['identifier'] . ' ) ─▶︎ ' . $value . ' ( eventAge = ' . $eventAge . 'sec )');
									$mobile->checkAndUpdateCmd('geoloc_' . $geofence['identifier'], $value);
								}
							} else log::add('mobile', 'debug', '|| [WARNING] Action unknown ─▶︎ ' . $geofence['action']);
						} else log::add('mobile', 'debug', '|| [ERROR] geofencing command unknown ─▶︎ ' . 'geoloc_' . $geofence['identifier']);
					} else log::add('mobile', 'debug', '|| [ERROR] No geofencing settings');
					log::add('mobile', 'debug', '|└────────────────────────');
					if ($key == $nbTransmition - 1) {
						$mobile->cmdForSpecificChannel($transmitions, $key);
					}
				}
			}
		}
	} else {
		if (isset($params['Iq'])) log::add('mobile', 'debug', '| [ERROR] EqLogic unknown ─▶︎ ' . $params['Iq']);
		else log::add('mobile', 'debug', '[WARNING] Parameter Iq does not exist !');
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

/**
 * save event qrcode scan from app
 * 
 * @return makeSuccess
 */
if ($jsonrpc->getMethod() == "qrcodemethod") {
	log::add('mobile', 'debug', '┌──────────▶︎ qrcodemethod ──────────────────');
	if ($params['appInfos']) {
		log::add('mobile', 'debug', '| [INFO] QR Code Value > ' . json_encode($params['appInfos']['qrCode']));
		if (isset($params['appInfos']['qrCode']['displayValue'])) {
			mobile::cmdForApi($params['Iq'], "barrecodemethod", $params['appInfos']['qrCode']['displayValue'], "CodeBarre");
		} else {
			mobile::cmdForApi($params['Iq'], "qrcodemethod", json_encode($params['appInfos']['qrCode']), "QrCode");
		}
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

/**
 * save and create cmd for methodeForSpecificChannel
 * 
 * @return makeSuccess || makeError
 */
if ($jsonrpc->getMethod() == "methodeForSpecificChannel") {
	log::add('mobile', 'debug', '┌──────────▶︎  methodeForSpecificChannel ──────────────────────');
	log::add('mobile', 'debug', '| [INFO] params ─▶︎ ' . json_encode($params));
	$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
	if (is_object($mobile)) {
		$mobile->cmdForSpecificChannel($params, 'location');
	} else {
		if (isset($params['Iq'])) {
			log::add('mobile', 'debug', '| [ERROR] EqLogic unknown ─▶︎ ' . $params['Iq']);
			$jsonrpc->makeError('EqLogic inconnu');
		} else {
			log::add('mobile', 'debug', '[WARNING] Parameter Iq does not exist !');
			$jsonrpc->makeError('Paramètre Iq inexistant');
		}
	}
	log::add('mobile', 'debug', '└─────────────────────────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

/**
 * save event nfc scan from app
 * 
 * @return makeSuccess
 */
if ($jsonrpc->getMethod() == "nfc") {
	log::add('mobile', 'debug', '┌──────────▶︎ NFC ───────────────────────────');
	$id = (isset($params['appInfos']['payload']['id'])) ? $params['appInfos']['payload']['id'] : "";
	$payload = (isset($params['appInfos']['payload']['payload'])) ? $params['appInfos']['payload']['payload'] : "";
	mobile::cmdForApi($params['Iq'], "nfcId", $id, "Nfc Id");
	mobile::cmdForApi($params['Iq'], "nfcPayload", json_encode($payload), "Nfc Payload");
	log::add('mobile', 'debug', '| [INFO]  Id ─▶︎ ' . $id);
	log::add('mobile', 'debug', '| [INFO]  Payload ─▶︎ ' . $payload);
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

/**
 * coming soon
 * 
 * @return
 */
if ($jsonrpc->getMethod() == "syncBella") {
	log::add('mobile', 'debug', '┌──────────▶︎ syncBella ─────────────────────');
	log::add('mobile', 'debug', '| JeedomApp ─▶︎ syncBella');
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
}

/**
 * get notifiactions present in json file
 * 
 * @return array
 */
if ($jsonrpc->getMethod() == 'getNotificationsFromFile') {
	log::add('mobile', 'debug', '┌──────────▶︎:fg-warning: getNotificationsFromFile :/fg:──────────');
	$Iq = $params['Iq'];
	$filePath = dirname(__FILE__) . '/../data/notifications/' . $Iq . '.json';
	$notifications = 'noNotifications';
	if (file_exists($filePath)) {
		$notifications = file_get_contents($filePath);
		if (empty($notifications)) {
			$notifications = 'noNotifications';
		}
	}
	log::add('mobile', 'debug', '| [INFO] Notifications ─▶︎ ' . $notifications);
	log::add('mobile', 'debug', '└─────────────────────────────────────────────────────');
	$jsonrpc->makeSuccess($notifications);
}

/**
 * delete notifiaction by id in json file
 * 
 * @return string ok
 */
if ($jsonrpc->getMethod() == 'deleteNotificationInJsonFile') {
	log::add('mobile', 'debug', '┌──────────▶︎ deleteNotificationInJsonFile ──────');
	$Iq = $params['Iq'];
	$filePath = dirname(__FILE__) . '/../data/notifications/' . $Iq . '.json';
	$idNotif = $params['IdNotif'];
	log::add('mobile', 'debug', '| [WARNING] Delete notification "' . $idNotif . '" in file > ' . $filePath);
	if (file_exists($filePath)) {
		$notifications = file_get_contents($filePath);
		$notificationsArray = json_decode($notifications, true);
		if ($idNotif == 'allNotifs') {
			file_put_contents($filePath, '');
		} elseif (isset($notificationsArray[$idNotif])) {
			unset($notificationsArray[$idNotif]);
			$notifications = json_encode($notificationsArray);
			file_put_contents($filePath, $notifications);
		}
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────────');
	$jsonrpc->makeSuccess('ok');
}

/**
 * geolofencing point delete
 * 
 * @return string ok
 */
if ($jsonrpc->getMethod() == 'deleteGeolocCommand') {
	log::add('mobile', 'debug', '┌──────────▶︎ deleteGeolocCommand ───────');
	$geolocId = $params['geoloc_id'];
	$eqLogic = eqLogic::byLogicalId($params['Iq'], 'mobile');
	if (is_object($eqLogic)) {
		$cmd = cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), 'geoloc_' . $geolocId);
		if (is_object($cmd)) {
			log::add('mobile', 'debug', '| [WARNING] Suppression de la commande "' . $cmd->getName() . '"');
			$cmd->remove();
		}
	} else if (isset($params['Iq'])) log::add('mobile', 'debug', '| [ERROR] EqLogic unknown ─▶︎ ' . $params['Iq']);
	else log::add('mobile', 'debug', '[WARNING] Parameter Iq does not exist !');
	log::add('mobile', 'debug', '└───────────────────────────────────────────────');
	$jsonrpc->makeSuccess('ok');
}

/**
 * get scenarios by group
 * 
 * @return array
 */
if ($jsonrpc->getMethod() == 'getScenarios') {
	log::add('mobile', 'debug', '┌──────────◀︎ getScenarios ─────────────────');
	$scenarios = array();
	$hasScenario = false;
	$scenarioListGroup = scenario::listGroup();
	$emptyListGroup = false;
	$emptyNoGroup = false;
	if (empty($scenarioListGroup)) {
		log::add('mobile', 'debug', '| Scenarios ─▶︎ No scenario group');
		$emptyListGroup = true;
	}
	if (is_array($scenarioListGroup)) {
		foreach ($scenarioListGroup as $group) {
			$scenarios[$group['group']] = scenario::all($group['group']);
		}
		$hasScenario = true;
	}
	$scenarioNoGroup = scenario::all(null);
	if (count($scenarioNoGroup) > 0) {
		$scenarios['{{Aucun}}'] = $scenarioNoGroup;
		$hasScenario = true;
	} else {
		log::add('mobile', 'debug', '| Scenarios ─▶︎ No scenario "without a group"');
		$emptyNoGroup = true;
	}
	log::add('mobile', 'debug', '| Scenarios ─▶︎ ' . json_encode($scenarios));
	$scenarioTemp = array();
	foreach ($scenarios as $key => $scenario) {
		$scenarioTemp[$key][] = utils::o2a($scenario);
	}
	log::add('mobile', 'debug', '| $scenarioTemp ─▶︎ ' . json_encode($scenarioTemp));
	if ($emptyListGroup && $emptyNoGroup) {
		$return = 'noScenarios';
	} else {
		$return = json_encode($scenarioTemp);
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess($return);
}

// APP V1 //

if ($jsonrpc->getMethod() == 'sync') {
	log::add('mobile', 'debug', '┌──────────▶︎ Sync App V1 ───────────────────');
	if (jeedom::version() >= '3.2.0') {
		log::add('mobile', 'debug', '| Demande du RDK ....');
		$registerDevice = $_USER_GLOBAL->getOptions('registerDevice', array());
		if (!is_array($registerDevice)) {
			$registerDevice = array();
		}
		$rdk = (!isset($params['rdk']) || !isset($registerDevice[sha512($params['rdk'])])) ? config::genKey() : $params['rdk'];
		$registerDevice[sha512($rdk)] = array();
		$registerDevice[sha512($rdk)]['datetime'] = date('Y-m-d H:i:s');
		$registerDevice[sha512($rdk)]['ip'] = getClientIp();
		$registerDevice[sha512($rdk)]['session_id'] = session_id();
		$_USER_GLOBAL->setOptions('registerDevice', $registerDevice);
		$_USER_GLOBAL->save();
		log::add('mobile', 'debug', '| RDK : ' . $rdk);
	}
	$mobile = null;
	if (isset($params['Iq'])) {
		$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
	}
	if (!is_object($mobile)) {
		$user = user::byHash($params['apikey']);
		$userId = $user->getId();
		$mobile = new mobile();
		$mobile->setEqType_name('mobile');
		$mobile->setName($params['platform'] . '-' . config::genKey(3));
		$mobile->setConfiguration('type_mobile', $params['platform']);
		$mobile->setConfiguration('affect_user', $userId);
		$mobile->setConfiguration('validate', 'no');
		if (isset($params['notificationProvider']) && $params['notificationProvider'] != '') {
			$mobile->setConfiguration('notificationArn', substr($params['notificationProvider'], 1, -1));
		}
		if (isset($params['notificationRegistrationToken']) && $params['notificationRegistrationToken'] != '') {
			if ($params['notificationRegistrationToken'] != 'nok') {
				$mobile->setConfiguration('notificationRegistrationToken', $params['notificationRegistrationToken']);
			}
		}
		$mobile->setIsEnable(1);
		$mobile->save();
		$params['Iq'] = $mobile->getLogicalId();
	}

	if (isset($params['notificationProvider']) && $params['notificationProvider'] != '') {
		log::add('mobile', 'debug', '| notificationProvider available');
		$arn = $mobile->getConfiguration('notificationArn', '');
		$arnMobile = substr($params['notificationProvider'], 1, -1);
		if ($arn != $arnMobile) {
			$mobile->setConfiguration('notificationArn', $arnMobile);
			$mobile->save();
		}
	}

	if (isset($params['notificationRegistrationToken']) && $params['notificationRegistrationToken'] != '') {
		log::add('mobile', 'debug', '| notificationRegistrationToken available');
		$token = $mobile->getConfiguration('notificationRegistrationToken', 'nok');
		$tokenMobile = $params['notificationRegistrationToken'];
		if ($token == 'nok') {
			log::add('mobile', 'debug', '| notificationRegistrationToken null in the configuration ─▶︎ ' . $token);
			$mobile->setConfiguration('notificationRegistrationToken', $tokenMobile);
			$mobile->save();
		} else {
			log::add('mobile', 'debug', '| Token in the configuration ─▶︎ ' . $token);
			if ($token != $tokenMobile) {
				log::add('mobile', 'debug', '| Token config != Token mobile ─▶︎ ' . $token . ' != ' . $tokenMobile);
				$mobile->setConfiguration('notificationRegistrationToken', $tokenMobile);
				$mobile->save();
			}
		}
	}

	if (isset($params['gen_json']) && $params['gen_json'] == 1) {
		mobile::makeTemplateJson();
	}
	$return = mobile::getTemplateJson();
	$return['messages'] = mobile::discovery_message();
	$return['summaryValue'] = mobile::discovery_summaryValue($return['objects']);
	$return['config']['datetime'] = getmicrotime();
	$return['config']['Iq'] = $params['Iq'];
	$return['config']['NameMobile'] = $mobile->getName();
	if (isset($rdk)) {
		$return['config']['rdk'] = $rdk;
	}
	log::add('mobile', 'debug', '| Return $discovery_summaryValue ─▶︎ ' . json_encode($return['summaryValue']));
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess($return);
}

if ($jsonrpc->getMethod() == 'cmdsbyEqlogicID') {
	log::add('mobile', 'debug', 'Querying the ID module ' . $params['id'] . ' for cmds');
	$PluginToSend = mobile::PluginToSend();
	$discover_eqLogic = mobile::discovery_eqLogic($PluginToSend);
	$sync_new = mobile::change_cmdAndeqLogic(mobile::discovery_cmd($PluginToSend, $discover_eqLogic, true), $discover_eqLogic);
	$i = 0;
	$cmdAPI = array();
	foreach ($sync_new['cmds'] as $cmd) {
		if (isset($cmd["eqLogic_id"])) {
			if ($cmd["eqLogic_id"] != $params['id']) {
				unset($commandes[$i]);
			} else {
				array_push($cmdAPI, $commandes[$i]);
			}
		}
		$i++;
	}
	log::add('mobile', 'debug', 'Commands ─▶︎ ' . json_encode($cmdAPI));
	$jsonrpc->makeSuccess($cmdAPI);
}

if ($jsonrpc->getMethod() == 'version') {
	$mobile_update = update::byLogicalId('mobile');
	$jsonrpc->makeSuccess($mobile_update->getLocalVersion());
}

if ($jsonrpc->getMethod() == 'event') {
	$eqLogic = eqLogic::byId($params['eqLogic_id']);
	if (!is_object($eqLogic)) {
		throw new Exception(__('EqLogic inconnu : ', __FILE__) . $params['eqLogic_id']);
	}
	$cmd = $eqLogic->getCmd(null, $params['cmd_logicalId']);
	if (!is_object($cmd)) {
		throw new Exception(__('Cmd inconnu : ', __FILE__) . $params['cmd_logicalId']);
	}
	$cmd->event($params['value']);
	$jsonrpc->makeSuccess();
}

if ($jsonrpc->getMethod() == 'saveMobile') {
	log::add('mobile', 'debug', '┌──────────▶︎:fg-warning: saveMobile V1 :/fg:──────────────');
	if (isset($params['Iq'])) {
		log::add('mobile', 'debug', '| Backup request ─▶︎ ' . $params['type'] . ' ─▶︎ ' . $params['Iq'] . ' (' . mobile::whoIsIq($params['Iq']) . ')');
		log::add('mobile', 'debug', '| Data to save ─▶︎ ' . json_encode($params['Json']));
		mobile::makeSaveJson($params['Iq'], $params['Json'], $params['type']);
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

if ($jsonrpc->getMethod() == 'getMobile') {
	log::add('mobile', 'debug', '┌──────────▶︎:fg-warning: getMobile V1 :/fg:──────────────');
	log::add('mobile', 'debug', '| Recovery request ─▶︎ ' . $params['type'] . ' ─▶︎ ' . $params['Iq'] . ' (' . mobile::whoIsIq($params['Iq']) . ')');
	log::add('mobile', 'debug', '| Backup recovery ─▶︎ ' . $params['IqRestore'] . ' (' . mobile::whoIsIq($params['IqRestore']) . ')');
	$saveJson = mobile::getSaveJson($params['IqRestore'], $params['type']);
	log::add('mobile', 'debug', '| Data sent to the app ─▶︎ ' . json_encode($saveJson));
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess($saveJson);
}

if ($jsonrpc->getMethod() == 'geoloc') {
	log::add('mobile', 'debug', '┌──────────▶︎:fg-warning: geoloc V1 :/fg:──────────────');
	if (isset($params['Iq'])) {
		$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
		if (is_object($mobile)) {
			if (isset($params['id']) && $params['id'] != '' && isset($params['name']) && $params['name'] != '' && isset($params['value']) && $params['value'] != '') {
				log::add('mobile', 'debug', '| geoId_' . $params['id'] . ' ─▶︎ ' . $params['name'] . ' ─▶︎ ' . $params['value']);
				if ($mobile->checkAndUpdateCmd('geoId_' . $params['id'], $params['value'])) {
					log::add('mobile', 'debug', '| ↳ Update geofence point ─▶︎ ' . $params['value']);
				}
			} else log::add('mobile', 'debug', '| [WARNING] A required parameter does not exist !');
		} else log::add('mobile', 'debug', '| [ERROR] EqLogic unknown ─▶︎ ' . $params['Iq']);
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

if ($jsonrpc->getMethod() == 'geolocSave') {
	log::add('mobile', 'debug', '┌──────────▶︎:fg-warning: geolocSave V1 :/fg:──────────────');
	if (isset($params['Iq'])) {
		$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
		if (is_object($mobile)) {
			if (isset($params['id']) && $params['id'] != '') {
				$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($mobile->getId(), 'geoId_' . $params['id']);
				if ($params['name'] == "") {
					$name = $params['id'];
				} else {
					$name = $params['name'];
				}
				if (!is_object($cmdgeoloc)) {
					$cmdgeoloc = new mobileCmd();
					$cmdgeoloc->setLogicalId('geoId_' . $params['id']);
					$cmdgeoloc->setEqLogic_id($mobile->getId());
					$cmdgeoloc->setType('info');
					$cmdgeoloc->setSubType('binary');
					$cmdgeoloc->setTemplate('dashboard', 'core::presence');
					$cmdgeoloc->setTemplate('mobile', 'core::presence');
					$cmdgeoloc->setDisplay('icon', '<i class="icon fas fa-location-arrow"></i>');
					$cmdgeoloc->setDisplay('showIconAndNamedashboard', 1);
					$cmdgeoloc->setIsHistorized(1);
					$cmdgeoloc->setGeneric_type('PRESENCE');
					$cmdgeoloc->setIsVisible(1);
					$cmdgeoloc->setName($name);
				}
				$cmdgeoloc->setConfiguration('latitude', $params['latitude']);
				$cmdgeoloc->setConfiguration('longitude', $params['longitude']);
				$cmdgeoloc->setConfiguration('subtitle', $params['subtitle']);
				$cmdgeoloc->setConfiguration('radius', $params['radius']);
				$cmdgeoloc->save();
				log::add('mobile', 'debug', '| geoId_' . $params['id'] . ' ─▶︎ ' . $name . ' ─▶︎ ' . $params['value']);
				if ($mobile->checkAndUpdateCmd('geoId_' . $params['id'], $params['value'])) {
					log::add('mobile', 'debug', '| ↳ Update geofence point ─▶︎ ' . $params['value']);
				}
			} else log::add('mobile', 'debug', '| [WARNING] A required parameter does not exist or is empty !');
		} else log::add('mobile', 'debug', '| [ERROR] EqLogic unknown ─▶︎ ' . $params['Iq']);
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

if ($jsonrpc->getMethod() == 'geolocDel') {
	log::add('mobile', 'debug', '┌──────────▶︎:fg-warning: geolocDel V1 :/fg:──────────────');
	if (isset($params['Iq'])) {
		$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
		if (is_object($mobile)) {
			if (isset($params['id']) && $params['id'] != '') {
				$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($mobile->getId(), 'geoId_' . $params['id']);
				if (is_object($cmdgeoloc)) {
					$cmdgeoloc->remove();
					log::add('mobile', 'debug', '| geoId_' . $params['id'] . ' (' . $params['name'] .  ') is deleted with success.');
				}
			} else log::add('mobile', 'debug', '| [WARNING] A required parameter Id does not exist or is empty !');
		} else log::add('mobile', 'debug', '| [ERROR] EqLogic unknown ─▶︎ ' . $params['Iq']);
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

throw new Exception(__('Aucune demande', __FILE__));
