<? session_start(); ?>
<script language="JavaScript" src="crossbrowser.js" type="text/javascript">
</script>
<script language="JavaScript" src="outlook.js" type="text/javascript">
</script>

<SCRIPT>

// ---------------------------------------------------------------------------
// Example of howto: use Outlook Like Bar
// ---------------------------------------------------------------------------


  //create OutlookBar-Object:
  //              Name
  //              x-position
  //              y-Position
  //              width
  //              height
  //              background-color
  //              page background-color (needed for OP5)
  //(screenSize object is created by 'crossbrowser.js')
  //

//  var o = new createOutlookBar('Bar',0,0,screenSize.width,screenSize.height,'#606060','white') // OutlookBar
 //##EFEFE7
  var o = new createOutlookBar('Bar',0,0,127,screenSize.height,'','black') // OutlookBar
  var p

  //create first panel..
  p = new createPanel('al','NF Eletronica');

  //add buttons:
  //             image name
  //             label text
  //             onClick JavaScript code
  //

  p.addButton('../Figuras/ebookonline.gif','Gerar Nota','javascript:parent.main.location.href="GerarNfSaida.php" ');
  //p.addButton('../Figuras/word.gif','Nota Complemento','javascript:parent.main.location.href="GerarNfSaida.php" ');
 // p.addButton('../Figuras/word.gif','Gerar Nota','javascript:parent.main.location.href="GerarNfSaida.php" ');
  o.addPanel(p);

  <?
  //create second panel...

  if ( $_SESSION['login'] == "VERA" ) {
  
   echo "p = new createPanel('p','Cancelamento(s)'); ";
  
   echo "p.addButton('../Figuras/word.gif','Canc Nfe','javascript:parent.main.location.href=\"canNFe.php\" ');";
  
   echo "o.addPanel(p);";
 }
 
 ?>
  //create two empty panels...
  p = new createPanel('p','Status');
  p.addButton('../Figuras/prefstatus.gif','Geral','javascript:parent.main.location.href="status/StatusGeral.php"');
  //p.addButton('../Figuras/baway.gif','Aprovada','javascript:parent.main.location.href="status/StatusGeralAprovada.php"');
  //p.addButton('../Figuras/bbusy.gif','Rejeitada','javascript:parent.main.location.href="status/StatusGeralNegada.php" ');
  o.addPanel(p);
  
    //create two empty panels...
  p = new createPanel('p','Visualização  Nfe');
  p.addButton('../Figuras/status.jpg','Nfe','javascript:parent.main.location.href="Relatorio/VisualizacaoDanfe.php"');
  o.addPanel(p);

    //create two empty panels... VisualizacaoDanfeContig
  p = new createPanel('p','Status Servidor');
  p.addButton('../Figuras/status.jpg','Nfe','javascript:parent.main.location.href="status/StatusWebServ.php"');
  o.addPanel(p);

  //create two empty panels... VisualizacaoDanfeContig
  p = new createPanel('p','Nfs Contigencia');
  p.addButton('../Figuras/status.jpg','Nfe','javascript:parent.main.location.href="Relatorio/VisualizacaoDanfeContigencia.php"');
  o.addPanel(p);
  
   //create two empty panels... VisualizacaoDanfeContig
  //p = new createPanel('p','Renvio de Notas');
 // p.addButton('../Figuras/status.jpg','Nfe','javascript:parent.main.location.href="RenviodeNota.php"');
 // o.addPanel(p);
  
   //create two empty panels... VisualizacaoDanfeContig
<?
 if ( $_SESSION['login'] == "VERA") {
 
   echo "p = new createPanel('p','Inutil. Numera Nfe'); ";

   echo "p.addButton('../Figuras/word.gif','Inutil Nfe','javascript:parent.main.location.href=\"inutNfe.php\" ');";

   echo "o.addPanel(p);";
}
 ?>
  //create two empty panels... VisualizacaoDanfeContig
 // p = new createPanel('p','Nfe Problema Conexao');
 // p.addButton('../Figuras/status.jpg','Nfe','javascript:parent.main.location.href="status/StatusGeralErro.php"');
 // o.addPanel(p);
  
  //p = new createPanel('p','Contigencia Manual');
  //p.addButton('../Figuras/word.gif','Entrar Contigencia','javascript:parent.main.location.href="manuContigencia/entrarContigencia.php"');
  //p.addButton('../Figuras/word.gif','Sair Contigencia','javascript:parent.main.location.href="manuContigencia/sairContigencia.php"');
 // o.addPanel(p); 
  
  p = new createPanel('p','Dist Arquivo');
  p.addButton('../Figuras/word.gif','Dist Arquivo','javascript:parent.main.location.href="DistrXML/DistrXML.php"')
  o.addPanel(p); 
 
 
  
//  p = new createPanel('l2','Leeres Panel 2');
//  o.addPanel(p);

  o.draw();         //draw the Outlook Like Bar!


//-----------------------------------------------------------------------------
//functions to manage window resize
//-----------------------------------------------------------------------------
//resize OP5 (test screenSize every 100ms)
function resize_op5() {
  if (bt.op5) {
    o.showPanel(o.aktPanel);
    var s = new createPageSize();
    if ((screenSize.width!=s.width) || (screenSize.height!=s.height)) {
      screenSize=new createPageSize();
      //need setTimeout or resize on window-maximize will not work correct!
      //benötige das setTimeout oder das Maximieren funktioniert nicht richtig
      setTimeout("o.resize(0,0,screenSize.width,screenSize.height)",100);
    }
    setTimeout("resize_op5()",100);
  }
}

//resize IE & NS (onResize event!)
function myOnResize() {
  if (bt.ie4 || bt.ie5 || bt.ns5) {
    var s=new createPageSize();
    o.resize(0,0,s.width,s.height);
  }
  else
    if (bt.ns4) location.reload();
}

</SCRIPT>

</head>
<!-- need an onResize event to redraw outlookbar after pagesize changes! -->
<!-- OP5 does not support onResize event! use setTimeout every 100ms -->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style><body onLoad="resize_op5();" onResize="myOnResize();">
</body>
</html>
