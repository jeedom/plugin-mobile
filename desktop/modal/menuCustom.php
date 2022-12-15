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

?>

<table class="table table-condensed tablesorter" id="table_menuCustom">
	<thead>
		<tr>
			<th>{{Mobile}}</th>
			<th>{{ID}}</th>
            <th>{{User}}</th>
            <th>{{Menu Defaut}}</th>
			<th>{{Nb Icones}}</th>
		</tr>
	</thead>
	<tbody>
	 <?php
   $idEqlogicMobile= intval(config::byKey('checkdefaultID', 'mobile'));

foreach ($eqLogics as $eqLogic)
{
    $activeMobileId = config::byKey('checkdefaultID', 'mobile');
    $userId = $eqLogic->getConfiguration('affect_user');
    $userType = user::byId($userId);
    if (is_object($userType))
    {
        $username = $userType->getLogin();
        echo '<tr><td width="35%"><a href="' . $eqLogic->getLinkToConfiguration() . '">' . $eqLogic->getHumanName(true) . '</a></td>';
        echo '<td width="12.5%"><span class="label label-info">' . $eqLogic->getId() . '</span></td>';
        echo '<td width="12.5%"><span class="label label-info">' . $username . '</span></td>';
        if($eqLogic->getConfiguration('appVersion') == 2){
                if(intval($activeMobileId) == $eqLogic->getId()){
                  echo '<td width="5%"><input type=checkbox class="menuDefault" eqId="' . $eqLogic->getId() . '" checked></td>';
                }else{
                  echo '<td width="5%"><input type=checkbox class="menuDefault" eqId="' . $eqLogic->getId() . '" ></td>';
                }
       }
        if($eqLogic->getConfiguration('appVersion') == 2){
                 echo '<td width="10%"><select class="selectNbicones" eqId="' . $eqLogic->getId() . '" onInput="nbiconSelect('.$eqLogic->getId().')">';
                 for ($i=1; $i < 5; $i++) {
                       if($eqLogic->getConfiguration('nbIcones') == $i){
                         echo '<option value="'.$i.'" selected>'.$i.'</option>';
                      }else{
                         echo '<option value="'.$i.'">'.$i.'</option>';
                      }
                 }
                echo '</select></td>';
              echo '<td width="12.5%"><button class="btn btn-primary menuConfigBtn" eqId="' . $eqLogic->getId() . '">Configurer Menu</td>';
        }else{
          echo '<td width="12.5%"><span class="label label-warning">PAS D\'APP V2 SUR CE MOBILE</span></td>';
        }
        echo '<td width="12.5%"><button class="btn btn-success validConfigBtn"  eqId="' . $eqLogic->getId() . '" style="display:none;">Valider Menu</td></tr>';
    }
    else
    {
        echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
        echo '<td><span class="label label-info">' . $eqLogic->getId() . '</span></td>';
        echo '<td><span class="label label-info">{{Utilisateur non trouvé}}</span></td></tr>';
    }
}
?>
	</tbody>
</table>

    <div class="warningV2" style="display:none;"></div>
     <div class="containerGlobal" style="display:none;">
             <div class="containerMenu" style="display:flex;justify-content:center;margin-top:1%;">
                    <div class="phoneElements" style="display:flex;width:75%;height:10%;margin-bottom:1%;z-index:5;align-content:'center';justify-content:space-evenly;background-color:#2C2C2C;border-radius:20px;">
                                    <div class="containerArea" id="containerArea1" style="padding-top:1%;width:20%;height:10%;display:flex;justify-content:space-evenly;align-items:center;flex-direction:column;color:white;">
                                        <span class="spanIconTest" id="spanIconTest1">
                                        <i id="area1" class="icon jeedomapp-in" style="font-size:60px;"></i>
                                        </span>
                                        <p id="titleArea1" style="color:white;">Home</p>
                                     </div>
                                    <div class="containerArea"  id="containerArea2" style="padding-top:1%;width:20%;height:10%;display:flex;justify-content:space-evenly;align-items:center;flex-direction:column;color:white;">
                                       <span class="spanIconTest" id="spanIconTest2">
                                            <i id="area2" class="icon jeedomapp-dash01" style="font-size:60px;"></i>
                                      </span>
                                          <p id="titleArea2" style="color:white;">Dashboard</p>
                                     </div>
                                      <div class="containerArea"  id="containerArea3" style="padding-top:1%;width:20%;height:10%;display:flex;justify-content:space-evenly;align-items:center;flex-direction:column;color:white;">
 										 <span class="spanIconTest" id="spanIconTest3">
                                           <i id="area3"  class="icon jeedom2-bright4" style="font-size:60px;"></i>
  											</span>
                                          <p id="titleArea3" style="color:white;">Lumieres</p>
                                     </div>
                                      <div class="containerArea"  id="containerArea4" style="padding-top:1%;width:20%;height:10%;display:flex;justify-content:space-evenly;align-items:center;flex-direction:column;color:white;">
                                        <span class="spanIconTest" id="spanIconTest4">
                                           <i id="area4" class="icon jeedomapp-plugin" style="font-size:60px;"></i>
  										</span>
                                          <p id="titleArea4" style="color:white;">Synthese</p>
                                     </div>
                    </div>
            </div>
       <div class="container">
            <div class="panelCustomMenuMobile active" id="panelIcon1">
                      <div class="nameIconNoActive" id="nameIconNoActive1">
                          <h6 class="iconh6" style="text-transform:uppercase;color:#93ca02;font-weight:bold;font-size:24px;">icone</h6>
                          <h6  class="iconh6Num" style="color:#93ca02;font-weight:bold;font-size:24px;">1</h6>
                      </div>
                         <div class="panelContainer panelBootstrap active" id="panelContainer" style="display:flex; justify-content:center; width:100%;align-items:center;">
                                    <div class="panel panel-success " id="panel1" style="width:70%;">
                                                    <h3 class="panel-title"><i class='icon jeedomapp-plugin '></i></i>{{ Icone 1}}</h3>
                                                     <div class="panel-body" style="display:flex;flex-direction:column;z-index:5;">
                                                                    <div class="menusSelectors" style="margin-bottom:2%;margin-left:5%;">
                                                                            <label>{{Type}}</label>
                                                                            <select id="typeMenu1" class="form-control selectMenuMobile" onInput="userSelect('typeMenu1')">;
                                                                            <option value="none">{{Choisir un Type}}</option>
                                                                            <option value="view">{{Vue}}</option>
                                                                            <option value="plan">{{Design}}</option>
                                                                            <option value="panel">{{Panel}}</option>
                                                                            <option value="dashboard">{{Dashboard}}</option>
                                                                            <option value="url">{{URL}}</option>
                                                                            </select>
                                                                    </div>
                                                                    <div id="divnameMenu1" style="margin-bottom:5%;margin-left:5%;">
                                                                         <select class="form-control selectObject item_dash" id="item_dashboard1" style="display:none;">;
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
                                                                         <select class="form-control selectObject item_dash"  id="item_view1" style="display:none;">;
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
                                                                          <select class="form-control selectObject item_dash"  id="item_panel1" style="display:none;">;
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
                                                                            <select class="form-control selectObject item_dash" id="item_plan1" style="display:none;">;
   																										                            <option value="none">Aucun</option>
                                                  																 <?php $panels = planHeader::all();
                                                                                    foreach ($panels as $panel)
                                                                                    {
                                                                                        $obArray = utils::o2a($panel);
                                                                                        echo '<option value="' . $obArray['id'] . '_plan">' . $obArray['name'] . '</option>';
                                                                                    }
                                                                                    ?>
                                                                            </select>
                                                                             <input class="form-control urlUser" id="urlUser1" type=text style="display:none;"placeholder="url perso"/>
                                                                        </div>
                                                                        <div class="renameDivClass" id="renameIcon1" style="margin-bottom:2%;margin-left:5%;display:none;">
                                                                              <label>Renommer Icone</label>
                                                                            <input class="form-control inputUser" id="inputUser1" type=text maxlength="15"/>
                                                                        </div>
                                                                        <div id="divIconMenu1" style="display:flex;justify-content:center;margin-bottom:5%;">
                                                                                     <a class="btn btn-default btn-sm btIconClass" id="bt_chooseIconMenu1"><i class="fas fa-flag"></i> {{Choisir Icone}}</a>
                                                                                <span class="spanIcon" id="spanIcon1"></span>
                                                                       </div>
                                                        </div>
                                   </div>
                       </div>
            </div>
            <div class="panelCustomMenuMobile" id="panelIcon2">
                        <div class="nameIconNoActive" id="nameIconNoActive2">
                            <h6 class="iconh6" style="text-transform:uppercase;color:#93ca02;font-weight:bold;font-size:24px;">icone</h6>
                            <h6  class="iconh6Num" style="color:#93ca02;font-weight:bold;font-size:24px;">2</h6>
                        </div>
                        <div class="panelContainer panelBootstrap" id="panelContainer" style="display:none; justify-content:center; width:100%;align-items:center;">
                                    <div class="panel panel-success " id="panel1" style="width:70%;">
                                           <h3 class="panel-title"><i class='icon jeedomapp-plugin '></i>{{ Icone 2}}</h3>
                                           <div class="panel-body" style="display:flex;flex-direction:column;">
                                                  <div class="menusSelectors" style="margin-bottom:2%;margin-left:5%;">
                                                                                                    <label>{{Type}}</label>
                                                                                                    <select id="typeMenu2" class="form-control selectMenuMobile" onInput="userSelect('typeMenu2')">;
                                                                                                    <option value="none" disabled selected>{{Choisir un Type}}</option>
                                                                                                    <option value="view">{{Vue}}</option>
                                                                                                    <option value="plan">{{Design}}</option>
                                                                                                    <option value="panel">{{Panel}}</option>
                                                                                                    <option value="dashboard">{{Dashboard}}</option>
                                                                                                    <option value="url">{{URL}}</option>
                                                                                                    </select>
                                                    </div>
                                                    <div id="divnameMenu2" style="margin-bottom:2%;margin-left:5%;">
                                                            <select class="form-control selectObject item_dash" id="item_dashboard2" style="display:none;">
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
                                                              <select class="form-control selectObject item_dash"  id="item_view2" style="display:none;">
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
                                                              <select class="form-control selectObject item_dash"  id="item_panel2" style="display:none;">
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
                                                              <select class="form-control selectObject item_dash" id="item_plan2" style="display:none;">
                                                                <option value="none">Aucun</option>
																												      	<?php $panels = planHeader::all();
                                                                    foreach ($panels as $panel)
                                                                    {
                                                                        $obArray = utils::o2a($panel);
                                                                        echo '<option value="' . $obArray['id'] . '_plan">' . $obArray['name'] . '</option>';
                                                                    }
                                                                    ?>
                                                              </select>
                                                              <input class="form-control urlUser" id="urlUser2" type=text style="display:none;"placeholder="url perso"/>
                                                    </div>
                                                   <div class="renameDivClass" id="renameIcon2"style="margin-bottom:2%;margin-left:5%;display:none;">
                                                             <label>Renommer Icone</label>
                                                            <input class="form-control inputUser" id="inputUser2" type=text  maxlength="15"/>
                                                   </div>
                                                    <div id="divIconMenu2" style="display:flex;justify-content:center;margin-bottom:5%;">
                                                          <a class="btn btn-default btn-sm btIconClass" id="bt_chooseIconMenu2"><i class="fas fa-flag"></i> {{Choisir Icone}}</a>
                                                          <span class="spanIcon" id="spanIcon2"></span>
                                                   </div>
                                           </div>
                                    </div>
                       </div>
            </div>
            <div class="panelCustomMenuMobile" id="panelIcon3">
                  <div class="nameIconNoActive" id="nameIconNoActive3">
                      <h6 class="iconh6" style="text-transform:uppercase;color:#93ca02;font-weight:bold;font-size:24px;">icone</h6>
                      <h6  class="iconh6Num" style="color:#93ca02;font-weight:bold;font-size:24px;">3</h6>
                  </div>
                   <div class="panelContainer panelBootstrap" id="panelContainer" style="display:none; justify-content:center; width:100%;align-items:center;">
                                    <div class="panel panel-success " id="panel1" style="width:70%;">
                                           <h3 class="panel-title"><i class='icon jeedomapp-plugin '></i>{{ Icone 3}}</h3>
                                                     <div class="panel-body" style="display:flex;flex-direction:column;">
                                                                    <div class="menusSelectors" style="margin-bottom:2%;margin-left:5%;">
                                                                            <label>{{Type}}</label>
                                                                            <select id="typeMenu3" class="form-control selectMenuMobile" onInput="userSelect('typeMenu3')">;
                                                                            <option value="none" disabled selected>{{Choisir un Type}}</option>
                                                                            <option value="view">{{Vue}}</option>
                                                                            <option value="plan">{{Design}}</option>
                                                                            <option value="panel">{{Panel}}</option>
                                                                            <option value="dashboard">{{Dashboard}}</option>
                                                                            <option value="url">{{URL}}</option>
                                                                            </select>
                                                                    </div>
                                                                    <div id="divnameMenu3" style="margin-bottom:2%;margin-left:5%;">
                                                                          <select class="form-control selectObject item_dash" id="item_dashboard3" style="display:none;">
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
                                                                          <select class="form-control selectObject item_dash"  id="item_view3" style="display:none;">
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
                                                                          <select class="form-control selectObject item_dash"  id="item_panel3" style="display:none;">
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
                                                                            <select class="form-control selectObject item_dash" id="item_plan3" style="display:none;">
                                                                               <option value="none">Aucun</option>
																													                     <?php $panels = planHeader::all();
                                                                                  foreach ($panels as $panel)
                                                                                  {
                                                                                      $obArray = utils::o2a($panel);
                                                                                      echo '<option value="' . $obArray['id'] . '_plan">' . $obArray['name'] . '</option>';
                                                                                  }
                                                                                  ?>
                                                                              </select>
                                                                           <input class="form-control urlUser" id="urlUser3" type=text style="display:none;"placeholder="url perso"/>
                                                                </div>
                                                             <div class="renameDivClass" id="renameIcon3"style="margin-bottom:2%;margin-left:5%;display:none;">
                                                                    <label>Renommer Icone</label>
                                                                  <input class="form-control inputUser" id="inputUser3" type=text  maxlength="15"/>
                                                             </div>
                                                               <div id="divIconMenu3" style="display:flex;justify-content:center;margin-bottom:5%;">
                                                                      <a class="btn btn-default btn-sm btIconClass" id="bt_chooseIconMenu3"><i class="fas fa-flag"></i> {{Choisir Icone}}</a>
                                                                      <span class="spanIcon" id="spanIcon3"></span>
                                                               </div>
                                                      </div>
                                  </div>
                 </div>
            </div>
            <div class="panelCustomMenuMobile" id="panelIcon4">
                  <div class="nameIconNoActive" id="nameIconNoActive4">
                      <h6 class="iconh6" style="text-transform:uppercase;color:#93ca02;font-weight:bold;font-size:24px;">icone</h6>
                      <h6 class="iconh6Num" style="color:#93ca02;font-weight:bold;font-size:24px;">4</h6>
                  </div>
                   <div class="panelContainer panelBootstrap" id="panelContainer" style="display:none; justify-content:center; width:100%;align-items:center;">
                                    <div class="panel panel-success " id="panel1" style="width:70%;">
                                                      <h3 class="panel-title"><i class='icon jeedomapp-plugin '></i>{{ Icone 4}}</h3>
                                                       <div class="panel-body" style="display:flex;flex-direction:column;">
                                                                <div class="menusSelectors" style="margin-bottom:2%;margin-left:5%;">
                                                                        <label>{{Type}}</label>
                                                                        <select id="typeMenu4" class="form-control selectMenuMobile" onInput="userSelect('typeMenu4')">;
                                                                        <option value="none" disabled selected>{{Choisir un Type}}</option>
                                                                        <option value="view">{{Vue}}</option>
                                                                        <option value="plan">{{Design}}</option>
                                                                        <option value="panel">{{Panel}}</option>
                                                                        <option value="dashboard">{{Dashboard}}</option>
                                                                        <option value="url">{{URL}}</option>
                                                                        </select>
                                                                </div>
                                                                <div id="divnameMenu4" style="margin-bottom:2%;margin-left:5%;">
                                                                      <select class="form-control selectObject item_dash" id="item_dashboard4" style="display:none;">
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
                                                                      <select class="form-control selectObject item_dash"  id="item_view4" style="display:none;">
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
                                                                        <select class="form-control selectObject item_dash"  id="item_panel4" style="display:none;">
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
                                                                          <select class="form-control selectObject item_dash" id="item_plan4" style="display:none;">
                                                                             <option value="none">Aucun</option>
																													                   <?php $panels = planHeader::all();
                                                                            foreach ($panels as $panel)
                                                                            {
                                                                                $obArray = utils::o2a($panel);
                                                                                echo '<option value="' . $obArray['id'] . '_plan">' . $obArray['name'] . '</option>';
                                                                            }
                                                                            ?>
                                                                           </select>
                                                                         <input class="form-control urlUser" id="urlUser4" type=text style="display:none;"placeholder="url perso"/>
                                                              </div>
                                                              <div class="renameDivClass" id="renameIcon4"style="margin-bottom:2%;margin-left:5%;display:none;">
                                                                  <label>Renommer Icone</label>
                                                                  <input class="form-control inputUser" id="inputUser4" type=text maxlength="15"/>
                                                              </div>
                                                               <div id="divIconMenu4" style="display:flex;justify-content:center;margin-bottom:5%;">
                                                                      <a class="btn btn-default btn-sm btIconClass" id="bt_chooseIconMenu4"><i class="fas fa-flag"></i> {{Choisir Icone}}</a>
                                                                      <span class="spanIcon" id="spanIcon4"></span>
                                                               </div>
                                                        </div>
                                    </div>
                  </div>
            </div>
    </div>
  </div>


<script>

function constructTableVisible(eqId){
   $('.selectMenuMobile, .item_dash, .btIconClass, .renameDivClass, .urlUser, .panelCustomMenuMobile, .spanIconTest').attr('eqId', eqId);
   nbiconSelect(eqId);
   $('.menuConfigBtn').addClass('btn-primary');
   $('.containerGlobal').css('display','block');
   $('.validConfigBtn').hide();
   $('.validConfigBtn[eqid="'+eqId+'"]').css('display','block');
   $('.menuConfigBtn[eqid="'+eqId+'"]').removeClass('btn-primary').addClass('btn-warning');
   let nbIcones = $('.selectNbicones[eqId="'+eqId+'"]').value();
   jeedom.eqLogic.byId({
     id: eqId,
     noCache:true,
     error: function(error) {
       $('#savAlert').showAlert({message: error.message, level: 'danger'});
     },
     success: function(data) {
              data = isset(data.result) ? data.result : data
                console.log(data)
                let j = 0;
                let arrayDefaultsNames = ['Home','Dashboard','Lights','Synthese'];
                let arrayDefaultsIcons = ['icon jeedomapp-in','icon jeedomapp-dash01','icon jeedom2-bright4','icon jeedomapp-plugin'];
              for(var i=1; i < nbIcones+1 ;i++){
                  $('#area'+i).attr('eqId', eqId);
                  window['defaultName'+i] = arrayDefaultsNames[j];
                  window['defaultIcon'+i] = arrayDefaultsIcons[j];
                  $('#renameIcon'+i+'[eqid="'+eqId+'"]').css('display','block');
                  let typeObject = 'dashboard',urlUser = '',selectNameChosen = 'none', renameIcon = 'renameIcon'+i,
                  selectName = 'selectNameMenu'+i,urlUserVar = 'urlUser'+i,spanIcon = 'spanIcon'+i;
                  if(isset(data.configuration[selectName])){
                        if(data.configuration[selectName] != 'none'){
                            selectNameChosen = data.configuration[selectName]
                            let arrayTest = selectNameChosen.split('_');
                            let objectId = arrayTest[0];
                            typeObject = arrayTest[1];
                        }
                  }
                  if(isset(data.configuration[renameIcon])){
                     if(data.configuration[renameIcon] != 'none'){
                         window['defaultName'+i] = data.configuration[renameIcon];
                     }

                  }
                  if(isset(data.configuration[spanIcon])){
                         if(data.configuration[spanIcon] != 'undefined' && data.configuration[spanIcon] != 'none'){
                         window['defaultIcon'+i] = data.configuration[spanIcon];
                     }
                  }
                   if(isset(data.configuration[urlUserVar])){
                        if(data.configuration[urlUserVar] != 'none'){
                            urlUser = data.configuration[urlUserVar]
                            typeObject = 'url';
                            $('#urlUser'+i+'[eqId="'+eqId+'"]').css('display','block');
                        }
                  }
                  $('#titleArea'+i).empty().value(window['defaultName'+i]);
                  $('.item_dash[id="item_'+typeObject+''+i+'"][eqId="'+eqId+'"]').css('display','block');
                  $('#renameIcon'+i+'[eqid="'+eqId+'"]').children('input:nth-child(2)').empty().value(window['defaultName'+i]);
                  $('.selectMenuMobile[eqId="'+eqId+'"][id="typeMenu'+i+'"]').value(typeObject);
                  $('.selectMenuMobile[eqId="'+eqId+'"][id="typeMenu'+i+'"] option[value='+typeObject+']').attr('selected','selected');
                  $('.item_dash[id="item_'+typeObject+''+i+'"][eqId="'+eqId+'"]').value(selectNameChosen)
                  $('.item_dash[id="item_'+typeObject+''+i+'"][eqId="'+eqId+'"] option[value="'+selectNameChosen+'"]').attr('selected','selected');
                  $('#urlUser'+i+'[eqId="'+eqId+'"]').value(urlUser);
                  $('#area'+i+'[eqid="'+eqId+'"]').attr('class',window['defaultIcon'+i]);


                 j++;
              }
     }
    });
}

$('.menuConfigBtn').off().on('click', function () {
    let eqLogicId = $(this).attr('eqId');
  console.log('menuCONFIGBTN')
    document.querySelectorAll('.selectNbicones').forEach((el) => {
      el.classList.add('hiddenEl');
   });
   document.querySelector('.selectNbicones[eqid="'+eqLogicId+'"]').classList.remove('hiddenEl');
   document.querySelector('.selectNbicones[eqid="'+eqLogicId+'"]').classList.add('visibleEl');

    if (eqLogicId != undefined) {
      constructTableVisible(eqLogicId);
    }
 });


 function displayElementsByIcons(nbIcon, eqId){
   var panels = document.querySelectorAll('.panelCustomMenuMobile');
   var containers = document.querySelectorAll('.containerArea');
   panels.forEach(panel => {
                   panel.style.display = 'none';
               })
   containers.forEach(container => {
       container.style.display = 'none';
   })
   for(let i = 1; i < parseInt(nbIcon) + 1; i++){
     $('#panelIcon'+i+'[eqId="'+eqId+'"]').css('display','flex');
     $('#containerArea'+i).css('display','flex');
   }
 }

function nbiconSelect(eqId){
  let nbIcone = $('.selectNbicones[eqid="'+eqId+'"]').value();
  displayElementsByIcons(nbIcone,eqId);

}

$('.validConfigBtn').on('click', function () {
    var arrayMenusElements;
    let eqLogicId = $(this).attr('eqId');
    let checkDefault = 'false';
    if($('.menuDefault[eqid="'+eqLogicId+'"]').prop('checked')){
      $("[class=menuDefault]").not(this).prop('checked', false);
      $('.menuDefault[eqid="'+eqLogicId+'"]').prop('checked', true);
       checkDefault = 'true';
    }
    let nbIcones = parseInt($('.selectNbicones[eqid="'+eqLogicId+'"]').value());
    switch(nbIcones){
      case 1: arrayMenusElements = [[]]; break;
      case 2: arrayMenusElements = [[],[]]; break;
      case 3: arrayMenusElements = [[],[],[]]; break;
      case 4: arrayMenusElements = [[],[],[],[]]; break;
      default : break;
    }
     var j = 0;
     for (let i = 1; i < parseInt(nbIcones) + 1; i++) {
       let objectSelected = $('.selectMenuMobile[eqId="'+eqLogicId+'"][id="typeMenu'+i+'"]').value();
       window['inputChosen'+i] =  $('#renameIcon'+i+'[eqid="'+eqLogicId+'"]').children('input:nth-child(2)').value();
       window['selectNameMenu'+i] = $('.item_dash[id="item_'+objectSelected+''+i+'"][eqId="'+eqLogicId+'"]').value();
       window['iconName'+i] = $('#spanIconTest'+i).children('i:nth-child(1)').attr('class');
       window['urlUser'+i] = $('#urlUser'+i).value();
       if(window['inputChosen'+i]  === undefined ) {
     			 window['inputChosen'+i] = 'none';
     	 }
          if(window['selectNameMenu'+i]  === undefined ) {
     			window['selectNameMenu'+i] = 'none';
     	 }
          if(window['iconName'+i]  === undefined ) {
     		window['iconName'+i] = 'none';
     	 }
           if(window['urlUser'+i]  === undefined ) {
     		window['urlUser'+i] = 'none';
     	 }
       arrayMenusElements[j].push(window['selectNameMenu'+i] , window['inputChosen'+i] , window['iconName'+i], window['urlUser'+i])
       j++;
      }
     $.ajax({
          type: "POST",
          url: "plugins/mobile/core/ajax/mobile.ajax.php",
          data: {
              action: "saveMenuEqLogics",
              eqId: eqLogicId,
              arrayMenu : arrayMenusElements,
              checkDefaultBtn : checkDefault,
              nbIcones : nbIcones
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

              $('#div_alert').showAlert({message: 'Configuration Menu Enregistrée', level: 'success'});
              $('#md_modal').dialog({title: "{{Menu Custom}}"});
              $('#md_modal').load('index.php?v=d&plugin=mobile&modal=menuCustom').dialog('open');
        }
       });


     });
</script>

<script>
    var panels = document.querySelectorAll('.panelCustomMenuMobile')
    var panelsBootstrap = document.querySelectorAll('.panelBootstrap')
    var panelContainer = document.getElementById('panelContainer');

    panels.forEach(panel => {
        panel.addEventListener('click', () => {
             removeActiveClasses()
             hidePanelsBootstrap()
             panel.classList.add('active')
             $('.nameIconNoActive').css('display', 'flex');
             let nameIconPanel = panel.querySelector('.nameIconNoActive');
             nameIconPanel.style.display = 'none';
             let panelBoot = panel.querySelector('.panelBootstrap');
             panelBoot.style.display = 'flex';
        })
        panel.addEventListener('mouseover', () => {
             let idPanel = panel.id;
             let numElement = idPanel.substr(-1, 1);
             $('.containerArea').css('color', 'white');
             $('#containerArea'+numElement).css('backgroundColor', '#93ca02');
             $('.iconh6, .iconh6Num').css('color', '#93ca02');
             let iconDiv = panel.querySelector('.iconh6');
             let iconDivNum = panel.querySelector('.iconh6Num');
             iconDiv.style.color = 'white';
             iconDivNum.style.color = 'white';
        })
        panel.addEventListener('mouseout', () => {
              let idPanel = panel.id;
              let numElement = idPanel.substr(-1, 1);
              $('.iconh6, .iconh6Num').css('color', '#93ca02');
              $('#containerArea'+numElement).css('backgroundColor', '#2C2C2C');
        })
    })

    function removeActiveClasses() {
        panels.forEach(panel => {
            panel.classList.remove('active')
        })
    }

   function hidePanelsBootstrap() {
        panelsBootstrap.forEach(panel => {
              panel.style.display = 'none';
        })

    }


</script>

<style>


.hiddenEl{
  display:none;
}

.visibleEL{
  display:block;
}


.nameIconNoActive{
  display:flex;
  flex-direction:column;
  align-items:center;
}

.container {
 display:flex;
 height: 100%;
  width: 75vw;
}

.panelContainer{
   display:flex;
   justify-content:center;
}

.panelCustomMenuMobile {
  display: flex;
  justify-content:center;
  align-items:center;
  flex-direction:column;
 width: 25%;
 border: solid #93ca02; !important;
 border-width: 0 2px;
  height: 40vh;
  border-radius: 50px;
  color: #fff;
  cursor: pointer;
  flex: 0.5;
  margin: 10px;
  position: relative;
  -webkit-transition: all 400ms ease-in;
}

.panelCustomMenuMobile:hover{
  background-color: #93ca02; !important;

}

.panelCustomMenuMobile h3 {
  font-size: 24px;
  position: absolute;
  top: 20px;
  left: 20px;
  margin: 0;
  opacity: 0.5;
}

.panelCustomMenuMobile.active {
  flex: 4;
  background-color: #93ca02; !important;
}

.btnValidate.active {
  display: block;
}

.btnValidate {
  display: none;

}

.panelCustomMenuMobile.active h3 {
  opacity: 1;
  transition: opacity 0.3s ease-in 0.4s;
}

.panelCustomMenuMobile.active h6 {
  display:none;
}

@media (max-width: 480px) {
  .container {
    width: 50vw;
  }

.panelCustomMenuMobile:nth-of-type(4),
.panelCustomMenuMobile:nth-of-type(5) {
  display: none;
}

}

</style>

<?php

include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');
