<html>
<head>
<title>.: Selecao de Pedido :.</title>
<link rel="stylesheet" href="../sistema.css" type="text/css">
</head>

<body>
<?
set_time_limit(0);
// Verificacao de Variaveis
if ((!is_numeric($start)) || (strlen($start) <= 0))
{
    $start = 1; // No comeco deve ser definida como 1.
}

if ((!is_numeric($offset)) || (strlen($offset) <= 0))
{
    $offset = 10; // Coloque aqui o limite de resultados por pagina.
}

// Conectando-se com o Banco de Dados.
$conn = OCILogon("","","") or die ("Nao foi possivel logar-se na Base de Dados.");

// Montando a query SQL.
$sql =  " SELECT P.PEDIDO,P.DATA_PEDIDO,C.NOME " .
		" FROM PEDIDO_VENDA P, CLIENTES C, EMPRESA EMP ".
		" WHERE P.CLIENTE = C.CODIGO ".
		" AND  EMP.EMPRESA = '001'".
        " AND  EMP.EMPRESA = P.EMPRESA";

// Analisando a query SQL.
$sql_statement = OCIParse($conn, $sql) or die ("Falha na passagem de param SQL.");

// Executando a query SQL.
OCIExecute($sql_statement) or die ("Nao foi possivel executar a param SQL.");

// Armazenando a quantidade de colunas do Select.
$num_columns = OCINumCols($sql_statement, $conn);

// Atribuindo a quantidade de registros pra 0.
$row_num = 0;

// Condi��o de la�o para quando existem registros no Banco de Dados.
?>
<div id="elementos">

<form name="form">

	<table align="center" width="759">
      <tr>
        <td align="center" class="TITULO" colspan="5">Selecao de Pedido</td>
      </tr>
      <tr>
        <td align="right" colspan="5"> <b>Buscar:</b>&nbsp; <input type="text" name="txtBusca" size="30" class="TEXT">
          &nbsp; <b>Por:</b> <select name="cmbTipoBusca" class="APELIDIO">
            <option value="1">C�digo</option>
            <option value="2">Nome</option>
          </select> <input type="button" name="btnBusca" value="OK" class="BUTTON" onClick="JavaScript:efetua_busca();">
        </td>
      </tr>
      <tr>
        <td width="97" align="left"><b>Pedido</b></td>
        <td width="137" align="left"><b>Data Emiss&atilde;o</b></td>
        <td width="216" align="left"><b>Cliente</b></td>
        <td width="285" align="center">Fornecedor</td>
      </tr>
      <tr>
        <td bgcolor="#065CA5" colspan="5"></td>
      </tr>
      <?
		// Condicao  para quando existem registros no Banco de Dados.
		while (OCIFetch($sql_statement))
		{
      	 $row_num++; //incrementa-se a quantidade de registros.
         for ($i=1; $i <= $num_columns; $i++)
         {
           $aresults[$row_num][$i] = OCIResult($sql_statement, $i); //armazena o resultado da coluna atual em uma array multidimensional.
         }
       }

	for($rloop = $start; $rloop <= $stop; $rloop++)
	{
    	for($cloop = 1; $cloop <= $cols; $cloop++)
   		 {
           $resultado = $aresults[$rloop][$cloop] . " ";
    ?>
      <!-- MOSTRANDO RESULTADOS - INICIO -->
      <!-- MOSTRANDO RESULTADOS - FIM -->
      <tr>
        <td align="left"><? echo "$resultado"; ?></td>
        <td align="left"></td>
      </tr>
      <?

	 }


}

?>
      <tr>
        <td bgcolor="#065CA5" colspan="5"></td>
      </tr>
      <tr>
        <td align="center" colspan="5"> </td>
      </tr>
      <tr>
        <td height="21" colspan="11" align="center" valign="top">
<?
	// Libera a query SQL da memoria
	OCIFreeStatement($sql_statement);

	// Desconecta-se do Banco de Dados.
    OCILogoff($conn);

	// Atribui a quantidade total de registros em $rows e colunas em $cols.
    $rows = $row_num;
    $cols = $num_columns;


    if ($rows > ($offset + $start))
    {
       $stop = ($offset + ($start - 1));
    }
   else
    {
     $stop = $rows;
    }





	if ($start > 1)
	{
    	// Mostra os registros anteriores em forma de link.
      $back = $start - $offset;
       echo "<A HREF=\"HelpPedido.php?start=$back\">Anteriores $offset</A>";
     }

	echo "&nbsp;";

	if ($stop < $rows)
	{
     // Mostra os proximos registros em forma de link.
     $go = $start + $offset;
     echo "<A HREF=\"HelpPedido.php?start=$go\">Pr�ximos $offset</A>";
    }

  echo "<p>"; ?>
		&nbsp; </td>
      <tr>
        <td colspan="5" class="INCLUIR_NOVO"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td bgcolor="#065CA5" colspan="5"></td>
      </tr>
      <tr>
        <td colspan="4" align="left">&nbsp; </td>
        <td width="0" align="left"></td>
      </tr>
      <tr>
        <td bgcolor="#065CA5" colspan="5"></td>
      </tr>
      <tr>
        <td colspan="5" align="center">&nbsp; </td>
      </tr>
    </table>
</form>
</div>
</body>
</html>
