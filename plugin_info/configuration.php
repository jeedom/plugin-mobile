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
	<?php
if (jeedom::isCapable('sudo')) {
	echo '<div class="form-group">
		<label class="col-lg-4 control-label">{{Dépendance}}</label>
		<div class="col-lg-3">
			<a class="btn btn-warning bt_installDeps" id="bt_installDeps"><i class="fa fa-check"></i> {{Installer/Mettre à jour}}</a>
		</div>
	</div>';
} else {
	echo '<div class="alert alert danger">{{Jeedom n\'a pas les droits sudo sur votre système, il faut lui ajouter pour qu\'il puisse installer les dépendances, voir <a target="_blank" href="https://jeedom.fr/doc/documentation/installation/fr_FR/doc-installation.html#autre">ici</a> partie 1.7.4}}</div>';
}
?>
</fieldset>
</form>
<script>
	$('#bt_installDeps').on('click',function(){
		bootbox.confirm('{{Etes-vous sûr de vouloir installer les dépendances }}', function (result) {
			if (result) {
				$('#md_modal').dialog({title: "{{Installation}}"});
				$('#md_modal').load('index.php?v=d&plugin=mobile&modal=update.mobile').dialog('open');
			}
		});
	});
</script>
