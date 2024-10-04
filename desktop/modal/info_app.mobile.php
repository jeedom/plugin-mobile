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
	throw new Exception('{{401 - Accès non autorisé}}');
}
mobile::makeTemplateJson();
$data = mobile::getTemplateJson();
$data['messages'] = mobile::discovery_message();
$data['config'] = array('datetime' => getmicrotime());
$replace = array('<i' => '\<\i');
?>
<legend>{{JSON valide}} (<?php echo sizeFormat(strlen(json_encode($data))) ?>)</legend>



<ul class="nav nav-tabs" role="tablist" id="ul_tabMobileConfig">
	<li role="presentation" class="active"><a href="#object" aria-controls="object" role="tab" data-toggle="tab">{{Objets / Pièces}}</a></li>
	<li role="presentation"><a href="#device" aria-controls="device" role="tab" data-toggle="tab">{{Modules}}</a></li>
	<li role="presentation"><a href="#cmd" aria-controls="cmd" role="tab" data-toggle="tab">{{Commandes}}</a></li>
	<li role="presentation"><a href="#scenario" aria-controls="scenario" role="tab" data-toggle="tab">{{Scénarios}}</a></li>
	<li role="presentation"><a href="#message" aria-controls="message" role="tab" data-toggle="tab">{{Messages}}</a></li>
	<li role="presentation"><a href="#plan" aria-controls="plan" role="tab" data-toggle="tab">{{Designs}}</a></li>
	<li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">{{Configurations}}</a></li>
</ul>
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="object">
		<pre style='overflow: auto; with:90%;'><?php echo str_replace(array_keys($replace), $replace, json_encode($data['objects'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
		</pre>
	</div>
	<div role="tabpanel" class="tab-pane" id="device">
		<pre style='overflow: auto; with:90%;'><?php echo str_replace(array_keys($replace), $replace, json_encode($data['eqLogics'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
		</pre>
	</div>
	<div role="tabpanel" class="tab-pane" id="cmd">
		<pre style='overflow: auto; with:90%;'><?php echo str_replace(array_keys($replace), $replace, json_encode($data['cmds'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
		</pre>
	</div>
	<div role="tabpanel" class="tab-pane" id="scenario">
		<pre style='overflow: auto; with:90%;'><?php echo str_replace(array_keys($replace), $replace, json_encode($data['scenarios'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
		</pre>
	</div>
	<div role="tabpanel" class="tab-pane" id="message">
		<pre style='overflow: auto; with:90%;'><?php echo str_replace(array_keys($replace), $replace, json_encode($data['messages'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
		</pre>
	</div>
	<div role="tabpanel" class="tab-pane" id="plan">
		<pre style='overflow: auto; with:90%;'><?php echo str_replace(array_keys($replace), $replace, json_encode($data['plans'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
		</pre>
	</div>
	<div role="tabpanel" class="tab-pane" id="settings">
		<pre style='overflow: auto; with:90%;'><?php echo str_replace(array_keys($replace), $replace, json_encode($data['config'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?>
	</pre>
	</div>
</div>