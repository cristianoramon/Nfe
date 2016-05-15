<HTML>
<HEAD>
 <TITLE>Documento PHP</TITLE>
</HEAD>
<BODY>
<?
   //Verifica Conexao
 require_once("../xmlSign/cVerConexao.php");
 
 //  $ArqXml="../xml_esquema/NfsAutorizacao.xml";
   //$_xml = simplexml_load_file("../xml_esquema/NfsAutorizacao.xml");
   //$_xml= file_get_contents($ArqXml);
   
   
   $verConn  = new cVerConexao();
    echo "Conexao -> " .  $verConn->fVerificaConexaoSSL("homologacao.nfe.sefazvirtual.rs.gov.br/ws/nferecepcao/NfeRecepcao.asmx" ,'c:\\xamppWeb\htdocs\xmlSign\crpaaa.pem') ;
    //$boolConexao = true;

?>
</BODY>
</HTML>
