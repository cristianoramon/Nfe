<?php 
// WebServices - iMasters.com.br
// Autor: Mauricio Reckziegel
// http://www.mauricioreckziegel.com
// Arquivo : cliente.php
// Observações:
// Comentar a extensão php_soap
// Baixar o arquivo nusoap.php

// inclusão do arquivo de classes NuSOAP
require_once('nusoap.php');
// criação de uma instância do cliente
$client = new soapclient('http://caminho/server.php'); // ex.: http://localhost/imasters/nuSOAP/server.php
// chamada do método SOAP
$result = $client->call('hello',array('Mauricio Reckziegel'));
// exibe o resultado
print_r($result);

// OPCIONAL : exibe a requisição e a resposta

/*

echo '<h2>Requisição</h2>';
echo '<pre>'.htmlspecialchars($client->request).'</pre>';
echo '<h2>Resposta</h2>';
echo '<pre>'.htmlspecialchars($client->response).'</pre>';
// Exibe mensagens para debug
echo '<h2>Debug</h2>';
echo '<pre>'.htmlspecialchars($client->debug_str).'</pre>';

*/
?>
