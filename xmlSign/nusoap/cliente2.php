<?php
// WebServices - iMasters.com.br
// Autor: Mauricio Reckziegel
// http://www.mauricioreckziegel.com
// Arquivo : cliente2.php
// Observações:
// Comentar a extensão php_soap
// Baixar o arquivo nusoap.php

require_once('nusoap.php');
// Definição da localização do arquivo WSDL
$wsdl = 'http://caminho/server2.php?wsdl'; // ex.: http://localhost/imasters/nuSOAP/server2.php?wsdl
// criação de uma instância do cliente
$client = new soapclient($wsdl, true);
// verifica se ocorreu erro na criação do objeto
$err = $client->getError();
if ($err){
	echo "Erro no construtor<pre>".$err."</pre>";
}
// chamada do método SOAP
$result = $client->call('hello',array('Mauricio Reckziegel')); 
// verifica se ocorreu falha na chamada do método
if ($client->fault){
	echo "Falha<pre>".print_r($result)."</pre>";
}else{
	// verifica se ocorreu erro
	$err = $client->getError();
	if ($err){
		echo "Erro<pre>".$err."</pre>";
	} else{
			print_r($result);
	}//end_else
}//end_else
?>

