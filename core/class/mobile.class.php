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

	//private static $_PLUGIN_COMPATIBILITY = array('openzwave', 'rfxcom', 'edisio', 'ipx800', 'mySensors', 'Zibasedom', 'virtual', 'camera','apcupsd', 'btsniffer', 'dsc', 'h801', 'rflink', 'mysensors', 'relaynet', 'remora', 'unipi', 'playbulb', 'doorbird','netatmoThermostat');

	/*     * ***********************Methode static*************************** */

	public static function Pluginsuported() {
		$Pluginsuported = ['openzwave','rfxcom','edisio','mpower', 'mySensors', 'Zibasedom', 'virtual', 'camera','weather','philipsHue','enocean','wifipower','alarm','mode','apcupsd', 'btsniffer','dsc','rflink','mysensors','relaynet','remora','unipi','eibd','thermostat','netatmoThermostat','espeasy','jeelink','teleinfo','tahoma','protexiom','lifx','wattlet','rfplayer','openenocean'];
		return $Pluginsuported;
	}
	
	public static function PluginWidget() {
		$PluginWidget = ['alarm','camera','thermostat','netatmoThermostat','weather','mode'];	
		return $PluginWidget;
	}
	
	public static function PluginMultiInEqLogic(){
		$PluginMulti = ['LIGHT_STATE','ENERGY_STATE','FLAP_STATE','HEATING_STATE','SIREN_STATE','LOCK_STATE'];
		return $PluginMulti;
	}
	
	public static function LienAWS() {
		return 'http://195.154.56.168:8000/notif/';
	}
	
	public static function DisallowedPIN() {
		$DisallowedPIN = ['000-00-000','111-11-111','222-22-222','333-33-333','444-44-444','555-55-555','666-66-666','777-77-777','888-88-888','999-99-999','123-45-678','876-54-321'];
		return $DisallowedPIN;
	}
	
	public static function PluginToSend() {
		$PluginToSend=[];
		$plugins = plugin::listPlugin(true);
		$plugin_compatible = mobile::Pluginsuported();
		$plugin_widget = mobile::PluginWidget();
		foreach ($plugins as $plugin){
			$plugId = $plugin->getId();
			if ($plugId == 'mobile') {
				continue;
			} else if (in_array($plugId,$plugin_widget)) {
				array_push($PluginToSend, $plugId);
			} else if (in_array($plugId,$plugin_compatible) && !in_array($plugId,$plugin_widget) && config::byKey('sendToApp', $plugId, 1) == 1){
				array_push($PluginToSend, $plugId);
			} else if (!in_array($plugId,$plugin_compatible) && config::byKey('sendToApp', $plugId, 0) == 1){
				$subClasses = config::byKey('subClass', $plugId, '');
				if ($subClasses != ''){
					$subClassesList = explode(';',$subClasses);
					foreach ($subClassesList as $subClass){
						array_push($PluginToSend, $subClass);
					}
				}
				array_push($PluginToSend, $plugId);
			} else {
				continue;
			}
		}
		return $PluginToSend;
		
	}
	
	/**************************************************************************************/
	/*                                                                                    */
	/*            Permet de decouvrir tout les modules de la Jeedom compatible            */
	/*                                                                                    */
	/**************************************************************************************/

	public static function discovery_eqLogic($plugin = array(),$hash = null){
		$return = array();
		foreach ($plugin as $plugin_type) {
			$eqLogics = eqLogic::byType($plugin_type, true);
			if (is_array($eqLogics)) {
				foreach ($eqLogics as $eqLogic) {
					if($eqLogic->getObject_id() !== null && object::byId($eqLogic->getObject_id())->getDisplay('sendToApp', 1) == 1 && $eqLogic->getIsEnable() == 1 && ($eqLogic->getIsVisible() == 1 || in_array($eqLogic->getEqType_name(), self::PluginWidget()))){
						$eqLogic_array = utils::o2a($eqLogic);
						if(isset($eqLogic_array["configuration"]["localApiKey"])){
							$eqLogic_array["localApiKey"] = $eqLogic_array["configuration"]["localApiKey"];
						}
						unset($eqLogic_array['eqReal_id'],$eqLogic_array['configuration'], $eqLogic_array['specificCapatibilities'],$eqLogic_array['timeout'],$eqLogic_array['category'],$eqLogic_array['display']);
						$return[] = $eqLogic_array;
					}
				}
			}
		}
		return $return;
	}
	
	public static function discovery_cmd($plugin = array()){
		$return = array();
		$genericisvisible = array();
		foreach (jeedom::getConfiguration('cmd::generic_type') as $key => $info) {
		        if ($info['family'] !== 'Generic') {
		            array_push($genericisvisible, $key);
		        }
		}
		foreach ($plugin as $plugin_type) {
			$eqLogics = eqLogic::byType($plugin_type, true);
			if (is_array($eqLogics)) {
				foreach ($eqLogics as $eqLogic) {
                  	$i = 0;
                  if($eqLogic->getObject_id() !== null && object::byId($eqLogic->getObject_id())->getDisplay('sendToApp', 1) == 1 && $eqLogic->getIsEnable() == 1 && ($eqLogic->getIsVisible() == 1 || in_array($eqLogic->getEqType_name(), self::PluginWidget()))){
					foreach ($eqLogic->getCmd() as $cmd) {
                    	if($cmd->getDisplay('generic_type') != null && !in_array($cmd->getDisplay('generic_type'),['GENERIC_ERROR','DONT']) && ($cmd->getIsVisible() == 1 || in_array($cmd->getDisplay('generic_type'), $genericisvisible) || in_array($eqLogic->getEqType_name(), self::PluginWidget()))){
                      		$cmd_array = $cmd->exportApi();
                      					
							//Variables
							$maxValue = null;
							$minValue = null;
							$actionCodeAccess = null;
							$actionConfirm = null;
							$generic_type = null;
							$icon = null;
							$invertBinary = null;
							$title_disable = null;
							$title_placeholder = null;
							$message_placeholder = null;
								
							if(isset($cmd_array['configuration'])){
								$configuration = $cmd_array['configuration'];
								if(isset($configuration['maxValue'])){
									$maxValue = $configuration['maxValue'];
								}
								if(isset($configuration['minValue'])){
									$minValue = $configuration['minValue'];
								}
								if(isset($configuration['actionCodeAccess'])){
									$actionCodeAccess = $configuration['actionCodeAccess'];
								}
								if(isset($configuration['actionConfirm'])){
									$actionConfirm = $configuration['actionConfirm'];
								}
							}
							if(isset($cmd_array['display'])){
								$display = $cmd_array['display'];
								if(isset($display['generic_type'])){
									$generic_type = $display['generic_type'];
								}
								if(isset($display['icon'])){
									$icon = $display['icon'];
								}
								if(isset($display['invertBinary'])){
									$invertBinary = $display['invertBinary'];
								}
								if(isset($display['title_disable'])){
									$title_disable = $display['title_disable'];
								}
								if(isset($display['title_placeholder'])){
									$title_placeholder = $display['title_placeholder'];
								}
								if(isset($display['message_placeholder'])){
									$message_placeholder = $display['message_placeholder'];
								}
							}
							unset($cmd_array['isHistorized'],$cmd_array['configuration'], $cmd_array['template'], $cmd_array['display'], $cmd_array['html']);
							$cmd_array['configuration']['maxValue'] = $maxValue;
							if ($minValue != null) {
								$cmd_array['configuration']['minValue'] = $minValue;
							}
							$cmd_array['display']['generic_type'] = $generic_type;
							if ($icon != null) {
								$cmd_array['display']['icon'] = $icon;
							}
							if(isset($invertBinary)){
								if ($invertBinary != null) {
									$cmd_array['display']['invertBinary'] = $invertBinary;
								}
							}
							if(isset($title_disable)){
								if ($title_disable != null) {
									$cmd_array['display']['title_disable'] = $title_disable;
								}
							}
							if(isset($title_placeholder)){
								if ($title_placeholder != null) {
									$cmd_array['display']['title_placeholder'] = $title_placeholder;
								}
							}
							if(isset($message_placeholder)){
								if ($message_placeholder != null) {
									$cmd_array['display']['message_placeholder'] = $message_placeholder;
								}
							}
							if(isset($actionCodeAccess)){
								if($actionCodeAccess !== null ){
									if($actionCodeAccess !== ''){
										$cmd_array['configuration']['actionCodeAccess'] = true;
									}
								}
							}
							if(isset($actionConfirm)){
								if($actionConfirm !== null){
									if($actionConfirm == 1){
										$cmd_array['configuration']['actionConfirm'] = true;
									}
								}
							}
							if ($cmd_array['type'] == 'action'){
								unset($cmd_array['currentValue']);
							}
							if ($cmd_array['value'] == null || $cmd_array['value'] == ""){
								//unset($cmd_array['value']);
								$cmd_array['value'] == "0";
							}else{
								$cmd_array['value'] = str_replace("#","",$cmd_array['value']);	
							}
							if ($cmd_array['unite'] == null || $cmd_array['unite'] == ""){
								unset($cmd_array['unite']);
							}
							$cmds_array[] = $cmd_array;
                      		$i++;
                      	}
					}
                  	if($i > 0){
                    	$return = $cmds_array;
                    }
				}
                }
			}
		}
		return $return;
	}
	
	public static function discovery_multi($cmds) {
		$array_final = array();
		$tableData = mobile::PluginMultiInEqLogic();
		foreach ($cmds as &$cmd) {
			if(in_array($cmd['generic_type'], $tableData)){
				$keys = array_keys(array_column($cmds,'eqLogic_id'), $cmd['eqLogic_id']);
				$trueKeys = array_keys(array_column($cmds,'generic_type'), $cmd['generic_type']);
				//if(count($keys) > 1 && count($trueKeys) > 1){
					$result =  array_intersect($keys, $trueKeys);
					if(count($result) > 1){
						$array_final = array_merge_recursive($array_final, $result);
					}
				//}
				
			}
		}
		$dif = array();
		$array_cmd_multi = array();
		foreach ($array_final as &$array_fi){
			if(!in_array($array_fi, $dif)){
				array_push($dif, $array_fi);
				array_push($array_cmd_multi,$array_fi);
			}
		}
		
		return $array_cmd_multi;
	}
	
	public static function change_cmdAndeqLogic($cmds,$eqLogics){
		$plage_cmd = mobile::discovery_multi($cmds);
		$eqLogic_array = array();
		$nbr_cmd = count($plage_cmd);
		log::add('mobile', 'debug', 'plage cmd > '.json_encode($plage_cmd).' // nombre > '.$nbr_cmd);
		if($nbr_cmd != 0){
			$i = 0;
			while($i < $nbr_cmd){
				//log::add('mobile', 'info', 'nbr cmd > '.$i.' // id > '.$plage_cmd[$i]);
				$eqLogic_id = $cmds[$plage_cmd[$i]]['eqLogic_id'];
				$name_cmd = $cmds[$plage_cmd[$i]]['name'];
				foreach ($eqLogics as &$eqLogic){
					if($eqLogic['id'] == $eqLogic_id){
						$eqLogic_name = $eqLogic['name'].' / '.$name_cmd;
					}
				}
				//log::add('mobile', 'debug', 'nouveau nom > '.$eqLogic_name);
				$id = $cmds[$plage_cmd[$i]]['id'];
				$new_eqLogic_id = '999'.$eqLogic_id.''.$id;
				$cmds[$plage_cmd[$i]]['eqLogic_id'] = $new_eqLogic_id;
				$keys = array_keys(array_column($cmds,'eqLogic_id'),$eqLogic_id);
				$nbr_keys = count($keys);
				$j = 0;
				while($j < $nbr_keys){
					if($cmds[$keys[$j]]['value'] == $cmds[$plage_cmd[$i]]['id'] && $cmds[$keys[$j]]['type'] == 'action'){
						//log::add('mobile', 'debug', 'Changement de l\'action > '.$cmds[$keys[$j]]['id']);
						$cmds[$keys[$j]]['eqLogic_id'] = $new_eqLogic_id;
					}
					$j++;
				}
				array_push($eqLogic_array,array($eqLogic_id, $new_eqLogic_id, $eqLogic_name));
				$i++;
			}
			
			$column_eqlogic = array_column($eqLogics,'id');
			foreach ($eqLogic_array as &$eqlogic_array_one) {
				$keys = array_keys($column_eqlogic, $eqlogic_array_one[0]);
				$new_eqLogic = $eqLogics[$keys[0]];
				$new_eqLogic['id'] = $eqlogic_array_one[1];
				$new_eqLogic['name'] = $eqlogic_array_one[2];
				array_push($eqLogics, $new_eqLogic);
			}		
		}
		$new_cmds = array('cmds' => $cmds);
		$new_eqLogic = array('eqLogics' => $eqLogics);
		$news = array($new_cmds,$new_eqLogic);
		return $news;
	}
	
	public static function discovery_object() {
		$all = utils::o2a(object::all());
		$return = array();
		foreach ($all as &$object){
			if (isset($object['display']['sendToApp']) && $object['display']['sendToApp'] == "0") {
				continue;
			} else {
				unset($object['configuration'],$object['display']['tagColor'], $object['display']['tagTextColor']);
				$return[]=$object;
			}
		}
		return $return;
	}
	 
	public static function discovery_scenario() {
		$all = utils::o2a(scenario::all());
		$return = array();
		foreach ($all as &$scenario){
			if (isset($scenario['display']['sendToApp']) && $scenario['display']['sendToApp'] == "0") {
				continue;
			} else {
				if ($scenario['display']['name'] != ''){
					$scenario['name'] = $scenario['display']['name'];
				}
				unset($scenario['mode'],$scenario['schedule'], $scenario['scenarioElement'],$scenario['trigger'],$scenario['timeout'],$scenario['description'],$scenario['configuration'],$scenario['type'],$scenario['display']['name']);
				if ($scenario['display'] == [] || $scenario['display']['icon'] == ''){
					unset($scenario['display']);
				}
				$return[]=$scenario;
			}	
		}
		return $return;
	}
	
	public static function discovery_message() {
		$all = utils::o2a(message::all());
		$return = array();
		foreach ($all as &$message){
				$return[]=$message;	
		}
		return $return;
	}
	
	public static function discovery_plan() {
		$all = utils::o2a(planHeader::all());
		$return = array();
		foreach ($all as &$plan){
          		if ($plan['image'] !== undefined){
					unset($plan['image']);
				}
          		$return[]=$plan;
		}
		return $return;
	}


	public static function delete_object_eqlogic_null($objectsATraiter,$eqlogicsATraiter){
		$retour = array();
		foreach ($objectsATraiter as &$objectATraiter){
			$id_object = $objectATraiter['id'];
			foreach ($eqlogicsATraiter as &$eqlogicATraiter){
				if ($id_object == $eqlogicATraiter['object_id']){
					array_push($retour,$objectATraiter);
					break;
				}
			}
		}
		return $retour;
	}
	/**************************************************************************************/
	/*                                                                                    */
	/*                         Permet de creer le Json du QRCode                          */
	/*                                                                                    */
	/**************************************************************************************/

	public function getQrCode() {
		$interne = network::getNetworkAccess('internal');
		$externe = network::getNetworkAccess('external');
		$user = $this->getConfiguration('affect_user');
		
		if($interne == null || $interne == 'http://:80' || $interne == 'https://:80'){
			$retour = 'internalError';
		}else if($externe == null || $externe == 'http://:80' || $externe == 'https://:80'){
			$retour = 'externalError';
		}else if($user == ''){
			$retour = 'UserError';
		}else{
			$key = $this->getLogicalId();
			$request_qrcode = array(
			'eqLogic_id' => $this->getId(),
				'url_internal' => $interne,
				'url_external' => $externe,
				'Iq' => $key
			);
			if ($user != '') {
				$username = user::byId($this->getConfiguration('affect_user'));
				if (is_object($username)) {
					$request_qrcode['username'] = $username->getLogin();
					$request_qrcode['apikey'] = $username->getHash();
				}
			}
			$retour = 'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl='.json_encode($request_qrcode);
		}
		return $retour;
	}
	
	/**************************************************************************************/
	/*                                                                                    */
	/*                                 Pour les notifications                             */
	/*                                                                                    */
	/**************************************************************************************/
	
	
	public static function jsonPublish($os,$titre,$message,$badge = 'null',$type,$idNotif,$answer,$timeout){
		$dateNotif = date("Y-m-d H:i:s");
		if($timeout != 'nok'){
			$timeout = date('Y-m-d H:i:s', strtotime("$dateNotif + $timeout SECONDS"));
		}
		if($os == 'ios'){
				$addAsk = '';
			if($type == 'ask_Text'){
				$addAsk = '\"category\":\"TEXT_CATEGORY\",\"answer\":\"'.$answer.'\",\"timeout\":\"'.$timeout.'\",';
			}
			if($badge == 'null'){
				$publish = '{"default": "Erreur de texte de notification","APNS": "{\"aps\":{\"content-available\":\"1\",'.$addAsk.'\"alert\": {\"title\":\"'.$titre.'\",\"body\":\"'.$message.'\"},\"badge\":'.$badge.',\"sound\":\"silence.caf\",\"date\":\"'.$dateNotif.'\",\"idNotif\":\"'.$idNotif.'\"}}"}';
			}else{
				$publish = '{"default": "test", "APNS": "{\"aps\":{\"content-available\":\"1\",'.$addAsk.'\"alert\": {\"title\":\"'.$titre.'\",\"body\":\"'.$message.'\"},\"sound\":\"silence.caf\",\"date\":\"'.$dateNotif.'\",\"idNotif\":\"'.$idNotif.'\"}}"}';
			}
		}else if($os == 'android'){
			$publish = '{"default": "Erreur de texte de notification", "GCM": "{ \"data\": {\"notificationId\":\"'.rand(3, 5).'\",\"title\":\"'.$titre.'\",\"text\":\"'.$message.'\",\"vibrate\":\"true\",\"lights\":\"true\",\"idNotif\":\"'.$idNotif.'\"}}"}';
		}else if($os == 'microsoft'){
			
		}
		return $publish;
	}
	
	public static function notification($arn,$os,$titre,$message,$badge = 'null',$type,$idNotif,$answer,$timeout){
		log::add('mobile', 'debug', 'notification en cours !');
		if($badge == 'null'){
			$publish = mobile::jsonPublish($os,$titre,$message,$badge,$type,$idNotif,$answer,$timeout);
		}else{
			$publish = mobile::jsonPublish($os,$titre,$message,$badge,$type,$idNotif,$answer,$timeout);
		}
		log::add('mobile', 'debug', 'JSON envoyé : '.$publish);
		$post = [
			'id' => $idNotif,
			'type' => $os,
			'arn' => $arn,
			'publish' => $publish 
		];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,mobile::LienAWS());
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post);            
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        log::add('mobile', 'debug', 'notification resultat > '.$server_output);
	}
	
	/**************************************************************************************/
	/*                                                                                    */
	/*                         Permet de creer l'ID Unique du téléphone                   */
	/*                                                                                    */
	/**************************************************************************************/
	public function postInsert() {
		$key = config::genKey(32);
		$this->setLogicalId($key);
		$this->save();
	}
	
	public function postSave() {
		$cmd_Notif = $this->getCmd(null, 'notif');
        if (!is_object($cmd_Notif)) {
			$cmd_Notif = new mobileCmd();
			$cmd_Notif->setLogicalId('notif');
			$cmd_Notif->setName(__('Notification', __FILE__));
			$cmd_Notif->setOrder(0);
			$cmd_Notif->setEqLogic_id($this->getId());
			$cmd_Notif->setDisplay('generic_type', 'GENERIC_ACTION');
			$cmd_Notif->setType('action');
			$cmd_Notif->setSubType('message');
		}
		$cmd_Notif->setIsVisible(1);
		$cmd_Notif->save();
		
		$cmd_AskText = $this->getCmd(null, 'ask_Text');
    	if (is_object($cmd_AskText)) {
			$cmd_AskText->remove();
		}
		
		$cmd_AskYN = $this->getCmd(null, 'ask_YN');
    	if (is_object($cmd_AskYN)) {
			$cmd_AskYN->remove();
		}
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
		$eqLogic = $this->getEqLogic();
		$arn = $eqLogic->getConfiguration('notificationArn', null);
		$os = $eqLogic->getConfiguration('type_mobile', null);
		$idNotif = $eqLogic->getConfiguration('idNotif', 0);
		$askType = 'notif';
		$timeout = 'nok';
        if ($this->getType() != 'action') {
			return;
		}
		log::add('mobile', 'debug', 'Notif > '.json_encode($_options).' / '.$eqLogic->getId().' / '.$this->getLogicalId().' / idNotif ='.$idNotif, 'config');
		
		/************ NOTIF ***********/
		if($this->getLogicalId() == 'notif') {
			if($_options['title'] == '' || $_options['title'] == $_options['message'] || $_options['title'] == ' '){
				$_options['title'] = 'Jeedom';
			}
			$answer = null;
			if($_options['answer']){
				$askType = "ask_Text";
				$answer = join(';',$_options['answer']);
			}
			if($_options['timeout']){
				$timeout = $_options['timeout'];
			}
			log::add('mobile', 'debug', 'Commande de notification '.$askType, 'config');
			if($arn != null && $os != null){
				$idNotif = $idNotif+1;
				$eqLogic->setConfiguration('idNotif', $idNotif);
				$eqLogic->save();
				mobile::notification($arn,$os,$_options['title'],$_options['message'],null,$askType,$idNotif,$answer,$timeout);
				log::add('mobile', 'debug', 'Action : Envoi d\'une configuration ', 'config');
			}else{
				log::add('mobile', 'debug', 'ARN non configuré ', 'config');	
			}
		}
		/************ FIN NOTIF ************/
	}

	/*     * **********************Getteur Setteur*************************** */
}

?>
