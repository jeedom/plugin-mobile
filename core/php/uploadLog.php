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

// Reçoit le journal natif de la lib de géolocalisation de l'app (logger.uploadLog) :
// multipart avec le fichier gzippé et les champs model, manufacturer, platform, version.
// Le journal est gardé dans data/ (non servi par Apache) puis envoyé par la commande
// mail choisie dans la configuration du plugin. Sans commande configurée, refus.

require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";

const GEOLOC_LOG_MAX_SIZE = 20 * 1024 * 1024;
const GEOLOC_LOG_KEEP = 10;

if (!jeedom::apiAccess(init('apikey'), 'mobile')) {
  http_response_code(401);
  die();
}

$mailCmdString = trim(config::byKey('geolocLogMailCmd', 'mobile', ''));
if ($mailCmdString == '') {
  http_response_code(403);
  die();
}
try {
  $mailCmd = cmd::byString($mailCmdString);
} catch (Exception $e) {
  log::add('mobile', 'error', 'Logs géoloc : commande mail introuvable ' . $mailCmdString);
  http_response_code(403);
  die();
}

$file = reset($_FILES);
if ($file === false || $file['error'] !== UPLOAD_ERR_OK || $file['size'] > GEOLOC_LOG_MAX_SIZE) {
  http_response_code(400);
  die();
}

$dir = dirname(__FILE__) . '/../../data/geolocLogs';
if (!is_dir($dir)) {
  mkdir($dir, 0775, true);
}
$iq = preg_replace('/[^A-Za-z0-9_-]/', '', init('Iq'));
$path = $dir . '/geoloc_' . $iq . '_' . date('Ymd_His') . '.log.gz';
if (!move_uploaded_file($file['tmp_name'], $path)) {
  http_response_code(500);
  die();
}

$stored = glob($dir . '/geoloc_*.log.gz');
usort($stored, function ($a, $b) {
  return filemtime($b) - filemtime($a);
});
foreach (array_slice($stored, GEOLOC_LOG_KEEP) as $old) {
  unlink($old);
}

$mobileName = mobile::whoIsIq(init('Iq'));
$device = trim(init('manufacturer') . ' ' . init('model'));
$os = trim(init('platform') . ' ' . init('version'));
log::add('mobile', 'info', 'Logs géoloc reçus de ' . $mobileName . ' (' . $device . ', ' . $os . ')');
$mailCmd->execCmd(array(
  'title' => 'Logs géoloc - ' . $mobileName,
  'message' => 'Mobile : ' . $mobileName . "\nAppareil : " . $device . "\nSystème : " . $os . "\nReçu le : " . date('Y-m-d H:i:s'),
  'files' => array($path),
));
echo 'ok';
