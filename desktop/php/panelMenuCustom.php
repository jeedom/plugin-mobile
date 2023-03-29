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
$eqLogics = mobile::byType('mobile');


if(isset($_GET["Iq"])){
  $mobile = eqLogic::byLogicalId($_GET["Iq"], 'mobile');
  if(is_object($mobile)){
      $arrayConfigs = array('Icones' => [],'SelectName' => [],'RenameIcon' => [],'UrlUser' => []);
      $nbIcones = $mobile->getConfiguration('nbIcones');
      echo  '<div class="firstDiv" style="display:flex;align-items:center;">';
      echo  '<div class="greenApp title" style="margin-top:2%;margin-bottom:2%;font-size:10px;font-family:Raleway;">NOMBRE ICONES</div>';
      echo  '<select class="nbIconesPanel" onInput="userIconSelectPanel()">';
      echo        '<option value=1>1</option>';
      echo        '<option value=2>2</option>';
      echo        '<option value=3>3</option>';
      echo        '<option value=4>4</option>';
      echo  '</select>';
      echo  '</div>';
     ?>
      <script>
        document.querySelector('.nbIconesPanel').value = <?= $nbIcones; ?>
      </script>

     <?php
      echo  '<div class="greenApp title" style="display:flex;justify-content:center;margin-top:10%;margin-bottom:10%;font-size:20px;font-family:Raleway;">MENU ACTUEL</div>';
      echo  '<div class="mainContainer" eqId="'.$mobile->getId().'" nbIconesPanel="'.$nbIcones.'" style="display:flex;justify-content:space-around">';
      $j=0;
      for($i=1;$i<5;$i++){
           $selectName = $mobile->getConfiguration('selectNameMenu'.$i , 'home');
           $selectEntire  = $mobile->getConfiguration('selectNameMenu'.$i , 'home');
           if(strpos($selectName, 'panel') != false || strpos($selectName, 'dashboard') != false){
              $arrayReturn = explode('_', $selectName);
              $selectName = $arrayReturn[1];
           }
          echo '<div id="numElPanel'.$i.'" selectName="'.$selectName.'" selectNameEntire="'.$selectEntire.'" numElPanel="'.$i.'" class="menuSelectPanel icon'.$i.'" style="display:flex;flex-direction:column;align-items:center;width: 25%;" touchstart="test()">
          <i class="spanIconPanel whiteApp '.$mobile->getConfiguration('spanIcon'.$i , 'icon jeedomapp-in').'" iconName="'.$mobile->getConfiguration('spanIcon'.$i , 'Default').'" id="spanIconPanel'.$i.'" style="font-size:60px;"></i>
          <span class="whiteApp actualsMenuNameUser" id="actualMenuNameUser'.$i.'" style="font-size:10px;">'.$mobile->getConfiguration('renameIcon'.$i , 'Exemple').'</span>
          </div>';


      /*  array_push($arrayConfigs['Icones'], );
        array_push($arrayConfigs['SelectName'], $mobile->getConfiguration('selectNameMenu'.$i , 'none'));
        array_push($arrayConfigs['RenameIcon'], $mobile->getConfiguration('renameIcon'.$i , 'none'));
        array_push($arrayConfigs['UrlUser'], $mobile->getConfiguration('urlUser'.$i , 'none'));
        */
        $j++;

      }
      for($j=1;$j<5;$j++){
            if($j > $nbIcones){
              ?>
              <script>
              console.log('--TOUR---')
              let elemSelectPanelInitial = document.querySelector('.menuSelectPanel[numElPanel="<?= $j; ?>"]');
              if(elemSelectPanelInitial){
                elemSelectPanelInitial.style.display = "none";
              }

              </script>




              <?php

            }
      }
        echo  '</div>';

?>
<div style="display:flex;flex-direction:column;align-items:center;">
  <div class="panelModal" id="panelModal1" style="display:none;margin-top:10vh;">
                               <div class="" style="display:flex;flex-direction:column;z-index:5;">
                                              <div class="menusSelectors" style="margin-bottom:2%;width:80vw;">
                                                      <label>{{Type}}</label>
                                                      <select id="typeMenu1" class="form-control selectMenuMobile" onInput="userSelectPanel('typeMenu1')">;
                                                      <option value="none" disabled selected>{{Choisir un Type}}</option>
                                                      <option value="home">{{Accueil}}</option>
                                                      <option value="overview">{{Synthese}}</option>
                                                      <option value="dashboard">{{Dashboard}}</option>
                                                      <option value="view">{{Vue}}</option>
                                                      <option value="plan">{{Design}}</option>
                                                      <option value="panel">{{Panel}}</option>
                                                      <option value="url">{{URL externe}}</option>
                                                      <option value="health">{{Santé}}</option>
                                                      <option value="timeline">{{TimeLine}}</option>
                                                      </select>
                                              </div>
                                              <div id="divnameMenu1" style="margin-bottom:5%;">
                                                   <select class="form-control selectObject item_dash" id="item_dashboard1" style="display:none;width:80vw;">;
                                                         <option value="none">Aucun</option>
                                                          <?php
                                                          $objects = jeeObject::all();
                                                          foreach ($objects as $object)
                                                          {
                                                              $obArray = utils::o2a($object);
                                                              echo '<option value="' . $obArray['id'] . '_dashboard">' . $obArray['name'] . '</option>';
                                                          }
                                                          ?>
                                                    </select>
                                                   <select class="form-control selectObject item_dash"  id="item_view1" style="display:none;width:80vw;">;
                                                         <option value="none">Aucun</option>
                                                          <?php
                                                          $views = view::all();
                                                          foreach ($views as $view)
                                                          {
                                                              $obArray = utils::o2a($view);
                                                              echo '<option value="' . $obArray['id'] . '_view">' . $obArray['name'] . '</option>';
                                                          }
                                                          ?>
                                                    </select>
                                                    <select class="form-control selectObject item_dash"  id="item_panel1" style="display:none;width:80vw;">;
                                                           <option value="none">Aucun</option>
                                                            <?php $pluginsPanel = plugin::listPlugin();
                                                              foreach ($pluginsPanel as $plugin)
                                                              {
                                                                  $obArray = utils::o2a($plugin);
                                                                  if ($obArray['mobile'] != '')
                                                                  {
                                                                      echo '<option value="' . $obArray['id'] . '_panel">' . $obArray['name'] . '</option>';
                                                                  }
                                                              }
                                                              ?>

                                                      </select>
                                                      <select class="form-control selectObject item_dash" id="item_plan1" style="display:none;width:80vw;">;
                                                            <option value="none">Aucun</option>
                                                             <?php $panels = planHeader::all();
                                                              foreach ($panels as $panel)
                                                              {
                                                                  $obArray = utils::o2a($panel);
                                                                  echo '<option value="' . $obArray['id'] . '_plan">' . $obArray['name'] . '</option>';
                                                              }
                                                              ?>
                                                      </select>
                                                       <input class="form-control urlUser" id="urlUser1" type=text style="display:none;width:80vw;" placeholder="url perso"/>
                                                  </div>
                                                  <div class="renameDivClass" id="renameIcon1" style="margin-bottom:2%;width:80vw;">
                                                        <label>Renommer Icone</label>
                                                      <input class="form-control inputUser inputPanel" id="inputUser1" type=text maxlength="15" value="<?= $mobile->getConfiguration('renameIcon1' , 'none');?>"/>
                                                  </div>
                                                  <div id="divIconMenu1" style="display:flex;justify-content:center;margin-bottom:5%;">
                                                      <button class="btn-chooseIcon-panel" id="bt_chooseIconMenu1" type="button">Choisir Icone</button>
                                                          <span class="spanIcon" id="spanIcon1"></span>
                                                 </div>

                                  </div>
             </div>
             <div class="panelModal" id="panelModal2" style="display:none;margin-top:10vh;">
                             <div class="" style="display:flex;flex-direction:column;z-index:5;">
                                            <div class="menusSelectors" style="margin-bottom:2%;width:80vw;">
                                                    <label>{{Type}}</label>
                                                    <select id="typeMenu2" class="form-control selectMenuMobile" onInput="userSelectPanel('typeMenu2')">;
                                                    <option value="none" disabled selected>{{Choisir un Type}}</option>
                                                    <option value="home">{{Accueil}}</option>
                                                    <option value="overview">{{Synthese}}</option>
                                                    <option value="dashboard">{{Dashboard}}</option>
                                                    <option value="view">{{Vue}}</option>
                                                    <option value="plan">{{Design}}</option>
                                                    <option value="panel">{{Panel}}</option>
                                                    <option value="url">{{URL externe}}</option>
                                                    <option value="health">{{Santé}}</option>
                                                    <option value="timeline">{{TimeLine}}</option>
                                                    </select>
                                            </div>
                                            <div id="divnameMenu2" style="margin-bottom:5%;">
                                                 <select class="form-control selectObject item_dash" id="item_dashboard2" style="display:none;width:80vw;">;
                                                       <option value="none">Aucun</option>
                                                        <?php
                                                        $objects = jeeObject::all();
                                                        foreach ($objects as $object)
                                                        {
                                                            $obArray = utils::o2a($object);
                                                            echo '<option value="' . $obArray['id'] . '_dashboard">' . $obArray['name'] . '</option>';
                                                        }
                                                        ?>
                                                  </select>
                                                 <select class="form-control selectObject item_dash"  id="item_view2" style="display:none;width:80vw;">;
                                                       <option value="none">Aucun</option>
                                                        <?php
                                                        $views = view::all();
                                                        foreach ($views as $view)
                                                        {
                                                            $obArray = utils::o2a($view);
                                                            echo '<option value="' . $obArray['id'] . '_view">' . $obArray['name'] . '</option>';
                                                        }
                                                        ?>
                                                  </select>
                                                  <select class="form-control selectObject item_dash"  id="item_panel2" style="display:none;width:80vw;">;
                                                         <option value="none">Aucun</option>
                                                          <?php $pluginsPanel = plugin::listPlugin();
                                                            foreach ($pluginsPanel as $plugin)
                                                            {
                                                                $obArray = utils::o2a($plugin);
                                                                if ($obArray['mobile'] != '')
                                                                {
                                                                    echo '<option value="' . $obArray['id'] . '_panel">' . $obArray['name'] . '</option>';
                                                                }
                                                            }
                                                            ?>

                                                    </select>
                                                    <select class="form-control selectObject item_dash" id="item_plan2" style="display:none;width:80vw;">;
                                                          <option value="none">Aucun</option>
                                                           <?php $panels = planHeader::all();
                                                            foreach ($panels as $panel)
                                                            {
                                                                $obArray = utils::o2a($panel);
                                                                echo '<option value="' . $obArray['id'] . '_plan">' . $obArray['name'] . '</option>';
                                                            }
                                                            ?>
                                                    </select>
                                                     <input class="form-control urlUser" id="urlUser2" type=text style="display:none;width:80vw;" placeholder="url perso"/>
                                                </div>
                                                <div class="renameDivClass" id="renameIcon2" style="margin-bottom:2%;width:80vw;">
                                                      <label>Renommer Icone</label>
                                                    <input class="form-control inputUser inputPanel" id="inputUser2" type=text maxlength="15"/>
                                                </div>
                                                <div id="divIconMenu2" style="display:flex;justify-content:center;margin-bottom:5%;">
                                                            <button class="btn-chooseIcon-panel" id="bt_chooseIconMenu2" type="button">Choisir Icone</button>
                                                        <span class="spanIcon" id="spanIcon2"></span>
                                               </div>
                                </div>
           </div>
           <div class="panelModal" id="panelModal3" style="display:none;margin-top:10vh;">
                             <div class="" style="display:flex;flex-direction:column;z-index:5;">
                                            <div class="menusSelectors" style="margin-bottom:2%;width:80vw;">
                                                    <label>{{Type}}</label>
                                                    <select id="typeMenu3" class="form-control selectMenuMobile" onInput="userSelectPanel('typeMenu3')">;
                                                    <option value="none" disabled selected>{{Choisir un Type}}</option>
                                                    <option value="home">{{Accueil}}</option>
                                                    <option value="overview">{{Synthese}}</option>
                                                    <option value="dashboard">{{Dashboard}}</option>
                                                    <option value="view">{{Vue}}</option>
                                                    <option value="plan">{{Design}}</option>
                                                    <option value="panel">{{Panel}}</option>
                                                    <option value="url">{{URL externe}}</option>
                                                    <option value="health">{{Santé}}</option>
                                                    <option value="timeline">{{TimeLine}}</option>
                                                    </select>
                                            </div>
                                            <div id="divnameMenu3" style="margin-bottom:5%;">
                                                 <select class="form-control selectObject item_dash" id="item_dashboard3" style="display:none;width:80vw;">;
                                                       <option value="none">Aucun</option>
                                                        <?php
                                                        $objects = jeeObject::all();
                                                        foreach ($objects as $object)
                                                        {
                                                            $obArray = utils::o2a($object);
                                                            echo '<option value="' . $obArray['id'] . '_dashboard">' . $obArray['name'] . '</option>';
                                                        }
                                                        ?>
                                                  </select>
                                                 <select class="form-control selectObject item_dash"  id="item_view3" style="display:none;width:80vw;">;
                                                       <option value="none">Aucun</option>
                                                        <?php
                                                        $views = view::all();
                                                        foreach ($views as $view)
                                                        {
                                                            $obArray = utils::o2a($view);
                                                            echo '<option value="' . $obArray['id'] . '_view">' . $obArray['name'] . '</option>';
                                                        }
                                                        ?>
                                                  </select>
                                                  <select class="form-control selectObject item_dash"  id="item_panel3" style="display:none;width:80vw;">;
                                                         <option value="none">Aucun</option>
                                                          <?php $pluginsPanel = plugin::listPlugin();
                                                            foreach ($pluginsPanel as $plugin)
                                                            {
                                                                $obArray = utils::o2a($plugin);
                                                                if ($obArray['mobile'] != '')
                                                                {
                                                                    echo '<option value="' . $obArray['id'] . '_panel">' . $obArray['name'] . '</option>';
                                                                }
                                                            }
                                                            ?>

                                                    </select>
                                                    <select class="form-control selectObject item_dash" id="item_plan3" style="display:none;width:80vw;">;
                                                          <option value="none">Aucun</option>
                                                           <?php $panels = planHeader::all();
                                                            foreach ($panels as $panel)
                                                            {
                                                                $obArray = utils::o2a($panel);
                                                                echo '<option value="' . $obArray['id'] . '_plan">' . $obArray['name'] . '</option>';
                                                            }
                                                            ?>
                                                    </select>
                                                     <input class="form-control urlUser" id="urlUser3" type=text style="display:none;width:80vw;" placeholder="url perso"/>
                                                </div>
                                                <div class="renameDivClass" id="renameIcon3" style="margin-bottom:2%;width:80vw;">
                                                      <label>Renommer Icone</label>
                                                    <input class="form-control inputUser inputPanel" id="inputUser3" type=text maxlength="15"/>
                                                </div>
                                                <div id="divIconMenu1" style="display:flex;justify-content:center;margin-bottom:5%;">
                                                             <button class="btn-chooseIcon-panel" id="bt_chooseIconMenu3" type="button">Choisir Icone</button>
                                                        <span class="spanIcon" id="spanIcon3"></span>
                                               </div>
                                </div>
           </div>
           <div class="panelModal" id="panelModal4" style="display:none;margin-top:10vh;">
                             <div class="" style="display:flex;flex-direction:column;z-index:5;">
                                            <div class="menusSelectors" style="margin-bottom:2%;width:80vw;">
                                                    <label>{{Type}}</label>
                                                    <select id="typeMenu4" class="form-control selectMenuMobile" onInput="userSelectPanel('typeMenu4')">;
                                                    <option value="none" disabled selected>{{Choisir un Type}}</option>
                                                    <option value="home">{{Accueil}}</option>
                                                    <option value="overview">{{Synthese}}</option>
                                                    <option value="dashboard">{{Dashboard}}</option>
                                                    <option value="view">{{Vue}}</option>
                                                    <option value="plan">{{Design}}</option>
                                                    <option value="panel">{{Panel}}</option>
                                                    <option value="url">{{URL externe}}</option>
                                                    <option value="health">{{Santé}}</option>
                                                    <option value="timeline">{{TimeLine}}</option>
                                                    </select>
                                            </div>
                                            <div id="divnameMenu4" style="margin-bottom:5%;">
                                                 <select class="form-control selectObject item_dash" id="item_dashboard4" style="display:none;width:80vw;">;
                                                       <option value="none">Aucun</option>
                                                        <?php
                                                        $objects = jeeObject::all();
                                                        foreach ($objects as $object)
                                                        {
                                                            $obArray = utils::o2a($object);
                                                            echo '<option value="' . $obArray['id'] . '_dashboard">' . $obArray['name'] . '</option>';
                                                        }
                                                        ?>
                                                  </select>
                                                 <select class="form-control selectObject item_dash"  id="item_view4" style="display:none;width:80vw;">;
                                                       <option value="none">Aucun</option>
                                                        <?php
                                                        $views = view::all();
                                                        foreach ($views as $view)
                                                        {
                                                            $obArray = utils::o2a($view);
                                                            echo '<option value="' . $obArray['id'] . '_view">' . $obArray['name'] . '</option>';
                                                        }
                                                        ?>
                                                  </select>
                                                  <select class="form-control selectObject item_dash"  id="item_panel4" style="display:none;width:80vw;">;
                                                         <option value="none">Aucun</option>
                                                          <?php $pluginsPanel = plugin::listPlugin();
                                                            foreach ($pluginsPanel as $plugin)
                                                            {
                                                                $obArray = utils::o2a($plugin);
                                                                if ($obArray['mobile'] != '')
                                                                {
                                                                    echo '<option value="' . $obArray['id'] . '_panel">' . $obArray['name'] . '</option>';
                                                                }
                                                            }
                                                            ?>

                                                    </select>
                                                    <select class="form-control selectObject item_dash" id="item_plan4" style="display:none;width:80vw;">;
                                                          <option value="none">Aucun</option>
                                                           <?php $panels = planHeader::all();
                                                            foreach ($panels as $panel)
                                                            {
                                                                $obArray = utils::o2a($panel);
                                                                echo '<option value="' . $obArray['id'] . '_plan">' . $obArray['name'] . '</option>';
                                                            }
                                                            ?>
                                                    </select>
                                                     <input class="form-control urlUser" id="urlUser4" type=text style="display:none;width:80vw;" placeholder="url perso"/>
                                                </div>
                                                <div class="renameDivClass" id="renameIcon4" style="margin-bottom:2%;width:80vw;">
                                                      <label>Renommer Icone</label>
                                                    <input class="form-control inputUser inputPanel" id="inputUser4" type=text maxlength="15"/>
                                                </div>
                                                <div id="divIconMenu1" style="display:flex;justify-content:center;margin-bottom:5%;">
                                                             <button class="btn-chooseIcon-panel" id="bt_chooseIconMenu4" type="button">Choisir Icone</button>
                                                        <span class="spanIcon" id="spanIcon4"></span>
                                               </div>
                                </div>
           </div>
</div>
<div style="display:flex;justify-content:center;align-items: center;">
  <button class="btn-validate-menuCustom" type="button">Valider Menu Global</button>
</div>

<?php

include_file('desktop', 'panelMenuCustom', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');
  }else{
        echo  '<div class="greenApp title" style="margin-top:50%;display:flex;flex-direction:column;justify-content:center;align-items:center;">';
        echo  '<h6 class="greenApp title">Pas de mobile enregistré sur l\'application mobile.</h6>';
        echo  '<h6 class="greenApp title">Scanner le QRCode du plugin mobile depuis l\'application</h6>';
        echo  '<h6 class="greenApp title">Version minimale du core pour etre fonctionnel : 4.4.0 </h6>';
        if(jeedom::version() >= '4.4.0'){
          echo  '<h6 class="greenApp title">Cliquer sur synchronisation de l\'application</h6>';
          echo '</br>';
          echo '</br>';
          echo '</br>';
            echo  '<button class="bt_syncBox" id="bt_syncBox" type="button">Synchronisation de la box</button>';
            ?>
             <script>
                    document.querySelector('.bt_syncBox').addEventListener('click', function() {
                      jeedom.appMobile.syncBoxs();
                      setTimeout(() => {
                        location.reload();
                      }, "3000")
                    });
              </script>
            <?php
        }
        echo  '</div>';
  }
}else{

  echo  '<div class="greenApp title" style="margin-top:50%;display:flex;flex-direction:column;justify-content:center;align-items:center;">';
  echo  '<h6 class="greenApp title">Pas de mobile enregistré sur l\'application mobile.</h6>';
  echo  '<h6 class="greenApp title">Scanner le QRCode du plugin mobile depuis l\'application</h6>';
  echo  '<h6 class="greenApp title">Version minimale du core pour etre fonctionnel : 4.4.0 </h6>';
  if(jeedom::version() >= '4.4.0'){
      echo  '<h6 class="greenApp title">Cliquer sur synchronisation de l\'application</h6>';
      echo '</br>';
      echo '</br>';
      echo '</br>';
      echo  '<button class="bt_syncBox" id="bt_syncBox" type="button">Synchronisation de la box</button>';
      ?>
       <script>
              document.querySelector('.bt_syncBox').addEventListener('click', function() {
                jeedom.appMobile.syncBoxs();
                setTimeout(() => {
                  location.reload();
                }, "3000")
              });
        </script>
      <?php
  }
  echo  '</div>';

}


 ?>

<script src="core/php/getJS.php?file=core/js/appMobile.class.js"></script>

<script>

function userIconSelectPanel(){
  let maxIcon = 4;
  let nbIconsSelect = document.querySelector('.nbIconesPanel').value;
  for(let j=1;j<5;j++){
      let elemSelectPanel = document.querySelector('.menuSelectPanel[numElPanel="'+j+'"]');
      if(j <= nbIconsSelect){
        if(elemSelectPanel){
          elemSelectPanel.style.display = "flex";
        }
      }else{
        if(elemSelectPanel){
          elemSelectPanel.style.display = "none";
        }
      }
    }
}


</script>

<style>

 input{

 }
.menusSelectors , .item_dash , .urlUser  , .inputUser{
  width:80vw !important;
}

select, input{
  height:7vh !important;
  border-radius: 17px !important;
}



.btn-validate-menuCustom , .btn-chooseIcon-panel, .bt_syncBox{
  background-color: #93C927;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  font-size: 16px;
  elevation: 10 !important;
  cursor: pointer;
  border-radius: 17px !important;
}

.btn-chooseIcon-panel{
  font-size: 12px;
}

.btn-validate-menuCustom{
  position:absolute;
  bottom : 0;
  width: 80vw;

}

.btn-validate-menuCustom:active {
  background-color: #93C927;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
}


</style>