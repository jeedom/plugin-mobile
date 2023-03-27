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

<table class="table table-condensed tablesorter" id="table_healthneato">
	<thead>
		<tr>
			<th>{{Plateforme}}</th>
			<th>{{Equipement}}</th>
			<th>{{ID}}</th>
			<th>{{User}}</th>
			<th>{{Depuis}}</th>
		</tr>
	</thead>
	<tbody>
	 <?php
foreach ($eqLogics as $eqLogic) {
  	$typeMobile = $eqLogic->getConfiguration('type_mobile');
	$file = 'plugins/mobile/core/img/' . $typeMobile . '.png';
	if (file_exists($file)) {
		$path = 'plugins/mobile/core/img/' . $typeMobile . '.png';
		$img = '<img src="' . $path . '" width="35px" /> '.$typeMobile;
	} else {
		$path = 'plugins/mobile/core/img/mobile_icon.png';
		$img = '<img src="' . $path . '" width="35px" /> '.$typeMobile;
	}
	$userId = $eqLogic->getConfiguration('affect_user');
	$userType = user::byId($userId);
	if(is_object($userType)){
		$username = $userType->getLogin();
		echo '<tr><td>' . $img . '</td><td><a href="' . $eqLogic->getLinkToConfiguration() . '">' . $eqLogic->getHumanName(true) . '</a></td>';
		echo '<td><span class="label label-info">' . $eqLogic->getId() . '</span></td>';
		echo '<td><span class="label label-info">' . $username . '</span></td>';
		echo '<td><span class="label label-info">' . $eqLogic->getConfiguration('createtime') . '</span></td></tr>';
	}else{
		echo '<tr><td>' . $img . '</td><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
		echo '<td><span class="label label-info">' . $eqLogic->getId() . '</span></td>';
		echo '<td><span class="label label-info">{{Utilisateur non trouvé}}</span></td>';
		echo '<td><span class="label label-info">' . $eqLogic->getConfiguration('createtime') . '</span></td></tr>';
	}
}
?>
	</tbody>
</table>
