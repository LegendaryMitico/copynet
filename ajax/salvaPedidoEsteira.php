<?php
//atualiza o database do pedido com base no json recebido e o id do pedido via ajax (linha 636 de pedido.php)
include_once("../php/connect.php");
session_start( );
$updateJson = json_encode($_POST['pedidoJson'],JSON_UNESCAPED_UNICODE);
$idPedido = $_POST['idPedido'];
$idMolde = $_POST['idMolde'];
$imagem = $_POST['imagem_alterada'];
$quantidades = $_POST['quantidades'];
$novaHora = $_POST['horaUpdate'];
$novaData = $_POST['dataEntrega_'];


$queryAt = "UPDATE `Esteira` SET `pedidoJson`='$updateJson',`imagem`='$imagem',`horaEntrega`='$novaHora',`Quantidades`='$quantidades',`dataEntrega`='$novaData'  WHERE idGeral='".$idPedido."' AND idMolde='".$idMolde."'";
mysqli_query($conn, $queryAt);

//alterando a imagem de fora (quantidade, hora entrega, imagem)
//$queryImagem = "UPDATE `Esteira` SET `imagem`='$imagem' WHERE idGeral='".$idPedido."' AND idMolde='".$idMolde."'";
//mysqli_query($conn, $queryImagem);

?>