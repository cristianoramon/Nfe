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
	  public function __construct( $arq ,$tipo=0){
	  
	     $this->dom   =  new DomDocument();
		 
		 if ( $tipo == 0  )
	      $this->novo  =  $this->dom->load($arq);
		 else 
		  $this->novo  =  $this->dom->loadXML($arq);
	  }
	  
	  
	  public function InsertElementSemAtributo($elemento,$tag,$uf,$index,$elementoPrinc,$VariosElemento){
	    
		 if ( (int) $VariosElemento == 0 ){
		   $elementoPrinc =  $elemento;
		   $posicao = 0;
		 
		 }
		 
		 if ( (int) $VariosElemento == 1 ) {
		     $posicao = $index-1;
		 }
		 
		 if ( (int) $VariosElemento == 2 ) {
		     $posicao = $index-1;
			 $elementoPrinc = $tag;
		 }
		 
		 
		 //echo " <br> Elemento ".$tag.'-'.$uf.'-'.$posicao;  
		 //echo " <br>Elemeno ".  $elementoPrinc." - ".$VariosElemento;  
		 //echo " <br> Id ". $this->dom->getElementById($elementoPrinc)->tagName;
		 
		 $percurso = $this->dom->getElementsByTagName($elementoPrinc)->item($posicao);
         $elemento = $this->dom->createElement($tag,$uf);
         $percurso->appendChild($elemento);
		 
	  
	  }
	  
	    /***
	    * Removendo o atributo
		****/
	   public function AlterarAtrr($elemento,$tag){
	    
		 $percurso = $this->dom->getElementsByTagName($elemento)->item(0);
         $percurso->setAttribute($tag,$uf);

	  
	  }
	  
	  
	  /***
	    * Alterando o atributo
		****/
	   public function RemoveAttr($elemento,$tag,$id){
	    
		 $percurso = $this->dom->getElementsByTagName($elemento)->item($id-1);
         $percurso->removeAttribute($tag);
	  
	  }
	  
	  
	   /*********************
	   * A Crescenta a elemento no Xml com atributo
	   *********************/
	  public function InsertElementAtrr($elemento,$tag,$uf,$atrr,$val){
	     
		 
	     $percurso = $this->dom->getElementsByTagName($elemento)->item(0);
         $elemento = $this->dom->createElement($tag,$uf);
         $percurso->appendChild($elemento);
		 
		 $attr = $this->dom->createAttribute($atrr);
		 $elemento->appendChild($attr);
		 
		 $AttrValue = $this->dom->createTextNode($val);
		 $attr->appendChild($AttrValue);
		 
	  }
	  
	  /*********************
	   * A Crescenta a elemento no Xml
	   *********************/
	  public function InsertElement($elemento,$tag,$uf,$attr,$val){
	     
		 
	     $percurso = $this->dom->getElementsByTagName($elemento);
         $elemento = $this->dom->createElement($tag,$uf);
		 
		 //$percurso2 = $this->dom->getElementsByTagName($elemento);
		 foreach($percurso as $filho) {
		   if( $filho->hasAttributes() ) {
			  foreach($filho->attributes as $_att) {
				  if($_att->nodeName==$attr && $_att->textContent==$val) {
				    $filho->appendChild($elemento);
				 
				    echo "<br>Alterado o atributo ". $_att->nodeName . "-"	.$_att->textContent;	
					echo "<br>tags " . $filho->nodeName ."==".$tag;
				  }
			  }
		   }
		  } 
         //$percurso->appendChild($elemento);
		 
	  }
	  
	 /****************************  
	 *
	 *  setAtributo = 0 aterar apenas valor do no
	 *  setAtributo = 1 alterar somente o atributo 
	 ****************************/
	 public function setNoValor($campo,$valor,$atrib,$atribValue,$_exc=FALSE,$_inc=FALSE,$_attributo,$setAtributo){
	   
	    $this->novo = $this->dom->createElement($campo,$valor);
		
		if ( $setAtributo == 0 ) 
		  $this->alteraNF($this->dom,$this->novo,$atrib,$atribValue,$_exc,$_inc,$_attributo);
		else 
		  $this->altera_attr($this->dom,$this->novo,$atrib,$atribValue,$_exc,$_inc,$_attributo, $setAtrib);   
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
	
	// Alterar o documento
	function alteraNF($_no,$_novo,$_satt,$_svlr,$_exc=FALSE,$_inc=FALSE,$_attributo) {
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
						  if( $_attributo == 1 ) { 
						   $_filho->removeAttribute("id");
						  }	
							if($_inc===TRUE) {
								$_filho->appendChild($_novo);
								return TRUE;
							}
							$_ok=TRUE;
						}
					}
			}
			if($_filho->nodeType!=3) {
				$_r = $this->alteraNF($_filho,$_novo,$_satt,$_svlr,$_exc,$_inc,$_attributo);
				if($_r===TRUE) {
					return TRUE;
				}
			}
		}
	}
	
	
      
	 /******************************
	  *
	  *
	  ******************************/ 
	// Alterar o documento
	function altera_no($_no,$_novo,$_satt,$_svlr,$_exc=FALSE,$_inc=FALSE,$_attributo) {
	
		static $_ok=FALSE;
		
		foreach($_no->childNodes as $_filho) {
		
		  echo $_filho;
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
						   
						   if ( $_attributo == 1 ) { 
						     $_filho->removeAttribute("id");
						   }
							if($_inc===TRUE) {
								$_filho->appendChild($_novo);
								return TRUE;
							}
							$_ok=TRUE;
						}
					}
			}
			$_r = $this->altera_no($_filho,$_novo,$_satt,$_svlr,$_exc,$_inc,$_attributo);
			
			if($_filho->nodeType!=3) {
				$_r = $this->altera_no($_filho,$_novo,$_satt,$_svlr,$_exc,$_inc,$_attributo);
				if($_r===TRUE) {
					return TRUE;
				}
			}
		}
	}
	
	
	//Remove o no 
	function remove_no($_no,$_novo,$_satt,$_svlr,$_exc=FALSE,$_inc=FALSE,$_attributo) {
	
		static $_ok=FALSE;
		
		foreach($_no->childNodes as $_filho) {
		
		  echo $_filho;
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
						   
						   if ( $_attributo == 1 ) { 
						     $_filho->removeAttribute("id");
						   }
							if($_inc===TRUE) {
								$_filho->appendChild($_novo);
								return TRUE;
							}
							$_ok=TRUE;
						}
					}
			}
			$_r = $this->altera_no($_filho,$_novo,$_satt,$_svlr,$_exc,$_inc,$_attributo);
			
			if($_filho->nodeType!=3) {
				$_r = $this->altera_no($_filho,$_novo,$_satt,$_svlr,$_exc,$_inc,$_attributo);
				if($_r===TRUE) {
					return TRUE;
				}
			}
		}
	}
	
	 /******************************
	  *
	  *
	  ******************************/ 
	// Alterar o Atributos
	function altera_attr($_no,$_novo,$_satt,$_svlr,$_exc=FALSE,$_inc=FALSE,$_attributo,$setAtributo) {
	
	 
		static $_ok=FALSE;
		
		//$this->novo = $this->dom->createElement($_no,$_novo);
		
	    foreach($_no->childNodes as $_filho) {
		
		    echo "<br><font color='red'> " . $_filho . "=".  $_novo->nodeName ."</font>";
		  //if ( if ( $_filho->nodeName == $_novo->nodeName ) {
			if($_filho->hasAttributes()) {
			     
					foreach($_filho->attributes as $_att) {
						  
						 
						   if ( $_att->nodeName == $_satt ) 
						      $_filho->setAttribute($_satt,$_svlr);
							 
						   
						 
					}
					
			 }
			
			 if($_filho->nodeType!=3) {
				  $_r = $this->altera_attr($_filho,$_novo,$_satt,$_svlr,$_exc,$_inc,$_attributo, $setAtrib);
				  if($_r===TRUE) {
					  return TRUE;
				  }
			 }
		 //}	
	    }
		
	 
	}
	
	 
	 /****************************
	 *
	 ****************************/
	 public function save($arq){
	 
	   $this->dom->formatOutput= TRUE;
	   $this->dom->save($arq);
	  
	   //$this->dom->flush();
	 }
	 
	  /****************************
	 *
	 ****************************/
	 public function saveXML(){
	   $this->dom->formatOutput= TRUE;
	   return $this->dom->saveXML();
	   //$this->dom->flush();
	 }
		
   }
?>