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
<legend><i class="icon maison-modern13"></i>  {{Les Pièces}}
  </legend>
  <div class="eqLogicThumbnailContainer">
    <?php
$allObject = jeeObject::buildTree(null, false);
foreach ($allObject as $object) {
	$opacity = '';
	if ($object->getDisplay('sendToApp', 1) == 0) {
		$opacity = 'opacity:0.3;';
	}
	echo '<div class="objectDisplayCard cursor" data-object_id="' . $object->getId() . '" onclick="clickobject(\'' . $object->getId() . '\')" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '">';
	echo "<center>";
	echo str_replace('></i>', ' style="font-size : 6em;color:#767676;"></i>', $object->getDisplay('icon', '<i class="fa fa-lemon-o"></i>'));
	echo "</center>";
	echo '<span><center>' . $object->getName() . '</center></span>';
	echo '</div>';
}
?>
</div>
  <?php include_file('desktop', 'mobile', 'js', 'mobile');?>
  <?php include_file('core', 'plugin.template', 'js');?>