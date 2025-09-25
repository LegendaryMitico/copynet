<?php
//essa parte é quem garante a segurança do sistema, e define o que pode ou não ser visto,
//atenção às alterações aqui, copie e cole em TODAS as páginas
session_start();
if($_SESSION['usuarioNivelAcesso'] != '1' && $_SESSION['usuarioNivelAcesso']!='0' && $_SESSION['usuarioNivelAcesso']!= '4'){
    header("Location:login.php?logado=semacesso");
    unset(
        $_SESSION['usuarioId'],
        $_SESSION['usuarioNome'],
        $_SESSION['usuarioNivelAcesso'],
        $_SESSION['usuarioLogin'],
    );   
    session_destroy();
}
?>

<?php
include_once("php/connect.php"); //conexão
include_once("funcoes.php"); //todas as funções
//essa parte controla o pedido armazenado no database, e gera pedido novo ('se n tiver')  
if(isset($_GET['numeroPedido'])){
    $pegaIdQuery = "SELECT * FROM `Esteira` WHERE `idGeral`='$_GET[numeroPedido]' AND `idMolde`='item_$_GET[numeroMolde]'";
    $resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
    $FinalPedido = mysqli_fetch_assoc($resultado_pegaId);
    //var_dump($FinalPedido);
    //echo($pegaIdQuery);
}else{
    //header("Location:geraNumeroPedido.php");
    //retirei essa linha, pois NÃO VAI GERAR NÚMERO DE PEDIDO NOVO!
    
}
?>

<script>
//passando os dados pro javascript :) obs: somente o vendedor que criou pode alterar o pedido ou usuário com acesso e criando o objeto produtos vazio
var pedido = JSON.parse(<?php echo(json_encode($FinalPedido['pedidoJson'])); ?>);
//pedido.produtos  = {};
console.log(pedido);
//colocando o nome do último vendedor que editou o pedido (para puxar o nome dele no bling)
var vendedorTemp = JSON.parse(JSON.stringify(<?php echo(json_encode($_SESSION)); ?>));//passando dados do vendedor logado da session pro js 
//console.log(vendedorTemp);
pedido.vendedor.id = vendedorTemp.idBling; //ID DO VENDEDOR
</script>

<!-- fim security place -->
<!doctype html>
<html lang="en">
<head>
<link rel="icon" href="img/icon.png">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Personal Produção</title>
<link rel="icon" href="img/favicon.png">
<!-- meu css --> 

<!-- vuejs e jquery para controle ajax e js -->  
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="js/jquery.js"></script>
<!-- api que gera pdf -->
  <script src="js/xepOnline.jqPlugin.js"></script>
<!-- bootstrap e css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<!-- font awersome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.6.0/css/all.min.css" integrity="sha512-ykRBEJhyZ+B/BIJcBuOyUoIxh0OfdICfHPnPfBy7eIiyJv536ojTCsgX8aqrLQ9VJZHGz4tvYyzOM0lkgmQZGw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- uma porrada de coisa q talvez precise -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>

<body>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 
<!-- chart js -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
<style type="text/css">
  @font-face {
  font-family: 'Montserrat', sans-serif;
  src: url("font/Montserrat-Light.ttf");
  }

body{
    background-color: #27272a; /* <!-- #606060 --> #18191a*/
    /* font-family: 'Helvetica-bold'; */

}
  /* Modify the background color */
         
        .navbar-custom {
            background-color: black;
        }
  /* Modify brand and text color */
         
        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-text {
            color: White;
        }

  input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
  }
</style>
<!-- menu -->
<?php include 'menu.php';?>

<!-- puxando os dados do bling -->
<?php
//pegando o token
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    $token = $resultadot["valor"];
    //$js_array = json_encode($php_array);
?>
<div style="padding: 3px 0px 3px 0px; margin-top: 100px;">
    <style>
        .selected{
        background-color:#27272a;
        color:white;
        }
        .disselected{
            display:none;
            color:white;
            background-color:black;
        }
</style>
  <!-- <button type="button" class="rounded-top" style="padding:3px 10px 0px 10px; border: none; background-color: #A1A1A1; color: #606060;">#987-3</button> -->
  <!-- fim pedidos -->
  <div class="rounded row" style="background-color: #27272a; width: 100%; padding: 10px;margin-top:-80px">
        <div class="col-sm-12" style="display: inline-block;margin-top:3px;width:100%">
            <span style="display:inline-block;color:white">Cliente:</span>
            <input id="myInput" type="text" style="width:90%;background-color:#3a3b3c;color:white;border-radius:5px;display:inline-block">
            <script>
                $(document).ready(function(){
                  $('#update_inputCelular').mask("(99)99999-9999");
                  $('#update_inputTelefone').mask("(99)99999-9999");
                  $('#update_inputCep').mask("99999-999");
                });
                //povoando o select de nomes de clientes
                var countries = '';
                $(document).ready(function(){
                      $("#buscaClienteBtn").click(function(e){
                                //alert($(this).attr('id'));
                                $.ajax({
                                    type: "POST",
                                    url: 'ajax/buscaContatos.php',
                                    data: jQuery.param({nome: $("#myInput").val()}) ,
                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                    success: function(data)
                                    {
                                        //("success!");
                                        //tratando os clientes que vieram
                                        var countries = data.replace('[','');
                                        var countries = countries.replace(']','');
                                        var countries = countries.replaceAll('"','');
                                        var countries = countries.split(',');
                                        //console.log('countries:'+countries);
                                        autocomplete(document.getElementById("myInput"), countries); //update no array
                                    }
                                });
                      });
                });
                 $("#myInput").bind("change paste keyup", function() {
                   var qtdLetras = $(this).val().length;
                   if(qtdLetras%4==1 && qtdLetras!=1){
                       $.ajax({
                                    type: "POST",
                                    url: 'ajax/buscaContatos.php',
                                    data: jQuery.param({nome: $("#myInput").val()}),
                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                    success: function(data)
                                    {
                                        //("success!");
                                        //tratando os clientes que vieram
                                        var countries = data.replace('[','');
                                        var countries = countries.replace(']','');
                                        var countries = countries.replaceAll('"','');
                                        var countries = countries.split(',');
                                        //console.log('countries:'+countries);
                                        autocomplete(document.getElementById("myInput"), countries); //update no array
                                    }
                        });
                   }
                   var idClienteAtual = '';
                });
            </script>
            <!-- Cadastra cliente button -->
            <button type="button" class="btn btn- btn-sm" id="btn_CadastraUser" onclick="javascript:abreAbaCadastro()" style="height: 28px;width:32px; line-height: 0.7; border: 1px solid grey;display:inline-block; margin-top:1px;color:green;margin-top:-3px" alt="pdf"> 
               <i class="fa fa-user-plus" style='color:white'> </i>
            </button>
            <!-- atualiza cliente button -->
            <button type="button" class="btn btn- btn-sm" id="btn_atualizaUser" onclick="javascript:puxaDadosClienteSelecionado()" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" style="height: 28px;width:32px; line-height: 0.7; border: 1px solid grey;display:inline-block; margin-top:1px;color:green;margin-top:-3px" alt="pdf">
               <i class="fa fa-pencil" style='color:white'></i>
            </button>
        </div>
        <hr style='border:1px solid grey'>
                
        <!-- FORMULÁRIO DE CADASTRO E ATUALIZAÇÃO DE CLIENTE -->
                
                
        <!-- FIM FORMULÁRIO DE CADASTRO E ATUALIZAÇÃO DE CLIENTE -->
        
            <div class="row" style="margin-top:5px">
          <!-- linha 2 -->
            <div class="col-sm-2" style="color: red; margin: 0px 0px 0px 0px; color: white">  
              Data do Pedido: 
            </div>
            <div class="col-sm-2" style="color: red; margin: 0px 0px 0px 0px; color: white">  
              Entrega: 
            </div>
            <div class="col-sm-1" style="color: red; margin-top: 3px; margin-left: -40px;color:white">  
              Hora:
            </div>
            <div class="col-sm-2" style="color: #606060; margin-top: 3px;color:white">  
              Vendedor:
            </div>
            <div class="col-sm-3" style="color: #606060; margin-top: 3px; margin-left: -7px;color:white">  
              Pasta:
            </div>
            <div class="col-sm-1" style="color:white">
                N°:
            </div>
          <!-- linha 3 -->
             <div class="col-sm-2" style="color: red; display: inline-block;color:red">  
            <!-- preenchendo os dias automaticamente  $prazo vem do datab-->
              <input type='date' id='dataPedidoInp'  style='color: white; width: 145px;background-color:#3a3b3c;line-height:1.85;border:1px solid green;border-radius:5px;font-size:14px';>
              <script>
                $('#dataPedidoInp').change(function(){
                  pedido.data = $(this).val();
                  pedido.dataSaida = $(this).val();
                });
              </script>
            
            </div>
            <div class="col-sm-2" style="color: red; display: inline-block;color:red">  
            <!-- preenchendo os dias automaticamente  $prazo vem do datab-->
              <input type='date' id='dataEntregaInp'  style='width: 145px;line-height:1.85;border:1px solid green;border-radius:5px;font-size:14px';>
              <script>
                let redDates = [];
                //PRIMEIRAMENTE, PEGANDO AS DATAS CONFIGURADAS COMO BLOQUEADAS DO BANCO DE DADOS
                //pegando as datas proibidas via ajax
                $.ajax({
                type: "POST",
                url: 'ajax/pagaDatasFeriado.php',
                data: jQuery.param({}) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function(data)
                    {
                        redDates = data;
                    }
                });
                

                $('#dataEntregaInp').change(function(){ 
                  console.log(redDates);
                  pedido.dataPrevista = $(this).val();
                  
                  $(this).removeClass('holiday');
                  if(redDates.indexOf($(this).val()) != -1)
                  $(this).addClass('holiday');
                });
                
                window.onload = function () {
                       $('#dataEntregaInp').removeClass('holiday');
                      if(redDates.indexOf($('#dataEntregaInp').val()) != -1)
                      $('#dataEntregaInp').addClass('holiday');
                }
              </script>
              
              <style>
                  .holiday{
                    color: white;
                    background-color:red;
                   }
              </style>
            
            </div>
            <div class="col-sm-1"> 
               <select id="horaEntregaInput" style="height: 28px; font-size: 14px; color: white; margin-left: -38px;background-color:#3a3b3c;border:1px solid green;border-radius:5px">
                    <option value='09:00'>09:00h</option>
                    <option value='10:00'>10:00h</option>
                    <option value='11:00'>11:00h</option>
                    <option value='15:00'>15:00h</option>
                    <option value='16:00' selected>16:00h</option>
              </select>
            </div>
            <script>
            //inserindo no json a hora selecionada
                $(document.body).on('change',"#horaEntregaInput",function (e) {
                   //doStuff
                   var optVal= $("#horaEntregaInput option:selected").val();
                   //console.log(optVal);
                   pedido.observacoesInternas.horaEntrega = optVal;
                   //console.log(pedido);
                });
                //pegando o valor do json (uma vez ao abrir o documento) e preenchendo
                var horaDoJson = pedido.observacoesInternas.horaEntrega;
                $('#horaEntregaInput option[value="'+horaDoJson+'"]').prop('selected', true);
            </script>
            
            <div class="col-sm-2" style="color: #606060; font-size: 15px;color:white;margin-top:3px;margin-left:-40px">  
              <?php
                echo($_SESSION['usuarioNome']);
              ?>
            </div>
            <div class="col-sm-3" style="color: red;display: inline-block; margin-left: -8px;">  
              <input type="text" id="pastaDriveInput" style="width:104%;background-color:#3a3b3c;color:white;border-radius:5px;" placeholder="https://drive/pasta">
            </div>
            <script>
                $("#pastaDriveInput").on("input", function(){
                   pedido.observacoesInternas.pastaDrive = $(this).val();
                });
            </script>
            <div class="col-sm-1" style="color:white;font-size:20px">
                #<span id="spanNumeroPedido"></span>
            </div>
        </div>
        <!-- início 3°linha (hr) -->
        <div class="container" style="background-color: #4b4c4e; margin-top: 5px;height: 1px"></div>
        </div> 
            <style>
            .janelaAppstyle1{
            background-color:#3a3b3c;
            width:350px;
            position:absolute;
            z-index:9999;
            text-align:center;
            color:white;
            }
            .janelaAppstyle2{
            background-color:#3a3b3c;
            /* width:1300px; */
            /* position:absolute; */
            /* z-index:9999; */
            text-align:center;
            color:white;
            }
            .composicoes-table,tr,td{
                
            }    
            
            .alinhado{
                float:left;
            }
            .alinhado-direita{
                display:inline-block;
                float:right;
            }
            .alinhado-centro{
                text-align:center;
            }
            
            .dropdown button{
                height: 25px;
                padding-top: 2px;
                line-height:0.65;
                border-radius:0px;
                background-color:#3a3b3c;
            }
            .fonte-pequena{
                font-size: 9px;
            }
            .dropdown{
                border-radius:0px;
            }
            
            .dropdown-item{
                padding:0px;
                width:350px;
            }
            
            .botaoAddCompo{
                background-color: #2e2f30;
                color:white;
                height:27px;
                line-height:0.55;
                display:inline-block;
            }
            
            .botaoAddCompo:hover {
                background-color: #5c636a;
                color:white;
            }
            
            .botaoSalvar:hover{
                background-color: #5c636a;
                color:white;
            }
            #janelaAplicacoes{
                padding:0px;
                border:1px solid #4b4c4e;
            }
            /* botõezinhos */
            #btn_CadastraUser{
                color:white;
            }
            #btn_CadastraUser:hover{
                background-color:green;
                color:white;
            }
            
            #btn_atualizaUser{
                color:white;
            }
            
            #btn_atualizaUser:hover{
                background-color:green;
            }
            #btnCadastrar{
                background-color:green;
            }
            
            #btnCadastrar:hover{
                background-color:#08ad08;
            }
            
            #btnCadastrar:active{
                background-color:#054d00;
            }
            .btnExcluiComp{
                background-color:#2e2f30;
                color:white;
                float:right;
            }
            .btnExcluiComp:hover{
                background-color:green;
                color:white;
            }
            
            </style>
            <!-- tela de aplicações parte mais crítica do sistema :) -->
            <div class="janelaAplicacoes janelaAppstyle1" style='display:none'>
                <div>
                    <div class="row" style="padding:0px;margin:0px">
                        <!-- COMPOSIÇÃO -->
                        <div class="col-sm-12" style="background-color:#2e2f30;color:white;font-family: system-ui;text-align:center;width:100%;border:1px solid #4b4c4e;"><span  id='atualMoldeNome'></span>Composição do produto</div>
                        <div class="col-sm-12" id="janelaCarac" style="padding:0px"><!-- JANELA DA COMPOSIÇÃO -->
                            
                        </div>
                        <!-- APLICAÇÕES -->
                        <div class="col-sm-12" id='janelaAplicacoes'></div>
                        <!-- OBSERVAÇÕES -->
                        <div class="col-sm-12" style="background-color:#2e2f30;color:white;font-family: system-ui;border:1px solid #4b4c4e;">Observações</div>
                        <div id='janelaObservacoes' class="col-sm-12" style="padding:0px">
                            
                        </div>
                    </div>
                </div> 
            </div>

            <!-- tela de resumo do pedido -->
            <div class='col-sm-12' style="font-size:14px;color:white;padding-top:5px">
                <!-- fim dos dados do cliente -->
            <!-- <div class="container" style="margin-top:5px;text-align:center"> -->
                <div style="margin-top:5px;text-align:center">
                <div class="row" id="janela-moldes" style="display:inline-block;vertical-align:middle">
                <!-- corpo dos pedidos -->
                
                </div>
                
                <button type="button" id="add-produto" class="btn btn- btn-sm" onclick="" style="height: 28px;width:32px; line-height: 0.7; border:1px solid green; display:inline-block;color:white;border-radius:15px;background-color:#3a3b3c;margin-left:10px;margin-top:5px">
                    +
                </button>
            </div>
            <div>
            </div>  
                    <div class="container" style="background-color: #4b4c4e; margin-top: 4px;height: 1px"></div>
                <style>
                    table {
                     border-radius: 9px;
                     border:1px solid red;
                     /*overflow: hidden /* add this */
                     
                    }
                    
                    /* Or do this */
                    
                    thead th:first-child {
                     border-top-left-radius: 9px;
                    }
                    
                    thead th:last-child {
                     border-top-right-radius: 9px;
                    }
                    
                    
                </style>
                <table class="table table-bordered text-center" style="color:white;border:1px solid #4b4c4e; margin-top:3px;margin-bottom:0px;">
                      <thead style="background-color:#2e2f30;z-index: 99999;">
                        <tr>
                          <th scope="col" style="width:10%">#Referência</th>
                          <th scope="col" style="width:60%">Descrição</th>
                          <th scope="col" style="width:10%">Quantidade</th>
                          <th scope="col" style="width:10%">Valor Unit.</th>
                          <th scope="col" style="width:10%">Valor Total</th>
                        </tr>
                      </thead>
                </table>
                
                <!-- janelas popUP -->
                <!-- mudaMolde -->
                <style>
                    .modal{
                        --bs-modal-header-border-color:#4b4c4e;
                    }
                    .modal-footer{
                        border-top: 1px solid #4b4c4e;
                    }
                </style>
                
                <div class="modal fade" id="modalMolde" tabindex="-1" role="dialog" aria-labelledby="modalMoldeLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content" style="background-color:#27272a;width: 820px;margin-left: -32%;">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Mudar molde
                            <span id='spanMoldeAtualTitle' value='hehe'></span>
                        </h5>
                      </div>
                      <div class="modal-body"> <!-- CORPO DO SELECT MOLDES -->
                        <div class='row'>
                            <div class="col-sm-6">
                                <select id='listaCategoriasPai' style="height: 29px; font-size: 14px; color: white; background-color:#3a3b3c;border-radius:5px">
                                    <option selected value> -- Todas as categorias -- </option> <!-- tava disabled -->
                                    <script>
                                        //lista as categorias vindas do bling :)
                                        $.ajax({
                                            type: "POST",
                                            url: 'ajax/pegaCategoriasPai.php',
                                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                            success: function(data)
                                            {
                                                var retorno = JSON.parse(data);
                                                $.each(retorno.data, function(key, value){
                                                    $('#listaCategoriasPai').append($('<option>', { 
                                                        value: value.id,
                                                        text : value.descricao
                                                    }));
                                                });
                                            }
                                        });
                                    </script>
                                </select>
                                <span style='margin-left:5px'>Código</span>
                                <input type="radio" id="css" name="tipoBusca" value="Codigo" checked>
                                Nome
                                <input type="radio" id="css" name="tipoBusca" value="Nome">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" id='inputAchaMolde' style="width:110%;background-color:#3a3b3c;color:white;border-radius:5px;height:30px;margin-left:-30px;margin-top:-2px;" placeholder="DIGITE E PRESSIONE ENTER PARA BUSCAR">
                            </div>
                            <!-- css do menu modal :) -->
                            <style>
                                .boxImgMolde:hover {
                                  transform: scale(1.025); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
                                  -webkit-transition: transform 0.20s ease-in-out;
                                  box-shadow: 1px 1px 1px 1px #3a3b3c;
                                }
                                
                                .boxImgMolde:onclick{
                                  transform: scale(0.9); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
                                  -webkit-transition: transform 0.20s ease-in-out;
                                }
                            </style>
                            <!-- LISTAGEM -->
                            <div class='row' id="listaMoldesDiv" style="margin-top:10px;margin-left:5px">
                                <div style='height:150px;text-align:center;display:table;text-align:center'>
                                        <img src='img/sistem/dinu.png' style="color:#4a4a4f;margin-left:25%; display:table-cell;vertical-align:middle;width:50%"></img>
                                </div>
                              
                               
                            </div>
                        </div>
                      </div>
                      
                      <div class="modal-footer" id="bottomFotterMolde" style="text-align:center;display:block" >
                        <button type="button" class="btn btn- btn-sm boxImgMolde" id="btnAnteriorPag" style="width:90px;margin-top:3px; color:white;line-height:2;margin-right:5px;background-color:#27272a;display:none">
                            <i class="fa fa-backward"></i> 
                        </button>
                        <button type="button" class="btn btn- btn-sm boxImgMolde" id="btnProximaPag" style="width:90px;margin-top:3px;color:white;background-color:#27272a;line-height:2;display:inline-block">
                            <i class="fa fa-forward"></i>  
                      
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- parte onde os moldes são inseridos -->
                
                <div id="insereMolde">
                </div>
                <!-- parte só para exibir o valor total dos pedidos e aplicações --> 
                <table class="table table text-center" style="color:white;border:1px solid #4b4c4e; margin-bottom:0px;border-radius:0px">
                    <thead style="background-color:#2e2f30">
                        <tr style="height:30px;" id="LinTotalProdutos">
                        <td scope="row" style="padding:0px;height:20px;width:80%;text-align:center;border:none;">
                        </td>
                        <td scope="row" style="padding:0px;height:20px;width:10%">
                            <span style="width:100%;color:white;height:30px;text-align:center;line-height:2">TOTAL</span>
                        </td>
                        <td scope="row" style="padding:0px;height:20px;width:232px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e">
                            <span id="valorFinal" style="width:100%;color:green;height:30px;line-height:2">R$ 0.00</span>
                        </td>
                        </tr>
                    </thead>
                </table>
                
                    <div class="container" style="background-color: #4b4c4e; margin-top: 5px;height: 1px"></div>
                        <!-- janela de comprovantes -->
                    <div class="janelaComprovante janelaAppstyle2" style='display:none'>
                            <div class="row" style="padding:0px;margin:0px">
                                <!-- PARCELAS -->
                                    <div class='col-sm-1 comp'>Dias</div>
                                    <div class='col-sm-2 comp'>Data</div>
                                    <div class='col-sm-1 comp'>Valor</div>
                                    <div class='col-sm-4 comp' style='text-align:left;padding:0px;margin:0px'>
                                        <span style='width:20%'>Categoria</span>
                              <!--           <input id="inputBuscaParcelas" type="text" style="width:75%;height:25px;text-align:center;background-color:#3a3b3c;color:white" value="0"> -->
                                                <select id="tipoPagamento" class="valorSelectParc" style="height:25px;font-size: 12px;color: white;background-color:#3a3b3c;text-align:center;width:83%">
                                                <option value='14663389497'>Boleto + cartão crédito</option>
                                                <option value='14663389498'>Boleto + cartão débito</option>
                                                <option value='14663389559'>Boleto + ted</option>
                                                <option value='14623371273'>Boleto Bancário</option>
                                                <option value='14624248754'>Cartão crédito link de pagamento</option>
                                                <option value='14625388014'>Cartão de crédito</option>
                                                <option value='14623371282'>Dinheiro</option>
                                                <option value='14623371276'>Dinheiro +  catão débito</option>
                                                <option value='14663389125'>Dinheiro + boleto</option>
                                                <option value='14663389062'>Dinheiro + cartão crédito</option>
                                                <option value='14664343361'>Dinheiro + transferência pix</option>
                                                <option value='14623371280'>DRE: Acréscimos recebido</option>
                                                <option value='14623371279'>DRE: Descontos recebidos</option>
                                                <option value='14623371278'>DRE: Juros recebidos</option>
                                                <option value='14664820798'>Patrocínio</option>
                                                <option value='14664866224'>Publicidade</option>
                                                <option value='14623371281'>Débito</option>
                                                <option value='14663389374'>Pix</option>
                                                <option value='14663389186'>Pix + boleto</option>
                                                <option value='14663389247'>Pix + cartão crédito</option>
                                                <option value='14663389248'>Pix + cartão débito</option>
                                                <option value='14623371283'>TED</option>
                                                <option value='14623371331'>Transferências</option>
                                                <option value='14663542869'>Vale funcionário</option>
                        
                                            </select>
                                    </div>
                                    <div class='col-sm-3 comp' style='text-align:left;padding:0px;margin:0px'>
                                        <input id="inputBuscaParcelas" type="text" style="width:93%;height:25px;text-align:center;background-color:#3a3b3c;color:white" value="0">
                                        <input class="form-check-input" type="checkbox" value="" id="automaticParcels" checked>
                                    </div>
                                    
                                    <div class='col-sm-1' style="padding:0px;margin:0px">
                                        <button type="button" class="btn btn- btn-sm" onclick="javascript:addParcela();" style="line-height: 0.9;color:white;width:100%;">
                                            <i class="fa fa-rotate-right"></i>
                                        </button>
                                    </div>
                                
                                
                                <!-- JANELA DE PARCELAS -->
                                <div id='parcelasInseridas' class='row' style="padding:0px;margin:0px">
                                    
                                </div>
                                
                                        <!--janela frete -->
                                        <div class="col-sm-6" style="background-color:#2e2f30;color:white;font-family: system-ui;text-align:center;border:1px solid #4b4c4e;height:30px"><span  id='atualMoldeNome' style='margin-left:15px'>Entrega do pedido/Frete</span>
                                        </div>
                                        <!--janela comrpovantes -->
                                        <div class="col-sm-6" style="background-color:#2e2f30;color:white;font-family: system-ui;text-align:center;border:1px solid #4b4c4e;height:30px"><span  id='atualMoldeNome' style='margin-left:15px'>Comprovantes</span>
                                        </div>
                                        
                                        <div class="col-sm-6" id="janelaFrete" style="padding:0px;border:1px solid grey">
                                            <!-- JANELA DO FRETE -->
                                            <select id='tipoDeFrete' style='height: 29px;font-size: 12px;color: white;background-color:#3a3b3c;text-align:center;width:100%;'>
                                                <option value='Balcão de entrega'>BALCÃO DE ENTREGA</option>
                                                <option value='Motoboy'>MOTOBOY</option>
                                                <option value='Correios sedex'>CORREIO SEDEX</option>
                                                <option value='Correios Pac'>CORREIO PAC</option>
                                                <option value='Latam Cargo'>LATAM CARGO</option>
                                                <option value='Azul Express'>AZUL EXPRESS</option>
                                            </select>
                                            <hr style='margin:2px'>
                                            <div id='infoEntregaOcultas' style='display:none'>  <!-- div oculta -->
                                                <div class='row'>
                                                            <div class='col-sm-1'>Nome:</div>
                                                            <div class='col-sm-11'>
                                                                <input id="nomeFrete"  onkeyup="" type="text" style="width:100%;height:25px;background-color:#3a3b3c;color:white" value="">
                                                            </div>
                                                            <div class='col-sm-1'>CEP:</div>
                                                            <div class='col-sm-4'>
                                                                <input id="cepFrete"  onkeyup="" type="text" style="width:100%;height:25px;background-color:#3a3b3c;color:white" value="">
                                                            </div>    
                                                            <div class='col-sm-1'>UF:</div> 
                                                            <div class='col-sm-2'>
                                                                <select id="ufFrete" style="background-color:#3a3b3c;color:white;height:26px;width:100%">
                                                                      <option value="AC">AC</option>
                                                                      <option value="AL">AL</option>
                                                                      <option value="AM">AM</option>
                                                                      <option value="AP">AP</option>
                                                                      <option value="BA">BA</option>
                                                                      <option value="CE">CE</option>
                                                                      <option value="DF">DF</option>
                                                                      <option value="ES">ES</option>
                                                                      <option value="GO">GO</option>
                                                                      <option value="MA">MA</option>
                                                                      <option value="MG">MG</option>
                                                                      <option value="MS">MS</option>
                                                                      <option value="MT">MT</option>
                                                                      <option value="PA">PA</option>
                                                                      <option value="PR">PR</option>
                                                                      <option value="PB">PB</option>
                                                                      <option value="PE">PE</option>
                                                                      <option value="PI">PI</option>
                                                                      <option value="RJ">RJ</option>
                                                                      <option value="RN">RN</option>
                                                                      <option value="RS">RS</option>
                                                                      <option value="RO">RO</option>
                                                                      <option value="RR">RR</option>
                                                                      <option value="SC">SC</option>
                                                                      <option value="SE">SE</option>
                                                                      <option value="SP">SP</option>
                                                                      <option value="TO">TO</option>
                                                                </select>
                                                            </div>
                                                            <div class='col-sm-1'>
                                                                Número 
                                                            </div>
                                                            <div class='col-sm-3'>
                                                                <input id="numeroFrete"  onkeyup="" type="text" style="width:100%;height:25px;background-color:#3a3b3c;color:white" value="">
                                                            </div>    
                                                            <div class='col-sm-1'>
                                                                Bairro: 
                                                            </div>
                                                            <div class='col-sm-11'>
                                                                <input id="bairroFrete"  onkeyup="" type="text" style="width:100%;height:25px;background-color:#3a3b3c;color:white" value="">
                                                            </div>
                                                            <div class='col-sm-1'>Municipio:</div>
                                                            <div class='col-sm-11'>
                                                                <input id="municipioFrete"  onkeyup="" type="text" style="width:100%;height:25px;background-color:#3a3b3c;color:white" value="">
                                                            </div>
                                                            <div class='col-sm-1'>End.:</div>
                                                            <div class='col-sm-11'>
                                                                <textarea id="enderecoFrete" rows="2" style='width:100%;background-color:#3a3b3c;color:white;vertical-align: top;'></textarea>
                                                            </div>
                                                            <div class='col-sm-12'><hr style='margin:2px'></div>
                                                            <div class='col-sm-10'><span style='float:right;margin-right:3px'>Valor do frete:</span></div>
                                                            <div class='col-sm-2'>
                                                                <input id="precoTotalFrete" type="number" style="width:100%;height:25px;text-align:center;background-color:#3a3b3c;color:#01ff01;font-weight:bold;float:right" value="0.00">
                                                            </div>
                                                             
                                                   </div>         
                                                </div>
                                            </div>
                                                    <div class="col-sm-6" id="janelaComprovante" style="padding:0px;border:1px solid grey">
                                                        <button class="btn btn- btn-sm botaoAddComprovante col-sm-1" id="" onclick="document.getElementById('file_comprovante').click()" style='height:28px;width:35px;display:inline-block;vertical-align:top;margin-top:6%;margin-bottom:4%'>+</button>
                                                        <!-- JANELA DOS COMPROVANTES -->
                                                        
                                                    </div>
                                                    <div>    
                                                        <input type="file" id="file_comprovante" style="display:none;" onChange='addComprovante(359)' multiple="">
                                                    </div>
                                        </div>
                                        <!-- script que controla o comportamento da janela de frete -->
                                        <script>
                                            $("#tipoDeFrete").on("change",function(){
                                                pedido.transporte.tipoFrete = $('option:selected',this).val();
                                                pedido.transporte.etiqueta.complemento = $('option:selected',this).val(); //adiciona no complemento, pra exibir no bling também
                                                if($('option:selected',this).val() == 'Balcão de entrega'){
                                                    $("#infoEntregaOcultas").hide();
                                                    pedido.transporte.frete = 0.00;
                                                    $("#precoTotalFrete").val(0.00);
                                                }else{
                                                    $("#infoEntregaOcultas").show();
                                                }
                                            });
                                            //nome
                                              $("#nomeFrete").on("change",function(){
                                                 pedido.transporte.etiqueta.nome = $(this).val();
                                              });
                                            //cep
                                            $("#cepFrete").on("change",function(){
                                                 pedido.transporte.etiqueta.cep = $(this).val();
                                            });
                                            //uf
                                            $("#ufFrete").on("change",function(){
                                                 pedido.transporte.etiqueta.uf = $('option:selected',this).val();
                                            });
                                            //numero
                                            $("#numeroFrete").on("change",function(){
                                                 pedido.transporte.etiqueta.numero = $(this).val();
                                            });
                                            //bairro
                                            $("#bairroFrete").on("change",function(){
                                                 pedido.transporte.etiqueta.bairro = $(this).val();
                                            });
                                            //municipio
                                            $("#municipioFrete").on("change",function(){
                                                 pedido.transporte.etiqueta.municipio = $(this).val();
                                            });
                                            
                                            
                                            //endereço
                                            $("#enderecoFrete").on("change",function(){
                                                 pedido.transporte.etiqueta.endereco = String($(this).val()).replaceAll(/\n/g,' ');
                                            });
                                            
                                            //preco 
                                            $("#precoTotalFrete").on("change",function(){
                                                 pedido.transporte.frete = $(this).val();
                                            });
                                            
                                        </script>
                                        <script>
                                        <!-- script que controla o select de forma pagamento -->
                                        $("#tipoPagamento").on("change",function(){
                                                pedido.categoria.id = $('option:selected',this).val();
                                                //console.log(pedido);
                                        });
                                        
                                        </script>
                                </div>
                                <!-- janela obs finais -->
                                <div class="col-sm-12" style="background-color:#2e2f30;color:white;font-family: system-ui;text-align:center;width:100%;border:1px solid #4b4c4e;"><span  id='' style='margin-left:-53px'>Observações Finais</span></div>
                                <textarea id="observacoes_pagamento" class="form-control observacoesClass" rows="3" style="color:red" onkeyup="updateObsPagamento()" placeholder='Observações sobre o pagamento, entrega, pedido em geral'></textarea>
                        </div> 

                    
                    <style>
                    .coinsButton{
                        color:green;
                        border:1px solid green;
                    }
                    .coinsButton:hover{
                        background-color: yellow;
                        color:black;
                    }
                    
                    .botaoAddComprovante{
                        color:white;
                        background-color:#2e2f30;
                    }
                    .botaoAddComprovante:hover{
                        background-color:green;
                        color:white;
                    }
                    .botaoAddParcela{
                        color:white;
                        background-color:#2e2f30;
                    }
                    .botaoAddParcela:hover{
                        background-color:green;
                        color:white;
                    }
                    .comp{
                        border:1px solid grey;
                    }
                    </style>
                    
                    </div>
                    <?php
                        if($_SESSION['usuarioNivelAcesso'] == 4){
                            echo("<button type='button' id='exibeVenda' class='btn btn- btn-sm' onclick='geraVenda();' style='width:90px;margin-top:3px; float:right; border:1px solid green; color:white;background-color:green;line-height:2'> Produzir <i class='fa fa-play'></i></button>)");
                            echo("<button type='button' id='exibeVenda' class='btn btn- btn-sm' onclick='devolverPedido();' style='width:100px;margin-top:3px; float:right; border:1px solid green; color:black;background-color:yellow;line-height:2; margin-right:5px'> <i class='fa fa-backward'></i> Devolver</button>)");
                        }else{
                            echo("<button type='button'  id='btnSalvar' class='btn btn- btn-sm botaoSalvar'  style='height:38px;width:90px;margin-top:3px; float:right; border:1px solid green; color:green;margin-right:5px'><span style='color:white;'>Salvar</span><i class='fa fa-floppy'></i></button>");
                        }
                    ?>
                    
                    
                    
                    <button type="button" class="btn btn- btn-sm" onclick="geraOrcamento()" style="height: 38px;width:43px; line-height: 0.7; border:1px solid green; display:inline-block; float:right; margin-top:3px;color:green;margin-left:5px;margin-right:5px" alt="orçamento">
                    <i class="fa regular fa-print fa-lg"></i>
                    </button>
                    
                    <button type="button" class="btn btn- btn-sm coinsButton" onclick="" style="width:36px;margin-top:3px; float:right;line-height:2;height:38px;">
                    <i class="fa fa-coins"></i> 
                    </button>
                    
                  

                    <br/><br/>
        </div
        </div>
      </div> 
      </div>
      
<!-- footerzin -->
<!-- fim gradient -->
<br><br><br><br><br><br>
<style>
    .fa-vest{
        color:green;
        float:left;
        margin-left:5px;
    }
    
    .fa-vest:hover{
        color:#2cc32c;
    }
</style>
<!-- style do modal -->
<style>
        table, th, td {
          border:1px solid grey;
          
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
            font-size:12px;
            color:black;
        }
        .tamanhos{
            height:55px;
            display: flex;
            justify-content: center;
        }
        
        #table {
          align-self: center;
        }
        
        .fonte-extra-grande{
            font-size:16px;
        }
        
        .modal-content{
            width:650px;
            margin-left:-70px;
        }

    </style>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" id='janelaTempPedidoModal'>
        <h5 class="modal-title" id="exampleModalLabel">Pedido <span id='tempPedidoIdMolde'></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='tempPedidoBody'>
        
      </div>

    </div>
  </div>
</div>
<!-- style do off canva -->
<style>
.offcanvas-size-xl {
    --bs-offcanvas-width: min(95vw, 600px) !important;
}

#btn-updateCadastro:hover{
    background-color:#036403;
}

#btn-updateCadastro{
    background-color:green;
}

</style>

<!-- off canva de update do cliente -->
<div class="offcanvas offcanvas-end offcanvas-size-xl" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" style='background-color:#18191a'>
  <div class="offcanvas-header" style='border-bottom:1px solid black'>
    <h5 id="offcanvasRightLabel" style='color:white'>Editar dados do cliente #<span id='clienteEditadoAtual'></span></h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body" style='color:white'>
    <div class='row'>
        <div class='col-sm-2'>
            Nome:
        </div>
        <div class='col-sm-10'>
            <input id="update_nomeCliente" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;display:inline-block">
        </div>
        <div class='col-sm-2'>
            Fantasia: 
        </div>
        <div class='col-sm-10'>
            <input id="update_nomeFantasiaCliente" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;display:inline-block;margin-top:5px">
        </div>
             <hr style='margin-top:5px'>  <!-- primeira linha -->
        <div class='col-sm-4'>
            <select id="update_tipoPessoa" class="form-select form-select-sm" aria-label=".form-select-sm example" style="background-color:#3a3b3c;color:white">
              <option value="J">Pessoa Jurídica</option>
              <option value="F">Pessoa Física</option>
              <option value="E">Estrangeiro</option>
            </select>
        </div>
        <div class='col-sm-2'>
            CPF/CNPJ: 
        </div>
        <div class='col-sm-6'>
            <input type="text" id="update_clienteCpf" class="cpf" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:30px" value="">
        </div>
         <hr style='margin-top:10px'>  <!-- primeira linha -->
        <div class='col-sm-4'>
            <select id="update_inputCont" class="form-select form-select-sm" aria-label=".form-select-sm example" style="background-color:#3a3b3c;color:white">
                  <option value="1">Contribuinte ICMS</option>
                  <option value="9">Não contribuinte</option>
                  <option value="2">constribuinte isento de de incrição no cadastro de Constribuintes</option>
            </select>
        </div>
        
        <div class='col-sm-2'>
            Inscrição: 
        </div>
        <div class='col-sm-6'>
            <input type="text" id="update_clienteInscEst" class="cpf" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:30px" value="">
        </div>
        <hr style='margin-top:10px'>  <!-- primeira linha -->
        <div class='col-sm-1'>
            CEP: 
        </div>
        <div class='col-sm-5'>
            <input type="text" id="update_inputCep" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="" maxlength="9">
        </div>
        
        <div class='col-sm-1'>
            UF: 
        </div>
        <div class='col-sm-2'>
            <select id="update_inputUf" class="form-select form-select-sm" aria-label=".form-select-sm example" style="background-color:#3a3b3c;color:white;height:26px">
              <option value="AC">AC</option>
              <option value="AL">AL</option>
              <option value="AM">AM</option>
              <option value="AP">AP</option>
              <option value="BA">BA</option>
              <option value="CE">CE</option>
              <option value="DF">DF</option>
              <option value="ES">ES</option>
              <option value="GO">GO</option>
              <option value="MA">MA</option>
              <option value="MG">MG</option>
              <option value="MS">MS</option>
              <option value="MT">MT</option>
              <option value="PA">PA</option>
              <option value="PB">PB</option>
              <option value="PE">PE</option>
              <option value="PI">PI</option>
              <option value="RJ">RJ</option>
              <option value="RN">RN</option>
              <option value="RS">RS</option>
              <option value="RO">RO</option>
              <option value="RR">RR</option>
              <option value="SC">SC</option>
              <option value="SE">SE</option>
              <option value="SP">SP</option>
              <option value="TO">TO</option>
            </select>
        </div>
        <div class='col-sm-1'>
            Nº:
        </div>
        <div class='col-sm-2'>
            <input id="update_numero" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px;font-size:14px" value="">
        </div>
        <br>
        <div class='col-sm-1' style='margin-top:10px'>
            Cidade: 
        </div>
        <div class='col-sm-5' style='margin-top:10px'>
            <input id="update_inputCidade" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="">
        </div>
        
        <div class='col-sm-1' style='margin-top:10px'>
            Bairro: 
        </div>
        <div class='col-sm-5' style='margin-top:10px'>
            <input id="update_inputBairro" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="">
        </div>
        <div class='col-sm-2' style='margin-top:10px'>
            Endereço: 
        </div>
        <div class='col-sm-10' style='margin-top:10px'>
            <input id="update_complemento" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:65px" value="">
        </div>
        
        <hr style='margin-top:10px'>  <!-- primeira linha -->
            <div class='col-sm-4'>
                Telefone:
            </div>
            <div class='col-sm-4'>
                Celular:
            </div>
            <div class='col-sm-4'>
                Email:
            </div>
            <div class='col-sm-4'>
                <input id="update_inputTelefone" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="" placeholder="Telefone" pattern="\([0-9]{2}\)[0-9]{5,7}-[0-9]{4,5}$" required="" maxlength="13">
            </div>
            <div class='col-sm-4'>
                <input id="update_inputCelular" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="" placeholder="Celular" pattern="\([0-9]{2}\)[0-9]{5,7}-[0-9]{4,5}$" required="" maxlength="14">
            </div>
            <div class='col-sm-4'>
                <input id="update_inputEmail" type="email" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="">
            </div>
        <hr style='margin-top:10px'>  <!-- primeira linha -->
        
        <div class='col-sm-2' style='margin-top:10px'>
            Inf. Adicionais: 
        </div>
        
        <div class='col-sm-10' style='margin-top:10px'>
            <input id="update_infAdicionais" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:65px" value="">
        </div>
    </div>   
    <div class='col-sm-12'>
        <hr style='margin-top:10px'>  <!-- primeira linha -->
        <!-- botão de cadastro e atualização -->
        <!-- <button type="button" class="btn btn- btn-sm" id='btnCadastrar' onclick="" style="float:right;width:120px;color:white;line-height:2;margin-left:5px">Cadastrar <i class="fa fa-play" aria-hidden="true"></i></button> -->
        <button type="button" class="btn btn- btn-sm" id='btn-updateCadastro' onclick="" style="float:right;width:180px;color:white;line-height:2">Atualizar este cadastro <i class="fa fa-play" aria-hidden="true"></i></button>

    </div>
  </div>
    
  </div>
</div>

<!-- script -->
<script>
        //variáveis globais :)
        var idClienteAtual;
        var NomeClienteAtual;
        var codigoCurtoCliente;
        $(document).ready(function(){
                      $("#btn-updateCadastro").click(function(e){
                                //let myOffcanvas = document.getElementById('offcanvasRight');
                                //let bsOffcanvas = bootstrap.Offcanvas.getInstance(myOffcanvas);
                                //bsOffcanvas.hide();
                                $('#offcanvasRight').offcanvas('hide');
                                //alert($(this).attr('id'));
                                $.ajax({
                                    type: "POST",
                                    url: 'ajax/atualizaCliente.php',
                                    data: jQuery.param({idCli: pedido.contato.id,
                                                        nomeCliente: $("#update_nomeCliente").val(),
                                                        nomeFantasia: $("#update_nomeFantasiaCliente").val(),
                                                        tipoPessoa: $( "#update_tipoPessoa option:selected" ).val(),
                                                        clienteCpf: $("#update_clienteCpf").val(),
                                                        inputCont: $("#update_inputCont").val(),
                                                        inscEstadual: $("#update_clienteInscEst").val(),
                                                        inputCep: $("#update_inputCep").val(),
                                                        inputUf: $( "#update_inputUf option:selected" ).text(),
                                                        inputCidade: $("#update_inputCidade").val(),
                                                        inputBairro: $("#update_inputBairro").val(),
                                                        endereco: $("#update_complemento").val(),
                                                        numero: $("#update_numero").val(),
                                                        complemento: $("#update_infAdicionais").val(),
                                                        inputTelefone: $("#update_inputTelefone").val(),
                                                        inputCelular: $("#update_inputCelular").val(),
                                                        inputEmail: $("#update_inputEmail").val(),
                                                        codigoCl: codigoCurtoCliente
                                    }) ,
                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                    success: function(data)
                                    {
                                        console.log(data);
                                        alert(data);
                                    }
                                });
                      });
        });
        //aqui cadastra o cliente novo (DEIXEI EM TELA SEPARADA PRA EVITAR PROBLEMAS DE CACHE OU TRASH MEMORY!)
       //  $("#btnCadastrar").click(function(e){
                        //console.log($('#nomeCliente').val());
                    //     ($('#nomeCliente').val()=='')? console.log('não ta preen'): console.log('sim ta preen');
                    //             //alert($(this).attr('id'));
                     //            $.ajax({
                     //                type: "POST",
                      //   //             url: 'ajax/cadastraCliente.php',
                        //             data: jQuery.param({nomeCliente: $("#update_nomeCliente").val(),
                           //                              nomeFantasia: $("#update_nomeFantasiaCliente").val(),
                             //                            tipoPessoa: $( "#update_tipoPessoa option:selected" ).val(),
                             //                            clienteCpf: $("#update_clienteCpf").val(),
                             //                            inputCont: $("#update_inputCont").val(),
                              //                           inscEstadual: $("#update_clienteInscEst").val(),
                              //                           inputCep: $("#update_inputCep").val(),
                              //                           inputUf: $( "#update_inputUf option:selected" ).text(),
                             //                            inputCidade: $("#update_inputCidade").val(),
                                //                         inputBairro: $("#update_inputBairro").val(),
                                 //                       endereco: $("#update_complemento").val(),
                                 //                       numero: $("#update_numero").val(),
                                  //                      complemento: $("#update_infAdicionais").val(),
                                  //                      inputTelefone: $("#update_inputTelefone").val(),
                                  //                      inputCelular: $("#update_inputCelular").val(),
                                  //                      inputEmail: $("#update_inputEmail").val(),
                                   //                     
                                  //  }),
                                   // contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                   // success: function(data)
                                  //  {
                                    //    alert(data);
                                  //  }
                               // });
       // });
       function puxaDadosClienteSelecionado(){
           if(pedido.contato.id == '0'){
               console.log('faz nada pae :>');
           }else{
               console.log('id atual:'+pedido.contato.id);
               //PREENCHENDO O JSON (só se caso tenha algum id de cliente já escolhido puxa os dados e mostra, só pra isso esse script)
                $.ajax({
                                        type: "POST",
                                        url: 'ajax/pegaDadosCliente.php',
                                        data: jQuery.param({codigoCliente: pedido.contato.id,
                                        }) ,
                                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                        success: function(data)
                                        {
                                            console.log(data);
                                            var dadosCliente = data.split(',');
                                            var enderecoCliente =  dadosCliente[0];
    
                                            //e aqui atualizando o update do lado esquerdo
                                            $("#update_nomeCliente").val(dadosCliente[0]);
                                            $("#update_nomeFantasiaCliente").val(dadosCliente[1]);
                                            $('#update_tipoPessoa option[value="'+dadosCliente[2]+'"]').prop('selected', true);
                                            $("#update_clienteCpf").val(dadosCliente[3]);
                                            $('#update_inputCont option[value="'+dadosCliente[4]+'"]').prop('selected', true);
                                            $("#update_clienteInscEst").val(dadosCliente[5]);
                                            $("#update_inputCep").val(dadosCliente[6]);
                                            $('#update_inputUf option[value="'+dadosCliente[8]+'"]').prop('selected', true);
                                            $("#update_numero").val(dadosCliente[11]);
                                            $("#update_inputCidade").val(dadosCliente[9]);
                                            $("#update_inputBairro").val(dadosCliente[10]);
                                            $("#update_complemento").val(dadosCliente[7]);
                                            
                                            $("#update_inputTelefone").val(dadosCliente[13]);
                                            $("#update_inputCelular").val(dadosCliente[14]);
                                            $("#update_inputEmail").val(dadosCliente[15]);
                                            $("#update_infAdicionais").val(dadosCliente[12]);
                                            
                                            $("#clienteEditadoAtual").text(dadosCliente[16]);
                                            
                                            codigoCurtoCliente = dadosCliente[17]; //atenção nesse código, pois vai ser usado para dar update nos dados do cliente (não sei se já existe no codigo)
                                        }
                });
           }
       }
</script>
<!-- fim offcanva -->

</body>

</html>
<!-- ---------------------------------------------------------------------------------------------------------- -->
<script>
    //parte que trata dos 3 botões inferiores, para gerar venda, relatório e orçamento
    //redirecionando para gerar relatório 
    function geraRelatorio(idPedido){
        window.open('geraRelatorio.php?numeroPedido='+pedido.id, '_blank');
    }
     //redirecionando para gerar orçamento             
    function geraOrcamento(idPedido){
        window.open('geraOrcamento.php?numeroPedido='+pedido.id, '_blank');
    }
    //redirecionando para gerar a venda
    function geraVenda(idPedido){
         window.location='trataPedido.php?numeroPedido='+pedido.id;
    }
    function abreAbaCadastro(){
        window.open('novoCliente.php?', '_blank');
    }
    
    
    
    //atualiza a observações de acordo com o id do molde atual
    function atualizaObservacoes(idMoldeAtual){
        var tempString = ($("#observacoes_"+idMoldeAtual).val()).replaceAll(/\n/g,' ');
        var myEscapedJSONString = JSON.stringify(tempString);
        console.log(myEscapedJSONString);
        pedido.produtos['item_'+idMoldeAtual].molde.Observacoes = JSON.parse(myEscapedJSONString);
    }
    //gera pdf de 1 molde (para enviar para cliente)
    function geraPdfAtual(idMolde,idPedido){
        window.open('geraPdfAtual.php?numeroPedido='+idPedido+'&idMolde='+idMolde, '_blank');
    }
    //adiciona aplicação no json do molde com base no id atual
    function addAppMolde(idMoldeAtual){
        //0° incrementa o contador de aplicações
        pedido.controleApp[idMoldeAtual]++;
        //1° cria a posição vazia caso não exista, para evitar bug de posição undefined
        if (pedido.produtos['item_'+idMoldeAtual].aplicacoes === undefined){
            pedido.produtos['item_'+idMoldeAtual].aplicacoes = [];
        }
        exibeTelaApp = '';
        
        //pegando todas as aplicações do bling
        $.ajax({
                type: "POST",
                url: 'ajax/pegaAplicacoes.php',
                data: jQuery.param({}), //o codigo das aplicações já ta tá em pegaplicacoes.php
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function(data)
                        {
                            //console.log(data);
                            var aplicacoes = JSON.parse(data); //pega as aplicações
                            updateAplicacao(aplicacoes.data[0],idMoldeAtual,pedido.controleApp[idMoldeAtual]); //cria a posição com a primeira app listada (para evitar bugs)
                            var exibeTelaApp = '';
                            $.each(aplicacoes.data,function(key,value){
                                exibeTelaApp += "<option value='"+value.id+","+value.codigo+","+value.preco+"'>"+value.nome+"</option>";
                            });
                            $("#janelaAplicacoes").append("<div><div style='width:40px;display:inline-block'>"+pedido.controleApp[idMoldeAtual]+"</div><select id='App_"+idMoldeAtual+"_"+pedido.controleApp[idMoldeAtual]+"'  class='selectAplicacoes' style='height: 29px;font-size: 12px;color: white;background-color:#3a3b3c;text-align:center;width:80%;'>"+exibeTelaApp+"</select><button type='button' class='btn btn- btn-sm botaoAddCompo deleteApp' id='App"+idMoldeAtual+"_"+pedido.controleApp[idMoldeAtual]+"' onClick='javascript:deletaApp("+'"'+"App_"+idMoldeAtual+"_"+pedido.controleApp[idMoldeAtual]+'"'+","+idMoldeAtual+");' style='height:29px;width:29px;line-height: 0.9;color:white;margin-top:-1px'>x</button><textarea id='appObs_"+idMoldeAtual+"_"+pedido.controleApp[idMoldeAtual]+"' class='form-control' rows='1' style='color:red' onkeyup='updateAppObservacao("+'"'+String(idMoldeAtual)+"_"+String(pedido.controleApp[idMoldeAtual])+'"'+")' placeholder='cor da linha, tamanho, observações...'></textarea><div>");
                            
                        }
                });
    }
    function updateAplicacao(aplicacao,idMoldeAtual,idGeral){
        pedido.produtos['item_'+idMoldeAtual].aplicacoes.push({'idGeral':'App_'+idMoldeAtual+'_'+pedido.controleApp[idMoldeAtual],'idApp':aplicacao.id,'nome':aplicacao.nome,'valor':aplicacao.preco,'quantidade':qtdApp(idMoldeAtual),'codigo':aplicacao.codigo,'observacoes':''});
        //insere aplicação inferior
        insereAppTabela(idMoldeAtual,aplicacao,idGeral);
    }
    //insere aplicação na tabela inferior
    function insereAppTabela(idMoldeAtual,aplicacao,idGeral){
        console.log(aplicacao);
        if(aplicacao.valor === undefined){
            console.log('ok');
        }else{
            aplicacao.preco = parseFloat(aplicacao.valor)
        }
        $("#insereMolde").append("<tr style='height:20px;display:flex' id='lin_App_"+idMoldeAtual+"_"+idGeral+"'><td scope='row' style='font-size:12px;padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+aplicacao.codigo+"</span></td><td scope='row' style='font-size:12px;font-weight:normal;padding:0px;height:20px;width:60%;text-align:left;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+aplicacao.nome+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;font-weight:normal'><span id='inp"+idMoldeAtual+'_'+'value.codigo'+"' style='width:100%;background-color:#3a3b3c;color:white;height:30px;text-align:center'>"+parseInt(qtdApp(idMoldeAtual))+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%'><input id='App"+idMoldeAtual+"_"+idGeral+"' type='number' value='"+parseFloat(aplicacao.preco).toFixed(2)+"' style='width:100%;background-color:#3a3b3c;color:white;border:1px solid #4b4c4e;height:100%;text-align:center;height:100%'></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span id='tot"+idMoldeAtual+"_"+idGeral+"'style='width:100%;color:white;height:30px;'>"+parseFloat(aplicacao.preco*parseInt(qtdApp(idMoldeAtual))).toFixed(2)+"</span></td></tr><div>");
        
         alteraTotal();
    }
    
    function alteraTotal(){
        //independente da alteração atualize o valor final do pedido com base nos dados inseridos do json (inserido depois !!!)
        var totalPedido = 0;
        $.each(pedido.produtos,function(key,value){ //primeiro somando os tamanhos
            $.each(value.quantidades,function(key,value){
                if(value.quantidade != 0){ //passando os dados do produto para itens
                    totalPedido += parseInt(value.quantidade) * parseFloat(value.preco);
                }
            })
        }); 
        var totalApp = 0;
        $.each(pedido.produtos,function(key,value){ //e depois somando o total das aplicações
            $.each(value.aplicacoes,function(key,value){
                if(value.quantidade != 0){ //passando os dados do produto para itens
                    totalApp += parseInt(value.quantidade) * parseFloat(value.valor);
                }
            })
        }); 
        $('#valorFinal').text((totalPedido+totalApp).toFixed(2));
    }
    
    function retornaTotalPedido(){
        //independente da alteração atualize o valor final do pedido com base nos dados inseridos do json (inserido depois !!!)
        var totalPedido = 0;
        $.each(pedido.produtos,function(key,value){ //primeiro somando os tamanhos
            $.each(value.quantidades,function(key,value){
                if(value.quantidade != 0){ //passando os dados do produto para itens
                    totalPedido += parseInt(value.quantidade) * parseFloat(value.preco);
                }
            })
        }); 
        var totalApp = 0;
        $.each(pedido.produtos,function(key,value){ //e depois somando o total das aplicações
            $.each(value.aplicacoes,function(key,value){
                //console.log(value);
                if(value.quantidade != 0){ //passando os dados do produto para itens
                    totalApp += parseInt(value.quantidade) * parseFloat(value.valor);
                }
            })
        }); 
        return((totalPedido+totalApp).toFixed(2));
    }
    
    
    //retorna a quantidade total de produtos do molde atual 
    function qtdApp(idMoldeAtual){ 
        var total = 0;
        $.each(pedido.produtos['item_'+idMoldeAtual].quantidades,function(key,value){
    	   total += parseInt(value.quantidade);
        }); 
        return(total);
    }
    //e aqui puxa todas as variações da categoria pai selecionada
    function puxaCoresTecidos(idCatPai,nomeComp,idMoldeAtual){
        
        var insereOpcoesHtml = '';
        $.ajax({
                type: "POST",
                url: 'ajax/puxaTecidosFilhos.php',
                data: jQuery.param({idCatPai: idCatPai,
                        }) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function(data)
                        {
                        var opcoes = JSON.parse(data); 
                        var insereOpcoesHtml = '';
                        console.log(opcoes);
                        
                        $.each(opcoes.data.variacoes,function(key,value){
                            console.log(value);
                            insereOpcoesHtml+= "<a class='dropdown-item' href='javascript:updateComposicao("+'"'+value.nome+'"'+","+'"'+value.codigo+'"'+","+idMoldeAtual+","+'"'+nomeComp+'"'+","+'"'+value.id+'"'+");'' name='hehe'><table class='fonte-pequena' width='100%' style='color:black'><tbody><tr><td rowspan='4' style='width:35px'><img src='moldes/tecidos/"+value.codigo+".jpg' style='width:55px'></td></tr><tr><td>Nome: "+value.nome+"</td></tr><tr><td>Código: "+value.codigo+"</td></tr><tr><td>Estoque: - </td></tr></tbody></table></a>";
                	    });
                	    $('#opt_'+idMoldeAtual+"_"+nomeComp).empty();
                        $('#opt_'+idMoldeAtual+"_"+nomeComp).append(insereOpcoesHtml);
                        }
        });
    }
    
    //e aqui, atualiza a composição com base na última selecionada 
    function updateComposicao(idCatPai,nomeComp,idMoldeAtual,nomeComp2,idAbate){
        //primeiro pega a quantidade do produto em estoque
        let estoqueFinalProduto = '';
        $.ajax({
                type: "POST",
                url: 'ajax/puxaEstoqueProduto.php',
                data: jQuery.param({idProduto_: idAbate,
                        }) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function(data)
                        {
                            estoqueFinalProduto = data;
                            $("#sel_"+idMoldeAtual+"_"+nomeComp2).empty();
                            $("#sel_"+idMoldeAtual+"_"+nomeComp2).append("<table class='fonte-pequena' width='100%' style='color:white'><tbody><tr><td rowspan='4' style='width:35px'><img src='moldes/tecidos/"+nomeComp+".jpg' style='width:55px'></td></tr><tr><td>Nome:"+idCatPai+"</td></tr><tr><td>Código:"+nomeComp+"</td></tr><tr><td>Estoque: "+estoqueFinalProduto+" <button class='btnExcluiComp' onClick='javascript:removeComp("+'"'+idMoldeAtual+'"'+","+'"'+nomeComp2+'"'+");''>X</button></td></tr></tbody></table>");
                            var ultimaPos = pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[nomeComp2].length - 1;
                            pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[nomeComp2][ultimaPos] = idCatPai+"_"+nomeComp+"_"+idMoldeAtual+"_"+idAbate;
                            console.log('adicionada comp:'+pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[nomeComp2][ultimaPos]);
                        }
        });
    }
    
    //e aqui gera um resumo de como vai ficar o pedido do molde clicado
    $('#exampleModal').on('show.bs.modal', function (e) {
    //pegando o nome do vendedor
    var vendedorTemp = JSON.parse(JSON.stringify(<?php echo(json_encode($_SESSION)); ?>));//passando dados do vendedor logado da session pro js 
        
    $("#tempPedidoIdMolde").text('#'+pedido.id+'-'+$(e).attr('relatedTarget').id.split('_')[1]);
      
    var nomeProduto = '';
    var composicoes = '';
    var aplicacoes = '';
    var tamanhos = '';
    //verificando a forma de entrega selecionada
    var formaentrega = '';
    if(pedido.transporte.tipoFrete == "Balcão de entrega"){
        formaentrega = 'Av. Via da Flores, 2077 - Pricumã';
    }else{
        formaentrega = "<span style='color:black;font-weight:normal;'><span style='color:red'>Forma: "+pedido.transporte.tipoFrete+"</span><br>Nome: "+pedido.transporte.etiqueta.nome+"<br>CEP: "+pedido.transporte.etiqueta.cep+" UF: "+pedido.transporte.etiqueta.uf+" Número: "+pedido.transporte.etiqueta.numero+"<br>Bairro: "+pedido.transporte.etiqueta.bairro+"<br>Município: "+pedido.transporte.etiqueta.municipio+"<br>Endereço: "+pedido.transporte.etiqueta.endereco+"</span>";
    }
    
    //gerando o pedido com todos os produtos
        var value = pedido.produtos['item_'+$(e).attr('relatedTarget').id.split('_')[1]];
        var idMolde = $(e).attr('relatedTarget').id.split('_')[1];
        console.log(value);
        $("#tempPedidoBody").empty();
        $("#tempPedidoBody").append("<table id='tabela_10' style='width:615px'></table>")
        $("#tabela_10").append("<tr><td rowspan='3' colspan='2' class='center'><img src='img/personal-logo.png' width='150px'></td></tr>"); //linha 1 imagem
        $("#tabela_10").append("<tr style='height:78px'><td class='left fonte-grande' colspan='11' valign='top'>Modelo: "+value.molde.nome+"</td></tr>"); //Modelo
        $("#tabela_10").append("<tr><td class='left fonte-grande' colspan='11'>Referência: "+value.molde.codigo+"</td></tr>");//
        $("#tabela_10").append("<tr><td colspan='2' class='fonte-pequena'>Data do Pedido: <br>"+pedido.data.split("-").reverse().join("-")+"</td><td colspan='2' class='fonte-pequena'>Data/Hora entrega: <br>"+pedido.dataPrevista.split("-").reverse().join("-")+" às "+pedido.observacoesInternas.horaEntrega+"h</td><td colspan='3' class='fonte-pequena'>Vendedor:<br> "+vendedorTemp.usuarioNome+"</td><td class='fonte-pequena' colspan='1'>Pedido: <br>#"+pedido.id+'-'+idMolde+"</td></tr>");
        $("#tabela_10").append("<tr><td class='left fonte-grande' colspan='11'>Cliente: "+pedido.contato.nome+"</td></tr><tr><td colspan='11' class='fonte-grande center fonte-extra-grande texto-forte'>FICHA TÉCNICA DO PRODUTO</td></tr>");
        $("#tabela_10").append("<tr><td colspan='11'><img src='"+value.molde.imagem+"' width='100%'></td></tr>"); //imagem
        $("#tabela_10").append("<tr><td colspan='11' class='fonte-grande center texto-forte'>QUANTIDADE</td></tr>");
        $("#tabela_10").append("<tr class='center'><td colspan='11'><div class='texto-grande tamanhos' id='tamanhosTemp_"+idMolde+"'></div></td></tr>"); //tamanhos
        
        $("#tamanhosTemp_"+idMolde).append("");
        
        $("#tabela_10").append("<tr><td colspan='2' class='fonte-grande center texto-forte'>OBSERVAÇÕES</td><td colspan='6' class='fonte-grande center texto-forte'>COMPOSIÇÃO</td></tr>");
        $("#tabela_10").append("<tr class='left' valign='top' style='height:130px'><td colspan='2' class='fonte-grande' style='color:red;font-weight:bold;font-size:10px'>"+value.molde.Observacoes+"<div style='border:1px solid grey;color:black;font-weight:bold;text-align:center;font-size:12px'>ENTREGA/RETIRADA</div>"+formaentrega+"</td><td colspan='6' style='height:25px;font-size:12px' id='composicao_"+idMolde+"'></td></tr>");
        $("#tabela_10").append("<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></tr>"); //Última linha
        
         //aqui inserindo os tamanhos
        nomeProduto = value.molde.nome;
        tamanhos += "<table width='100%'><tr class='center fonte-grande texto-forte'>";
            //primeiro os tamanhos
            if(pedido.produtos['item_'+idMolde]?.quantidades?.[0]?.altura != undefined){
                 tamanhos+= "<td class='texto-forte'>"+'Altura'+"</td>";
                 tamanhos+= "<td class='texto-forte'>"+'largura'+"</td>";
                 tamanhos+= "<td class='texto-forte'>"+'Quantidade'+"</td>";
                 tamanhos+= "</tr><tr class='center fonte-grande'>"; //linha debaixo
                 tamanhos+= "<td class='texto-forte'>"+value.quantidades[0].altura+"</td>";
                 tamanhos+= "<td class='texto-forte'>"+value.quantidades[0].largura+"</td>";
                 tamanhos+= "<td class='texto-forte'>"+value.quantidades[0].quantidade+"</td>";
                 tamanhos+="</tr></table>";
                 
            }else{
                $.each(value.quantidades,function(key,value){
                    tamanhos+= "<td class='texto-forte'>"+value.nome+"</td>";
                });
                tamanhos+= "</tr><tr class='center fonte-grande'>";
                //depois as quantidades de cada
                $.each(value.quantidades,function(key,value){
                    if(value.quantidade==0){
                        tamanhos+= "<td class='texto-forte'></td>";
                    }else{
                        tamanhos+= "<td class='texto-forte'>"+value.quantidade+"</td>";
                    }
                    
                });
                tamanhos+="</tr></table>";
            }
        //e por ultimo inserindo na tabela    
        $("#tamanhosTemp_"+idMolde).append(tamanhos); 
            
        //aqui inserindo as composições
        composicoes = value.molde.descricaoCurta.composicao;
        $.each(composicoes,function(key,value){
            if(composicoes[key][((composicoes[key].length)-1)].selecionado != ''){
            $("#composicao_"+idMolde).append("<b>"+key+": </b>");
            $("#composicao_"+idMolde).append(composicoes[key][((composicoes[key].length)-1)]+" || ");
            }
        });
        
        //aqui as aplicações
        aplicacoes = value.aplicacoes;
        $.each(aplicacoes,function(key,value){
            console.log(value);
            $("#composicao_"+idMolde).append("<b>"+value.nome+"</b> || ");
        });
        
        //e por fim, inserindo o botão (dinâmico) que vai gerar o pdf
        $("#dinamicButtonPdf").remove();
        $("#janelaTempPedidoModal").append("<button type='button' class='btn btn- btn-sm' id='dinamicButtonPdf' onclick='geraPdfAtual("+idMolde+","+pedido.id+");' style='height: 32px;width:43px;;line-height: 0.7; border:1px solid grey; display:inline-block;color:grey;margin-left:-360px' alt='pdf'><i class='fa regular fa-print fa-lg' aria-hidden='true'></i>");
    })
    //aqui deleta a app (do json e a parte gráfica)
    function deletaApp(idAppAtual,idMoldeAtual){
        console.log('idappatual:'+idAppAtual);
        $("#"+idAppAtual).parent('div').remove(); //elemento gráfico da janela molde 
        
        $.each(pedido.produtos['item_'+idMoldeAtual]['aplicacoes'],function(key,value){
            if(value?.idGeral == idAppAtual){
                console.log('achou:'+value.idGeral);
                pedido.produtos['item_'+idMoldeAtual].aplicacoes.splice(key,1);//removendo
            }
    	    //if(pedido.produtos['item_'+idMoldeAtual]['aplicacoes'][key]['idGeral'] == idAppAtual){
    	        //console.log(pedido.produtos['item_'+idMoldeAtual]['aplicacoes'][key]);
    	        //
    	    //}
        });
        //parte gráfica
        //console.log(idAppAtual);
        $('#lin_'+idAppAtual).remove();
        
        alteraTotal();
    }
    
    function printaAplicacoes(idMoldeAtual){
        $.each(pedido.produtos['item_'+idMoldeAtual]['aplicacoes'],function(key,value){
            $("#insereMolde").append("<tr style='height:20px;display:flex' id='lin_"+value.idGeral+"'><td scope='row' style='font-size:12px;padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+value.codigo+"</span></td><td scope='row' style='font-size:12px;font-weight:normal;padding:0px;height:20px;width:60%;text-align:left;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+value.nome+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;font-weight:normal'><span id='inp"+x+'_'+'value.codigo'+"' style='width:100%;background-color:#3a3b3c;color:white;height:30px;text-align:center'>"+parseInt(qtdApp(idMoldeAtual))+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%'><input id='App"+idMoldeAtual+"_"+value.idGeral.split('_')[2]+"' type='number' value='"+parseFloat(value.valor).toFixed(2)+"' style='width:100%;background-color:#3a3b3c;color:white;border:1px solid #4b4c4e;height:100%;text-align:center;height:100%'></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span id='tot"+idMoldeAtual+"_"+value.idGeral.split('_')[2]+"'style='width:100%;color:white;height:30px;'>"+parseFloat(value.valor*parseInt(qtdApp(idMoldeAtual))).toFixed(2)+"</span></td></tr><div>");
        });
        alteraTotal();
    }
    
    function deletaTodasAsLinhasApp(idMoldeAtual){
        console.log('id q veio:'+idMoldeAtual);
        $.each(pedido.produtos['item_'+idMoldeAtual]['aplicacoes'],function(key,value){
            console.log(value);
            $("#lin_"+value.idGeral).remove(); 
        });
    }

     $(document.body).on('change',".selectAplicacoes",function (e){
                   //doStuff
                   var idAplicacao = $(this).attr('id');
                   var idMoldeAtual = $(this).attr('id').split('_')[1];
                   var optVal= $('option:selected',this).val();
                   var optText = $('option:selected',this).text();
                   var keyAtual = '';
                   $.each(pedido.produtos['item_'+idMoldeAtual]['aplicacoes'],function(key,value){
                        if(pedido.produtos['item_'+idMoldeAtual]['aplicacoes'][key]['idGeral'] == idAplicacao){
                            pedido.produtos['item_'+idMoldeAtual]['aplicacoes'][key].nome = optText;
                            pedido.produtos['item_'+idMoldeAtual]['aplicacoes'][key].codigo = optVal.split(',')[1];
                            pedido.produtos['item_'+idMoldeAtual]['aplicacoes'][key].idApp = optVal.split(',')[0];
                            pedido.produtos['item_'+idMoldeAtual]['aplicacoes'][key].valor = optVal.split(',')[2];
                            keyAtual = key; 
                        }
                   });
                   //aqui atualizando a parte gráfica
                   //console.log(pedido.produtos['item_'+idMoldeAtual]['aplicacoes'][keyAtual]);
                   $("#lin_"+idAplicacao).remove(); //removendo antiga
                   insereAppTabela(idMoldeAtual,pedido.produtos['item_'+idMoldeAtual]['aplicacoes'][keyAtual],idAplicacao.split('_')[2]);
                   
     });
                
    //aqui onde tava dando bug antes
    function updateQtdApp(idMoldeAtual){
        var total = 0;
        $.each(pedido.produtos['item_'+idMoldeAtual].quantidades,function(key,value){
    	   total += parseInt(value.quantidade);
        }); 
        $.each(pedido.produtos['item_'+idMoldeAtual].aplicacoes,function(key,value){
    	   pedido.produtos['item_'+idMoldeAtual].aplicacoes[key].quantidade = total;
        });
        console.log(pedido);
    }
    $(".coinsButton").on("click", function(e){
        console.log('ta chamando');
        $(".janelaComprovante").toggle(250);
        //1° apenas atualizando a posição da div 
        $(".janelaComprovante").show();
        var pos = $(e.target).offset();
        pos.left = pos.left-555;
        pos.top = pos.top-3;
        //$(".janelaComprovante").offset(pos);
    });
    
    function addComprovante(){
        //verificando antes se o array não foi apagado
        if(pedido?.comprovantes == undefined){
            pedido.comprovantes=[];
        }

        var form_data = new FormData();
        //Read selected files
        var totalfiles = document.getElementById('file_comprovante').files.length;
        for (var index = 0; index < totalfiles; index++) {
            form_data.append("files[]", document.getElementById('file_comprovante').files[index]);
        }
        //AJAX request
        var local = 'ajaxFile.php?idMolde='+String(pedido.id);
          $.ajax({
               url: local, 
               type: 'post',
               data: form_data,
               dataType: 'json',
               contentType: false,
               processData: false,
               success: function (response){
                    for(var index = 0; index < response.length; index++) {
                         var src = response[index];
                         pedido.comprovantes.push(src);
                         console.log('molde index:'+index);
                         var contadorIndex = pedido.comprovantes.length-1;
                         $("#janelaComprovante").append("<div style='border:1px solid grey;display:inline-block;' id='comprovante_"+contadorIndex+"'><img src='"+src+"'height='100px' width='50px'><div id='comp_'><button class='btn btn- btn-sm botaoAddComprovante col-sm-1' style='width:45px' onClick='verComprovante("+contadorIndex+")'>Ver</button><button class='btn btn- btn-sm botaoAddComprovante col-sm-1' onClick='excluirComprovante("+contadorIndex+")' style='width:55px'>Excluir</button></div></div>");

                    }
               }
          });
    }
    
    function verComprovante(idComprovante){
        var src = pedido.comprovantes[idComprovante];
        window.open(src, "_blank");
    }
    
    function excluirComprovante(idComprovante){
        pedido.comprovantes.splice(parseInt(idComprovante),1);
        $("#comprovante_"+idComprovante).remove();
        console.log(pedido);
    }
     
    function addParcela(){ 
        //1° primeiro delete todas as parcelas que já tem (óbvio) 
        $("#parcelasInseridas").empty();
        
        var str = String($("#inputBuscaParcelas").val()).replace(/\s+$/, '');; //2° pega o valor digitado
        var arrayDias = str.split(/[, ]+/); //3° splita nos espaços
        
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
                                        
        today = yyyy + '/' + mm + '/' + dd; //4° pega o dia de hoje
        
        var parcela = (parseFloat(retornaTotalPedido())+parseFloat(pedido.transporte.frete))/arrayDias.length; //5° calcula o valor de cada parcela (ATENÇAO ESPECIAL AQUI) ============
        //pegando todas as aplicações do bling, e armazenando nessa variável opcoes Pagamento
        var opcoesPagamento = '';
        
        $.ajax({
                type: "POST",
                url: 'ajax/puxaFormasPagamento.php',
                data: jQuery.param({}),
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function(data)
                        {
                            console.log(data);
                            var formasPagamento = JSON.parse(data); //pega as aplicações
                            $.each(formasPagamento.data,function(key,value){
                                opcoesPagamento += "<option value='"+value.id+"'>"+value.descricao+"</option>"
                            });
                            console.log(formasPagamento);
                            pedido.controleApp[0] = -1; //zerando o controlador já que vai ser renovado tudo
                            pedido.parcelas = [];
                            $.each(arrayDias, function(key, value){
                                pedido.controleApp[0]++; 
                                var someDate = new Date(today);
                                someDate.setDate(someDate.getDate()+parseInt(value)); //number  of days to add, e.x. 15 days
                                var dateFormated = someDate.toISOString().substr(0,10);
                                var dataTemp = dateFormated.split('-').reverse().join('/');
                                
                                $("#parcelasInseridas").append("<div class='row' style='padding:0px;margin:0px' id='parcela_"+pedido.controleApp[0]+"'><div class='col-sm-1 comp' style='padding:0px;margin:0px'>"+parseInt(value)+"</div><div class='col-sm-2 comp' style='padding:0px;margin:0px'>"+dateFormated.split('-').reverse().join('/')+"</div><div class='col-sm-1 comp' style='padding:0px;margin:0px'><input id='valorParcela_"+pedido.controleApp[0]+"' class='valorParcelaInput' onkeyup='alterouValorParcela("+pedido.controleApp[0]+")' type='number' style='width:100%;height:25px;text-align:center;background-color:#3a3b3c;color:white' value='"+parseFloat(parcela).toFixed(2)+"'></div><div class='col-sm-7 comp' style='padding:0px;margin:0px'><select id='selectPagamentoParc_"+pedido.controleApp[0]+"' onChange='updateFormaPagamento("+pedido.controleApp[0]+")' class='valorSelectParc' style='height:25px;font-size: 12px;color: white;background-color:#3a3b3c;text-align:center;width:100%'>"+opcoesPagamento+"</select></div><div class='col-sm-1' style='padding:0px;margin:0px'><button type='button' class='btn btn- btn-sm botaoAddCompo deleteApp' onclick='javascript:removeParcela("+pedido.controleApp[0]+");' style='line-height: 0.9;color:white;width:100%;'>x</button></div></div>");
                                //e aqui adicionando no json a 1 f de pag
                                pedido.parcelas.push({"dias": value,"idGeral":pedido.controleApp[0],"id":"-","dataVencimento":dateFormated,"valor":parcela.toFixed(2),"observacoes":formasPagamento.data[0].descricao,"formaPagamento":{"id":formasPagamento.data[0].id}}); 
                                
                            });
                        }
        });
        console.log(opcoesPagamento);
    }
    
    
    function removeParcela(idGeralParcela){
            $.each(pedido.parcelas, function(key, value){
                console.log(value);
                if(String(value.idGeral) == String(idGeralParcela)){
                    pedido.parcelas.splice(key,1);
                    $("#parcela_"+idGeralParcela).remove();
                }
            });
    }
    //e aqui, ouve todas as alterações que são realizadas na janela de parcelas
    function alterouValorParcela(idGeralParcela){
         $.each(pedido.parcelas,function(key,value){
            if(value.idGeral == idGeralParcela){
                console.log('idGeral='+value.idGeral);
                console.log('novo valor:'+$("#valorParcela_"+idGeralParcela).val());
                pedido.parcelas[key].valor = $("#valorParcela_"+idGeralParcela).val();
            }else{
                if($("#automaticParcels").is(':checked')){ //calcula as parcelas automáticas e atualiza tudo no json
                    var totalTemp = retornaTotalPedido();
                    var resto = totalTemp-$("#valorParcela_"+idGeralParcela).val();
                    var totalDivisao = pedido.parcelas.length-1;
                    var parcelaResult = (parseFloat(resto)+parseFloat(pedido.transporte.frete))/totalDivisao;  //atenção especial aqui, ele está somando o resto com o frete!!!
                    pedido.parcelas[key].valor = parcelaResult;
                    $("#valorParcela_"+value.idGeral).val(parcelaResult.toFixed(2));
                }
                else{
                    //pedido.parcelas[key].valor = $("#valorParcela_"+idGeralParcela).val(); //pega qualquer valor e joga no json
                    //tirei aqui, mas atenção nos testes pois pode ter erros ainda
                }
            }
         });
    }
    $("#automaticParcels").on('change',function(){
       alterouValorParcela(0);
    });

    //altera a observações de pagamento
    function updateObsPagamento(){
        var tempString = ($("#observacoes_pagamento").val()).replaceAll(/\n/g,' ');
        var myEscapedJSONString = JSON.stringify(tempString);
        console.log(myEscapedJSONString);
        pedido.observacoes = JSON.parse(myEscapedJSONString);
    }
    //altera o tipo de forma de pagamento (MUITO IMPORTANTE AQUI)
    function updateFormaPagamento(idSelect){
        var e = $('#selectPagamentoParc_'+idSelect);
        var texto = $('option:selected',e).text();
        var valor = $('option:selected',e).val();
        
        $.each(pedido.parcelas,function(key,value){
            if(value.idGeral == idSelect){
                console.log("alterou:"+idSelect);
                pedido.parcelas[key].formaPagamento.id = valor;
                pedido.parcelas[key].id = valor;
                pedido.parcelas[key].observacoes = texto;
            }
         });
    }
    
    function updateAppObservacao(idObsApp){
        var moldeA = idObsApp.split('_')[0];
        $.each(pedido.produtos['item_'+moldeA]['aplicacoes'],function(key,value){
            if(value.idGeral == 'App_'+idObsApp){
                pedido.produtos['item_'+moldeA]['aplicacoes'][key].observacoes = String($("#appObs_"+idObsApp).val()).replaceAll(/\n/g,' ');
            }
        });
    }
    
    function devolverPedido(){
        window.location='devolvePedido.php?numeroPedido='+pedido.id;
    }
    
    function enviaAnalise(){
        //aqui ele faz a verificação de tudo antes de enviar para análise======================================
        var prossegue = true;
        var totalPedido = String($("#valorFinal").text());
        
        var frete = $("#precoTotalFrete").val();
        //verifica se tem produtos marcados ou se o valor da venda da zerado
        if(totalPedido == '0.00' || totalPedido == 'R$ 0.00'){
            alert('Insira produtos no pedido'); 
            prossegue = false;
            return false;
        }
        //primeiro verifica as parcelas =======================================================================
        var totalParcelas = 0;
        $.each(pedido.parcelas,function(key,value){
            //console.log(value);
            totalParcelas += parseFloat(value.valor);
        });
        
        //console.log(totalPedido+"//"+totalParcelas+'//'+frete);
        if(parseFloat(parseFloat(totalPedido)+parseFloat(frete)).toFixed(2) != parseFloat(totalParcelas).toFixed(2)){
            console.log('total pedido:' + totalPedido);
            console.log('total frete:' + frete);
            console.log('totalParcelas:' + totalParcelas);
            prossegue = false;
            alert('Valor das parcelas diferente do total do pedido+frete');
            return false;
        }
        //depois verifica se todos os tecidos estão presentes ================================================
        $.each(pedido.produtos,function(key,value){
            $.each(value.molde.descricaoCurta.composicao,function(key2,value2){
                if(value2[value2.length-1].selecionado == ''){
                    if(key2 == 'tecido-malha'){
                        alert("Faltou selecionar "+key2+" de : #"+key);
                        prossegue = false;
                        return false; //apenas para parar a execução do código
                    }
                }
            });
            if(prossegue == false){
                return false; //apenas para parar a execução do código
            }
        });
        //depois verifica se tem molde com quantidades zeradas===============================================
        $.each(pedido.produtos,function(key,value){
            var zerado = true;
            $.each(value.quantidades,function(key2,value2){
                if(value2.quantidade != '0'){
                    zerado = false;
                    return false; //apenas para parar a execução do código
                }
            });
            if(zerado == true){
                alert('produto #'+key+' com quantidades zeradas');
                prossegue = false;
                return false; //apenas para parar a execução do código
            }
        });

        //depois verifica se tem algum cliente selecionado ==================================================
        if(pedido.contato.id == '0' || pedido.contato.nome == '' || pedido.contato.numeroDocumento == ''){
            alert("Faltou selecionar o cliente");
            prossegue = false;
        }
        
        //depois verifica se tem o link do drive selecionado
        if($("#pastaDriveInput").val() == ''){
             alert("Faltou informar a pasta do drive");  
             prossegue = false;
        }
        
        if(prossegue == true){
            if (window.confirm('Mandar esse pedido para o caixa?')){
                window.location='enviaAnalise.php?numeroPedido='+pedido.id;
            }
        }
    }
    </script>
<script>
//variáveis globais de controle;
var idMoldeAtual = <?php echo($_GET['numeroMolde']);?>;
<!-------------------------------------------------------------------------------------------------------------!>
//add id molde 
$(document).ready(function(){
      var add_button = $("#add-produto");//Add button ID
      //pega o ultimo molde inserido
      var x = 10;
      var max_fields = 99; //maximo de produtos
      var wrapper2 = $("#janela-moldes"); //janela de inserir moldes
      $(add_button).click(function(e) { //on add input button click
      if(!pedido.hasOwnProperty('produtos')){
          pedido.produtos = {};
      }
      if(pedido.produtos != {}){
        $.each(pedido.produtos, function(key, value){
            x =  key.split("_")[1];
            x++;
        });
      }
        e.preventDefault();
        if (x < max_fields) { //max input box allowed
          var idTela = x;
          var nomeUpImg = 'files_'+x;
          $(wrapper2).append("<div id='janelaAtual_"+x+"' class='col-sm-4' style='float:left;height:340px; width:245px;border-radius:8px;display:inline-block;vertical-align:middle;margin-right:-4px;margin-bottom:20px'><div class='row'><div class='container boxImgMolde'><div clss='col-sm-12' style='background-color:#2e2f30;border-radius: 10px 10px 0px 0px; padding-top:2px'><a href='javascript:exibeResumoPedido()' data-toggle='modal' data-target='#exampleModal' id='butExibePedido_"+x+"'><i class='fa fa-vest' aria-hidden='true'></i></a><span style='margin-left:25px'>#"+pedido.id+"-"+x+"</span><button type='button' id='"+x+"' class='btn btn- btn-sm remove_field' onclick='' style='height: 18px;width:20px; line-height: 1.25; border:1px solid red; display:inline; float:right;color:green;margin-right:5px;font-size:10px;background-color:#3a3b3c' alt='orçamento'><span style='margin-left:-2px;color:red'>X</span></button></div><a id='imgMolde' href='#'><img id='moldeImg_"+x+"' class='mudaMoldeImg' data-toggle='modal' data-target='#modalMolde' src='img/sistem/dinu.png' alt='Minha Figura' width='100%' style='background-color:white'></a><div col-sm-12 style='margin-top:3px'><button type='button' id='appMoldeBtn' class='btn btn- btn-sm' onclick='' style='height: 18px;width:20px; line-height: 0.7; border:1px solid green; display:inline; float:right;color:green;margin-left:-3px' alt='orçamento'><i id='appBtn_"+x+"' class='fa fa-bookmark fa-sm appMoldeBtn' style='margin-left:-3.5px;margin-top:-3px'></i></button><button type='button' class='btn btn- btn-sm' onclick='' style='height: 18px;width:20px; line-height: 0.7; border:1px solid green; display:inline; float:left;color:green;margin-left:3px;margin-right:5px' alt='orçamento'><a id='downBtn_"+x+"' href='#' target='_blank'><i id='downBtn_"+x+"' class='fa fa-download fa-2xs btnDownloadClass' style='margin-left:-5.5px;margin-top:-3px'></i></a></button><button style='display:block;width:20px; height:18px;background-color:#2e2f30;border:1px solid green;color:green;'><i id='upBtn_"+x+"' class='fa fa-upload fa-2xs btnUpClass' style='margin-left:-5.5px;margin-top:-3px;color:green;' onclick="+'"'+"document.getElementById("+"'"+"files_"+x+"'"+").click()"+'"'+"></i></button><input type='file' id='files_"+x+"' style='display:none' multiple><span id='spanNomeMoldeAtual_"+x+"' style='margin-left:-20px'>-</span></div><div id='tamanhos_"+x+"' style='width:220px;height:60px;background-color:#2e2f30; border-radius: 0px 0px 10px 10px'></div></div></div></div>");//adiciona o botãozinho na parte superior
          
        }
        //e após inserir os elementos gráficos, cria a posição vazia em produtos (temporário apenas para evitar bugs)
        //console.log(produtos);
        var temp = {"molde":{"codigo":"-","descricaoCurta": {"imagem":"","moldeLink": "","abate":{"PP":0.85,"P":0.86,"M":0.89,"G":0.92,"GG":0.95,"EXG":0.99}},"formato":"V","id":123456,"imagem":"img/sistem/dinu.png","nome":".","preco": 39.90,"situacao":"A","tipo":"P","Observacoes":""},"aplicacoes":[],"quantidades":[]};
        pedido.produtos['item_'+x] = temp;
        //console.log(pedido);
        //e aqui novamente, só fechando a janela de aplicações pra n ficar por cima
        $(".janelaAplicacoes").hide();
      });
      
//FUNÇÕES DE COMPORTAMENTO DE TODOS OS BOTÕES ===================================================================================================
    //remove o molde pelo botão X
    $(wrapper2).on("click", ".remove_field", function(e){ 
        e.preventDefault();
        $(this).parent('div').parent('div').parent('div').parent('div').remove();
        var moldeA = $(this).attr('id');
        //remove todos as linhas pré inseridas ------------------------------------------------------
        $.each(pedido.produtos['item_'+moldeA]['quantidades'],function(key,value){
            $('#lin'+moldeA+'_'+value.codigo).remove(); //reovendo aqui
        });
        //remove todas as linhas de aplicações pré inseridas
        $.each(pedido.produtos['item_'+moldeA]['aplicacoes'],function(key,value){
            $('#lin_'+value.idGeral).remove(); //reovendo aqui
        });
        
        //removendo do produtos array (o de controle pra abate)
        delete(pedido.produtos['item_'+moldeA]);
        
        //dando update no valor total
        alteraTotal();
      });
      //muda o molde pelo botão
    $(wrapper2).on("click", ".mudaMoldeBtnClass", function(e){ 
        var texto = $(e.target).attr('id');
        //console.log(texto);
      });
      //faz o download do molde pelo botão
    $(wrapper2).on("click", ".btnDownloadClass", function(e){ 
        var texto = $(e.target).attr('id');
        //console.log(texto);
      });
//muda aplicação do molde pelo botão ===================================================== PARTE MAIS COMPLICADA =======================
    
    //quando clica na imagem  
    $(wrapper2).on("click",'.mudaMoldeImg',function(e){ 
        texto = ($(e.target).attr('id')).split("_");
        $('#spanMoldeAtualTitle').text('-'+texto[1]); //muda o código que tá no topo
        idMoldeAtual = texto[1];
        
        
        //aqui fecha a janela de app (pq tava por cima)
        $(".janelaAplicacoes").hide();
    });
    
    //quando seleciona o novo molde (clicando nas novas imagens puxadas via json do bling)
    var janelaDosMoldesNovos = $("#listaMoldesDiv");
    $(janelaDosMoldesNovos).on("click",'.mudaMoldeImg',function(e){
    //e aqui, após cada alteração no valor do input altere os valores no objeto venda
        //console.log(idMoldeAtual);
        texto = $(e.target).attr('id').split('_');
        //PARTE IMPORTANTE, ELE PUXA DO BLING OS DADOS DO NOVO MOLDE SELECIONADO -------------------------------------------------------
        $.ajax({
                                    type: "POST",
                                    url: 'ajax/puxaDadosMoldeId.php',
                                    data: jQuery.param({idMoldeSelecionado: texto[1]}), //passa o id da mini imagem selecionada
                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                    success: function(data)
                                    {
                                        var retorno = JSON.parse(data);
                                        if(retorno?.error?.type != undefined){
                                                //removendo todos os elementos gráficos debaixo
                                                $.each(pedido.produtos['item_'+idMoldeAtual]['quantidades'],function(key,value){
                                                    $('#lin'+idMoldeAtual+'_'+value.codigo).remove(); //removendo aqui
                                                });
                                                //aqui removendo os elementos gráficos das aplicações
                                                $.each(pedido.produtos['item_'+idMoldeAtual].aplicacoes,function(key,value){
                                                    $('#lin_'+value.idGeral).remove(); //removendo aqui as linhas do molde
                                                });
                                                //limpando os tamanhos
                                                $("#tamanhos_"+idMoldeAtual).empty();
                                                
                                                pedido.produtos['item_'+idMoldeAtual].aplicacoes = []; //zerando as aplicações também (para evitar bugs)

                                                console.log('produto sem variantes');
                                                $.ajax({
                                                    type: "POST",
                                                    url: 'ajax/puxaDadosSemVariantes.php',
                                                    data: jQuery.param({idMoldeSelecionado: texto[1]}), //passa o id da mini imagem selecionada
                                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                                    success: function(data){
                                                            //AQUI ELE PUXA OS DADOS DO MOLDE COM TODAS AS CATEGORIAS, ATENÇÃO SUPREMA A ESSE RETORNO
                                                            var dadosMoldeNovo = JSON.parse(data);
                                                            console.log(dadosMoldeNovo);
                                                            pedido.produtos['item_'+idMoldeAtual].molde.codigo = dadosMoldeNovo.data.codigo; //update codigo
                                                            //pegando a descrição curta
                                                            var html = dadosMoldeNovo.data.descricaoCurta;
                                                            var div = document.createElement("div");
                                                            div.style.display = 'none'; //só pra n aparecer na tela
                                                            div.innerHTML = html;
                                                            var descricaoCurta = div.textContent || div.innerText || "";
                                                            pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta = JSON.parse(descricaoCurta); //update descricao curta
                                                            console.log(pedido.produtos);
                                                            
                                                            //aqui atualizando a imagem no json
                                                            var imageNova = 'moldes/'+dadosMoldeNovo.data.codigo+'.jpg'; 
                                                            pedido.produtos['item_'+idMoldeAtual].molde.imagem = imageNova;//update imagem
                                                            
                                                            pedido.produtos['item_'+idMoldeAtual].molde.preco = dadosMoldeNovo.data.preco;//e aqui o preço do molde atual
                                                            pedido.produtos['item_'+idMoldeAtual].molde.id = dadosMoldeNovo.data.id; //e aqui o id novo
                                                            
                                                            //E AGORA INSERINDO AS VARIAÇÕES (ID,NOME E CÓDIGO) *E JÁ APROVEITANDO O LOOP PARA CRIAR OS INPUTS DE TAMANHO
                                                            var tamanhos = "";
                                                            pedido.produtos['item_'+idMoldeAtual].quantidades = [];
                                                            //passando a altura e a largura predefinida, caso o molde já tenha :)
                                                            var altura = 0;
                                                            var largura = 0;
                                                            //parte interessante de ser explorada :) para diferenciar produtos sem variantes e altura e largura 
                                                            //altura = pedido?.produtos['item_'+idMoldeAtual].molde.descricaoCurta?.composicao['tecido-malha'][0].quantidade[0].Altura;
                                                            //largura = pedido?.produtos['item_'+idMoldeAtual].molde.descricaoCurta?.composicao['tecido-malha'][0].quantidade[1].Largura;

                                                            pedido.produtos['item_'+idMoldeAtual].quantidades.push({"codigo":dadosMoldeNovo.data.codigo,"id":dadosMoldeNovo.data.id,"quantidade":0,"nome":dadosMoldeNovo.data.nome,"preco":dadosMoldeNovo.data.preco,'altura':altura,'largura':largura});
                                                            
                                                            
                                                            
                                                            //inserindo os tamanhos (altura, largura e quantidade apenas)
                                                            tamanhos += "<th scope='col' style='width:200px;padding:0px;'>"+'Altura'+"<input id='"+idMoldeAtual+"_altura' type='number' style='width:100%;height:35px;text-align:center' value='0'></th>";
                                                            
                                                            tamanhos += "<th scope='col' style='width:200px;padding:0px;'>"+'Largura'+"<input id='"+idMoldeAtual+"_largura' type='number' style='width:100%;height:35px;text-align:center' value='0'></th>";
                                                            
                                                            tamanhos += "<th scope='col' style='width:200px;padding:0px;'>"+'Quantidade'+"<input id='"+idMoldeAtual+"_quantidade' type='number' style='width:100%;height:35px;text-align:center' value='0'></th>";
                                                            
                                                            //e aqui pegando o nome do molde que eu tinha esquecido
                                                            pedido.produtos['item_'+idMoldeAtual].molde.nome = dadosMoldeNovo.data.nome;
                                                            
                                                            $("#moldeImg_"+idMoldeAtual).attr('src',pedido.produtos['item_'+idMoldeAtual].molde.imagem); //muda a imagem
                                                            $("#downBtn_"+idMoldeAtual).attr('href',pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.moldeLink); //muda o link de download
                                                            $("#spanNomeMoldeAtual_"+idMoldeAtual).text(pedido.produtos['item_'+idMoldeAtual].molde.codigo); //muda o texto do molde
                                                            
                                                            $("#tamanhos_"+idMoldeAtual).append("<table class='table table-bordered text-center' style='border:1px solid #4b4c4e;margin-bottom:0px;height:10px'><thead style='background-color:#2e2f30;height:15px;text-align:center;color:white;font-size:14px'><tr>"+tamanhos+"</tr></thead></table>");
                                                            
                                                            //e aqui, inserindo a altura e a largura nos inputs
                                                            $("#"+idMoldeAtual+"_altura").val(altura);
                                                            $("#"+idMoldeAtual+"_largura").val(largura);
                                                            
                                                            //e dando um update no total
                                                            alteraTotal();
                                                    }
                                                });
                                        }
                                        // separação aqui =========================== Com variantes V A sem variantes
                                        else
                                        {
                                                console.log('produto com variantes');
                                                $.each(pedido.produtos['item_'+idMoldeAtual]['quantidades'],function(key,value){
                                                    //console.log("linha removida: #lin_"+varemove_fieldlue.idGeral);
                                                    $('#lin'+idMoldeAtual+'_'+value.codigo).remove(); //removendo aqui as linhas do molde
                                                });
                                                // e aqui removendo as linhas das aplicações
                                                $.each(pedido.produtos['item_'+idMoldeAtual].aplicacoes,function(key,value){
                                                    $('#lin_'+value.idGeral).remove(); //removendo aqui as linhas do molde
                                                });
                                                
                                                pedido.produtos['item_'+idMoldeAtual].aplicacoes = []; //zerando as aplicações também (para evitar bugs)
                                                
                                                //limpando os tamanhos
                                                $("#tamanhos_"+idMoldeAtual).empty();
                                                
    
                                                //AQUI ELE PUXA OS DADOS DO MOLDE COM TODAS AS CATEGORIAS, ATENÇÃO SUPREMA A ESSE RETORNO
                                                var dadosMoldeNovo = JSON.parse(data);
                                                //pegando a descrição curta vinda do bling (EXTREMAMENTE IMPORTANTE PARCEIRO!!!!!!) E INSERINDO NO PEDIDo
                                                pedido.produtos['item_'+idMoldeAtual].molde.codigo = dadosMoldeNovo.data.codigo; //update codigo
                                                
                                                //atualizando a descrição curta obs:essa parte só existe pq a descr curta vem com <p> e </p>
                                                    var html = dadosMoldeNovo.data.descricaoCurta;
                                                    var div = document.createElement("div");
                                                    div.style.display = 'none'; //só pra n aparecer na tela
                                                    div.innerHTML = html;
                                                    var descricaoCurta = div.textContent || div.innerText || "";
                                                pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta = JSON.parse(descricaoCurta); //update descricao curta
        
                                                
                                                //aqui atualizando a imagem no json
                                                var imageNova = 'moldes/'+dadosMoldeNovo.data.codigo+'.jpg'; 
                                                pedido.produtos['item_'+idMoldeAtual].molde.imagem = imageNova;//update imagem
                                                
                                                
                                                pedido.produtos['item_'+idMoldeAtual].molde.preco = dadosMoldeNovo.data.preco;//e aqui o preço do molde atual
                                                pedido.produtos['item_'+idMoldeAtual].molde.id = dadosMoldeNovo.data.id; //e aqui o id novo
                                                 
                                                //E AGORA INSERINDO AS VARIAÇÕES (ID,NOME E CÓDIGO) *E JÁ APROVEITANDO O LOOP PARA CRIAR OS INPUTS DE TAMANHO
                                                //OBSERVAÇÃO IMPORTANTE: aqui varia de acordo com o tipo de produto (alguns tem altura e largura, outros tem quantidade em kd variação)
                                                var tamanhos = "";
                                                pedido.produtos['item_'+idMoldeAtual].quantidades = [];
                                                $.each(dadosMoldeNovo.data.variacoes, function(key, value){
                                                    //if(pedido.produtos['item_'+idMoldeAtual].quantidades.length == 6){//evitar caixa de tamanhos gigante
                                                         //tamanhos += "</tr><tr>";
                                                    //}
                                                    var nomeTamanho = (dadosMoldeNovo.data.variacoes[key].nome).split(':');
                                                    pedido.produtos['item_'+idMoldeAtual].quantidades.push({"codigo":dadosMoldeNovo.data.variacoes[key].codigo,"id":dadosMoldeNovo.data.variacoes[key].id,"quantidade":0,"nome":nomeTamanho[1],"preco":dadosMoldeNovo.data.preco});
                                                    
                                                    tamanhos += "<th scope='col' style='width:200px;padding:0px;'>"+nomeTamanho[1]+"<input id='"+idMoldeAtual+"_"+dadosMoldeNovo.data.variacoes[key].codigo+"' type='number' style='width:100%;height:35px;text-align:center' value='0'></th>";
                                                    
                                                });

                                                //e aqui pegando o nome do molde que eu tinha esquecido
                                                pedido.produtos['item_'+idMoldeAtual].molde.nome = dadosMoldeNovo.data.nome;
                                                
                                                //E POR ÚLTIMO, mudando os elementos gráficos
                                                
                                                
                                                $("#moldeImg_"+idMoldeAtual).attr('src',pedido.produtos['item_'+idMoldeAtual].molde.imagem); //muda a imagem
                                                $("#downBtn_"+idMoldeAtual).attr('href',pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.moldeLink); //muda o link de download
                                                $("#spanNomeMoldeAtual_"+idMoldeAtual).text(pedido.produtos['item_'+idMoldeAtual].molde.codigo); //muda o texto do molde
                                                //aqui gerando os tamanhos com input
                                                $("#tamanhos_"+idMoldeAtual).empty();
                                                $("#tamanhos_"+idMoldeAtual).append("<table class='table table-bordered text-center' style='border:1px solid #4b4c4e;margin-bottom:0px;height:10px'><thead style='background-color:#2e2f30;height:15px;text-align:center;color:white;font-size:14px'><tr>"+tamanhos+"</tr></thead></table>");
                                                
                                                //e dando um update no total
                                                alteraTotal();
                                                }
                                    }
        });
    });
    
    //e aqui, altera  cada quantidade pelo valor digitado pelo usuário e gera as tabelas de preços
    $("#janela-moldes").on('input',function(e){
        $('.linhasGuia').remove()
        console.log('alterou em cima');
        var name = e.target.id;
        var moldeA = e.target.id.split("_")[0];
        var tam = e.target.id.split("_")[1];
        var precoUnit;
        var descricao = '';
        console.log('tamanho alterado:'+moldeA);
        //quantidade
        if(tam == 'quantidade' || tam == 'altura' || tam == 'largura'){ // aqui trato dos produtos sem variantes apenas 
        //remove todos as linhas pré inseridas ------------------------------------------------------
        if(tam=='quantidade'){
            $('#lin'+moldeA+'_'+ pedido.produtos['item_'+moldeA]['quantidades'][0].codigo).remove(); //reovendo aqui
        }
        
        //-------------------------------------------------------------------------------------------
        console.log('tamanhinho atual: '+tam);
            if(tam == 'quantidade'){
                pedido.produtos['item_'+moldeA]['quantidades'][0].quantidade = parseInt(e.target.value); //alterando quantidade sem variantes
            }else if (tam == 'altura'){
                pedido.produtos['item_'+moldeA]['quantidades'][0].altura = parseFloat(e.target.value);
            }else if(tam=='largura'){
                pedido.produtos['item_'+moldeA]['quantidades'][0].largura = parseFloat(e.target.value);
            }
            if(e.target.value == 0 && tam == 'quantidade'){
                    console.log('remove:'+'lin'+moldeA+"_"+tam); //debug
                    $('#lin'+moldeA+"_"+tam).remove(); //removendo a linha gerada
            }else{
                var wrapper = $("#insereMolde"); //janela de inserir os botões
                if($('#inp'+moldeA+"_"+tam).length == 0){
                    if(tam=='quantidade'){
                    $(wrapper).append("<tr style='height:20px;display:flex' id='lin"+moldeA+"_quantidade'><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+pedido.produtos['item_'+moldeA]['quantidades'][0].codigo+"</span></td><td scope='row' style='font-weight:normal;padding:0px;height:20px;width:60%;text-align:left;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span id='des"+moldeA+'_'+tam+"' style='font-size:12px;width:100%;background-color:#3a3b3c;color:white;height:30px;text-align:left;font-weight:normal'>"+pedido.produtos['item_'+moldeA]['quantidades'][0].nome+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span id='inp"+moldeA+'_'+tam+"' style='width:100%;background-color:#3a3b3c;color:white;height:30px;text-align:center'>"+e.target.value+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%'><input id='pre"+moldeA+'_'+tam+"' type='number' value='"+pedido.produtos['item_'+moldeA]['quantidades'][0].preco+"' style='width:100%;background-color:#3a3b3c;color:white;border:1px solid #4b4c4e;height:100%;text-align:center'></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span id='tot"+moldeA+'_'+tam+"' style='width:100%;color:white;height:30px;'>"+parseFloat(pedido.produtos['item_'+moldeA]['quantidades'][0].quantidade*pedido.produtos['item_'+moldeA]['quantidades'][0].preco).toFixed(2)+"</span></td></tr><div>");
                    
                    $('#inp'+moldeA+"_"+tam).text(e.target.value); //e aqui inserindo o valor de entrada no input das quantidades
                    $('#tot'+moldeA+'_'+tam).text((pedido.produtos['item_'+moldeA]['quantidades'][0].quantidade*pedido.produtos['item_'+moldeA]['quantidades'][0].preco).toFixed(2));
                    }
                }else{
                    if(tam=='quantidade'){
                        $('#inp'+moldeA+"_"+tam).text(e.target.value); //e aqui inserindo o valor de entrada no input das quantidades
                        $('#tot'+moldeA+'_'+tam).text((pedido.produtos['item_'+moldeA]['quantidades'][0].quantidade*pedido.produtos['item_'+moldeA]['quantidades'][0].preco).toFixed(2));
                    }
                }
            }
            
        } //cuidado não tinha (fim do primeiro if)
        else{
            console.log('ta passand0 n0 els3');
            if(moldeA != 'files'){ //PARA CASO NÃO SEJA O INPUT DA NOVA IMAGEM DO MOLDE
            //inserindo no json a nova quantidade (nos moldes)
        	$.each(pedido.produtos['item_'+moldeA]['quantidades'],function(key,value){
        	    if(pedido.produtos['item_'+moldeA]['quantidades'][key].codigo==tam){
        	        console.log('alterou quantidade do tamanho');
        	        pedido.produtos['item_'+moldeA]['quantidades'][key].quantidade = parseInt(e.target.value);
        	        precoUnit = pedido.produtos['item_'+moldeA]['quantidades'][key].preco;
        	        descricao = pedido.produtos['item_'+moldeA].molde.nome;
        	        
        	        deletaTodasAsLinhasApp(moldeA);
                    printaAplicacoes(moldeA);
                    alteraTotal();
        	    }
            });
            
            //atualizando os elementos gráficos para sumir caso seja 0 a quantidade
            if(e.target.value == 0){
                console.log('remove:'+'lin'+moldeA+"_"+tam);
                $('#lin'+moldeA+"_"+tam).remove();
            }
            else{ // e para gerar caso seja maior que zero 
                var wrapper = $("#insereMolde"); //janela de inserir os botões
                console.log('preçu:'+precoUnit);
                if($('#inp'+moldeA+"_"+tam).length == 0) {
                    $(wrapper).append("<tr style='height:20px;display:flex' id='lin"+moldeA+"_"+tam+"'><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+tam+"</span></td><td scope='row' style='font-weight:normal;padding:0px;height:20px;width:60%;text-align:left;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span id='des"+moldeA+'_'+tam+"' style='font-size:12px;width:100%;background-color:#3a3b3c;color:white;height:30px;text-align:left;font-weight:normal'>"+descricao+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span id='inp"+moldeA+'_'+tam+"' style='width:100%;background-color:#3a3b3c;color:white;height:30px;text-align:center'>"+e.target.value+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%'><input id='pre"+moldeA+'_'+tam+"' type='number' value='"+precoUnit+"' style='width:100%;background-color:#3a3b3c;color:white;border:1px solid #4b4c4e;height:100%;text-align:center'></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span id='tot"+moldeA+'_'+tam+"' style='width:100%;color:white;height:30px;'>"+(e.target.value*precoUnit).toFixed(2)+"</span></td></tr><div>");
                    
                    
                }else{
                    console.log('entrou no primeiro elzin');
                    console.log('idzin:'+'#inp'+moldeA+"_"+tam);
                        $('#inp'+moldeA+"_"+tam).text(e.target.value); //e aqui inserindo o valor de entrada no input das quantidades
                        $('#tot'+moldeA+'_'+tam).text((e.target.value*precoUnit).toFixed(2));
                }
            }
            //inserindo em todas as aplicações daquele molde a nova quantidade
            updateQtdApp(moldeA);
            }
            else{ //se for a nova imagem do molde :)
              console.log('entrou aquiiii');
              var moldeId = e.target.id.split("_")[1];
              var form_data = new FormData();
    
              //Read selected files
              var totalfiles = document.getElementById('files_'+moldeId).files.length;
              for (var index = 0; index < totalfiles; index++) {
                   form_data.append("files[]", document.getElementById('files_'+moldeId).files[index]);
              }
              //AJAX request
              var local = 'ajaxFile.php?idMolde='+String(pedido.id);
              $.ajax({
                   url: local, 
                   type: 'post',
                   data: form_data,
                   dataType: 'json',
                   contentType: false,
                   processData: false,
                   success: function (response){
                        for(var index = 0; index < response.length; index++) {
                             var src = response[index];
                             console.log('molde a:'+response);
                             //e agora, inserindo a nova imagem no json atual (no json)
                             pedido.produtos['item_'+moldeId].molde.imagem = src;//update imagem
                             //e aqui o elemento gráfico
                             $("#moldeImg_"+moldeId).attr('src',pedido.produtos['item_'+moldeId].molde.imagem); //muda a imagem
                             //Add img element in <div id='preview'>
                             //$('#preview').append('<img src="'+src+'" width="200px;" height="200px">');
                        }
                   }
              });
            }
        }
        //independente da alteração atualize o valor final do pedido com base nos dados inseridos do json
        var totalPedido = 0;
        $.each(pedido.produtos,function(key,value){
            $.each(value.quantidades,function(key,value){
                if(value.quantidade != 0){ //passando os dados do produto para itens
                    totalPedido += parseInt(value.quantidade) * parseFloat(value.preco);
                }
            })
        }); 
        $('#valorFinal').text(totalPedido.toFixed(2));
        alteraTotal();
    });
    
        
    //e aqui, para caso o vendedor queira alterar os preços unitários (a parte debaixo onde tem os preços) -----------------------------------
    $("#insereMolde").on('input',function(e){
        console.log('alterou embaixo');
        var name = e.target.id;
        var nomeRed = name.slice(3);
        var moldeA = nomeRed.split("_")[0];
        var tam = nomeRed.split("_")[1];
        //console.log('REDUZED:'+name);
        if(name.slice(0,3)=='pre'){
            console.log('nan aqui ');
            var quantidade;
            if(pedido.produtos['item_'+moldeA]?.quantidades?.[0]?.altura != undefined){
                pedido.produtos['item_'+moldeA]['quantidades'][0].preco = parseFloat(e.target.value);
                quantidade = parseInt(pedido.produtos['item_'+moldeA]['quantidades'][0].quantidade);
                $('#tot'+moldeA+'_'+tam).text((e.target.value*quantidade).toFixed(2)); //atualizando o preço final do tamanho 
            }else{
                $.each(pedido.produtos['item_'+moldeA]['quantidades'],function(key,value){
            	    if(pedido.produtos['item_'+moldeA]['quantidades'][key].codigo==tam){
            	        pedido.produtos['item_'+moldeA]['quantidades'][key].preco = parseFloat(e.target.value);
            	        quantidade = parseInt(pedido.produtos['item_'+moldeA]['quantidades'][key].quantidade);
            	    }
                 });
                 $('#tot'+moldeA+'_'+tam).text((e.target.value*quantidade).toFixed(2)); //atualizando o preço final do tamanho 
            }     
            
        }else if(name.slice(0,3)=='App'){
           console.log('é uma aplicacion');
           var keyAtual = 0;
           $.each(pedido.produtos['item_'+moldeA].aplicacoes,function(key,value){
            	    if(pedido.produtos['item_'+moldeA]['aplicacoes'][key].idGeral=='App_'+moldeA+'_'+tam){
            	       pedido.produtos['item_'+moldeA]['aplicacoes'][key].valor = parseFloat(e.target.value); // pronto, app
            	       keyAtual = parseInt(key);
            	    }
                //aqui calcula o valor total das aplicações daquele molde
                console.log('keyzinha:'+keyAtual);
                $("#tot"+moldeA+"_"+tam).text(pedido.produtos['item_'+moldeA].aplicacoes[keyAtual].valor*pedido.produtos['item_'+moldeA].aplicacoes[keyAtual].quantidade);
                
                //
           });
        }
        else{
                var precoUnit;
                $.each(pedido.produtos['item_'+moldeA]['quantidades'],function(key,value){
            	    if(pedido.produtos['item_'+moldeA]['quantidades'][key].codigo==tam){
            	        if(e.target.value == 0){
                            $('#lin'+moldeA+"_"+tam).remove();
                             $('#'+moldeA+'_'+tam).val(e.target.value);
                        }else{
            	            precoUnit = parseFloat(pedido.produtos['item_'+moldeA]['quantidades'][key].preco);
                        }
                        pedido.produtos['item_'+moldeA]['quantidades'][key].quantidade = parseInt(e.target.value);
            	    }
            	    
                 });
                 if(tam!='quantidade' || tam!='altura' || tam!= 'largura'){
                     $('#total'+moldeA+'_'+tam).text((e.target.value*precoUnit).toFixed(2)); //atualizando o preço final do tamanho
                     $('#'+moldeA+'_'+tam).val(e.target.value); // e aqui inserindo a nova quantidade no molde em cima
                 }
                 
        }
        //inserindo em todas as aplicações daquele molde a nova quantidade (porque alterou)
        updateQtdApp(moldeA);

        //independente da alteração atualize o valor final do pedido com base nos dados inseridos do json (inserido depois !!!)
        var totalPedido = 0;
        $.each(pedido.produtos,function(key,value){ //primeiro somando os tamanhos
            $.each(value.quantidades,function(key,value){
                if(value.quantidade != 0){ //passando os dados do produto para itens
                    totalPedido += parseInt(value.quantidade) * parseFloat(value.preco);
                }
            })
        }); 
        
        var totalApp = 0;
        $.each(pedido.produtos,function(key,value){ //e depois somando o total das aplicações
            $.each(value.aplicacoes,function(key,value){
                if(value.quantidade != 0){ //passando os dados do produto para itens
                    totalApp += parseInt(value.quantidade) * parseFloat(value.valor);
                }
            })
        }); 
        console.log('total app:'+totalApp);
        $('#valorFinal').text((totalPedido+totalApp).toFixed(2));
    }); 
    
    //ultimo botão (pega o objeto editado e atualizado no database)
    $("#btnSalvar").click(function(e){
            $.ajax({
                type: "POST",
                url: 'ajax/salvaPedidoEsteira.php',
                data: jQuery.param({idPedido: pedido.id,
                                    idMolde: Object.keys(pedido.produtos)[0],
                                    pedidoJson: pedido,
                                    imagem_alterada: pedido.produtos['item_'+idMoldeAtual].molde.imagem,
                                    quantidades: JSON.stringify(pedido.produtos['item_'+idMoldeAtual].quantidades),
                                    horaUpdate: pedido.observacoesInternas.horaEntrega,
                                    dataEntrega_: pedido.dataPrevista
                }),
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function(data)
                {
                    alert('Pedido atualizado com sucesso!');
                }
            });
    });
    
    //ultimo botão (pega o objeto editado e atualizado no database)
    $("#btnEnviaBling").click(function(e){
        //aqui ele faz a verificação de tudo antes de enviar para análise======================================
        var prossegue = true;
        var totalPedido = String($("#valorFinal").text());
        
        var frete = $("#precoTotalFrete").val();
        //verifica se tem produtos marcados ou se o valor da venda da zerado
        if(totalPedido == '0.00' || totalPedido == 'R$ 0.00'){
            alert('Insira produtos no pedido'); 
            prossegue = false;
            return false;
        }
        //primeiro verifica as parcelas =======================================================================
        var totalParcelas = 0;
        $.each(pedido.parcelas,function(key,value){
            //console.log(value);
            totalParcelas += parseFloat(value.valor);
        });
        
        //console.log(totalPedido+"//"+totalParcelas+'//'+frete);
        if(parseFloat(parseFloat(totalPedido)+parseFloat(frete)).toFixed(2) != parseFloat(totalParcelas).toFixed(2)){
            console.log('total pedido:' + totalPedido);
            console.log('total frete:' + frete);
            console.log('totalParcelas:' + totalParcelas);
            prossegue = false;
            alert('Valor das parcelas diferente do total do pedido+frete');
            return false;
        }
        //depois verifica se todos os tecidos estão presentes ================================================
        $.each(pedido.produtos,function(key,value){
            $.each(value.molde.descricaoCurta.composicao,function(key2,value2){
                if(value2[value2.length-1].selecionado == ''){
                    if(key2 == 'tecido-malha'){
                        alert("Faltou selecionar "+key2+" de : #"+key);
                        prossegue = false;
                        return false; //apenas para parar a execução do código
                    }
                }
            });
            if(prossegue == false){
                return false; //apenas para parar a execução do código
            }
        });
        //depois verifica se tem molde com quantidades zeradas===============================================
        $.each(pedido.produtos,function(key,value){
            var zerado = true;
            $.each(value.quantidades,function(key2,value2){
                if(value2.quantidade != '0'){
                    zerado = false;
                }
            });
            if(zerado == true){
                alert('produto #'+key+' com quantidades zeradas');
                prossegue = false;
            }
        });

        //depois verifica se tem algum cliente selecionado ==================================================
        if(pedido.contato.id == '0' || pedido.contato.nome == '' || pedido.contato.numeroDocumento == ''){
            alert("Faltou selecionar o cliente");
            prossegue = false;
        }
        //depois verifica se tem o link do drive selecionado
        if(pedido?.observacoesInternas?.pastaDrive == ''){
             alert("Faltou informar a pasta do drive");  
             prossegue = false;
        }
        
        if(prossegue == true){
            if (window.confirm('Gerar venda no bling?')){
                window.location='apenasGeraVendaBling.php?numeroPedido='+pedido.id;
            }
        }
        
    });

//E AQUI A PARTE QUE TRATA DE TODAS AS APLICAÇÕES E COMPOSIÇÕES (TODOS OS SCRIPTS FORAM MUDADOS PARA CÁ PARA FACILITAR E REPARAR O CÓDIGO) =======================
$(wrapper2).on("click", ".appMoldeBtn", function(e){ 
    idMoldeAtual = e.target.id.split('_')[1]; //pega o id do molde atual pelo id do botaozinho de aplicação (IMPORTANTE)
    var selecaoAdd = ''; //vazio inicialmente, mas que vai receber o html que será inserido na janela de composição
    var observacoes = ""; //vazio, mas que vai receber as observações
    $("#janelaApp").empty(); //esvazia todas as aplicações *para receber as novas dinamicamente
    $("#janelaCarac").empty(); //esvazia as característcas *para receber as novas do json
    
    //0° primeiro, ele printa o nome aplicações e o botão de add app já com as novas funções do molde selecionado


    var composicao = pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao;
    //Aqui, ele percorre as composições e  
    $.each(pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao,function(key,value){
        //console.log(value);
        selecaoAdd += "<div><div class='alinhado alinhado-centro'><span style='margin-left:5px'>"+key+"</span></div>";
        selecaoAdd+="<div class='alinhado-direita'><div class='dropdown'><button class='btn btn-secondary dropdown-toggle' type='button' id='com_"+idMoldeAtual+"_"+key+"' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Selecione</button><div id='opt_"+idMoldeAtual+"_"+key+"' class='dropdown-menu' aria-labelledby=''></div></div></div>";
        
        selecaoAdd += "<div class='alinhado-direita'><div class='dropdown'><button class='btn btn-secondary dropdown-toggle' type='button' id='com_"+idMoldeAtual+"_"+key+"_btn1' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Selecione</button><div class='dropdown-menu' aria-labelledby=''>";
        
        $.each(value,function(key2,value2){
            //console.log(value2);
            if(key2!=0 && key2 != value.length-1){
                selecaoAdd+="<a class='dropdown-item' href='javascript:puxaCoresTecidos("+value2.codigo+","+'"'+key+'"'+","+idMoldeAtual+");'>"+value2.nome+"</a>";
            }
        });
        selecaoAdd+="</div></div></div></div><br>";
        
        //aqui, ele verifica se já tem alguma composição pré selecionada, se já tiver ele a insere na boa
        var ultimaPos = pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[key].length - 1;
        console.log('última pos:'+ultimaPos);
        selecaoAdd+="<div id='sel_"+idMoldeAtual+"_"+key+"'>";
        if(pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[key][ultimaPos].selecionado != ''){
            var text = String(pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[key][ultimaPos]);
            pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[key][ultimaPos] = text.replace("//",String(idMoldeAtual));
            console.log(String(pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[key][ultimaPos]));

            selecaoAdd+="<div id='sel_"+idMoldeAtual+"_"+key+"'><table class='fonte-pequena' width='100%' style='color:white'><tbody><tr><td rowspan='4' style='width:35px'><img src='moldes/tecidos/"+String(pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[key][ultimaPos]).split("_")[1]+".jpg' width='75px'></td></tr><tr><td>Nome: "+String(pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[key][ultimaPos]).split("_")[0]+"</td></tr><tr><td>Código: "+String(pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[key][ultimaPos]).split("_")[1]+"</td></tr><tr><td>Estoque: <span id='estoque_"+key+"_"+idMoldeAtual+"'>-</span> <button class='btnExcluiComp' onClick='javascript:removeComp("+'"'+idMoldeAtual+'"'+","+'"'+key+'"'+");''>X</button></td></tr></tbody></table>";
        }
        selecaoAdd+= "</div>";
        $("#janelaCarac").append(selecaoAdd);
        selecaoAdd = "";
    });

    //AQUI TUDO QUE PRECISA SER FEITO, AO CLICAR NO BOTÃO DE COMPOSIÇÃO
    //2° Mudar o texto de observações
    observacoes = "<textarea id='observacoes_"+idMoldeAtual+"' class='form-control observacoesClass' rows='3' style='color:red' onkeyup='atualizaObservacoes("+idMoldeAtual+")'>"+String(pedido.produtos['item_'+idMoldeAtual].molde.Observacoes).replaceAll(/\n/g,' ')+"</textarea>";
    pedido.produtos['item_'+idMoldeAtual].molde.Observacoes = String(pedido.produtos['item_'+idMoldeAtual].molde.Observacoes).replaceAll(/\n/g,' '); //isso aqui só para evitar bugs
    $("#janelaObservacoes").empty();
    $("#janelaObservacoes").append(observacoes);

    //tratando a janela de aplicações aqui
    //1° apenas atualizando a posição da div 
    //console.log($( ".janelaAplicacoes" ).style.display);
    
    $(".janelaAplicacoes").toggle(250);
    //$(".janelaAplicacoes").stopPropagation();

    var pos = $(e.target).offset();
    pos.left = pos.left+20;
    pos.top = pos.top-250;
    $(".janelaAplicacoes").offset(pos);
    
    //2° esvaziando a janela
    $("#janelaAplicacoes").empty();
    //3° printa o nome e o botão de add e adiciona todas as aplicações que o molde atual já possui
    $("#janelaAplicacoes").append("<div style='background-color:#2e2f30;color:white;font-family: system-ui;display:inline-block;width:91%;height:29px;padding-left:23px'>Aplicações</div><button class='btn btn- btn-sm botaoAddCompo col-sm-1' id='' onclick='addAppMolde("+idMoldeAtual+")'>+</button>");
    $.each(pedido.produtos['item_'+idMoldeAtual].aplicacoes, function(key,value){
        console.log(value.observacoes);
         $("#janelaAplicacoes").append("<div><div style='width:40px;display:inline-block'>"+value.idGeral.split('_')[2]+"</div><select id='"+value.idGeral+"' class='selectAplicacoes' style='height: 29px;font-size: 12px;color: white;background-color:#3a3b3c;text-align:center;width:80%;'><option value=''>"+value.nome+"</option></select><button type='button' class='btn btn- btn-sm botaoAddCompo deleteApp' onClick='javascript:deletaApp("+'"'+value.idGeral+'"'+","+idMoldeAtual+");' style='height:29px;width:29px;line-height: 0.9;color:white;margin-top:-1px'>x</button><textarea id='appObs_"+idMoldeAtual+"_"+value.idGeral.split('_')[2]+"' class='form-control' rows='1' style='color:red' onkeyup='updateAppObservacao("+'"'+String(value.idGeral.split('_')[1])+"_"+String(value.idGeral.split('_')[2])+'"'+")'>"+value.observacoes+"</textarea><div></div></div>");
     });
     
}); 
    
});  
//e aqui, só para caso clique em qualquer lugar que não a janela de aplicações, a esconda
$(document).mouseup(function(e) 
{
    var idMoldeAtual = '10';
    var container = $(".janelaAplicacoes");
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
        container.hide();
        //pedido.produtos['item_'+idMoldeAtual].molde.observacoes <-tava dentro dos parenteses de hide
    }
});


function removeComp(idMoldeAtual,nomeComp2){
    console.log('removido:'+"#sel_"+idMoldeAtual+"_"+nomeComp2);
    $("#sel_"+idMoldeAtual+"_"+nomeComp2).empty();
    var ultimaPos = pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[nomeComp2].length - 1;
    pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[nomeComp2][ultimaPos] = {"selecionado":""};
}

<!---------------------------------------------------- PARTE QUE AUTO COMPLETA, NÃO MECHA :) -->

function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          b.style.backgroundColor = '#3a3b3c';
          b.style.color = 'grey';
          b.style.width = '650px';
          b.style.marginLeft = '65px';
          /*make the matching letters bold:*/
          b.innerHTML = "<strong style=color:white>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
            NomeClienteAtual = ($("#myInput").val().split('|'))[0];
            idClienteAtual = ($("#myInput").val().split('|'))[1];
            //PARTE CRUCIAL - É AQUI QUE É PEGO OS DADOS DO CLIENTE DO BLING (COM BASE NO CLICK E INSERIDO DIRETAMENTE NO JSON DA VENDA OBS: SÓ SERÁ MUDADO NO DATABASE DPS DE SALVAR!)
            if(idClienteAtual!=''){
            console.log(idClienteAtual);
            //AQUI SÓ PREENCHE O INPUT
            $("#myInput").val(NomeClienteAtual);
            $("#buscaClienteBtn").css("background-color", "green");
            $("#buscaClienteBtn").css("color", "white");
            }
            //PREENCHENDO O JSON
            $.ajax({
                                    type: "POST",
                                    url: 'ajax/pegaDadosCliente.php',
                                    data: jQuery.param({codigoCliente: idClienteAtual,
                                    }) ,
                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                    success: function(data)
                                    {
                                        console.log(data);
                                        var dadosCliente = data.split(',');
                                        var enderecoCliente =  dadosCliente[0];
                                        console.log(dadosCliente);
                                        
                                        //alert("VERIFIQUE SE OS DADOS ESTÃO CORRETOS: <br>"+data);
                                        pedido.contato.id = dadosCliente[16];
                                        pedido.contato.nome = NomeClienteAtual;
                                        pedido.contato.numeroDocumento = dadosCliente[3];
                                        pedido.contato.tipoPessoa = dadosCliente[2];
                                        //aqui falta inserir os dados de entrega
                                        pedido.transporte.etiqueta.bairro = dadosCliente[10];
                                        pedido.transporte.etiqueta.endereco = String(dadosCliente[7]).replaceAll("'",'');//dadosCliente[7]
                                        pedido.transporte.etiqueta.municipio = dadosCliente[9];
                                        pedido.transporte.etiqueta.nome = NomeClienteAtual;
                                        pedido.transporte.etiqueta.nomePais = 'Brasil';
                                        pedido.transporte.etiqueta.numero = dadosCliente[11];
                                        pedido.transporte.etiqueta.uf = dadosCliente[8];
                                        pedido.transporte.etiqueta.complemento = dadosCliente[12];
                                        pedido.transporte.etiqueta.cep = dadosCliente[6];
                                        
                                        //aqui atualizando as entradas inferiores de frete com base nos dados puxados
                                        $('#tipoDeFrete option[value="'+pedido.transporte.tipoFrete+'"]').prop('selected', true);
                                        $('#nomeFrete').val(NomeClienteAtual);
                                        $("#cepFrete").val(dadosCliente[6]);
                                        $('#ufFrete option[value="'+dadosCliente[8]+'"]').prop('selected', true);
                                        $("#numeroFrete").val(dadosCliente[11]);
                                        $("#bairroFrete").val(dadosCliente[10]);
                                        $("#municipioFrete").val(dadosCliente[9]);
                                        $("#enderecoFrete").val(dadosCliente[7]);
                                        $("#precoTotalFrete").val(pedido.transporte.frete);
                                        
                                        //e aqui atualizando o update do lado esquerdo
                                        $("#update_nomeCliente").val(NomeClienteAtual);
                                        $("#update_nomeFantasiaCliente").val(dadosCliente[1]);
                                        $('#update_tipoPessoa option[value="'+dadosCliente[2]+'"]').prop('selected', true);
                                        $("#update_clienteCpf").val(dadosCliente[3]);
                                        $('#update_inputCont option[value="'+dadosCliente[4]+'"]').prop('selected', true);
                                        $("#update_clienteInscEst").val(dadosCliente[5]);
                                        $("#update_inputCep").val(dadosCliente[6]);
                                        $('#update_inputUf option[value="'+dadosCliente[8]+'"]').prop('selected', true);
                                        $("#update_numero").val(dadosCliente[11]);
                                        $("#update_inputCidade").val(dadosCliente[9]);
                                        $("#update_inputBairro").val(dadosCliente[10]);
                                        $("#update_complemento").val(dadosCliente[7]);
                                        
                                        $("#update_inputTelefone").val(dadosCliente[13]);
                                        $("#update_inputCelular").val(dadosCliente[14]);
                                        $("#update_inputEmail").val(dadosCliente[15]);
                                        $("#update_infAdicionais").val(dadosCliente[12]);
                                        
                                        codigoCurtoCliente = dadosCliente[17];//atenção nesse código, pois vai ser usado para dar update nos dados do cliente
                                    }
            });
            closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}
</script>




<script>
    //PARTE DESTINADA A LEITURA DOS DADOS INICIAIS E APRESENTAÇÃO NA TELA (para evitar misturar com os códigos de edição) ATENÇÃO ESPECIAL AQUI :)
    //muda o valor com o cliente atual
    if(pedido.contato.nome != '-'){
        $("#myInput").val(pedido.contato.nome);
        $("#buscaClienteBtn").css("background-color", "green");
        $("#buscaClienteBtn").css("color", "white");
    }
    
    $("#pastaDriveInput").val(pedido.observacoesInternas.pastaDrive);
    $("#spanNumeroPedido").text(pedido.id);
    
     //pegando a data atual e inserindo no json
                                        var today = new Date();
                                        var dd = String(today.getDate()).padStart(2, '0');
                                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                                        var yyyy = today.getFullYear();
                                        
                                        today = yyyy + '-' + mm + '-' + dd;
                                        
    //e aqui, pegando a data preenchida já inserida antes no json e...
    //if(pedido.data == ''){
        //pedido.data(today);
    //}
    pedido.data = today;
    
    $('#dataPedidoInp').val(today);
    $("#dataEntregaInp").val(pedido.dataPrevista);
    
    //printa os moldes
    if(pedido.produtos != {}){
    $.each(pedido.produtos, function(key, value){
      x = key.split('_');
      x = x[1];
      //apenas para o vendedor saber qual molde é qual    
      $("#insereMolde").append("<tr class='linhasGuia' style='height:20px;display:inline-table;width:100%' id=''><td colspan='5' scope='row' style='font-size:12px;padding:0px;height:20px;width:10%;text-align:center;background-color:#2e2f30;border:1px solid #4b4c4e;'><span>"+pedido.id+"#"+x+"</span></td></tr><div>");  
      //fim aqui
        
      
      
      var wrapper2 = $("#janela-moldes"); //janela de inserir moldes
      var wrapper = $("#insereMolde"); //janela de inserir os botões
      var tamanhos = ""; //string q vai receber os tamanhos vindos do json (dados já preenchidos antes) 
      //percorrendo o array e inserindo cada tamanho
      if(pedido.produtos['item_'+x]?.quantidades?.[0]?.altura != undefined){
          console.log('sem variantes');
          //tamanhos
          tamanhos += "<th scope=col style='width:200px;padding:0px;'>"+'Altura'+"<input id='"+x+"_"+'altura'+"' type='number' style='width:100%;height:35px' value="+pedido.produtos['item_'+x]['quantidades'][0].altura+"></th>";
          tamanhos += "<th scope=col style='width:200px;padding:0px;'>"+'Largura'+"<input id='"+x+"_"+'largura'+"' type='number' style='width:100%;height:35px' value="+pedido.produtos['item_'+x]['quantidades'][0].largura+"></th>";
          tamanhos += "<th scope=col style='width:200px;padding:0px;'>"+'Quantidade'+"<input id='"+x+"_"+'quantidade'+"' type='number' style='width:100%;height:35px' value="+pedido.produtos['item_'+x]['quantidades'][0].quantidade+"></th>";
          //tabela inferior
          if(pedido.produtos['item_'+x]['quantidades'][0].quantidade != 0){
              $(wrapper).append("<tr style='height:20px;display:flex' id='lin"+x+"_"+pedido.produtos['item_'+x]['quantidades'][0].codigo+"'><td scope='row' style='font-size:12px;padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+pedido.produtos['item_'+x]['quantidades'][0].codigo+"</span></td><td scope='row' style='font-size:12px;font-weight:normal;padding:0px;height:20px;width:60%;text-align:left;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+pedido.produtos['item_'+x]['quantidades'][0].nome+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;font-weight:normal'><span id='inp"+x+'_'+pedido.produtos['item_'+x]['quantidades'][0].codigo+"' style='width:100%;background-color:#3a3b3c;color:white;height:30px;text-align:center'>"+parseInt(pedido.produtos['item_'+x]['quantidades'][0].quantidade)+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%'><input id='pre"+x+'_'+pedido.produtos['item_'+x]['quantidades'][0].codigo+"' type='number' value='"+parseFloat(pedido.produtos['item_'+x]['quantidades'][0].preco).toFixed(2)+"' style='width:100%;background-color:#3a3b3c;color:white;border:1px solid #4b4c4e;height:100%;text-align:center;height:100%'></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span id='tot"+x+'_'+pedido.produtos['item_'+x]['quantidades'][0].codigo+"'style='width:100%;color:white;height:30px;'>"+(parseFloat(pedido.produtos['item_'+x]['quantidades'][0].preco)*parseInt(pedido.produtos['item_'+x]['quantidades'][0].quantidade)).toFixed(2)+"</span></td></tr><div>");
          }
        }
          
      else{
          $.each(pedido.produtos['item_'+x]['quantidades'],function(key,value){
        	    //onsole.log(pedido.produtos['item_'+x]['quantidades'][key]);
        	    tamanhos += "<th scope=col style='width:200px;padding:0px;'>"+pedido.produtos['item_'+x]['quantidades'][key].nome+"<input id='"+x+"_"+pedido.produtos['item_'+x]['quantidades'][key].codigo+"' type='number' style='width:100%;height:35px' value="+pedido.produtos['item_'+x]['quantidades'][key].quantidade+"></th>";
    
        	    if(parseInt(value.quantidade)!= 0){
        	    $(wrapper).append("<tr style='height:20px;display:flex' id='lin"+x+"_"+value.codigo+"'><td scope='row' style='font-size:12px;padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+value.codigo+"</span></td><td scope='row' style='font-size:12px;font-weight:normal;padding:0px;height:20px;width:60%;text-align:left;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+pedido.produtos['item_'+x].molde.nome+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;font-weight:normal'><span id='inp"+x+'_'+value.codigo+"' style='width:100%;background-color:#3a3b3c;color:white;height:30px;text-align:center'>"+parseInt(value.quantidade)+"</span></td><td scope='row' style='padding:0px;height:20px;width:10%'><input id='pre"+x+'_'+value.codigo+"' type='number' value='"+parseFloat(value.preco).toFixed(2)+"' style='width:100%;background-color:#3a3b3c;color:white;border:1px solid #4b4c4e;height:100%;text-align:center;height:100%'></td><td scope='row' style='padding:0px;height:20px;width:10%;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span id='tot"+x+'_'+value.codigo+"'style='width:100%;color:white;height:30px;'>"+(parseFloat(value.preco)*parseInt(value.quantidade)).toFixed(2)+"</span></td></tr><div>");
        	    }
          });
      }
      $(wrapper2).append("<div id='janelaAtual_"+x+"' class='col-sm-4' style='float:left;height:340px; width:245px;border-radius:8px;display:inline-block;vertical-align:middle;margin-right:-4px;margin-bottom:20px'><div class='row'><div class='container boxImgMolde'><div clss='col-sm-12' style='background-color:#2e2f30;border-radius: 10px 10px 0px 0px; padding-top:2px'><a href='javascript:exibeResumoPedido()' data-toggle='modal' data-target='#exampleModal' id='butExibePedido_"+x+"'><i class='fa fa-vest' aria-hidden='true'></i></a><span style='margin-left:25px'>#"+pedido.id+"-"+x+"</span><button type='button' id='"+x+"' class='btn btn- btn-sm remove_field' onclick='' style='height: 18px;width:20px; line-height: 1.25; border:1px solid red; display:inline; float:right;color:green;margin-right:5px;font-size:10px;background-color:#3a3b3c' alt='orçamento'><span style='margin-left:-2px;color:red'>X</span></button></div><a id='imgMolde' href='#' ><img id='moldeImg_"+x+"' class='mudaMoldeImg' data-toggle='modal' data-target='#modalMolde' src='"+pedido.produtos['item_'+x].molde.imagem+"' alt='Minha Figura' width='100%' style='background-color:white'></a><div col-sm-12 style='margin-top:3px'><button type='button' id='appMoldeBtn' class='btn btn- btn-sm' onclick='' style='height: 18px;width:20px; line-height: 0.7; border:1px solid green; display:inline; float:right;color:green;margin-left:-3px' alt='orçamento'><i id='appBtn_"+x+"' class='fa fa-bookmark fa-sm appMoldeBtn' style='margin-left:-3.5px;margin-top:-3px'></i></button><button type='button' class='btn btn- btn-sm' onclick='' style='height: 18px;width:20px; line-height: 0.7; border:1px solid green; display:inline; float:left;color:green;margin-left:3px;margin-right:5px' alt='orçamento'><a id='downBtn_"+x+"' href='"+pedido.produtos[key].molde.descricaoCurta.moldeLink+"' target='_blank'><i class='fa fa-download fa-2xs btnDownloadClass' style='margin-left:-5.5px;margin-top:-3px'></i></a></button><button style='display:block;width:20px; height:18px;background-color:#2e2f30;border:1px solid green;color:green'><i id='upBtn_"+x+"' class='fa fa-upload fa-2xs btnUpClass' style='margin-left:-5.5px;margin-top:-3px;color:green;' onclick="+'"'+"document.getElementById("+"'"+"files_"+x+"'"+").click()"+'"'+"></i></button><input type='file' id='files_"+x+"' style='display:none' multiple><span id='spanNomeMoldeAtual_"+x+"' style='margin-left:-20px'>"+pedido.produtos[key].molde.codigo+"</span></div><div id='tamanhos_"+x+"' style='width:220px;height:60px;background-color:#2e2f30; border-radius: 0px 0px 10px 10px'> <table class='table table-bordered text-center' style='border:1px solid #4b4c4e;margin-bottom:0px;height:10px'><thead style='background-color:#2e2f30;height:15px;text-align:center;color:white'><tr>"+tamanhos+"</tr></thead></table></div></div></div></div>");

        //printa a tabela inferior
        printaAplicacoes(x); // aplicações (e a call function de alterar o total) 
        
        });
        
        
        //parte que adiciona as parcelas
        if(pedido?.parcelas != undefined){
            var parcela = (retornaTotalPedido()+pedido.transporte.frete)/pedido.parcelas.length; //5° calcula o valor de cada parcela (ATENÇAO ESPECIAL AQUI) ============
        }else{
            var parcela = 1;
        }
        
        
        
        $.each(pedido.parcelas,function(key,value){
            //console.log(value);
            var dataExibe = value.dataVencimento.split('-').reverse().join('/');
            $("#parcelasInseridas").append("<div class='row' style='padding:0px;margin:0px' id='parcela_"+value.idGeral+"'><div class='col-sm-1 comp' style='padding:0px;margin:0px'>"+parseInt(value.dias)+"</div><div class='col-sm-2 comp' style='padding:0px;margin:0px'>"+dataExibe+"</div><div class='col-sm-1 comp' style='padding:0px;margin:0px'><input id='valorParcela_"+value.idGeral+"' class='valorParcelaInput' onkeyup='alterouValorParcela("+value.idGeral+")' type='number' style='width:100%;height:25px;text-align:center;background-color:#3a3b3c;color:white' value='"+parseFloat(value.valor).toFixed(2)+"'></div><div class='col-sm-7 comp' style='padding:0px;margin:0px'><select id='selectPagamentoParc_"+value.idGeral+"' class='valorSelectParc' style='height:25px;font-size: 12px;color: white;background-color:#3a3b3c;text-align:center;width:100%'><option value=''>"+value.observacoes+"</option></select></div><div class='col-sm-1' style='padding:0px;margin:0px'><button type='button' class='btn btn- btn-sm botaoAddCompo deleteApp' onclick='javascript:removeParcela("+value.idGeral+");' style='line-height: 0.9;color:white;width:100%;'>x</button></div></div>");
        });
        
        //parte que seleciona a forma de pagamento pre preenchida
        $('#tipoPagamento option[value="'+pedido.categoria.id+'"]').prop('selected', true);
        
        //parte que insere as informações de entrega pré preenchdias
        $('#tipoDeFrete option[value="'+pedido.transporte.tipoFrete+'"]').prop('selected', true);
        $('#nomeFrete').val(pedido.transporte.etiqueta.nome);
        $("#cepFrete").val(pedido.transporte.etiqueta.cep);
        $('#ufFrete option[value="'+pedido.transporte.etiqueta.uf+'"]').prop('selected', true);
        $("#numeroFrete").val(pedido.transporte.etiqueta.numero);
        $("#bairroFrete").val(pedido.transporte.etiqueta.bairro);
        $("#municipioFrete").val(pedido.transporte.etiqueta.municipio);
        $("#enderecoFrete").val(pedido.transporte.etiqueta.endereco);
        $("#precoTotalFrete").val(parseFloat(pedido.transporte.frete).toFixed(2));
        
        //faz a verificação, para exibir ou não os dados adicionais
        var tipoFreteTemp = $("#tipoDeFrete");
        if($('option:selected',tipoFreteTemp).val() != 'Balcão de entrega'){
               $("#infoEntregaOcultas").show();
        }
        
        //parte que insere a observação de pagamento
        
        $("#observacoes_pagamento").text(String(pedido.observacoes).replaceAll(/\n/g,' '));
        pedido.observacoes = String(pedido.observacoes).replaceAll(/\n/g,' ');
        console.log(pedido.observacoes);
        if($("#observacoes_pagamento").val() == 'undefined' || pedido.observacoes==''){$("#observacoes_pagamento").text("");} //evitando bug do undefined que ficava escrito lá
        
        //parte que adiciona os comprovantes :)
        $.each(pedido.comprovantes,function(key,value){
            $("#janelaComprovante").append("<div style='width:105px;border:1px solid grey;display:inline-block' id='comprovante_"+key+"'><img src='"+pedido.comprovantes[key]+"' height='100px' width='50px'><div id='comp_'><button class='btn btn- btn-sm botaoAddComprovante col-sm-1' style='width:45px' onClick='verComprovante("+key+")'>Ver</button><button class='btn btn- btn-sm botaoAddComprovante col-sm-1' onClick='excluirComprovante("+key+")' style='width:55px'>Excluir</button></div></div>");
        });
    }
</script>







