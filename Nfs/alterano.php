<?php
	$_dom = new DomDocument();
	//$_dom->load('exemplo.xml');
	$_dom->load('xml/teste3.xml');	
	
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
	nos($_dom);		 
	

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
	
	$_novo = $_dom->createElement("xNome","Maiotese/2001");
	altera_no($_dom,$_novo,"id","fisco");
	
	$_dom->save('teste4.xml');
	/*$_novo = $_dom->createElement("paginas","999");
	altera_no($_dom,$_novo,"id","0002"); 
	
	$_novo = $_dom->createElement("isbn");
	altera_no($_dom,$_novo,"id","0002",TRUE); 	
	
	$_novo = $_dom->createElement("isbn","0521785197");
	if(altera_no($_dom,$_novo,"id","0001",FALSE,TRUE)) {
		$_novo->setAttribute("tipo","A"); 	
		$_novo->setAttribute("ordem","1"); 			
		$_novo->removeAttribute("tipo");
	}	
	*/
	echo "<br/><br/>Documento Alterado<br/><br/>";
	nos($_dom);
	
	
?>
