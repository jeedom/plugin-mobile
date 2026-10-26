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
include_file('core', 'authentification', 'php');
if (!isConnect()) {
	include_file('desktop', '404', 'php');
	die();
}
?>

<form class="form-horizontal">
	<fieldset>
		<legend>
			<i class="fas fa-mobile-alt"></i> {{Notifications de l'app}}
		</legend>
		<div class="form-group">
			<label class="col-sm-3 control-label">{{Réponse Ask sensible à la case : }}</label>
			<div class="col-sm-1">
				<input type="checkbox" class="configKey orm-control" data-l1key="askCasse" />
			</div>
		</div>
		</div>
		<legend>
			<i class="fas fa-map-marker-alt"></i> {{Logs de géolocalisation}}
		</legend>
		<div class="form-group">
			<label class="col-sm-3 control-label">{{Commande mail de réception}}
				<sup><i class="fas fa-question-circle tooltips" title="{{Les apps en mode développeur ou bêta envoient chaque jour leur journal de géolocalisation à cette commande. Vide : envoi refusé.}}"></i></sup>
			</label>
			<div class="col-sm-5">
				<div class="input-group">
					<input class="configKey input-sm form-control" data-l1key="geolocLogMailCmd" />
					<span class="input-group-btn">
						<a class="btn btn-default btn-sm" id="bt_selectGeolocLogMailCmd"><i class="fas fa-list-alt"></i></a>
					</span>
				</div>
			</div>
		</div>
	</fieldset>
</form>
<script>
	$('#bt_selectGeolocLogMailCmd').on('click', function () {
		var el = $(this).closest('.form-group').find('.configKey');
		jeedom.cmd.getSelectModal({cmd: {type: 'action', subType: 'message'}}, function (result) {
			el.value(result.human);
		});
	});
</script>