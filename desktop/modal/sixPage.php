<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$path_img = 'plugins/mobile/core/img/';

?>

<div class="test" style="display:flex;flex-direction:column;width:100%;height:100%;margin-bottom:5%;margin-top:2%;align-items:center;">

  <p class="greenP" style="font-weight:bold;">Personnalisation du Menu</p>
  <p class="greenP" style="font-weight:bold;">Vous pouvez personnaliser le Menu de votre WebView via le plugin Mobile ou l'application</p>

  <div style="display:flex;width:100%;height:100%;padding-top:0;margin-top:2%;">
       <div style="display:flex;flex-direction:column;align-items:center;text-align:center;justify-content:center;width:25%;">
         <p style="font-weight:bold;margin-top:2%;">On clique sur Menu Custom</p>
         <p style="font-weight:bold;">Une modale s'ouvre et vous propose vos téléphones deja connectés</p>
         <p style="font-weight:bold">Cliquer sur Configurer Menu sur le téléphone choisi</p>
         <p style="font-weight:bold">Le nombre d'icones peut aller de 1 à 4</p>
         <p style="font-weight:bold">En cochant Menu Default, le menu configuré sur ce téléphone devient celui par défaut sur tous vos téléphones</p>
       </div>
       <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:25%;text-align:center;">
         <p style="font-weight:bold">Via l'application Mobile : si vous avez configuré une box par défaut, alors le Menu Custom apparait dans le menu de l'application</p>
         <p style="font-weight:bold">Meme principe que via le plugin Mobile</p>
       </div>
  </div>

  <div style="display:flex;justify-content:space-evenly;width:100%;height:100%;padding-top:0;margin-top:2%;">
       <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
         <img src="<?= $path_img.'v2ModalMenuCustom.png'; ?>" />
         <img src="<?= $path_img.'v2ModalMenuCustom.png'; ?>" />
         <img src="<?= $path_img.'v2ModalMenuCustom.png'; ?>" />
       </div>
       <div>
         <p>Menu Custom Mobile</p>
         <img src="<?= $path_img.'v2connectMarket.png'; ?>" />
         <img src="<?= $path_img.'v2FullMenu.png'; ?>" />
       </div>
  </div>
</div>

<style>

img {
  width: 60%;
  height: 60vh;
}

.greenP{
  color:#94CA02;
}

p{
  margin:1px;
}


</style>

<?php
