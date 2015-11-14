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

require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
global $jsonrpc;
if (!is_object($jsonrpc)) {
	throw new Exception(__('JSONRPC object not defined', __FILE__), -32699);
}
$params = $jsonrpc->getParams();

if ($jsonrpc->getMethod() == 'sync') {
	$jsonrpc->makeSuccess(array(
		'eqLogics' => mobile::discovery($params['allowPlugin']),
		'objects' => mobile::object(),
		'scenarios' => mobile::scenario(),
		'config' => array('datetime' => strtotime('now'), 'nodeJsKey' => config::byKey('nodeJsKey')),
	));
}

if ($jsonrpc->getMethod() == 'updateEqLogicValue') {
	$eqLogic = eqLogic::byId($params['id']);
	if (!is_object($eqLogic)) {
		throw new Exception(__('EqLogic inconnu : ', __FILE__) . $params['id']);
	}
	$jsonrpc->makeSuccess(mobile::buildEqlogic($eqLogic));
}

if ($jsonrpc->getMethod() == 'updateCmdValue') {
	$cmd = cmd::byId($params['id']);
	if (!is_object($cmd)) {
		throw new Exception(__('Cmd inconnu : ', __FILE__) . $params['id']);
	}
	$jsonrpc->makeSuccess(mobile::getCmdValue($cmd));
}

if ($jsonrpc->getMethod() == 'updateObjectValue') {
	$object = object::byId($params['id']);
	if (!is_object($object)) {
		throw new Exception(__('Object inconnu : ', __FILE__) . $params['id']);
	}
	$return = array();
	foreach ($object->getEqLogic() as $eqLogic) {
		if (!in_array($eqLogic->getEqType_name(), $params['allowPlugin'])) {
			continue;
		}
		$return[] = mobile::buildEqlogic($eqLogic);
	}
	$jsonrpc->makeSuccess($return);
}

if ($jsonrpc->getMethod() == 'changes') {
	$return = array('datetime' => strtotime('now'), 'result' => array());
	$cache = cache::byKey('nodejs_event');
	$values = json_decode($cache->getValue('[]'), true);
	if (count($values) > 0) {
		foreach ($values as $value) {
			if ($value['datetime'] <= $params['datetime']) {
				break;
			}
			$return['result'][] = $value;
		}
	}
	$jsonrpc->makeSuccess($return);
}

throw new Exception(__('Aucune demande', __FILE__));
?>