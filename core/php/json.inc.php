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
	if(isset($_GET['api'])){
	$api = $_GET['api'];
	}
	if(isset($_GET['demande'])){
	$demande = $_GET['demande'];
	}
	if(isset($_GET['id'])){
	$id = $_GET['id'];
	}
	if(isset($_GET['valeur'])){
	$valeur = $_GET['valeur'];
	}
	if(isset($_GET['date_archi'])){
	$date_archi = $_GET['date_archi'];
	}
	if(isset($_GET['id_mobile'])){
	$id_mobile = $_GET['id_mobile'];
	}
	if(isset($_GET['json_archi'])){
	$json_archi = $_GET['json_archi'];
	}
	//$api = $_GET['api'];
	//$demande = $_GET['demande'];
	//$id = $_GET['id'];
	//$valeur = $_GET['valeur'];
	// Variable Archi
	//$date_archi = $_GET['date_archi'];
	//$id_mobile = $_GET['id_mobile'];
	//$json_archi = $_GET['json_archi'];

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
	$json_complet = array(mobile::decouverte('valide','all'),mobile::pieces('all'),mobile::scenario('all'),mobile::Plugin_valide_func());
	echo json_encode(array('return' => $json_complet));
}elseif($demande == 'test'){
	$nginx = config::byKey('nodeJsKey');
	$json_test = array('return' => 'perfect','nodekey' => $nginx);
	echo json_encode($json_test);
	//echo 'perfect';
}elseif($demande == 'archi'){
	echo mobile::archi('sauvegarde',$date_archi,$id_mobile,$json_archi);
}elseif($demande == 'commande'){
	echo json_encode(mobile::decouverte('valide','info'));
}elseif($demande == 'cmd'){
	if($valeur !== null){
	$json_cmd = mobile::cmd($id,$valeur);
	}else{
		$json_cmd = mobile::cmd($id);
	}
	echo json_encode($json_cmd);
}elseif($demande == 'plugin'){
	echo json_encode(mobile::Plugin_valide_func());
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