<<<<<<< HEAD
function mobile_update(){
    	foreach (eqLogic::byType('mobile') as $mobile){
			if($mobile->getLogicalId() == null || $mobile->getLogicalId() == ""){
				$mobile->remove();
			}
		}
}
=======
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
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function mobile_update(){
	$ios = 0;
    	foreach (eqLogic::byType('mobile') as $mobile){
		if($mobile->getLogicalId() == null || $mobile->getLogicalId() == ""){
			$mobile->remove();
		}else{
			if($mobile->getConfiguration('type_mobile') == "ios"){
				$ios = 1;
			}
		}
	}
	if($ios == 1){
		$pluginmobile = plugin::byId('mobile');
		$pluginmobile->dependancy_install();
	}
}
?>
>>>>>>> beta
