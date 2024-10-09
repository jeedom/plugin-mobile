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
log::add('mobile', 'debug', '┌──────────▶︎ :fg-warning: Appel API Mobile :/fg: ◀︎───────────');
log::add('mobile', 'debug', '| Method > ' . $jsonrpc->getMethod());
log::add('mobile', 'debug', '| Paramètres passés > ' . json_encode($params));
if ($params['Iq']) {
	log::add('mobile', 'debug', '| Mobile demandeur > ' . mobile::whoIsIq($params['Iq']));
} else {
  	log::add('mobile', 'debug', '| [WARNING] Paramètre Iq inexistant !');
}
log::add('mobile', 'debug', '└───────────────────────────────────────────');

/**
 * Create a new equipment
 * Call by api setConfigs
 * @param array 
 * @return new object
 */
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
	$mobile->setConfiguration('menuCustomArray', mobile::getMenuDefaultV2($nbIcones));
	$mobile->setConfiguration('nbIcones', intval($nbIcones));
	//$mobile->setConfiguration('defaultIdMobile', 'default'); moved to postInsert
	$mobile->setConfiguration('type_mobile', $notification['platform']);
	$mobile->setConfiguration('affect_user', $userId);
	$mobile->setConfiguration('validate', 'no');
	$mobile->setConfiguration('appVersion', '2');
	$mobile->setLogicalId($params['Iq']);
	$mobile->setIsEnable(1);
	$mobile->save();
	return $mobile;
}

/**
 * Save menu from app
 * Call by setCustomMenu
 * @param array
 */
function saveMenuFromAppV2($menu, $mobile) {
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
	log::add('mobile', 'debug', '┌─────▶︎ AppV2 setConfigs ─────────────────');
	$configs = $params['configs'];
	$geolocs = $params['geolocs'];
	$menu = $configs['menu'];
	$notification = $configs['notification'];
	log::add('mobile', 'debug', '| [INFO] Configs > ' . json_encode($configs));
	log::add('mobile', 'debug', '| [INFO] Geolocs > ' . json_encode($geolocs));
	//log::add('mobile', 'debug', '| [INFO] Menu > ' . json_encode($menu));
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

	/* moved to new method setCustomMenu
	saveMenuFromAppV2($menu, $mobile);
	*/

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

/**
 * Save menu from app
 * 
 * @return string ok makeSuccess
 */
if ($jsonrpc->getMethod() == 'setCustomMenu') {
	log::add('mobile', 'debug', '┌─────▶︎ AppV2 setCustomMenu ─────────────────');
	$configs = $params['configs'];
	$menu = $configs['menu'];
	log::add('mobile', 'debug', '| [INFO] Configs > ' . json_encode($configs));
	log::add('mobile', 'debug', '| [INFO] Menu > ' . json_encode($menu));
	$mobile = null;
	if (isset($params['Iq']) && is_object($mobile = eqLogic::byLogicalId($params['Iq'], 'mobile'))) {
		saveMenuFromAppV2($menu, $mobile);
	} else {
		log::add('mobile', 'debug', '| [WARNING] Paramètre Iq manquant ou équipement inexistant !');
	}
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess('ok');
}

/**
 * getJson
 * 
 * @return array makeSuccess
 */
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
		$return[$idBox]['configs']['menu'] = $mobile->configMenuCustom();
		/* Hide some menus by configuration */
		$return[$idBox]['configs']['hideMenuCustom'] = intval($mobile->getConfiguration('hideMenuCustom', 0));
		$return[$idBox]['configs']['hideMenuGeoloc'] = intval($mobile->getConfiguration('hideMenuGeoloc', 0));
	} else {
		$return[$idBox]['configs']['menu'] = mobile::getMenuDefaultTab();
		$return[$idBox]['configs']['hideMenuCustom'] = 0;
		$return[$idBox]['configs']['hideMenuGeoloc'] = 0;
	}
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

/**
 * return menu custom in app
 * 
 * @return array makeSuccess
 */
if ($jsonrpc->getMethod() == 'getCustomMenu') {
	log::add('mobile', 'debug', '┌─────◀︎ AppV2 getCustomMenu ────────────────────');
	log::add('mobile', 'debug', '| Recherche du mobile via sont Iq > ' . $params['Iq']);
	$mobile = eqLogic::byLogicalId($params['Iq'], 'mobile');
	if (is_object($mobile)) {
		log::add('mobile', 'debug', '|  OK  Mobile trouvé > ' . $mobile->getName());
		$menu = $mobile->configMenuCustom();
	} else {
		$menu = mobile::getMenuDefaultTab();
	}
	log::add('mobile', 'debug', '| [INFO] Retour vers App > ' . json_encode($menu));
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
	$jsonrpc->makeSuccess($menu);
}

/**
 * delete message jeedom by id
 * 
 * @return bool makeSuccess
 */
if ($jsonrpc->getMethod() == 'deleteMessage') {
	$message = message::byId($params['appInfos']['idmessage']);
	if (is_object($message)) {
		$message->remove();
		$jsonrpc->makeSuccess("true");
	}
	$jsonrpc->makeSuccess("false");
}

/**
 * delete message jeedom by type
 * 
 * @return bool makeSuccess
 */
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

/**
 * get Ask Response
 * 
 * @return string ok
 */
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

/**
 * saveMobile
 * 
 * @return makeSuccess
 */
if ($jsonrpc->getMethod() == 'saveMobile') {
	log::add('mobile', 'debug', 'Demande de sauvegarde ' . $params['type'] . ' > ' . $params['Iq'] . ' > ' . mobile::whoIsIq($params['Iq']));
	mobile::makeSaveJson($params['Iq'], $params['Json'], $params['type']);
	$jsonrpc->makeSuccess();
}

/**
 * save event coming from geofencing
 * 
 * @return makeSuccess
 */
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

/**
 * save event qrcode scan from app
 * 
 * @return makeSuccess
 */
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

/**
 * save event nfc scan from app
 * 
 * @return makeSuccess
 */
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

/**
 * coming soon
 * 
 * @return
 */
if ($jsonrpc->getMethod() == "syncBella") {
	log::add('mobile', 'debug', '┌─────▶︎ syncBella ─────────────────────────');
	log::add('mobile', 'debug', '| JeedomApp > syncBella');
	log::add('mobile', 'debug', '└───────────────────────────────────────────');
}

/**
 * get notifiactions present in json file
 * 
 * @return array
 */
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

/**
 * delete notifiaction by id in json file
 * 
 * @return string ok
 */
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

/**
 * geolofencing point delete
 * 
 * @return string ok
 */
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

/**
 * get scenarios by group
 * 
 * @return array
 */
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

/**
 * command scenarios by id
 * 
 * @return array
 */
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

throw new Exception(__('Aucune demande', __FILE__));
