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

if (!isConnect('admin')) {
	throw new Exception('401 Unauthorized');
  
  
}
$eqLogics = mobile::byType('mobile');
?>
  
  	<ul class="nav nav-tabs" style="padding-left:8px">
    	<li><a class="cursor" id="bt_returnMenu" style="width:32px;"><i class="fas fa-arrow-circle-left"></i></a></li>
  		<li class="active">
            	<a class="cursor" id="bt_returnMenu" style="width:32px;"><i class="fas fa-arrow-circle-left"></i></a>
  		</li>
  	</ul>


  
<table class="table table-condensed tablesorter" id="table_menuCustom">
	<thead>
		<tr>
			<th>{{PLOUP}}</th>
			<th>{{ID}}</th>
			<th>{{User}}</th>
		</tr>
	</thead>
	<tbody>
	 <?php

foreach ($eqLogics as $eqLogic) {
	$userId = $eqLogic->getConfiguration('affect_user');
	$userType = user::byId($userId);
	if(is_object($userType)){
		$username = $userType->getLogin();
		echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '">' . $eqLogic->getHumanName(true) . '</a></td>';
		echo '<td><span class="label label-info">' . $eqLogic->getId() . '</span></td>';
		echo '<td><span class="label label-info">' . $username . '</span></td></tr>';
	}else{
		echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
		echo '<td><span class="label label-info">' . $eqLogic->getId() . '</span></td>';
		echo '<td><span class="label label-info">{{Utilisateur non trouvé}}</span></td></tr>';
	}
}
?>
	</tbody>
</table>