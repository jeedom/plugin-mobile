<?php

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

?>
<div class="resume">
  <div class="resumeTile">
    <div class="resumeValue">
      <div class="single-chart">
        <svg width="60" height="60" class="circular-chart green">
          <path class="circle-bg"
            d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831"
          />
          <path class="circle"
            stroke-dasharray="100, 100"
            d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831"
          />
        </svg>
      </div>
      <i class="iconResume icon jeedomapp-ampoule-on"></i>
    </div>
    <div class="resumeLabel">
      <div class="title bold">Lumières</div>
      <div class="title mini">2/2 Allumées</div>
    </div>
  </div>
  <div class="resumeTile">
    <div class="resumeValue">
      <div class="single-chart">
        <svg width="60" height="60" class="circular-chart orange">
          <path class="circle-bg"
            d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831"
          />
          <path class="circle"
            stroke-dasharray="60, 100"
            d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831"
          />
        </svg>
      </div>
      <i class="iconResume icon jeedomapp-prise"></i>
    </div>
    <div class="resumeLabel">
      <div class="title bold">Prises</div>
      <div class="title mini">2/3 Allumées</div>
    </div>
  </div><div class="resumeTile">
    <div class="resumeValue">
      <div class="single-chart">
        <svg width="60" height="60" class="circular-chart red">
          <path class="circle-bg"
            d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831"
          />
          <path class="circle"
            stroke-dasharray="20, 100"
            d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831"
          />
        </svg>
      </div>
      <i class="iconResume icon jeedomapp-ouvrants"></i>
    </div>
    <div class="resumeLabel">
      <div class="title bold">Portes</div>
      <div class="title mini">1 Fermée</div>
    </div>
  </div><div class="resumeTile">
    <div class="resumeValue">
      <div class="single-chart">
        <svg width="60" height="60" class="circular-chart green">
          <path class="circle-bg"
            d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831"
          />
          <path class="circle"
            stroke-dasharray="100, 100"
            d="M18 2.0845
              a 15.9155 15.9155 0 0 1 0 31.831
              a 15.9155 15.9155 0 0 1 0 -31.831"
          />
        </svg>
      </div>
      <i class="iconResume icon jeedomapp-volet-ferme"></i>
    </div>
    <div class="resumeLabel">
      <div class="title bold">Volets</div>
      <div class="title mini">Tous Fermés</div>
    </div>
  </div>
</div>
<div class="gridPage">
  <div class="tile">
    <div class="TileUp">
      <div class="UpLeft">
        <i class="iconTile icon jeedomapp-ampoule-off"></i>
      </div>
      <div class="UpRight">

      </div>
    </div>
    <div class="TileDown">
      <div class="title bold">Lumière Salon</div>
      <div class="title">Eteinte</div>
    </div>
  </div>  

  <div class="tile on">
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
  <div class="tile">

  </div>
  <div class="tile">

  </div>
  <div class="tile dual on">

  </div>

  <div class="tile dual">

  </div>
  <div class="tile">

  </div>
  <div class="tile">

  </div>
  <div class="tile quadral on">

  </div>
  <div class="tile">

  </div>
  <div class="tile">

  </div>
  <div class="tile">

  </div>
  <div class="tile">

  </div>
  <div class="tile">

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
  jeedom.appMobile.postToApp("updateBella",
    {0: {
      options: {
        on: 1, 
        value : "Allumée",
      }
    }}
  );
setTimeout(() => {
  jeedom.appMobile.postToApp("updateBella",
    {1: {
        options: {
          on: 1, 
          value : "30% Allumée",
      }}}
  );
}, 4000);

setTimeout(() => {
  jeedom.appMobile.postToApp("updateBella",
    {2: {
          options: {
            on: 1, 
            value : "22,5°C",
          }
      }}
  );
}, 2000);

setTimeout(() => {
  jeedom.appMobile.postToApp("updateBella",
    {4: {
        options: {
          on: 1, 
          value : "1 Personne",
        }
      }}
  );
}, 1000);

setTimeout(() => {
  jeedom.appMobile.postToApp("updateBella",
    {
      5: {
        options: {
          on: 1, 
          value : "Il Pleut !",
        }
      }
    }
  );
}, 1000);


  //JEEDOM APP ENVOI BELLA : 

jeedom.appMobile.postToApp('createBella',
                          {
    0: {
      0: {
        size : 1,
        type: 'onOff',
        options: {
          on: 0, 
          title: "Prise",
          value : null,
          icons:{
            on: {type: "jeedomapp",name: "prise", color: "#f7d959"},
            off: {type: "jeedomapp",name: "prise-off", color: "#a4a4a3"}
          },
          iconBlur: false
        }
      },
      1: {
        size : 1,
        type: 'onOff',
        options: {
          on: 0, 
          title: "Lumière salon",
          value : null,
          icons:{
            on: {type: "jeedomapp",name: "ampoule-on", color: "#f7d959"},
            off: {type: "jeedomapp",name: "ampoule-off", color: "#a4a4a3"}
          },
          iconBlur: false
        }
      },
      2: {
          size : 1,
          type: 'info',
          options: {
            on:0, 
            title: "Température",
            value : null,
            icons:{
              on: {type: "jeedomapp",name: "temperature", color: "#00ff00"},
              off: {type: "jeedomapp",name: "temperature", color: "#a4a4a3"}
            },
            iconBlur: false
          }
      },
      4: {
        size : 1,
        type: 'info',
        options: {
          on: 0, 
          title: "Présence",
          value : null,
          icons:{
            on: {type: "jeedom",name: "mouvement", color: "#FF0000"},
            off: {type: "jeedom",name: "mouvement", color: "#a4a4a3"}
          },
          iconBlur: true
        }
      }
    },
    1: {
      5: {
        size : 2,
        type: 'meteo',
        options: {
          on: 0, 
          title: "Météo",
          value : null,
          icons:{
            on: {type: "jeedomapp",name: "meteo", color: "#FF0000"},
            off: {type: "jeedomapp",name: "meteo", color: "#a4a4a3"}
          },
          iconBlur: true
        }
      },
      6: {
        size : 1,
        type: 'test',
        options: {}
      },
      7: {
        size : 1,
        type: 'test',
        options: {}
      }
    },
    2: {
      8: {
        size : 4,
        type: 'test',
        options: {}
      }
    }
  });


  </script>


<?php
include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');
?>
