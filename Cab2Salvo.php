<?php
define('FPDF_FONTPATH','nfeV3/Relatorio/font/');
require('nfeV3/Relatorio/fpdf/fpdf.php');

class PDF extends FPDF
{

function Polygon($points, $style='D')
{
	//Draw a polygon
	if($style=='F')
		$op='f';
	elseif($style=='FD' or $style=='DF')
		$op='b';
	else
		$op='s';

	$h = $this->h;
	$k = $this->k;

	$points_string = '';
	for($i=0; $i<count($points); $i+=2){
		$points_string .= sprintf('%.2f %.2f', $points[$i]*$k, ($h-$points[$i+1])*$k);
		if($i==0)
			$points_string .= ' m ';
		else
			$points_string .= ' l ';
	}
	$this->_out($points_string . $op);
}

var $angle=0;

function Rotate($angle,$x=-1,$y=-1)
{
	if($x==-1)
		$x=$this->x;
	if($y==-1)
		$y=$this->y;
	if($this->angle!=0)
		$this->_out('Q');
	$this->angle=$angle;
	if($angle!=0)
	{
		$angle*=M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$this->k;
		$cy=($this->h-$y)*$this->k;
		$this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	}
}

function _endpage()
{
	if($this->angle!=0)
	{
		$this->angle=0;
		$this->_out('Q');
	}
	parent::_endpage();
}
	function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234')
	{
		$k = $this->k;
		$hp = $this->h;
		if($style=='F')
			$op='f';
		elseif($style=='FD' or $style=='DF')
			$op='B';
		else
			$op='S';
		$MyArc = 4/3 * (sqrt(2) - 1);
		$this->_out(sprintf('%.2f %.2f m',($x+$r)*$k,($hp-$y)*$k ));

		$xc = $x+$w-$r;
		$yc = $y+$r;
		$this->_out(sprintf('%.2f %.2f l', $xc*$k,($hp-$y)*$k ));
		if (strpos($angle, '2')===false)
			$this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k,($hp-$y)*$k ));
		else
			$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

		$xc = $x+$w-$r;
		$yc = $y+$h-$r;
		$this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$yc)*$k));
		if (strpos($angle, '3')===false)
			$this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-($y+$h))*$k));
		else
			$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

		$xc = $x+$r;
		$yc = $y+$h-$r;
		$this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
		if (strpos($angle, '4')===false)
			$this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-($y+$h))*$k));
		else
			$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

		$xc = $x+$r ;
		$yc = $y+$r;
		$this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
		if (strpos($angle, '1')===false)
		{
			$this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$y)*$k ));
			$this->_out(sprintf('%.2f %.2f l',($x+$r)*$k,($hp-$y)*$k ));
		}
		else
			$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
		$this->_out($op);
	}

	function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
	{
		$h = $this->h;
		$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
			$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
	}

function RotatedText($x,$y,$txt,$angle)
{
	//Text rotated around its origin
	$this->Rotate($angle,$x,$y);
	$this->Text($x,$y,$txt);
	$this->Rotate(0);
}
	function SetDash($black=false,$white=false)
	{
		if($black and $white)
			$s=sprintf('[%.3f %.3f] 0 d',$black*$this->k,$white*$this->k);
		else
			$s='[] 0 d';
		$this->_out($s);
	}

function Header()
{

    $this->SetTitle('- DANFE - V 1.00.');
    $this->SetAuthor('Cooperativa  - Divisao de Sistemas e Metodos.');
    $this->SetSubject('Documento Auxiliar da Nota Fiscal Eletronica.');
    $this->RoundedRect(5,10,155,9,0.7,'','124');
    $this->RoundedRect(5,19,30,14,0.7,'','134');
    $this->SetFont('Arial','',4.5);
    $this->SetXY(5,19.5);
    $this->Cell(30,1.5,'DATA DE RECEBIMENTO',0,0,'C');
    $this->RoundedRect(35,19,125,14,0.7,'','34');
    $this->SetXY(35,19.5);
    $this->Cell(30,1.5,'IDENTIFICAO E ASSINATURA DO RECEBEDOR');
    $this->RoundedRect(160,10,45,23,0.7,'','1234');
    $this->SetFont('Arial','B',11);
    $this->SetXY(160,11);
    $this->Cell(45,2,'NF-e',0,0,'C');
    $this->SetDash(2.4,1); //5mm on, 5mm off
    $this->Line(5,35.5,205,35.5);
    $this->SetDash();
    $this->RoundedRect(5,38,75,25,0.7,'','1234');
    $px = 8;
    $py = 41;

//  Logotipo
    $this->SetDrawColor(230,230,230);
    $this->SetFillColor(230,230,230);
    $this->Polygon(array(62,192,79,164,79,177,74,185,80,185,80,192),'FD');
    $this->Polygon(array(81,202,81,161,85,154,105,183,118,164,118,177,113,185,119,185,119,192,93,192,93,185,98,185,91,175,91,202),'FD');
    $this->Polygon(array(120,202,120,161,124,154,151,192,132,192,132,185,137,185,130,175,130,202),'FD');
    $this->SetFont('Arial','B',70);
    $this->SetTextColor(230,230,230);
    $this->RotatedText(35,225,'C R P A A A',0);
    $this->SetTextColor(0,0,0);
    $this->SetDrawColor(0,0,0);
    $this->SetFillColor(0,0,0);
    $this->SetFont('Arial','B',6.8);
    $this->SetXY(5,13.3);
    $this->Cell(155,2,' OS PRODUTOS CONSTANTES DA NF-e INDICADA AO LADO');
    $this->SetFont('Arial','B',7);
    $this->SetXY(5,39.5);
    $this->Cell(75,2,'COOP. REG.',0,0,'C');
    $this->SetFont('Arial','',5);
    $this->SetXY(29.5,49);
    $this->Cell(70,2,'RUA S E ALBUQUERQUE, 235 - JARAGU - MACEI - AL');
    $this->SetXY(29.5,51);
    $this->Cell(70,2,'FONE (82) 9999-5200 / 99999-5388');
    $this->SetXY(29.5,53);
    $this->Cell(70,2,'FAX   (82) 9999-9999 / 999-9999');
    $this->SetXY(29.5,55);
    $this->Cell(70,2,'TELEG.:  TESTE');
    $this->SetXY(29.5,57);
    $this->Cell(70,2,'CAIXA POSTAL: 10  -  CEP: 99.999-180');
    $this->SetXY(29.5,59);
    $this->Cell(70,2,'E-MAIL teste@teste.com.br');
    $px = 7;
    $py = 43;
    $py = $py + 11.637;
    $this->Polygon(array($px,$py-2.35,$px+4.32,$py-9,$px+4.32,$py-5.74,$px+3.11,$py-3.91,$px+4.32,$py-3.91,$px+4.32,$py-2.35),'FD');
    $this->Polygon(array($px+4.85,$py,$px+4.85,$py-9.71,$px+6.13,$py-11.637,$px+10.62,$py-4.81,$px+13.31,$py-8.95,$px+13.31,$py-5.74,$px+12.08,$py-3.91,$px+13.31,$py-3.91,$px+13.31,$py-2.35,$px+7.88,$py-2.35,$px+7.88,$py-3.91,$px+9.18,$py-3.91,$px+7.37,$py-6.71,$px+7.37,$py),'FD');
    $this->Polygon(array($px+13.84,$py,$px+13.84,$py-9.71,$px+15.12,$py-11.637,$px+21.249,$py-2.35,$px+16.87,$py-2.35,$px+16.87,$py-3.91,$px+18.17,$py-3.91,$px+16.37,$py-6.71,$px+16.37,$py),'FD');
    $this->SetDash(0.95,0.5);
    $this->Line($px,$py+0.863,$px+21.249,$py+0.863);
    $this->Line($px,$py+6.163,$px+21.249,$py+6.163);
    $this->SetDash();
    $this->SetFont('Arial','',3.9);
    $this->SetXY($px,$py+1.363);
    $this->Cell(21.249,1.5,'TESTE',0,0,'C');
    $this->SetXY($px,$py+2.863);
    $this->Cell(21.249,1.5,'TTTT',0,0,'C');
    $this->SetXY($px,$py+4.363);
    $this->Cell(21.249,1.5,'E LCOOL DE ALAGOAS',0,0,'C');
//  Fim logotipo da cooperativa
    $this->SetFont('Arial','B',10);
    $this->SetXY(82.5,38);
    $this->Cell(30,2,'DANFE',0,0,'C');
    $this->SetFont('Arial','',5);
    $this->SetXY(82.5,41);
    $this->Cell(30,2,'DOCUMENTO AUXILIAR DA NOTA',0,0,'C');
    $this->SetXY(82.5,43);
    $this->Cell(30,2,'FISCAL ELETRONICA',0,0,'C');
    $this->SetXY(82.5,48);
    $this->Cell(30,2,'      1 - SAIDA');
    $this->SetXY(82.5,51);
    $this->Cell(30,2,'      2 - ENTRADA');
    $this->Rect(101.5,48.5,3,3);
    $this->RoundedRect(115,38,90,31,0.7,'','1234');
    $this->SetFont('Arial','',4.5);
    $this->SetXY(115,38.5);
    $this->Cell(30,1.5,'CONTROLE DO FISCO');
    $this->RoundedRect(5,64,109,6,0.7,'','1234');
    $this->SetXY(5,64.5);
    $this->Cell(30,1.5,'NATUREZA DA OPERACAO');
    $this->RoundedRect(5,70,35,6,0.7,'','134');
    $this->SetXY(5,70.5);
    $this->Cell(30,1.5,'INSCRI��O ESTADUAL');
    $this->RoundedRect(40,70,39,6,0.7,'','34');
    $this->SetXY(40,70.5);
    $this->Cell(30,1.5,'INSC. ESTADUAL DO SUBS. TRIBUTORIO');
    $this->RoundedRect(79,70,35,6,0.7,'','234');
    $this->SetXY(79,70.5);
    $this->Cell(30,1.5,'CNPJ');
    $this->RoundedRect(115,70,90,6,0.7,'','1234');
    $this->SetXY(115,70.5);
    $this->Cell(30,1.5,'CHAVE DE ACESSO PARA CONSULTA DE AUTENTICIDADE NO SITE WWW.NFE.FAZENDA.GOV.BR');
    $this->SetFont('Arial','',5);
    $this->SetXY(5,77);
    $this->Cell(30,1.5,'DESTINAT�RIO / REMETENTE');
    $this->RoundedRect(5,79,128,6,0.7,'','124');
    $this->RoundedRect(133,79,40,6,0.7,'','123');
    $this->RoundedRect(175,79,30,6,0.7,'','1234');
    $this->SetFont('Arial','',4);
    $this->SetXY(5,79.5);
    $this->Cell(30,1.5,'NOME / RAZO SOCIAL');
    $this->SetXY(133,79.5);
    $this->Cell(30,1.5,'CNPJ / CPF');
    $this->SetXY(175,79.5);
    $this->Cell(35,1.5,'DATA DA EMISSO');
    $this->RoundedRect(5,85,105,6,0.7,'','14');
    $this->Rect(110,85,48,6);
    $this->RoundedRect(158,85,15,6,0.7,'','23');
    $this->RoundedRect(175,85,30,6,0.7,'','1234');
    $this->SetXY(10,85.5);
    $this->Cell(30,1.5,'ENDERE�O');
    $this->SetXY(110,85.5);
    $this->Cell(30,1.5,'BAIRRO / DISTRITO');
    $this->SetXY(158,85.5);
    $this->Cell(15,1.5,'CEP');
    $this->SetXY(175,85.5);
    $this->Cell(30,1.5,'DATA DA SA�DA / ENTRADA');
    $this->RoundedRect(5,91,83,6,0.7,'','134');
    $this->RoundedRect(88,91,35,6,0.7,'','34');
    $this->RoundedRect(123,91,10,6,0.7,'','34');
    $this->RoundedRect(133,91,40,6,0.7,'','234');
    $this->RoundedRect(175,91,30,6,0.7,'','1234');
    $this->SetXY(5,91.5);
    $this->Cell(30,1.5,'MUNICIPIO');
    $this->SetXY(88,91.5);
    $this->Cell(30,1.5,'TELEFONE / FAX');
    $this->SetXY(123,91.5);
    $this->Cell(10,1.5,'UF');
    $this->SetXY(133,91.5);
    $this->Cell(30,1.5,'INSCRICAO ESTADUAL');
    $this->SetXY(175,91.5);
    $this->Cell(30,1.5,'HORA DA SAIDA');
    $this->SetFont('Arial','',5);
    $this->SetXY(5,98);
    $this->Cell(30,1.5,'FATURA');
    $this->RoundedRect(5,100,22.2,6,0.7,'','1234');
    $this->RoundedRect(27.2,100,22.2,6,0.7,'','1234');
    $this->RoundedRect(49.4,100,22.2,6,0.7,'','1234');
    $this->RoundedRect(71.6,100,22.2,6,0.7,'','1234');
    $this->RoundedRect(93.8,100,22.2,6,0.7,'','1234');
    $this->RoundedRect(116,100,22.2,6,0.7,'','1234');
    $this->RoundedRect(138.2,100,22.2,6,0.7,'','1234');
    $this->RoundedRect(160.4,100,22.2,6,0.7,'','1234');
    $this->RoundedRect(182.6,100,22.4,6,0.7,'','1234');
    $this->SetFont('Arial','',4.5);
    $this->SetXY(5,100.5);
    $this->Cell(22.2,1.5,'NUMERO',0,0,'C');
    $this->SetXY(27.2,100.5);
    $this->Cell(22.2,1.5,'VENCIMENTO',0,0,'C');
    $this->SetXY(49.4,100.5);
    $this->Cell(22.2,1.5,'VALOR',0,0,'C');
    $this->SetXY(71.6,100.5);
    $this->Cell(22.2,1.5,'NUMERO',0,0,'C');
    $this->SetXY(93.8,100.5);
    $this->Cell(22.2,1.5,'VENCIMENTO',0,0,'C');
    $this->SetXY(116,100.5);
    $this->Cell(22.2,1.5,'VALOR',0,0,'C');
    $this->SetXY(138.2,100.5);
    $this->Cell(22.2,1.5,'N�MERO',0,0,'C');
    $this->SetXY(160.4,100.5);
    $this->Cell(22.2,1.5,'VENCIMENTO',0,0,'C');
    $this->SetXY(182.6,100.5);
    $this->Cell(22.4,1.5,'VALOR',0,0,'C');
    $this->SetFont('Arial','',5);
    $this->SetXY(5,107);
    $this->Cell(30,1.5,'CALCULO DO IMPOSTO');
    $this->RoundedRect(5,109,40,6,0.7,'','124');
    $this->RoundedRect(45,109,40,6,0.7,'','12');
    $this->RoundedRect(85,109,40,6,0.7,'','12');
    $this->RoundedRect(125,109,40,6,0.7,'','12');
    $this->RoundedRect(165,109,40,6,0.7,'','123');
    $this->SetFont('Arial','',4.5);
    $this->SetXY(5,109.5);
    $this->Cell(30,1.5,'BASE DE CALCULO DO ICMS');
    $this->SetXY(45,109.5);
    $this->Cell(30,1.5,'VALOR DO ICMS');
    $this->SetXY(85,109.5);
    $this->Cell(40,1.5,'BASE DE CALCULO DO ICMS SUBSTITUICAOO');
    $this->SetXY(125,109.5);
    $this->Cell(30,1.5,'VALOR DO ICMS SUBSTITUICAO');
    $this->SetXY(165,109.5);
    $this->Cell(30,1.5,'VALOR TOTAL DOS PRODUTOS');
    $this->RoundedRect(5,115,32,6,0.7,'','34');
    $this->RoundedRect(37,115,32,6,0.7,'','34');
    $this->RoundedRect(69,115,32,6,0.7,'','34');
    $this->RoundedRect(101,115,32,6,0.7,'','34');
    $this->RoundedRect(133,115,32,6,0.7,'','34');
    $this->RoundedRect(165,115,40,6,0.7,'','234');
    $this->SetXY(5,115.5);
    $this->Cell(30,1.5,'VALOR DO FRETE');
    $this->SetXY(37,115.5);
    $this->Cell(30,1.5,'VALOR DO SEGURO');
    $this->SetXY(69,115.5);
    $this->Cell(30,1.5,'DESCONTO');
    $this->SetXY(101,115.5);
    $this->Cell(30,1.5,'OUTRAS DESPESAS ACESSORIAS');
    $this->SetXY(133,115.5);
    $this->Cell(30,1.5,'VALOR DO IPI');
    $this->SetXY(165,115.5);
    $this->Cell(30,1.5,'VALOR TOTAL DA NOTA');
    $this->SetFont('Arial','',5);
    $this->SetXY(5,122);
    $this->Cell(30,1.5,'TRANSPORTADOR / VOLUMES TRANSPORTADOS');
    $this->RoundedRect(5,124,90,6,0.7,'','124');
    $this->RoundedRect(95,124,23.5,6,0.7,'','12');
    $this->Rect(114,126.4,3,3);
    $this->RoundedRect(118.5,124,23.5,6,0.7,'','12');
    $this->RoundedRect(142,124,23.5,6,0.7,'','12');
    $this->RoundedRect(165.5,124,9.5,6,0.7,'','12');
    $this->RoundedRect(175,124,30,6,0.7,'','123');
    $this->SetFont('Arial','',4.5);
    $this->SetXY(5,124.5);
    $this->Cell(30,1.5,'NOME / RAZ�O SOCIAL');
    $this->SetXY(95,124.5);
    $this->Cell(30,1.5,'FRETE POR CONTA:');
    $this->SetFont('Arial','',5);
    $this->SetXY(95,126.5);
    $this->Cell(30,1.5,'1 - EMITENTE');
    $this->SetXY(95,128.2);
    $this->Cell(30,1.5,'2 - DESTINATORIO');
    $this->SetFont('Arial','',4.5);
    $this->SetXY(118.5,124.5);
    $this->Cell(30,1.5,'C�DIGO ANTT');
    $this->SetXY(142,124.5);
    $this->Cell(30,1.5,'PLACA DO VEICULO');
    $this->SetXY(165,124.5);
    $this->Cell(10,1.5,'UF');
    $this->SetXY(175,124.5);
    $this->Cell(30,1.5,'CNPJ / CPF');
    $this->RoundedRect(5,130,90,6,0.7,'','14');
    $this->RoundedRect(95,130,70.5,6,0.7,'','');
    $this->RoundedRect(165.5,130,9.5,6,0.7,'','');
    $this->RoundedRect(175,130,30,6,0.7,'','23');
    $this->SetXY(5,130.5);
    $this->Cell(30,1.5,'ENDERECO');
    $this->SetXY(95,130.5);
    $this->Cell(30,1.5,'MUNICIPIO');
    $this->SetXY(165.5,130.5);
    $this->Cell(10,1.5,'UF');
    $this->SetXY(175,130.5);
    $this->Cell(30,1.5,'INSCRICAO ESTADUAL');
    $this->RoundedRect(5,136,33.4,6,0.7,'','134');
    $this->RoundedRect(38.4,136,33.4,6,0.7,'','34');
    $this->RoundedRect(71.8,136,33,6,0.7,'','34');
    $this->RoundedRect(104.8,136,33.4,6,0.7,'','34');
    $this->RoundedRect(138.2,136,33.4,6,0.7,'','34');
    $this->RoundedRect(171.6,136,33.4,6,0.7,'','234');
    $this->SetXY(5,136.5);
    $this->Cell(30,1.5,'QUANTIDADE');
    $this->SetXY(38.4,136.5);
    $this->Cell(30,1.5,'ESPECIE');
    $this->SetXY(71.8,136.5);
    $this->Cell(30,1.5,'MARCA');
    $this->SetXY(104.8,136.5);
    $this->Cell(30,1.5,'NUMERACAO');
    $this->SetXY(138.2,136.5);
    $this->Cell(30,1.5,'PESO BRUTO');
    $this->SetXY(171.6,136.5);
    $this->Cell(30,1.5,'PESO LIQUIDO');
    $this->SetFont('Arial','',5);
    $this->SetXY(5,143);
    $this->Cell(30,1.5,'DADOS DOS PRODUTOS / SERVICOS');
    $this->RoundedRect(5,145,11,6,0.7,'','124');
    $this->RoundedRect(16,145,48,6,0.7,'','12');
    $this->RoundedRect(64,145,11,6,0.7,'','12');
    $this->RoundedRect(75,145,7,6,0.7,'','12');
    $this->RoundedRect(82,145,7,6,0.7,'','12');
    $this->RoundedRect(89,145,7,6,0.7,'','12');
    $this->RoundedRect(96,145,18,6,0.7,'','12');
    $this->RoundedRect(114,145,15,6,0.7,'','12');
    $this->RoundedRect(129,145,18,6,0.7,'','12');
    $this->RoundedRect(147,145,18,6,0.7,'','12');
    $this->RoundedRect(165,145,13,6,0.7,'','12');
    $this->RoundedRect(178,145,13,6,0.7,'','12');
    $this->RoundedRect(191,145,7,6,0.7,'','12');
    $this->RoundedRect(198,145,7,6,0.7,'','123');
    $this->SetFont('Arial','',5);
    $this->SetXY(5,146.5);
    $this->Cell(11,1.5,'CODIGO DO',0,0,'C');
    $this->SetXY(5,148.5);
    $this->Cell(11,1.5,'PRODUTO',0,0,'C');
    $this->SetXY(16,147.5);
    $this->Cell(48,1.5,'DESCRICAO DO PRODUTO / SERVICO',0,0,'C');
    $this->SetXY(64,147.5);
    $this->Cell(11,1.5,'NCM / SH',0,0,'C');
    $this->SetXY(75,147.5);
    $this->Cell(7,1.5,'CST',0,0,'C');
    $this->SetXY(82,147.5);
    $this->Cell(7,1.5,'CFOP',0,0,'C');
    $this->SetXY(89,147.5);
    $this->Cell(7,1.5,'UNID.',0,0,'C');
    $this->SetXY(96,147.5);
    $this->Cell(18,1.5,'QUANTIDADE',0,0,'C');
    $this->SetXY(114,146.5);
    $this->Cell(15,1.5,'VALOR',0,0,'C');
    $this->SetXY(114,148.5);
    $this->Cell(15,1.5,'UNITARIO',0,0,'C');
    $this->SetXY(129,147.5);
    $this->Cell(18,1.5,'VALOR TOTAL',0,0,'C');
    $this->SetXY(147,147.5);
    $this->Cell(18,1.5,'BASE DO ICMS',0,0,'C');
    $this->SetXY(165,146.5);
    $this->Cell(13,1.5,'VALOR',0,0,'C');
    $this->SetXY(165,148.5);
    $this->Cell(13,1.5,'DO ICMS',0,0,'C');
    $this->SetXY(178,146.5);
    $this->Cell(13,1.5,'VALOR',0,0,'C');
    $this->SetXY(178,148.5);
    $this->Cell(13,1.5,'DO IPI',0,0,'C');
    $this->SetXY(191,146.5);
    $this->Cell(7,1.5,'ALIQ.',0,0,'C');
    $this->SetXY(191,148.5);
    $this->Cell(7,1.5,'ICMS',0,0,'C');
    $this->SetXY(198,146.5);
    $this->Cell(7,1.5,'ALIQ.',0,0,'C');
    $this->SetXY(198,148.5);
    $this->Cell(7,1.5,'IPI',0,0,'C');
    $this->RoundedRect(5,151,11,80,0.7,'','134');
    $this->RoundedRect(16,151,48,80,0.7,'','34');
    $this->RoundedRect(64,151,11,80,0.7,'','34');
    $this->RoundedRect(75,151,7,80,0.7,'','34');
    $this->RoundedRect(82,151,7,80,0.7,'','34');
    $this->RoundedRect(89,151,7,80,0.7,'','34');
    $this->RoundedRect(96,151,18,80,0.7,'','34');
    $this->RoundedRect(114,151,15,80,0.7,'','34');
    $this->RoundedRect(129,151,18,80,0.7,'','34');
    $this->RoundedRect(147,151,18,80,0.7,'','34');
    $this->RoundedRect(165,151,13,80,0.7,'','34');
    $this->RoundedRect(178,151,13,80,0.7,'','34');
    $this->RoundedRect(191,151,7,80,0.7,'','34');
    $this->RoundedRect(198,151,7,80,0.7,'','234');
    $this->SetXY(5,232);
    $this->Cell(30,1.5,'CALCULO DO ISSQN');
    $this->RoundedRect(5,234,50,6,0.7,'','1234');
    $this->RoundedRect(55,234,50,6,0.7,'','1234');
    $this->RoundedRect(105,234,50,6,0.7,'','1234');
    $this->RoundedRect(155,234,50,6,0.7,'','1234');
    $this->SetFont('Arial','',4.5);
    $this->SetXY(5,234.5);
    $this->Cell(30,1.5,'INSCRICAO MUNICIPAL');
    $this->SetXY(55,234.5);
    $this->Cell(30,1.5,'VALOR TOTAL DOS SERVICOS');
    $this->SetXY(105,234.5);
    $this->Cell(30,1.5,'BASE DE CALCULO DO ISSQN');
    $this->SetXY(155,234.5);
    $this->Cell(30,1.5,'VALOR DO ISSQN');
    $this->SetFont('Arial','',5);
    $this->SetXY(5,241);
    $this->Cell(30,1.5,'DADOS ADICIONAIS');
    $this->RoundedRect(5,243,100,46,0.7,'','1234');
    $this->RoundedRect(105,243,98,46,0.7,'','1234');
    $this->SetFont('Arial','',4.5);
    $this->SetXY(5,243.5);
    $this->Cell(30,1.5,'INFORMACOES COMPLEMENTARES');
    $this->SetXY(105,243.5);
    $this->Cell(30,1.5,'RESERVADO AO FISCO');
    $this->SetFont('Arial','',5.8);
    $this->RotatedText(205,289,'CRPAAA - DIVISAO DE SISTEMAS E M�TODOS',90);

 }
}
$pdf=new PDF('P','mm','A4');
$pdf->SetMargins(5, 10);
$pdf->Open();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->SetXY(10,20);
$txt='TESTE DE IMPRESSAO DO CABECALHO.';
//for($i=0;$i<25;$i++)
//    $pdf->Write(5,$txt);
$pdf->Output();
?>
