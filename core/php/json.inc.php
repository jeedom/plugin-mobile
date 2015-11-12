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
/*                 Verification clef api                                              */
/*                                                                                    */
/**************************************************************************************/
if (!jeedom::apiAccess(init('api'))) {
	connection::failed();
	echo json_encode(array('return' => 'API_failed'));
	die();
}
/**************************************************************************************/
/*                                                                                    */
/*                             On traite la demande JSON                              */
/*                                                                                    */
/**************************************************************************************/
switch (init('demande')) {
	case 'complet':
		echo json_encode(array('return' => array(mobile::discovery('valide', 'all'), mobile::object('all'), mobile::scenario('all'), mobile::getAllowPlugin())));
		break;
	case 'test':
		echo json_encode(array('return' => 'perfect', 'nodekey' => config::byKey('nodeJsKey')));
		break;
	case 'archi':
		echo mobile::archi('sauvegarde', init('date_archi'), init('id_mobile'), init('json_archi'));
		break;
	case 'commande':
		echo json_encode(mobile::discovery('valide', 'info'));
		break;
	case 'cmd':
		echo json_encode(mobile::cmd(init('id'), init('valeur', null)));
		break;
	case 'plugin':
		echo json_encode(mobile::Plugin_valide_func());
		break;
	default:
		echo json_encode(array('return' => 'no_command'));
		break;
}
/**************************************************************************************/
/*                                                                                    */
/*                                          END                                       */
/*                                                                                    */
/**************************************************************************************/
$out = ob_get_clean();
echo trim(substr($out, strpos($out, '{')));
?>