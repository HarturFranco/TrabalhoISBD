<?php



  include("./config.php");
  $con = mysqli_connect($host, $login, $senha, $bd);
  $date = new DateTime($_POST['Data']);
  $dataT = $date->format('Y-m-d');
  $regras = addslashes(file_get_contents($_FILES["Regras"]["tmp_name"]));
  $sql = myQuery();
  if(isset($_POST["id"])){
    $result = mysqli_query($con, $sql[0]);
    if(mysqli_num_rows($result)!=0){
      $res = mysqli_query($con, $sql[1]);
    }
  }else{
    $res = mysqli_query($con, $sql); //nao ta inserindo
    //echo gettype($sql);
  }

  mysqli_close($con);
  header("location: ./exibir.php?table=".$_POST["table"]);

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
          $sql[] = "SELECT idTorneio FROM torneio WHERE idTorneio=".$_POST['id'];
          $sql[] = "UPDATE torneio SET nome='".$_POST["Nome"]."',data='".$GLOBALS["dataT"]."',descricao='".$_POST["Descricao"]
          ."',regras='".$GLOBALS["regras"]
          ."',cidade='".$_POST["Cidade"]."',estado='".$_POST["Estado"]."',logradouro='".$_POST["Logradouro"]
          ."',numero='".$_POST["Numero"]."',complemento='".$_POST["Complemento"]
          ."',bairro='".$_POST["Bairro"]."',referencia='".$_POST["Referencia"]."',CEP='".$_POST["CEP"]."' WHERE idTorneio=".$_POST["id"];
          $sql[] = "INSERT INTO torneio (nome, data, descricao, regras, logradouro, CEP, cidade, estado, complemento, numero, referencia, bairro)
          VALUES ('".$_POST["Nome"]."','".$GLOBALS["dataT"]."','".$_POST["Descricao"]."','".$GLOBALS["regras"]."'
          ,'".$_POST["Logradouro"]."','".$_POST["CEP"]."','".$_POST["Cidade"]."','".$_POST["Estado"]."','".$_POST["Complemento"]."','".$_POST["Numero"]."'
          ,'".$_POST["Referencia"]."','".$_POST["Bairro"]."')";
        break;

      default:
        // code...
        break;
    }
    return $sql;
  }
?>
