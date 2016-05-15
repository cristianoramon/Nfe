<? session_start(); ?>
<?
function varre($dir,$filtro="",$nivel="")
{
    $qt = 0;
    $diraberto = opendir($dir); // Abre o diretorio especificado
    chdir($dir); // Muda o diretorio atual p/ o especificado
    while($arq = readdir($diraberto)) { // Le o conteudo do arquivo
        if($arq == ".." || $arq == ".")continue; // Desconsidera os diretorios
        $arr_ext = explode(";",$filtro);
        foreach($arr_ext as $ext) {
            $extpos = (strtolower(substr($arq,strlen($arq)-strlen($ext)))) == strtolower($ext);
            //echo   "$extpos   --- " . strlen($arq) . " <br>"; date ("F d Y H:i:s.", filemtime($filename));
            if ($extpos == strlen($arq) and is_file($arq)) { // Verifica se o arquivo é igual ao filtro
            // if ( (date("m/d/Y",filemtime($arq)) >= date("m/d/Y",getdate())) &&  (date("m/d/Y",filemtime($arq)) <= date("m/d/Y",getdate()))
			  
			   if ( (date("d/m/Y",filemtime($arq)) == date("d/m/Y"))) {
				$arqNome[$qt] = $nivel.$arq;
				echo $arqNome[$qt];
				$qt = $qt + 1;
				
			   }	
            }
         }
        if (is_dir($arq)) {
            echo $nivel.$arq.' - '.filemtime($arq)."<br>"; // Imprimi em forma de arvore
            varre($arq,$filtro,$nivel."----"); // Executa a funcao novamente se subdiretorio
        }
    }
	
	echo "<dsds>" . count($arqNome);
    chdir(".."); // Volta um diretorio
    closedir($diraberto); // Fecha o diretorio atual
}

?>
<html><head>
<script language="JavaScript" type="text/JavaScript">
<!--



function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

//Imprimir relatorio
function Impr() {
  if (typeof(window.print) != 'undefined'){ 
    window.print(); 
  } 
}
//-->
</script>
<link href="../../estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<table width="82%" height="243" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="100%" height="128" align="center" background="../Figuras/NfSaidaTopo1.jpg" style="background-repeat: no-repeat;"></td>
  </tr>
  <tr> 
    <td height="25" colspan="4" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo"> 
      <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Status da Nota fiscal Eletr&ocirc;nica </div></td>
  </tr>
  <tr> 
    <td width="100%" align="right" height="81" valign="top"> <table width="79%"  border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#99CC99">
      <tr>
          <td width="14%" valign="top">          
          <td width="86%" valign="top">        
        <tr>
          <td colspan="2" valign="top" bgcolor="#3DAA69">        <div align="center"><span class="style2">Status Geral da Nota no Dia <? echo date("d/m/y");?>
            </span>
            </div>
        <tr>
          <td valign="top">ss        
          <td valign="top">        
        <tr>
          <td valign="top">          
          <td valign="top">
            <? 
			  $dir = "../xml/xml_resposta/" .$HTTP_SESSION_VARS['login'] ."/";  
			  varre($dir);
			?>
        <tr>
          <td colspan="2" valign="top">          <form name="form1" method="post" action="">
              <div align="center"></div>
          </form> 
          </table>
</table>

