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
<link rel="icon" href="img/icon.png">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Personal Produção</title>
<link rel="icon" href="img/favicon.png">
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
<!-- pegando dados pré preenchidos -->
<?php
include_once("php/connect.php");
include_once("funcoes.php");
$valores = pegaCustos($conn);
var_dump($valores);
?>

<div class="container" style="padding: 3px 0px 3px 0px; width: 820px; margin-top: 160px; box-shadow: 5px 5px 20px grey; background-color: white;border-radius:0px;font-size:12px">
<!-- teste alinhamento -->
<form action="atualiza_custo.php" method="post">
<div class="container3">
    <div class="row">
        <div class="col-sm-2" style="line-height:1.5; background-color:#444444;border-radius:0px;color:white;text-align:center;border:1px solid black">
            Atelier
        </div>
        <div class="col-sm-2" style="line-height:1.5; background-color:#444444;border-radius:0px;color:white;text-align:center;border:1px solid black">
            Corte
        </div>
        <div class="col-sm-2" style="line-height:1.5; background-color:#444444;border-radius:0px;color:white;text-align:center;border:1px solid black">
            Impressão
        </div>
        <div class="col-sm-2" style="line-height:1.5; background-color:#444444;border-radius:0px;color:white;text-align:center;border:1px solid black">
            Serigrafia
        </div>
        <div class="col-sm-2" style="line-height:1.5; background-color:#444444;border-radius:0px;color:white;text-align:center;border:1px solid black">
            Bordado
        </div>
        <div class="col-sm-2" style="line-height:1.5; background-color:#444444;border-radius:0px;color:white;text-align:center;border:1px solid black">
            Expedição
        </div>
        <div class="col-sm-2" style="line-height:1.5;border-radius:0px;color:white;text-align:center;border:1px solid grey;padding:0px">
            <?php
            global $valores;
            echo('<input type="text" placeholder="R$ 1.800,00" style="width:100%;" name="atelier" value="'.$valores['atelier'].',00">');
            ?>
            
        </div>
        <div class="col-sm-2" style="line-height:1.5;border-radius:0px;color:white;text-align:center;border:1px solid grey;padding:0px">
            <?php
            global $valores;
            echo('<input type="text" placeholder="R$ 1.800,00" style="width:100%;" name="corte" value="'.$valores['corte'].',00">');
            ?>
        </div>
        <div class="col-sm-2" style="line-height:1.5;border-radius:0px;color:white;text-align:center;border:1px solid grey;padding:0px">
            <?php
            global $valores;
            echo('<input type="text" placeholder="R$ 1.800,00" style="width:100%;" name="impressao" value="'.$valores['impressao'].',00">');
            ?>
        </div>
        <div class="col-sm-2" style="line-height:1.5;border-radius:0px;color:white;text-align:center;border:1px solid grey;padding:0px">
            <?php
            global $valores;
            echo('<input type="text" placeholder="R$ 1.800,00" style="width:100%;" name="serigrafia" value="'.$valores['serigrafia'].',00">');
            ?>
        </div>
        <br>
        <div class="col-sm-2" style="line-height:1.5;border-radius:0px;color:white;text-align:center;border:1px solid grey;padding:0px">
            <?php
            global $valores;
            echo('<input type="text" placeholder="R$ 1.800,00" style="width:100%;" name="bordado" value="'.$valores['bordado'].',00">');
            ?>
        </div>
        <div class="col-sm-2" style="line-height:1.5;border-radius:0px;color:white;text-align:center;border:1px solid grey;padding:0px">
            <?php
            global $valores;
            echo('<input type="text" placeholder="R$ 1.800,00" style="width:100%;" name="expedicao" value="'.$valores['expedicao'].',00">');
            ?>
        </div>
        <div class="col-sm-2" style="line-height:1.5; background-color:#444444;border-radius:0px;color:white;text-align:center;border:1px solid black">
            Estamparia
        </div>
        <div class="col-sm-2" style="line-height:1.5; background-color:#444444;border-radius:0px;color:white;text-align:center;border:1px solid black">
            PCP
        </div>
        <div class="col-sm-2" style="line-height:1.5; background-color:#444444;border-radius:0px;color:white;text-align:center;border:1px solid black">
            Fábrica
        </div>
        <div class="col-sm-6" style="line-height:1.5;border-radius:0px;color:white;text-align:center;padding:0px"></div>
        
        <div class="col-sm-2" style="line-height:1.5;border-radius:0px;color:white;text-align:center;border:1px solid grey;padding:0px">
            <?php
            global $valores;
            echo('<input type="text" placeholder="R$ 1.800,00" style="width:100%;" name="estamparia" value="'.$valores['estamparia'].',00">');
            ?>
        </div>
        <div class="col-sm-2" style="line-height:1.5;border-radius:0px;color:white;text-align:center;border:1px solid grey;padding:0px">
            <?php
            global $valores;
            echo('<input type="text" placeholder="R$ 1.800,00" style="width:100%;" name="pcp" value="'.$valores['pcp'].',00">');
            ?>
        </div>
        <div class="col-sm-2" style="line-height:1.5;border-radius:0px;color:white;text-align:center;border:1px solid grey;padding:0px">
            <?php
            global $valores;
            echo('<input type="text" placeholder="R$ 1.800,00" style="width:100%;" name="fabrica" value="'.$valores['fabrica'].',00">');
            ?>
        </div>
        <div class="col-sm-6" style="line-height:1.5;border-radius:0px;color:white;text-align:center;padding:0px"></div>
    </div>
    <br/>
    <div class="col-sm-2" style="line-height:1.5; background-color:black;border-radius:0px;color:white;text-align:center;border:1px solid black;margin-left:-12px">
            Impostos
    </div>
    <div class="col-sm-10" style="line-height:1.5;border-radius:0px;color:white;text-align:center;padding:0px"></div>
        <div class="col-sm-2" style="line-height:1.5;border-radius:0px;color:white;text-align:center;border:1px solid grey;padding:0px;margin-left:-12px">
            <?php
            global $valores;
            echo('<input type="text" placeholder="R$ 1.800,00" style="width:100%;" name="impostos" value="'.$valores['impostos'].'%">');
            ?>
    </div>
    
        
    <div class="col-sm-12" style="height:1px;background-color:#D1CED6;margin-top:5px;"></div>
            <div class="col-sm-12">
                <button type="submit" class="btn btn-dark btn-sm" style="width:90px;margin-top:5px; float:right; ">Atualizar</button>
            <br/>
    </div>
    <form/>
    <br>
    </div>

<style>
.container3 {
  font-family: arial;
  font-size: 13px;
  margin: 15px;
  margin-top:0px;
  width: 96.5%;
  /* Setup */
  position: relative;
  
}
</style>



</div>
</div>
</div>
</div>
<!-- footerzin -->
<!-- gradientzinho -->
<div style="height: 3px; background-image: linear-gradient(to right, #FF0009, #EB1E13, #C41910, #FF0009); margin-top: 250px; width: 100%;"></div>
<!-- fim gradient -->
 <footer id="sticky-footer" class="flex-shrink-0 py-4 bg-dark text-white-50" style="height:290px">
    <div class="container text-center">
      <small>Copyright &copy; Personal confecções</small>
    </div>
  </footer>
</body>
</html>