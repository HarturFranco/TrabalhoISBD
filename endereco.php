<?php
header("Content-Type: text/html; charset=utf8",true);
?>
<html>
<head><title>Endereço <?php echo $_GET['table']; ?>: <?php echo $_GET['id']; ?>.</title></head>
<body>
  <center>
    <h3>Endereço Completo <?php echo $_GET['table']; ?>: <?php echo $_GET['id']; ?></h3>
  </center>

  <table border="0" align="center" width="">
    <?php
      include("./config.php");
      $con = mysqli_connect($host, $login, $senha, $bd);
      $sql = myQuery(); // seleciona consulta de acordo com tabela
      $tabela = mysqli_query($con, $sql);
      if(mysqli_num_rows($tabela)==0){
    ?>
        <tr><td align="center">Não Existe <?php echo $_GET['table']; ?> de id <?php echo $_GET['id']; ?></td></tr>
        <tr><td align="center"><input type="button" value="Voltar" onclick="location.href='exibir.php?table=".<?php echo $_GET['table']; ?>."'"></td></tr>
    <?php
      }else{
        $dados = mysqli_fetch_row($tabela);
    ?>
    	<tr bgcolor="grey">
        <th>Cidade</th>
        <th>Estado</th>
        <th>Logradouro</th>
        <th>Numero</th>
        <?php if(!is_null($dados[4])) {echo "<th>Complemento</th>";} ?>
        <th>Bairro</th>
        <?php if(!is_null($dados[6])) {echo "<th>Referencia</th>";} ?>
        <th>CEP</th>
      </tr>

      <tr>
        <td><?php echo $dados[0]; ?></td>
        <td><?php echo $dados[1]; ?></td>
        <td><?php echo $dados[2]; ?></td>
        <td><?php echo $dados[3]; ?></td>
        <?php if(!is_null($dados[4])) {echo "<td>".$dados[4]."</td>";} ?>
        <td><?php echo $dados[5]; ?></td>
        <?php if(!is_null($dados[6])) {echo "<td>".$dados[6]."</td>";} ?>
        <td><?php echo $dados[7]; ?></td>
      </tr>

    <tr bgcolor="grey"><td colspan="8" height="5"></td></tr>
    <?php
    mysqli_close($con);
    ?>
    <tr><td colspan="8" align="center"><input type="button" value="Voltar" onclick="location.href='javascript:history.go(-1)'"></td></tr>
    <?php
    }
    ?>
  </table>

</body>
</html>
<?php

  function myQuery(){
    $sql = "SELECT cidade, estado, logradouro, numero, complemento, bairro,referencia, CEP";
    switch ($_GET['table']) {
      case 'torneio':
        $sql = $sql." FROM torneio WHERE idTorneio = ".$_GET['id'];
        break;
      case 'pessoa':
        $sql = $sql." FROM pessoa WHERE cpf = ".$_GET['id'];
        break;
      case 'universidade':
        $sql = $sql." FROM universidade WHERE idUniversidade = ".$_GET['id'];
        break;
      default:
        // code...
        break;
    }
    return $sql;
  }

 ?>
