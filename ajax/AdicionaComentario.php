<?php
// Esse script é usado para mover a OP entre os setores, muita atenção aqui pois tem controle de visibilidade
include_once("../php/connect.php");

// Pegando dados
$idOp = $_POST['idOp'];
$idMolde = $_POST['idMolde_'];
$novoSetor = $_POST['novoSetor'];
$observacao = $_POST['observacao']; // Recebendo a observação

// Dados de data e hora
date_default_timezone_set('America/Manaus'); // Definindo o fuso horário
session_start(); // Iniciando a sessão para obter o nome do usuário

// Pegando a movimentação que já tem
$pegaIdQuery = "SELECT historico FROM `Esteira` WHERE `idGeral`='$idOp' AND `idMolde`='item_$idMolde'";
$resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
$FinalPedido = mysqli_fetch_assoc($resultado_pegaId);

$pessoa = mb_strtoupper($_SESSION['usuarioNome']);
$setor1 = $novoSetor;

$jsonRecebido = $FinalPedido['historico'];

if ($jsonRecebido == '') {
    $jsonRecebido = '{"movimentacao":[],"abate":[]}';
}

$historico = json_decode($jsonRecebido, true);

$envioData = strval(date('d/m/Y'));
$envioHora = date('H:i:s');

// Adicionando a movimentação com a observação
$historico['movimentacao'] = array_merge($historico['movimentacao'], array($pessoa . " Comentou: " . $observacao . " em " . $envioData . " às " . $envioHora));

$final = json_encode($historico, JSON_UNESCAPED_UNICODE);

$queryAt = "UPDATE `Esteira` SET `historico`='$final' WHERE idGeral='$idOp' AND idMolde='item_$idMolde'";
mysqli_query($conn, $queryAt);

echo("pedido #" . $idOp . "-" . $idMolde . " movido para: " . $novoSetor);
?>
