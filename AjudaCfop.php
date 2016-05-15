<? session_start(); ?>
<?PHP

//Tnsname
require_once("tnsNames/cTsnames.php");

//Objetos
$tnsName = new cTnsName();

$db = $tnsName->fTnsNames($HTTP_SESSION_VARS['banco']);

set_time_limit(0);
// Verificação de Variáveis
$start = $_GET["start"];

if ( (!is_numeric($start)) || (strlen($start) <= 0) )
{
    $start = 1; // No começo deve ser definida como 1.
	$contReg = 1;
	//echo "começo " . $start;
}

if ((!is_numeric($offset)) || (strlen($offset) <= 0))
{
    $offset = 10; // Coloque aqui o limite de resultados por página.
}


// Conectando-se com o Banco de Dados.
$conn = OCILogon($HTTP_SESSION_VARS['login'], $HTTP_SESSION_VARS['senha'],$db ) or die ("Não foi possível logar-se na Base de Dados.");
//$conn = OCILogon($HTTP_SESSION_VARS['login'], $HTTP_SESSION_VARS['senha'],"crpaaa" ) or die ("Não foi possível logar-se na Base de Dados.");

//Variavel POST
$CfopIni = $_GET["txt_cfopIni"] ;
$CfopFim = $_GET["txt_cfopFim"] ;



//Busca Condição


if ( (  strlen($CfopIni) > 2  ) && ( strlen($CfopFim) < 2 ) )
  $busca =  "AND CF.CODIGO >='$CfopIni'  ";

if ( (  strlen($CfopIni) < 2   ) && ( strlen($CfopFim) > 2 ) )
  $busca = "AND CF.CODIGO <='$CfopFim'  ";


 if ( ( strlen($CfopFim) > 2 ) && ( strlen($CfopFim) > 2 ) )
    $busca =  "AND  CF.CODIGO >='$CfopIni' AND  CF.CODIGO <='$CfopFim'";

//>>>>
// Montando a query SQL.
$sql = " SELECT CF.CODIGO,CF.DESCRICAO ".
       " FROM CFOP CF ".
	   " WHERE LENGTH(CF.CODIGO) >=4" .$busca  ;

$selQt = "SELECT COUNT(*)  QTREG ".
         " FROM CFOP CF  WHERE LENGTH(CF.CODIGO) >=4";

// Analisando a query SQL.
$sql_statement = OCIParse($conn, $sql) or die ("Falha na passagem de cláusula SQL.");
$sql_statQtReg = OCIParse($conn, $selQt) or die ("Falha na passagem de cláusula SQL.");

// Executando a query SQL.
OCIExecute($sql_statement) or die ("Não foi possível executar a cláusula SQL.");
OCIExecute($sql_statQtReg) or die ("Não foi possível executar a cláusula SQL.");
OCIFetch($sql_statQtReg);


// Atribuindo a quantidade de registros pra 0.
$row_num = OCIResult($sql_statQtReg, "QTREG");


// Atribui a quantidade total de registros em $rows .
$rows = $row_num;


// Condição de limitação da exibição dos resultados.
// $stop recebe um número onde deve ser o limite dos registros.
if ($rows > ($offset + $start))
{
    $stop = ($offset + ($start - 1));
	//echo "<br>sasa".$stop.'    s '.$offset . 'd'.$start;
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

function PassValorPedido( pedido,dsc ) {
 window.opener.parent.main.document.form.elements["txt_Cfop"].value = pedido;
 window.opener.parent.main.document.form.elements["txt_DscCfop"].value = dsc;
 window.opener.parent.main.document.form.elements["txt_Cfop"].disabled=false;
 window.close();
}
</script>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body bgcolor="#FFFFFF">
<form name="form" method="post" action="">
  <table align="center" width="477">
    <tr>
      <td   colspan="5" >&nbsp;&nbsp;&nbsp;<font color="#009966" size="2"><b>Filial
        : </b>COOP. TESTE <? //echo $busca;?></font></td>
    </tr>
    <tr>
      <td colspan="4" align="left" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seleção
        de Pedido</td>
    </tr>
    <tr>
      <td height="50" colspan="4" align="left" valign="top"><table width="463" height="47" border="1" cellpadding="0" cellspacing="0" bordercolor="#009966">
          <tr>
            <td width="490" valign="top"><table width="457" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="209"><b><font color="#009966">Cfop Inicial</font></b></td>
                  <td width="80"><b></b></td>
                  <td width="127"><b></b></td>
                  <td width="41">&nbsp;</td>
                </tr>
                <tr>
                  <td> <input name="txt_cfopIni" type="text" class="TXT" id="txt_cfopIni" value="<? echo $periodoIni;?>"></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><b><font color="#009966">Cfop Final</font></b></td>
                  <td><b></b></td>
                  <td><b></b></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><input name="txt_cfopFim" type="text" class="TXT" id="txt_cfopFim" value="<? echo $periodoFim;?>"></td>
                  <td><strong> </strong></td>
                  <td>&nbsp; </td>
                  <td><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('btOk','','../Figuras/btOk2.jpg',1)"><img src="../Figuras/btOk1.jpg" name="btOk" width="36" height="23" border="0"  onClick="document.form.submit();"></a></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td width="50" align="left"><b><font color="#009966">Cfop</font></b></td>
      <td width="415" align="left"><b><font color="#009966">Descri&ccedil;&atilde;o
       <? //echo 'stop'.$stop.'-'.$rows;?> </font></b></td>

    </tr>
    <tr>
      <td bgcolor="#009966" colspan="5"></td>
    </tr>
    <!-- MOSTRANDO RESULTADOS - INICIO -->
    <!-- MOSTRANDO RESULTADOS - FIM -->
    <?
    //$contReg = $start;
	$acho = true;
     while (  OCIFetch($sql_statement)  && $acho ) {

	if ( ( $contReg >= $start ) && ( $contReg <= $stop ) ) {
  ?>
    <tr onMouseOver="liga('tr<?=$contReg?>'); " id="tr<?=$contReg?>" onMouseOut="desliga('tr<?=$contReg?>', '');" onClick="PassValorPedido( '<?  echo OCIResult($sql_statement, "CODIGO"); ?>','<? echo OCIResult($sql_statement,"DESCRICAO");?>' );" class="CURSOR">
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "CODIGO"); ?></font></td>
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "DESCRICAO"); ?></font></td>

    </tr>
    <?
    }

	if ( $contReg > $stop )
	 $acho = false;

	$contReg++;


   }

    ?>
    <tr>

    </tr>
    <tr>
      <td bgcolor="#009966" colspan="5"></td>
    </tr>
    <tr>
      <td align="center" colspan="5"> </td>
    </tr>
    <tr>
      <td height="21" colspan="11" align="center" valign="top">
        <?



// Condições para mostrar na tela os próximos registros e os registros anteriores.
if ($start > 1)
{
    // Mostra os registros anteriores em forma de link.
    $back = $start - $offset;
    echo "<A HREF=\"AjudaCfop.php?start=$back\">Anteriores $offset</A>";
}

echo "&nbsp;";

if ($stop < $rows)
{
    // Mostra os próximos registros em forma de link.
    $go = $start + $offset;
    echo "<A HREF=\"AjudaCfop.php?start=$go\">Próximos $offset</A>";
}

?>
      </td>
  </table>
</td>
  </tr>

  </tr>
</table>
<?
// Libera a query SQL da memória.
OCIFreeStatement($sql_statement);

// Desconecta-se do Banco de Dados.
OCILogoff($conn);
?>
</form>
</body>
</html>
