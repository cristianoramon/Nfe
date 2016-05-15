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
 require_once("xmlSign/cEnviaNF.php");

 //Retorno o valor do xml
  require_once("Nfs/cValorXml.php");

 //Classe Social
 require_once("Nfs/cClasseFiscal.php");

 //Classe Configuracao
 require_once("Nfs/cConfigura.php");

 //Tnsname
 require_once("tnsNames/cTsnames.php");

 require('Nfs/cGeraPasseNf.php');




$cGeraPasseNf   =  new  cGeraPasseNf();
$tnsName = new cTnsName();



$login  =  $_SESSION["login"];
$db     =  $_SESSION['banco'];
$senha  =  $_SESSION['senha'];
$nf     =  $_POST["txt_nfe"];
$filial =  $_SESSION["empresa"];



 $banco = $db;
 $db = $tnsName->fTnsNames($db);
 // echo "<br>TnsName " . $banco . '-'.$db;

 $login  = strtoupper($login);

 $senha  = $senha;





 $cQuery = new cQuery($login, $senha,$db,'Oracle');

 //$xmlGerarNf = new cGerarXmlNfs($xml);
 $chave = new cChavedeAcesso();
 $cAssinar = new cAssinaXML();
 $cEnvNF   = new cEnviaNF();
 $verConn  = new cVerConexao();
 $cClasse  = new cClasseFiscal();
 $cConf    = new cConfigura();


 $in_nf =  $nf;
 $serie="1";
 $filial = $filial;

$xml = "xml/xml_assinado/". $login."/".$login ."_".(int) $nf.".xml";

$xml2 = "xml/xml_resposta/". $login ."/".$login ."_".(int) $nf.".xml";

$xml3 = "xml/xml_resposta_recibo/". $login ."/". $login ."_".(int) $nf.".xml";

$xml4 = "xml/xml_recibo_envia/". $login ."/". $login ."_".(int) $nf.".xml";


if ( file_exists($xml) )
  unlink($xml);

if ( file_exists($xml2) )
  unlink($xml2);

if ( file_exists($xml3) )
  unlink($xml3);

if ( file_exists($xml4) )
  unlink($xml4);


 //Select da Nf
 //echo $cQuery->fSelect(str_pad($in_nf, 6, "0", STR_PAD_LEFT),$serie,$filial);;
 $cQuery->execSql(str_pad($nf, 6, "0", STR_PAD_LEFT),$serie,$filial);

 //echo "<br> Numero " .$cQuery->getCampo("NF");

 if ( strlen($cQuery->getCampo("NF")) > 5 ) {

  $out_var = $cQuery->getCampo("NF");
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
  //$login    =  $login;
  //$senha    =  $senha;
  //$banco    = $db;
  $in_var   = $cQuery->getCampo("PEDIDO");
  $cpnjEmitente = '';
  $natOp = '';
  $nfComp = '';
  $nfReferenciada =   '';
  $obs = $cQuery->getCampo("DSC_OBSCORPONF");
  $serie =  $cQuery->getCampo("SERIE");
  $veicPlaca =$cQuery->getCampo("PLACA_PRI");
  $veicUf =$cQuery->getCampo("UF_PRI");
  $rebPlaca = $cQuery->getCampo("PLACA_REB");
  $rebUf =$cQuery->getCampo("UF_REB");
  $rebSecPlaca =$cQuery->getCampo("PLACA_REB2");
  $rebSecUf=$cQuery->getCampo("UF_REB2");
  $login  =  $cQuery->getCampo("USUARIO");

} else {

	   echo "<br>Nota inexistente";
	   exit();
 }

//echo $in_nf ."-". $obs . $in_var.'--'. $cQuery->getCampo("NF");




//Gerando os XMLs
$nomeXML = $cGeraPasseNf->gerarPasse( (int) $out_var ,$NomeEmit,$emit ,$emitCpf ,
                                      $emitRep ,  $filial ,  $motCpf ,  $trans,
						              $nomTrans ,  $transUf ,  $transCnpj ,
						              $veicPlaca ,  $veicUf ,  $rebPlaca ,
						              $rebUf ,  $rebSecPlaca ,  $rebSecUf ,
						              $login ,  $senha ,
						              $banco,$in_var ,$cpnjEmitente,
									  $natOp,$nfComp,$nfReferenciada,
									  $obs,$serie );
//echo "Arquivo ". $nomeXML;







 $dir = "xml/xml_resposta/";


 $link = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/notaGeradaCea.php";

echo "<script>";
echo "location.href=\"$link?nmNota=$out_var&dirNota=$dir&login=$login\";";
echo "</script>";

?>
