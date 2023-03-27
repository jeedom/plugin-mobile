<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$path_img = 'plugins/mobile/core/img/';

?>

<div class="test" style="display:flex;flex-direction:column;width:100%;height:100%;margin-bottom:5%;margin-top:2%;align-items:center;">
	<p style="font-weight:bold;">Dans le menu, vous aurez un onglet Boxs, qui regroupe toutes les box présentes sur ce compte market</p>
  <div style="display:flex;justify-content:space-evenly;width:100%;height:100%;margin-top:2%;padding-top:0;">

	      <img src="<?= $path_img.'v2MenuBoxs.jpeg'; ?>" />
				<img src="<?= $path_img.'v2floutedBoxs.png'; ?>" />

  </div>

</div>

<style>

img {
  width: 15%;
  height: 60vh;
}

p {
  margin:1px;
  font-size: 16px;
	color:#94CA02;
}

</style>

<?php
