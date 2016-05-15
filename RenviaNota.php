<? session_start(); ?>
<?
  //require("Nfs/seguranca.php");

  //Ler o select
 require_once("Nfs/cQuery.php");

 //Gerar o Xml
 require_once("Nfs/cGerarXmlNfs.php");

 //Chave de acesso
 require_once("Nfs/cChavedeAcesso.php");

 //Assinatura
 require_once("xmlSign/cAssinaXml.php");

 //Verifica Conexao
 require_once("xmlSign/cVerConexao.php");

 //Enviar o XML
 //require_once("xmlSign/cEnviaNF.php");

 //Retorno o valor do xml
  require_once("Nfs/cValorXml.php");

 //Classe Social
 require_once("Nfs/cClasseFiscal.php");

 //Classe Configuracao
 require_once("Nfs/cConfigura.php");

 //Tnsname
 require_once("tnsNames/cTsnames.php");

 //Responsavel por garar o XML
 require('Nfs/cGeraPasseNf.php');

 //Responsavel por pegar As notas Geradas
 require_once('cNFSaidas.php');



$cGeraPasseNf   =  new  cGeraPasseNf();
$tnsName = new cTnsName();

//echo "<br>Usuario " . $_SESSION['banco'];


$login  =  $_SESSION["login"];
$db     =  $_SESSION['banco'];
$senha  =  $_SESSION['senha'];
$nf     =  $_POST["txt_nfe"];
$filial =  $_SESSION["empresa"];





 $banco = $db;
 $db = $tnsName->fTnsNames($db);
 //echo "<br>TnsName " . $banco . '-'.$db;

 $login  = strtoupper($login);

 $senha  = $senha;






 //echo "<br> Ok -----";
 //$xmlGerarNf = new cGerarXmlNfs($xml);
 $chave = new cChavedeAcesso();
 $cAssinar = new cAssinaXML();
 //$cEnvNF   = new cEnviaNF();
 $verConn  = new cVerConexao();
 $cClasse  = new cClasseFiscal();
 $cConf    = new cConfigura();
 $cGrava  = new cNFSaidas();

 //echo " <br> ". $db;

 $conn = ocilogon("NEWPIRAM", $senha, $db );
 $cQuery = new cQuery("NEWPIRAM", $senha,$db,'Oracle');

 //echo $cGrava->cselNFeEnviaNota(  );
 $sqlGravNFLista = OCIParse($conn,$cGrava->cselNFeEnviaNota(  ) ) ;
 OCIExecute($sqlGravNFLista);


 echo "teste";
 while ( OCIFetch($sqlGravNFLista) ) {

     echo "<br>sss<br>";
	 $nf    =  (int) OCIResult($sqlGravNFLista, "NF");

     //Usuario Dir
     $login  = OCIResult($sqlGravNFLista, "USUARIO");

     echo "<br>Usuario-- " . $login;
         $in_nf  =  $nf;
 	 $serie  =  OCIResult($sqlGravNFLista, "SERIE");
 	 $filial = OCIResult($sqlGravNFLista, "FILIAL");

	 $xml = "xml/xml_assinado/". $login."/".$filial."/".$login ."_".(int) $nf.".xml";

	 $xml2 = "xml/xml_resposta/". $login ."/".$filial."/".$login ."_".(int) $nf.".xml";

	 $xml3 = "xml/xml_resposta_recibo/". $login ."/". $filial."/".$login ."_".(int) $nf.".xml";

	 $xml4 = "xml/xml_recibo_envia/". $login ."/".$filial."/". $login ."_".(int) $nf.".xml";

     $xml5 = "xml/xml_nao_assinado/". $login."/".$filial."/".$login ."_".(int) $nf.".xml";

	if ( file_exists($xml) )
      unlink($xml);

	if ( file_exists($xml2) )
      unlink($xml2);

	if ( file_exists($xml3) )
     unlink($xml3);

	if ( file_exists($xml4) )
     unlink($xml4);

   if ( file_exists($xml5) )
     unlink($xml5);

 	//Select da Nf
	echo "<br>Banco ".$db;

 	//echo $cQuery->fSelect(str_pad($in_nf, 6, "0", STR_PAD_LEFT),$serie,$filial);;
 	$cQuery->execSql(str_pad($in_nf, 6, "0", STR_PAD_LEFT),$serie,$filial);
	$nf1 = $cQuery->getCampo("NF");
	echo "<br> Nota-" .$nf1 ."-<br>";
   //exit();
 	if ( strlen($cQuery->getCampo("NF")) > 0 ) {


  		$out_var = $cQuery->getCampo("NF");

  		echo "<br>NF " . $out_var;
  		$NomeEmit = '';
  		$emit  = ' ';
  		$emitCpf = ' ';
  		$emitRep = '';
  		$filial  = $cQuery->getCampo("FILIAL");
  		$motCpf = '';
  		$trans  = '';
  		$nomTrans =  $cQuery->getCampo("NOMEF");
  		$transUf  =  $cQuery->getCampo("ESTADO");
  		$transCnpj = $cQuery->getCampo("CGCTRANSP");
  		$veicPlaca  = $_POST["txtPlaca"];
  		$veicUf =     $_POST["uf1"];
  		$rebPlaca =   $_POST["txtPlaca2"];
  		$rebUf  =     $_POST["uf12"];
  		$rebSecPlaca = '';
  		$rebSecUf  = '';
  		$in_var   = $cQuery->getCampo("NF");
  		$cpnjEmitente = '';
  		$natOp = '';
  		$nfComp = '';
  		$nfReferenciada =   '';

  		if ( ( strlen($cQuery->getCampo("NFE_PEDIDO")) > 5 )  && ( $cQuery->getCampo("NFE_PEDIDO") <> '' ) ) {

		  $nfComp = 'S';
  		  $nfReferenciada =   $cQuery->getCampo("NFE_PEDIDO");

		}


	   	if ( ( strlen($cQuery->getCampo("NFE_SAIDAS")) > 5 )  && ( $cQuery->getCampo("NFE_SAIDAS") <> '' ) ) {

		  $nfComp = 'S';
  		  $nfReferenciada =   $cQuery->getCampo("NFE_SAIDAS");

		}

        if( ($cQuery->getCampo("NFE_PEDIDO") == 5 )  ||  ($cQuery->getCampo("NFE_PEDIDO") == 6 ) || ($cQuery->getCampo("NFE_PEDIDO") == 7 ) ||  ($cQuery->getCampo("NFE_PEDIDO") == 8 ) ||  ($cQuery->getCampo("NFE_PEDIDO") == 9 ) || ($cQuery->getCampo("NFE_PEDIDO") == 10 ) ||  ($cQuery->getCampo("NFE_PEDIDO") == 11 )  )
         $nfComp = 'S';

  		$obs = $cQuery->getCampo("DSC_OBSCORPONF");
  		$serie =  $cQuery->getCampo("SERIE");
  		$veicPlaca =$cQuery->getCampo("PLACA_PRI");
  		$veicUf =$cQuery->getCampo("UF_PRI");
  		$rebPlaca = $cQuery->getCampo("PLACA_REB");
  		$rebUf =$cQuery->getCampo("UF_REB");
  		$rebSecPlaca =$cQuery->getCampo("PLACA_REB2");
 		$rebSecUf=$cQuery->getCampo("UF_REB2");
		$pedido = $cQuery->getCampo("PEDIDO");
	} else {

	   echo "Erro:Nota inexistente:Erro";
	  // exit();

 	}

	//Gerando os XMLs
	$nomeXML = $cGeraPasseNf->gerarPasse( $out_var ,$NomeEmit,$emit ,$emitCpf ,
                                      	  $emitRep ,  $filial ,  $motCpf ,  $trans,
						              	  $nomTrans ,  $transUf ,  $transCnpj ,
						              	  $veicPlaca ,  $veicUf ,  $rebPlaca ,
						              	  $rebUf ,  $rebSecPlaca ,  $rebSecUf ,
						              	  $login ,  $senha ,
						                  $banco,$pedido ,$cpnjEmitente,
									      $natOp,$nfComp,$nfReferenciada,
									      $obs,$serie);

echo "<br>Arquivo ". $nomeXML;




 }


 	$dir = "xml/xml_resposta/";

 	$link = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/notaGerada.php";

	echo "<script>";
	//echo "location.href=\"$link?nmNota=$out_var&dirNota=$dir&login=$login\"";
	echo "</script>";
   // exit();
   OCILogoff($conn);
?>
