<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$path_img = 'plugins/mobile/core/img/';

?>

<div class="test" style="display:flex;flex-direction:column;width:100%;height:100%;margin-bottom:5%;margin-top:2%;align-items:center;">

  <p style="font-weight:bold;font-size:16px;color:#94CA02;">{{Pour acceder aux fonctionnalités comme les Notifications, la Personnalisation du Menu ou la Géolocalisation, il faut au préalable avoir selectionné au moins une box 'actuelle'}}</p>


  <div style="display:flex;width:100%;height:100%;padding-top:0;margin-top:2%;">
    <div style="display:flex;flex-direction:column;align-items:center;text-align:center;justify-content:center;width:33%;">
      <p style="font-weight:bold;margin-top:2%;">{{Pour cela, dans l'onglet Boxs, il faut selectionner une box, et s'identifier.}}</p>
      <p style="font-weight:bold">{{Une fois les identifiants entrés, la box passe en haut de liste.}}</p>
      <p style="font-weight:bold">{{Si elle est repond au ping, elle a un voyant vert}}</p>
      <p style="font-weight:bold">{{On peut cliquer sur Synchroniser pour mettre à jour le statut des boxs}}</p>
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:33%;text-align:center;">
      <p style="font-weight:bold">{{Pour acceder a toutes les fonctionnalités, il faut cliquer sur la box configurée; cela permettra à l'application de recuperer ses informations, et donc autoriser les affichages du menu.}}</p>
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:33%;text-align:center;">
      <p style="font-weight:bold">{{Menu Complet}}</p>
    </div>
  </div>

  <div style="display:flex;justify-content:space-evenly;width:100%;height:100%;padding-top:0;margin-top:2%;">
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
      <img src="<?= $path_img . 'v2ActualBoxFlouted.jpeg'; ?>" />
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
      <img src="<?= $path_img . 'v2ConnectBox.jpeg'; ?>" />
    </div>
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
      <img src="<?= $path_img . 'v2FullMenu.jpeg'; ?>" />
    </div>
  </div>
</div>

<style>
  img {
    width: 40%;
    height: 50vh;
  }

  p {
    margin: 1px;
  }
</style>

<?php
