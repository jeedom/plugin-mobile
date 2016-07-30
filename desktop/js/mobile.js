
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
 $('#bt_healthmobile').on('click', function () {
    $('#md_modal').dialog({title: "{{Santé Mobile}}"});
    $('#md_modal').load('index.php?v=d&plugin=mobile&modal=health').dialog('open');
})
function clickplugin(id_plugin,name_plugin){
	$('#md_modal').dialog({title: "{{Configuration Mobile du Plugin "+name_plugin+"}}"});
    $('#md_modal').load('index.php?v=d&plugin=mobile&modal=plugin.mobile&plugin_id=' +id_plugin).dialog('open');
}

$('li').click(function(){
	 setTimeout(function(){
		$('.eqLogicThumbnailContainer').packery();
		},50);
 });
var hash = document.location.hash;
if (hash) {
    $('.nav-tabs a[href="'+hash+'"]').tab('show');
} 
$('.nav-tabs a').on('shown.bs.tab', function (e) {
    window.location.hash = e.target.hash;
});
/*
 * Fonction pour l'ajout de commande, appellé automatiquement par plugin.template
 */

 function printEqLogic(_eqLogic){
    $.ajax({// fonction permettant de faire de l'ajax
        type: "POST", // méthode de transmission des données au fichier php
        url: "plugins/mobile/core/ajax/mobile.ajax.php", // url du fichier php
        data: {
            action: "getQrCode",
            id: _eqLogic.id,
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) { // si l'appel a bien fonctionné
        if (data.state != 'ok') {
            $('#div_alert').showAlert({message: data.result, level: 'danger'});
            return;
        }
        $('.qrCodeImg').empty().append('<img src='+data.result+' />');
    }
});
}