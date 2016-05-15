<? session_start(); ?>
<?PHP


//Tnsname
require_once("tnsNames/cTsnames.php");

//Objetos
$tnsName = new cTnsName();

$db = $tnsName->fTnsNames($_SESSION['banco']);

set_time_limit(0); 

$pedido = $_GET["pedido"];

$filial = $_SESSION['empresa'];


// Conectando-se com o Banco de Dados.
$conn = OCILogon($_SESSION['login'], $_SESSION['senha'],$db ) or die ("Não foi possível logar-se na Base de Dados.");
//$conn = OCILogon($_SESSION['login'], $_SESSION['senha'],"crpaaa" ) or die ("Não foi possível logar-se na Base de Dados.");



if ( $_SESSION['login'] == "NEWPIRAM")  {
  $sql =  " SELECT  P.PEDIDO, DE.CODDEP, DE.SIGLA,PR.CODPROD,PR.DESCRICAO,( PV.QTD_PEDIDA - (PV.QTD_ATENDIDA + PV.QTD_CANCELADA )) QTD_PEDIDA , PV.SEQUENCIAL".
	      " FROM PEDIDO_VENDA P, CLIENTES C, EMPRESA EMP ,ITENS_PEDIDO_VENDA PV ,CALCULO_ICMS CICMS , NDO N ,  ".
		  " CFOP CF ,DEPOSITO_EMPRESA DE , PRODUTOS PR ".
		  " WHERE P.CLIENTE = C.CODIGO ".
		  " AND  C.ESTADO_FAT = CICMS.UF_DESTINO".
		  " AND P.STATUS !=  'L' ". 
		  " AND P.STATUS !=  'C' ".
          " AND PV.PEDIDO = P.PEDIDO ".
    	  " AND PV.FILIAL = P.FILIAL ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, PV.CBT, PV.CBTMAE ), PV.CBT )  = CICMS.CBT ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, P.NDO, P.NDO_MAE ), P.NDO )  = CICMS.NDO ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, P.NDO, P.NDO_MAE ), P.NDO )  = N.CODIGO ".
		  " AND PV.CODDEP = DE.CODDEP ".
		  " AND PR.CODPROD = PV.CODPROD ".
		  " AND PV.FILIAL = DE.FILIAL ".
		  " AND CF.CODIGO  =  DECODE(C.ESTADO_FAT,'AL',N.CFOP,N.CFOP_FORA_ESTADO ) ".
          " AND P.PEDIDO IN ( ".
          " SELECT PEDIDO  ".
          " FROM ITENS_PEDIDO_VENDA V, T_USUARIO_EMPRESA_DEPOSITO U  ".
          " WHERE V.EMPRESA = U.COD_EMPRESA ".
          " AND  V.CODDEP  = U.COD_DEPOSITO ".
		  " AND  V.CODDEP IN (" . $_SESSION['cod_sigla'] .")".
          //" AND CODPROD   IN ('599','601','602','603','2059','600','2148','94','95')  ".
          " AND NOM_USUARIO = '" . $_SESSION['login'] ."'".
          " AND   V.FILIAL    = '$filial' AND V.PEDIDO ='$pedido' )  ".
		  " AND  EMP.EMPRESA = '$filial'". 
		  " AND  P.PEDIDO = '$pedido'".
		  " AND PV.PEDIDO='$pedido' ".
          " AND  EMP.EMPRESA = P.EMPRESA";
} else {
    $sql =  " SELECT  P.PEDIDO, DE.CODDEP, DE.SIGLA ,PR.CODPROD,PR.DESCRICAO,( PV.QTD_PEDIDA - (PV.QTD_ATENDIDA + PV.QTD_CANCELADA )) QTD_PEDIDA , PV.SEQUENCIAL ".
	      " FROM PEDIDO_VENDA P, CLIENTES C, EMPRESA EMP ,ITENS_PEDIDO_VENDA PV ,CALCULO_ICMS CICMS , NDO N ".
		  " ,CFOP CF ,DEPOSITO_EMPRESA DE , PRODUTOS PR ".
		  " WHERE P.CLIENTE = C.CODIGO ".
		  " AND  C.ESTADO_FAT = CICMS.UF_DESTINO".
		  " AND P.STATUS !=  'L' ". 
		  " AND P.STATUS !=  'C' ".
          " AND PV.PEDIDO = P.PEDIDO ".
    	  " AND PV.FILIAL = P.FILIAL ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, PV.CBT, PV.CBTMAE ), PV.CBT )  = CICMS.CBT ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, P.NDO, P.NDO_MAE ), P.NDO ) = CICMS.NDO ".
		  " AND DECODE( P.NR_MAE,NULL,DECODE( P.NDO_MAE, NULL, P.NDO, P.NDO_MAE ), P.NDO )  = N.CODIGO ".
		  " AND PV.CODDEP = DE.CODDEP ".
		  " AND PR.CODPROD = PV.CODPROD ".
		  " AND PV.FILIAL = DE.FILIAL ".
		  " AND CF.CODIGO  =  DECODE(C.ESTADO_FAT,'AL',N.CFOP,N.CFOP_FORA_ESTADO ) ".
          " AND P.PEDIDO IN ( ".
          " SELECT PEDIDO  ".
          " FROM ITENS_PEDIDO_VENDA V, T_USUARIO_EMPRESA_DEPOSITO U  ".
          " WHERE V.EMPRESA = U.COD_EMPRESA ".
          " AND  V.CODDEP  = U.COD_DEPOSITO ".
		  " AND  V.CODDEP IN (" . $_SESSION['cod_sigla'] .")".
          //" AND CODPROD   IN ('599','601','602','603','2059','600','2148','94','95')  ".
          " AND NOM_USUARIO = '" . $_SESSION['login'] ."'".
          " AND   V.FILIAL    = '$filial' AND V.PEDIDO ='$pedido')  ".
		  " AND  EMP.EMPRESA = '$filial'". 
		  " AND  P.PEDIDO = '$pedido'".
		  " AND PV.PEDIDO='$pedido' ".
          " AND  EMP.EMPRESA = P.EMPRESA";

}

//echo $sql;


		 
// Analisando a query SQL.
$sql_statement = OCIParse($conn, $sql) or die ("Falha na passagem de cláusula SQL.");



// Executando a query SQL.
OCIExecute($sql_statement) or die ("Não foi possível executar a cláusula SQL.");


?>
<html>
<head>
<link href="../estilo.css" rel="stylesheet" type="text/css">
<script language="JavaScript">

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

function passValor( qtParam ){

  sProduto = "";
  sDeposito = "";
  sQtde = "";
  sSeqProd = "";
  for ( qt=0 ; qt < parseInt(qtParam) ; qt++ ){
  
     if ( document.form.elements["ped_"+qt+"_0"].checked   ) {
	 
        sProduto = sProduto + document.form.elements["ped_"+qt+"_1"].value + "|";
	    sDeposito=sDeposito + document.form.elements["ped_"+qt+"_5"].value + "|";
	    sQtde=sQtde + document.form.elements["ped_"+qt+"_4"].value + "|";
        sSeqProd=sSeqProd + document.form.elements["ped_"+qt+"_6"].value + "|";
		
	 }
	 
  }
  
  
  //alert(sProduto);
  sProduto= sProduto.substr(0, sProduto.length-1);
  sDeposito= sDeposito.substr(0, sDeposito.length-1);
  sQtde= sQtde.substr(0, sQtde.length-1);
  sSeqProd= sSeqProd.substr(0, sSeqProd.length-1);
  
  
  window.opener.parent.main.document.form.elements["txtProduto"].value = sProduto;
  window.opener.parent.main.document.form.elements["txtDeposito"].value = sDeposito;
  window.opener.parent.main.document.form.elements["txtQtde"].value = sQtde;
  window.opener.parent.main.document.form.elements["txtSeqPrd"].value = sSeqProd;
  window.close();
  //alert(window.opener.parent.main.document.form.elements["txtQtde"].value + "-"+ window.opener.parent.main.document.form.elements["txtDeposito"].value+"-"+window.opener.parent.main.document.form.elements["txtProduto"].value )
  
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
<body bgcolor="#FFFFFF">
<form name="form" method="post" action="">  
  <table align="center" width="719">
    <tr> 
      <td   colspan="11" >&nbsp;&nbsp;&nbsp;<font color="#009966" size="2"><b>Filial 
        : </b>COOPERATIVA REG.PROD.ACUCAR E ALC.AL 
        <? //echo $sql;?>
        </font></td>
    </tr>
    <tr> 
      <td colspan="10" align="left" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seleção 
        de Itens do Pedido</td>
    </tr>
    <tr>
      <td width="52" align="left"><b><font color="#009966">Sele&ccedil;&atilde;o</font></b></td> 
      <td width="47" align="left"><b><font color="#009966">C&oacute;digo</font></b></td>
      <td width="60" align="left"><b><font color="#009966">Deposito</font></b></td>
      <td width="396" align="left"><b><font color="#009966">Produto</font></b></td>
      <td width="123" align="left"><b><font color="#009966">Quantidade</font></b></td>
    </tr>
    <tr> 
      <td bgcolor="#009966" colspan="11"></td>
    </tr>
   
  
   
  <?
  $contReg = 0;
  while (  OCIFetch($sql_statement)   ) { 
  ?>
    <tr onMouseOver="liga('tr<?=$contReg?>'); " id="tr<?=$contReg?>" onMouseOut="desliga('tr<?=$contReg?>', '');" class="CURSOR">
      <td>
        <input type="checkbox" name="ped_<?=$contReg ?>_0" value="checkbox">     </td> 
      <td><font color="#009966" size="1"><input type="text" size="5" name="ped_<?=$contReg ?>_1" value="<? echo OCIResult($sql_statement, "CODPROD"); ?>" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'"></font></td>
      <td><font color="#009966" size="1"><input size="10" type="text" name="ped_<?=$contReg ?>_2" value="<? echo OCIResult($sql_statement, "SIGLA"); ?>" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'"></font></td>
      <td><font color="#009966" size="1">
        <input size="65" type="text" name="ped_<?=$contReg ?>_3" value="<? echo OCIResult($sql_statement, "DESCRICAO"); ?>" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'">
      </font></td>
      <td><input type="text" name="ped_<?=$contReg ?>_4" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'"value="<? echo OCIResult($sql_statement, "QTD_PEDIDA"); ?>"></td>
      <input type="hidden" name="ped_<?=$contReg ?>_5"   value="<? echo OCIResult($sql_statement, "CODDEP"); ?>">
      <input type="hidden" name="ped_<?=$contReg ?>_6"   value="<? echo OCIResult($sql_statement, "SEQUENCIAL"); ?>">
    </tr>
    <?
	$contReg++;
	
     
   }
   
    ?>
    <tr> 
      <td bgcolor="#009966" colspan="11"></td>
    </tr>
    <tr> 
      <td align="center" colspan="11"> </td>
    </tr>
    <tr> 
      <td height="20" colspan="17" align="center" valign="top"><input  name="btnAtual" type="button" class="btnAtual" id="btnAtual" onClick="passValor( <?=$contReg?>);" value="Atualizar">    </td>
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
