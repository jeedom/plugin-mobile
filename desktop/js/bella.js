
var arrayInfos;
var arrayObjects;
var carouselHtml;



function initializeData(arrayInfosData, arrayObjectsData, carouselHtmlData) {

    if (typeof arrayInfosData === 'string') {
        arrayInfos = JSON.parse(arrayInfosData);
    } else {
        arrayInfos = arrayInfosData;
    }
    if (typeof arrayObjectsData === 'string') {
        arrayObjects = JSON.parse(arrayObjectsData);
    } else {
        arrayObjects = arrayObjectsData;
    }
    carouselHtml = carouselHtmlData;

    console.log(arrayInfos, arrayObjects, carouselHtml);

    window.arrayInfos = arrayInfos;
    window.arrayObjects = arrayObjects;
    window.carouselHtml = carouselHtml;

   starting_script();
}

var associatedImagesHardware = {
    "Luna": "https://www.jeedom.com/background/background-Luna2.jpg",
    "Atlas": "https://www.jeedom.com/background/background12.png",
    "Smart": "https://www.jeedom.com/background/background9-4.jpg",
    "diy": "https://www.jeedom.com/background/background9-4.jpg"
};


var currentImageIndex  = 0;
var currentImageIndex2 = 0;

function starting_script() {
    if (!arrayInfos || arrayInfos.length === 0) {
        console.error('arrayInfos est indéfini ou vide');
        return;
    }

    if (currentImageIndex < 0 || currentImageIndex >= arrayInfos.length) {
        console.error('currentImageIndex est hors des limites de arrayInfos');
        return;
    }

    var imgBg = arrayInfos[currentImageIndex]['imageBg'];
    var name = arrayInfos[currentImageIndex]['name'];
    var bellaHtml = arrayInfos[currentImageIndex]['bellaHtml'];
    var idBella = arrayInfos[currentImageIndex]['idBella'];
    document.getElementById('bella-container').innerHTML = bellaHtml;
    let carousels = document.querySelectorAll('#bella-container .carousels');
    carousels.forEach(function(carousel) {
        carousel.innerHTML = carouselHtml;
    });
    mainScript();   
}

function changeImageCarousel(newIndex, divContainerCarousels, tileId) {
    currentImageIndex = newIndex;
    var imgBg = arrayInfos[currentImageIndex]['imageBg'];
    var name  = arrayInfos[currentImageIndex]['name'];
    var specificDiv = divContainerCarousels.querySelector(`#${tileId}`);
    specificDiv.querySelector('.carousel-image').style.backgroundImage = "url('" + imgBg + "')";
    specificDiv.querySelector('.box-name').textContent = name ? name : 'Objet sans nom';

    updateDots(specificDiv, tileId, name);
    if(name == 'Objets de votre Jeedom'){
        changeImageCarousel2(2, divContainerCarousels, tileId);
      }else{
          changeImageCarousel2(newIndex, divContainerCarousels, tileId);
      }
}

function changeImageCarousel2(newIndex, divContainerCarousels, tileId) {
    currentImageIndex2 = newIndex;
    var imgBg = arrayObjects[currentImageIndex2]['imageBg'];
    var name  = arrayObjects[currentImageIndex2]['name'];

    var specificDiv = divContainerCarousels.querySelector(`#${tileId}`);
    specificDiv.querySelector('.carousel2-image').style.backgroundImage = "url('" + imgBg + "')";
    specificDiv.querySelector('.box-name2').textContent = name ? name : 'Objet sans nom';

    updateDots2(specificDiv, tileId);
   // var bellaHtml = arrayObjects[currentImageIndex2]['bellaHtml'];
    // document.getElementById('carousel2-image').style.backgroundImage = "url('" + imgBg + "')";
    // document.getElementById('box-name2').textContent = name ? name : 'Objet sans nom';
   // updateDots2();
}


function updateDots(specificDiv, tileId, name) {
   
    var dotsContainer = specificDiv.querySelector('.carousel-dots');
    var divContainerCarousels = document.getElementById('containerCarousels');
    dotsContainer.innerHTML = '';
    for (var i = 0; i < arrayInfos.length; i++) {
        var dot = document.createElement('span');
        dot.className = 'carousel-dot' + (i === currentImageIndex ? ' active' : '');
        dot.addEventListener('click', (function(index) {
            return function() {
              changeImageCarousel(index, divContainerCarousels, tileId);
            };
        })(i));
        dotsContainer.appendChild(dot);
    }
}

function updateDots2(specificDiv, tileId) {
    var dotsContainer2 = specificDiv.querySelector('.carousel2-dots');
    var divContainerCarousels = document.getElementById('containerCarousels');
    dotsContainer2.innerHTML = '';
    for (var i = 0; i < arrayObjects.length; i++) {
        var dot = document.createElement('span');
        dot.className = 'carousel-dot' + (i === currentImageIndex2 ? ' active' : '');
        dot.addEventListener('click', (function(index) {
            return function() {
              changeImageCarousel2(index, divContainerCarousels, tileId);
            };
        })(i));
        dotsContainer2.appendChild(dot);
    }
}

//changeImage(0);
//starting_script();
//changeImage2(0);
// updateDots();
// updateDots2();

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

  if (typeof longClickOccurred === 'undefined') {
        var longClickOccurred = false;
    }
    if (typeof timer === 'undefined') {
        var timer;
    }

    // var btnClose = jeeDialog.get('#configBella', 'title').querySelector('button.btClose')
    //   btnClose.addEventListener('click', function() {
    //     location.reload()
    // });


    var tiles = document.querySelectorAll('.tile');

    var colors = ["#94ca02", "#9fcf1b", "#a9d535", "#b4da4e", "#bfdf67", "#cae581", "#d4ea9a", "#dfefb3", "#eaf4cc", "#f4fae6"];

    function getRandomColor() {
        var randomIndex = Math.floor(Math.random() * colors.length);
        return colors[randomIndex];
    }



    const createConfigTile = (tileElement, idTile, randomColor, MODELS_CHOICE) => {
        let carousels = tileElement.querySelector('.carousels');
        let configTileDiv = document.createElement('div');
        let bgDiv = document.createElement('div');
        bgDiv.classList.add('bgDiv');
        bgDiv.setAttribute('data-id', idTile);
        bgDiv.setAttribute('style', 'background-color:' + randomColor + ';');
        configTileDiv.appendChild(bgDiv);
        configTileDiv.classList.add('configTileDiv');
        //configTileDiv.setAttribute('style', 'position: relative;overflow: hidden;padding-left:10px;margin-bottom:10px;width:100% !important;height:100px;background-color:#ffffff;display:flex;border-radius:10px !important;');
        configTileDiv.setAttribute('id', 'configTileDiv'+idTile);
        configTileDiv.setAttribute('data-id', idTile);
        var splitIdTile = idTile.split('_');
        var numberTile = splitIdTile[0];
        configTileDiv.style.order = numberTile; 
        let firstSection = document.createElement('div');
        let label = document.createElement('label');
        label.innerHTML = 'Choisir le type de template à appliquer';
        firstSection.appendChild(label);
        firstSection.classList.add('firstSection')
       // firstSection.setAttribute('style', 'width:20% !important;display:flex;flex-direction:column;justify-content:center !important;');
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

tiles.forEach(function(tile) {

  tile.dataset.state = null;

  tile.addEventListener('mouseup', function(event) {

    clearTimeout(timer); 
    let carousels = tile.querySelector('.carousels');
    //carousels.style.display = "flex";
    let carouselFirstConfigElement = tile.querySelector('.carouselFirstConfig');
    let carouselObjectsElement = tile.querySelector('.carouselObjects');
    carouselFirstConfigElement.classList.toggle('hiddenElement');
    carouselFirstConfigElement.classList.toggle('visibleElement');

    carouselObjectsElement.classList.toggle('hiddenElement');
    carouselObjectsElement.classList.toggle('visibleElement');
    let divContainerCarousels = document.getElementById('containerCarousels');

    if (!longClickOccurred && tile.classList.contains('selected')){
        tile.classList.remove('1', 'dual', 'quadral');

        if (tile.dataset.state === '1') {
            tile.dataset.state = 'dual';
        } else if (tile.dataset.state === 'dual') {
            tile.dataset.state = 'quadral';
        } else {
            tile.dataset.state = '1';
        }

        tile.classList.add(tile.dataset.state);
    }else if (!tile.classList.contains('selected')) {
        tiles.forEach(function(otherTile) {
            if (otherTile !== tile) {
              let idClone = otherTile.getAttribute('id');
              let carouselsClone = divContainerCarousels.querySelector(`#carousels${idClone}`);
              if(carouselsClone){
                carouselsClone.style.display = "none";
              }
                otherTile.classList.remove('selected');
                otherTile.dataset.state = null;
            }
        });
        tile.classList.add('selected');
        // if (carousels) {
        //   let carouselClone = carousels.cloneNode(true);
        //   let existingClone = divContainerCarousels.querySelector('#' + carouselClone.id);


        //   if (!existingClone) {
        //       let carouselClone = carousels.cloneNode(true);
        //       carouselClone.style.display = "flex";
        //       divContainerCarousels.appendChild(carouselClone);
        //       changeImage(0, divContainerCarousels, carouselClone.id) 
        //       // updateDots(divContainerCarousels, carouselClone.id);
        //   }else{
        //     existingClone.style.display = "flex";
        //   }

        // }

        if (carousels) {
              let existingClone = divContainerCarousels.querySelector('#' + carousels.id);

              if (existingClone) {
                  existingClone.classList.remove('fade-out');
                  setTimeout(() => {
                      existingClone.style.display = "flex";
                      existingClone.classList.add('fade-in');
                  }, 20);
              } else {
                  let carouselClone = carousels.cloneNode(true);
                  carouselClone.style.display = "none"; 
                  divContainerCarousels.appendChild(carouselClone);
                  changeImageCarousel(0, divContainerCarousels, carouselClone.id) 
                 // changeImageCarousel2(0, divContainerCarousels, carouselClone.id)
                  // updateDots(divContainerCarousels, carouselClone.id);

                  setTimeout(() => {
                      carouselClone.style.display = "flex";
                      carouselClone.classList.add('fade-in');
                  }, 20);
              }
          }

    }
});


  tile.addEventListener('mousedown', function(event) {
    longClickOccurred = false;
     let idTile = tile.id;
     let tileElement = this;

     if (tile.classList.contains('selected')) {
      timer = setTimeout(function() {
        longClickOccurred = true; 
        let existingConfigTileDiv = document.getElementById('configTileDiv' + idTile);
        if (existingConfigTileDiv){
          existingConfigTileDiv.remove();
          tileElement.style.setProperty("background-color", 'white', "important");
          return;
        }  

       
 
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
    } 
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




