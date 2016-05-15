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
 
    while ( $contReg >= 0 )  {
   
      //Subtrainda a apenas o valor
      $val =  (int) substr($str,$contReg,1);
   
      //Multiplicando pelo Peso da ponderacao
      $mult = $val * $fatMult;
   
      //Somatorio da Poderaчуo
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
 
}

?>