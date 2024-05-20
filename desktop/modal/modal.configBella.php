<?php

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

?>
<div class="resumeBtn" style="display:flex;justify-content:center;">
     <button class="btn btn-success" id="validView">Valider la vue</button>
</div>

<div class="gridPage">
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
  <div class="tile customTile" id="3">

  </div>
  <div class="tile customTile" id="4">

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
<div>

<style>

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
      var tileConfig = {
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

// document.getElementById('validView').addEventListener('click', function(event) {
//     event.preventDefault();
//     var tiles = document.querySelectorAll('.tile');
//     var config = [];
//     tiles.forEach(function(tile) {
//       var tileConfig = {
//         size: tile.getAttribute('data-state'),
//         type: 'info',
//         options: {
//           on: 0,
//           title: "Lumière Salon",
//           value: null,
//           icons: {
//             on: {
//               type: "jeedomapp",
//               name: "ampoule-off",
//               color: "#00ff00"
//             },
//             off: {
//               type: "jeedomapp",
//               name: "ampoule-off",
//               color: "#a4a4a3"
//             }
//           },
//           iconBlur: false
//         }
//       };
//     });


// });


var tiles = document.querySelectorAll('.tile');

tiles.forEach(function(tile) {

  tile.dataset.state = null;

  tile.addEventListener('click', function(event) {

      tile.classList.remove('1', 'dual', 'quadral');

      if (tile.dataset.state === '1') {
        tile.dataset.state = 'dual';
      } else if (tile.dataset.state === 'dual') {
        tile.dataset.state = 'quadral';
      } else {
        tile.dataset.state = '1';
      }

      tile.classList.add(tile.dataset.state);
  });


  var timer = null;


  tile.addEventListener('mousedown', function(event) {
     let idTile = tile.id;
      timer = setTimeout(function() {
        var MODELS_CHOICE = [ {text :'Info', value:'Info'}, 
                              {text :'Meteo', value:'Meteo'}, 
                              {text :'Lumière', value:'OnOff'}
                            ];
          bootbox.prompt({
            title: "{{Choisir le type de template à appliquer}}",
            inputType: 'select',
            inputOptions: MODELS_CHOICE,
            callback: function(model) {
              if (model == null) {
                return
              }

              if(model == 'OnOff'){
                var idOn;
                var idOff;
                $.ajax({
                  type: 'POST',
                  url: 'plugins/mobile/core/ajax/mobile.ajax.php',
                  data: {
                    action: 'getEqlogicByGenericType',
                    model: 'LIGHT_COLOR'
                  },
                  dataType: 'json',
                  error: function(request, status, error) {
                    handleAjaxError(request, status, error);
                  },
                  success: function(data) {
                    var EQLOGICS = [];
                    data.result.forEach(function(cmd) {
                      EQLOGICS.push({
                        text: cmd.name,
                        value: cmd.id
                      });
                    });
                    bootbox.prompt({
                        title: "Choisir l equipement",
                        inputType: 'select',
                        inputOptions: EQLOGICS,
                        callback: function(id) {
                          if (id == null) {
                            return
                          } 
                          $.ajax({
                            type: 'POST',
                            url: 'plugins/mobile/core/ajax/mobile.ajax.php',
                            data: {
                              action: 'getCmdsByValues',
                              id: id
                            },
                            dataType: 'json',
                            error: function(request, status, error) {
                              handleAjaxError(request, status, error);
                            },
                            success: function(data) {
                              data.result.forEach(function(cmd) {
                               if(cmd.name == 'On'){
                                  idOn = cmd.id
                               }else if(cmd.name == 'Off'){
                                  idOff = cmd.id
                               }
                              });

                              var selectedOption = EQLOGICS.find(function(option) {
                                  return option.value == id;
                                });
                                  var array = {
                                      'size': tile.getAttribute('data-state'),
                                      'type': 'onOff',
                                      'idEvent': id,
                                      'options': {
                                          'on': 0,
                                          'title': selectedOption.text,
                                          'value': null,
                                          'icons': {
                                              'on': {'type' : "jeedomapp", 'name' : "ampoule-on", 'color' : "#f7d959"},
                                              'off': {'type' : "jeedomapp", 'name' : "ampoule-off", 'color' : "#a4a4a3"}
                                          },
                                          'actions': {
                                              'on': {'id': idOn},
                                              'off': {'id': idOff}
                                          },
                                          'iconBlur': false
                                      }
                                  }    
                                  
                                  console.log('arraySLELELE', array)
                                  title.classList.add('on');
                                  


                            }
                          })
             
                          
                        }
                    });
                  }
                });
              }

            }
          })
      }, 1000);
    });

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
include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');
?>