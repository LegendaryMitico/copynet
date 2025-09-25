<?php
date_default_timezone_set('America/Manaus');
include_once("php/connect.php"); //conexão
//include_once("funcoes.php"); //todas as funções
//essa parte controla o pedido armazenado no database, e gera pedido novo ('se n tiver')  
if(isset($_GET['numeroPedido'])){
    $pegaIdQuery = "SELECT * FROM `pedidos` WHERE `idPedido`='$_GET[numeroPedido]'";
    $resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
    $FinalPedido = mysqli_fetch_assoc($resultado_pegaId);
    
}else{
    //header("Location:geraNumeroPedido.php");
    echo("id do pedido não encontrado, chame o suporte :>");
}
//Pedido <?php echo($FinalPedido['idPedido']);
$pedidoJson = get_object_vars(json_decode($FinalPedido['pedidoJson']));
$idMolde = $_GET['idMolde'];
session_start();
$usuario = $_SESSION['usuarioNome'];
$vendedor = strval('{"nome":"'.$usuario.'"}');
?>
<html>
    <head>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    </head>
    <body>
        <style>
        table, th, td {
          border:1px solid grey;
          border-collapse: collapse;
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
            font-size:25px;
        }
        .espaco{
            height:10px;
        }
        .texto-forte{
            
        }
        .fonte-grande{
            font-size:22px;
        }
        .tamanhos{
            height:105px;
            display: flex;
            justify-content: center;
        }
        
        #table {
          align-self: center;
        }
        
        .fonte-extra-grande{
            font-size:28px;
        }

    </style>
    
        <div id='tempPedidoBody'>
            teste
        </div>
    </body>
</html>


<script>
//passando os dados pro javascript :) obs: somente o vendedor que criou pode alterar o pedido ou usuário com acesso e criando o objeto produtos vazio
var pedido = JSON.parse(<?php echo(json_encode($FinalPedido['pedidoJson'])); ?>);
var idMoldeAtual = JSON.parse(<?php echo(json_encode($idMolde)); ?>);
var vendedor = JSON.parse(<?php echo(json_encode($vendedor)); ?>);
console.log(vendedor);
$("#tempPedidoIdMolde").text('#'+pedido.id+'-'+idMoldeAtual);
      
    var nomeProduto = '';
    var composicoes = '';
    var aplicacoes = '';
    var tamanhos = '';
    //gerando o pedido com todos os produtos
        var value = pedido.produtos['item_'+idMoldeAtual];
        var idMolde = idMoldeAtual;
        console.log(value);
        $("#tempPedidoBody").empty();
        $("#tempPedidoBody").append("<table id='tabela_10' style='width:980px'></table>")
        $("#tabela_10").append("<tr><td rowspan='3' colspan='2' class='center'><img src='img/personal-logo.png' width='150px'></td></tr>"); //linha 1 imagem
        $("#tabela_10").append("<tr style='height:78px'><td class='left fonte-grande' colspan='11' valign='top'>Modelo: "+value.molde.nome+"</td></tr>"); //Modelo
        $("#tabela_10").append("<tr><td class='left fonte-grande' colspan='11'>Referência: "+value.molde.codigo+"</td></tr>");//
        $("#tabela_10").append("<tr><td colspan='2' class='fonte-pequena'>Data do Pedido: <br>"+pedido.data.split("-").reverse().join("-")+"</td><td colspan='2' class='fonte-pequena' style='color:red'>Data/Hora entrega: <br>"+pedido.dataPrevista.split("-").reverse().join("-")+","+pedido.observacoesInternas.horaEntrega+"h</td><td colspan='3' class='fonte-pequena'>Vendedor:<br>"+vendedor.nome+"</td><td class='fonte-pequena' colspan='1'>Pedido: <br>#"+pedido.id+'-'+idMolde+"</td></tr>");
        $("#tabela_10").append("<tr><td class='left fonte-grande' colspan='11'>Cliente: "+pedido.contato.nome+"</td></tr><tr><td colspan='11' class='fonte-grande center fonte-extra-grande texto-forte'>FICHA TÉCNICA DO PRODUTO</td></tr>");
        $("#tabela_10").append("<tr><td colspan='11' style='text-align:center;padding:10px'><img src='"+value.molde.imagem+"' width='90%'></td></tr>"); //imagem
        $("#tabela_10").append("<tr><td colspan='11' class='fonte-grande center texto-forte' style='color:red'>ATENÇÃO: Verifique com atenção as informações, artes, textos, quantidades e tamanhos informados.</td></tr>");
        $("#tabela_10").append("<tr class='center'><td colspan='11'><div class='texto-grande tamanhos' id='tamanhosTemp_"+idMolde+"'></div></td></tr>"); //tamanhos
        
        $("#tamanhosTemp_"+idMolde).append("");
        
        $("#tabela_10").append("<tr><td colspan='3' class='fonte-grande center texto-forte'>OBSERVAÇÕES</td><td colspan='5' class='fonte-grande center texto-forte'>COMPOSIÇÃO<span id='totalGeralProdutos' style='float:right;font-size:24px;font-weight:normal;line-height:2.5;border:1px solid grey'></span></td></tr>");
        $("#tabela_10").append("<tr class='left' valign='top' style='height:80px'><td colspan='3' class='fonte-grande' style='color:red;font-weight:bold'>"+value.molde.Observacoes+"</td><td colspan='5' style='height:25px;font-size:12px' id='composicao_"+idMolde+"'></td></tr>");
        $("#tabela_10").append("<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></tr>"); //Última linha
        
        //aqui inserindo os tamanhos
        nomeProduto = value.molde.nome;
        tamanhos += "<table width='100%'><tr class='center fonte-grande texto-forte'>";
            //primeiro os tamanhos
            if(pedido.produtos['item_'+idMoldeAtual]?.quantidades?.[0]?.altura != undefined){
                tamanhos+= "<td class='texto-forte'>"+'Altura'+"</td>";
                tamanhos+= "<td class='texto-forte'>"+'largura'+"</td>";
                tamanhos+= "<td class='texto-forte'>"+'Quantidade'+"</td>";
                tamanhos+= "</tr><tr class='center fonte-grande'>"; //linha debaixo
                tamanhos+= "<td class='texto-forte'>"+value.quantidades[0].altura+"</td>";
                tamanhos+= "<td class='texto-forte'>"+value.quantidades[0].largura+"</td>";
                tamanhos+= "<td class='texto-forte'>"+value.quantidades[0].quantidade+"</td>";
                tamanhos+="</tr></table>";
                 
            }else{
                var totalGeralProdutos = 0;//variável usada apenas para pegar o total de produtos do molde
                    $.each(value.quantidades,function(key,value){
                        tamanhos+= "<td class='texto-forte' style='font-weight:normal'>"+value.nome+"</td>";
                    });
                    tamanhos+= "</tr><tr class='center fonte-grande'>";
                    //depois as quantidades de cada
                    $.each(value.quantidades,function(key,value){
                        if(value.quantidade==0){
                            tamanhos+= "<td class='texto-forte'> </td>";
                        }else{
                            tamanhos+= "<td class='texto-forte'>"+value.quantidade+"</td>";
                            totalGeralProdutos += parseInt(value.quantidade);
                        }
                        
                    });
                    tamanhos+="</tr></table>";
            $('#totalGeralProdutos').text('Total de peças: '+totalGeralProdutos); //aqui só inserindo o textinho de total de peças
                }
            //e por ultimo inserindo na tabela    
            $("#tamanhosTemp_"+idMolde).append(tamanhos);
            
        //aqui inserindo as composições
        composicoes = value.molde.descricaoCurta.composicao;
        $.each(composicoes,function(key,value){
            if(composicoes[key][((composicoes[key].length)-1)].selecionado != ''){
            $("#composicao_"+idMolde).append("<b>"+key+": </b>");
            $("#composicao_"+idMolde).append(composicoes[key][((composicoes[key].length)-1)]+"<br>");
            }
        });
        
        //aqui as aplicações
        aplicacoes = value.aplicacoes;
        $.each(aplicacoes,function(key,value){
            console.log(value);
            $("#composicao_"+idMolde).append("<b>"+value.nome+"</b><br>");
        });
        
</script>
