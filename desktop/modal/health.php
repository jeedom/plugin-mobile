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
	throw new Exception('{{401 - Accès non autorisé}}');
}
$eqLogics = mobile::byType('mobile');
?>

<table class="table table-condensed tablesorter" id="table_healthneato">
	<thead>
		<tr>
			<th>{{Équipement}}</th>
			<th>{{Type de Mobile}}</th>
			<th>{{Utilisateur}}</th>
			<th>{{Dernière activité}}</th>
			<th>{{Date création}}</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($eqLogics as $eqLogic) {
			$typeMobile = $eqLogic->getConfiguration('type_mobile');
			$userId = $eqLogic->getConfiguration('affect_user');
			$userType = user::byId($userId);
			echo '<tr><td width="40%"><a href="' . $eqLogic->getLinkToConfiguration() . '">' . $eqLogic->getHumanName(true) . '</a></td>';
			if ($eqLogic->getConfiguration('type_mobile') == 'android') {
				echo '<td width="12.5%"><span class="label label-info"><i class="fab fa-android"></i></span></td>';
			} else if ($eqLogic->getConfiguration('type_mobile') == 'windows') {
				echo '<td width="12.5%"><span class="label label-info"><i class="fab fa-windows"></i></span></td>';
			} else if ($eqLogic->getConfiguration('type_mobile') == 'ios') {
				echo '<td width="12.5%"><span class="label label-info"><i class="fab fa-apple"></i></span></td>';
			} else {
				echo '<td width="12.5%"><span class="label label-info"><i class="far fa-question-circle"></i></i></span></td>';
			}
			if (is_object($userType)) {
				$username = $userType->getLogin();
				echo '<td width="15.5%"><span class="label label-info">' . $username . '</span></td>';
			} else {
				echo '<td width="15.5%"><span class="label label-info">{{Utilisateur non trouvé}}</span></td>';
			}
			echo '<td><span class="label label-info">' . $eqLogic->getStatus('lastCommunication') . '</span></td>';
			echo '<td><span class="label label-info">' . $eqLogic->getConfiguration('createtime') . '</span></td></tr>';
		}
		?>
	</tbody>
</table>