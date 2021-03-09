<?php

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
<head><title>Incluir/Editar tabela <?php echo $_GET['table']; ?>.</title></head>
<body>
  <form name="form1" method="POST" action="incluir.php" enctype="multipart/form-data">
    <?php
    include("./config.php");
    $con = mysqli_connect($host, $login, $senha, $bd);
    if(count($_GET) >= 2){
    ?>
      <center><h3>Editar item tabela <?php echo $_GET['table']; ?></h3></center>
    <?php
      $sql = myQuery();
      $result = mysqli_query($con, $sql);
      $vetor = mysqli_fetch_array($result, MYSQLI_ASSOC);
      mysqli_close($con);
      if(array_key_exists("Data", $vetor)){
        $date = new DateTime($vetor['Data']);
        $vetor['Data'] = $date->format('d-m-Y');
      }

    ?>
      <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
    <?php
    }else{

      $vetor = myVetor();
    ?>

      <center><h3>Cadastrar Novo item tabela <?php echo $_GET['table']; ?></h3></center>

    <?php
    }
    ?>
      <input type="hidden" name="table" value="<?php echo $_GET['table']; ?>">
    <table border="0" align="center" width="35%">

    <?php
    foreach ($vetor as $key => $value) {
      if($key == 'Cidade'){
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
      if($key == 'Regras'){
    ?>
          <input type="file" name="Regras" maxlength="2" size="3">


    <?php
      } else if($key == 'Data' || $key == 'Data Nascimento'){
    ?>
          <input type="text" name="<?php echo @$key; ?>" value="<?php echo @$value; ?>" size="8">
    <?php
      } else if($key == 'Descricao'){
    ?>
          <!-- HTML CODE HERE -->
          <textarea id="descricao" name="<?php echo @$key; ?>" rows="4" cols="32" style="resize: none;">
              <?php echo @$value; ?>
          </textarea>
    <?php
      } else if ($key == "Estado"){
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
      }else {
    ?>
          <input type="text" name="<?php echo @$key; ?>" value="<?php echo @$value; ?>" maxlength="50" size="31">
    <?php
      } //ENDIF
    ?>

          </td>
        </tr>

    <?php
    } //ENDFOREACH
    ?>
      <tr><td colspan="3" align="center">
            <input type="button" value="Cancelar" onclick="location.href='javascript:history.go(-1)'">
            <input type="submit" value="Gravar">
          </td>
      </tr>
    </table>
  </form>
</body>
</html>


<?php
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
      $sql = "SELECT nome AS Nome, idAtletica AS Atletica, idUniversidade AS Universidade FROM `time` WHERE  `time`.idTime =".$_GET['id'];
      break;
    case 'disputa':
      $sql = "SELECT idTorneio AS Torneio, idEsporte AS Esporte, dataHora AS 'Data e Hora' FROM disputa WHERE  idDisputa =".$_GET['id'];
      break;
    case 'compete':

      break;
    default:
      // code... iz4g0n_
      break;
  }

  return $sql;
};



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
      $keys = array("Nome","Data Nascimento","Genero","Cidade","Estado","Logradouro","Numero","Complemento","Bairro","Referencia","CEP");
      break;
    case 'esporte':
      $keys = array("Nome","Categoria","Regras");
      break;
    case 'telefone':

      break;
    case 'atletica':

      break;
    case 'curso':

      break;
    case 'time':

      break;
    case 'disputa':

      break;
    case 'compete':

      break;
    default:
      // code...
      break;
  }
  $vetor = array_fill_keys($keys, "");
  return $vetor;
}
 ?>
