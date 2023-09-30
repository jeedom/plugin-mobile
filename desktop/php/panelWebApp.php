<?php

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

?>

<div class="principal">
  <div class="principal">
    <h6 class="greenApp" style="font-size:2em">{{Paramétrer Icône 1}}</h6>
    <i class="icon jeedomapp-in" style="font-size:6em;color:white;"></i>
  </div>
  <div class="principal">
    <h6>{{Paramétrer Icône 2}}</h6>
    <a class="iconePush" style="margin: 5px;">
      <i class="jeedomapp-in" style="font-size: 12em;"></i><br /><span style="color:#94ca02;">{{ICÔNE 1}}</span></a>
  </div>
  <div class="principal">
    <h6>{{Paramétrer Icône 3}}</h6>

  </div>
  <div class="principal">
    <h6>{{Paramétrer Icône4}}</h6>
    <p>IMG
    <P>
  </div>
</div>


<style>
  .containerPanel {
    display: flex;
    flex-direction: column;
    font-family: 'Raleway';
    align-items: center;
    justify-content: space-evenly;
    height: 100%;
    width: 100%;
    background-color: #2C2C2C;
    color: #94CA02;
    height: 100%;
  }



  .containerItem {
    display: flex;
    font-size: 30px;
    background-color: #3C3C3C;
    border-radius: 30px;
    elevation: 5;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    height: 20%;
    width: 80%;
  }
</style>



<?php
include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');
?>