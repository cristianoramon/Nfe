<?php
  require("cConnectDb.php");
  /*****************************
   *
   *
   *****************************/
   
   class  cConsultaSQL extends cConnectDb {
   
      protected  $ora_fetch = null;
	  protected  $stat = null;
	  protected  $tipoConex;
	  
      /****************************
	   *
	   ****************************/
	 function __construct( $usu, $sen, $banco,$tipo ) {
	    
	    parent::__construct( $usu, $sen, $banco,$tipo );
	 }
	 
	 /********************
	  *
	  *********************/
	function execQuery( $sql ) {
	   
	   $this->tipoConex = parent::getTipoCon();
	   
	   switch ( $tipoConex ) {
	     case 0 : 
		           $this->stat =  OCIParse(parent::getConn(), $sql); 
				   
				   if (  $this->stat ==  false )
				    return "erro no parse";
					
					$query = OCIExecute(  $this->stat );
					
					if ($query == false )
					 return "erro no quey";
					
					$this->ora_fetch = $this->oracle_fetch( $this->stat );
					return 0;
	   }
	}
	
	/***********
	 *
	 ***********/ 
	 public function oracle_fetch($stat) {
	    return OCIFetch($stat);
	 } 
	 
	 /***********
	 *
	 ***********/ 
	 public function getFetch() {
	    OCIExecute(  $this->stat );
		$this->ora_fetch = OCIFetch($this->stat); 
	    return $this->ora_fetch;
	 } 
	 
	/***********
	 *
	 ***********/ 
	 public function getStat() {
	    return $this->stat;
	 } 
	 
	 /***********
	 *
	 ***********/ 
	 public function getCampo($campo) {
	  
	  switch( $this->tipoConex ) {
	    case 0 : return OCIResult($this->stat,$campo);
		         break; 
	  } 
	    
	 } 
 }
 
?>
