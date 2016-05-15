<?php
/******************************************************************
 *
 *
 *****************************************************************/


 require_once("cGerarXmlNfs.php");

 $xmlGerarNf = new cGerarXmlNfs("Autorizacao.xml");


 $xmlGerarNf->setNoValor("xNome","TESTE ","id","fisco");

 $xmlGerarNf->save("NfsCorreto.xml");

?>
