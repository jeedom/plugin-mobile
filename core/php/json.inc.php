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
 
 header('Content-type: application/json');
 ob_start();
 require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
 
	/**************************************************************************************/
	/*                                                                                    */
	/*                 On créé les Variables que l'on reçoit par le mobile                */
	/*                                                                                    */
	/**************************************************************************************/
	$api = $_GET['api'];
	$demande = $_GET['demande'];
	$id = $_GET['id'];
	// Variable Archi
	$date_archi = $_GET['date_archi'];
	$id_mobile = $_GET['id_mobile'];
	$json_archi = $_GET['json_archi'];

	/**************************************************************************************/
	/*                                                                                    */
	/*                         On test l'API key de la demande                            */
	/*                                                                                    */
	/**************************************************************************************/
 if ($api != config::byKey('api') || config::byKey('api') == '') {
	connection::failed();
	echo "{'return':'API_failed'}";
	die();
}

	/**************************************************************************************/
	/*                                                                                    */
	/*                             On traite la demande JSON                              */
	/*                                                                                    */
	/**************************************************************************************/
if ($demande == 'complet'){
	$json_complet = array(mobile::decouverte('valide'),mobile::pieces('all'),mobile::scenario('all'));
	echo json_encode(array('return' => $json_complet));
}elseif($demande == 'test'){
	//$json_test = array('return' => 'perfect');
	//echo json_encode($json_test);
	echo 'perfect';
}elseif($demande == 'archi'){
	echo mobile::archi($date_archi,$id_mobile,$json_archi);
}else{
	$json_test = array('return' => 'no_command');
	echo json_encode($json_test);
}


	/**************************************************************************************/
	/*                                                                                    */
	/*                                          END                                       */
	/*                                                                                    */
	/**************************************************************************************/
$out = ob_get_clean();
echo trim(substr($out, strpos($out, '{')));
?>