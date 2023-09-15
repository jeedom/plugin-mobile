<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$path_img = 'plugins/mobile/core/img/';

?>

<div class="test" style="display:flex;flex-direction:column;width:100%;height:100%;margin-bottom:5%;margin-top:2%;align-items:center;">

  <p style="font-weight:bold;font-size:16px;color:#94CA02;">Fonctionnement de la Geolocalisation</p>

  <div style="display:flex;width:100%;height:100%;padding-top:0;margin-top:2%;">
    <div style="display:flex;flex-direction:column;align-items:center;text-align:center;justify-content:center;width:25%;">
      <p style="font-weight:bold;margin-top:2%;">On ajoute une zone de Geolocalisation</p>
      <p style="font-weight:bold">On recherce l'adresse, on nomme la Zone, et on valide</p>
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:25%;text-align:center;">
      <p style="font-weight:bold">La zone apparaitra en rouge, signifiant que l'on se trouve pas dans la Zone</p>
      <p style="font-weight:bold">Si vous vous trouvé dans la zone à sa création, elle passera en vert apres quelques secondes</p>
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:25%;text-align:center;">
      <p style="font-weight:bold">Modifier largueur de la zone</p>
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:25%;text-align:center;">
      <p style="font-weight:bold">Ajouter une zone ajoutera dans votre équipement téléphone dans le plugin Mobile une commande binaire, qui pourra nous servir à programmer des actions en fonction de son état.</p>
    </div>
  </div>

  <div style="display:flex;justify-content:space-evenly;width:100%;height:100%;padding-top:0;margin-top:2%;">
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
      <img src="<?= $path_img . 'v2AddZone.jpeg'; ?>" />
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
      <img src="<?= $path_img . 'v2ZoneInactive.jpeg'; ?>" />
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
      <img src="<?= $path_img . 'v2ModifyBigRadius.jpeg'; ?>" />
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
      <img src="<?= $path_img . 'v2FullMenu.jpeg'; ?>" />
    </div>
  </div>
</div>

<style>
  img {
    width: 60%;
    height: 60vh;
  }

  p {
    margin: 1px;
  }
</style>

<?php
