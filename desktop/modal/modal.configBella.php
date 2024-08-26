<?php

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

$carouselHtml = file_get_contents(__DIR__ . '/../../core/data/html/carouselHtml.html');
$bellaHtml    = file_get_contents(__DIR__ . '/../../core/data/jsonBella/jsonObject_default.html');


$arrayInfos   = array();
$arrayInfos[] = array(
                        'name' => 'Commande d\'equipement',
                        'imageBg' => 'core/img/background/jeedom_abstract_04_light.jpg',
                        'bellaHtml' => $bellaHtml,
                        'idBella' => 'jsonObject_default'
                      );
$arrayInfos[] = array(
                        'name' => 'Objets de votre Jeedom',
                        'imageBg' => 'https://www.jeedom.com/background/background12.png',
                        'bellaHtml' => $bellaHtml,
                        'idBella' => 'jsonObject_default'
                      );


$arrayObjects = array();
foreach(jeeObject::all() as $object){
    $objetArray = utils::o2a($object);
    $pathHtmlBella = __DIR__ . '/../../core/data/jsonBella/';
    if(!is_dir($pathHtmlBella)){
      mkdir($pathHtmlBella, 0777, true);
    }
    $pathHtmlBella .= 'jsonObject_'.$objetArray['id'].'.html';
    if(file_exists($pathHtmlBella)){
      $bellaHtml= file_get_contents($pathHtmlBella);
    }else{
      $bellaHtml = file_get_contents(__DIR__ . '/../../core/data/jsonBella/jsonObject_default.html');
    }
    $arrayObjects[] = array(
          'name' => $objetArray['name'],
          'imageBg' => $objetArray['img'] ? $objetArray['img'] : 'core/img/background/jeedom_abstract_04_light.jpg',
          'idObject' => $objetArray['id'],
          'bellaHtml' => $bellaHtml);
}


?>

<body id="bodyBella">
<div class="resumeBtn" style="display:flex;justify-content:flex-end;">
     <button class="btn btn-success" id="validView" style="border-radius:20px !important;padding-left:5px !important;padding-right:5px !important;margin-bottom:10px;">Valider la vue</button>
     <button class="btn btn-warning" id="quitView" style="border-radius:20px !important;padding-left:5px !important;padding-right:5px !important;margin-bottom:10px;">Quitter</button>
</div>



<div id="main" style="display:flex;flex-direction:row;">
    <!-- INJECTION OF BELLA HTML -->
    <div style="display:flex;flex-direction:column;" id="bella-container"></div>
    <div id="rightContent" style="width:100%;height;100vh;display:flex;flex-direction:column;align-items:center;">
        <div id="containerCarousels" style="height:30vh;width:100%;"><!-- INJECTION CAROUSELS --></div>

    </div>
</div>
</body>
<?php include_file('desktop', 'bella', 'js', 'mobile'); ?>

<script>
        var arrayInfos = <?php echo json_encode($arrayInfos); ?>;
        var arrayObjects = <?php echo json_encode($arrayObjects); ?>;
        var carouselHtml = <?php echo json_encode($carouselHtml); ?>;
        initializeData(arrayInfos, arrayObjects, carouselHtml);
</script>

<?php

include_file('desktop', 'configBella', 'css', 'mobile');
include_file('3rdparty', 'animate/animate', 'css');
// include_file('desktop', 'mobile', 'js', 'mobile');
include_file('core', 'plugin.template', 'js');


?>

