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

<style>
    .a-buton {
        text-decoration: none;
        color: white;
        background: black;
        padding: 5px 20px;
    }
</style>

<body>

<div style="margin-top: 20px;">
    <a href="https://rksrodrigues.com/Pedido.php?numeroPedido=<?= $idPedido ?>" target="_blank" class="a-buton">Painel do Pedido</a>
    <a href="https://www.bling.com.br/vendas.php#list" target="_blank" class="a-buton">Verifica Pedido no Bling</a>
</div>

<script>
    var pedido = JSON.parse(<?php echo(json_encode($dados)); ?>);//passando a venda do php pro js para ser tratada
    var vendedor = JSON.parse(JSON.stringify(<?php echo(json_encode($_SESSION)); ?>));//passando dados do vendedor logado da session pro js 
    var pedidoFinal = JSON.parse(JSON.stringify(pedido));
    
    var pedidoFabrica = JSON.parse(JSON.stringify(pedido));
    
    //transformando produtos em itens para compor a venda
    function geraVendaBling(pedido){
        //copiando os produtos para n alterar os dados originais recebidos
        var produtos = JSON.stringify(pedido.produtos);
        var itens = []; //gerando array vazio que irá receber os produtos
        var itemAtual = {
                      "id": "16124498123",
                      "quantidade": "1",
                      "valor": "164",
                      "descricao": "sem descrição",
                      "codigo": "COMC018T2M",
                      "unidade": "UN",
                      "desconto": "0,00",
                      "aliquotaIPI": "0,00",
                      "descricaoDetalhada": "",
                      "produto": {
                      "id": "16124498123"
                      }
                    };
                    
       //================================================================================================================Tamanhos
       $.each(pedido.produtos,function(key,value){
            $.each(value.quantidades,function(key,value){
                if(value.quantidade != 0){ //passando os dados do produto para itens

                    itemAtual.id = value.id;
                    itemAtual.quantidade = value.quantidade;
                    itemAtual.valor = value.preco;
                    itemAtual.descricao = "";
                    itemAtual.codigo = value.codigo;
                    itemAtual.produto = {"id": value.id};
                    
                    //e aqui inserindo em itens
                    itens.push(JSON.parse(JSON.stringify(itemAtual)));
                }
            })
        });  
        
        //================================================================================================================Aplicações
        
            $.each(pedido.produtos,function(key,value){
                if(value.aplicacoes != undefined){
                    $.each(value.aplicacoes,function(key,value){
                            if(value.quantidade != 0){ //passando os dados do produto para itens
                                //console.log(value);
                                itemAtual.id = value.idApp;
                                itemAtual.quantidade = value.quantidade;
                                itemAtual.valor = value.valor;
                                itemAtual.descricao = value.observacoes;
                                itemAtual.codigo = value.codigo;
                                itemAtual.produto = {"id": value.idApp};
                                
                                //e aqui inserindo em itens
                                itens.push(JSON.parse(JSON.stringify(itemAtual)));
                            }
                    })
                }
            });
        
        //Formas de pagamento (parcelas) ==========================================================================================
        var parcelas = [];
        var itemParcela = {"id":"","dataVencimento":"","valor":"","observacoes":"gerado via api","formaPagamento":{"id":""}};
        $.each(pedido.parcelas,function(key,value){
            //removendo o que é inútil =================================================
            delete(pedido.parcelas[key].dias);
            delete(pedido.parcelas[key].idGeral);
            pedido.parcelas[key].id = "";
            //tratando os dados ========================================================
            pedido.parcelas[key].valor = parseFloat(pedido.parcelas[key].valor);
            pedido.parcelas[key].formaPagamento.id = parseInt(pedido.parcelas[key].formaPagamento.id);
        });
        
       //inserindo os itens que irão COMPOR a venda ====================================================================
       pedido.itens = itens; 

       //REMOVENDO ITENS INÚTEIS USADOS ANTES ==========================================================================
       delete(pedido.comprovantes);
       delete(pedido.controleApp);
       delete(pedido.produtos);
       delete(pedido.id); 
       delete(pedido.transporte.tipoFrete); //deleta o tipo de frete
       
       //TRATANDO ALGUNS DADOS ============================================================================================
        pedido.loja.id = 204578072; //204578072//204245993 ID DA LOJA 
        pedido.situacao.id = parseInt(pedidoFinal.situacao.id); // SITUAÇÃO DO PEDIDO 
        pedido.situacao.valor = parseInt(pedidoFinal.situacao.valor); //VALOR DA SITUAÇÃO
        //pedido.vendedor.id = vendedor.idBling; //ID DO VENDEDOR (cuidado, tava pegando o id do financeiro :v)
        pedido.contato.id = parseInt(pedidoFinal.contato.id);  //ID DO CLIENTE DO PEDIDO
       console.log(vendedor.idBling);
       //RETORNO VENDA FINAL
       return pedido;
    }
    
    var pedidoFinal = geraVendaBling(pedido);
    console.log(pedidoFinal);
    console.log(vendedor);
    
    //enviando para o bling
     //e aqui enviando os dados do pedido (já tratados) para enviar o pedido de venda para o bling
         $.ajax({
            type: "POST",
            url: 'ajax/geraVenda.php',
            data: jQuery.param({dados: pedidoFinal,
            }),
           contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
              success: function(data){
              var retorno = JSON.parse(data);
              if(retorno?.data?.id != undefined){
                    alert("Pedido gerado com sucesso, id:"+retorno.data.id);
                    window.location.href = 'https://rksrodrigues.com/meusPedidos.php';
              } else {
                alert("Pedido gerado com sucesso, id:"+retorno.data.id);
                console.log(retorno);
              }
           }
       });
    
</script>


</body>
</html>



