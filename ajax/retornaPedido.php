<?php
date_default_timezone_set('America/Manaus');

include_once("../php/connect.php"); //conexão
//include_once("funcoes.php"); //todas as funções
//essa parte controla o pedido armazenado no database, e gera pedido novo ('se n tiver')  
if(isset($_POST['numeroPedido'])){
    $pegaIdQuery = "SELECT * FROM `Esteira` WHERE `idGeral`='$_POST[numeroPedido]' AND `idMolde`='item_$_POST[numeroMolde]'";
    $resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
    $FinalPedido = mysqli_fetch_assoc($resultado_pegaId);
}else{
    //header("Location:geraNumeroPedido.php");
    echo("id do pedido não encontrado, chame o suporte :>");
}
echo(json_encode($FinalPedido['pedidoJson']));
?>
