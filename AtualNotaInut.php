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




 $in_var = (int) $HTTP_POST_VARS["txt_nfe"];
 $in_var2 = (int) $HTTP_POST_VARS["txt_nfe_final"];
 $just   = $HTTP_POST_VARS["txt_just"];
 //$in_var = "247693";

 $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
 $chave = new cChavedeAcesso();
 $cAssinar = new cAssinaXML();
 $cEnvNF   = new cEnviaNF("");
 $verConn  = new cVerConexao();
 $tnsName = new cTnsName();
 $cConf    = new cConfigura();

  //Banco
  $db = $tnsName->fTnsNames($HTTP_SESSION_VARS['banco']);
  $conn = ocilogon($HTTP_SESSION_VARS['login'], $HTTP_SESSION_VARS['senha'], $db );
  $filial = $_SESSION['empresa'];
 /*****/


 //Configuracao
 $vetConf = $cConf->fConfiguracao( $_SESSION['empresa'] );

 //Lendo o esquema de cancelamento
 $xml = "xml_esquema/canNumercao.xml";
 $xmlGerarNf = new cGerarXmlNfs($xml);

 $cnpj = "12277646000108";
 $ins  = "240015061";
 $emit = "EMPRESA";


  if ( $filial == '002' ) {
    $cnpj = "08426389000143";
    $ins  = "240650760";
    $emit = "COMERCIO EXP. E IMPORT. S.A.";
  }

  if ( $filial == '005' ) {
    $cnpj = "12277646001414";
    $ins  = "241046696";
    $emit = "COOP FILIAL";
  }


 $chaveAcesso = "27".$cnpj."55".$filial.$in_var.$in_var2;

 echo "<br>".$cnpj."-".$in_var;
 $xmlSalvar = "xml/xml_inut_numeracao_nfe/". $HTTP_SESSION_VARS['login']. "/". $HTTP_SESSION_VARS['login'] ."_".$in_var."_".$in_var2.".xml";
 $xmlGerarNf->setNoValor("ano",date("y"),"id","inutilizacao",FALSE,FALSE,0,0);
 $xmlGerarNf->setNoValor("CNPJ",$cnpj,"id","inutilizacao",FALSE,FALSE,0,0);
 $xmlGerarNf->setNoValor("xJust",$just,"id","inutilizacao",FALSE,FALSE,0,0);
 $xmlGerarNf->setNoValor("nNFIni",$in_var,"id","inutilizacao",FALSE,FALSE,0,0);
 $xmlGerarNf->setNoValor("nNFFin",$in_var2,"id","inutilizacao",FALSE,FALSE,1,0);
 $xmlGerarNf->save($xmlSalvar);

 //Assinando o arquivo XML
 $arqSalvar ="xml/xml_inut_numeracao_assinado_nfe/";
 $arqXmlAssinado= $cAssinar->fAssinXMLInut( $xmlSalvar ,"ID".$chaveAcesso , $HTTP_SESSION_VARS['login'] ,$in_var."_".$in_var2,$arqSalvar,$vetConf[1],$vetConf[2]);

 //Enviando o XML
 $cabecalho = "xml/xml_cabecalho/CabCalho1.07.xml";
 //$link = "https://homologacao.nfe.sefazvirtual.rs.gov.br/ws/nfeinutilizacao/NfeInutilizacao.asmx?wsdl";
 $link = "https://nfe.sefazvirtual.rs.gov.br/ws/nfeinutilizacao/NfeInutilizacao.asmx?wsdl";

 $metodo = "nfeInutilizacaoNF";
 $dir = "xml/xml_inut_numeracao_resp_nfe/";
 $cEnvNF->cEnviaXML($link, $arqXmlAssinado, $HTTP_SESSION_VARS['login'],$in_var."_".$in_var2,$cabecalho,$dir,$metodo,4,$vetConf[0],'','','',$_SESSION['senha'],$_SESSION['banco'],$filial);



 echo "<script>";
 $in_var = $in_var."_".$in_var2 ;
 $link = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/ notaNumInu.php";
 //echo "location.href=\"$link?nmNota=$in_var&dirNota=$dir\&respBanco=$out_var\" ";
 echo "</script>";

?>

<?












echo "<br>".$in_var;



?>
