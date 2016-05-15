<? ob_start(); ?>
<? session_start(); ?>
<? require("seguranca.php"); ?>
<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

// Sempre modificado
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);

// HTTP/1.0
header("Pragma: no-cache");
?>
<html>
<head>
<script src="js/cpf_cnpj.js" type="text/javascript"></script>
<style>


		td,body{font-family: verdana,tahoma,arial,verdana; font-size: 11px; color: black;}
		.campo{background-color: #E5E5E5; color: #373F56; border-color: white; border-width: 1px; font-family: verdana; font-size: 11px}
		.botao{color: #373F56; background-color: #E5E5E5; height: 18px;	border-width: 1px; font-family: verdana; font-size: 11px}
	</style>
<script language="JavaScript">

//Data

function DataVencimento(){

  data = new Date();

  dataEmissao = Date.UTC( data.getFullYear(),data.getMonth()  +  1,data.getDate(),0,0,0);

  //strDataEmissao = data.getDate() + '/' + ( data.getMonth()  +  1 )  + '/' + data.getFullYear();

  /*dia = parseInt(String(document.form.txtData.value).substring(0,2));
  mes = parseInt(String(document.form.txtData.value).substring(3,5));
  ano = parseInt(String(document.form.txtData.value).substring(6,10));
  */

  dia = (String(document.form.txtData.value).substring(0,2));
  mes = (String(document.form.txtData.value).substring(3,5));
  ano = (String(document.form.txtData.value).substring(6,10));

  dataVencimento = Date.UTC(ano,mes,dia,0,0,0);

 // alert(dia + '-'+mes+'-'+ano)

  //alert((dataEmissao ) + '  ' + (dataVencimento));
 // alert(Date.parse(strDataEmissao,0,0,0 ) + '  ' + Date.parse(document.form.txtData.value,0,0,0));
   if ( dataVencimento  < dataEmissao  )  {
     alert("ATEN��O : A data do vencimento tem que ser maior que data de emissao ! ");
	 document.form.txtData.focus();
   }
}

//Zero a esquerda
function preenche(campo, tamanho){

//captura do texto
var strText = campo.value;

/*verifica se o campo esta vazio e pede
a confirmacao do valor*/

if (strText == "" ) {

//apresenta a caixa de confirmacao
if (confirm("Texto vazio - preencher com " + tamanho + " zeros? ")) {
//preeche com a quantidade de zeros informada no
//par�metro tamanho

for (i=0; i<tamanho;i++)
campo.value += "0";
}else{
//a pessoa clicou no cancelar, voltando o foco para
//o campo
campo.focus();
}

}else{ //haalguma string no campo

//tamanho da string
var intTamStr = strText.length;

/*verifica se o tamanho da string eh
menor ou igual ao tamanho que eh pedido
na funcao*/

if (intTamStr <= tamanho){//executa a adaptacao do texto

//quantos zeros serao incluidos no texto
var intTam = parseInt(tamanho) - intTamStr;

//preenchimento do campo
for (i=0; i<intTam; i++){
strText = "0" + strText;
}

//atribuicao da variavel ao campo
campo.value = strText;

}else{
// o texto eh maior do que eh pedido na funcao
alert("Este campo pode ter no m�ximo \n" + tamanho + " caracteres.");
campo.focus();
}
}
}


//Placa Veiculo
function valida(valor){
    with(document.form){
        var erro = 0; //Erros
        var msg = ""; //MSGs
        var er = /[a-z]{3}?\d{4}/gim; //Expressao regular para 3 letras e 4 n�meros

       if (valor != ""){
            er.lastIndex = 0;
            pl = valor;
            if (!er.test(pl)){
                msg = msg + "Placa invalida. Uma placa valida deve contem 3 letras e 4 numeros.\n";
                erro = erro + 1;
				 alert(msg);
            }
        }

        /*if (erro==0){
            submit();
        } else {
            alert(msg);
        }*/
		// alert(msg);
    }
}


function doSubmit(pEvent, pForm,bCpfCnpj){

   //bCpfCnpj = true CNPFJ
   //bCpfCnpj = false CPF

	if ( bCpfCnpj )
		var val = pForm.txt_TransCnpj.value;
    else
	  var val = pForm.txt_EmiCpf.value;

	var base = val.substring(0, val.length-2);

	if (bCpfCnpj) {
	  if ( isCnpj(val) == false ) {
		alert("CNPJ: Invalido !"
			+ "\nDesformatado = " + unformatNumber(val)
			+ "\nFormatado = " + formatCpfCnpj(val, true, true)
			+ "\nDVs = " + dvCpfCnpj(base, true)
			+ "\nValido = " + isCnpj(val));
	    pForm.txt_TransCnpj.focus();
	  }
	} else {
	   if ( isCpf(val) == false ) {
		alert("CPF: Invalido !"
			+ "\nDesformatado = " + unformatNumber(val)
			+ "\nFormatado = " + formatCpfCnpj(val, true)
			+ "\nDVs = " + dvCpfCnpj(base, false)
			+ "\nValido = " + isCpf(val));
	    pForm.txt_EmiCpf.focus();
	   }

	}
	return false;
} //doSubmit

function  janOS(pagina,alt,larg){
 url=pagina;
 //alert(url);
 parametros  = "height="+alt+", width="+larg;
 parametros += ", status=yes, location=no";
 parametros += ", toolbar=no, menubar=no, scrollbars=yes, ";
 // define o tamanho e o local da janela a ser aberta
 parametros += "top="+(((screen.height)/2)-(400/2))+", ";
 parametros += "left="+(((screen.width)/2)-(750/2))+"";
 //alert(url+"?filial=");
 window.open(url,"",parametros);

}

function  janItemPedido(pagina,alt,larg){
 url=pagina;
 //alert(url);
 parametros  = "height="+alt+", width="+larg;
 parametros += ", status=yes, location=no";
 parametros += ", toolbar=no, menubar=no, scrollbars=yes, ";
 // define o tamanho e o local da janela a ser aberta
 parametros += "top="+(((screen.height)/2)-(400/2))+", ";
 parametros += "left="+(((screen.width)/2)-(750/2))+"";
 //alert(url+"?filial=");
 window.open(url+"?pedido="+document.form.txt_pedido.value,"",parametros);

}

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function atualiza(){



   if ( document.form.txtProduto.value.length <= 0  ) {
    alert('Falta o produto');
    return false;
  }





  if ( document.form.txt_Cfop.value.length <= 0  ) {
   alert('O Cfop e necessario');
   form.txt_Cfop.focus();
   document.form.txt_Cfop.style.backgroundColor='Red';
   return false;
  }

  if ( document.form.txt_codTrans.value.length <= 0  ) {
   alert('A transportadora e necessario');
   document.form.txt_codTrans.style.backgroundColor='Red';
   form.txt_codTrans.focus();
   return false;
  }

  /*
  if ( document.form.txt_placa.value.length <= 0  ) {
   alert('A placa do veiculo  e necessario');
   document.form.txt_placa.style.backgroundColor='Red';
   form.txt_placa.focus();
   return false;
  } */

  if ( document.form.txt_cbt.value.length <= 0  ) {
   alert('O cbt e necessario');
   document.form.txt_cbt.style.backgroundColor='Red';
   form.txt_cbt.focus();
   return false;
  }

  if ( document.form.txt_TransUf.value.length <= 1  ) {
    alert('a uf da transportadora estar faltando');
	document.form.txt_TransUf.style.backgroundColor='Red';
    form.txt_TransUf.focus();
	return false;
  }

  document.getElementById('btnAtual').disabled = true;
 document.form.submit();

}

/* Formatacao para qualquer mascara */

function formatar(src, mask)
{
  var i = src.value.length;
  var saida = mask.substring(0,1);
  var texto = mask.substring(i)
if (texto.substring(0,1) != saida)
  {
	src.value += texto.substring(0,1);
  }
}

/**Habilita Ndo */
/*
  1= Habi
  2 = Des
*/

/*
function fHabNdo(valor, hab ){

 if ( hab == 0 )
  document.form.txt_ndo.disabled = true;
 else
  if ( document.form.txt_mae.value != "" )
    document.form.txt_ndo.disabled = false;
  else
    if ( valor.length == 0 ){
      document.form.txt_ndo.disabled = true;
	  document.form.txt_ndo.value = "";
	}
}*/

//Valida a quantidade do Pedido
function qtPedido(qtPedido,qtdAtual,qtdCancelada,qtdAtendida) {

 qtPedido =  parseFloat(qtPedido);
 qtdAtual = parseFloat(qtdAtual);
 qtdCancelada = parseFloat(qtdCancelada);
 qtdAtendida = parseFloat(qtdAtendida);
 document.form.txt_qt.style.backgroundColor='#CBE4CB';
 document.form.txt_qt.style.color='black';

 if ( qtPedido < ( qtdAtual + qtdCancelada + qtdAtendida )) {

  if ( qtPedido > ( qtdCancelada + qtdAtendida + qtdAtual) )
   qt = qtPedido - ( qtdCancelada + qtdAtendida + qtdAtual);
 else
   qt =  ( qtdCancelada + qtdAtendida + qtdAtual) - qtPedido ;


  alert("A quantidade da nota (" + qtdAtual + ")  utrapassou em  (" + qt  + ") !");
  document.form.txt_qt.style.backgroundColor='Red';
 } else {
    document.form.txt_qt.style.backgroundColor='white';
	document.form.txt_qt.style.color='black';
   }

}


/***********************************************
* Cool DHTML tooltip script II-  Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetfromcursorX=12 //Customize x offset of tooltip
var offsetfromcursorY=10 //Customize y offset of tooltip

var offsetdivfrompointerX=10 //Customize x offset of tooltip DIV relative to pointer image
var offsetdivfrompointerY=14 //Customize y offset of tooltip DIV relative to pointer image. Tip: Set it to (height_of_pointer_image-1).

document.write('<div id="dhtmltooltip"></div>') //write out tooltip DIV
document.write('<img id="dhtmlpointer" src="arrow2.gif">') //write out pointer image

var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

var pointerobj=document.all? document.all["dhtmlpointer"] : document.getElementById? document.getElementById("dhtmlpointer") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thewidth, thecolor){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var nondefaultpos=false
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var winwidth=ie&&!window.opera? ietruebody().clientWidth : window.innerWidth-20
var winheight=ie&&!window.opera? ietruebody().clientHeight : window.innerHeight-20

var rightedge=ie&&!window.opera? winwidth-event.clientX-offsetfromcursorX : winwidth-e.clientX-offsetfromcursorX
var bottomedge=ie&&!window.opera? winheight-event.clientY-offsetfromcursorY : winheight-e.clientY-offsetfromcursorY

var leftedge=(offsetfromcursorX<0)? offsetfromcursorX*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth){
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=curX-tipobj.offsetWidth+"px"
nondefaultpos=true
}
else if (curX<leftedge)
tipobj.style.left="5px"
else{
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetfromcursorX-offsetdivfrompointerX+"px"
pointerobj.style.left=curX+offsetfromcursorX+"px"
}

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight){
tipobj.style.top=curY-tipobj.offsetHeight-offsetfromcursorY+"px"
nondefaultpos=true
}
else{
tipobj.style.top=curY+offsetfromcursorY+offsetdivfrompointerY+"px"
pointerobj.style.top=curY+offsetfromcursorY+"px"
}
tipobj.style.visibility="visible"
if (!nondefaultpos)
pointerobj.style.visibility="visible"
else
pointerobj.style.visibility="hidden"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
pointerobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip

</script>
<link href="../estilo.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">

#dhtmltooltip{
position: absolute;
left: -300px;
width: 150px;
border: 1px solid black;
padding: 2px;
background-color: lightyellow;
visibility: hidden;
z-index: 100;
/*Remove below line to remove shadow. Below line should always appear last within this CSS*/
filter: progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135);
}

#dhtmlpointer{
position:absolute;
left: -300px;
z-index: 101;
visibility: hidden;
}

body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>
<body align="center" bgcolor="white" >
<form action="AtualNotaV2.php" name="form" method="post">

  <table border="0" width="96%" cellspacing="0" cellpadding="0" height="1">
    <tr>
          <td width="1%" valign="top" height="1"></td>
            <p align="center" class="body"><br>
           </p>
           <td>
          </td>
          <td width="99%" valign="top" height="1">
            <table border="0" width="100%" cellspacing="0" cellpadding="0" height="426">
          <tr>

            <td width="100%" height="117" align="center" background="../Figuras/NfSaidaTopo1.jpg" style="background-repeat: no-repeat;"></td>
              </tr>
              <tr>
          <td height="25" colspan="4" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo">
          <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gerar Nota de Saida</div></td>      </tr>

              <tr>
                <td width="100%" align="center" height="81">
                  <table border="0" width="103%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="82%">
                        <table border="0" width="791" cellspacing="0" cellpadding="0" height="371">
                          <tr class="body">
                            <td align="right" height="22"><b>Pedido:</b></td>
                            <td height="22">
                            <td height="22"><input type="text" name="txt_pedido" value = "" size="12" style="border: 1 solid #00923f; background-Color:'#CBE4CB';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" onMouseDown="ddrivetip('N&uacute;mero do Pedido', 300);" onMouseout="hideddrivetip();" disabled>
                                <img src="../Figuras/btEdit.jpg" width="16" height="16" onClick="janOS('AjudaPedido.php',450,800);" onMouseDown="ddrivetip('Abre uma nova janela pra escolher o pedido', 300);" onMouseout="hideddrivetip();">                          <b>Nota Referenciada :
                                <input type="text" name="txt_nfRef" value = "" size="12" style="border: 1 solid #00923f; background-Color:'#CBE4CB';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" onMouseDown="ddrivetip('N&uacute;mero do Pedido', 300);" onMouseout="hideddrivetip();" >
                          </b>                          </tr>
                      <tr class="body">
                        <td align="right" height="22"><b>Seleciona o produto :</b></td>
                        <td height="22">&nbsp;</td>
                        <td height="22"><img src="../Figuras/btEdit.jpg" width="16" height="16" onClick="janItemPedido('AjudaItemPedido.php',450,800);" onMouseDown="ddrivetip('Abre um nova janela pra escolher o produto', 300);" onMouseout="hideddrivetip();"></td>
                      </tr>
                      <tr>
                        <td align="right"><b>Cfop:</b></td>
                        <td></td>
                        <td ><input name="txt_Cfop" type="text" id="txt_Cfop" value="" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" size="12" onMouseDown="ddrivetip('N�mero da Cfop', 300);" onMouseout="hideddrivetip();">
                          <input name="txt_DscCfop" type="text" id="txt_DscCfop" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" value = "" size="50" onMouseDown="ddrivetip('Descri��o da Cfop', 300);" onMouseout="hideddrivetip();"  >
                          <img src="../Figuras/btEdit.jpg" width="16" height="16" onClick="janOS('AjudaCfop.php',400,500);" onMouseDown="ddrivetip('Abre uma nova janela pra escolher a CFOP', 300);" onMouseout="hideddrivetip();"></td>
                      </tr>
                      <tr>
                        <td align="right" height="25"><b>Transportadora:</b></td>
                        <td height="25"></td>
                        <td height="25"><input name="txt_codTrans" type="text" id="txt_codTrans" value="" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" size="5" disabled>
                          <input name="txt_DscTrans" type="text" id="txt_DscTrans" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" value = "" size="40" onMouseDown="ddrivetip('Descri&ccedil;&atilde;o da transportadora ', 300);" onMouseout="hideddrivetip();">
                          <img src="../Figuras/btEdit.jpg" width="16" height="16" onClick="janOS('AjudaTransportadora.php',400,500);" onMouseDown="ddrivetip('Abre uma nova janela pra escolher a transportadora', 300);" onMouseout="hideddrivetip();"> <strong>UF:</strong>
                          <label>
                          <input name="txt_TransUf" type="text" id="txt_TransUf" style="border: 1 solid #00923f; background-Color:'#CBE4CB';text-transform:uppercase;" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" onMouseDown="ddrivetip('N&uacute;mero do Pedido', 300);" onMouseout="hideddrivetip();" value = "" size="2" maxlength="2" >
                          </label>
                          <strong>Cnpj:</strong>
                          <input name="txt_TransCnpj" type="text" id="txt_TransCnpj" style="border: 1 solid #00923f; background-Color:'#CBE4CB';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black';return doSubmit(event, form,true);" onMouseDown="ddrivetip('N&uacute;mero do Pedido', 300);" onMouseout="hideddrivetip();" size="14" maxlength="14" ></td>
                      </tr>
                      <tr>
                        <td align="right" height="25"><b>Placa Veiculo:</b></td>
                        <td height="25"></td>
                        <td height="25"><input name="txt_placa" type="text" id="txt_placa" style="border: 1 solid #00923f; background-Color:'#CBE4CB';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black';valida(this.value);" onMouseDown="ddrivetip('Placa do veiculo', 300);" onMouseOut="hideddrivetip();" value = "" size="8" maxlength="7" >
                          <strong>UF:</strong>
                          <label>
                          <select name="SelUfVeiculo" id="SelUfVeiculo" class="cmbCampo">
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AM">AM </option>
                            <option value="AP">AP </option>
                            <option value="BA">BA </option>
                            <option value="CE">CE </option>
                            <option value="DF">DF </option>
                            <option value="ES">ES </option>
                            <option value="GO">GO </option>
                            <option value="MA">MA </option>
                            <option value="MG">MG </option>
                            <option value="MS">MS </option>
                            <option value="MT">MT </option>
                            <option value="PA">PA </option>
                            <option value="PB">PB </option>
                            <option value="PE">PE </option>
                            <option value="PI">PI </option>
                            <option value="PR">PR </option>
                            <option value="RJ">RJ </option>
                            <option value="RN">RN </option>
                            <option value="RO">RO </option>
                            <option value="RR">RR </option>
                            <option value="RS">RS </option>
                            <option value="SC">SC </option>
                            <option value="SE">SE </option>
                            <option value="SP">SP </option>
                            <option value="TO">TO </option>
                            <option value="EX">EX </option>
                          </select>
                          </label></td>
                      </tr>
                      <tr>
                        <td align="right" height="25"><b>Reboque :</b></td>
                        <td height="25"></td>
                        <td height="25"><input name="txt_reb" type="text" id="txt_reb" style="border: 1 solid #00923f; background-Color:'#CBE4CB';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black';valida(this.value);" onMouseDown="ddrivetip('Reboque Primario', 300);" onMouseout="hideddrivetip();" value = "" size="8" maxlength="7" >
                          <strong>UF:</strong>
                          <label>
                          <select name="SelUfReb" id="SelUfReb" class="cmbCampo">
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AM">AM </option>
                            <option value="AP">AP </option>
                            <option value="BA">BA </option>
                            <option value="CE">CE </option>
                            <option value="DF">DF </option>
                            <option value="ES">ES </option>
                            <option value="GO">GO </option>
                            <option value="MA">MA </option>
                            <option value="MG">MG </option>
                            <option value="MS">MS </option>
                            <option value="MT">MT </option>
                            <option value="PA">PA </option>
                            <option value="PB">PB </option>
                            <option value="PE">PE </option>
                            <option value="PI">PI </option>
                            <option value="PR">PR </option>
                            <option value="RJ">RJ </option>
                            <option value="RN">RN </option>
                            <option value="RO">RO </option>
                            <option value="RR">RR </option>
                            <option value="RS">RS </option>
                            <option value="SC">SC </option>
                            <option value="SE">SE </option>
                            <option value="SP">SP </option>
                            <option value="TO">TO </option>
                            <option value="EX">EX </option>
                          </select>
                          </label></td>
                      </tr>
                      <tr>
                        <td align="right" height="25"><b>Reboque Sec:</b></td>
                        <td height="25"></td>
                        <td height="25"><input name="txt_rebSec" type="text" id="txt_rebSec" style="border: 1 solid #00923f; background-Color:'#CBE4CB';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black';valida(this.value);" onMouseDown="ddrivetip('Reboque Secundario', 300);" onMouseout="hideddrivetip();" value = "" size="8" maxlength="7" >
                          <strong>UF:</strong>
                          <label>
                          <select name="SelRebSec" id="SelRebSec" class="cmbCampo">
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AM">AM </option>
                            <option value="AP">AP </option>
                            <option value="BA">BA </option>
                            <option value="CE">CE </option>
                            <option value="DF">DF </option>
                            <option value="ES">ES </option>
                            <option value="GO">GO </option>
                            <option value="MA">MA </option>
                            <option value="MG">MG </option>
                            <option value="MS">MS </option>
                            <option value="MT">MT </option>
                            <option value="PA">PA </option>
                            <option value="PB">PB </option>
                            <option value="PE">PE </option>
                            <option value="PI">PI </option>
                            <option value="PR">PR </option>
                            <option value="RJ">RJ </option>
                            <option value="RN">RN </option>
                            <option value="RO">RO </option>
                            <option value="RR">RR </option>
                            <option value="RS">RS </option>
                            <option value="SC">SC </option>
                            <option value="SE">SE </option>
                            <option value="SP">SP </option>
                            <option value="TO">TO </option>
                            <option value="EX">EX </option>
                          </select>
                          </label></td>
                      </tr>
                      <tr>
                        <td align="right" height="25"><b>Cbt:</b></td>
                        <td height="25"></td>
                        <td height="25"><input name="txt_cbt" value = "00" type="text" id="txt_liq" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" size="10" onMouseDown="ddrivetip('C�digo Brasileiro de Tributa��o ', 300);" onMouseOut="hideddrivetip();"  ></td>
                      </tr>
                      <tr>
                        <td align="right" height="25"><b>Aliq ICMSF:</b></td>
                        <td height="25"></td>
                        <td height="25"><input name="txtAlqICMSF" type="text" id="txtAlaICMSF" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" value = "0" size="20" maxlength="10" onMouseDown="ddrivetip('Aliquota de icmsf  ', 300);" onMouseout="hideddrivetip();" ></td>
                      </tr>
                      <tr>
                        <td align="right" height="25"><b>Observa&ccedil;&atilde;o:</b></td>
                        <td height="25"></td>
                        <td height="25"><textarea name="txt_Obs" cols="35" id="txt_Obs" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" onMouseDown="ddrivetip('Observa��o ( Aparece no corpo da nota )  ', 300);" onMouseout="hideddrivetip();" ></textarea></td>
                      </tr>
                      <tr>
                        <td width="151" align="right" height="25"><b>NF m�e:</b></td>
                        <td width="12" height="25"> </td>
                        <!--onKeyPress="fHabNdo( this.value,1 );" onKeyDown="fHabNdo( this.value,1 );"-->
                        <td width="604" height="25">
						 <input name="txt_mae" type="text" id="txt_mae" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" onMouseDown="ddrivetip('C�digo da nota fiscal mae ', 300);" onMouseout="hideddrivetip();"  value="" size="7" maxlength="6" >
                          <b>NDO:</b>
					    <input name="txt_ndo" type="text" id="txt_ndo" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" value="" size="12" maxlength="10" onMouseDown="ddrivetip('Ndo da Nota s� pode ser digitado se existir nota fiscal m�e', 300);" onMouseout="hideddrivetip();" ></td>
                      </tr>
                      <tr>
                        <td width="151" align="right" height="25"><b>Peso Liq:</b></td>
                        <td width="12" height="25"> </td>
                        <td width="604" height="25"> <input name="txt_liq" value = "" type="text" id="txt_liq2" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" size="12" onMouseDown="ddrivetip('Peso Liquido ', 300);" onMouseout="hideddrivetip();" ></td>
                      </tr>
                      <tr>
                        <td width="151" align="right" height="23"><b>Peso Bruto:</b></td>
                        <td width="12" height="23"> </td>
                        <td width="604" height="23">
						<input name="txt_PesoBruto" value = "" type="text" id="txt_fone" style="border: 1 solid #00923f; background-Color:'white';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" size="12" onMouseDown="ddrivetip('Peso Bruto ', 300);" onMouseout="hideddrivetip();" >
						<input type="hidden" value="" name="txtProduto">
						<input type="hidden" value="" name="txtDeposito">
						<input type="hidden" value="" name="txtQtde">
						<input type="hidden" value="" name="txtSeqPrd">

						</td>
                      </tr>
                      <tr>

					    <td height="27" colspan="3" align="right"> <div align="right">
						  <input  name="btnAtual" type="button" class="btnAtual" id="btnAtual" onClick="atualiza();" value="Gerar Nfe">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('img','','../Figuras/btAtualizar2.jpg',1)"></a></div></td>
                      </tr>
                    </table>
 </table>
 </table>
</table>
</form>
</form>
</body>
</html>
<? ob_end_flush(); ?>
