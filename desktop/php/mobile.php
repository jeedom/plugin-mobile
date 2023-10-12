<?php
ini_set('display_errors', 0);
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'mobile');
$eqLogics = eqLogic::byType('mobile');
$plugins = plugin::listPlugin(true);
$plugin_compatible = mobile::$_pluginSuported;
$plugin_widget = mobile::$_pluginWidget;
//$pathImgMenu = 'plugins/mobile/core/img/imgMenuPerso.jpg';
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-mobile-alt"></i> {{App V2 - (VERSION BETA PUBLIQUE SEULEMENT)}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i><br>
				<span>{{Configuration}}</span>
			</div>
			<?php if (jeedom::version() >= '4.4.0') {
				/*
				echo '<div class="cursor eqLogicAction logoSecondary" data-action="bt_customMenu" id="bt_customMenu">';
				echo '<i class="fas icon jeedomapp-plugin"></i><br>';
				echo '<span >{{Menu Custom}}</span>';
				echo '</div>';
				}else{
				echo '<div style="color:orange;" class="cursor eqLogicAction logoSecondary" data-action="bt_customMenu" id="bt_customMenu">';
				echo '<i class="fas icon jeedomapp-plugin"></i><br>';
				echo '<span style="color:orange;">{{Menu Custom}}</span>';
				echo '</div>';*/
			}
			?>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_qrCodev2" id="bt_qrCodev2">
				<i class="fas fa-qrcode"></i><br>
				<span>{{QR Code}}</span>
			</div>
			<!--
			<div style="color:#94CA02;" class="cursor eqLogicAction logoSecondary" data-action="bt_qrCodev2" id="bt_startTuto">
				<i class="fas fa-book"></i><br>
				<span>{{Documentation APP V2}}</span>
			</div> -->
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_healthmobile" id="bt_healthmobile">
				<i class="fas fa-medkit"></i><br>
				<span>{{Santé}}</span>
			</div>
		</div>
		<legend><i class="fas fa-mobile"></i> {{Mes Téléphones Mobiles}}</legend>
		<div class="input-group" style="margin:5px;">
			<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
			<div class="input-group-btn">
				<a id="bt_resetSearch" class="btn" style="width:30px"><i class="fas fa-times"></i>
				</a><a class="btn hidden roundedRight" id="bt_pluginDisplayAsTable" data-coreSupport="1" data-state="0"><i class="fas fa-grip-lines"></i></a>
			</div>
		</div>
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				if ($eqLogic->getConfiguration('appVersion', '1') == '2') {
					$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
					echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
					$file = 'plugins/mobile/core/img/' . $eqLogic->getConfiguration('type_mobile') . '.png';
					if (file_exists($file)) {
						$path = 'plugins/mobile/core/img/' . $eqLogic->getConfiguration('type_mobile') . '.png';
						echo '<img src="' . $path . '" />';
					} else {
						$path = 'plugins/mobile/core/img/mobile_icon.png';
						echo '<img src="' . $path . '" />';
					}
					echo '<br>';
					echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
					echo '<span class="hidden hiddenAsCard displayTableRight">';
					echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Équipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Équipement non visible}}"></i>';
					echo '</span>';
					echo '</div>';
				}
			}
			?>
		</div>
	</div>
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-mobile-alt"></i> {{App V1}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i><br>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_pluguinmobile" id="bt_pluguinmobile">
				<i class="fas jeedomapp-plugin"></i><br>
				<span>{{Plugins}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_piecemobile" id="bt_piecemobile">
				<i class="fas icon jeedomapp-piece-jeedom"></i><br>
				<span>{{Objets/Pièces}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_piecemobile" id="bt_scenariomobile">
				<i class="fas icon jeedomapp-scenario-jeedom"></i><br>
				<span>{{Scénarios}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_regenConfig" id="bt_regenConfig">
				<i class="fas fa-cogs"></i><br>
				<span>{{Régénérer la configuration}}</span>
			</div>
		</div>
		<legend><i class="fas fa-mobile"></i> {{Mes Téléphones Mobiles}}</legend>
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				if ($eqLogic->getConfiguration('appVersion', '1') != '2') {
					$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
					echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
					$file = 'plugins/mobile/core/img/' . $eqLogic->getConfiguration('type_mobile') . '.png';
					if (file_exists($file)) {
						$path = 'plugins/mobile/core/img/' . $eqLogic->getConfiguration('type_mobile') . '.png';
						echo '<img src="' . $path . '" />';
					} else {
						$path = 'plugins/mobile/core/img/mobile_icon.png';
						echo '<img src="' . $path . '" />';
					}
					echo '<br>';
					echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
					echo '<span class="hidden hiddenAsCard displayTableRight">';
					echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Équipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Équipement non visible}}"></i>';
					echo '</span>';
					echo '</div>';
				}
			}
			?>
		</div>
	</div>
	<div id="div_editSmartphone" class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}
				</a><a class="btn btn-sm btn-info" id="info_app"><i class="fas fa-question-circle"></i> {{Infos envoyées à l'app}}
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictabin" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Mobile}}</a></li>
			<li role="presentation"><a href="#notificationtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-mobile-alt"></i> {{Notifications}}</a></li>
			<li role="presentation" class="saveTab"><a href="#sauvegardetab" aria-controls="sauvegarde" role="tab" data-toggle="tab"><i class="fas fa-save"></i> {{Sauvegarde Mobile}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="eqlogictabin">
				<form class="form-horizontal">
					<fieldset>
						<div class="col-lg-6">
							<legend><i class="fas fa-wrench"></i> {{Paramètres généraux}}</legend>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Nom de l'équipement}}</label>
								<div class="col-sm-6">
									<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display:none;">
									<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label">{{Objet parent}}</label>
								<div class="col-sm-6">
									<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
										<option value="">{{Aucun}}</option>
										<?php
										$options = '';
										foreach ((jeeObject::buildTree(null, false)) as $object) {
											$options .= '<option value="' . $object->getId() . '">' . str_repeat('&nbsp;&nbsp;', $object->getConfiguration('parentNumber')) . $object->getName() . '</option>';
										}
										echo $options;
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Catégorie}}</label>
								<div class="col-sm-6">
									<?php
									foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
										echo '<label class="checkbox-inline">';
										echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" >' . $value['name'];
										echo '</label>';
									}
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Options}}</label>
								<div class="col-sm-6">
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked>{{Activer}}</label>
									<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked>{{Visible}}</label>
								</div>
							</div>

							<legend><i class="fas fa-cogs"></i> {{Paramètres spécifiques}}</legend>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Type de Mobile}}</label>
								<div class="col-sm-6">
									<select class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="type_mobile">
										<option value="ios">{{iPhone}}</option>
										<option value="android">{{Android}}</option>
										<option value="windows">{{Windows (non officiel)}}</option>
									</select>
								</div>
								<label class="col-sm-4 control-label">{{Utilisateur}}</label>
								<div class="col-sm-6">
									<select class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="affect_user">
										<option value="">{{Aucun}}</option>
										<?php
										foreach (user::all() as $user) {
											echo '<option value="' . $user->getId() . '">' . ucfirst($user->getLogin()) . '</option>';
										}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<legend><i class="fas fa-qrcode"></i> {{QRCode}}</legend>
							<center>
								<div class="qrCodeImg"></div>
							</center>
						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="notificationtab">
				<form class="form-horizontal">
					<fieldset>
						<div class="col-lg-6">
							<legend><i class="fas fa-mobile-alt"></i> {{Notifications}}</legend>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Id Mobile}}</label>
								<div class="col-sm-8">
									<input type="text" class="eqLogicAttr form-control" data-l1key="logicalId" placeholder="{{Iq}}" disabled />
								</div>
							</div>
							<div class="form-group monitoringToDisable">
								<label class="col-sm-4 control-label">{{ARN Mobile}}</label>
								<div class="col-sm-8">
									<input type="text" id="arnComplet" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="notificationArn" placeholder="{{ARN}}" disabled />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{TOKEN Mobile}}</label>
								<div class="col-sm-8">
									<input type="text" id="arnComplet" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="notificationRegistrationToken" placeholder="{{TOKEN}}" disabled />
								</div>
							</div>
							<div class="form-group monitoringToDisable">
								<label class="col-sm-4 control-label">{{ARN pour Monitoring}}</label>
								<div class="col-sm-8">
									<input type="text" id="to-copy-monitoring" class="eqLogicAttr form-control" placeholder="{{ARN pour Monitoring}}" disabled />
									<button class="btn btn-info eqLogicAction pull-right" id="copy-monitoring" type="button">{{Copier pour Monitoring}}</button>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="sauvegardetab">
				<form class="form-horizontal">
					<fieldset>
						<div class="col-lg-6">
							<legend><i class="fas fa-save"></i> {{Sauvegarde}}</legend>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Sauvegarde Dashboard}}</label>
								<div class="col-sm-7">
									<span id="SaveDash" class="badge">{{Vérification en Cours}}</span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Sauvegarde Favoris}}</label>
								<div class="col-sm-7">
									<span id="SaveFav" class="badge">{{Vérification en Cours}}</span>
								</div>
							</div>

						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">

				<br><br>
				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th class="hidden-xs" style="min-width:50px;width:70px;">ID</th>
							<th style="min-width:400px;width:450px;">{{Nom}}</th>
							<th style="width:400px;">{{Type}}</th>
							<th style="min-width:160px;">{{Options}}</th>
							<th style="min-width:160px;">{{Valeur}}</th>
							<th style="min-width:80px;width:140px;">{{Actions}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
	include_file('desktop', 'mobile', 'js', 'mobile');
	include_file('core', 'plugin.template', 'js');
	?>
