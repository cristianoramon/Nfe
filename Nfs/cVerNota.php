<? session_start(); ?>
<?
 require("seguranca.php");
 
  //Ler o select 
 require_once("cQuery.php");
  
 //Gerar o Xml  
 require_once("cGerarXmlNfs.php");
 
 //Chave de acesso
 require_once("nfs/cChavedeAcesso.php");
   
 //Assinatura
 require_once("xmlSign/cAssinaXml.php");
 
 //Verifica Conexao
 require_once("xmlSign/cVerConexao.php");
 
 
 //Enviar o XML
 require_once("xmlSign/cEnviaNF.php");
 
 //Retorno o valor do xml
  require_once("cValorXml.php");
  
 //Classe Social
 require_once("cClasseFiscal.php");  
 
 //Classe Configuracao
 require_once("cConfigura.php");
 
 //Tnsname
 require_once("tnsNames/cTsnames.php");
 
 //Gravar no Banco
 require_once("cNFSaidas.php");
	
	
?>


<?

  class cVerNota {
  
     
	  public function fVerNota($nota,$login,$empresa,$senha,$banco) {
	  
	    $xmlGerarNf = new cGerarXmlNfs(realpath("xml_esquema/RetEnvioVersao10.xml"),0);
		$cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
		$cConf    = new cConfigura();
		$cEnvNF   = new cEnviaNF("");
		$cGrava  = new cNFSaidas();
		$tnsName = new cTnsName();
		 		
		$dirEnv = "xml/xml_resposta/" .$login ."/";
		$dirEnv = $dirEnv."/".$login ."_".$nota.".xml";
				
		
		echo "<br> Dire" . $dirEnv ;		
		$dirEnv = realpath($dirEnv);
				
				
		if ( file_exists( $dirEnv ) ) {
				
				  		    
		 $valorRecibo = $cValorXml->ValorXmlNameSpace("//nRec",$dirEnv);
 		 $xmlGerarNf->setNoValor("nRec",$valorRecibo,"id","Recibo",FALSE,FALSE,1,0);
		
		 echo " <br> Valor do Recibo " . $valorRecibo;		
 		 $xmlArq = "xml/xml_recibo_envia/". $login  ."/".$login ."_".$nota.".xml";	
				  	
				 
		if ( ! file_exists( $xmlArq ) )
			$xmlGerarNf->save($xmlArq);
				
				    
	  }
	  
	  $dir = "xml/xml_resposta_recibo/";
     
    
	  $dirTotal = $dir. $login  ."/".$login ."_".$nota.".xml";	
				
	  //Configuracao
	  $vetConf = $cConf->fConfiguracao( $empresa );  
	  
	  //Enviando o XML
      $cabecalho = realpath("xml/xml_cabecalho/CabCalho.xml");
      $link = "https://homologacao.nfe.sefazvirtual.rs.gov.br/ws/nferetrecepcao/NfeRetRecepcao.asmx?wsdl";
	  $metodo = "nfeRetRecepcao";
      $cEnvNF->cEnviaXML($link, $xmlArq, $login,$nota,$cabecalho,$dir,$metodo,1,$vetConf[0],'');
	  
	  $dir = "xml/xml_resposta_recibo/";
     
    
	  $dirTotal = $dir.$login."/".$login ."_".$nota.".xml";
				
	  $arqXML = $dirTotal;
		
	  echo '<br>padd'.$arqXML;				
	  //Lendo o XML xml do Recibo
	  $Status = $cValorXml->ValorXmlNameSpaceVetor("//cStat",$arqXML);
	  $valor = $cValorXml->ValorXmlNameSpaceVetor("//xMotivo",$arqXML);
	  $chave = $cValorXml->ValorXmlNameSpaceVetor("//chNFe",$arqXML);
	  $rec = $cValorXml->ValorXmlNameSpaceVetor("//nRec",$arqXML);
			
	   //var_dump($Status);			 
	   
	    //Banco
		$db = $tnsName->fTnsNames($banco);
		/*
		echo '<br>Banco'.$db;
		$conn = ocilogon($login, $senha, $db );
			   				
	  $sqlGravNFLista = OCIParse($conn,$cGrava->cGraNflista($rec[0],$chave[0],$login,$Status[1],$Status[1],$valor[1]) );
	  
 	  if ( ! OCIExecute($sqlGravNFLista) ) {
    		ocirollback($conn);	
      }*/
				   
	   if ( ( ( int) $Status[1] == 105  )  ) {

		 if ( file_exists( $arqXML ) )
		   unlink($arqXML);
	  }

	  if ( ( int) $Status[1] == 100 )
	    return 0;
        		    			
	  return $Status[1];
	}  
  }
?>
