<? session_start(); ?>
<?PHP

//Tnsname
require_once("tnsNames/cTsnames.php");


//Objetos
$tnsName = new cTnsName();

$db = $tnsName->fTnsNames($_SESSION['banco']);


set_time_limit(0);
// Verifica��o de Vari�veis
$start = $_GET["start"];
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
$codigoIni = $_POST["txt_codigoIni"] ;
$codigoFim = $_POST["txt_codigoFin"] ;

$nomeIni = $_POST["txt_nomeIni"] ;
$nomeFim = $_POST["txt_nomeFim"] ;

if ( strlen($nomeIni) <= 0  )
 $nomeIni = $_GET["txt_nomeIni"] ;

if ( strlen($nomeFim) <= 0  )
 $nomeFim = $_GET["txt_nomeFim"] ;
//>>>>>>>

//Busca Condi��o

if (  strlen($codigoIni) > 4   )
  $busca = "AND FO.CODIGO >='$codigoIni'  " . $busca;
if (  strlen($codigoFim) > 4   )
  $busca = "AND FO.CODIGO <='$codigoFim'  ". $busca;

if (  strlen($nomeIni) > 0   )
  $busca = "AND FO.NOME >='$nomeIni'  ". $busca;
  if (  strlen($nomeFim) > 0   )
  $busca = "AND FO.NOME =< '$nomeFim'  ". $busca;

 /*
if (  strlen($CondIni) > 2 )  )
  $busca = "AND P.PEDIDO <='$periodoFim'  ". $busca;
  if (  strlen($CondFim) > 2 )  )
  $busca = "AND P.PEDIDO <='$periodoFim'  ". $busca;    */


//>>>>
// Montando a query SQL.

$sql =  " SELECT FO.CODIGO,FO.NOME, FO.CGC ,  M.COD_UF ESTADO ".
        " FROM FORNEC FO, FORNEC_SEGMENTO EM, FORNEC_EMPRESA FE , MUNICIPIO M ".
        " WHERE EM.COD_SEGMER = '002' AND FO.CODIGO=FE.FORNEC AND FE.EMPRESA='". $_SESSION['empresa'] ."' " .
		" AND FO.COD_MUNICIPIO = M.COD_MUNICIPIO ".
		" AND FE.ATIVO='S' ".
		"  AND FO.CODIGO = EM.COD_FORNEC ".$busca ;

$selQt = "SELECT COUNT(FO.CODIGO)  QTREG ".
         " FROM FORNEC FO, FORNEC_SEGMENTO EM ".
	     " WHERE EM.COD_SEGMER = '002' AND FO.CODIGO = EM.COD_FORNEC ".$busca;
//echo $sql;

// Analisando a query SQL.
$sql_statement = OCIParse($conn, $sql) or die ("Falha na passagem de cl�usula SQL.");
$sql_statQtReg = OCIParse($conn, $selQt) or die ("Falha na passagem de cl�usula SQL.");

// Executando a query SQL.
OCIExecute($sql_statement) or die ("N�o foi poss�vel executar a cl�usula SQL.");
OCIExecute($sql_statQtReg) or die ("N�o foi poss�vel executar a cl�usula SQL.");
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


// Atribui a quantidade total de registros em $rows e colunas em $cols.
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

function PassValorPedido( pedido,nome,cgc,uf ) {
 window.opener.parent.main.document.form.elements["txt_codTrans"].value = pedido;
 window.opener.parent.main.document.form.elements["txt_DscTrans"].value = nome;
 window.opener.parent.main.document.form.elements["txt_TransUf"].value = uf;
 window.opener.parent.main.document.form.elements["txt_TransCnpj"].value = cgc;

 window.opener.parent.main.document.form.elements["txt_codTrans"].disabled=false;
 window.close();
}
</script>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body bgcolor="#FFFFFF" onLoad="MM_preloadImages('../Figuras/btOk2.jpg')">
<form name="form" method="post" action="">
  <table align="center" width="469">
    <tr>
      <td   colspan="5" ><font color="#009966" size="2"><? //echo $sql;?>
        </font></td>
    </tr>
    <tr>
      <td colspan="3" align="left" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sele��o
        da Transportadora</td>
    </tr>
    <tr>
      <td height="50" colspan="5" align="left" valign="top"><table width="459" height="47" border="1" cellpadding="0" cellspacing="0" bordercolor="#009966">
          <tr>
            <td width="455" valign="top"><table width="454" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="79"><b><font color="#009966">C&oacute;digo</font></b></td>
                  <td width="326"><b><font color="#009966">Nome Inicial</font></b></td>
                  <td width="49">&nbsp;</td>
                </tr>
                <tr>
                  <td> <input name="txt_codigoIni" type="text" class="TXT" id="txt_codigoIni" value="<? echo $codigoIni;?>"></td>
                  <td><input name="txt_nomeIni" type="text" class="TXT2" id="txt_nomeIni" value="<? echo $nomeIni;?>"></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><b><font color="#009966">C&oacute;digo</font></b></td>
                  <td><b><font color="#009966">Nome Final</font></b></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><input name="txt_codigoFin" type="text" class="TXT" id="txt_pedidoFin" value="<? echo $codigoFim;?>"></td>
                  <td><strong>
                    <input name="txt_nomeFim" type="text" class="TXT2" id="txt_nomeFim" value="<? echo $nomeFim;?>">
                    </strong></td>
                  <td> <a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('btOk','','../Figuras/btOk2.jpg',1)"><img src="../Figuras/btOk1.jpg" name="btOk" width="36" height="23" border="0"  onClick="document.form.submit();"></a></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
    </tr>
    <tr>
      <td width="49" height="24" align="left"><b><font color="#009966">C&oacute;digo</font></b></td>
      <td width="250" align="left"><b><font color="#009966">Fornecedor</font></b></td>
	  <td width="105" align="left"><b><font color="#009966">Cgc</font></b></td>
	  <td width="40" align="left"><b><font color="#009966">UF</font></b></td>
      <td width="1" align="left"><b></b></td>
    </tr>
    <tr>
      <td bgcolor="#009966" colspan="4"></td>
    </tr>
    <!-- MOSTRANDO RESULTADOS - INICIO -->
    <!-- MOSTRANDO RESULTADOS - FIM -->

    <?
    //$contReg = $start;
	$acho = true;
     while (  OCIFetch($sql_statement)  && $acho ) {

	if ( ( $contReg >= $start ) && ( $contReg <= $stop ) ) {
  ?>
    <tr onMouseOver="liga('tr<?=$contReg?>'); " id="tr<?=$contReg?>" onMouseOut="desliga('tr<?=$contReg?>', '');" onClick="PassValorPedido( '<?  echo OCIResult($sql_statement, "CODIGO"); ?>' ,'<? echo OCIResult($sql_statement, "NOME");?>' ,'<? echo OCIResult($sql_statement, "CGC");?>' ,'<? echo OCIResult($sql_statement, "ESTADO");?>');" class="CURSOR">
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "CODIGO"); ?></font></td>
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "NOME"); ?></font></td>
	  <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "CGC"); ?></font></td>
	  <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "ESTADO"); ?></font></td>
    </tr>
    <?
    }

	if ( $contReg > $stop )
	 $acho = false;

	$contReg++;


   }

    ?>

    <tr>
      <td bgcolor="#009966" colspan="4"></td>
    </tr>
    <tr>
      <td align="center" colspan="4"> </td>
    </tr>
    <tr>
      <td height="21" colspan="10" align="center" valign="top">
        <?



// Condi��es para mostrar na tela os pr�ximos registros e os registros anteriores.
if ($start > 1)
{
    // Mostra os registros anteriores em forma de link.
    $back = $start - $offset;
    echo "<A HREF=\"AjudaTransportadora.php?start=$back&txt_nomeFim=$nomeFim&txt_nomeIni=$nomeIni\">Anteriores $offset</A>";
}

echo "&nbsp;";

if ($stop < $rows)
{
    // Mostra os pr�ximos registros em forma de link.
    $go = $start + $offset;
    echo "<A HREF=\"AjudaTransportadora.php?start=$go&txt_nomeFim=$nomeFim&txt_nomeIni=$nomeIni\">Pr�ximos $offset</A>";
}

echo "<p>";

// Mostra o total de registros.
//echo "Total de Registros : <b>" .   "</b>";
?>
      </td>
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
