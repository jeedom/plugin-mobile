<?php

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$path_img = 'plugins/mobile/core/img/';

?>



<div class="col-md-12 text-center">
</div>
<div class="col-md-8 col-md-offset-2 text-center">
  <img class="img-responsive center-block" style="margin-top:2%;width:25%;height:25%;" src="<?= $path_img . 'v2firstConnect.jpeg'; ?>" />
</div>
<div class="col-md-12 text-center" style="margin-top:2%;">
  <p class="text-center" style="font-weight:bold;">{{Pour connecter votre téléphone : il y a 2 méthodes possible}}</p>
  <p class="text-center" style="font-weight:bold;">{{Sur le premier écran de l'application, il vous est proposé de connecter votre compte market, et ainsi de retrouver toutes les boxs associées à ce compte, ou bien simplement ajouter une box.
}}</p>
</div>


<style>
  p {
    color: #94CA02;
    margin: 1px;
    font-size: 18px;
  }
</style>