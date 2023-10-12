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

<legend><i class="icon maison-modern13"></i> {{Les Pièces}}</legend>
<div class="eqLogicThumbnailContainer">
	<?php
	$allObject = jeeObject::buildTree(null, false);
	$_echo = '';
	foreach ($allObject as $object) {
		$opacity = '';
		if ($object->getDisplay('sendToApp', 1) == 0) {
			$opacity = 'opacity:0.3;';
		}
		$_echo .= '<div class="objectDisplayCard cursor dClrIcon" data-object_id="' . $object->getId() . '" onclick="clickobject(\'' . $object->getId() . '\')">';
		$_echo .= $object->getDisplay('icon', '<i class="fas fa-lemon-o"></i>');
		$_echo .= '<span><center>' . $object->getName() . '</center></span>';
		$_echo .= '</div>';
	}
	echo $_echo;
	?>
</div>
<?php
include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');
?>