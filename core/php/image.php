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

require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";

if(init('name') == null || init('key') == null){
	die();
}

/* Variables */
$keyFile = init('key');
$nameFile = init('name');
$file = dirname(__FILE__) .'/../../data/images/'.$nameFile;


if(!file_exists($file)){
  echo "FILE NON EXISTE !";
  	die(); 
}

$nameMD5 = md5_file($file);

if($nameMD5 != $keyFile){
  echo "KEY NOK !";
  die();
}
//ob_clean();
header('Content-Type: image/'.pathinfo($file, PATHINFO_EXTENSION));
  echo file_get_contents($file);

unlink($file);
