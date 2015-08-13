<?php
include('qrlib.php');

$filename = 'temp/qrcode.png';
$matrixPointSize = 4;
$errorCorrectionLevel = 'L';

QRcode::png('http://jeedom.fr', $filename, $errorCorrectionLevel, $matrixPointSize, 2);

echo '<img src="'.$filename.'" />';
?>