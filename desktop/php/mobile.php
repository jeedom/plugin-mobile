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
?>
<div class="row row-overflow">
  <div class="col-lg-2 col-md-3 col-sm-4">
    <div class="bs-sidebar">
      <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
        <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un mobile}}</a>
        <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
        <?php
foreach ($eqLogics as $eqLogic) {
	echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
}
?>
     </ul>
   </div>
 </div>

 <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
  <a class="btn btn-default pull-right" id="bt_regenConfig"><i class="fa fa-cogs"></i> {{Regenerer configuration}}</a>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipements}}</a></li>
    <li role="presentation"><a href="#plugintab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Plugins}}</a></li>
    <li role="presentation"><a href="#objecttab" aria-controls="profile" role="tab" data-toggle="tab"><i class="icon maison-house112"></i> {{Objets / Pièces}}</a></li>
  </ul>
  <div class="tab-content">
   <div role="tabpanel" class="tab-pane active" id="eqlogictab">
     <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
     <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
       <center>
        <i class="fa fa-plus-circle" style="font-size : 5em;color:#94ca02;"></i>
      </center>
      <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>Ajouter</center></span>
    </div>
    <div class="cursor eqLogicAction" data-action="gotoPluginConf" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;">
      <center>
        <i class="fa fa-wrench" style="font-size : 5em;color:#767676;"></i>
      </center>
      <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Configuration}}</center></span>
    </div>
    <div class="cursor" id="bt_healthmobile" style="background-color : #ffffff; height : 120px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
      <center>
        <i class="fa fa-medkit" style="font-size : 5em;color:#767676;"></i>
      </center>
      <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#767676"><center>{{Santé}}</center></span>
    </div>
  </div>
  <legend><i class="icon techno-listening3"></i> {{Mes Téléphones Mobiles}}</legend>
  <div class="eqLogicThumbnailContainer">
    <?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
	echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
	echo "<center>";
	$file = 'plugins/mobile/docs/images/' . $eqLogic->getConfiguration('type_mobile') . '.png';
	if (file_exists($file)) {
		$path = 'plugins/mobile/docs/images/' . $eqLogic->getConfiguration('type_mobile') . '.png';
		echo '<img src="' . $path . '" height="105" width="105" />';
	} else {
		$path = 'plugins/mobile/docs/images/mobile_icon.png';
		echo '<img src="' . $path . '" height="105" width="105" />';
	}
	echo "</center>";
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
	echo '</div>';
}
?>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="plugintab">
  <legend><i class="fa fa-check-circle-o"></i>  {{Le(s) Plugin(s) Compatible(s)}}</legend>
  <div class="eqLogicThumbnailContainer">
   <?php
foreach ($plugins as $plugin) {
	$opacity = '';
	if ($plugin->getId() != 'mobile' && $plugin->getId() != 'homebridge') {
		if (in_array($plugin->getId(), $plugin_compatible)) {
			if (in_array($plugin->getId(), $plugin_widget)) {
				$text = '<center><span class="label label-success" style="font-size : 0.9em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;" title="Il est disponible dans la liste des plugins de l\'application, il a aussi une intégration appronfondie sur le dashboard">{{Plugin Spécial}}</span></center>';
			} else {
				if (config::byKey('sendToApp', $plugin->getId(), 1) == 1) {
					$text = '<center><span class="label label-info" style="font-size : 0.9em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;" title="Il est visible dans les pièces de l\'application mobile, pour certains d\'entre eux il peut être nécessaire de configurer les types génériques (virtuels, scripts etc..). Il peut être désactivé pour ne pas être transmis">{{Via Type générique}}</span></center>';
				} else {
					$text = '<center><span class="label label-danger" style="font-size : 0.9em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;" title="N\'est pas transmis à l\'application, vous pouvez le transmettre à l\'application en l\'activant et configurant les types génériques">{{Non transmis}}</span></center>';
					$opacity = 'opacity:0.3;';
				}
			}
			echo '<div class="cursor eqLogicAction" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '">';
			echo '<center>';
			if (file_exists(dirname(__FILE__) . '/../../../../' . $plugin->getPathImgIcon())) {
				echo '<img class="img-responsive" style="width : 120px;" src="' . $plugin->getPathImgIcon() . '" />';
				echo "</center>";
			} else {
				echo '<i class="' . $plugin->getIcon() . '" style="font-size : 6em;margin-top:20px;"></i>';
				echo "</center>";
				echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $plugin->getName() . '</center></span>';
			}
			echo $text;
			echo '</div>';
		}
	}
}
?>
</div>
<legend><i class="fa fa-times-circle-o"></i>  {{Le(s) Plugin(s) Non Testé(s)}}</legend>
<div class="eqLogicThumbnailContainer">
 <?php
foreach ($plugins as $plugin) {
	$opacity = '';
	if ($plugin->getId() != 'mobile') {
		if (!in_array($plugin->getId(), $plugin_compatible)) {
			if (config::byKey('sendToApp', $plugin->getId(), 0) == 1) {
				$text = '<center><span class="label label-warning" style="font-size : 1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;" title="Vous avez activé la transmission de ce plugin en se basant sur les types génériques">{{Transmis à l\'app}}</span></center>';
			} else {
				$opacity = 'opacity:0.3;';
				$text = '<center><span class="label label-danger" style="font-size : 1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;" title="N\'est pas transmis à l\'application, vous pouvez le transmettre à l\'application en l\'activant et configurant les types génériques">{{Non transmis}}</span></center>';
			}
			echo '<div class="cursor eqLogicAction" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '">';
			echo '<center>';
			if (file_exists(dirname(__FILE__) . '/../../../../' . $plugin->getPathImgIcon())) {
				echo '<img class="img-responsive" style="width : 120px;" src="' . $plugin->getPathImgIcon() . '" />';
				echo "</center>";
			} else {
				echo '<i class="' . $plugin->getIcon() . '" style="font-size : 6em;margin-top:20px;"></i>';
				echo "</center>";
				echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $plugin->getName() . '</center></span>';
			}
			echo $text;
			echo '</div>';
		}
	}
}
?>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="objecttab">
  <legend><i class="icon maison-modern13"></i>  {{Les Pièces}}
  </legend>
  <div class="eqLogicThumbnailContainer">
    <?php
$allObject = object::buildTree(null, false);
foreach ($allObject as $object) {
	$opacity = '';
	if ($object->getDisplay('sendToApp', 1) == 0) {
		$opacity = 'opacity:0.3;';
	}
	echo '<div class="objectDisplayCard cursor" data-object_id="' . $object->getId() . '" onclick="clickobject(\'' . $object->getId() . '\')" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '">';
	echo "<center>";
	echo str_replace('></i>', ' style="font-size : 6em;color:#767676;"></i>', $object->getDisplay('icon', '<i class="fa fa-lemon-o"></i>'));
	echo "</center>";
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $object->getName() . '</center></span>';
	echo '</div>';
}
?>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="scenariotab">
  <legend><i class="icon jeedom-clap_cinema"></i>  {{Les Scénarios}}
  </legend>
  <div class="eqLogicThumbnailContainer">
    <?php
$allScenario = scenario::all();
foreach ($allScenario as $scenario) {
	$opacity = '';
	if ($scenario->getDisplay('sendToApp', 1) == 0) {
		$opacity = 'opacity:0.3;';
	}
	echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" data-type="' . $scenario->getType() . '" onclick="clickscenario(\'' . $scenario->getId() . '\',\'' . $scenario->getName() . '\')" style="background-color : #ffffff; height : 140px;margin-bottom : 35px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
	echo "<center>";
	echo '<img src="core/img/scenario.png" height="90" width="85" />';
	echo "</center>";
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $scenario->getHumanName(true, true, true, true) . '</center></span>';
	echo '</div>';
}
?>
</div>
</div>
</div>
</div>
<div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
  <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
  <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
  <a class="btn btn-info pull-right" id="info_app"><i class="fa fa-question-circle"></i> {{Infos envoyées à l'app}}</a>
  <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#eqlogictabin" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Mobile}}</a></li>
    <li role="presentation"><a href="#notificationtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Notifications}}</a></li>
    <li role="presentation"><a href="#sauvegardetab" aria-controls="sauvegarde" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Sauvegarde Mobile}}</a></li>
  </ul>
  <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
    <div role="tabpanel" class="tab-pane active" id="eqlogictabin">
      <div class="row">
        <div class="col-lg-6">
          <form class="form-horizontal">
            <fieldset>
              <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}</legend>
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
foreach (object::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
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
  <form class="form-horizontal">
   <fieldset>
    <legend><i class="fa fa-qrcode"></i>  {{Notifications Infos}}</legend>
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
  <form class="form-horizontal">
   <fieldset>
    <legend><i class="fa fa-qrcode"></i>  {{Sauvegarde et Dashboard}}</legend>
    <form class="form-horizontal">
      <fieldset>
        <div class="form-group">
          <label class="col-sm-2 control-label">{{Id Mobile :}}</label>
          <div class="col-sm-7">
           <input id="Iq" type="text" class="eqLogicAttr form-control" data-l1key="logicalId" placeholder="{{Iq}}" disabled/>
         </div>
       </div>
	<div class="form-group">
		<label class="col-sm-2 control-label">{{Sauvegarde Dashboard :}}</label>
		<div class="col-sm-7">
			<span class="SaveDash badge">{{Vérification en Cours}}</span>
         	</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">{{Sauvegarde Favoris :}}</label>
		<div class="col-sm-7">
			<span class="SaveFav badge">{{Vérification en Cours}}</span>
         	</div>
	</div>
     </fieldset>
   </form>
	   <script>
		$.get("google.com").done(function () {
  			$('#SaveDash').addClass('badge-success');
			$('#SaveDash').setText('OK');
			$('#SaveFav').addClass('badge-success');
			$('#SaveFav').setText('OK');
		}).fail(function () {
   			$('#SaveDash').addClass('badge-danger');
			$('#SaveDash').setText('NOK');
			$('#SaveFav').addClass('badge-danger');
			$('#SaveFav').setText('NOK');
		});
	   </script>
 </div>
 <div role="tabpanel" class="tab-pane" id="commandtab">
   <div class="row">
     <div class="col-lg-6">

     </div>
   </div>
 </div>
</div>
</div>
<?php include_file('desktop', 'mobile', 'js', 'mobile');?>
<?php include_file('core', 'plugin.template', 'js');?>
