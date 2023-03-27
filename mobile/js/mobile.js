"use strict"

$('body').attr('data-page', 'mobile')
/*
$(".iconePushTEST").off("click").on("click", function() {
  jeedom.mobile.panel.test({
    method:  $(this).attr('data-icone'),
    options:  'ploup',
    error: function(error) {
      $('#div_alert').showAlert({ message: error.message, level: 'danger' })
    },
    success: function(data) {
      $('#div_alert').showAlert({ message: '{{NUMERO ICONE TEST}}', level: 'success' })
    }
  })
})

*/


$(".iconePush").off("click").on("click", function() {
 let optionsModal = {'type' : 'WebviewApp', 'uri' : '/index.php?v=d&m=mobile&p=panelWebApp', 'sizeModal' : 10};
////  let optionsModal = {'type' : 'WebviewApp', 'uri' : '/plugins/mobile/mobile/php/menuForPanel2.php?app=1', 'sizeModal' : 50};
  jeedom.appMobile.modal(optionsModal);
  //jeedom.appMobile.notifee('JesuisInappTest', 'Corps du message', 2000)
})


$(".menuCustomPush").off("click").on("click", function() {
 let optionsModal = {'type' : 'WebviewApp', 'uri' : '/index.php?v=d&m=mobile&p=panelMenuCustom', 'sizeModal' : 10};
////  let optionsModal = {'type' : 'WebviewApp', 'uri' : '/plugins/mobile/mobile/php/menuForPanel2.php?app=1', 'sizeModal' : 50};
  jeedom.appMobile.modal(optionsModal);
  //jeedom.appMobile.notifee('JesuisInappTest', 'Corps du message', 2000)
})

$("#testQR").off("click").on("click", function() {
 let optionsModal = {'type' : 'barreCode','optionText':'Je suis un text de test', 'method':'qrcodemethod','plugin':'mobile', 'uri' : '/plugins/mobile/mobile/html/newMenu3.php?app=1', 'sizeModal' : 50};
//  let optionsModal = {'type' : 'WebviewApp', 'uri' : '/plugins/mobile/mobile/php/menuForPanel2.php?app=1', 'sizeModal' : 50};
  jeedom.appMobile.modal(optionsModal);
})

/*
$("#testQR").off("click").on("click", function() {
 let optionsModal = {'type' : 'qrCode','optionText':'Je suis un text de test', 'method':'qrcodemethod','plugin':'mobile', 'uri' : '/plugins/mobile/mobile/html/newMenu3.php?app=1', 'sizeModal' : 50};
})*/
