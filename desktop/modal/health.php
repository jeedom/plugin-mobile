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
			<th>{{Module}}</th>
			<th>{{ID}}</th>
			<th>{{User}}</th>
			<th>{{Date création}}</th>
		</tr>
	</thead>
	<tbody>
	 <?php
foreach ($eqLogics as $eqLogic) {
	$file = 'plugins/mobile/docs/images/' . $eqLogic->getConfiguration('type_mobile') . '.png';
	if (file_exists($file)) {
		$path = 'plugins/mobile/docs/images/' . $eqLogic->getConfiguration('type_mobile') . '.png';
		$img = '<img src="' . $path . '" height="55" width="55" />';
	} else {
		$path = 'plugins/mobile/docs/images/mobile_icon.png';
		$img = '<img src="' . $path . '" height="55" width="55" />';
	}
	$userId = $eqLogic->getConfiguration('affect_user');
	$userType = user::byId($userId);
	if(is_object($userType)){
		$username = $userType->getLogin();
		echo '<tr><td>' . $img . '</td><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
		echo '<td><span class="label label-info" style="font-size : 1em;">' . $eqLogic->getId() . '</span></td>';
		echo '<td><span class="label label-info" style="font-size : 1em;">' . $username . '</span></td>';
		echo '<td><span class="label label-info" style="font-size : 1em;">' . $eqLogic->getConfiguration('createtime') . '</span></td></tr>';
	}else{
		echo '<tr><td>' . $img . '</td><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
		echo '<td><span class="label label-info" style="font-size : 1em;">' . $eqLogic->getId() . '</span></td>';
		echo '<td><span class="label label-info" style="font-size : 1em;">{{Utilisateur non trouvé}}</span></td>';
		echo '<td><span class="label label-info" style="font-size : 1em;">' . $eqLogic->getConfiguration('createtime') . '</span></td></tr>';
	}
}
?>
	</tbody>
</table>
