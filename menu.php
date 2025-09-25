<?php
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


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css" integrity="sha512-10/jx2EXwxxWqCLX/hHth/vu2KY3jCF70dCQB8TSgNjbCVAC/8vai53GfMDrO2Emgwccf2pJqxct9ehpzG+MTw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<nav class="navbar navbar-custom" style="padding-bottom: 0px;background-color: black"> 
<!-- fixed-top  -->
<div class="container" style="width: 820px; padding: 0px;"> 
    <div class="row" style="width: 100%;">
      <div class="col-sm">
            <a href="index.php">
                <img src="img/logo.png" width="130" height="30" alt="" style="margin-top: 5px;">
            </a>
      </div>
      <div class="col-sm text-center">
        <p style="color: white; font-size: 28px; margin-bottom: -5px;">ConfecPersonal</p>
      </div>
      <div class="col-sm" style="text-align:right; margin-top: 5px;">
        <!-- Small button groups (default and split) -->
        <div class="btn-group" style="margin-right: -20px;">
        
       <?php
            //nessa parte, ele verifica se tem alguma imagem no sistema de foto de perfil
            $arquivo = "img/".$_SESSION['usuarioNome'].".png";
        
            if (file_exists($arquivo)){
                echo('<img src="img/'.$_SESSION['usuarioNome'].'.png" style="width: 30px; height: 30px">');
            }else{
                if($_SESSION['usuarioNome']=='Desenvolvimento'){
                    echo('<img src="img/Desenvolvimento.gif" style="width: 30px; height: 30px">');
                    echo('');
                }else{
                    echo('<img src="img/vendedor.png" style="width: 30px; height: 30px">');
                }
            }
        ?>   
        <button class="btn btn-black btn-sm dropdown-toggle text-white" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo($_SESSION['usuarioNome']); ?>
        </button>
        <div class="dropdown-menu" style="margin-top: 33px;">
            <!-- <a class="dropdown-item" href="#">Perfil</a> -->
            <a class="dropdown-item" href="#">Perfil</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="sair.php">Sair</a>
          </div>
        </div>
      </div>
    </div>
  </div> 



        <style>
    .button{
      
    }
    .dropdown-item:hover{

    color: white;
    background-color:#3a3b3c;

    }
  </style>
  <?php
    if(isset($_SESSION['usuarioNivelAcesso'])){
      echo('<span style="color:white">'.$_SESSION['usuarioNivelAcesso'].'</span>');
      //menu do vendedor
      if($_SESSION['usuarioNivelAcesso']==2){
        echo('<div style="height: 40px; background-color: #18191a; margin-top: 10px; width: 100%; padding: 5px;">');
          echo('<div class="container" style="width: 820px; padding: 0px;">');
            echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'tecido.php'".';">Conferir Estoque</button>');
            echo('</div>');
            echo('</div>');
      }
      //menu do admin
      else if($_SESSION['usuarioNivelAcesso']==0){
        echo('<div style="height: 40px; background-color: #18191a; margin-top: 10px; width: 100%; padding: 5px;">');
        echo('<div class="container" style="width: 820px; padding: 0px;">');
          echo('<button type="button" class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height:30px;line-height:1;font-size:15px">Pedido</button>');
          echo('<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="background-color:#27272a;">
                <a class="dropdown-item" href="Pedido.php" style="color:white">Tirar pedido</a>
                <a class="dropdown-item" href="meusPedidos.php" style="color:white">Meus pedidos</a>
                <a class="dropdown-item" href="novoCliente.php" style="color:white">Cadastrar cliente</a>
                <a class="dropdown-item" href="atualizaCliente.php" style="color:white">Atualizar cliente</a>
                <a class="dropdown-item" href="historicoPedidos.php" style="color:white">Histórico de pedidos</a>
              </div>');
          echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'movimentacao.php?setorAtual=10&aplicacaoAtual='".';">Em produção</button>');
          echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'tecido.php'".';">Estoque tecidos</button>');
          echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'meusMoldes.php'".';">Moldes</button>');
          echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'auth.php'".';">Token</button>');
          echo('<button type="button" id="btnFeriados" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="showFeriados()">Feriados</button>');
          echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'controlePedidos.php'".';">Controle de Pedidos</button>');
          echo('</div>');
          echo('</div>');
      }
      //menu do vendedor
      else if($_SESSION['usuarioNivelAcesso']==1){
        echo('<div style="height: 40px; background-color: #18191a; margin-top: 10px; width: 100%; padding: 5px;">');
        echo('<div class="container" style="width: 820px; padding: 0px;">');
          echo('<button type="button" class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height:30px;line-height:1;font-size:15px">Pedido</button>');
          echo('<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="background-color:#27272a;">
                <a class="dropdown-item" href="Pedido.php" style="color:white">Tirar pedido</a>
                <a class="dropdown-item" href="meusPedidos.php" style="color:white">Meus pedidos</a>
                <a class="dropdown-item" href="novoCliente.php" style="color:white">Cadastrar cliente</a>
                <a class="dropdown-item" href="atualizaCliente.php" style="color:white">Atualizar cliente</a>
                <a class="dropdown-item" href="historicoPedidos.php" style="color:white">Histórico de pedidos</a>
              </div>');
          echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'movimentacao.php'".';">Em produção</button>');
          echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'tecido.php'".';">Gasto tecido</button>');
          echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'controlePedidos.php'".';">Controle de Pedidos</button>');
          echo('</div>');
          echo('</div>');
          
      }
    //menu da fábrica (Nada editável e menor nível de acesso)
    else if($_SESSION['usuarioNivelAcesso']==4){
        echo('<div style="height: 40px; background-color: #18191a; margin-top: 10px; width: 100%; padding: 5px;">');
        echo('<div class="container" style="width: 820px; padding: 0px;">');
          echo('<button type="button" class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height:30px;line-height:1;font-size:15px">Pedido</button>');
          echo('<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="background-color:#27272a;">
                <a class="dropdown-item" href="meusPedidos.php" style="color:white">Meus pedidos</a>
                <a class="dropdown-item" href="novoCliente.php" style="color:white">Cadastrar cliente</a>
                <a class="dropdown-item" href="atualizaCliente.php" style="color:white">Atualizar cliente</a>
              </div>');
          echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'movimentacao.php'".';">Em produção</button>');
          echo('</div>');
          echo('</div>');
    }
    else if($_SESSION['usuarioNivelAcesso']==5){
        echo('<div style="height: 40px; background-color: #18191a; margin-top: 10px; width: 100%; padding: 5px;">');
        echo('<div class="container" style="width: 820px; padding: 0px;">');
        echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'movimentacao.php'".';">Em produção</button>');
        echo('<button type="button" style="margin-left:5px" class="btn btn-dark btn-sm" onclick="window.location='."'controlePedidos.php'".';">Controle de Pedidos</button>');
        echo('</div>');
        echo('</div>');
    }
    }else{
      header("Location:login.php");
    }
  ?>
        
<!-- gradientzinho -->
<div style="height: 3px; background-image: linear-gradient(to right, #FF0009, #EB1E13, #C41910, #FF0009); margin-top: 0px; width: 100%;"></div>
<!-- fim gradient -->
</nav> 

<!-- parte que trata das datas que não podem ser selecionadas -->

<style>
    #dataProibida{
        color:white;
        border:1px solid white;
        font-size:14px;
        width:100%;
        display:inline-block;
        line-height:2;
    }
    .btnAddDate:hover{
        color:white;
        background-color:red;
        
    }
    .btnAddDate{
        height:15px;
        border:1px solid grey;
        float:right;
        width:30px;
    }
    #btnAddDate2:hover{
        color:white;
        background-color:green;
        
    }
    #btnAddDate2{
        height:15px;
        border:1px solid grey;
        float:right;
        width:30px;
    }
</style>

<center>
    <div id='janelaFeriados' style='display:none;border:1px solid green;width:200px;background-color:#2e2f30;position:absolute'>
        <div id='dataProibida'>
            
        </div>
        <!-- adicionar data proibida -->
        <hr style='color:white;margin:0px'>
        <input type="date" id="dataToBlock" style="width: 145px;line-height:1.85;border:1px solid green;border-radius:5px;font-size:14px;width:165px">
        <button type="button" id="btnAddDate2" onclick="insereData()" class="btn btn- btn-sm" style='height:29px;width:30px;color:white'>+</button>
    </div>
</center>

<script>
    let datasBloqueadas;
    function showFeriados(){
        $("#dataProibida").empty();
        $("#janelaFeriados").toggle(250);
        //mudando a posição da janela===========================
        var pos = $("#btnFeriados").offset();
        pos.left = pos.left;
        pos.top = pos.top+35;
        $("#janelaFeriados").offset(pos);
        //showFeriados();

        //pegando as datas proibidas via ajax ==================
        $.ajax({
        type: "POST",
        url: 'ajax/pagaDatasFeriado.php',
        data: jQuery.param({}) ,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(data)
            {
                console.log(data);
                datasBloqueadas = JSON.parse(data);
                if(datasBloqueadas == null){
                    datasBloqueadas = [];
                }
                $.each(datasBloqueadas, function(key, value){
                    var dataExibe = value.split('-').reverse().join('/');
                    $("#dataProibida").append("<div style='border:1px solid grey'>"+dataExibe+"<button type='button' class='btnAddDate' id='btnAddDate_"+key+"' onclick='removeData("+key+")' class='btn btn- btn-sm' style='height:29px;'>X</button></div>");
                });
            }
        });
        
        
    }
    
    function insereData(){
        datasBloqueadas.push($("#dataToBlock").val());
        console.log(datasBloqueadas);
        $.ajax({
        type: "POST",
        url: 'ajax/insereDataFeriadoDB.php',
        data: jQuery.param({datasTemp: datasBloqueadas}),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(data)
            {
                var dataExibe = $("#dataToBlock").val().split('-').reverse().join('/');
                var tamanhoDatas = (datasBloqueadas.length)-1;
                $("#dataProibida").append("<div style='border:1px solid grey'>"+dataExibe+"<button type='button' class='btnAddDate' id='btnAddDate_"+tamanhoDatas+"' onclick='removeData("+tamanhoDatas+")' class='btn btn- btn-sm' style='height:29px;'>X</button></div>");                       
            }
        });
        
    }
    
    function removeData(indexRemovido){
        datasBloqueadas.splice(indexRemovido,1);
        $.ajax({
        type: "POST",
        url: 'ajax/insereDataFeriadoDB.php',
        data: jQuery.param({datasTemp: datasBloqueadas}),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(data)
            {
                $("#btnAddDate_"+indexRemovido).parent('div').remove();                      
            }
        });
    }
</script>
