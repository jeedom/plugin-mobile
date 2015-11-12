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
		'eqLogics' => mobile::discovery(),
		'objects' => mobile::object(),
		'scenarios' => mobile::scenario(),
		'plugins' => mobile::getAllowPlugin(),
		'nodekey' => config::byKey('nodeJsKey'),
	));
}

if ($jsonrpc->getMethod() == 'eqLogics') {
	$jsonrpc->makeSuccess(mobile::discovery(true));
}

if ($jsonrpc->getMethod() == 'plugins') {
	$jsonrpc->makeSuccess(mobile::getAllowPlugin());
}
throw new Exception(__('Aucune demande', __FILE__));
?>