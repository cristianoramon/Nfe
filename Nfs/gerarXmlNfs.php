<?php
   /**************************************************
    *
	*
	*
	************************************************/
	 
   class cGerarXmlNfs {
   
      protected $dom;
	  protected $novo;
	  
	  /**********
	   *
	   *
	   **********/
	  public function __construct( $arq ){
	  
	     $this->dom   =  new DomDocument();
	     $this->novo =  $this->dom->load($arq);
	  }
	  
	    
      /***************
	   *
	   ***************/
      function nos($_no,$_n=0) {	 
		foreach($_no->childNodes as $_filho) {
			if($_filho->nodeType!=3) {
				if($_n!=0) { echo "<br/>";}
				echo str_repeat("&nbsp;",$_n*5);
				echo "{$_filho->nodeName}";	 
				if($_filho->hasAttributes()) {	
					foreach($_filho->attributes as $_att) {
						echo " ({$_att->nodeName} = {$_att->textContent}) ";
					}
				}
			}
			if($_no->nodeType==1 AND $_filho->nodeType==3) { 
				if(trim($_filho->textContent)!="") {
					echo " = " . trim($_filho->textContent);
				}
			} else {  
				nos($_filho,$_n+1,$_dom);	
			}
		}
	}
	
	// Documento original
	//nos($_dom);		 
	
      
	 /******************************
	  *
	  *
	  ******************************/ 
	// Alterar o documento
	function altera_no($_no,$_novo,$_satt,$_svlr,$_exc=FALSE,$_inc=FALSE) {
		static $_ok=FALSE;
		foreach($_no->childNodes as $_filho) {
		  //echo $_filho;
			if($_ok==TRUE&&$_filho->nodeName==$_novo->nodeName) { 
				if($_exc===TRUE) {
					$_no->removeChild($_filho);
				} else {
					$_no->replaceChild($_novo,$_filho);
				}
				$_ok=FALSE;
				return TRUE;
			}
			if($_filho->hasAttributes()) {
					foreach($_filho->attributes as $_att) {
						if($_att->nodeName==$_satt && $_att->textContent==$_svlr) {
						   $_filho->removeAttribute("id");
							if($_inc===TRUE) {
								$_filho->appendChild($_novo);
								return TRUE;
							}
							$_ok=TRUE;
						}
					}
			}
			if($_filho->nodeType!=3) {
				$_r = altera_no($_filho,$_novo,$_satt,$_svlr,$_exc,$_inc);
				if($_r===TRUE) {
					return TRUE;
				}
			}
		}
	}
	
	
	/****************************
	 *
	 ****************************/
	 public function setNoValor($campo,$valor){
	    $this->novo = $this->dom->createElement($campo,valor);
	 }
	 
	 /****************************
	 *
	 ****************************/
	 public function setNoValor($campo,$valor){
	    $_novo = $_dom->createElement($campo,valor);
	 }
	
   }
?>