SELECT
  s.nf ,  
 lpad(s.nf,9, '0') NFDV,    
   to_char(s.emissao,'yyyy-mm-dd') emissao ,    
   s.peso_liquido,   
   c.cgc CGCC,    
   rtrim(ltrim(c.nome)) NOMEC,   
   c.estado_fat,   
   C.ENDERECO_FAT,   
   C.BAIRRO_FAT ,   
   C.CIDADE_FAT ,   
   f.cgc CGCF,   
   f.nome NOMEF,   
   f.estado,   
   it.quantidade,   
   s.valor,   
   --cf.cod_classe_fiscal,   
   c.inscricao CINSC ,   
   S.PESO_BRUTO,   
   lpad(s.nf || to_char(s.emissao,'dd'),9,'0') CNF , 
   IT.CFOP,   
   IT.PRECO,   
   IT.BASE_ICMS,   
   IT.BASE_IPI,   
   IT.VAL_ICMS,   
   IT.VAL_IPI,   
   IT.cod_cbt,   
   S.VAL_PIS,   
   S.COMP_OBS,   
   S.DSC_OBSCORPONF,   
   S.VAL_COFINS,   
   C.CGC,   
   P.DESCRICAO,   
   PE.COD_ANP,   
   P.NCM_SEFAZ,   
   it.cod_cbt,   
   it.Alq_Icms,   
   it.alq_ipi,   
   it.ALQ_ICMSF,   
   it.BASE_ICMSF,   
   it.VAL_ICMSF,   
   DE.SIGLA ,  
   f.endereco     ENDFAT ,  
   F.ESTADO UFTRANSP ,   
   F.CIDADE CIDTRANSP,    
   ltrim(rtrim(u.simbolo)) simbolo ,    
   i.cod_ibge,     
   OP.COD_PIS,     
   OP.COD_COFINS,     
   OP.COD_IPI ,    
   P.CODPROD ,   
   CB.CBT_NOTA ,   
   N.TIPO_NF,   
   F.INSCRICAO,   
   F.COD,   
   ltrim(rtrim(S.VOLUME_ESP)) ESPECIE,   
   f.codigo CODTRANSP,   
   S.TIPOFRETE TIPOFRETE,   
   S.NDO,   
   S.VOLUME_QTD,   
   C.COD_FAT,   
   PV.VOLUME_ESPE ESPECIE,   
    s.filial,   
    s.serie,    
    CFOP.DESCRICAO DSCFOP,    
    F.CGC CGCTRANSP ,   
    ltrim(rtrim(S.PLACA_PRI)) PLACA_PRI ,   
    ltrim(rtrim(S.PLACA_REB)) PLACA_REB,   
    ltrim(rtrim(S.PLACA_REB2)) PLACA_REB2,   
    S.UF_PRI ,   
    S.UF_REB ,   
    S.UF_REB2 ,   
    IT.PEDIDO,    
    fRetiraCaracteres(P.OBSERVACAO) OBSPRODUTO,  
    NFE.CHAVE,  
    NFE.SERIE,  
    NFE.CHAVE_DV,  
    NFE.CHAVE_SEQNUM,  
    NFE.USUARIO,  
    NFE.IMPRESSAO,  
    NFE.TIPO_NOTA,  
    C.NR_SUFRAMA,   
    decode(PV.VOLUME_ESPE,'',0,1) VERTIPOESPECIE ,   
   VENDEDOR.NOME VENDNOME,  
   P.CODTIP ,  
  ROUND(IT.PRECO*IT.QUANTIDADE,4) TOTALINTENS ,  
 
 ( SELECT CP.VAL_DEFAULT_PARAM FROM CADASTRO_PARAMETRO  CP  WHERE CP.COD_PARAM='CONTG' ) TIPOCONT,
  
   PV.NF_REFERENCIADA NFE_PEDIDO ,    
   NFE.NF_REFERENCIADA NFE_SAIDAS,     
   S.TBASE_ICMS   

FROM saidas s,  
   itens_saidas it,   
   clientes  c,       
   fornec f,    
   prod_empresa pe,    
   --classe_fiscal cf ,   
   produtos P ,   
   DEPOSITO_EMPRESA DE,    
   unimed u ,    
   ibge i ,    
   NDO N ,   
   OPERACAO OP,   
   CBT CB,   
   PEDIDO_VENDA PV,   
   CFOP  CFOP,   
   SAIDAS_NFE NFE,   
   VENDEDOR VENDEDOR
WHERE s.nf = it.nf   
   and   s.serie = it.serie   
   and   s.filial = it.filial   
   and   s.cliente = c.codigo   
   and   s.transporta = f.codigo   
   and   pe.codprod = it.codprod   
  -- and   pe.posicao = cf.cod_classe   
   and pe.empresa = it.empresa   
   and P.CODPROD = it.CODPROD   
   AND DE.CODDEP = it.CODDEP   
   AND   DE.FILIAL = IT.FILIAL   
   AND   u.codmed = IT.cod_unimed   
   AND   N.CODIGO = S.NDO   
   AND   c.cod_municipio_fat = i.cod_municipio(+)   
   AND   N.OPERACAO =  OP.OPERACAO   
   AND   CB.CODCBT =  IT.cod_cbt  
   AND   PV.PEDIDO =  IT.PEDIDO  
   AND   IT.FILIAL =  PV.FILIAL  
   AND   CFOP.CODIGO =  IT.CFOP  
   AND   NFE.FILIAL =  S.FILIAL  
   AND   NFE.SERIE =  S.SERIE  
   AND   NFE.NF =  S.NF  
   AND   PV.VENDEDOR_01 =  VENDEDOR.VENDEDOR(+)  
   AND  s.nf  ='026983'
   AND S.SERIE  ='1'
   AND   s.filial  ='001'
