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
<div class="col-xs-12 eqLogicThumbnailDisplay">
     <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
     <div class="eqLogicThumbnailContainer">
      <div class="cursor eqLogicAction logoPrimary" data-action="add">
        <i class="fas fa-plus-circle"></i>
        <br>
        <span>{{Ajouter}}</span>
    </div>
   <div class="cursor eqLogicAction logoSecondary" data-action="bt_pluguinmobile" id="bt_pluguinmobile">
      <i class="fas jeedomapp-plugin"></i>
    <br>
    <span>{{Plugins Compatibles}}</span>
  </div>
  <div class="cursor eqLogicAction logoSecondary" data-action="bt_piecemobile" id="bt_piecemobile">
      <i class="fas icon jeedomapp-piece-jeedom"></i>
    <br>
    <span>{{Objets / Pièces}}</span>
  </div>
  <div class="cursor eqLogicAction logoSecondary" data-action="bt_piecemobile" id="bt_scenariomobile">
      <i class="fas icon jeedomapp-scenario-jeedom"></i>
    <br>
    <span>{{Scénarios}}</span>
  </div>
    <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
      <i class="fas fa-wrench"></i>
    <br>
    <span>{{Configuration}}</span>
  </div>
  <div class="cursor eqLogicAction logoSecondary" data-action="bt_healthmobile" id="bt_healthmobile">
      <i class="fas fa-medkit"></i>
    <br>
    <span>{{Santé}}</span>
  </div>
   <div class="cursor eqLogicAction logoSecondary" data-action="bt_regenConfig" id="bt_regenConfig">
      <i class="fas fa-cogs"></i>
    <br>
    <span>{{Regenerer configuration}}</span>
  </div>
  </div>
  <legend><i class="icon techno-listening3"></i> {{Mes Téléphones Mobiles}}</legend>
  <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
  <div class="eqLogicThumbnailContainer">

  <?php
foreach ($eqLogics as $eqLogic) {
	$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
	echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
  	$file = 'plugins/mobile/docs/images/' . $eqLogic->getConfiguration('type_mobile') . '.png';
	if (file_exists($file)) {
		$path = 'plugins/mobile/docs/images/' . $eqLogic->getConfiguration('type_mobile') . '.png';
		echo '<img src="' . $path . '" />';
	} else {
		$path = 'plugins/mobile/docs/images/mobile_icon.png';
		echo '<img src="' . $path . '" />';
	}
	echo '<br>';
	echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
	echo '</div>';
}
?>
</div>
</div>
</div>
  </div>
<div class="col-xs-12 eqLogic" style="display: none;">
  <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
  <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
  <a class="btn btn-info pull-right" id="info_app"><i class="fa fa-question-circle"></i> {{Infos envoyées à l'app}}</a>
  <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a>
  <ul class="nav nav-tabs" role="tablist">
   <li role="presentation"><a class="eqLogicAction cursor" aria-controls="home" role="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
   <li role="presentation" class="active"><a href="#eqlogictabin" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Mobile}}</a></li>
   <li role="presentation"><a href="#notificationtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Notifications}}</a></li>
   <li role="presentation"><a href="#sauvegardetab" aria-controls="sauvegarde" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Sauvegarde Mobile}}</a></li>
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
foreach (jeeObject::all() as $object) {
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
 <div class="row">
   <div class="col-lg-6">

   </div>
 </div>
</div>
</div>
</div>
<?php include_file('desktop', 'mobile', 'js', 'mobile');?>
<?php include_file('core', 'plugin.template', 'js');?>
