<?php
include_once("../php/connect.php");
ini_set('default_charset','UTF-8'); //encoding
mysqli_set_charset($conn, "utf8");
session_start();

//pegando dados
$idOp = $_POST['idOp_'];
$idMolde = $_POST['idMolde_'];
$motivo = $_POST['resultado_'];
//dados de data e hora
date_default_timezone_set('America/Manaus'); //horário
session_start(); //pelo nome do cliente

//pegando a movimentação que já tem
$pegaIdQuery = "SELECT historico FROM `Esteira` WHERE `idGeral`='$idOp' AND `idMolde`='item_$idMolde'";
$resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
$FinalPedido = mysqli_fetch_assoc($resultado_pegaId);
    
    $pessoa = mb_strtoupper($_SESSION['usuarioNome']);
    
    $jsonRecebido = $FinalPedido['historico'];
    
    if($jsonRecebido==''){
        $jsonRecebido = '{"movimentacao":[],"abate":[]}';
    }
    
    $historico = json_decode($jsonRecebido,true);
    
    $envioData = strval(date('d/m/Y'));
    $envioHora = date('H:i:s');

    $historico['movimentacao'] = array_merge($historico['movimentacao'],array($pessoa." parou essa op em ".$envioData." às ".$envioHora. ", motivo: " .$motivo));
    
    $final = json_encode($historico,JSON_UNESCAPED_UNICODE);
    //print_r(json_decode($final));

$queryAt = "UPDATE `Esteira` SET `status`='parado', `historico`='$final' WHERE idGeral='$idOp' AND idMolde='item_$idMolde'";
mysqli_query($conn, $queryAt);

echo('status do pedido: '.$idOp .'-'.$idMolde.'alterado para parado //'.$motivo);
?>