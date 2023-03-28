<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

if (!isConnect('admin'))
{
    throw new Exception('401 Unauthorized');
}
$sliderId = $_GET["cmdId"];
$eqId = $_GET["eqId"];
$cmdSlider = cmd::byId($sliderId);
if(is_object($cmdSlider)){
  
  $nameCmd = $cmdSlider->getName();
  $cmdInfo = cmd::byId($cmdSlider->getValue());
  $valueActual = $cmdInfo->execCmd();

}else{
   echo '<div style="color:red; font-weight:30px;">ERREUR SUR LA COMMANDE</div>';
   return;

}


sendVarToJs('valeurActuel',$valueActual);
sendVarToJs('cmdId',$sliderId);
  

  echo '<div class="cmdName">'.$nameCmd.'</div>';
  echo '<div class="value cmd cmd-widget cursor" data-cmd_id="">'.$valueActual.'</div>';
  echo '<div id="slider" class="ui-widget-content ui-corner-all">';
  echo '<div class="flexTest"><input class="testDiv slider_bar" type="range" min="0" max="100" step="1" value="'.$valueActual.'" data-cmd_id=""></div>';


?>
<style>

body {
  font-family: "Roboto", Helvetica, Arial, sans-serif; 
  background: #ecf0f1;
  color: #34495e;
  padding-top: 60px;
    overflow-y: hidden !important;
 

}


.value {

  text-align: center;
  font-weight: bold;
  color: #94CA02 !important;
  font-size: 10em;
  width: 300px; 
  height: 100px;
  line-height: 60px;
  margin: 40px auto;
  letter-spacing: -.07em;

}




.cmdName{
  
 top:10px;
 left:10px;
 font-size: 1em; 
   color: #94CA02 !important;
}


.flexTest{
display:flex;
justify-content:center;
align-items:center;
align-content:center; 
  width:100%;
  
}



input.slider_bar {
  //-webkit-appearance: none;
  height: 100px;
  width: 100%;
  margin: 0;
  outline: none;
  border-radius: 30px !important;
  background :#94CA02 !important;
  overflow: hidden;
  transform-origin:100px 100px;
  -webkit-box-reflect : below 10px linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,.5)) !important;

}

.slider_bar::-webkit-slider-thumb {
  -webkit-appearance:none;
  width: 20px;
  height: 40px;
  border-radius: 20px;
  margin-right: 5px !important;
  background: #fff;
  box-shadow: -100vw 0 0 100vw #94CA02 !important;
}


</style>
  

    
    
<?php include_file('desktop', 'mobile', 'js', 'mobile'); ?> 
<?php include_file('desktop', 'widgetSlider', 'js', 'mobile'); ?>