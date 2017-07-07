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
?>
<style media="screen" type="text/css">
    .noscrolling {
        width: 99%;
        overflow: hidden;
    }
    .table-striped {
        width: 90%;
    }
    .node-item {
        border: 1px solid;
    }
    .modal-dialog-center {
        margin: 0;
        position: absolute;
        top: 0%;
        left: 0%;
    }
    .greeniconcolor {
        color: green;
    }
    .yellowiconcolor {
        color: #FFD700;
    }
    .rediconcolor {
        color: red;
    }
</style>

<style>
    .bound-config {
        width: 100%;
        margin: 0px;
        padding: 0px;
    }
    textarea {
        width: 100%;
        margin: 0px;
        padding: 10px;
        height: 380px;
        font-size: 14px;
    }
</style>
<div id='div_configMobileAlert' style="display: none;"></div>
<div class="alert alert-danger">{{!!! Cette configuration est en mode avancé !!! à vos risques et périls !!! aucun support ne sera donné !!!}}</div>
<div class="alert alert-info">
1. {{Le contenu doit être un objet JSON de type platform}}<br />
2. {{Si de multiples plateformes, les séparer par |}}<br />
3. {{le plugin homebridge correspondant doit être installé avec <i>npm</i> (homebridge-camera-ffmpeg est déjà installé et utilise <i>avconv</i> ou <i>ffmpeg</i> s'il est présent)}}<br />
4. Nécessite plugin Camera pour avoir <i>avconv</i> (ou installe manuelle)<br />
5. <i>avconv</i> des sources officielles de raspbian Jessie n'est malheureusement pas compilé avec support omx (mais ffmpeg de Debian Stretch bien :))<br />
<br />
<a href="https://github.com/KhaosT/homebridge-camera-ffmpeg/wiki/Tested-Configuration" target="_blank">{{Exemples camera}}</a> - <a href="https://www.ispyconnect.com/sources.aspx" target="_blank">{{Trouvez votre camera}}</a><br />
<br />
NB : Une nouvelle Plateforme doit être ajoutée à nouveau dans l'application "Maison" avec le même code PIN</label>
</div>
<button id="saveconf" class="btn btn-success pull-left"><i class="fa fa-floppy-o"></i> {{Sauvegarder les changements}}
</button><br/>
<br/>
<div class="bound-config">
    <textarea id="platformFile" class="boxsizingborder"></textarea>
</div>

<?php include_file('desktop', 'platformFile', 'js', 'mobile');?>
<script>
    var nodes = {};
    if (window["app_config"] != undefined) {
        window["app_config"].init();
        window["app_config"].show();
    }
</script>
