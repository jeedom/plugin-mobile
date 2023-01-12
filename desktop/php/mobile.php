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
$pathImgMenu = 'plugins/mobile/core/img/imgMenuPerso.jpg';
?>


<style>

.containerArea:hover {
	background-color: #93ca02;
}

</style>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-mobile"></i>  {{App V2 - VERSION ALPHA PRIVEE SEULEMENT}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i><br>
					<span>{{Configuration}}</span>
			</div>
            <div class="cursor eqLogicAction logoSecondary" data-action="bt_customMenu" id="bt_customMenu">
				<i class='fas icon jeedomapp-plugin'></i><br>
				<span>{{Menu Custom}}</span>
			</div>
            <div class="cursor eqLogicAction logoSecondary" data-action="bt_qrCodev2" id="bt_qrCodev2">
				 <i class="fa fa-qrcode"></i><br>  
				<span>{{QR Code}}</span>
			</div>
		</div>
		<legend><i class="icon techno-listening3"></i> {{Mes Téléphones Mobiles}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />

		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				if($eqLogic->getConfiguration('appVersion', '1') == '2'){
					$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
					echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
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
					echo '</div>';
				}
			}
			?>
		</div>
	</div>
</div>
<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-mobile"></i>  {{App V1}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i><br>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i><br>
					<span>{{Configuration}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_pluguinmobile" id="bt_pluguinmobile">
				<i class="fas jeedomapp-plugin"></i><br>
				<span>{{Plugins}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_piecemobile" id="bt_piecemobile">
				<i class="fas icon jeedomapp-piece-jeedom"></i><br>
				<span>{{Objets / Pièces}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_piecemobile" id="bt_scenariomobile">
				<i class="fas icon jeedomapp-scenario-jeedom"></i><br>
				<span>{{Scénarios}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_healthmobile" id="bt_healthmobile">
				<i class="fas fa-medkit"></i><br>
				<span>{{Santé}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_regenConfig" id="bt_regenConfig">
				<i class="fas fa-cogs"></i><br>
				<span>{{Régénérer la configuration}}</span>
			</div>

		</div>
		<legend><i class="icon techno-listening3"></i> {{Mes Téléphones Mobiles}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />

		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				if($eqLogic->getConfiguration('appVersion', '1') != '2'){
					$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
					echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
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
					echo '</div>';
				}
			}
			?>
		</div>
	</div>
</div>

<div id="div_editSmartphone" class="col-xs-12 eqLogic" style="padding-top: 5px;display: none;">
	<div class="input-group pull-right" style="display:inline-flex">
		<span class="input-group-btn">
			<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}
			</a><a class="btn btn-sm btn-info" id="info_app"><i class="fa fa-question-circle"></i> {{Infos envoyées à l'app}}
			</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
			</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
		</span>
	</div>

	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
		<li role="presentation" class="active"><a href="#eqlogictabin" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Mobile}}</a></li>
		<li role="presentation"><a href="#notificationtab" aria-controls="profile" role="tab" data-toggle="tab" ><i class="fas fa-list-alt"></i> {{Notifications}}</a></li>
		<li role="presentation"><a href="#sauvegardetab" aria-controls="sauvegarde" role="tab" data-toggle="tab" ><i class="fas fa-list-alt"></i> {{Sauvegarde Mobile}}</a></li>
    <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab" ><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
	</ul>

	<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
		<div role="tabpanel" class="tab-pane active" id="eqlogictabin">
		<br/><br/>
		<div class="row">
			<div class="col-lg-6">
			<form class="form-horizontal">
				<fieldset>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{Nom de l'équipement mobile}}</label>
					<div class="col-sm-4">
						<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
						<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement template}}"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label" >{{Objet parent}}</label>
					<div class="col-sm-4">
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
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-8">
						<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
						<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{Type de Mobile}}</label>
					<div class="col-sm-4">
						<select class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="type_mobile">
							<option value="ios">{{iPhone}}</option>
							<option value="android">{{Android}}</option>
							<option value="windows">{{Windows (non officiel)}}</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">{{Utilisateurs}}</label>
					<div class="col-sm-4">
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
				</fieldset>
			</form>
			</div>
			<div class="col-lg-6">
			<form class="form-horizontal">
				<fieldset>
				<legend><i class="fa fa-qrcode"></i>  {{QRCode}}</legend>
				<center>
					<div class="qrCodeImg"></div>
				</center>
				</fieldset>
			</form>
			</div>
		</div>
		</div>

		<div role="tabpanel" class="tab-pane" id="notificationtab">
		<br/><br/>
		<form class="form-horizontal">
			<fieldset>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Id Mobile :}}</label>
					<div class="col-sm-7">
						<input type="text" class="eqLogicAttr form-control" data-l1key="logicalId" placeholder="{{Iq}}" disabled/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{ARN Mobile :}}</label>
					<div class="col-sm-7">
						<input type="text" id="arnComplet" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="notificationArn" placeholder="{{ARN}}" disabled/>
					</div>
				</div>
                <div class="form-group">
					<label class="col-sm-2 control-label">{{TOKEN Mobile :}}</label>
					<div class="col-sm-7">
						<input type="text" id="arnComplet" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="notificationRegistrationToken" placeholder="{{TOKEN}}" disabled/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{ARN pour Monitoring :}}</label>
					<div class="col-sm-7">
						<input type="text" id="to-copy-monitoring" class="eqLogicAttr form-control" placeholder="{{ARN pour Monitoring}}" disabled/>
						<button class="btn btn-info eqLogicAction pull-right" id="copy-monitoring" type="button">{{Copier pour Monitoring}}</button>
					</div>
				</div>
			</fieldset>
		</form>
		</div>

		<div role="tabpanel" class="tab-pane" id="sauvegardetab">
		<br/><br/>
		<form class="form-horizontal">
			<fieldset>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Sauvegarde Dashboard :}}</label>
					<div class="col-sm-7">
						<span id="SaveDash" class="badge">{{Vérification en Cours}}</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">{{Sauvegarde Favoris :}}</label>
					<div class="col-sm-7">
						<span id="SaveFav" class="badge">{{Vérification en Cours}}</span>
					</div>
				</div>
			</fieldset>
		</form>
		</div>


        <div role="tabpanel" class="tab-pane" id="commandtab">
<br/>

<table id="table_cmd" class="table table-bordered table-condensed">
    <thead>
        <tr>
		<th>{{Nom}}</th><th>{{Type}}</th><th>{{Info}}</th><th>{{Action}}</th>
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