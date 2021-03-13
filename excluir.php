<?php
include("./config.php");
$con = mysqli_connect($host, $login, $senha, $bd);
$sql = deleteQuery();
if(mysqli_query($con, $sql)){
  header("location: ./");
} else{
  echo "Erro". $sql . "<br>";
  mysqli_error($con);
}
// mysqli_query($con, $sql);



function deleteQuery(){
  $sql;
  switch ($_GET["table"]) {
    case 'torneio':
      $sql = "DELETE FROM torneio WHERE idTorneio=".$_GET["id"];
      break;
    case 'pessoa':
      $sql = "DELETE FROM pessoa WHERE cpf='".$_GET["CPF"]."'";
      break;
    case 'universidade':
      $sql = "DELETE FROM universidade WHERE idUniversidade=".$_GET["id"];
      break;
    case 'joga':// lincar
      $sql = "DELETE FROM joga WHERE pessoa_cpf='".$_GET["CPF"]."' AND time_idTime=".$_GET["idTime"];
      break;
    case 'esporte':
      $sql = "DELETE FROM esporte WHERE idEsporte=".$_GET["id"];
      break;
    case 'telefone': // lincar
      $sql = "DELETE FROM telefone WHERE pessoa_cpf='".$_GET["CPF"]."' AND telefone='".$_GET["tel"]."'";
      break;
    case 'atletica':
      $sql = "DELETE FROM atletica WHERE idAtletica=".$_GET["id"];
      break;
    case 'curso':
      $sql = "DELETE FROM curso WHERE idCurso=".$_GET["id"];
      break;
    case 'time':
      $sql = "DELETE FROM `time` WHERE idTime=".$_GET["id"];
      break;
    case 'disputa':
      $sql = "DELETE FROM disputa WHERE idDisputa=".$_GET["id"];
      break;
    case 'compete': // verificar de onde ta vindo -- Atualmente tá vindo de DISPUTA // lincar
      $sql = "DELETE FROM compete WHERE disputa_idDisputa=".$_GET["idDis"]." AND time_idTime=".$_GET["idTime"];
      break;
    default:
      // code...
      break;
  }
  return $sql;
}
?>
