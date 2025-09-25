<?php
include_once("php/connect.php");
ini_set('default_charset','UTF-8'); //encoding
mysqli_set_charset($conn, "utf8");
session_start();                            
                            
//apenas uma query simples    
$queryAt = "UPDATE `pedidos` SET `setor`='1' WHERE idPedido='".$_GET['numeroPedido']."'";
mysqli_query($conn, $queryAt);
?>

<script>
    alert("pedido devolvido para o vendedor :(");
    location.href = "meusPedidos.php";
</script>