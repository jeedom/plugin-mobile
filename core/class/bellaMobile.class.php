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

class bellaMobile extends eqLogic
{
    function recupAllCmd($jeeObject_id){
        $arrayBella = array();
        $jeeObject = jeeObject::byId();
        $eqLogics = $jeeObject->getEqLogic();
        foreach ($eqLogics as $eqLogic) {
            $cmds = $eqLogic->getCmd();
            foreach($cmds as $cmd){
                $arrayBella[$cmd->getId()] = array();
                $arrayBella[$cmd->getId()]['options'] = array();
                $arrayBella[$cmd->getId()]['options']['name'] = $cmd->getName();
                $arrayBella[$cmd->getId()]['options']['value'] = $cmd->getDisplay('value');
                $arrayBella[$cmd->getId()]['options']['genericType'] = $cmd->getGenericType();
            }
        }
    }

	function syncBella(){

    }
}