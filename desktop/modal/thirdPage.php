<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$path_img = 'plugins/mobile/core/img/';

?>

<div class="mainContainer" style="display:flex;flex-direction:column;width:100%;height:100%;margin-bottom:5%;margin-top:2%;align-items:center;">
	<p style="font-weight:bold;">Dans le menu, vous aurez un onglet Boxs, qui regroupe toutes les box présentes sur ce compte market</p>
	<div class="imgContainer" style="display:flex;justify-content:space-evenly;width:100%;height:100%;margin-top:2%;padding-top:0;">

		<img src="<?= $path_img . 'v2MenuBoxs.jpeg'; ?>" />
		<img src="<?= $path_img . 'v2floutedBoxs.png'; ?>" />

	</div>

</div>

<style>
	@media only screen and (max-width: 600px) {
		.mainContainer {
			margin-bottom: 10% !important;
			display: flex !important;
			flex-direction: row !important;
			align-items: center !important;
			height: 50%
		}

		.secondText,
		.firstText {
			display: block;
			width: 100% !important;
			height: 25% !important;
			/*	background-color: green !important;*/
		}

		.imgContainer {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: flex-start;
			/*	background-color: red !important;*/
		}

		img {
			min-width: 150px !important;
			min-height: 150px !important;
			width: 70% !important;
			height: 25% !important;
			margin-top: 1vh !important;
		}

		p {
			font-size: 12px !important;
			text-align: center !important;
		}

		.explainContainer {
			display: flex;
			flex-direction: column;
			align-items: center;
			text-align: :'center';
		}

		.firstText {
			display: : block;
			background-color: :red !important;
		}
	}

	img {
		width: 15%;
		height: 60vh;
	}

	p {
		margin: 1px;
		font-size: 16px;
	}
</style>

<?php
