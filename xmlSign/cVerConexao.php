<?
 /**************************************************************************************************
  *
  *
  **************************************************************************************************/
  
  class cVerConexao {
  
       function fVerificaConexaoSSL($url,$cert ) {
	   
	   
	      # working vars
		  $host = $url;
		  $service_uri = '';
		  //$local_cert_path = $cert;
		  $local_cert_path =  realpath($cert);
		  
		  ini_set('default_socket_timeout',  120); 
		  //$local_cert_path = "c:\\xamppWeb\htdocs\xmlSign\crpaaa.pem";
	      $local_cert_passphrase = 'serasa';
		  //echo " <br> PathConexao ". realpath( $cert ) .'-';
		  $request_data = '';

		  # array with the options to create stream context
		  $opts = Array();

		 # compose HTTP request header
		 $header = "Host: $host\\r\\n";
		 $header .= "User-Agent: PHP Script\\r\\n";
		 $header .= "Content-Type: text/xml\\r\\n";
		 $header .= "Content-Length: ".strlen($request_data)."\\r\\n";
	     $header .= "Connection: close";
		               

	     # define context options for HTTP request (use 'http' index, NOT 'httpS')
		 $opts['https']['method'] = 'POST';
		 $opts['https']['header'] = $header;
		 $opts['https']['content'] = $request_data;
		 $opts['https']['timeout'] = 120;

		# define context options for SSL transport
		$opts['ssl']['local_cert'] = $local_cert_path;
		$opts['ssl']['passphrase'] = $local_cert_passphrase;

		# create stream context
		$context = stream_context_create($opts);

		# POST request and get response
		$filename = 'https://'.$host.$service_uri;
		$content = @file($filename, false, $context);

        
        //echo ' <br>-- l '.$filename.' l --- '; 
		if ( ! $content ) return 1;
		
		return 0;
    }
 }
?>
