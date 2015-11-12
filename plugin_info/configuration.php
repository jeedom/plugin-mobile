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
	<div class="form-group">
		<fieldset>
			<label class="col-lg-2 control-label">{{Droits Sudo : }}</label>
			<?php
if (jeedom::isCapable('sudo')) {
	echo '<div class="col-lg-2"><span class="label label-success">OK</span></div>';
} else {
	echo '<div class="col-lg-2"><span class="label label-danger">NOK</span>    <span><a href="https://jeedom.fr/doc/documentation/installation/fr_FR/doc-installation.html#_etape_4_définition_des_droits_root_à_jeedom"><i class="fa fa-question-circle"></i></a></span></div>';
}
?>
		</fieldset>
	</div>
	<fieldset>
		<div class="form-group">
			<label class="col-lg-2 control-label">{{Dépendances :}}</label>
			<div class="col-lg-2">
				<a class="btn btn-danger" id="bt_installDeps"><i class="fa fa-check"></i> {{Installer}}</a>
			</div>
		</div>
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
