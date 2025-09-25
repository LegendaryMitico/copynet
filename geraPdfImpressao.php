<?php
date_default_timezone_set('America/Manaus');

include_once("php/connect.php"); //conexão
//include_once("funcoes.php"); //todas as funções
//essa parte controla o pedido armazenado no database, e gera pedido novo ('se n tiver')  
if(isset($_GET['numeroPedido'])){
    //$pegaIdQuery = "SELECT * FROM `Esteira` WHERE `idPedido`='$_GET[numeroPedido]'";
    $pegaIdQuery = "SELECT * FROM `Esteira` WHERE `idGeral`='$_GET[numeroPedido]' AND idMolde='item_$_GET[idMolde]'";
    $resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
    $FinalPedido = mysqli_fetch_assoc($resultado_pegaId);
}else{
    //header("Location:geraNumeroPedido.php");
    echo("id do pedido não encontrado, chame o suporte :>");
}
//Pedido <?php echo($FinalPedido['idPedido']);
$pedidoJson = get_object_vars(json_decode($FinalPedido['pedidoJson']));
session_start();
$idMolde = $_GET['idMolde'];
?>

<script>
//passando os dados pro javascript :) obs: somente o vendedor que criou pode alterar o pedido ou usuário com acesso e criando o objeto produtos vazio
var pedido = JSON.parse(<?php echo(json_encode($FinalPedido['pedidoJson'])); ?>);
console.log(pedido);
var vendedor = "<?php echo($FinalPedido['idVendedor']);?>";
//pedido.produtos  = {};

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

    <title> PEDIDO #<?PHP echo($_GET['numeroPedido'].'-'.$_GET['idMolde']);?></title>
</head>
<body>
    <style>
        table, th, td {
          border:1px solid grey;
          font-size:20px;
          
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
            font-size:14px;
        }
        .espaco{
            height:10px;
        }
        .texto-forte{
            font-weight:bold;
        }
        .fonte-grande{
            font-size:28px;
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
            font-size:36px;
        }

    </style>
<!-- parte que converte os decimais em dinheiro -->
<script>
const price = 14340;
// Format the price above to USD using the locale, style, and currency.


//console.log(`The formated version of ${price} is ${USDollar.format(price).slice(0,-1)}`);
</script>

<!-- usage open (parte que vai ser impressa) -->
<div id="Usage">
    
</div>
<style>
    .fonte-padrao{
        text-align:right;
        font-size:15pxpx;
    }
    
    .pedidoFonte{
        font-size:28px;
    }
</style>


<script>
//parte que pega os dados do cliente utilizado na venda (de novo, por que não salvei antes :())
$.ajax({
                type: "POST",
                url: 'ajax/pegaDadosCliente.php',
                data: jQuery.param({codigoCliente: pedido.contato.id,
                }) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function(data)
                {
                    //console.log(data);
                    var dadosCliente = data.split(',');
                    //Aqui ele verifica se todas as informações vindas d bling estão presentes, se não tiver simplesmente não exibe
                    codigoCliente = dadosCliente[16];
                    $("#dadosClientePedido").empty();
                    $("#dadosClientePedido").append("<div style='line-height:0.9'>");
                    if(pedido.contato.nome != ''){$("#dadosClientePedido").append("<span style='font-weight:bold'>"+pedido.contato.nome+'</span>');} //nome}
                    if(dadosCliente[17] != ''){$("#dadosClientePedido").append('<br>Código: '+dadosCliente[17]);} //codigo bling}
                    if(dadosCliente[3]  != ''){
                        var cpfTemp = dadosCliente[3];
                        if(pedido.contato.tipoPessoa == 'F'){
                            cpfTemp = cpfTemp.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                            $("#dadosClientePedido").append(" || CPF: "+cpfTemp);
                        }else{
                            cpfTemp = cpfTemp.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                            $("#dadosClientePedido").append(" || CNPJ: "+cpfTemp);
                        }
                        
                    } //cpf
                    if(dadosCliente[7]  != ''){$("#dadosClientePedido").append('<br>'+dadosCliente[7]);} //endereço
                    if(dadosCliente[11]  != ''){$("#dadosClientePedido").append(', N°: '+dadosCliente[11]);} //número
                    if(dadosCliente[10]  != ''){$("#dadosClientePedido").append(', Bairro: '+dadosCliente[10]+'<br>');} //bairro
                    if(dadosCliente[8]  != ''){$("#dadosClientePedido").append(dadosCliente[9]+', '+dadosCliente[8]);} //cidade
                    if(dadosCliente[6]  != ''){
                        var cepT = dadosCliente[6].replace(/[^\d]+/g, '');
                        cepT  = cepT.replace(/(\d{5})(\d{3})/, '$1-$2');
                        $("#dadosClientePedido").append(', '+cepT);} //estado uf
                    if(dadosCliente[14] != ''){$("#dadosClientePedido").append(", Celular: "+dadosCliente[14]);} //celular
                    if(dadosCliente[15]  != ''){$("#dadosClientePedido").append("<br>Email: "+dadosCliente[15]);} // email
                    $("#dadosClientePedido").append("</div>");
                }
            });
</script>




<script>
    //parte que cria o pedido
    var totalPedido = 0;
    var nomeProduto = '';
    var composicoes = '';
    //verificando a forma de retirada
    var formaentrega = '';
    if(pedido.transporte.tipoFrete == "Balcão de entrega"){
        formaentrega = 'Av. Via da Flores, 2077 - Pricumã';
    }else{
        //trata cep
        var cepT = pedido.transporte.etiqueta.cep.replace(/[^\d]+/g, ''); // remove non digit chars.
        cepT  = cepT.replace(/(\d{5})(\d{3})/, '$1-$2');
        
        
        formaentrega = "<span style='color:black'>Forma: "+pedido.transporte.tipoFrete+"<br>Nome: "+pedido.transporte.etiqueta.nome+"<br>CEP: "+cepT+" UF: "+pedido.transporte.etiqueta.uf+" Número: "+pedido.transporte.etiqueta.numero+"<br>Bairro: "+pedido.transporte.etiqueta.bairro+"<br>Município: "+pedido.transporte.etiqueta.municipio+"<br>Endereço: "+pedido.transporte.etiqueta.endereco+"</span>";
    }
    
    //gerando o pedido com todos os produtos
    var idMoldeAtual = JSON.parse(<?php echo(json_encode($idMolde)); ?>);

    if(pedido.produtos['item_' + idMoldeAtual].pedidoExpresso != undefined) {
        var pedidoExpresso = "</td><td class='fonte-grande' colspan='1'>"+pedido.produtos['item_' + idMoldeAtual].pedidoExpresso+"</td></tr>"
    } else {
        var pedidoExpresso = "</td><td class='fonte-grande' colspan='1'>PEDIDO NORMAL</td></tr>"
    }

    $.each(pedido.produtos,function(key,value){
        var idMolde = key.split("_")[1];
        if(idMolde == idMoldeAtual){
            console.log(value);
            $("#Usage").append("<table id='tabela_10' style='width:1500px;border:none'></table>")
            $("#tabela_10").append("<tr><td rowspan='3' colspan='3' class='center'><img src='img/personal-logo.png' width='350px'></td></tr>"); //linha 1 imagem
            $("#tabela_10").append("<tr style='height:78px'><td class='left fonte-grande' colspan='11' valign='top'>Modelo: "+value.molde.nome+"</td></tr>"); //Modelo
            $("#tabela_10").append("<tr><td class='left fonte-grande' colspan='11'>Referência: "+value.molde.codigo+"</td></tr>");//
            $("#tabela_10").append("<tr><td colspan='3' class='fonte-grande'>Data do Pedido: <br>"+pedido.data.split("-").reverse().join("-")+"</td><td colspan='2' class='fonte-grande' style='color:red'>Data/Hora entrega: <br>"+pedido.dataPrevista.split("-").reverse().join("-")+" às "+pedido.observacoesInternas.horaEntrega+"h</td><td colspan='1' class='fonte-grande'>Vendedor:<br>"+vendedor+" </td><td class='fonte-grande' colspan='1'>Pedido:<br> #"+pedido.id+'-'+idMolde+ pedidoExpresso);
            $("#tabela_10").append("<tr><td class='left fonte-grande' colspan='11'>Cliente: "+pedido.contato.nome+"</td></tr><tr><td colspan='11' class='fonte-grande center fonte-extra-grande texto-forte'>FICHA TÉCNICA DO PRODUTO</td></tr>");
            $("#tabela_10").append("<tr><td colspan='11'><center><img src='"+value.molde.imagem+"' width='94%'></center></td></tr>"); //imagem
            $("#tabela_10").append("<tr><td colspan='11' class='fonte-grande center texto-forte'>QUANTIDADE</td></tr>");
            $("#tabela_10").append("<tr class='center'><td colspan='11'><div class='texto-grande tamanhos' id='tamanhos_"+idMolde+"'></div></td></tr>"); //tamanhos
            
            $("#tabela_10").append("<tr><td colspan='3' class='fonte-grande center texto-forte'>OBSERVAÇÕES</td><td colspan='5' class='fonte-grande center texto-forte'>COMPOSIÇÃO<span id='totalGeralProdutos' style='float:right;font-size:18px;font-weight:normal;line-height:2.5;border:1px solid grey'></span></td></tr>");
            $("#tabela_10").append("<tr class='left' valign='top' style='height:260px'><td colspan='3' class='fonte-grande' style='color:red;font-size:20px'>"+value.molde.Observacoes+"<div style='border:1px solid grey;color:black;font-weight:bold;text-align:center;font-size:24px'>ENTREGA/RETIRADA</div>"+formaentrega+"</td><td colspan='5' style='height:45px;font-size:20px' id='composicao_"+idMolde+"'></td></tr>");
            $("#tabela_10").append("<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></tr>"); //Última linha
            //aqui inserindo os tamanhos
            var tamanhos = '';
            tamanhos += "<table width='100%'><tr class='center fonte-grande texto-forte'>";
                //primeiro os tamanhos
                if(pedido.produtos['item_'+idMolde]?.quantidades?.[0]?.altura != undefined){
                     tamanhos+= "<td class='texto-forte' style='font-weight:normal'>"+'Altura'+"</td>";
                     tamanhos+= "<td class='texto-forte' style='font-weight:normal'>"+'largura'+"</td>";
                     tamanhos+= "<td class='texto-forte' style='font-weight:normal'>"+'Quantidade'+"</td>";
                     tamanhos+= "</tr><tr class='center fonte-grande'>"; //linha debaixo
                     tamanhos+= "<td class='texto-forte' style='font-size:24px'>"+value.quantidades[0].altura+"</td>";
                     tamanhos+= "<td class='texto-forte' style='font-size:24px'>"+value.quantidades[0].largura+"</td>";
                     tamanhos+= "<td class='texto-forte' style='font-size:24px'>"+value.quantidades[0].quantidade+"</td>";
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
                            tamanhos+= "<td class='texto-forte' style='font-size:24px'> </td>";
                        }else{
                            tamanhos+= "<td class='texto-forte' style='font-size:24px'>"+value.quantidade+"</td>";
                            totalGeralProdutos += parseInt(value.quantidade);
                        }
                        
                    });
            tamanhos+="</tr></table>";
            $('#totalGeralProdutos').text('Total de peças: '+totalGeralProdutos); //aqui só inserindo o textinho de total de peças
                }
            //e por ultimo inserindo na tabela    
            $("#tamanhos_"+idMolde).append(tamanhos);
            
            //aqui inserindo as composições
            composicoes = value.molde.descricaoCurta.composicao;
            $.each(composicoes,function(key,value){
                if(composicoes[key][((composicoes[key].length)-1)].selecionado != ''){
                $("#composicao_"+idMolde).append("<b>"+key+": </b>");
                $("#composicao_"+idMolde).append(composicoes[key][((composicoes[key].length)-1)]+"  |  ");
                }
            });
            
            //aqui inserindo as aplicações se tiver
            aplicacoesTemp = value.molde?.aplicacoes;
            
            if(value?.aplicacoes != undefined){
                $.each(value.aplicacoes,function(key,value2){
                    if(value2.quantidade != 0){ 
                       $("#composicao_"+idMolde).append("<b>"+key+": </b>");
                       $("#composicao_"+idMolde).append("<b>"+value2.nome+" </b>"+value2.observacoes+"  |  "); 
                    }
                });
            }
    } 
});
    //$('#tabela').append("<tr class='right'><td colspan='10'>TOTAL</td><td>"+totalPedido+"</td></tr>");
</script>




</body>
</html>