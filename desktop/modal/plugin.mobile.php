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
 ini_set('display_errors', 0);
if (!isConnect('admin')) {
	throw new Exception('{{401 - AccèÈs non autorisÈ}}');
}

$plugin_compatible = mobile::Pluginsuported();
$plugin_widget = mobile::PluginWidget();
$plugin = plugin::byId($_GET['plugin_id']);
sendVarToJS('pluginId', $_GET['plugin_id']);
?>

<div class="row row-overflow">
	<center>
		<?php
					if (file_exists(dirname(__FILE__) . '/../../../../' . $plugin->getPathImgIcon())) {
		echo '<img class="img-responsive" style="width : 120px;" src="' . $plugin->getPathImgIcon() . '" />';
		echo "</center>";
	} else {
		echo '<i class="' . $plugin->getIcon() . '" style="font-size : 6em;margin-top:20px;"></i>';
		echo "</center>";
		echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $plugin->getName() . '</center></span>';
	}
?>
	</center>
	<div class="col-lg-12 col-md-12 col-sm-12 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
		<legend><i class="fa fa-info"></i>  {{Envoi auprès de l'app mobile}}
    </legend>
    <?php
    if(in_array($plugin->getId(), $plugin_widget)){
    	echo '<div class="alert alert-success" role="alert">';
    	echo '{{Le Plugin est entièrement compatible, il ne nécessite aucune action de votre part}}';
    	echo '</div>';
		echo '<center>';
		$path = dirname(__FILE__) . '/../../core/template/images/' . $plugin->getId();
        $files = scandir($path);
        foreach ($files as $imgname){
			 if (!in_array($imgname, ['.','..'])){
				echo '<img margin="10px" src="plugins/mobile/core/template/images/' . $plugin->getId() . '/' . $imgname . '" height="500"/>';
			 }
        }
        echo '</center>';
    	$generique_ok = false;
    }else if(in_array($plugin->getId(), $plugin_compatible)){
    	echo '<div class="alert alert-info div_plugin_configuration" role="alert">';
	    echo '{{Le Plugin est compatible mais il vous faut peux être vérifier les Types génériques des commandes}}';
		$check = 'checked';
		if (config::byKey('sendToApp', $plugin->getId(), 1) == 0) {
			$check = 'unchecked';
		}
		echo '<label class="checkbox-inline pull-right"><input type="checkbox" class="configKey" data-l1key="sendToApp" ' . $check .'/>{{Activer}}</label>';
		echo '</div>';
		$generique_ok = true;
    }else{
    	echo '<div class="alert alert-danger div_plugin_configuration" role="alert">';
	    echo '{{Le Plugin n\'est pas compatible, vous pouvez l\'activer si vous le souhaitez}}';
		$check = 'unchecked';
		if (config::byKey('sendToApp', $plugin->getId(), 0) == 1) {
			$check = 'checked';
		}
		echo '<label class="checkbox-inline pull-right"><input type="checkbox" class="configKey" data-l1key="sendToApp" ' . $check .'/>{{Activer}}</label>';
		echo '</div>';
	    $generique_ok = true;
    }
    ?>
	</div>
	<?php
	if($generique_ok == true){
	echo '<div class="col-lg-12 col-md-12 col-sm-12 eqLogicPluginDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">';
	echo '<legend><i class="fa fa-building"></i>  {{Type Générique du Plugin}}
    <div class="form-actions pull-right">
		<a class="btn btn-success eqLogicAction"  style="padding:0px 3px 0px 3px;" onclick="SavePlugin()"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
	</div></legend>';
    ?>
    <div class="EnregistrementDisplay"></div>
    
    <?php
    	$tableau_cmd = array();
		$eqLogics = eqLogic::byType($_GET['plugin_id']);
		$subClasses = config::byKey('subClass', $_GET['plugin_id'], '');
		if ($subClasses != ''){
			$subClassesList = explode(';',$subClasses);
			foreach ($subClassesList as $subClass){
				$subEqLogics = eqLogic::byType($subClass);
				$eqLogics = array_merge ($eqLogics, $subEqLogics);
			}
		}
		$checkHomebridge = '';
		echo '<div class="panel-group" id="accordionConfiguration">';
		foreach ($eqLogics as $eqLogic){
			if(mobile::check_ios() == 1){
				$check = 'checked';
				if ($eqLogic->getConfiguration('sendToHomebridge', 1) == 0) {
					$check = 'unchecked';
				}
				$checkHomebridge = '<small><label style="cursor:default;margin-left:5px">{{  Envoyer à Homebridge  }}<input style="display:inline-block" type="checkbox" class="eqLogicAttr configuration" data-l1key="configuration" data-l2key="sendToHomebridge"' . $check .'/></label></small>';;
			}
		echo '<div class="panel panel-default">';
		if ($eqLogic->getEqType_name() != $_GET['plugin_id']){
			$subClassName = ' (' . $eqLogic->getEqType_name() . ') ';
		} else {
			$subClassName = '';
		}
		echo ' <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_'.$eqLogic->getId().'" style="text-decoration:none"><span class="eqLogicAttr hidden" data-l1key="id">'.$eqLogic->getId().'</span>'.$eqLogic->getHumanName(true). $subClassName . '<a class="btn btn-mini btn-success eqLogicAction pull-right" style="padding:0px 3px 0px 3px;cursor:pointer;" onclick="SavePlugin()"><i class="fa fa-floppy-o" style="color:white;"></i></a>'.$checkHomebridge.'
                    </a>
                </h3>
            </div>';
			echo '<div id="config_'.$eqLogic->getId().'" class="panel-collapse collapse">';
			echo '<div class="panel-body">';
			$cmds = null;
			$cmds = cmd::byEqLogicId($eqLogic->getId());
			echo '<table id=' . $eqLogic->getId() . ' class="table TableCMD">';
			echo '<tr>
				<th>{{Id Cmd}}</th>
				<th>{{Nom de la Commande}}</th>
				<th>{{Type Générique}}</th>
			</tr>';
			foreach ($cmds as $cmd){
			array_push($tableau_cmd, $cmd->getId());
				echo '<tr class="cmdLine">';
				echo '<td>';
				echo '<span class="cmdAttr" data-l1key="id">'.$cmd->getId().'</span>';
				echo '</td>';
				echo '<td>';
				echo $cmd->getName();
				$display_icon = 'none';
				$icon ='';
				if (in_array($cmd->getDisplay('generic_type'), ['GENERIC_INFO','GENERIC_ACTION'])) {
					$display_icon = 'block';
					$icon = $cmd->getDisplay('icon');
				}
				echo '<div class="iconeGeneric pull-right" style="display:' . $display_icon . ';">
				<div>
                <span class="cmdAttr label label-info cursor" data-l1key="display" data-l2key="icon" style="font-size : 1.2em;" >' . $icon . '</span>
                <a class="cmdAction btn btn-default btn-sm" data-l1key="chooseIcon"><i class="fa fa-flag"></i> {{Icône}}</a>
				</div>
				</div>';
				echo '</td>';
				echo '<td>';
				?><select class="cmdAttr form-control" data-l1key="display" data-l2key="generic_type" data-cmd_id="<?php echo $cmd->getId(); ?>">
             <option value="">{{Aucun}}</option>
             <?php
    $groups = array();
    foreach (jeedom::getConfiguration('cmd::generic_type') as $key => $info) {
        if ($cmd->getType() == 'info' && $info['type'] == 'Action') {
            continue;
        } elseif ($cmd->getType() == 'action' && $info['type'] == 'Info') {
            continue;
        }  elseif (isset($info['ignore']) && $info['ignore'] == true) {
            continue;
        }
        $info['key'] = $key;
        if (!isset($groups[$info['family']])) {
            $groups[$info['family']][0] = $info;
        } else {
            array_push($groups[$info['family']], $info);
        }
    }
    ksort($groups);
    foreach ($groups as $group) {
    usort($group, function ($a, $b) {
        return strcmp($a['name'], $b['name']);
    });
    foreach ($group as $key => $info) {
        if ($key == 0) {
            echo '<optgroup label="{{' . $info['family'] . '}}">';
        }
        if($info['key'] == $cmd->getDisplay('generic_type')){
	        echo '<option value="' . $info['key'] . '" selected>' . $info['name'] . '</option>';
        }else{
        	echo '<option value="' . $info['key'] . '">' . $info['name'] . '</option>';
        }
    }
    echo '</optgroup>';
    }
?>
          </select>
          <?php
				echo '</td>';
				echo '</tr>';
			}
			echo '</table>';
			echo '</div>';
			echo '</div>';
			
			echo '</div>';
			
		}
		echo '</div>';
		?>
			<div class="form-actions pull-right">
		<a class="btn btn-success eqLogicAction" onclick="SavePlugin()" ><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
	</div>

		<?php
	echo '</div>';
	}
	?>
</div>

<script>
var changed=0;
// CHANGE CLICK
$('.cmdAttr').on('change click',function(){
   $(this).closest('tr').attr('data-change','1');
});
$('.configKey').on('change click',function(){
   changed=1;
});
// SAUVEGARDE
function SavePlugin(){
   var cmds = [];
   $('.TableCMD tr').each(function(){
   	if($(this).attr('data-change') == '1'){
       cmds.push($(this).getValues('.cmdAttr')[0]);
    }
   });
   $('.panel-title').each(function(){
   	   jeedom.eqLogic.simpleSave({
	       eqLogic : $(this).getValues('.eqLogicAttr')[0],
	       error: function (error) {
	           $('.EnregistrementDisplay').showAlert({message: error.message, level: 'danger'});
	       },
	       success: function (data) {
	          $('.EnregistrementDisplay').showAlert({message: '{{Modifications sauvegardées avec succès}}', level: 'success'});
	      }
	  });
   });

   jeedom.cmd.multiSave({
       cmds : cmds,
       error: function (error) {
           $('.EnregistrementDisplay').showAlert({message: error.message, level: 'danger'});
       },
       success: function (data) {
          $('.EnregistrementDisplay').showAlert({message: '{{Modifications sauvegardées avec succès}}', level: 'success'});
      }
      
	  });
	  
	  jeedom.config.save({
    configuration: $('.div_plugin_configuration').getValues('.configKey')[0],
    plugin: pluginId,
    error: function (error) {
       $('.EnregistrementDisplay').showAlert({message: error.message, level: 'danger'});
    },
    success: function () {
       $('.EnregistrementDisplay').showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
    }
  });
}

// ICONE
$('body').undelegate('.cmdAction[data-l1key=chooseIcon]', 'click').delegate('.cmdAction[data-l1key=chooseIcon]', 'click', function () {
   var iconeGeneric = $(this).closest('.iconeGeneric');
    chooseIcon(function (_icon) {
        iconeGeneric.find('.cmdAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
   });
   $(this).closest('tr').attr('data-change','1');
});

$('body').undelegate('.cmdAttr[data-l1key=display][data-l2key=icon]', 'click').delegate('.cmdAttr[data-l1key=display][data-l2key=icon]', 'click', function () {
   $(this).empty();
});

$('.cmdAttr[data-l1key=display][data-l2key=generic_type]').on('change', function () {
	var cmdLine = $(this).closest('.cmdLine');
    if ($(this).value() == 'GENERIC_INFO' || $(this).value() == 'GENERIC_ACTION') {
		cmdLine.find('.iconeGeneric').show();
    } else {
        cmdLine.find('.iconeGeneric').hide();
        cmdLine.find('.cmdAttr[data-l1key=display][data-l2key=icon]').empty();
    }
});
$('#md_modal').on('dialogclose', function () {
   if(changed==1) {
	   location.reload();
   }
})
</script>
