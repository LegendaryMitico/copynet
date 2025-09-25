<?php
// Esse script é usado para mover a OP entre os setores, muita atenção aqui pois tem controle de visibilidade
include_once("../php/connect.php");

// Pegando dados
$idOp = $_POST['idOp'];
$idMolde = $_POST['idMolde_'];
$novoSetor = $_POST['novoSetor'];
$observacao = $_POST['observacao']; // Recebendo a observação

// Lista de setores
$setores = [
    ['10.FASE INICIAL', 10], ['20.PRODUÇÃO', 20], ['22.PRONTA ENTREGA', 22],
    ['15.MODELAGEM', 15], ['2.SUBLIMAÇÃO', 2], ['3.CORTE MALHARIA', 3],
    ['25.CORTE TECIDO', 25], ['11.ESTAMPARIA', 11], ['12.PEQUENOS FORMATOS', 12],
    ['13.BORDADO', 13], ['14.VINILPERS', 14], ['23.DTF', 23],
    ['5.ATELIÊ', 5], ['6.PASSADEIRAS', 6], ['7.REVISÃO', 7],
    ['8.EMBALAGEM', 8], ['24.FACÇÃO JANETH', 24], ['9.CONCLUÍDO', 9],
    ['--16.ENTREGUE--', 16], ['--PARADOS--', 0]
];
// Função para obter o nome do setor pelo ID
function getNomeSetor($idSetor, $setores) {
    foreach ($setores as $setor) {
        if ($setor[1] == $idSetor) {
            return $setor[0];
        }
    }
    return "Setor Desconhecido"; // Caso não encontre o setor
}
// Substituir número do setor pelo nome
$nomeSetor = getNomeSetor($novoSetor, $setores);
// Dados de data e hora
date_default_timezone_set('America/Manaus'); // Definindo o fuso horário
session_start(); // Iniciando a sessão para obter o nome do usuário

// Pegando a movimentação que já tem
$pegaIdQuery = "SELECT historico, historicoPedido FROM `Esteira` WHERE `idGeral`='$idOp' AND `idMolde`='item_$idMolde'";
$resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
$FinalPedido = mysqli_fetch_assoc($resultado_pegaId);

// Movimentação existente
$jsonRecebido = $FinalPedido['historico'];
if ($jsonRecebido == '') {
    $jsonRecebido = '{"movimentacao":[],"abate":[]}';
}

$historico = json_decode($jsonRecebido, true);

$pessoa = mb_strtoupper($_SESSION['usuarioNome']);
$envioData = strval(date('d/m/Y'));
$envioHora = date('H:i:s');

// Adicionando a movimentação com a observação
$historico['movimentacao'] = array_merge($historico['movimentacao'], array($pessoa . " moveu para " . $nomeSetor . " em " . $envioData . " às " . $envioHora));
if ($observacao) {
    $historico['movimentacao'] = array_merge($historico['movimentacao'], array($pessoa . " OBS: " . $observacao . " em " . $envioData . " às " . $envioHora));
}

$final = json_encode($historico, JSON_UNESCAPED_UNICODE);

// Atualizando o histórico geral
$queryAt = "UPDATE `Esteira` SET `setorAtual`='$novoSetor',`historico`='$final' WHERE idGeral='$idOp' AND idMolde='item_$idMolde'";
mysqli_query($conn, $queryAt);

// Gerenciar o histórico de setores
$jsonHistoricoPedido = $FinalPedido['historicoPedido'];
if ($jsonHistoricoPedido == '') {
    $jsonHistoricoPedido = '{"historicoSetor":[]}';
}
$historicoPedido = json_decode($jsonHistoricoPedido, true);
$historicoPedido['historicoSetor'][] = array(
    "novoSetor" => $nomeSetor,
    "data" => $envioData
);
$finalHistoricoPedido = json_encode($historicoPedido, JSON_UNESCAPED_UNICODE);
// Atualizar a coluna historicoPedido
$queryAtHistoricoPedido = "UPDATE `Esteira` SET `historicoPedido`='$finalHistoricoPedido' WHERE idGeral='$idOp' AND idMolde='item_$idMolde'";
mysqli_query($conn, $queryAtHistoricoPedido);
echo("pedido #" . $idOp . "-" . $idMolde . " movido para: " . $nomeSetor);
?>
