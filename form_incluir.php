<?php
// array para identificar identificar campos de chave estrangeira
$tabelas = array(
  'Pessoa' => 'pessoa',
  'Universidade' => 'universidade',
  'Atletica' => 'atletica',
  'Esporte' => 'esporte',
  'Torneio' => 'torneio',
  'Time' => 'time'
);
// array para povoar select de campo Estado
$estadosBrasileiros = array(
  'AC'=>'Acre',
  'AL'=>'Alagoas',
  'AP'=>'Amapá',
  'AM'=>'Amazonas',
  'BA'=>'Bahia',
  'CE'=>'Ceará',
  'DF'=>'Distrito Federal',
  'ES'=>'Espírito Santo',
  'GO'=>'Goiás',
  'MA'=>'Maranhão',
  'MT'=>'Mato Grosso',
  'MS'=>'Mato Grosso do Sul',
  'MG'=>'Minas Gerais',
  'PA'=>'Pará',
  'PB'=>'Paraíba',
  'PR'=>'Paraná',
  'PE'=>'Pernambuco',
  'PI'=>'Piauí',
  'RJ'=>'Rio de Janeiro',
  'RN'=>'Rio Grande do Norte',
  'RS'=>'Rio Grande do Sul',
  'RO'=>'Rondônia',
  'RR'=>'Roraima',
  'SC'=>'Santa Catarina',
  'SP'=>'São Paulo',
  'SE'=>'Sergipe',
  'TO'=>'Tocantins'
);


header("Content-Type: text/html; charset=utf8",true);
?>
<html>
<head><title>Editar tabela <?php echo $_GET['table']; ?>.</title></head>
<body>
  <form name="form1" method="POST" action="incluir.php" enctype="multipart/form-data">
    <?php
    include("./config.php");
    $con = mysqli_connect($host, $login, $senha, $bd);
    if(count($_GET) >= 2){ // Editar
    ?>
      <center><h3>Editar item tabela <?php echo $_GET['table']; ?></h3></center>
    <?php
      $sql = myQuery(); // selecionando a consulta de acordo com a tabela a ser editada
      $result = mysqli_query($con, $sql); // executa consulta
      $vetor = mysqli_fetch_array($result, MYSQLI_ASSOC); //

      //tratando os campos de DATE e DATETIME
      if(array_key_exists("Data", $vetor)){
        $date = new DateTime($vetor['Data']);
        $vetor['Data'] = $date->format('d-m-Y');
      } else if(array_key_exists("Data Nascimento", $vetor)){
        $date = new DateTime($vetor['Data Nascimento']);
        $vetor['Data Nascimento'] = $date->format('d-m-Y');
      } else if(array_key_exists("Data e Hora", $vetor)){
        $date = new DateTime($vetor['Data e Hora']);
        $vetor['Data e Hora'] = $date->format('d-m-Y H:i:s');
      }
      // setando chaves para diferentes tabelas
      if(isset($_GET["table"]) && ($_GET["table"] == "joga")){
    ?>
        <input type="hidden" name="idTime" value="<?php echo $_GET['idTime']; ?>">
        <input type="hidden" name="NCPF" value="<?php echo $_GET['CPF']; ?>">
    <?php
      } else if(isset($_GET["table"]) && ($_GET["table"] == "compete")){
    ?>
        <input type="hidden" name="idTime" value="<?php echo $_GET['idTime']; ?>">
        <input type="hidden" name="idDis" value="<?php echo $_GET['idDis']; ?>">
    <?php
      } else if(isset($_GET["table"]) && ($_GET["table"] == "telefone")){
    ?>
        <input type="hidden" name="tel" value="<?php echo $_GET['tel']; ?>">
        <input type="hidden" name="NCPF" value="<?php echo $_GET['CPF']; ?>">
    <?php
      } else if(isset($_GET["table"]) && ($_GET["table"] == "pessoa")){
    ?>
        <input type="hidden" name="NCPF" value="<?php echo $_GET['CPF']; ?>">
    <?php
      } else{
    ?>
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
    <?php
      }
    }else{ // cadastrar

      $vetor = myVetor(); // vetor possui apenas o nome dos campos sem conteudo
    ?>

      <center><h3>Cadastrar Novo item tabela <?php echo $_GET['table']; ?></h3></center>

    <?php
    }
    ?>
      <input type="hidden" name="table" value="<?php echo $_GET['table']; ?>">
    <table border="0" align="center" width="35%">

    <?php
    foreach ($vetor as $key => $value) {
      if(isset($tabelas[$key])){ // se compo refere a uma tabela (chave estrangeira)
        $names = myTableName($tabelas[$key]); // passa tabela para função que retorna todos ids e 'nomes' da tabela
    ?>
      <tr>
          <td width="20%"><?php echo @$key; ?>:</td>
          <td colspan="2" width="90%">
            <select name=<?php echo $key ?>>
    <?php
        foreach ($names as $k => $v) { // povoa um select com todos dos dados da tabela onde o valor da opcao é a chave e o display é o 'nome'
    ?>
          <option value="<?php echo @$k; ?>" <?php if($k == $value){ ?> selected="selected";<?php } ?>  ><?php echo $v; ?></option>
    <?php
        }
    ?>
            </select>
    <?php
          //mysqli_close($con);
        } else {
          //mysqli_close($con);
          if($key == 'Cidade'){  // cidade é sempre primeiro campo dos enderecos, portanto adiciona divisoria
    ?>
        <tr>
            <th colspan="2" align="center"><br>Endereço:<br></th>
        </tr>
    <?php
      }
    ?>

      <tr>
          <td width="20%"><?php echo @$key; ?>:</td>
          <td colspan="2" width="90%">
    <?php
      if($key == 'Regras'){ // caso compo é regras, adiciona imput do tipo file
    ?>
          <input type="file" name="Regras" maxlength="2" size="3">


    <?php
      } else if($key == 'Data' || $key == 'Data Nascimento'){ // caso compo é = data
    ?>
          <input type="text" name="Data" value="<?php echo @$value; ?>" size="8">
    <?php
      } else if($key == 'Data e Hora'){ // tratamento diferenciado para datetime em incluir.php
    ?>
          <input type="text" name="DataH" value="<?php echo @$value; ?>" size="8">
    <?php
      } else if($key == 'Descricao'){ // descricao exige textarea
    ?>
          <!-- HTML CODE HERE -->
          <textarea id="descricao" name="<?php echo @$key; ?>" rows="4" cols="32" style="resize: none;">
              <?php echo @$value; ?>
          </textarea>
    <?php
      } else if ($key == "Estado"){ // caso campo estado, povoa select com estados brasileiros
    ?>
          <select name="Estado">
    <?php
          foreach ($estadosBrasileiros as $k => $v) {
     ?>
            <option value="<?php echo @$k; ?>" <?php if($k == $value){ ?> selected="selected";<?php } ?>  ><?php echo $v; ?></option>
     <?php
          }
      ?>
          </select>
    <?php
      }else { // demais campos
    ?>
          <input type="text" name="<?php echo @$key; ?>" value="<?php echo @$value; ?>" maxlength="50" size="31">
    <?php
      } //ENDIF
    }
    ?>

          </td>
        </tr>

    <?php
    } //ENDFOREACH
    ?>
      <tr><td colspan="3" align="center">
            <input type="button" value="Cancelar" onclick="location.href='javascript:history.go(-1)'"> <!--Voltar para ultima pagina-->
            <input type="submit" value="Gravar" onclick="location.href='javascript:history.go(-1)'">
          </td>
      </tr>
    </table>
  </form>
</body>
</html>


<?php
// função retorna a consulta de acordo com a tabela a ser editada
function myQuery() {
  $sql = "";
  switch ($_GET['table']) {
    case 'torneio':
      $sql = "SELECT nome AS Nome, data AS Data, descricao AS Descricao, regras AS Regras, cidade AS Cidade, estado AS Estado, logradouro AS Logradouro, numero AS Numero, complemento AS Complemento, bairro AS Bairro, referencia AS Referencia, CEP FROM torneio WHERE idTorneio=".$_GET['id']; // substituir * por tudo menos id
      break;
    case 'universidade':
      $sql = "SELECT nome AS Nome, categoria AS Categoria, cidade AS Cidade, estado AS Estado, logradouro AS Logradouro, numero AS Numero, complemento AS Complemento, bairro AS Bairro, referencia AS Referencia, CEP FROM universidade WHERE idUniversidade=".$_GET['id']; // substituir * por tudo menos id
      break;
    case 'pessoa':
      $sql = "SELECT nome AS Nome, genero AS Genero, dataNasc AS 'Data Nascimento',cidade AS Cidade, estado AS Estado, logradouro AS Logradouro, numero AS Numero, complemento AS Complemento, bairro AS Bairro, referencia AS Referencia, CEP FROM pessoa WHERE pessoa.cpf='".$_GET['CPF']."'"; // substituir * por tudo menos id
      break;
    case 'joga': // verificar de onde ta vindo
    // FAZ SENTIDO EDITAR??
      $sql = "SELECT pessoa_cpf AS Pessoa, time_idTime as Time From joga WHERE pessoa_cpf='".$_GET['CPF']."' AND time_idTime =".$_GET['idTime'];
      break;
    case 'esporte':
      $sql = "SELECT nome AS Nome, categoria AS Categoria, regras AS Regras FROM esporte WHERE esporte.idEsporte =".$_GET['id']; // substituir * por tudo menos id
      break;
    case 'telefone':
      $sql = "SELECT telefone AS Telefone, pessoa_cpf AS Pessoa FROM telefone WHERE pessoa_cpf ='".$_GET['CPF']."' AND telefone ='".$_GET['tel']."'";
      break;
    case 'atletica':
      $sql = "SELECT nome AS Nome, idUniversidade AS Universidade FROM atletica WHERE idAtletica =".$_GET['id'];
      break;
    case 'curso':
      $sql = "SELECT nome AS Nome, idAtletica AS Atletica, idUniversidade AS Universidade FROM curso WHERE curso.idCurso =".$_GET['id'];
      break;
    case 'time':
      $sql = "SELECT nome AS Nome, idAtletica AS Atletica, idUniversidade AS Universidade, idEsporte AS Esporte FROM `time` WHERE  `time`.idTime =".$_GET['id'];
      break;
    case 'disputa':
      $sql = "SELECT idTorneio AS Torneio, idEsporte AS Esporte, dataHora AS 'Data e Hora' FROM disputa WHERE  idDisputa =".$_GET['id'];
      break;
    case 'compete': // verificar de onde ta vindo
      $sql = "SELECT time_idTime AS Time, pontuacao AS Pontuacao FROM compete WHERE disputa_idDisputa=".$_GET['idDis']." AND time_idTime=".$_GET['idTime'];
      break;
    default:
      // code... iz4g0n_
      break;
  }
  return $sql;
};
// retorna chave e nome de todos os dados da tabela para povoar select dropdown em campos de chave estrangeira
function myTableName($tabela){
  $values = array();
  $keys = array();
  switch ($tabela) {
    case 'torneio':
      $result = mysqli_query($GLOBALS["con"], "SELECT nome FROM torneio ORDER BY idTorneio");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $values[] = $row[0];
      }
      $result = mysqli_query($GLOBALS["con"], "SELECT idTorneio FROM torneio ORDER BY idTorneio");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $keys[] = $row[0];
      }
      break;
    case 'pessoa':
      $result = mysqli_query($GLOBALS["con"], "SELECT nome FROM pessoa ORDER BY cpf");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $values[] = $row[0];
      }
      $result = mysqli_query($GLOBALS["con"], "SELECT cpf FROM pessoa ORDER BY cpf");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $keys[] = $row[0];
      }
      break;
    case 'universidade':
      $result = mysqli_query($GLOBALS["con"], "SELECT nome FROM universidade ORDER BY idUniversidade");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $values[] = $row[0];
      }
      $result = mysqli_query($GLOBALS["con"], "SELECT idUniversidade FROM universidade ORDER BY idUniversidade");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $keys[] = $row[0];
      }
      break;
    case 'atletica':
      $result = mysqli_query($GLOBALS["con"], "SELECT nome FROM atletica ORDER BY idAtletica");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $values[] = $row[0];
      }
      $result = mysqli_query($GLOBALS["con"], "SELECT idAtletica FROM atletica ORDER BY idAtletica");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $keys[] = $row[0];
      }
      break;
    case 'esporte':
      $result = mysqli_query($GLOBALS["con"], "SELECT nome FROM esporte ORDER BY idEsporte");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $values[] = $row[0];
      }
      $result = mysqli_query($GLOBALS["con"], "SELECT idEsporte FROM esporte ORDER BY idEsporte");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $keys[] = $row[0];
      }
      break;
    case 'time':
      $result = mysqli_query($GLOBALS["con"], "SELECT nome FROM `time` ORDER BY idTime");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $values[] = $row[0];
      }
      $result = mysqli_query($GLOBALS["con"], "SELECT idTime FROM `time` ORDER BY idTime");
      while($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
          $keys[] = $row[0];
      }
      break;
  }
  $names = array_combine($keys, $values);

  return $names;

}
// retorna vetor com campos do formulario caso opção seja incluir novo
function myVetor(){
  $keys = "";
  switch ($_GET['table']) {
    case 'torneio':
      $keys = array("Nome","Data","Descricao","Regras","Cidade","Estado","Logradouro","Numero","Complemento","Bairro","Referencia","CEP");
      break;
    case 'universidade':
      $keys = array("Nome","Categoria","Cidade","Estado","Logradouro","Numero","Complemento","Bairro","Referencia","CEP");
      break;
    case 'pessoa':
      $keys = array("CPF", "Nome","Data Nascimento","Genero","Cidade","Estado","Logradouro","Numero","Complemento","Bairro","Referencia","CEP");
      break;
    case 'joga':
      $keys = array("Pessoa", "Time");
      break;
    case 'esporte':
      $keys = array("Nome","Categoria","Regras");
      break;
    case 'telefone':
      $keys = array("Telefone", "Pessoa");
      break;
    case 'atletica':
      $keys = array("Nome", "Universidade");
      break;
    case 'curso':
      $keys = array("Nome", "Atletica", "Universidade");
      break;
    case 'time':
      $keys = array("Nome", "Atletica", "Universidade", "Esporte");
      break;
    case 'disputa':
      $keys = array("Torneio", "Esporte", "Data e Hora");
      break;
    case 'compete':
      $keys = array("Time", "Pontuacao");
      break;
    default:
      // code...
      break;
  }
  $vetor = array_fill_keys($keys, "");
  return $vetor;
}
 ?>
