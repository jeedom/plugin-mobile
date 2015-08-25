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



    /*     * ***********************Methode static*************************** */
	public static function updatemobile() {
		log::remove('mobile_update');
		$cmd = '/bin/bash ' .dirname(__FILE__) . '/../../ressources/install.sh';
		$cmd .= ' >> '.log::getPathToLog('mobile_update').' 2>&1 &';
		exec($cmd);
	}
	
	public static function json_for_qrcode($id_app,$urlinterne,$urlexterne,$api_jeedom,$utilisateur){
		
		$request_qrcode = array(
				'id_app' =>  $id_app,
				'urlinterne' => $urlinterne,
				'urlexterne' => $urlexterne,
				'api_jeedom' => $api_jeedom,
				'utilisateur' => $utilisateur
			);
	
		$qrcode = json_encode($request_qrcode);
		return $qrcode;
	}
	
	public static function check_plugin_mobile(){
		//permet de check tout les plugin compatible App Mobile
		
		
	}
	
	public function pieces(){
		//Permet de connaitre les pieces de la box jeedom.
		$response = array();
		foreach (object::all() as $object) {
			$response[] = array(
				'id' => $object->getId(),
				'name' => $object->getName(),
			);
		}
		$response[] = array(
			'id' => 99999,
			'name' => __('Aucun', __FILE__),
		);
		return json_encode(array("pieces" => $response));	
	}
	
	public function decouverte($type){
		//Permet de decouvrir tout les modules de la Jeedom
		if($type == 'all'){
			return json_encode(utils::o2a(eqLogic::all()));
		}else{
			return json_encode(utils::o2a(eqLogic::byObjectId($type)));
		}
	}
	
	public function commande($type){
		if($type == 'all'){
			return json_encode(utils::o2a(cmd::all()));
		}else{
			return json_encode(utils::o2a(cmd::byEqLogicId($type)));
		}
	}
	
	public function scenario($type){
		if($type == 'all'){
			return json_encode(utils::o2a(scenario::all()));
		}else{
			return json_encode(scenario::byId($type));
		}
	}
	
	public function convertType($cmd) {
		switch ($cmd->getEqType()) {
			case "presence":
				return 'MultiSwitch';
			case "camera":
				return 'Camera';
			case 'Store':
				return 'Shutter';
			case 'ipx800_relai':
			case 'ipx800_bouton':
				return 'Switch';
		}
		if (strpos(strtolower($cmd->getTemplate('dashboard')), 'door') !== false) {
			return 'Door';
		}
		if (strpos(strtolower($cmd->getTemplate('dashboard')), 'window') !== false) {
			return 'Door';
		}
		if (strpos(strtolower($cmd->getTemplate('dashboard')), 'porte_garage') !== false) {
			return 'Door';
		}
		if (strpos(strtolower($cmd->getTemplate('dashboard')), 'presence') !== false) {
			return 'Motion';
		}
		if (strpos(strtolower($cmd->getTemplate('dashboard')), 'store') !== false) {
			return 'Shutter';
		}
		if (strpos(strtolower($cmd->getTemplate('dashboard')), 'fire') !== false) {
			return 'Smoke';
		}
		if (strpos(strtolower($cmd->getTemplate('dashboard')), 'light') !== false) {
			return 'Dimmer';
		}
		if ($cmd->getSubType() == 'binary' && strtolower($cmd->getName()) == 'co') {
			return 'CO2Alert';
		}
		if ($cmd->getSubType() == 'binary' && (strpos(strtolower($cmd->getName()), __('fumée', __FILE__)) !== false || strpos(strtolower($cmd->getName()), __('smoke', __FILE__)) !== false)) {
			return 'Smoke';
		}
		if (strpos(strtolower($cmd->getName()), __('humidité', __FILE__)) !== false) {
			$cache = cache::byKey('issConfig');
			$issConfig = json_decode($cache->getValue('{}'), true);
			foreach ($cmd->getEqLogic()->getCmd('info') as $info) {
				if ($info->getUnite() == '°C') {
					if (isset($issConfig[$info->getId()]) && $issConfig[$info->getId()]['cmd_transmit'] == 1) {
						return 'TempHygro';
					}
				}
			}
			return 'Hygrometry';
		}
		if (strtolower($cmd->getName()) == __('uv', __FILE__)) {
			return 'UV';
		}
		$eqlogic = $cmd->getEqLogic();
		if (strpos(strtolower($cmd->getName()), __('etat', __FILE__)) !== false) {
			if (strpos(strtolower($eqlogic->getName()), __('fenêtre', __FILE__)) !== false || strpos(strtolower($eqlogic->getName()), __('fenetre', __FILE__)) !== false || strpos(strtolower($eqlogic->getName()), __('porte', __FILE__)) !== false) {
				return 'Door';
			}
		}

		switch ($cmd->getSubtype()) {
			case 'numeric':
				switch (strtolower($cmd->getUnite())) {
					case '°c':
						$cache = cache::byKey('issConfig');
						$issConfig = json_decode($cache->getValue('{}'), true);
						foreach ($cmd->getEqLogic()->getCmd('info') as $info) {
							if (strpos(strtolower($info->getName()), __('humidité', __FILE__)) !== false) {
								if (isset($issConfig[$info->getId()]) && $issConfig[$info->getId()]['cmd_transmit'] == 1) {
									return 'TempHygro';
								}
							}
						}
						return 'Temperature';
					case '%':
						if (count(cmd::byValue($cmd->getId(), 'action')) == 0) {
							return 'GenericSensor';
						}
						return 'Dimmer';
					case 'pa':
						return 'Pressure';
					case 'db':
						return 'Noise';
					case 'km/h':
						return 'Wind';
					case 'mm/h':
						return 'Rain';
					case 'mm':
						return 'Rain';
					case 'm3':
						return 'Flood';
					case 'ppm':
						return 'CO2';
					case 'lux':
						return 'Luminosity';
					case 'w':
						return 'Electricity';
					case 'kwh':
						return 'Electricity';
				}
				return 'GenericSensor';
			case 'binary':
				if (count(cmd::byValue($cmd->getId(), 'action')) == 0) {
					return 'GenericSensor';
				}
				return 'Switch';

		}
		foreach ($eqlogic->getCmd() as $cmd) {
			if ($cmd->getSubtype() == 'color') {
				return 'RGBLight';
			}
		}
		if ($cmd->getType() == 'action') {
			return 'Switch';
		}
		return 'GenericSensor';
	}
    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
      public static function cron() {

      }
     */


    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {

      }
     */

    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDayly() {

      }
     */



    /*     * *********************Méthodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        
    }

    public function preUpdate() {
        
    }

    public function postUpdate() {
        
    }

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

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
        
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>
