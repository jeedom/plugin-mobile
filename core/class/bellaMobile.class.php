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

    public static function jsonBella(){
        $arrayBella = array(
            0 => array(
                0 => array(
                    'size' => 1,
                    'type' => 'onOff',
                  	'idEvent' => [7],
                    'options' => array(
                        'on' => 0,
                        'title' => "Prise",
                        'value' => null,
                        'icons' => array(
                            'on' => array('type' => "jeedomapp", 'name' => "prise", 'color' => "#f7d959"),
                            'off' => array('type' => "jeedomapp", 'name' => "prise-off", 'color' => "#a4a4a3")
                        ),
                      	'actions' => array(
                        	'on' => array(
                            	'id' => 8
                            ),
                          	'off' => array(
                            	'id' => 9
                            )
                        ),
                        'iconBlur' => false
                    )
                ),
                1 => array(
                    'size' => 1,
                    'type' => 'onOff',
                    'options' => array(
                        'on' => 0,
                        'title' => "Lumière salon",
                        'value' => null,
                        'icons' => array(
                            'on' => array('type' => "jeedomapp", 'name' => "ampoule-on", 'color' => "#f7d959"),
                            'off' => array('type' => "jeedomapp", 'name' => "ampoule-off", 'color' => "#a4a4a3")
                        ),
                        'iconBlur' => false
                    )
                ),
                2 => array(
                    'size' => 1,
                    'type' => 'info',
                    'options' => array(
                        'on' => 0,
                        'title' => "Température",
                        'value' => null,
                        'icons' => array(
                            'on' => array('type' => "jeedomapp", 'name' => "temperature", 'color' => "#00ff00"),
                            'off' => array('type' => "jeedomapp", 'name' => "temperature", 'color' => "#a4a4a3")
                        ),
                        'iconBlur' => false
                    )
                ),
                4 => array(
                    'size' => 1,
                    'type' => 'info',
                    'options' => array(
                        'on' => 0,
                        'title' => "Présence",
                        'value' => null,
                        'icons' => array(
                            'on' => array('type' => "jeedom", 'name' => "mouvement", 'color' => "#FF0000"),
                            'off' => array('type' => "jeedom", 'name' => "mouvement", 'color' => "#a4a4a3")
                        ),
                        'iconBlur' => true
                    )
                )
            ),
            1 => array(
                5 => array(
                    'size' => 2,
                    'type' => 'meteo',
                    'options' => array(
                        'on' => 0,
                        'title' => "Météo",
                        'value' => null,
                        'icons' => array(
                            'on' => array('type' => "jeedomapp", 'name' => "meteo", 'color' => "#FF0000"),
                            'off' => array('type' => "jeedomapp", 'name' => "meteo", 'color' => "#a4a4a3")
                        ),
                        'iconBlur' => true
                    )
                ),
                6 => array(
                    'size' => 1,
                    'type' => 'test',
                    'options' => array()
                ),
                7 => array(
                    'size' => 1,
                    'type' => 'test',
                    'options' => array()
                )
            ),
            2 => array(
                8 => array(
                    'size' => 4,
                    'type' => 'test',
                    'options' => array()
                )
            )
        );

        return json_encode($arrayBella);

    }

}