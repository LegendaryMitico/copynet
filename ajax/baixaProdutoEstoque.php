<?php
//esse código abaixa o estoque do produto no estoque, extremamente importante
function baixaEstoque($token,$idProduto,$quantidade,$deposito,$pedidoId){
    
    $identificador = $pedidoId;
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.bling.com.br/Api/v3/estoques',
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_ENCODING=>'',
    CURLOPT_MAXREDIRS=>10,
    CURLOPT_TIMEOUT=>0,
    CURLOPT_FOLLOWLOCATION=>true,
    CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST=>'POST',
    CURLOPT_POSTFIELDS=>'{
    "deposito":{
    "id":'.$deposito.'
    },
    "operacao":"S",
    "produto":{
    "id":'.$idProduto.'
    },
    "quantidade":'.$quantidade.',
    "preco":"<float>",
    "custo":"<float>",
    "observacoes":"'.$identificador.'"
    }',
    CURLOPT_HTTPHEADER=>array(
    'Content-Type:application/json',
    'Accept:application/json',
    'Authorization: Bearer '.$token.''
    ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    echo $response;
}

require('../php/connect.php');
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    $token = $resultadot["valor"];
//--------------------------------//só pegando o token do data base
$idProduto = strVal($_POST['idProduto_']);
$pedidoId_ = $_POST['identificador_'];

$quantidade = floatVal($_POST['quantidade_']);
$deposito = '14886781811'; //pego diretamente do bling

baixaEstoque($token,$idProduto,$quantidade,$deposito,$pedidoId_);

//E aqui inserindo o nome da composição para saber que abateu =====================================================================================
    $composicaoAbatida = $_POST['nomeCompAtual_'];
    //echo($composicaoAbatida);
//pegando dados do pedido e molde atual para inserir a composição abatida ==
    $idPedido = explode("#",$pedidoId_)[1];
    
    $idOp = strval(explode("-",$idPedido)[1]);
    $idMolde = strval(explode("-",$idPedido)[0]);

//pegando o histórico do produto atual ----------------------------------------------------------------------------

    $pegaIdQuery = "SELECT historico FROM `Esteira` WHERE `idGeral`='$idMolde' AND `idMolde`='item_$idOp'";
    //echo($pegaIdQuery);
    $resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
    $FinalPedido = mysqli_fetch_assoc($resultado_pegaId);
    
    $jsonRecebido = $FinalPedido['historico'];

    if($jsonRecebido==''){
        $historico = json_decode('{"movimentacao":[],"abate":[]}',true);
    }else{
        $historico = json_decode($jsonRecebido,true);
    }
    
    $historico['abate'] = array_merge($historico['abate'],array($composicaoAbatida));
    
    $final = json_encode($historico,JSON_UNESCAPED_UNICODE);

    $queryAt = "UPDATE `Esteira` SET `historico`='$final' WHERE idGeral='$idMolde' AND idMolde='item_$idOp'";
    mysqli_query($conn, $queryAt);

?>