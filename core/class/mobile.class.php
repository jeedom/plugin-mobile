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
	
		$plugin_valide = array('openzwave');
		
		return $plugin_valide;
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
			);
		}
		$response[] = array(
			'id' => 99999,
			'name' => __('Aucun', __FILE__),
		);
		return array("objet" => $response);	
	}
	
	
	/**************************************************************************************/
	/*                                                                                    */
	/*            Permet de decouvrir tout les modules de la Jeedom compatible            */
	/*                                                                                    */
	/**************************************************************************************/
	
	public function decouverte($type){
		if($type == 'all'){
			return json_encode(array("decouverte" => utils::o2a(eqLogic::all())));
		}elseif($type == 'valide'){
			$array_plugin = mobile::check_plugin_mobile();
			$json_decouverte_valide = array();
			$plugin_object_present = array();
			foreach($array_plugin as $key => $value){
				$plugin_object = utils::o2a(eqLogic::byType($value));
					foreach($plugin_object as $key => $value){
						if($value['isEnable'] == 1){
							$object = array('id' => $value['id'], 'name' => $value['name'], 'logicalId' => $value['logicalId'], 'object_id' => $value['object_id'], 'eqType_name' => $value['eqType_name'], 'category' => $value['category'],'commands' => mobile::commande($value['id']));
							array_push($plugin_object_present, $object);
						}
					}
			}
			$json_decouverte_valide = array('decouverte' => $plugin_object_present);
			//$json_decouverte_valide = json_encode($json_decouverte_valide);
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
	/*           Permet de recuperer ou de sauvegarder l'architecture de l'app            */
	/*                                                                                    */
	/**************************************************************************************/
	
	public function archi($date_archi,$id_mobile,$json_archi){
		$lien_archi = dirname(__FILE__) . '/../../core/json/archi_mobile_'.$id_mobile.'.json';
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
	
	/**************************************************************************************/
	/*                                                                                    */
	/*          Permet de recuperer les commandes compatible avec l'app Mobile            */
	/*                                                                                    */
	/**************************************************************************************/
	public function commande($type){
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
				$commande_complet_json = array('id' => $value['id'], 'name' => $value['name'], 'order' => $value['order'], 'type' => $value['type'], 'subType' => $value['subType'], 'unite' => $value['unite'], 'template' => $value['template']['appmobile'], 'invertBinary' => $value['display']['invertBinary'], 'isVisible' => $value['isVisible'], 'value' => $valeur_cmd);
					
					array_push($Json_commande, $commande_complet_json);	
				}
			return array('commands' => $Json_commande);
		}else{
			$Json_commande = array();
			$commande_plugin = utils::o2a(cmd::byEqLogicId($type));
				foreach($commande_plugin as $key => $value){
									if($value['type'] !== 'action'){
					$valeur_cmd = cmd::byId($value['id'])->execCmd();
				}else{
					$valeur_cmd = $value['value'];
				}
					$commande_complet_json = array('id' => $value['id'], 'name' => $value['name'], 'order' => $value['order'], 'type' => $value['type'], 'subType' => $value['subType'], 'unite' => $value['unite'], 'template' => $value['template']['appmobile'], 'invertBinary' => $value['display']['invertBinary'], 'isVisible' => $value['isVisible'], 'value' => $valeur_cmd);
					
					array_push($Json_commande, $commande_complet_json);	
				}	
			return $Json_commande;
		}
	}
	
	public function cmd($id_cmd){
	
		$valeur_cmd = cmd::byId($id_cmd)->execCmd();
		$Json_commande = array("id" => $id_cmd,"valeur" => $valeur_cmd);
		return $Json_commande;
		
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
