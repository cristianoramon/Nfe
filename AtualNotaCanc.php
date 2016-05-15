<? session_start(); ?>
<? DEFINE("_DATE_FORMAT_LC","%d %m %Y");
 ?>

 <?php

  require("seguranca.php");



 //Gerar o Xml
 require_once("Nfs/cGerarXmlNfs.php");

 //Chave de acesso
 require_once("nfs/cChavedeAcesso.php");

 //Assinatura
 require_once("xmlSign/cAssinaXml.php");

 //Verifica Conexao
 require_once("xmlSign/cVerConexao.php");

 //Enviar o XML
 require_once("xmlSign/cEnviaNF.php");

 //Retorno o valor do xml
 require_once("Nfs/cValorXml.php");

 //Tnsname
 require_once("tnsNames/cTsnames.php");

 //Classe Configuracao
 require_once("Nfs/cConfigura.php");

 //Select
require_once("cNFSaidas.php");


 $in_var =  str_pad( $HTTP_POST_VARS["txt_nfe"],6, "0",STR_PAD_LEFT);
 $just   = $HTTP_POST_VARS["txt_just"];
 $filial = $_SESSION['empresa'];
 $serie =  $_SESSION['serie'];
 //$in_var = "247693";

 $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
 $chave = new cChavedeAcesso();
 $cAssinar = new cAssinaXML();
 $cEnvNF   = new cEnviaNF("");
 $verConn  = new cVerConexao();
 $tnsName = new cTnsName();
 $cConf    = new cConfigura();
 $cSel = new cNFSaidas();

  //Banco
  $db = $tnsName->fTnsNames($_SESSION['banco']);





  $conn = ocilogon("NEWPIRAM", $_SESSION['senha'], $db );

  //$conn = ocilogon("", $_SESSION['senha'], $db );
 /*****/

 //Cancelando a nota
  $s = OCIParse($conn, "begin IPRC_CANCNOTA(:status, :snf, :sFilialP, :sSerie ); end;");

 //echo "Filial $filial - $serie ";
 //Param IN $filial
  OCIBindByName($s, ":snf",  $in_var );
  OCIBindByName($s, ":sFilialP",  $filial );
  OCIBindByName($s, ":sSerie",  $serie );

  //Param OUT
  OCIBindByName($s, ":status", $out_var, 1000);
  OCIExecute($s, OCI_DEFAULT);
  OCICommit($conn);

 //$nf,$serie,$filial
// echo $cSel->cselNFeCanc($in_var,$serie,$filial);
 $sLogin = OCIParse($conn, $cSel->cselNFeCanc($in_var,$serie,$filial));
 OCIExecute($sLogin) or die ("Nao foi possovel executar a clausula SQL.");
 OCIFetch($sLogin);

 //echo "<br>Teste".OCIResult($sLogin, "USUARIO");

 $_SESSION['login'] = OCIResult($sLogin, "USUARIO");

 //echo "<br>cx".$_SESSION['login'];
 $xml = "xml/xml_resposta_recibo/". $_SESSION['login']. "/".$filial."/". $_SESSION['login'] ."_".(int) $in_var.".xml";


 //echo "Path " .$xml;
 $strProto = $cValorXml->ValorXmlNameSpace("//nProt",$xml);
 $chaveAcesso = (String) $cValorXml->ValorXmlNameSpace("//chNFe",$xml);

 $xml = "xml_esquema/canNota.xml";
 $xmlGerarNf = new cGerarXmlNfs($xml);

 $xmlSalvar = "xml/xml_canc/". $_SESSION['login'];
 if ( ! is_dir($xmlSalvar ))
   mkdir($xmlSalvar);

 $xmlSalvar = "xml/xml_canc/". $_SESSION['login']."/".$filial;
 if ( ! is_dir($xmlSalvar ))
   mkdir($xmlSalvar);


 $xmlSalvar = "xml/xml_canc/".$_SESSION['login']. "/".$filial."/". $_SESSION['login'] ."_".(int) $in_var.".xml";

 echo "<br> va $chaveAcesso - $strProto - $just";
 $xmlGerarNf->setNoValor("chNFe",$chaveAcesso,"id","Cancelar",FALSE,FALSE,0,0);
 $xmlGerarNf->setNoValor("nProt",$strProto,"id","Cancelar",FALSE,FALSE,0,0);
 $xmlGerarNf->setNoValor("xJust",$just,"id","Cancelar",FALSE,FALSE,1,0);
 $xmlGerarNf->save($xmlSalvar);


 //Configuracao
 $vetConf = $cConf->fConfiguracao( $_SESSION['empresa'] );

 //Assinando o arquivo XML
 $arqSalvar ="xml/xml_canc_assinado/";
 $arqXmlAssinado= $cAssinar->fAssinXMLCanc( $xmlSalvar ,"ID".$chaveAcesso , $_SESSION['login'] ,(int) $in_var,$arqSalvar,$vetConf[1],$vetConf[2],$filial);

 //Enviando o XML
 $cabecalho = "xml/xml_cabecalho/CabCalho1.07.xml";
 $link = "https://nfe.sefazvirtual.rs.gov.br/ws/nfecancelamento/NfeCancelamento.asmx?wsdl";
 $metodo = "nfeCancelamentoNF";
 $dir = "xml/xml_canc_resposta/";
 $cEnvNF->cEnviaXML($link, $arqXmlAssinado, $_SESSION['login'],(int) $in_var,$cabecalho,$dir,$metodo,3,$vetConf[0],$chaveAcesso,'CANCELADA','CA',$_SESSION['senha'],$_SESSION['banco'],$filial);


 echo "<script>";
 $link = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/notaCancelada.php";
 $in_var = (int) $in_var;
 echo "location.href=\"$link?nmNota=$in_var&dirNota=$dir\&respBanco=$out_var&chave=$chaveAcesso&filial=$filial\" ";
 echo "</script>";

?>

<?












//echo "<br>".$in_var;



?>
