<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:pfi="http://www.portalfiscal.inf.br/pfi" xmlns:nfe="http://www.portalfiscal.inf.br/nfe">
	<xsl:output method="html"/>
	<xsl:decimal-format decimal-separator="," grouping-separator="." name="moeda"/>
	
	<xsl:template name="nomUF">
		<xsl:param name="codUF"/>
		<xsl:choose>
			<xsl:when test="$codUF = 'RO'">RONDÔNIA</xsl:when>
			<xsl:when test="$codUF = 'AC'">ACRE</xsl:when>
			<xsl:when test="$codUF = 'AM'">AMAZONAS</xsl:when>
			<xsl:when test="$codUF = 'RR'">RORAIMA</xsl:when>
			<xsl:when test="$codUF = 'PA'">PARÁ</xsl:when>
			<xsl:when test="$codUF = 'AP'">AMAPÁ</xsl:when>
			<xsl:when test="$codUF = 'TO'">TOCANTINS</xsl:when>
			<xsl:when test="$codUF = 'MA'">MARANHÃO</xsl:when>
			<xsl:when test="$codUF = 'PI'">PIAUÍ</xsl:when>
			<xsl:when test="$codUF = 'CE'">CEARÁ</xsl:when>
			<xsl:when test="$codUF = 'RN'">RIO GRANDE DO NORTE</xsl:when>
			<xsl:when test="$codUF = 'PB'">PARAÍBA</xsl:when>
			<xsl:when test="$codUF = 'PE'">PERNAMBUCO</xsl:when>
			<xsl:when test="$codUF = 'AL'">ALAGOAS</xsl:when>
			<xsl:when test="$codUF = 'SE'">SERGIPE</xsl:when>
			<xsl:when test="$codUF = 'BA'">BAHIA</xsl:when>
			<xsl:when test="$codUF = 'MG'">MINAS GERAIS</xsl:when>
			<xsl:when test="$codUF = 'ES'">ESPÍRITO SANTO</xsl:when>
			<xsl:when test="$codUF = 'RJ'">RIO DE JANEIRO</xsl:when>
			<xsl:when test="$codUF = 'SP'">SÃO PAULO</xsl:when>
			<xsl:when test="$codUF = 'PR'">PARANÁ</xsl:when>
			<xsl:when test="$codUF = 'SC'">SANTA CATARINA</xsl:when>
			<xsl:when test="$codUF = 'RS'">RIO GRANDE DO SUL</xsl:when>
			<xsl:when test="$codUF = 'MS'">MATO GROSSO DO SUL</xsl:when>
			<xsl:when test="$codUF = 'MG'">MATO GROSSO</xsl:when>
			<xsl:when test="$codUF = 'GO'">GOIÁS</xsl:when>
			<xsl:when test="$codUF = 'DF'">DISTRITO FEDERAL</xsl:when>
		</xsl:choose>
	</xsl:template>
	
	<xsl:template name="unidades">
		<xsl:param name="uTrib"/>
		<xsl:choose>
			<xsl:when test="$uTrib = 1">Litro</xsl:when>
			<xsl:when test="$uTrib = 2">Duzia</xsl:when>
			<xsl:when test="$uTrib = 3">Caixa</xsl:when>
			<xsl:when test="$uTrib = 4">Caixa com 06 unidades</xsl:when>
			<xsl:when test="$uTrib = 5">Caixa com 12 unidades</xsl:when>
			<xsl:when test="$uTrib = 6">Caixa com 24 unidades</xsl:when>
			<xsl:when test="$uTrib = 7">Pacote</xsl:when>
			<xsl:when test="$uTrib = 8">Pacote com 06 unidades</xsl:when>
			<xsl:when test="$uTrib = 9">Pacote com 12 unidades</xsl:when>
			<xsl:when test="$uTrib = 10">Cilindro</xsl:when>
			<xsl:when test="$uTrib = 11">Bag</xsl:when>
			<xsl:when test="$uTrib = 12">Mix Bag</xsl:when>
			<xsl:when test="$uTrib = 13">Saca com 50Kg</xsl:when>
			<xsl:when test="$uTrib = 14">Fardo com 30Kg</xsl:when>
			<xsl:when test="$uTrib = 15">Fardo com 25Kg</xsl:when>
			<xsl:when test="$uTrib = 16">Tonelada</xsl:when>
			<xsl:when test="$uTrib = 17">Kilograma</xsl:when>
			<xsl:when test="$uTrib = 18">Saca com 60Kg</xsl:when>
			<xsl:when test="$uTrib = 19">Fardo com 10Kg</xsl:when>
			<xsl:when test="$uTrib = 20">Milheiro</xsl:when>
			<xsl:when test="$uTrib = 21">Saca com 25Kg</xsl:when>
			<xsl:when test="$uTrib = 22">Unidade</xsl:when>
			<xsl:when test="$uTrib = 23">Metro Cúbico</xsl:when>
			<xsl:when test="$uTrib = 24">Metro Quadrado</xsl:when>
			<xsl:when test="$uTrib = 25">Metro</xsl:when>
			<xsl:when test="$uTrib = 26">Rolos</xsl:when>
			<xsl:when test="$uTrib = 27">Saca com 42,5 Kg</xsl:when>
		</xsl:choose>
	</xsl:template>

	<xsl:key match="//nfe:dest/nfe:UF" use="." name="ufDest"/>
	
    <xsl:template match="nfe:UF">
        <P>
            <xsl:text> ...</xsl:text>
            <xsl:value-of select="text()"/>
        </P>
    </xsl:template>		

	<xsl:template match="/">
		
		<HTML>
			<HEAD>
				<TITLE>Registro de Passe Fiscal Interestadual</TITLE>
				<META http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
				<script>
					<xsl:comment><![CDATA[
function intercalar(num1, num2) {
	var codes=new Array()
	codes[0]=new Array(1,1,3,3,1)
	codes[1]=new Array(3,1,1,1,3)
	codes[2]=new Array(1,3,1,1,3)
	codes[3]=new Array(3,3,1,1,1)
	codes[4]=new Array(1,1,3,1,3)
	codes[5]=new Array(3,1,3,1,1)
	codes[6]=new Array(1,3,3,1,1)
	codes[7]=new Array(1,1,1,3,3)
	codes[8]=new Array(3,1,1,3,1)
	codes[9]=new Array(1,3,1,3,1)
	
	var newNumber = ""
	for (i = 0; i < 5; i = i + 1) {
		newNumber = newNumber + codes[num1][i] + codes[num2][i]
	}
	return newNumber
}

function gerarCodigoBarrasI25(num) {
	if (num.length != 15) {
			alert("A linha digitável do código de barras é inválida!")
		return false;
    }
	var start = "1111"
	var end = "311"
	var linha = num + "3"
	
	var barCode = start
	var i = 0
	while (i<linha.length) {
		barCode = barCode + intercalar(linha.substr(i,1), linha.substr(i+1,1))
		i = i + 2
	}
	barCode = barCode + end
	return barCode
}

function mostrarCodigoBarrasI25(num) {
	var linha = gerarCodigoBarrasI25(num)
	var i = 0
	var html = "<NOBR>"
	var nomeImagem = ""
	while (i<linha.length) {
		if ((i%2) == 0)
		{
			nomeImagem = "black.gif";
		} 
		else 
		{
			nomeImagem = "white.gif";
		}
		html = html + "<IMG height='40' src='" + nomeImagem + "' width='" + linha.substr(i,1) + "' border='0' />"
		i = i + 1
	}
	html = html + "</NOBR>"
	document.write(html)
}
]]></xsl:comment>
				</script>
				<STYLE type="text/css">
.texto {
	FONT-SIZE: 7pt; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif
}
.textogrande {
	FONT-SIZE: 9pt; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif
}
.imput {
	FONT-SIZE: 7pt; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif
}
.input {
	FONT-SIZE: 7pt; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif
}
.botao {
	BORDER-RIGHT: #000000 1px solid; BORDER-TOP: #338877 1px solid; FONT-WEIGHT: bolder; FONT-SIZE: 7pt; MARGIN: 1px; BORDER-LEFT: #338877 1px solid; WIDTH: 125px; CURSOR: hand; COLOR: #ffffff; BORDER-BOTTOM: #000000 1px solid; FONT-FAMILY: Verdana, Arial; BACKGROUND-COLOR: #338877
}
</STYLE>
			</HEAD>
			<BODY bgColor="#fafafa" leftMargin="0" topMargin="0" marginheight="0" marginwidth="0">
				<BR/>
				<TABLE class="texto" cellSpacing="0" cellPadding="2" width="700" border="1">
					<TBODY>
						<TR vAlign="top">
							<TD class="titulo" colSpan="7">______ Autenticação Mecânica ______ Linha 
      digitável: <xsl:value-of select="//pfi:codBarPFI"/>
							</TD>
						</TR>
						<TR vAlign="top">
							<TD vAlign="top" noWrap="true" align="center" colSpan="7" height="42">
								<script>mostrarCodigoBarrasI25('<xsl:value-of select="//pfi:codBarPFI"/>')</script>
							</TD>
						</TR>
						<TR vAlign="top">
							<TD class="titulo" colSpan="5"><B>GOVERNO DO ESTADO DE <xsl:call-template name="nomUF">
									<xsl:with-param name="codUF" select="//pfi:dadosEmissor/pfi:UF"/>
								</xsl:call-template> - SECRETARIA DA FAZENDA</B><BR/>
								<BR/>SISTEMA DE CONTROLE INTERESTADUAL DE MERCADORIAS EM TRÂNSITO</TD>
							<TD class="titulo" vAlign="middle" align="center" colSpan="2">
								<B>Nº <xsl:value-of select="//pfi:chPFI"/>
								</B>
							</TD>
						</TR>
						<TR>
							<TD class="titulo2" align="center" colSpan="6">
								<BR/>
								<B>
									<FONT size="4">PASSE FISCAL INTERESTADUAL<BR/>PROTOCOLO ICMS 10/03</FONT>
								</B>
								<BR/>
								<BR/>
							</TD>
							<TD class="texto" align="center">
								<BR/>
								<B>
									<FONT size="2">Situação<BR/>Em Trânsito</FONT>
								</B>
								<BR/>
								<BR/>
							</TD>
						</TR>
						<TR>
							<TD vAlign="middle" align="center" colSpan="7">
								<BR/>
								<B>IDENTIFICAÇÃO DO 
      TRANSPORTADOR</B>
								<BR/>
								<BR/>
							</TD>
						</TR>
						<TR vAlign="top">
							<TD colSpan="2">NOME DO TRANSPORTADOR (MOTORISTA)<BR/>
								<B>
									<xsl:value-of select="//pfi:condutor/pfi:xNome"/>
								</B>
							</TD>
							<TD colSpan="6">CPF<BR/>
								<xsl:variable name="cpfCond" select="//pfi:condutor/pfi:CPF"/>
								<xsl:variable name="tamCpfC" select="number(string-length($cpfCond))"/>
								<B>
									<xsl:value-of select="substring($cpfCond,number($tamCpfC - 10),3)"/>.<xsl:value-of select="substring($cpfCond,number($tamCpfC - 7),3)"/>.<xsl:value-of select="substring($cpfCond,number($tamCpfC - 4),3)"/>-<xsl:value-of select="substring($cpfCond,number($tamCpfC - 1),2)"/>
								</B>
								<BR/>
							</TD>
						</TR>
						<TR vAlign="top">
							<TD width="193">PLACA PRINCIPAL/UF<BR/>
								<xsl:value-of select="//pfi:veicTransp/nfe:placa"/> / <xsl:value-of select="//pfi:veicTransp/nfe:UF"/>
							</TD>
							<xsl:for-each select="//pfi:reboque">
								<TD width="193" colSpan="3">PLACA DO REBOQUE/UF<BR/>
									<xsl:value-of select="//pfi:reboque/nfe:placa"/> / <xsl:value-of select="//pfi:reboque/nfe:UF"/>
								</TD>
							</xsl:for-each>
						</TR>
						<TR vAlign="top">
							<TD width="193">CNPJ TRANSPORTADORA<BR/>
								<xsl:variable name="cpfTransp" select="//pfi:transporta/pfi:CPF"/>
								<xsl:variable name="tamCpfT" select="string-length($cpfTransp)"/>
								<xsl:variable name="cnpjTransp" select="//pfi:transporta/pfi:CNPJ"/>
								<xsl:variable name="tamCnpjT" select="string-length($cnpjTransp)"/>
								<xsl:choose>
									<xsl:when test="$tamCnpjT > 0">
										<xsl:value-of select="substring($cnpjTransp,number($tamCnpjT - 14),3)"/>.<xsl:value-of select="substring($cnpjTransp,number($tamCnpjT - 11),3)"/>.<xsl:value-of select="substring($cnpjTransp,number($tamCnpjT - 8),3)"/>/<xsl:value-of select="substring($cnpjTransp,number($tamCnpjT - 5),4)"/>-<xsl:value-of select="substring($cnpjTransp,number($tamCnpjT - 1),2)"/>
									</xsl:when>
									<xsl:when test="$tamCpfT > 0">
										<xsl:value-of select="substring($cpfTransp,number($tamCpfT - 10),3)"/>.<xsl:value-of select="substring($cpfTransp,number($tamCpfT - 7),3)"/>.<xsl:value-of select="substring($cpfTransp,number($tamCpfT - 4),3)"/>-<xsl:value-of select="substring($cpfTransp,number($tamCpfT - 1),2)"/>
									</xsl:when>
								</xsl:choose>
							</TD>
							<TD colSpan="6">RAZÃO SOCIAL DA TRANSPORTADORA<BR/>
								<B>
									<xsl:value-of select="//pfi:transporta/pfi:xNome"/>
								</B>
							</TD>
						</TR>
						<TR>
							<TD vAlign="middle" align="center" colSpan="7">
								<BR/>
								<B>IDENTIFICAÇÃO DO ESTADO EMITENTE</B>
								<BR/>
								<BR/>
							</TD>
						</TR>
						<TR vAlign="top">
							<TD>UF EMITENTE<BR/>
								<xsl:call-template name="nomUF">
									<xsl:with-param name="codUF" select="//pfi:dadosEmissor/pfi:UF"/>
								</xsl:call-template>
								<BR/>
							</TD>
							<TD colSpan="4">REPARTIÇÃO FISCAL EMITENTE<BR/>
								<xsl:value-of select="//pfi:dadosEmissor/pfi:xNome"/>
							</TD>
							<TD>DATA<BR/>
								<xsl:variable name="dEmi" select="//pfi:dEmi"/>
								<xsl:value-of select="substring($dEmi,9,2)"/>/<xsl:value-of select="substring($dEmi,6,2)"/>/<xsl:value-of select="substring($dEmi,1,4)"/>
							</TD>
							<TD>HORA<BR/>
								<xsl:value-of select="//pfi:hEmi"/>
							</TD>
						</TR>
						<TR>
							<TD vAlign="middle" align="center" colSpan="7">
								<BR/>
								<B>DOCUMENTAÇÃO FISCAL E MERCADORIAS</B>
								<BR/>
								<BR/>
							</TD>
						</TR>
						<TR>
							<TD colSpan="7">
								<TABLE class="table02" cellSpacing="1" cellPadding="2" width="100%" border="0">
									<TBODY>
										<TR>
											<TD class="texto">
												<B>N. FISCAL</B>
											</TD>
											<TD class="texto">
												<B>EMITENTE</B>
											</TD>
											<TD class="texto">
												<B>DESTINATÁRIO</B>
											</TD>
										</TR>
										<xsl:for-each select="//pfi:dadosNFs/nfe:infNFe">
											<xsl:sort select="@Id"/>
											<TR vAlign="top">
												<TD class="texto">
													<xsl:value-of select="nfe:ide/nfe:nNF"/>
													<BR/>
												</TD>
												<TD class="texto">
													<xsl:value-of select="nfe:emit/nfe:UF"/>&#x20;&#x20;
													<xsl:variable name="cpfEmit" select="nfe:emit/nfe:CPF"/>
													<xsl:variable name="tamCpfE" select="string-length($cpfEmit)"/>
													<xsl:variable name="cnpjEmit" select="nfe:emit/nfe:CNPJ"/>
													<xsl:variable name="tamCnpjE" select="string-length($cnpjEmit)"/>
													<xsl:choose>
														<xsl:when test="$tamCnpjE > 0">
															<xsl:value-of select="substring($cnpjEmit,number($tamCnpjE - 14),3)"/>.<xsl:value-of select="substring($cnpjEmit,number($tamCnpjE - 11),3)"/>.<xsl:value-of select="substring($cnpjEmit,number($tamCnpjE - 8),3)"/>/<xsl:value-of select="substring($cnpjEmit,number($tamCnpjE - 5),4)"/>-<xsl:value-of select="substring($cnpjEmit,number($tamCnpjE - 1),2)"/>
														</xsl:when>
														<xsl:when test="$tamCpfE > 0">
															<xsl:value-of select="substring($cpfEmit,number($tamCpfE - 10),3)"/>.<xsl:value-of select="substring($cpfEmit,number($tamCpfE - 7),3)"/>.<xsl:value-of select="substring($cpfEmit,number($tamCpfE - 4),3)"/>-<xsl:value-of select="substring($cpfEmit,number($tamCpfE - 1),2)"/>
														</xsl:when>
													</xsl:choose>
													<BR/>
													<xsl:value-of select="nfe:emit/nfe:xNome"/>
												</TD>
												<TD class="texto" colSpan="2">
													<xsl:value-of select="nfe:dest/nfe:UF"/>&#x20;&#x20;
													<xsl:variable name="cpfDest" select="nfe:dest/nfe:CPF"/>
													<xsl:variable name="tamCpfD" select="string-length($cpfDest)"/>
													<xsl:variable name="cnpjDest" select="nfe:dest/nfe:CNPJ"/>
													<xsl:variable name="tamCnpjD" select="string-length($cnpjDest)"/>
													<xsl:choose>
														<xsl:when test="$tamCnpjD > 0">
															<xsl:value-of select="substring($cnpjDest,number($tamCnpjD - 14),3)"/>.<xsl:value-of select="substring($cnpjDest,number($tamCnpjD - 11),3)"/>.<xsl:value-of select="substring($cnpjDest,number($tamCnpjD - 8),3)"/>/<xsl:value-of select="substring($cnpjDest,number($tamCnpjD - 5),4)"/>-<xsl:value-of select="substring($cnpjDest,number($tamCnpjD - 1),2)"/>
														</xsl:when>
														<xsl:when test="$tamCpfD > 0">
															<xsl:value-of select="substring($cpfDest,number($tamCpfD - 10),3)"/>.<xsl:value-of select="substring($cpfDest,number($tamCpfD - 7),3)"/>.<xsl:value-of select="substring($cpfDest,number($tamCpfD - 4),3)"/>-<xsl:value-of select="substring($cpfDest,number($tamCpfD - 1),2)"/>
														</xsl:when>
													</xsl:choose>
													<BR/>
													<xsl:value-of select="nfe:dest/nfe:xNome"/>
												</TD>
											</TR>
											<xsl:for-each select="nfe:det">
												<xsl:sort select="@nItem"/>
												<TR vAlign="top">
													<TD class="texto" align="right">Item <xsl:number/>
													</TD>
													<TD class="texto" align="center">
														<xsl:value-of select="nfe:prod/nfe:xProd"/>
													</TD>
													<TD class="texto" align="left">
														<xsl:value-of select="nfe:prod/nfe:qTrib"/>
											(<xsl:call-template name="unidades">
															<xsl:with-param name="uTrib" select="nfe:prod/nfe:uTrib"/>
														</xsl:call-template>)
											</TD>
													<TD class="texto" align="right">
														<xsl:value-of select="format-number(nfe:prod/nfe:vProd,'#.##0,00','moeda')"/>
													</TD>
												</TR>
											</xsl:for-each>
											<TR vAlign="top">
												<TD class="texto">&#x20;</TD>
												<TD class="texto" align="right" colSpan="2">Peso Total da Carga: <xsl:value-of select="floor(sum(nfe:det/nfe:vol/nfe:pesoB))"/> Kg</TD>
												<TD class="texto" align="right">Valor Total das Mercadorias: <xsl:value-of select="format-number(sum(nfe:det/nfe:prod/nfe:vProd),'#.##0,00','moeda')"/>
												</TD>
											</TR>
										</xsl:for-each>
										<TR vAlign="top">
											<TD class="texto">&#x20;</TD>
											<TD class="texto" align="right" colSpan="3">
												<B>Valor Total do Passe: <xsl:value-of select="format-number(sum(//nfe:vProd),'#.##0,00','moeda')"/>
												</B>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
							</TD>
						</TR>
						<TR>
							<TD align="left" colSpan="7">OBSERVAÇÕES: <BR/>
								<BR/>
							</TD>
						</TR>
						<TR>
							<TD colSpan="7">
								<BR/>
								<BR/>
								<BR/>
								<BR/>
								<BR/>
								<BR/>
							</TD>
						</TR>
						<TR>
							<TD colSpan="7">
								<TABLE class="table02" cellSpacing="0" cellPadding="2" width="100%" border="0">
									<TBODY>
										<TR>
											<TD class="texto" align="center" width="100%" colSpan="7">
												<B>
													<FONT face="Verdana, Arial, Helvetica, sans-serif">TERMO DE 
            DEPÓSITO</FONT>
												</B>
											</TD>
										</TR>
										<TR>
											<TD class="texto" colSpan="3">
												<xsl:text>
			Com a lavratura do presente Termo de 
            Depósito, o transportador e os responsáveis solidários qualificados 
            neste Passe Fiscal Interestadual são nomeados fiéis depositários das 
            mercadorias relacionadas neste documento, ficando os mesmos 
            responsáveis pela guarda das mercadorias perante todas as 
            Secretarias de Fazenda das Unidades Federadas do trajeto e entrega 
            das mesmas aos contribuintes das Unidades Federadas de destino 
            especificadas nas documentações fiscais, bem como pela solicitação 
            da baixa desse termo, no primeiro posto de entrada da Unidade 
            Federada de destino final das mercadorias.</xsl:text>
												<BR/>
												<BR/>
												<xsl:text>Caso não seja 
            comprovada a entrada das mercadorias na Unidade Federada do destino 
            final, após o prazo de 30 dias, a Unidade Federada poderá efetuar o 
            lançamento de ofício, nos termos da Cláusula Sexta do Protocolo ICMS 
            10/2003, ficando os fiéis depositários, qualificados neste 
            documento, responsáveis pelo pagamento do imposto e da multa, 
            conforme a legislação da respectiva Unidade Federada. 
			</xsl:text>
											</TD>
										</TR>
										<TR>
											<TD class="texto" align="center" width="31%">
												<BR/>
												<BR/>
												<xsl:value-of select="substring($dEmi,9,2)"/>/<xsl:value-of select="substring($dEmi,6,2)"/>/<xsl:value-of select="substring($dEmi,1,4)"/>
												<BR/>___________________________<BR/>Data 
          </TD>
											<TD class="texto" align="center" width="39%">
												<BR/>
												<BR/>
												<xsl:value-of select="//pfi:condutor/pfi:xNome"/>
												<BR/>___________________________________________
												<BR/>Nome do Depositário por Extenso (Transportador)</TD>
											<TD class="texto" align="center" width="30%">
												<BR/>
												<BR/>___________________________<BR/>Assinatura</TD>
										</TR>
									</TBODY>
								</TABLE>
							</TD>
						</TR>
						<TR align="center">
							<TD colSpan="7">
								<BR/>
								<TABLE class="texto" cellSpacing="0" cellPadding="2" width="95%" border="1">
									<TBODY>
										<TR>
											<TD align="center" colSpan="7">IDENTIFICAÇÃO DO RESPONSÁVEL PELA EMISSÃO 
            <BR/>
												<BR/>
											</TD>
										</TR>
										<TR>
											<TD vAlign="top">
												<B>NOME DO SERVIDOR</B>
												<BR/>
												<BR/>
												<xsl:value-of select="//pfi:xResp"/>
												<BR/>
											</TD>
											<TD vAlign="top">
												<B>MATRÍCULA</B>
												<BR/>
												<BR/>
												<xsl:value-of select="//pfi:nMatResp"/>
												<BR/>
											</TD>
										</TR>
										<TR>
											<TD colSpan="2">
												<B>ASSINATURA</B>
												<BR/>
												<BR/>
												<BR/>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
								<P style="PAGE-BREAK-AFTER: always"/>
								<TABLE class="texto" cellSpacing="1" cellPadding="3" width="710" border="0">
									<TBODY>
										<TR>
											<TD vAlign="top" bgColor="#cccccc" colSpan="6">
												<B>REGISTROS DE PASSAGEM NAS UNIDADES FEDERADAS DO PERCURSO</B>
											</TD>
										</TR>
										<TR vAlign="top">
											<TD class="titulo" colSpan="5">GOVERNO DO ESTADO DE <xsl:call-template name="nomUF">
												<xsl:with-param name="codUF" select="//pfi:dadosEmissor/pfi:UF"/>
											</xsl:call-template> - SECRETARIA DA FAZENDA<BR/>
												<BR/>SISTEMA DE CONTROLE INTERESTADUAL DE MERCADORIAS EM TRÂNSITO</TD>
											<TD class="titulo" vAlign="middle" align="center" colSpan="2">
												<B>Nº <xsl:value-of select="//pfi:chPFI"/>
												</B>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
								<BR/>
								<TABLE class="texto" height="100" cellSpacing="0" cellPadding="0" width="710" border="1">
									<TBODY>
										<TR>
											<TD class="texto" vAlign="top" width="84">
												<P>UF<BR/>
													<BR/>&#x20;&#x20;&#x20;<B/>
												</P>
											</TD>
											<TD class="texto" vAlign="top" width="159">
												<P>DATA<BR/>&#x20;</P>
											</TD>
											<TD class="texto" vAlign="top" colSpan="2">
												<P>HORA<BR/>&#x20;</P>
											</TD>
											<TD class="texto" vAlign="top" width="157">
												<P>REPARTIÇÃO FISCAL (PF)<BR/>&#x20;</P>
											</TD>
											<TD class="texto" vAlign="top" width="191" colSpan="2">
												<P>AUTENTICAÇÃO<BR/>&#x20;</P>
											</TD>
										</TR>
										<TR>
											<TD class="texto" colSpan="4">
												<P>MATRÍCULA DO SERVIDOR:<BR/>
													<BR/>&#x20;<BR/>
												</P>
											</TD>
											<TD class="texto" vAlign="top" colSpan="3" rowSpan="3">
												<P>ASSINATURA SOB CARIMBO</P>
											</TD>
										</TR>
										<TR>
											<TD class="texto" vAlign="top" colSpan="4">
												<P>NOME DO SERVIDOR POR EXTENSO<BR/>
													<BR/>&#x20;<BR/>
												</P>
											</TD>
										</TR>
										<TR>
											<TD class="texto" vAlign="top" colSpan="4">
												<P>ENDEREÇO IP DA PASSAGEM:<BR/>
													<BR/>&#x20;<BR/>
												</P>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
								<BR/>
								<TABLE class="texto" height="100" cellSpacing="0" cellPadding="0" width="710" border="1">
									<TBODY>
										<TR>
											<TD class="texto" vAlign="top" width="84">
												<P>UF<BR/>
													<BR/>&#x20;&#x20;&#x20;<B/>
												</P>
											</TD>
											<TD class="texto" vAlign="top" width="159">
												<P>DATA<BR/>&#x20;</P>
											</TD>
											<TD class="texto" vAlign="top" colSpan="2">
												<P>HORA<BR/>&#x20;</P>
											</TD>
											<TD class="texto" vAlign="top" width="157">
												<P>REPARTIÇÃO FISCAL (PF)<BR/>&#x20;</P>
											</TD>
											<TD class="texto" vAlign="top" width="191" colSpan="2">
												<P>AUTENTICAÇÃO<BR/>&#x20;</P>
											</TD>
										</TR>
										<TR>
											<TD class="texto" colSpan="4">
												<P>MATRÍCULA DO SERVIDOR:<BR/>
													<BR/>&#x20;<BR/>
												</P>
											</TD>
											<TD class="texto" vAlign="top" colSpan="3" rowSpan="3">
												<P>ASSINATURA SOB CARIMBO</P>
											</TD>
										</TR>
										<TR>
											<TD class="texto" vAlign="top" colSpan="4">
												<P>NOME DO SERVIDOR POR EXTENSO<BR/>
													<BR/>&#x20;<BR/>
												</P>
											</TD>
										</TR>
										<TR>
											<TD class="texto" vAlign="top" colSpan="4">
												<P>ENDEREÇO IP DA PASSAGEM:<BR/>
													<BR/>&#x20;<BR/>
												</P>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
								<BR/>
								<BR/>
								<xsl:for-each select="//nfe:dest/nfe:UF[generate-id()=generate-id(key('ufDest',.))]">
								<TABLE class="texto" cellSpacing="0" cellPadding="0" width="740" border="1">
									<TBODY>
										<TR vAlign="middle" align="center">
											<TD colSpan="4" height="20">
												<B>REGISTRO DE BAIXA NA UNIDADE FEDERADA DE DESTINO DAS MERCADORIAS (<xsl:value-of select="."/>)</B>
											</TD>
										</TR>
										<TR>
											<TD vAlign="top" colSpan="4">
												<TABLE>
													<TBODY>
														<TR>
															<TD class="texto" align="center" width="31%">
																<BR/>
																<BR/>
																<BR/>___________________________
																<BR/>Data 
															</TD>
															<TD class="texto" align="center" width="39%">
																<BR/>
																<BR/>
																<BR/>___________________________________________
																<BR/>Nome do Depositário por Extenso (Transportador)
															</TD>
															<TD class="texto" align="center" width="30%">
																<BR/>
																<BR/>
																<BR/>___________________________
																<BR/>Assinatura
															</TD>
														</TR>
													</TBODY>
												</TABLE>
											</TD>
										</TR>
										<TR>
											<TD vAlign="top" width="147">
												<P>REPARTIÇÃO FISCAL<BR/>
													<BR/>&#x20;<BR/>
												</P>
											</TD>
											<TD vAlign="top">
												<P>DATA<BR/>&#x20;</P>
											</TD>
											<TD vAlign="top" width="70">
												<P>HORA<BR/>&#x20;</P>
											</TD>
											<TD vAlign="top" width="301">
												<P>AUTENTICAÇÃO<BR/>&#x20;</P>
											</TD>
										</TR>
										<TR>
											<TD colSpan="3">
												<P>MATRÍCULA DO SERVIDOR:<BR/>
													<BR/>&#x20;<BR/>
												</P>
											</TD>
											<TD vAlign="top" width="301" rowSpan="3">ASSINATURA SOB CARIMBO</TD>
										</TR>
										<TR>
											<TD vAlign="top" colSpan="3">
												<P>NOME DO SERVIDOR POR EXTENSO<BR/>
													<BR/>&#x20;<BR/>
												</P>
											</TD>
										</TR>
										<TR>
											<TD vAlign="top" colSpan="3">
												<P>ENDEREÇO IP DA BAIXA:<BR/>
													<BR/>&#x20;<BR/>
												</P>
											</TD>
										</TR>
									</TBODY>
								</TABLE>
								<BR/>
								</xsl:for-each>
							</TD>
						</TR>
					</TBODY>
				</TABLE>
			</BODY>
		</HTML>
	</xsl:template>
</xsl:stylesheet>
