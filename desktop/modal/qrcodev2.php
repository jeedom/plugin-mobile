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

$eqLogics = mobile::byType('mobile');

?>

<div class="panel panel-info">
				<div class="panel-heading" style="display:flex;justify-content:center;">
					<h3 class="panel-title" style="display:flex;justify-content:center;align-items:center;"><i class='icon jeedomapp-dash01' style="font-size:35px;font-weight:bold;"></i>&nbsp&nbsp&nbsp&nbsp{{AJOUT DE LA BOX SUR VOTRE APPLICATiON MOBILE }}</h3>
				</div>
				<div class="panel-body">
                    <div style="display:flex;justify-content:center;margin-top:2%;font-size:18px;">
                       <p style="font-weight:bold;">Vous pouvez choisir un utilisateur : un QRCode sera généré. Vous pourrez ensuite scanner le QRCode depuis la nouvelle application, et ainsi ajouter cette box à votre application</p>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:center;align-content:center;margin-bottom:10%;">
                          <div id="containerMainQrCode" style="height:100%;width:50%;display:flex;align-items:center;justify-content: center;margin-top:3%;">
                                <div id="contentExplain" style="display:flex; flex-direction:column; justify-content:center;align-items: center;width:50%;height:50%;">
                                        <label>{{Utilisateurs}}</label>
                                          <select style="width:250px;" class="eqLogicAttr configuration form-control" id="selectUserqrCodeV2" onInput="userSelectqrCodev2()">
                                            <option value="" disabled selected>{{Aucun}}</option>
                                            <?php
                                              foreach (user::all() as $user) {
                                                echo '<option value="' . $user->getId() . '">' . ucfirst($user->getLogin()) . '</option>';
                                              }
                                            ?>
                                          </select>
                                  </div>
                          </div>
                          <div id="qrCodecontainer" style="display:none;flex-direction:column;justify-content: center;align-items:center;align-content:center;margin-top: 5%;width:50%;">
                                              <label>{{QRCode}}</label>
                                              <div class="qrCodeImgV2" style="width:25%;display:flex;justify-content:center;"></div>
                          </div>
                    </div>

        </div>

</div>

<script>


   function userSelectqrCodev2(){
      document.getElementById('qrCodecontainer').style.display = "none";
       let chooseUser = document.getElementById('selectUserqrCodeV2').value;
          $.ajax({
        type: "POST",
        url: "plugins/mobile/core/ajax/mobile.ajax.php",
        data: {
            action: "getQrCodeV2",
            chooseUser : chooseUser
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
            if (data.result == 'internalError') {
              $('.qrCodeImgV2').empty().append('{{Erreur Pas d\'adresse interne (voir configuration de votre Jeedom !)}}');
          }else if(data.result == 'externalError'){
              $('.qrCodeImgV2').empty().append('{{Erreur Pas d\'adresse externe (voir configuration de votre Jeedom !)}}');
          }else if(data.result == 'UserError'){
              $('.qrCodeImgV2').empty().append('{{Erreur Pas d\'utilisateur selectionné}}');
          }else{
              $('.qrCodeImgV2').empty().append('<img src="data:image/png;base64, '+data.result+'" />');
              document.getElementById('qrCodecontainer').style.display = "flex";
          }
      }
  });



   }


</script>

<?php

include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');
