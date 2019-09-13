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
  <legend><i class="icon jeedom-clap_cinema"></i>  {{Les Scénarios}}
  </legend>
  <div class="eqLogicThumbnailContainer">
    <?php
$allScenario = scenario::all();
foreach ($allScenario as $scenario) {
	$opacity = '';
	if ($scenario->getDisplay('sendToApp', 1) == 0) {
		$opacity = 'opacity:0.3;';
	}
	echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" onclick="clickscenario(\'' . $scenario->getId() . '\',\'' . $scenario->getName() . '\')" style="background-color : #ffffff; height : 140px;margin-bottom : 35px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
	echo "<center>";
	echo '<img src="core/img/scenario.png" height="90" width="85" />';
	echo "</center>";
	echo '<span><center>' . $scenario->getHumanName(true, true, true, true) . '</center></span>';
	echo '</div>';
}
?>
</div>
   <?php include_file('desktop', 'mobile', 'js', 'mobile');?>
  <?php include_file('core', 'plugin.template', 'js');?>