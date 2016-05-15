<?
   session_start(); 
   set_time_limit(600000);
   
    //Tnsname
    require_once("../tnsNames/cTsnames.php");
	 
    //Classe que Procurar pelo Arquivos
     require_once("../ProcuraArq/cVarreArq.php");
   
   //Retorno o valor do xml
    require("../Nfs/cValorXml.php");
   
   //Include Necessaria para o XML
    require_once("../Nfs/cGerarXmlNfs.php");
	
	//Enviar o XML
     require_once("../xmlSign/cEnviaNF.php");
	 
	//Gravar no Banco
    require_once("../cNFSaidas.php");
	
	//Classe Configuracao
    require_once("../Nfs/cConfigura.php");
 
  //Objetos
   $cVarre = new cVarreArq();
   $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
   $cEnvNF   = new cEnviaNF();
   $tnsName = new cTnsName();
   $cGrava  = new cNFSaidas();
   $cConf    = new cConfigura();
   
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
//-->
</script>
<link href="../../estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>

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
          <td colspan="3" valign="top" bgcolor="#3DAA69">        <div align="center"><span class="style2">Status Geral da Nota no Dia <? echo date("d/m/y");?>
            </span>
            </div>
        <tr>
          <td valign="top">      
          <td valign="top">          
          <td valign="top">        
        
             <? 
			 
		
				
			  $dirResposta = "../xml/xml_resposta/" .$_SESSION['login'] ."/";  
			  
			  //Banco
			  $db = $_SESSION['banco'];
			  $dbBanco = $tnsName->fTnsNames($_SESSION['banco']);
			  $conn = ocilogon($_SESSION['login'], $_SESSION['senha'], $dbBanco );
			  /*****/
			  
		      
			  //Varre o diretorio a procura de nota
			  $vetor=$cVarre->fVarrArq($dirResposta,$filtro="",$nivel="");
			  
			  for ( $qtNota = 0 ; $qtNota < count($vetor) ; $qtNota++ ) {
			  	
				$teste = realpath("../../xml_esquema/RetEnvioVersao10.xml");    
				
				$xmlGerarNf = new cGerarXmlNfs(realpath("../../xml_esquema/RetEnvioVersao10.xml"),0);
				
			    $dirEnv = "../../xml/xml_resposta/" .$_SESSION['login'] ."/";
			    $dirEnv = $dirEnv."/".$vetor[$qtNota];
				
				
				$dirEnv = realpath($dirEnv);
				
				
				if ( file_exists( $dirEnv ) ) {
				
				  		    
			      $valorRecibo = $cValorXml->ValorXmlNameSpace("//nRec",$dirEnv);
 			      $xmlGerarNf->setNoValor("nRec",$valorRecibo,"id","Recibo",FALSE,FALSE,1,0);
				
 			      $xmlArq = "../../xml/xml_recibo_envia/".$_SESSION['login']  ."/".$vetor[$qtNota];	
				  	
				 
				  if ( ! file_exists( $xmlArq ) )
				     $xmlGerarNf->save($xmlArq);
					
				  
				  
				}   
				      
				$dir = "../../xml/xml_resposta_recibo/";
     
    
	
	            $dirTotal = $dir.$_SESSION['login']."/".$vetor[$qtNota];
				
				$numNota = explode("_",$vetor[$qtNota]);
				$numNota = explode(".",$numNota[1]);
				
				
				
	            if ( file_exists( $dirTotal ) ) {
				   $arqXML = $dirTotal;
				}
				else {
				
				     //Configuracao
				     $vetConf = $cConf->fConfiguracao( $_SESSION['empresa'] );  
				
					//Enviando o XML
                	$cabecalho = realpath("../../xml/xml_cabecalho/CabCalho.xml");
                	$link = "https://homologacao.nfe.sefazvirtual.rs.gov.br/ws/nferetrecepcao/NfeRetRecepcao.asmx?wsdl";
					$metodo = "nfeRetRecepcao";
                	$cEnvNF->cEnviaXML($link, $xmlArq, $_SESSION['login'],$numNota[0],$cabecalho,$dir,$metodo,1,$vetConf[0],'','','',$senha,$db);
					$arqXML = $dirTotal;

					//Lendo o XML xml do Recibo
				    $Status = $cValorXml->ValorXmlNameSpaceVetor("//cStat",$arqXML);
				    $valor = $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$arqXML);
					$chave = $cValorXml->ValorXmlNameSpaceVetor("//chNFe",$arqXML);
					$rec = $cValorXml->ValorXmlNameSpaceVetor("//nRec",$arqXML);

					//echo $cGrava->cGraNflista($rec[0],$chave[0],$_SESSION['login'],$Status[1],$Status,$valor[1]);
					$sqlGravNFLista = OCIParse($conn,$cGrava->cGraNflista($rec[0],$chave[0],$_SESSION['login'],$Status[1],$Status[1],$valor[1]) );
 					if ( ! OCIExecute($sqlGravNFLista) ) {
    			       ocirollback($conn);
                   }


				    $dirXmlNf = "../../xml/xml_assinado/".$_SESSION['login']."/".$vetor[$qtNota];

				   if (  ! file_exists($dirXmlNf )  )
				      $dirXmlNf = "../../xml/xml_assinado_contigente/".$_SESSION['login']."/".$vetor[$qtNota];



				   $filial = "001";
				   $lob = OCINewDescriptor($conn, OCI_D_LOB);
				   $stmt = OCIParse($conn,$cGrava->cGraNf('001',$numNota[0],$chave[0],$Status[1],' EMPTY_CLOB()')) ;
     			   OCIBindByName($stmt, ":the_blob", &$lob, -1, OCI_B_CLOB);
                   OCIExecute($stmt, OCI_DEFAULT);
                   $lob->savefile( $dirXmlNf);
				   oci_free_statement($stmt);
                   $lob->free();
                   oci_commit($conn);


				}


				//Lendo o XML xml do Recibo
				 $Status = $cValorXml->ValorXmlNameSpaceVetor("//cStat",$arqXML);
				 $valor = $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$arqXML);

				 $figStatus = "foldersel.gif";

				 $cor ="red";
				 if ( (int) $Status[1] == 100 ){

				   $cor = "blue";
				   $figStatus = "folderopen.gif";
				   $nomNome = explode(".", $vetor[$qtNota] );

				   $val =  $valor[1];
				   if ( ( $valor[1] == NULL ) || ( strlen($valor[1]) < 0  ) )
				    $val =  $valor[0];

				   echo "<tr>";
				   echo '<td valign="top">'."<font color=\"".$cor."\" >  ". $nomNome[0] ." </font></td>";
				   echo  '<td valign="top">'."<font color=\"".$cor."\" >  " . $val. "   </font></td>";
				   echo  '<td valign="top">'."<img src= \"../../Figuras/" . $figStatus . "\"></td>";
				   echo "</tr>";
				}

			  }


			?>
          <td colspan="3" valign="top">          <form name="form1" method="post" action="">
              <div align="center"></div>
          </form>
          </table>
</table>


