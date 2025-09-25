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
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="js/jquery.js"></script>
<!-- api que gera pdf -->
  <script src="js/xepOnline.jqPlugin.js"></script>
<!-- bootstrap e css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<!-- font awersome -->
<script src="https://kit.fontawesome.com/704200b128.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


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
include_once("php/connect.php"); //conexão
include_once("funcoes.php"); //todas as funções

//pegando o token
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    $token = $resultadot["valor"];
    //$js_array = json_encode($php_array);
?>
<div class="" style="padding: 3px 0px 3px 0px; margin-top: 75px;">
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
    <div class="rounded" id='janelaPedidosAbertos' style="background-color: #27272a; width: 100%; padding: 10px;margin-top:-80px;text-align:center">
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
                    .boxImgMolde:hover {
                    transform: scale(1.2); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
                    -webkit-transition: transform 0.20s ease-in-out;
                    }
                                
                    .boxImgMolde:onclick{
                        transform: scale(0.9); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
                        -webkit-transition: transform 0.20s ease-in-out;
                    }
                    
                    
                </style>
                <table class="table table-bordered text-center" style="color:white;border:1px solid #4b4c4e; margin-top:3px;margin-bottom:0px;">
                      <thead style="background-color:#2e2f30">
                        <tr>
                          <th scope="col" style="">#Cód.</th>
                          <th scope="col" style="width:200px">Vendedor</th>
                          <th scope="col" style="width:300px">Cliente</th>
                          <th scope="col" style="width:150px">Previsão</th>
                          <th scope="col" style="width:100px">Valor Total</th>
                          <th scope="col" style="">Opções</th>
                        </tr>
                        <?php 
                        //controlando o que cada um pode ver, essa parte é extremamente importante pois mostra ou oculta pedidos (ATENçÃO!!!)
                        $queryP = "";
                        
                        if($_SESSION['usuarioNivelAcesso'] == 4){
                            $queryP = "SELECT * FROM pedidos WHERE status='aberto' AND setor='20'";
                        }else if($_SESSION['usuarioNivelAcesso'] == 1){
                            $queryP = "SELECT * FROM pedidos WHERE vendedor = '$_SESSION[usuarioId]' AND status='aberto' AND setor='1' ORDER BY idPedido DESC";
                        }else if($_SESSION['usuarioNivelAcesso'] == 0){
                            $queryP = "SELECT * FROM pedidos WHERE status='aberto' AND setor='1' ORDER BY idPedido DESC";
                        }
                        //pegando os pedidos do database com base no vendedor logado e o status do pedido
                            $resultado_ped = mysqli_query($conn, $queryP);
                            $resultSet = mysqli_fetch_all($resultado_ped, MYSQLI_BOTH);
                            foreach ($resultSet as $id => $row) {
                               //print_r($row);
                               $vendaTemp = json_decode($row['pedidoJson']);
                               $dados = json_decode(json_encode($vendaTemp));
                               $total = 0;
                               if(isset($dados->produtos)){
                                   foreach ($dados->produtos as $key=> $linha){
                                       if(isset($linha->quantidades)){
                                       //print_r($linha);
                                        foreach ($linha->quantidades as $key2=> $linha2){
                                        
                                        $total += floatval($linha2->quantidade)*floatval($linha2->preco);
                                        }
                                        if(isset($linha->aplicacoes)){
                                            foreach ($linha->aplicacoes as $key3=> $linha3){
                                                //print_r($linha3); 
                                                //print_r($linha2->preco); 
                                                $total += floatval($linha3->quantidade)*floatval($linha3->valor);
                                            }
                                       } 
                                     } 
                                   }
                               }
                               //organizando o formato da data
                               $orgDate = $dados->dataPrevista;  
                               $date = str_replace('-"', '/', $orgDate);  
                               $newDate1 = date("d/m/Y", strtotime($date));  
                               
                               
                               
                               echo("<tr style='height:20px;'>
                                <td scope='row' style='padding:0px;height:20px;font-family: system-ui;'>#".$row['idPedido']."</td>
                                <td scope='row' style='padding:0px;height:20px;width:150px;font-family: system-ui;'>".$row['vendedorNome']."</td>
                                <td scope='row' style='padding:0px;height:20px;width:430px;font-family: system-ui;'>
                                <input type='text' id='clienteNomeTemp_".$vendaTemp->id."' class='inputNomeCliente' style='width:100%;background-color:#3a3b3c;color:white;border:none' value='".$vendaTemp->contato->nome."'>
                                <td style='font-size:12px;line-height:0.5'>$newDate1</td>
                                </td>
                                <td scope='row' style='padding:0px;height:20px;font-family: system-ui;color:green'>R$ <span id='total_$row[idPedido]'>".number_format((float)$total,2)."</span></td>
                                <td scope='row' style='padding:0px;height:20px;font-family: system-ui;'>
                                <a href='Pedido.php?numeroPedido=".$row['idPedido']."'><i class='fa fa-pencil boxImgMolde'></i></a>
                                <a href='#' id='mudaVendedor_".$row['idPedido']."' onClick='mudaVendedor(".$row['idPedido'].")'<i class='fa fa-user'></i></a>
                                <a href='javascript:geraOrcamento(".$row['idPedido'].")' alt='imprimir'><i class='fa regular fa-print boxImgMolde' aria-hidden='true'></i></a>
                                <a href='deletaPedido.php?numeroPedido=".$row['idPedido']."' alt='imprimir'><i class='fa fa-trash boxImgMolde'></i></a>
                            </td>
                            </tr>");
                            }
                        ?>
                      </thead>
                </table>
                <!-- Janela que lista os vendedores para serem selecionados -->
                <style>
                  #janela-vendedores{
                      width:210px;
                      background-color:#27272a;
                      height:68px;
                      border:1px solid green;
                      color:white;
                      border-radius:10px;
                  }
                  #btnOkMudarV{
                      height:30px;
                      width:35px;
                      margin-top:0px;
                      float:right;
                      border:1px solid green; 
                      color:green;
                      margin-right:5px;
                  }
                  #btnOkMudarV:hover{
                      background-color:green;
                  }
                  
                  #lista-vendedores{
                      height:30px;
                  }
                </style>
                
                <script>
                let idPedidoTemp = ''; //MUITA ATENÇÃO AQUI, VARIÁVEL DE ESCOPO GLOBAL
                    function mudaVendedor(idPedido){
                        idPedidoTemp = idPedido;
                        //$("#janela-vendedores").hide();
                        $("#janela-vendedores").toggle(250);
                        
                        //mudando a janela de posição
                        var pos = $("#mudaVendedor_"+idPedidoTemp).offset();
                        pos.left = pos.left-215;
                        pos.top = pos.top;
                        $("#janela-vendedores").offset(pos);
                        
                        
                        //pegando uma lista com os nomes dos vendedores para listar
                        $.ajax({
                            type: "POST",
                            url: 'ajax//buscaUsuáriosSetor.php',
                            data: jQuery.param({
                            })
                            ,
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            success: function(data)
                            {
                                var vendedores = JSON.parse(data); //pega os vendedores 
                                $("#lista-vendedores").empty(); //esvazia o select 
                                $.each(vendedores, function(key, value){
                                    $("#lista-vendedores").append("<option value='"+value[0]+"'>"+value[1]+"</option>");            
                                });
                            }
                        });
                    }
                    
                    function confirmaTransf(){
                          var idVendedor = $('option:selected',$('#lista-vendedores')).val();
                          //console.log(idPedidoTemp+'//'+idVendedor);
                          $.ajax({
                            type: "POST",
                            url: 'ajax/transferePedidoEntreVendedor.php',
                            data: jQuery.param({numeroPedido:idPedidoTemp,idVendedorDestino:idVendedor
                            })
                            ,
                            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                            success: function(data)
                            {
                                console.log(data);
                                location.href = "meusPedidos.php";
                            }
                        });
                    }
                    
                    function geraOrcamento(idPedido){
                        window.open('geraOrcamento.php?numeroPedido='+idPedido, '_blank');
                    }
                </script>
                
                <div id='janela-vendedores' style='display:none'>
                    Transferir pedido
                        <select id='lista-vendedores'>
                            <option>--SELECIONE O VENDEDOR--</option>
                        </select>
                    <button type="button" id="btnOkMudarV" onClick='confirmaTransf()' class="btn btn- btn-sm">
                        <span style="color:white;">ok</span><i class="fa fa-floppy" aria-hidden="true"></i>
                    </button>
                </div>
                
            
                <script>
                //detecta qual o input foi selecionado para alterar o nome na venda no banco de dados via ajax
                var wrapper2 = $("#janelaPedidosAbertos");
                $(wrapper2).on("click", ".inputNomeCliente", function(e){ 
                    var texto = $(e.target).attr('id');
                    //console.log(texto);
                    $(this).keypress(function(event){
                                      var keycode = (event.keyCode ? event.keyCode : event.which);
                                          $.ajax({
                                                type: "POST",
                                                url: 'ajax/mudaNomeTemporario.php',
                                                data: jQuery.param({idTemp: texto.split('_')[1],
                                                                    nomeTemp: $(this).val()
                                                })
                                                ,
                                                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                                success: function(data)
                                                {
                                                    //console.log(data);
                                                    //alert("nome alterado!");
                                                }
                                            });
                  });
                });
                </script>
                <!-- mudaMolde -->
                <style>
                    .modal{
                        --bs-modal-header-border-color:#4b4c4e;
                    }
                    .modal-footer{
                        border-top: 1px solid #4b4c4e;
                    }
                </style>
    <div>
           
<!-- início 3°linha (hr) -->
        
<!-- inicío quinta linha -->
          <!-- inicio linha hr -->
        </div> <!-- fim do row pq tava bugado -->
        <!-- início dos dados reais+imagem -->
        <div class="container-xxl" style="margin-top: 8px;height: 1px;"></div>
        <!-- local que a janela do molde é inserida -->
                <!-- tela de resumo do pedido -->
            <div class='col-sm-12' style="font-size:14px;color:white;padding-top:5px;">
                <!-- fim dos dados do cliente -->
        </div
        <div class='row selected' id='janelaAtual_Resumo' style="text-align:center">
        <button type="button"  class="btn btn- btn-sm center" onclick="javascript:location.href='geraNumeroPedido.php?novo=sim'" style="height: 28px;width:32px; line-height: 0.7; border:1px solid green; display:inline-block;color:white;border-radius:15px;background-color:#3a3b3c;margin-top:5px">
                    +
        </button>
        </div>
        
        </div>
      </div> 
      </div>
<!-- footerzin -->

</body>
</html>