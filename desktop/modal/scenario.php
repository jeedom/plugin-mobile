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

$scenarios = array();
$totalScenario = scenario::all();
$scenarios[-1] = scenario::all(null);
$scenarioListGroup = scenario::listGroup();
if (is_array($scenarioListGroup)) {
	foreach ($scenarioListGroup as $group) {
		$scenarios[$group['group']] = scenario::all($group['group']);
	}
}

?>

  <legend><i class="icon jeedom-clap_cinema"></i>  {{Mes scénarios}}</legend>
  <span id="span_ongoing" class="label label-warning">{{Attention, seul les scénarios visibles dans le dashboard, sont visible sur l'application mobile}}</span>
  <br />
  <br />
		<?php
		if (count($totalScenario) == 0) {
			echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez encore aucun scénario. Cliquez sur ajouter pour commencer</span></center>";
		} else {
			$div = '<div class="input-group" style="margin-bottom:5px;">';
			$div .= '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchScenario"/>';
			$div .= '<div class="input-group-btn">';
			$div .= '<a id="bt_resetScenarioSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
			$div .= '<a class="btn" id="bt_openAll"><i class="fas fa-folder-open"></i></a>';
			$div .= '<a class="btn roundedRight" id="bt_closeAll"><i class="fas fa-folder"></i></a>';
			$div .= '</div>';
			$div .= '</div>';
			$div .= '<div class="panel-group" id="accordionScenario">';
			if (count($scenarios[-1]) > 0) {
				$div .= '<div class="panel panel-default">';
				$div .= '<div class="panel-heading">';
				$div .= '<h3 class="panel-title">';
				$div .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#config_none">Aucun - ';
				$c = count($scenarios[-1]);
				$div .= $c. ($c > 1 ? ' scénarios' : ' scénario').'</a>';
				$div .= '</h3>';
				$div .= '</div>';
				$div .= '<div id="config_none" class="panel-collapse collapse">';
				$div .= '<div class="panel-body">';
				$div .= '<div class="scenarioListContainer">';
				foreach ($scenarios[-1] as $scenario) {
					$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                  	if($opacity !== jeedom::getConfiguration('eqLogic:style:noactive')){
                  		$opacity = ($scenario->getIsVisible()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                    }
					$div .= '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" style="' . $opacity . '" >';
					if($scenario->getDisplay('icon') != ''){
						$div .= '<span>'.$scenario->getDisplay('icon').'</span>';
					}else{
						$div .= '<span><i class="icon noicon jeedom-clap_cinema"></i></span>';
					}
					$div .= "<br>";
					$div .= '<span class="name">' . $scenario->getHumanName(true, true, true, true) . '</span>';
					$div .= '</div>';
				}
				$div .= '</div>';
				$div .= '</div>';
				$div .= '</div>';
				$div .= '</div>';
			}
			echo $div;
			$i = 0;
			$div = '';
			foreach ($scenarioListGroup as $group) {
				if ($group['group'] == '') {
					continue;
				}
				$div .= '<div class="panel panel-default">';
				$div .= '<div class="panel-heading">';
				$div .= '<h3 class="panel-title">';
				$div .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#config_' . $i . '">' . $group['group'] . ' - ';
				$c = count($scenarios[$group['group']]);
				$div .= $c. ($c > 1 ? ' scénarios' : ' scénario').'</a>';
				$div .= '</h3>';
				$div .= '</div>';
				$div .= '<div id="config_' . $i . '" class="panel-collapse collapse">';
				$div .= '<div class="panel-body">';
				$div .= '<div class="scenarioListContainer">';
				foreach ($scenarios[$group['group']] as $scenario) {
					$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                  	if($opacity !== jeedom::getConfiguration('eqLogic:style:noactive')){
                  		$opacity = ($scenario->getIsVisible()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                    }
					$div .= '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" style="' . $opacity . '" >';
					if($scenario->getDisplay('icon') != ''){
						$div .= '<span>'.$scenario->getDisplay('icon').'</span>';
					}else{
						$div .= '<span><i class="icon noicon jeedom-clap_cinema"></i></span>';
					}
					$div .= '<br/>';
					$div .= '<span class="name">' . $scenario->getHumanName(true, true, true, true) . '</span>';
					$div .= '</div>';
				}
				$div .= '</div>';
				$div .= '</div>';
				$div .= '</div>';
				$div .= '</div>';
				$i += 1;
			}
			$div .= '</div>';
			echo $div;
		}
		?>
	</div>

<?php
include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');
include_file('3rdparty', 'jquery.sew/jquery.caretposition', 'js');
include_file('3rdparty', 'jquery.sew/jquery.sew.min', 'js');
include_file('desktop', 'scenario', 'js');
?>
