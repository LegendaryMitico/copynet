<?php
//atualiza o database do pedido com base no json recebido e o id do pedido via ajax (linha 636 de pedido.php)
include_once("../php/connect.php");
session_start( );
$updateJson = json_encode($_POST['pedidoJson'],JSON_UNESCAPED_UNICODE);
$idPedido = $_POST['idPedido'];

$queryAt = "UPDATE `pedidos` SET `pedidoJson`='$updateJson', `vendedorNome`='$_SESSION[usuarioNome]' WHERE idPedido='".$idPedido."'";
mysqli_query($conn, $queryAt);
?>