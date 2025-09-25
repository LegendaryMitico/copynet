

<?php
include_once("php/connect.php");
ini_set('default_charset','UTF-8'); //encoding
mysqli_set_charset($conn, "utf8");
session_start();                            
                            
//apenas uma query simples    
$queryAt = "UPDATE `pedidos` SET `setor`='20' WHERE idPedido='".$_GET['numeroPedido']."'";
mysqli_query($conn, $queryAt);
?>
<script>
    alert("Pedido enviado para o financeiro, em breve iniciaremos a produção :)");
    location.href = "meusPedidos.php";
</script>