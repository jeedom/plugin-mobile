<?php
ini_set('display_errors', 0);
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'mobile');
include_file('3rdparty','qrcode/qrlib','php','mobile');
$invisible = null;
$eqLogics = eqLogic::byType('mobile');
// On creer l'adresse interne complete //
$adresseinterne = config::byKey('internalProtocol').''.config::byKey('internalAddr').':'.config::byKey('internalPort').''.config::byKey('internalComplement');

$api = config::byKey('api');
$utilisateur = config::byKey('market::username');

if(config::byKey('market::allowDNS') == 1){
	$type_dns = "{{DNS activé}}";
	$adresseexterne = config::byKey('jeedom::url');
}else{
	$type_dns = "{{DNS non activé}}";
	if(config::byKey('externalAddr') == ''){
		$adresseexterne = '{{Merci de vérifier vos configurations réseau}}';
		$invisible = 1;
	}else{
		$adresseexterne = config::byKey('externalProtocol').''.config::byKey('externalAddr').':'.config::byKey('externalPort').''.config::byKey('externalComplement');
		$invisible = 0;
	}
}

?>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar">
            <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
                <a class="btn btn-default eqLogicAction" style="width : 100%;margin-top : 5px;margin-bottom: 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter un mobile}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
foreach ($eqLogics as $eqLogic) {
	echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName() . '</a></li>';
}
?>
            </ul>
        </div>
    </div>

        <div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend>{{Mes téléphones mobiles}}
        </legend>

            <div class="eqLogicThumbnailContainer">
                      <div class="cursor eqLogicAction" data-action="add" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
           <center>
            <i class="fa fa-plus-circle" style="font-size : 7em;color:#94ca02;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 23px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>Ajouter</center></span>
    </div>
                <?php
foreach ($eqLogics as $eqLogic) {
	echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
	echo "<center>";
		echo '<img src="plugins/mobile/doc/images/mobile_icon.png" height="105" width="95" />';
	echo "</center>";
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
	echo '</div>';
}
?>
            </div>
        </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
        <form class="form-horizontal">
            <fieldset>
                <legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}  <i class='fa fa-cogs eqLogicAction pull-right cursor expertModeVisible' data-action='configure'></i></legend>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{Nom de l'équipement mobile}}</label>
                    <div class="col-sm-3">
                        <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                        <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement template}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                    <div class="col-sm-3">
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
                    <label class="col-sm-3 control-label" >{{Activer}}</label>
                    <div class="col-sm-1">
                        <input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" size="16" checked/>
                    </div>
                    <label class="col-sm-3 control-label" >{{Visible}}</label>
                    <div class="col-sm-1">
                        <input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>
                    </div>
                </div>
                <legend>{{Mobile}}</legend>
                <div class="form-group">
                    <label class="col-sm-3 control-label">{{Type de Mobile :}}</label>
                    <div class="col-sm-3">
                        <select class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="type_mobile">
                        	<option value="ios">iPhone</option>
                        	<option value="android">Android</option>
                        	<option value="windows">Windows</option>
                        </select>
                    </div><br /><br />
                    <label class="col-sm-3 control-label">{{Adresse interne :}}</label>
                    <div class="col-sm-3">
                    	<input type="text" class="eqLogicAttr configuration form-control" placeholder="<?php echo $adresseinterne; ?>" DISABLED />
                    </div>
                    <br /><br />
                    <label class="col-sm-3 control-label">{{Adresse externe :}}</label>
                    <div class="col-sm-3">
                    	<select disabled class="eqLogicAttr configuration form-control"><option><?php echo $type_dns; ?></option></select>
                    </div>
                    <div class="col-sm-3">
                    	<input type="text" class="eqLogicAttr configuration form-control" placeholder="<?php echo $adresseexterne; ?>" DISABLED />
                    </div>
                    <br /><br />
                    <label class="col-sm-3 control-label">{{Votre clé API :}}</label>
                    <div class="col-sm-3">
                    	<input type="text" class="eqLogicAttr configuration form-control" placeholder="<?php echo $api; ?>" DISABLED />
                    </div>
                    <br /><br />
                     <label class="col-sm-3 control-label">{{Nom d'utilisateur :}}</label>
                    <div class="col-sm-3">
                    	<input type="text" class="eqLogicAttr configuration form-control" placeholder="<?php echo $utilisateur; ?>" DISABLED />
                    </div>
                    <br /><br />
                    <center>{{Si vos configurations réseau ne sont pas bonne vous pouvez les changer : Général > Administration > Configuration}}</center>
                    <br /><br />
                    <center>
                    <?php 
                    if($invisible == 1){
	                    echo '<center><span class="label label-danger">Attention vous n\'avez pas configuré l\'adresse externe.</span></center>';
                    }else{
                    	//On creer le QRcode//
                    	$qrcode = mobile::json_for_qrcode($eqLogic->getId(),$adresseinterne,$adresseexterne,$api,$utilisateur);
	                    $filename = dirname(__FILE__) .'/../../3rdparty/qrcode/temp/qrcode.png';
						$errorCorrectionLevel = 'L';
						$matrixPointSize = 4;
						QRcode::png($qrcode, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
						//On montre le QRCode//
						$filename = '../plugins/mobile/3rdparty/qrcode/temp/qrcode.png';
						echo '<img src="'.$filename.'" />';
	                    }
                    ?>
                    </center>
                </div>
            </fieldset>
        </form>
<!-- TEST ISS -->
<legend>{{Plugin compatible :}}</legend>
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="configISS">
		<table class="table table-bordered table-condensed tablesorter" id="cmdList">
			<thead>
				<tr>
					<th>{{Objet}}</th>
					<th>{{Equipement}}</th>
					<th>{{Type}}</th>
					<th>{{Commande}}</th>
					<th>{{Transmettre}}</th>
					<th>{{Type App}}</th>
				</tr>
			</thead>
			<tbody>
				<?php
foreach (eqLogic::all() as $eqLogic) {
	if ($eqLogic->getIsEnable() == 0) {
		continue;
	}
	$object = $eqLogic->getObject();
	if (is_object($object) && $object->getIsVisible() == 0) {
		continue;
	}
	$cmds = $eqLogic->getCmd('info');
	if (count($cmds) == 0) {
		continue;
	}

	$countCmd = 0;
	foreach ($cmds as $cmd) {
		if (method_exists($cmd, 'imperihomeCmd') && !$cmd->imperihomeCmd()) {
			continue;
		}
		$countCmd++;
	}

	$firstLine = true;
	foreach ($cmds as $cmd) {
		if (method_exists($cmd, 'imperihomeCmd') && !$cmd->imperihomeCmd()) {
			continue;
		}		
		if($eqLogic->getEqType_name() == 'openzwave'){
			
		}else{
		if ($firstLine) {
			$firstLine = false;
			echo '<tr class="imperihome" data-cmd_id="' . $cmd->getId() . '">';
			echo '<td rowspan="' . $countCmd . '">';
			if (is_object($object)) {
				echo $object->getName();
			} else {
				echo __('Aucun', __FILE__);
			}
			echo '</td>';
			echo '<td rowspan="' . $countCmd . '">';
			echo $eqLogic->getName();
			echo '</td>';
			echo '<td rowspan="' . $countCmd . '">';
			echo $eqLogic->getEqType_name();
			echo '</td>';
		} else {
			echo '<tr class="tablesorter-childRow imperihome" data-cmd_id="' . $cmd->getId() . '">';
		}

		echo '<td>';
		echo $cmd->getName();
		echo '</td>';
		echo '<td>';
		echo '<input type="checkbox" class="imperihomeAttr bootstrapSwitch" data-size="small" data-label-text="{{Transmettre}}" data-l1key="cmd_transmit" />';
		echo '</td>';
		echo '<td>';
		echo '<span class="label label-info" style="font-size : 1em;">' . mobile::convertType($cmd) . '</span>';
		echo '<span class="btn btn-warning btn-xs pull-right expertModeVisible bt_createManualConfig" data-id="' . $cmd->getId() . '"><i class="fa fa-wrench"></i></span>';
		echo '</td>';
		echo '</tr>';
		}
	}
}

foreach (scenario::all() as $scenario) {
	$object = $scenario->getObject();
	echo '<tr class="imperihomeScenario" data-scenario_id="' . $scenario->getId() . '">';
	echo '<td>';
	if (is_object($object)) {
		echo $object->getName();
	} else {
		echo __('Aucun', __FILE__);
	}
	echo '</td>';
	echo '<td>';
	echo $scenario->getName();
	echo '</td>';
	echo '<td> {{Scénario}}';
	echo '</td>';
	echo '<td></td>';
	echo '<td>';
	echo '<input type="checkbox" class="imperihomeAttr bootstrapSwitch" data-size="small" data-l1key="scenario_transmit" data-label-text="{{Transmettre}}" />';
	echo '</td>';
	echo '<td>';
	echo ' <span class="label label-info" style="font-size : 1em;">Scene</span>';
	echo '</td>';
	echo '</tr>';
}
?>

			</tbody>
		</table>
	</div>
	
	
	
</div>

		<form class="form-horizontal">
            <fieldset>
                <div class="form-actions">
                    <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
                    <a class="btn btn-success eqLogicAction" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                </div>
            </fieldset>
        </form>


    </div>
</div>

<?php include_file('desktop', 'mobile', 'js', 'mobile');?>
<?php include_file('core', 'plugin.template', 'js');?>