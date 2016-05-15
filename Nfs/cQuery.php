<?php
 require("cValorXml.php");
 
/********************************************************************
 *
 *
 ********************************************************************/

class cQuery extends cValorXml {

  /*****
   *
   *****/
  function __construct( $usu, $sen, $banco,$tipo ) {
	    
	    parent::__construct( $usu, $sen, $banco,$tipo );
  }
  
  
  /*********
   *
   ********/
  function getConexao(){
     return parent::getConexao();
  }
	  
   /******
    *
	******/
  public function fSelect( $param ,$param2,$param3) {
     
	 $sel = "SELECT " . parent::ValorXml("//SelCo") . " FROM " . parent::ValorXml("//SelFrom") . " WHERE " .
	         parent::ValorXml("//SelWhereC") . parent::ValorXml("//SelWhereParam") . " ='$param'".
			 parent::ValorXml("//SelWhereParam1") . " ='$param2' " .
			 parent::ValorXml("//SelWhereParam2") . " ='". $param3  ."' " ;
     return $sel;
  }
  
  /******
   * Duplicata a receber
   ******/
   public function fSelectDuplicata( $filial ,$pedido,$serie,$fatura) {
     
	 $sel = "SELECT " .  parent::ValorXml("//SelCo", "xml_esquema/NfsDuplicata.xml") . 
	        " FROM "  .  parent::ValorXml("//SelFrom","xml_esquema/NfsDuplicata.xml") .
		    " WHERE " .	 parent::ValorXml("//SelWhereParam1", "xml_esquema/NfsDuplicata.xml") . " ='$filial' " .
			" AND "   .  parent::ValorXml("//SelWhereParam2", "xml_esquema/NfsDuplicata.xml") . " ='$pedido' ".
			" AND "   .  parent::ValorXml("//SelWhereParam3", "xml_esquema/NfsDuplicata.xml") . " ='$serie' ".
			" AND "   .	 parent::ValorXml("//SelWhereParam4", "xml_esquema/NfsDuplicata.xml") . " ='$fatura' ".
		" Order by "  .	 parent::ValorXml("//orderBy", "xml_esquema/NfsDuplicata.xml")  ; 
     return $sel;
  }
  
  /************
   *
   ***********/
  public function execSql( $nf,$serie ,$param3){
       
	   echo  parent::getConsulta( $this->fSelect( $nf , $serie ,$param3) );
  }
  
  /************
   *
   ***********/
  public function execSqlDup( $filial ,$pedido,$serie,$fatura ){
       
	   echo  parent::getConsulta( $this->fSelectDuplicata( $filial ,$pedido,$serie,$fatura) );
  } 
  
   
  
  /************
   *
   ***********/
  public function getOcifetch(){
       
	   return  parent::getFetch( );
  }  
  
  /************
   *
   ************/
   public function getStat(){
       
	   return  parent::getStat( );
  }  
  
  /************
   *
   ************/
   public function getCampo($campo) {
	   return parent::getCampo($campo);
   }       
}
?>