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
session_start();
?>
<script>
//passando os dados pro javascript :) obs: somente o vendedor que criou pode alterar o pedido ou usuário com acesso e criando o objeto produtos vazio
var pedido = JSON.parse(<?php echo(json_encode($FinalPedido['pedidoJson'])); ?>);
var vendedor = "<?php echo($FinalPedido['vendedorNome']);?>";
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
        
        @media print
        {
              .page-break  { display:block; page-break-before:always; }
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

<br>
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
console.log('pedido.contato.id:'+pedido.contato.id);
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
                    console.log(dadosCliente);
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


<!-- tabela de orçamento -->
<div class="row" style='width:1500px;margin-left:2px;font-size:24px'>
    <div class='col-sm-4'>
        <img src='img/personal-logo.png' width='350px'>
    </div>
    <div class='col-sm-8'>
        <div class='fonte-padrao'>R K S RODRIGUES FABRICACAO LTDA</div>
        <div class='fonte-padrao'>CNPJ: 07.610.743/0001-22, IE: 240160044</div>
        <div class='fonte-padrao'>Via das Flores, 2077 - Pricumã, Boa Vista - RR, 69309-366 - (95) 99165-6961</div>
        
    </div>
    <div class='col-sm-12' class='pedidoFonte' style='font-weight:bold;font-size:26px;text-align:center;padding-top:24px'>Pedido <?php echo($FinalPedido['idPedido']); ?></div>
    <div class='col-sm-8'>
        <span style='font-weight:bold;font-size:20px'>Cliente</span>
    </div>
    <div class='col-sm-4'>
        
    </div>
    <!-- =================================== -->
    <div class='col-sm-7' id='dadosClientePedido' style='border:1px solid black'>
         <?php print_r($pedidoJson['contato']->nome);?><br>
         Código: <?php echo($pedidoJson['contato']->id);?><br>
         CPF: <?php echo($pedidoJson['contato']->numeroDocumento);?><br>
         Endereço: -<br>
    </div>
    <div class='col-sm-1'></div>
    <div class='col-sm-2' style='border:1px solid black;padding:0px'>
        <div style='border-bottom:1px solid black;height:33%;font-weight:bold'>Número do Pedido </div>
        <div style='border-bottom:1px solid black;height:33%;font-weight:bold'>Data: </div>
        <div style='height:34%;font-weight:bold'>Data prevista:</div>
    </div>
    <?php
        //organizando o formato das datas
        $orgDate = $pedidoJson['data'];  
        $date = str_replace('-"', '/', $orgDate);  
        $newDate1 = date("d/m/Y", strtotime($date));  
         
        $orgDate2 = $pedidoJson['dataPrevista'];  
        $date2 = str_replace('-"', '/', $orgDate2);  
        $newDate2 = date("d/m/Y", strtotime($date2)); 
         
    ?>
    <div class='col-sm-2' style='border:1px solid black;padding:0px'>
        <div style='border-bottom:1px solid black;height:33%'>#<?php echo($FinalPedido['idPedido']);?> </div>
        <div style='border-bottom:1px solid black;height:33%'><?php echo($newDate1);?> </div>
        <div style='height:34%;color:red'><?php echo($newDate2);?></div>
    </div>
    <!-- =================================== -->
    <div class='col-sm-12' style='height:20px'></div>
    <div class='col-sm-7'>
        <span style='font-weight:bold'>Vendedor</span>
    </div>
    <div class='col-sm-1'></div>
    <div class='col-sm-4'>
        <span style='font-weight:bold'>Loja</span>
    </div>
    <div class='col-sm-7' style='border:1px solid black'>
        <span id='nomeVendedorTirouPedido'></span>
    </div>
    <div class='col-sm-1'></div>
    <div class='col-sm-4' style='border:1px solid black'>
        Personal Confecções
    </div>
    <div class='col-sm-12' style='height:40px'></div>
    <div class='col-sm-12' style='font-weight:bold'>Itens do pedido de venda</div>
    
</div>
<br>


<table id='tabela' width='1500px' style="border:0px">
  <tr class='center' style='font-weight:bold'>
    <td colspan='6' style='font-size:26px'>Descrição do produto</td>
    <td style='font-size:22px;'>Código</td>
    <td style='font-size:22px'>Un.</td>
    <td style='font-size:22px'>Qtd.</td>
    <td style='font-size:22px'>Valor unit.</td>
    <td style='font-size:22px'>Valor total</td>
  </tr>
  <tr>
    <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
  </tr>
</table>

<script>
//tratando decimais 
let USDollar = new Intl.NumberFormat('de-DE', {
    style: 'currency',
    currency: 'EUR',
    });


    //parte que gera os itens do pedido
    var totalPedido = 0;
    var nomeProduto = '';
    let totalDeProdutos = 0;
    $.each(pedido.produtos,function(key,value){
        nomeProduto = value.molde.nome;
            $.each(value.quantidades,function(key,value){
                if(value.quantidade != 0){ 
                    //passando os dados do produto para itens
                    //console.log(value);
                    var linha = "<tr class='left'><td colspan='6' style='font-size:24px'>"+nomeProduto+"</td><td style='font-size:24px'>"+value.codigo+"</td><td>UN</td><td style='font-size:24px'>"+value.quantidade+"</td><td style='font-size:24px'>"+USDollar.format(parseFloat(value.preco).toFixed(2)).slice(0,-1)+"</td><td style='font-size:24px'>"+USDollar.format(((value.quantidade)*(value.preco)).toFixed(2)).slice(0,-1)+"</td></tr>";
                    $('#tabela').append(linha);
                    totalPedido += parseFloat(value.quantidade)*parseFloat(value.preco);
                    
                    //e aqui, calculando o total de produtos no pedido
                    totalDeProdutos += parseInt(value.quantidade);
            }
        });

        if(value?.aplicacoes != undefined){
            $.each(value.aplicacoes,function(key,value){
                if(value.quantidade != 0){ 
                    //passando os dados do produto para itens
                    //console.log(value);
                    var linha = "<tr class='left'><td colspan='6' style='font-size:24px'>"+value.nome+"</td><td style='font-size:24px'>"+value.codigo+"</td><td style='font-size:24px'>UN</td><td style='font-size:24px'>"+value.quantidade+"</td><td style='font-size:24px'>"+USDollar.format(parseFloat(value.valor).toFixed(2)).slice(0,-1)+"</td><td style='font-size:24px'>"+USDollar.format(((value.quantidade)*(value.valor)).toFixed(2)).slice(0,-1)+"</td></tr>";
                    
                    $('#tabela').append(linha);
                    totalPedido += parseFloat(value.quantidade)*parseFloat(value.valor);
                    //e aqui, calculando o total de produtos no pedido
                    totalDeProdutos += parseInt(value.quantidade);
                }
            });
        }
    });
    console.log('total de produtos:'+totalDeProdutos);
    
    //console.log(totalPedido);
    var totalProdutoT = USDollar.format(totalPedido.toFixed(2)).slice(0,-1);
    var freteT        = USDollar.format(parseFloat(pedido.transporte.frete).toFixed(2)).slice(0,-1);
    var totalT        = USDollar.format(parseFloat((parseFloat(pedido.transporte.frete)+parseFloat(totalPedido)).toFixed(2))).slice(0,-1);
    
    $('#tabela').append("<tr class='right'><td colspan='10' style='font-size:24px'>Total dos produtos<br>Quantidade de itens<br>Frete<br>Total</td><td style='font-size:24px;font-weight:bold;text-align:left'>"+totalProdutoT +"<br>"+totalDeProdutos+"<br>"+freteT+"<br>"+totalT+"</td></tr>");
    
    //observações do pedido
   // ${USDollar.format(parseFloat((parseFloat(pedido.transporte.frete)+parseFloat(totalPedido)).toFixed(2)).slice(1)
    
</script>

<!-- fim tabela orçamento -->

<script>
    //parte que cria o pedido
    var totalPedido = 0;
    var nomeProduto = '';
    var composicoes = '';
    //verificando a forma de retirada
    var formaentrega = '';
    if(pedido.transporte.tipoFrete == "Balcão de entrega"){
        formaentrega = 'Via das Flores, 2077 - Pricumã';
    }else{
        //trata cep
        var cepT = pedido.transporte.etiqueta.cep.replace(/[^\d]+/g, ''); // remove non digit chars.
        cepT  = cepT.replace(/(\d{5})(\d{3})/, '$1-$2');
        
        
        formaentrega = "<span style='color:black'>Forma: "+pedido.transporte.tipoFrete+"<br>Nome: "+pedido.transporte.etiqueta.nome+"<br>CEP: "+cepT+" UF: "+pedido.transporte.etiqueta.uf+" Número: "+pedido.transporte.etiqueta.numero+"<br>Bairro: "+pedido.transporte.etiqueta.bairro+"<br>Município: "+pedido.transporte.etiqueta.municipio+"<br>Endereço: "+pedido.transporte.etiqueta.endereco+"</span>";
    }
    
    //gerando o pedido com todos os produtos

        $.each(pedido.produtos,function(key,value){
        var idMolde = key.split("_")[1];

        //var pedidoExpresso = value["item_"].idMolde.pedidoExpresso;
        //console.log(pedidoExpresso);
        console.log("MEu pedido todo:\n");
        console.log(value);

        console.log("Expresso:\n");
        console.log(value.pedidoExpresso);


        if(value.pedidoExpresso != undefined && value.pedidoExpresso != "") {
            var pedidoExpresso = "</td><td class='fonte-grande' colspan='1'>"+value.pedidoExpresso+"</td></tr>"
        } else {
            var pedidoExpresso = "</td><td class='fonte-grande' colspan='1'>PEDIDO NORMAL</td></tr>"
        }
        $("#Usage").append("<div style='width:1450px;margin:auto'><table id='tabela_10' style='width:1450px;border:none;'></table></div>");
        $("#tabela_10").append("<tr><td rowspan='3' colspan='3' class='center'><img src='img/personal-logo.png' width='350px'></td></tr>"); //linha 1 imagem
        $("#tabela_10").append("<tr style='height:78px'><td class='left fonte-grande' colspan='11' valign='top'>Modelo: "+value.molde.nome+"</td></tr>"); //Modelo
        $("#tabela_10").append("<tr><td class='left fonte-grande' colspan='11'>Referência: "+value.molde.codigo+"</td></tr>");//
        $("#tabela_10").append("<tr><td colspan='3' class='fonte-grande'>Data do Pedido: <br>"+pedido.data.split("-").reverse().join("-")+"</td><td colspan='2' class='fonte-grande' style='color:red'>Data/Hora entrega: <br>"+pedido.dataPrevista.split("-").reverse().join("-")+" às "+pedido.observacoesInternas.horaEntrega+"h</td><td colspan='2' class='fonte-grande'>Vendedor:<br>"+vendedor+" </td><td class='fonte-grande' colspan='2'>Pedido:<br> #"+pedido.id+'-'+idMolde+ pedidoExpresso);
        $("#tabela_10").append("<tr><td class='left fonte-grande' colspan='11'>Cliente: "+pedido.contato.nome+"</td></tr><tr><td colspan='11' class='fonte-grande center fonte-extra-grande texto-forte'>FICHA TÉCNICA DO PRODUTO</td></tr>");
        $("#tabela_10").append("<tr><td colspan='11'><center><img src='"+value.molde.imagem+"' width='94%'></center></td></tr>"); //imagem
        $("#tabela_10").append("<tr><td colspan='11' class='fonte-grande center texto-forte'>QUANTIDADE</td></tr>");
        $("#tabela_10").append("<tr class='center'><td colspan='11'><div class='texto-grande tamanhos' id='tamanhos_"+idMolde+"'></div></td></tr>"); //tamanhos
        
        $("#tabela_10").append("<tr><td colspan='4' class='fonte-grande center texto-forte'>OBSERVAÇÕES</td><td colspan='6' class='fonte-grande center texto-forte'>COMPOSIÇÃO<span id='totalGeralProdutos_"+idMolde+"' style='float:right;font-size:24px;font-weight:normal;line-height:2.5;border:1px solid grey'>Quantidade</span></td></tr>");
        $("#tabela_10").append("<tr class='left' valign='top' style='height:260px'><td colspan='4' class='fonte-grande' style='color:red;font-size:20px'>"+String(value.molde.Observacoes).replaceAll(/\\n/g,' ')+"<div style='border:1px solid grey;color:black;font-weight:bold;text-align:center;font-size:24px'>ENTREGA/RETIRADA</div>"+formaentrega+"</td><td colspan='6' style='height:45px;font-size:20px' id='composicao_"+idMolde+"'></td></tr>");
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
                    $('#totalGeralProdutos_'+idMolde).text('Total de peças: '+totalGeralProdutos); //aqui só inserindo o textinho de total de peças
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
        
    });
    //$('#tabela').append("<tr class='right'><td colspan='10'>TOTAL</td><td>"+totalPedido+"</td></tr>");
</script>
<!-- formas de pagamento -->
<div class="row" style='width:1500px;margin-left:2px;font-size:24px'>
    <div class='col-sm-12' style='padding-top:40px'>
        <span style='font-weight:bold'>Parcelas</span>
    </div>
    <div class='col-sm-12'  style='border:1px solid black'>
        <div class='row' id='parcelasGerais'>
            <div class='col-sm-1' style='border:1px solid black;font-weight:bold'>Dias</div>
            <div class='col-sm-2' style='border:1px solid black;font-weight:bold'>Data vencimento</div>
            <div class='col-sm-6' style='border:1px solid black;font-weight:bold'>Forma pagamento</div>
            <div class='col-sm-3' style='border:1px solid black;font-weight:bold'>Valor</div>
        </div>
    </div>
</div>
<script>
    $("#parcelasGerais").append();
    $.each(pedido.parcelas,function(key,value){
        $("#parcelasGerais").append("<div class='col-sm-1' style='border:1px solid black;'>"+value.dias+"</div>");
        $("#parcelasGerais").append("<div class='col-sm-2' style='border:1px solid black;'>"+value.dataVencimento.split('-').reverse().join('/')+"</div>");
        $("#parcelasGerais").append("<div class='col-sm-6' style='border:1px solid black;'>"+value.observacoes+"</div>");
        $("#parcelasGerais").append("<div class='col-sm-3' style='border:1px solid black;'>"+USDollar.format(parseFloat(value.valor).toFixed(2)).slice(0,-1)+"</div>");
    });
</script>

<!-- Observações do pedido -->
<div class="row" style='width:1500px;margin-left:2px;font-size:24px'>
    <div class='col-sm-12' style='padding-top:40px'>
        <span style='font-weight:bold'>Observações</span>
    </div>
    <div class='col-sm-12' id='observacoesGerais' style='border:1px solid black;'>
        
    </div>
</div>
<script>
    if(pedido.observacoes != 'undefined'){
        var minhasObs = pedido.observacoes;
        console.log(pedido.observacoes);
        console.log();
        $("#observacoesGerais").append(String(minhasObs).replaceAll(/\\n/g,' '));
    } else {
        $("#observacoesGerais").text('‎');
    }
</script>

<!-- comprovantes -->
<div style='margin-top:50px'></div>

<div id='fotosComprovantes' class='row'>

</div>

<script>
    $.each(pedido.comprovantes,function(key,value){
        console.log(value);
        if(value.substring(value.length-3)=='pdf'){
            // Adiciona o estilo diretamente
            document.getElementById('fotosComprovantes').style.height = '1800px';
            document.getElementById('fotosComprovantes').style.width = '1500px';
            document.getElementById('fotosComprovantes').style.marginLeft = '5px';

            // Ou utilizando setAttribute
            document.getElementById('fotosComprovantes').setAttribute('style', 'height:1800px;width:1500px;margin-left:5px');

             $("#fotosComprovantes").append("<object class='page-break' data='"+value+"#page=1&zoom=190' type='application/pdf' width='1500' height='2150'>alt : <a href='your.pdf'>"+value+"</a></object>");
        }else{
            $("#fotosComprovantes").append("<div class='col-sm-6 page-break' style='height:2150'><img src='"+value+"' width='100%'></div><br>");
        }
    });  
    //adicionando o nome do vendedor que tirou o pedido (PARTE IMPORTANTE!!!)
    $("#nomeVendedorTirouPedido").text(vendedor);
</script>



</body>
</html>

