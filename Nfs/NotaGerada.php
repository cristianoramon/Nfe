<? session_start(); ?>
<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

// Sempre modificado
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

// HTTP/1.0
header("Pragma: no-cache");
?> 
<html>
<head>

<style>
		td,body{font-family: verdana,tahoma,arial,verdana; font-size: 11px; color: black;}
		.campo{background-color: #E5E5E5; color: #373F56; border-color: white; border-width: 1px; font-family: verdana; font-size: 11px}
		.botao{color: #373F56; background-color: #E5E5E5; height: 18px;	border-width: 1px; font-family: verdana; font-size: 11px}
	</style>
<script language="JavaScript">
<!--



function  janOS(pagina,alt,larg){
 url=pagina;
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

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function atualiza(){
 document.form.submit();
}

/* Formatação para qualquer mascara */

function formatar(src, mask) 
{
  var i = src.value.length;
  var saida = mask.substring(0,1);
  var texto = mask.substring(i)
if (texto.substring(0,1) != saida) 
  {
	src.value += texto.substring(0,1);
  }
}//-->
</script>
<link href="../estilo.css" rel="stylesheet" type="text/css">	
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" align="center" bgcolor="white">
<?php 
   $nota = $HTTP_GET_VARS["nmNota"];
 // echo " <br> ip " . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'])  ;
  $link = "http://".$_SERVER['HTTP_HOST']  .dirname($_SERVER['PHP_SELF'])."/".$nota;
?>
<form action="" name="form" method="post">
      
  <table border="0" width="96%" cellspacing="0" cellpadding="0" height="1">
    <tr>
          <td width="1%" valign="top" height="1"></td>
            <p align="center" class="body"><br>
           </p>
           <td>
          </td>
          <td width="99%" valign="top" height="1">
            <table border="0" width="100%" cellspacing="0" cellpadding="0" height="426">
          <tr> 
            <td width="100%" height="117" align="center" background="../Figuras/NfSaidaTopo1.jpg" style="background-repeat: no-repeat;"></td>
          </tr>
          <tr> 
            <td height="25" colspan="4" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo"> 
              <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Xml da Nota </div></td>
          </tr>
          <tr>
            <td height="81" align="left" valign="top"> <table width="624" border="1" cellpadding="0" cellspacing="0" bordercolor="#66CC99">
                <tr> 
                  <td bordercolor="#99CC99" bgcolor="#CCCCCC"> <div align="center"><strong>Nota 
                      Gerada </strong></div></td>
                </tr>
                <tr> 
                  <td width="624" height="63"> <div align="center"><br>
                      <a href="<? echo "$link"; ?>" target="_blank"><? echo "$link"; ?> </a><font color="#FF3300">Click com o Bot&atilde;o Direito do mouse e salvar destino como </font></div></td>
                </tr>
                <tr>
                  <td height="63">&nbsp;</td>
                </tr>
              </table><tr> 
            <td width="100%" align="center" height="81"> </table>
</table>
</form>  
</form>
</body>
</html>
