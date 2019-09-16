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

<legend>{{Plugins Spécials Compatibles}}</legend>
  <span class="label label-success">{{Ils sont disponibles dans la liste des plugins de l'application, ils ont aussi une intégrations appronfondies sur le dashboard de l'app}}</span>
  <div class="pluginListContainer">
   <?php
foreach ($plugins as $plugin) {
	$opacity = '';
	if ($plugin->getId() != 'mobile' && $plugin->getId() != 'homebridge') {
		if (in_array($plugin->getId(), $plugin_compatible)) {
			if (in_array($plugin->getId(), $plugin_widget)) {
              echo '<div class="cursor pluginDisplayCard" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="'.$opacity.'">';
              echo '<center>';
              echo '<img class="img-responsive" src="' . $plugin->getPathImgIcon() . '" />';
              echo '</center>';
              echo '<span class="name">' . $plugin->getName() . '</span>';
              echo '</div>';
            }
		}
	}
}
?>
</div>

<legend>{{Plugins Validés Type générique}}</legend>
  <span class="label label-warning">{{Ils sont visibles dans les pièces de l'application mobile, pour certains d'entre eux il peut être nécessaire de configurer les types génériques (virtuels, scripts etc..). Il peut être désactivé pour ne pas être transmis}}</span>
  <div class="pluginListContainer">
   <?php
foreach ($plugins as $plugin) {
	$opacity = '';
	if ($plugin->getId() != 'mobile' && $plugin->getId() != 'homebridge') {
		if (in_array($plugin->getId(), $plugin_compatible)) {
			if (config::byKey('sendToApp', $plugin->getId(), 1) == 1 && !in_array($plugin->getId(), $plugin_widget)) {
              echo '<div class="cursor pluginDisplayCard" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="'.$opacity.'">';
              echo '<center>';
              echo '<img class="img-responsive" src="' . $plugin->getPathImgIcon() . '" />';
              echo '</center>';
              echo '<span class="name">' . $plugin->getName() . '</span>';
              echo '</div>';
            }
		}
	}
}
?>
</div>

<legend>{{Plugins compatibles non transmis}}</legend>
  <span class="label label-danger">{{N\'est pas transmis à l'application}}</span>
  <div class="pluginListContainer">
   <?php
foreach ($plugins as $plugin) {
	$opacity = '';
	if ($plugin->getId() != 'mobile' && $plugin->getId() != 'homebridge') {
		if (in_array($plugin->getId(), $plugin_compatible)) {
			if (config::byKey('sendToApp', $plugin->getId(), 1) != 1 && !in_array($plugin->getId(), $plugin_widget)) {
            	$opacity = jeedom::getConfiguration('eqLogic:style:noactive');
              echo '<div class="cursor pluginDisplayCard" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="'.$opacity.'">';
              echo '<center>';
              echo '<img class="img-responsive" src="' . $plugin->getPathImgIcon() . '" />';
              echo '</center>';
              echo '<span class="name">' . $plugin->getName() . '</span>';
              echo '</div>';
            }
		}
	}
}
?>
</div>

<legend>{{Plugins non testés transmis à l'application}}</legend>
  <span class="label label-warning">{{Vous avez activé la transmission de ces plugins en se basant sur les types génériques}}</span>
  <div class="pluginListContainer">
   <?php
foreach ($plugins as $plugin) {
	$opacity = '';
	if ($plugin->getId() != 'mobile' && $plugin->getId() != 'homebridge') {
		if (!in_array($plugin->getId(), $plugin_compatible)) {
			if (config::byKey('sendToApp', $plugin->getId(), 0) == 1) {
              echo '<div class="cursor pluginDisplayCard" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="'.$opacity.'">';
              echo '<center>';
              echo '<img class="img-responsive" src="' . $plugin->getPathImgIcon() . '" />';
              echo '</center>';
              echo '<span class="name">' . $plugin->getName() . '</span>';
              echo '</div>';
            }
		}
	}
}
?>
</div>

<legend>{{Plugins non testés et non transmis}}</legend>
  <span class="label label-danger">{{N\'est pas transmis à l'application}}</span>
  <div class="pluginListContainer">
   <?php
foreach ($plugins as $plugin) {
	$opacity = '';
	if ($plugin->getId() != 'mobile' && $plugin->getId() != 'homebridge') {
		if (!in_array($plugin->getId(), $plugin_compatible)) {
			if (config::byKey('sendToApp', $plugin->getId(), 0) != 1) {
              $opacity = jeedom::getConfiguration('eqLogic:style:noactive');
              echo '<div class="cursor pluginDisplayCard" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="'.$opacity.'">';
              echo '<center>';
              echo '<img class="img-responsive" src="' . $plugin->getPathImgIcon() . '" />';
              echo '</center>';
              echo '<span class="name">' . $plugin->getName() . '</span>';
              echo '</div>';
            }
		}
	}
}
?>
</div>

  <?php include_file('desktop', 'mobile', 'js', 'mobile');?>
  <?php include_file('core', 'plugin.template', 'js');?>
  <?php include_file("desktop", "plugin", "js");?>
