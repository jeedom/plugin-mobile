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

$plugin_compatible = mobile::$_pluginSuported;
$plugin_widget = mobile::$_pluginWidget;
$plugin = plugin::byId($_GET['plugin_id']);
sendVarToJS('pluginId', $_GET['plugin_id']);
?>

<div class="row">
	<ul class="nav nav-tabs" style="padding-left:8px">
		<li><a class="cursor" id="bt_returnPlugins" style="width:32px;"><i class="fas fa-arrow-circle-left"></i></a></li>
		<li class="active">
		<?php
			if (file_exists(dirname(__FILE__) . '/../../../../' . $plugin->getPathImgIcon())) {
			  echo '<a><img width="14px" src="' . $plugin->getPathImgIcon() . '" /> '.$plugin->getName().'</a>';
			} else {
			  echo '<a><i class="fas fa-tags"></i> '.$plugin->getName().'</a>';
			}
		?>
		</li>
	</ul>

	<div class="col-lg-12 col-md-12 col-sm-12 eqLogicThumbnailDisplay">
		<legend><i class="fa fa-info"></i>  {{Envoi auprès de l'app mobile}}</legend>
		<?php
		if (in_array($plugin->getId(), $plugin_widget)) {
			$div = '<div class="alert alert-success" role="alert">';
			$div .= '{{Le Plugin est entièrement compatible, il ne nécessite aucune action de votre part}}';
			$div .= '</div>';
			$div .= '<center>';
			$path = dirname(__FILE__) . '/../../core/template/images/' . $plugin->getId();
			$files = scandir($path);
			foreach ($files as $imgname) {
				if (!in_array($imgname, ['.', '..'])) {
					$div .= '<img margin="10px" src="plugins/mobile/core/template/images/' . $plugin->getId() . '/' . $imgname . '" height="500"/>';
				}
			}
			$div .= '</center>';
			echo $div;
			$generique_ok = false;
		} else if (in_array($plugin->getId(), $plugin_compatible)) {
			$div = '<div class="alert alert-info div_plugin_configuration" role="alert">';
			$div .=  '{{Le Plugin est compatible mais il vous faut peux être vérifier les Types génériques des commandes}}';
			$check = 'checked';
			if (config::byKey('sendToApp', $plugin->getId(), 1) == 0) {
				$check = 'unchecked';
			}
			$div .=  '<label class="checkbox-inline pull-right"><input type="checkbox" class="configKey" data-l1key="sendToApp" ' . $check . '/>{{Activer}}</label>';
			$div .=  '</div>';
			echo $div;
			$generique_ok = true;
		} else {
			$div = '<div class="alert alert-danger div_plugin_configuration" role="alert">';
			$div .=   '{{Le Plugin n\'est pas compatible, vous pouvez l\'activer si vous le souhaitez}}';
			$check = 'unchecked';
			if (config::byKey('sendToApp', $plugin->getId(), 0) == 1) {
				$check = 'checked';
			}
			$div .=   '<label class="checkbox-inline pull-right"><input type="checkbox" class="configKey" data-l1key="sendToApp" ' . $check . '/>{{Activer}}</label>';
			$div .=   '</div>';
			echo $div;
			$generique_ok = true;
		}
		?>
	</div>
	<?php
		if ($generique_ok) {
			echo '<div class="col-lg-12 col-md-12 col-sm-12 eqLogicPluginDisplay">';
			echo '<legend><i class="fa fa-building"></i>  {{Type Générique du Plugin}}<a class="btn btn-sm btn-success pull-right" onclick="SavePlugin()"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a></legend>';
	?>
	<div class="EnregistrementDisplay"></div>
	<?php
		$tableau_cmd = array();
		$eqLogics = eqLogic::byType($_GET['plugin_id']);
		$subClasses = config::byKey('subClass', $_GET['plugin_id'], '');
		if ($subClasses != '') {
			$subClassesList = explode(';', $subClasses);
			foreach ($subClassesList as $subClass) {
				$subEqLogics = eqLogic::byType($subClass);
				$eqLogics = array_merge($eqLogics, $subEqLogics);
			}
		}
		$checkHomebridge = '';
		echo '<div class="panel-group" id="accordionConfiguration">';
		foreach ($eqLogics as $eqLogic) {
			echo '<div class="panel panel-default">';
			if ($eqLogic->getEqType_name() != $_GET['plugin_id']) {
				$subClassName = ' (' . $eqLogic->getEqType_name() . ') ';
			} else {
				$subClassName = '';
			}
			$eqLogicId = $eqLogic->getId();
			echo ' <div class="panel-heading">
					<h3 class="panel-title">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionConfiguration" href="#config_' . $eqLogicId . '" style="text-decoration:none"><span class="eqLogicAttr hidden" data-l1key="id">' . $eqLogicId . '</span>' . $eqLogic->getHumanName(true) . $subClassName . '<a class="btn btn-xs btn-success eqLogicAction pull-right" onclick="SavePlugin()"><i class="fas fa-save"></i></a>' . $checkHomebridge . '
						</a>
					</h3>
				</div>';
			echo '<div id="config_' . $eqLogicId . '" class="panel-collapse collapse">';
			echo '<div class="panel-body">';
			$cmds = null;
			$cmds = cmd::byEqLogicId($eqLogicId);
			echo '<table id=' . $eqLogicId . ' class="table TableCMD">';
			echo '<tr>
					<th>{{Id Cmd}}</th>
					<th>{{Nom de la Commande}}</th>
					<th>{{Type Générique}}</th>
				</tr>';
			foreach ($cmds as $cmd) {
				array_push($tableau_cmd, $cmd->getId());
				echo '<tr class="cmdLine">';
				echo '<td>';
				echo '<span class="cmdAttr" data-l1key="id">' . $cmd->getId() . '</span>';
				echo '</td>';
				echo '<td>';
				echo $cmd->getName();
				$display_icon = 'none';
				$icon = '';
				if (in_array($cmd->getGeneric_type(), ['GENERIC_INFO', 'GENERIC_ACTION'])) {
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
			?>
			<select class="cmdAttr form-control" data-l1key="generic_type" data-cmd_id="<?php echo $cmd->getId(); ?>">
				<option value="">{{Aucun}}</option>
				<?php
				$groups = array();
				foreach (jeedom::getConfiguration('cmd::generic_type') as $key => $info) {
					if (strtolower($cmd->getType()) != strtolower($info['type'])) {
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
					if ($info['key'] == $cmd->getGeneric_type()) {
						echo '<option value="' . $info['key'] . '" selected>' . $info['type'] . ' / ' . $info['name'] . '</option>';
					} else {
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
	<a class="btn btn-sm btn-success pull-right" onclick="SavePlugin()" ><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
	<?php
	echo '</div>';
	}
	?>
</div>



<script>

$('#bt_returnPlugins').on('click', function () {
	$('#md_modal').dialog({title: "{{Plugins compatibles}}"})
	$('#md_modal').load('index.php?v=d&plugin=mobile&modal=plugin').dialog('open')
})

var changed = 0
var eqLogicsHomebridge = []

$('.cmdAttr').on('change click',function() {
	$(this).closest('tr').attr('data-change','1')
})
$('.configKey').on('change click',function() {
	changed = 1
})
$('.eqLogicAttr').on('change click',function() {
	var eqLogic = $(this).closest('.panel-title').getValues('.eqLogicAttr')[0]
	eqLogicsHomebridge.push(eqLogic)
})

function SavePlugin() {
	var cmds = []
	$('.TableCMD tr').each(function() {
		if($(this).attr('data-change') == '1') {
			cmds.push($(this).getValues('.cmdAttr')[0])
		}
	})

	var eqLogicsHomebridgeFiltered = []
	$.each(eqLogicsHomebridge, function(index, eqLogic) {
		var eqLogics = $.grep(eqLogicsHomebridgeFiltered, function (e) {
			return eqLogic.id === e.id
		})
		if (eqLogics.length === 0) {
			eqLogicsHomebridgeFiltered.push(eqLogic)
		}
	})
	$.each(eqLogicsHomebridgeFiltered ,function(index, eqLogic) {
		jeedom.eqLogic.simpleSave({
			eqLogic : eqLogic,
			error: function (error) {
				$('.EnregistrementDisplay').showAlert({message: error.message, level: 'danger'})
			},
			success: function (data) {
				$('.EnregistrementDisplay').showAlert({message: '{{Modifications sauvegardées avec succès}}', level: 'success'})
				eqLogicsHomebridge = []
			}
		})
	})

	jeedom.cmd.multiSave({
		cmds : cmds,
		error: function (error) {
			$('.EnregistrementDisplay').showAlert({message: error.message, level: 'danger'})
		},
		success: function (data) {
			$('.EnregistrementDisplay').showAlert({message: '{{Modifications sauvegardées avec succès}}', level: 'success'})
		}
	})

	jeedom.config.save({
		configuration: $('.div_plugin_configuration').getValues('.configKey')[0],
		plugin: pluginId,
		error: function (error) {
			$('.EnregistrementDisplay').showAlert({message: error.message, level: 'danger'})
		},
		success: function () {
			$('.EnregistrementDisplay').showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'})
		}
	})
}

$('body').undelegate('.cmdAction[data-l1key=chooseIcon]', 'click').delegate('.cmdAction[data-l1key=chooseIcon]', 'click', function () {
	var iconeGeneric = $(this).closest('.iconeGeneric');
	chooseIcon(function (_icon) {
		iconeGeneric.find('.cmdAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon)
	})
	$(this).closest('tr').attr('data-change','1')
})

$('body').undelegate('.cmdAttr[data-l1key=display][data-l2key=icon]', 'click').delegate('.cmdAttr[data-l1key=display][data-l2key=icon]', 'click', function () {
   $(this).empty()
})

$('.cmdAttr[data-l1key=generic_type]').on('change', function () {
	var cmdLine = $(this).closest('.cmdLine')
	if ($(this).value() == 'GENERIC_INFO' || $(this).value() == 'GENERIC_ACTION') {
		cmdLine.find('.iconeGeneric').show()
	} else {
		cmdLine.find('.iconeGeneric').hide()
		cmdLine.find('.cmdAttr[data-l1key=display][data-l2key=icon]').empty()
	}
})

$('#md_modal').on('dialogclose', function () {
   if (changed == 1) {
	   location.reload()
   }
})
</script>