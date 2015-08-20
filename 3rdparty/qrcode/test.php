<?php
require_once('qrlib.php');

$filename = 'temp/qrcode.png';
		$errorCorrectionLevel = 'L';
		$matrixPointSize = 4;
		
		QRcode::png('test', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
		
		echo '<img src="'.$filename.'" />';
?>