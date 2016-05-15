<?
   require_once('nusoap/lib/nusoap.php');
   
   /**************
    *
	*
	*
	**************/
   
   class cWebCliente {
      
	  
	  /*****
	   *
	   ******/
	  public function fConnecSoap($url, $param){
	  
	     $client = new SoapClient('http://200.141.128.77/WSGeraPFUsinasTeste/WSGeraPFUsinasTeste.asmx?wsdl',true);
 
         $erro = $client->getError();
  
         if ( $erro ) {
           
		   echo '<br>erro <br>';
		   return 0; 
	     
		 }
		
		$par = array('XMLEntrada'=>$param);
  
        $result = $client->call("GeraPasse",array('parameters'=>$par));
 
  
       if ( $client->fault ) {
        echo "<h2>Falha na chamada do metedo </h2><pre>";
       }	
  
		return $result;
   }
   
   
   /*****************
    *
	*****************/
	
	public function fArquiXML($path){
	
	    $fh = fopen('../Nfs/xml/CRPAAA229385.xml', 'r+') or die("Error!!");
 
 		if ( $fh ) {
  		 while ( !feof($fh) ) {
		 
           $buffer = fgets($fh, 4096);
	       $buffer2 = $buffer2 . $buffer;
           echo '<br>sa'.$buffer.' sa <br>';
         }
       //fwrite($fh,$buffer);
       fclose($fh);
      }
 	
  }
 }  
?>
