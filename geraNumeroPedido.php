<?php
    include_once("php/connect.php");
    require('funcoes.php');
    session_start();
    foreach ($_SESSION as $key=>$val)
    echo $key." ".$val."<br/>";
    $pedidoJson = '';
    //data
    $date2 = date('Y/m/d');
    // essa parte cria o pedido no db com um json vazio mas preenchido com o nome do vendedor
    $pedidoQuery = "INSERT INTO `pedidos`(`idPedido`, `setor`,`vendedor`,`status`, `pedidoJson`,`vendedorNome`,`dataPedido`) VALUES ('','$_SESSION[setor]','$_SESSION[usuarioId]','aberto','$pedidoJson','$_SESSION[usuarioNome]','$date2')";
    //$pedidoQuery = "INSERT INTO `pedidos`(`idPedido`, `setor`,`vendedor`,`status`, `pedidoJson`,`vendedorNome`) VALUES ('','$_SESSION[setor]','$_SESSION[usuarioId]','aberto','$pedidoJson','$_SESSION[usuarioNome]')";
    $resultado_token = mysqli_query($conn, $pedidoQuery);
    //e aqui após inserir ele pega o ultimo id que acabou de ser inserido
    $pegaIdQuery = "SELECT LAST_INSERT_ID() FROM `pedidos`";
    $resultadoPedido = mysqli_query($conn, $pegaIdQuery);
    $resultadot = mysqli_fetch_assoc($resultadoPedido);
    //echo($resultadot["LAST_INSERT_ID()"]);
    $date = date('Y-m-d');
    
    
    //e por último atualiza o pedido (JSON DO PEDIDO) com o valor do pedido atual (o último elemento inserido do database)
    $updateJson = '{"parcelas":[],"categoria":{"id":14623371282},"controleApp":[-1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],"comprovantes":[],"id": "'.$resultadot["LAST_INSERT_ID()"].'","numero":"","numeroLoja":"","data":"'.$date.'","dataSaida":"'.$date.'","dataPrevista":"'.$date.'","totalProdutos":0,"total":0.00,"contato":{"id":0,"nome":"","tipoPessoa":"F","numeroDocumento":""},"situacao":{"id":6,"valor":0},"loja":{"id":204245993},"itens": [],"vendedor": {"id": "'.intval($_SESSION["idBling"]).'"},"transporte":{"fretePorConta":1,"tipoFrete":"Balcão de entrega","frete":0,"quantidadeVolumes":0,"pesoBruto":0,"prazoEntrega":0,"contato":{"id":0,"nome":""},"etiqueta":{"nome":"","endereco":"","numero":"","complemento":"","municipio":"","uf":"","cep":"","bairro":"","nomePais":"Brasil"},"volumes":[]},"observacoesInternas":{"horaEntrega":"16:00"}}';
    //echo($updateJson);
    $queryAt = "UPDATE `pedidos` SET `pedidoJson`='$updateJson' WHERE idPedido='".$resultadot['LAST_INSERT_ID()']."'";
    mysqli_query($conn, $queryAt);
    if(isset($_GET['novo'])){
       header("Location:meusPedidos.php"); 
    }else{
       mkdir(__DIR__.'/pedidos/'.$resultadot["LAST_INSERT_ID()"].'/', 0777, true);
       header("Location:Pedido.php?numeroPedido=".$resultadot["LAST_INSERT_ID()"]); 
    }
    
?>
