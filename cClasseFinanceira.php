<?php
  //require_once('ClasseValorNota.php');
 class ClassFinanceira  {


   function Transacao($nTransacao,$emp,$nf) {
     $insertTrans = "Insert Into TRANSACAO( NUMTRANSAC, FILIAL, NUMDOC, " .
                                          " SERDOC,TIPODOC,EMPRESA) ".
                  "Values ( lpad(TO_CHAR($nTransacao),8,0) , '$emp' , '$nf' , 'U', '4', '$emp' )";


    return $insertTrans;
   }

   function ContaProduto ($nCodProd,$Ndo,$emp) {
    $selContaProduto = " select conta,HISTORICO  ".
                       " from TIPO_EMPRESA_OPER  TMO, produtos p, ndo n   ".
                       " where tmo.codtip = p.codtip  ".
                       " and p.codprod = '$nCodProd' ".
                       " and tmo.operacao = n.operacao ".
                       " and tmo.natureza = 'C' ".
                       " and n.codigo='$Ndo'".
					   " and empresa = '$emp' ".
                       " and  ROWNUM = 1";
    return $selContaProduto;
   }

   function Movimento($emp,$nMov,$nTransacao,$nCodDep,$nCodProd,$nPreco,$nQt,$operacao,$EstGeral){
      $insertMov = " Insert Into MOVIMENTO(SEQUENCIAL, NUMTRANSAC, FILIAL, ".
                                     " EMPRESA, CODDEP, CODPROD, " .
                                     " DT_MOV, TIPO_MOV, ESPECIE, ".
                                     " QTD_MOV, QTD_MOV_EST, ESTQ_ATU, ".
                                     " ESTQ_RES, ESTQ_CON_IN, ESTQ_CON_OUT,".
                                     " ULT_CUSTO_MEDIO, ULT_CUSTO_ENTRA,".
                                     " ULT_CUSTO_REPOS, ULT_CUSTO_MEDMF, ".
                                     " ULT_CUSTO_REPMF, QTD_ANT, DT_MOV_DOCNO,".
                                     "  ESTQ_GERAL, OPERACAO_NDO ) ".
             " VALUES( $nMov, lpad(TO_CHAR($nTransacao),8,0), '$emp',   ".
             "'$emp', '$nCodDep', '$nCodProd',  ".
             " to_date(sysdate,'dd-MM-yy'), 'S', '00000009', ".
             " $nQt,$nQt ,$nQt - $EstGeral, "  .
             " 0, 0, 0, ".
             " $nPreco , $nPreco , ".
             " 0 , 0, ".
             " 0, 0,to_date(sysdate,'dd-MM-yy'),".
             " $EstGeral, '$operacao'  )";
      return    $insertMov;
   }

   function cliente($cliente,$emp){
    //Conta corrente do cliente
    $selCliConta = " SELECT CM.CONTA,CM.CNT_AUX,CM.ITEM_CNTAUX   ".
                   " FROM CLIENTE_EMPRESA CM " .
                   " WHERE CM.CLIENTE = '$cliente'  "  .
				   " AND  empresa = '$emp' ".
                   " AND ROWNUM = 1";

     return $selCliConta;
   }

   function InsCliente ( $emp ,$nf, $nGrupo , $origem ,$valor ,$contaCli,$obs,$codHistC ,$contAux,$contItemAux){

       $insetCliLanc = " Insert Into LANCAMENTO ( DDATA, CODLANC,GRUPO_LANC,".
                                            " EMPRESA, LDATA, CONTA, ".
                                            " VALOR_DEB,  ".
                                            " UNIDADE, CODHIST, DOCNO, ".
                                            " COMPLEMENTO, CTIPO, CODORIGEM , ".
                                            " CCUSTO, FILIAL , COD_ARQUIVO, CNT_AUX,ITEM_CNTAUX ) ".
                  " VALUES ( to_date(sysdate,'dd-MM-yy'),TO_CHAR(NUM_KEY_COD_LANC.NEXTVAL),lpad($nGrupo,12,0), ".
                  " '$emp',to_date(sysdate,'dd-MM-yy') ,'$contaCli',  ".
                  " round($valor,2),   ".
                  " '001','$codHistC', '$nf' ,  " .
                  " '$obs', 'C','$origem' ," .
                  " ' ', '$emp', 'FATU' || TO_CHAR(sysdate,'YYYY') || SUBSTR(to_CHAR(sysdate,'MM'),2,1)  || to_CHAR(sysdate,'DD')," .
                  " '$contAux','$contItemAux' )";
    return $insetCliLanc;
   }


  function InsertProduto($nGrupo,$sConta,$valor,$nf,$origem,$emp,$obs,$codHist){
    $insetProdLanc = " Insert Into LANCAMENTO ( DDATA, CODLANC,GRUPO_LANC,".
                                            " EMPRESA, LDATA, CONTA, ".
                                            "  VALOR_CRE, ".
                                            " UNIDADE, CODHIST, DOCNO, ".
                                            " COMPLEMENTO, CTIPO, CODORIGEM , ".
                                            " CCUSTO, FILIAL , COD_ARQUIVO ) ".
                  " VALUES ( to_date(sysdate,'dd-MM-yy'),TO_CHAR(NUM_KEY_COD_LANC.NEXTVAL),lpad($nGrupo,12,0), ".
                  " '$emp',to_date(sysdate,'dd-MM-yy') ,'$sConta',  ".
                  "  round($valor,2),  ".
                  " '001', '$codHist', '$nf' ,  " .
                  " '$obs', 'C','$origem' ," .
                  " ' ', '$emp', 'FATU' || TO_CHAR(sysdate,'YYYY') || SUBSTR(to_CHAR(sysdate,'MM'),2,1)  || to_CHAR(sysdate,'DD') )";

  return $insetProdLanc;
 }


//****************************************************************************/
 function fatura($emp,$cliente,$portador,$grupo,$valor,$nf) {
  //Fatura

  $instFat = " INSERT INTO FATURA (EMPRESA,FILIAL,SERIE ".
                                   " ,FATURA,CLIENTE,EMISSAO,".
                                   " STATUS,PORTADOR,GRUPO_LANC,VALOR)".
             " VALUES('$emp','$emp','U', ".
             " '$nf','$cliente',to_date(sysdate,'dd-MM-yy') , ".
             " 'P','$portador','$grupo',$valor   )";
  return $instFat;
 }

 function impNdo($ndo) {
  //Imposto das Ndos
  $selImp = " SELECT ci.conta_cre ,ci.conta_deb,ci.formula,ci.nome_imp, ".
            " CI.HIST_CRE,CI.HIST_DEB ".
            " FROM NDO N, CONF_IMP CI ".
            " WHERE N.CODIGO='$ndo' ".
            " AND N.COD_IMP = CI.COD_IMP ".
            " ORDER BY N.COD_IMP";
  return  $selImp;
 }

 function impNdoConf($ndo,$operacao) {
    $selImpConf =" SELECT NC.CODHIST  ".
           " FROM NDO_CONF NC  ".
           "WHERE NC.CODIGO = '$ndo'   ".
           " AND NC.OPERACAO = '$operacao'";
  return  $selImpConf;
 }
}
?>


