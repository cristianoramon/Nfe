<? session_start(); ?>

<?

//Tnsname
require_once("tnsNames/cTsnames.php");


//Select
require_once("cNFSaidas.php");

//Objetos
$tnsName = new cTnsName();
$cSel = new cNFSaidas();

//$_SESSION['login']
$Usuario = strtoupper($_POST["txtLogin"]);
$Senha  = $_POST["txtSenha"];
$Banco  = $_POST["txtBanco"];
$Filial = $_POST["txtEmpresa"];
//$Banco  = 'NT';

//echo "Usuario " .strtoupper($Usuario) ;
$_SESSION['login'] = $Usuario;
$_SESSION['senha'] = $Senha;
$_SESSION['banco'] = $Banco;
$_SESSION['empresa'] = $Filial;

 //echo "<script> location.href =\"IndexPrincipalAdmin.php\"; </script>";

//echo "<br> $Usuario - $Senha <br> " . $_SESSION['banco'];

$db = $tnsName->fTnsNames($_SESSION['banco']);

//echo "<br>Banco".$db;
$conn = ocilogon($Usuario, $Senha, $db) ;

if ( ! $conn ) {
     $erro =  "Não foi efetivada a conexao : " . var_dump( OCIError() );

}
else {

  $sql = " SELECT DP.SIGLA , UED.COD_DEPOSITO ".
         " FROM T_USUARIO_EMPRESA_DEPOSITO UED, DEPOSITO_EMPRESA DP ".
         " WHERE UED.COD_DEPOSITO = DP.CODDEP ".
		 "  AND DP.EMPRESA = '".$Filial ."'".
		 " AND UED.NOM_USUARIO = '" . $Usuario. "'";
 $sql_statUsuario = OCIParse($conn, $sql) or die ("Falha na passagem de cláusula SQL.");
 OCIExecute($sql_statUsuario) or die ("Não foi possível executar a cláusula SQL.");

  $_SESSION['cod_sigla'] = NULL;

 //SE O USUARIO PERTENCER A VARIOS DEPOSITO
 while ( OCIFetch($sql_statUsuario) ) {
  $_SESSION['sigla'] =  OCIResult($sql_statUsuario,"SIGLA");
  $_SESSION['cod_sigla'] = $_SESSION['cod_sigla'] . "'".OCIResult($sql_statUsuario,"COD_DEPOSITO") . "',";

 }


 $_SESSION['cod_sigla'] = substr($_SESSION['cod_sigla'],0, strlen($_SESSION['cod_sigla']) -1 );

 //Serie
 $sqlSerie = "  SELECT EP.VALOR " .
          " FROM EMPRESA_PARAM EP ".
          " WHERE EP.EMPRESA='".$Filial."'".
          " AND  EP.PARAM='SERIEPAD'";
 $sql_statSerie = OCIParse($conn, $sqlSerie) or die ("Falha na passagem de cláusula SQL.");
 OCIExecute($sql_statSerie) or die ("Não foi possível executar a cláusula SQL.");
 OCIFetch($sql_statSerie);

 $_SESSION['serie'] =  OCIResult($sql_statSerie,"VALOR");

 if ( strlen($_SESSION['sigla']) > 0  ) {
  echo "<script> location.href =\"IndexPrincipalAdmin.php\"; </script>";
 }
 else {
   echo "<script> alert( \"O usuario não estar associado a nenhum deposito \"); </script>";

 }

}

?>
