<?php

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

?>

<div class="resumeBtn" style="display:flex;justify-content:flex-end;">
     <button class="btn btn-success" id="validView" style="border-radius:20px !important;padding-left:5px !important;padding-right:5px !important;margin-bottom:10px;">Valider la vue</button>
</div>


<div id="main" style="display:flex;flex-direction:row;">
<div class="gridPage" style="width:400px; height;100vh;">
  <div class="tile  customTile" id="1">
    <div class="TileUp">
      <div class="UpLeft">
        <i class="iconTile icon jeedomapp-ampoule-off"></i>
      </div>
      <div class="UpRight">

      </div>
    </div>
    <div class="TileDown" >
      <div class="title bold">Lumière Salon</div>
      <div class="title">Eteinte</div>
    </div>
  </div>  

  <div class="tile on" id="2">
    <div class="TileUp">
        <div class="UpLeft">
          <i class="iconTile on icon jeedomapp-ampoule-on"></i>
        </div>
        <div class="UpRight">

        </div>
      </div>
      <div class="TileDown">
        <div class="title on bold">Lumière Cuisine</div>
        <div class="title on">Allumée à 30%</div>
      </div>
  </div>
  <div class="tile customTile" id="3" data-title="Météo">

  </div>
  <div class="tile customTile" id="4" data-title="Prise Chambre">

  </div>
  <div class="tile dual on" id="5">

  </div>

  <div class="tile dual" id="6">

  </div>
  <div class="tile" id="7">

  </div>
  <div class="tile" id="8">

  </div>
  <div class="tile quadral on" id="9">

  </div>
  <div class="tile" id="10">

  </div>
  <div class="tile" id="11">

  </div>
  <div class="tile" id="12">

  </div>
  <div class="tile" id="13">

  </div>
  <div class="tile" id="14">

  </div>

</div>
  <div id="rightContent" style="width:100%;height;100vh; display:flex;flex-direction:column;align-items:center;">

  </div>
</div>

<style>

@keyframes bounceAndScale {
    0%, 20%, 50%, 80%, 100% {
        transform: scale(1.0);
    }
    40% {
        transform: scale(1.1) translateY(-30px);
    }
    60% {
        transform: scale(1.1) translateY(-15px);
    }
}

.bounceAndScale {
    animation: bounceAndScale 1s;
}

/* #main > div:first-child {
    border-right: 3px solid #96C927; 
    padding-right:2%;
} */

.firstSection{
  z-index: 2;;
}

/* .configTileDiv:hover{
  text-decoration: none;
  color: #FFF;
} */


.bgDiv{
  height: 128px;
  width: 128px;
  z-index: 1;
  position: absolute;
  top: -90px;
  right: -90px;

  border-radius: 50%;

  -webkit-transition: all 1s ease;
  -o-transition: all 1s ease;
  transition: all 1s ease;

}


.bootBoxClass{
  position: absolute;
    top: 50px;
    left: 50px;
}

  .resume{
  display: flex;
  flex-direction: row;
  justify-content: space-around;
  align-items: center;
  height: 50px;
  width: 100%;
  margin-bottom: 15px;
  margin-top: 15px;
  font-family: Raleway, Helvetica, sans-serif;
  font-size: 1.1em;
  overflow-x: auto;
  overflow-y: hidden;
  }

  .iconResume{
    position: relative;
    top: -72px;
    left: 11px;
    font-size: 0.8em;
    color: #000;
  }

  .resumeTile{
    display: flex;
    flex-direction: row;
    width: 150px;
    min-width: 150px;
    -webkit-backdrop-filter: blur(5px);
    backdrop-filter: blur(5px);
    background-color: rgba(255, 255, 255, 0.9);
    margin: 5px;
    border-radius: 10px;
    box-shadow:
      0 1px 1px hsl(0deg 0% 0% / 0.075),
      0 2px 2px hsl(0deg 0% 0% / 0.075),
      0 4px 4px hsl(0deg 0% 0% / 0.075)
    ;
  }

  .resumeLabel{
    display: flex;
    flex-direction: column;
    justify-content: end;
    align-items: center;
    width: 100px;
    color: #002439;
  }
  .resumeTitle{
    margin-left: 5px;
  }
  .resumeValue{
    font-size: 2em;
    width: 50px;
    height: 50px;
    max-height: 50px;
    display: inline-block;
  }

.gridPage {
  display: grid;
  justify-content: center;
  grid-template-rows: 150px;
  grid-auto-rows: 150px;
  grid-template-columns: repeat(auto-fit, minmax(150px, 150px));
  row-gap: 15px;
  column-gap: 15px;
  grid-column-gap: 15px;
  padding-right: 10px;
  padding-top: 15px;
  font-family: Raleway, Helvetica, sans-serif;
  font-size: 1.1em;
}

.tile{
  display: flex;
  flex-direction: column;
  justify-content: start;
  align-items: left;
  height: 150px;
  width: 150px;
  -webkit-backdrop-filter: blur(5px);
  backdrop-filter: blur(5px);
  background-color: rgba(235, 235, 233, 0.9);
  margin: 5px;
  border-radius: 10px;
  box-shadow:
    0 1px 1px hsl(0deg 0% 0% / 0.075),
    0 2px 2px hsl(0deg 0% 0% / 0.075),
    0 4px 4px hsl(0deg 0% 0% / 0.075),
    0 8px 8px hsl(0deg 0% 0% / 0.075),
    0 16px 16px hsl(0deg 0% 0% / 0.075)
  ;
}

.tile.on {
  background-color: rgba(255, 255, 255, 0.9) !important;
}

.tile.dual{
  height: 150px;
  width: 315px;
  grid-column: span 2;
}

.tile.quadral{
  height: 315px;
  width: 315px;
  grid-column: span 2;
  grid-row: span 2;
}

.TileUp{
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  height: 65%;
  width: 100%;
}
.TileDown{
  display: flex;
  flex-direction: column;
  height: 35%;
  align-items: start;
  margin-bottom: 5px;
  margin-left: 5px;
}
.UpLeft{
  display: flex;
  height: 100%;
  width: 50%;
  justify-content: center;
  align-items: center;
}
.UpRight{
  height: 100%;
  width: 50%;
}
.iconTile{
  font-size: 3.5em;
  color: #a4a4a3;
}
.iconTile.on{
  color: #f7d959;
}
.title{
  text-align: left;
  color: #a4a4a3;
  margin-left: 5px;
  width: 100%;
}
.title.bold{
  font-weight: bold;
}
.title.on{
  color: #002439;
}
.title.mini{
  font-size: 0.8em;
  margin-left: 5px;
}

.single-chart {
  width: 100%;
}

.circular-chart {
  display: block;
  margin: 10px auto;
  max-width: 80%;
  max-height: 60px;
}

.circle-bg {
  fill: none;
  stroke: #eee;
  stroke-width: 3.8;
}

.circle {
  fill: none;
  stroke-width: 2.8;
  stroke-linecap: round;
  animation: progress 1s ease-out forwards;
}

@keyframes progress {
  0% {
    stroke-dasharray: 0 100;
  }
}

.circular-chart.orange .circle {
  stroke: #ff9f00;
}

.circular-chart.red .circle {
  stroke: red;
}

.circular-chart.green .circle {
  stroke: #4CC790;
}

.circular-chart.blue .circle {
  stroke: #3c9ee5;
}

.percentage {
  fill: #666;
  font-family: sans-serif;
  font-size: 0.5em;
  text-anchor: middle;
}
</style>


<script>

  


if (typeof AJAX_URL === 'undefined') {
    const AJAX_URL = 'plugins/mobile/core/ajax/mobile.ajax.php';
}

document.getElementById('validView').addEventListener('click', function(event) {
    event.preventDefault();
    var tiles = document.querySelectorAll('.tile');
    var config = [];
    tiles.forEach(function(tile) {
      let idTile = tile.id;
      let sizeAttribute = tile.getAttribute('data-state');
      // console.log(tile);
      // console.log(tile.classList); 
      if (!sizeAttribute || sizeAttribute == undefined || sizeAttribute == "null") {
          if (tile.classList.contains('dual')) {
              sizeAttribute = 2;
          } else if (tile.classList.contains('quadral')) {
              sizeAttribute = 4;
          } else {
              sizeAttribute = 1;
          }
      }
      console.log('sizeAttribute', sizeAttribute)
      var tileConfig;
      if (tile.hasAttribute('data-array')) {
        tileConfig = JSON.parse(tile.getAttribute('data-array'));
      } else {
         tileConfig = {
          size: sizeAttribute ,
          type: 'info',
          options: {
            on: 0,
            title: tile.getAttribute('data-title'),
            value: null,
            icons: {
              on: {
                type: "jeedomapp",
                name: tile.getAttribute('data-icon-on'),
                color: "#00ff00"
              },
              off: {
                type: "jeedomapp",
                name: tile.getAttribute('data-icon-off'),
                color: "#a4a4a3"
              }
            },
            iconBlur: false
          }
        };
    }
      if (!config[idTile]) {
        config[idTile] = [];
      }
      config[idTile].push(tileConfig);
    });
 

    console.log('config', config);
    $.ajax({
      type: 'POST',
      url: 'plugins/mobile/core/ajax/mobile.ajax.php',
      data: {
        action: 'createJsonBellaMobile',
        config: config
      },
      dataType: 'json',
      error: function(request, status, error) {
        handleAjaxError(request, status, error);
      },
      success: function(data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'});
          return;
        }
        $('#div_alert').showAlert({message: '{{Configuration sauvegardée}}', level: 'success'});
      }
    });
  
});


var tiles = document.querySelectorAll('.tile');

var colors = ["#94ca02", "#9fcf1b", "#a9d535", "#b4da4e", "#bfdf67", "#cae581", "#d4ea9a", "#dfefb3", "#eaf4cc", "#f4fae6"];

function getRandomColor() {
    var randomIndex = Math.floor(Math.random() * colors.length);
    return colors[randomIndex];
}


const createConfigTile = (tileElement, idTile, randomColor, MODELS_CHOICE) => {
    let configTileDiv = document.createElement('div');
    let bgDiv = document.createElement('div');
    bgDiv.classList.add('bgDiv');
    bgDiv.setAttribute('data-id', idTile);
    bgDiv.setAttribute('style', 'background-color:' + randomColor + ';');
    configTileDiv.appendChild(bgDiv);
   //configTileDiv.setAttribute('style', 'position: relative;overflow: hidden;padding-left:10px;margin-bottom:10px;width:100% !important;height:100px;background-color:' + randomColor + ';display:flex;border-radius:10px !important;');
    configTileDiv.setAttribute('style', 'position: relative;overflow: hidden;padding-left:10px;margin-bottom:10px;width:100% !important;height:100px;background-color:#ffffff;display:flex;border-radius:10px !important;');
    configTileDiv.setAttribute('id', 'configTileDiv'+idTile);
    configTileDiv.classList.add('configTileDiv');
    configTileDiv.setAttribute('data-id', idTile);
    configTileDiv.style.order = idTile; 
    let firstSection = document.createElement('div');
    let label = document.createElement('label');
    label.innerHTML = 'Choisir le type de template à appliquer';
    firstSection.appendChild(label);
    firstSection.classList.add('firstSection')
    firstSection.setAttribute('style', 'width:20% !important;display:flex;flex-direction:column;justify-content:center !important;');
    let firstSelect = document.createElement('select');
    firstSelect.setAttribute('id', 'firstSelect');
    firstSelect.setAttribute('style', 'margin-bottom: 10px;');
    configTileDiv.appendChild(firstSection);

    // ADD ANIMATIONS HOVER
    configTileDiv.addEventListener('mouseover', function() {
        var associatedTile = configTileDiv.getAttribute('data-id')
        if (associatedTile) {
          var associatedElement = document.getElementById(associatedTile);
          associatedElement.style.zIndex = '10'; 
          associatedElement.classList.add('bounceAndScale');
          associatedElement.addEventListener('animationend', function() {
              this.classList.remove('bounceAndScale');
          });
          let bgDiv = document.querySelector(`.bgDiv[data-id="${associatedTile}"]`);
         bgDiv.style.transform = 'scale(20)';
        }
    });
  // REMOVE ANIMATIONS OUT HOVER
    configTileDiv.addEventListener('mouseout', function() {
      var associatedTile = configTileDiv.getAttribute('data-id')
      if (associatedTile) {
        var associatedElement = document.getElementById(associatedTile);
        associatedElement.style.transform = 'scale(1.0)';
        associatedElement.style.zIndex = '1'; 
        let bgDiv = document.querySelector(`.bgDiv[data-id="${associatedTile}"]`);
         bgDiv.style.transform = 'scale(1)';
      }
   });

  tileElement.addEventListener('mouseover', function() {
        let tileId = tileElement.getAttribute('id');
        let bgDiv = document.querySelector(`.bgDiv[data-id="${tileId}"]`);
        bgDiv.style.transform = 'scale(20)';
        tileElement.style.transform = 'scale(1.1)';
    });
    tileElement.addEventListener('mouseout', function() {
        let tileId = tileElement.getAttribute('id');
        let bgDiv = document.querySelector(`.bgDiv[data-id="${tileId}"]`);
        bgDiv.style.transform = 'scale(1)';
        tileElement.style.transform = 'scale(1.0)';
    });
            
  MODELS_CHOICE.forEach(function(model) {
    let option = document.createElement('option');
    option.value = model.value;
    option.text = model.text;
    firstSelect.appendChild(option);
  });
  firstSection.appendChild(firstSelect);
  document.getElementById('rightContent').appendChild(configTileDiv);
  }






if (typeof longClickOccurred === 'undefined') {
    var longClickOccurred = false;
}
if (typeof timer === 'undefined') {
    var timer;
}


tiles.forEach(function(tile) {

  tile.dataset.state = null;

  tile.addEventListener('mouseup', function(event) {
    clearTimeout(timer); 

    if (!longClickOccurred) {
        tile.classList.remove('1', 'dual', 'quadral');

        if (tile.dataset.state === '1') {
            tile.dataset.state = 'dual';
        } else if (tile.dataset.state === 'dual') {
            tile.dataset.state = 'quadral';
        } else {
            tile.dataset.state = '1';
        }

        tile.classList.add(tile.dataset.state);
    }
});


  tile.addEventListener('mousedown', function(event) {
    longClickOccurred = false;
     let idTile = tile.id;
     let tileElement = this;


      timer = setTimeout(function() {
        longClickOccurred = true; 
        let existingConfigTileDiv = document.getElementById('configTileDiv' + idTile);
        if (existingConfigTileDiv){
          existingConfigTileDiv.remove();
          tileElement.style.setProperty("background-color", 'white', "important");
          return;
        }  
       
        let randomColor = getRandomColor();
        tileElement.style.setProperty("background-color", randomColor, "important");
        var MODELS_CHOICE = [ {text :'Info', value:'Info'}, 
                              {text :'Meteo', value:'Meteo'}, 
                              {text :'Lumière', value:'OnOff'}
                            ];
            createConfigTile(tileElement, idTile, randomColor, MODELS_CHOICE);
            return;

          bootbox.prompt({
            title: "{{Choisir le type de template à appliquer}}",
            inputType: 'select',
            inputOptions: MODELS_CHOICE,
            className: 'slideInUp animated',
            // className: 'bootBoxClass',
            
            callback: function(model) {
              if (model == null) {
                return
              }
              var CHOICE_TYPECMD_TOSEARCH = [ {text :'Manuellement', value:'manualSearch'}, {text :'Generic Type', value:'genericType'}];

              bootbox.prompt({
                title: "{{Choisir une commande manuellement ou via les generics types ? }}",
                inputType: 'select',
                inputOptions: CHOICE_TYPECMD_TOSEARCH,
                className: 'slideInDown animated',
                callback: function(choiceCmd) {
                  if (choiceCmd == null) {
                    return
                  }
                  if(choiceCmd == 'genericType'){
                    if(model == 'OnOff'){
                      bootBoxGenericTypeFunction(model, choiceCmd, tileElement).then(returnBootBox => {
                            console.log('returnBootBox', returnBootBox);
                        }).catch(error => {
                            console.error('Une erreur est survenue :', error);
                        });                
                    }

                  }else{
                    bootBoxAllCmds(model, choiceCmd, tileElement)
                  }
                }})



            }
          })
      }, 1000);
      // tile.addEventListener('mouseup', function() {
      //   clearTimeout(timer); 
      // });
    });



    // document.querySelectorAll('configTileDiv')?.forEach(function(configTileDiv) {
    //   configTileDiv.addEventListener('mouseover', function() {
    //           var associatedTile = configTileDiv.getAttribute('data-id')
    //           if (associatedTile) {
    //             document.getElementById(associatedTile).style.transform = 'scale(1.1)';
    //             document.getElementById(associatedTile).style.zIndex = '10'; 
    //           }
    //         });
    //         configTileDiv.addEventListener('mouseout', function() {
    //           var associatedTile = configTileDiv.getAttribute('data-id')
    //           if (associatedTile) {
    //             document.getElementById(associatedTile).style.transform = 'scale(1.1)';
    //             document.getElementById(associatedTile).style.zIndex = '10'; 
    //           }
    //       });
    // });


      

    

   

    const ajaxConfig = (action, data) => ({
      type: 'POST',
      url: AJAX_URL,
      data: {
        action,
        ...data
      },
      dataType: 'json',
      error: (request, status, error) => handleAjaxError(request, status, error)
    });


  const bootBoxAllCmds = (tmodel, choiceCmd, tileElement) => {
      jeedom.eqLogic.getSelectModal({}, function(eqLogic) {
         jeedom.eqLogic.getCmd({
            id: eqLogic.id,
            typeCmd : 'info',
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_search', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function(result) {
              const ALLCMDS = result.map(cmd => ({ text: cmd.name, value: cmd.id }));
              bootbox.prompt({
                  title: "Choisir la commande",
                  inputType: 'select',
                  inputOptions: ALLCMDS,
                  callback: idCmd => {
                    if (idCmd == null) return;
                      var _icon = false
                     jeedomUtils.chooseIcon(function(_icon) {
                      var iconElement = tileElement.querySelector('.iconTile');
                      var newIconClasses = _icon.match(/class='([^']*)'/)[1].split(' ');
                      iconElement.className = 'iconTile';
                      newIconClasses.forEach(function(className) {
                          if (className !== 'iconTile') {
                              iconElement.classList.add(className);
                          }
                      });
                        jeeFrontEnd.modifyWithoutSave = true
                      }, { icon: _icon })
                  }
                })
            }
          })
      })
    // return new Promise((resolve, reject) => {

    // });
    
  }


  const bootBoxGenericTypeFunction = (model, choiceCmd, tileElement) => {
      return new Promise((resolve, reject) => {
            let idOn, idOff;

            $.ajax({
              ...ajaxConfig('getEqlogicByGenericType', { model: 'LIGHT_COLOR' }),
              success: data => {
                const EQLOGICS = data.result.map(cmd => ({ text: cmd.name, value: cmd.id }));

                bootbox.prompt({
                  title: "Choisir l equipement",
                  inputType: 'select',
                  inputOptions: EQLOGICS,
                  callback: id => {
                    if (id == null) return;

                    $.ajax({
                      ...ajaxConfig('getCmdsByValues', { id }),
                      success: data => {
                        data.result.forEach(cmd => {
                          if (cmd.name === 'On') idOn = cmd.id;
                          else if (cmd.name === 'Off') idOff = cmd.id;
                        });

                        const selectedOption = EQLOGICS.find(option => option.value === id);
                        const array = {
                          size: tile.getAttribute('data-state'),
                          type: 'onOff',
                          idEvent: id,
                          options: {
                            on: 0,
                            title: selectedOption.text,
                            value: null,
                            icons: {
                              on: { type: "jeedomapp", name: "ampoule-on", color: "#f7d959" },
                              off: { type: "jeedomapp", name: "ampoule-off", color: "#a4a4a3" }
                            },
                            actions: {
                              on: { id: idOn },
                              off: { id: idOff }
                            },
                            iconBlur: false
                          }
                        };

                        const jsonString = JSON.stringify(array);
                        tileElement.setAttribute('data-array', jsonString);
                        tileElement.classList.add('on');
                        resolve(array);
                      },
                      error: reject
                    });
                  }
                });
              }
            });
      });
};




  //   const bootBoxGenericTypeFunction = (model, choiceCmd, tileElement) => {
  //     return new Promise((resolve, reject) => {
  //         var idOn;
  //         var idOff;
  //         $.ajax({
  //           type: 'POST',
  //           url: 'plugins/mobile/core/ajax/mobile.ajax.php',
  //           data: {
  //             action: 'getEqlogicByGenericType',
  //             model: 'LIGHT_COLOR'
  //           },
  //           dataType: 'json',
  //           error: function(request, status, error) {
  //             handleAjaxError(request, status, error);
  //           },
  //           success: function(data) {
  //             var EQLOGICS = [];
  //             data.result.forEach(function(cmd) {
  //               EQLOGICS.push({text: cmd.name, value: cmd.id});
  //             });
  //             bootbox.prompt({
  //                 title: "Choisir l equipement",
  //                 inputType: 'select',
  //                 inputOptions: EQLOGICS,
  //                 callback: function(id) {
  //                   if (id == null) {
  //                     return
  //                   } 
  //                   console.log('ajax')
  //                   $.ajax({
  //                     type: 'POST',
  //                     url: 'plugins/mobile/core/ajax/mobile.ajax.php',
  //                     data: {
  //                       action: 'getCmdsByValues',
  //                       id: id
  //                     },
  //                     dataType: 'json',
  //                     error: function(request, status, error) {
  //                       handleAjaxError(request, status, error);
  //                       reject(error);
  //                     },
  //                     success: function(data) {
  //                             data.result.forEach(function(cmd) {
  //                                 if(cmd.name == 'On'){
  //                                     idOn = cmd.id
  //                                 }else if(cmd.name == 'Off'){
  //                                     idOff = cmd.id
  //                                 }
  //                             });

  //                             var selectedOption = EQLOGICS.find(function(option) {
  //                                 return option.value == id;
  //                               });
  //                                 var array = {
  //                                     'size': tile.getAttribute('data-state'),
  //                                     'type': 'onOff',
  //                                     'idEvent': id,
  //                                     'options': {
  //                                         'on': 0,
  //                                         'title': selectedOption.text,
  //                                         'value': null,
  //                                         'icons': {
  //                                             'on': {'type' : "jeedomapp", 'name' : "ampoule-on", 'color' : "#f7d959"},
  //                                             'off': {'type' : "jeedomapp", 'name' : "ampoule-off", 'color' : "#a4a4a3"}
  //                                         },
  //                                         'actions': {
  //                                             'on': {'id': idOn},
  //                                             'off': {'id': idOff}
  //                                         },
  //                                         'iconBlur': false
  //                                     }
  //                                 }   
  //                                 var jsonString = JSON.stringify(array);
  //                                 tileElement.setAttribute('data-array', jsonString); 
  //                                 tileElement.classList.add('on');
  //                                 resolve(array)
  //                           }
  //                   })
  //                 }
  //             });
  //           }
  //         });
  //   });
  // }

    tile.addEventListener('mouseup', cancelLongClick);
    tile.addEventListener('mouseleave', cancelLongClick);

    function cancelLongClick() {
      if (timer !== null) {
        clearTimeout(timer);
        timer = null;
      }
    }


})







  </script>


<?php
  include_file('3rdparty', 'animate/animate', 'css');
include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');

?>