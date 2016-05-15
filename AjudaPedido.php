<? session_start(); ?>
<?PHP


//Tnsname
require_once("tnsNames/cTsnames.php");

//Objetos
$tnsName = new cTnsName();

$db = $tnsName->fTnsNames($_SESSION['banco']);

set_time_limit(0);


$start = $_GET["start"];

$filial = $_SESSION['empresa'];

// Verifica��o de Vari�veis
if ((!is_numeric($start)) || (strlen($start) <= 0))
{
    $start = 1; // No come�o deve ser definida como 1.
	$contReg = 1;
}

if ((!is_numeric($offset)) || (strlen($offset) <= 0))
{
    $offset = 8; // Coloque aqui o limite de resultados por p�gina.
}

// Conectando-se com o Banco de Dados.
$conn = OCILogon($_SESSION['login'], $_SESSION['senha'],$db ) or die ("N�o foi poss�vel logar-se na Base de Dados.");
//$conn = OCILogon($_SESSION['login'], $_SESSION['senha'],"crpaaa" ) or die ("N�o foi poss�vel logar-se na Base de Dados.");


//Variavel POST
$periodoIni = $_POST["txt_pedidoIni"] ;
$periodoFim = $_POST["txt_pedidoFin"] ;

$EmissaoIni = $_POST["txt_EmissIni"] ;
$EmissaoFim = $_POST["txt_EmissFim"] ;
$CondIni    = $_POST["txt_CondIni"] ;
$CondFim    = $_POST["txt_CondFim"] ;
//>>>>>>>


$busca = "AND P.PEDIDO >='1' AND P.PEDIDO <='1' ";
$busca2 = "AND V.PEDIDO >='1' AND V.PEDIDO <='1' ";;

//Busca Condi��o

if (  strlen($periodoIni) > 4   ) {
  $busca ='';
  $busca2 ='';
  $busca = "AND P.PEDIDO >='$periodoIni'  " . $busca;
  $busca2 = "AND V.PEDIDO >='$periodoIni'  " . $busca2;
}

if (  strlen($periodoFim) > 4   ){
  $busca =  " AND P.PEDIDO <='$periodoFim'  ". $busca;
  $busca2 =  " AND V.PEDIDO <='$periodoFim'  ". $busca2;
}

if (  strlen($EmissaoIni) > 4   )
  $busca =  "AND P.DATA_PEDIDO >='$EmissaoIni'  ". $busca;

if (  strlen($EmissaoFim) > 4   )
  $busca =  "AND P.DATA_PEDIDO =< '$EmissaoFim'  ". $busca;



if ( $_SESSION['login'] == "NEWPIRAM")  {
  $sql =  " SELECT DISTINCT P.PEDIDO,TO_CHAR(P.DATA_PEDIDO,'dd/mm/yyyy') DATA_PEDIDO,C.NOME,EMP.NOME NOMEEMP ," .
          " PV.QTD_PEDIDA, PV.QTD_ATENDIDA , PV.QTD_CANCELADA, ".
		  " TO_CHAR(P.DATA_PEDIDO,'yyyy') ANO ,  C.CODIGO CLIENTE,c.fantasia ,P.FILIAL ".
		  " ,DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, PV.CBT, PV.CBTMAE ), PV.CBT ) CBT  ".
		  ", CICMS.PCT_ALIQ_ICMSF_C , DECODE(C.ESTADO_FAT,'AL',N.CFOP,N.CFOP_FORA_ESTADO )  CFOP ".
		  " ,CF.DESCRICAO , P.NF_REFERENCIADA , DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, P.NDO, P.NDO_MAE ), P.NDO ) NDO, P.NR_MAE , DECODE('1',P.COD_TIP_FRETE,'CIF','FOB')  FRETE".
	      " FROM PEDIDO_VENDA P, CLIENTES C, EMPRESA EMP ,ITENS_PEDIDO_VENDA PV ,CALCULO_ICMS CICMS , NDO N ".
		  " ,CFOP CF ".
		  " WHERE P.CLIENTE = C.CODIGO ".
		  " AND P.STATUS !=  'L' ". $busca.
		  " AND P.STATUS !=  'C' ".
          " AND PV.PEDIDO = P.PEDIDO ".
    	  " AND PV.FILIAL = P.FILIAL ".
		  " AND CICMS.UF_DESTINO = C.ESTADO_FAT ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, PV.CBT, PV.CBTMAE ), PV.CBT )  = CICMS.CBT ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, P.NDO, P.NDO_MAE ), P.NDO )  = CICMS.NDO ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, P.NDO, P.NDO_MAE ), P.NDO )  = N.CODIGO ".
		  " AND  P.COD_PORTADOR IS NOT NULL ".
		  " AND CF.CODIGO  =  DECODE(C.ESTADO_FAT,'AL',N.CFOP,N.CFOP_FORA_ESTADO ) ".
          " AND P.PEDIDO IN ( ".
          " SELECT PEDIDO  ".
          " FROM ITENS_PEDIDO_VENDA V, T_USUARIO_EMPRESA_DEPOSITO U  ".
          " WHERE V.EMPRESA = U.COD_EMPRESA ".
          " AND  V.CODDEP  = U.COD_DEPOSITO ".
		  " AND  V.CODDEP IN (" . $_SESSION['cod_sigla'] .")".
          //" AND CODPROD   IN ('599','601','602','603','2059','600','2148','94','95')  ".
          " AND NOM_USUARIO = '" . $_SESSION['login'] ."' ". $busca2 .
          " AND   V.FILIAL    = '$filial')  ".
		  " AND  EMP.EMPRESA = '$filial'". $busca  .
          " AND  EMP.EMPRESA = P.EMPRESA";
} else {
    $sql =  " SELECT DISTINCT P.PEDIDO,TO_CHAR(P.DATA_PEDIDO,'dd/mm/yyyy') DATA_PEDIDO,C.NOME,EMP.NOME NOMEEMP ," .
          " PV.QTD_PEDIDA, PV.QTD_ATENDIDA , PV.QTD_CANCELADA, ".
		  " TO_CHAR(P.DATA_PEDIDO,'yyyy') ANO ,  C.CODIGO CLIENTE,c.fantasia ,P.FILIAL,".
          " DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, PV.CBT, PV.CBTMAE ), PV.CBT ) CBT , CICMS.PCT_ALIQ_ICMSF_C , DECODE(C.ESTADO_FAT,'AL',N.CFOP,N.CFOP_FORA_ESTADO )  CFOP ".
		  " ,CF.DESCRICAO , P.NF_REFERENCIADA  , DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, P.NDO, P.NDO_MAE ), P.NDO ) NDO, P.NR_MAE , DECODE('1',P.COD_TIP_FRETE,'CIF','FOB')  FRETE".
	      " FROM PEDIDO_VENDA P, CLIENTES C, EMPRESA EMP ,ITENS_PEDIDO_VENDA PV ,CALCULO_ICMS CICMS , NDO N ".
		  " ,CFOP CF ".
		  " WHERE P.CLIENTE = C.CODIGO ".
		  " AND P.STATUS !=  'L' ". $busca.
		  " AND P.STATUS !=  'C' ".
          " AND PV.PEDIDO = P.PEDIDO ".
    	  " AND PV.FILIAL = P.FILIAL ".
          " AND CICMS.UF_DESTINO = C.ESTADO_FAT ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, PV.CBT, PV.CBTMAE ), PV.CBT )  = CICMS.CBT ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, P.NDO, P.NDO_MAE ), P.NDO ) = CICMS.NDO ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, P.NDO, P.NDO_MAE ), P.NDO ) = N.CODIGO ".
		  " AND  P.COD_PORTADOR IS NOT NULL ".
		  " AND CF.CODIGO  =  DECODE(C.ESTADO_FAT,'AL',N.CFOP,N.CFOP_FORA_ESTADO ) ".
          " AND P.PEDIDO IN ( ".
          " SELECT PEDIDO  ".
          " FROM ITENS_PEDIDO_VENDA V, T_USUARIO_EMPRESA_DEPOSITO U  ".
          " WHERE V.EMPRESA = U.COD_EMPRESA ".
          " AND  V.CODDEP  = U.COD_DEPOSITO ".
		  " AND  V.CODDEP IN (" . $_SESSION['cod_sigla'] .")".
          //" AND CODPROD   IN ('599','601','602','603','2059','600','2148','94','95')  ".
          " AND NOM_USUARIO = '" . $_SESSION['login'] ."' ". $busca2.
          " AND   V.FILIAL    = '$filial')  ".
		  " AND  EMP.EMPRESA = '$filial'". $busca  .
          " AND  EMP.EMPRESA = P.EMPRESA";

}

echo $sql;

$selQt = "SELECT COUNT(*)  QTREG ".
         " FROM PEDIDO_VENDA P ".
	     " WHERE P.EMPRESA = '$filial'";



// Analisando a query SQL.
$sql_statement = OCIParse($conn, $sql) or die ("Falha na passagem de clasula SQL.");
$sql_statQtReg = OCIParse($conn, $selQt) or die ("Falha na passagem de clausula SQL.");


// Executando a query SQL.
OCIExecute($sql_statement) or die ("Nao foi poss�vel executar a clausula SQL.");
OCIExecute($sql_statQtReg) or die ("Nao foi poss�vel executar a clausula SQL.");
OCIFetch($sql_statQtReg);



// Atribuindo a quantidade de registros pra 0.
$row_num = OCIResult($sql_statQtReg, "QTREG");
/*
// Condi��o de la�o para quando existem registros no Banco de Dados.
while (OCIFetch($sql_statement))
{
    $row_num++; //incrementa-se a quantidade de registros.
    for ($i=1; $i <= $num_columns; $i++)
    {
        $aresults[$row_num][$i] = OCIResult($sql_statement, $i); //armazena o resultado da coluna atual em uma array multidimensional.
    }
} */


// Atribui a quantidade total de registros em $rows .
$rows = $row_num;

// Condi��o de limita��o da exibi��o dos resultados.
// $stop recebe um n�mero onde deve ser o limite dos registros.
if ($rows > ($offset + $start))
{
    $stop = ($offset + ($start - 1));
}
else
{
    $stop = $rows;
}
?>
<html>
<head>
<link href="../estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}


function liga(cod) {
	 eval(cod+".style.backgroundColor='#FFFFE6'");
}

function desliga(cod, cor) {
	 eval(cod+".style.backgroundColor='" + cor + "'");
}

function PassValorPedido( pedido,qtPed,qtAten, qtdCan,filial,cbt , alqIcmf, cfop,nmCfop, nmEmp, nfRef,nNdoMae, nNrMae) {

 if ( cbt == null )
   cbt = '00';

 window.opener.parent.main.document.form.elements["txt_pedido"].value = pedido;
 //window.opener.parent.main.document.form.elements["txt_qtPed"].value = qtPed;
 //window.opener.parent.main.document.form.elements["txt_qtAten"].value = qtAten;
 //window.opener.parent.main.document.form.elements["txt_qtCanc"].value = qtdCan;
 //window.opener.parent.main.document.form.elements["txt_filial"].value = filial;
 window.opener.parent.main.document.form.elements["txt_cbt"].value = cbt;
 window.opener.parent.main.document.form.elements["txtAlqICMSF"].value = alqIcmf;
 window.opener.parent.main.document.form.elements["txt_Cfop"].value = cfop;
 window.opener.parent.main.document.form.elements["txt_DscCfop"].value = nmCfop;
 //window.opener.parent.main.document.form.elements["txt_DscFilial"].value = nmEmp;
 window.opener.parent.main.document.form.elements["txt_nfRef"].value = nfRef;
 window.opener.parent.main.document.form.elements["txt_ndo"].value = nNdoMae;
 window.opener.parent.main.document.form.elements["txt_mae"].value = nNrMae;
// window.opener.parent.main.document.form.elements["txt_qt"].value = parseFloat(qtPed) -( parseFloat(qtAten) + parseFloat(qtdCan));
 window.opener.parent.main.document.form.elements["txt_pedido"].disabled=false;
 window.close();
}


function  janela(pagina,alt,larg,param){
 url=pagina+param;
 //alert(url);
 parametros  = "height="+alt+", width="+larg;
 parametros += ", status=yes, location=no";
 parametros += ", toolbar=no, menubar=no, scrollbars=no, ";
 // define o tamanho e o local da janela a ser aberta
 parametros += "top="+(((screen.height)/2)-(400/2))+", ";
 parametros += "left="+(((screen.width)/2)-(750/2))+"";
 //alert(parametros);
 window.open(url,"",parametros);

}

</script>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body bgcolor="#FFFFFF" onLoad="MM_preloadImages('../Figuras/btOk2.jpg')">
<form name="form" method="post" action="">
  <table align="center" width="719">
    <tr>
      <td   colspan="9" >&nbsp;&nbsp;&nbsp;<font color="#009966" size="2"><b>Filial
        : </b>COOPER
        <? //echo $sql;?>
        </font></td>
    </tr>
    <tr>
      <td colspan="8" align="left" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sele��o
        de Pedido</td>
    </tr>
    <tr>
      <td height="50" colspan="8" align="left" valign="top"><table width="776" height="47" border="1" cellpadding="0" cellspacing="0" bordercolor="#009966">
          <tr>
            <td width="772" valign="top"><table width="768" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="79"><b><font color="#009966">Ped. Inicial</font></b></td>
                  <td width="108"><b><font color="#009966">Emiss&atilde;o Inicial</font></b></td>
                  <td width="229"><b><font color="#009966">Cond. Inicial</font></b></td>
                  <td width="352">&nbsp;</td>
                </tr>
                <tr>
                  <td> <input name="txt_pedidoIni" type="text" class="TXT" id="txt_pedidoIni" value="<? echo $periodoIni;?>"></td>
                  <td><input name="txt_EmissIni" type="text" class="TXT" id="txt_EmissIni" value="<? echo $EmissaoIni;?>"></td>
                  <td><input name="txt_CondIni" type="text" class="TXT" id="txt_CondIni" value="<? echo $CondIni;?>"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><b><font color="#009966">Ped. Final</font></b></td>
                  <td><b><font color="#009966">Emiss&atilde;o Final</font></b></td>
                  <td><b><font color="#009966">Cond. Final</font></b></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><input name="txt_pedidoFin" type="text" class="TXT" id="txt_pedidoFin" value="<? echo $periodoFim;?>"></td>
                  <td><strong>
                    <input name="txt_EmissFim" type="text" class="TXT" id="txt_EmissFim" value="<? echo $EmissaoFim;?>">
                    </strong></td>
                  <td><input name="txt_CondFim" type="text" class="TXT" id="txt_CondFim" value="<? echo $CondFim;?>">                  </td>
                  <td> <a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('btOk','','../Figuras/btOk2.jpg',1)"><img src="../Figuras/btOk1.jpg" name="btOk" width="36" height="23" border="0"  onClick="document.form.submit();"></a></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td width="45" align="left"><b><font color="#009966">Pedido</font></b></td>
      <td width="56" align="left"><b><font color="#009966">Emiss&atilde;o</font></b></td>
      <td width="192" align="left"><b><font color="#009966">Cliente</font></b></td>
      <td width="192" align="left"><b><font color="#009966">Cliente Fantasia</font></b></td>
      <td width="45" align="left"><b><font color="#009966">Qtde Pedido</font></b></td>
      <td width="60" align="left"><b><font color="#009966">Qtde Atendida</font></b></td>
      <td width="50" align="left"><b><font color="#009966">Qtde Cancelada</font></b></td>
      <td width="50" align="left"><b><font color="#009966">Frete</font></b></td>
    </tr>
    <tr>
      <td bgcolor="#009966" colspan="9"></td>
    </tr>
    <!-- MOSTRANDO RESULTADOS - INICIO -->
    <!-- MOSTRANDO RESULTADOS - FIM -->

    <?
    //$contReg = $start;
	$acho = true;
     while (  OCIFetch($sql_statement)  && $acho ) {

	if ( ( $contReg >= $start ) && ( $contReg <= $stop ) ) {
  ?>
    <tr onMouseOver="liga('tr<?=$contReg?>'); " id="tr<?=$contReg?>" onMouseOut="desliga('tr<?=$contReg?>', '');" onClick="PassValorPedido( '<?  echo OCIResult($sql_statement, "PEDIDO"); ?>','<? echo OCIResult($sql_statement, "QTD_PEDIDA"); ?>','<? echo OCIResult($sql_statement, "QTD_ATENDIDA"); ?>','<? echo OCIResult($sql_statement, "QTD_CANCELADA"); ?>' ,'<? echo OCIResult($sql_statement, "FILIAL"); ?>','<? echo OCIResult($sql_statement, "CBT"); ?>','<? echo OCIResult($sql_statement, "PCT_ALIQ_ICMSF_C"); ?>','<? echo OCIResult($sql_statement, "CFOP"); ?>','<? echo OCIResult($sql_statement, "DESCRICAO"); ?>','<? echo OCIResult($sql_statement, "NOMEEMP"); ?>','<? echo OCIResult($sql_statement, "NF_REFERENCIADA"); ?>','<? echo OCIResult($sql_statement, "NDO"); ?>','<? echo OCIResult($sql_statement, "NR_MAE"); ?>');" class="CURSOR">
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "PEDIDO"); ?></font></td>
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "DATA_PEDIDO"); ?></font></td>
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "NOME"); ?></font></td>
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "FANTASIA"); ?></font></td>
      <td><div align="right"><font color="#009966" size="1"><? echo number_format(OCIResult($sql_statement, "QTD_PEDIDA"),2,',','.'); ?></font></div></td>
      <td><div align="right"><font color="#009966" size="1"><? echo number_format(OCIResult($sql_statement, "QTD_ATENDIDA"),2,',','.'); ?></font></div></td>
      <td align="right"><font color="#009966" size="1"><? echo number_format(OCIResult($sql_statement, "QTD_CANCELADA"),2,',','.'); ?></font></td>
      <td align="left"><font color="#009966" size="1"><? echo OCIResult($sql_statement, "FRETE"); ?></font></td>
    </tr>
    <?
    }

	if ( $contReg > $stop )
	 $acho = false;

	$contReg++;


   }

    ?>
    <tr>
      <td bgcolor="#009966" colspan="9"></td>
    </tr>
    <tr>
      <td align="center" colspan="9"> </td>
    </tr>
    <tr>
      <td height="20" colspan="15" align="center" valign="top">
        <?



// Condi��es para mostrar na tela os pr�ximos registros e os registros anteriores.
if ($start > 1)
{
    // Mostra os registros anteriores em forma de link.
    $back = $start - $offset;
    echo "<A HREF=\"AjudaPedido.php?start=$back\">Anteriores $offset</A>";
}

echo "&nbsp;";

if ($stop < $rows)
{
    // Mostra os pr�ximos registros em forma de link.
    $go = $start + $offset;
    echo "<A HREF=\"AjudaPedido.php?start=$go\">Pr�ximos $offset</A>";
}

?>      </td>
  </table>
</td>
  </tr>

  </tr>
</table>
<?
// Libera a query SQL da mem�ria.
OCIFreeStatement($sql_statement);

// Desconecta-se do Banco de Dados.
OCILogoff($conn);
?>
</form>
</body>
</html>
