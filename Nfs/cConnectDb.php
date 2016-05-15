<?php
    /***************************************************************
	 *
	 *
	 ***************************************************************/
	 
   class cConnectDb {
     
     protected  $usuario = " ";
	 protected  $senha = " ";
	 protected  $bd = " ";
	 protected  $tipoBanco = "Oracle";
	 protected  $conn = null;
	 protected  $tipo = 0;
	  
	  /****************************
	   *
	   ****************************/
	 function __construct( $usu, $sen, $banco,$tipo ) {
	    //echo "<font color=red>$usu</font>";
		$this->usuario = $usu;
	    $this->senha = $sen ;
		$this->bd = $banco;
		
		//Tipo de Sgdb
		switch( $tipo ) {
		  case 'Oracle' :
		                  $this->tipo = 0;
						  $this->conexao();
						  break; 
		}
		
	 }
       
      /**************************************************************
	   *
	   **************************************************************/
	  public function conexao() {
	     		 
		 if ( strlen($this->usuario) > 3 &&  strlen($this->senha) > 3 && strlen($this->bd) > 1  ) {
		 	
		   //Tipo de sgbd
		   switch(  $this->tipo ) {
		      case 0 : 
			           $this->conn = ocilogon($this->usuario, $this->senha,$this->bd ) ;
				       if ( $this->conn == false ) {
						    $this->conn = null;
						
					   } 
			            break;
		   }
		 } 
	    
	  }
   
     /***********************
	  *
	  ***********************/
	  public function getConn() {
	    return $this->conn;
	  }
	  
	  /**********************
	   *
	   **********************/
	   public function getTipoCon(){
	     return $this->tipo;
	   }
	   
	   /**********************
	   *
	   **********************/
	   public function Close(){
	    OCILogoff($this->conn);
	   }
   }
?>
