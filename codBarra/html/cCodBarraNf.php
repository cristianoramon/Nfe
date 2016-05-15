<?
/***************************************************************************************
 *
 *
 ***************************************************************************************/
 
 
 class cCodBarraNf {
 
 
     function fCodBarra( $valor,$nomeJpg ){
	 
	    define('IN_CB',true);
	    require('../codBarra/class/index.php');
	    require('../codBarra/class/FColor.php');
	    require('../codBarra/class/BarCode.php');
	    require('../codBarra/class/FDrawing.php');
		
	    if(include('../codBarra/class/'.'code128'.'.barcode.php')){
		
		  $color_black = new FColor(0,0,0);
		  $color_white = new FColor(255,255,255);
		  $valor = '90';
		  $code_generated = new code128(45,$color_black,$color_white,3,$valor,2,'C');
		
		  $drawing = new FDrawing(1024,1024,$nomeJpg,$color_white);
		  $drawing->init();
		  $drawing->add_barcode($code_generated);
		  $drawing->draw_all();
		  $im = $drawing->get_im();
		  $im2 = imagecreate($code_generated->lastX,$code_generated->lastY);	
		  imagecopyresized($im2, $im, 0, 0, 0, 0, $code_generated->lastX, $code_generated->lastY, $code_generated->lastX, $code_generated->lastY);
		  $drawing->set_im($im2);
		  $drawing->finish(2);
		  
	   }
	   
	   
	 }
 
 }
?>