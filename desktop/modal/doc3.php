<?php

$pathImg = __DIR__ . '/../../docs/images/v2firstConnect.jpeg';
$type = pathinfo($pathImg, PATHINFO_EXTENSION);
$data = file_get_contents($pathImg);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>

<img src="<?= $base64 ?>" width="250" height="600" style="display: block; margin: 0 auto; margin-bottom: 20px;"/>

Pour connecter votre téléphone, deux méthodes sont disponibles :  
Sur le premier écran de l'application, vous pouvez soit connecter votre compte Market pour retrouver toutes les Box associées à ce compte, soit ajouter une Box manuellement.

