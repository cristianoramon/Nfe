<?

 define('FPDF_FONTPATH','font/');
 session_start();

 date_default_timezone_set('America/Maceio');

 require_once('fpdf/fpdf.php');

 //Retorno o valor do xml
 require_once("../Nfs/cValorXml.php");

 //Codigo de Barra
 require_once("../codBarra/cCodBarraNf.php");

 //Digito Verificado
 require_once("../Nfs/cChavedeAcesso.php");

 //Layout
 require_once("PDF.php");

 //Tnsname
 require_once("../tnsNames/cTsnames.php");

 //Gravar no Banco
 require_once("../cNFSaidas.php");

 //Classe Configuracao
 require_once("../Nfs/cConfigura.php");


 //Var Get
  $dir =    $_GET["dir"];
  $chave =  $_GET["chaveAcesso"];
  $filial = $_GET["filial"];
  $tipo    = 	$_GET["tipo"];
  $chaveC  = $_GET["valContigencia"];

 //Objetos
 $cValorXml = new cValorXml(NULL, NULL,NULL,NULL);
 $codBarra = new cCodBarraNf();
 $dig103 = new cChavedeAcesso();
 $tnsName = new cTnsName();
 $cGrava  = new cNFSaidas();
 $cConf    = new cConfigura();

 //$_SESSION['login']='NEWPIRAM';
  //$_SESSION['senha']='';
 //Banco
 $db = $tnsName->fTnsNames($_SESSION['banco']);
 $conn = ocilogon($_SESSION['login'], $_SESSION['senha'], $db );
/*****/


 $dv = $dig103->fDigitoDVModulo103 (103, $dig103->fStrPonderacaoModulo103($chave, 10,1)) ;

// $codBarra->fCodBarra($chaveC,'imgCodBarra/'.$chaveC.'C.png');


 $pdf=new PDF($filial);
 $pdf->SetMargins(10, 20);
 $pdf->Open();
 $pdf->AddPage();


// busca informações de emissao em contigencia
   $sql = "select protocolo,to_char(data_protocolo,'dd/MM/yyyy hh24:mi:ss') DATA,chave_contigencia,contigencia_normal,registro_dpec,dpec_data from saidas_nfe where chave = '$chave'" ;
   $res = OCIParse($conn,$sql) ;
   OCIExecute($res);
   OCIFetch($res) ;
   $protocolo = OCIResult($res,"PROTOCOLO") ;
   $data_protoc = OCIResult($res,"DATA") ;
   $NFeCont = OCIResult($res,"CONTIGENCIA_NORMAL") ;

   if($NFeCont == 'S') { // Danfe em contigencia
      $chave = OCIResult($res,"CHAVE_CONTIGENCIA") ;
      $protocolo = OCIResult($res,"REGISTRO_DPEC") ;
      $data_protoc = OCIResult($res,"DPEC_DATA") ;
   }

// Gera o código de barras
 $codBarra->fCodBarra($chave,'imgCodBarra/'.$chave.'.png');

 $pdf->SetFont('Arial','',8);
 //$pdf->Image('imgCodBarra/'.$chave.'.png',120,45,80,0);
 $pdf->Image('imgCodBarra/'.$chave.'.png',120,39,80,10);

//Chave de acesso
 $pdf->SetXY(115,52);
 $chaveF = substr($chave,0,4) .  ' ' . substr($chave,4,4) . ' ' . substr($chave,8,4);
 $chaveF = $chaveF .' '. substr($chave,12,4) .  ' ' . substr($chave,16,4) . ' ' . substr($chave,20,4);
 $chaveF = $chaveF .' '. substr($chave,24,4) .  ' ' . substr($chave,28,4) . ' ' . substr($chave,32,4);
 $chaveF = $chaveF .' '. substr($chave,36,4) .  ' ' . substr($chave,40,4);
 $pdf->MultiCell(90,4,$chaveF,"C");

 $pdf->SetFont('Arial','',4.5);
 $pdf->SetXY(115,70.5);

 if ($NFeCont == 'S' ) {   // Em contigencia DPEC

    $pdf->Cell(30,1.5,'NÚMERO DE REGISTRO DPEC');

 } else {

    $pdf->Cell(30,1.5,'PROTOCOLO DE AUTORIZAÇÃO DE USO');

 }
    $pdf->SetFont('Arial','',8);

   $pdf->SetXY(115,58);
   $pdf->MultiCell(90,4,"Consulta de autenticidade no portal nacional da NF-e www.nfe.fazenda.gov.br/portal ou no site da Sefaz autorizadora.",0,"C");


   $pdf->SetXY(115,71.7);
   $pdf->MultiCell(90,4,$protocolo . " - " . $data_protoc,0,"C");

 //Tipo Nota
 $x = $cValorXml->ValorXml("//tipoNota/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//tipoNota/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//tipoNota/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);

 $tipNf = 1;

 if ( (int) $valor[0] == 0  )
   $tipNf = 2;

 $pdf->MultiCell(208,4,$tipNf);

 //N pagina
 $pdf->SetXY(90,52);
 $pdf->MultiCell(204,4,'N : 1'  ,0);



 //str_pad($numNota[0], 6, "0", STR_PAD_LEFT)
//Numero Nota
 $pdf->SetFont('Arial','B',12);
 $x = $cValorXml->ValorXml("//numNota/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//numNota/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//numNota/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,str_pad($valor[0], 6, "0", STR_PAD_LEFT) ,0);

 $numNota = $valor[0] ;


 //Serie
 $x = $cValorXml->ValorXml("//serie/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//serie/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//serie/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,'Serie : ' . $valor[0] ,0);

 $serieNota = $valor[0] ;


 $pdf->SetFont('Arial','',8);

 //Numero Nota2
 $x = $cValorXml->ValorXml("//numnfSerie/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//numnfSerie/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//numNota/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,str_pad($valor[0], 6, "0", STR_PAD_LEFT) ,0);

 $x = $cValorXml->ValorXml("//serie2/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//serie2/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//serie2/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,'Serie : ' . $valor[0] ,0);


 //hora
 $pdf->SetXY(170,92.5);
 $pdf->MultiCell(30,4,date("H:i:s"),0,"C");

  //Data Emissao datSaiSant
 $x = $cValorXml->ValorXml("//datSaiSant/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//datSaiSant/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//datSaiSant/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);

 $pdf->SetXY($x,$y);
 if($numNota == '041727' || $numNota == '041728' || $numNota == '041735' || $numNota == '041736' ||
    $numNota == '041740' || $numNota == '041741')
    $pdf->MultiCell(30,4,'07/05/2010',0,"C");
 else
    $pdf->MultiCell(30,4,date("d/m/Y",strtotime($valor[0])),0,"C");

 //Data Emissao
 $x = $cValorXml->ValorXml("//datEmissao/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//datEmissao/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//datEmissao/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(30,4,date("d/m/Y",strtotime($valor[0])),0,"C");


//Operacao
 $x = $cValorXml->ValorXml("//operacao/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//operacao/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//operacao/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,$valor[0],0);

 //Inscricao
 $x = $cValorXml->ValorXml("//inscricaoEstadual/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//inscricaoEstadual/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//inscricaoEstadual/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,substr($valor[0],0,3).".".substr($valor[0],3,3).".".substr($valor[0],6,3),0);
//

//Nr duplicata
 $x = $cValorXml->ValorXml("//vencimento1/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//vencimento1/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//vencimento1/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(20,4,$valor[0],0,'C');

 //Nr2
 $pdf->SetXY($x+67,$y);
 $pdf->MultiCell(20,4,$valor[1],0,'C');

 //Nr3
 $pdf->SetXY($x+135,$y);
 $pdf->MultiCell(20,4,$valor[2],0,'C');

 //Data Vencimento
 $x = $cValorXml->ValorXml("//vencimento2/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//vencimento2/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//vencimento2/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);


 $pdf->SetXY($x,$y);

 if ( strlen($valor[0]) > 3 )
  $pdf->MultiCell(20,4,date('d/m/Y',strtotime($valor[0]) ),0,'C');

 //Nr2
 $pdf->SetXY($x+64,$y);

 if ( strlen($valor[1]) > 3 )
  $pdf->MultiCell(20,4,date('d/m/Y',strtotime($valor[1]) ),0,'C');

 //Nr3
 $pdf->SetXY($x+130,$y);

 if ( strlen($valor[2]) > 3 )
  $pdf->MultiCell(20,4,date('d/m/Y',strtotime($valor[2]) ),0,'C');

 //Valor da Duplicata
 $x = $cValorXml->ValorXml("//vencimento3/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//vencimento3/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//vencimento3/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 //number_format($valor[$qtItem],3,",",".")

 if ( $valor[0] > 0 )
  $pdf->MultiCell(20,4,number_format($valor[0],2,",","."),0,'R');

 //Nr2
 $pdf->SetXY($x+66,$y);

 if ( $valor[1] > 0 )
  $pdf->MultiCell(20,4,number_format($valor[1],2,",","."),0,'R');

 //Nr3
 $pdf->SetXY($x+134,$y);

 if ( $valor[2] > 0 )
   $pdf->MultiCell(20,4,number_format($valor[2],2,",","."),0,'R');



//Cnpj
 $x = $cValorXml->ValorXml("//cnpj/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//cnpj/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//cnpj/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 //$pdf->MultiCell(204,4,$valor[0]);
 $pdf->MultiCell(204,4,substr($valor[0],0,2).".".substr($valor[0],2,3).".".substr($valor[0],5,3)."/".substr($valor[0],8,4)."-".substr($valor[0],12,2));


 $pdf->SetFont('Arial','',8);

 //Destinatario
 $x = $cValorXml->ValorXml("//nomeDest/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//nomeDest/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//nomeDest/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//dest/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,$valor[0]);

 //Dest cnpj
 $x = $cValorXml->ValorXml("//cnpjDest/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//cnpjDest/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//cnpjDest/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//dest/".$campo,$dir);
 $pdf->SetXY($x,$y);

 if ( strlen($valor[0])> 11 )
  $pdf->MultiCell(204,4,substr($valor[0],0,2).".".substr($valor[0],2,3).".".substr($valor[0],5,3)."/".substr($valor[0],8,4)."-".substr($valor[0],12,2));

  //Dest cpf
 $x = $cValorXml->ValorXml("//cpfDest/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//cpfDest/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//cpfDest/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//dest/".$campo,$dir);
 $pdf->SetXY($x,$y);
 //$pdf->MultiCell(204,4,$valor[0].'-');
 $pdf->MultiCell(204,4,substr($valor[0],0,3).".".substr($valor[0],3,3).".".substr($valor[0],6,3)."-".substr($valor[0],9,2));


 //End
 $x = $cValorXml->ValorXml("//endDest/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//endDest/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//endDest/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//enderDest/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,$valor[0]);

 //Bairro
 $x = $cValorXml->ValorXml("//bairroDest/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//bairroDest/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//bairroDest/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//enderDest/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,$valor[0]);

 //Cep
 $x = $cValorXml->ValorXml("//cepdest/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//cepdest/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//cepdest/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,$valor[0]);


 //Municipio
  $x = $cValorXml->ValorXml("//muniDest/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//muniDest/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//muniDest/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//enderDest/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,$valor[0]);

 //Uf
 $x = $cValorXml->ValorXml("//ufDest/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//ufDest/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//ufDest/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//enderDest/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(10,4,$valor[0],0,'C');

 //Ins Dest
 $x = $cValorXml->ValorXml("//insDest/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//insDest/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//insDest/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//dest/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,$valor[0]);
 //$pdf->MultiCell(204,4,substr($valor[0],0,3).".".substr($valor[0],3,3).".".substr($valor[0],6,3));

 //Total BCICMS
 $x = $cValorXml->ValorXml("//bcIcms/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//bcIcms/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//bcIcms/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//ICMSTot/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(37,4,number_format($valor[0],2,",","."),0,'R');

 //Valor Iccms
 $x = $cValorXml->ValorXml("//valIcms/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//valIcms/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//valIcms/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//ICMSTot/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(37,4,number_format($valor[0],2,",","."),0,'R');

 //Base ICMSS
 $x = $cValorXml->ValorXml("//bcIcmss/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//bcIcmss/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//bcIcmss/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//ICMSTot/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(37,4,number_format($valor[0],2,",","."),0,'R');

 //Val ICMSS
 $x = $cValorXml->ValorXml("//valIcmss/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//valIcmss/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//valIcmss/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//ICMSTot/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(37,4,number_format($valor[0],2,",","."),0,'R');



//Frete
 $x = $cValorXml->ValorXml("//frete/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//frete/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//frete/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//ICMSTot/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(29,4,number_format($valor[0],2,",","."),0,'R');

 //Seguro
 $x = $cValorXml->ValorXml("//seguro/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//seguro/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//seguro/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//ICMSTot/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(29,4,number_format($valor[0],2,",","."),0,'R');

 //Desconto
 $x = $cValorXml->ValorXml("//desconto/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//desconto/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//desconto/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//ICMSTot/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(29,4,number_format($valor[0],2,",","."),0,'R');


 //Outras
 $x = $cValorXml->ValorXml("//outras/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//outras/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//outras/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//ICMSTot/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(29,4,number_format($valor[0],2,",","."),0,'R');

 //ValIPI
 $x = $cValorXml->ValorXml("//valIPI/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//valIPI/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//valIPI/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//ICMSTot/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(29,4,number_format($valor[0],2,",","."),0,'R');

 //ValNota
 $x = $cValorXml->ValorXml("//valNota/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//valNota/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//valNota/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//ICMSTot/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(40,4,number_format($valor[0],2,",","."),0,'R');

 //Transportadora
 $x = $cValorXml->ValorXml("//nomTransp/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//nomTransp/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//nomTransp/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//transporta/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,substr($valor[0],0,50));

 //Modo do Frete
 $x = $cValorXml->ValorXml("//modFreteTransp/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//modFreteTransp/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//modFreteTransp/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//transp/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,$valor[0]+1);

  $pdf->SetFont('Arial','',6);

 //Placa Veiculo
 $x = $cValorXml->ValorXml("//placaTransp/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//placaTransp/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//placaTransp/campo","confRelDanfe1.xml");
 $valorP  = $cValorXml->ValorXmlNameSpaceVetor("//veicTransp/".$campo,$dir);

 //Uf Veiculo
 //$x = $cValorXml->ValorXml("//ufTransp/x","confRelDanfe1.xml");
 //$y = $cValorXml->ValorXml("//ufTransp/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//ufTransp/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//veicTransp/".$campo,$dir);

 //Placa Reboque
 $campo =  $cValorXml->ValorXml("//placaTransp/campo","confRelDanfe1.xml");
 $valorR  = $cValorXml->ValorXmlNameSpaceVetor("//reboque/".$campo,$dir);

 //Uf Reboque
 $campo =  $cValorXml->ValorXml("//ufTransp/campo","confRelDanfe1.xml");
 $valorUfR  = $cValorXml->ValorXmlNameSpaceVetor("//reboque/".$campo,$dir);

 //$pdf->SetXY($x,$y);
 //$pdf->MultiCell(10,4,"/".$valor[0],0,"C");


 $pdf->SetXY($x,$y);
 $sPlaca = $valorP[0]."/".$valor[0];

 if ( strlen($valorR[0]) > 4)
 $sPlaca =$sPlaca." ". $valorR[0]."/".$valorUfR[0];

 if ( strlen($valorR[1]) > 4 )
 $sPlaca =$sPlaca ." ". $valorR[1]."/".$valorUfR[1];

 $pdf->MultiCell(204,4,$sPlaca);

 //Uf Veiculo
 /*
 $x = $cValorXml->ValorXml("//ufTransp/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//ufTransp/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//ufTransp/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//veicTransp/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(10,4,"/".$valor[0],0,"C");      */

  $pdf->SetFont('Arial','',8);
 //Cnpj Transporta
 $x = $cValorXml->ValorXml("//cnpjTransp/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//cnpjTransp/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//cnpjTransp/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//transporta/".$campo,$dir);
 $pdf->SetXY($x,$y);

 if ( strlen($valor[0] ) > 11 )
   $pdf->MultiCell(204,4,substr($valor[0],0,2).".".substr($valor[0],2,3).".".substr($valor[0],5,3)."/".substr($valor[0],8,4)."-".substr($valor[0],12,2));

 //cpf  Transporta
 $x = $cValorXml->ValorXml("//cpfTransp/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//cpfTransp/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//cpfTransp/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//transporta/".$campo,$dir);
 $pdf->SetXY($x,$y);
 //$pdf->MultiCell(204,4, $valor[0]);
 $pdf->MultiCell(204,4,substr($valor[0],0,3).".".substr($valor[0],3,3).".".substr($valor[0],6,3)."-".substr($valor[0],9,2));


 //End Transporta
 $x = $cValorXml->ValorXml("//endTransp/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//endTransp/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//endTransp/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//transporta/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,substr($valor[0],0,50));

 //Mun Transp
 $x = $cValorXml->ValorXml("//munTransp/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//munTransp/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//munTransp/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//transporta/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,substr($valor[0],0,50));


 //Uf Transp
 $x = $cValorXml->ValorXml("//ufTransp2/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//ufTransp2/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//ufTransp2/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//transporta/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(10,4,substr($valor[0],0,50),0,"C");

 //Inscricao
 $x = $cValorXml->ValorXml("//insTransp2/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//insTransp2/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//insTransp2/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//transporta/".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,$valor[0]);
 //$pdf->MultiCell(204,4,substr($valor[0],0,3).".".substr($valor[0],3,3).".".substr($valor[0],6,3));

 $pdf->SetFont('Arial','',6);

 /****************** Item da Nota **************************************/
 //Produto Codigo


 $alt = 0;
 $campo =  $cValorXml->ValorXml("//codProduto/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//prod/".$campo,$dir);


 $qtProduto = count($valor);


 $qtGeral = 0;
 $valorProduto = 0;
 $valor = 0;


 for ( $qtItem = 0 ; $qtItem < $qtProduto ; $qtItem++ ) {

   //echo '<br>qt ->'.$qtItem;
   $x = $cValorXml->ValorXml("//codProduto/x","confRelDanfe1.xml");
   $y = $cValorXml->ValorXml("//codProduto/y","confRelDanfe1.xml");
   $campo =  $cValorXml->ValorXml("//codProduto/campo","confRelDanfe1.xml");
   $valor  = $cValorXml->ValorXmlNameSpaceVetor("//prod/".$campo,$dir);
   $pdf->SetXY($x,$y+$alt);
   $pdf->MultiCell(204,4,substr($valor[$qtItem],0,50));

   //Dsc Produto
    $x = $cValorXml->ValorXml("//dscProduto/x","confRelDanfe1.xml");
    $y = $cValorXml->ValorXml("//dscProduto/y","confRelDanfe1.xml");
    $campo =  $cValorXml->ValorXml("//dscProduto/campo","confRelDanfe1.xml");
    $valor  = $cValorXml->ValorXmlNameSpaceVetor("//prod/".$campo,$dir);
    $pdf->SetXY($x,$y+$alt);
    $pdf->MultiCell(47,4,substr($valor[$qtItem],0,50),0,'L');

    //Ncm
    $x = $cValorXml->ValorXml("//ncmProduto/x","confRelDanfe1.xml");
    $y = $cValorXml->ValorXml("//ncmProduto/y","confRelDanfe1.xml");
    $campo =  $cValorXml->ValorXml("//ncmProduto/campo","confRelDanfe1.xml");
    $valor  = $cValorXml->ValorXmlNameSpaceVetor("//prod/".$campo,$dir);
    $pdf->SetXY($x,$y+$alt);
    $pdf->MultiCell(204,4,substr($valor[$qtItem],0,25));

    //Cst
    $x = $cValorXml->ValorXml("//cst/x","confRelDanfe1.xml");
    $y = $cValorXml->ValorXml("//cst/y","confRelDanfe1.xml");
    $campo =  $cValorXml->ValorXml("//cst/campo","confRelDanfe1.xml");
    $valor  = $cValorXml->ValorXmlNameSpaceVetor("//det//imposto//ICMS//".$campo,$dir);
    $oriCST  = $cValorXml->ValorXmlNameSpaceVetor("//det//imposto//ICMS//orig",$dir);
    $pdf->SetXY($x,$y+$alt);
    $pdf->MultiCell(204,4,$oriCST[$qtItem] .substr($valor[$qtItem],0,25));

     //Cfop
     $x = $cValorXml->ValorXml("//cfop/x","confRelDanfe1.xml");
     $y = $cValorXml->ValorXml("//cfop/y","confRelDanfe1.xml");
     $campo =  $cValorXml->ValorXml("//cfop/campo","confRelDanfe1.xml");
     $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
     $pdf->SetXY($x,$y+$alt);
     $pdf->MultiCell(204,4,substr($valor[$qtItem],0,25));

     //Unidade
     $x = $cValorXml->ValorXml("//unidade/x","confRelDanfe1.xml");
     $y = $cValorXml->ValorXml("//unidade/y","confRelDanfe1.xml");
     $campo =  $cValorXml->ValorXml("//unidade/campo","confRelDanfe1.xml");
     $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
     $pdf->SetXY($x,$y+$alt);
     $pdf->MultiCell(204,4,substr($valor[$qtItem],0,25));

     //qt Nota
     $x = $cValorXml->ValorXml("//qtNota/x","confRelDanfe1.xml");
     $y = $cValorXml->ValorXml("//qtNota/y","confRelDanfe1.xml");
     $campo =  $cValorXml->ValorXml("//qtNota/campo","confRelDanfe1.xml");
     $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
     $pdf->SetXY($x,$y+$alt);
     $pdf->MultiCell(16,4,number_format($valor[$qtItem],3,",","."),0,'R');


	  //Quantidade Geral da Nota
	  $qtGeral = $qtGeral +    (float) $valor[$qtItem];

     //Val Unitario
     $x = $cValorXml->ValorXml("//valUnitario/x","confRelDanfe1.xml");
     $y = $cValorXml->ValorXml("//valUnitario/y","confRelDanfe1.xml");
     $campo =  $cValorXml->ValorXml("//valUnitario/campo","confRelDanfe1.xml");
     $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
     $pdf->SetXY($x,$y+$alt);
     $pdf->MultiCell(14,4,number_format($valor[$qtItem],4,",","."),0,'R');

     //Valor Total Nota2
     $x = $cValorXml->ValorXml("//valNota2/x","confRelDanfe1.xml");
     $y = $cValorXml->ValorXml("//valNota2/y","confRelDanfe1.xml");
     $campo =  $cValorXml->ValorXml("//valNota2/campo","confRelDanfe1.xml");
     $valor  = $cValorXml->ValorXmlNameSpaceVetor("//prod/".$campo,$dir);
     $pdf->SetXY($x,$y+$alt);
     $pdf->MultiCell(18,4,number_format($valor[$qtItem],2,",","."),0,'R');

     $valorProduto = $valorProduto + $valor[$qtItem];

     //Valor base icms Nota2
     $x = $cValorXml->ValorXml("//basIcmsNota2/x","confRelDanfe1.xml");
     $y = $cValorXml->ValorXml("//basIcmsNota2/y","confRelDanfe1.xml");
     $campo =  $cValorXml->ValorXml("//basIcmsNota2/campo","confRelDanfe1.xml");
     $valor  = $cValorXml->ValorXmlNameSpaceVetor("//det//imposto//ICMS//".$campo,$dir);
     $pdf->SetXY($x,$y+$alt);
     $pdf->MultiCell(17,4,number_format($valor[$qtItem],2,",","."),0,'R');

     //Val Imcs
     $x = $cValorXml->ValorXml("//valIcmsNota2/x","confRelDanfe1.xml");
     $y = $cValorXml->ValorXml("//valIcmsNota2/y","confRelDanfe1.xml");
     $campo =  $cValorXml->ValorXml("//valIcmsNota2/campo","confRelDanfe1.xml");
     $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
     $pdf->SetXY($x,$y+$alt);
     $pdf->MultiCell(13,4,number_format($valor[$qtItem],2,",","."),0,'R');

     //Val IPI
     $x = $cValorXml->ValorXml("//valIpiNota2/x","confRelDanfe1.xml");
     $y = $cValorXml->ValorXml("//valIpiNota2/y","confRelDanfe1.xml");
     $campo =  $cValorXml->ValorXml("//valIpiNota2/campo","confRelDanfe1.xml");
     $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
     $pdf->SetXY($x,$y+$alt);
     $pdf->MultiCell(13,4,number_format($valor[$qtItem],2,",","."),0,'R');

     //Aliq ICMS
     $x = $cValorXml->ValorXml("//aliqICMSNota2/x","confRelDanfe1.xml");
     $y = $cValorXml->ValorXml("//aliqICMSNota2/y","confRelDanfe1.xml");
     $campo =  $cValorXml->ValorXml("//aliqICMSNota2/campo","confRelDanfe1.xml");
     $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
     $pdf->SetXY($x,$y+$alt);
     $pdf->MultiCell(8,4,number_format($valor[$qtItem],2,",","."),0,'R');

     //Aliq IPI
     $x = $cValorXml->ValorXml("//aliqIPINota2/x","confRelDanfe1.xml");
     $y = $cValorXml->ValorXml("//aliqIPINota2/y","confRelDanfe1.xml");
     $campo =  $cValorXml->ValorXml("//aliqIPINota2/campo","confRelDanfe1.xml");
     $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
     $pdf->SetXY($x,$y+$alt);
     $pdf->MultiCell(8,4,number_format($valor[$qtItem],2,",","."),0,'R');

    $alt = $alt + 5;
 }

 $pdf->SetFont('Arial','',5);
 $x = $cValorXml->ValorXml("//obsComplemento/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//obsComplemento/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//obsComplemento/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y+$alt+15);
 $obs = explode('---',$valor[0]);

 $pdf->MultiCell(100,2,$obs[0],0,'J' );

 $pdf->SetFont('Arial','B',5);

 $pdf->SetXY($x,$y+$alt+38);
 $pdf->MultiCell(100,2,$obs[1],0,'J' );


 $pdf->SetXY($x,$y+$alt+43);
 $pdf->MultiCell(100,2,$obs[2],0,'J' );

 $obsAss ="";

 if ( (  count($obs) > 0 ) && ( $obs[1] <> "") && ( $obs[2] <> "")  ) {

   $obsAss = 'ASSINATURA :____________________________';

   //Assinatura
   $pdf->SetFont('Arial','',6);
   $pdf->SetXY($x+5,$y+$alt+60);
   $pdf->MultiCell(50,5,$obsAss,0,'J' );

 }
 /******** Item da Nota **********/


 $pdf->SetFont('Arial','',8);

 //Peso Liq
 $x = $cValorXml->ValorXml("//pesoL/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//pesoL/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//pesoL/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(30,4,number_format($valor[0],3,",","."),0,"R");

 //Peso Bruto
 $x = $cValorXml->ValorXml("//pesoB/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//pesoB/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//pesoB/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(30,4,number_format($valor[0],3,",","."),0,"R");

 //Marca
 $x = $cValorXml->ValorXml("//marca/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//marca/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//marca/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(204,4,$valor[0]);

 //Especie
 $x = $cValorXml->ValorXml("//especie/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//especie/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//especie/campo","confRelDanfe1.xml");
 $especie  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
 $pdf->SetXY($x,$y);
 $pdf->MultiCell(30,4,$especie[0],0,"C");


 //Qt Transp
 if($especie[0] != 'GRANEL') {
    $x = $cValorXml->ValorXml("//volume/x","confRelDanfe1.xml");
    $y = $cValorXml->ValorXml("//volume/y","confRelDanfe1.xml");
    $campo =  $cValorXml->ValorXml("//volume/campo","confRelDanfe1.xml");
    $valor  = $cValorXml->ValorXmlNameSpaceVetor("//".$campo,$dir);
    $pdf->SetXY($x,$y);
    $pdf->MultiCell(30,4,number_format( $valor[0],0,",","."),0,'R');
 }
 //$pdf->MultiCell(30,4,number_format( $qtGeral,4,",","."),0,'R');
 //$pdf->MultiCell(30,4, $qtGeral,0,'R');

 //Total Produto
 $x = $cValorXml->ValorXml("//totaProduto/x","confRelDanfe1.xml");
 $y = $cValorXml->ValorXml("//totaProduto/y","confRelDanfe1.xml");
 $campo =  $cValorXml->ValorXml("//totaProduto/campo","confRelDanfe1.xml");
 $valor  = $cValorXml->ValorXmlNameSpaceVetor("//ICMSTot/".$campo,$dir);
 $pdf->SetXY($x,$y);
// echo $chave ;
// if($chave == '27110112277646000108550010000513050051305219') {
//    echo $x . `-` . $y . `-` . $valor[0] ;
//     echo 'ok' ;
  //  $pdf->MultiCell(40,4,"teste",0,'R');
// }
// else
    $pdf->MultiCell(40,4,number_format($valor[0],2,",","."),0,'R');


/*
 $status = "IA";
 $msg = "IMPRESSA AUTORIZADA";
 */


 /*

 //Verificando o Tipo de Envio ( Contigencia ou Normal )
 $sqlVerStatus = OCIParse($conn,$cGrava->cVerStatus( $chave ) );
 OCIExecute($sqlVerStatus);

//echo $cGrava->cVerStatus( $chave ) ;

if ( OCIFetch( $sqlVerStatus ) ) {

   if ( strtoupper(OCIResult($sqlVerStatus,"STATUS")) == "IC"  ) {
	     $status = "EC";
	     $mens   = "ENVIADA EM CONTIGENCIA";
	  }

}

 */


 $sqlGravNFLista = OCIParse($conn,$cGrava->cAtualizaImpr($chave ) );

 if ( ! OCIExecute($sqlGravNFLista) ) {
   ocirollback($conn);
 }

 $sqlGravNFLista = OCIParse($conn,$cGrava->cVerStatus($chave ) );

 if ( ! OCIExecute($sqlGravNFLista) ) {
   ocirollback($conn);
 }

 OCIFetch($sqlGravNFLista);

 if ( (int) ( OCIResult($sqlGravNFLista,"IMP") <= 20 ) && ( OCIResult($sqlGravNFLista,"STATUS") !="AU") && ( OCIResult($sqlGravNFLista,"STATUS") !="EN")) {

	  if ( (int ) $tipo == 1 ) {

      	$status = "EP";
	  	$msg    = "PENDENTE DE ENVIO";
	  	$tipoNf = "C";

		$sqlGravNFLista = OCIParse($conn,$cGrava->cAtualizaStatus($status,$chave,$msg . " IMPR ",$tipoNf ) );

 	    if ( ! OCIExecute($sqlGravNFLista) ) {
    		ocirollback($conn);
        }

     }

 }

 oci_commit($conn);
 OCILogoff($conn);

 $pdf->Output("RelDanfe/". $chave.".pdf");

  //$pdf->Output();


?>

<?php

$par = $_GET['NOOPEN'] ;

if ( strlen($par) == 0 || $par != 'SIM') {
echo "<script>";
  $link = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/RelDanfe/". $chave.".pdf";
  echo "location.href=\"$link?nmNota=$out_var&dirNota=$dir\"";
echo "</script>";
}

?>
