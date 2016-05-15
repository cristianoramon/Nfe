<?

class CValorNota{

/************************************
 * Retorna a expressao para calcular o valor
 ***********************************/
 function Valor ($valor){


 $ValE = explode(" ", $valor);
 $qt = count($ValE);


  //echo "Qt $qt";
  for ( $c = 0 ; $c < $qt ; $c++ ){

    if  ( ( trim($ValE[$c]) == "(") || ( trim($ValE[$c]) == "-") || ( trim($ValE[$c]) == "*") || ( trim($ValE[$c]) == "-") || ( trim($ValE[$c]) == ")") || ( trim($ValE[$c]) == "/") || ( trim($ValE[$c]) == "+")) {
      $ValE[$c] = $ValE[$c];
    }
     else {

        if (  ereg("[a-zA-Z]",trim($ValE[$c]))   )
          $ValE[$c] = "$".$ValE[$c];

      }

    $str2 = $str2.  $ValE[$c];

  }
  return $str2;
 }
}



?>

