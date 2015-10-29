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
    
    /**************************************************************************************/
	/*                                                                                    */
	/*                        Permet d'installer les dépendances                          */
	/*                                                                                    */
	/**************************************************************************************/
	
	public static function updatemobile() {
		log::remove('mobile_update');
		$cmd = '/bin/bash ' .dirname(__FILE__) . '/../../ressources/install.sh';
		$cmd .= ' >> '.log::getPathToLog('mobile_update').' 2>&1 &';
		exec($cmd);
	}
	
	/**************************************************************************************/
	/*                                                                                    */
	/*                         Permet de creer le Json du QRCode                          */
	/*                                                                                    */
	/**************************************************************************************/
	
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
	
	/**************************************************************************************/
	/*                                                                                    */
	/*           Permet de valider quel plugin est compatible avec l'app mobile           */
	/*                                                                                    */
	/**************************************************************************************/
	
	public function check_plugin_mobile(){
	
		$plugin_valide = array('openzwave','rfxcom');
		
		return $plugin_valide;
	}
	
	
	/**************************************************************************************/
	/*                                                                                    */
	/*                  Class Loic permet d'avoir un Tag par Action                       */
	/*                                                                                    */
	/**************************************************************************************/
	
	public static function getGenericType($cmd_id){
	
		$cmd = cmd::byId($cmd_id);
		if ($cmd->getDisplay('generic_type') != '') {
			return $cmd->getDisplay('generic_type');
		}
		$category = $cmd->getEqLogic()->getPrimaryCategory();
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
			}
			return $type . strtoupper($cmd->getSubtype());
		} else {
			switch ($cmd->getUnite()) {
				case 'W':
					return $type . 'POWER';
				case 'kWh':
					return $type . 'CONSUMPTION';
				case '°C':
					return $type . 'TEMPERATURE';
				case 'Lux':
					return $type . 'BRIGHTNESS';
			}
			$name = strtolower($cmd->getName());
			if (strpos($name, 'Présence') !== false) {
					return $type . 'PRESENCE';
				}
			if (strpos($name, 'Batterie') !== false) {
					return $type . 'BATTERY';
				}
			if (strpos($name, 'Fumées') !== false) {
					return $type . 'FUMES';
				}
			if (strpos($name, 'Anti Sabotage') !== false) {
					return $type . 'TAMPER';
				}	
			return $type . 'STATE';
		}
	}
	
	

	
	/**************************************************************************************/
	/*                                                                                    */
	/*                  Permet de connaitre les pieces de la box jeedom                   */
	/*                                                                                    */
	/**************************************************************************************/
	
	public function pieces(){
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
			'ordre' => $object->getPosition(),
		);
		return array("objet" => $response);	
	}
	
	
	/**************************************************************************************/
	/*                                                                                    */
	/*            Permet de decouvrir tout les modules de la Jeedom compatible            */
	/*                                                                                    */
	/**************************************************************************************/
	
	public function decouverte($type,$track){
		if($type == 'all'){
			return json_encode(array("decouverte" => utils::o2a(eqLogic::all())));
		}elseif($type == 'valide'){
			$array_plugin = mobile::check_plugin_mobile();
			$json_decouverte_valide = array();
			$plugin_object_present = array();
			$arraycommande = array();
			foreach($array_plugin as $key => $value){
				$plugin_object = utils::o2a(eqLogic::byType($value));
					foreach($plugin_object as $key => $value){
						if($value['isEnable'] == 1){
							if($track == 'all'){
								if($value['category'] == null){
									$value['category'] = 'nok';
								}
								$object = array('id' => $value['id'], 'name' => $value['name'], 'logicalId' => $value['logicalId'], 'object_id' => $value['object_id'], 'eqType_name' => $value['eqType_name'], 'category' => $value['category'],'commands' => mobile::commande($value['id']));
								array_push($plugin_object_present, $object);
							}elseif($track == 'info'){
								$arraycommande = mobile::commande($value['id'],'ok',$arraycommande);
							}
						}
					}
			}
			if($track == 'all'){
				$json_decouverte_valide = array('decouverte' => $plugin_object_present);
			}elseif($track == 'info'){
				$json_decouverte_valide = array('commande' => $arraycommande);
			}
			return $json_decouverte_valide;
		}
	}
	
	/**************************************************************************************/
	/*                                                                                    */
	/*                      Permet de decouvrir tout les scenarios                        */
	/*                                                                                    */
	/**************************************************************************************/
	
	public function scenario($type){
		if($type == 'all'){
			$scenario = utils::o2a(scenario::all());
				$json_scenario = array();
				foreach($scenario as $key => $value){
					if($value['isActive'] == 1){
						$scenar = array('id' => $value['id'],'name' => $value['name'],'state' => $value['state'], 'lastLaunch' => $value['lastLaunch'], 'display' => $value['display'], 'description' => $value['description']);
						array_push($json_scenario, $scenar);
					}
				}
			return array("scenario" => $json_scenario);
		}
	}
	
	/**************************************************************************************/
	/*                                                                                    */
	/*    Permet de recuperer ou de sauvegarder l'architecture de l'app et de la creer    */
	/*                                                                                    */
	/**************************************************************************************/
	
	public function archi($type,$date_archi,$id_mobile,$json_archi){
			$lien_archi = dirname(__FILE__) . '/../../core/json/archi_mobile_'.$id_mobile.'.json';
		if($type == 'sauvegarde'){
			if(file_exists($lien_archi)){
				$json_archi_in = json_decode(file_get_contents($lien_archi));
			}else{
				file_put_contents($lien_archi);
				$json_archi_in = array();
			}
			if($json_archi_in['date'] > $date_archi){
				// On envoi le Json de la structure
				return $json_archi_in;	
			}else{
				// On sauvegarde le Json
				file_put_contents($lien_archi,$json_archi);
				return "{'return':'save_archi'}";
			}
		}	
	}
	
	/**************************************************************************************/
	/*                                                                                    */
	/*          Permet de recuperer les commandes compatible avec l'app Mobile            */
	/*                                                                                    */
	/**************************************************************************************/
	public function commande($type,$info,$arraycommande){
		//Permet de decouvrir tout les commandes
		if($type == 'all'){
			$Json_commande = array();
			$commande_plugin = utils::o2a(cmd::all());
			foreach($commande_plugin as $key => $value){
				if($value['type'] !== 'action'){
					$valeur_cmd = cmd::byId($value['id'])->execCmd();
				}else{
					$valeur_cmd = $value['value'];
				}
				
				$tag = mobile::getGenericType($value['id']);
				
				$commande_complet_json = array('id' => $value['id'], 'name' => $value['name'], 'order' => if(isset($value['order'])){$value['order']}else{null}, 'type' => $value['type'], 'subType' => $value['subType'], 'unite' => if(isset($value['unite'])){$value['unite']}else{null}, 'template' => if(isset($value['template']['mobile'])){$value['template']['mobile']}else{null}, 'invertBinary' => if(isset($value['display']['invertBinary'])){$value['display']['invertBinary']}else{null}, 'isVisible' => $value['isVisible'], 'value' => $valeur_cmd, 'tag' => $tag);
					
					array_push($Json_commande, $commande_complet_json);	
				}
			return array('commands' => $Json_commande);
		}else{
			$Json_commande = array();
			$commande_plugin = utils::o2a(cmd::byEqLogicId($type));
				foreach($commande_plugin as $key => $value){
				if($info == 'ok'){
					if($value['type'] == 'info'){
					$tag = mobile::getGenericType($value['id']);
					$valeur_cmd = cmd::byId($value['id'])->execCmd();
						$commande_complet_json = array('id' => $value['id'], 'name' => $value['name'], 'order' => if(isset($value['order'])){$value['order']}else{null}, 'type' => $value['type'], 'subType' => $value['subType'], 'unite' => if(isset($value['unite'])){$value['unite']}else{null}, 'template' => if(isset($value['template']['mobile'])){$value['template']['mobile']}else{null}, 'invertBinary' => if(isset($value['display']['invertBinary'])){$value['display']['invertBinary']}else{null}, 'isVisible' => $value['isVisible'], 'value' => $valeur_cmd, 'tag' => $tag);
						array_push($arraycommande, $commande_complet_json);
					}	
				}else{
					if($value['type'] !== 'action'){
						$valeur_cmd = cmd::byId($value['id'])->execCmd();
					}else{
						$valeur_cmd = $value['value'];
					}
					$tag = mobile::getGenericType($value['id']);
					$commande_complet_json = array('id' => $value['id'], 'name' => $value['name'], 'order' => if(isset($value['order'])){$value['order']}else{null}, 'type' => $value['type'], 'subType' => $value['subType'], 'unite' => if(isset($value['unite'])){$value['unite']}else{null}, 'template' => if(isset($value['template']['mobile'])){$value['template']['mobile']}else{null}, 'invertBinary' => if(isset($value['display']['invertBinary'])){$value['display']['invertBinary']}else{null}, 'isVisible' => $value['isVisible'], 'value' => $valeur_cmd, 'tag' => $tag);
					
					array_push($Json_commande, $commande_complet_json);
				}	
				}	
			if($info == 'ok'){
				return $arraycommande;
			}else{
				return $Json_commande;
			}
		}
	}
	
	/**************************************************************************************/
	/*                                                                                    */
	/*         Permet de d'executer les commandes compatible avec l'app Mobile            */
	/*                                                                                    */
	/**************************************************************************************/
	public function cmd($id_cmd,$valeur_demande){
		if($valeur_demande !== null){
			$option = array("slider"=>$valeur_demande);
		}else{
			$option = null;
		}
		$valeur_cmd = cmd::byId($id_cmd)->execCmd();
		
		
		$cmd = cmd::byId($id_cmd);
			if(is_object($cmd)){
				$valeur_cmd = $cmd->execCmd($option,2);
			}
		
		
		$Json_commande = array("id" => $id_cmd,"valeur" => $valeur_cmd);
		return $Json_commande;
		
	}
	
	
	
	/**************************************************************************************/
	/*                                                                                    */
	/*                  Permet d'avoir les infos des plugins compatible                   */
	/*                                                                                    */
	/**************************************************************************************/
	public function Plugin_valide_func(){
		$plugin_ok = array();
		$plugin_list = utils::o2a(plugin::listPlugin());
		$array_plugin = mobile::check_plugin_mobile();
			foreach($plugin_list as $key => $value){
				$plugin_test_en_cours = $value['id'];
					foreach($array_plugin as $key_array => $value_array){
						if($value_array == $plugin_test_en_cours){
							$plugin_ok_complet = array('id' => $value['id'],'name' => $value['name'] ,'description' => $value['description']);
							array_push($plugin_ok, $plugin_ok_complet);
						}
					}
			}
		return array("plugin" => $plugin_ok);
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
