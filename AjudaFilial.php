<? session_start(); ?>
<?PHP

//Tnsname
require_once("tnsNames/cTsnames.php");

//Objetos
$tnsName = new cTnsName();

$db = $tnsName->fTnsNames($_SESSION['banco']);

//Var Get
 $usuario =  $_GET["usuario"];

set_time_limit(0);
// Verifica??o de Vari?veis
if ((!is_numeric($start)) || (strlen($start) <= 0))
{
    $start = 1; // No come?o deve ser definida como 1.
	$contReg = 1;
}

if ((!is_numeric($offset)) || (strlen($offset) <= 0))
{
    $offset = 10; // Coloque aqui o limite de resultados por p?gina.
}


// Conectando-se com o Banco de Dados.
//Tem quer tirar essa arruma?ao

//*************/

$conn = OCILogon($login, $senha ,$banco) or die ("Nao foi poss?vel logar-se na Base de Dados.");


$sql = " SELECT EMP.EMPRESA, EMP.NOME ".
       " FROM EMPRESA EMP , USUARIO_EMPRESA UE   ".
	   " WHERE UE.EMPRESA = EMP.EMPRESA ".
       " AND   UE.USUARIO_LOGIN = '". $usuario . "'";

$selQt = "SELECT COUNT(*)  QTREG ".
         " FROM EMPRESA EMP ";

// Analisando a query SQL.
$sql_statement = OCIParse($conn, $sql) or die ("Falha na passagem de clausula SQL.");
$sql_statQtReg = OCIParse($conn, $selQt) or die ("Falha na passagem de clausula SQL.");

// Executando a query SQL.
OCIExecute($sql_statement) or die ("N?o foi poss?vel executar a cl?usula SQL.");
OCIExecute($sql_statQtReg) or die ("N?o foi poss?vel executar a cl?usula SQL.");
OCIFetch($sql_statQtReg);



// Atribuindo a quantidade de registros pra 0.
$row_num = OCIResult($sql_statQtReg, "QTREG");
/*
// Condi??o de la?o para quando existem registros no Banco de Dados.
while (OCIFetch($sql_statement))
{
    $row_num++; //incrementa-se a quantidade de registros.
    for ($i=1; $i <= $num_columns; $i++)
    {
        $aresults[$row_num][$i] = OCIResult($sql_statement, $i); //armazena o resultado da coluna atual em uma array multidimensional.
    }
} */


// Atribui a quantidade total de registros em $rows
$rows = $row_num;

// Condi??o de limita??o da exibi??o dos resultados.
// $stop recebe um n?mero onde deve ser o limite dos registros.
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

function liga(cod) {
	 eval(cod+".style.backgroundColor='#FFFFE6'");
}

function desliga(cod, cor) {
	 eval(cod+".style.backgroundColor='" + cor + "'");
}

function PassValorPedido( empresa ) {


 window.opener.document.frmLogin.elements["txtEmpresa"].value = empresa;
 window.close();
}
</script>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body bgcolor="#FFFFFF">
<form name="form1" method="post" action="">
  <table align="center" width="706">
    <tr>
      <td   colspan="5" >&nbsp;&nbsp;&nbsp;<font color="#009966" size="2"><b>Filial
        : </b>COOP E ALC.AL</font></td>
    </tr>
    <tr>
      <td colspan="4" align="left" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sele??o
        de Filial</td>
    </tr>

    <tr>
      <td width="45" align="left"><b><font color="#009966">Empresa</font></b></td>
      <td width="56" align="left"><b><font color="#009966">Nome</font></b></td>

    </tr>
    <tr>
      <td bgcolor="#009966" colspan="5"></td>
    </tr>
    <!-- MOSTRANDO RESULTADOS - INICIO -->
    <!-- MOSTRANDO RESULTADOS - FIM -->
    <?
  /*
    // Condi??o de exibi??o dos registros na tela.
	// se acabaram os registros ele n?o mostra mais nada.
	for($rloop = $start; $rloop <= $stop; $rloop++)
	{
	  echo "<tr>";
    	for($cloop = 1; $cloop <= $cols; $cloop++)
    	{
      	  $resultado = $aresults[$rloop][$cloop] . " ";
           echo "<td>".$resultado."</td>";
    	}
    	echo "</tr>";
	}

	echo "<p>";
 */
   ?>
    <?
    //$contReg = $start;
	$acho = true;
     while (  OCIFetch($sql_statement)  && $acho ) {

	if ( ( $contReg >= $start ) && ( $contReg <= $stop ) ) {
  ?>
    <tr onMouseOver="liga('tr<?=$contReg?>'); " id="tr<?=$contReg?>" onMouseOut="desliga('tr<?=$contReg?>', '');" onClick="PassValorPedido( '<?  echo OCIResult($sql_statement, "EMPRESA"); ?>');" class="CURSOR">
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "EMPRESA"); ?></font></td>
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "NOME"); ?></font></td>
    </tr>
    <?
    }

	if ( $contReg > $stop )
	 $acho = false;

	$contReg++;


   }

    ?>

    <tr>
      <td bgcolor="#009966" colspan="5"></td>
    </tr>
    <tr>
      <td align="center" colspan="5"> </td>
    </tr>
    <tr>
      <td height="21" colspan="11" align="center" valign="top">
        <?



// Condi??es para mostrar na tela os pr?ximos registros e os registros anteriores.
if ($start > 1)
{
    // Mostra os registros anteriores em forma de link.
    $back = $start - $offset;
    echo "<A HREF=\"AjudaFilial.php?start=$back\">Anteriores $offset</A>";
}

echo "&nbsp;";

if ($stop < $rows)
{
    // Mostra os pr?ximos registros em forma de link.
    $go = $start + $offset;
    echo "<A HREF=\"AjudaFilial.php?start=$go\">Pr?ximos $offset</A>";
}

?>
      </td>
  </table>
</td>
  </tr>

  </tr>
</table>
<?
// Libera a query SQL da mem?ria.
OCIFreeStatement($sql_statement);

// Desconecta-se do Banco de Dados.
OCILogoff($conn);
?>
</form>
</body>
</html>
