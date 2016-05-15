<? session_start(); ?>
<?


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

 //Classe Social
 require_once("cClasseFiscal.php");

 //Classe Configuracao
 require_once("cConfigura.php");

 //Tnsname
 require_once("tnsNames/cTsnames.php");


?>

<?php

 class cGeraPasseNf {

	/*
	 *
	 */

	function gerarPasse( $in_nf, $nomRep,$emit , $emitCpf, $emitRep, $filial ,
	                     $motCpf, $trans ,$nomTrans ,$transUf , $transCnpj,
						 $veicPlaca ,$veicUf , $rebPlaca,$rebUf,$rebSecPlaca ,
						 $rebSecUf , $login,$senha,$banco,$pedido,
						 $cnpjEmitente,$dscCFOP,$nfComp,$nfRef,
						 $obs,$serie){


	  $xml="xml_esquema/Nfe10.xml";


	  // echo "<br>Banco  22 "  . $banco;

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


	  $tnsName = new cTnsName();


	  $db = $tnsName->fTnsNames($banco);
      //echo "<br> TnsName--- 2 " . $banco .'--'. $db . "-----<br>";


	  $cQuery = new cQuery($login, $senha,$db,'Oracle');

	  $xmlGerarNf = new cGerarXmlNfs($xml);

	  $chave = new cChavedeAcesso();
	  $cAssinar = new cAssinaXML();
	  $cEnvNF   = new cEnviaNF("");
	  $verConn  = new cVerConexao();
	  $cClasse  = new cClasseFiscal();
	  $cConf    = new cConfigura();



	  //Select da Nf
 	  //echo "<br>SELECT "  .  $cQuery->fSelect(str_pad($in_nf, 6, "0", STR_PAD_LEFT),$serie,$filial);

	  $cQuery->execSql(str_pad($in_nf, 6, "0", STR_PAD_LEFT),$serie,$filial);

	  $cnpj = "12277646000203";
      $ins  = "240015055";
      $emit = "COOP.REG";


      if ( $filial == '002' ) {
            $cnpj = "08426389000204";
            $ins  = "240650733";
            $emit = "COMERCIO EXP. E IMPORT. S.A.";

      }

	 //Formando a chave UF + AAMM+CNPJ-EMITENTE+MODELO+SERIE+NNF+CONDNUM
	 $strChave =  "27".date("ym").$cnpj."55"."001".$cQuery->getCampo("NFDV").$cQuery->getCampo("CNF");
	 //$strChave = "27080412277646000108550010000003190000319075";
	 //echo "<br>Nota -> ".$cQuery->getCampo("NFDV") . "<br>";
	 //$strChave = "2708011227764600010855000000247418024741818";
	 $somPod   =  $chave->fStrPonderacao ($strChave,10,2);
	 $dv       =  $chave->fDigitoDV (11,$somPod);
	 //****


    //Dados do lote
	//$xmlGerarNf->InsertElementSemAtributo('enviNFe','idLote','111',1,'enviNFe',1);
	$xmlGerarNf->setNoValor("idLote",$cQuery->getCampo("NFDV"),"id","lote",FALSE,FALSE,1,0);


    //Dados do fisco quem emite a nota
	$tipNota = 1;

	if ( strtoupper($cQuery->getCampo("NDO")) == "DEVCLI" )
	  $tipNota = 0;

	//echo "<br> Tipo " .$tipNota ;

	$xmlGerarNf->setNoValor("tpEmi","C","id","dadosEmitente",FALSE,FALSE,0,0);

    $xmlGerarNf->setNoValor("CPF",$emitCpf,"id","dadosEmitente",FALSE,FALSE,0,0);
    $xmlGerarNf->setNoValor("UF",$emit,"id","dadosEmitente",FALSE,FALSE,0,0);
    $xmlGerarNf->setNoValor("repEmi",substr($emitRep,0,50),"id","dadosEmitente",FALSE,FALSE,0,0);
	$xmlGerarNf->setNoValor("CNPJ",$cnpjEmitente,"id","dadosEmitente",FALSE,FALSE,0,0);
	$xmlGerarNf->setNoValor("xNome",substr($nomRep,0,50),"id","dadosEmitente",FALSE,FALSE,1,0);
   //*******


   //identificacao da Nota	nfe
   $xmlGerarNf->setNoValor("nNF", (int) $cQuery->getCampo("NF"),"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("dEmi",$cQuery->getCampo("EMISSAO"),"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("tpNF",$tipNota,"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("dSaiEnt",$cQuery->getCampo("EMISSAO"),"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("cNF",$cQuery->getCampo("CNF"),"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("finNFe",$tipoNota,"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("cDV",$dv,"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("natOp",substr($cQuery->getCampo("DSCFOP"),0,50),"id","nfe",FALSE,FALSE,0,0);
   $xmlGerarNf->setNoValor("refNFe",$valor[0],"id","nfe",FALSE,FALSE,0,0);




   //$xmlGerarNf->InsertElementSemAtributo('NFref','refNFe',"00000000");
  //*****


  //Emitente  nfe


  $xmlGerarNf->setNoValor("CNPJ",$cnpj,"id","emitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xNome",rtrim(trim(substr($emit,0,48))),"id","emitente",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("IE",$ins,"id","emitente",FALSE,FALSE,1,0);
  //*****
 // $xmlGerarNf->setNoValor("nfe:IE","240015061","id","emitente",FALSE,FALSE,0);

 //Endereco do Emitente nfe
  $end  = "RUA SA";
  $nu   = "237";

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



  $strUf = $cQuery->getCampo("ESTADO_FAT");
  $xMuni = substr( $cQuery->getCampo("CIDADE_FAT"),0,50);
  $cMun  = substr($cQuery->getCampo("COD_IBGE"),0,7);

  if ( strtoupper( rtrim(ltrim( $cQuery->getCampo("ESTADO_FAT") ))) == "EX"  ) {

    $strUf = 'EX';
    $xMuni = 'EXTERIOR';
    $cMun  = '9999999';
  }

  $xmlGerarNf->InsertElementSemAtributo('infNFe','dest','',1,'infNFe',0);

  //if ( strtoupper( rtrim(ltrim( $cQuery->getCampo("ESTADO_FAT") ))) == "EX"  )
  $xmlGerarNf->InsertElementSemAtributo('dest','CNPJ',$cpnjCli,1,'dest',0);

  $xmlGerarNf->InsertElementSemAtributo('dest','xNome',rtrim(trim(substr(htmlentities($cQuery->getCampo("NOMEC")),0,50),"\t\n\r\0\x0B" ),"' ' "),1,'dest',0);
  $xmlGerarNf->InsertElementSemAtributo('dest','enderDest','',1,'dest',0);
  $xmlGerarNf->InsertElementSemAtributo('enderDest','xLgr',ltrim(rtrim(trim(substr(htmlentities($cQuery->getCampo("ENDERECO_FAT")),0,60)))),1,'enderDest',0);
  $xmlGerarNf->InsertElementSemAtributo('enderDest','nro',$nu,1,'enderDest',0);
  $xmlGerarNf->InsertElementSemAtributo('enderDest','xBairro',ltrim(rtrim(substr($cQuery->getCampo("BAIRRO_FAT"),0,60))),1,'enderDest',0);
  $xmlGerarNf->InsertElementSemAtributo('enderDest','cMun',$cMun,1,'enderDest',0);
  $xmlGerarNf->InsertElementSemAtributo('enderDest','xMun',ltrim(rtrim($xMuni)),1,'enderDest',0);
  $xmlGerarNf->InsertElementSemAtributo('enderDest','UF',$strUf,1,'enderDest',0);
  $xmlGerarNf->InsertElementSemAtributo('enderDest','CEP',str_pad($cQuery->getCampo("COD"),8,"0"),1,'enderDest',0);
  $xmlGerarNf->InsertElementSemAtributo('dest','IE',substr($insCli,0,50),1,'dest',0);


  //******


  //Endereço do Cliente nfe  2611606- 2927408



  $xmlGerarNf->setNoValor("xLgr",ltrim(rtrim(trim(substr(htmlentities($cQuery->getCampo("ENDERECO_FAT")),0,60)))),"id","destEndereco",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("nro",$nu,"id","destEndereco",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xBairro",ltrim(rtrim(substr($cQuery->getCampo("BAIRRO_FAT"),0,60))),"id","destEndereco",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("cMun",$cMun,"id","destEndereco",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("xMun",ltrim(rtrim($xMuni)),"id","destEndereco",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("UF",$strUf,"id","destEndereco",FALSE,FALSE,0,0);
  $xmlGerarNf->setNoValor("CEP",str_pad($cQuery->getCampo("COD"),8,"0"),"id","destEndereco",FALSE,FALSE,1,0);
  //******




ociexecute($cQuery->getStat());
$qt = 1;
$precoTotalProd = 0;

while( ocifetch($cQuery->getStat()) ) {


	 //echo "<br>Qtreg" .$qt."-".$cQuery->getCampo("CODPROD")."<br>";


  $xmlGerarNf->InsertElementAtrr('infNFe','det','','nItem',$qt);



  //Produtos Nfe
  $xmlGerarNf->InsertElementSemAtributo('det','prod','',$qt,'det',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','cProd',$cQuery->getCampo("CODPROD"),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','cEAN','',$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','xProd', rtrim(ltrim(substr(htmlentities($cQuery->getCampo("DESCRICAO")),0,50))) ,$qt,'prod',1);


  $codClasse = $cClasse->fClasse($cQuery->getCampo("COD_CLASSE_FISCAL"));

  if ( strlen(( $codClasse) ) > 7  )
    $xmlGerarNf->InsertElementSemAtributo('prod','NCM',substr($codClasse,0,8),$qt,'prod',1);

  $xmlGerarNf->InsertElementSemAtributo('prod','CFOP',$cQuery->getCampo("CFOP"),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','uCom',$cQuery->getCampo("SIMBOLO"),$qt,'prod',1);

  if ( (strtoupper($cQuery->getCampo("NDO")) == "TR084")  || (strtoupper($cQuery->getCampo("NDO")) == "TR086") || (strtoupper($cQuery->getCampo("NDO")) == "TR290") || (strtoupper($cQuery->getCampo("NDO")) == "TR287") )
	$xmlGerarNf->InsertElementSemAtributo('prod','qCom',number_format(0,4,".",""),$qt,'prod',1);
  else
    $xmlGerarNf->InsertElementSemAtributo('prod','qCom',number_format($cQuery->getCampo("QUANTIDADE"),4,".",""),$qt,'prod',1);

  $precoTotalProd = $precoTotalProd + $cQuery->getCampo("QUANTIDADE") * $cQuery->getCampo("PRECO");


  $xmlGerarNf->InsertElementSemAtributo('prod','vUnCom',number_format(abs($cQuery->getCampo("PRECO")*10000)/10000,4,".",""),$qt,'prod',1);



  if (  ( strtoupper($cQuery->getCampo("NDO") ) == "996C" )) {
    $precoTotalProd =  0;
    $xmlGerarNf->InsertElementSemAtributo('prod','vProd',0,$qt,'prod',1);
  } else {

	  $xmlGerarNf->InsertElementSemAtributo('prod','vProd',number_format(100 * round( $cQuery->getCampo("QUANTIDADE") * $cQuery->getCampo("PRECO"),2)/100,2,".",""),$qt,'prod',1);
	}



   //$xmlGerarNf->InsertElementSemAtributo('prod','vProd',number_format(100 * round( $cQuery->getCampo("QUANTIDADE") * $cQuery->getCampo("PRECO"),2)/100,2,".",""),$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','cEANTrib','',$qt,'prod',1);
  $xmlGerarNf->InsertElementSemAtributo('prod','uTrib',$cQuery->getCampo("SIMBOLO"),$qt,'prod',1);

  $qtProduto = $cQuery->getCampo("QUANTIDADE");

  if ( (strtoupper($cQuery->getCampo("NDO")) == "TR084")  || (strtoupper($cQuery->getCampo("NDO")) == "TR086") || (strtoupper($cQuery->getCampo("NDO")) == "TR290") || (strtoupper($cQuery->getCampo("NDO")) == "TR287") )
   $xmlGerarNf->InsertElementSemAtributo('prod','qTrib',number_format(0,4,".",""),$qt,'prod',1);
  else
    $xmlGerarNf->InsertElementSemAtributo('prod','qTrib',number_format($cQuery->getCampo("QUANTIDADE"),4,".",""),$qt,'prod',1);

  $xmlGerarNf->InsertElementSemAtributo('prod','vUnTrib',number_format(abs($cQuery->getCampo("PRECO")*10000)/10000,4,".",""),$qt,'prod',1);
  //$xmlGerarNf->RemoveAttr('prod','nItem',$qt);
  //****

   //Impostos
   $xmlGerarNf->InsertElementSemAtributo('det','imposto','',$qt,'det',1);

   //Icms nfe
   $xmlGerarNf->InsertElementSemAtributo('imposto','ICMS','',$qt,'imposto',1);

   //CSTICMS
   $cstIcms = substr($cQuery->getCampo("CBT_NOTA"),1,2);

   //echo "<br>Cbt -> " . $cstIcms;

  //Tributado Integralmente
   if ( ($cstIcms == "00")   ) {


	$xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS00','',$qt,'ICMS',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','orig','0',$qt,'ICMS00',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','CST',$cstIcms,$qt,'ICMS00',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','modBC','0',$qt,'ICMS00',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','vBC', number_format( $cQuery->getCampo("BASE_ICMS"),2,".",""),$qt,'ICMS00',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','pICMS', number_format( $cQuery->getCampo("ALQ_ICMS"),2,".",""),$qt,'ICMS00',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS00','vICMS', number_format( $cQuery->getCampo("VAL_ICMS"),2,".",""),$qt,'ICMS00',1);

	//$xmlGerarNf->RemoveAttr('ICMS00','nItem',$qt);
   }

   //tributado com atributaçao do icms subst
   if ( $cstIcms == "10" ) {

	$xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS10','',$qt,'ICMS',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','orig','0',$qt,'ICMS10',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','CST','10',$qt,'ICMS10',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','modBC','1',$qt,'ICMS10',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','vBC',  number_format($cQuery->getCampo("BASE_ICMS") ,2,".",""),$qt,'ICMS10',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','pICMS',number_format($cQuery->getCampo("ALQ_ICMS") ,2,".",""),$qt,'ICMS10',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','vICMS',number_format($cQuery->getCampo("VAL_ICMS") ,2,".",""),$qt,'ICMS10',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','modBCST','5',$qt,'ICMS10',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','vBCST',number_format($cQuery->getCampo("BASE_ICMSF") ,2,".",""),$qt,'ICMS10',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','pICMSST',number_format($cQuery->getCampo("ALQ_ICMSF") ,2,".",""),$qt,'ICMS10',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS10','vICMSST',number_format($cQuery->getCampo("VAL_ICMSF") ,2,".",""),$qt,'ICMS10',1);
	//$xmlGerarNf->RemoveAttr('ICMS10','nItem',$qt);
   }

   //Tributado com reduçao de base icms
   if ( $cstIcms == "20" ) {

	$xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS20','',$qt,'ICMS',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','orig','0',$qt,'ICMS20',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','CST','20',$qt,'ICMS20',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','modBC','0',$qt,'ICMS20',1);
	//Falta
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','pRedBC','0',$qt,'ICMS20',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','vBC',  number_format($cQuery->getCampo("BASE_ICMS") ,2,".",""),$qt,'ICMS20',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','pICMS',number_format($cQuery->getCampo("ALQ_ICMS") ,2,".",""),$qt,'ICMS20',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS20','vICMS',number_format($cQuery->getCampo("VAL_ICMS") ,2,".",""),$qt,'ICMS20',1);
	//$xmlGerarNf->RemoveAttr('ICMS20','nItem',$qt);
   }


    //Isenta ou tributado com combrança o ICMS subst
   if ( ($cstIcms == "30")  ) {

	$xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS30','',$qt,'ICMS',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS30','orig','0',$qt,'ICMS30',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS30','CST',$cstIcms,$qt,'ICMS30',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS30','vBCST',number_format($cQuery->getCampo("BASE_ICMSF"),2,".",""),$qt,'ICMS30',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS30','pICMSST',number_format($cQuery->getCampo("ALQ_ICMSF"),2,".",""),$qt,'ICMS30',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS30','vICMSST',number_format($cQuery->getCampo("VAL_ICMSF"),2,".",""),$qt,'ICMS30',1);
	//$xmlGerarNf->RemoveAttr('ICMS30','nItem',$qt);
   }

    if ( ( $cstIcms == "90" )  ) {


	$xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS90','',$qt,'ICMS',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','orig','0',$qt,'ICMS90',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS90','CST',$cstIcms,$qt,'ICMS90',1);

	//Natureza de operação mae
	$tipoNota = $cQuery->getCampo("TIPO_NF");

	if ( (int)  $tipoNota  != 2 ) {

	  $xmlGerarNf->InsertElementSemAtributo('ICMS90','modBC','1',$qt,'ICMS90',1);
	  $xmlGerarNf->InsertElementSemAtributo('ICMS90','vBC',  number_format($cQuery->getCampo("BASE_ICMS"),2,".",""),$qt,'ICMS90',1);
	  $xmlGerarNf->InsertElementSemAtributo('ICMS90','pICMS',number_format($cQuery->getCampo("ALQ_ICMS"),2,".",""),$qt,'ICMS90',1);
	  $xmlGerarNf->InsertElementSemAtributo('ICMS90','vICMS',number_format($cQuery->getCampo("VAL_ICMS"),2,".",""),$qt,'ICMS90',1);
	  $xmlGerarNf->InsertElementSemAtributo('ICMS90','modBCST','5',$qt,'ICMS90',1);
	  $xmlGerarNf->InsertElementSemAtributo('ICMS90','vBCST',number_format($cQuery->getCampo("BASE_ICMSF"),2,".",""),$qt,'ICMS90',1);
	  $xmlGerarNf->InsertElementSemAtributo('ICMS90','pICMSST',number_format($cQuery->getCampo("ALQ_ICMSF"),2,".",""),$qt,'ICMS90',1);
	  $xmlGerarNf->InsertElementSemAtributo('ICMS90','vICMSST',number_format($cQuery->getCampo("VAL_ICMSF"),2,".",""),$qt,'ICMS90',1);
	}
	//$xmlGerarNf->RemoveAttr('ICMS90','nItem',$qt);
   }


   /*****************/
    //Tributacao pelo icms

   if ( ( $cstIcms == "40") ||  ( $cstIcms == "41") || ( $cstIcms == "50") ) {

    $xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS40','',$qt,'ICMS',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS40','orig','0',$qt,'ICMS40',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS40','CST',$cstIcms,$qt,'ICMS40',1);
	//$xmlGerarNf->RemoveAttr('ICMS40','nItem',$qt);

   }

    //Diferimento
   if ( ( $cstIcms == "51" ) ) {

    $xmlGerarNf->InsertElementSemAtributo('ICMS','ICMS51','',$qt,'ICMS',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS51','orig','0',$qt,'ICMS51',1);
	$xmlGerarNf->InsertElementSemAtributo('ICMS51','CST',$cstIcms,$qt,'ICMS51',1);
	//$xmlGerarNf->RemoveAttr('ICMS51','nItem',$qt);

   }


   //Removendo Atributo ICMS
   //$xmlGerarNf->RemoveAttr('ICMS','nItem',$qt);
   //IPI

   if ( $cQuery->getCampo("ALQ_IPI") > 0 ) {

    $xmlGerarNf->InsertElementSemAtributo('imposto','IPI','',$qt,'imposto',1);
   	$xmlGerarNf->InsertElementSemAtributo('IPI','clEnq','Str',$qt,'IPI',1);
    $xmlGerarNf->InsertElementSemAtributo('IPI','CNPJProd','00000000000000',$qt,'IPI',1);
    $xmlGerarNf->InsertElementSemAtributo('IPI','cSelo','Str',$qt,'IPI',1);
    $xmlGerarNf->InsertElementSemAtributo('IPI','qSelo','0',$qt,'IPI',1);
    $xmlGerarNf->InsertElementSemAtributo('IPI','cEnq','Str',$qt,'IPI',1);


    if ( ( $cQuery->getCampo("COD_IPI") == "00" ) || ( $cQuery->getCampo("COD_IPI") == "49" ) || ( $cQuery->getCampo("COD_IPI") == "50" ) || ( $cQuery->getCampo("COD_IPI") == "99" ) ) {

     $xmlGerarNf->InsertElementSemAtributo('IPI','IPITrib','',$qt,'IPI',1);
     $xmlGerarNf->InsertElementSemAtributo('IPITrib','CST',substr($cQuery->getCampo("COD_IPI"),0,2),$qt,'IPITrib',1);
     $xmlGerarNf->InsertElementSemAtributo('IPITrib','vBC',number_format($cQuery->getCampo("BASE_IPI"),2,".",""),$qt,'IPITrib',1);
     $xmlGerarNf->InsertElementSemAtributo('IPITrib','pIPI',number_format($cQuery->getCampo("ALQ_IPI"),2,".",""),$qt,'IPITrib',1);
     $xmlGerarNf->InsertElementSemAtributo('IPITrib','vIPI',number_format($cQuery->getCampo("VAL_IPI"),2,".",""),$qt,'IPITrib',1);

	}  else {
	      $xmlGerarNf->InsertElementSemAtributo('IPI','IPINT','',$qt,'IPI',1);
		  $xmlGerarNf->InsertElementSemAtributo('IPINT','CST',$cQuery->getCampo("COD_IPI"),$qt,'IPINT',1);
	   }
   //$xmlGerarNf->RemoveAttr('IPITrib','nItem',$qt);
   //$xmlGerarNf->RemoveAttr('IPI','nItem',$qt);
   }
   //PIS


   $Porcentagem = 0;
   $PorcentagemConfins = 0;


	if ( $cQuery->getCampo("VALOR") > 0 )  {
      $Porcentagem = ( $cQuery->getCampo("VAL_PIS") * 100 ) / $cQuery->getCampo("VALOR");
	  $valAliqPIS  =  $Porcentagem * $cQuery->getCampo("VALOR");

	  $PorcentagemConfins = ( $cQuery->getCampo("VAL_COFINS") * 100 ) / $cQuery->getCampo("VALOR");
	  $valAliqConfins =  $PorcentagemConfins * $cQuery->getCampo("VALOR");

	}





    $xmlGerarNf->InsertElementSemAtributo('imposto','PIS','',$qt,'imposto',1);

	if ( ( $cQuery->getCampo("COD_PIS") == "01" ) || ( $cQuery->getCampo("COD_PIS") == "02" )  ) {
     $xmlGerarNf->InsertElementSemAtributo('PIS','PISAliq','',$qt,'PIS',1);
     $xmlGerarNf->InsertElementSemAtributo('PISAliq','CST',$cQuery->getCampo("COD_PIS"),$qt,'PISAliq',1);
     $xmlGerarNf->InsertElementSemAtributo('PISAliq','vBC',number_format( $Porcentagem * $cQuery->getCampo("VALOR"),2,".",""),$qt,'PISAliq',1);
     $xmlGerarNf->InsertElementSemAtributo('PISAliq','pPIS',number_format( $Porcentagem,2,".",""),$qt,'PISAliq',1);
     $xmlGerarNf->InsertElementSemAtributo('PISAliq','vPIS',number_format($cQuery->getCampo("VAL_PIS"),2,".",""),$qt,'PISAliq',1);
    }

	if ( ( $cQuery->getCampo("COD_PIS") == "03" )  ) {
     $xmlGerarNf->InsertElementSemAtributo('PIS','PISQtde','',$qt,'PIS',1);
     $xmlGerarNf->InsertElementSemAtributo('PISQtde','CST',$cQuery->getCampo("COD_PIS"),$qt,'PISQtde',1);
     $xmlGerarNf->InsertElementSemAtributo('PISQtde','qBCProd',number_format($valAliqPIS,2,".",""),$qt,'PISQtde',1);
     $xmlGerarNf->InsertElementSemAtributo('PISQtde','vAliqProd',number_format( $Porcentagem,2,".",""),$qt,'PISQtde',1);
     $xmlGerarNf->InsertElementSemAtributo('PISQtde','vPIS',number_format($cQuery->getCampo("VAL_PIS"),2,".",""),$qt,'PISQtde',1);
    }

	if ( ( $cQuery->getCampo("COD_PIS") == "04" )   || ( $cQuery->getCampo("COD_PIS") == "06" ) ||  ( $cQuery->getCampo("COD_PIS") == "07" ) || ( $cQuery->getCampo("COD_PIS") == "08" ) || ( $cQuery->getCampo("COD_PIS") == "09" ) ) {
     $xmlGerarNf->InsertElementSemAtributo('PIS','PISNT','',$qt,'PIS',1);
     $xmlGerarNf->InsertElementSemAtributo('PISNT','CST',$cQuery->getCampo("COD_PIS"),$qt,'PISNT',1);

   }


   if ( ( $cQuery->getCampo("COD_PIS") == "99" )  ) {
     $xmlGerarNf->InsertElementSemAtributo('PIS','PISOutr','',$qt,'PIS',1);
     $xmlGerarNf->InsertElementSemAtributo('PISOutr','CST',$cQuery->getCampo("COD_PIS"),$qt,'PISOutr',1);
	 $xmlGerarNf->InsertElementSemAtributo('PISOutr','vBC',number_format( $Porcentagem * $cQuery->getCampo("VALOR"),2,".",""),$qt,'PISOutr',1);
     $xmlGerarNf->InsertElementSemAtributo('PISOutr','pPIS',number_format( $Porcentagem,2,".",""),$qt,'PISOutr',1);
	 //$xmlGerarNf->InsertElementSemAtributo('PISOutr','qBCProd',number_format($cQuery->getCampo("QUANTIDADE"),4,".",""),$qt,'PISOutr',1);
     //$xmlGerarNf->InsertElementSemAtributo('PISOutr','vAliqProd',number_format( $Porcentagem,2,".",""),$qt,'PISOutr',1);
     $xmlGerarNf->InsertElementSemAtributo('PISOutr','vPIS',number_format($cQuery->getCampo("VAL_PIS"),2,".",""),$qt,'PISOutr',1);
   }


   //$xmlGerarNf->RemoveAttr('PISAliq','nItem',$qt);
   //$xmlGerarNf->RemoveAttr('PIS','nItem',$qt);

   //COFINS


     $xmlGerarNf->InsertElementSemAtributo('imposto','COFINS','',$qt,'imposto',1);

	 if ( ( $cQuery->getCampo("COD_COFINS") == "01" )  || ( $cQuery->getCampo("COD_COFINS") == "02" )  ) {

       $xmlGerarNf->InsertElementSemAtributo('COFINS','COFINSAliq','',$qt,'COFINS',1);
       $xmlGerarNf->InsertElementSemAtributo('COFINSAliq','CST',$cQuery->getCampo("COD_COFINS"),$qt,'COFINSAliq',1);
       $xmlGerarNf->InsertElementSemAtributo('COFINSAliq','vBC',number_format( $PorcentagemConfins * $cQuery->getCampo("VALOR"),2,".",""),$qt,'COFINSAliq',1);
       $xmlGerarNf->InsertElementSemAtributo('COFINSAliq','pCOFINS',number_format( $PorcentagemConfins,2,".",""),$qt,'COFINSAliq',1);
       $xmlGerarNf->InsertElementSemAtributo('COFINSAliq','vCOFINS',number_format($cQuery->getCampo("VAL_COFINS"),2,".",""),$qt,'COFINSAliq',1);
	}

	if ( ( $cQuery->getCampo("COD_COFINS") == "03" )    ) {

       $xmlGerarNf->InsertElementSemAtributo('COFINS','COFINSQtde','',$qt,'COFINS',1);
       $xmlGerarNf->InsertElementSemAtributo('COFINSQtde','CST',$cQuery->getCampo("COD_COFINS"),$qt,'COFINSQtde',1);
       $xmlGerarNf->InsertElementSemAtributo('COFINSQtde','qBCProd',number_format( $PorcentagemConfins * $cQuery->getCampo("VALOR"),2,".",""),$qt,'COFINSQtde',1);
       $xmlGerarNf->InsertElementSemAtributo('COFINSQtde','vAliqProd',number_format( $valAliqPIS,2,".",""),$qt,'COFINSQtde',1);
       $xmlGerarNf->InsertElementSemAtributo('COFINSQtde','vCOFINS',number_format($cQuery->getCampo("VAL_COFINS"),2,".",""),$qt,'COFINSQtde',1);
	}

	if ( ( $cQuery->getCampo("COD_COFINS") == "04" )   || ( $cQuery->getCampo("COD_COFINS") == "06" ) ||  ( $cQuery->getCampo("COD_COFINS") == "07" ) || ( $cQuery->getCampo("COD_COFINS") == "08" ) || ( $cQuery->getCampo("COD_COFINS") == "09" ) ) {

	 $xmlGerarNf->InsertElementSemAtributo('COFINS','COFINSNT','',$qt,'COFINS',1);
     $xmlGerarNf->InsertElementSemAtributo('COFINSNT','CST',$cQuery->getCampo("COD_COFINS"),$qt,'COFINSNT',1);

   }

   if ( ( $cQuery->getCampo("COD_COFINS") == "99" )    ) {

       $xmlGerarNf->InsertElementSemAtributo('COFINS','COFINSOutr','',$qt,'COFINS',1);
       $xmlGerarNf->InsertElementSemAtributo('COFINSOutr','CST',$cQuery->getCampo("COD_COFINS"),$qt,'COFINSOutr',1);
	   $xmlGerarNf->InsertElementSemAtributo('COFINSAliq','vBC',number_format( $PorcentagemConfins * $cQuery->getCampo("VALOR"),2,".",""),$qt,'COFINSOutr',1);
       $xmlGerarNf->InsertElementSemAtributo('COFINSAliq','pCOFINS',number_format( $PorcentagemConfins,2,".",""),$qt,'COFINSOutr',1);
	   //$xmlGerarNf->InsertElementSemAtributo('COFINSOutr','qBCProd',number_format( $PorcentagemConfins * $cQuery->getCampo("VALOR"),2,".",""),$qt,'COFINSOutr',1);
      // $xmlGerarNf->InsertElementSemAtributo('COFINSOutr','vAliqProd',number_format( $valAliqPIS,2,".",""),$qt,'COFINSOutr',1);
       $xmlGerarNf->InsertElementSemAtributo('COFINSOutr','vCOFINS',number_format($cQuery->getCampo("VAL_COFINS"),2,".",""),$qt,'COFINSOutr',1);
	}





   $qt++;
 }

   //*****


   /******************************************* Detalhe do produto ***********************************/

    $cQuery->execSql(str_pad($in_nf, 6, "0", STR_PAD_LEFT),$serie,$filial);

   //Total da Nota fiscal
    //icmsTotal nfe


	$BaseIcms = number_format($cQuery->getCampo("BASE_ICMS"),2,".","");
	$ValorIcms = number_format($cQuery->getCampo("VAL_ICMS"),2,".","");

	//Enviada
	 if ( (int)  $tipoNota  == 2 ) {

		$BaseIcms = 0;
		$ValorIcms = 0;
	 }

	$precoTotalProd = round($precoTotalProd,2);
	//echo " <br> Preço Produto " . $precoTotalProd;

	$xmlGerarNf->InsertElementSemAtributo('infNFe','total','',$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('total','ICMSTot','',$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vBC',$BaseIcms,$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vICMS',$ValorIcms,$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vBCST',number_format($cQuery->getCampo("BASE_ICMSF"),2,".",""),$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vST',number_format($cQuery->getCampo("VAL_ICMSF"),2,".",""),$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vProd',number_format(round(100 *  $precoTotalProd ,2)/100,2,".",""),$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vFrete','0',$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vSeg','0',$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vDesc','0',$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vII','0',$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vIPI',number_format($cQuery->getCampo("VAL_IPI"),2,".",""),$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vPIS',number_format($cQuery->getCampo("VAL_PIS"),2,".",""),$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vCOFINS',number_format($cQuery->getCampo("VAL_COFINS"),2,".",""),$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vOutro','0',$qt,'',0);
	$xmlGerarNf->InsertElementSemAtributo('ICMSTot','vNF',number_format($cQuery->getCampo("VALOR"),2,".",""),$qt,'',0);
   //***


  //Transportadora  nfe
  $xmlGerarNf->InsertElementSemAtributo('infNFe','transp','',$qt,'',0);
  $xmlGerarNf->InsertElementSemAtributo('transp','modFrete',((int) $cQuery->getCampo("TIPOFRETE")) - 1 ,$qt,'',0);


  if ( (strlen($transCnpj) > 0 )  && ( $cQuery->getCampo("CODTRANSP")  != '10470' ) ) {

	$xmlGerarNf->InsertElementSemAtributo('transp','transporta','',$qt,'',0);

        if ( strlen($transCnpj) > 11 )
 	      $xmlGerarNf->InsertElementSemAtributo('transporta','CNPJ',$transCnpj,$qt,'',0);
        else
         $xmlGerarNf->InsertElementSemAtributo('transporta','CPF',$transCnpj,$qt,'',0);

	$xmlGerarNf->InsertElementSemAtributo('transporta','xNome',substr($nomTrans,0,50),$qt,'',0);

	if (strlen($cQuery->getCampo("INSCRICAO") )  > 1 )
	  $xmlGerarNf->InsertElementSemAtributo('transporta','IE',substr($cQuery->getCampo("INSCRICAO"),0,17) ,$qt,'',0);


	if ( strlen($cQuery->getCampo("ENDFAT") ) > 5 )
	   $xmlGerarNf->InsertElementSemAtributo('transporta','xEnder',ltrim(rtrim(trim(substr(htmlentities($cQuery->getCampo("ENDFAT")),0,50)))),$qt,'',0);


	if (strlen($cQuery->getCampo("CIDTRANSP") )  > 2 )
	  $xmlGerarNf->InsertElementSemAtributo('transporta','xMun',$cQuery->getCampo("CIDTRANSP"),$qt,'',0);




	$qt++;

  }
  //******




  //*****

   //Veiculo nfe
   if ( strlen($veicPlaca) > 0 ) {

	 $xmlGerarNf->InsertElementSemAtributo('transp','veicTransp','',$qt,'',0);
	 $xmlGerarNf->InsertElementSemAtributo('veicTransp','placa',$veicPlaca,$qt,'',0);
	 $xmlGerarNf->InsertElementSemAtributo('veicTransp','UF',$veicUf,$qt,'',0);

   }

   //***


   //Reboques  nfe
  if (strlen($rebPlaca) > 0 ) {

     $xmlGerarNf->InsertElementSemAtributo('transp','reboque','',$qt,'',0);
	 $xmlGerarNf->InsertElementSemAtributo('reboque','placa',$rebPlaca,$qt,'',0);
	 $xmlGerarNf->InsertElementSemAtributo('reboque','UF',$rebUf,$qt,'',0);

  }

  //Agiliza a isso estar sem funcionar

  if ( ( strlen($rebSecPlaca) > 0) && strlen($rebPlaca) > 0  ) {


   //$xmlGerarNf->InsertElementSemAtributo('COFINS','COFINSOutr','',$qt,'COFINS',1);
    $xmlGerarNf->InsertElementSemAtributo('transp','reboque','',1,'transp',1);
	$xmlGerarNf->InsertElementSemAtributo('reboque','placa',$rebPlaca,2,'reboque',1);
	$xmlGerarNf->InsertElementSemAtributo('reboque','UF',$rebUf,2,'reboque',1);
	//$xmlGerarNf->InsertElementSemAtributo('reboque','placa','AA',2,'reboque',1);
	//$xmlGerarNf->InsertElementSemAtributo('reboque','UF',$rebUf,$qt,'reboque',1);

 }



  //Volume nfe
  $xmlGerarNf->InsertElementSemAtributo('transp','vol','',$qt,'',0);

  //$xmlGerarNf->InsertElementSemAtributo('vol','qVol',$qtProduto*20,$qt,'',0);

   if ( ( strtoupper($cQuery->getCampo("NDO")) == "TR084")  || (strtoupper($cQuery->getCampo("NDO")) == "TR086") || (strtoupper($cQuery->getCampo("NDO")) == "TR290") || (strtoupper($cQuery->getCampo("NDO")) == "TR287") || ( strtoupper($cQuery->getCampo("NDO")) == "996C")  || ( strtoupper($cQuery->getCampo("NDO")) == "865B") )
   $xmlGerarNf->InsertElementSemAtributo('vol','qVol', 0  ,$qt,'',0);
  else
   if ( strtoupper($cQuery->getCampo("NDO")) != "DEVCLI" )
    $xmlGerarNf->InsertElementSemAtributo('vol','qVol', $cQuery->getCampo("VOLUME_QTD")  ,$qt,'',0);


  if (  strlen($cQuery->getCampo("ESPECIE")) > 1 )
    $xmlGerarNf->InsertElementSemAtributo('vol','esp',substr($cQuery->getCampo("ESPECIE"),0,18),$qt,'',0);


  if ( strlen($cQuery->getCampo("SIGLA")) > 2 )
   $xmlGerarNf->InsertElementSemAtributo('vol','marca',$cQuery->getCampo("SIGLA"),$qt,'',0);


  if (  ( float ) $cQuery->getCampo("PESO_LIQUIDO") > 1 )
   $xmlGerarNf->InsertElementSemAtributo('vol','pesoL',number_format($cQuery->getCampo("PESO_LIQUIDO"),3,".",""),$qt,'',0);

  if (  ( float ) $cQuery->getCampo("PESO_BRUTO") > 1 )
   $xmlGerarNf->InsertElementSemAtributo('vol','pesoB',number_format($cQuery->getCampo("PESO_BRUTO"),3,".",""),$qt,'',0);




 //******



//Inf Adicional
 $arrSubstituir = array("<",">","'","\"","º","&","$","´","%");
 $arrValorSubst = array(" - "," - "," - "," - "," - "," - "," - "," - "," - ");



 $obsNF = rtrim(trim( ( $cQuery->getCampo("COMP_OBS") . $cQuery->getCampo("DSC_OBSCORPONF")  ),"\t\n\r\0\x0B"),"\t\n\r\0\x0B");


 //$obsNF = str_replace($arrSubstituir, $arrValorSubst, $obsNF);



 $qtDup = 1;


 $cQuery->execSqlDup($filial ,$pedido,$serie,str_pad($in_nf, 6, "0", STR_PAD_LEFT));

 //echo $cQuery->fSelectDuplicata( $filial ,$pedido,$serie,$in_nf);

 if (  strlen( $cQuery->getCampo("FATURA") ) > 0 ) {

 $xmlGerarNf->InsertElementSemAtributo('infNFe','cobr','',$qtDup,'',0);


 ociexecute($cQuery->getStat());

    while ( ocifetch($cQuery->getStat())  ) {


        //echo "<br> Qtde " . $qtDup;
		$xmlGerarNf->InsertElementSemAtributo('cobr','dup','',$qtDup,'cobr',0);
		$xmlGerarNf->InsertElementSemAtributo('dup','nDup',$cQuery->getCampo("TITULO"),$qtDup,'dup',1);
		$xmlGerarNf->InsertElementSemAtributo('dup','dVenc',$cQuery->getCampo("VENCIMENTO"),$qtDup,'dup',1);
		$xmlGerarNf->InsertElementSemAtributo('dup','vDup',number_format($cQuery->getCampo("VALOR"),2,".",""),$qtDup,'dup',1);
		$qtDup++;
		//$qtDup = $qtDup + 1;
    }



 }


 if (strlen($obsNF) > 10 ) {
  $xmlGerarNf->InsertElementSemAtributo('infNFe','infAdic','',$qt,'',0);
  $xmlGerarNf->InsertElementSemAtributo('infAdic','infCpl',substr(trim($obsNF),0,4999),$qt,'',0);
 }

 //Duplicata a Receber  //serie,in_nf,serie,pedido execSqlDup
 //$cQuery->fSelectDuplicata( $filial ,$pedido,$serie,$in_nf);




 //echo "<br> Vencimento ". $cQuery->getCampo("VENCIMENTO");




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


  $vetConf = $cConf->fConfiguracao( $filial );

  //echo "<br> Configuracao  " . $filial ;

  if ( $verConn->fVerificaConexaoSSL("homologacao.nfe.sefazvirtual.rs.gov.br/ws/nferecepcao/NfeRecepcao.asmx" ,$vetConf[0]) == 0 )
    $boolConexao = true;



 // $boolConexao = true;

 // echo $cEnvNF->fVerServStatus($link, $arqStatus, $login,$data,$cabecalho,$dir,$metodo,2,$vetConf[0]) ;

  if (  ( $boolConexao ) && ( $cEnvNF->fVerServStatus($link, $arqStatus, $login,$data,$cabecalho,$dir,$metodo,2,$vetConf[0]) == 0 ) ) {

      $arqXML = $dir = "xml/xml_status_servico/" . $login ."/".  $login ."_".$data.".xml";

	  //Lendo o XML statusServico
	  $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
	  $Status = $cValorXml->ValorXmlNameSpaceVetor("//cStat",$arqXML);
	  $valor =  $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$arqXML);


	   //$Status[0] = 108;


	  //Serviço em operacao
	 if ( (int) $Status[0] == 107 )  {

		  $xmlGerarNf->setNoValor("tpEmis","1","id","nfe",FALSE,FALSE,1,0);
		  $xmlArq = "xml/xml_nao_assinado/".$login."/".$login."_".(int) $in_nf.".xml";
          $xmlGerarNf->save($xmlArq);


          //Assinando o arquivo XML
		   $arqSalvar = "xml/xml_assinado/";
		   /*
		   echo "<br> Patch " . realpath($vetConf[0]);
		   echo "<br> Patch " . realpath($vetConf[1]);
		   echo "<br> Patch " . realpath($vetConf[2]);*/
          $arqXmlAssinado = $cAssinar->fAssinXML( $xmlArq ,"Nfe".$strChave.$dv , $login ,(int)$in_nf,$arqSalvar,$vetConf[1],$vetConf[2]);


         //Enviando o XML
         $cabecalho = "xml/xml_cabecalho/CabCalho.xml";
         $dir = "xml/xml_resposta/";
         $link = "https://homologacao.nfe.sefazvirtual.rs.gov.br/ws/nferecepcao/NfeRecepcao.asmx?wsdl";
         $metodo = "nfeRecepcaoLote";
         $cEnvNF->cEnviaXML($link, $arqXmlAssinado, $login ,(int) $in_nf,$cabecalho,$dir,$metodo,0,$vetConf[0],$strChave);


	  }
  }

  else{

      //Contigencia
	  $obsContigencia = "DANFE em contigencia , impresso em decorrencia de problemas tecnicos - ART 139 -K do RICMS/AL";
	  $xmlGerarNf->setNoValor("tpEmis","2","id","nfe",FALSE,FALSE,0,0);

	  /*
	  if ( strlen($obsNF) < 1  ) {

	    $xmlGerarNf->InsertElementSemAtributo('infNFe','infAdic','',$qt,'',0);
        $xmlGerarNf->InsertElementSemAtributo('infAdic','infCpl',substr(trim($obsNF),0,4999),$qt,'',0);
	  }*/

	   if (strlen($obsNF) < 1 ) {
        $xmlGerarNf->InsertElementSemAtributo('infNFe','infAdic','',$qt,'',0);
        $xmlGerarNf->InsertElementSemAtributo('infAdic','infCpl',substr(trim($obsNF),0,4999),$qt,'',0);
      }

	  $xmlGerarNf->setNoValor("infCpl",$obsNF.' - '.$obsContigencia,"id","nfe",FALSE,FALSE,1,0);
	  $xmlArq = "xml/xml_nao_assinado_contigencia/".$login."/".$login."_".(int) $in_nf.".xml";
      $xmlGerarNf->save($xmlArq);

      //Assinando o arquivo XML
	  $arqSalvar ="xml/xml_assinado_contigente/";
      $arqXmlAssinado= $cAssinar->fAssinXML( $xmlArq ,"Nfe".$strChave.$dv , $login , (int)$in_nf,$arqSalvar,$vetConf[1],$vetConf[2]);

	  //Assinando o arquivo XML tmp
	  $arqSalvar ="xml/xml_assinado_contigente_tmp/";
      $arqXmlAssinado= $cAssinar->fAssinXML( $xmlArq ,"Nfe".$strChave.$dv , $login ,(int)$in_nf,$arqSalvar,$vetConf[1],$vetConf[2]);


     echo "<script>alert(\"Estado de Contigencia ! Problema com a conexao \");</script>";
  }

  /*****/




  //**/

  return 	$xmlArq;
  }

 }

?>
