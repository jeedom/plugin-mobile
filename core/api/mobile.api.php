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

function createMobile($params, $nbIcones = 3)
{
	$configs = $params['configs'];
	$notification = $configs['notification'];
	$user = user::byHash($params['apikey']);
	$userId = $user->getId();
	$mobile = new mobile();
	$mobile->setEqType_name('mobile');
	$mobile->setName($notification['platform'] . '-' . $params['Iq']);
	log::add('mobile', 'debug', ' ---- CREATE_NEW_MOBILE WITH ' . $nbIcones . ' ICONS ----');
	$namesMenus =  ['home', 'overview', 'health', 'home'];
	$renamesIcons =  ['Accueil', 'Synthese', 'Santé', 'Accueil'];
	$spanIcons =  ['icon jeedomapp-in', 'fab fa-hubspot', 'fas fa-medkit', 'icon jeedomapp-in'];
	$urlUsers =  ['none', 'none', 'none', 'none'];
	$j = 0;
	$countFor = intval($nbIcones) + 1;
	$menuCustomArray = [];
	for ($i = 1; $i < $countFor; $i++) {
		$menuCustomArray[$i] = array(
			'selectNameMenu' => $namesMenus[$j],
			'renameIcon' => $renamesIcons[$j],
			'spanIcon' => $spanIcons[$j],
			'urlUser' => $urlUsers[$j],
		);
		$j++;
	}
	$mobile->setConfiguration('menuCustomArray', $menuCustomArray);
	$mobile->setConfiguration('nbIcones', intval($nbIcones));
	$mobile->setConfiguration('defaultIdMobile', 'default');
	$mobile->setConfiguration('type_mobile', $notification['platform']);
	$mobile->setConfiguration('affect_user', $userId);
	$mobile->setConfiguration('validate', 'no');
	$mobile->setConfiguration('appVersion', '2');
	$mobile->setLogicalId($params['Iq']);
	$mobile->setIsEnable(1);
	$mobile->save();
	return $mobile;
}

function checkDateMenu($menu, $mobile)
{
	log::add('mobile', 'debug', '|┌──:fg-success: checkDateMenu :/fg:──');
	$dateMobile = $mobile->getConfiguration('DateMenu', 'pasdedate');
	log::add('mobile', 'debug', '|| dateMobile > ' . $dateMobile);
	log::add('mobile', 'debug', '|| menu date > ' . (isset($menu['date']) ? $menu['date'] : 'pasdedate'));
	if (isset($dateMobile) && isset($menu['date'])) {
		if ($dateMobile < $menu['date']) {
			log::add('mobile', 'debug', '||  OK  Sauvegarde Menu depuis l\'App');
			saveMenuFromAppV2($menu, $mobile);
		}
	}
	log::add('mobile', 'debug', '|└────────────────────');
}


function saveMenuFromAppV2($menu, $mobile)
{
	log::add('mobile', 'debug', '||┌── :fg-success:saveMenuFromAppV2:/fg: ──');
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
		$mobile->save();
	}
	log::add('mobile', 'debug', '||└───────────────────');
}

$params = $jsonrpc->getParams();
log::add('mobile', 'debug', '┌──────────▶︎ :fg-warning: Appel API Mobile :/fg: ◀︎───────────');
log::add('mobile', 'debug', '| Method > ' . $jsonrpc->getMethod());
log::add('mobile', 'debug', '| Paramètres passés > ' . json_encode($params));
if ($params['Iq']) {
	log::add('mobile', 'debug', '| Mobile demandeur > ' . mobile::whoIsIq($params['Iq']));
}
else {
  	log::add('mobile', 'debug', '| [WARNING] Paramètre Iq inexistant !');
}
log::add('mobile', 'debug', '└───────────────────────────────────────────');

if ($jsonrpc->getMethod() == 'setConfigs') {
	log::add('mobile', 'debug', '┌─────▶︎ AppV2 setConfigs ─────────────────');
	$configs = $params['configs'];
	$geolocs = $params['geolocs'];
	$menu = $configs['menu'];
	$notification = $configs['notification'];
	log::add('mobile', 'debug', '| [INFO] Configs > ' . json_encode($configs));
	log::add('mobile', 'debug', '| [INFO] Geolocs > ' . json_encode($geolocs));
	log::add('mobile', 'debug', '| [INFO] Menu > ' . json_encode($menu));
	log::add('mobile', 'debug', '| [INFO] Notification > ' . json_encode($notification));
	$mobile = null;
	if (isset($params['Iq'])) {
		$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
	}
	if (!is_object($mobile)) {
		$mobile = createMobile($params, 3);
	}
	if (isset($notification['token'])) {
		if ($notification['token'] != '') {
			log::add('mobile', 'debug', '| Token à ajouter > ' . $notification['token']);
			$mobile->setConfiguration('notificationRegistrationToken', $notification['token']);
			if ($notification['token'] == 'notifsBGDisabled') {
				message::removeAll("mobile", 'alertNotifs');
				message::add('mobile', 'Les notifications sur votre mobile : ' . $mobile->getName() . ' sont desactivées', 'notifsbg', 'alertNotifs');
			}
		} else {
			log::add('mobile', 'debug', '| [NOTICE] Token vide ');
        }
	}
	$mobile->save();
	// TEMPORAIREMENT DESACTIVE
	checkDateMenu($menu, $mobile);
	if ($geolocs) {
		if ($geolocs != [] && !(is_object($geolocs) && empty((array)$geolocs)) && !(is_string($geolocs) && $geolocs == "{}")) {
			mobile::createCmdGeoLocV2($params['Iq'], $params['geolocs']);
		} else {
			log::add('mobile', 'debug', '| Geolocs vide, suppression des commandes précédentes');
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
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess('ok');
}

if ($jsonrpc->getMethod() == 'getJson') {
	log::add('mobile', 'debug', '┌─────◀︎ AppV2 getJson ────────────────────');
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
	log::add('mobile', 'debug', '| RDK :' . $rdk);
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
	$return[$idBox]['informations']['nbMessage'] = message::nbMessage();
	$userConnected = user::byHash($_USER_GLOBAL->getHash());
	if(is_object($userConnected)){
		$return[$idBox]['informations']['userConnected'] = $userConnected->getLogin();
	}
	$arrayObjectMessages = message::all();
	$arrayMessages = [];
	foreach ($arrayObjectMessages as $message) {
		$messageArray = utils::o2a($message);
		array_push($arrayMessages, $messageArray);
	}
	$return[$idBox]['informations']['messages'] = $arrayMessages;
	$arrayPlugins = [];
	$changeLogs = [];
	$healthPlugins = [];
	$deamons_infos = [];
	$objectsPanel = [];
	$pluginPanelMobile = [];
	$pluginPanelOutMobile = [];
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
	config::save('pluginPanelOutMobile', $pluginPanelOutMobile, 'mobile');
	$return[$idBox]['informations']['objects']['panel'] = $objectsPanel;
	$categories = [];
	foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
		$categories[$value['icon']] =  $value['name'];
	}
	$return[$idBox]['informations']['objects']['categories'] = $categories;
	//sleep(1);
	$coreData = [];
	$resultCore = utils::o2a(update::byLogicalId('jeedom'));
	array_push($coreData, $resultCore);
	$return[$idBox]['informations']['coreBranch'] = config::byKey('core::branch');
	$return[$idBox]['informations']['coreData'] = $coreData;
	$return[$idBox]['informations']['plugins'] = $arrayPlugins;
	$return[$idBox]['informations']['changelog'] = $changeLogs;
	$return[$idBox]['informations']['infosDemon'] = $deamons_infos;
	$return[$idBox]['informations']['nbUpdate'] = update::nbNeedUpdate();
	$return[$idBox]['informations']['uname'] = system::getDistrib() . ' ' . method_exists('system', 'getOsVersion') ? system::getOsVersion() : 'UnknownVersion';
	$return[$idBox]['jeedom_version'] = jeedom::version();
	$return[$idBox]['rdk'] = $rdk;
	$return[$idBox]['name'] = config::byKey('name') == '' ? 'Jeedom' : config::byKey('name');
	log::add('mobile', 'debug', '| [INFO] Retour de base > ' . json_encode($return));
	log::add('mobile', 'debug', '| Recherche du mobile via sont Iq > ' . $params['Iq']);
	$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
	$return[$idBox]['configs'] = array();
	if (is_object($mobile)) {
		log::add('mobile', 'debug', '|  OK  Mobile trouvé > ' . $mobile->getName());
		$return[$idBox]['configs']['menu'] = mobile::configMenuCustom($mobile->getId(), jeedom::version());
	} else {
		if (jeedom::version() < '4.4.0') {
			log::add('mobile', 'debug', '| [WARNING] Version du core > ' . jeedom::version());
			$return[$idBox]['configs']['menu'] = mobile::configMenuCustom($mobile->getId(), jeedom::version());
		} else {
			$defaultMenuJson = '{"tab0":{"active":true,"icon":{"name":"in","type":"jeedomapp"},"name":"Accueil","options":{"uri":"\/index.php?v=m&p=home"},"type":"WebviewApp"},
								"tab1":{"active":true,"icon":{"name":"hubspot","type":"fa"},"name":"Synthese","options":{"uri":"\/index.php?v=m&p=overview"},"type":"WebviewApp"},
								"tab2":{"active":true,"icon":{"name":"medkit","type":"fa"},"name":"Sant\u00e9","options":{"uri":"\/index.php?v=m&p=health"},"type":"WebviewApp"},
								"tab3":{"active":false,"icon":{"name":"in","type":"jeedomapp"},"name":"Accueil","options":{"uri":"\/index.php?v=m&app_mode=1"},"type":"WebviewApp"}}';
			$defaultMenuArray = json_decode($defaultMenuJson, true);
			$return[$idBox]['configs']['menu'] = $defaultMenuArray;
		}
	}
	// ENREGISTRER LES 5 DERNIERS MENUS DU TELEPHONE :
	// Récupérer les enregistrements précédents pour ce téléphone
	$previousMenus = config::byKey('previousMenus', 'mobile');
	if (empty($previousMenus)) {
		$previousMenus = [];
	}

	$phoneMenus = isset($previousMenus[$params['Iq']]) ? $previousMenus[$params['Iq']] : [];

	$newMenu = $return[$idBox]['configs']['menu'];


	if (empty($phoneMenus) || $newMenu != $phoneMenus[0]) {
		array_unshift($phoneMenus, $newMenu);
		$phoneMenus = array_slice($phoneMenus, 0, 5);
		$previousMenus[$params['Iq']] = $phoneMenus;

		// 5 DERNIERS
		config::save('previousMenus', $previousMenus, 'mobile');
	}
	config::save('menuCustom_' . $params['Iq'], $newMenu, 'mobile');

	log::add('mobile', 'debug', '| [INFO] CustomENVOICONFIGSAPI GETJSON > ' . json_encode($return[$idBox]['configs']));
	log::add('mobile', 'debug', '| [INFO] Retour vers App > ' . json_encode($return));
	log::add('mobile', 'debug', '└───────────────────────────────────────────');

	$retentionTime = config::byKey('retentionTime', 'mobile', null);
	if(isset($retentionTime) && $retentionTime != null) {
		log::add('mobile', 'debug', '| [INFO] Nettoyage des notifs et images > ');
		mobile::cleaningNotifications($params['Iq'], $retentionTime);
	}

	$jsonrpc->makeSuccess($return);
}

if ($jsonrpc->getMethod() == 'deleteMessage') {
	$message = message::byId($params['appInfos']['idmessage']);
	if (is_object($message)) {
		$message->remove();
		$jsonrpc->makeSuccess("true");
	}
	$jsonrpc->makeSuccess("false");
}

if ($jsonrpc->getMethod() == 'deleteAllMessageByType') {
	if (isset($params['appInfos']['messageType'])) {
		if ($params['appInfos']['messageType'] == 'jeedom') {
			message::removeAll('jeedom');
			$jsonrpc->makeSuccess("true");
		} elseif ($params['appInfos']['messageType'] == 'plugins') {
			$messages = message::all();
			foreach ($messages as $message) {
				if (is_object($message)) {
					if ($message->getPlugin() != 'jeedom') {
						$message->remove();
					}
				} else {
					$jsonrpc->makeSuccess("false");
				}
			}
			$jsonrpc->makeSuccess("true");
		}
	} else {
		$jsonrpc->makeSuccess("false");
	}
}

if ($jsonrpc->getMethod() == 'sync') {
	if (jeedom::version() >= '3.2.0') {
		log::add('mobile', 'debug', 'SYNC');
		log::add('mobile', 'debug', 'Demande du RDK');
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
		log::add('mobile', 'debug', 'RDK :' . $rdk);
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
		if (isset($params['notificationProvider'])) {
			$mobile->setConfiguration('notificationArn', substr($params['notificationProvider'], 1, -1));
		}
		if (isset($params['notificationRegistrationToken'])) {
			if ($params['notificationRegistrationToken'] != 'nok') {
				$mobile->setConfiguration('notificationRegistrationToken', $params['notificationRegistrationToken']);
			}
		}
		$mobile->setIsEnable(1);
		$mobile->save();
		$params['Iq'] = $mobile->getLogicalId();
	}
	if (isset($params['notificationProvider']) || $params['notificationProvider'] != '' || isset($params['notificationRegistrationToken']) || $params['notificationRegistrationToken'] != 'nok') {
		log::add('mobile', 'debug', 'notificationProvider Disponible');
		log::add('mobile', 'debug', 'EqLogic dispo');
		$arn = $mobile->getConfiguration('notificationArn', null);
		$token = $mobile->getConfiguration('notificationRegistrationToken', null);
		$arnMobile = substr($params['notificationProvider'], 1, -1);
		$tokenMobile = $params['notificationRegistrationToken'];
		if ($arn == null) {
			log::add('mobile', 'debug', 'arn null dans la configuration > ' . $arn);
			$mobile->setConfiguration('notificationArn', $arnMobile);
			$mobile->save();
		} else {
			log::add('mobile', 'debug', 'arn NON null dans la configuration > ' . $arn);
			if ($arn != $arnMobile) {
				$mobile->setConfiguration('notificationArn', $arnMobile);
				$mobile->save();
			}
		}
		if ($token == 'nok') {
			log::add('mobile', 'debug', 'token null dans la configuration > ' . $token);
			$mobile->setConfiguration('notificationRegistrationToken', $tokenMobile);
			$mobile->save();
		} else {
			log::add('mobile', 'debug', 'Token dans la configuration > ' . $token);
			if ($token != $tokenMobile) {
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
	log::add('mobile', 'debug', 'Return $discovery_summaryValue > ' . json_encode($return['summaryValue']));
	$jsonrpc->makeSuccess($return);
}

if ($jsonrpc->getMethod() == 'cmdsbyEqlogicID') {
	log::add('mobile', 'debug', 'Interogation du module id:' . $params['id'] . ' Pour les cmds');
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
	log::add('mobile', 'debug', 'Commande > ' . json_encode($cmdAPI));
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

if ($jsonrpc->getMethod() == 'askText') {
	log::add('mobile', 'debug', '┌─────▶︎ ASK ───────────────────────────────');
	/*$configs = $params['configs'];
  	$menu = $configs['menu'];
  	$notification = $configs['notification'];*/
	$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
	if (is_object($mobile)) {
		$askCasse = config::byKey('askCasse', 'mobile', false);
		$textCasse = $params['text'];
		if ($askCasse == false) {
			$textCasse = strtolower($params['text']);
		}
		$cmd = $mobile->getCmd(null, 'notif');
		log::add('mobile', 'debug', '| Réponse : ' . $textCasse . ' - IQ > ' . $params['Iq'] . ' -- Demande cmd > ' . $cmd->getId());
		if ($cmd->askResponse($textCasse)) {
			log::add('mobile', 'debug', '| ASK bien trouvé : Réponse validée');
		} else {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $cmd->generateAskResponseLink($params['text']));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
			if (!empty($output)) log::add('mobile', 'debug', '| ' . $output);
		}
	} else {
		log::add('mobile', 'debug', __('| [ERROR] EqLogic inconnu : ', __FILE__) . $params['Iq']);
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

if ($jsonrpc->getMethod() == 'saveMobile') {
	log::add('mobile', 'debug', 'Demande de sauvegarde ' . $params['type'] . ' > ' . $params['Iq'] . ' > ' . mobile::whoIsIq($params['Iq']));
	mobile::makeSaveJson($params['Iq'], $params['Json'], $params['type']);
	$jsonrpc->makeSuccess();
}

if ($jsonrpc->getMethod() == 'getMobile') {
	log::add('mobile', 'debug', 'Demande de recuperation ' . $params['type'] . ' > ' . $params['Iq'] . '(' . mobile::whoIsIq($params['Iq']) . ') recuperation save du > ' . $params['IqRestore'] . ' (' . mobile::whoIsIq($params['IqRestore']) . ')');
	$jsonrpc->makeSuccess(mobile::getSaveJson($params['IqRestore'], $params['type']));
}

if ($jsonrpc->getMethod() == 'geoloc') {
	log::add('mobile', 'debug', 'Geoloc ' . $params['id'] . ' > ' . $params['name'] . ' > ' . $params['value']);
	mobile::EventGeoloc($params);
	$jsonrpc->makeSuccess();
}

if ($jsonrpc->getMethod() == 'geolocSave') {
	log::add('mobile', 'debug', 'Geoloc SAVE ' . $params['id'] . ' > ' . $params['name']);
	if ($params['id'] != '' || $params['id'] != null) {
		mobile::SaveGeoloc($params);
		$jsonrpc->makeSuccess();
	} else {
		throw new Exception(__('pas d\'id : ', __FILE__) . $params['name']);
	}
}

if ($jsonrpc->getMethod() == 'geolocDel') {
	log::add('mobile', 'debug', 'Geoloc DEL ' . $params['id'] . ' > ' . $params['name']);
	mobile::delGeoloc($params);
	$jsonrpc->makeSuccess();
}



if ($jsonrpc->getMethod() == 'mobile::geoloc') {
	log::add('mobile', 'debug', '┌─────▶︎ GeoLocV2 geofencing ───────────────');
	if (isset($params['transmition']) && isset($params['transmition']['event']) && $params['transmition']['event'] == 'geofence') {
		log::add('mobile', 'debug', '| Event > ' . $params['transmition']['event']);
		$geofence = $params['transmition']['geofence'];
		log::add('mobile', 'debug', '| Event > ' . json_encode($geofence));
		$eqLogicMobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
		if ($eqLogicMobile) {
			log::add('mobile', 'debug', '|  OK  Mobile trouvé -> ' . $eqLogicMobile->getName() . ' (' . $params['Iq'] . ')');
			$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($eqLogicMobile->getId(), 'geoloc_' . $geofence['identifier']);
			if (is_object($cmdgeoloc)) {
				if ($geofence['action'] == 'ENTER') {
					log::add('mobile', 'debug', '|  OK  Commande "'. $cmdgeoloc->getName() . '" passée à 1');
					$cmdgeoloc->event(1);
				} elseif ($geofence['action'] == 'EXIT') {
					log::add('mobile', 'debug', '|  OK  Commande "'. $cmdgeoloc->getName() . '" passée à 0');
					$cmdgeoloc->event(0);
				}
				else {
					log::add('mobile', 'debug', '| Event -> ' . $geofence['action']);
				}
			}
		} elseif (isset($params['Iq'])) {
			log::add('mobile', 'debug', __('| [ERROR] EqLogic inconnu : ', __FILE__) . $params['Iq']);
		} else {
			log::add('mobile', 'debug', __('| [ERROR] Paramètre Iq inexistant !', __FILE__));
        }
	} else {
		$transmitions = $params['transmition'];
		$errorCount = 0;
		foreach ($transmitions as $transmition) {
			if (isset($transmition['event']) && $transmition['event'] == 'geofence') {
				log::add('mobile', 'debug', '| Transmition :' . json_encode($params['transmition']));
				$geofence = $transmition['geofence'];
				log::add('mobile', 'debug', '| Event > ' . json_encode($geofence));
				$eqLogicMobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
				if ($eqLogicMobile) {
					log::add('mobile', 'debug', '|  OK  Mobile trouvé -> ' . $eqLogicMobile->getName() . ' (' . $params['Iq'] . ')');
					$cmdgeoloc = cmd::byEqLogicIdAndLogicalId($eqLogicMobile->getId(), 'geoloc_' . $geofence['identifier']);
					if (is_object($cmdgeoloc)) {
						if ($geofence['action'] == 'ENTER') {
							log::add('mobile', 'debug', '|  OK  Commande "'. $cmdgeoloc->getName() . '" passée à 1');
							$cmdgeoloc->event(1);
						} elseif ($geofence['action'] == 'EXIT') {
							log::add('mobile', 'debug', '|  OK  Commande "'. $cmdgeoloc->getName() . '" passée à 0');
							$cmdgeoloc->event(0);
						} else {
							log::add('mobile', 'debug', '| [INFO] Event -> ' . $geofence['action']);
						}
					} else {
						log::add('mobile', 'debug', '| [ERROR] Commande geoloc_' . $geofence['identifier'] . ' inexistante.');
					}
				} else {
					log::add('mobile', 'debug', __('| [ERROR] EqLogic inconnu : ', __FILE__) . $params['Iq']);
				}
			} else {
				$errorCount++;
			}
		}
		if ($errorCount > 0) {
			log::add('mobile', 'debug', __('| Pas de paramètre de geofencing', __FILE__));
		}
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

if ($jsonrpc->getMethod() == "qrcodemethod") {
	log::add('mobile', 'debug', '┌─────▶︎ qrcodemethod ──────────────────────');
	if ($params['appInfos']) {
		log::add('mobile', 'debug', '| [INFO] Valeur du QrCode > ' . json_encode($params['appInfos']['qrCode']));
		if (isset($params['appInfos']['qrCode']['displayValue'])) {
			mobile::cmdForApi($params['Iq'], "barrecodemethod", $params['appInfos']['qrCode']['displayValue'], "CodeBarre");
		} else {
			mobile::cmdForApi($params['Iq'], "qrcodemethod", json_encode($params['appInfos']['qrCode']), "QrCode");
		}
		log::add('mobile', 'debug', '└───────────────────────────────────────────');
	}
	$jsonrpc->makeSuccess();
}

if ($jsonrpc->getMethod() == "nfc") {
	log::add('mobile', 'debug', '┌─────▶︎ nfc ───────────────────────────────');
	$id = (isset($params['appInfos']['payload']['id'])) ? $params['appInfos']['payload']['id'] : "";
	$payload = (isset($params['appInfos']['payload']['payload'])) ? $params['appInfos']['payload']['payload'] : "";
	mobile::cmdForApi($params['Iq'], "nfcId", $id, "Nfc Id");
	mobile::cmdForApi($params['Iq'], "nfcPayload", json_encode($payload), "Nfc Payload");
	log::add('mobile', 'debug', '| [INFO]  Id > ' . $id);
	log::add('mobile', 'debug', '| [INFO]  Payload > ' . $payload);
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess();
}

if ($jsonrpc->getMethod() == "syncBella") {
	log::add('mobile', 'debug', '┌─────▶︎ syncBella ─────────────────────────');
	log::add('mobile', 'debug', '| JeedomApp > syncBella');
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
}

if ($jsonrpc->getMethod() == 'getNotificationsFromFile') {
	log::add('mobile', 'debug', '┌──────────▶︎ :fg-warning: Recuperation des Notifications :/fg: ──────────');
	$Iq = $params['Iq'];
	$filePath = dirname(__FILE__) . '/../data/notifications/' . $Iq . '.json';
	$notifications = 'noNotifications';
	if (file_exists($filePath)) {
		$notifications = file_get_contents($filePath);
	}
	log::add('mobile', 'debug', '| [INFO] Notifications > ' . $notifications);
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess($notifications);	
}

if ($jsonrpc->getMethod() == 'deleteNotificationInJsonFile') {
	log::add('mobile', 'debug', '┌────▶︎ deleteNotificationInJsonFile ──────');
	$Iq = $params['Iq'];
	$filePath = dirname(__FILE__) . '/../data/notifications/' . $Iq . '.json';
	$idNotif = $params['IdNotif'];
	log::add('mobile', 'debug', '| [WARNING] Delete notification "' . $idNotif . '" in file > ' . $filePath);
	if (file_exists($filePath)) {
		$notifications = file_get_contents($filePath);
		$notificationsArray = json_decode($notifications, true);
		if ($idNotif == 'allNotifs') {
			file_put_contents($filePath, '');
		}
		elseif (isset($notificationsArray[$idNotif])) {
			unset($notificationsArray[$idNotif]);
			$notifications = json_encode($notificationsArray);
			file_put_contents($filePath, $notifications);
		}
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess('ok');
}

if ($jsonrpc->getMethod() == 'deleteGeolocCommand') {
	log::add('mobile', 'debug', '┌────▶︎ Commande suppression GeoLoc ───────');
	log::add('mobile', 'debug', '| Paramètres > ' . json_encode($params));
	$geolocId = $params['geoloc_id'];
	$eqLogic = eqLogic::byLogicalId($params['Iq'], 'mobile');
	if (is_object($eqLogic)) {
		$cmd = cmd::byEqLogicIdAndLogicalId($eqLogic->getId(), 'geoloc_' . $geolocId);
		if (is_object($cmd)) {
			log::add('mobile', 'debug', '| [WARNING] Suppression de la commande "' . $cmd->getName() . '"');
			$cmd->remove();
		}
	} else {
		log::add('mobile', 'debug', __('| [ERROR] EqLogic inconnu : ', __FILE__) . $params['Iq']);
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess('ok');
}

if ($jsonrpc->getMethod() == 'getAskResponse') {
	log::add('mobile', 'debug', '┌────▶︎ getAskResponse ────────────────────');
	$Iq = $params['Iq'];
	$filePath = dirname(__FILE__) . '/../data/notifications/' . $Iq . '.json';
	$idNotif = $params['idNotif'];
	$choiceAsk = $params['choiceAsk'];
	log::add('mobile', 'debug', '| Réponse ASK > ' . $Iq . ' > ' . $idNotif . ' > ' . $choiceAsk);
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
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess('ok');
}

if ($jsonrpc->getMethod() == 'getScenarios'){
	log::add('mobile', 'debug', '┌────◀︎ Get Scénarios ─────────────────────');
	$scenarios = array();
	$hasScenario = false;
	$scenarioListGroup = scenario::listGroup();
	if (empty($scenarioListGroup)){
		log::add('mobile', 'debug', '| Scénarios > Aucun groupe de scénario');
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
		log::add('mobile', 'debug', '| Scénarios > Aucun "sans groupe" de scénario');
		$emptyNoGroup = true;
	}
	log::add('mobile', 'debug', '| Scénarios > ' . json_encode($scenarios));
	$scenarioTemp = array();
	foreach ($scenarios as $key => $scenario){
		$scenarioTemp[$key][] = utils::o2a($scenario);
	}
	log::add('mobile', 'debug', '| $scenarioTemp > ' . json_encode($scenarioTemp));
	if ($emptyListGroup && $emptyNoGroup){
		$return = 'noScenarios';
	} else {
		$return = json_encode($scenarioTemp);
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess($return);
}

if ($jsonrpc->getMethod() == 'handleScenario'){
	log::add('mobile', 'debug', '┌────▶︎ handleScenario ────────────────────');
	$scenarioId = $params['scenario_id'];
	$action = $params['action'];
	$result = 'ko';
	log::add('mobile', 'debug', '| Scénario > ' . $scenarioId);
	log::add('mobile', 'debug', '| Action > ' . $action);
	if (is_object($scenario = scenario::byId($scenarioId))) {
		switch($action) {
			case 'start':
				$scenario->launch();
				$result = 'ok';
			break;
			case 'stop':
				$scenario->stop();
				$result = 'ok';
			break;
			case 'activate':
				$scenario->setIsActive(1);
				$scenario->save();
				$result = 'ok';
			break;
	    	case 'desactivate':
				$scenario->setIsActive(0);
				$scenario->save();
				$result = 'ok';
			break;
		}
	} else {
		log::add('mobile', 'debug', '| [ERROR] Scénario > ' . $scenarioId . ' inexistant !');
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess($result);
}


// if($jsonrpc->getMethod() == 'modifyNotifInJsonFile'){
//     log::add('mobile', 'debug', 'modifyNotifInJsonFile');
//     $Iq = $params['Iq'];
//     $notifsToModify =  $params['notifsToModify'];
//     $pathNotification = __DIR__ . '/../data/notifications';
//     if(file_exists($pathNotification)){
//         $notifications = file_get_contents($pathNotification.'/'.$Iq.'.json');
//         $notificationsArray = json_decode($notifications, true); 

//         foreach ($notificationsArray as $key => $notif) {
// 			log::add('mobile', 'debug', 'Notif > '.json_encode($notif));

//             foreach ($notifsToModify as $notifToModify) {
// 				log::add('mobile', 'debug', 'NotifMODIFY > '.json_encode($notifToModify));
// 				if ($notif['data']['idNotif'] == $notifToModify['data']['idNotif']) {
// 					if (!isset($notif['data']['textToDisplay'])) {
// 						$notificationsArray[$key]['data']['textToDisplay'] = $notifToModify['textToDisplay']; 
// 					}
// 					$notificationsArray[$key]['data']['textToDisplay'] = $notifToModify['textToDisplay'];
// 					break;
// 				}
//             }
//         }

//         $updatedNotifications = json_encode($notificationsArray);
//         file_put_contents($pathNotification.'/'.$Iq.'.json', $updatedNotifications);
//     }

//     $jsonrpc->makeSuccess('ok');
// }

throw new Exception(__('Aucune demande', __FILE__));