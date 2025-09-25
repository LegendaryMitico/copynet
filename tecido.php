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
<!-- fim security place -->
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Personal Produção</title>
<link rel="icon" href="img/icon.png">
<!-- meu css --> 
<link rel="stylesheet" href="css/style.css">
<!-- vuejs e jquery para controle ajax e js -->  
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.js"></script>
  <script src="js/jquery.js"></script>
<!-- api que gera pdf -->
  <script src="js/xepOnline.jqPlugin.js"></script>
<!-- bootstrap e css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
<!-- font awersome -->
<script src="https://kit.fontawesome.com/704200b128.js" crossorigin="anonymous"></script>
</head>
<body>
 <!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!- chart js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<style type="text/css">
  @font-face {
  font-family: "Helvetica-bold";
  src: url("font/Helvetica-Bold-Font.ttf");
  }

  body{
    background-color: white; /* <!-- #606060 --> */
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

    <div class="container" style="padding: 3px 0px 3px 0px; margin-top: 250px; text-align: center">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4" style="background-color:white; border-radius:30px; box-shadow: 5px 5px 20px black;">
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4"><img src="img/logoblack.png" width="100%" style="margin-top: 25px"></div>
                    <div class="col-sm-4"></div>
                    <!-- formulário de login -->
                    <form action="acha_molde.php" method="post">
                            <div class="col-sm-12" style="color: grey;text-align: left; margin-top:30px">PRODUTO:</div>
            
                            <div class="col-sm-12" style="text-align:left">
                            <?php
                            if(isset($_GET['tamanhos'])){
                                if($_GET['tamanhos'] == 'NAOACHOU'){
                                   unset($_SESSION['molde']);
                                    unset($_SESSION['qtd_tecido']);
                                    unset($_SESSION['tecido']);
                                    unset($_SESSION['gastoReal']);
                                    unset($_SESSION['id_tecido_cor']); 
                                }
                            }
                            if(isset($_GET['pai'])){
                                unset($_SESSION['molde']);
                                unset($_SESSION['qtd_tecido']);
                                unset($_SESSION['tecido']);
                                unset($_SESSION['gastoReal']);
                                unset($_SESSION['id_tecido_cor']);
                            }
                            //var_dump($_SESSION['molde']);
                            if(isset($_GET['molde'])){
                            if($_GET['tamanhos']=='NAOACHOU'){
                                    echo('<span style="color:red">Não encontrado *obs: sem o tecido no fim :)</span>');
                                }    
                            else if($_SESSION['molde']=='NADA'){
                                echo($_SESSION['nome_molde']);
                                echo('<input type="hidden" id=molde" name="molde" value="'.$_GET['molde'].'" />');   
                                }
                            }else{
                                unset($_SESSION['molde']);
                                echo('<input type="text" class="form-control" id="molde" placeholder="digite" name="molde" autofocus required style="font-size:12px">');
                                echo('<span style="color:grey;">TECIDO</span>');
                                
                                //require conexão ao db
                                //include('update/funcoes.php');
                                //require('update/connect.php');
                                require_once('php/connect.php');
                                require_once('funcoes.php');
                                $token = pegaToken($conn);
                                pegaPai($token);
                            }
                            ?>
                            
                                
                            </div>
                            <div class="col-sm-12" style="text-align:left">
                            <!-- pegando os tamanhos do link hehe -->
                             <?php
                                if(isset($_GET['tamanhos'])){
                                    if($_GET['tamanhos']=='masculina' && $_SESSION['molde']=='NADA'){
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">PP</span><input type="number" class="form-control" id="PP" name="PP" required autofocus style="width:45px;font-size:12px" value="0"></div>');  
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">P</span><input type="number" class="form-control" id="PP" name="P" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">M</span><input type="number" class="form-control" id="M" name="M" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">G</span><input type="number" class="form-control" id="G" name="G" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">GG</span><input type="number" class="form-control" id="GG" name="GG" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">EXG</span><input type="number" class="form-control" id="EXG" name="EXG" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        
                                    }
                                    if($_GET['tamanhos']=='feminino' && $_SESSION['molde']=='NADA'){
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">PP</span><input type="number" class="form-control" id="PP" name="PP" required autofocus style="width:45px;font-size:12px" value="0"></div>');  
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">P</span><input type="number" class="form-control" id="PP" name="P" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">M</span><input type="number" class="form-control" id="M" name="M" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">G</span><input type="number" class="form-control" id="G" name="G" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">GG</span><input type="number" class="form-control" id="GG" name="GG" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">EXG</span><input type="number" class="form-control" id="EXG" name="EXG" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        
                                    }
                                    if($_GET['tamanhos']=='infantojuvenil' && $_SESSION['molde']=='NADA'){
                                        echo('<div class="col-sm-1" style="margin-top:10px;display:inline-block"><span style="margin-left:10px">2A</span><input type="number" class="form-control" id="2A" name="2A" required autofocus style="width:45px;font-size:12px" value="0"></div>');  
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">4A</span><input type="number" class="form-control" id="4A" name="4A" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">6A</span><input type="number" class="form-control" id="6A" name="6A" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">8A</span><input type="number" class="form-control" id="8A" name="8A" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">10A</span><input type="number" class="form-control" id="10A" name="10A" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">12A</span><input type="number" class="form-control" id="12A" name="12A" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">14A</span><input type="number" class="form-control" id="14A" name="14A" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">16A</span><input type="number" class="form-control" id="16A" name="16A" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        
                                    }
                                    if($_GET['tamanhos']=='plussize' && $_SESSION['molde']=='NADA'){
                                        echo('<div class="col-sm-1" style="margin-top:10px; display:inline-block"><span style="margin-left:10px">X1</span><input type="number" class="form-control" id="X1" name="X1" required autofocus style="width:45px;font-size:12px" value="0"></div>');  
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">X2</span><input type="number" class="form-control" id="X2" name="X2" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">X3</span><input type="number" class="form-control" id="X3" name="X3" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">X4</span><input type="number" class="form-control" id="X4" name="X4" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">X5</span><input type="number" class="form-control" id="X5" name="X5" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        echo('<div class="col-sm-1" style="margin-top:10px;margin-left:20px; display:inline-block"><span style="margin-left:10px">X6</span><input type="number" class="form-control" id="X6" name="X6" required autofocus style="width:45px;font-size:12px" value="0"></div>');
                                        
                                    }     
                                }
                                ?>
                                
                            </div>
                            <?php
                            //echo($_SESSION['molde']);
                            if(isset($_SESSION['molde'])){
                                if($_SESSION['molde']=='NADA'){
                                    //seleciona a cor do tecido
                                    echo('<br><br><span style="color:grey;">TECIDO/MALHA '.$_SESSION['tecido'].'</span>');
                                    
                                    //gera o select com os filhos
                                    require_once('php/connect.php');
                                    require_once('funcoes.php');
                                    $token = pegaToken($conn);
                                    pegaFilho($token,$_SESSION['idTecidoPai']);
                                    
                                    echo('<button type="submit" class="btn btn-dark" style="margin-top:30px; width:150px">Calcular</button>'); 
                                }else{
                                $molde = json_decode($_SESSION['molde']);
                                //echo('<span style="font-size:24px;">'.$molde->nome.'</span><hr><br/>');
                                //echo('aqui:<br/>');
                                //var_dump($molde);
                                    if($molde->tipoMolde=='masculina' || $molde->tipoMolde=='feminino'){
                                         //echo('Consumo Unitário: <br/>');
                                        //echo('PP: '.str_replace('.',',',$molde->PP.' mt | '));
                                        //echo('P: '.str_replace('.',',',$molde->P.' mt | '));
                                        //echo('M: '.str_replace('.',',',$molde->M.' mt | '));
                                        //echo('G: '.str_replace('.',',',$molde->G.' mt | '));
                                        //echo('GG: '.str_replace('.',',',$molde->GG.' mt | '));
                                        //echo('EXG: '.str_replace('.',',',$molde->EXG.' mt <br/>'));
                                        //echo('<hr/>');
                                        //consumo por tamanho
                                        echo('Consumo por tamanho: <br/>');
                                        echo($molde->qtdPP.' x PP = '.(str_replace('.',',',$molde->qtdPP*$molde->PP)).' mt<br/>');
                                        echo($molde->qtdP.' x P = '.(str_replace('.',',',$molde->qtdP*$molde->P)).' mt<br/>');
                                        echo($molde->qtdM.' x M = '.(str_replace('.',',',$molde->qtdM*$molde->M)).' mt<br/>');
                                        echo($molde->qtdG.' x G = '.(str_replace('.',',',$molde->qtdG*$molde->G)).' mt<br/>');
                                        echo($molde->qtdGG.' x GG = '.(str_replace('.',',',$molde->qtdGG*$molde->GG)).' mt<br/>');
                                        echo($molde->qtdEXG.' x EXG = '.(str_replace('.',',',$molde->qtdEXG*$molde->EXG)).' mt<br/>');
                                        //soma
                                        $total = ($molde->qtdPP*$molde->PP)+($molde->qtdP*$molde->P)+($molde->qtdM*$molde->M)+($molde->qtdG*$molde->G)+($molde->qtdGG*$molde->GG)+($molde->qtdEXG*$molde->EXG);
                                        //TOTAL
                                        echo('<hr/>');
                                        echo('Tecido/malha: '.$_SESSION['tecido'].'<br/>'.$_SESSION['molde_cor'].'<br/><hr>');
                                        echo('<span style="color:red; font-size:16px;">Total a ser usado no pedido: '.str_replace('.',',',$_SESSION['gastoReal']).' mt</span></br>');
                                        if(isset($_SESSION['qtd_tecido'])){
                                            echo('Quantidade em estoque: '.$_SESSION['qtd_tecido'].' mt');
                                        }else{
                                            echo('Quantidade em estoque: '.'indisponível no bling (avise o suporte sobre esse produto pls)');
                                        } 
                                        
                                        //echo($_SESSION['id_tecido_cor']);
                                        if(isset($_SESSION['id_tecido_cor'])){
                                            if($_SESSION['usuarioNivelAcesso']==1 || $_SESSION['usuarioNivelAcesso']==0){
                                                echo('<button class="btn btn-success" style="margin-top:10px;width:100%;float:right" onclick="location.href='.'".../tecido.php">'.'Abater</button>');
                                            }else{
                                                echo('<button class="btn btn-dark" style="margin-bottom:15px;width:100%" onclick="location.href='.'".../tecido.php">'.'Ver outro tecido/malha</button>');
                                            }
                                        }
            
                                    }
                                    else if($molde->tipoMolde=='plussize'){
                                        //echo('Consumo Unitário: <br/>');
                                        //echo('X1: '.str_replace('.',',',$molde->X1).' mt | ');
                                        //echo('X2: '.str_replace('.',',',$molde->X2).' mt | ');
                                        //echo('X3: '.str_replace('.',',',$molde->X3).' mt | ');
                                        //echo('X4: '.str_replace('.',',',$molde->X4).' mt | ');
                                       // echo('X5: '.str_replace('.',',',$molde->X5).' mt | ');
                                        //echo('X6: '.str_replace('.',',',$molde->X6).' mt <br/>');
                                        //echo('<hr/>');
                                        //consumo por tamanho
                                        echo('Consumo por tamanho: <br/>');
                                        echo($molde->qtdX1.' x X1 = '.str_replace('.',',',($molde->qtdX1*$molde->X1)).' mt<br/>');
                                        echo($molde->qtdX2.' x X2 = '.str_replace('.',',',($molde->qtdX2*$molde->X2)).' mt<br/>');
                                        echo($molde->qtdX3.' x X3 = '.str_replace('.',',',($molde->qtdX3*$molde->X3)).' mt<br/>');
                                        echo($molde->qtdX4.' x X4 = '.str_replace('.',',',($molde->qtdX4*$molde->X4)).' mt<br/>');
                                        echo($molde->qtdX5.' x X5 = '.str_replace('.',',',($molde->qtdX5*$molde->X5)).' mt<br/>');
                                        echo($molde->qtdX6.' x X6 = '.str_replace('.',',',($molde->qtdX6*$molde->X6)).' mt<br/>');
                                        //soma
                                        $total = ($molde->qtdX1*$molde->X1)+($molde->qtdX2*$molde->X2)+($molde->qtdX3*$molde->X3)+($molde->qtdX4*$molde->X4)+($molde->qtdX5*$molde->X5)+($molde->qtdX6*$molde->X6);
                                        //TOTAL
                                        echo('<hr/>');
                                        echo('tecido:'.$_SESSION['molde_cor'].'<br/><hr>');
                                        echo('<span style="color:red; font-size:16px;">Total a ser usado na venda: '.str_replace('.',',',$total).' mt</span></br>');
                                        if(isset($_SESSION['qtd_tecido'])){
                                            echo('Quantidade em estoque: '.$_SESSION['qtd_tecido'].' mt');
                                        }else{
                                            echo('Quantidade em estoque: '.'indisponível');
                                        }    
                                        
                                        if(isset($_SESSION['id_tecido_cor'])){
                                            if($_SESSION['usuarioNivelAcesso']==1 || $_SESSION['usuarioNivelAcesso']==0){
                                                echo('<button class="btn btn-success" style="margin-top:10px;width:100%;float:right" onclick="location.href='.'".../tecido.php">'.'Abater</button>');
                                            }else{
                                                echo('<button class="btn btn-dark" style="margin-bottom:15px;width:100%" onclick="location.href='.'".../tecido.php">'.'Ver outro tecido/malha</button>');
                                            }
                                        }
                                    }
                                    else if($molde->tipoMolde=='infantojuvenil'){
                                      //echo('Consumo Unitário: <br/>');
                                      //echo('2A: '.str_replace('.',',',$molde->A2).' mt | ');
                                      //echo('4A: '.str_replace('.',',',$molde->A4).' mt | ');
                                     // echo('6A: '.str_replace('.',',',$molde->A6).' mt | ');
                                      //echo('8A: '.str_replace('.',',',$molde->A8).' mt | ');
                                      //echo('10A: '.str_replace('.',',',$molde->A10).' mt | ');
                                      //echo('12A: '.str_replace('.',',',$molde->A12).' mt <br/>');
                                      //echo('14A: '.str_replace('.',',',$molde->A14).' mt <br/>');
                                      //echo('16A: '.str_replace('.',',',$molde->A16).' mt <br/>');
                                      echo('<hr/>');
                                      //consumo por tamanho
                                      echo('Consumo por tamanho: <br/>');
                                      echo($molde->qtd2A.' x 2A = '.str_replace('.',',',($molde->qtd2A*$molde->A2)).' mt<br/>');
                                      echo($molde->qtd4A.' x 4A = '.str_replace('.',',',($molde->qtd4A*$molde->A4)).' mt<br/>');
                                      echo($molde->qtd6A.' x 6A = '.str_replace('.',',',($molde->qtd6A*$molde->A6)).' mt<br/>');
                                      echo($molde->qtd8A.' x 8A = '.str_replace('.',',',($molde->qtd8A*$molde->A8)).' mt<br/>');
                                      echo($molde->qtd10A.' x 10A = '.str_replace('.',',',($molde->qtd10A*$molde->A10)).' mt<br/>');
                                      echo($molde->qtd12A.' x 12A = '.str_replace('.',',',($molde->qtd12A*$molde->A12)).' mt<br/>');
                                      echo($molde->qtd14A.' x 14A = '.str_replace('.',',',($molde->qtd14A*$molde->A14)).' mt<br/>');
                                      echo($molde->qtd16A.' x 16A = '.str_replace('.',',',($molde->qtd16A*$molde->A16)).' mt<br/>');
                                      //soma
                                      $total = ($molde->qtd2A*$molde->A2)+($molde->qtd4A*$molde->A4)+($molde->qtd6A*$molde->A6)+($molde->qtd8A*$molde->A8)+($molde->qtd10A*$molde->A10)+($molde->qtd12A*$molde->A12)+($molde->qtd14A*$molde->A14)+($molde->qtd16A*$molde->A16);
                                      //TOTAL
                                      echo('<hr/>');
                                      echo('tecido:'.$_SESSION['molde_cor'].'<br/><hr>');
                                      echo('<span style="color:red; font-size:16px;">Total a ser usado na venda: '.str_replace('.',',',$total).' mt</span></br>');
                                      if(isset($_SESSION['qtd_tecido'])){
                                          echo('Quantidade em estoque: '.str_replace('.',',',($_SESSION['qtd_tecido'])).' mt');
                                      }else{
                                          echo('Quantidade em estoque: '.'indisponível');
                                      }
                                      
                                      if(isset($_SESSION['id_tecido_cor'])){
                                            if($_SESSION['usuarioNivelAcesso']==1 || $_SESSION['usuarioNivelAcesso']==0){
                                                echo('<button class="btn btn-success" style="margin-top:10px;width:100%;float:right" onclick="location.href='.'".../tecido.php">'.'Abater</button>');
                                            }else{
                                                echo('<button class="btn btn-dark" style="margin-bottom:15px;width:100%" onclick="location.href='.'".../tecido.php">'.'Ver outro tecido/malha</button>');
                                            }
                                        }
 
                                    }
                                }
                            }else{
                                echo('<button type="submit" class="btn btn-dark" style="margin-top:30px; width:150px">Calcular</button>'); 
                                if(isset($_GET['quantia'])){
                                    echo('<br/><span style="color:green">Foram abatidos '.$_GET['quantia'].' mt do estoque de '.$_GET['tecido'].'</span><br/>');
                                }
                            }
                            ?>
                            <div style="font-size:14px; text-align:left">

                            </div>
                        </div>
                    </form>
                    <!-- fim formulário -->
                <br/>
            </div>
            <div class="col-sm-4"></div>
    </div>
    </div>
</div>
</div>
</div>
<!-- footerzin -->
<!-- gradientzinho -->
<div style="height: 3px; background-image: linear-gradient(to right, #FF0009, #EB1E13, #C41910, #FF0009); margin-top: 250px; width: 100%;"></div>
<!-- fim gradient -->
 <footer id="sticky-footer" class="flex-shrink-0 py-4 bg-dark text-white-50" style="height:200px">
    <div class="container text-center">
      <small>Copyright &copy; Personal confecções</small>
    </div>
  </footer>
</div>
</body>
</html>