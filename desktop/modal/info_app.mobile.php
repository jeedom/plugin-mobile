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
mobile::makeTemplateJson();
$data = mobile::getTemplateJson();
$data['messages'] = mobile::discovery_message();
$data['config'] = array('datetime' => getmicrotime());
?>
<legend>{{JSON valide}} (<?php echo sizeFormat(strlen(json_encode($data))) ?>)</legend>
<legend>{{Objets / Pièces}}</legend>
<pre style='overflow: auto; with:90%;'><?php echo json_encode($data['objects'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
<legend>{{Modules}}</legend>
<pre style='overflow: auto; with:90%;'><?php echo json_encode($data['eqLogics'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
<legend>{{Commandes}}</legend>
<pre style='overflow: auto; with:90%;'><?php echo json_encode($data['cmds'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
<legend>{{Scénarios :}}</legend>
<pre style='overflow: auto; with:90%;'><?php echo json_encode($data['scenarios'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
<legend>{{Messages}}</legend>
<pre style='overflow: auto; with:90%;'><?php echo json_encode($data['messages'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
<legend>{{Configurations :}}</legend>
<pre style='overflow: auto; with:90%;'><?php echo json_encode($data['config'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
