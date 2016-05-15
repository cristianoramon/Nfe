<? 
   session_start(); 
    ob_flush();	 
	
	
    //Classe que Procurar pelo Arquivos
     require_once("ProcuraArq/cVarreArq.php");
   
   //Retorno o valor do xml
    require_once("nfs/cValorXml.php");
   
   //Include Necessaria para o XML
    require_once("nfs/cGerarXmlNfs.php");
	
   
   ///nmNota=247411&dirNota=xml/xml_resposta/
   //Var Get
    $dir =  $HTTP_GET_VARS["dirNota"];
    $dirNota = $HTTP_GET_VARS["nmNota"];
	$dirXML = $dir . "/".$HTTP_SESSION_VARS['login'] . "/".$HTTP_SESSION_VARS['login']. "_".(int) $dirNota .".xml";
	$nomNota = $HTTP_SESSION_VARS['login']. "_".(int) $dirNota .".xml";
	
	//echo "sdsd->".$HTTP_GET_VARS["dirnota"]."-- $dirNota --$dir";
  //Objetos
   $cVarre = new cVarreArq();
   //$cEnvNF   = new cEnviaNF(); 
   $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
   
   

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
          <td width="23%" valign="top">          
          <td width="63%" valign="top">          
          <td width="14%" valign="top">        
        <tr>
          <td colspan="3" valign="top" bgcolor="#3DAA69">        <div align="center"><span class="style2">Status do envio da nota no Dia <? echo date("d/m/y");?>
            </span>
            </div>
        <tr>
          <td valign="top">      
          <td valign="top">          
          <td valign="top">        
        
              <? 
			 
		
				
			 	
				//Lendo o XML xml do Recibo
				 $Status = $cValorXml->ValorXmlNameSpaceVetor("//cStat",$dirXML);
				 $valor = $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$dirXML);
				 
				 $figStatus = "foldersel.gif";
				 
				 $cor ="red";
				 if ( (int) $Status[0] == 103 ){
				   $cor = "blue";
				   $figStatus = "folderopen.gif";
				 }
				 
				
				 $nomNome = explode(".", $nomNota );
				 
				 $val =  $valor[1];
				 if ( ( $valor[1] == NULL ) || ( strlen($valor[1]) < 0  ) )
				   $val =  $valor[0];
				 
				  $val = "Nota Gerada"; 
				  $cor = "blue";
				  $figStatus = "folderopen.gif";
				   
				 echo "<tr>";
				 echo '<td valign="top">'."<font color=\"".$cor."\" >  ". $nomNome[0] ." </font></td>";
				 echo  '<td valign="top">'."<font color=\"".$cor."\" >  " . $val. "   </font></td>";  
				 echo  '<td valign="top">'."<img src= \"../../Figuras/" . $figStatus . "\"></td>";    
				 echo "</tr>";
				
				
			  
			  
 
			?>
        
          <td colspan="3" valign="top">          <form name="form1" method="post" action="">
              <div align="center"></div>
          </form> 
          </table>
</table>
</body>
</html>
<? ob_end_flush()  ?>

