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

if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$eqLogics = mobile::byType('mobile');

?>

<table class="table table-condensed tablesorter" id="table_menuCustom">
  <thead>
    <tr>
      <th>{{Équipement}}</th>
      <th>{{Type de Mobile}}</th>
      <th>{{Utilisateur}}</th>
      <th>{{Menu Défaut}}</th>
      <th>{{Dernière activité}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $idEqlogicMobile = intval(config::byKey('checkdefaultID', 'mobile'));

    foreach ($eqLogics as $eqLogic) {
      $activeMobileId = $eqLogic->getConfiguration('defaultIdMobile', 'none');
      $userId = $eqLogic->getConfiguration('affect_user');
      $userType = user::byId($userId);
      if (is_object($userType)) {
        $username = $userType->getLogin();
        echo '<tr><td width="40%"><a href="' . $eqLogic->getLinkToConfiguration() . '">' . $eqLogic->getHumanName(true) . '</a></td>';
        if ($eqLogic->getConfiguration('type_mobile') == 'android') {
          echo '<td width="12.5%"><span class="label label-info" style="width:20%;display:flex;justify-content:center;"><i class="fab fa-android"></i></span></td>';
        } else if ($eqLogic->getConfiguration('type_mobile') == 'windows') {
          echo '<td width="12.5%"><span class="label label-info" style="width:20%;display:flex;justify-content:center;"><i class="fab fa-windows"></i></span></td>';
        } else if ($eqLogic->getConfiguration('type_mobile') == 'ios') {
          echo '<td width="12.5%"><span class="label label-info" style="width:20%;display:flex;justify-content:center;"><i class="fab fa-apple"></i></span></td>';
        } else {
          echo '<td width="12.5%"><span class="label label-info" style="width:20%;display:flex;justify-content:center;"><i class="far fa-question-circle"></i></span></td>';
        }
        echo '<td width="15.5%"><span class="label label-info">' . $username . '</span></td>';
        if ($eqLogic->getConfiguration('appVersion') == 2) {
          echo '<td><select class="menuDefault" eqIdMobile="' . $eqLogic->getId() . '">';
          echo '<option value="none"  ' . ($activeMobileId === 'none' ? 'selected' : '') . ' disabled>- {{Choisir Menu}} -</option>';
          echo '<option value="default" ' . ($activeMobileId === 'default' ? 'selected' : '') . '>{{Menu par défaut}}</option>';
          foreach ($eqLogics as $mobileToChoose) {
            if ($mobileToChoose->getConfiguration('appVersion') == 2) {
              echo '<option value="' . $mobileToChoose->getId() . '" eqIdMobile="' . $eqLogic->getId() . '" ' . ($activeMobileId === $mobileToChoose->getId() ? 'selected' : '') . '>' . $mobileToChoose->getHumanName(true) . '</option>';
            }
          }
          echo '</select></td>';
        } else {
          echo '<td width="25%"><span class="label label-warning">{{APPLICATION V2 NON INTALLÉE}}</span></td>';
        }
        echo '<td><span class="label label-info">' . $eqLogic->getStatus('lastCommunication') . '</span></td>';
      } else {
        echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
        if ($eqLogic->getConfiguration('type_mobile') == 'android') {
          echo '<td width="12.5%"><span class="label label-info" style="width:20%;display:flex;justify-content:center;"><i class="fab fa-android"></i></span></td>';
        } else if ($eqLogic->getConfiguration('type_mobile') == 'windows') {
          echo '<td width="12.5%"><span class="label label-info" style="width:20%;display:flex;justify-content:center;"><i class="fab fa-windows"></i></span></td>';
        } else if ($eqLogic->getConfiguration('type_mobile') == 'ios') {
          echo '<td width="12.5%"><span class="label label-info" style="width:20%;display:flex;justify-content:center;"><i class="fab fa-apple"></i></span></td>';
        } else {
          echo '<td width="12.5%"><span class="label label-info" style="width:20%;display:flex;justify-content:center;"><i class="icon far fa-question-circle"></i></span></td>';
        }
        echo '<td><span class="label label-info">{{Utilisateur non trouvé}}</span></td></tr>';
      }
    }
    ?>
  </tbody>
</table>

<div class="warningV2" style="display:none;"></div>



<script>
  var selects = document.querySelectorAll('.menuDefault');


  selects.forEach(function(select) {
    select.addEventListener('change', function() {
      var selectedValue = this.value;
      var eqIdMobile = this.getAttribute("eqIdMobile");
      console.log("Valeur sélectionnée : " + selectedValue);
      console.log("eqIdMobile : " + eqIdMobile);
      $.ajax({
        type: "POST",
        url: "plugins/mobile/core/ajax/mobile.ajax.php",
        data: {
          action: "menuDefault",
          eqId: eqIdMobile,
          eqIdDefault: selectedValue
        },
        dataType: 'json',
        global: false,
        error: function(request, status, error) {
          handleAjaxError(request, status, error);
        },
        success: function(data) {
          if (data.state != 'ok') {
            $('#div_alert').showAlert({
              message: data.result,
              level: 'danger'
            });
            return;
          }

          $('#div_alert').showAlert({
            message: '{{Configuration Menu Enregistrée}}',
            level: 'success'
          });

        }
      });
    });
  });
</script>


<style>
  .hiddenEl {
    display: none;
  }

  .visibleEL {
    display: block;
  }


  .nameIconNoActive {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .container {
    display: flex;
    height: 100%;
    width: 75vw;
  }

  .panelContainer {
    display: flex;
    justify-content: center;
  }

  .panelCustomMenuMobile {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    width: 25%;
    border: solid #93ca02;
    !important;
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

  .panelCustomMenuMobile:hover {
    background-color: #93ca02;
    !important;

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
    background-color: #93ca02 !important;
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
    display: none;
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
