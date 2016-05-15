<?php
  require_once("nfs/cChavedeAcesso.php");
  
  $teste = new cChavedeAcesso();

   
 $str = "2708011227764600010855000000246167024615619";
 //$str = "4306093300991100471155010000000001239981587";
 $str = "2708011227764600010855000000247421024742129";
 
 $str ="430603926656110131555509900000699200070554";
 $str ="2708011227764600010855000000247431024743129";
 //echo "<br>Soma " . $teste->fStrPonderacao ($str, 10,2);
 
 //echo "<br>DV " . $teste->fDigitoDV (11, $teste->fStrPonderacao ($str, 10,2));
 $str="31071112229415001605550010000093860002214910";
 echo "<br>DV " .strlen($str). ' '.$teste->fDigitoDVModulo103 (103, $teste->fStrPonderacaoModulo103($str, 10,1));
?>
