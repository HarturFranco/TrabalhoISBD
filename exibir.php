<?php
header("Content-Type: text/html; charset=utf8",true);
?>
<html>
<head><title><?php echo $_GET['table']; ?>  <?php echo @$_GET['id']; ?>.</title></head>
<body>
  <center>
    <h3><?php echo $_GET['table']; ?>: <?php echo @$_GET['id']; ?>.</h3>
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
            } else if($key == 'Telefone'){
      ?>
              <a href="exibir.php?table=telefone&CPF=<?php echo $dados['CPF'];?>"><?php echo $value; ?></a>
      <?php
            } else if($key == 'Atleticas'){
      ?>
              <a href="exibir.php?table=atletica&idUni=<?php echo $dados['ID'];?>"><?php echo $value; ?></a>
      <?php
            } else if($key == 'Cursos'){
      ?>
              <a href="exibir.php?table=curso&idAtl=<?php echo $dados['ID'];?>"><?php echo $value; ?></a>
      <?php
            } else if($key == 'Times'){
      ?>
              <a href="exibir.php?table=time&idAtl=<?php echo $dados['ID'];?>"><?php echo $value; ?></a>
      <?php
            } else if($key == 'Disputas'){
      ?>
              <a href="exibir.php?table=disputa&idTor=<?php echo $dados['ID'];?>"><?php echo $value; ?></a>
      <?php
            } else if($key == 'Competidores'){
      ?>
              <a href="exibir.php?table=compete&idDis=<?php echo $dados['ID'];?>"><?php echo $value; ?></a>
      <?php
            } else if($key == 'Joga Para'){
      ?>
              <a href="exibir.php?table=joga&CPF=<?php echo $dados['CPF'];?>"><?php echo $value; ?></a>
      <?php
            } else if ($key == 'Data' || $key == 'Data Nascimento') {
              $date = new DateTime($value);
              $value = $date->format('d-m-Y');
      ?>
              <?php echo $value; ?>
      <?php
            } else if ($key == 'Data e Hora') {
              $date = new DateTime($value);
              $value = $date->format('d-m-Y H:i:s');
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
      $sql = "SELECT idTorneio AS ID, nome AS Nome, descricao AS Descricao, 'Visualizar Regras' AS Regras, CONCAT(cidade, ' - ', estado) AS Endereco, 'Ver Disputas' AS Disputas, `data` AS Data FROM torneio ORDER BY `data`";
      break;
    case 'universidade':
      $sql = "SELECT idUniversidade AS ID, nome AS Nome, categoria AS Categoria, CONCAT(cidade, ' - ', estado) AS Endereco, 'Visualizar Atleticas' AS Atleticas FROM universidade";
      break;
    case 'pessoa':
      $sql = "SELECT cpf AS CPF, nome AS Nome, genero AS Genero, dataNasc AS 'Data Nascimento',CONCAT(cidade, ' - ', estado) AS Endereco, 'Contato' AS Telefone, 'Ver Times' AS 'Joga Para' FROM pessoa";
      break;
    case 'joga':
      if(isset($_GET['CPF'])){
          $sql = "SELECT `time`.nome AS `Time` FROM joga INNER JOIN `time` ON time_idTime = `time`.idTime WHERE pessoa_cpf='".$_GET['CPF']."'";
      } else{
          $sql = "SELECT pessoa.nome AS Pessoa, `time`.nome AS Time FROM pessoa INNER JOIN joga INNER JOIN `time` ON pessoa.cpf = pessoa_cpf AND time_idTime = `time`.idTime ";
      }
      break;
    case 'esporte':
      $sql = "SELECT idEsporte AS ID, nome AS Nome, categoria AS Categoria, 'Visualizar Regras' AS Regras FROM esporte";
      break;
    case 'telefone':
      if(isset($_GET['CPF'])){
          $sql = "SELECT telefone FROM telefone WHERE pessoa_cpf='".$_GET['CPF']."'";
      } else{
          $sql = "SELECT pessoa.nome,  telefone FROM pessoa INNER JOIN telefone ON pessoa.cpf = telefone.pessoa_cpf ORDER BY pessoa.nome";
      }
      break;
    case 'atletica':
      if(isset($_GET['idUni'])){
          $sql = "SELECT idAtletica AS ID, nome AS Nome, 'Ver Cursos' AS Cursos, 'Ver Times' AS Times FROM atletica WHERE idUniversidade=".$_GET['idUni'];
      } else{
          $sql = "SELECT idAtletica AS ID,  universidade.nome AS Universidade, atletica.nome AS Nome, 'Ver Cursos' AS Cursos, 'Ver Times' AS Times FROM universidade INNER JOIN atletica ON universidade.idUniversidade = atletica.idUniversidade ORDER BY universidade.nome";
      }
      break;
    case 'curso':
      if(isset($_GET['idAtl'])){
          $sql = "SELECT idCurso AS ID, nome AS Nome FROM curso WHERE idAtletica=".$_GET['idAtl'];
      } else{
          $sql = "SELECT idCurso AS ID,  universidade.nome AS Universidade, atletica.nome AS Atletica, curso.nome AS Curso FROM universidade INNER JOIN atletica INNER JOIN curso ON universidade.idUniversidade = atletica.idUniversidade AND atletica.idAtletica = curso.idAtletica ORDER BY atletica.nome";
      }

      break;
    case 'time':
      if(isset($_GET['idAtl'])){
          $sql = "SELECT idTime AS ID, `time`.nome AS Nome, esporte.nome AS Esporte FROM `time` INNER JOIN esporte ON `time`.idEsporte = esporte.idEsporte WHERE idAtletica=".$_GET['idAtl'];
      } else{
          $sql = "SELECT idTime AS ID, `time`.nome AS Nome, atletica.nome AS Atletica , esporte.nome AS Esporte
                  FROM universidade INNER JOIN atletica INNER JOIN `time` INNER JOIN esporte
                  ON universidade.idUniversidade = atletica.idUniversidade AND atletica.idAtletica = `time`.idAtletica AND `time`.idEsporte = esporte.idEsporte
                  ORDER BY atletica.nome, universidade.nome";
      }
      break;
    case 'disputa':
      if(isset($_GET['idTor'])){
          $sql = "SELECT disputa.idDisputa AS ID, disputa.dataHora AS 'Data e Hora', esporte.nome AS Esporte, 'Ver Competidores' AS Competidores
                  FROM torneio INNER JOIN disputa INNER JOIN esporte ON torneio.idTorneio = disputa.idTorneio AND disputa.idEsporte = esporte.idEsporte
                  WHERE torneio.idTorneio=".$_GET['idTor'];
      } else{
          $sql = "SELECT * FROM disputa";
      }
      break;
    case 'compete':
      if(isset($_GET['idDis'])){
          $sql = "SELECT `time`.idTime, `time`.nome AS 'Time', pontuacao AS Pontuacao
                  FROM disputa INNER JOIN compete INNER JOIN `time` ON disputa.idDisputa = compete.disputa_idDisputa AND compete.time_idTime = `time`.idTime
                  WHERE disputa.idDisputa=".$_GET['idDis']." ORDER BY compete.pontuacao DESC";
      } else{
          $sql = "SELECT * FROM compete";
      }
      break;
    default:
      // code...
      break;
  }

  return $sql;
}
 ?>
