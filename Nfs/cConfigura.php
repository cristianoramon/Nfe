<?
   class cConfigura {
   
       /********
	    * c:\\xamppWeb\htdocs\xmlSign\crpaaa.pem
		*******/
		function fConfiguracao( $filial ) {
		   
		   $vet = array();
		   
		   switch ( strtoupper($filial) ) {
		
		      case "001" :
		                 // $vet[0] =  "../xmlSign/crpaaa.pem"; 
						  $vet[0] =  'c:\\xamppWeb\htdocs\xmlSign\crpaaa.pem'; 
						  $vet[1] =  'xmlSign/KeyPrivadacrpaaa.pem'; 
						  $vet[2] = 'xmlSign/KeyPublicacrpaaa.pem'; 
			             break; 
			 case "002" :
		                 $vet[0]  =  "c:\\xamppWeb\htdocs\xmlSign\TradeCert.pem"; 
			             $vet[1]  =  'xmlSign/KeyTradePrivada.pem'; 
						 $vet[2]  = 'xmlSign/KeyTradePublica.pem'; 			 
						 break;
			 case "005" :
		                 // $vet[0] =  "../xmlSign/crpaaa.pem";
						  $vet[0] =  'c:\\xamppWeb\htdocs\xmlSign\crpaaa.pem';
						  $vet[1] =  'xmlSign/KeyPrivadacrpaaa.pem';
						  $vet[2] = 'xmlSign/KeyPublicacrpaaa.pem';
			             break;
		   
		   
		 }
		 
		 return $vet;
	 }	
   }
 
?>
