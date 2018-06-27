
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
 $('#info_app').on('click', function(){
     $('#md_modal').dialog({title: "{{Informations envoyées à l'app}}"});
     $('#md_modal').load('index.php?v=d&plugin=mobile&modal=info_app.mobile').dialog('open');
 })
 function clickplugin(id_plugin,name_plugin){
     $('#md_modal').dialog({title: "{{Configuration Mobile du Plugin "+name_plugin+"}}"});
     $('#md_modal').load('index.php?v=d&plugin=mobile&modal=plugin.mobile&plugin_id=' +id_plugin).dialog('open');
 }

 function clickobject(id_object){
   $('#md_modal').dialog({title: "{{Configuration Mobile de la Pièce}}"});
   $('#md_modal').load('index.php?v=d&plugin=mobile&modal=object.mobile&object_id=' +id_object).dialog('open');
}

function clickscenario(id_scenario,name_scenario){
	$('#md_modal').dialog({title: "{{Configuration Mobile du Scnéario "+name_scenario+"}}"});
    $('#md_modal').load('index.php?v=d&plugin=mobile&modal=scenario.mobile&scenario_id=' +id_scenario).dialog('open');
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
    $.ajax({
        type: "POST", 
        url: "plugins/mobile/core/ajax/mobile.ajax.php", 
        data: {
            action: "getQrCode",
            id: _eqLogic.id,
        },
        dataType: 'json',
        global: false,
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            if (data.result == 'internalError') {
              $('.qrCodeImg').empty().append('{{Erreur Pas d\'adresse interne (voir configuration de votre Jeedom !)}}');
          }else if(data.result == 'externalError'){
              $('.qrCodeImg').empty().append('{{Erreur Pas d\'adresse externe (voir configuration de votre Jeedom !)}}');
          }else if(data.result == 'UserError'){
              $('.qrCodeImg').empty().append('{{Erreur Pas d\'utilisateur selectionné}}');
          }else{
              $('.qrCodeImg').empty().append('<img src='+data.result+' />');
          }
      }
  });
}


$('#bt_regenConfig').on('click',function(){
    $.ajax({
        type: "POST", 
        url: "plugins/mobile/core/ajax/mobile.ajax.php", 
        data: {
            action: "regenConfig"
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_alert').showAlert({message: '{{Configuration mise à jour}}', level: 'success'});
        }
    });
});

// Copie pour monitoring
var toCopy  = document.getElementById( 'to-copy-monitoring' ),
	arnComplet = document.getElementById( 'arnComplet' ),
    btnCopy = document.getElementById( 'copy-monitoring' );

btnCopy.addEventListener( 'click', function(){
	var fichier = arnComplet.value;
	var fichierCouper = fichier.substr(44);
    toCopy.value = fichierCouper;
	toCopy.select();
	document.execCommand( 'copy' );
	return false;
} );
