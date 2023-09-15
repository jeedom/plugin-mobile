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

<legend class="pluspecial">{{Plugins Spéciaux Compatibles}}
	<sup><i class="fas fa-question-circle" title="{{Disponibles dans la liste des plugins et intégrés au Dashboard de l’App Mobile.}}"></i></sup>
</legend>
<div class="pluginListContainer">
	<?php
	$num = 0;
	$_echo = '';
	foreach ($plugins as $plugin) {
		$opacity = '';
		if ($plugin->getId() != 'mobile' && $plugin->getId() != 'homebridge') {
			if (in_array($plugin->getId(), $plugin_compatible)) {
				if (in_array($plugin->getId(), $plugin_widget)) {
					$_echo .= '<div class="cursor pluginDisplayCard" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="' . $opacity . '">';
					$_echo .= '<center>';
					$_echo .= '<img class="img-responsive" src="' . $plugin->getPathImgIcon() . '" />';
					$_echo .= '</center>';
					$_echo .= '<span class="name">' . $plugin->getName() . '</span>';
					$_echo .= '</div>';
					$num++;
				}
			}
		}
	}
	echo $_echo;
	if ($num == 0) {
		echo '<style>.pluspecial { Display:None; }</style>';
	}
	?>
</div>

<legend class="pluvaltg">{{Plugins Validés Type générique}}
	<sup><i class="fas fa-question-circle" title="{{Visibles dans l'app, peuvent nécessiter un type générique, peuvent être désactivés.}}"></i></sup>
</legend>
<div class="pluginListContainer">
	<?php
	$num = 0;
	$_echo = '';
	foreach ($plugins as $plugin) {
		$opacity = '';
		if ($plugin->getId() != 'mobile' && $plugin->getId() != 'homebridge') {
			if (in_array($plugin->getId(), $plugin_compatible)) {
				if (config::byKey('sendToApp', $plugin->getId(), 1) == 1 && !in_array($plugin->getId(), $plugin_widget)) {
					$_echo .= '<div class="cursor pluginDisplayCard" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="' . $opacity . '">';
					$_echo .= '<center>';
					$_echo .= '<img class="img-responsive" src="' . $plugin->getPathImgIcon() . '" />';
					$_echo .= '</center>';
					$_echo .= '<span class="name">' . $plugin->getName() . '</span>';
					$_echo .= '</div>';
					$num++;
				}
			}
		}
	}
	echo $_echo;
	if ($num == 0) {
		echo '<style>.pluvaltg { Display:None; }</style>';
	}
	?>
</div>

<legend class="plucomnontran">{{Plugins compatibles non transmis}}
	<sup><i class="fas fa-question-circle" title="{{Ne sont pas transmis à l’App Mobile.}}"></i></sup>
</legend>
<div class="pluginListContainer">
	<?php
	$num = 0;
	$_echo = '';
	foreach ($plugins as $plugin) {
		$opacity = '';
		if ($plugin->getId() != 'mobile' && $plugin->getId() != 'homebridge') {
			if (in_array($plugin->getId(), $plugin_compatible)) {
				if (config::byKey('sendToApp', $plugin->getId(), 1) != 1 && !in_array($plugin->getId(), $plugin_widget)) {
					$opacity = jeedom::getConfiguration('eqLogic:style:noactive');
					$_echo .= '<div class="cursor pluginDisplayCard" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="' . $opacity . '">';
					$_echo .= '<center>';
					$_echo .= '<img class="img-responsive" src="' . $plugin->getPathImgIcon() . '" />';
					$_echo .= '</center>';
					$_echo .= '<span class="name">' . $plugin->getName() . '</span>';
					$_echo .= '</div>';
					$num++;
				}
			}
		}
	}
	echo $_echo;
	if ($num == 0) {
		echo '<style>.plucomnontran { Display:None; }</style>';
	}
	?>
</div>

<legend class="plunontestran">{{Plugins non testés transmis à l'application}}
	<sup><i class="fas fa-question-circle" title="{{Sont transmis à l’App Mobile en se basant sur les types génériques.}}"></i></sup>
</legend>
<div class="pluginListContainer">
	<?php
	$num = 0;
	$_echo = '';
	foreach ($plugins as $plugin) {
		$opacity = '';
		if ($plugin->getId() != 'mobile' && $plugin->getId() != 'homebridge') {
			if (!in_array($plugin->getId(), $plugin_compatible)) {
				if (config::byKey('sendToApp', $plugin->getId(), 0) == 1) {
					$_echo .= '<div class="cursor pluginDisplayCard" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="' . $opacity . '">';
					$_echo .= '<center>';
					$_echo .= '<img class="img-responsive" src="' . $plugin->getPathImgIcon() . '" />';
					$_echo .= '</center>';
					$_echo .= '<span class="name">' . $plugin->getName() . '</span>';
					$_echo .= '</div>';
					$num++;
				}
			}
		}
	}
	echo $_echo;
	if ($num == 0) {
		echo '<style>.plunontestran { Display:None; }</style>';
	}
	?>
</div>

<legend class="plugnontestetnontrans">{{Plugins non testés et non transmis}}
	<sup><i class="fas fa-question-circle" title="{{Ne sont pas transmis à l’App Mobile}}"></i></sup>
</legend>
<div class="pluginListContainer">
	<?php
	$num = 0;
	$_echo = '';
	foreach ($plugins as $plugin) {
		$opacity = '';
		if ($plugin->getId() != 'mobile' && $plugin->getId() != 'homebridge') {
			if (!in_array($plugin->getId(), $plugin_compatible)) {
				if (config::byKey('sendToApp', $plugin->getId(), 0) != 1) {
					$opacity = jeedom::getConfiguration('eqLogic:style:noactive');
					$_echo .= '<div class="cursor pluginDisplayCard" onclick="clickplugin(\'' . $plugin->getId() . '\',\'' . $plugin->getName() . '\')" style="' . $opacity . '">';
					$_echo .= '<center>';
					$_echo .= '<img class="img-responsive" src="' . $plugin->getPathImgIcon() . '" />';
					$_echo .= '</center>';
					$_echo .= '<span class="name">' . $plugin->getName() . '</span>';
					$_echo .= '</div>';
					$num++;
				}
			}
		}
	}
	echo $_echo;
	if ($num == 0) {
		echo '<style>.plugnontestetnontrans { Display:None; }</style>';
	}
	?>
</div>

<?php
include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');
?>

<script>
	setTimeout(function() {
		$('.pluginListContainer').packery()
	}, 100)

	$(window).resize(function() {
		$('.pluginListContainer').packery()
	})
</script>