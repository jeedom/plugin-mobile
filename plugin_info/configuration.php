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

sendVarToJs('hasIos', mobile::check_ios());
?>
<form class="form-horizontal">
	<fieldset>
		<legend><i class="fa fa-list-alt"></i> {{Homebridge}}</legend>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Suppression du cache}}</label>
			<div class="col-lg-3">
				<a class="btn btn-warning" id="bt_eraseCache"><i class="fa fa-erase"></i> {{Supprimer}}</a>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Regénérer le fichier de configuration}}</label>
			<div class="col-lg-3">
				<a class="btn btn-warning" id="bt_generateConf"><i class="fa fa-erase"></i> {{Générer}}</a>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{PIN Homebridge (format : XXX-XX-XXX)}}</label>
			<div class="col-lg-3">
					<input class="configKey form-control" data-l1key="pin_homebridge" placeholder="031-45-154" />
			</div>
		</div>
	</fieldset>
</form>
<script>
setTimeout(function(){

if(hasIos == 0){
	 $('#div_plugin_dependancy').closest('.panel').hide();
     $('#div_plugin_deamon').closest('.panel').parent().removeClass('col-md-6');
     $('#div_plugin_deamon').closest('.panel').hide();
     $('#div_plugin_dependancy').closest('.panel').parent().removeClass('col-md-6');
     $('#div_plugin_configuration').closest('.panel').hide();
     $('#div_plugin_configuration').closest('.panel').parent().removeClass('col-md-6');
}else{
	$('#div_plugin_dependancy').closest('.panel').children('.panel-heading').children().html('<i class="fa fa-certificate"></i> {{Dépendances Homebridge}}');
	$('#div_plugin_deamon').closest('.panel').children('.panel-heading').children().html('<i class="fa fa-university"></i> {{Démon Homebridge}}');
}

},50);

$('#bt_eraseCache').on('click', function () {
	bootbox.confirm('{{Etes-vous sûr de vouloir supprimer le cache ? Vous devrez réinstaller les équipements sur votre appareil iOS.}}', function (result) {
		if (result) {
			$.ajax({
		        type: 'POST',
		        url: 'plugins/mobile/core/ajax/mobile.ajax.php',
		        data: {
		            action: 'eraseHomebridgeCache',
		        },
		        dataType: 'json',
		        global: false,
		        error: function (request, status, error) {
		        	$('#div_alert').showAlert({message: error.message, level: 'danger'});
		        },
		        success: function () {
		        	$('#div_alert').showAlert({message: "{{Cache Homebridge vidé}}", level: 'success'});
		        }
		    });
		}
	});
});

$('#bt_generateConf').on('click', function () {
	$.ajax({
        type: 'POST',
        url: 'plugins/mobile/core/ajax/mobile.ajax.php',
        data: {
            action: 'regenerateHomebridgeConf',
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
        	$('#div_alert').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
        	$('#div_alert').showAlert({message: "{{Fichier regénéré}}", level: 'success'});
        }
    });
});
</script>