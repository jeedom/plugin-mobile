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
 
 // on creer les variable de reception
 $api = $_GET['api'];
 $demande = $_GET['demande'];
 $id = $_GET['id'];
 
 // Variable Archi
 $date_archi = $_GET['date_archi'];
 $id_mobile = $_GET['id_mobile'];
 $json_archi = $_GET['json_archi'];

 // On teste l'apiKey de la box
 if ($api != config::byKey('api') || config::byKey('api') == '') {
	connection::failed();
	echo "{'return':'API_failed'}";
	die();
}

// On teste les demmandes effectuées
if ($demande == 'complet'){
	//Premiere demande faite par l'application
	echo mobile::decouverte('valide');
	echo mobile::pieces('all');
	echo mobile::scenario('all');
}elseif($demande == 'test'){
	echo "{'return':'perfect'}";
}elseif($demande == 'archi'){
	echo mobile::archi($date_archi,$id_mobile,$json_archi);
}else{
	echo "{'return':'no_command'}";
}



$out = ob_get_clean();
echo trim(substr($out, strpos($out, '{')));
?>