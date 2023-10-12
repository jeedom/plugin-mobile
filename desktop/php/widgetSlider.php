

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


     echo '<section class="container">';
        echo '<div class="topNames">';
              echo '<div class="cmdName">'.$nameCmd.'</div>';
              echo '<div class="value cmd cmd-widget cursor" data-cmd_id="">'.$valueActual.'</div>';
        echo '</div>';
        echo '<div class="flexTest"><input class="testDiv slider_bar" type="range" min="0" max="100" step="1" value="'.$valueActual.'" data-cmd_id=""></div>';
     echo '</section>';




?>
<style>


   .container {
     width: 100%;
     height: 100%;
  position:absolute;
     display:flex;
     flex-direction: column;
     justify-content:space-between;
   }


.topNames{
  width: 100%;
  display:flex !important;

  justify-content:space-between !important;
  margin-top: 20px !important;

}



.value {

  font-weight: bold;
  color: #94CA02 !important;
  font-size: 5em;
  height: 100px;
  line-height: 60px;
  //margin: 40px auto;
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

  height:100%;


}



input.slider_bar {

  postion:absolute;
  top:200px;
  height: 100px;
  width: 300px;
  margin: 0;
  outline: none;
  border-radius: 30px !important;
  background :#94CA02 !important;
  overflow: hidden;
  transform: rotate(90deg);
  -webkit-box-reflect: below 10px !important;


}

.slider_bar::-webkit-slider-thumb {
  -webkit-appearance:none;
  width: 30px;
  height: 80px;
  border-radius: 20px;
  margin-right: 5px !important;
  background: #fff;
  box-shadow: -100vw 0 0 100vw #94CA02 !important;
}


</style>

<script>
var elem = document.querySelector('input[type="range"]');
var elemBis = document.querySelector('input');
var getTest = parseInt(elem.getAttribute('data-cmd_id'));
var cmdName = document.querySelector('.cmdName');



$( document ).ready(function() {

   	elem.addEventListener("touchend", function( event ) {
      jeedom.cmd.execute({id: cmdId , value: {slider: elem.value}});
    });



var rangeValue = function(){
  var newValue = elem.value;
  var target = document.querySelector('.value');
  target.innerHTML = newValue;



}

elem.addEventListener("input", rangeValue);

})



</script>


<?php include_file('desktop', 'widgetSlider', 'js', 'mobile'); ?>
