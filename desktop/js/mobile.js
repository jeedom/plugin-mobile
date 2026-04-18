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

//  AppV2  \\\

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

document.querySelector("#bt_doc")?.addEventListener("click", function (event) {
 jeedomUtils.loadPage('index.php?v=d&m=mobile&p=wizard')
});

//  AppV1  \\

document.querySelector("#info_app")?.addEventListener("click", function (event) {
  jeeDialog.dialog({
    id: "infosApp",
    title: "{{Informations envoyées - Application V1}}",
    contentUrl: "index.php?v=d&plugin=mobile&modal=AppV1Info"
  })
})

document.querySelector("#bt_pluginmobile")?.addEventListener("click", function (event) {
  jeeDialog.dialog({
    id: "pluginsCompatibles",
    title: "{{Plugins compatibles - Application V1}}",
    contentUrl: "index.php?v=d&plugin=mobile&modal=AppV1Plugin",
  });
});

document.querySelector("#bt_piecemobile")?.addEventListener("click", function (event) {
  jeeDialog.dialog({
    id: "objectsModal",
    title: "{{Objets / Pièces - Application V1}}",
    contentUrl: "index.php?v=d&plugin=mobile&modal=AppV1Piece",
  });
});

document.querySelector("#bt_scenariomobile")?.addEventListener("click", function (event) {
  jeeDialog.dialog({
    id: "scenariosModal",
    title: "{{Scénarios  - Application V1}}",
    contentUrl: "index.php?v=d&plugin=mobile&modal=AppV1Scenario",
  });
});

document.querySelector("#bt_regenConfig")?.addEventListener("click", function (event) {
  domUtils.ajax({
    type: "POST",
    url: "plugins/mobile/core/ajax/mobile.ajax.php",
    data: {
      action: "AppV1RegenConfig",
    },
    dataType: "json",
    error: function (request, status, error) {
      domUtils.handleAjaxError(request, status, error)
    },
    success: function (data) {
      if (data.state != "ok") {
        jeedomUtils.showAlert({ message: data.result, level: "danger" })
        return
      }
      jeedomUtils.showAlert({ message: "{{Configuration mise à jour}}", level: "success"});
    },
  });
});

// NotificationsV2
document.querySelector(".notification-box")?.addEventListener("click", function (event) {
  var _target = null
  if (_target = event.target.closest('.bt_removeNotification')) {
    let notification = _target.closest('.notification-step')
    let id = notification.getAttribute('data-id');
    let iq = notification.getAttribute('data-iq');
    domUtils.ajax({
      type: "POST",
      url: "plugins/mobile/core/ajax/mobile.ajax.php",
      data: {
        action: "removeNotificationV2",
        id: id,
        iq: iq
      },
      dataType: "json",
      error: function (request, status, error) {
        domUtils.handleAjaxError(request, status, error)
      },
      success: function (data) {
        if (data.state != "ok") {
          jeedomUtils.showAlert({ message: data.result, level: "danger" })
          return
        }
        notification.remove()
        if (data.result == '0' && is_object(el = document.querySelector(".notification-box"))) {
          el.innerHTML = '<div class="alert alert-warning">{{Aucune notifications}}</div>' 
        }
        jeedomUtils.showAlert({ message: "{{Notification supprimées.}}", level: "success"});
      },
    });
    return
  }

  if (_target = event.target.closest('.bt_refreshNotifications')) {
    let iq = _target.getAttribute('data-iq')
     printNotification(iq)
    jeedomUtils.showAlert({ message: "{{Terminé.}}", level: "success"});
    return
  }
});

// Copie pour monitoring
var toCopy = document.getElementById("to-copy-monitoring");
var arnComplet = document.getElementById("arnComplet");
document.getElementById("copy-monitoring")?.addEventListener("click", function () {
  if (arnComplet.jeeValue() != '') {
    var fichier = arnComplet.jeeValue();
    var fichierCouper = fichier.substr(44);
    toCopy.value = fichierCouper;
    toCopy.select();
    document.execCommand("copy");
    return false;
  }
});
/////////////////


function printEqLogic(_eqLogic) {
  let appVersion = _eqLogic.configuration.appVersion;
  if (appVersion == 2) {
    document.querySelectorAll(".paramV1").unseen()
    document.querySelectorAll(".paramV2").seen()
    printNotification(_eqLogic.logicalId)
  } else {
    document.querySelectorAll(".paramV2").unseen()
    document.querySelectorAll(".paramV1").seen()
    let el = document.querySelector(".notification-box");
    el.innerHTML = '<div class="alert alert-warning">Compatible AppV2 uniquement.</div>'
  }
  
  // AppV1
  if (appVersion != 2) {
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
        let el = document.querySelector(".qrCodeImg");
        el.innerHTML = "";
        if (data.result == "internalError") {
          el.innerHTML = "{{Erreur pas d'adresse interne (voir configuration de votre Jeedom !)}}";
        } else if (data.result == "externalError") {
          el.innerHTML = "{{Erreur pas d'adresse externe (voir configuration de votre Jeedom !)}}";
        } else if (data.result == "UserError") {
          el.innerHTML = "{{Erreur pas d'utilisateur selectionné}}";
        } else {
          el.innerHTML = '<img src="data:image/png;base64, ' + data.result + '" />';
        }
      },
    });
    
    domUtils.ajax({
      type: "POST",
      url: "plugins/mobile/core/ajax/mobile.ajax.php",
      data: {
        action: "AppV2GetSaveFavDash",
        iq: _eqLogic.logicalId,
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
        let saveFav = document.querySelector("#SaveFav")
        if (is_object(saveFav)) {
          if (data.result == true) {
            saveFav.removeClass('label-warning', 'label-danger').addClass('label-success')
            saveFav.innerHTML = "OK"
          } else if (data.result == false) {
            saveFav.removeClass('label-success', 'label-warning').addClass('label-danger')
            saveFav.innerHTML = "NOK"
          }
        }
      }
    });

    domUtils.ajax({
      type: "POST",
      url: "plugins/mobile/core/ajax/mobile.ajax.php",
      data: {
        action: "AppV2GetSaveDashboard",
        iq: _eqLogic.logicalId,
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
        let savedash = document.querySelector("#SaveDash");
        if (is_object(savedash)) {
          if (data.result == true) {
            savedash.removeClass('label-warning', 'label-danger').addClass('label-success')
            savedash.innerHTML = "OK";
          } else if (data.result == false) {
            savedash.removeClass('label-success', 'label-warning').addClass('label-danger')
            savedash.innerHTML = "NOK";
          }
        }
      },
    });

    document.getElementById("copy-monitoring")?.click()
  }
  
  /// if delete code AppV1 in mobile.php into Paramètres spécifiques -> 
  /// change <span class="label label-primary type_mobile"></span> by <span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="type_mobile"></span> 
  let el_type = document.querySelector(".type_mobile")
  let select_type = document.querySelector('.eqLogicAttr[data-l1key="configuration"][data-l2key="type_mobile"]')
  let value_type = ''
  if (is_object(select_type)) {
    value_type = select_type.options[select_type.selectedIndex]?.text || 'INCONNU'
  }
  if (is_object(el_type)) {
    el_type.innerHTML = value_type
    if (value_type == 'INCONNU') el_type.removeClass('label-primary').addClass('label-danger')
    else el_type.removeClass('label-danger').addClass('label-primary')
  }

  /// if delete code AppV1 in mobile.php into Paramètres spécifiques -> 
  /// change <span class="label label-primary affect_user"></span> by <span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="affect_user"></span> 
  let el_affect_user = document.querySelector(".affect_user")
  let select_affect_user = document.querySelector('.eqLogicAttr[data-l1key="configuration"][data-l2key="affect_user"]')
  let value_affect_user = ''
  if (is_object(select_affect_user)) {
    value_affect_user = select_affect_user.options[select_affect_user.selectedIndex]?.text || 'INCONNU'
  }
  if (is_object(el_affect_user)) {
    el_affect_user.innerHTML = value_affect_user
    if (value_affect_user == 'INCONNU' || value_affect_user == 'Aucun') el_affect_user.removeClass('label-primary').addClass('label-danger')
    else el_affect_user.removeClass('label-danger').addClass('label-primary')
  }
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
  if (init(_cmd.logicalId) !== "notif" && init(_cmd.logicalId) !== "notifCritical" && init(_cmd.logicalId) !== "notifSilent") {
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

function printNotification(_iq) {
  domUtils.ajax({
    type: "POST",
    url: "plugins/mobile/core/ajax/mobile.ajax.php",
    data: {
      action: "getNotificationsV2",
      iq: _iq,
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
        
      let el = document.querySelector(".notification-box");
      
      try {
        var objectData = JSON.parse(data.result);
      } catch {
        el.innerHTML = '<div class="alert alert-warning">' + data.result + '</div>'
        return
      }

      if (typeof objectData == "object") {
        let notification = ''
        notification += '<div class="notification-button bt_refreshNotifications" data-iq="' + _iq + '"><a class="btn btn-sm btn-default"><i class="fas fa-sync-alt"></i> Rafraichir</a></div>'
        moment.locale(jeeFrontEnd.language.substring(0, 2))
        for (var i in objectData) { 
          // ASK
          let askStatus = ''
          let askChoice = ''
          if (isset(objectData[i].data.choiceAsk)) {
            askStatus = 'ask-success'
            askChoice = objectData[i].data.choiceAsk
          } else if (objectData[i].data.askVariable != 'rien') {
            let notifTime = moment(objectData[i].data.date, "YYYY-MM-DD hh:mm:ss").unix()
            let currentTime = moment().unix()
            try {
              let askParams = (isset(objectData[i].data.askParams)) ? JSON.parse(objectData[i].data.askParams) : ''
              if (isset(askParams.timeout)) {
                let timeout = askParams.timeout / 1000
                if ((currentTime - notifTime) > timeout) {
                  askStatus = 'ask-timeout'
                  askChoice = '{{Timeout}}'
                } else {
                  askStatus += 'ask-in-progress'
                  askChoice = '{{En cours...}}'
                }
              }
            } catch { }
          }
          
          notification += '<div class="notification-step ' + ((objectData[i].data.critical == 'true') ? 'stepper-critical ' : (askStatus != '') ? askStatus : '') + '" data-id="' + objectData[i].data.idNotif + '" data-iq="' + _iq + '">'
            notification += '<div class="notification-date">' + objectData[i].data.date + '</div>'
            if (objectData[i].data.askVariable != 'rien') {
              notification += '<div class="notification-circle" title="variable : ' + objectData[i].data.askVariable + '">ASK</div>'
            } else if (objectData[i].data.critical == 'true') {
              notification += '<div class="notification-circle" title="{{Notification critique}}">N</div>'
            } else if (objectData[i].data.silent == 'true') {
              notification += '<div class="notification-circle" title="{{Notification silencieuse}}">N<sub>S</sub></div>'
            } else {
              notification += '<div class="notification-circle" title="{{Notification}}">N</div>'
            }
            notification += '<div class="notification-line"></div>'
            notification += '<div class="notification-content">'
              //notification += '<a class="btn btn-sm btn-danger pull-right bt_removeNotification" data-action="removeNotification"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>'
              notification += '<i class="fas fa-minus-circle pull-right bt_removeNotification cursor" title="Supprimer"></i>'
              notification += '<div class="notification-title">' + objectData[i].data.title + '</div>'
              notification += '<div class="notification-body">' + objectData[i].data.body + '</div>'
              if (isset(objectData[i].data.image)) {
                notification += '<div class="notification-img"><img src="' + objectData[i].data.image + '"></img></div>'
              }
              if (askChoice != '') notification += '<div class="notification-status">' + askChoice + '</div>'
            notification += '</div>'
          notification += '</div>'
          //console.log(objectData[i])
        }
        el.innerHTML = notification
        jeedomUtils.initTooltips(el)
      }
    },
  });
  
}

// WIZARD 

var _contentContainer = document.getElementById('wizard_container');
var currentStep = getUrlVars('step'); 

if (!currentStep) {
    currentStep = localStorage.getItem('wizardStep') || document.querySelector('.navDot')?.dataset.step;
}
document.querySelector('.navDot[data-step="' + currentStep + '"]')?.classList.add('active');
if (window.updateQuitButtonVisibility) window.updateQuitButtonVisibility();
loadStep(currentStep);

var slideOut = {
  opacity: [1, 0],
  transform: ['translateX(0)', 'translateX(-10%)']
}
var slideIn = {
  opacity: [0, 1],
  transform: ['translateX(10%)', 'translateX(0)']
}
var slideOutReverse = {
  opacity: [1, 0],
  transform: ['translateX(0)', 'translateX(10%)']
}
var slideInReverse = {
  opacity: [0, 1],
  transform: ['translateX(-10%)', 'translateX(0)']
}

document.querySelectorAll('.navDot').forEach(_dot => {
  _dot.addEventListener('click', function() {
    let current = document.querySelector('.navDot.active')
    
    if (this == current || this.classList.contains('blocked')) {
      return false
    }
    if (current.nextElementSibling?.classList.contains('blocked')) {
      allowNavigation()
    }

    let outAnimation = slideOut
    let inAnimation = slideIn
    if (Number(this.innerText) < Number(current.innerText)) {
      outAnimation = slideOutReverse
      inAnimation = slideInReverse
    }

    _contentContainer.animate(outAnimation, {
      duration: 500
    })
    setTimeout(() => {
      document.querySelector('.navDot.active').classList.remove('active')
      this.classList.add('active')
      _contentContainer.empty()
      localStorage.setItem('wizardStep', this.dataset.step);
      loadStep(this.dataset.step)
      _contentContainer.animate(inAnimation, {
        duration: 500
      })
      if (window.updateQuitButtonVisibility) window.updateQuitButtonVisibility();
    }, 450)
  })
})

document.querySelectorAll('.navBtn').forEach(_navBtn => {
  _navBtn.addEventListener('click', function() {
    let activeNavDot = document.querySelector('.navDot.active')
    if (this.classList.value.includes('bt_next')) {
      activeNavDot.nextElementSibling.triggerEvent('click')
    } else if (this.classList.value.includes('bt_prev')) {
      activeNavDot.previousElementSibling.triggerEvent('click')
    }
  })
})


var quitBtn = document.getElementById('bt_quitmobileWizard');
if (quitBtn) {
  quitBtn.addEventListener('click', function() {
    let confirm
      confirm = "{{Voulez-vous vraiment fermer la documentation ?}}"
      confirm += '<br><br>'

    bootbox.confirm(confirm, function(result) {
      if (result) {
        exitWizard()
      }
    })
  })
}

var readyBtn = document.getElementById('bt_jeedom_ready');
if (readyBtn) {
  readyBtn.addEventListener('click', function() {
    exitWizard()
  })
}

function loadStep(_step) {
    updateNavigation(_step);
    updateContent('index.php?v=d&plugin=mobile&modal=' + _step);
}

function updateContent(_url) {
    if (!_contentContainer) return;
    _contentContainer.style.visibility = 'hidden';
    fetch(_url)
        .then(response => response.text())
        .then(data => {
            if (!_contentContainer) return;
            _contentContainer.innerHTML = data;

            _contentContainer.querySelectorAll('script').forEach(_script => {
                let newScript = document.createElement('script');
                if (_script.src) {
                    newScript.src = _script.src;
                } else {
                    newScript.textContent = _script.textContent;
                }
                _contentContainer.appendChild(newScript);
                _contentContainer.removeChild(newScript);
            });

            _contentContainer.style.visibility = 'visible';
            jeedomUtils.initTooltips(_contentContainer);
            const stepOrder = Array.from(document.querySelectorAll('.navDot')).map(dot => dot.dataset.step);
        })
        .catch(error => {
            console.error('{{Erreur au chargement de la page}}:', error);
            domUtils.hideLoading();
            if (_contentContainer) {
                _contentContainer.style.visibility = 'visible';
            }
        });
}



function updateNavigation(_step) {
    let current = document.querySelector('.navDot[data-step="' + _step + '"]');
    if (!current) {
        return;
    }
    document.querySelector('.navBtn.bt_next').dataset.step = _step
  if (!current.previousElementSibling) {
    document.querySelector('.navBtn.bt_prev')?.classList.add('hidden')
  } else {
    document.querySelector('.navBtn.bt_prev').title = current.previousElementSibling.dataset.title
    document.querySelector('.navBtn.bt_prev.hidden')?.classList.remove('hidden')
  }
  if (!current.nextElementSibling) {
    document.querySelector('.navBtn.bt_next')?.classList.add('hidden')
    if (_step == 'ready') {
      document.getElementById('bt_jeedom_ready')?.classList.remove('hidden')
    }
  } else {
    document.getElementById('bt_jeedom_ready')?.classList.add('hidden')
    document.querySelector('.navBtn.bt_next').title = current.nextElementSibling.dataset.title
    document.querySelector('.navBtn.bt_next.hidden')?.classList.remove('hidden')
  }
  jeedomUtils.addOrUpdateUrl('step', _step);
    localStorage.setItem('wizardStep', _step);
    if (window.updateQuitButtonVisibility) window.updateQuitButtonVisibility();
}

function allowNavigation(_direction = 'both', _allowed = true) {
    let current = document.querySelector('.navDot.active');
    if (_direction != 'next') {
        let prevDot = current.previousElementSibling;
        while (prevDot) {
            if (!_allowed) {
                prevDot.classList.add('blocked');
            } else {
                prevDot.classList.remove('blocked');
            }
            prevDot = prevDot.previousElementSibling;
        }
    }
    if (_direction != 'prev') {
        let nextDot = current.nextElementSibling;
        while (nextDot) {
            if (!_allowed) {
                nextDot.classList.add('blocked');
            } else {
                nextDot.classList.remove('blocked');
            }
            nextDot = nextDot.nextElementSibling;
        }
    }
}

function exitWizard() {
  window.location.href = 'index.php?v=d&m=mobile&p=mobile';
}
