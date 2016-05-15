<?php

  function fValorXml( $no , $qt = 0 ) {
    
	foreach( $no->children() as  $no1 => $no2){
	  
	   //$valor = $no2;
	    echo "<br> $no1 =  $no2 "; 
	
	}
	
	fValorXml( $no2 , $qt + 1 );
	
	//return $valor;
  } 
  
  
    $xml = simplexml_load_file('xml/Nfs2.xml');
	//fValorXml( $xml );
	
	
	$crit = "//SelCo1";
	$xmlXpath = $xml->xpath($crit);
	
	//echo $xmlXpath;

	if ( $xmlXpath !== FALSE ) {
	 echo "ds";
	foreach(  $xmlXpath as $v ) {
	   echo "ds";
	  $nc = $v->children();
	 
	  if (sizeof($nc[0]) == 0 )  {
	     echo "<br>eee</br>";
	  } else {
	        fValorXml( $nc );
	    } 
	  
	}
	}
	
	
	
  echo("<pre>");
 // var_dump($xml);
  echo("</pre>");
?>