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

?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/roundSlider/1.3.2/roundslider.min.css" rel="stylesheet" />

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">



<div class="frame">
	<div id="slider" class="rslider"></div>
	<div class="thermostat">
		<div class="ring">
			<div class="bottom_overlay"></div>
		</div>
		<div class="control">
			<div class="temp_outside">23°</div>
			<div class="temp_room"><span>°</span></div>
			<div class="room">Bedroom</div>
		</div>
	</div>
	<div class="instructions">
		<h4>Drag the handle, click at the temp you want, or directly type your number in <i class="fas fa-heart"></i></h4>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
                                    
<script src="https://cdnjs.cloudflare.com/ajax/libs/roundSlider/1.3.2/roundslider.min.js"></script>

<style>
@import url('https://fonts.googleapis.com/css?family=Rubik:300,400|Raleway:300');
body {
	background: #643a7b;
}
// use only the available space inside the 400x400 frame
.frame {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 400px;
  height: 400px;
  margin-top: -200px;
  margin-left: -200px;
  border-radius: 2px;
	box-shadow: .5rem 1rem 1rem 0 rgba(0,0,0,0.6);
	overflow: hidden;
  color: #333;
	font-family: 'Rubik', Helvetica, sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
 background: #201c29;
}

.thermostat {
  position: absolute;
  width: 200px;
  height: 200px;
  top: 100px;
  left: 100px;
  background: #F2F2F2;
  border-radius: 50%;
  box-shadow: 0px 0px 1rem rgba(0, 0, 0, 0.8);
}

.thermostat .control {
  position: absolute;
  z-index: 5;
  width: 130px;
  height: 130px;
  top: 25%;
  left: 35px;
  background: #E6E6E6;
  border-radius: 50%;
  box-shadow: 0 0 1rem rgba(0, 0, 0, 0.7);
}
.thermostat .control .temp_outside {
  position: absolute;
  top: 25px;
  left: 6px;
  right: 0;
  text-align: center;
  font-weight: 300;
  font-size: 1rem;
}
.thermostat .control .temp_room {
  position: absolute;
  top: 34px;
  left: 0;
  right: 0;
  text-align: center;
  font-weight: 400;
  font-size: 60px;
  line-height: 60px;
  color: #873183;
  letter-spacing: -8px;
  padding-right: 12px;
  opacity: 1;
  transform: translateX(0);
  transition: all .5s ease-in-out;
}
.thermostat .control .temp_room span {
  position: absolute;
  top: 0;
  right: 37px;
  font-size: 2rem;
  line-height: 34px;
  padding: 3px 0 0 7px;
	color: #8e2275;
}
.room {
  position: absolute;
  bottom: 18px;
  left: 0;
  right: 0;
  text-align: center;
  font-weight: 300;
  font-size: 1rem;
}
.thermostat .ring {
  position: absolute;
  width: 180px;
  height: 180px;
  top: 10px;
  left: 10px;
  background: url("http://100dayscss.com/codepen/thermostat-gradient.jpg") center center no-repeat;
  border-radius: 50%;
  box-shadow: inset 2px 4px 4px 0px rgba(0, 0, 0, 0.3);
}
.thermostat .ring .bottom_overlay {
  position: absolute;
  width: 95px;
  height: 95px;
  top: 50%;
  left: 50%;
  background: #F2F2F2;
  transform-origin: 0 0;
  transform: rotate(45deg);
  border-radius: 0 0 95px 0;
}

#slider {
	position: absolute;
	width: 170px;
	height: 150px;
	top: 36%;
	left: 32%;
	z-index: 1000;
}

#slider .rs-border  {
    border-color: transparent;
}
.rs-control .rs-range-color, .rs-control .rs-path-color, .rs-control .rs-bg-color {
    background-color: rgba(0, 0, 0, 0);
}
.rs-control .rs-handle {
    background-color: fade-out(#522c6d, .2);
}
.rs-tooltip.edit, .rs-tooltip .rs-input, .rs-tooltip-text {
	font-family: rubik, helvetica, sans-serif;
	font-size: 3.3rem;
	background: transparent;
	color: #8e2275;
	font-weight: 400;
	top: 65%;
	height: 3.9rem;
	padding: 0 !important;
	width: 4.5rem;
}
#slider:hover .rs-tooltip, .rs-tooltip:focus, .rs-tooltip-text:focus {
	border: none;
	transform: scale(1.1);
	transition: .1s;
}
#slider .rs-transition  {
  transition-timing-function: cubic-bezier(1.000, -0.530, 0.405, 1.425);
}
.instructions {
	position: absolute;
	bottom: .5rem;
	color: fade-out(white, .75);
	font-size: 1rem;
	font-family: raleway, sans-serif;
	width: 85%;
	left: 10%;
	font-weight: 300;
	letter-spacing: .05rem;
	line-height: 1.3;
	text-align: center;
}
.fas {
	animation: pulse 1s infinite;
}
@keyframes pulse {
	50% {
		transform: scale(.9);
	}
}
      
      
      </style>
                        
                        
<script>
  $("#slider").roundSlider({
	radius: 72,
	circleShape: "half-top",
  sliderType: "min-range",
	mouseScrollAction: true,
  value: 19,
	handleSize: "+5",
	min: 10,
	max: 50
});                      
                        
</script>