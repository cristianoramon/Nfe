<?

  // function error_function($errno, $errstr, $errfile, $errline, $errcontext) {
    //print_r("<br>dsds" . $errcontext);
	/*echo "<script>location.href=\"http://www.php.net/manual/pt_BR/function.fsockopen.php\"</script>";*/
   //}

  //set_error_handler('error_function'); */



  session_start();

  /*****************************************************************************************
   *  Classe Responsavel por Enviar o xml
   *
   *****************************************************************************************/


  class cEnviaNF{


  function __construct($path){

    //Tnsname
    require_once($path . "tnsNames/cTsnames.php");

   //
   require_once($path . "cNFSaidas.php");

    require_once($path . "nfs/cValorXml.php");

  }

  /*****************************************************************************************
   *
   *****************************************************************************************/
   function cEnviaNota($link,$arq,$usuario,$nf,
                      $dir,$cert,$chave,$strMsg="",$senha,$banco,$filial){

      $cabecalho = 'xml/xml_cabecalho/CabecalhoEnvioLote.xml' ;
      $wsdl = $link;
      $metodo = "nfeRecepcaoLote2";
	  $pass = 'serasa';
      //$certFile = "c:\\xamppWeb\htdocs\xmlSign\crpaaa.pem";
	  $certFile = realpath($cert);
	  //$certFile = "c:\\xamppWeb\htdocs\xmlSign\crpaaa.pem";
	  //'../../xmlSign/crpaaa.pem'

	  //echo "<br> Patch " . $certFile;
	 // $certFile = realpath($cert);



	  try {

        $tempInicioConexao = microtime(true);

        $client = new SoapClient($link, array (

                     'encoding'      => "utf-8",
                     'verifypeer'    => false,
                     'verifyhost'    => false,
                     'soap_version'  => SOAP_1_2,
                     'trace'         => true,
                     'exceptions'    => false,
                     'local_cert'    => $cert,
                     'style'         => SOAP_DOCUMENT,
                     'use'           => SOAP_LITERAL,
                     'connection_timeout ' => 360,
                     'passphrase'    => $pass)
                 );


      $tempFimConexao = microtime(true);

      $tempo = round($tempFimConexao  - $tempInicioConexao,4);

      //$erro = " Conexao estabelecida Tempo -> ". $tempo ." s ";
//      $erro = " Conexao estabelecida ";
  //    $erro = $erro . "Tempo : "  . $tempo ." s ";

    //  $dbConexao = 0;

	} catch ( SoapFault $fault ) {

	    //$tempFimConexao = microtime(TRUE);

       $tes = $tes . " Erro Conexao ";

        //trigger_error("Erro:SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring}):Erro", E_USER_ERROR);
        $erro = "Erro de conexao 1 Conexao -> $tes ". $fault->faultstring;
        $erro = $erro . " Tempo : " . $tempo ." s ";
        $status = "CX";
        $mens   = "Problema de conexao";

        $dbConexao = 1;
        //echo "<br>--".$arq;
        return false ;
	 }
// Arquivo do cabeçalho
    $handle = fopen($cabecalho, "r");
    $buffer = '' ;
    if ($handle) {

       while (!feof($handle))
		$buffer .= fgets($handle) ;

	   fclose($handle);
    } else {
         echo "<br>Erro ao abrir o arquivo de cabeçalho<br>" ;
        return false ;
    }

//    echo "<br>Cabeçalho: " . htmlspecialchars($buffer) .'<br>' ;

    $var_cabec = new SoapVar($buffer, XSD_ANYXML);

    $header = new SOAPHeader("http://www.portalfiscal.inf.br/nfe/wsdl/NfeRecepcao2", 'nfeCabecMsg', $var_cabec);

    $client->__setSoapHeaders($header);

// xml da nota
    $handle = @fopen(realpath($arq), "r");
	$buffer = '' ;

    if ($handle) {
	  $lin = 0 ;
      while (!feof($handle))  {
         $reg = fgets($handle) ;
        if($lin > 0)  // Despreza a 1º linha do xml da nota
   		   $buffer = $buffer  .  $reg ;
//        else
  //         echo '<br>1 linha: ' .htmlspecialchars($reg) . '<br>' ;

        $lin ++ ;
	  }
      fclose($handle);
   } else {
       echo "<br>Erro ao abrir o xml da nota<br>" ;
       return false ;
   }
   $buffer = utf8_encode($buffer);
//    echo "<br>Nota: " . htmlspecialchars($buffer) . '<br>' .


   $params = array('any' => $buffer);

    //echo "<br> Cabecalho " .realpath($cabecalho) . " - " . $cabecalho;

   $status = 'EP';
   $xml = '';
   $msg = '' ;
   $recibo = '' ;
   try {

     $xml = $client->__soapCall($metodo, array('parameters' => $params));
     $xml = $client->__getLastResponse() ;

//     echo("<br>\nDumping response headers:\n" .$client->__getLastResponseHeaders());

     $arqRespostaDir = $dir.$usuario;

     if ( ! is_dir( $arqRespostaDir ) )
      mkdir($arqRespostaDir);

     $arqRespostaDir = $arqRespostaDir . "/" . $filial;

     if ( ! is_dir( $arqRespostaDir ) )
      mkdir($arqRespostaDir);

     $arqResposta = $arqRespostaDir . "/" . $usuario . "_" . $nf . ".xml";
     // Grava o arquivo de retorno
     $handle = @fopen($arqResposta, "w+");
     fwrite($handle,$xml);
     fclose($handle);

     $achoStatus = 0;
     if ( (strlen($xml) > 20 ) )  {

	  $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
	  $retStatus = $cValorXml->ValorXmlNameSpaceVetor("//cStat",$arqResposta);
	  $msg =  $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$arqResposta);
	  $msg = $msg[0] ;

      echo "<br>Retorno: " . $retStatus[0] . '<br>' ;

	  if((int)$retStatus[0] == 103) { // retorno com sucesso
  	    $recibo =  $cValorXml->ValorXmlNameSpaceVetor("//nRec",$arqResposta);
  	    $recibo = $recibo[0] ;

	    $msg   = " ENVIADA.No RECIBO " . $recibo ;
	    $erro = $msg ;
        $status = 'EN' ;


	  } else  if(!strlen($msg))
         $erro = $xml ;  // Retornou algum erro não identificado
       else
         $erro = $msg ;


   } else {
      echo "<br>Erro: " . $xml ;

      $erro = $xml ;
   }

  }  catch (SoapFault $excecao) {

   $erro   = "ERRO NO ENVIO." . $client->__getLastResponse() ;

 }

 // altera o Status da nota
 $tnsName = new cTnsName();
 $cGrava  = new cNFSaidas();

  // Nome do Banco
 $db = $tnsName->fTnsNames($banco);

 $conn = ocilogon("NEWPIRAM", $senha, $db );

 $tipoNf = "N";
 if ( strlen( $strMsg ) > 0  &&   $status == 'EN'   )
   $msg  .= '-' . $strMsg;

 //Verificando o Tipo de Envio ( Contigencia ou Normal )
 //echo '<br>'.$cGrava->cVerStatus( $chave );
 $sqlVerStatus = OCIParse($conn,$cGrava->cVerStatus( $chave ) );
 OCIExecute($sqlVerStatus);

if ( OCIFetch( $sqlVerStatus ) ) {
	 if ( strtoupper(OCIResult($sqlVerStatus,"TIPO_NOTA")) == "C"  ) {
		 $tipoNf = "C";
		 $mens = $mens . " IMPR ";
	  }
}

 //$erro
 $erro = str_replace("'","",$erro);
 $sqlGravNFAtual = OCIParse($conn,$cGrava->cAtualizaStatus($status,$chave,$msg,$tipoNf,$erro,$recibo ) );

 if ( ! OCIExecute($sqlGravNFAtual) ) {
    ocirollback($conn);
 }


 oci_commit($conn);
 OCILogoff($conn);

 //echo  "<br> Resquest " . $client->__getLastRequest();
 //echo "<br> Response " . $client->__getLastResponse();
 return $status == 'EN' ;
 }

  /*****************************************************************************************
   *
   *****************************************************************************************/
   function fVerServStatus($link,$usuario,$dir,$cert){

      $wsdl = $link;
      $metodo = "nfeStatusServicoNF2";
	  $pass = 'serasa';
	  $cabecalho = 'xml/xml_cabecalho/CabecalhoStatusServ.xml' ;
	  //$certFile = "c:\\xamppWeb\htdocs\xmlSign\crpaaa.pem";
      $certFile = realpath($cert);
      $nomeArqDest = date("d_m_Y_H_m_i");


      $arq = "xml_esquema/statusServ.xml";

//      $arq= "C:\\Inetpub\wwwroot\NfeV3Hom\xml_esquema\statusServ.xml";
//	  echo "<br>Certificado: ".$certFile . '<br>' ;
//	  echo "<br>Arquivo Status: " . $arq . '<br>' ;
//	  echo "<br>Cabeçalho: " . $cabecalho . '<br>' ;
//	  echo "<br>Destino: " . $dir . '<br>' ;



	  try {


       $client = new SoapClient($link, array (

                     'encoding'      => "utf-8",
                     'verifypeer'    => false,
                     'verifyhost'    => false,
                     'soap_version'  => SOAP_1_2,
                     'trace'         => true,
                     'exceptions'    => false,
                     'local_cert'    => $certFile,
                     'style'         => SOAP_DOCUMENT,
                     'use'           => SOAP_LITERAL,
                     'passphrase'    => $pass)
                 );


	} catch ( SoapFault  $fault ) {
	   echo "<b>Erro: SoapFault  {$fault->faultstring} :Erro</b>";
	  return false ;

	 } catch ( Exception  $fault ) {
	     echo "<b>Erro: Exception {$fault->faultstring} :Erro</b>";
	    return false ;
	  }



    $cabecalho = realpath($cabecalho) ;
    $handle = @fopen($cabecalho, "r");

    if ($handle) {

       while (!feof($handle))
		$buffer3 = $buffer3  . fgets($handle) ;

	   fclose($handle);
    } else
        echo "<br>Erro ao abrir o arquivo de cabeçalho " . $cabecalho ;

    $var_cabec = new SoapVar($buffer3, XSD_ANYXML);
    $header = new SOAPHeader("http://www.portalfiscal.inf.br/nfe/wsdl/NfeStatusServico2", 'nfeCabecMsg', $var_cabec);

    $client->__setSoapHeaders($header);

// Ler arquivo xml do status do servidor
      $arq = realpath($arq) ;
    $handle = @fopen($arq, "r");  // Arquivo de retorno do status

    if ($handle) {

      while (!feof($handle))
		$buffer = $buffer  .  fgets($handle) ;


      fclose($handle);
   } else
        echo "<br>Erro ao abrir o arquivo de status " . $arq ;


   $params = array('any' => $buffer);


   try {

  // echo "<br> Call " . $call . " - " . $link;
   $xmlresp = $client->__soapCall($metodo, array('parameters' => $params));

   $xml = $client->__getLastResponse() ;


   $arqRespostaVer = $dir . $usuario . "/" ;

   $arqResposta = $arqRespostaVer . $usuario . "_" . $nomeArqDest . ".xml";

   if ( ! is_dir( $arqRespostaVer ) )
     mkdir($arqRespostaVer);


   if ( strlen($xml) > 20 ) {

    $handle = @fopen($arqResposta, "w");
    fwrite($handle,$xml);
    fclose($handle);

	  $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
	  $Status = $cValorXml->ValorXmlNameSpaceVetor("//cStat",$arqResposta);
	  $valor =  $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$arqResposta);

	  if ( (int)$Status[0] == 107) return true ;
    }

 }
 catch (SoapFault $excecao) {
	echo "Erro2: ";
	echo "<b> {$excecao->faultstring}:Erro</b>";
	return false ;
 }

 catch (Exception $excecao) {
	echo "Erro2: ";
	echo "<b> {$excecao->faultstring}:Erro</b>";
	return false ;
 }

    //echo  "<br> Resquest " . $client->__getLastRequest();
    //echo "<br> Response " . $client->__getLastResponse();
	return false ;
 }

   function RetornoLote($link,$recibo,$arqRetorno,$cert){

      $metodo = "NfeRetRecepcao2";
	  $pass = 'serasa';

	  $cabecalho = 'NfeV3HomV2/xml/xml_cabecalho/CabecalhoRetornoLote.xml' ;
//	  $arqDados  = 'C:/Inetpub/wwwroot/NfeV3HomV2/xml_esquema/RetEnvioVersao20.xml' ;
      $certFile = realpath($cert);


	  try {


       $client = new SoapClient($link, array (

                     'encoding'      => "utf-8",
                     'verifypeer'    => false,
                     'verifyhost'    => false,
                     'soap_version'  => SOAP_1_2,
                     'trace'         => true,
                     'exceptions'    => false,
                     'local_cert'    => $certFile,
                     'style'         => SOAP_DOCUMENT,
                     'use'           => SOAP_LITERAL,
                     'passphrase'    => $pass)
                 );


	} catch ( SoapFault  $fault ) {
	   echo "<b>Erro: SoapFault  {$fault->faultstring} :Erro</b>";
	  return false ;

	 } catch ( Exception  $fault ) {
	     echo "<b>Erro: Exception {$fault->faultstring} :Erro</b>";
	    return false ;
	  }


    $handle = @fopen($cabecalho, "r");

    if ($handle) {
       while (!feof($handle)) {
         $var = fgets($handle) ;
 		   $buffer3 = $buffer3  . $var ;
		}

	   fclose($handle);
    } else {
        echo "<br>Erro ao abrir o arquivo de cabeçalho " . $cabecalho ;
        return false ;
     }

    $var_cabec = new SoapVar($buffer3, XSD_ANYXML);
    $header = new SOAPHeader("http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2", 'nfeCabecMsg', $var_cabec);

    $client->__setSoapHeaders($header);

// Ler arquivo xml do retorno
    $buffer = '<consReciNFe xmlns="http://www.portalfiscal.inf.br/nfe" versao="2.00"><tpAmb>2</tpAmb><nRec>' . $recibo . '</nRec></consReciNFe>' ;

/*    $handle = @fopen($arqEnvio, "r");  // Arquivo de retorno do status
    $buffer = '' ;
    if ($handle) {
      $lin = 0 ;
      while (!feof($handle)) {
         $var = fgets($handle) ;
         if($lin > 0) // Despreza a 1 linha
 		   $buffer .=   $var ;
	     $lin ++ ;
  	 }
      fclose($handle);
   } else {
        echo "<br>Erro ao abrir o arquivo de status " . $arqEnvio ;
        return false ;
        }*/
//   echo "Arquivo de Cabec: " . htmlspecialchars($buffer3)  . '<br>' ;
//   echo "Arquivo de Envio: " . htmlspecialchars($buffer)  . '<br>' ;
   $params = array('any' => $buffer);

   try {

  // echo "<br> Call " . $call . " - " . $link;
   $xmlresp = $client->__soapCall($metodo, array('parameters' => $params));

   $xml = $client->__getLastResponse() ;


   if ( strlen($xml) > 20 ) {

    $handle = @fopen($arqRetorno, "w+");
    fwrite($handle,$xml);
    fclose($handle);

     return true ;
     }
 }
 catch (SoapFault $excecao) {
	echo "Erro2: ";
	echo "<b> {$excecao->faultstring}:Erro</b>";
	return false ;
 }

 catch (Exception $excecao) {
	echo "Erro2: ";
	echo "<b> {$excecao->faultstring}:Erro</b>";
	return false ;
 }

	return false ;
 }

   function CancelaNota($link,$arqEnvio,$arqRetorno,$cert){

      $metodo = "nfeCancelamentoNF2";
	  $pass = 'serasa';

	  $cabecalho = 'C:/Inetpub/wwwroot/NfeV3HomV2/xml/xml_cabecalho/CabecalhoCancelamento.xml' ;
      $certFile = realpath($cert);

	  try {


       $client = new SoapClient($link, array (

                     'encoding'      => "utf-8",
                     'verifypeer'    => false,
                     'verifyhost'    => false,
                     'soap_version'  => SOAP_1_2,
                     'trace'         => true,
                     'exceptions'    => false,
                     'local_cert'    => $certFile,
                     'style'         => SOAP_DOCUMENT,
                     'use'           => SOAP_LITERAL,
                     'passphrase'    => $pass)
                 );


	} catch ( SoapFault  $fault ) {
	   echo "<b>Erro: SoapFault  {$fault->faultstring} :Erro</b>";
	  return false ;

	 } catch ( Exception  $fault ) {
	     echo "<b>Erro: Exception {$fault->faultstring} :Erro</b>";
	    return false ;
	  }


    $handle = @fopen($cabecalho, "r");

    if ($handle) {
       while (!feof($handle)) {
         $var = fgets($handle) ;
 		   $buffer3 = $buffer3  . $var ;
		}

	   fclose($handle);
    } else {
        echo "<br>Erro ao abrir o arquivo de cabeçalho " . $cabecalho ;
        return false ;
     }

    $var_cabec = new SoapVar($buffer3, XSD_ANYXML);
    $header = new SOAPHeader("http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2", 'nfeCabecMsg', $var_cabec);

    $client->__setSoapHeaders($header);

// Ler arquivo xml do retorno
    $handle = @fopen($arqEnvio, "r");  // Arquivo de retorno do status

    if ($handle) {
      $lin = 0 ;
      while (!feof($handle)) {
         $var = fgets($handle) ;
         if($lin > 0) // Despreza a 1 linha
 		   $buffer3 = $buffer3  . $var ;
	     $lin ++ ;
  	 }
      fclose($handle);
   } else {
        echo "<br>Erro ao abrir o arquivo de status " . $arqEnvio ;
        return false ;
        }


   $params = array('any' => $buffer);


   try {

  // echo "<br> Call " . $call . " - " . $link;
   $xmlresp = $client->__soapCall($metodo, array('parameters' => $params));

   $xml = $client->__getLastResponse() ;


   if ( strlen($xml) > 20 ) {

    $handle = @fopen($arqRetorno, "w+");
    fwrite($handle,$xml);
    fclose($handle);

     return true ;
     }
 }
 catch (SoapFault $excecao) {
	echo "Erro2: ";
	echo "<b> {$excecao->faultstring}:Erro</b>";
	return false ;
 }

 catch (Exception $excecao) {
	echo "Erro2: ";
	echo "<b> {$excecao->faultstring}:Erro</b>";
	return false ;
 }

	return false ;
 }


}
?>
