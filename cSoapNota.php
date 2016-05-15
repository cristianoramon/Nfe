<?

 require_once('nusoap/lib/nusoap.php');

  class cSoapNota {

	public function conexao($arqEnv,$arqRec,$usu,$pwd) {

	  //$usuario = "84554541487";
      //$senha  = "al84554";
	  $usuario = $usu;
      $senha  = $pwd;
	  //$fraseHash="12277646000108"."AL84554"."84554541487"."01102007"."380"."1";
	 // $hash = md5($fraseHash);

	  $senha = $pwd;

	  $str = 'http://200.141.128.77/WSPFUsinasTesteAutenticado/WSPFUsinasTesteAutenticado.asmx?wsdl';
	  //http://200.141.128.77/WSGeraPFUsinasTeste/WSGeraPFUsinasTeste.asmx?wsdl

	  $client = new SoapClient('http://200.141.128.77/WSPFUsinasAutenticado/WSPFUsinasAutenticado.asmx?wsdl',true,'192.168.226.233','3128','ramon','download');

      $authXml = "<Autenticacao xmlns=\"http://tempuri.org/WSPFUsinasAutenticado/WSPFUsinasAutenticado\"><usuario>".$usuario."</usuario><hash>".$senha."</hash></Autenticacao>";


	   echo "<br><font color=blue>".$authXml."</font></br>";

      $client->setHeaders($authXml);

	 // $client->setCredentials('84554541487','al84554');
	  $erro = $client->getError();


    echo "<h1>Numero $erro</h1><br>";

  if ( $erro )
    echo "Erro no construtor " .$erro;

  //'../Nfs/xml/CRPAAA229385.xml'
  $fh = fopen($arqEnv, 'r+') or die("Error!!");

 if ( $fh ) {
  while ( !feof($fh) ) {
    $buffer = fgets($fh, 4096);
	$buffer2 = $buffer2 . $buffer;
       echo '<br>sa'.$buffer.' sa <br>';
   }
   //fwrite($fh,$buffer);
   fclose($fh);
 }

 echo '<br>buffer'.$buffer2.'<br>';

  //$par = array('XMLEntrada'=>$xml2);
 $par = array('XMLEntrada'=>$buffer2);

  $result = $client->call("GeraPasse",array('parameters'=>$par));

 // echo "<br>".$client->faultstring."</br>";

  if ( $client->fault ) {
    echo "<h2>Falha na chamada do metedo </h2><pre>";
    print_r($result);
  }


   echo '<h2>Requisicao</h2>';
  echo '<pre>'.$client->request.'</pre>';

  echo '<h2>Resposta</h2>';
  echo '<pre>'.$client->response.'</pre>';

$xml = utf8_encode($result["GeraPasseResult"]);

    $fh = fopen($arqRec, 'w') or die("Error!!");
   fwrite($fh,$xml);

  }

 }
?>
