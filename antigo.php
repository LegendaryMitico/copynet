<?php
//essa parte é quem garante a segurança do sistema, e define o que pode ou não ser visto,
//atenção às alterações aqui, copie e cole em TODAS as páginas
session_start();
ini_set('error_reporting', E_ALL); // mesmo resultado de: error_reporting(E_ALL);
ini_set('display_errors', 1);
    if(isset($_SESSION['usuarioId'])&&isset($_SESSION['usuarioNome'])&&isset($_SESSION['usuarioNivelAcesso'])&&
    isset($_SESSION['usuarioLogin'])){
        //ta autenticado, só deixa continuar brother
    }else{
        header("Location:login.php?logado=semacesso");
        echo('<span>não autenticado</span>');
    }
?>
<?php
include_once("php/connect.php"); //conexão
include_once("funcoes.php"); //todas as funções
//essa parte controla o pedido armazenado no database, e gera pedido novo ('se n tiver')  
if(isset($_GET['numeroPedido'])){
    $pegaIdQuery = "SELECT * FROM `pedidos` WHERE `idPedido`='$_GET[numeroPedido]'";
    $resultado_pegaId = mysqli_query($conn, $pegaIdQuery);
    $FinalPedido = mysqli_fetch_assoc($resultado_pegaId);
    //var_dump($FinalPedido);
}else{
    header("Location:geraNumeroPedido.php");
}
?>

<script>
//passando os dados pro javascript :) obs: somente o vendedor que criou pode alterar o pedido ou usuário com acesso e criando o objeto produtos vazio
var pedido = JSON.parse(<?php echo(json_encode($FinalPedido['pedidoJson'])); ?>);
//pedido.produtos  = {};
console.log(pedido);
</script>

<!-- fim security place -->
<!doctype html>
<html lang="en">
<head>
<link rel="stylesheet" href="css/style.css">
<link rel="icon" href="img/icon.png">
<meta charset="utf-8">
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
<script src="https://kit.fontawesome.com/704200b128.js" crossorigin="anonymous"></script>
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
<!-- chart js -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->


<style type="text/css">
  @font-face {
  font-family: "Helvetica-bold";
  src: url("font/Helvetica-Bold-Font.ttf");
  }

  body{
    background-color: #18191a; /* <!-- #606060 --> */
    font-family: 'Helvetica-bold';

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
<div class="container" style="padding: 3px 0px 3px 0px; width: 820px; margin-top: 100px;">
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
  <div class="rounded" style="background-color: #27272a; width: 100%; padding: 10px;margin-top:-80px">
        <div class="col-sm-12" style="display: inline-block;margin-top:3px;position:absolute">
            <span style="display:inline-block;color:white">Cliente:</span>
            <input id="myInput" type="text" style="width:700px;background-color:#3a3b3c;color:white;border-radius:5px;display:inline-block">
            <script>
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
                   }
                   var idClienteAtual = '';
                   if($("#myInput").val()==''){
                       $("#buscaClienteBtn").css("background-color", "#27272a");
                   }else{
                   $("#buscaClienteBtn").css("background-color", "red");
                   $("#buscaClienteBtn").css("color", "white");
                   }
                });
            </script>
            
            <button type="button" class="btn btn- btn-sm" id="buscaClienteBtn" style="height: 29px;width:32px; line-height: 0.7; border: 1px solid grey;display:inline-block; margin-top:1px;color:green" alt="pdf">
               <i class="fa fa-paper-plane"></i>
            </button>
        </div>
        
        <div>
            <div class="row" style="margin-top:35px">
          <!-- linha 2 -->
            <div class="col-sm-3" style="color: red; margin: 0px 0px 0px 0px; color: white">  
              Entrega: 
            </div>
            <div class="col-sm-2" style="color: red; margin-top: 3px; margin-left: -40px;color:white">  
              Hora:
            </div>
            <div class="col-sm-2" style="color: #606060; margin-top: 3px; margin-left: -45px;color:white">  
              Vendedor:
            </div>
            <div class="col-sm-4" style="color: #606060; margin-top: 3px; margin-left: -7px;color:white">  
              Pasta:
            </div>
            <div class="col-sm-1" style="margin-left:70px;color:white">
                N°:
            </div>
          <!-- linha 3 -->
            <div class="col-sm-3" style="color: red; display: inline-block;color:red">  
            <!-- preenchendo os dias automaticamente  $prazo vem do datab-->
              <input type='date' id='dataEntregaInpt'  style='color: white; width: 145px;background-color:#3a3b3c;line-height:1.85;border:1px solid green;border-radius:5px;font-size:14px';>
              <script>
                $('#dataEntregaInpt').change(function(){
                  pedido.dataPrevista = $(this).val();
                  //console.log(pedido);
                });
              </script>
            
            </div>
            <div class="col-sm-1"> 
               <select id="horaEntregaInput" style="height: 29px; font-size: 14px; color: white; margin-left: -38px;background-color:#3a3b3c;border:1px solid green;border-radius:5px">
                    <option value='08:00'>08:00h</option>
                    <option value='09:00'>09:00h</option>
                    <option value='10:00'>10:00h</option>
                    <option value='11:00'>11:00h</option>
                    <option value='12:00'>12:00h</option>
                    <option value='14:00'>14:00h</option>
                    <option value='15:00'>15:00h</option>
                    <option value='16:00'>16:00h</option>
                    <option value='17:00' selected>17:00h</option>
                    <option value='18:00'>18:00h</option>
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
            
            <div class="col-sm-2" style="color: #606060; font-size: 15px; margin-left: -16px;color:white;margin-top:3px">  
              <?php
                echo($_SESSION['usuarioNome']);
              ?>
            </div>
            <div class="col-sm-5" style="color: red;display: inline-block; margin-left: -8px;">  
              <input type="text" id="pastaDriveInput" style="width:104%;background-color:#3a3b3c;color:white;border-radius:5px;border:1px solid green" placeholder="https://drive/pasta">
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
        <div class="container" style="background-color: #4b4c4e; margin-top: 0px;height: 1px"></div>
        </div> 
        
        
            <!-- tela de aplicações parte mais crítica do sistema :) -->
            <div class="janelaAplicacoes" style="background-color:#3a3b3c;width:350px;position:absolute;border-radius:10px;z-index:9999;text-align:center;border:1px solid green;display:none">
                <div class='container' style="padding:0px;margin:0px">
                    <div class="row" style="padding:0px;margin:0px">
                        <!-- CARACTERÍSTICAS -->
                        <div class="col-sm-12" style="background-color:#2e2f30;border-top-left-radius:10px;border-top-right-radius:10px;color:white;font-family: system-ui;text-align:center;width:100%">#<span  id='atualMoldeNome'></span>-Informações
                        <button type="button" id="closeAppBtn" class="btn btn- btn-sm" onclick="" style="height: 18px;width:20px; line-height: 1; border:1px solid red; display:inline; float:right;color:green;font-size:8px;background-color:#3a3b3c;margin-top:3px" alt="orçamento"><span style="margin-left:-2px;color:red">X</span>
                        </button>
                        </div>
                        <div class="col-sm-12" id="janelaCarac" style="padding:0px"> <!-- JANELA DAS CARACTERÍSTICAS -->
                            
                            
                        </div>
                        <!-- APLICAÇÕES -->
                        <div class="col-sm-12" style="background-color:#2e2f30;color:white;font-family: system-ui;">Aplicações</div>
                        <div class="col-sm-12" id="janelaApp" style="padding:0px"> <!-- JANELA DAS APLICAÇÕES -->
                        
                        </div>
                        
                        <div class="col-sm-12" style="text-align:center">
                        <!--BOTÃO DE ADICIONAR -->
                        <button type="button" id="btnAddAppMolde" class="btn btn- btn-sm" style="height: 28px;width:28px; line-height: 0.9; border:1px solid green;color:white;border-radius:15px;background-color:#3a3b3c;margin-top:5px">
                                +
                        </button>
                        </div>
                    </div>
                </div> 
            </div>

            <!-- tela de resumo do pedido -->
            <div class='col-sm-12' style="font-size:14px;color:white;padding-top:5px">
                <!-- fim dos dados do cliente -->
            <div class="container" style="margin-top:5px;text-align:center">
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
                     overflow: hidden /* add this */
                     
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
                      <thead style="background-color:#2e2f30">
                        <tr>
                          <th scope="col" style="width:200px">#Referência</th>
                          <th scope="col" style="width:200px">Quantidade</th>
                          <th scope="col" style="width:200px">Valor Unit.</th>
                          <th scope="col">Valor Total</th>
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
                            <i class="fa fa-vest"></i>
                            Mudar molde
                            <span id='spanMoldeAtualTitle' value='hehe'></span>
                        </h5>
                      </div>
                      <div class="modal-body"> <!-- CORPO DO SELECT MOLDES -->
                        <div class='row'>
                            <div class="col-sm-6">
                                <select id='listaCategoriasPai' style="height: 29px; font-size: 14px; color: white; background-color:#3a3b3c;border-radius:5px">
                                    <option disabled selected value> -- selecione o molde -- </option>
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
                            </div>
                            <div class="col-sm-6">
                                <input type="text" id='inputAchaMolde' style="width:110%;background-color:#3a3b3c;color:white;border-radius:5px;height:30px;margin-left:-30px;margin-top:-2px;" placeholder="CTMC0123">
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
                                <script>
                                        var pagina = 1;
                                        //lista caso tenha alteração
                                        $('#listaCategoriasPai').change(function(){ 
                                            pagina = 1; //volta a pagina pra 1 quando muda o tipo de produto
                                            console.log($('option:selected',this).val());
                                            console.log('pagina atuu:'+pagina);
                                            $.ajax({
                                                    type: "POST",
                                                    url: 'ajax/pegaProdutosCategoria.php',
                                                    data: jQuery.param({idCatPai: ($('option:selected',this).val()),
                                                                        paginaAtual: pagina,
                                                                        codProd: $('#inputAchaMolde').val()
                                                    }) ,
                                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                                    success: function(data)
                                                    {
                                                        $('#listaMoldesDiv').empty();
                                                        var produtosMolde = JSON.parse(data);
                                                        console.log(produtosMolde);
                                                        $.each(produtosMolde.data, function(key, value){
                                                            
                                                        console.log(value);
                                                            
                                                        $('#listaMoldesDiv').append("<div class='col-sm-4 boxImgMolde' style='border-radius:8px;display:inline-block;margin-top:10px'><div style='height:320px;text-align:center;border-radius:5px;background-color:#3a3b3c'><div>"+value.codigo+"</div><a id='imgMolde' href='#'><img id='moldeImgMini_"+value.id+"' class='mudaMoldeImg' data-toggle='modal' data-target='#modalMolde' src='moldes/"+value.codigo+".jpg' alt='Minha Figura' width='100%' style='background-color:white;margin-top:-5px'></a><span style='font-size:12px;line-height: 1.5;'>"+value.nome+"</span></div></div>");
                                                    });
                                                    }
                                                });
                                        });    
                                </script>
                                <script>
                                    //e por último, caso o usuário informe um código e queira buscar, apertando enter
                                    $('#inputAchaMolde').keypress(function(event){
                                      var keycode = (event.keyCode ? event.keyCode : event.which);
                                      //caso ele pressione enter
                                      if(keycode == '13'){
                                          $.ajax({
                                                    type: "POST",
                                                    url: 'ajax/pegaProdutosCategoria.php',
                                                    data: jQuery.param({idCatPai: ($('option:selected',$('#listaCategoriasPai')).val()),
                                                                        paginaAtual: pagina,
                                                                        codProd: $(this).val()
                                                        
                                                    }) ,
                                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                                    success: function(data)
                                                    {
                                                        $('#listaMoldesDiv').empty();
                                                        var produtosMolde = JSON.parse(data);
                                                        //console.log(produtosMolde);
                                                        $.each(produtosMolde.data, function(key, value){
                                                        $('#listaMoldesDiv').append("<div class='col-sm-4 boxImgMolde' style='border-radius:8px;display:inline-block;margin-top:80px'><div style='height:320px;text-align:center;border-radius:5px;background-color:#3a3b3c'><div>"+value.codigo+"</div><a id='imgMolde' href='#'><img id='moldeImgMini_"+value.id+"' class='mudaMoldeImg' data-toggle='modal' data-target='#modalMolde' src='moldes/"+value.codigo+".jpg' alt='Minha Figura' width='100%' style='background-color:white;margin-top:-5px'></a><span style='font-size:10px;line-height: 1.5;'>"+value.nome+"</span></div></div>");
                                                    });
                                                    }
                                                });
                                      }
                                    });
                                </script>
                            </div>
                        </div>
                      </div>
                      
                      <div class="modal-footer" id="bottomFotterMolde" style="text-align:center;display:block" >
                        <button type="button" class="btn btn- btn-sm boxImgMolde" id="btnAnteriorPag" style="width:90px;margin-top:3px; color:white;line-height:2;margin-right:5px;background-color:#27272a;display:none">
                            <i class="fa fa-backward"></i> 
                        </button>
                        <button type="button" class="btn btn- btn-sm boxImgMolde" id="btnProximaPag" style="width:90px;margin-top:3px;color:white;background-color:#27272a;line-height:2;display:inline-block">
                            <i class="fa fa-forward"></i>  
                        </button>
                        
                        <script>
                            $(document).on('click','#btnProximaPag',function(){
                                pagina++;
                                //altera a listagem com base na página passada como parâmetro
                                $.ajax({
                                                    type: "POST",
                                                    url: 'ajax/pegaProdutosCategoria.php',
                                                    data: jQuery.param({idCatPai: ($('option:selected',this).val()),
                                                                        paginaAtual: pagina,
                                                                        codProd: $('#inputAchaMolde').val()
                                                    }) ,
                                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                                    success: function(data)
                                                    {
                                                        $('#listaMoldesDiv').empty();
                                                        var produtosMolde = JSON.parse(data);
                                                        //console.log(produtosMolde);
                                                        $.each(produtosMolde.data, function(key, value){
                                                        $('#listaMoldesDiv').append("<div class='col-sm-4 boxImgMolde' style='border-radius:8px;display:inline-block;margin-top:80px'><div style='height:320px;text-align:center;border-radius:5px;background-color:#3a3b3c'><div>"+value.codigo+"</div><a id='imgMolde' href='#'><img id='moldeImgMini_"+value.id+"' class='mudaMoldeImg' data-toggle='modal' data-target='#modalMolde' src='moldes/"+value.codigo+".jpg' alt='Minha Figura' width='100%' style='background-color:white;margin-top:-5px'></a><span style='font-size:8px;line-height: 0.5;'>"+value.nome+"</span></div></div>");
                                                    });
                                                    }
                                });
                                
                                
                                if(pagina>1){
                                    $('#btnAnteriorPag').css("display", "inline-block");
                                }
                            });
                            $(document).on('click','#btnAnteriorPag',function(){
                                pagina--;
                                $.ajax({
                                                    type: "POST",
                                                    url: 'ajax/pegaProdutosCategoria.php',
                                                    data: jQuery.param({idCatPai: ($('option:selected',this).val()),
                                                                        paginaAtual: pagina,
                                                                        codProd: $('#inputAchaMolde').val()
                                                    }) ,
                                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                                    success: function(data)
                                                    {
                                                        $('#listaMoldesDiv').empty();
                                                        var produtosMolde = JSON.parse(data);
                                                        //console.log(produtosMolde);
                                                        $.each(produtosMolde.data, function(key, value){
                                                        $('#listaMoldesDiv').append("<div class='col-sm-4 boxImgMolde' style='border-radius:8px;display:inline-block;margin-top:80px'><div style='height:320px;text-align:center;border-radius:5px;background-color:#3a3b3c'><div>"+value.codigo+"</div><a id='imgMolde' href='#'><img id='moldeImg_' class='mudaMoldeImg' data-toggle='modal' data-target='#modalMolde' src='moldes/"+value.codigo+".jpg' alt='Minha Figura' width='100%' style='background-color:white;margin-top:-5px'></a><span style='font-size:10px;line-height: 1.5;'>"+value.nome+"</span></div></div>");
                                                    });
                                                    }
                                });
                                if(pagina==1){ //pra sumir o botão de página anterior caso esteja na pág 1
                                    $('#btnAnteriorPag').css("display", "none");
                                }
                            });
                        </script>
                      
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
                        <td scope="row" style="padding:0px;height:20px;width:228px;text-align:center;border:none;">
                        </td>
                        <td scope="row" style="padding:0px;height:20px;width:210px;text-align:center;border:none;">
                            
                        </td>
                        <td scope="row" style="padding:0px;height:20px;width:250px">
                            <span style="width:100%;background-color:#3a3b3c;color:white;height:30px;text-align:center;line-height:2">TOTAL</span>
                        </td>
                        <td scope="row" style="padding:0px;height:20px;width:232px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e">
                            <span id="valorFinal" style="width:100%;color:green;height:30px;line-height:2">R$ 0.00</span>
                        </td>
                        </tr>
                    </thead>
                </table>
                
                    <div class="container" style="background-color: #4b4c4e; margin-top: 5px;height: 1px"></div>
                    <button type="button" class="btn btn- btn-sm" onclick="geraRelatorio();" style="height: 32px;width:43px; line-height: 0.7; border:1px solid green; display:inline-block; float:left; margin-top:3px;color:green" alt="pdf">
                    <i class="fa regular fa-print fa-lg"></i>
                    </button>
                    <button type="button" class="btn btn- btn-sm" onclick="geraOrcamento()" style="height: 32px;width:43px; line-height: 0.7; border:1px solid green; display:inline-block; float:left; margin-top:3px;color:green;margin-left:5px" alt="orçamento">
                    <i class="fa fa-file"></i>
                    </button>
                    
                    <button type="button" id='exibeVenda' class="btn btn- btn-sm" onclick="geraVenda();" style="width:90px;margin-top:3px; float:right; border:1px solid green; color:white;background-color:green;line-height:2">
                    Fabricar
                    <i class="fa fa-play"></i>    
                    </button>
                    <script>
                        $('#exibeVenda').on('click',function(e){
                            console.log(pedido);
                        });
                        
                    </script>
                    <button type="button"  id="btnSalvar" class="btn btn- btn-sm" onclick="window.location='#';" style="width:90px;margin-top:3px; float:right; border:1px solid green; color:green;line-height:2;margin-right:5px">
                    Salvar
                    <i class="fa fa-floppy"></i>    
                    </button>

                    <br/><br/>
        </div
        </div>
      </div> 
      </div>
      
<!-- footerzin -->
<!-- fim gradient -->
<br><br><br><br><br><br>
 <footer id="sticky-footer" class="flex-shrink-0 py-4 bg-dark text-white-50" style="position: fixed;
Width: 100%;
bottom: -50px;">
     <div style="height: 3px; background-image: linear-gradient(to right, #FF0009, #EB1E13, #C41910, #FF0009); width: 100%;margin-top:-25px"></div>
    <div class="container text-center" style="height:50px;padding:50px">
      <span style="">Copyright &copy; Personal confecções</span>
    </div>
  </footer>
</body>
</html>
<!-- --------------------------------------------------------------------------------------------------------- -->
<!-- ---------------------------------------------------------------------------------------------------------- -->
<script>
    //parte que trata dos 3 botões inferiores, para gerar venda, relatório e orçamento
    function geraRelatorio(idPedido){
        window.open('geraRelatorio.php?numeroPedido='+pedido.id, '_blank');
    }
                    
    function geraOrcamento(idPedido){
        window.open('geraOrcamento.php?numeroPedido='+pedido.id, '_blank');
    }
    //redirecionando para gerar a venda
    function geraVenda(idPedido){
         window.location='trataPedido.php?numeroPedido='+pedido.id;
         //caba aqui :)
    }
</script>

<script>
//variáveis globais de controle;
var idMoldeAtual = "";

//variável global de aplicações da sessão
var controleAppId = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,];
<!-------------------------------------------------------------------------------------------------------------!>
//add id molde (botoezinhos superiores) ///////////////////////////////////
$(document).ready(function(){
      var add_button = $("#add-produto"); //Add button ID
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
          $(wrapper2).append("<div id='janelaAtual_"+x+"' class='col-sm-4' style='float:left;height:340px; width:245px;border-radius:8px;display:inline-block;vertical-align:middle;margin-right:-4px;margin-bottom:20px'><div class='row'><div class='container boxImgMolde'><div clss='col-sm-12' style='background-color:#2e2f30;border-radius: 10px 10px 0px 0px; padding-top:2px'><span style='margin-left:25px'>#"+pedido.id+"-"+x+"</span><button type='button' id='"+x+"' class='btn btn- btn-sm remove_field' onclick='' style='height: 18px;width:20px; line-height: 1.25; border:1px solid red; display:inline; float:right;color:green;margin-right:5px;font-size:10px;background-color:#3a3b3c' alt='orçamento'><span style='margin-left:-2px;color:red'>X</span></button></div><a id='imgMolde' href='#'><img id='moldeImg_"+x+"' class='mudaMoldeImg' data-toggle='modal' data-target='#modalMolde' src='img/sistem/dinu.png' alt='Minha Figura' width='100%' style='background-color:white'></a><div col-sm-12 style='margin-top:3px'><button type='button' id='appMoldeBtn' class='btn btn- btn-sm' onclick='' style='height: 18px;width:20px; line-height: 0.7; border:1px solid green; display:inline; float:right;color:green;margin-left:-3px' alt='orçamento'><i id='appBtn_"+x+"' class='fa fa-bookmark fa-sm appMoldeBtn' style='margin-left:-3.5px;margin-top:-3px'></i></button><button type='button' class='btn btn- btn-sm' onclick='' style='height: 18px;width:20px; line-height: 0.7; border:1px solid green; display:inline; float:left;color:green;margin-left:3px;margin-right:5px' alt='orçamento'><a id='downBtn_"+x+"' href='#' target='_blank'><i id='downBtn_"+x+"' class='fa fa-download fa-2xs btnDownloadClass' style='margin-left:-5.5px;margin-top:-3px'></i></a></button><button style='display:block;width:20px; height:18px;background-color:#2e2f30;border:1px solid green;color:green;'><i id='upBtn_"+x+"' class='fa fa-upload fa-2xs btnUpClass' style='margin-left:-5.5px;margin-top:-3px;color:green;' onclick="+'"'+"document.getElementById("+"'"+"files_"+x+"'"+").click()"+'"'+"></i></button><input type='file' id='files_"+x+"' style='display:none' multiple><span id='spanNomeMoldeAtual_"+x+"' style='margin-left:-20px'>-</span></div><div id='tamanhos_"+x+"' style='width:220px;height:60px;background-color:#2e2f30; border-radius: 0px 0px 10px 10px'></div></div></div></div>");//adiciona o botãozinho na parte superior
          
        }
        //e após inserir os elementos gráficos, cria a posição vazia em produtos
        //console.log(produtos);
        var temp = {"molde":{"codigo":"-","descricaoCurta": {"imagem":"https://img.elo7.com.br/product/zoom/30C3AE4/molde-camiseta-tradicional-gola-v-pp-ao-egg-pdf.jpg","moldeLink": "https://drive.google.com/open?id=14rRzdCtKGukTKw5uRPvXKH90M6zW2L02&usp=drive_fs","abate":{"PP":0.85,"P":0.86,"M":0.89,"G":0.92,"GG":0.95,"EXG":0.99}},"formato":"V","id":123456,"imagem":"img/sistem/dinu.png","nome":".","preco": 39.90,"situacao":"A","tipo":"P"},"aplicacoes":[],"quantidades":[]};
        pedido.produtos['item_'+x] = temp;
        //console.log(pedido);
        //e aqui novamente, só fechando a janela de aplicações pra n ficar por cima
        $(".janelaAplicacoes").hide();
      });
      //FUNÇÕES DE COMPORTAMENTO DE TODOS OS BOTÕES
      
      //remove o molde pelo botão X
     
    $(wrapper2).on("click", ".remove_field", function(e){ 
        e.preventDefault();
        $(this).parent('div').parent('div').parent('div').parent('div').remove();
        var moldeA = $(this).attr('id');
        //remove todos as linhas pré inseridas ------------------------------------------------------
        $.each(pedido.produtos['item_'+moldeA]['quantidades'],function(key,value){
            $('#lin'+moldeA+'_'+value.codigo).remove(); //reovendo aqui
        });
        //removendo do produtos array (o de controle pra abate)
        delete(pedido.produtos['item_'+moldeA]);
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


    //muda aplicação do molde pelo botão
    $(wrapper2).on("click", ".appMoldeBtn", function(e){ 
        idMoldeAtual = e.target.id.split('_')[1]; //pega o id do molde atual pelo id do botaozinho de aplicação (IMPORTANTE)
        console.log(idMoldeAtual);
        $("#janelaApp").empty(); //esvazia todas as aplicações *para receber as novas dinamicamente
        $("#janelaCarac").empty(); //esvazia as característcas *para receber as novas do json
        //---------------------------------------------------------------------------------------------------
        //aqui pega os dados de composição do molde atual
        var composicao = pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao;
        //console.log(composicao);
        var selecaoAdd = '';
        $.each(pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao,function(key,value){
                var selecaoAdd = "<div class='col-sm-12' style='float:left'><select id='car"+idMoldeAtual+'_'+key+"' style='width:100%;height: 29px; font-size: 12px; color: black;background-color:#3a3b3c;border-radius:5px;color:white'><option disabled selected>"+key+"</option>;"
                $.each(pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.composicao[key],function(key,value){
                    //pq no 0 ficam as quantidades em todas as características
                    if(key!=0){ 
        	         selecaoAdd+= "<option value='"+value['codigo']+"'>"+value['nome']+"</option>";
                    }
        	    });
                selecaoAdd+="</select></div>";
                
                selecaoAdd+= "<div class='col-sm-12' style='display: inline-block;width: 100%;font-size:8px'><div class='dropdown'><div class='btn btn-secondary dropdown-toggle show' id='btnDro"+idMoldeAtual+"_"+key+"'data-bs-toggle='dropdown'aria-expanded='true'style='width: 100%;height: 30px;line-height: 1.75;background-color: #3a3b3c;padding:0px'><span>seleciona</span></div><ul class='dropdown-menu show' id='ul_"+idMoldeAtual+"_"+key+"' aria-labelledby='dro"+idMoldeAtual+"_"+key+"' data-popper-placement='bottom-end' style='color:white;background-color:#2e2f30'>";
                //aqui para colocar a que já está selecionada!!!
                selecaoAdd += "<a class='dropdown-item' href='#' id='teste ae' value='toasty'><div class='row' style='pointer-events: none;font-family: monospace;'><div style='display:inline-flex;color: white;'><div class='col-sm-4' style='pointer-events: none;'></div></div></div></a>";
                selecaoAdd += "</ul></div></div>";

                $("#janelaCarac").append(selecaoAdd);
        });
        
        $.each(pedido.produtos['item_'+idMoldeAtual].aplicacoes,function(key,value){
            $("#janelaApp").append("<div style='margin-top:-3px'><select id='"+value.idGeral+"' style='height: 29px; font-size: 12px; color: white;background-color:#3a3b3c;border:1px solid green;border-radius:5px;text-align:center;width:91%'><option value='A3ID'>"+value.nome+"</option></select><button type='button' class='btn btn- btn-sm deleteApp' id='"+value.idGeral+"' style='height:30px;width:28px; line-height: 0.9; border:1px solid green;color:white;background-color:#3a3b3c;margin-top:1px'>x</button></div>");
        });
        //aqui o que já tinha 
        $(".janelaAplicacoes").show();
        var texto = $(e.target).attr('id');
        //var classe = $(e.target).attr('class');
        console.log(texto);
        var pos = $(e.target).offset();
        pos.left = pos.left+15;
        pos.top = pos.top-260;
        $(".janelaAplicacoes").offset(pos);
        console.log(e.target.id);
        $("#atualMoldeNome").text(idMoldeAtual); //AQUI MUDA O TEXTINHO QUE APARECE NA CAIXA DE APLICAÇÕES
    });
    //quando muda o select da característica pai
    $("#janelaCarac").on("change",function(e){ 
        texto = ($(e.target).attr('id'));
        console.log(texto);
        console.log($(e.target).find(":selected").val());
        $.ajax({
                type: "POST",
                url: 'ajax/puxaTecidosFilhos.php',
                data: jQuery.param({idCatPai: ($(e.target).find(":selected").val()),
                        }) ,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function(data)
                        {
                        var opcoes = JSON.parse(data); 
                        var insereOpcoesHtml = '';
                        console.log(opcoes);
                        
                        $.each(opcoes.data.variacoes,function(key,value){
                            insereOpcoesHtml+= "<a class='dropdown-item' href='#' name='"+value.id+","+value.codigo+","+value.nome+"'><div class='row' style='pointer-events: none;font-family: monospace;'><div style='display:inline-flex;color: white;'><div class='col-sm-2' style='pointer-events: none;'><img src='img/ate/"+12+".png' width='100%' style='pointer-events: none;'></div><div class='col-sm-10' style='font-size:11px;width:330px;white-space: normal;line-height:1'>"+value.nome+"<br>Código:"+value.codigo+"<br>Id:"+value.id+"</div></div></div></a>";
                	    });
                	    $('#ul_10_tecido-malha').empty();
                        $('#ul_10_tecido-malha').append(insereOpcoesHtml);
                        }
        });
        
    });
      
    //e por último, quando clica no filho gerado em cima, muda o botão com os dados do filho selecionado
    $("#janelaCarac").on("click",".dropdown-item",function(e){
        console.log(e.target.name);
        $("#btnDro10_tecido-malha").css('height', '60px');
        $("#btnDro10_tecido-malha").css('text-align', 'left');
        $("#btnDro10_tecido-malha").empty();
        $("#btnDro10_tecido-malha").append("<img style='width: 60px;height: 60px;line-height: 1.75;background-color: #3a3b3c;padding:0px;float: left;' src='img/ate/"+12+".png' width='100%' style='pointer-events: none;'>");
        $("#btnDro10_tecido-malha").append("<span style='line-height:1.45;font-size:9px;'>Nome:"+e.target.name.split(',')[2]+"<br><span style='font-size:11px'>Código:123456<br>Estoque: 24 Unid.<br>Usado na venda: 25</span></span>");
        
    });
      
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
                                        //antes de inserir os dados novos vindo do molde novo, é crucial verificar se já tem dados antes, por isso aqui
                                        //exclui principalmente as tabelas geradas e limpa o json 
                                        
                                        //e.preventDefault(); resquícios do codigo original !!!
                                        //var moldeA = $(this).attr('id');
                                        //remove todos as linhas pré inseridas ------------------------------------------------------
                                        $.each(pedido.produtos['item_'+idMoldeAtual]['quantidades'],function(key,value){
                                            $('#lin'+idMoldeAtual+'_'+value.codigo).remove(); //reovendo aqui
                                        });
                                        //removendo do produtos array (o de controle pra abate)
                                        //delete(pedido.produtos['item_'+moldeA]);
                                        
                                        
                                        //AQUI ELE PUXA OS DADOS DO MOLDE COM TODAS AS CATEGORIAS, ATENÇÃO SUPREMA A ESSE RETORNO
                                        var dadosMoldeNovo = JSON.parse(data);
                                        console.log(dadosMoldeNovo);
                                        //pegando a descrição curta vinda do bling (EXTREMAMENTE IMPORTANTE PARCEIRO!!!!!!) E INSERINDO NO PEDIDo
                                        pedido.produtos['item_'+idMoldeAtual].molde.codigo = dadosMoldeNovo.data.codigo; //update codigo
                                        
                                        //atualizando a descrição curta obs:essa parte só existe pq a descr curta vem com <p> e </p>
                                            var html = dadosMoldeNovo.data.descricaoCurta;
                                            var div = document.createElement("div");
                                            div.style.display = 'none'; //só pra n aparecer na tela
                                            div.innerHTML = html;
                                            var descricaoCurta = div.textContent || div.innerText || "";
                                        pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta = JSON.parse(descricaoCurta); //update descricao curta
                                        //pedido.produtos['item_'+idMoldeAtual].molde.imagem = 
                                        
                                        //aqui atualizando a imagem no json
                                        var imageNova = 'moldes/'+dadosMoldeNovo.data.codigo+'.jpg'; 
                                        pedido.produtos['item_'+idMoldeAtual].molde.imagem = imageNova;//update imagem
                                        
                                        
                                        pedido.produtos['item_'+idMoldeAtual].molde.preco = dadosMoldeNovo.data.preco;//e aqui o preço do molde atual
                                        pedido.produtos['item_'+idMoldeAtual].molde.id = dadosMoldeNovo.data.id; //e aqui o id novo
                                         
                                        console.log(pedido);
                                        //E AGORA INSERINDO AS VARIAÇÕES (ID,NOME E CÓDIGO) *E JÁ APROVEITANDO O LOOP PARA CRIAR OS INPUTS DE TAMANHO
                                        var tamanhos = "";
                                        pedido.produtos['item_'+idMoldeAtual].quantidades = [];
                                        $.each(dadosMoldeNovo.data.variacoes, function(key, value){
                                            var nomeTamanho = (dadosMoldeNovo.data.variacoes[key].nome).split(':');
                                            pedido.produtos['item_'+idMoldeAtual].quantidades.push({"codigo":dadosMoldeNovo.data.variacoes[key].codigo,"id":dadosMoldeNovo.data.variacoes[key].id,"quantidade":0,"nome":nomeTamanho[1],"preco":dadosMoldeNovo.data.preco});
                                            tamanhos += "<th scope='col' style='width:200px;padding:0px;'>"+nomeTamanho[1]+"<input id='"+idMoldeAtual+"_"+dadosMoldeNovo.data.variacoes[key].codigo+"' type='number' style='width:100%;height:35px;text-align:center' value='0'></th>";
                                            
                                        });
                                        //E POR ÚLTIMO, mudando os elementos gráficos
                                        
                                        
                                        $("#moldeImg_"+idMoldeAtual).attr('src',pedido.produtos['item_'+idMoldeAtual].molde.imagem); //muda a imagem
                                        $("#downBtn_"+idMoldeAtual).attr('href',pedido.produtos['item_'+idMoldeAtual].molde.descricaoCurta.moldeLink); //muda o link de download
                                        $("#spanNomeMoldeAtual_"+idMoldeAtual).text(pedido.produtos['item_'+idMoldeAtual].molde.codigo); //muda o texto do molde
                                        //aqui gerando os tamanhos com input
                                        $("#tamanhos_"+idMoldeAtual).empty();
                                        $("#tamanhos_"+idMoldeAtual).append("<table class='table table-bordered text-center' style='border:1px solid #4b4c4e;margin-bottom:0px;height:10px'><thead style='background-color:#2e2f30;height:15px;text-align:center;color:white;font-size:14px'><tr>"+tamanhos+"</tr></thead></table>");
               }
        });
    });

    $("#closeAppBtn").on("click",function(){
        $(".janelaAplicacoes").hide();
        console.log('clicked');
    });
    
    function atualizaView(idMolde){
        
    }
    
    //PARTE QUE INSERE APLICAÇÕES NO PEDIDO ------------------------------------------------------------------
    $("#btnAddAppMolde").click(function(e){
        //aqui só pra evitar o bug do .push undefined em array
        if (pedido.produtos['item_'+idMoldeAtual]['aplicacoes'] === undefined){
            pedido.produtos['item_'+idMoldeAtual].aplicacoes = [];
        }
        controleAppId[idMoldeAtual]++;
        //pegando todas as aplicações do bling
        $.ajax({
                type: "POST",
                url: 'ajax/pegaAplicacoes.php',
                data: jQuery.param({}), //o codigo das aplicações já ta tá em peaplicacoes.php
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        success: function(data)
                        {
                            var aplicacoes = JSON.parse(data); 
                            console.log(aplicacoes);
                            var exibeTelaApp = '';
                            $.each(aplicacoes.data,function(key,value){
                                exibeTelaApp += "<option value='"+value.id+","+value.codigo+","+value.preco+"'>"+value.nome+"</option>";
                            });
                             $("#janelaApp").append("<div style='margin-top:-3px'><select id='App"+idMoldeAtual+"_"+controleAppId[idMoldeAtual]+"' style='height: 29px; font-size: 12px; color: white;background-color:#3a3b3c;border:1px solid green;border-radius:5px;text-align:center;width:91%'>"+exibeTelaApp+"</select><button type='button' class='btn btn- btn-sm deleteApp' id='App"+idMoldeAtual+"_"+controleAppId[idMoldeAtual]+"' style='height:30px;width:28px; line-height: 0.9; border:1px solid green;color:white;background-color:#3a3b3c;margin-top:1px'>x</button></div>");
                             

                            //console.log(idMoldeAtual+'//'+controleAppId[idMoldeAtual]);
                            //e aqui, criando a aplicação (por enquanto vazia) mo objeto de venda se estiver vazio cria a pos
                            pedido.produtos['item_'+idMoldeAtual].aplicacoes.push({'idGeral':'App'+idMoldeAtual+'_'+controleAppId[idMoldeAtual],'idApp':aplicacoes.data[0].id,'nome':aplicacoes.data[0].nome,'valor':aplicacoes.data[0].preco,'quantidade':qtdApp(idMoldeAtual),'codigo':aplicacoes.data[0].codigo});
                            //e aqui gerando o elemento gráfico na tabela inferior
                            var wrapper = $("#insereMolde"); //janela de inserir os botões
                            $(wrapper).append("<tr id='App"+idMoldeAtual+"_"+controleAppId[idMoldeAtual]+"' style='height:20px;' id='' title='"+aplicacoes.data[0].nome+"'><td scope='row' style='padding:0px;height:20px;width:249px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>#"+aplicacoes.data[0].codigo+"</span></td><td scope='row' style='padding:0px;height:20px;width:240px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span style='width:100%;color:white;height:30px;'>"+qtdApp(idMoldeAtual)+"</span></td><td scope='row' style='padding:0px;height:20px;width:250px'><input id='App"+idMoldeAtual+"_"+controleAppId[idMoldeAtual]+"' type='number' value='"+aplicacoes.data[0].preco+"' style='width:100%;background-color:#3a3b3c;color:white;border:1px solid #4b4c4e;height:30px;text-align:center'></td><td scope='row' style='padding:0px;height:20px;width:240px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span id='tot"+idMoldeAtual+"_"+controleAppId[idMoldeAtual]+"'style='width:100%;color:white;height:30px;'>"+(qtdApp(idMoldeAtual)*aplicacoes.data[0].preco).toFixed(2)+"</span></td></tr><div>");
                    }
        });
        
    });
    
    
   function qtdApp(idMolde){ //retorna a quantidade total de produtos do molde atual 
        var total = 0;
        $.each(pedido.produtos['item_'+idMolde].quantidades,function(key,value){
    	   total += parseInt(value.quantidade);
        }); 
        return(total);
    }  
    
    function alteraQtdApp(idMolde){
        $.each(pedido.produtos['item_'+idMolde].aplicacoes,function(key,value){
            pedido.produtos['item_'+idMolde].aplicacoes.quantidade = qtdApp(idMolde);
        });
        return(pedido);
    }
    
    function updateQtdApp(idMolde){//aqui ele controla todas as aplicações
         //segundo: percorre todas as aplicações correspondentes Àquele molde já inseridas e as remove
         $.map($("#insereMolde > tr"), function (item) {
            if($(item).attr('id').slice(0, 5) == 'App'+idMolde){
                $(item).remove();
            }
         })
         //e aqui, as reinsere com o novo valor vindo do json com os valores atualizados :) (tudo isso para evitar erros!)
         var wrapper = $("#insereMolde"); //janela de inserir a tabela inferior
         $.each(pedido.produtos['item_'+idMolde]['aplicacoes'],function(key,value){
    	    //console.log(value);
    	    $(wrapper).append("<tr id='"+value.idGeral+"' style='height:20px;' id='' title='"+value.nome+"'><td scope='row' style='padding:0px;height:20px;width:249px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>#"+value.codigo+"</span></td><td scope='row' style='padding:0px;height:20px;width:240px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span style='width:100%;color:white;height:30px;'>"+qtdApp(idMolde)+"</span></td><td scope='row' style='padding:0px;height:20px;width:250px'><input id='"+value.idGeral+"' type='number' value='"+value.valor+"' style='width:100%;background-color:#3a3b3c;color:white;border:1px solid #4b4c4e;height:30px;text-align:center'></td><td scope='row' style='padding:0px;height:20px;width:240px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span id='tot' style='width:100%;color:white;height:30px;'>"+(qtdApp(idMolde)*value.valor).toFixed(2)+"</span></td></tr><div>");
    	    });
    }
    //insere aplicação emcima e remove aqui embaixo
    $("#janelaApp").on("click",'.deleteApp', function(e){ 
        //aqui removendo do pedido
        $.each(pedido.produtos['item_'+idMoldeAtual]['aplicacoes'],function(key,value){
    	    if(pedido.produtos['item_'+idMoldeAtual]['aplicacoes'][key]['idGeral'] == $(e.target).attr('id')){
    	        pedido.produtos['item_'+idMoldeAtual].aplicacoes.splice(key,1); //removendo
    	    }
        });
        console.log(pedido.produtos['item_'+idMoldeAtual].aplicacoes.length);
        $(e.target).parent('div').remove(); //elemento gráfico da janela molde 
        $("#"+e.target.id).remove();//elemento gráfico da tabela embaixo
    }); 
    
    //escuta quando a aplicação é alterada - PARTE IMPORTANTE e atualiza o pedido (aqui é em partes)
    $('#janelaApp').on('change', function(e){
        console.log('taki pae');
        console.log($(e.target).find(":selected").val()); //pegando o novo valor do select da aplicação
        var dadosApp = $(e.target).find(":selected").val().split(',');
        console.log(dadosApp);
        
        //console.log($(e.target).find(":selected").attr('name'));
        var idMoldeApp = $(e.target).attr('id').slice(3);
        var moldeA = idMoldeApp.split('_')[0]; //pegando o molde do id do select
        var contApp = moldeA.split('_')[1]; // pegando o cont da applicação do select
        $.each(pedido.produtos['item_'+moldeA].aplicacoes,function(key,value){
    	    if(pedido.produtos['item_'+moldeA].aplicacoes[key].idGeral==$(e.target).attr('id')){
    	        pedido.produtos['item_'+moldeA].aplicacoes[key].idApp = parseInt(dadosApp[0]); //id da aplicação
    	        pedido.produtos['item_'+moldeA].aplicacoes[key].nome = $(e.target).find(":selected").text(); // nome da aplicação
    	        pedido.produtos['item_'+moldeA].aplicacoes[key].valor = parseFloat(dadosApp[2]);
    	        pedido.produtos['item_'+moldeA].aplicacoes[key].quantidade = qtdApp(moldeA);
    	        pedido.produtos['item_'+moldeA].aplicacoes[key].codigo = dadosApp[1];
    	    }
        });
        //console.log(pedido);
        //pedido.produtos['item_'+].aplicaccoes
        //e por último deleta o elemento gráfico inferior (a linha da tabela) e insere a nova com os dados do objeto pedido
        var ids = $.map($("#insereMolde > tr"), function (item) {
            if($(item).attr('id') == $(e.target).attr('id')){
                $(item).remove();
            }
        })
        //e aqui inserindo o novo
        var wrapper = $("#insereMolde"); //janela de inserir os botões
                            $(wrapper).append("<tr id='"+$(e.target).attr('id')+"' style='height:20px;' id=''><td scope='row' style='padding:0px;height:20px;width:249px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'title='"+$(e.target).find(":selected").text()+"'><span>#"+dadosApp[1]+"</span></td><td scope='row' style='padding:0px;height:20px;width:240px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span style='width:100%;color:white;height:30px;'>"+qtdApp(idMoldeAtual)+"</span></td><td scope='row' style='padding:0px;height:20px;width:250px'><input id='"+$(e.target).attr('id')+"' type='number' value='"+parseFloat(dadosApp[2])+"' style='width:100%;background-color:#3a3b3c;color:white;border:1px solid #4b4c4e;height:30px;text-align:center'></td><td scope='row' style='padding:0px;height:20px;width:240px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span id=''style='width:100%;color:white;height:30px;'>"+(qtdApp(idMoldeAtual)*parseFloat(dadosApp[2])).toFixed(2)+"</span></td></tr><div>");
        });
    
    
    //e após selecionar o molde altere a imagem pela que veio do bling
    $('#horaEntregaInpu').change(function() {
        console.log($(this).val());
    });

    //e aqui, altera  cada quantidade pelo valor digitado pelo usuário e gera as tabelas de preços
    $("#janela-moldes").on('input',function(e){ 
        console.log('alterou em cima');
        var name = e.target.id;
        var moldeA = e.target.id.split("_")[0];
        var tam = e.target.id.split("_")[1];
        var precoUnit;
        if(moldeA != 'files'){ //PARA CASO NÃO SEJA O INPUT DA NOVA IMAGEM DO MOLDE
        //inserindo no json a nova quantidade (nos moldes)
    	$.each(pedido.produtos['item_'+moldeA]['quantidades'],function(key,value){
    	    if(pedido.produtos['item_'+moldeA]['quantidades'][key].codigo==tam){
    	        console.log('alterou quantidade do tamanho');
    	        pedido.produtos['item_'+moldeA]['quantidades'][key].quantidade = parseInt(e.target.value);
    	        precoUnit = pedido.produtos['item_'+moldeA]['quantidades'][key].preco;
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
                $(wrapper).append("<tr style='height:20px;' id='lin"+moldeA+"_"+tam+"'><td scope='row' style='padding:0px;height:20px;width:210px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+tam+"</span></td><td scope='row' style='padding:0px;height:20px;width:210px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span id='inp"+moldeA+'_'+tam+"' style='width:100%;background-color:#3a3b3c;color:white;height:30px;text-align:center'>"+e.target.value+"</span></td><td scope='row' style='padding:0px;height:20px;width:250px'><input id='pre"+moldeA+'_'+tam+"' type='number' value='"+precoUnit+"' style='width:100%;background-color:#3a3b3c;color:white;border:1px solid #4b4c4e;height:30px;text-align:center'></td><td scope='row' style='padding:0px;height:20px;width:238px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span id='tot"+moldeA+'_'+tam+"' style='width:100%;color:white;height:30px;'>"+(e.target.value*precoUnit).toFixed(2)+"</span></td></tr><div>");
            }else{
                console.log('entrou no primeiro elzin');
                console.log('idzin:'+'#inp'+moldeA+"_"+tam);
                $('#inp'+moldeA+"_"+tam).text(e.target.value); //e aqui inserindo o valor de entrada no input das quantidades
                $('#tot'+moldeA+'_'+tam).text((e.target.value*precoUnit).toFixed(2));
                // $('#total'+moldeA+'_'+tam).text((e.target.value*precoUnit).toFixed(2));  <- que tava antes
            }
        }
        //inserindo em todas as aplicações daquele molde a nova quantidade
        //primeiro: atualiza no json o total de aplicações novas
         console.log('alterou para a nova quantidade');
         alteraQtdApp(moldeA);
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
            var quantidade;
            $.each(pedido.produtos['item_'+moldeA]['quantidades'],function(key,value){
        	    if(pedido.produtos['item_'+moldeA]['quantidades'][key].codigo==tam){
        	        pedido.produtos['item_'+moldeA]['quantidades'][key].preco = parseFloat(e.target.value);
        	        quantidade = parseInt(pedido.produtos['item_'+moldeA]['quantidades'][key].quantidade);
        	    }
             });
            $('#tot'+moldeA+'_'+tam).text((e.target.value*quantidade).toFixed(2)); //atualizando o preço final do tamanho 
        }else if(name.slice(0,3)=='App'){
           console.log('é uma aplicacion');
           $.each(pedido.produtos['item_'+moldeA]['aplicacoes'],function(key,value){
            	    if(pedido.produtos['item_'+moldeA]['aplicacoes'][key].idGeral==e.target.id){
            	       pedido.produtos['item_'+moldeA]['aplicacoes'][key].valor = parseFloat(e.target.value); // pronto, app
            	    }
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
                 $('#total'+moldeA+'_'+tam).text((e.target.value*precoUnit).toFixed(2)); //atualizando o preço final do tamanho
                 $('#'+moldeA+'_'+tam).val(e.target.value); // e aqui inserindo a nova quantidade no molde em cima
        }
        //inserindo em todas as aplicações daquele molde a nova quantidade (porque alterou)
        updateQtdApp(moldeA);
        console.log(pedido);
        
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
                console.log(value);
                if(value.quantidade != 0){ //passando os dados do produto para itens
                    totalApp += parseInt(value.quantidade) * parseFloat(value.valor);
                }
            })
        }); 
        console.log('total app:'+totalApp);
        $('#valorFinal').text(totalPedido.toFixed(2));
    }); 
    
    //ultimo botão (pega o objeto editado e atualizado no database)
    $("#btnSalvar").click(function(e){
        console.log(pedido);
        $.ajax({
            type: "POST",
            url: 'ajax/salvaPedido.php',
            data: jQuery.param({idPedido: pedido.id,
                                pedidoJson: pedido
            }) ,
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function(data)
            {
                alert("pedido salvo com sucesso!");
        }
      });
    });
});   

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
            var NomeClienteAtual = ($("#myInput").val().split('-'))[0];
            var idClienteAtual = ($("#myInput").val().split('-'))[1];
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
                                        var dadosCliente = data.split(',');
                                        console.log(dadosCliente);
                                        alert("VERIFIQUE SE OS DADOS ESTÃO CORRETOS: <br>"+data);
                                        pedido.contato.id = dadosCliente[16];
                                        pedido.contato.nome = NomeClienteAtual;
                                        pedido.contato.numeroDocumento = dadosCliente[3];
                                        pedido.contato.tipoPessoa = dadosCliente[2];
                                        console.log(pedido);
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
    $("#myInput").val(pedido.contato.nome);
    $("#pastaDriveInput").val(pedido.observacoesInternas.pastaDrive);
    $("#spanNumeroPedido").text(pedido.id);
    
     //pegando a data atual e inserindo no json
                                        var today = new Date();
                                        var dd = String(today.getDate()).padStart(2, '0');
                                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                                        var yyyy = today.getFullYear();
                                        
                                        today = yyyy + '-' + mm + '-' + dd;
                                        pedido.data = today;
                                        
    //e aqui, pegando a data preenchida já inserida antes no json e...
    $("#dataEntregaInpt").val(pedido.dataPrevista);
    
    //printa os moldes
    if(pedido.produtos != {}){
    $.each(pedido.produtos, function(key, value){
      x = key.split('_');
      x = x[1];
      //console.log(x);
      var wrapper2 = $("#janela-moldes"); //janela de inserir moldes
      var wrapper = $("#insereMolde"); //janela de inserir os botões
      var tamanhos = ""; //string q vai receber os tamanhos vindos do json (dados já preenchidos antes) 
      //percorrendo o array e inserindo cada tamanho
      $.each(pedido.produtos['item_'+x]['quantidades'],function(key,value){
    	    //onsole.log(pedido.produtos['item_'+x]['quantidades'][key]);
    	    tamanhos += "<th scope=col style='width:200px;padding:0px;'>"+pedido.produtos['item_'+x]['quantidades'][key].nome+"<input id='"+x+"_"+pedido.produtos['item_'+x]['quantidades'][key].codigo+"' type='number' style='width:100%;height:35px' value="+pedido.produtos['item_'+x]['quantidades'][key].quantidade+"></th>";

    	    if(parseInt(value.quantidade)!= 0){
    	    $(wrapper).append("<tr style='height:20px;' id='lin"+x+"_"+value.codigo+"'><td scope='row' style='padding:0px;height:20px;width:228px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span>"+value.codigo+"</span></td><td scope='row' style='padding:0px;height:20px;width:210px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e;'><span id='inp"+x+'_'+value.codigo+"' style='width:100%;background-color:#3a3b3c;color:white;height:30px;text-align:center'>"+parseInt(value.quantidade)+"</span></td><td scope='row' style='padding:0px;height:20px;width:250px'><input id='pre"+x+'_'+value.codigo+"' type='number' value='"+parseFloat(value.preco)+"' style='width:100%;background-color:#3a3b3c;color:white;border:1px solid #4b4c4e;height:30px;text-align:center'></td><td scope='row' style='padding:0px;height:20px;width:238px;text-align:center;background-color:#3a3b3c;border:1px solid #4b4c4e'><span id='tot"+x+'_'+value.codigo+"'style='width:100%;color:white;height:30px;'>"+(parseFloat(value.preco)*parseInt(value.quantidade)).toFixed(2)+"</span></td></tr><div>");
    	    }
      });

      $(wrapper2).append("<div id='janelaAtual_"+x+"' class='col-sm-4' style='float:left;height:340px; width:245px;border-radius:8px;display:inline-block;vertical-align:middle;margin-right:-4px;margin-bottom:20px'><div class='row'><div class='container boxImgMolde'><div clss='col-sm-12' style='background-color:#2e2f30;border-radius: 10px 10px 0px 0px; padding-top:2px'><span style='margin-left:25px'>#"+pedido.id+"-"+x+"</span><button type='button' id='"+x+"' class='btn btn- btn-sm remove_field' onclick='' style='height: 18px;width:20px; line-height: 1.25; border:1px solid red; display:inline; float:right;color:green;margin-right:5px;font-size:10px;background-color:#3a3b3c' alt='orçamento'><span style='margin-left:-2px;color:red'>X</span></button></div><a id='imgMolde' href='#' ><img id='moldeImg_"+x+"' class='mudaMoldeImg' data-toggle='modal' data-target='#modalMolde' src='"+pedido.produtos['item_'+x].molde.imagem+"' alt='Minha Figura' width='100%' style='background-color:white'></a><div col-sm-12 style='margin-top:3px'><button type='button' id='appMoldeBtn' class='btn btn- btn-sm' onclick='' style='height: 18px;width:20px; line-height: 0.7; border:1px solid green; display:inline; float:right;color:green;margin-left:-3px' alt='orçamento'><i id='appBtn_"+x+"' class='fa fa-bookmark fa-sm appMoldeBtn' style='margin-left:-3.5px;margin-top:-3px'></i></button><button type='button' class='btn btn- btn-sm' onclick='' style='height: 18px;width:20px; line-height: 0.7; border:1px solid green; display:inline; float:left;color:green;margin-left:3px;margin-right:5px' alt='orçamento'><a id='downBtn_"+x+"' href='"+pedido.produtos[key].molde.descricaoCurta.moldeLink+"' target='_blank'><i class='fa fa-download fa-2xs btnDownloadClass' style='margin-left:-5.5px;margin-top:-3px'></i></a></button><button style='display:block;width:20px; height:18px;background-color:#2e2f30;border:1px solid green;color:green'><i id='upBtn_"+x+"' class='fa fa-upload fa-2xs btnUpClass' style='margin-left:-5.5px;margin-top:-3px;color:green;' onclick="+'"'+"document.getElementById("+"'"+"files_"+x+"'"+").click()"+'"'+"></i></button><input type='file' id='files_"+x+"' style='display:none' multiple><span id='spanNomeMoldeAtual_"+x+"' style='margin-left:-20px'>"+pedido.produtos[key].molde.codigo+"</span></div><div id='tamanhos_"+x+"' style='width:220px;height:60px;background-color:#2e2f30; border-radius: 0px 0px 10px 10px'> <table class='table table-bordered text-center' style='border:1px solid #4b4c4e;margin-bottom:0px;height:10px'><thead style='background-color:#2e2f30;height:15px;text-align:center;color:white'><tr>"+tamanhos+"</tr></thead></table></div></div></div></div>");

           //printa a tabela inferior
        
        //
    
        });
        
        //e aqui atualizando o total do pedido quando carrega-lo
        var totalPedido = 0;
        $.each(pedido.produtos,function(key,value){
            $.each(value.quantidades,function(key,value){
                if(value.quantidade != 0){ //passando os dados do produto para itens
                    totalPedido += parseInt(value.quantidade) * parseFloat(value.preco);
                }
            })
        }); 
        $('#valorFinal').text(totalPedido.toFixed(2));
    }
</script>




