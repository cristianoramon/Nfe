<? session_start(); ?>

  <? require("mail.php"); ?>

<?
  $nf = $HTTP_GET_VARS["nf"];
  $conn = ocilogon($HTTP_SESSION_VARS['login'], $HTTP_SESSION_VARS['senha'], $HTTP_SESSION_VARS['banco'] );


  echo 'NFFF => ' . $nf ;

  $s = OCIParse($conn, "begin IPRC_CANCNOTA(:status, :snf); end;");

//Param IN
  OCIBindByName($s, ":snf",  $nf );

 //Param OUT
 OCIBindByName($s, ":status", $out_var, 1000);
 OCIExecute($s, OCI_DEFAULT);
 OCICommit($conn);

 $os = '';
 $resolucao = '';
 $resumo = '';
 $datFech = '';
 $email ='twarw@teste.com.br';
 $tecnico = '';
 $arq = $HTTP_SESSION_VARS['login'] . "/Nota_".$HTTP_SESSION_VARS['login']."_$nf.pdf";
 //$arq = "";
 echo "<br>$arq";

 $enviar = EviarEmail($os,"Crpaaa",$out_var,$resumo,$datFech,$email,$tecnico,$arq);

 if ( $enviar == 0 ) {
   echo "<script>alert(\"Nao foi possovel enviar email para $email $arq \");</script>";
 }
 else {
   echo "<script>alert(\"Nota foi cancelada com sucesso , a copia  da nota foi  enviado  para $email \");</script>";

 }


 //echo " <br> STATUS -> " . $out_var;

?>
