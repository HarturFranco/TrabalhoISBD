<?php
header("Content-Type: text/html; charset=utf8",true);
?>
<html>
<head><title>Torneios Intertléticas.</title></head>
<body>
  <center>
    <h3>Torneios Intertléticas.</h3>
  </center>
  <form name="form1" method="POST" action="form_incluir.php?table=<?php echo $_GET['table']; ?>">
    <table border="0" align="center" width="">
      <?php
        include("./config.php");
        $con = mysqli_connect($host, $login, $senha, $bd);
        $sql = myQuery();
        $tabela = mysqli_query($con, $sql);
        if(mysqli_num_rows($tabela)==0){
      ?>
        <tr><td align="center">Sem torneios cadastrados no banco</td></tr>
        <tr><td align="center"><input type="submit" value="Inserir Torneio"></td></tr>
      <?php
        }else{
      ?>
        <tr bgcolor="grey">
      <?php
          $dados = mysqli_fetch_assoc($tabela);
          $headers = array_keys($dados);
          foreach ($headers as $key => $header) {
      ?>
          <th><?php echo $header; ?></th>
      <?php
          }
      ?>
          <th></th>
        </tr>

      <?php
        do{
      ?>
          <tr>
      <?php
          foreach ($dados as $key => $value) {
      ?>
            <td style='text-align:center; vertical-align:middle'>
      <?php

            if($key == 'Regras'){
      ?>
              <a href="exibirRegra.php?table=<?php echo $_GET['table']; ?>&id=<?php echo $dados['ID']; ?>"><?php echo $value; ?></a>
      <?php
            } else if($key == 'Endereco'){
      ?>
              <a href="endereco.php?table=<?php echo $_GET['table']; ?>&id=<?php if($_GET['table'] == 'pessoa') echo $dados['CPF']; else echo $dados['ID'];?>"><?php echo $value; ?></a>
      <?php
            } else if ($key == 'Data' || $key == 'Data Nascimento') {
              $date = new DateTime($value);
              $value = $date->format('d-m-Y');
      ?>
              <?php echo $value; ?>
      <?php
            } else{
      ?>
              <?php echo $value; ?>
      <?php
            }
       ?>

            </td>
      <?php
          }  // end for
       ?>
             <td align="center">

              <input type="button" value="Excluir" onclick="location.href='excluir.php?table=<?php echo $_GET['table']; ?>&id=<?php if($_GET['table'] == 'pessoa') echo $dados['CPF']; else echo $dados['ID'];?>'">
              <input type="button" value="Editar " onclick="location.href='form_incluir.php?table=<?php echo $_GET['table']; ?>&id=<?php if($_GET['table'] == 'pessoa') echo $dados['CPF']; else echo $dados['ID'];?>'">

             </td>
           </tr>
      <?php
        } while($dados = mysqli_fetch_assoc($tabela));// end while
      ?>
      <tr bgcolor="grey"><td colspan="10" height="5"></td></tr>
      <?php
      mysqli_close($con);
      ?>
      <tr><td colspan="10" align="center"><input type="submit" value="Incluir Novo"></td></tr>
      <?php
      }
      ?>
    </table>
  </form>
</body>
</html>


<?php
function myQuery() {
  $sql = "";
  switch ($_GET['table']) {
    case 'torneio':
      $sql = "SELECT idTorneio AS ID, nome AS Nome, descricao AS Descricao, 'Visualizar Regras' AS Regras, CONCAT(cidade, ' - ', estado) AS Endereco, `data` AS Data FROM torneio ORDER BY `data`";
      break;
    case 'universidade':
      $sql = "SELECT idUniversidade AS ID, nome AS Nome, categoria AS Categoria, CONCAT(cidade, ' - ', estado) AS Endereco FROM universidade";
      break;
    case 'pessoa':
      $sql = "SELECT cpf AS CPF, nome AS Nome, genero AS Genero, dataNasc AS 'Data Nascimento',CONCAT(cidade, ' - ', estado) AS Endereco FROM pessoa";
      break;
    case 'esporte':
      $sql = "SELECT idEsporte AS ID, nome AS Nome, categoria AS Categoria, 'Visualizar Regras' AS Regras FROM esporte";
      break;
    default:
      // code...
      break;
  }

  return $sql;
}
 ?>
