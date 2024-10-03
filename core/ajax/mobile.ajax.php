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
try {
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}


	if (init('action') == 'createJsonBellaMobile') {
        $configArray = init('config');
        log::add('mobile', 'debug', 'CONFIGARRAYENTER ' . json_encode($configArray));
        $return = mobile::jsonTransformForBella($configArray);
        if($return){
            ajax::success();
        }else{
            ajax::error();
        }
    }


	if (init('action') == 'updatemobile') {
		mobile::updatemobile();
		ajax::success();
	}

	if (init('action') == 'constructMenu') {
		$reponse = mobile::constructMenu(init('eqId'));
		ajax::success($reponse);
	}


	if (init('action') == 'getEqLogicConfigs') {

		$eqLogic = eqLogic::byId(intval(init('eqId')));
		if (is_object($eqLogic)) {
			$j = 0;
			$arrayMenuConfig = array();
			for ($i = 1; $i < 5; $i++) {
				$arrayTemp = [];
				${'spanIcon' . $i} = $eqLogic->getConfiguration('spanIcon' . $i, 'pasencorela');
				${'renameIcon' . $i} = $eqLogic->getConfiguration('renameIcon' . $i, 'pasencorela');
				${'selectNameMenu' . $i} = $eqLogic->getConfiguration('selectNameMenu' . $i, 'pasencorela');
				array_push($arrayMenuConfig, [${'spanIcon' . $i}, ${'renameIcon' . $i}, ${'selectNameMenu' . $i}]);
				$j++;
			}
			log::add('mobile', 'debug', 'RETURNAJAXGETCONFIG ' . json_encode($arrayMenuConfig));
		}
		ajax::success($arrayMenuConfig);
	}

	if (init('action') == 'menuDefault') {
		mobile::handleMenuDefaultBySelect(init('eqId'), init('eqIdDefault'));
		ajax::success();
	}


	if (init('action') == 'getQrCode') {
		$eqLogic = mobile::byId(init('id'));
		if (!is_object($eqLogic)) {
			throw new Exception(__('Equipement non trouvé : ', __FILE__) . init('id'));
		} else {
			ajax::success($eqLogic->getQrCode());
		}
	}

	if (init('action') == 'getQrCodeV2') {
		$user = user::byId(init('chooseUser'));
		if (!is_object($user)) {
			throw new Exception(__('User inexistant : ', __FILE__));
		} else {
			ajax::success(mobile::getQrCodeV2($user->getId()));
		}
	}




	if (init('action') == 'regenConfig') {
		mobile::makeTemplateJson();
		ajax::success();
	}


	if (init('action') == 'getSaveDashboard') {
		$iq = init('iq');
		$jsonDashboard = mobile::getSaveJson($iq, 'dashboard');
		if ($jsonDashboard == "") {
			$reponse = false;
		} else {
			$reponse = true;
		}
		ajax::success($reponse);
	}

	if (init('action') == 'getSaveFavDash') {
		$iq = init('iq');
		$jsonFavDash = mobile::getSaveJson($iq, 'favdash');
		if ($jsonFavDash == "") {
			$reponse = false;
		} else {
			$reponse = true;
		}
		ajax::success($reponse);
	}

	if (init('action') == 'savescenario') {
		$id = init('id');
		$sendApp = init('valueSend');
		$scenario = scenario::byId($id);
		if (!is_object($scenario)) {
			throw new Exception(__('scenario non trouvé', __FILE__));
		}
		$scenario->setDisplay("sendToApp", $sendApp);
		$scenario->save();
		ajax::success();
	}

	throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
} catch (Exception $e) {
	ajax::error(displayException($e), $e->getCode());
}