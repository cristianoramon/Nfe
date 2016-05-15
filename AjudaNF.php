<? session_start(); ?>
<?PHP

set_time_limit(0);

//Tnsname
require_once("tnsNames/cTsnames.php");


//Objetos
$tnsName = new cTnsName();

$db = $tnsName->fTnsNames($_SESSION['banco']);

$start = $_GET["start"];
// Verificação de Variáveis
if ((!is_numeric($start)) || (strlen($start) <= 0))
{
    $start = 1; // No começo deve ser definida como 1.
	$contReg = 1;
}

if ((!is_numeric($offset)) || (strlen($offset) <= 0))
{
    $offset = 8; // Coloque aqui o limite de resultados por página.
}


// Conectando-se com o Banco de Dados.
$conn = OCILogon($_SESSION['login'], $_SESSION['senha'],$db) or die ("Não foi possível logar-se na Base de Dados.");
//$conn = OCILogon($_SESSION['login'], $_SESSION['senha'],"crpaaa" ) or die ("Não foi possível logar-se na Base de Dados.");


if ( $_SESSION['login'] == "NEWPIRAM")  {
  $sql =  " SELECT P.PEDIDO,TO_CHAR(P.DATA_PEDIDO,'dd/mm/yyyy') DATA_PEDIDO,C.NOME,EMP.NOME NOMEEMP ," .
          " PV.QTD_PEDIDA, PV.QTD_ATENDIDA , PV.QTD_CANCELADA ,".
		  " TO_CHAR(P.DATA_PEDIDO,'yyyy') ANO ,  C.CODIGO CLIENTE,c.fantasia ".
	      " FROM PEDIDO_VENDA P, CLIENTES C, EMPRESA EMP ,ITENS_PEDIDO_VENDA PV ".
		  " WHERE P.CLIENTE = C.CODIGO ".
		  " AND P.STATUS !=  'L' ". $busca.
		  " AND P.STATUS !=  'C' ".
          " AND PV.PEDIDO = P.PEDIDO ".
    	  " AND PV.FILIAL = P.FILIAL " ;


} else {



 	$sql =  "  SELECT SAID.NF,SAID.EMISSAO,C.NOME,SAID.VALOR, SAID.STATUS ".
  	        "  FROM (SELECT  S.NF,S.EMISSAO,S.VALOR, S.STATUS,S.CLIENTE ,S.FILIAL,S.SERIE ".
            "         FROM SAIDAS S ".
            "         WHERE S.EMPRESA = '001' AND ".
            "           S.FILIAL = '001' AND ".
            "           S.EMISSAO = to_date(sysdate,'dd-MM-yy')) SAID , ".
            "           ITENS_SAIDAS ITS, CLIENTES C , ".
            " (SELECT PEDIDO  ".
            " FROM ITENS_PEDIDO_VENDA V, T_USUARIO_EMPRESA_DEPOSITO U ".
            " WHERE V.EMPRESA = '001' ".
            " AND U.COD_EMPRESA ='001' ".
            " AND  V.CODDEP  = U.COD_DEPOSITO ".
            " AND CODPROD   IN ('599','601','602','603','2059') ".
            " AND NOM_USUARIO = '" . $_SESSION['login'] ."' ".
            " AND   V.FILIAL    = '001')  PED".
            " WHERE SAID.SERIE   = ITS.SERIE ".
            " AND   SAID.FILIAL  = ITS.FILIAL ".
            " AND   SAID.CLIENTE = C.CODIGO ".
            " AND   ITS.FILIAL='001'  ".
            " AND   ITS.NF = SAID.NF   ".
            " AND   ITS.SERIE  = SAID.SERIE ".
            " AND   ITS.CODPROD IN ('599','601','602','603','2059') ".
            " AND    ITS.PEDIDO = PED.PEDIDO ".
            " ORDER BY SAID.NF DESC ";





}

echo $sql;
$selQt = "SELECT COUNT(*)  QTREG ".
         " FROM PEDIDO_VENDA P ".
	     " WHERE P.EMPRESA = '001'";



// Analisando a query SQL.
$sql_statement = OCIParse($conn, $sql) or die ("Falha na passagem de cláusula SQL.");
$sql_statQtReg = OCIParse($conn, $selQt) or die ("Falha na passagem de cláusula SQL.");

// Executando a query SQL.
OCIExecute($sql_statement) or die ("Não foi possível executar a cláusula SQL.");
OCIExecute($sql_statQtReg) or die ("Não foi possível executar a cláusula SQL.");
OCIFetch($sql_statQtReg);

// Atribuindo a quantidade de registros pra 0.
$row_num = OCIResult($sql_statQtReg, "QTREG");
/*
// Condição de laço para quando existem registros no Banco de Dados.
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

// Condição de limitação da exibição dos resultados.
// $stop recebe um número onde deve ser o limite dos registros.
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
<!--


function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function liga(cod) {
	 eval(cod+".style.backgroundColor='#FFFFE6'");
}

function desliga(cod, cor) {
	 eval(cod+".style.backgroundColor='" + cor + "'");
}

function PassValorPedido( pedido ) {

 //alert('Deseja cancelar a nota realmente' + pedido);
 //document.form.action="delNf.php?nf='229735'"; //+pedido+"'";
 resposta = window.confirm("Deseja realmente cancelar a nota");

 // location.href = "delNf.php?nf=" + pedido ;

 if ( resposta )
  location.href = "delNf.php?nf=" + pedido ;
 else
  alert("Nota não cancelada");
 //document.form.submit();
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

}//-->
</script>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body bgcolor="#FFFFFF">
<form name="form" method="get" action="">
  <table align="center" width="646">
    <tr>
      <td   colspan="9" ><font color="#009966" size="2"><? //echo $sql;?>
        </font></td>
    </tr>
    <tr>
      <td colspan="8" align="left" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seleção
        da Nf </td>
    </tr>
    <tr>
      <td width="72" align="left"><b><font color="#009966">N&uacute;mero</font></b></td>
      <td width="75" align="left"><b><font color="#009966">Emiss&atilde;o</font></b></td>
      <td width="414" align="left"><b><font color="#009966">Cliente</font></b></td>
      <td width="103" align="left"><div align="left"><b><font color="#009966"> Valor</font></b></div></td>
    </tr>
    <tr>
      <td bgcolor="#009966" colspan="9"></td>
    </tr>
    <!-- MOSTRANDO RESULTADOS - INICIO -->
    <!-- MOSTRANDO RESULTADOS - FIM -->
    <?
  /*
    // Condição de exibição dos registros na tela.
	// se acabaram os registros ele não mostra mais nada.
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
     if (  OCIFetch($sql_statement)  && $acho ) {

	if ( ( $contReg >= $start ) && ( $contReg <= $stop )  && ( OCIResult($sql_statement, "STATUS") =="F")) {
  ?>
    <tr onMouseOver="liga('tr<?=$contReg?>'); " id="tr<?=$contReg?>" onMouseOut="desliga('tr<?=$contReg?>', '');" onClick="PassValorPedido( '<?  echo OCIResult($sql_statement, "NF"); ?>' );" class="CURSOR">
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "NF"); ?></font></td>
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "EMISSAO"); ?></font></td>
      <td><font color="#009966" size="1"><? echo OCIResult($sql_statement, "NOME"); ?></font></td>
      <td><div align="right"><font color="#009966" size="1"><? echo OCIResult($sql_statement, "VALOR"); ?></font></div></td>
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



// Condições para mostrar na tela os próximos registros e os registros anteriores.
if ($start > 1)
{
    // Mostra os registros anteriores em forma de link.
    $back = $start - $offset;
    echo "<A HREF=\"AjudaNF.php?start=$back\">Anteriores $offset</A>";
}

echo "&nbsp;";

if ($stop < $rows)
{
    // Mostra os próximos registros em forma de link.
    $go = $start + $offset;
    echo "<A HREF=\"AjudaNF.php?start=$go\">Próximos $offset</A>";
}

?>      </td>
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
