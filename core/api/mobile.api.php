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
$PluginsuportedMobile = $params['allowPlugin'];
$Pluginsuported = ['openzwave','rfxcom','edisio','mpower', 'ipx800', 'mySensors', 'Zibasedom', 'virtual', 'camera','netatmoWeather','weather','philipsHue','enocean','wifipower','alarm','mode','apcupsd', 'btsniffer','dsc','h801','rflink','mysensors','relaynet','remora','unipi','playbulb','doorbird','eibd','ipx800','ipx800v2','boxio','thermostat','netatmoThermostat'];
if ($jsonrpc->getMethod() == 'sync') {
	$jsonrpc->makeSuccess(array(
		'eqLogics' => mobile::discovery($Pluginsuported),
		'objects' => utils::o2a(object::all()),
		'scenarios' => utils::o2a(scenario::all()),
		'config' => array('datetime' => getmicrotime()),
	));
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