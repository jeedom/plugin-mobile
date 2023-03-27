<?php
require_once __DIR__ . '/../../../../core/php/core.inc.php';

include_file('core', 'authentification', 'php');

if (!isConnect())
{
    throw new Exception('401 Unauthorized');
}

?>
<html>
<head>
<link rel="stylesheet" href="/../../../../mobile/css/mobile.main.css">
</head>

    <div class="containerPanel">
          <div class="containerItem">
            <h6>Parametrer Icone 1</h6>
            <i class="icon jeedomapp-in" style="font-size:6em;color:white;"></i>
          </div>
          <div class="containerItem">
            <h6>Parametrer Icone 2</h6>
            <a class="iconePush ui-btn ui-btn-raised" style="margin: 5px;">
              <i class="jeedomapp-in" style="font-size: 12em;"></i><br /><span style="color:#94ca02;">{{ICONE 1}}</span></a>
          </div>
          <div class="containerItem">
            <h6>Parametrer Icone 3</h6>
            <a href="#" class="iconePush link ui-btn ui-btn-raised clr-primary waves-effect waves-button" data-page="test" data-icone="3" data-title="{{Testmenu}}" data-role="button" data-plugin="mobile"
              style="margin: 5px;">
              <i class="icon jeedom2-bright4" style="font-size: 6em;"></i><br />{{ICONE 3}}</a>
          </div>
          <div class="containerItem">
            <h6>Parametrer Icone 4</h6>
            <p>IMG<P>
          </div>
    </div>

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

<script src="core/php/getJS.php?file=plugins/mobile/core/js/mobile.class.js"></script>
<script src="core/php/getJS.php?file=plugins/mobile/mobile/js/mobile.js"></script>
<script src="core/php/getJS.php?file=core/js/appMobile.class.js"></script>

<?php
	include_file('desktop', 'mobile', 'js', 'mobile');
	include_file('core', 'plugin.template', 'js');
?>
