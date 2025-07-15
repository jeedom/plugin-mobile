<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('desktop', 'wizard', 'css', 'mobile');

?>


<button class="btn btn-xs btn-danger" id="bt_quitmobileWizard"><i class="fas fa-times"></i> {{Fermer l'assistant}}</button>

<div id="mobile_wizard">
	<div class="container text-center" id="wizard_container" style="visibility:hidden;">
	</div>
	<div class="flex-evenly" id="wizard_navigation">
		<div>
			<i class="far fa-arrow-alt-circle-left navBtn bt_prev hidden"></i>
		</div>
		<div>
        <?php
			$i = 1;
			$wizard = mobile::getWizardSteps();
			foreach ($wizard as $step => $title) {
				$finalAttr = ($step == 'finalization') ? ' data-finalization="1"' : '';
				echo '<span class="navDot cursor shadowed" data-step="' . $step . '" title="' . $title . '" data-tippy-placement="bottom"' . $finalAttr . '>';
				echo $i;
				echo '</span>';
				$i++;
			}
			?>
		</div>
		<div>
			<i class="far fa-arrow-alt-circle-right navBtn bt_next"></i>
			<i class="fas fa-check-circle hidden" id="bt_jeedom_ready"></i>
		</div>
	</div>
</div>

<?php include_file('desktop', 'mobile', 'js', 'mobile'); ?>

<script>

window.mobileStepLoaded = function() {
    domUtils.hideLoading();
    document.getElementById('mobile_initial_loader')?.remove();
    document.getElementById('wizard_container').style.visibility = 'visible';
};

setTimeout(function() {
    document.getElementById('mobile_initial_loader')?.remove();
    document.getElementById('wizard_container').style.visibility = 'visible';
}, 10000);


document.addEventListener('DOMContentLoaded', function() {
	var activeFinal = document.querySelector('.navDot.active[data-finalization="1"]');
	if (activeFinal) {
		document.getElementById('bt_quitmobileWizard')?.classList.add('hidden');
	}
});

function updateQuitButtonVisibility() {
	var activeFinal = document.querySelector('.navDot.active[data-finalization="1"]');
	var quitBtn = document.getElementById('bt_quitmobileWizard');
	if (quitBtn) {
		if (activeFinal) {
			quitBtn.classList.add('hidden');
		} else {
			quitBtn.classList.remove('hidden');
		}
	}
}

document.addEventListener('DOMContentLoaded', function() {
	updateQuitButtonVisibility();
});

window.updateQuitButtonVisibility = updateQuitButtonVisibility;
</script>


