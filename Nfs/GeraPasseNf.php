<? session_start(); ?>
<?
 require("seguranca.php");
?>
<?php
  //Post
  $in_nf = $HTTP_POST_VARS["txt_nf"];
  $emit = $HTTP_POST_VARS["selEmitente"];
  $emitCpf = $HTTP_POST_VARS["txt_EmiCpf"];
  $emitRep = $HTTP_POST_VARS["txt_repart"];

  $mot = $HTTP_POST_VARS["txt_motorista"];
  $motCpf = $HTTP_POST_VARS["txt_cpf"];

  $trans     = $HTTP_POST_VARS["txt_codTrans"];
  $nomTrans  = $HTTP_POST_VARS["txt_DscTrans"];
  $transUf   = $HTTP_POST_VARS["txt_TransUf"];
  $transCnpj   = $HTTP_POST_VARS["txt_TransCnpj"];
  echo "<br>estado $transUf" ;

  $veicPlaca = $HTTP_POST_VARS["txt_placa"];
  $veicUf = $HTTP_POST_VARS["SelUfVeiculo"];

  echo "<br>estado2 $veicUf" ;
  $rebPlaca = $HTTP_POST_VARS["txt_reb"];
  $rebUf = $HTTP_POST_VARS["SelUfReb"];

  echo "<br>estado3 $rebUf" ;
  $rebSecPlaca = $HTTP_POST_VARS["txt_reb"];
  $rebSecUf = $HTTP_POST_VARS["SelRebSec"];

  $Unidade = $HTTP_POST_VARS["SelUnidade"];
  //

  $login  = strtoupper($HTTP_SESSION_VARS["login"]);
  $senha  = $HTTP_SESSION_VARS['senha'];
  $banco  = $HTTP_SESSION_VARS['banco'];
?>

<?php
/******************************************************************
 *
 *
 *****************************************************************/

  //Ler o select
  require_once("cQuery.php");

  $cQuery = new cQuery($login, $senha,$banco,'Oracle');


 //Gerar o Xml
 require_once("cGerarXmlNfs.php");

 $xmlGerarNf = new cGerarXmlNfs("xml/Pfi.xml");

 //Select da Nf
 echo $cQuery->fSelect($in_nf);

if ( $cQuery->execSql($in_nf) == 0 ) {
 echo "<br> Campo1 " . $cQuery->getCampo("NOME");
 echo "<br> Campo2 " . $cQuery->getCampo("EMISSAO");
}

//Data e hora
$xmlGerarNf->setNoValor("dEmi",date("Y-m-d"),"id","pfi",FALSE,FALSE,0);
$xmlGerarNf->setNoValor("hEmi",date("H:i:s"),"id","pfi",FALSE,FALSE,0);


//Dados do fisco quem emite a nota
$xmlGerarNf->setNoValor("CPF",$emitCpf,"id","dadosEmitente",FALSE,FALSE,0);
$xmlGerarNf->setNoValor("UF",$emit,"id","dadosEmitente",FALSE,FALSE,0);
$xmlGerarNf->setNoValor("repEmi",$emitRep,"id","dadosEmitente",FALSE,FALSE,1);
//*******


//Nota
 $xmlGerarNf->setNoValor("nfe:nNF",$cQuery->getCampo("NF"),"id","nota",FALSE,FALSE,0);
 $xmlGerarNf->setNoValor("nfe:dEmi",$cQuery->getCampo("EMISSAO"),"id","nota",FALSE,FALSE,0);
 $xmlGerarNf->setNoValor("nfe:natOp",$cQuery->getCampo("CFOP"),"id","nota",FALSE,FALSE,1);
 //$xmlGerarNf->setNoValor("pesoL",$cQuery->getCampo("PESO_LIQUIDO"),"id","nota",FALSE,FALSE,1);
//*****


//Emitente
 $emit = "COOP.REG. DE ACUCAR E ALCOOL DE ALAGOAS";
 $xmlGerarNf->setNoValor("nfe:CNPJ","12277646000108","id","emitente",FALSE,FALSE,0);
 $xmlGerarNf->setNoValor("nfe:xNome",substr($emit,0,50),"id","emitente",FALSE,FALSE,0);
 $xmlGerarNf->setNoValor("nfe:IE","240015061","id","emitente",FALSE,FALSE,0);
 $xmlGerarNf->setNoValor("nfe:UF","AL","id","emitente",FALSE,FALSE,1);
//********

//Cliente
  $insCli    = str_replace("/","",str_replace("-","",str_replace(".","",$cQuery->getCampo("CINSC"))));
  $cpnjCli   = str_replace("-","",str_replace(".","",$cQuery->getCampo("CGCC")));
  $xmlGerarNf->setNoValor("nfe:CNPJ",$cpnjCli,"id","destinatario",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("nfe:xNome",substr($cQuery->getCampo("NOMEC"),0,50),"id","destinatario",FALSE,FALSE,0);
   $xmlGerarNf->setNoValor("nfe:IE",substr($insCli,0,50),"id","destinatario",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("nfe:UF",$cQuery->getCampo("ESTADO_FAT"),"id","destinatario",FALSE,FALSE,1);
//******

echo "<br> Codigo Classe ".$cQuery->getCampo("COD_CLASSE_FISCAL");
//Produto
  switch( strtoupper($cQuery->getCampo("COD_CLASSE_FISCAL") ) )  {
    case "A" :
	           $strNcm="17011100";
	           break;
    case "B" :
	           $strNcm="17019900";
	           break;
    case "C" :
	           $strNcm="17029000";
	           break;
    case "D" :
	           $strNcm="17031000";
	           break;
    case "E" :
	           $strNcm="39235000";
	           break;

    case "F" :
	           $strNcm="55092200";
	           break;
    case "G" :
	           $strNcm="63053310";
	           break;
    case "H" :
	           $strNcm="63053390";
	           break;
    case "I" :
	           $strNcm="22071000";
	           break;

  }

  //$xmlGerarNf->setNoValor("nfe:NCM",$strNcm,"id","produto",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("nfe:NCM",$cQuery->getCampo("NCM_SEFAZ"),"id","produto",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("nfe:qTrib",number_format($cQuery->getCampo("QUANTIDADE"),3,".",""),"id","produto",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("nfe:uTrib",$Unidade,"id","produto",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("nfe:vProd",number_format($cQuery->getCampo("VALOR"),2,".",""),"id","produto",FALSE,FALSE,1);
//****


//Transportadora
  $xmlGerarNf->setNoValor("CNPJ",$transCnpj,"id","transportadora",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("xNome",substr($nomTrans,0,50),"id","transportadora",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("UF",$transUf,"id","transportadora",FALSE,FALSE,1);
//******

//Motorista
  $xmlGerarNf->setNoValor("CPF",$motCpf,"id","motorista",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("xNome",$mot,"id","motorista",FALSE,FALSE,1);
 //*****

 //Veiculo
   $xmlGerarNf->setNoValor("nfe:placa",$veicPlaca,"id","veiculo",FALSE,FALSE,0);
   $xmlGerarNf->setNoValor("nfe:UF",$veicUf,"id","veiculo",FALSE,FALSE,1);
 //***


 //Reboques
  if (strlen($rebPlaca) > 0 ) {
   $xmlGerarNf->setNoValor("nfe:placa",$rebPlaca,"id","reboque1",FALSE,FALSE,0);
   $xmlGerarNf->setNoValor("nfe:UF",$rebUf,"id","reboque1",FALSE,FALSE,1);
  }

  if (strlen($rebSecPlaca) > 0 ) {
   $xmlGerarNf->setNoValor("nfe:placa",$rebSecPlaca,"id","reboque2",FALSE,FALSE,0);
   $xmlGerarNf->setNoValor("nfe:UF",$rebSecUf,"id","reboque2",FALSE,FALSE,1);
 }
 //******

 //Transportadora2
  $xmlGerarNf->setNoValor("nfe:CNPJ",$transCnpj,"id","transportadora2",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("nfe:xNome",substr($nomTrans,0,50),"id","transportadora2",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("nfe:UF",$transUf,"id","transportadora2",FALSE,FALSE,1);
//******

 //Veiculo2
   $xmlGerarNf->setNoValor("nfe:placa",$veicPlaca,"id","veiculo2",FALSE,FALSE,0);
   $xmlGerarNf->setNoValor("nfe:UF",$veicUf,"id","veiculo2",FALSE,FALSE,1);
 //***

  //Reboques 2
  if (strlen($rebPlaca) > 0 ) {
   $xmlGerarNf->setNoValor("nfe:placa",$rebPlaca,"id","reboque21",FALSE,FALSE,0);
   $xmlGerarNf->setNoValor("nfe:UF",$rebUf,"id","reboque21",FALSE,FALSE,1);
  }
 //******

  //Volume PESO_BRUTO
   $xmlGerarNf->setNoValor("nfe:qVol",number_format($cQuery->getCampo("QUANTIDADE"),0,"",""),"id","volume",FALSE,FALSE,0);
   $xmlGerarNf->setNoValor("nfe:pesoL",number_format($cQuery->getCampo("PESO_LIQUIDO"),3,".",""),"id","volume",FALSE,FALSE,0);
  $xmlGerarNf->setNoValor("nfe:pesoB",number_format($cQuery->getCampo("PESO_BRUTO"),3,".",""),"id","volume",FALSE,FALSE,1);
 //***

// $xmlGerarNf->save("xml/NfsCorreto2.xml");

 //******

  $xmlArq = "xml/".$login.$in_nf.".xml";
  //echo $xmlArq;
  $xmlGerarNf->save($xmlArq);

   echo "<script> location.href =\"NotaGerada.php?nmNota=$xmlArq\"; </script>";


?>
