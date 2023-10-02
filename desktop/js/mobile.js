
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

if(typeof jeeDialog !== 'undefined'){
  document.querySelector('#bt_healthmobile').addEventListener('click', function(event) {
    jeeDialog.dialog({
      id: 'santémobile',
      title: "{{Santé Mobile}}",
      contentUrl: 'index.php?v=d&plugin=mobile&modal=health'
    })
  })

  document.querySelector('#bt_pluguinmobile').addEventListener('click', function(event) {
    jeeDialog.dialog({
      id: 'pluginsCompatibles',
      title: "{{Plugins compatibles}}",
      contentUrl: 'index.php?v=d&plugin=mobile&modal=plugin'
    })
  })

  document.querySelector('#bt_piecemobile').addEventListener('click', function(event) {
    jeeDialog.dialog({
      id: 'objectsModal',
      title: "{{Objets / Pièces}}",
      contentUrl: 'index.php?v=d&plugin=mobile&modal=piece'
    })
  })

  document.querySelector('#bt_scenariomobile').addEventListener('click', function(event) {
    jeeDialog.dialog({
      id: 'scenariosModal',
      title: "{{Scénarios}}",
      contentUrl: 'index.php?v=d&plugin=mobile&modal=scenario'
    })
  })

  document.querySelector('#info_app').addEventListener('click', function(event) {
    jeeDialog.dialog({
      id: 'infosApp',
      title: "{{Informations envoyées à l'app}}",
      contentUrl: 'index.php?v=d&plugin=mobile&modal=info_app.mobile'
    })
  })

  document.querySelector('#bt_handlePhones')?.addEventListener('click', function(event) {
    jeedom.version({
    success: function(version) {
      if(version >= '4.4.0'){
        jeeDialog.dialog({
          id: 'menuCustom',
          title: "{{Gestion des Mobiles}}",
          fullScreen:1,
          contentUrl: 'index.php?v=d&plugin=mobile&modal=menuCustom'
        })
      }else{
        $('#div_alert').showAlert({message: 'Module compatible uniquement avec la version core 4.4.0 et supérieure', level: 'warning'});
        return;
      }
    }
  })

  })

  document.querySelector('#bt_qrCodev2').addEventListener('click', function(event) {
    jeeDialog.dialog({
      id: 'qrcodev2',
      title: "{{QrCode}}",
      contentUrl: 'index.php?v=d&plugin=mobile&modal=qrcodev2'
    })
  })

  document.querySelector('#bt_startTuto')?.addEventListener('click', function(event) {
    jeeDialog.dialog({
      id: 'startTuto',
      title: "{{Bien demarrer}}",

        fullScreen:1,
      contentUrl: 'index.php?v=d&plugin=mobile&modal=wizard'
    })
  })



}else{
  $('#bt_healthmobile').on('click', function () {
     $('#md_modal').dialog({title: "{{Santé Mobile}}"});
     $('#md_modal').load('index.php?v=d&plugin=mobile&modal=health').dialog('open');
 })
 $('#bt_startTuto').on('click', function () {
    $('#md_modal').dialog({title: "{{Bien demarrer}}"});
    $('#md_modal').load('index.php?v=d&plugin=mobile&modal=wizard').dialog('open');
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
        jeedom.version({
        success: function(version) {
          if(version >= '4.4.0'){
            $('#md_modal').dialog({title: "{{Menu Custom}}"});
            $('#md_modal').load('index.php?v=d&plugin=mobile&modal=menuCustom').dialog('open');
          }else{
            $('#div_alert').showAlert({message: 'Module compatible uniquement avec la version core 4.4.0 et supérieure', level: 'warning'});
            return;
          }
        }
      })

   })

   $('#bt_qrCodev2').on('click', function(){
      $('#md_modal').load('index.php?v=d&plugin=mobile&modal=qrcodev2').dialog('open');
   })


}


 function clickplugin(id_plugin,name_plugin){
   if(typeof jeeDialog !== 'undefined'){
       jeeDialog.dialog({
         id: 'configMobile',
         title: "{{Configuration Mobile du Plugin "+name_plugin+"}}",
         contentUrl: 'index.php?v=d&plugin=mobile&modal=plugin.mobile&plugin_id=' +id_plugin
       })
   }else{
     $('#md_modal').dialog({title: "{{Configuration Mobile du Plugin "+name_plugin+"}}"});
     $('#md_modal').load('index.php?v=d&plugin=mobile&modal=plugin.mobile&plugin_id=' +id_plugin).dialog('open');
   }
 }

 function clickobject(id_object){
   if(typeof jeeDialog !== 'undefined'){
       jeeDialog.dialog({
         id: 'configMobilePiece',
         title: "{{Configuration Mobile de la Pièce}}",
         contentUrl: 'index.php?v=d&plugin=mobile&modal=object.mobile&object_id=' +id_object
       })
   }else{
     $('#md_modal').dialog({title: "{{Configuration Mobile de la Pièce}}"});
     $('#md_modal').load('index.php?v=d&plugin=mobile&modal=object.mobile&object_id=' +id_object).dialog('open');
   }


}

function clickscenario(id_scenario,name_scenario){
  if(typeof jeeDialog !== 'undefined'){
      jeeDialog.dialog({
        id: 'configMobileScenario',
        title: "{{Configuration Mobile du Scénario "+name_scenario+"}}",
        contentUrl: 'index.php?v=d&plugin=mobile&modal=scenario.mobile&scenario_id=' +id_scenario
      })
  }else{
    $('#md_modal').dialog({title: "{{Configuration Mobile du Scénario "+name_scenario+"}}"});
    $('#md_modal').load('index.php?v=d&plugin=mobile&modal=scenario.mobile&scenario_id=' +id_scenario).dialog('open');
  }

}

 document.querySelector('li').click(function(){
  setTimeout(function(){
      $('.eqLogicThumbnailContainer').packery();
  },50);
});

var hash = document.location.hash;
if (hash) {
  $('.nav-tabs a[href="'+hash+'"]').tab('show');
  /*document.querySelector('.nav-tabs a[href="'+hash+'"]').tab('show');*/
}

/*
var aTabs = document.querySelectorAll('a[data-toggle="tabPlug"');
for (let i = 0; i < aTabs.length; i++) {
  console.log(aTabs[i].id);
  aTabs[i].addEventListener('click', function(event) {
      window.location.hash = e.target.hash;
  });
}*/



document.querySelector('.nav-tabs a').addEventListener('shown.bs.tab', function (e) {
    window.location.hash = e.target.hash;
});



document.querySelectorAll('.renameDivClass').forEach(el => {
      el.addEventListener('input', function(e){
          let idElement = this.getAttribute('id');
          let eqLogicId = this.getAttribute('eqId');
          let numElement = idElement.substr(-1, 1);
          let areatitleEl = document.querySelector('#titleArea'+numElement)
          areatitleEl.innerHTML = ''
          areatitleEl.innerHTML = this.children[1].value
      });
})




document.querySelectorAll('.btIconClass').forEach(el => {
      el.addEventListener('click', function(e){
      let idElement = this.getAttribute('id');
      let eqLogicId = this.getAttribute('eqId');
      let numElement = idElement.substr(-1, 1);
      var _icon = false;
      jeedomUtils.chooseIcon(function(_icon) {
            let spanIcon = document.querySelector('#spanIconTest'+numElement+'[eqid="'+eqLogicId+'"]')
            spanIcon.innerHTML = ''
            spanIcon.insertAdjacentHTML("beforeend",_icon);
            $('#spanIconTest'+numElement).children('i:nth-child(1)').css('font-size', '60px');
            spanIcon.setAttribute('eqId', eqLogicId)
            $('#spanIconTest'+numElement).children('i:nth-child(1)').attr('id', 'area'+numElement);
          }, {
            icon: _icon
          })
      });
})


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
    document.querySelector('#urlUser'+numElement+'[eqId="'+eqLogicId+'"]').style.display = 'block';
  }else{
    /*let urlsUser = document.querySelectorAll('.urlUser');
    urlsUser.forEach(el => {
        el.style.display = 'none';
      })*/
      document.querySelector('#urlUser'+numElement+'[eqId="'+eqLogicId+'"]').style.display = 'none';
  }
}



 function printEqLogic(_eqLogic){
   let appVersion = _eqLogic.configuration.appVersion;
   var monitoringElements = document.querySelectorAll('.monitoringToDisable');
  var saveTab =  document.querySelector('.saveTab');
   if(appVersion == 2){
     monitoringElements.forEach(el => {
          el.style.display = 'none';

     })
     saveTab.style.display = 'none';
   }else{
     monitoringElements.forEach(el => {
          el.style.display = 'block';
     })
     saveTab.style.display = 'block';
   }

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
            let el = document.querySelector('.qrCodeImg')
            el.innerHTML = '';
            if (data.result == 'internalError') {
              el.innerHTML = '{{Erreur pas d\'adresse interne (voir configuration de votre Jeedom !)}}';
          }else if(data.result == 'externalError'){
            el.innerHTML = '{{Erreur pas d\'adresse externe (voir configuration de votre Jeedom !)}}'
          }else if(data.result == 'UserError'){
            el.innerHTML = '{{Erreur pas d\'utilisateur selectionné}}'
          }else{
            el.innerHTML = '<img src="data:image/png;base64, '+data.result+'" />'
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
            let savedash = document.querySelector('#SaveDash')
          	if (data.result == true) {
               savedash.classList.add('badge-success');
			         savedash.innerHTML = 'OK';
          	}else if(data.result == false){
              savedash.classList.add('badge-danger');
              savedash.innerHTML = 'NOK';
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
            let savefav = document.querySelector('#SaveFav')
          	if (data.result == true) {
              savefav.classList.add('badge-success');
              savefav.innerHTML = 'OK';
          	}else if(data.result == false){
              savefav.classList.add('badge-danger');
              savefav.innerHTML = 'NOK';
          	}
      }
  });

}

document.getElementById('bt_regenConfig').addEventListener('click', function(){
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
var toCopy = document.getElementById('to-copy-monitoring');
var arnComplet = document.getElementById('arnComplet');
var btnCopy = document.getElementById('copy-monitoring');



btnCopy.addEventListener('click', function(){
	var fichier = arnComplet.value;
	var fichierCouper = fichier.substr(44);
  toCopy.value = fichierCouper;
	toCopy.select();
	document.execCommand( 'copy' );
	return false;
  });

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
    tr += '<td class="hidden-xs">'
    tr += '<span class="cmdAttr" data-l1key="id"></span>'
    tr += '</td>'
    tr += '<td>'
  	tr += '<div class="input-group">'
  	tr += '<input class="cmdAttr form-control input-sm roundedLeft" data-l1key="name" placeholder="{{Nom de la commande}}">'
  	tr += '<span class="input-group-btn"><a class="cmdAction btn btn-sm btn-default" data-l1key="chooseIcon" title="{{Choisir une icône}}"><i class="fas fa-icons"></i></a></span>'
  	tr += '<span class="cmdAttr input-group-addon roundedRight" data-l1key="display" data-l2key="icon" style="font-size:19px;padding:0 5px 0 0!important;"></span>'
    tr += '</div>'
    tr += '<td>';
    tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + '</span>';
    tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>';
    tr += '</td>';
    tr += '<td>';
    tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/>{{Afficher}}</label> '
    tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isHistorized" checked/>{{Historiser}}</label> '
    tr += '<div style="margin-top:7px;">'
    tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
    tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
    tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
    tr += '</div>'
    tr += '<td>';
    tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>';
    tr += '</td>';
    tr += '<td>';
    if (is_numeric(_cmd.id)) {
      tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure" title="{{Configuration avancée}}"><i class="fas fa-cogs"></i></a> ';
      tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>';
    }
    tr += '</td>';
    tr += '<td>';
  	if (init(_cmd.logicalId) !== 'notif' && init(_cmd.logicalId) !== 'notifCritical' ){
    	tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove" title="{{Supprimer la commande}}"></i>';
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
