<?

  /*********************************************************************************************
   *  Clase que contem o TNSNAME necessario para conexao
   *
   *
	********************************************************************************************/
   class cTnsName {


     /******************************************************************************************
	  *
	  *
	  ******************************************************************************************/
      function fTnsNames($tnsName) {


        $CRPAAA ='(DESCRIPTION =
        (ADDRESS_LIST =
        (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.226.139)(PORT = 1521))
        )
        (CONNECT_DATA =
        (SID = prod01)
        )
        )';


        $DBTESTE ='(DESCRIPTION =
        (ADDRESS_LIST =
        (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.226.3)(PORT = 1521))
        )
        (CONNECT_DATA =
        (SID = dbteste)
        )
        )';


       $NT = '( DESCRIPTION =
            (ADDRESS_LIST =
            (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.226.66)(PORT = 1521))
            )
            (CONNECT_DATA =
            (SERVICE_NAME = NT)
            )
            )';



     $JAIME = '(DESCRIPTION =
              (ADDRESS_LIST =
              (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.226.117)(PORT = 1521))
              )
              (CONNECT_DATA =
              (SID = LOCAL)
              )
              )';

	$RESERVA = '(DESCRIPTION =
               (ADDRESS_LIST =
               (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.226.176)(PORT = 1521))
               )
               (CONNECT_DATA =
               (SID = RESERVA)
               )
               )';


   $JAIME =' (DESCRIPTION =
             (ADDRESS_LIST =
             (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.226.117)(PORT = 1521))
           )
           (CONNECT_DATA =
           (SID = LOCAL)
           )
           )';

	$IBERIA ='(DESCRIPTION =
             (ADDRESS_LIST =
             (ADDRESS = (PROTOCOL = TCP)(HOST =  192.168.5.5)(PORT = 1521))
              )
              (CONNECT_DATA =
              (SID = ibsora)
              )
              )';


	    switch ( strtoupper($tnsName) ) {

		  case "CRPAAA" :
		     return $CRPAAA;
			 break;

		  case "NT" :
		     return $NT;
			 break;

		  case "JAIME_TESTE" :
		     return $JAIME;
			 break;

		  case "RESERVA" :
		     return $RESERVA;
			 break;

		 case "JAIME" :
		     return $JAIME;
			 break;

		case "DBTESTE" :
		     return $DBTESTE;
			 break;

		case "IBERIA" :
		     return $IBERIA;
			 break;

		 default :
		     return "Sem TNSNAME";
			 break;
	   }
	  }

   }
?>
