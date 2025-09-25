<?php
session_start(); //pelo nome do cliente
//esse script é usado para deletar uma op da esteira de produção, muita atenção aqui pois tem controle de visibilidade
include_once("../php/connect.php");
date_default_timezone_set('America/Manaus'); //horário

$envioData = strval(date('d/m/Y'));
$envioHora = date('H:i:s');

//pegando dados
$idOp = $_POST['idOp_'];
$idMolde = $_POST['idMolde_'];
$pessoa = mb_strtoupper($_SESSION['usuarioNome']);

    
$queryAt = "DELETE FROM `Esteira` WHERE idGeral='$idOp' AND idMolde='item_$idMolde'";
mysqli_query($conn, $queryAt);

echo("pedido #".$idOp."-".$idMolde."Deletado!");

$queryDaLixeira = "INSERT INTO `Lixeira`(`idOp`, `Data`,`Usuário`) VALUES ('$idOp - $idMolde','$envioData às $envioHora','$pessoa')";
mysqli_query($conn,$queryDaLixeira);

?>