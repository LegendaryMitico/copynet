<?php
date_default_timezone_set('America/Manaus');

include_once("php/connect.php"); //conexão
//include_once("funcoes.php"); //todas as funções
//essa parte controla o pedido armazenado no database, e gera pedido novo ('se n tiver')  
if(isset($_GET['numeroPedido'])){
    $pegaIdQuery = "SELECT * FROM `pedidos` WHERE `idPedido`='$_GET[numeroPedido]'";
    $resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
    $FinalPedido = mysqli_fetch_assoc($resultado_pegaId);
    //var_dump($FinalPedido);
}else{
    //header("Location:geraNumeroPedido.php");
    echo("id do pedido não encontrado, chame o suporte :>");
}
$pedidoJson = get_object_vars(json_decode($FinalPedido['pedidoJson']));
//print_r($json_encode($FinalPedido['pedidoJson']));
?>

<script>
//passando os dados pro javascript :) obs: somente o vendedor que criou pode alterar o pedido ou usuário com acesso e criando o objeto produtos vazio
var pedido = JSON.parse(<?php echo(json_encode($FinalPedido['pedidoJson'])); ?>);
//pedido.produtos  = {};
console.log(pedido);
</script>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- framework css -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- gerador de pdf (importante) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <title>FICHA TECNICA PERSONAL</title>
</head>
<body>
    <style>
        table, th, td {
          border:1px solid grey;
          width: 1100px;
          font-weight: normal;
        }  
        .center{
            text-align:center;   
        }
        .left{
            text-align:left;
        }
        .right{
            text-align:right;
        }
        .fonte-pequena{
            font-size:12px;
        }
        .espaco{
            height:10px;
        }
        .texto-forte{
            font-weight:bold;
        }
        .fonte-grande{
            font-size:18px;
        }
    </style>
    
    <script>
        
    </script>
    
<!-- usage open (parte que vai ser impressa) -->
<!-- tabela de orçamento -->
<table id='tabela' width='1500px'>
  <tr>
    <td rowspan='5' colspan='6'><img src='img/personal-logo.png' width='150px'></td>
  </tr>
  <tr><td class='right fonte-pequena' colspan='5'>R K S RODRIGUES FABRICACAO EIRELI - (95) 3627-6940</td></tr>
  <tr><td class='right fonte-pequena' colspan='5'>Avenida Nossa Senhora de Nazaré, N° 858, Fabrica Matriz</td></tr>
  <tr><td class='right fonte-pequena' colspan='5'>69312305 - Boa Vista, RR</td></tr>
  <tr><td class='right fonte-pequena' colspan='5'>CNPJ: 07.610.743/0001-22, IE: 240160044</td></tr>
  <tr>
      <td colspan='11' class='fonte-grande center texto-forte'>Pedido <?php echo($FinalPedido['idPedido']); ?></td>
  </tr>
  <tr>
      <td class='left' colspan='11'>Cliente</td>
  </tr>
  <tr>
      <td colspan='8' rowspan='4'>
          Nome: <br>
          Código: <br>
          CPF: <br>
          Endereço: <br>
      </td>
  </tr>
  <tr><td colspan='3'>Número do Pedido: <?php echo($FinalPedido['idPedido']);?></td></tr>
  <tr><td colspan='3'>Data: <?php echo($pedidoJson['data']);?></td></tr>
  <tr><td colspan='3'>Data prevista: <?php echo($pedidoJson['dataPrevista']);?></td></tr>
  <tr>
      <td class='left' colspan='6'>Vendedor</td>
      <td class='left' colspan='5'>Loja</td>
  </tr>
  <tr>
      <td colspan='6'><?php echo($_SESSION['usuarioNome']);?></td>
      <td colspan='5'>Personal Confecções</td>
  </tr>
  <tr class='center'>
    <td colspan='6'>Descrição do produto</td>
    <td>Código</td>
    <td>Un.</td>
    <td>Qtd.</td>
    <td>Valor unit.</td>
    <td>Valor total</td>
  </tr>
  <tr>
    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
  </tr>
</table>

<script>
    var totalPedido = 0;
    var nomeProduto = '';
    $.each(pedido.produtos,function(key,value){
        nomeProduto = value.molde.nome;
            $.each(value.quantidades,function(key,value){
                if(value.quantidade != 0){ //passando os dados do produto para itens
                    console.log(value);
                    var linha = "<tr class='left'><td colspan='6' style='font-size:12px'>"+nomeProduto+"</td><td>"+value.codigo+"</td><td>UN</td><td>"+value.quantidade+"</td><td>"+value.preco+"</td><td>"+((value.quantidade)*(value.preco)).toFixed(2)+"</td></tr>";
                    $('#tabela').append(linha);
                    //itemAtual.id = value.id;
                    //itemAtual.quantidade = value.quantidade;
                    //itemAtual.valor = value.preco;
                    //itemAtual.descricao = "";
                    //itemAtual.codigo = value.codigo;
                    //itemAtual.produto = {"id": value.id};
                    
                    //e aqui inserindo em itens
                    //itens.push(JSON.parse(JSON.stringify(itemAtual)));
                    totalPedido = ((value.quantidade)*(value.preco)).toFixed(2);
            }
        })
    });
    $('#tabela').append("<tr class='right'><td colspan='10'>TOTAL</td><td>"+totalPedido+"</td></tr>");
</script>

<script>
    //xepOnline.Formatter.Format('Usage',{render:'download'}); //parte que gera o pdf
</script>
</body>
</html>