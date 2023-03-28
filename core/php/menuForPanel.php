<?php
require_once __DIR__ . '/../../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');



if (!isConnect())
{
    throw new Exception('401 Unauthorized');
}


?>

<html>
  <body>
    <div><i class="icon jeedomapp-in" style="font-size:20;"></i></div>
    <div class="containerPanel">
          <div class="containerItem">
            <h6>Parametrer Icone 1</h6>
            <i class="icon jeedomapp-in"></i>
          </div>
          <div class="containerItem">
            <h6>Parametrer Icone 2</h6>
            <i class="fa fa-cloud"></i>
          </div>
          <div class="containerItem">
            <h6>Parametrer Icone 3</h6>
            <p>IMG<P>
          </div>
          <div class="containerItem">
            <h6>Parametrer Icone 4</h6>
            <p>IMG<P>
          </div>
    </div>
  </body>
</html>





<style>

html, body {
 margin:0;
 padding:0;
 height: 100%;
 width: 100%;
 overflow: hidden;
 overflow-x: hidden;
 }

.containerPanel{
  display:flex;
  flex-direction: column;
  font-family:'Raleway';
  align-items: center;
  justify-content: space-evenly;
  height: 100%;
  width: 100%;
  background-color:#2C2C2C;
  color: #94CA02;
  height: 100%;
}


.containerItem {
  display:flex;
  font-size: 30px;
  background-color:#3C3C3C;
  border-radius: 30px;
  elevation: 5;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  height: 20%;
  width: 80%;
}


</style>
