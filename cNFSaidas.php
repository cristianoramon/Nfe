<?


  /********************************************************************************
   *
   *
   *******************************************************************************/
   
   
   class cNFSaidas {
   
   
   /*******************************************************************************
    *
	*******************************************************************************/
    function cGraNflista($rec,$chave,$usuario,$status,$cod,$mens){

	   $selInsert = "INSERT INTO SAIDAS_NFE_LISTA(RECIBO,CHAVE,
	                                              DATA,USUARIO,
												  STATUS,CODIGO_RETORNO,
												  MENSAGEM)
                    VALUES('$rec','$chave',
					       sysdate,'$usuario',
						   $status,$cod,
						   '$mens')";
      return $selInsert;
	}



    /*******************************************************************************
    *
	*******************************************************************************/
    function cGraNf($filial,$nf,$chave,$status,$mens){

	   $selInsert = "INSERT INTO SAIDAS_NFE(FILIAL,SERIE,
             					  		    NF,CHAVE,
             					  			DATA,STATUS,
             					  			XML )
					VALUES ('$filial','1','$nf','$chave',sysdate,'$status',$mens) returning xml into :the_blob ";
      return $selInsert;
	}

	/***********************************************************************************
	 *
	 ***********************************************************************************/
	   function cAtualizaStatus($status='PE',$chave,$mensagem,$tipoNF='N',$erro=NULL){

	   $selUpdate = " UPDATE SAIDAS_NFE  SNFE
							SET SNFE.STATUS = '$status',
							    SNFE.MENSAGEM = '$mensagem',
								SNFE.TIPO_NOTA ='$tipoNF',
								SNFE.ERRO_DEBUG = '$erro'
							WHERE SNFE.CHAVE ='$chave'";
      return $selUpdate;
	}



	/***********************************************************************************
	 *
	 ***********************************************************************************/
	   function cselNFe( ){

	   $sel = " SELECT SNF.FILIAL, SNF.NF, SNF.USUARIO,
                       SNF.STATUS,SNF.CHAVE,SNF.TIPO_NOTA
				FROM SAIDAS_NFE SNF
				WHERE (  SNF.STATUS='EN' OR SNF.STATUS='EC' OR SNF.STATUS='LP')
				AND to_date(SNF.DATA,'dd/mm/yy') >= to_date(SYSDATE,'dd/mm/yy')
				--AND ROWNUM <= 2
				ORDER BY SNF.NF ";
      return $sel;
	}


	/***********************************************************************************
	 *
	 ***********************************************************************************/
	   function cselNFe2( $filial ){

	   $sel = " SELECT SNF.FILIAL, SNF.NF, SNF.USUARIO,
                       SNF.STATUS, SNF.MENSAGEM,SNF.CHAVE,DECODE(SNF.TIPO_NOTA,'N','NORMAL','CONTIGENCIA') TIPNF
				FROM SAIDAS_NFE SNF
				--WHERE ( SNF.STATUS!='PE' AND SNF.STATUS!='EV' AND SNF.STATUS!='EC' AND SNF.STATUS!='CX')
				WHERE to_date(SNF.DATA,'dd/mm/yy') >= to_date(SYSDATE,'dd/mm/yy')
				AND SNF.FILIAL = '$filial'
				--AND SNF.STATUS != 'CA'
				ORDER BY SNF.NF
				--AND  SNF.NF !='000181' ";
      return $sel;
	}

	/***********************************************************************************
	 *
	 ***********************************************************************************/
	   function cselNFeEnviaNota( ){

	   $sel = " SELECT SNF.FILIAL, SNF.NF, SNF.USUARIO,
                       SNF.CHAVE,SNF.SERIE
				FROM SAIDAS_NFE SNF
				WHERE (  SNF.STATUS='EP' OR SNF.STATUS='CX' OR SNF.STATUS='NE' OR SNF.STATUS='IC' OR SNF.STATUS='RE')
				AND to_date(SNF.DATA,'dd/mm/yy') >= to_date(SYSDATE,'dd/mm/yy')
				--AND ROWNUM <= 2
				ORDER BY SNF.NF ";
      return $sel;
	}

	 /*********************************************************************************
	 *
	 ***********************************************************************************/
	   function cVerStatus( $chave ){

	   $sel = " SELECT  SNF.STATUS, SNF.MENSAGEM, SNF.TIPO_NOTA,NVL(SNF.IMPRESSAO,0) IMP ,ERRO_DEBUG
				FROM SAIDAS_NFE SNF
				WHERE SNF.CHAVE ='$chave'";
      return $sel;
	}

	/***********************************************************************************
	 *
	 ***********************************************************************************/
	   function cAtualizaImpr( $chave ){

	   $selUpdate = " UPDATE SAIDAS_NFE  SNFE
							SET SNFE.IMPRESSAO = NVL(IMPRESSAO,0) + 1
							WHERE SNFE.CHAVE ='$chave'";
      return $selUpdate;
	}


	/***********************************************************************************
	 *
	 ***********************************************************************************/
	   function cAtualizaStatusCanc($status,$chave,$msg,$tipoCanc = 'S' ){

	   $selUpdate = " UPDATE SAIDAS_NFE  SNFE
							SET SNFE.CANCELADA = '$tipoCanc',
							    SNFE.MENSAGEM  ='$msg',
							    SNFE.STATUS    = '$status'
							WHERE SNFE.CHAVE ='$chave'";
      return $selUpdate;
	}

    function cNumBarCont( $chave,$filial ){

	   $sel = " select s.CHAVE_CONTIGENCIA  from saidas_nfe s
                where s.filial ='$filial'
                and   s.chave  ='$chave'";
      return $sel;

   }
	
  }
?>
