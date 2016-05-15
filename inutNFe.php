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

function atualiza(){

 if ( document.form.txt_nfe.value.length < 1 ) 
   return false;
   
 if ( document.form.txt_just.value.length < 15 ) {
   alert(' Valor Limite 15 ');
   return false;  
 }  
   
 document.getElementById('btnAtual').disabled = true;
 document.form.submit(); 
 
}

/***********************************************
* Cool DHTML tooltip script II- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
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
<body align="center" bgcolor="white" onLoad="fHabNdo( '',0 );">
<form action="AtualNotaInut.php" name="form" method="post">
      
  <table border="0" width="96%" cellspacing="0" cellpadding="0" height="1">
    <tr>
          <td width="1%" valign="top" height="1"></td>
            <p align="center" class="body"><br>
           </p>
           <td>
          </td>
          <td width="99%" valign="top" height="1">
            <table border="0" width="100%" cellspacing="0" cellpadding="0" height="247">
          <tr>
                
            <td width="100%" height="121" align="center" background="../Figuras/NfSaidaTopo1.jpg" style="background-repeat: no-repeat;"></td>
              </tr>
              <tr>
          <td height="25" colspan="4" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo"> 
          <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Gerar Nota de Saida</div></td>      </tr>              

              <tr>
                <td width="100%" align="center" height="81">
                  <table border="0" width="103%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="82%">
                        <table width="616" height="142" border="0" align="left" cellpadding="0" cellspacing="0">
                          <tr class="body">
                            <td height="22" align="right" valign="top"><b>Numero Ini NFe:</b></td>
                            <td height="22">                          
                            <td height="22"><input type="text" name="txt_nfe" value = "" size="12" style="border: 1 solid #00923f; background-Color:'#CBE4CB';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" onMouseDown="ddrivetip('N&uacute;mero do Pedido', 300);" onMouseout="hideddrivetip();" >                                                    </tr>
                          <tr class="body">
                            <td height="22" align="right" valign="top"><b>Numero Final NFe:</b></td>
                            <td height="22">                          
                            <td height="22"><input type="text" name="txt_nfe_final" value = "" size="12" style="border: 1 solid #00923f; background-Color:'#CBE4CB';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" onMouseDown="ddrivetip('N&uacute;mero do Pedido', 300);" onMouseout="hideddrivetip();" >                                                    </tr>
                          <tr class="body">
                            <td height="22" align="right" valign="top"><b>Justificativa NFe:</b></td>
                            <td height="22">                          
                          <td height="22"><textarea name="txt_just" cols="50" rows="10" id="txt_just" style="border: 1 solid #00923f; background-Color:'#CBE4CB';" onFocus="style.backgroundColor='#CBE4CB'; style.color='black'" onBlur="style.backgroundColor='white'; style.color='black'" onMouseDown="ddrivetip('N&uacute;mero do Pedido', 300);" onMouseOut="hideddrivetip();"></textarea>                          </tr>
                          <tr class="body">
                            <td width="151" height="22" align="right" valign="top">&nbsp;</td>
                            <td width="14" height="22">                          
                          <td width="451" height="22">                          </tr>
                          <tr>
                            <td height="27" colspan="3" align="right" valign="top">&nbsp;</td>
                          </tr>
                          <tr> 
                        
					    <td height="27" colspan="3" align="right" valign="top"> <div align="right">
						  <input  name="btnAtual" type="button" class="btnAtual" id="btnAtual" onClick="atualiza();" value="Atualizar">
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