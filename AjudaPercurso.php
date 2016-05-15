<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Percurso</title>

<script language="javascript">

  function fPassaValor(){


	  ind = document.form.SelUf.selectedIndex;
	  tot = document.form.SelUf.length;

	  indPer = 0;

	  for( indPer; indPer < document.form.SelPercurso.length;indPer++) {

	    if ( document.form.SelPercurso.options[indPer].value == "" ) {
		// alert(indPer);
		 break;
		}

	  }

	  //alert( indPer);

	/* if ( indPer > 0 ) {
	   indPer = document.form.SelPercurso.selectedIndex+1;
	 } else {
	     indPer = 0;
	   }
	  */



	 // alert(ind+'  '+tot +'  '+ indPer);

	 // alert(document.form.SelUf.options[ind].text );

	  document.form.SelPercurso.options[indPer].text = document.form.SelUf.options[ind].text;
	  document.form.SelPercurso.options[indPer].value = document.form.SelUf.options[ind].value;
     // alert(document.form.SelPercurso.options[0].text  );
  }

  function fRetiraValor(){

	 indPer = document.form.SelPercurso.selectedIndex;

	 document.form.SelPercurso.options[indPer].text = "";
	 document.form.SelPercurso.options[indPer].value = "";

	 indPer = 0;

	 for( indPer; indPer < document.form.SelPercurso.length;indPer++) {

	    if ( document.form.SelPercurso.options[indPer].value == "" ) {
		 document.form.SelPercurso.options[indPer].text = document.form.SelPercurso.options[indPer+1].text;
	     document.form.SelPercurso.options[indPer].value = document.form.SelPercurso.options[indPer+1].value;
		 document.form.SelPercurso.options[indPer+1].text = "";
		 document.form.SelPercurso.options[indPer+1].value = "";
		// alert(indPer);

		}

	  }

  }

  function fPassaValMae(){

   indPer = 0;
   for( indPer; indPer < document.form.SelPercurso.length;indPer++) {

	    if ( document.form.SelPercurso.options[indPer].value != "" )
           window.opener.parent.frames.document.form.elements["uf"+indPer].value = document.form.SelPercurso.options[indPer].value ;
  }

 /* indPer = 0;
   for( indPer; indPer < document.form.SelPercurso.length;indPer++) {

	    if ( document.form.SelPercurso.options[indPer].value != "" )
           alert(window.opener.parent.frames.document.form.elements["uf"+indPer].value) ;
  }*/

  window.close();
 }
</script>
<link href="../estilo.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style></head>

<body>
<form id="form" name="form1" method="post" action="">
<table>
 <tr>
      <td width="480" colspan="2" align="left" background="../Figuras/FundoTitulo.jpg" class="tdTitFundo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Percurso </td>
    </tr>
  <tr>
</table>
<table width="487" height="162" border="1" bordercolor="#43B182">

    <th width="477" align="left" valign="top" scope="row"><table width="477" border="0">
      <tr align="left">
        <th width="129" scope="col"><select name="SelUf" size="15" class="cmbPercurso" id="SelUf">
          <option value="AC">AC</option>
          <option value="AL">AL</option>
          <option value="AM">AM </option>
          <option value="AP">AP </option>
          <option value="BA">BA </option>
          <option value="CE">CE </option>
          <option value="DF">DF </option>
          <option value="ES">ES </option>
          <option value="GO">GO </option>
          <option value="MA">MA </option>
          <option value="MG">MG </option>
          <option value="MS">MS </option>
          <option value="MT">MT </option>
          <option value="PA">PA </option>
          <option value="PB">PB </option>
          <option value="PE">PE </option>
          <option value="PI">PI </option>
          <option value="PR">PR </option>
          <option value="RJ">RJ </option>
          <option value="RN">RN </option>
          <option value="RO">RO </option>
          <option value="RR">RR </option>
          <option value="RS">RS </option>
          <option value="SC">SC </option>
          <option value="SE">SE </option>
          <option value="SP">SP </option>
          <option value="TO">TO </option>
          <option value="EX">EX </option>
        </select></th>
        <th width="57" scope="col"><input name="btAdd" type="button" id="btAdd" value="&gt;" onclick="fPassaValor();" />
          <br />
          <input name="btnDel" type="button" id="btnDel" value="&lt;" onclick="fRetiraValor();" />
          <br /></th>
        <th width="124" scope="col"><select name="SelPercurso" size="15" class="cmbPercurso" id="SelPercurso">
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""> </option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
          <option value=""></option>
        </select></th>
        <th width="359" scope="col"><img src="../Figuras/Mapa2.jpg" alt="t" width="193" height="153"></th>
      </tr>

    </table>    </th>

    </tr>
	<tr>
	 <td align="center"><input name="btnOk" type="button"  id="btnOk" onclick="fPassaValMae();" value="ok" style="width:487px;background:#43B182;color:#fff;text-decoration: none;
"></td>
    </tr>
</table>

</form>
</body>
</html>
