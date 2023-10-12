<?php
if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$path_wizard = json_decode(file_get_contents('plugins/mobile/core/data/wizard.json'), true);
?>

<div class="bodyModal animated slideInRight" id="wizardModal">

  <div class="multisteps-form">
    <!--progress bar-->
    <div class="row">
      <div class="multisteps-form__progress">
        <?php
        $i = 0;
        $step = $path_wizard['trame'];
        usort($step, function ($a, $b) {
          return $a['order'] <=> $b['order'];
        });
        foreach ($step as $key => $value) {
          if ($i == 0)
            $current_step = ' js-active current';
          else
            $current_step = '';

          echo '<button id="' . $step[$key]['wizard'] . '" class="multisteps-form__progress-btn ' . $current_step . '" type="button" data-stepwizard="' . $step[$key]['order'] . '">' . $step[$key]['title'] . '</button>';

          $i++;
        }
        ?>
      </div>
    </div>
    <div class="row" id="contentModal" style="width:100%;height:100%;margin-top:0;"></div>
  </div>

  <div class="prevDiv">
    <i class="next fas fa-arrow-circle-left cursor" id="bt_prev"></i>
  </div>
  <div class="nextDiv">
    <i class="next fas fa-arrow-circle-right cursor" id="bt_next"></i>
  </div>

</div>

<style>
  .next {
    font-size: 50px;
    color: rgba(147, 204, 1, 1);
  }

  .prevDiv {
    width: 100px;
    height: 100px;
    margin-right: -45px;
    bottom: -40px;
    margin-top: 10px;
    left: 5px;
    position: absolute;
  }

  .nextDiv {
    width: 100px;
    height: 100px;
    margin-right: -45px;
    margin-top: 10px;
    bottom: -40px;
    right: 5px;
    position: absolute;
  }

  #wizardModal {

    background-color: white !important;
    padding-top: 0;

  }

  ##bodymodalupdate {
    background-color: #FFFAF0 !important;
    width: 75%;
    min-height: 85%;
    margin: 0 auto;
    padding: 20px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    position: relative;
  }

  .multisteps-form {
    margin-bottom: 0px;
    margin-top: 0px;
  }

  .multisteps-form__progress {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(0, 1fr));
    margin: 0 auto;
  }

  .multisteps-form__progress-btn {
    transition-property: all;
    transition-duration: 0.15s;
    transition-timing-function: linear;
    transition-delay: 0s;
    position: relative;
    padding-top: 20px;
    color: rgba(108, 117, 125, 0.7);
    text-indent: -9999px;
    border: none;
    background-color: transparent;
    outline: none !important;
    cursor: pointer;
  }

  @media (min-width: 500px) {
    .multisteps-form__progress-btn {
      text-indent: 0;
    }
  }

  .multisteps-form__progress-btn:before {
    position: absolute;
    top: 0;
    left: 50%;
    display: block;
    width: 13px;
    height: 13px;
    content: '';
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%);
    transition: all 0.15s linear 0s, -webkit-transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
    transition: all 0.15s linear 0s, transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
    transition: all 0.15s linear 0s, transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s, -webkit-transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
    border: 2px solid currentColor;
    border-radius: 50%;
    background-color: #fff;
    box-sizing: border-box;
    z-index: 3;
  }

  .multisteps-form__progress-btn:after {
    position: absolute;
    top: 5px;
    left: calc(-50% - 13px / 2);
    transition-property: all;
    transition-duration: 0.15s;
    transition-timing-function: linear;
    transition-delay: 0s;
    display: block;
    width: 100%;
    height: 2px;
    content: '';
    background-color: currentColor;
    z-index: 1;
  }

  .multisteps-form__progress-btn:first-child:after {
    display: none;
  }

  .multisteps-form__progress-btn.js-active {
    color: rgba(147, 204, 1, 1);
  }

  .multisteps-form__progress-btn.js-active:before {
    -webkit-transform: translateX(-50%) scale(1.2);
    transform: translateX(-50%) scale(1.2);
    background-color: currentColor;
  }

  .multisteps-form__form {
    position: relative;
  }

  .multisteps-form__panel {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 0;
    opacity: 0;
    visibility: hidden;
  }

  .multisteps-form__panel.js-active {
    height: auto;
    opacity: 1;
    visibility: visible;
  }
</style>

<script>
  $(document).ready(function() {
    $('.prevDiv').hide();
    $('.saveDiv').hide();
    var jsonArr = {};
    var stepObject = JSON.parse('<?php echo json_encode($step); ?>');
    $('#contentModal').load('index.php?v=d&plugin=mobile&modal=' + stepObject[0]['wizard']);
    $('#bt_prev').click(function() {
      var current = $('.current').data('stepwizard');
      $('.current').removeClass('current');
      var prev = $(".multisteps-form__progress").find("[data-stepwizard='" + (current - 1) + "']").attr('id');
      $('#contentModal').removeClass('animated').removeClass('fadeIn');
      $('#contentModal').addClass('fadeOut');
      $('#contentModal').addClass('animated');
      $('#' + prev).addClass('js-active current');
      $('.saveDiv').hide();
      if ((current - 1) == 0) {
        $('.prevDiv').hide();
        $('.nextDiv').show();
      } else {
        $('.prevDiv').show();
        $('.nextDiv').show();
      }
      setTimeout(NextWizard(prev), 2000);
    });
    $('#bt_next').click(function() {
      var current = $('.current').data('stepwizard');
      var stateArr = {};
      stateArr.name = $('.current').prop('id');
      stateArr.state = "ok";
      //saveJson(JSON.stringify(stateArr));
      $('.current').removeClass('current');
      var next = $(".multisteps-form__progress").find("[data-stepwizard='" + (current + 1) + "']").attr('id');
      $('#contentModal').removeClass('animated').removeClass('fadeIn');
      $('#contentModal').addClass('fadeOut');
      $('#contentModal').addClass('animated');
      $('#' + next).addClass('js-active current');

      if ((current + 1) == (stepObject.length - 1)) {
        //$('.nextDiv').hide();
        $('.saveDiv').show();
      } else {
        $('.saveDiv').hide();
        $('.prevDiv').show();
        $('.nextDiv').show();
      }
      setTimeout(NextWizard(next), 2000);
    });
  });



  function NextWizard(step) {
    $('#contentModal').removeClass('animated').removeClass('fadeOut').addClass('fadeIn');
    $('#contentModal').load('index.php?v=d&plugin=mobile&modal=' + step);
    $('#contentModal').addClass('animated');
  }
</script>

<?php

if (jeedom::version() >= '4.4.0') {
  include_file('3rdparty', 'animate/animate', 'css');
} else {
  include_file('3rdparty', 'animate/animate', 'css');
  include_file('3rdparty', 'animate/animate', 'js');
}

//include('plugins/mobile/3rdparty/animate/animate.css');
//include('plugins/mobile/3rdparty/animate/animate.js');
//include_file('plugins/mobile/3rdparty', 'animate/animate', 'js');
?>