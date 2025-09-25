<?php
    include_once("../php/connect.php");
    date_default_timezone_set('America/Manaus');
    session_start();
    //foreach ($_SESSION as $key=>$val)
    //echo $key." ".$val."<br/>";

    //$pedido = json_decode($_POST['molde']);
    $item = $_POST['item'];
    $pedido = json_decode(json_encode($_POST['pedidoJson'],JSON_UNESCAPED_UNICODE));
    $pedido->produtos[$item] = json_decode(json_encode($_POST['molde'],JSON_UNESCAPED_UNICODE));
    $cliente = $pedido->contato->nome;
    $data = $pedido->dataPrevista;
    $idPedido = $pedido->id;
    $horaEntrega = strval($pedido->observacoesInternas->horaEntrega);
    $pedido = json_encode($pedido,JSON_UNESCAPED_UNICODE);
    $tamanhos = json_encode(json_decode(json_encode($_POST['molde'],JSON_UNESCAPED_UNICODE))->quantidades);
    $imagem = json_encode(json_decode(json_encode($_POST['molde'],JSON_UNESCAPED_UNICODE))->molde->imagem);
    $imagem2 = trim($imagem, '"');
    $produtoNome = json_encode(json_decode(json_encode($_POST['molde'],JSON_UNESCAPED_UNICODE))->molde->nome,JSON_UNESCAPED_UNICODE);
    $produtoNome = trim($produtoNome, '"');
    
    $idBlingVendedor = strval(json_encode(json_decode(json_encode($_POST['pedidoJson'],JSON_UNESCAPED_UNICODE))->vendedor->id,JSON_UNESCAPED_UNICODE));
    
    //$vendedor = $idBlingVendedor;
    
    //fazendo a query para pegar o nome do vendedor
    $query = "SELECT `nome_usuario` FROM `usuarios` WHERE `idBling` = $idBlingVendedor";
    $resultado_ = mysqli_query($conn, $query);
    $FinalNome1 = mysqli_fetch_assoc($resultado_);
    
    
    $FinalNome = $FinalNome1['nome_usuario'];
    //e passando o nome do vendedor pro json
    $envioData = strval(date('d/m/Y'));
    $envioHora = date('H:i:s');
    $dataDeCriacao = strval('{"movimentacao":["OP criada em '.$envioData.' às '.$envioHora.'"],"abate":[]}');
    
    
    
    // essa parte cria o pedido no db com um json vazio mas preenchido com o nome do vendedor
    //$pedidoQuery = "INSERT INTO `pedidos`(`idPedido`, `setor`,`vendedor`,`status`, `pedidoJson`) VALUES ('','$_SESSION[setor]','$_SESSION[usuarioId]','aberto','$pedidoJson')";
    
    $pedidoQuery = "INSERT INTO `Esteira`(`contador`, `idGeral`, `idMolde`, `cliente`, `idVendedor`, `setorAtual`, `dataEntrega`, `pedidoJson`,`horaEntrega`,`Quantidades`,`imagem`,`nomeProduto`,`status`,`historico`) VALUES ('','$idPedido','$item','$cliente','$FinalNome','10','$data','$pedido','$horaEntrega','$tamanhos',' $imagem2','$produtoNome','ativo','$dataDeCriacao')";

    $resultado_token = mysqli_query($conn, $pedidoQuery);
?>