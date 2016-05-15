<?

class cChavedeAcesso {





 /**
  *   Somatorio da Chave de Acesso
  *   Uso : fStrPonderacao (valor, 10,2)
  **/
  
  function fStrPonderacao($str, $fatMultFinal,$fatMultInicio) {
  
   $contReg  = strlen($str) - 1;
 
   $fatMult  = $fatMultInicio;
   
   $somaPonderancao = 0;
 
   //echo "<br>".$str ;
    while ( $contReg >= 0 )  {
   
      //Subtrainda a apenas o valor
      $val =  (int) substr($str,$contReg,1);
   
      //Multiplicando pelo Peso da ponderacao
      $mult = $val * $fatMult;
      
	  //echo "<br> mult ->  ".$fatMult . " val " . $val;
	  
      //Somatorio da Poderação
      $somaPonderancao  = $somaPonderancao + $mult;
   
   
      $contReg--;
      $fatMult++;
   
      //Inicializando o peso quando for 10
      if ( $fatMult == $fatMultFinal )
       $fatMult = $fatMultInicio;
   }
   
  
   return $somaPonderancao;
 }
 
 function fDigitoDV($algorismo,$soma){
 
   $somaPod = $soma;
   
   $resto = $somaPod % $algorismo;
   
   if ( $resto == 0 || $resto == 1 ) 
    return 0;
	
   return $algorismo - $resto;
   
 }
 
 /**********************************************************************************************************************
  *
  **********************************************************************************************************************/
  function fStrPonderacaoModulo103($str, $fatMultFinal,$fatMultInicio) {
  
   $contReg  = strlen($str) -1;
 
   $fatMult  = $fatMultInicio;
   
   $somaPonderancao = 105;
   $qt = 0;
   
   //echo "<br>".$contReg ;
    while ( $contReg >=  $qt )  {
   
      //Subtrainda a apenas o valor
      $val =  (int) substr($str,$qt,2);
                  
      //Multiplicando pelo Peso da ponderacao
      $mult = $val * $fatMult;
      
	  //echo "<br> mult ->  $qt ".$fatMult . " val " . $val;
	  
      //Somatorio da Poderação
      $somaPonderancao  = $somaPonderancao + $mult;
   
  
      $fatMult++;
      $qt=$qt+2;
   
   }
   
  
  
   return $somaPonderancao;
 }
 
 /****************************************************************************************************
  *
  ****************************************************************************************************/
   function fDigitoDVModulo103($algorismo,$soma){
 
   $somaPod = $soma;
   
   $resto = $somaPod % $algorismo;
 
   return  $resto;
   
 }
 
 
}

?>