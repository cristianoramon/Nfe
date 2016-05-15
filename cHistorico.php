<?php

class cHistorico {

//Retorna o historico
function selHist($codHistorico){
 $sel = " SELECT  SUBSTR(DSC_COMPLEMENTO,1,200)  DESCRICAO , SUBSTR(COD_HISTORICO,1,1) HISTORICO ".
        " FROM HISTORICO_ORIGEM  ".
        " WHERE COD_HISTORICO = '$codHistorico' ".
        " and  COD_ORIGEM = '14'" ;
 return $sel;
}

//Retorna o historico formatado
 function dscHistorico ( $dscHist ){

  $strRep = str_replace("<", "$", $dscHist);
  $ValE = explode("<", $strRep );
  $qt = count($ValE);

  for ( $c=0;$c<$qt;$c++){
   $ValS = explode(">",$ValE[$c]);
   $qt2 = count($ValS);
   
   for ( $s=0;$s<$qt2;$s++){
    $str = $str .  $ValS[$s];
   }

  }
  return  $str;
 }

}


?>
