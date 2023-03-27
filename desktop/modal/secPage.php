<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$path_img = 'plugins/mobile/core/img/';

?>

<div class="test" style="display:flex;flex-direction:column;width:100%;height:100%;margin-bottom:5%;">
  <div style="display:flex;justify-content: space-evenly;align-items:center;width:100%;height:100%;margin-top:2%;padding-top:0;">
     <div style="width:50%;display:flex;flex-direction:column;align-items:center;justify-content:center;">
       <p class="pinfos" style=font-weight:bold;>{{COMPTE MARKET}}</p>
     <img src="<?= $path_img.'v2connectMarket.jpeg'; ?>" />
     </div>
     <div style="width:50%;display:flex;flex-direction:column;align-items:center;justify-content:center;">
       <p style=font-weight:bold; class="pinfos">{{BOX ID}}</p>
     <img src="<?= $path_img.'v22methods.jpeg';?>" />
     </div>
  </div>
  <div style="display:flex;width:100%;height:100%;margin-top:2vh;margin-bottom:2vh;">
    <div style="display:flex;width:50%;height:100%;justify-content:center;align-items:center;margin:auto;">
        <p style=font-weight:bold;>{{Il vous faut simplement rentrer votre identifiant Market ainsi que votre mot de passe.}}</p>
    </div>
     <div style="display:flex;flex-direction:column;width:50%;height:100%;justify-content:center;align-items:center;">
       <p class="text-center" style="margin-right: 2%;font-weight:bold;">{{Plusieurs choix sur cet écran :}}</p>
       <p class="text-center" style="margin-right: 2%;font-weight:bold;">{{- Vous entrez l'url de votre Jeedom (interne ou externe), ainsi que les identifiants d'accès à celle-ci et vous confirmez avec le bouton CONNEXION}}</p>
       <p class="text-center" style="margin-right: 2%;font-weight:bold;">{{- Vous cliquez sur QR Code : un nouvel écran apparait; vous pourrez scanner un QRCode depuis le plugin Mobile de la box que vous souhaitez ajoutée, via l'onglet QRCODE du plugin.}}</p>
     </div>
  </div>
</div>

<style>

img {
  width: 25%;
  height: 50vh;
}

.pinfos {
  color:#94CA02;
}

p {
  margin:1px;
  font-size: 16px;
}


</style>

<?php
