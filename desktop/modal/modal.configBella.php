<?php

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$arrayInfos = array();
$bellaHtml = file_get_contents(__DIR__ . '/../../core/data/jsonBella/jsonObject_default.html');
$arrayInfos[] = array(
  'name' => 'Equipements',
  'imageBg' => 'core/img/background/jeedom_abstract_04_light.jpg',
   'bellaHtml' => $bellaHtml);
$arrayInfos[] = array(
    'name' => 'Objets',
    'imageBg' => 'core/img/background/jeedom_abstract_04_light.jpg',
    'bellaHtml' => $bellaHtml);




$arrayObjects = array();
foreach(jeeObject::all() as $object){
  $objetArray = utils::o2a($object);
  $pathHtmlBella = __DIR__ . '/../../core/data/jsonBella/';
  if(!is_dir($pathHtmlBella)){
    mkdir($pathHtmlBella, 0777, true);
  }
  $pathHtmlBella .= 'jsonObject_'.$objetArray['id'].'.html';
  if(file_exists($pathHtmlBella)){
    $bellaHtml= file_get_contents($pathHtmlBella);
  }else{
    $bellaHtml = file_get_contents(__DIR__ . '/../../core/data/jsonBella/jsonObject_default.html');
  }
  $arrayObjects[] = array(
        'nameBox' => $objetArray['name'],
        'imageBg' => $objetArray['img'] ? $objetArray['img'] : 'core/img/background/jeedom_abstract_04_light.jpg',
        'idObject' => $objetArray['id'],
        'bellaHtml' => $bellaHtml);
}



?>


<div class="resumeBtn" style="display:flex;justify-content:flex-end;">
     <button class="btn btn-success" id="validView" style="border-radius:20px !important;padding-left:5px !important;padding-right:5px !important;margin-bottom:10px;">Valider la vue</button>
</div>

<div id="carousels" style="height:30vh;margin-bottom:2vh;width:100%;display:none;flex-direction:row;">

<div id="carousel" style="height:20vh;margin-bottom:2vh;width:400px;">
    <div id="box-name" style="position:absolute;top:10vh;width:150px;color:white;font-size:20px;font-weight:bold;z-index:1;background-color:#B5DA4E;padding-left:5px;"></div>
    <div id="carousel-image" style="height:20vh;background-image: url('https://www.jeedom.com/background/background-Luna2.jpg');background-size: cover; background-position: center;"></div> 
    <div id="carousel-dots"></div>
</div>

<div id="carousel2" style="height:20vh;margin-bottom:2vh;width:400px;margin-left:20px;display:none;">
    <div id="box-name2" style="position:absolute;top:10vh;width:150px;color:white;font-size:20px;font-weight:bold;z-index:1;background-color:#B5DA4E;padding-left:5px;"></div>
    <div id="carousel2-image" style="height:20vh;background-image: url('https://www.jeedom.com/background/background-Luna2.jpg');background-size: cover; background-position: center;"></div> 
    <div id="carousel2-dots"></div>
</div>



</div>

<div id="main" style="display:flex;flex-direction:row;">
    <div style="display:flex;flex-direction:column;" id="bella-container">
        <!-- <div class="gridPage" style="width:400px; height:100vh;">
          <div class="tile  customTile" id="1">
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
          <div class="tile customTile" id="3" data-title="Météo">

          </div>
          <div class="tile customTile" id="4" data-title="Prise Chambre">

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

        </div> -->
    </div>
    <div id="rightContent" style="width:100%;height;100vh; display:flex;flex-direction:column;align-items:center;">

  </div>

</div>



<script>


var arrayInfos = <?php echo json_encode($arrayInfos); ?>;
var arrayObjects = <?php echo json_encode($arrayObjects); ?>;
console.log(arrayObjects.length);
// console.log('arrayObjects', arrayObjects);
// console.log('arrayInfos', arrayInfos);

var associatedImagesHardware = {
    "Luna": "https://www.jeedom.com/background/background-Luna2.jpg",
    "Atlas": "https://www.jeedom.com/background/background12.png",
    "Smart": "https://www.jeedom.com/background/background9-4.jpg",
    "diy": "https://www.jeedom.com/background/background9-4.jpg"
};


var currentImageIndex = 0;
var currentImageIndex2 = 0;

// function changeImage(newIndex) {
//     currentImageIndex = newIndex;
//     var imgBg = arrayInfos[currentImageIndex]['imageBg'];
//     var nameBox = arrayInfos[currentImageIndex]['nameBox'];
//     var bellaHtml = arrayInfos[currentImageIndex]['bellaHtml'];
//     var idObject = arrayInfos[currentImageIndex]['idObject'];
//     document.getElementById('carousel-image').style.backgroundImage = "url('" + imgBg + "')";
//     document.getElementById('box-name').textContent = nameBox ? nameBox : 'Objet sans nom';
//     bellaHtml = bellaHtml.replace(/(\d+)_defaut/g, "$1_" + idObject);
//     document.getElementById('bella-container').innerHTML = bellaHtml;
//     document.getElementById('rightContent').innerHTML = "";
//     mainScript();
//     updateDots();
// }

function changeImage(newIndex) {
    currentImageIndex = newIndex;
    var imgBg = arrayInfos[currentImageIndex]['imageBg'];
    var name = arrayInfos[currentImageIndex]['name'];
    var bellaHtml = arrayInfos[currentImageIndex]['bellaHtml'];
    if(name == 'Objets'){
      document.getElementById('carousel2').style.display = "block";
    }else{
      document.getElementById('carousel2').style.display = "none";
    }
    document.getElementById('carousel-image').style.backgroundImage = "url('" + imgBg + "')";
    document.getElementById('box-name').textContent = name ? name : 'Objet sans nom';
    document.getElementById('bella-container').innerHTML = bellaHtml;
    document.getElementById('rightContent').innerHTML = "";
    mainScript();
    updateDots();
}

function changeImage2(newIndex) {
    currentImageIndex2 = newIndex;
    var imgBg = arrayObjects[currentImageIndex2]['imageBg'];
    var name = arrayObjects[currentImageIndex2]['name'];
    var bellaHtml = arrayObjects[currentImageIndex2]['bellaHtml'];
    document.getElementById('carousel2-image').style.backgroundImage = "url('" + imgBg + "')";
    document.getElementById('box-name2').textContent = name ? name : 'Objet sans nom';
    updateDots2();
}


function updateDots() {
    var dotsContainer = document.getElementById('carousel-dots');
    dotsContainer.innerHTML = '';
    for (var i = 0; i < arrayInfos.length; i++) {
        var dot = document.createElement('span');
        dot.className = 'carousel-dot' + (i === currentImageIndex ? ' active' : '');
        dot.addEventListener('click', (function(index) {
            return function() {
                changeImage(index);
            };
        })(i));
        dotsContainer.appendChild(dot);
    }
}

function updateDots2() {
    var dotsContainer2 = document.getElementById('carousel2-dots');
    dotsContainer2.innerHTML = '';
    for (var i = 0; i < arrayObjects.length; i++) {
        var dot = document.createElement('span');
        dot.className = 'carousel-dot' + (i === currentImageIndex2 ? ' active' : '');
        dot.addEventListener('click', (function(index) {
            return function() {
                changeImage2(index);
            };
        })(i));
        dotsContainer2.appendChild(dot);
    }
}

changeImage(0);
changeImage2(0);
updateDots();
updateDots2();

if (typeof AJAX_URL === 'undefined') {
    const AJAX_URL = 'plugins/mobile/core/ajax/mobile.ajax.php';
}

document.getElementById('validView').addEventListener('click', function(event) {
    event.preventDefault();
    var tiles = document.querySelectorAll('.tile');
    var config = [];
    tiles.forEach(function(tile) {
      let idTile = tile.id;
      let sizeAttribute = tile.getAttribute('data-state');
      // console.log(tile);
      // console.log(tile.classList); 
      if (!sizeAttribute || sizeAttribute == undefined || sizeAttribute == "null") {
          if (tile.classList.contains('dual')) {
              sizeAttribute = 2;
          } else if (tile.classList.contains('quadral')) {
              sizeAttribute = 4;
          } else {
              sizeAttribute = 1;
          }
      }
      console.log('sizeAttribute', sizeAttribute)
      var tileConfig;
      if (tile.hasAttribute('data-array')) {
        tileConfig = JSON.parse(tile.getAttribute('data-array'));
      } else {
         tileConfig = {
          size: sizeAttribute ,
          type: 'info',
          options: {
            on: 0,
            title: tile.getAttribute('data-title'),
            value: null,
            icons: {
              on: {
                type: "jeedomapp",
                name: tile.getAttribute('data-icon-on'),
                color: "#00ff00"
              },
              off: {
                type: "jeedomapp",
                name: tile.getAttribute('data-icon-off'),
                color: "#a4a4a3"
              }
            },
            iconBlur: false
          }
        };
    }
      if (!config[idTile]) {
        config[idTile] = [];
      }
      config[idTile].push(tileConfig);
    });
 

    $.ajax({
      type: 'POST',
      url: 'plugins/mobile/core/ajax/mobile.ajax.php',
      data: {
        action: 'createJsonBellaMobile',
        config: config
      },
      dataType: 'json',
      error: function(request, status, error) {
        handleAjaxError(request, status, error);
      },
      success: function(data) {
        if (data.state != 'ok') {
          $('#div_alert').showAlert({message: data.result, level: 'danger'});
          return;
        }
        $('#div_alert').showAlert({message: '{{Configuration sauvegardée}}', level: 'success'});
      }
    });
  
});

function mainScript() {

  var btnClose = jeeDialog.get('#configBella', 'title').querySelector('button.btClose')
    btnClose.addEventListener('click', function() {
      location.reload()
  });


var tiles = document.querySelectorAll('.tile');

var colors = ["#94ca02", "#9fcf1b", "#a9d535", "#b4da4e", "#bfdf67", "#cae581", "#d4ea9a", "#dfefb3", "#eaf4cc", "#f4fae6"];

function getRandomColor() {
    var randomIndex = Math.floor(Math.random() * colors.length);
    return colors[randomIndex];
}


const createConfigTile = (tileElement, idTile, randomColor, MODELS_CHOICE) => {
  console.log('idTile', idTile)
    let configTileDiv = document.createElement('div');
    let bgDiv = document.createElement('div');
    bgDiv.classList.add('bgDiv');
    bgDiv.setAttribute('data-id', idTile);
    bgDiv.setAttribute('style', 'background-color:' + randomColor + ';');
    configTileDiv.appendChild(bgDiv);
   //configTileDiv.setAttribute('style', 'position: relative;overflow: hidden;padding-left:10px;margin-bottom:10px;width:100% !important;height:100px;background-color:' + randomColor + ';display:flex;border-radius:10px !important;');
    configTileDiv.setAttribute('style', 'position: relative;overflow: hidden;padding-left:10px;margin-bottom:10px;width:100% !important;height:100px;background-color:#ffffff;display:flex;border-radius:10px !important;');
    configTileDiv.setAttribute('id', 'configTileDiv'+idTile);
    configTileDiv.classList.add('configTileDiv');
    configTileDiv.setAttribute('data-id', idTile);
    var splitIdTile = idTile.split('_');
    var numberTile = splitIdTile[0];
    configTileDiv.style.order = numberTile; 
    let firstSection = document.createElement('div');
    let label = document.createElement('label');
    label.innerHTML = 'Choisir le type de template à appliquer';
    firstSection.appendChild(label);
    firstSection.classList.add('firstSection')
    firstSection.setAttribute('style', 'width:20% !important;display:flex;flex-direction:column;justify-content:center !important;');
    let firstSelect = document.createElement('select');
    firstSelect.setAttribute('id', 'firstSelect');
    firstSelect.setAttribute('style', 'margin-bottom: 10px;');
    configTileDiv.appendChild(firstSection);

    // ADD ANIMATIONS HOVER
    configTileDiv.addEventListener('mouseover', function() {
        var associatedTile = configTileDiv.getAttribute('data-id')
        if (associatedTile) {
          var associatedElement = document.getElementById(associatedTile);
          associatedElement.style.zIndex = '10'; 
          associatedElement.classList.add('bounceAndScale');
          associatedElement.addEventListener('animationend', function() {
              this.classList.remove('bounceAndScale');
          });
          let bgDiv = document.querySelector(`.bgDiv[data-id="${associatedTile}"]`);
         bgDiv.style.transform = 'scale(20)';
        }
    });
  // REMOVE ANIMATIONS OUT HOVER
    configTileDiv.addEventListener('mouseout', function() {
      var associatedTile = configTileDiv.getAttribute('data-id')
      if (associatedTile) {
        var associatedElement = document.getElementById(associatedTile);
        associatedElement.style.transform = 'scale(1.0)';
        associatedElement.style.zIndex = '1'; 
        let bgDiv = document.querySelector(`.bgDiv[data-id="${associatedTile}"]`);
         bgDiv.style.transform = 'scale(1)';
      }
   });

  tileElement.addEventListener('mouseover', function() {
        let tileId = tileElement.getAttribute('id');
        let bgDiv = document.querySelector(`.bgDiv[data-id="${tileId}"]`);
        bgDiv.style.transform = 'scale(20)';
        tileElement.style.transform = 'scale(1.1)';
    });
    tileElement.addEventListener('mouseout', function() {
        let tileId = tileElement.getAttribute('id');
        let bgDiv = document.querySelector(`.bgDiv[data-id="${tileId}"]`);
        bgDiv.style.transform = 'scale(1)';
        tileElement.style.transform = 'scale(1.0)';
    });
            
  MODELS_CHOICE.forEach(function(model) {
    let option = document.createElement('option');
    option.value = model.value;
    option.text = model.text;
    firstSelect.appendChild(option);
  });
  firstSection.appendChild(firstSelect);
  document.getElementById('rightContent').appendChild(configTileDiv);
  }






if (typeof longClickOccurred === 'undefined') {
    var longClickOccurred = false;
}
if (typeof timer === 'undefined') {
    var timer;
}


tiles.forEach(function(tile) {

  tile.dataset.state = null;

  tile.addEventListener('mouseup', function(event) {
    clearTimeout(timer); 

    if (!longClickOccurred) {
        tile.classList.remove('1', 'dual', 'quadral');

        if (tile.dataset.state === '1') {
            tile.dataset.state = 'dual';
        } else if (tile.dataset.state === 'dual') {
            tile.dataset.state = 'quadral';
        } else {
            tile.dataset.state = '1';
        }

        tile.classList.add(tile.dataset.state);
    }
});


  tile.addEventListener('mousedown', function(event) {
    longClickOccurred = false;
     let idTile = tile.id;
     let tileElement = this;


      timer = setTimeout(function() {
        longClickOccurred = true; 
        let existingConfigTileDiv = document.getElementById('configTileDiv' + idTile);
        if (existingConfigTileDiv){
          existingConfigTileDiv.remove();
          tileElement.style.setProperty("background-color", 'white', "important");
          return;
        }  

        document.getElementById('carousels').style.display = "flex";
 
        let randomColor = getRandomColor();
        tileElement.style.setProperty("background-color", randomColor, "important");
        var MODELS_CHOICE = [ {text :'Info', value:'Info'}, 
                              {text :'Meteo', value:'Meteo'}, 
                              {text :'Lumière', value:'OnOff'}
                            ];
            createConfigTile(tileElement, idTile, randomColor, MODELS_CHOICE);
            return;

          bootbox.prompt({
            title: "{{Choisir le type de template à appliquer}}",
            inputType: 'select',
            inputOptions: MODELS_CHOICE,
            className: 'slideInUp animated',
            // className: 'bootBoxClass',
            
            callback: function(model) {
              if (model == null) {
                return
              }
              var CHOICE_TYPECMD_TOSEARCH = [ {text :'Manuellement', value:'manualSearch'}, {text :'Generic Type', value:'genericType'}];

              bootbox.prompt({
                title: "{{Choisir une commande manuellement ou via les generics types ? }}",
                inputType: 'select',
                inputOptions: CHOICE_TYPECMD_TOSEARCH,
                className: 'slideInDown animated',
                callback: function(choiceCmd) {
                  if (choiceCmd == null) {
                    return
                  }
                  if(choiceCmd == 'genericType'){
                    if(model == 'OnOff'){
                      bootBoxGenericTypeFunction(model, choiceCmd, tileElement).then(returnBootBox => {
                            console.log('returnBootBox', returnBootBox);
                        }).catch(error => {
                            console.error('Une erreur est survenue :', error);
                        });                
                    }

                  }else{
                    bootBoxAllCmds(model, choiceCmd, tileElement)
                  }
                }})



            }
          })
      }, 1000);
      // tile.addEventListener('mouseup', function() {
      //   clearTimeout(timer); 
      // });
    });


   

    const ajaxConfig = (action, data) => ({
      type: 'POST',
      url: AJAX_URL,
      data: {
        action,
        ...data
      },
      dataType: 'json',
      error: (request, status, error) => handleAjaxError(request, status, error)
    });


  const bootBoxAllCmds = (tmodel, choiceCmd, tileElement) => {
      jeedom.eqLogic.getSelectModal({}, function(eqLogic) {
         jeedom.eqLogic.getCmd({
            id: eqLogic.id,
            typeCmd : 'info',
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_search', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function(result) {
              const ALLCMDS = result.map(cmd => ({ text: cmd.name, value: cmd.id }));
              bootbox.prompt({
                  title: "Choisir la commande",
                  inputType: 'select',
                  inputOptions: ALLCMDS,
                  callback: idCmd => {
                    if (idCmd == null) return;
                      var _icon = false
                     jeedomUtils.chooseIcon(function(_icon) {
                      var iconElement = tileElement.querySelector('.iconTile');
                      var newIconClasses = _icon.match(/class='([^']*)'/)[1].split(' ');
                      iconElement.className = 'iconTile';
                      newIconClasses.forEach(function(className) {
                          if (className !== 'iconTile') {
                              iconElement.classList.add(className);
                          }
                      });
                        jeeFrontEnd.modifyWithoutSave = true
                      }, { icon: _icon })
                  }
                })
            }
          })
      })
    // return new Promise((resolve, reject) => {

    // });
    
  }


  const bootBoxGenericTypeFunction = (model, choiceCmd, tileElement) => {
      return new Promise((resolve, reject) => {
            let idOn, idOff;

            $.ajax({
              ...ajaxConfig('getEqlogicByGenericType', { model: 'LIGHT_COLOR' }),
              success: data => {
                const EQLOGICS = data.result.map(cmd => ({ text: cmd.name, value: cmd.id }));

                bootbox.prompt({
                  title: "Choisir l equipement",
                  inputType: 'select',
                  inputOptions: EQLOGICS,
                  callback: id => {
                    if (id == null) return;

                    $.ajax({
                      ...ajaxConfig('getCmdsByValues', { id }),
                      success: data => {
                        data.result.forEach(cmd => {
                          if (cmd.name === 'On') idOn = cmd.id;
                          else if (cmd.name === 'Off') idOff = cmd.id;
                        });

                        const selectedOption = EQLOGICS.find(option => option.value === id);
                        const array = {
                          size: tile.getAttribute('data-state'),
                          type: 'onOff',
                          idEvent: id,
                          options: {
                            on: 0,
                            title: selectedOption.text,
                            value: null,
                            icons: {
                              on: { type: "jeedomapp", name: "ampoule-on", color: "#f7d959" },
                              off: { type: "jeedomapp", name: "ampoule-off", color: "#a4a4a3" }
                            },
                            actions: {
                              on: { id: idOn },
                              off: { id: idOff }
                            },
                            iconBlur: false
                          }
                        };

                        const jsonString = JSON.stringify(array);
                        tileElement.setAttribute('data-array', jsonString);
                        tileElement.classList.add('on');
                        resolve(array);
                      },
                      error: reject
                    });
                  }
                });
              }
            });
      });
};


    tile.addEventListener('mouseup', cancelLongClick);
    tile.addEventListener('mouseleave', cancelLongClick);

    function cancelLongClick() {
      if (timer !== null) {
        clearTimeout(timer);
        timer = null;
      }
    }


})


}




  </script>


<?php

include_file('desktop', 'configBella', 'css', 'mobile');
include_file('3rdparty', 'animate/animate', 'css');
include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');

?>