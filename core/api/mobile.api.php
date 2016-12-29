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
if (!is_object($jsonrpc)) {
	throw new Exception(__('JSONRPC object not defined', __FILE__), -32699);
}

$params = $jsonrpc->getParams();
$PluginToSend = mobile::PluginToSend();

if ($jsonrpc->getMethod() == 'sync') {
	log::add('mobile', 'debug', 'Demande de Sync');
	$sync_new = mobile::change_cmdAndeqLogic(mobile::discovery_cmd($PluginToSend),mobile::discovery_eqLogic($PluginToSend));
	log::add('mobile', 'debug', 'Sync cmd et eqlogics > '.json_encode($sync_new));
	$eqLogics = $sync_new[1];
	$cmds = $sync_new[0];
	
	$sync_array = array(
		'eqLogics' => $eqLogics['eqLogics'],
		'cmds' => $cmds['cmds'],
		'objects' => mobile::discovery_object(),
		'scenarios' => mobile::discovery_scenario(),
		'messages' => mobile::discovery_message(),
		'config' => array('datetime' => getmicrotime()),
	);
	$jsonrpc->makeSuccess($sync_array);
}

if ($jsonrpc->getMethod() == 'sync_homebridge') {
	log::add('mobile', 'debug', 'Demande de Sync Homebridge');
	$sync_new = mobile::change_cmdAndeqLogic(mobile::discovery_cmd($PluginToSend),mobile::discovery_eqLogic($PluginToSend));
	log::add('mobile', 'debug', 'Sync cmd et eqlogics > '.json_encode($sync_new));
	$eqLogics = $sync_new[1]['eqLogics'];
	$cmds = $sync_new[0];
	$i = 0;
	while($i <= count($eqLogics)){
		if(isset($eqLogics[$i]["configuration"]["sendToHomebridge"])){
			if($eqLogics[$i]["configuration"]["sendToHomebridge"] == 0){
				unset($eqLogics, $i);	
			}
		}
	$i++;	
	}
	
	$sync_array = array(
		'eqLogics' => $eqLogics,
		'cmds' => $cmds['cmds'],
		'objects' => mobile::discovery_object(),
		'scenarios' => mobile::discovery_scenario(),
		'config' => array('datetime' => getmicrotime()),
	);
	
	log::add('mobile', 'debug', 'Demande de Sync Homebridge');
	$jsonrpc->makeSuccess($sync_array);
}

if ($jsonrpc->getMethod() == 'Iq') {
	$platform = $params['platform'];
	$user = user::byHash($params['apikey']);
	$userId = $user->getId();
	$mobile = new eqLogic;
	$mobile->setEqType_name('mobile');
	$mobile->setName($platform.'-'.config::genKey(3));
	$mobile->setConfiguration('type_mobile',$platform);
	$mobile->setConfiguration('affect_user',$userId);
	$mobile->setIsEnable(1);
	$mobile->save();
	$jsonrpc->makeSuccess($mobile->getLogicalId());	
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

throw new Exception(__('Aucune demande', __FILE__));
?>
