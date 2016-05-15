<?php
// WebServices - iMasters.com.br
// Autor: Mauricio Reckziegel
// http://www.mauricioreckziegel.com
// Arquivo : server.php
// Observações:
// Comentar a extensão php_soap
// Baixar o arquivo nusoap.php

// inclusão do arquivo de classes NuSOAP
require_once('nusoap.php');
// criação de uma instância do servidor
$server = new soap_server;
// registro do método
$server->register('hello');
// definição do método como uma função do PHP
	function hello($name) {
		return 'Hello '.$name;
	}
// requisição para uso do serviço
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ?
$HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>

