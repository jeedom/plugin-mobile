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
$data = mobile::getTemplateJson();
$data['messages'] = mobile::discovery_message();
$data['config'] = array('datetime' => getmicrotime());
?>
<h3>JSON valide :</h3>
<pre id='pre_eventlog' style='overflow: auto; with:90%;'></pre>
<h3>{{Objets / Pièces :}}</h3>
<pre id='pre_eventlog' style='overflow: auto; with:90%;'><?php echo json_encode($data['objects']); ?></pre>
<h3>{{Modules :}}</h3>
<pre id='pre_eventlog' style='overflow: auto; with:90%;'><?php echo json_encode($data['eqLogics']); ?></pre>
<h3>{{Commandes :}}</h3>
<pre id='pre_eventlog' style='overflow: auto; with:90%;'><?php echo json_encode($data['cmds']); ?></pre>
<h3>{{Scénarios :}}</h3>
<pre id='pre_eventlog' style='overflow: auto; with:90%;'><?php echo json_encode($data['scenarios']); ?></pre>
<h3>{{Messages :}}</h3>
<pre id='pre_eventlog' style='overflow: auto; with:90%;'><?php echo json_encode($data['messages']); ?></pre>
<h3>{{Configurations :}}</h3>
<pre id='pre_eventlog' style='overflow: auto; with:90%;'><?php echo json_encode($data['config']); ?></pre>
