<?php
    include_once("../php/connect.php");
    ini_set('default_charset','UTF-8'); //encoding
    mysqli_set_charset($conn, "utf8");
    session_start();
    
    //pegando dados
    $idOp = $_POST['idOp_'];
    $idMolde = $_POST['idMolde_'];
    
    $queryAt = "UPDATE `Esteira` SET `status`='ativo' WHERE idGeral='$idOp' AND idMolde='item_$idMolde'";
    mysqli_query($conn, $queryAt);
    
    echo('status do pedido: '.$idOp .'-'.$idMolde.'alterado para parado');

?>