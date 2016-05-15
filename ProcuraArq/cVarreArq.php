<?

 /***********************************************************************************************
  *
  *
  *
  **********************************************************************************************/
  
  class cVarreArq {
  
  /*********************************************************************************************
   *
   *********************************************************************************************/
   
   function fVarrArq($dir,$filtro="",$nivel="",$verArq=0){
      
	 $qt = 0;
     $diraberto = opendir($dir); // Abre o diretorio especificado
     chdir($dir); // Muda o diretorio atual p/ o especificado
	 
     while($arq = readdir($diraberto)) { // Le o conteudo do arquivo
	 
         if($arq == ".." || $arq == ".")continue; // Desconsidera os diretorios
           $arr_ext = explode(";",$filtro);
		   
           foreach($arr_ext as $ext) {
		   
             $extpos = (strtolower(substr($arq,strlen($arq)-strlen($ext)))) == strtolower($ext);
             if ($extpos == strlen($arq) and is_file($arq)) { // Verifica se o arquivo é igual ao filtro
			 
              if ( $verArq == 0 ) {
         	   if ( (date("d/m/Y",filemtime($arq)) == date("d/m/Y"))) {

				$arqNome[$qt] = $nivel.$arq;
				$qt = $qt + 1;

			   }
			  }  else {
			      $arqNome[$qt] = $nivel.$arq;
				  $qt = $qt + 1;

				}
            }
         }
		 
		/* 
        if (is_dir($arq)) {
            echo $nivel.$arq.' - '.filemtime($arq)."<br>"; // Imprimi em forma de arvore
            varre($arq,$filtro,$nivel."----"); // Executa a funcao novamente se subdiretorio
        }*/
    }
	
    chdir(".."); // Volta um diretorio
    closedir($diraberto); // Fecha o diretorio atual
	
	return $arqNome;
   }
  }
?>
