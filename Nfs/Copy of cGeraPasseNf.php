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
  $emit = "COOP.REG.";

  $xmlGerarNf->setNoValor("CNPJ","12277646000203","id","emitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xNome",rtrim(trim(substr($emit,0,48))),"id","emitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("IE","240015044","id","emitente",FALSE,FALSE,1,0);
  //*****
 // $xmlGerarNf->setNoValor("nfe:IE","240015061","id","emitente",FALSE,FALSE,0);

 //Endereco do Emitente nfe
  $end  = "RUA SA E ALBUQUERQUE "";
  $nu   = "232";

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
  $xmlGerarNf->InsertElementAtrr('det','prod','','nItem',$qt);
  $xmlGerarNf->InsertElement('prod','cProd',$cQuery->getCampo("CODPROD"),'nItem',$qt);
  $xmlGerarNf->InsertElement('prod','cEAN','','nItem',$qt);
  $xmlGerarNf->InsertElement('prod','xProd',substr(htmlentities($cQuery->getCampo("DESCRICAO")),0,50),'nItem',$qt);
  $xmlGerarNf->InsertElement('prod','CFOP',$cQuery->getCampo("CFOP"),'nItem',$qt);
  $xmlGerarNf->InsertElement('prod','uCom',$cQuery->getCampo("SIMBOLO"),'nItem',$qt);
  $xmlGerarNf->InsertElement('prod','qCom',number_format($cQuery->getCampo("QUANTIDADE"),4,".",""),'nItem',$qt);
  $xmlGerarNf->InsertElement('prod','vUnCom',number_format($cQuery->getCampo("PRECO"),4,".",""),'nItem',$qt);
  $xmlGerarNf->InsertElement('prod','vProd',number_format(round( $cQuery->getCampo("QUANTIDADE") * $cQuery->getCampo("PRECO")),2,".",""),'nItem',$qt);
  $xmlGerarNf->InsertElement('prod','cEANTrib','','nItem',$qt);
  $xmlGerarNf->InsertElement('prod','uTrib',$cQuery->getCampo("SIMBOLO"),'nItem',$qt);
  $xmlGerarNf->InsertElement('prod','qTrib',number_format($cQuery->getCampo("QUANTIDADE"),4,".",""),'nItem',$qt);
  $xmlGerarNf->InsertElement('prod','vUnTrib',number_format($cQuery->getCampo("PRECO"),4,".",""),'nItem',$qt);
  //$xmlGerarNf->RemoveAttr('prod','nItem',$qt);
  //****

   //Impostos
   $xmlGerarNf->InsertElementAtrr('det','imposto','','nItem',$qt);

   //Icms nfe
   $xmlGerarNf->InsertElementAtrr('imposto','ICMS','','nItem',$qt);

  //Tributado Integralmente
   if ( ($cQuery->getCampo("COD_CBT") == "00")   ||  ($cQuery->getCampo("COD_CBT") == "55")  ) {


	$xmlGerarNf->InsertElementAtrr('ICMS','ICMS00','','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS00','orig','0','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS00','CST','00','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS00','modBC','0','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS00','vBC', number_format( $cQuery->getCampo("BASE_ICMS"),2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS00','pICMS', number_format( $cQuery->getCampo("ALQ_ICMS"),2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS00','vICMS', number_format( $cQuery->getCampo("VAL_ICMS"),2,".",""),'nItem',$qt);
	//$xmlGerarNf->RemoveAttr('ICMS00','nItem',$qt);
   }

   //tributado com atributaçao do icms subst
   if ( $cQuery->getCampo("COD_CBT") == "66" ) {

	$xmlGerarNf->InsertElementAtrr('ICMS','ICMS10','','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS10','orig','0','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS10','CST','10','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS10','modBC','1','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS10','vBC',  number_format($cQuery->getCampo("BASE_ICMS") ,2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS10','pICMS',number_format($cQuery->getCampo("ALQ_ICMS") ,2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS10','vICMS',number_format($cQuery->getCampo("VAL_ICMS") ,2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS10','modBCST','5','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS10','vBCST',number_format($cQuery->getCampo("BASE_ICMSF") ,2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS10','pICMSST',number_format($cQuery->getCampo("ALQ_ICMSF") ,2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS10','vICMSST',number_format($cQuery->getCampo("VAL_ICMSF") ,2,".",""),'nItem',$qt);
	//$xmlGerarNf->RemoveAttr('ICMS10','nItem',$qt);
   }

   //Tributado com reduçao de base icms
   if ( $cQuery->getCampo("COD_CBT") == "70" ) {

	$xmlGerarNf->InsertElementAtrr('ICMS','ICMS20','','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS20','orig','0','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS20','CST','20','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS20','modBC','0','nItem',$qt);
	//Falta
	$xmlGerarNf->InsertElement('ICMS20','pRedBC','0','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS20','vBC',  number_format($cQuery->getCampo("BASE_ICMS") ,2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS20','pICMS',number_format($cQuery->getCampo("ALQ_ICMS") ,2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS20','vICMS',number_format($cQuery->getCampo("VAL_ICMS") ,2,".",""),'nItem',$qt);
	//$xmlGerarNf->RemoveAttr('ICMS20','nItem',$qt);
   }


    //Isenta ou tributado com combrança o ICMS subst
   if ( $cQuery->getCampo("COD_CBT") == "-1" ) {

	$xmlGerarNf->InsertElementAtrr('ICMS','ICMS30','','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS30','orig','0','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS30','CST','30','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS30','vBCST',number_format($cQuery->getCampo("BASE_ICMSF"),2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS30','pICMSST',number_format($cQuery->getCampo("ALQ_ICMSF"),2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS30','vICMSST',number_format($cQuery->getCampo("VAL_ICMSF"),2,".",""),'nItem',$qt);
	//$xmlGerarNf->RemoveAttr('ICMS30','nItem',$qt);
   }

    if ( ( $cQuery->getCampo("COD_CBT") == "09" ) ||  ($cQuery->getCampo("COD_CBT") == "88") ) {


	$xmlGerarNf->InsertElementAtrr('ICMS','ICMS90','','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS90','orig','0','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS90','CST','90','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS90','modBC','1','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS90','vBC',  number_format($cQuery->getCampo("BASE_ICMS"),2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS90','pICMS',number_format($cQuery->getCampo("ALQ_ICMS"),2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS90','vICMS',number_format($cQuery->getCampo("VAL_ICMS"),2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS90','modBCST','5','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS90','vBCST',number_format($cQuery->getCampo("BASE_ICMSF"),2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS90','pICMSST',number_format($cQuery->getCampo("ALQ_ICMSF"),2,".",""),'nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS90','vICMSST',number_format($cQuery->getCampo("VAL_ICMSF"),2,".",""),'nItem',$qt);
	//$xmlGerarNf->RemoveAttr('ICMS90','nItem',$qt);
   }


   /*****************/
    //Tributacao pelo icms

   if ( ( $cQuery->getCampo("COD_CBT") == "04")  ) {

    $xmlGerarNf->InsertElementAtrr('ICMS','ICMS40','','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS40','orig','0','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS40','CST','41','nItem',$qt);
	//$xmlGerarNf->RemoveAttr('ICMS40','nItem',$qt);

   }

    //Diferimento
   if ( ( $cQuery->getCampo("COD_CBT") == "05" )  || ( $cQuery->getCampo("COD_CBT") == "99"  ) ) {

    $xmlGerarNf->InsertElementAtrr('ICMS','ICMS51','','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS','ICMS51','','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS51','orig','0','nItem',$qt);
	$xmlGerarNf->InsertElement('ICMS51','CST','51','nItem',$qt);
	//$xmlGerarNf->RemoveAttr('ICMS51','nItem',$qt);

   }


   //Removendo Atributo ICMS
   //$xmlGerarNf->RemoveAttr('ICMS','nItem',$qt);
   //IPI

   $xmlGerarNf->InsertElementAtrr('imposto','IPI','','nItem',$qt);
   $xmlGerarNf->InsertElement('IPI','clEnq','Str','nItem',$qt);
   $xmlGerarNf->InsertElement('IPI','CNPJProd','00000000000000','nItem',$qt);
   $xmlGerarNf->InsertElement('IPI','cSelo','Str','nItem',$qt);
   $xmlGerarNf->InsertElement('IPI','qSelo','0','nItem',$qt);
   $xmlGerarNf->InsertElement('IPI','cEnq','Str','nItem',$qt);


   $xmlGerarNf->InsertElementAtrr('IPI','IPITrib','','nItem',$qt);
   $xmlGerarNf->InsertElement('IPITrib','CST','00','nItem',$qt);
   $xmlGerarNf->InsertElement('IPITrib','vBC','0','nItem',$qt);
   $xmlGerarNf->InsertElement('IPITrib','pIPI','0','nItem',$qt);
   $xmlGerarNf->InsertElement('IPITrib','vIPI','0','nItem',$qt);
   //$xmlGerarNf->RemoveAttr('IPITrib','nItem',$qt);
   //$xmlGerarNf->RemoveAttr('IPI','nItem',$qt);

   //PIS
   $Porcentagem = 0;
   $PorcentagemConfins = 0;


	if ( $cQuery->getCampo("VALOR") > 0 )  {
      $Porcentagem = ( $cQuery->getCampo("VAL_PIS") * 100 ) / $cQuery->getCampo("VALOR");
	  $PorcentagemConfins = ( $cQuery->getCampo("VAL_COFINS") * 100 ) / $cQuery->getCampo("VALOR");
	}




   $xmlGerarNf->InsertElementAtrr('imposto','PIS','','nItem',$qt);
   $xmlGerarNf->InsertElementAtrr('PIS','PISAliq','','nItem',$qt);
   $xmlGerarNf->InsertElement('PISAliq','CST','01','nItem',$qt);
   $xmlGerarNf->InsertElement('PISAliq','vBC',number_format( $Porcentagem * $cQuery->getCampo("VALOR"),2,".",""),'nItem',$qt);
   $xmlGerarNf->InsertElement('PISAliq','pPIS',number_format( $Porcentagem,2,".",""),'nItem',$qt);
   $xmlGerarNf->InsertElement('PISAliq','vPIS',number_format($cQuery->getCampo("VAL_PIS"),2,".",""),'nItem',$qt);
   //$xmlGerarNf->RemoveAttr('PISAliq','nItem',$qt);
   //$xmlGerarNf->RemoveAttr('PIS','nItem',$qt);

   //COFINS
    $xmlGerarNf->InsertElementAtrr('imposto','COFINS','','nItem',$qt);
   $xmlGerarNf->InsertElementAtrr('COFINS','COFINSAliq','','nItem',$qt);
   $xmlGerarNf->InsertElement('COFINSAliq','CST','01','nItem',$qt);
   $xmlGerarNf->InsertElement('COFINSAliq','vBC','0','nItem',$qt);
   $xmlGerarNf->InsertElement('COFINSAliq','pCOFINS','0','nItem',$qt);
   $xmlGerarNf->InsertElement('COFINSAliq','vCOFINS','0','nItem',$qt);
  // $xmlGerarNf->RemoveAttr('COFINSAliq','nItem',$qt);
   //$xmlGerarNf->RemoveAttr('COFINS','nItem',$qt);
   //$xmlGerarNf->RemoveAttr('imposto','nItem',$qt);

   $qt++;
 }

   //*****



   /******************************************* Detalhe do produto ***********************************/

    $cQuery->execSql($in_nf);

   //Total da Nota fiscal
    //icmsTotal nfe
	$xmlGerarNf->InsertElementSemAtributo('infNFe','total','');
	$xmlGerarNf->InsertElementSemAtributo('total','ICMSTot','');
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vBC',number_format($cQuery->getCampo("BASE_ICMS"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vICMS',number_format($cQuery->getCampo("VAL_ICMS"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vBCST',number_format($cQuery->getCampo("BASE_ICMSF"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vST',number_format($cQuery->getCampo("VAL_ICMSF"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vProd',number_format(round( $cQuery->getCampo("QUANTIDADE") * $cQuery->getCampo("PRECO")),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vFrete','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vSeg','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vDesc','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vII','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vIPI',number_format($cQuery->getCampo("VAL_IPI"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vPIS',number_format($cQuery->getCampo("VAL_PIS"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vCOFINS',number_format($cQuery->getCampo("VAL_COFINS"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vOutro','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vNF',number_format($cQuery->getCampo("VALOR"),2,".",""));
   //***


  //Transportadora  nfe
  $xmlGerarNf->InsertElementSemAtributo('infNFe','transp','');
  $xmlGerarNf->InsertElementSemAtributo('transp','modFrete',1);


  if ( strlen($transCnpj) > 0 ) {

	$xmlGerarNf->InsertElementSemAtributo('transp','transporta','');
	$xmlGerarNf->InsertElementSemAtributo('transporta','CNPJ',$transCnpj);
	$xmlGerarNf->InsertElementSemAtributo('transporta','xNome',substr($nomTrans,0,50));
	//$xmlGerarNf->InsertElementSemAtributo('transporta','IE',$rebUf);

	if ( strlen($cQuery->getCampo("ENDFAT") ) > 5 )
	   $xmlGerarNf->InsertElementSemAtributo('transporta','xEnder',trim(substr(htmlentities($cQuery->getCampo("ENDFAT")),0,50)));


	if (strlen($cQuery->getCampo("CIDTRANSP") )  > 2 )
	  $xmlGerarNf->InsertElementSemAtributo('transporta','xMun',$cQuery->getCampo("CIDTRANSP"));

	if (strlen($cQuery->getCampo("UFTRANSP") )  > 1 )
	  $xmlGerarNf->InsertElementSemAtributo('transporta','UF',substr($cQuery->getCampo("UFTRANSP"),0,2) );

	$qt++;

  }
  //******




  //*****

   //Veiculo nfe
   if ( strlen($veicPlaca) > 0 ) {

	 $xmlGerarNf->InsertElementSemAtributo('transp','veicTransp','');
	 $xmlGerarNf->InsertElementSemAtributo('veicTransp','placa',$veicPlaca);
	 $xmlGerarNf->InsertElementSemAtributo('veicTransp','UF',$veicUf);

   }

   //***


   //Reboques  nfe
  if (strlen($rebPlaca) > 0 ) {

     $xmlGerarNf->InsertElementSemAtributo('transp','reboque','');
	 $xmlGerarNf->InsertElementSemAtributo('reboque','placa',$rebPlaca);
	 $xmlGerarNf->InsertElementSemAtributo('reboque','UF',$rebUf);

  }

  if (strlen($rebSecPlaca) > 0 ) {

    $xmlGerarNf->InsertElementSemAtributo('transp','reboque','');
	$xmlGerarNf->InsertElementSemAtributo('reboque','placa',$rebPlaca);
	$xmlGerarNf->InsertElementSemAtributo('reboque','UF',$rebUf);

 }



  //Volume nfe
  $xmlGerarNf->InsertElementSemAtributo('transp','vol','');


  if ( strlen($cQuery->getCampo("SIGLA")) > 2 )
   $xmlGerarNf->InsertElementSemAtributo('vol','marca',$cQuery->getCampo("SIGLA"));

  if (  ( float ) $cQuery->getCampo("PESO_LIQUIDO") > 1 )
   $xmlGerarNf->InsertElementSemAtributo('vol','pesoL',number_format($cQuery->getCampo("PESO_LIQUIDO"),3,".",""));

  if (  ( float ) $cQuery->getCampo("PESO_BRUTO") > 1 )
   $xmlGerarNf->InsertElementSemAtributo('vol','pesoB',number_format($cQuery->getCampo("PESO_BRUTO"),3,".",""));


 //******



//Inf Adicional
 $arrSubstituir = array("<",">","'","\"","º","&","$","´","%");
 $arrValorSubst = array(" &lt; "," &gt; "," &#39; "," &quot; "," - "," - "," - "," - "," - ");



 $obsNF = rtrim(trim( ( $cQuery->getCampo("COMP_OBS") . $cQuery->getCampo("DSC_OBSCORPONF") .  $obs ),"\t\n\r\0\x0B"),"\t\n\r\0\x0B");


 $obsNF = str_replace($arrSubstituir, $arrValorSubst, $obsNF);




 if (strlen($obsNF) > 10 ) {
  $xmlGerarNf->InsertElementSemAtributo('infNFe','infAdic','');
  $xmlGerarNf->InsertElementSemAtributo('infAdic','infCpl',substr(trim($obsNF),0,4999));
 }






   //****

 //******

 //Verificado o servidor estar OK
  $cabecalho = "xml/xml_cabecalho/CabCalho1.07.xml";
  $link = "https://homologacao.nfe.sefazvirtual.rs.gov.br/ws/nfestatusservico/nfestatusservico.asmx?wsdl";
  $metodo = "nfeStatusServicoNF";
  $dir = "xml/xml_status_servico/";
  $data = date("d_m_Y_H_m_i");
  $arqStatus = realpath("xml_esquema/statusServ.xml");

  $boolConexao = false;

  if ( $verConn->fVerificaConexaoSSL("homologacao.nfe.sefazvirtual.rs.gov.br/ws/nferecepcao/nferecepcao.asmx?wsdl" ) == 0 )
    $boolConexao = true;



  if (  ( $boolConexao ) && ( $cEnvNF->fVerServStatus($link, $arqStatus, $login,$data,$cabecalho,$dir,$metodo,2) == 0 ) ) {

      $arqXML = $dir = "xml/xml_status_servico/" . $login ."/".  $login ."_".$data.".xml";

	  //Lendo o XML statusServico
	  $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
	  $Status = $cValorXml->ValorXmlNameSpaceVetor("//cStat",$arqXML);
	  $valor =  $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$arqXML);


	   //$Status[0] = 108;


	  //Serviço em operacao
	  if ( (int) $Status[0] == 107 )  {

		  $xmlGerarNf->setNoValor("tpEmis","1","id","nfe",FALSE,FALSE,1,0);
		  $xmlArq = "xml/xml_nao_assinado/".$login."/".$login."_".$in_nf.".xml";
          $xmlGerarNf->save($xmlArq);

          //Assinando o arquivo XML
		   $arqSalvar ="xml/xml_assinado/";
          $arqXmlAssinado= $cAssinar->fAssinXML( $xmlArq ,"Nfe".$strChave.$dv , $login ,$in_nf,$arqSalvar);


         //Enviando o XML
         $cabecalho = "xml/xml_cabecalho/CabCalho.xml";
         $dir = "xml/xml_resposta/";
         $link = "https://homologacao.nfe.sefazvirtual.rs.gov.br/ws/nferecepcao/nferecepcao.asmx?wsdl";
         $metodo = "nfeRecepcaoLote";
         $cEnvNF->cEnviaXML($link, $arqXmlAssinado, $login ,$in_nf,$cabecalho,$dir,$metodo,0);


	  }
  }
  else{

     //Contigencia
	  $xmlGerarNf->setNoValor("tpEmis","2","id","nfe",FALSE,FALSE,1,0);
	  $xmlArq = "xml/xml_nao_assinado_contigencia/".$login."/".$login."_".$in_nf.".xml";
      $xmlGerarNf->save($xmlArq);

      //Assinando o arquivo XML
	  $arqSalvar ="xml/xml_assinado_contigente/";
      $arqXmlAssinado= $cAssinar->fAssinXML( $xmlArq ,"Nfe".$strChave.$dv , $login ,$in_nf,$arqSalvar);

	  //Assinando o arquivo XML tmp
	  $arqSalvar ="xml/xml_assinado_contigente_tmp/";
      $arqXmlAssinado= $cAssinar->fAssinXML( $xmlArq ,"Nfe".$strChave.$dv , $login ,$in_nf,$arqSalvar);


     echo "<script>alert(\"Estado de Contigencia ! Problema com a conexao \");</script>";
  }

  /*****/




  //**/

  return 	$xmlArq;
  }

 }

?>
