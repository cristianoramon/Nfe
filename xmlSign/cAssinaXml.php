<?php
/*********************************************************************
 *  Classe Responsavel pela Assinatura do XML
 *
 *********************************************************************/
 
 require('../libs/xmlseclibs.php');
 
 class cAssinaXML {
    
	/******************************************************************
	 *
	 ******************************************************************/
	 
	 function fAssinXML( $arq , $id , $usuario , $nf,$arqSalvar,$privada,$publica,$filial){
	 
	  
       
	   /*$privatekey = 'xmlSign/KeyPrivadacrpaaa.pem';
       $publiccert = 'xmlSign/KeyPublicacrpaaa.pem';
       */
	   
	   $privatekey = $privada;
       $publiccert = $publica;

       $doc = new DOMDocument();
	   
	   $handle = @fopen($arq, "r+");
	   
       if ($handle) {
	   
        while (!feof($handle)) 
		 $buffer = $buffer  .  ltrim(rtrim(fgets($handle),"\t\n\r\0\x0B"),"\t\n\r\0\x0B");
       
	    fclose($handle);
	   } 
	   
       
     
	 
       $buffer = utf8_encode($buffer);
		
      $doc->preservWhiteSpace = FALSE; //elimina espaços em branco
      $doc->formatOutput = FALSE;
      $doc->loadXML( $buffer,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG );

	  $objDSig = new XMLSecurityDSig();

      $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);

      $element=$doc->getElementsByTagName('infNFe')->item(0);

      //Assinatura
      $elementAssinatura=$doc->getElementsByTagName('NFe')->item(0);

      $options=array('prefix'=>NULL,
                     'prefix_ns' => NULL,
			         'id_name'   =>  'Id',
			         'overwrite' => FALSE,
			         'attValue'  => $id);
			   

      $transforms = array('http://www.w3.org/2000/09/xmldsig#enveloped-signature','http://www.w3.org/TR/2001/REC-xml-c14n-20010315');


      $objDSig->addReference($element, XMLSecurityDSig::SHA1, $transforms,$options);


      $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));
      $objKey->loadKey($privatekey, TRUE);

      $objDSig->sign($objKey);

      $objDSig->add509Cert(file_get_contents($publiccert));

      $objDSig->appendSignature($elementAssinatura);

      $doc->formatOutput = FALSE;
      
//	  $arqRespostaVer2 = $arqSalvar . $usuario;
	  
//	  if ( ! is_dir( $arqRespostaVer2 ) )
  //      mkdir($arqRespostaVer2);
		
//	  $arqRespostaVer = $arqSalvar . $usuario."/".$filial;
	  
//	  if ( ! is_dir( $arqRespostaVer ) )
  //      mkdir($arqRespostaVer);
    
	
	  
	   //echo "<br>".$arqSalvar;
//	  $arqSalvar =$arqSalvar . $usuario ."/".$filial."/". $usuario ."_". $nf . ".xml";
     // echo "<br>".$arqSalvar;
//      $cad = $doc->saveXML();
      
      
//      $order = array("\r\n", "\n", "\r");
  //    $replace = '';
    //  $cad = str_replace($order, $replace, $cad);

	  // $handle = @fopen($arqSalvar, "w+");

    //   if ($handle) {
  //         fwrite($handle,$cad) ;
//	       fclose($handle);
//	   }

      
	  $doc->save($arqSalvar);
      return $arqSalvar;
   }

    public function signXML($filexml, $filexmlDest,$tagid='',$privada,$publica){

    //        if ( $tagid == '' ){
//                $this->errMsg = 'Uma tag deve ser indicada para que seja assinada!!';
  //              $this->errStatus = TRUE;
//                return FALSE;
  //          }
//            if ( $docxml == '' ){
    //            $this->errMsg = 'Um xml deve ser passado para que seja assinado!!';
      //          $this->errStatus = TRUE;
  //              return FALSE;
    //        }

   	       $handle = @fopen($filexml, "r+");
           $docxml = '' ;
           if ($handle) {

             while (!feof($handle))
		          $docxml = $docxml  .  ltrim(rtrim(fgets($handle),"\t\n\r\0\x0B"),"\t\n\r\0\x0B");

	         fclose($handle);
	       } else
               return false ;

            // obter o chave privada para a ssinatura
            $fp = fopen($privada, "r");
            $priv_key = fread($fp, 8192);
            fclose($fp);
            $pkeyid = openssl_get_privatekey($priv_key);
            // limpeza do xml com a retirada dos CR e LF
            $order = array("\r\n", "\n", "\r");
            $replace = '';
            $docxml = str_replace($order, $replace, $docxml);
            // carrega o documento no DOM
            $xmldoc = new DOMDocument('1.0', 'utf-8');
            $xmldoc->preservWhiteSpace = FALSE; //elimina espaços em branco
            $xmldoc->formatOutput = FALSE;
            // muito importante deixar ativadas as opçoes para limpar os espacos em branco
            // e as tags vazias
            $xmldoc->loadXML($docxml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
            $root = $xmldoc->documentElement;
            //extrair a tag com os dados a serem assinados
            $node = $xmldoc->getElementsByTagName($tagid)->item(0);
            if(!$node)
                echo "TAG ID n�o retornada: " . $tagid ;
            $id = trim($node->getAttribute("Id"));
            $idnome = preg_replace('/[^0-9]/','', $id);
            //extrai os dados da tag para uma string
            $dados = $node->C14N(FALSE,FALSE,NULL,NULL);
            //calcular o hash dos dados
            $hashValue = hash('sha1',$dados,TRUE);
            //converte o valor para base64 para serem colocados no xml
            $digValue = base64_encode($hashValue);
            //monta a tag da assinatura digital
            $Signature = $xmldoc->createElementNS($this->URLdsig,'Signature');
            $root->appendChild($Signature);
            $SignedInfo = $xmldoc->createElement('SignedInfo');
            $Signature->appendChild($SignedInfo);
            //Cannocalization
            $newNode = $xmldoc->createElement('CanonicalizationMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLCanonMeth);
            //SignatureMethod
            $newNode = $xmldoc->createElement('SignatureMethod');
            $SignedInfo->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLSigMeth);
            //Reference
            $Reference = $xmldoc->createElement('Reference');
            $SignedInfo->appendChild($Reference);
            $Reference->setAttribute('URI', '#'.$id);
            //Transforms
            $Transforms = $xmldoc->createElement('Transforms');
            $Reference->appendChild($Transforms);
            //Transform
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_1);
            //Transform
            $newNode = $xmldoc->createElement('Transform');
            $Transforms->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLTransfMeth_2);
            //DigestMethod
            $newNode = $xmldoc->createElement('DigestMethod');
            $Reference->appendChild($newNode);
            $newNode->setAttribute('Algorithm', $this->URLDigestMeth);
            //DigestValue
            $newNode = $xmldoc->createElement('DigestValue',$digValue);
            $Reference->appendChild($newNode);
            // extrai os dados a serem assinados para uma string
            $dados = $SignedInfo->C14N(FALSE,FALSE,NULL,NULL);
            //inicializa a variavel que irá receber a assinatura
            $signature = '';
            //executa a assinatura digital usando o resource da chave privada
            $resp = openssl_sign($dados,$signature,$pkeyid);
            //codifica assinatura para o padrao base64
            $signatureValue = base64_encode($signature);
            //SignatureValue
            $newNode = $xmldoc->createElement('SignatureValue',$signatureValue);
            $Signature->appendChild($newNode);
            //KeyInfo
            $KeyInfo = $xmldoc->createElement('KeyInfo');
            $Signature->appendChild($KeyInfo);
            //X509Data
            $X509Data = $xmldoc->createElement('X509Data');
            $KeyInfo->appendChild($X509Data);
            //carrega o certificado sem as tags de inicio e fim
            $cert = $this->__cleanCerts($publica);
            //X509Certificate
            $newNode = $xmldoc->createElement('X509Certificate',$cert);
            $X509Data->appendChild($newNode);
            //grava na string o objeto DOM
            $xmldoc->save($filexmlDest);
            
            echo 'Arquivo: ' . $filexmlDest . '<br>';
            // libera a memoria
            openssl_free_key($pkeyid);
            return true ;
    }

      private function __cleanCerts($certFile){
        //carregar a chave publica do arquivo pem
        $pubKey = file_get_contents($certFile);
        //inicializa variavel
        $data = '';
        //carrega o certificado em um array usando o LF como referencia
        $arCert = explode("\n", $pubKey);
        foreach ($arCert AS $curData) {
            //remove a tag de inicio e fim do certificado
            if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 && strncmp($curData, '-----END CERTIFICATE', 20) != 0 ) {
                //carrega o resultado numa string
                $data .= trim($curData);
            }
        }
        return $data;
    }


   /************************************************************************************
    *  Cancelar 
	***********************************************************************************/
	
	 function fAssinXMLCanc( $arq , $id , $usuario , $nf,$arqSalvar,$privada,$publica,$filial){
	 
 	  /* $privatekey = 'xmlSign/KeyPrivadacrpaaa.pem';
       $publiccert = 'xmlSign/KeyPublicacrpaaa.pem';*/


       $privatekey = $privada;
       $publiccert = $publica;
	   
       $doc = new DOMDocument();
	   
	   $handle = @fopen($arq, "r+");
	   
       if ($handle) {
	   
        while (!feof($handle)) 
		 $buffer = $buffer  .  ltrim(rtrim(fgets($handle),"\t\n\r\0\x0B"),"\t\n\r\0\x0B");
       
	    fclose($handle);
	   } 
	   
       

 
      $doc->loadXML( $buffer );
	  
	  $objDSig = new XMLSecurityDSig();

      $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);

      $element=$doc->getElementsByTagName('infCanc')->item(0);

      //Assinatura
      $elementAssinatura=$doc->getElementsByTagName('cancNFe')->item(0);

      $options=array('prefix'=>NULL,
                     'prefix_ns' => NULL,
			         'id_name'   =>  'Id',
			         'overwrite' => FALSE,
			         'attValue'  => $id);
			   

      $transforms = array('http://www.w3.org/2000/09/xmldsig#enveloped-signature','http://www.w3.org/TR/2001/REC-xml-c14n-20010315');


      $objDSig->addReference($element, XMLSecurityDSig::SHA1, $transforms,$options);


      $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));
      $objKey->loadKey($privatekey, TRUE);

      $objDSig->sign($objKey);

      $objDSig->add509Cert(file_get_contents($publiccert));

      $objDSig->appendSignature($elementAssinatura);

      $doc->formatOutput = FALSE;
      
	  $arqRespostaVer2 = $arqSalvar . $usuario;
	  
	  if ( ! is_dir( $arqRespostaVer2 ) ) 
        mkdir($arqRespostaVer2);
		
		
	  $arqRespostaVer = $arqSalvar . $usuario."/".$filial;
	  
	  if ( ! is_dir( $arqRespostaVer ) ) 
        mkdir($arqRespostaVer);
		
	  $arqSalvar =$arqSalvar . $usuario ."/".$filial."/". $usuario ."_". $nf . ".xml";
      $doc->save($arqSalvar);
      return $arqSalvar;
   }
   
   function fAssinXMLInut( $arq , $id , $usuario , $nf,$arqSalvar,$privada,$publica){

 	  /* $privatekey = 'xmlSign/KeyPrivadacrpaaa.pem';
       $publiccert = 'xmlSign/KeyPublicacrpaaa.pem';*/


       $privatekey = $privada;
       $publiccert = $publica;

       $doc = new DOMDocument();

	   $handle = @fopen($arq, "r+");

       if ($handle) {

        while (!feof($handle))
		 $buffer = $buffer  .  ltrim(rtrim(fgets($handle),"\t\n\r\0\x0B"),"\t\n\r\0\x0B");

	    fclose($handle);
	   }




      $doc->loadXML( $buffer );

	  $objDSig = new XMLSecurityDSig();

      $objDSig->setCanonicalMethod(XMLSecurityDSig::C14N);

      $element=$doc->getElementsByTagName('infInut')->item(0);

      //Assinatura
      $elementAssinatura=$doc->getElementsByTagName('inutNFe')->item(0);

      $options=array('prefix'=>NULL,
                     'prefix_ns' => NULL,
			         'id_name'   =>  'Id',
			         'overwrite' => FALSE,
			         'attValue'  => $id);


      $transforms = array('http://www.w3.org/2000/09/xmldsig#enveloped-signature','http://www.w3.org/TR/2001/REC-xml-c14n-20010315');


      $objDSig->addReference($element, XMLSecurityDSig::SHA1, $transforms,$options);


      $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));
      $objKey->loadKey($privatekey, TRUE);

      $objDSig->sign($objKey);

      $objDSig->add509Cert(file_get_contents($publiccert));

      $objDSig->appendSignature($elementAssinatura);

      $doc->formatOutput = FALSE;

	  $arqSalvar =$arqSalvar . $usuario ."/". $usuario ."_". $nf . ".xml";
      $doc->save($arqSalvar);
      return $arqSalvar;
   }

 }
 
?>
