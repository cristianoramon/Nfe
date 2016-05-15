 <?php 
     require('cAssinaXml.php');
	 
	  $cAssinar = new cAssinaXML();
	  //$arq , $id , $usuario , $nf,$arqSalvar,$privada,$publica,$filial
	  
	  $xmlArq = "NEWPIRAM_53220.xml";
	  $cAssinar->fAssinXML( $xmlArq ,"NFe" , "" , "","teste2345.xml","../KeyPrivada2.pem","../KeyPublica.pem","");
	  
	 
	 
 ?>