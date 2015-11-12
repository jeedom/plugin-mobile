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
include_file('3rdparty', 'qrcode/qrlib', 'php', 'mobile');

class mobile extends eqLogic {
	/*     * *************************Attributs****************************** */

	private static $_PLUGIN_COMPATIBILITY = array('openzwave', 'rfxcom');

	/*     * ***********************Methode static*************************** */

	/**************************************************************************************/
	/*                                                                                    */
	/*                        Permet d'installer les dépendances                          */
	/*                                                                                    */
	/**************************************************************************************/

	public static function updatemobile() {
		log::remove('mobile_update');
		$cmd = '/bin/bash ' . dirname(__FILE__) . '/../../ressources/install.sh';
		$cmd .= ' >> ' . log::getPathToLog('mobile_update') . ' 2>&1 &';
		exec($cmd);
	}

	/**************************************************************************************/
	/*                                                                                    */
	/*                  Class Loic permet d'avoir un Tag par Action                       */
	/*                                                                                    */
	/**************************************************************************************/

	public static function getGenericType($cmd) {
		if ($cmd->getDisplay('generic_type') != '') {
			return $cmd->getDisplay('generic_type');
		}
		$category = $cmd->getEqLogic()->getPrimaryCategory();
		$name_eq = strtolower($cmd->getEqLogic()->getName());
		$type = strtoupper($category) . '_';
		if ($cmd->getType() == 'action') {
			if ($cmd->getSubtype() == 'other') {
				$name = strtolower($cmd->getName());
				if ($category = 'heating' && strpos($name, 'cool') !== false) {
					$type = 'COOL_';
				}
				if (strpos($name, 'off') !== false) {
					return $type . 'OFF';
				}
				if (strpos($name, 'on') !== false) {
					return $type . 'ON';
				}
				if (strpos($name, 'up') !== false) {
					return $type . 'UP';
				}
				if (strpos($name, 'down') !== false) {
					return $type . 'DOWN';
				}
			}
			return $type . strtoupper($cmd->getSubtype());
		} else {
			switch ($cmd->getUnite()) {
				case 'W':
					return $type . 'POWER';
				case 'kWh':
					return $type . 'CONSUMPTION';
				case '°C':
					return 'TEMPERATURE';
				case 'Lux':
					return 'BRIGHTNESS';
			}
			$name = strtolower($cmd->getName());
			if (strpos($name, 'présence') !== false) {
				return 'PRESENCE';
			}
			if (strpos($name, 'batterie') !== false) {
				return 'BATTERY';
			}
			if (strpos($name, 'fumées') !== false) {
				return 'FUMES';
			}
			if (strpos($name, 'température') !== false) {
				return 'TEMPERATURE';
			}
			if (strpos($name, 'luminosité') !== false) {
				return 'BRIGHTNESS';
			}
			if (strpos($name, 'fuite') !== false) {
				return 'FLIGHT';
			}
			if (strpos($name_eq, 'porte') !== false || strpos($name_eq, 'door') !== false || strpos($name_eq, 'fenetre') !== false || strpos($name_eq, 'fenêtre') !== false) {
				return 'OPENING';
			}
			return $type . 'STATE';
		}
	}

	/**************************************************************************************/
	/*                                                                                    */
	/*                  Permet de connaitre les pieces de la box jeedom                   */
	/*                                                                                    */
	/**************************************************************************************/

	public static function object() {
		$response = array();
		foreach (object::all() as $object) {
			$response[] = array(
				'id' => $object->getId(),
				'name' => $object->getName(),
				'ordre' => $object->getPosition(),
			);
		}
		$response[] = array(
			'id' => 99999,
			'name' => __('Aucun', __FILE__),
			'ordre' => -1,
		);
		return $response;
	}

	/**************************************************************************************/
	/*                                                                                    */
	/*            Permet de decouvrir tout les modules de la Jeedom compatible            */
	/*                                                                                    */
	/**************************************************************************************/

	public static function discovery($type, $track) {
		if ($type == 'all') {
			return json_encode(array('decouverte' => utils::o2a(eqLogic::all())));
		} elseif ($type == 'valide') {
			$return = array();
			foreach (self::$_PLUGIN_COMPATIBILITY as $plugin_type) {
				$eqLogics = eqLogic::byType($plugin_type, true);
				if (is_array($eqLogics)) {
					foreach ($eqLogics as $eqLogic) {
						$eqLogic_array = utils::o2a($eqLogic);
						$eqLogic_array['commands'] = self::getPrepareCommand($eqLogic, ($track == 'info'));
						$return[] = $eqLogic_array;
					}
				}
			}
			return $return;
		}
	}

	/**************************************************************************************/
	/*                                                                                    */
	/*                      Permet de decouvrir tout les scenarios                        */
	/*                                                                                    */
	/**************************************************************************************/

	public static function scenario($type) {
		if ($type == 'all') {
			$return = array();
			foreach (scenario::all() as $scenario) {
				if ($scenario->getIsActive() == 1) {
					$return[] = utils::o2a($scenario);
				}
			}
			return $return;
		}
	}

	/**************************************************************************************/
	/*                                                                                    */
	/*    Permet de recuperer ou de sauvegarder l'architecture de l'app et de la creer    */
	/*                                                                                    */
	/**************************************************************************************/

	public static function archi($type, $date_archi, $id_mobile, $json_archi) {
		$json_archi_in = array();
		$filename = dirname(__FILE__) . '/../../data/json/archi_mobile_' . $id_mobile . '.json';
		if ($type == 'sauvegarde') {
			if (file_exists($filename)) {
				$json_archi_in = json_decode(file_get_contents($filename));
			}
			if (isset($json_archi_in['date']) && $json_archi_in['date'] > $date_archi) {
				// On envoi le Json de la structure
				return $json_archi_in;
			} else {
				// On sauvegarde le Json
				file_put_contents($filename, $json_archi);
				return 'save_archi';
			}
		}
	}

	/**************************************************************************************/
	/*                                                                                    */
	/*          Permet de recuperer les commandes compatible avec l'app Mobile            */
	/*                                                                                    */
	/**************************************************************************************/
	public static function getPrepareCommand($eqLogic, $infoOnly = false) {
		$return = array();
		if ($infoOnly) {
			$cmds = $eqLogic->getCmd('info');
		} else {
			$cmds = $eqLogic->getCmd();
		}
		foreach ($cmds as $cmd) {
			$json_cmd = utils::o2a($cmd);
			$json_cmd['tag'] = mobile::getGenericType($cmd);
			$json_cmd['value'] = ($cmd->getType() !== 'action') ? $cmd->execCmd(null, 2) : $cmd->getConfiguration('lastCmdValue');
			$return[] = $json_cmd;
		}
		return $return;
	}

	/**************************************************************************************/
	/*                                                                                    */
	/*                  Permet d'avoir les infos des plugins compatible                   */
	/*                                                                                    */
	/**************************************************************************************/
	public static function getAllowPlugin() {
		$return = array();
		foreach (self::$_PLUGIN_COMPATIBILITY as $plugin_id) {
			try {
				$return[] = utils::o2a(plugin::byId($plugin_id));
			} catch (Exception $e) {

			}
		}
		return $return;
	}

	/**************************************************************************************/
	/*                                                                                    */
	/*                         Permet de creer le Json du QRCode                          */
	/*                                                                                    */
	/**************************************************************************************/

	public function getQrCode() {
		$request_qrcode = array(
			'eqLogic_id' => $this->getId(),
			'url_internal' => network::getNetworkAccess('internal'),
			'url_external' => network::getNetworkAccess('external'),
			'api_jeedom' => config::byKey('api'),
			'market_user' => config::byKey('market::username'),
		);
		if (!file_exists(dirname(__FILE__) . '/../../data')) {
			mkdir(dirname(__FILE__) . '/../../data');
		}
		QRcode::png(json_encode($request_qrcode), dirname(__FILE__) . '/../../data/qrcode.png', 'L', 4, 2);
		return 'plugins/mobile/data/qrcode.png?' . strtotime('now');
	}

	/*     * *********************Méthodes d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}

class mobileCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	/*
	 * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
	public function dontRemoveCmd() {
	return true;
	}
	 */

	public function execute($_options = array()) {
		return false;
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
