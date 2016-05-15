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
 <meta http-equiv="refresh">
<body>

              <?



  		 $login  =  $_SESSION["login"];
  		 $db     =  $_SESSION['banco'];
  		 $senha  =  $_SESSION['senha'];
  		 $filial =  $_SESSION["empresa"];




			  $dirResposta = "../xml/xml_resposta/" .$login ."/";



	     	 if ( ! is_dir( $dirResposta ) )
               mkdir($dirResposta);


			  //Banco
			  $dbBanco = $tnsName->fTnsNames($db);
			  //echo $login .'-'. $senha .'-'. $dbBanco;
			  $conn = ocilogon("NEWPIRAM", $senha, $dbBanco ) or die('Erro:Conexao Banco:Erro');
			  $conn2 = ocilogon("NEWPIRAM", $senha, $dbBanco ) or die('Erro:Conexao Banco:Erro');
			  /*****/


			  //Varre o diretorio a procura de nota
			  $vetor=$cVarre->fVarrArq($dirResposta,$filtro="",$nivel="");

			 // for ( $qtNota = 0 ; $qtNota < count($vetor) ; $qtNota++ ) {


              //echo $cGrava->cselNFe( );
			  $sqlGravNFLista = OCIParse($conn,$cGrava->cselNFe( ) ) or die ("Erro:Não foi possível executar a cláusula SQL.Erro");
			  OCIExecute($sqlGravNFLista) or die ("Erro:Não foi possível executar a cláusula SQL.:Erro");


			  //OCIFetch($sqlGravNFLista );
			  //$qt=0;
			 while ( OCIFetch($sqlGravNFLista ) ) {

			    echo'---<br>'. $qt++;
                $teste = realpath("../../xml_esquema/RetEnvioVersao10.xml");


				$filial =  OCIResult($sqlGravNFLista, "FILIAL");
				$nf     =  (int) OCIResult($sqlGravNFLista, "NF");
				$login  =  OCIResult($sqlGravNFLista, "USUARIO");
				$status  = OCIResult($sqlGravNFLista, "STATUS");
				$chaveNfe  = OCIResult($sqlGravNFLista, "CHAVE");
				$tipoNf  = OCIResult($sqlGravNFLista, "TIPO_NOTA");
				$nf     = $login . "_".$nf.".xml";

				echo '<br> Filial '.$filial . '-'.$nf .'-'.$login.'-'.$status;
				$xmlGerarNf = new cGerarXmlNfs(realpath("../../xml_esquema/RetEnvioVersao10.xml"),0);

			    $dirEnv = "../../xml/xml_resposta/" .$login ."/".$filial."/";

				if ( ! is_dir( $dirEnv ) )
                  mkdir($dirEnv);


                //$dirEnv = $dirEnv."/"."RAFAEL_2857.xml";
                $dirEnv = $dirEnv."/". $nf;

				//echo ' <br> '.$dirEnv;

				$dirEnv = realpath($dirEnv);


				if ( file_exists( $dirEnv ) ) {


			      $valorRecibo = $cValorXml->ValorXmlNameSpace("//nRec",$dirEnv);
 			      $xmlGerarNf->setNoValor("nRec",$valorRecibo,"id","Recibo",FALSE,FALSE,1,0);


				  $xmlArq = "../../xml/xml_recibo_envia/".$login ;

			     if ( ! is_dir( $xmlArq ) )
                  mkdir($xmlArq);

				  $xmlArq = "../../xml/xml_recibo_envia/".$login ."/".$filial."/";

			     if ( ! is_dir( $xmlArq ) )
                  mkdir($xmlArq);

 			      $xmlArq = $xmlArq."/".$nf;


				  if ( ! file_exists( $xmlArq ) )
				     $xmlGerarNf->save($xmlArq);



				}

				$dir = "../../xml/xml_resposta_recibo/";

                $dirTotal = $dir.$login."/";
				if ( ! is_dir( $dirTotal ) )
                  mkdir($dirTotal);

				 $dirTotal = $dir.$login."/".$filial;
				if ( ! is_dir( $dirTotal ) )
                  mkdir($dirTotal);

	            $dirTotal = $dir.$login."/".$filial."/".$nf;
	            //$dirTotal = $dir.$_SESSION['login']."/"."RAFAEL_2857.xml";


				//Configuracao
				$vetConf = $cConf->fConfiguracao( $filial );


				$numNota = explode("_",$nf);
				$numNota = explode(".",$numNota[1]);
				//echo "<br>2--" .$dirTotal ;

	            if ( file_exists( $dirTotal ) && ( (strtoupper($status) !="EN") && (strtoupper($status) !="EC") && (strtoupper($status) !="IC") )  ) {
				   $arqXML = $dirTotal;
                   //echo "teste";
				}

				else {

                   //Pegando a mensagem
				   $sqlSelStatus = OCIParse($conn2,$cGrava->cVerStatus( $chaveNfe ) );

 				   if ( ! OCIExecute($sqlSelStatus) )
    			       ocirollback($conn2);

				   OCIFetch($sqlSelStatus);


                    //Enviando o XML
                	$cabecalho = realpath("../../xml/xml_cabecalho/CabCalho.xml");
                	$link = "https://homologacao.nfe.sefazvirtual.rs.gov.br/ws/nferetrecepcao/NfeRetRecepcao.asmx?wsdl";
					$metodo = "nfeRetRecepcao";

                   // $numNota[0] =  "2857";


                	$cEnvNF->cEnviaXML($link, $xmlArq, $login,$numNota[0],$cabecalho,$dir,$metodo,1,$vetConf[0],$chaveNfe,'','',$senha,$db,$filial);

					$arqXML = $dirTotal;

					echo '<br>2---'.$arqXML;

					//Lendo o XML xml do Recibo
				    $Status = $cValorXml->ValorXmlNameSpaceVetor("//cStat",$arqXML);
				    $valor = $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$arqXML);
					$chave = $cValorXml->ValorXmlNameSpaceVetor("//chNFe",$arqXML);
					$rec = $cValorXml->ValorXmlNameSpaceVetor("//nRec",$arqXML);


                    if ( ( $Status[1]==null ) || ( strlen($Status[1])<=0  ) )
                      $Status[1] = $Status[0];

                    $obs = $valor[0].$valor[1];

				    $verRej = strpos($obs,"Rejeicao");

                    $obsExp  = explode(":", $valor[1]);

                    //var_dump( "<br> Nota" . $nf . "Chave" .$chave ." Status " . $Status[0]." - " . $valor[0]);
				    //echo ( " <br> sss " . strlen($Status[1]));



	               $dirXmlNf = "../../xml/xml_assinado/".$login."/".$filial."/".$nf;

				   if (  ! file_exists($dirXmlNf )  ) {

				      $dirXmlNf = "../../xml/xml_assinado_contigente/".$login."/".$filial."/".$nf;
				      $statusCont = "AC";

				   }

				   $tamanhoArray = count($Status);

				   $filial = "001";

				   //$status = 'RE';

                   $statusAnterior = OCIResult($sqlSelStatus,"STATUS");

				   if ( ( int) $Status[1] == 100 )
				     $status = 'AU';
                   else
				    if ( ( int) $Status[1] == 204 )  {
				     //$status = 'AU';
				     $status = 'DP';
                    }
                    else
                     if ( (( int) $Status[1] == 215 ) || (( int) $Status[1] == 225  )  ||  (( int) $Status[1] == 243 ) ||  (( int) $Status[1] == 255 ))
				      $status = 'RE';
                     else
                      if ( ($verRej !== false ) && ( (int) $tamanhoArray == 2 ) )
                        $status = 'RE';
                      else
                       if ( ((strlen($Status[1]) < 2) || ( (int) $tamanhoArray != 2) ) && ( $statusAnterior=="EN") ) {
                           $status = "EN";
                           $smg = "Ok";
                       }
                      //else
                       // $status = 'RE';


				   /*
				    if ( strlen($statusCont) > 0  ) {

					  if ( ( int) $Status[1] == 100 )
				        $status = 'AC';
					  else
					    $status = 'NC';
				   } */


				   if ( strlen($status)<= 0  )
				     $status='PB';



				  //Lotem em Processamento apagar o arquivo para fazer a consulta novamente
                 if ( (int) $Status[0] == 105 ){

					 $status = 'LP';
                    if ( file_exists( $arqXML ) )
                     unlink( $arqXML);

                 }

				   if ( ( $valor[1] == NULL ) || ( strlen($valor[1]) < 2  ) )
				     $valor[1] = $valor[0];


				   $str=explode("-",OCIResult($sqlSelStatus,"MENSAGEM"));

                   //$debug = substr(" Status : $tamanhoArray " . " - " .$smg . " - " . OCIResult($sqlSelStatus,"ERRO_DEBUG"),0,1800);
                   //$debug = " Status : $tamanhoArray " . " - " .$smg . " - " . OCIResult($sqlSelStatus,"ERRO_DEBUG");
                   //$debug =  OCIResult($sqlSelStatus,"ERRO_DEBUG");
				   //$debug = substr($debug,0,1800);

				   $valor[1] = $valor[1] . "-". $str[1];


                   //echo "<br>Status Nfe" . $cGrava->cAtualizaStatus($status,$chaveNfe,$valor[1],$tipoNf );

				   $sqlGravNFLista2 = OCIParse($conn2,$cGrava->cAtualizaStatus($status,$chaveNfe,$valor[1],$tipoNf,$debug ." Rej-> ".$verRej . " ta ".$tamanhoArray . " Status ".$Status[1]  . " obs "  .$obs ." exp " . $obsExp[0] ) );

 					if ( ! OCIExecute($sqlGravNFLista2) ) {
    			       ocirollback($conn2);
                   }


				   oci_commit($conn2);

				}


				//Lendo o XML xml do Recibo
				 $Status = $cValorXml->ValorXmlNameSpaceVetor("//cStat",$arqXML);
				 $valor = $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$arqXML);

				 $figStatus = "foldersel.gif";

				 $cor ="red";
				 if ( (int) $Status[1] == 100 ){
				   $cor = "blue";
				   $figStatus = "folderopen.gif";
				 }


				 $nomNome = explode(".", $nf );

				 $val =  $valor[1];
				 if ( ( $valor[1] == NULL ) || ( strlen($valor[1]) < 0  ) )
				   $val =  $valor[0];







			  }

              // Desconecta-se do Banco de Dados.
              OCILogoff($conn);
			  OCILogoff($conn2);
			?>
</body>
</html>
<? ob_end_flush()  ?>
