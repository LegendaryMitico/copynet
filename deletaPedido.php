<?php
include_once("php/connect.php");

$numeroPedido = $_GET['numeroPedido'];
$query = "DELETE FROM `pedidos` WHERE idPedido='$numeroPedido'";
mysqli_query($conn, $query);

header("Location:meusPedidos.php"); 

?>