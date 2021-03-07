<?php
include("./config.php");
$con = mysqli_connect($host, $login, $senha, $bd);
$sql = "DELETE FROM torneio WHERE nome='".$_GET["codigo"]."'";
mysqli_query($con, $sql);
header("location: ./exibir.php");
?>
