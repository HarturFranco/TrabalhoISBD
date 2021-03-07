<?php
header("Content-Type: text/html; charset=utf8",true);
?>
<html>
<head><title>Regra <?php echo $_GET['table']; ?>: <?php echo $_GET['id']; ?>.</title></head>
<body>
  <?php
    include("./config.php");
    $con = mysqli_connect($host, $login, $senha, $bd);
    $sql = myQuery();
    $tabela = mysqli_query($con, $sql);
    mysqli_close($con);
    if(mysqli_num_rows($tabela)==0){
  ?>
    <table border="0" align="center" width="">
      <tr><td align="center">Não Existe <?php echo $_GET['table']; ?>  de id <?php echo $_GET['id']; ?></td></tr>
      <tr><td align="center"><input type="button" value="Voltar" onclick="location.href='exibir.php'"></td></tr>
    </table>
  <?php
    } else {
      $dados = mysqli_fetch_row($tabela);
  ?>
      <object data="data:application/pdf;base64,<?php echo base64_encode($dados[0]);?>" type="application/pdf" height="100%" width="100%"></object>
  <?php
    }
  ?>

</body>
</html>
<?php
function myQuery(){
  $sql = "";
  switch ($_GET['table']) {
    case 'torneio':
      $sql = "SELECT regras FROM torneio WHERE idTorneio=".$_GET['id'];
      break;
    case 'esporte':
      $sql = "SELECT regras FROM esporte WHERE idEsporte=".$_GET['id'];
      break;
  }
  return $sql;
}
 ?>
