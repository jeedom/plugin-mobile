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
	throw new Exception('{{401 - AccèÈs non autorisÈ}}');
}

$object = object::byId($_GET['object_id']);
sendVarToJS('object', $_GET['object_id']);
?>

<div class="row row-overflow">
	<?php
	echo "<div><center>";
	echo str_replace('></i>', ' style="font-size : 6em;color:#767676;"></i>', $object->getDisplay('icon', '<i class="fa fa-lemon-o"></i>'));
	echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $object->getName() . '</center></span>';
	echo "</center></div><br/>";
	echo '<div class="EnregistrementDisplay"></div>';
	echo '<div class="alert alert-info div_object_configuration" role="alert">';
	    echo '{{Vous pouvez activer ou désactiver l\'envoi de cette pièce vers l\'application}}';
		$check = 'checked';
		if ($object->getDisplay('sendToApp', 1) == 0) {
			$check = 'unchecked';
		}
		echo '<label class="checkbox-inline pull-right"><input type="checkbox" class="objectAttr" data-l1key="display" data-l2key="sendToApp" ' . $check .'/>{{Activer}}</label>';
		echo '<span class="form-control objectAttr" type="text" data-l1key="id" style="display : none;">' . $_GET['object_id'] . '</span>';
		echo '<span class="form-control objectAttr" type="text" data-l1key="name" style="display : none;">' . $object->getName() . '</span>';
		echo '</div>';
?>
<div class="form-actions pull-right">
		<a class="btn btn-success" id="bt_saveObject"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
	</div>
</div>

<script>
var changed=0;
$("#bt_saveObject").on('click', function (event) {
	changed=1;
    jeedom.object.save({
            object: $('.div_object_configuration').getValues('.objectAttr')[0],
            error: function (error) {
                $('.EnregistrementDisplay').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                modifyWithoutSave = false;
               $('.EnregistrementDisplay').showAlert({message: '{{Sauvegarde effectuée avec succès}}', level: 'success'});
            }
        });
    return false;
});
$('#md_modal').on('dialogclose', function () {
   if(changed==1) {
	   location.reload();
   }
})
</script>
