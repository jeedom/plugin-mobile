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

document.querySelector("#info_app")?.addEventListener("click", function (event) {
  jeeDialog.dialog({
    id: "infosApp",
    title: "{{Informations envoyées à l'app}}",
    contentUrl: "index.php?v=d&plugin=mobile&modal=info_app.mobile"
  })
})

document.querySelector("#bt_handlePhones")?.addEventListener("click", function (event) {
  jeeDialog.dialog({
    id: "menuCustom",
    title: "{{Gestion des Mobiles}}",
    contentUrl: "index.php?v=d&plugin=mobile&modal=menuCustom",
  })
  return
})

document.querySelector("#bt_qrCodev2")?.addEventListener("click", function (event) {
  jeeDialog.dialog({
    id: "qrcodev2",
    title: "{{QrCode}}",
    contentUrl: "index.php?v=d&plugin=mobile&modal=qrcodev2",
  })
})

function printEqLogic(_eqLogic) {
  domUtils.ajax({
    type: "POST",
    url: "plugins/mobile/core/ajax/mobile.ajax.php",
    data: {
      action: "getQrCode",
      id: _eqLogic.id,
    },
    dataType: "json",
    global: false,
    error: function (request, status, error) {
     domUtils.handleAjaxError(request, status, error)
    },
    success: function (data) {
      if (data.state != "ok") {
        jeedomUtils.showAlert({ message: data.result, level: "danger" })
        return
      }
      let el = document.querySelector(".qrCodeImg")
      el.innerHTML = ""
      if (data.result == "internalError") {
        el.innerHTML = "{{Erreur pas d'adresse interne (voir configuration de votre Jeedom !)}}"
      } else if (data.result == "externalError") {
        el.innerHTML = "{{Erreur pas d'adresse externe (voir configuration de votre Jeedom !)}}"
      } else if (data.result == "UserError") {
        el.innerHTML = "{{Erreur pas d'utilisateur selectionné}}"
      } else {
        el.innerHTML = '<img src="data:image/png;base64, ' + data.result + '" />'
      }
    }
  })
}

function addCmdToTable(_cmd) {
  if (document.getElementById('table_cmd') == null) return
  if (!isset(_cmd)) {
    var _cmd = { configuration: {} }
  }
  if (!isset(_cmd.configuration)) {
    _cmd.configuration = {}
  }
  var tr = '<tr>'
  tr += '<td class="hidden-xs">'
  tr += '<span class="cmdAttr" data-l1key="id"></span>'
  tr += "</td>"
  tr += "<td>"
  tr += '<div class="input-group">'
  var isGeoloc = init(_cmd.logicalId).includes("geoloc_")
  tr += '<input ' + ((isGeoloc == true) ? 'disabled' : '') + ' class="cmdAttr form-control input-sm roundedLeft" data-l1key="name" placeholder="{{Nom de la commande}}">'
  tr += '<span class="input-group-btn"><a class="cmdAction btn btn-sm btn-default" data-l1key="chooseIcon" title="{{Choisir une icône}}"><i class="fas fa-icons"></i></a></span>'
  tr += '<span class="cmdAttr input-group-addon roundedRight" data-l1key="display" data-l2key="icon" style="font-size:19px;padding:0 5px 0 0!important;"></span>'
  tr += "</div>"
  tr += "<td>"
  tr += '<span class="type" type="' + init(_cmd.type) + '">' + jeedom.cmd.availableType() + "</span>"
  tr += '<span class="subType" subType="' + init(_cmd.subType) + '"></span>'
  tr += "</td>"
  tr += "<td>"
  tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isVisible" checked/>{{Afficher}}</label> '
  tr += '<label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isHistorized" checked/>{{Historiser}}</label> '
  tr += '<div style="margin-top:7px;">'
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="minValue" placeholder="{{Min}}" title="{{Min}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="configuration" data-l2key="maxValue" placeholder="{{Max}}" title="{{Max}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
  tr += '<input class="tooltips cmdAttr form-control input-sm" data-l1key="unite" placeholder="Unité" title="{{Unité}}" style="width:30%;max-width:80px;display:inline-block;margin-right:2px;">'
  tr += "</div>"
  tr += "<td>"
  tr += '<span class="cmdAttr" data-l1key="htmlstate"></span>'
  tr += "</td>"
  tr += "<td>"
  if (is_numeric(_cmd.id)) {
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="configure" title="{{Configuration avancée}}"><i class="fas fa-cogs"></i></a> '
    tr += '<a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fas fa-rss"></i> {{Tester}}</a>'
  }
  tr += "</td>"
  tr += "<td>"
  if (init(_cmd.logicalId) !== "notif" && init(_cmd.logicalId) !== "notifCritical") {
    tr += '<i class="fas fa-minus-circle pull-right cmdAction cursor" data-action="remove" title="{{Supprimer la commande}}"></i>'
  }
  tr += "</td>"
  tr += "</tr>"
  let newRow = document.createElement('tr')
  newRow.innerHTML = tr
  newRow.addClass('cmd')
  newRow.setAttribute('data-cmd_id', init(_cmd.id))
  document.getElementById('table_cmd').querySelector('tbody').appendChild(newRow)
  jeedom.eqLogic.buildSelectCmd({
    id: document.querySelector('.eqLogicAttr[data-l1key="id"]').jeeValue(),
    filter: { type: 'info' },
    error: function(error) {
      jeedomUtils.showAlert({ message: error.message, level: 'danger' })
    },
    success: function(result) {
      newRow.setJeeValues(_cmd, '.cmdAttr')
      jeedom.cmd.changeType(newRow, init(_cmd.subType))
    }
  })
}
