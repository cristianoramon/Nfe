<?
  //Classe que Procurar pelo Arquivos
     require_once("../ProcuraArq/cVarreArq.php");
   
   //Retorno o valor do xml
    require_once("../Nfs/cValorXml.php");
   
   //Include Necessaria para o XML
    require_once("../Nfs/cGerarXmlNfs.php");
	
    //Enviar o XML

 

   
   
   class cGravaXML {
   
      function fGravaXML($login,$valor){
	  
	      //Objetos
          $xmlGerarNf = new cGerarXmlNfs('../xml_esquema/RetEnvioVersao10.xml'); 
		 
		  $xmlGerarNf->setNoValor("nRec",$valorRecibo,"id","Recibo",FALSE,FALSE,1,0);		
 		  $xmlArq = "../../xml/xml_recibo_envia/".$login ."/rec2_".$valor.".xml";	 	
		  
         if ( ! file_exists( $xmlArq ) ){
				  
			$xmlGerarNf->save($xmlArq);
			
	    }
				 
	 } 
   }   

?>
