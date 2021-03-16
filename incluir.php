<?php

  include("./config.php");
  $con = mysqli_connect($host, $login, $senha, $bd);

  //tratamento de campos de DATE e DATETIME
  $dataT;
  if(isset($_POST["Data"])){
    $date = new DateTime($_POST['Data']);
    $dataT = $date->format('Y-m-d');
  }
  if(isset($_POST["DataH"])){
    $date = new DateTime($_POST['DataH']);
    $dataT = $date->format('Y-m-d H:i:s');
  }
  // Tratando campo regras (input file)
  $regras = "";
  if(isset($_FILES["Regras"])){
    $regras = addslashes(file_get_contents($_FILES["Regras"]["tmp_name"]));
  }

  $sql = myQuery();//seleciona consulta baseada na tabela passada por metodo POST
  if(isset($_POST["id"]) || isset($_POST["NCPF"]) || isset($_POST["idDis"])){ // campos que indicam edição (UPDATE)
    $result = mysqli_query($con, $sql[0]); //executa primeira consulta
    if(mysqli_num_rows($result)!=0){ // se existe campo com o id passado por POST executa o UPDATE
      $res = mysqli_query($con, $sql[1]);
      $var = $_POST['table'];
      header('location:exibir.php?table='.$var);
    }
  }else{ // POST nao possui campos ID portanto é feito a inserção de um novo item
    if(mysqli_query($con, $sql)){
      mysqli_close($con);
      $var = $_POST['table'];
      header('location:exibir.php?table='.$var); // se executada com sucesso retorna para exibir da tabela
    } else{
      echo "Erro". $sql . "<br>";
      mysqli_error($con);
    }
  }


//  Funcao retorna a/as consulta(s) a serem executadas de acordo com a tabela
  function myQuery(){
    $sql;
    switch ($_POST["table"]) {
      case 'torneio':
          if(isset($_POST["id"])){
            $sql[] = "SELECT idTorneio FROM torneio WHERE idTorneio=".$_POST['id'];
            $sql[] = "UPDATE torneio SET nome='".$_POST["Nome"]."',data='".$GLOBALS["dataT"]."',descricao='".$_POST["Descricao"]
                      ."',regras='".$GLOBALS["regras"]
                      ."',cidade='".$_POST["Cidade"]."',estado='".$_POST["Estado"]."',logradouro='".$_POST["Logradouro"]
                      ."',numero='".$_POST["Numero"]."',complemento='".$_POST["Complemento"]
                      ."',bairro='".$_POST["Bairro"]."',referencia='".$_POST["Referencia"]."',CEP='".$_POST["CEP"]."' WHERE idTorneio=".$_POST["id"];
          } else{
            $sql = "INSERT INTO torneio (nome, data, descricao, regras, logradouro, CEP, cidade, estado, complemento, numero, referencia, bairro)
                    VALUES ('".$_POST["Nome"]."','".$GLOBALS["dataT"]."','".$_POST["Descricao"]."','".$GLOBALS["regras"]."'
                    ,'".$_POST["Logradouro"]."','".$_POST["CEP"]."','".$_POST["Cidade"]."','".$_POST["Estado"]."','".$_POST["Complemento"]."','".$_POST["Numero"]."'
                    ,'".$_POST["Referencia"]."','".$_POST["Bairro"]."')";
          }
        break;
      case 'pessoa':
          if(isset($_POST["NCPF"])){
            $sql[] = "SELECT cpf FROM pessoa WHERE cpf='".$_POST['CPF']."'";
            $sql[] = "UPDATE pessoa SET nome='".$_POST["Nome"]."',dataNasc='".$GLOBALS["dataT"]."',genero='".$_POST["Genero"]
                      ."',cidade='".$_POST["Cidade"]."',estado='".$_POST["Estado"]."',logradouro='".$_POST["Logradouro"]
                      ."',numero='".$_POST["Numero"]."',complemento='".$_POST["Complemento"]
                      ."',bairro='".$_POST["Bairro"]."',referencia='".$_POST["Referencia"]."',CEP='".$_POST["CEP"]."' WHERE cpf='".$_POST["NCPF"]."'";
          } else {
            $sql = "INSERT INTO pessoa (cpf, nome, dataNasc, genero, logradouro, CEP, cidade, estado, complemento, numero, referencia, bairro)
                    VALUES ('".$_POST["CPF"]."','".$_POST["Nome"]."','".$GLOBALS["dataT"]."','".$_POST["Genero"]."'
                    ,'".$_POST["Logradouro"]."','".$_POST["CEP"]."','".$_POST["Cidade"]."','".$_POST["Estado"]."','".$_POST["Complemento"]."','".$_POST["Numero"]."'
                    ,'".$_POST["Referencia"]."','".$_POST["Bairro"]."')";
          }
        break;
      case 'universidade':
        if(isset($_POST["id"])){
          $sql[] = "SELECT idUniversidade FROM universidade WHERE idUniversidade=".$_POST['id'];
          $sql[] = "UPDATE pessoa SET nome='".$_POST["Nome"]."',categoria='".$_POST["Categoria"]
                    ."',cidade='".$_POST["Cidade"]."',estado='".$_POST["Estado"]."',logradouro='".$_POST["Logradouro"]
                    ."',numero='".$_POST["Numero"]."',complemento='".$_POST["Complemento"]
                    ."',bairro='".$_POST["Bairro"]."',referencia='".$_POST["Referencia"]."',CEP='".$_POST["CEP"]."' WHERE idUniversidade=".$_POST["id"];
        } else{
          $sql = "INSERT INTO universidade (nome, categoria, logradouro, CEP, cidade, estado, complemento, numero, referencia, bairro)
                  VALUES ('".$_POST["Nome"]."','".$_POST["Categoria"]."'
                  ,'".$_POST["Logradouro"]."','".$_POST["CEP"]."','".$_POST["Cidade"]."','".$_POST["Estado"]."','".$_POST["Complemento"]."','".$_POST["Numero"]."'
                  ,'".$_POST["Referencia"]."','".$_POST["Bairro"]."')";
        }
        break;
      case 'joga':
        if(isset($_POST["idTime"]) && isset($_POST["NCPF"])){
          $sql[] = "SELECT * FROM joga WHERE time_idTime=".$_POST['idTime']." AND pessoa_cpf='".$_POST['NCPF']."'";
          $sql[] = "UPDATE joga SET pessoa_cpf='".$_POST["Pessoa"]."',time_idTime=".$_POST["Time"]
                    ." WHERE time_idTime=".$_POST['idTime']." AND pessoa_cpf='".$_POST['NCPF']."'";
        } else{
          $sql = "INSERT INTO joga (pessoa_cpf, time_idTime)
                  VALUES ('".$_POST["Pessoa"]."',".$_POST["Time"].")";
        }
        break;
      case 'esporte':
        if(isset($_POST["id"])){
          $sql[] = "SELECT idEsporte FROM esporte WHERE idEsporte=".$_POST['id'];
          $sql[] = "UPDATE esporte SET nome='".$_POST["Nome"]."',categoria='".$_POST["Categoria"]
                    ."',regras='".$GLOBALS["regras"]."' WHERE idEsporte=".$_POST["id"];
        } else{
          $sql = "INSERT INTO esporte (nome, categoria, regras)
                  VALUES ('".$_POST["Nome"]."','".$_POST["Categoria"]."','".$GLOBALS["regras"]."')";
        }
        break;
      case 'telefone':
        if(isset($_POST["tel"]) && isset($_POST["NCPF"])){
          $sql[] = "SELECT * FROM telefone WHERE telefone='".$_POST['tel']."' AND pessoa_cpf='".$_POST['NCPF']."'";
          $sql[] = "UPDATE telefone SET pessoa_cpf='".$_POST["Pessoa"]."',telefone='".$_POST["Telefone"]
                    ."' WHERE telefone='".$_POST['tel']."' AND pessoa_cpf='".$_POST['NCPF']."'";
        } else{
          $sql = "INSERT INTO telefone (pessoa_cpf, telefone)
                  VALUES ('".$_POST["Pessoa"]."','".$_POST["Telefone"]."')";
        }
        break;
      case 'atletica':
        if(isset($_POST["id"])){
          $sql[] = "SELECT idAtletica FROM atletica WHERE idAtletica=".$_POST['id'];
          $sql[] = "UPDATE atletica SET nome='".$_POST["Nome"]."',idUniversidade=".$_POST["Universidade"]
                    ." WHERE idAtletica=".$_POST['id'];
        } else{
          $sql = "INSERT INTO atletica (nome, idUniversidade) VALUES ('".$_POST["Nome"]."',".$_POST["Universidade"].")";
        }
        break;
      case 'curso':
        if(isset($_POST["id"])){
          $sql[] = "SELECT idCurso FROM curso WHERE idCurso=".$_POST['id'];
          $sql[] = "UPDATE curso SET nome='".$_POST["Nome"]."',idAtletica=".$_POST["Atletica"]
                    .",idUniversidade=".$_POST["Universidade"]." WHERE idCurso=".$_POST['id'];
        } else{
          $sql = "INSERT INTO curso (nome, idAtletica, idUniversidade)
                  VALUES ('".$_POST["Nome"]."','".$_POST["Atletica"]."','".$_POST["Universidade"]."')";
        }
        break;
      case 'time':
        if(isset($_POST["id"])){
          $sql[] = "SELECT idTime FROM `time` WHERE idTime=".$_POST['id'];
          $sql[] = "UPDATE `time` SET nome='".$_POST["Nome"]."',idAtletica=".$_POST["Atletica"]
                    .",idUniversidade=".$_POST["Universidade"].",idEsporte=".$_POST["Esporte"]." WHERE idTime=".$_POST['id'];
        } else{
          $sql = "INSERT INTO `time` (nome, idAtletica, idUniversidade, idEsporte)
                  VALUES ('".$_POST["Nome"]."',".$_POST["Atletica"].",".$_POST["Universidade"].",".$_POST["Esporte"].")";
        }
        break;
      case 'disputa':
        if(isset($_POST["id"])){
          $sql[] = "SELECT idDisputa FROM disputa WHERE idDisputa=".$_POST['id'];
          $sql[] = "UPDATE disputa SET idTorneio=".$_POST['Torneio']
                    .",idEsporte=".$_POST['Esporte'].",dataHora='".$GLOBALS['dataT']
                    ."' WHERE idDisputa=".$_POST['id'];
        } else{
          $sql = "INSERT INTO disputa (idTorneio, idEsporte, dataHora)
                  VALUES (".$_POST["Torneio"].",".$_POST["Esporte"].",'".$GLOBALS["dataT"]."')";
        }
        break;
      case 'compete': // verificar de onde ta vindo -- Atualmente tá vindo de DISPUTA
        if(isset($_POST["idTime"]) && isset($_POST["idDis"])){
          echo "AQUIIIIIIIIIi".$_POST["Pontuacao"].",time_idTime=".$_POST["Time"]."idDisputa=".$_POST['idDis']." AND time_idTime=".$_POST['idTime'];
          $sql[] = "SELECT pontuacao FROM compete WHERE time_idTime=".$_POST['idTime']." AND disputa_idDisputa=".$_POST['idDis'];
          $sql[] = "UPDATE compete SET pontuacao=".$_POST["Pontuacao"].",time_idTime=".$_POST["Time"]
                    ." WHERE disputa_idDisputa=".$_POST['idDis']." AND time_idTime=".$_POST['idTime'];
        } else{
          $sql = "INSERT INTO compete (pontuacao, time_idTime)
                  VALUES (".$_POST["Pontuacao"].",".$_POST["Time"].")";
        }
        break;
      default:
        // code...
        break;
    }
    return $sql;
  }
?>
