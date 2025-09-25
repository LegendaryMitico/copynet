<?php
//primeiro pegando os dados do pedido pelo id 
    print($_GET['numeroPedido']);
    $idPedido = $_GET['numeroPedido'];
    
     include_once("php/connect.php");
     require('funcoes.php');
    //Buscar na tabela pedido o pedido que corresponde ao id passado na url
        $result_molde = "SELECT * FROM pedidos WHERE idPedido = '$idPedido' LIMIT 1";
        $resultado_molde = mysqli_query($conn, $result_molde);
        $resultado = mysqli_fetch_assoc($resultado_molde);
        
    //print_r($resultado['pedidoJson']);  
    //convertendo o resultado do jquery em um json (para poder editá-lo)
    $dados = $resultado['pedidoJson'];
    
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
</head>
<body>

<script>
    var pedido = JSON.parse(<?php echo(json_encode($dados)); ?>);//passando a venda do php pro js para ser tratada
    var pedidoFabrica = JSON.parse(JSON.stringify(pedido));
    
    //após gerar a venda direitinho, envie o pedido para a produção :) ============================= somente se tiver tudo okay
    var moldes = JSON.parse(JSON.stringify(pedidoFabrica.produtos));
    delete(pedidoFabrica.produtos);

    $.each(moldes,function(key,value){
            var moldeAtual = JSON.parse(JSON.stringify(pedidoFabrica));
            moldeAtual['produtos'] = [];
            moldeAtual.produtos[key] = JSON.parse(JSON.stringify(value));
            $.ajax({
                    type: "POST",
                    url: 'ajax/insereBancoDeDados.php',
                    data: jQuery.param({pedidoJson: moldeAtual,molde:value,item:key}),
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function(data)
                    {
                            alert('Pedido enviado para a produção!');
                            window.location.href = 'https://rksrodrigues.com/movimentacao.php?setorAtual=10';
                    }
                });
          });
    
</script>

<?php
//e por fim, se tudo correr bem mude o status do pedido para producao (para indicar que ele foi para produção)
$queryAt = "UPDATE `pedidos` SET `status`='producao' WHERE idPedido='".$idPedido."'";
mysqli_query($conn, $queryAt);
?>


</body>
</html>



