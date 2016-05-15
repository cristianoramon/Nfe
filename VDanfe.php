<? 
   session_start(); 
 
    //Classe que Procurar pelo Arquivos
     require_once("../ProcuraArq/cVarreArq.php");
   
   //Retorno o valor do xml
    require("../Nfs/cValorXml.php");
   
   //Include Necessaria para o XML
    require_once("../Nfs/cGerarXmlNfs.php");
	
   //Enviar o XML
    require_once("../xmlSign/cEnviaNF.php");
	
   //Classe Configuracao
   require_once("../Nfs/cConfigura.php");
 
  //Objetos
   $cVarre = new cVarreArq();
   $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
   $cEnvNF   = new cEnviaNF();
   
?>

<html><head>
<meta http-equiv="refresh"  content="30">
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

function  janOS(pagina){
 url=pagina;

  window.open(url,"");
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
          <td width="55%" valign="top">          
          <td width="14%" valign="top">  
		   <td width="14%" valign="top">       
        <tr>
          <td colspan="4" valign="top" bgcolor="#3DAA69">        <div align="center"><span class="style2">Status Geral da Nota no Dia <? echo date("d/m/y");?>
            </span>
            </div>
        <tr>
          <td valign="top">      
          <td valign="top">
          <td valign="top">        
        
             <? 
			 
		       $filial = $_GET["E"];
				
			  $dirResposta = "../xml/xml_resposta_recibo/".$_GET["u"] ."/";  
		      
			 
			  //Varre o diretorio a procura de nota
			  $vetor=$cVarre->fVarrArq($dirResposta,$filtro="",$nivel="");
			  
			  
			  for ( $qtNota = 0 ; $qtNota < count($vetor) ; $qtNota++ ) {
			  
			   
				$arqXML = realpath( "../../xml/xml_resposta_recibo/".$_GET['U']."/".$vetor[$qtNota]);
				
				
				//Lendo o XML xml do Recibo
				 $Status  =  $cValorXml->ValorXmlNameSpaceVetor("//cStat",$arqXML);
				 $valor   =  $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$arqXML);
				 $numNota =  $cValorXml->ValorXmlNameSpaceVetor("//chNFe",$arqXML);
				
				 $figStatus = "foldersel.gif";
				 
				 $cor ="red";
				 if ( (int) $Status[1] == 100 ){
				 
				   $cor = "blue";
				   $figStatus = "folderopen.gif";
				   $nomNome = explode(".", $vetor[$qtNota] );
				   
				   $numNotaAcesso = $numNota[0];
			
				   $numNota[0] = (int) substr($numNota[0],25,9);
				   
				   $dirDanfe = "../xml/xml_assinado/". $_GET['U'] . "/".$_GET['U']."_". (int) $numNota[0]  .".xml";
				   
				   $numNotaUsr = $_GET['U'].$numNota[0];
				  
				   $val =  $valor[1];
				   if ( ( $valor[1] == NULL ) || ( strlen($valor[1]) < 0  ) )
				    $val =  $valor[0];
				   
				   echo "<tr>";
				   
				   echo '<td valign="top">'."<font color=\"".$cor."\" >  ". str_pad($numNota[0], 6, "0", STR_PAD_LEFT)  ." </font></td>";
				   echo  '<td valign="top">'."<font color=\"".$cor."\" >  " . $val. "   </font></td>";  
				   echo  '<td valign="top">'."<img src= \"../../Figuras/" . $figStatus . "\"></td>";  
				   echo  '<td valign="top">'."<img src= \"../../Figuras/" . "print.gif" . "\" onClick=\"janOS('RelNFDanf.php?dir=$dirDanfe&chaveAcesso=$numNotaAcesso&filial=$filial');\"></td>";    
				   echo "</tr>";
				}
				
			  }
			  
 
			?>
          <td colspan="3" valign="top">          <form name="form1" method="post" action="">
              <div align="center"></div>
          </form> 
        </table>
</table>


