<?
/***************************************************************************************
 *
 *
 ***************************************************************************************/
 
 
 class cCodBarraNf {
 
 
     function fCodBarra( $valor,$nomeJpg ){
	 
	    
                require_once('class/BCGFont.php');
		require_once('class/BCGColor.php');
		require_once('class/BCGDrawing.php');
		require_once('class/BCGcode128.barcode.php');

		//$font = new BCGFont('C:\xamppWeb\htdocs\nfeV3\codBarra\class\font\Arial.ttf', 7);
		$font = 0;
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);

		$code = new BCGcode128();
		$code->setScale(1);
		$code->setThickness(50);
		$code->setForegroundColor($color_black);
		$code->setBackgroundColor($color_white);
		$code->setFont($font);
		$code->setStart('C');
		$code->setTilde(true);
	    //$code->setShowText(false);
		$code->parse($valor);

		// Drawing Part
		$drawing = new BCGDrawing( $nomeJpg, $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();

		//header('Content-Type: image/jpg');

		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
	}
 
 }
?>
