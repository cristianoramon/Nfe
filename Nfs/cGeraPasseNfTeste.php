<? session_start(); ?>
<?
 require("seguranca.php");

  //Ler o select
 require_once("cQuery.php");

 //Gerar o Xml
 require_once("cGerarXmlNfs.php");

 //Chave de acesso
 require_once("nfs/cChavedeAcesso.php");

 //Assinatura
 require_once("xmlSign/cAssinaXml.php");

 //Verifica Conexao
 require_once("xmlSign/cVerConexao.php");


 //Enviar o XML
 require_once("xmlSign/cEnviaNF.php");

 //Retorno o valor do xml
  require_once("cValorXml.php");

?>

<?php

 class cGeraPasseNf {

	/*
	 *
	 */

	function gerarPasse( $in_nf, $nomRep,$emit , $emitCpf, $emitRep, $mot ,
	                     $motCpf, $trans ,$nomTrans ,$transUf , $transCnpj,
						 $veicPlaca ,$veicUf , $rebPlaca,$rebUf,$rebSecPlaca ,
						 $rebSecUf , $login,$senha,$banco,$perc,
						 $cnpjEmitente,$dscCFOP,$nfComp,$nfRef,
						 $obs){


	  $xml="xml_esquema/Nfe10.xml";


	  //$nfComp = "S";

	  $tipoNota = 1;

	  if ( strtoupper($nfComp) == 'S' ){
	    $tipoNota = 2;
	    $xml="xml_esquema/NfeComplemento10.xml";
		$xmlArqRef = "xml/xml_resposta_recibo/".$login."/".$login."_" . $nfRef. ".xml";
		// "NEWPIRAM_247534.xml";

		//echo $xmlArqRef ."<br>";
		$cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
		$valor = $cValorXml->ValorXmlNameSpaceVetor("//chNFe",$xmlArqRef);
		//echo "<br>Valor" . $valor[0];
	  }


	  $cQuery = new cQuery($login, $senha,$banco,'Oracle');
	  $xmlGerarNf = new cGerarXmlNfs($xml);
	  $chave = new cChavedeAcesso();
	  $cAssinar = new cAssinaXML();
	  $cEnvNF   = new cEnviaNF();
	  $verConn  = new cVerConexao();

	  //Select da Nf
 	  $cQuery->fSelect($in_nf);

	  $cQuery->execSql($in_nf);


	 //Formando a chave UF + AAMM+CNPJ-EMITENTE+MODELO+SERIE+NNF+CONDNUM
	 $strChave =  "27".date("ym")."12277646000304"."55"."000".$cQuery->getCampo("NFDV").$cQuery->getCampo("CNF");
	 //$strChave = "2708011227764600010855000000247418024741818";
	 $somPod   =  $chave->fStrPonderacao ($strChave,10,2);
	 $dv       =  $chave->fDigitoDV (11,$somPod);
	 //****



    //Dados do fisco quem emite a nota
	$xmlGerarNf->setNoValor("tpEmi","C","id","dadosEmitente",FALSE,FALSE,0,0);
    $xmlGerarNf->setNoValor("CPF",$emitCpf,"id","dadosEmitente",FALSE,FALSE,0,0);
    $xmlGerarNf->setNoValor("UF",$emit,"id","dadosEmitente",FALSE,FALSE,0,0);
    $xmlGerarNf->setNoValor("repEmi",substr($emitRep,0,50),"id","dadosEmitente",FALSE,FALSE,0,0);
	$xmlGerarNf->setNoValor("CNPJ",$cnpjEmitente,"id","dadosEmitente",FALSE,FALSE,0,0);
	$xmlGerarNf->setNoValor("xNome",substr($nomRep,0,50),"id","dadosEmitente",FALSE,FALSE,1,0);
   //*******


   //identificacao da Nota	nfe
   $xmlGerarNf->setNoValor("nNF",$cQuery->getCampo("NF"),"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("dEmi",$cQuery->getCampo("EMISSAO"),"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("dSaiEnt",$cQuery->getCampo("EMISSAO"),"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("cNF",$cQuery->getCampo("CNF"),"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("finNFe",$tipoNota,"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("cDV",$dv,"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("natOp",substr($dscCFOP,0,50),"id","nfe",FALSE,FALSE,0,0);

   $xmlGerarNf->setNoValor("refNFe",$valor[0],"id","nfe",FALSE,FALSE,0,0);




   //$xmlGerarNf->InsertElementSemAtributo('NFref','refNFe',"00000000");
  //*****


  //Emitente  nfe
  $emit = "COOP.REG";

  $xmlGerarNf->setNoValor("CNPJ","12277646000304","id","emitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xNome",rtrim(trim(substr($emit,0,48))),"id","emitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("IE","240015045","id","emitente",FALSE,FALSE,1,0);
  //*****
 // $xmlGerarNf->setNoValor("nfe:IE","240015061","id","emitente",FALSE,FALSE,0);

 //Endereco do Emitente nfe
  $end  = "RUA SA E ALBUQUERQU";
  $nu   = "234";

  $xmlGerarNf->setNoValor("xLgr",trim(htmlentities(substr($end,0,60))),"id","endEmitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("nro",$nu,"id","endEmitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xBairro","Jaragua","id","endEmitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("cMun","2704302","id","endEmitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xMun","Maceio","id","endEmitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("UF","AL","id","endEmitente",FALSE,FALSE,1,0);
  //********

  //Cliente  nfe estado_fat
  $insCli    = str_replace("/","",str_replace("-","",str_replace(".","",$cQuery->getCampo("CINSC"))));
  $cpnjCli   = str_replace("-","",str_replace(".","",$cQuery->getCampo("CGCC")));
  $xmlGerarNf->setNoValor("CNPJ",$cpnjCli,"id","destinatario",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xNome",rtrim(trim(substr(htmlentities($cQuery->getCampo("NOMEC")),0,50),"\t\n\r\0\x0B" ),"' ' "),"id","destinatario",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xMun",substr($cQuery->getCampo("ESTADO_FAT"),0,50),"id","destinatario",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("IE",substr($insCli,0,50),"id","destinatario",FALSE,FALSE,1,0);
  //******


  //Endereço do Cliente nfe  2611606- 2927408
  $xmlGerarNf->setNoValor("xLgr",trim(substr(htmlentities($cQuery->getCampo("ENDERECO_FAT")),0,60)),"id","destEndereco",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("nro",$nu,"id","destEndereco",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xBairro",substr($cQuery->getCampo("BAIRRO_FAT"),0,60),"id","destEndereco",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("cMun",substr($cQuery->getCampo("COD_IBGE"),0,7),"id","destEndereco",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xMun",substr($cQuery->getCampo("CIDADE_FAT"),0,50),"id","destEndereco",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("UF",$cQuery->getCampo("ESTADO_FAT"),"id","destEndereco",FALSE,FALSE,1,0);
  //******




ociexecute($cQuery->getStat());
$qt = 1;
while( ocifetch($cQuery->getStat()) ) {


	 echo "<br>Qtreg" .$qt."-".$cQuery->getCampo("CODPROD")."<br>";


  $xmlGerarNf->InsertElementAtrr('infNFe','det','','nItem',$qt);



  //Produtos Nfe
  $xmlGerarNf->InsertElementSemAtributo('det','prod','',$qt,'det',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','cProd',$cQuery->getCampo("CODPROD"),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','cEAN','',$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','xProd',substr(htmlentities($cQuery->getCampo("DESCRICAO")),0,50),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','CFOP',$cQuery->getCampo("CFOP"),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','uCom',$cQuery->getCampo("SIMBOLO"),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','qCom',number_format($cQuery->getCampo("QUANTIDADE"),4,".",""),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','vUnCom',number_format($cQuery->getCampo("PRECO"),4,".",""),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','vProd',number_format(round( $cQuery->getCampo("QUANTIDADE") * $cQuery->getCampo("PRECO")),2,".",""),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','cEANTrib','',$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','uTrib',$cQuery->getCampo("SIMBOLO"),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','qTrib',number_format($cQuery->getCampo("QUANTIDADE"),4,".",""),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','vUnTrib',number_format($cQuery->getCampo("PRECO"),4,".",""),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('det','imposto','',$qt,'det',1);

  //$xmlGerarNf->RemoveAttr('prod','nItem',$qt);
  //****
   $qt++;
  }

   $xmlGerarNf->setNoValor("tpEmis","1","id","nfe",FALSE,FALSE,1,0);
   $xmlArq = "xml/xml_nao_assinado/".$login."/".$login."_".$in_nf.".xml";
   $xmlGerarNf->save($xmlArq);

 }
}
?>
