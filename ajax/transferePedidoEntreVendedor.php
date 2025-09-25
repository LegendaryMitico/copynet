<?php
include_once("../php/connect.php");
ini_set('default_charset','UTF-8'); //encoding
mysqli_set_charset($conn, "utf8");
session_start();                            
                            
//apenas uma query simples    
$queryAt = "UPDATE `pedidos` SET `vendedor`='".$_POST['idVendedorDestino']."',`status`='aberto',`setor`='1' WHERE idPedido='".$_POST['numeroPedido']."'";
mysqli_query($conn, $queryAt);

echo("pedido tranferido para".$_POST['idVendedorDestino']);
?>