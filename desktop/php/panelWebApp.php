<?php

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

?>

<div class="flexPage">
  <div class="tile">

  </div>  
  <div class="tile">

  </div>
  <div class="tile">

  </div>
  <div class="tile">

  </div>
  <div class="tileDual">

  </div>
  <div class="tileQuadral">

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
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  height: 100%;
  width: 100%;
}
.tile {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 150px;
  width: 150px;
  -webkit-backdrop-filter: blur(15px); /* assure la compatibilité avec safari */
  backdrop-filter: blur(15px);
  background-color: rgba(182, 182, 182, 0.8);
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
.tileDual{
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 150px;
  width: 310px;
  -webkit-backdrop-filter: blur(15px); /* assure la compatibilité avec safari */
  backdrop-filter: blur(15px);
  background-color: rgba(182, 182, 182, 0.8);
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
.tileQuadral{
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: 310px;
  width: 310px;
  -webkit-backdrop-filter: blur(15px); /* assure la compatibilité avec safari */
  backdrop-filter: blur(15px);
  background-color: rgba(182, 182, 182, 0.8);
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
.iconTile{
  text-align: left;
  font-size: 5em;
  background-color: blue;
}
.title{
  text-align: left;
  font-size: 1.5em;
  font-weight: bold;
}
</style>



<?php
include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');
?>