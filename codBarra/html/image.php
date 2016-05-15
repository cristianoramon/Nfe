<?php
if(isset($_GET['code']) && isset($_GET['t']) && isset($_GET['r']) && isset($_GET['text']) && isset($_GET['f1']) && isset($_GET['f2']) && isset($_GET['o']) && isset($_GET['a1']) && isset($_GET['a2'])) {
	require('config.php');
	require($class_dir.'/BCGColor.php');
	require($class_dir.'/BCGBarcode.php');
	require($class_dir.'/BCGDrawing.php');
	require($class_dir.'/BCGFont.php');
	if(include($class_dir . '/BCG' . $_GET['code'] . '.barcode.php')) {
		if($_GET['f1'] !== '0' && $_GET['f1'] !== '-1' && intval($_GET['f2']) >= 1){
			$font = new BCGFont($class_dir.'/font/'.$_GET['f1'], intval($_GET['f2']));
		} else {
			$font = 0;
		}
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		$codebar = 'BCG'.$_GET['code'];
		$code_generated = new $codebar();
		if(isset($_GET['a1']) && intval($_GET['a1']) === 1) {
			$code_generated->setChecksum(true);
		}
		if(isset($_GET['a2']) && !empty($_GET['a2'])) {
			$code_generated->setStart($_GET['a2']);
		}
		if(isset($_GET['a3']) && !empty($_GET['a3'])) {
			$code_generated->setLabel($_GET['a3']);
		}
		$code_generated->setThickness($_GET['t']);
		$code_generated->setScale($_GET['r']);
		$code_generated->setBackgroundColor($color_white);
		$code_generated->setForegroundColor($color_black);
		$code_generated->setFont($font);
		$code_generated->parse($_GET['text']);
		$drawing = new BCGDrawing('', $color_white);
		$drawing->setBarcode($code_generated);
		$drawing->draw();
		$drawing->finish(intval($_GET['o']));
	}
	else{
		header('Content: image/png');
		readfile('error.png');
	}
}
else{
	header('Content: image/png');
	readfile('error.png');
}
?>