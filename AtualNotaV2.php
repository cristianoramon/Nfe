<? session_start(); ?>
<? DEFINE("_DATE_FORMAT_LC","%d %m %Y");
?>

 <?php

 //Tnsname
  require_once("tnsNames/cTsnames.php");


 //Objetos
 $tnsName = new cTnsName();


 $db = $tnsName->fTnsNames($_SESSION['banco']);

  //Post
 // $in_nf = $_POST["txt_nf"];


  $emit = $_POST["selEmitente"];

  $emitCpf = $_POST["txt_EmiCpf"];

  $emitRep = $_POST["txt_repart"];

  $pwd      = $_POST["txt_pwd"];

  $mot =  strtoupper($_POST["txt_motorista"]);

  $motCpf = $_POST["txt_cpf"];

  $trans     = $_POST["txt_codTrans"];

  $nomTrans  = $_POST["txt_DscTrans"];

  $transUf   =  strtoupper($_POST["txt_TransUf"]);

  $transCnpj   = $_POST["txt_TransCnpj"];

  $veicPlaca = strtoupper($_POST["txt_placa"]);

  $veicUf = $_POST["SelUfVeiculo"];

  $rebPlaca = strtoupper($_POST["txt_reb"]);

  $rebUf = $_POST["SelUfReb"];

  $rebSecPlaca = strtoupper($_POST["txt_rebSec"]);

  $rebSecUf = $_POST["SelRebSec"];

  $nfReferenciada = $_POST["txt_nfRef"];

  $login  = strtoupper($_SESSION["login"]);

  $senha  = $_SESSION['senha'];

  //$banco  = "crpaaa";
  $banco  = $_SESSION['banco'];

  $natOp = $_POST["txt_DscCfop"];

  $codProd = $_POST["txtProduto"];

  $codDepo = $_POST["txtDeposito"];

  $qtdeProd = $_POST["txtQtde"];

  $seqProd = $_POST["txtSeqPrd"];


?>

<?
set_time_limit(9000000000);

require('ClasseValorNota.php ');
require('cClasseFinanceira.php');
require('cHistorico.php');
require('Nfs/cGeraPasseNf.php');
require('Nfs/cVerNota.php');


$in_var = $_POST["txt_pedido"];
$filial = $_SESSION['empresa'];
$NfMae  = $_POST["txt_mae"] ;
$pl     = $_POST["txt_liq"];
$pb     = $_POST["txt_PesoBruto"];
$qt     = $_POST["txt_qt"] ;
$Cfop   = $_POST["txt_Cfop"] ;
$obs    = $_POST["txt_Obs"] ;
$placa  = $_POST["txtPlaca"] ;
$cbt    = $_POST["txt_cbt"] ;
$adicionais    = $_POST["textAdicionais"];
$codTransp    = $_POST["txt_codTrans"] ;
$Alicmsf      = $_POST["txtAlqICMSF"] ;
$ParNdo      = $_POST["txt_ndo"] ;
$Placa      = $_POST["txt_placa"] ;


$conn = ocilogon($_SESSION['login'], $_SESSION['senha'], $db );

//Instancia
$cValorNota     =  new CValorNota();
$cFinanceira    =  new ClassFinanceira();
$cHistorico     =  new cHistorico();
$cGeraPasseNf   =  new  cGeraPasseNf();
$cVerNota       =  new cVerNota();



$selPedido = " select   p.val_total,ip.qtd_pedida,".
              " ip.preco ,ip.alq_ipi,ip.alq_icms,p.cliente,    ".
              " p.cod_portador,p.ndo,ip.coddep,ip.codprod, ".
              " p.filial "  .
              " from pedido_venda p,itens_pedido_venda ip  ".
              " where p.filial=ip.filial  ".
              " and p.pedido=ip.pedido "  .
              " and p.pedido ='$in_var'";
$sql_statement = OCIParse($conn, $selPedido) or die ("Falha na passagem de cl�usula SQL selPedido.");
OCIExecute($sql_statement) or die ("N�o foi poss�vel executar a cl�usula SQL selPedido.");
Ocifetch($sql_statement );

 $scliente = ociresult($sql_statement,"CLIENTE");
 $sPortador = ociresult($sql_statement,"COD_PORTADOR");
 $codDep    =   ociresult($sql_statement,"CODDEP");


/*

*/

 if ( strlen($ParNdo) >= 2 )
  $ndo = $ParNdo;
 else
  $ndo = ociresult($sql_statement,"NDO");


 $serie = '1';

  //echo "<br>codProd".$codProd;
  $codProd = explode("|",$codProd);

  $codDepo = explode("|",$codDepo);

  $qtdeProd = explode("|",$qtdeProd);

  $seqProd = explode("|",$seqProd);

  $selSeq = " SELECT SEQ_NFE.NEXTVAL SEQNF FROM DUAL";
  $sql_statement = OCIParse($conn, $selSeq) or die ("Falha na passagem de cl�usula SQL selPedido.");
  OCIExecute($sql_statement) or die ("N�o foi poss�vel executar a cl�usula SQL selPedido.");
  Ocifetch( $sql_statement );

  $seqNf = ociresult($sql_statement,"SEQNF");


 //Inserindo Valores na tabela temporario
 for ( $qtPro = 0 ; $qtPro < count($codProd) ; $qtPro++){


   $inSert = "INSERT INTO T_TMP_NFE_SAIDA(  SERIE,
  											FILIAL,
  											CODPRODUTO,
  											QTDE,
  											CODDEPOSITO,
  											PEDIDO,
                                            SEQNF,
                                            SEQPEDIDO,
                                            NF_REFERENCIADA)
					VALUES ('$serie','$filial','".$codProd[$qtPro]."','".$qtdeProd[$qtPro]."','".$codDepo[$qtPro]."','$in_var',$seqNf,".$seqProd[$qtPro].",'')";

   $sqlStatProd = OCIParse($conn,$inSert);



   if ( ! OCIExecute( $sqlStatProd ) ) {

	  ocirollback($conn);
	  echo "<br> Nao foi possovel executar a clausula SQL sqlStatProd.";
	  OCILogoff($conn);
	  exit();

   } else {
          OCICommit($conn);
		  //echo "<br>".$inSert;
	}

 }






 $sPtr = "N";
 $nFrete = 0;
 $sUsuario = '';

 $s = OCIParse($conn, "begin igravanfsaidav3(:pedido, :filial, :NfMae, :pl ,".
                      ":pb,:codTransportadora,:snf,:serie,:obs,:cfop,:seq,:sPtrobras,:sPedido2,".
                      ":sPlacaVeic,:sPlacaReb,:sPlacaReb2,:sPlacaVeicUf,:sPlacaRebUf,:sPlacaRebUf2,:nFretePar,:sNomeUsuario); end;");


//Param IN
  OCIBindByName($s, ":pedido", $in_var);
  OCIBindByName($s, ":filial", $filial);
  OCIBindByName($s, ":NfMae", $NfMae);
  OCIBindByName($s, ":pl", $pl);
  OCIBindByName($s, ":pb", $pb);
  OCIBindByName($s, ":codTransportadora", $codTransp);
  OCIBindByName($s, ":snf", $out_var, 32);
  OCIBindByName($s, ":serie", $serie);
  OCIBindByName($s,":obs", $obs);
  OCIBindByName($s, ":cfop", $Cfop);
  OCIBindByName($s, ":seq", $seqNf);
  OCIBindByName($s, ":sPtrobras", $sPtr);
  OCIBindByName($s, ":sPedido2", $in_var);
  OCIBindByName($s, ":sPlacaVeic", $veicPlaca);
  OCIBindByName($s, ":sPlacaReb", $rebPlaca);
  OCIBindByName($s, ":sPlacaReb2", $rebSecPlaca);
  OCIBindByName($s, ":sPlacaVeicUf", $veicUf);
  OCIBindByName($s, ":sPlacaRebUf", $rebUf);
  OCIBindByName($s, ":sPlacaRebUf2", $rebSecUf  );
  OCIBindByName($s, ":nFretePar", $nFrete  );
  OCIBindByName($s, ":sNomeUsuario", $sUsuario  );


 if ( OCIExecute($s, OCI_DEFAULT) )
   OCICommit($conn);
 else {

   OCILogoff($conn);
   exit();
 }

 $dir = "xml/xml_resposta/";

if ( ($ndo == rtrim(ltrim('995A')))  ||  ($ndo == rtrim(ltrim('890'))) ) {

  sleep(15);
  $nfReferenciada = (int) $out_var;

    include('notaPtr.php');
	$nfComp = "S";

 }

echo "<script>";
$link = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/notaGerada.php";
//echo "location.href=\"$link?nmNota=$out_var&dirNota=$dir\"";
echo "</script>";

?>
