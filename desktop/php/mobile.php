<?php
ini_set('display_errors', 0);
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'mobile');
$eqLogics = eqLogic::byType('mobile');
$eqLogicsV1 = [];
$eqLogicsV2 = [];
foreach ($eqLogics as $eqLogic) {
	if ($eqLogic->getConfiguration('appVersion', '1') == '2') {
		array_push($eqLogicsV2, $eqLogic);
	} else {
		array_push($eqLogicsV1, $eqLogic);
	}
}
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-mobile-alt"></i> {{App V2 - (VERSION BETA SEULEMENT)}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i><br>
				<span>{{Configuration}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_handlePhones" id="bt_handlePhones">
				<i class="fas icon kiko-old-phone"></i><br>
				<span>{{Gestion Mobiles}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="bt_qrCodev2" id="bt_qrCodev2">
				<i class="fas fa-qrcode"></i><br>
				<span>{{QR Code}}</span>
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
			if ($eqLogic->getImage() != 'plugins/mobile/plugin_info/mobile_icon.png') $logoV2 = $eqLogic->getImage();
			else if (file_exists('plugins/mobile/plugin_info/mobileV2_icon.png')) $logoV2 = 'plugins/mobile/plugin_info/mobileV2_icon.png';
			else $logoV2 = $eqLogic->getImage();

			if (count($eqLogicsV2) >= 1) {  // AppV2
				foreach ($eqLogicsV2 as $eqLogic) {
					if ($eqLogic->getConfiguration('appVersion', '1') == '2') {
						$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
						echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
						/* getImage : 
							core 4.4 - returns plugin image
							core 4.5 - returns the custom image if exist or else the plugin image 
						*/
						if ($eqLogic->getImage() != 'plugins/mobile/plugin_info/mobile_icon.png') $logoV2 = $eqLogic->getImage();
						else if (file_exists('plugins/mobile/plugin_info/mobileV2_icon.png')) $logoV2 = 'plugins/mobile/plugin_info/mobileV2_icon.png';
						else $logoV2 = $eqLogic->getImage();
						echo '<img src="' . $logoV2 . '" height="105" width="95">';
						echo '<a style="width: 30px;height: 30px;border-radius: 15px;background-color: #94CA02;position: absolute;bottom: 65px;right: 7px;">';
						if ($eqLogic->getConfiguration('type_mobile') == 'android') {
							echo '<i class="fab fa-android" style="margin: 8px;color: #FFFFFF;"></i>';
						} else {
							echo '<i class="fab fa-apple" style="margin: 8px;color: #FFFFFF;"></i>';
						}
						echo '</a>';
						echo '<br>';
						echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
						echo '<span class="hiddenAsCard displayTableRight hidden">';
						echo '<span class="label">' . $eqLogic->getConfiguration('type_mobile')  .  '</span>';
						$user = $eqLogic->getConfiguration('affect_user');
						$username = user::byId($user);
						if (is_object($username)) {
							$user = $username->getLogin();
						}
						echo '<span class="label">' . $user  .  '</span>';
						echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Équipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Équipement non visible}}"></i>';
						echo '</span>';
						echo '</div>';
					}
				}
			} else {
				echo '<div class="alert alert-info text-center" style="width: 100%; background-color: var(--al-info-color) !important;">';
				echo '{{Pour ajouter un téléphone, il y a 2 méthodes possible :}} <br> {{Sur le premier écran de l\'application, il vous est proposé de connecter votre compte market, et ainsi de retrouver toutes les boxs associées à ce compte, ou bien simplement ajouter une box.}}';
				echo '</div>';
            }
			?>
		</div>
	</div>

    <?php if (count($eqLogicsV1) >= 1) {  // AppV1
      ?>
		<div class="col-xs-12 eqLogicThumbnailDisplay">
			<legend><i class="fas fa-mobile-alt"></i> {{App V1}}</legend>
			<div class="eqLogicThumbnailContainer">
				<div class="alert alert-danger" style="background-color: var(--al-danger-color) !important; font-size:1.2em;font-weight:bold;">
					{{Attention, en beta il n'est plus possible d'utiliser l'APP V1}}</div>
				</div>
				<legend><i class="fas fa-mobile"></i> {{Mes Téléphones Mobiles}}</legend>
				<div class="eqLogicThumbnailContainer">
				<?php
				foreach ($eqLogicsV1 as $eqLogic) {
					if ($eqLogic->getConfiguration('appVersion', '1') != '2') {
						$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
						echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
						echo '<img src="' . $eqLogic->getImage() . '"/>';
						echo '<a style="width: 30px;height: 30px;border-radius: 15px;background-color: #94CA02;position: absolute;bottom: 65px;right: 7px;">';
						if ($eqLogic->getConfiguration('type_mobile') == 'android') {
							echo '<i class="fab fa-android" style="margin: 8px;color: #FFFFFF;"></i>';
						} else if ($eqLogic->getConfiguration('type_mobile') == 'windows') {
							echo '<i class="fab fa-windows" style="margin: 8px;color: #FFFFFF;"></i>';
						} else if ($eqLogic->getConfiguration('type_mobile') == 'ios') {
							echo '<i class="fab fa-apple" style="margin: 8px;color: #FFFFFF;"></i>';
						} else {
							echo '<i class="far fa-question-circle" style="margin: 8px;color: #FFFFFF;"></i>';
						}
						echo '</a>';
						echo '<br>';
						echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
						echo '<span class="hiddenAsCard displayTableRight hidden">';
						echo '<span class="label">' . $eqLogic->getConfiguration('type_mobile')  .  '</span>';
						$user = $eqLogic->getConfiguration('affect_user');
						$username = user::byId($user);
						if (is_object($username)) {
							$user = $username->getLogin();
						}
						echo '<span class="label">' . $user  .  '</span>';
						echo ($eqLogic->getIsVisible() == 1) ? '<i class="fas fa-eye" title="{{Équipement visible}}"></i>' : '<i class="fas fa-eye-slash" title="{{Équipement non visible}}"></i>';
						echo '</span>';
						echo '</div>';
					}
				}
				?>
			</div>
		</div>
    <?php } ?>

	<div id="div_editSmartphone" class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-sm btn-default eqLogicAction roundedLeft" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}
				</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-sm btn-danger eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}
				</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictabin" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Mobile}}</a></li>
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
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">{{Utilisateur}}</label>
								<div class="col-sm-6">
									<select class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="affect_user">
										<option value="">{{Aucun}}</option>
										<?php
										$hidden_user = array('jeedom_support', 'internal_report');
										foreach (user::all() as $user) {
											if (in_array($user->getLogin(), $hidden_user) || $user->getEnable() != 1) continue;
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
							<legend><i class="fas fa-mobile-alt"></i> {{Notifications}}</legend>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{Id Mobile}}
									<sup><i class="fas fa-question-circle" title="{{Id Mobile}}"></i></sup>
								</label>
								<div class="col-sm-8">
									<span type="text" class="eqLogicAttr label label-primary" data-l1key="logicalId" placeholder="{{Iq}}"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">{{TOKEN Mobile}}
									<sup><i class="fas fa-question-circle" title="{{TOKEN Mobile}}"></i></sup>
								</label>
								<div class="col-sm-8">
									<span type="text" id="arnComplet" class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="notificationRegistrationToken" placeholder="{{TOKEN}}"></span>
								</div>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="commandtab">
				<br><br>
				<div class="table-responsive">
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
	</div>
</div>
<?php include_file('desktop', 'mobile', 'js', 'mobile'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
