<?php
// WebServices - iMasters.com.br
// Autor: Mauricio Reckziegel
// http://www.mauricioreckziegel.com
// Arquivo : server2.php
// Observações:
// Comentar a extensão php_soap
// Baixar o arquivo nusoap.php

// inclusão do arquivo de classes NuSOAP
require_once('nusoap.php');
// criação de uma instância do servidor
$server = new soap_server;
// inicializa o suporte a WSDL
$server->configureWSDL('server.hello','urn:server.hello');
$server->wsdl->schemaTargetNamespace = 'urn:server.hello';
// registra o método a ser oferecido
$server->register('hello', //nome do método
array('name' => 'xsd:string'), //parâmetros de entrada
array('return' => 'xsd:string'), //parâmetros de saída
'urn:server.hello', //namespace
'urn:server.hello#hello', //soapaction
'rpc', //style
'encoded', //use
'Retorna o nome' //documentação do serviço
);

	function hello($name) {
		return 'Hello '.$name;
	}
// requisição para uso do serviço
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
