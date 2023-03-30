
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



 const btnMenu = document.querySelector('.btn-validate-menuCustom');
 const menusSelectPanel = document.querySelectorAll('.menuSelectPanel');
 const mainContainer = document.querySelector('.mainContainer');
 const renamesDivs = document.querySelectorAll('.renameDivClass');
 const eqLogicId = mainContainer.getAttribute('eqId');
 const nbIconesPanel = mainContainer.getAttribute('nbIconesPanel');

 const inputsPanel = document.querySelectorAll('.inputPanel');
 const selectorsDefault = document.querySelectorAll('.selectMenuMobile');
 const btnsChooseIcon = document.querySelectorAll('.btn-chooseIcon-panel');
 const spanIconsPanel = document.querySelectorAll('.spanIconPanel');

// INIT DEFAULT DISPLAY BY EQLOGICCONFIG
 document.addEventListener("DOMContentLoaded", () => {
       let defaultDivIcon = document.querySelector('.mainContainer > div');
       defaultDivIcon.children[0].classList.remove("whiteApp");
       defaultDivIcon.children[0].classList.add("greenApp");
       let defaultSelect = defaultDivIcon.getAttribute('selectName');
       let numElPanel = defaultDivIcon.getAttribute('numElPanel');
       document.getElementById(`panelModal${numElPanel}`).style.display = 'flex';
       document.getElementById(`typeMenu${numElPanel}`).value = defaultSelect;
       for (let i = 1; i < parseInt(nbIconesPanel) + 1; i++) {
           let typeObject = document.querySelector(`.icon${i}`).getAttribute('selectName');
           console.log(typeObject)
            let entireSelect = document.querySelector(`.icon${i}`).getAttribute('selectNameEntire');
            if(entireSelect.includes(typeObject)) {
              if(typeObject != 'home' && typeObject != 'overview' && typeObject != 'health' && typeObject != 'timeline'){
                 document.querySelector(`#item_${typeObject}${i}`).style.display = 'block';
                 document.querySelector(`#item_${typeObject}${i}`).value = entireSelect;
               }
            }
       }
       selectorsDefault.forEach(el => {
           let id = el.getAttribute('id');
           let numElement = id.substr(-1, 1);
           let selectElement = document.querySelector('.icon'+numElement)?.getAttribute('selectName');
           document.getElementById(`typeMenu${numElement}`).value = selectElement;
       });
       inputsPanel.forEach(el => {
              let id = el.getAttribute('id');
              let numElement = id.substr(-1, 1);
              el.value = document.getElementById(`actualMenuNameUser${numElement}`)?.textContent;
        });
  });


// CHANGE NAMEICONES BY USER
   inputsPanel.forEach(el => {
         el.addEventListener('input', function(event) {
           let id = el.getAttribute('id');
           let numElement = id.substr(-1, 1);
           let actualMenuNameUser = document.getElementById(`actualMenuNameUser${numElement}`);
           actualMenuNameUser.innerHTML = '';
           actualMenuNameUser.innerHTML = el.value;
        });
 });


 // CHANGE NAMEICONES BY USER
    btnsChooseIcon.forEach(el => {
          el.addEventListener('click', function(event) {
                let idElement = this.getAttribute('id');
                 let numElement = idElement.substr(-1, 1);
                 var _icon = false;
                 let spanIcon = document.querySelector('#spanIconPanel'+numElement)
                 jeedomUtils.chooseIcon(function(_icon) {
                   spanIcon.innerHTML = ''
                   let iconName = spanIcon.getAttribute('iconName');
                   let arraySplits = (iconName.trim()).split(' ');
                   if(iconName !== 'undefined'){
                     for(let j=0;j < parseInt(arraySplits.length);j++){
                       spanIcon.classList.remove(arraySplits[j]);
                     }
                   }
                   spanIcon.insertAdjacentHTML("beforeend",_icon);
                    $('#spanIconPanel'+numElement).css('font-size', '60px');
                   const htmlString = _icon;
                    const classRegex = /<i\s+class=['"]([^'"]+)['"]/;
                    const matches = htmlString.match(classRegex);
                    const iconClass = matches[1];
                   spanIcon.setAttribute('iconname', iconClass);
                 }, {
                   icon: _icon
                 })
             });
  });



function saveMenu(iconesPanel, eqLogicId){
  var nbIconesPanel = parseInt(iconesPanel)
   switch(nbIconesPanel){
     case 1: var arrayMenusElements = [[]]; break;
     case 2: var arrayMenusElements = [[],[]]; break;
     case 3: var arrayMenusElements = [[],[],[]]; break;
     case 4: var arrayMenusElements = [[],[],[],[]]; break;
     default : break;
   }
    var j = 0;
    var inputChosen = {};
    var iconName = {};
    var urlUser = {};
    var selectNameMenu = {};
    for (let i = 1; i < nbIconesPanel + 1; i++) {

      let objectSelected = document.getElementById(`typeMenu${i}`).value;
      inputChosen[i] = document.getElementById(`inputUser${i}`).value;
      if (objectSelected == 'overview'){
          selectNameMenu[i] = 'overview';
      }else if(objectSelected == 'health'){
        selectNameMenu[i]  = 'health';
      }else if(objectSelected == 'home'){
        selectNameMenu[i]  = 'home';
      }else if(objectSelected == 'timeline'){
          selectNameMenu[i] = 'timeline';
     }else if(objectSelected == 'url'){
         selectNameMenu[i] = 'url';
         urlUser[i]= document.getElementById(`urlUser${i}`).value;
      }else{
         selectNameMenu[i]  = document.querySelector(`.item_dash[id=item_${objectSelected}${i}]`).value;
      }
     console.log(selectNameMenu[i])
       iconName[i] = $('#spanIconPanel'+i).attr('iconname');
       console.log(iconName[i])
      if(inputChosen[i]  === undefined ) {
          inputChosen[i] = 'none';
      }
         if(selectNameMenu[i] === undefined ) {
        selectNameMenu[i] = 'none';
      }
       if(iconName[i]  === undefined ) {
       iconName[i] = 'none';
     }
          if(urlUser[i]  === undefined ) {
       urlUser[i] = 'none';
      }
      arrayMenusElements[j].push(selectNameMenu[i], inputChosen[i]  , iconName[i], urlUser[i])
      j++;

     }
  $.ajax({
       type: "POST",
       url: "plugins/mobile/core/ajax/mobile.ajax.php",
       data: {
           action: "saveMenuEqLogics",
           eqId: eqLogicId,
           arrayMenu : arrayMenusElements,
           checkDefaultBtn : 'false',
           nbIcones : parseInt(nbIconesPanel)
       },
       dataType: 'json',
       global: false,
       error: function (request, status, error) {
           handleAjaxError(request, status, error);
       },
       success: function (data) {
           if (data.state != 'ok') {
               $('#div_alert').showAlert({message: data.result, level: 'danger'});
               return;
           }

           jeedom.appMobile.notifee('Menu Enregistre', 'Success', 2000);
           jeedom.appMobile.syncBoxs();
           jeedom.appMobile.modalClose();

     }
    });
 }


// HANDLE CHANGE NAVIGATION BY CLICK
 [].forEach.call(menusSelectPanel, function(el, i, menusSelectPanel) {
     el.addEventListener('click', function(event) {
         [].forEach.call(menusSelectPanel, function(el) {
            var numElPanel = this.getAttribute('numElPanel');
             if(el !== this) {
                el.children[0].style.color = '#FFFFFF';
                var allPanels = document.querySelectorAll(`.panelModal:not(#panelModal${numElPanel})`);
                for (var x = 0; x < allPanels.length; x++) allPanels[x].style.display = 'none';
             }else{
                 let selectElement = el.getAttribute('selectName');
                 el.children[0].style.color = '#94CA02';
                 document.getElementById(`panelModal${numElPanel}`).style.display = 'flex';
             }
         }, this);

     });
 });


btnMenu.addEventListener('click', function() {
let iconesPanel = mainContainer.getAttribute('nbIconesPanel');
 saveMenu(iconesPanel, eqLogicId)
 this.innerHTML = ''
 this.innerHTML = 'Menu Validé'
 this.style.backgroundColor =  '#3e8e41';

});

// CLICK OUTSITE BTN VALIDE
 document.addEventListener("click", function(event) {
 	if (event.target.closest(".btn-validate-menuCustom")) return;
     btnMenu.innerHTML = ''
     btnMenu.innerHTML = 'Valider Menu Global'
     btnMenu.style.backgroundColor = '#93C927'
 });

// USER CHANGE TYPE SELECTOR
 function userSelectPanel(typeMenu){
   let numElement = typeMenu.substr(-1, 1);
   let typeObject = document.getElementById(typeMenu).value;
   let selectorsObject = document.querySelectorAll('.item_dash');
   selectorsObject.forEach(selector => {
         let idSelector = selector.id;
         if(idSelector.includes(typeObject) && idSelector.includes(numElement)){
            selector.style.display = 'block';
            selector.value = 'none';
            selector.select = 'selected';
         }
         if(!idSelector.includes(typeObject) && idSelector.includes(numElement)){
            selector.style.display = 'none';
         }
   })
   if(typeObject == 'url'){
     document.querySelector('#urlUser'+numElement).style.display = 'block';
   }

 }
