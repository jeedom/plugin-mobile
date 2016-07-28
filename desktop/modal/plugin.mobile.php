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
	<div class="col-lg-10 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
		<legend><i class="fa fa-info"></i>  {{Envoi au près de l'app mobile}}
    </legend>
    <?php
    if(in_array($plugin->getId(), $plugin_widget)){
    	echo '<div class="alert alert-success" role="alert">';
    	echo '{{Le Plugin est entièrement compatible, il ne nécessite aucune action de votre part}}';
    	echo '</div>';
    	$generique_ok = false;
    }else if(in_array($plugin->getId(), $plugin_compatible)){
    	echo '<div class="alert alert-info div_plugin_configuration" role="alert">';
	    echo '{{Le Plugin est compatible mais il vous faut peux être vérifier les Types génériques des commandes}}';
		echo '<label class="checkbox-inline pull-right"><input type="checkbox" class="configKey" data-l1key="sendToApp" checked/>{{Activer}}</label>';
		echo '</div>';
		$generique_ok = true;
    }else{
    	echo '<div class="alert alert-danger div_plugin_configuration" role="alert">';
	    echo '{{Le Plugin n\'est pas compatible, vous pouvez l\'activer si vous le souhaitez}}';
		echo '<label class="checkbox-inline pull-right"><input type="checkbox" class="configKey" data-l1key="sendToApp" unchecked/>{{Activer}}</label>';
		echo '</div>';
	    $generique_ok = true;
    }
    ?>
	</div>
	<?php
	if($generique_ok == true){
	echo '<div class="col-lg-10 col-md-9 col-sm-8 eqLogicPluginDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">';
	echo '<legend><i class="fa fa-building"></i>  {{Type Générique du Plugin}}
    <div class="form-actions pull-right">
		<a class="btn btn-success eqLogicAction"  style="padding:0px 3px 0px 3px;" onclick="SavePlugin()"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
	</div></legend>';
    ?>
    <div class="EnregistrementDisplay"></div>
    
    <?php
    	$tableau_cmd = array();
		$eqLogics = eqLogic::byType($_GET['plugin_id']);
		foreach ($eqLogics as $eqLogic){
		echo '<div class="panel panel-primary">';
		echo '<div class="panel-heading">'.$eqLogic->getHumanName(true).'<a class="btn btn-mini btn-success eqLogicAction pull-right" style="padding:0px 3px 0px 3px;cursor:pointer;" onclick="SavePlugin()"><i class="fa fa-floppy-o"></i></a></div>';
			$cmds = null;
			$cmds = cmd::byEqLogicId($eqLogic->getId());
			echo '<table class="table TableCMD">';
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
				if (in_array($cmd->getDisplay('generic_type'), ['GENERIC','GENERIC_ACTION'])) {
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
		}
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

// CHANGE CLICK
$('.cmdAttr').on('change click',function(){
   $(this).closest('tr').attr('data-change','1');
});

// SAUVEGARDE
function SavePlugin(){
   var cmds = [];
   $('.TableCMD tr').each(function(){
   	if($(this).attr('data-change') == '1'){
       cmds.push($(this).getValues('.cmdAttr')[0]);
    }
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
    if ($(this).value() == 'GENERIC' || $(this).value() == 'GENERIC_ACTION') {
		cmdLine.find('.iconeGeneric').show();
    } else {
        cmdLine.find('.iconeGeneric').hide();
        cmdLine.find('.cmdAttr[data-l1key=display][data-l2key=icon]').empty();
    }
});
</script>
