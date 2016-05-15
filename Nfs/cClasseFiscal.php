<?php

   class cClasseFiscal {
   
   
      function fClasse ( $codClasse ) {
	    
	
          switch( strtoupper( $codClasse ) )  {
		  
    		case "A" :
	           $strNcm="17011100";  
	           break;
    		case "B" : 
	           $strNcm="17019900"; 
	           break;
    		case "C" :  
	           $strNcm="17029000";
	           break;
    		case "D" : 
	           $strNcm="17031000";  
	           break;
    		case "E" : 
	           $strNcm="39235000"; 
	           break;
    		case "F" : 
	           $strNcm="55092200"; 
	           break;
    		case "G" : 
	           $strNcm="63053310"; 
	           break;
    		case "H" :  
	           $strNcm="63053390";
	           break;
    		case "I" :  
	           $strNcm="22071000";
	           break;
	       	case "Z" :
	           $strNcm="87032290";
	           break;
            case "O" :
	           $strNcm="32041920";
	           break;
            case "M" :
	           $strNcm="17031000";
	           break;
			default :   
			   $strNcm=str_replace(".","",$codClasse);
	           break;  
  
  		}
	    
		return $strNcm;
		
	  }
   }
?>
