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
 * along with Jeedom. If not, see <http://www..gnu.org/licenses/>.
*/

if (!isConnect('admin'))
{
    throw new Exception('401 Unauthorized');
}


  $logicalPlugin = secureXSS(init('logicalPlugin'));
  echo '<div class="container">';
  if(!@include('plugins/'.$logicalPlugin.'/plugin_info/configuration.php')) throw new Exception("Pas de configuration sur ce plugin");
  //if(!@include('plugins/zigbee/plugin_info/configuration.php')) throw new Exception("Pas de configuration sur ce plugin");
  echo '</div>';





 ?>

<script src="core/php/getJS.php?file=core/js/appMobile.class.js"></script>

<style>

.container{
  width:100%;
  height:100%;
}



</style>
