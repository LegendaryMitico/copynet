<?php
//atualiza o database do pedido com base no json recebido e o id do pedido via ajax (linha 636 de pedido.php)
//atenção EXTREMA aqui, pois o pedido é trocado pelo novo com o incremento do código de {abate} que está vindo do bling, então tem possibilidade de ocorrer erros nessa troca 
include_once("../php/connect.php");
session_start();
$updateJson = json_encode($_POST['pedidoJson_'], JSON_UNESCAPED_UNICODE);
$idPedido = $_POST['idOp_'];
$idMolde = 'item_' . $_POST['idMolde_'];

$queryAt = "UPDATE `Esteira` SET `pedidoJson`='$updateJson' WHERE idGeral='" . $idPedido . "' AND idMolde='" . $idMolde . "'";
mysqli_query($conn, $queryAt);

echo ($updateJson);
