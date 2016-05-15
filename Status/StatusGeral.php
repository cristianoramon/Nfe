<?
   session_start();
   set_time_limit(700000000000);

	 //Tnsname
     require_once("../tnsNames/cTsnames.php");

    //Classe que Procurar pelo Arquivos
     require_once("../ProcuraArq/cVarreArq.php");

   //Retorno o valor do xml
    require_once("../Nfs/cValorXml.php");

   //Include Necessaria para o XML
    require_once("../Nfs/cGerarXmlNfs.php");

    //Enviar o XML
    require_once("../xmlSign/cEnviaNF.php");

	//Grava xml
    require_once("cGravaXML.php");

	//Gravar no Banco
    require_once("../cNFSaidas.php");

    //Classe Configuracao
    require_once("../Nfs/cConfigura.php");

  //Objetos
   $cVarre = new cVarreArq();
   $cEnvNF   = new cEnviaNF("../");
   $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
   $cGravaXML = new cGravaXML();
   $tnsName = new cTnsName();
   $cGrava  = new cNFSaidas();
   $cConf    = new cConfigura();

?>

<html><head>
 <meta http-equiv="refresh"  content="60">
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
    <td height="25" colspan="5" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo">
      <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Status da Nota fiscal Eletr&ocirc;nica </div></td>
  </tr>
  <tr>
    <td width="100%" align="right" height="81" valign="top"> <table width="79%"  border="1" align="left" cellpadding="0" cellspacing="0" bordercolor="#99CC99">
      <tr>
          <td width="23%" valign="top">
          <td width="15%" valign="top">
		  <td width="15%" valign="top">
          <td width="63%" valign="top">
          <td width="14%" valign="top">
        <tr>
          <td colspan="5" valign="top" bgcolor="#3DAA69">        <div align="center"><span class="style2">Status Geral da Nota
            </span>
            </div>
        <tr>
          <td valign="top">
          <td valign="top">
          <td valign="top">

              <?




  			$login  =  $_SESSION["login"];
  			$db     =  $_SESSION['banco'];
  			$senha  =  $_SESSION['senha'];
  			$filial =  $_SESSION["empresa"];




			  $dirResposta = "../xml/xml_resposta/" .$login ."/";


			  //Banco
			  $dbBanco = $tnsName->fTnsNames($db);
			  //echo $login .'-'. $senha .'-'. $dbBanco;
			  $conn = ocilogon($login, $senha, $dbBanco );
			  /*****/

			  //echo $cGrava->cselNFe2( $filial );

			  $sqlGravNFLista = OCIParse($conn,$cGrava->cselNFe2( $filial,$login ) ) ;

			  OCIExecute($sqlGravNFLista);

			  //Varre o diretorio a procura de nota
			  $vetor=$cVarre->fVarrArq($dirResposta,$filtro="",$nivel="");

			 // for ( $qtNota = 0 ; $qtNota < count($vetor) ; $qtNota++ ) {

			  while ( OCIFetch($sqlGravNFLista ) ) {

				$teste = realpath("../../xml_esquema/RetEnvioVersao10.xml");


				$filial =  OCIResult($sqlGravNFLista, "FILIAL");
				$nf     =  (int) OCIResult($sqlGravNFLista, "NF");
				$login  =  OCIResult($sqlGravNFLista, "USUARIO");
				           $status  = OCIResult($sqlGravNFLista, "STATUS");
				$msg     = OCIResult($sqlGravNFLista, "MENSAGEM");
				$nf     = $login . "_".$nf.".xml";
				$tipNf  = OCIResult($sqlGravNFLista, "TIPNF");
				$data  = OCIResult($sqlGravNFLista, "DATA");
				$cor = "RED";
				$figStatus = "foldersel.gif";

				if ( ( strtoupper( $status ) == "AC") || strtoupper( $status ) == "AU"  || strtoupper( $status ) == "IA"  || strtoupper( $status ) == "IC" || strtoupper( $status ) == "EP" || strtoupper( $status ) == "EN" ){

				  $cor = "BLUE";
				  $figStatus = "folderopen.gif";
				}

				 echo "<tr>";
				 echo '<td valign="top">'."<font color=\"".$cor."\" >  ". $nf ." </font></td>";
                  echo '<td valign="top">'."<font color=\"".$cor."\" >  ". $data ." </font></td>";
                 echo '<td valign="top">'."<font color=\"".$cor."\" >  ". $tipNf ." </font></td>";
				 echo  '<td valign="top">'."<font color=\"".$cor."\" >  " . $msg. "   </font></td>";
				 echo  '<td valign="top">'."<img src= \"../../Figuras/" . $figStatus . "\"></td>";
				 echo "</tr>";

                 //Lotem em Processamento apagar o arquivo para fazer a consulta novamente
                 /*
				 if ( (int) $Status[0] == 105 ){

                    if ( file_exists( $arqXML ) )
                     unlink( $arqXML);

                 }*/

			  }

              // Desconecta-se do Banco de Dados.
              OCILogoff($conn);
			?>

          <td colspan="3" valign="top">          <form name="form1" method="post" action="">
              <div align="center"></div>
          </form>
          </table>
</table>
</body>
</html>
<? ob_end_flush()  ?>
