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

if (!isConnect('admin')) {
	throw new Exception('401 Unauthorized');
}
sendVarToJS('eqType', 'mobile');
$eqLogics = eqLogic::byType('mobile');
$plugins = plugin::listPlugin(true);
$plugin_compatible = mobile::$_pluginSuported;
$plugin_widget = mobile::$_pluginWidget;
?>
  <legend><i class="fas fa-check-circle-o"></i>  {{Le(s) Plugin(s) Compatible(s)}}</legend>
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
					$text = '<center><span class="label label-info" style="font-size : 0.9em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;" title="Il est visible dans les pièces de l\'application mobile, pour certains d\'entre eux il peut être nécessaire de configurer les types génériques (virtuels, scripts etc..). Il peut être désactivé pour ne pas être transmis">{{Type générique}}</span></center>';
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
				echo '<span><center>' . $plugin->getName() . '</center></span>';
			}
			echo $text;
			echo '</div>';
		}
	}
}
?>
</div>
<legend><i class="fas fa-times-circle-o"></i>  {{Le(s) Plugin(s) Non Testé(s)}}</legend>
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
				echo '<span><center>' . $plugin->getName() . '</center></span>';
			}
			echo $text;
			echo '</div>';
		}
	}
}
?>
</div>
  <?php include_file('desktop', 'mobile', 'js', 'mobile');?>
  <?php include_file('core', 'plugin.template', 'js');?>