<?php

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

?>

<div class="flexPage">
  <div class="tile">
    <div class="TileUp">
      <div class="UpLeft">
        <i class="iconTile icon far fa-lightbulb"></i>
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
          <i class="iconTile on icon fas fa-lightbulb"></i>
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
.flexPage {
  display: grid;
  justify-content: center;
  grid-template-rows: 150px;
  grid-auto-rows: 150px;
  grid-template-columns: repeat(auto-fit, minmax(150px, 150px));
  row-gap: 15px;
  column-gap: 15px;
  grid-column-gap: 15px;
  
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
  /*background-color: blue;*/
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
  /*background-color: green;*/
  justify-content: center;
  align-items: center;
}
.UpRight{
  height: 100%;
  width: 50%;
  /*background-color: yellow;*/
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
  font-size: 1.1em;
  font-family: Raleway, Helvetica, sans-serif;
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
</style>



<?php
include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');
?>