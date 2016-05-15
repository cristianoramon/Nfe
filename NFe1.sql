--Alteração na Tabela NDO--

alter table NDO add TIPO_NF varchar2(1);
-----------------------------------------

alter table NDO add CFOP_FORA_ESTADO varchar2(4);

----------------------------------------------------------------

--Alteração na Tabela PEDIDO_VENDA--

alter table PEDIDO_VENDA add NF_REFERENCIADA varchar2(9);

-----------------------------------------

alter table PEDIDO_VENDA add NDO_MAE varchar2(6);

-----------------------------------------

alter table PEDIDO_VENDA add NR_MAE varchar2(9);

-----------------------------------------

alter table PEDIDO_VENDA add COD_TABELA number;

-----------------------------------------------------------------

--Alteração na Tabela ITENS_PEDIDO_VENDA--

alter table ITENS_PEDIDO_VENDA add CBTMAE varchar2(4)


-----------------------------------------------------------------

--Alteração na Tabela OPERACAO--


alter table OPERACAO add COD_PIS varchar2(4);

-----------------------------------------

alter table OPERACAO add COD_COFINS varchar2(4);

-----------------------------------------

alter table OPERACAO add COD_IPI varchar2(4);

-----------------------------------------------------------------

--Criar Tabela--

create table T_TMP_NFE_SAIDA
(
  SERIE       VARCHAR2(5),
  FILIAL      VARCHAR2(5),
  CODPRODUTO  VARCHAR2(10),
  QTDE        NUMBER,
  CODDEPOSITO VARCHAR2(5),
  PEDIDO      VARCHAR2(20)
)

-----------------------------------------------------------------

create table T_CST_IPI
(
  COD_IPI VARCHAR2(4) not null,
  DSC_IPI VARCHAR2(100)
)

-----------------------------------------

alter table T_CST_IPI
  add constraint PK_CST_IPI primary key (COD_IPI)
  
-------------------------------------------------------------------

create table T_CST_PIS
(
  COD_PIS NVARCHAR2(8) not null,
  DSC_PIS VARCHAR2(100)
)

-----------------------------------------

alter table T_CST_PIS
  add constraint PK_CST_PIS primary key (COD_PIS)
  
------------------------------------------------------------------

create table T_TIPO_NF
(
  COD_TIPO_NF NUMBER,
  DSC_TIPO    VARCHAR2(50)
)
  
-------------------------------------------------------------------

create table T_CST_COFINS
(
  COD_COFINS VARCHAR2(4) not null,
  DSC_COFINS VARCHAR2(100)
)

-----------------------------------------

alter table T_CST_COFINS
  add constraint PK_CST_COFINS primary key (COD_COFINS)
  
-------------------------------------------------------------------

create table T_FAIXA_PAGAMENTO
(
  COD_TABELA     NUMBER not null,
  DAT_INICIO     DATE not null,
  DAT_FIM        DATE not null,
  DAT_VENCIMENTO DATE
)

-----------------------------------------

alter table T_FAIXA_PAGAMENTO
  add constraint PK_FAIXA_PAGAMENTO primary key (COD_TABELA,DAT_INICIO,DAT_FIM)
  

--------------------------------------------------------------------  

create table T_BENEFICIO_FISCAL
(
  COD_CLIENTE   VARCHAR2(8) not null,
  COD_IMPOSTO   VARCHAR2(10) not null,
  DAT_LIMITE    DATE,
  PCT_BENEFICIO FLOAT
)

-----------------------------------------

alter table T_BENEFICIO_FISCAL
  add constraint PK_BENEFICIO_FISCAL primary key (COD_CLIENTE,COD_IMPOSTO)

-----------------------------------------

alter table T_BENEFICIO_FISCAL
  add constraint FK_BENFIS_IMPOSTO foreign key (COD_IMPOSTO)
  references IMPOSTO (COD_IMPOSTO);  
---------------------------------------------
-- Create table
create table T_LOG_ERRO_NFE
(
  codErro number,
  dscErro varchar2(2000),
  numNota varchar2(9)
)
;
------------------------------------------------------
alter table CLIENTES add NR_SUFRAMA varchar2(9);