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

ini_set('display_errors', 0);
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div class="col-lg-12 col-md-12 col-sm-12 eqLogicPluginDisplay">
  <legend><i class="fas fa-cogs"></i> {{Scénarios}}
    <!--<a id="bt_saveScenarios" class="btn btn-sm btn-success pull-right" ><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>-->
  </legend>

  <div>
    <table id="table_scenarioSummary" class="table table-bordered table-condensed tablesorter">
      <thead>
        <tr>
          <th>{{ID}}</th>
          <th>{{Scénario}}</th>
          <th data-sorter="checkbox" data-filter="false">{{Transmis}}</th>
          <!--<th data-sorter="false" data-filter="false">{{Actions}}</th>-->
        </tr>
      </thead>
      <tbody>
        <?php
        $scenarios = scenario::all();
        foreach ($scenarios as $scenario) {
          $check = 'unchecked';
          $scenario_id = $scenario->getId();
          if ($scenario->getDisplay('sendToApp', 0) == 1) {
            $check = 'checked';
          }
          $tr = '<tr data-id="' . $scenario_id . '"><td>' . $scenario_id . '</td>';
          $tr .= '<td>' . $scenario->getHumanName() . '</td>';
          $tr .= '<td><label><input type="checkbox" class="configuration sendtoapp" value="' . $scenario_id . '" ' . $check . ' title="{{Envoyer à l\'application}}"/></label></td>';
          /*$tr .= '<td><a class="btn btn-xs btn-success bt_saveScenario"><i class="fas fa-save"></i></a>';*/
          $tr .= '</tr>';
          echo $tr;
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  var table_scenarioSummary = document.getElementById('table_scenarioSummary')
  new DataTable(table_scenarioSummary, {
    columns: [{
        select: 1,
        sort: "asc"
      },
      {
        select: [2],
        sortable: false
      }
    ],
    paging: true,
    perPage: 20,
    perPageSelect: [10, 20, 30, 50, 100, 200],
    searchable: true,
  })

  /*$('#bt_saveScenarios').off('click').on('click', function () {
      console.log('save all scenarios')
	  $('#table_scenarioSummary tbody tr').each(function(){
        var scID = $(this).attr('data-id')
        var scState = $(this).find('input.ScenarioAttr').is(':checked')
        console.log(scID + ' -> ' + scState)
      })
    })

    $('.bt_saveScenario').off('click').on('click', function () {
      var scID = $(this).closest('tr').attr('data-id')
      var scState = $(this).closest('tr').find('input.ScenarioAttr').is(':checked')
      console.log(scID + ' -> ' + scState)

    })*/

  document.getElementById('table_scenarioSummary')?.addEventListener('click', function(event) {
    var _target = null
    var sendApp = 0
    if (_target = event.target.closest('.sendtoapp')) {
      idScenario = _target.value
      if (_target.checked == true) sendApp = 1;
      domUtils.ajax({
        type: "POST",
        url: "plugins/mobile/core/ajax/mobile.ajax.php",
        data: {
          action: "savescenario",
          id: idScenario,
          valueSend: sendApp
        },
        dataType: 'json',
        global: false,
        error: function(error) {
          jeedomUtils.showAlert({
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          if (data.state != 'ok') {
            jeedomUtils.showAlert({
              message: data.result,
              level: 'danger'
            })
            return;
          }
        }
      })
    }
  })
</script>
