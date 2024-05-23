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

  <div class="resumeTile" id="validView">
     <button class="btn btn-success">Valider la vue</button>
  </div>

</div>
<div class="gridPage">
  <div class="tile  tileConfig" id="1"> 
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


  <div class="tile" id="3">

  </div>
  <div class="tile" id="4">

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



var tiles = document.querySelectorAll('.tile');


for (var i = 0; i < tiles.length; i++) {

  tiles[i].dataset.state = '1';


  tiles[i].addEventListener('click', function(event) {

    this.classList.remove('1', 'dual', 'quadral');


    if (this.dataset.state === '1') {
      this.dataset.state = 'dual';
    } else if (this.dataset.state === 'dual') {
      this.dataset.state = 'quadral';
    } else {
      this.dataset.state = '1';
    }

    this.classList.add(this.dataset.state);
  });


  var timer = null;


    tiles[i].addEventListener('mousedown', function(event) {
      timer = setTimeout(function() {
        jeedom.cmd.getSelectModal({
                cmd: {
                  type: 'info',
                  subType: 'numeric'
                }
              }, function(result) {
                console.log('result', result)
              });
      }, 500);
    });

    tiles[i].addEventListener('mouseup', cancelLongClick);
    tiles[i].addEventListener('mouseleave', cancelLongClick);

    function cancelLongClick() {
      if (timer !== null) {
        clearTimeout(timer);
        timer = null;
      }
    }



}


document.getElementById('validView').addEventListener('click', function(event) {
    event.preventDefault();
 
});



//    document.querySelectorAll('.tileConfig').forEach(function(element) {
//       element.addEventListener('click', function() {
//             jeedom.cmd.getSelectModal({
//                 cmd: {
//                   type: 'info',
//                   subType: 'numeric'
//                 }
//               }, function(result) {
//                 console.log('result', result)
//               });
//       });
//   });


  </script>


<?php
include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');

?>