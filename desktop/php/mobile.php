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
		echo '<img src="plugins/mobile/desktop/images/phone_icon.png" height="105" width="95" />';
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
               		<label class="col-sm-3 control-label"></label>
                    <div class="col-sm-8">
                    	<input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>
						<input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>
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
                    	if($object->getId() == null || $object->getId() == ''){
	                    	$id = 'null';
                    	}else{
	                    	$id = $object->getId();
							$qrcode = mobile::json_for_qrcode($id,$adresseinterne,$adresseexterne,$api,$utilisateur);
							$filename = dirname(__FILE__) .'/../../3rdparty/qrcode/temp/qrcode.png';
							$errorCorrectionLevel = 'L';
							$matrixPointSize = 4;
							QRcode::png($qrcode, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
							//On montre le QRCode//
							$filename = '../plugins/mobile/3rdparty/qrcode/temp/qrcode.png';
							echo '<img src="'.$filename.'" />';
						}
					}
	                    
                    ?>
                    </center>
                </div>
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