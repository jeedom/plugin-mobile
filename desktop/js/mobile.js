
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
 $('#bt_pluguinmobile').on('click', function () {
    $('#md_modal').dialog({title: "{{Plugins compatibles}}"});
    $('#md_modal').load('index.php?v=d&plugin=mobile&modal=plugin').dialog('open');
})
 $('#bt_piecemobile').on('click', function () {
    $('#md_modal').dialog({title: "{{Objets / Pièces}}"});
    $('#md_modal').load('index.php?v=d&plugin=mobile&modal=piece').dialog('open');
})
 $('#bt_scenariomobile').on('click', function () {
    $('#md_modal').dialog({title: "{{Scénarios}}"});
    $('#md_modal').load('index.php?v=d&plugin=mobile&modal=scenario').dialog('open');
})
 $('#info_app').on('click', function(){
     $('#md_modal').dialog({title: "{{Informations envoyées à l'app}}"});
     $('#md_modal').load('index.php?v=d&plugin=mobile&modal=info_app.mobile').dialog('open');
 })
 $('#bt_customMenu').on('click', function(){
    $('#md_modal').dialog({title: "{{Menu Custom}}"});
    $('#md_modal').load('index.php?v=d&plugin=mobile&modal=menuCustom').dialog('open');
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


$('.renameDivClass').on('input',function(e){
    let idElement = $(this).attr('id');
    let eqLogicId =  $(this).attr('eqId');
    let numElement = idElement.substr(-1, 1);
    $('#titleArea'+numElement).empty().text($(this).children('input:nth-child(2)').val());
});


$('.btIconClass').on('click', function () {
  let idSelect = $(this).attr('id');
  let eqLogicId = $(this).attr('eqid');
  console.log(eqLogicId);
  let numElement = idSelect.substr(-1, 1);
  console.log( $('#spanIconTest'+numElement+'[eqid="'+eqLogicId+'"]'))
  var _icon = false;
  jeedomUtils.chooseIcon(function(_icon) {
    $('#spanIconTest'+numElement+'[eqid="'+eqLogicId+'"]').empty().append(_icon);
    $('#spanIconTest'+numElement+'[eqid="'+eqLogicId+'"]').children('i:nth-child(1)').css('font-size', '60px');
    $('#spanIconTest'+numElement+'[eqid="'+eqLogicId+'"]').children('i:nth-child(1)').attr('eqId', eqLogicId);
    $('#spanIconTest'+numElement+'[eqid="'+eqLogicId+'"]').children('i:nth-child(1)').attr('id', 'area'+numElement);
  }, {
    icon: _icon
  })
});



function userSelect(idSelect){
  let eqLogicId = document.getElementById(idSelect).getAttribute('eqid');
  let numElement = idSelect.substr(-1, 1);
  let typeObject = document.getElementById(idSelect).value;
  let selectorsObject = document.querySelectorAll('.item_dash');
  selectorsObject.forEach(selector => {
    let idSelector = selector.id;
    let eqId = selector.getAttribute('eqId');
    if(idSelector.includes(typeObject) && idSelector.includes(numElement) && eqId == eqLogicId){
       selector.style.display = 'block';
       selector.value = 'none';
       selector.select = 'selected';
    }
    if(!idSelector.includes(typeObject) && idSelector.includes(numElement) && eqId == eqLogicId){
       selector.style.display = 'none';
    }
  })
  if(typeObject == 'url'){
    $('#urlUser'+numElement+'[eqId="'+eqLogicId+'"]').css('display','block');

  }
}



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
              $('.qrCodeImg').empty().append('<img src="data:image/png;base64, '+data.result+'" />');
          }
      }
  });
  $.ajax({
        type: "POST",
        url: "plugins/mobile/core/ajax/mobile.ajax.php",
        data: {
            action: "getSaveDashboard",
            iq: _eqLogic.logicalId,
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
          	if (data.result == true) {
              $('#SaveDash').addClass('badge-success');
			  $('#SaveDash').text('OK');
          	}else if(data.result == false){
              $('#SaveDash').addClass('badge-danger');
			  $('#SaveDash').text('NOK');
          	}
      }
  });
  $.ajax({
        type: "POST",
        url: "plugins/mobile/core/ajax/mobile.ajax.php",
        data: {
            action: "getSaveFavDash",
            iq: _eqLogic.logicalId,
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
          	if (data.result == true) {
              $('#SaveFav').addClass('badge-success');
			  $('#SaveFav').text('OK');
          	}else if(data.result == false){
              $('#SaveFav').addClass('badge-danger');
			  $('#SaveFav').text('NOK');
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

$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
/*
 * Fonction pour l'ajout de commande, appellé automatiquement par plugin.template
 */
function addCmdToTable(_cmd) {
    if (!isset(_cmd)) {
        var _cmd = {configuration: {}};
    }
    if (!isset(_cmd.configuration)) {
        _cmd.configuration = {};
    }

    var tr = '<tr class="cmd" data-cmd_id="' + init(_cmd.id) + '">';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="id" style="display:none;"></span>';
    tr += '<input class="cmdAttr form-control input-sm" data-l1key="name" style="width : 140px;" placeholder="{{Nom}}">';
    tr += '</td>';
    tr += '<td>';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '</td>';
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure"><i class="fa fa-cogs"></i></a> ';
        tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>';
    }
  	if (init(_cmd.logicalId) !== 'notif'){
    	tr += '<i class="fa fa-minus-circle pull-right cmdAction cursor" data-action="remove"></i>';
    }
    tr += '</td>';
    tr += '</tr>';
    $('#table_cmd tbody').append(tr);
    $('#table_cmd tbody tr:last').setValues(_cmd, '.cmdAttr');
    if (isset(_cmd.type)) {
        $('#table_cmd tbody tr:last .cmdAttr[data-l1key=type]').value(init(_cmd.type));
    }
    jeedom.cmd.changeType($('#table_cmd tbody tr:last'), init(_cmd.subType));
}
