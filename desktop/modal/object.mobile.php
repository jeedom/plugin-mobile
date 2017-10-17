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

$object = object::byId($_GET['object_id']);
sendVarToJS('object', $_GET['object_id']);
?>

<div class="row">
	<?php
	echo "<div><center>";
	echo str_replace('></i>', ' style="font-size : 6em;color:#767676;"></i>', $object->getDisplay('icon', '<i class="fa fa-lemon-o"></i>'));
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $object->getName() . '</center></span>';
	echo "</center></div><br/>";
	echo '<div class="alert alert-info div_object_configuration" role="alert">';
	    echo '{{Vous pouvez activer ou désactiver l\'envoi de cette pièce vers l\'application}}';
		$check = 'checked';
		if ($object->getDisplay('sendToApp', 1) == 0) {
			$check = 'unchecked';
		}
		echo '<label class="checkbox-inline pull-right"><input type="checkbox" class="objectAttr" data-l1key="display" data-l2key="sendToApp" ' . $check .'/>{{Activer}}</label>';
		echo '<span class="form-control objectAttr" type="text" data-l1key="id" style="display : none;">' . $_GET['object_id'] . '</span>';
		echo '<span class="form-control objectAttr" type="text" data-l1key="name" style="display : none;">' . $object->getName() . '</span>';
		echo '</div>';
		echo '<div class="col-lg-12 col-md-12 col-sm-12 eqLogicPluginDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">';
	echo '<legend><i class="fa fa-building"></i>  {{Type Générique de l\'objet}}
    <div class="form-actions pull-right">
		<a class="btn btn-success eqLogicAction"  style="padding:0px 3px 0px 3px;" onclick="SaveObject()"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
	</div></legend>';
    ?>

<div class="EnregistrementDisplay"></div>
    
    <?php
    	$tableau_cmd = array();
		$eqLogics = $object->getEqLogic();
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
		echo ' <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_'.$eqLogic->getId().'" style="text-decoration:none"><span class="eqLogicAttr hidden" data-l1key="id">'.$eqLogic->getId().'</span>'.$eqLogic->getHumanName(true). '<a class="btn btn-mini btn-success eqLogicAction pull-right" style="padding:0px 3px 0px 3px;cursor:pointer;" onclick="SaveObject()"><i class="fa fa-floppy-o" style="color:white;"></i></a>'.$checkHomebridge.'
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
	        echo '<option value="' . $info['key'] . '" selected>' . $info['type'] . ' / ' . $info['name'] . '</option>';
        }else{
        	echo '<option value="' . $info['key'] . '">' . $info['type'] . ' / ' . $info['name'] . '</option>';
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
		<a class="btn btn-success eqLogicAction" onclick="SaveObject()" ><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
	</div>

		<?php
	echo '</div>';
	?>
</div>

<script>
var changed=0;
var eqLogicsHomebridge = [];
// CHANGE CLICK
$('.cmdAttr').on('change click',function(){
   $(this).closest('tr').attr('data-change','1');
});
$('.objectAttr').on('change click',function(){
   changed=1;
});
$('.eqLogicAttr').on('change click',function(){
	var eqLogic = $(this).closest('.panel-title').getValues('.eqLogicAttr')[0];
	eqLogicsHomebridge.push(eqLogic);
	
});

// SAUVEGARDE
function SaveObject(){
   var cmds = [];
   $('.TableCMD tr').each(function(){
   	if($(this).attr('data-change') == '1'){
       cmds.push($(this).getValues('.cmdAttr')[0]);
    }
   });
   var eqLogicsHomebridgeFiltered = [];
   $.each(eqLogicsHomebridge, function(index, eqLogic) {
	    var eqLogics = $.grep(eqLogicsHomebridgeFiltered, function (e) {
	        return eqLogic.id === e.id;
	    });
	    if (eqLogics.length === 0) {
	      eqLogicsHomebridgeFiltered.push(eqLogic);
	    }
	});
   $.each(eqLogicsHomebridgeFiltered ,function(index, eqLogic){
   	   jeedom.eqLogic.simpleSave({
	       eqLogic : eqLogic,
	       error: function (error) {
	           $('.EnregistrementDisplay').showAlert({message: error.message, level: 'danger'});
	       },
	       success: function (data) {
	          $('.EnregistrementDisplay').showAlert({message: '{{Modifications sauvegardées avec succès}}', level: 'success'});
	          eqLogicsHomebridge = [];
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
	  
	   jeedom.object.save({
            object: $('.div_object_configuration').getValues('.objectAttr')[0],
            error: function (error) {
                $('.EnregistrementDisplay').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                modifyWithoutSave = false;
               $('.EnregistrementDisplay').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
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
