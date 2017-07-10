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
if(!isConnect()) {
	include_file('desktop', '404', 'php');
	die();
}

sendVarToJs('hasIos', mobile::check_ios());
?>
<form class="form-horizontal">
	<fieldset>
		<legend>
			<i class="fa fa-list-alt"></i> {{Homebridge}}
		</legend>
		<?php
			$interne = network::getNetworkAccess('internal');
			if($interne == null || $interne == 'http://:80' || $interne == 'https://:80'){
		?>
			<div class="form-group">
				<div class="col-lg-7">
				<span class="badge" style="background-color : #c9302c;">{{Attention votre adresse interne (configuration) n'est pas valide.}}</span>
				</div>
			</div>
		<?php
			}else{
		?>
			<div class="form-group">
				<label class="col-lg-4 control-label">{{Adresse Ip Homebridge :}}</label>
				<span class="badge" style="background-color : #ec971f;"><?php echo $interne; ?></span>
			</div>
		<?php
			}
		?>
		
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Utilisateur}}</label>
			<div class="col-lg-3">
				<select class="configKey form-control configuration form-control" data-l1key="user_homebridge">
					<?php
					foreach(user::all() as $user) {
						echo '<option value="' . $user->getId() . '">' . ucfirst($user->getLogin()) . '</option>';
					}
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Nom Homebridge}}</label>
			<div class="col-lg-3">
				<input class="configKey form-control" data-l1key="name_homebridge" placeholder="<?php echo config::byKey('name') ?>" />
			</div>
		</div>
		<div class="form-group hide">
			<label class="col-lg-4 control-label">{{MAC Homebridge}}</label>
			<div class="col-lg-3">
				<input class="configKey form-control" data-l1key="mac_homebridge" placeholder="CC:22:3D:E3:CE:30" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{PIN Homebridge (format : XXX-XX-XXX)}}</label>
			<div class="col-lg-3">
				<input class="configKey form-control" data-l1key="pin_homebridge" placeholder="031-45-154" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Réparation de Homebridge}}</label>
			<div class="col-lg-3">
				<a class="btn btn-warning" id="bt_repairHome"><i class="fa fa-erase"></i> {{Réparer}}</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" id="bt_repairHome_reinstall"><i class="fa fa-erase"></i> {{Réparer & Réinstaller}}</a>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Configuration avancée}}</label>
			<div class="col-lg-3">
				<a class="btn btn-danger" id="bt_platformFile"><i class="fa fa-file-o"></i> {{Plateforme Homebridge supplémentaire}}</a>
			</div>
		</div>		
	</fieldset>
</form>
<script>
	setTimeout(function() {
		if (hasIos == 0) {
			$('#div_plugin_dependancy').closest('.panel').hide();
			$('#div_plugin_deamon').closest('.panel').parent().removeClass('col-md-6');
			$('#div_plugin_deamon').closest('.panel').hide();
			$('#div_plugin_dependancy').closest('.panel').parent().removeClass('col-md-6');
			$('#div_plugin_configuration').closest('.panel').hide();
			$('#div_plugin_configuration').closest('.panel').parent().removeClass('col-md-6');
		} else {
			$('#div_plugin_dependancy').closest('.panel').children('.panel-heading').children().html('<i class="fa fa-certificate"></i> {{Dépendances Homebridge}}');
			$('#div_plugin_deamon').closest('.panel').children('.panel-heading').children().html('<i class="fa fa-university"></i> {{Démon Homebridge}}');
		}

	}, 50);
	$('#bt_platformFile').on('click', function () {
		bootbox.confirm('{{Configuration avancée, à vos propres risques !!! Aucun support ne sera donné !!!}}', function(result) {
			if (result) {
				$('#md_modal2').dialog({title: "{{Configuration Plateforme Homebridge supplémentaire}}"});
				$('#md_modal2').load('index.php?v=d&plugin=mobile&modal=platformHB.mobile').dialog('open');
			}
		});
	});
	$('#bt_repairHome').on('click', function() {
		bootbox.confirm('{{Etes-vous sûr de vouloir réparer Homebridge ? Vous devrez réinstaller les équipements sur votre appareil iOS (Merci, de supprimer la passerelle Jeedom sur l\'app Home).}}', function(result) {
			if (result) {
				$.ajax({
					type : 'POST',
					url : 'plugins/mobile/core/ajax/mobile.ajax.php',
					data : {
						action : 'repairHomebridge',
					},
					dataType : 'json',
					global : false,
					error : function(request, status, error) {
						$('#div_alert').showAlert({
							message : error.message,
							level : 'danger'
						});
					},
					success : function() {
						$('#div_alert').showAlert({
							message : "{{Réparation Homebridge effectuée, merci de patienter jusqu'au démarrage du démon}}",
							level : 'success'
						});
					}
				});
			}
		});
	});
	$('#bt_repairHome_reinstall').on('click', function() {
		bootbox.confirm('{{Etes-vous sûr de vouloir supprimer et reinstaller Homebridge ? Vous devrez réinstaller les équipements sur votre appareil iOS (Merci, de supprimer la passerelle Jeedom sur l\'app Home).}}', function(result) {
			if (result) {
				$.ajax({
					type : 'POST',
					url : 'plugins/mobile/core/ajax/mobile.ajax.php',
					data : {
						action : 'repairHomebridge_reinstall',
					},
					dataType : 'json',
					global : false,
					error : function(request, status, error) {
						$('#div_alert').showAlert({
							message : error.message,
							level : 'danger'
						});
					},
					success : function() {
						$('#div_alert').showAlert({
							message : "{{Réinstallation Homebridge effectuée, merci de patienter jusqu'au démarrage du démon}}",
							level : 'success'
						});
					}
				});
			}
		});
	});
</script>
