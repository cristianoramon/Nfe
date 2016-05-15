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
	 $strChave =  "27".date("ym")."12277646000203"."55"."000".$cQuery->getCampo("NFDV").$cQuery->getCampo("CNF");
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
  $emit = "COOP.TESTE";

  $xmlGerarNf->setNoValor("CNPJ","12277646000345","id","emitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xNome",rtrim(trim(substr($emit,0,48))),"id","emitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("IE","240015045","id","emitente",FALSE,FALSE,1,0);
  //*****
 // $xmlGerarNf->setNoValor("nfe:IE","240015061","id","emitente",FALSE,FALSE,0);

 //Endereco do Emitente nfe
  $end  = "RUA SA E ALBUQUERQUE";
  $nu   = "245";

  $xmlGerarNf->setNoValor("xLgr",trim(htmlentities(substr($end,0,60))),"id","endEmitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("nro",$nu,"id","endEmitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xBairro","Tabuleiro","id","endEmitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("cMun","2704334","id","endEmitente",FALSE,FALSE,0,0);
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



  // Produto nfe
  $xmlGerarNf->setNoValor("cProd",$cQuery->getCampo("CODPROD"),"id","produto",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("NCM",$cQuery->getCampo("NCM_SEFAZ"),"id","produto",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xProd",substr(htmlentities($cQuery->getCampo("DESCRICAO")),0,50),"id","produto",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("CFOP",$cQuery->getCampo("CFOP"),"id","produto",FALSE,FALSE,0,0);

  $xmlGerarNf->setNoValor("uCom",$cQuery->getCampo("SIMBOLO"),"id","produto",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("qCom",number_format($cQuery->getCampo("QUANTIDADE"),4,".",""),"id","produto",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("vUnCom",number_format($cQuery->getCampo("PRECO"),4,".",""),"id","produto",FALSE,FALSE,0,0);

  $xmlGerarNf->setNoValor("vProd",number_format(round( $cQuery->getCampo("QUANTIDADE") * $cQuery->getCampo("PRECO") ,2),2,".",""),"id","produto",FALSE,FALSE,0,0);

  $xmlGerarNf->setNoValor("uTrib",$cQuery->getCampo("SIMBOLO"),"id","produto",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("qTrib",number_format($cQuery->getCampo("QUANTIDADE"),4,".",""),"id","produto",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("vUnTrib",number_format($cQuery->getCampo("PRECO"),4,".",""),"id","produto",FALSE,FALSE,1,0);
  //$xmlGerarNf->setNoValor("nfe:cSIMP",$cQuery->getCampo("COD_ANP"),"id","combustivel",FALSE,FALSE,1);
  //****

   //Icms nfe

  //Tributado Integralmente
   if ( ($cQuery->getCampo("COD_CBT") == "00")   ||  ($cQuery->getCampo("COD_CBT") == "55")  ) {

    $xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS00','');
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','orig','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','CST','00');
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','modBC','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','vBC', number_format( $cQuery->getCampo("BASE_ICMS"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','pICMS', number_format( $cQuery->getCampo("ALQ_ICMS"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','vICMS', number_format( $cQuery->getCampo("VAL_ICMS"),2,".",""));
   }

   //tributado com atributaçao do icms subst
   if ( $cQuery->getCampo("COD_CBT") == "66" ) {

    $xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS10','');
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','orig','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','CST','10');
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','modBC','1');
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','vBC',  number_format($cQuery->getCampo("BASE_ICMS") ,2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','pICMS',number_format($cQuery->getCampo("ALQ_ICMS") ,2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','vICMS',number_format($cQuery->getCampo("VAL_ICMS") ,2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','modBCST','5');
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','vBCST',number_format($cQuery->getCampo("BASE_ICMSF") ,2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','pICMSST',number_format($cQuery->getCampo("ALQ_ICMSF") ,2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','vICMSST',number_format($cQuery->getCampo("VAL_ICMSF") ,2,".",""));
   }

   //Tributado com reduçao de base icms
   if ( $cQuery->getCampo("COD_CBT") == "70" ) {

    $xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS20','');
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','orig','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','CST','20');
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','modBC','0');
	//Falta
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','pRedBC','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','vBC',  number_format($cQuery->getCampo("BASE_ICMS") ,2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','pICMS',number_format($cQuery->getCampo("ALQ_ICMS") ,2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','vICMS',number_format($cQuery->getCampo("VAL_ICMS") ,2,".",""));
   }


    //Isenta ou tributado com combrança o ICMS subst
   if ( $cQuery->getCampo("COD_CBT") == "-1" ) {

    $xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS30','');
	$xmlGerarNf->InsertElementSemAtributo('ICMS30','orig','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMS30','CST','30');
	$xmlGerarNf->InsertElementSemAtributo('ICMS30','vBCST',number_format($cQuery->getCampo("BASE_ICMSF"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS30','pICMSST',number_format($cQuery->getCampo("ALQ_ICMSF"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS30','vICMSST',number_format($cQuery->getCampo("VAL_ICMSF"),2,".",""));
   }

    if ( ( $cQuery->getCampo("COD_CBT") == "09" ) ||  ($cQuery->getCampo("COD_CBT") == "88") ) {

    $xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS90','');
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','orig','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','CST','90');
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','modBC','1');
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','vBC',  number_format($cQuery->getCampo("BASE_ICMS"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','pICMS',number_format($cQuery->getCampo("ALQ_ICMS"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','vICMS',number_format($cQuery->getCampo("VAL_ICMS"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','modBCST','5');
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','vBCST',number_format($cQuery->getCampo("BASE_ICMSF"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','pICMSST',number_format($cQuery->getCampo("ALQ_ICMSF"),2,".",""));
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','vICMSST',number_format($cQuery->getCampo("VAL_ICMSF"),2,".",""));
   }


   /*****************/
    //Tributacao pelo icms

   if ( ( $cQuery->getCampo("COD_CBT") == "04")  ) {

    $xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS40','');
	$xmlGerarNf->InsertElementSemAtributo('ICMS40','orig','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMS40','CST','41');

   }

    //Diferimento
   if ( ( $cQuery->getCampo("COD_CBT") == "05" )  || ( $cQuery->getCampo("COD_CBT") == "99"  ) ) {

    $xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS51','');
	$xmlGerarNf->InsertElementSemAtributo('ICMS51','orig','0');
	$xmlGerarNf->InsertElementSemAtributo('ICMS51','CST','51');

   }



   //*****


  //Transportadora  nfe
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

  $xmlGerarNf->setNoValor("modFrete",1,"id","transp",FALSE,FALSE,1,0);
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


   //icmsTotal nfe
    $xmlGerarNf->setNoValor("vBC",number_format($cQuery->getCampo("BASE_ICMS"),2,".",""),"id","icms",FALSE,FALSE,0,0);
    $xmlGerarNf->setNoValor("vICMS",number_format($cQuery->getCampo("VAL_ICMS"),2,".",""),"id","icms",FALSE,FALSE,0,0);
	$xmlGerarNf->setNoValor("vBCST",number_format($cQuery->getCampo("BASE_ICMSF"),2,".",""),"id","icms",FALSE,FALSE,0,0);
	$xmlGerarNf->setNoValor("vST",number_format($cQuery->getCampo("VAL_ICMSF"),2,".",""),"id","icms",FALSE,FALSE,0,0);
	$xmlGerarNf->setNoValor("vProd",number_format(round( $cQuery->getCampo("QUANTIDADE") * $cQuery->getCampo("PRECO") ,2),2,".",""),"id","icms",FALSE,FALSE,0,0);
	$xmlGerarNf->setNoValor("vIPI",number_format($cQuery->getCampo("VAL_IPI"),2,".",""),"id","icms",FALSE,FALSE,0,0);
	$xmlGerarNf->setNoValor("vPIS",number_format($cQuery->getCampo("VAL_PIS"),2,".",""),"id","icms",FALSE,FALSE,0,0);
	$xmlGerarNf->setNoValor("vCOFINS",number_format($cQuery->getCampo("VAL_COFINS"),2,".",""),"id","icms",FALSE,FALSE,0,0);
    $xmlGerarNf->setNoValor("vNF",number_format($cQuery->getCampo("VALOR"),2,".",""),"id","icms",FALSE,FALSE,1,0);
   //***

   //PIS

     $Porcentagem = 0;
	 $PorcentagemConfins = 0;


	 if ( $cQuery->getCampo("VALOR") > 0 )  {
      $Porcentagem = ( $cQuery->getCampo("VAL_PIS") * 100 ) / $cQuery->getCampo("VALOR");
	  $PorcentagemConfins = ( $cQuery->getCampo("VAL_COFINS") * 100 ) / $cQuery->getCampo("VALOR");
	 }


	 $xmlGerarNf->setNoValor("CST","01","id","aliqPIS",FALSE,FALSE,0,0);
	 $xmlGerarNf->setNoValor("vBC",number_format( $Porcentagem * $cQuery->getCampo("VALOR"),2,".","") ,"id","aliqPIS",FALSE,FALSE,0,0);
	 $xmlGerarNf->setNoValor("pPIS", number_format( $Porcentagem,2,".",""),"id","aliqPIS",FALSE,FALSE,0,0);
	 $xmlGerarNf->setNoValor("vPIS", number_format($cQuery->getCampo("VAL_PIS"),2,".","") ,"id","aliqPIS",FALSE,FALSE,1,0);

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
