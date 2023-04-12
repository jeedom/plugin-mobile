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
