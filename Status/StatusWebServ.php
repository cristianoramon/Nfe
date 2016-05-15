<?
   session_start();
   ob_flush();
   set_time_limit(60000);

    //Classe que Procurar pelo Arquivos
     require_once("../ProcuraArq/cVarreArq.php");

   //Retorno o valor do xml
    require_once("../Nfs/cValorXml.php");

   //Include Necessaria para o XML
    require_once("../Nfs/cGerarXmlNfs.php");


	//Verifica Conexao
    require_once(".../xmlSign/cVerConexao.php");

    //Enviar o XML
    require_once("../xmlSign/cEnviaNF.php");

	//Enviar o XML
    require_once("cGravaXML.php");

	//Classe Configuracao
    require_once("../Nfs/cConfigura.php");


  //Objetos
   $cVarre = new cVarreArq();
   $cEnvNF   = new cEnviaNF("../");
   $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
   $cGravaXML = new cGravaXML();
   $verConn  = new cVerConexao();
   $cConf    = new cConfigura();


?>

<html><head>
 <meta http-equiv="refresh"  content="3000">
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
          <td width="50%" valign="top">
          <td width="40%" valign="top">
        <tr>
          <td colspan="3" valign="top" bgcolor="#3DAA69">        <div align="center"><span class="style2">Status Geral da Nota no Dia <? echo date("d/m/y");?>
            </span>
            </div>
        <tr>
          <td valign="top">
          <td valign="top">

              <?


  			$login  =  $_SESSION["login"];
  			$db     =  $_SESSION['banco'];
  			$senha  =  $_SESSION['senha'];
  			$filial =  $_SESSION["empresa"];






				//Verificando nota não enviada ao webServ
				$dirResposta = "../xml/xml_assinado_contigente_tmp/" .$HTTP_SESSION_VARS['login'] ."/";

			    $qtContigencia = 0;

			    //Configuracao
				$vetConf = $cConf->fConfiguracao( $_SESSION['empresa'] );

				//var_dump($vetConf);

				$boolConexao = false;

				  //https://nfe.sefazvirtual.rs.gov.br/ws/nfestatusservico/NfeStatusServico.asmx

                //echo $verConn->fVerificaConexaoSSL("nfe.sefazvirtual.rs.gov.br/ws/nfestatusservico/nfestatusservico.asmx" ,$vetConf[0]) ;
 				if ( $verConn->fVerificaConexaoSSL("nfe.sefazvirtual.rs.gov.br/ws/nfestatusservico/nfestatusservico.asmx",$vetConf[0] ) == 0 )
    				$boolConexao = true;
                //exit();

				//$boolConexao = true;

				if ( $boolConexao ) {

				    $dir = "../xml/xml_status_servico/";

			        $data = date("d_m_Y_H_m_i");

					//echo "<br>--". $boolConexao;

					//Enviando o XML
                	$cabecalho = realpath("../xml/xml_cabecalho/CabCalho1.07.xml");
					$arqStatus = realpath("../xml_esquema/statusServ.xml");


                	$link = "https://nfe.sefazvirtual.rs.gov.br/ws/nfestatusservico/nfestatusservico.asmx?wsdl";
					$metodo = "nfeStatusServicoNF";
                	$cEnvNF->cEnviaXML($link, $arqStatus, $HTTP_SESSION_VARS['login'],$data,$cabecalho,$dir,$metodo,2,$vetConf[0],'','','',$senha,$db,'');

					$arqXML =  "../xml/xml_status_servico/" . $HTTP_SESSION_VARS['login'] ."/". $HTTP_SESSION_VARS['login'] ."_".$data.".xml";

				    //Lendo o XML xml do Recibo
				    $Status = $cValorXml->ValorXmlNameSpaceVetor("//cStat",$arqXML);
				    $valor = $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$arqXML);

				    $figStatus = "foldersel.gif";

				    $cor ="red";

				    if ( (int) $Status[0] == 107 ){

				     $cor = "blue";
				     $figStatus = "folderopen.gif";

					 //Enviado as nfs de contigencia
                     $cabecalho =  realpath("../xml/xml_cabecalho/CabCalho.xml");


                     $dir = "../../xml/xml_resposta/";
                     $link = "https://nfe.sefazvirtual.rs.gov.br/ws/nferecepcao/NfeRecepcao.asmx?wsdl";
                     $metodo = "nfeRecepcaoLote";

					 //Varre o diretorio a procura de nota
					$dirResposta = "../xml/xml_assinado_contigente_tmp/" .$HTTP_SESSION_VARS['login'] ."/";
				    $vetor=$cVarre->fVarrArq($dirResposta,$filtro="",$nivel="");

			           for ( $qtNota = 0 ; $qtNota < count($vetor) ; $qtNota++ ) {

					     $arqXmlAssinado = "../../xml/xml_assinado_contigente_tmp/" . $HTTP_SESSION_VARS['login'] ."/".$vetor[$qtNota];
						 $arqXmlAssinado = realpath($arqXmlAssinado);

						 $numNota = explode(".",$vetor[$qtNota]);
						 $numNota = explode("_",$numNota[0]);
                         //$cEnvNF->cEnviaXML($link, $xmlArq, $_SESSION['login'],$numNota[0],$cabecalho,$dir,$metodo,1,$vetConf[0]);
                         //$cEnvNF->cEnviaXML($link, $arqXmlAssinado, $HTTP_SESSION_VARS['login'] ,$numNota[1],$cabecalho,$dir,$metodo,0,$vetConf[0],'','','',$senha,$db);



						 //$cEnvNF->cEnviaXML($link, $arqXmlAssinado, $login ,(int) $in_nf,$cabecalho,$dir,$metodo,0,$vetConf[0]);
                        // unlink($arqXmlAssinado);
					  }
				 }




				   //$cVarre = new cVarreArq();
				   //$dirResposta = "../xml/xml_assinado_contigente_tmp/" .$HTTP_SESSION_VARS['login'] ."/";
				   //$vetor=$cVarre->fVarrArq($dirResposta,$filtro="",$nivel="");
				  echo "<tr>";
				  echo  '<td valign="top">'."<font color=\"".$cor."\" >  " . $valor[0]. "   </font></td>";
				  echo  '<td valign="top">'."<font color=\"".$cor."\" >  " . count( $vetor). " Nfs  </font></td>";
				  echo  '<td valign="top">'."<img src= \"../../Figuras/" . $figStatus . "\"></td>";
				  echo "</tr>";

			  }



			?>

          <td colspan="3" valign="top">          <form name="form1" method="post" action="">
              <div align="center"></div>
          </form>
        </table>
</table>
</body>
</html>
<? ob_end_flush()  ?>
