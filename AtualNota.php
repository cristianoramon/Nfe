<? session_start(); ?>
<? DEFINE("_DATE_FORMAT_LC","%d %m %Y");
 ?>

 <?php

 //Tnsname
  require_once("tnsNames/cTsnames.php");


 //Objetos
 $tnsName = new cTnsName();


 $db = $tnsName->fTnsNames($HTTP_SESSION_VARS['banco']);

  //Post
 // $in_nf = $HTTP_POST_VARS["txt_nf"];


  $emit = $HTTP_POST_VARS["selEmitente"];

  $emitCpf = $HTTP_POST_VARS["txt_EmiCpf"];

  $emitRep = $HTTP_POST_VARS["txt_repart"];


  $pwd      = $HTTP_POST_VARS["txt_pwd"];

  $mot =  strtoupper($HTTP_POST_VARS["txt_motorista"]);

  $motCpf = $HTTP_POST_VARS["txt_cpf"];


  $trans     = $HTTP_POST_VARS["txt_codTrans"];

  $nomTrans  = $HTTP_POST_VARS["txt_DscTrans"];

  $transUf   =  strtoupper($HTTP_POST_VARS["txt_TransUf"]);

  $transCnpj   = $HTTP_POST_VARS["txt_TransCnpj"];


  $veicPlaca = strtoupper($HTTP_POST_VARS["txt_placa"]);
  $veicUf = $HTTP_POST_VARS["SelUfVeiculo"];


  $rebPlaca = strtoupper($HTTP_POST_VARS["txt_reb"]);
  $rebUf = $HTTP_POST_VARS["SelUfReb"];


  $rebSecPlaca = strtoupper($HTTP_POST_VARS["txt_rebSec"]);
  $rebSecUf = $HTTP_POST_VARS["SelRebSec"];


  $nfReferenciada = $HTTP_POST_VARS["txt_nfRef"];

  //

  $login  = strtoupper($HTTP_SESSION_VARS["login"]);

  $senha  = $HTTP_SESSION_VARS['senha'];


  $banco  = $HTTP_SESSION_VARS['banco'];

  $natOp = $HTTP_POST_VARS["txt_DscCfop"];

?>

<?
set_time_limit(0);

require('ClasseValorNota.php ');
require('cClasseFinanceira.php');
require('cHistorico.php');
require('Nfs/cGeraPasseNfTeste.php');


$in_var = $HTTP_POST_VARS["txt_pedido"] ;
$filial = $HTTP_POST_VARS["txt_filial"] ;
$NfMae  = $HTTP_POST_VARS["txt_mae"] ;
$pl     = $HTTP_POST_VARS["txt_liq"];
$pb     = $HTTP_POST_VARS["txt_PesoBruto"];
$qt     = $HTTP_POST_VARS["txt_qt"] ;
$Cfop   = $HTTP_POST_VARS["txt_Cfop"] ;
$obs    = $HTTP_POST_VARS["txt_Obs"] ;
$placa  = $HTTP_POST_VARS["txtPlaca"] ;
$cbt    = $HTTP_POST_VARS["txt_cbt"] ;
$adicionais    = $HTTP_POST_VARS["textAdicionais"] ;
$codTransp    = $HTTP_POST_VARS["txt_codTrans"] ;
$datVenc      = $HTTP_POST_VARS["txtData"] ;
$Alicmsf      = $HTTP_POST_VARS["txtAlqICMSF"] ;
$ParNdo      = $HTTP_POST_VARS["txt_ndo"] ;
$Placa      = $HTTP_POST_VARS["txt_placa"] ;

$conn = ocilogon($HTTP_SESSION_VARS['login'], $HTTP_SESSION_VARS['senha'], $db );
//$conn = ocilogon($HTTP_SESSION_VARS['login'], $HTTP_SESSION_VARS['senha'], "crpaaa" );



//Instancia
$cValorNota     =  new CValorNota();
$cFinanceira    =  new ClassFinanceira();
$cHistorico     =  new cHistorico();
$cGeraPasseNf   =  new  cGeraPasseNf();

//Tabela de impressao de cbt amarrada
if ($cbt == '04')
 $cbtDsc = '041';


if ($cbt == '99')
 $cbtDsc = '051';

  /****************************************************************************/
 //Numero do grupo lancamento   nGrupo
 $selGrupo = " SELECT NUM_KEY_GRP_LANC.NEXTVAL NUMGRUPO".
             " FROM DUAL";

  // Analisando a query SQL.
 $sqlStatGrupo = OCIParse($conn, $selGrupo) or die ("Falha na passagem de cl�usula SQL selGrupo.");
 OCIExecute($sqlStatGrupo) or die ("N�o foi poss�vel executar a cl�usula SQL selGrupo.");
 Ocifetch( $sqlStatGrupo );
 $nGrupo = ociresult($sqlStatGrupo,"NUMGRUPO");



  //NUMERO DA TRANSACAO  nNumTrans
 $selNumTrans = " SELECT MAX(SM.SEQUENCIAL) NUMTRANS  ".
                " FROM SEMAFORO SM ".
                " WHERE  SM.TABELA = 'NUMTRANSAC'";

 // Analisando a query SQL.
 $sqlStatNumTrans = OCIParse($conn, $selNumTrans) or die ("Falha na passagem de cl�usula SQL selNumTrans .");
 OCIExecute($sqlStatNumTrans) or die ("N�o foi poss�vel executar a cl�usula SQL selNumTrans.");
 Ocifetch($sqlStatNumTrans );
 $nNumTrans = ociresult($sqlStatNumTrans,"NUMTRANS");

   //ATUALIZANDO O NUM DA TRANSACAO
  $updTrans = " UPDATE SEMAFORO SM SET SM.SEQUENCIAL = SM.SEQUENCIAL + 1 ".
              " WHERE  SM.TABELA = 'NUMTRANSAC'";

  // Analisando a query SQL.
 $sqlStatTrans = OCIParse($conn, $updTrans) or die ("Falha na passagem de cl�usula SQL updTrans.");
 OCIExecute($sqlStatTrans) or die ("N�o foi poss�vel executar a cl�usula SQL updTrans.");
 //Ocifetch($sqlStatTrans );

// echo "<br> Grupo $nGrupo";


  //NUMERO DO MOV  nNumMov
  $selMov = "SELECT MAX(SM.SEQUENCIAL) NUMMOV".
            " FROM SEMAFORO SM ".
            " WHERE  SM.TABELA = 'SEQ_MOV'";
  // Analisando a query SQL.
 $sqlStatMov = OCIParse($conn, $selMov) or die ("Falha na passagem de cl�usula SQL selMov.");
 OCIExecute($sqlStatMov) or die ("N�o foi poss�vel executar a cl�usula SQL selMov .");
 Ocifetch($sqlStatMov );

  //ATUALIZANDO O NUM DA MOVIMENTACAO
   $updat = "UPDATE SEMAFORO SM SET SM.SEQUENCIAL = SM.SEQUENCIAL + 1 ".
           " WHERE  SM.TABELA = 'SEQ_MOV'";
   $sqlStatUpd = OCIParse($conn, $updat) or die ("Falha na passagem de cl�usula SQL updat.");
  OCIExecute($sqlStatUpd) or die ("N�o foi poss�vel executar a cl�usula SQL updat.");
 $nNumMov = ociresult($sqlStatMov,"NUMMOV");
 // echo "<br> Movimento $nNumMov";

OCICommit($conn);

 /*****************************************************************************/


 $selPedido = " select   p.val_total,ip.qtd_pedida,".
              " ip.preco ,ip.alq_ipi,ip.alq_icms,p.cliente,    ".
              " p.cod_portador,p.ndo,ip.coddep,ip.codprod, ".
              " p.filial "  .
              " from pedido_venda p,itens_pedido_venda ip  ".
              " where p.filial=ip.filial  ".
              " and p.pedido=ip.pedido "  .
              " and p.pedido ='$in_var'";

 //FORMULA DA BASE DO ICMS OU IPI
 $selBase = " SELECT CB.BASE_IPI,CB.BASE_ICMS  " .
            " FROM CBT CB WHERE CB.CODCBT = '$cbt' ";

 //Origem
 $selOrigem = " SELECT EMP.ESTADO ".
              " FROM EMPRESA EMP ".
              " WHERE EMP.EMPRESA = '$filial' ";

  // Analisando a query SQL.
 $sql_statement = OCIParse($conn, $selPedido) or die ("Falha na passagem de cl�usula SQL selPedido.");
 OCIExecute($sql_statement) or die ("N�o foi poss�vel executar a cl�usula SQL selPedido.");
 Ocifetch($sql_statement );

 $scliente = ociresult($sql_statement,"CLIENTE");
 $sPortador = ociresult($sql_statement,"COD_PORTADOR");

 if ( strlen($ParNdo) >= 2 )
  $sNdo = $ParNdo;
 else
  $sNdo = ociresult($sql_statement,"NDO");



 $codDep    =   ociresult($sql_statement,"CODDEP");
 $codProd   =   ociresult($sql_statement,"CODPROD");


 //Variavel usado na formula
 $AlqICMS      = ociresult($sql_statement,"ALQ_ICMS");
 $AlqIPI      = ociresult($sql_statement,"ALQ_IPI");
 $AlqISS      = 0;
 $TotalValorICMS     =  0;
 $TotalValorIPI      =  0;
 $ValorICMSF    =  0;
 $valNota     =  0;
 $BaseIPI  =  0;
 $BaseICMS =  0;
 $TotalMercadorias = 0;
 $TotalProdutos     = 0;
 $TotalDescMercadorias = 0;
 $TotalDescProdutos = 0;
 $ValorMercadoria = 0;
 $DescMercadoria = 0;
 $nPorcICMSF = 0;
 $nValDestino = 0;
 $nValOrig = 0;
 $BaseICMSF = 0;
 $nValPauta = 0;
 $valorConfins =   0;
 $valorPis =   0;

 $sql_statOrg = OCIParse($conn, $selOrigem) or die ("Falha na passagem de csula SQL selOrigem .");
 OCIExecute($sql_statOrg) or die ("Naofoi possivel executar a clsula SQL selOrigem.");
 Ocifetch($sql_statOrg );
 $sEstadoOrigem =  ociresult($sql_statOrg,"ESTADO");


  //Destino
  //Estado Cliente sEstadoDestino
 $selEstDestino = " SELECT c.estado_fat,c.nome   ".
                  "  FROM CLIENTES C  " .
                  " WHERE C.CODIGO = '".ociresult($sql_statement,"CLIENTE") ."'";

 $sql_statDst = OCIParse($conn, $selEstDestino) or die ("Falha na passagem de clsula SQL selEstDestino.");
 OCIExecute($sql_statDst) or die ("Nao foi possivel executar a clsula SQL selEstDestino.");
 Ocifetch($sql_statDst );
 $sEstadoDestino =  ociresult($sql_statDst,"ESTADO_FAT");
 $NomeCliente =  ociresult($sql_statDst,"NOME");

 //Ndo com as formulas
  $selImp =  " SELECT N.FORMULA_ICMS,N.FORMULA_ICMSF,N.FORMULA_IPI,  ".
            "  N.FORMULA_NOTA, N.OPERACAO ".
            " FROM NDO N, CONF_IMP CI ".
            " WHERE N.CODIGO='$sNdo' ".
            " AND N.COD_IMP = CI.COD_IMP ".
            " ORDER BY N.COD_IMP";

 //Valor Pauta    nValPauta
  $selPauta = " SELECT CICMSF.VAL_PAUTA , CICMSF.VAL_PAUTA_DEST ".
              " FROM CALCULO_ICMS CICMSF ".
              " WHERE CICMSF.CBT = '$cbt' ".
              " AND CICMSF.NDO = '$sNdo' ".
              " AND CICMSF.UF_DESTINO='$sEstadoDestino'";

 $sql_statPauta = OCIParse($conn, $selPauta) or die ("Falha na passagem de clusula SQL selPauta.");
 OCIExecute($sql_statPauta) or die ("Nao foi possivel executar a clusula SQL selPauta.");

 if ( Ocifetch($sql_statPauta ) )  {
  $nValPauta =  ociresult($sql_statPauta,"VAL_PAUTA");
  $nValPautaDest =  ociresult($sql_statPauta,"VAL_PAUTA_DEST");
 }


 $sql_statBase = OCIParse($conn, $selBase) or die ("Falha na passagem de clusula SQL selBase.");
 OCIExecute($sql_statBase) or die ("Nao foi possivel executar a clusula SQL selBase.");

 if ( Ocifetch($sql_statBase ) ) {
  $BaseIPI  =  $cValorNota->Valor ( ociresult($sql_statBase,"BASE_IPI"));
  $BaseICMS =  $cValorNota->Valor ( ociresult($sql_statBase,"BASE_ICMS"));

 }


  //Total Produto
  $precoUnitario =   ociresult($sql_statement,"PRECO");
  $TotalProdutos =  $precoUnitario * $qt;


 //Ndo
 $sql_statimp = OCIParse($conn, $selImp) or die ("Falha na passagem de clsula SQL selImp.");
 OCIExecute($sql_statimp) or die ("Nao foi possivel executar a clla SQL selImp.");

 //Valor Defaut para calcular a nota
 $valNota = $TotalProdutos;
 $ValorMercadoria = $TotalProdutos;

if( Ocifetch($sql_statimp ) ) {


 $ValorICMSF    =  $cValorNota->Valor ( ociresult($sql_statimp,"FORMULA_ICMSF"));
 $valNota     =  $cValorNota->Valor ( ociresult($sql_statimp,"FORMULA_NOTA"));

 $TotalValorICMS     =  $cValorNota->Valor ( ociresult($sql_statimp,"FORMULA_ICMS"));
 $TotalValorIPI      =  $cValorNota->Valor ( ociresult($sql_statimp,"FORMULA_IPI"));
 $NdoOperacao  =    ociresult($sql_statimp,"OPERACAO");
}



 eval( "\$ValorICMSF=" . $ValorICMSF . ";"  );
 eval( "\$BaseICMS=" . $BaseICMS . ";"  );
 eval( "\$BaseIPI=" . $BaseIPI . ";"  );

if ( $AlqIPI == 0 ) {
  $BaseIPI  = 0;
  $TotalValorIPI =  0;
}


 $nPorcICMSF = $Alicmsf;

  //Calculo do imposto
 if ( $cbt =='66' )  {
  if ( $precoUnitario > $nValPauta )
   $nValPauta = $precoUnitario;

  if ( $precoUnitario > $nValPautaDest )
   $nValPautaDest =   $precoUnitario;

  $BaseICMS = $nValPauta * $qt;
  $ValorICMS = $BaseICMS*$AlqICMS/100;

   if ( $AlqICMS == 0 )
     $ValorICMS = 0;

  //ICMSF
  $BaseICMSF =   $nValPautaDest * $qt;
  $ValorICMSF = (( $nPorcICMSF * $BaseICMSF ) -  ( $TotalProdutos  * $AlqICMS ) ) /100;

  if ( $nPorcICMSF == 0 )
    $ValorICMSF = 0;


 }

 if ( $cbt =='55' ) {

    if ( $precoUnitario > $nValPauta )
      $nValPauta = $precoUnitario;

    if ( $precoUnitario > $nValPautaDest )
      $nValPautaDest =   $precoUnitario;

    $BaseICMS = $nValPauta * $qt;
    $ValorICMS = $BaseICMS*$AlqICMS/100;

 }

 $ValorICMSF = round( $ValorICMSF ,2);
 $TotalValorICMS =  $BaseICMS * $AlqICMS / 100 +   $TotalValorICMS;
 $TotalValorIPI =  $BaseIPI * $AlqIPI / 100 +   $TotalValorIPI;




 $TotalMercadorias =  $TotalProdutos;

 echo "<br>Formula Nota".$TotalValorIPI;
 eval( "\$valNota=" . $valNota . ";"  );



 if ( $nPorcICMSF > 0 )
  $nPorcICMSF = $nPorcICMSF - $AlqICMS;




 $s = OCIParse($conn, "begin iGravaNFSaida(:pedido, :filial, :NfMae, :pl ,".
                      ":pb, :qt,:cfop,:obs,:cbt, :snf ,".
                      ":bICMS, :bICMSF, :bIPI," .
                      ":valNota, :alqICMS, :alqIPI," .
                      ":valICMS, :valIPI, :valICMSF, :porICMSF , :grupoLan,".
                      " :trasportadora,:data, :pNdo, :pnError ); end;");


//Param IN
  OCIBindByName($s, ":pedido", $in_var);
  OCIBindByName($s, ":filial", $filial);
  OCIBindByName($s, ":NfMae", $NfMae);
  OCIBindByName($s, ":pl", $pl);
  OCIBindByName($s, ":pb", $pb);
  OCIBindByName($s, ":qt", $qt);
  OCIBindByName($s, ":cfop", $Cfop);
  OCIBindByName($s, ":obs", $obs);
  OCIBindByName($s, ":cbt", $cbt);
  OCIBindByName($s, ":bICMS", $BaseICMS);
  OCIBindByName($s, ":bICMSF", $BaseICMSF);
  OCIBindByName($s, ":bIPI", $BaseIPI);
  OCIBindByName($s, ":valNota", $valNota);
  OCIBindByName($s, ":alqICMS", $AlqICMS);
  OCIBindByName($s, ":alqIPI", $AlqIPI);
  OCIBindByName($s, ":valICMS", $TotalValorICMS);
  OCIBindByName($s, ":valIPI", $TotalValorIPI);
  OCIBindByName($s, ":valICMSF", $ValorICMSF);
  OCIBindByName($s, ":porICMSF", $nPorcICMSF);
  OCIBindByName($s, ":grupoLan", $nGrupo);
  OCIBindByName($s, ":trasportadora", $codTransp);
  OCIBindByName($s, ":data", $datVenc);
  OCIBindByName($s, ":pNdo", $sNdo);

 // echo ("$in_var - $filial - $NfMae - $pl - $pb -$qt - $Cfop - $obs - $cbt -  $BaseICMS");
//Param OUT
  OCIBindByName($s, ":snf", $out_var, 32); // 32 is the return length
  OCIBindByName($s, ":pnError", $nError);
  OCIExecute($s, OCI_DEFAULT);
 // echo "Procedure returned value: " . $out_var;
//

//Nome da Nota
$nomNota = $HTTP_SESSION_VARS['login'] . "/\Nota_".$HTTP_SESSION_VARS['login']."_$out_var.pdf";


$selProdMat = " SELECT P.DESCRICAO PRODUTO, P.CODTIP,TP.DESCRICAO MATERIAL  ".
              " FROM PRODUTOS P,TIPO_MATERIAL TP  ".
              " WHERE P.CODTIP = TP.CODTIP  ".
              "AND P.CODPROD = '$codProd'";

$sqlStatProdMat = OCIParse($conn, $selProdMat) or die ("Falha na passagem de cl�usula SQL Material Produto.");
OCIExecute($sqlStatProdMat) or die ("N�o foi poss�vel executar a cl�usula SQL Material Produto.");
if ( Ocifetch($sqlStatProdMat )) {
 $dscProduto =  ociresult($sqlStatProdMat,"PRODUTO");
 $codTip = ociresult($sqlStatProdMat,"CODTIP");
 $dscMaterial  = ociresult($sqlStatProdMat,"MATERIAL");
}


//Variavel para montar o historico
$CODIGO_CLIENTE = $scliente;
$CODIGO_FILIAL = $filial;
$CODIGO_TIPO_MATERIAL =$codTip ;
$NOME_CLIENTE=$NomeCliente;
$NOME_FILIAL ="COOP. REG. PROD. DE ACUCAR E ALCOOL DE ALAGOAS" ;
$DESCRICAO_TIPO_MATERIA = $dscMaterial;
$NUMERO_FATURA =$out_var ;
$NUMERO_NOTA_FISCAL = $out_var;
$NUMERO_NOTA_FISCAL_MAE= $NfMae;
$OBSERVACAO = $obs;


//********************************************************************


//********************************************************
if ( strlen($out_var) > 3 &&  (int) $nError <= 0   ) {


   //Verifica se afeta esto,contabi e financeiro
  $selAfeta = " SELECT N.ESTOQUE,n.contabil,n.financeiro, N.SIT_NAOCONTROLA_ESTQ, n.sit_complemento ".
              " FROM NDO N ".
              " WHERE N.CODIGO ='$sNdo'";
  $statAfeta = OCIParse($conn,$selAfeta) or die ("Falha na passagem de cl�usula Ndo F-E-C.");
  OCIExecute($statAfeta) or die ("N�o foi poss�vel executar a cl�usula  Ndo F-E-C..");
  Ocifetch($statAfeta );
  $afetaContab = ociResult($statAfeta,"CONTABIL");
  $afetaMov = ociResult($statAfeta,"SIT_NAOCONTROLA_ESTQ");
  $nfComp = ociResult($statAfeta,"SIT_COMPLEMENTO");

  // Contabiliza��o
  $sqlStatFinanc = OCIParse($conn, $cFinanceira->Transacao($nNumTrans,$filial,$out_var)  ) or die ("Falha na passagem de cl�usula SQL Transacao.");
  if ( ! OCIExecute($sqlStatFinanc) ) {
    ocirollback($conn);
    echo "<br>" . "N�o foi poss�vel executar a cl�usula SQL. Transacao";
	exit();
  }

  //Estoque geral
  $EstGeral = 0;
  $selEst=  " select p.estq_geral ".
			" from prod_empresa p ".
			" where p.empresa = '$filial'".
			" and   p.codprod = '$codProd'";

  $sqlStatEstoq = OCIParse($conn, $selEst) or die ("Falha na passagem de cl�usula SQL. Estoque");
  OCIExecute($sqlStatEstoq) or die ("N�o foi poss�vel executar a cl�usula SQL. Estoque");
  Ocifetch($sqlStatEstoq );
  $EstGeral =  ociresult($sqlStatEstoq,"ESTQ_GERAL");

  //Movimento
  if ( strtoupper($afetaMov) == 'N'  )  {
     $sqlStatMovimentoFinanc = OCIParse($conn, $cFinanceira->Movimento($filial,$nNumMov,$nNumTrans,$codDep,$codProd,$TotalProdutos,$qt, $NdoOperacao,$EstGeral)  ) or die ("Falha na passagem de cl�usula SQL.");
      if ( ! OCIExecute($sqlStatMovimentoFinanc)) {
       ocirollback($conn);
       echo "<br> N�o foi poss�vel executar a cl�usula SQL. Movimento";
	   exit();
     }
  }




  $sqlStatContaCli = OCIParse($conn, $cFinanceira->cliente($scliente,$filial)) or die ("Falha na passagem de cl�usula SQL. cliente");
  OCIExecute($sqlStatContaCli) or die ("N�o foi poss�vel executar a cl�usula SQL. cliente");
  Ocifetch($sqlStatContaCli );
  $contaCli =  ociresult($sqlStatContaCli,"CONTA");
  $cntAux   =   ociresult($sqlStatContaCli,"CNT_AUX");
  $cntItemAux   =   ociresult($sqlStatContaCli,"ITEM_CNTAUX");


  if ( $cntAux =="" )
   $cntAux = ' ';






  /****************** Debito Cliente ***************************************************/

 if ( $afetaContab == "S") {
    $sqlStatNdo = OCIParse($conn, $cFinanceira->impNdoConf($sNdo,'D')) or die ("Falha na passagem de cl�usula SQL.");
    OCIExecute($sqlStatNdo) or die ("N�o foi poss�vel executar a cl�usula SQL Hist  Cli.");
    if ( Ocifetch($sqlStatNdo ) )
      $codHistC = ociresult($sqlStatNdo,"CODHIST");

   if ( ( $codHistC != NULL ) || (strlen($codHistC) > 0 ) ) {

    $sqlStatHist = OCIParse($conn,$cHistorico->selHist( $codHistC)) or die ("Falha na passagem de cl�usula SQL Hist Cli.");
    OCIExecute($sqlStatHist) or die ("N�o foi poss�vel executar a cl�usula SQL. Hist Cli");
    Ocifetch($sqlStatHist );



    $dscHist = ociresult($sqlStatHist,"DESCRICAO");
    $cont = strlen($dscHist );
    $dscHist = substr($dscHist,0,$cont);

    $complemento = $cHistorico->dscHistorico($dscHist);
    eval( eval("\$obsHistorico = \"".$complemento."\";"));

    $sqlStatInsertCli = OCIParse($conn,$cFinanceira->InsCliente($filial,$out_var,$nGrupo,'14',$valNota,$contaCli,$obsHistorico,$codHistC,$cntAux,$cntItemAux)) or die ("Falha na passagem de cl�usula SQL.");

    if ( ! OCIExecute($sqlStatInsertCli) ) {
      ocirollback($conn);
      echo "<br> N�o foi poss�vel executar a cl�usula SQL. sqlStatInsertCli";
	  exit();
    }
  }
    //Imposto do cliente
    $sqlStatImpCli = OCIParse($conn, $cFinanceira->impNdo($sNdo)) or die ("Falha na passagem de cl�usula SQL impNdo.");
    OCIExecute($sqlStatImpCli) or die ("N�o foi poss�vel executar a cl�usula SQL impNdo .");



  while ( Ocifetch($sqlStatImpCli ) ) {
   $imposto = ociresult($sqlStatImpCli,"NOME_IMP");
   $debito  = ociresult($sqlStatImpCli,"CONTA_DEB");
   $codHist  = ociresult($sqlStatImpCli,"HIST_DEB");


   $sqlStatHist = OCIParse($conn,$cHistorico->selHist($codHist)) or die ("Falha na passagem de cl�usula SQL selHist.");
   OCIExecute($sqlStatHist) or die ("N�o foi poss�vel executar a cl�usula SQL selHist.");
   Ocifetch($sqlStatHist );


   $dscHist = ociresult($sqlStatHist,"DESCRICAO");
   $cont = strlen($dscHist );
   $dscHist = substr($dscHist,0,$cont);
   $dscHist2 =  htmlspecialchars($dscHist);




   $complemento = $cHistorico->dscHistorico($dscHist);
   eval( eval("\$obsHistorico = \"".$complemento."\";"));
   $valor = $cValorNota->Valor ( ociresult($sqlStatImpCli,"FORMULA"));

   if ( ociresult($sqlStatImpCli,"FORMULA") != "" )
    eval("\$valor=".$valor.";");

   if ( $imposto == "ICMS" ) {
    $valor =  $TotalValorICMS;
   }

   if ( $imposto == "ICMSF" ) {
     $valor =   $ValorICMSF;
   }

   if ( $imposto == "CONFINS" ) {
    $valorConfins =   $valor;
   }

   if ( $imposto == "PIS" ) {
     $valorPis =   $valor;
   }

   if ( ( $imposto == "IPI"  ) && ( $AlqIPI > 0 ) ) {
     $valor =   $TotalValorIPI;
   }


   if ( ( $debito != "" ) && ( $valor != "" ) && ( $codHist != NULL) )  {
    $sqlStatInsertCli = OCIParse($conn,$cFinanceira->InsCliente($filial,$out_var,$nGrupo,'14',$valor,$debito,$obsHistorico,$codHist,'', '')) or die ("Falha na passagem de cl�usula SQL.");

    if ( ! OCIExecute($sqlStatInsertCli) ) {
	  ocirollback($conn);
	  echo "<br> Nao foi poss�vel executar a clsula SQL sqlStatInsertCli.";
	  exit();

	}
   }
 }

 $sqlStatContaProd = OCIParse($conn, $cFinanceira->ContaProduto($codProd,$sNdo,$filial)) or die ("Falha na passagem de  SQL Conta Produto.");
 OCIExecute($sqlStatContaProd) or die ("Nao foi possivel executar a  SQL Conta Produto.");
 Ocifetch($sqlStatContaProd );
 $contaProd =  ociresult($sqlStatContaProd,"CONTA");
 $codHistP  =  ociresult($sqlStatContaProd,"HISTORICO");


/****************** Credito Produto ***************************************************/
 if ( ( $contaProd != NULL ) || ( strlen($codHistP)> 0 ) ) {

   if ( $AlqIPI > 0 )
     $TotalProdutos = $TotalProdutos + $TotalValorIPI;

   $sqlStatInsertProd = OCIParse($conn,$cFinanceira->InsertProduto($nGrupo,$contaProd,$TotalProdutos,$out_var,'14',$filial,$obsHistorico,$codHistP ) ) or die ("Falha na passagem de cl�usula SQL.");


   if ( ! OCIExecute($sqlStatInsertProd) ) {
     ocirollback($conn);
	 echo  $cFinanceira->InsertProduto($nGrupo,$contaProd,$TotalProdutos,$out_var,'14',$filial,$obsHistorico,$codHistP );
     echo "<br> Da  SQL InsertProduto.";
	 exit();
  }
 }

 //Imposto do produto
 $sqlStatImpProd = OCIParse($conn, $cFinanceira->impNdo($sNdo)) or die ("Falha na passagem de cl�usula SQL impNdo.");
 OCIExecute($sqlStatImpProd) or die ("nao foi possivel executar a clausula SQL impNdo.");


 while ( Ocifetch($sqlStatImpProd ) ) {
   $imposto = ociresult($sqlStatImpProd,"NOME_IMP");
   $credito  = ociresult($sqlStatImpProd,"CONTA_CRE");
   $codHist  = ociresult($sqlStatImpProd,"HIST_CRE");


   $sqlStatHist = OCIParse($conn,$cHistorico->selHist($codHist)) or die ("Falha na passagem de cl�usula SQL selHist.");
   OCIExecute($sqlStatHist) or die ("nao foi possivel executar a clausula  a clausula SQL selHist.");
   Ocifetch($sqlStatHist );


  $dscHist = ociresult($sqlStatHist,"DESCRICAO");
  $cont = strlen($dscHist );
  $dscHist = substr($dscHist,0,$cont);
  $dscHist2 =  htmlspecialchars($dscHist);




  $complemento = $cHistorico->dscHistorico($dscHist);
  eval( eval("\$obsHistorico = \"".$complemento."\";"));
  $valor = $cValorNota->Valor ( ociresult($sqlStatImpProd,"FORMULA"));

  if ( ociresult($sqlStatImpProd,"FORMULA") != "" )
   eval("\$valor=".$valor.";");



  if ( $imposto == "ICMS" ) {
   $valor =  $TotalValorICMS;
  }

  if ( $imposto == "ICMSF" ) {
   $valor =   $ValorICMSF;
  }

   if ( ( $imposto == "IPI"  ) && ( $AlqIPI > 0 ) ) {
     $valor =   $TotalValorIPI;
   }


  if ( ($credito != "") && ( $valor != "" )  )   {
   $sqlStatInsertProd = OCIParse($conn,$cFinanceira->InsertProduto($nGrupo,$credito,$valor,$out_var,'14',$filial,$obsHistorico,$codHist)) or die ("Falha na passagem de cl�usula SQL.");
   if ( ! OCIExecute($sqlStatInsertProd) ) {
     ocirollback($conn);
	echo "<br>nao foi possivel executar a clausulaQL sqlStatInsertProd.";
	exit();
   }
  }

 }
}

//Atualizando o PIS e o CONFINS
$upSaida = "UPDATE SAIDAS SET VAL_PIS = round($valorPis,2), VAL_COFINS = round($valorConfins,2),PLACA_PRI = '$veicPlaca', PLACA_REB='$rebPlaca', PLACA_REB2='$rebSecPlaca' WHERE NF = '$out_var' AND cliente = '$scliente'  and empresa='$filial'";
$sqlUpdateSaidas = OCIParse($conn,$upSaida) or die ("Falha na passagem de cl�usula SQL.Saidas");
//echo $upSaida;

 if ( ! OCIExecute($sqlUpdateSaidas) ) {
   ocirollback($conn);
    echo "<br>Nao foi Atualizar o PIS o CONFIS na saidas.";
	exit();
 }

OCICommit($conn);
}else {
  ocirollback($conn);
 }
?>


<?




//Gerando os XMLs
$nomeXML = $cGeraPasseNf->gerarPasse( $out_var ,$NomeEmit,$emit ,$emitCpf ,
                                      $emitRep ,  $mot ,  $motCpf ,  $trans,
						              $nomTrans ,  $transUf ,  $transCnpj ,
						              $veicPlaca ,  $veicUf ,  $rebPlaca ,
						              $rebUf ,  $rebSecPlaca ,  $rebSecUf ,
						              $login ,  $senha ,
						              $banco,$arrPer ,$cpnjEmitente,
									  $natOp,$nfComp,$nfReferenciada,
									  $obs );



echo "<script>";



 if (( $nError > 0 )  ) {
  echo " alert( ' Nota Fiscal mae invalida  ". $NfMae ." ' );";
}

 $dir = "xml/xml_resposta/";

 $link = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/notaGerada.php";


// echo "location.href=\"$link?nmNota=$out_var&dirNota=$dir\"";
echo "</script>";
?>
