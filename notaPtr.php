<?
   $selSeq = " SELECT SEQ_NFE.NEXTVAL SEQNF FROM DUAL";
  $sql_statement = OCIParse($conn, $selSeq) or die ("Falha na passagem de cláusula SQL selPedido.");
  OCIExecute($sql_statement) or die ("Não foi possível executar a cláusula SQL selPedido.");
  Ocifetch( $sql_statement );
  $in_var2 = $in_var;
  //$in_var = '097728';
  $seqNf = ociresult($sql_statement,"SEQNF");
 // $seqNf = 248;
  
  //echo "<br>Qtde ".count($codProd);
 //Inserindo Valores na tabela temporario
 for ( $qtPro = 0 ; $qtPro < count($codProd) ; $qtPro++){
 
 //$qtdeProd[$qtPro] = 1;
  //echo "<br>Qtde2 " . $qtPro ."-".$codProd[$qtPro];
  
   $inSert = "INSERT INTO T_TMP_NFE_SAIDA(  SERIE,
  											FILIAL,
  											CODPRODUTO,
  											QTDE,
  											CODDEPOSITO,
  											PEDIDO,SEQNF,NF_REFERENCIADA)
					VALUES ('$serie','$filial','".$codProd[$qtPro]."','".$qtdeProd[$qtPro]."','".$codDepo[$qtPro]."','$in_var',$seqNf,'$out_var')";
    
   $sqlStatProd = OCIParse($conn,$inSert);
   
   
   
   if ( ! OCIExecute( $sqlStatProd ) ) {
      
	  ocirollback($conn);
	  echo "<br> Não foi possível executar a cláusula SQL sqlStatProd.";
	  exit();
	  
   } else {
          OCICommit($conn);	
		  //echo "<br>".$inSert;	
	}						
 
 }
 



	
 $obs  = $obs . '  NF COMPLEMENTO - ' .  $out_var . '  ';
 $sPtr = "S";
 $nFrete = 0;
 $sNomeUsuario ='';
 
 $s = OCIParse($conn, "begin igravanfsaidav3(:pedido, :filial, :NfMae, :pl ,".
                      ":pb,:codTransportadora,:snf,:serie,:obs,:cfop,:seq,:sPtrobras,:sPedido2,".
                      ":sPlacaVeic,:sPlacaReb,:sPlacaReb2,:sPlacaVeicUf,:sPlacaRebUf,:sPlacaRebUf2,:nFretePar,:sNomeUsuario); end;");
 
 


//Param IN
 // $in_var = '097728';
  $pl = 0;
  $pb = 0;

  OCIBindByName($s, ":pedido", $in_var);
  OCIBindByName($s, ":filial", $filial);
  OCIBindByName($s, ":NfMae", $NfMae);
  OCIBindByName($s, ":pl", $pl);
  OCIBindByName($s, ":pb", $pb);
  OCIBindByName($s, ":codTransportadora", $codTransp);
  OCIBindByName($s, ":snf", $out_var, 32);
  OCIBindByName($s, ":serie", $serie);
  OCIBindByName($s,":obs", $obs);
  //OCIBindByName($s, ":data", $datVenc);
  //OCIBindByName($s, ":cbt", $cbt);
  OCIBindByName($s, ":cfop", $Cfop);
  OCIBindByName($s, ":seq", $seqNf);
  OCIBindByName($s, ":sPtrobras", $sPtr);
  OCIBindByName($s, ":sPedido2", $in_var2);
  OCIBindByName($s, ":sPlacaVeic", $veicPlaca);
  OCIBindByName($s, ":sPlacaReb", $rebPlaca);
  OCIBindByName($s, ":sPlacaReb2", $rebSecPlaca);
  OCIBindByName($s, ":sPlacaVeicUf", $veicUf);
  OCIBindByName($s, ":sPlacaRebUf", $rebUf);
  OCIBindByName($s, ":sPlacaRebUf2", $rebSecUf  );
  OCIBindByName($s, ":nFretePar", $nFrete  );
  OCIBindByName($s, ":sNomeUsuario", $sUsuario  );
  //OCIBindByName($s, ":pnError", $erro);
  //OCIBindByName($s, ":ndo", $ndo);
  
  //echo ("<BR>2- $in_var - $filial - $NfMae - $pl - $pb -$qt -$codTransp - $serie- $codProd[0] -$obs - $datVenc-$cbt-$Cfop-$ndo");
  
//Param OUT
 if ( OCIExecute($s, OCI_DEFAULT) ) 
   OCICommit($conn);
 else
   exit();  
  
 //echo "<br> 2- Procedure returned value: " . $out_var;
//







?>
