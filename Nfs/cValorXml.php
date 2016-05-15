<?php
  require("cConsultaSQL.php");
  
/******************************************************************
 *
 *
 *****************************************************************/

class cValorXml extends cConsultaSQL{

  /******************
   *
   ******************/
  function __contruct($usu, $sen, $banco,$tipo){
  
    if ($tipo != NULL ) 
     parent::__contruct($usu, $sen, $banco,$tipo);
  }
  
  /************
   *
   ***********/
   function getConsulta( $sql ){
       return parent:: execQuery( $sql );
   }
   
   /************
   *
   ***********/
   function getFetch(  ){
       return parent::getFetch();
   }
   
   /************
   *
   ***********/
   function getStat(  ){
       return parent::getStat();
   }
  
  /*******
   *
   *******/
  function getConexao() {
    return parent::getConn();
  }
  
   /***********
	*
	***********/ 
   public function getCampo($campo) {
	   return parent::getCampo($campo);
   } 
   
  /*****
   *  Procura o no filho
   *  Paramentros necessarios
   *  Param @no
   *  Param qt filhos caso ceja necessario
   *****/
  public function filhos($_no,$_n=0) {
	 foreach($_no->children() as $_f=>$_v) {
		 		filhos($_v,$_n+1);
	}
 }
 
 /*****
  * Retorna o Valor do Xml
  *
  ****/
 public function ValorXml( $campoXml , $ArqXml = "xml_esquema/NfsAutorizacao.xml" ) {
	
	if ( file_exists( $ArqXml) ) 
	  $_xml = simplexml_load_file($ArqXml);
	else
	  return " 1-> Erro Ao Abrir o arquivo -> " . $ArqXml;
   
		   
	$valXml = ''; 
	
	$_criterio = $campoXml;
	
	$_r = $_xml->xpath($_criterio);
	
	
	if($_r!==FALSE) { 		 
	     
	
		foreach($_r as $_v) {
		 
			$_n = $_v->children();
			
			if(sizeof($_n[0])==0) {
			
				 $valXml = $valXml . $_v;
				
			} else {
			
				filhos($_v);
			}
			
		}
	} else {
	
	   return "Nenhuma ocorrência encontrada para $_criterio";
	}
	
   return $valXml;
 }
 
 
 
  /*****
  * Retorna o Valor do Xml
  *
  ****/
 public function ValorXmlNameSpace( $campoXml , $ArqXml = "xml_esquema/NfsAutorizacao.xml" ) {
   
  
   if ( file_exists( $ArqXml) ) 
	  $_xml = simplexml_load_file($ArqXml);
   else
	  return "1->Erro Ao Abrir o arquivo -> " . $ArqXml;
 
 
 $_xml= file_get_contents($ArqXml);	
 
 
 $ProcessaNameSpace = str_replace("xmlns=", "ns=", $_xml); 

 $xml = new SimpleXMLElement($ProcessaNameSpace); 

 $result = $xml->xpath($campoXml); 

 return $result[0];
}



  /*****
  * Retorna o Valor do Xml
  *
  ****/
 public function ValorXmlNameSpaceVetor( $campoXml , $ArqXml = "xml_esquema/NfsAutorizacao.xml" ) {
   
  
   if ( file_exists( $ArqXml) )
      $_xml = @simplexml_load_file($ArqXml);
   else
	  return " 1-> Erro Ao Abrir o arquivo -> " . $ArqXml;
 
 
 $_xml= file_get_contents($ArqXml);	
 
 
 $ProcessaNameSpace = str_replace("xmlns=", "ns=", $_xml); 

 $xml = new SimpleXMLElement($ProcessaNameSpace); 

 $result = $xml->xpath($campoXml); 

 return $result;
}  

 /*****
  * Retorna o Valor do Xml do Atributo
  *
  ****/
 public function ValorXmlNameSpaceAttr( $campoXml , $ArqXml = "xml_esquema/NfsAutorizacao.xml" ,$attr ) {
   
  
   if ( file_exists( $ArqXml) ) 
	  $_xml = simplexml_load_file($ArqXml);
   else
	  return "1->  Erro Ao Abrir o arquivo ->" . $ArqXml;
 
 
   $_xml= file_get_contents($ArqXml);	
 
 
   //echo $_xml;
   
   $ProcessaNameSpace = str_replace("xmlns=", "ns=", $_xml); 

    
   $xml = new SimpleXMLElement($ProcessaNameSpace); 
   
   $result = $xml->xpath($campoXml); 

   foreach($result[0]->attributes() as $a => $b) {
   
      if ( $a == $attr )
        $result =  $b;
   } 
	 
 



   return $result;
 }  
}

?>
