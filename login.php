<!doctype html>
<html lang="pt-bt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Personal Produção</title>
<link rel="icon" href="img/favicon.png">
<!-- bootstrap e css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
<style type="text/css">
  @font-face {
  font-family: "Helvetica-bold";
  src: url("font/Helvetica-Bold-Font.ttf");
  }

  body{
    background-color: #606060; /* <!-- #606060 --> */
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
<nav class="navbar fixed-top navbar-custom" style="padding-bottom: 0px;"> 
<div class="container" style="width: 820px; padding: 0px;"> 
    <div class="row" style="width: 100%;">
      <div class="col-sm">
            <a href="index.php">
                <img src="img/logo.png" width="130" height="30" alt="" style="margin-top: 5px;">
            </a>
      </div>
      <div class="col-sm text-center">
        <p style="color: white; font-size: 28px; margin-bottom: -5px;">Login</p>
        <br/>
      </div>
      <div class="col-sm" style="text-align:right; margin-top: 5px;">
      </div>
    </div>
  </div>   
<!-- gradientzinho -->
<div style="height: 3px; background-image: linear-gradient(to right, #FF0009, #EB1E13, #C41910, #FF0009); margin-top: 0px; width: 100%;"></div>
<!-- fim gradient -->
</nav> 

    <div class="container" style="padding: 3px 0px 3px 0px; margin-top: 250px; text-align: center">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4" style="background-color:white; height:340px; border-radius:30px; box-shadow: 5px 5px 20px black;">
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4"><img src="img/logoblack.png" width="100%" style="margin-top: 25px"></div>
                    <div class="col-sm-4"></div>
                    <!-- formulário de login -->
                    <form action="login_vai.php" method="post">
                            <div class="col-sm-12" style="color: grey;text-align: left; margin-top:30px">Usuário</div>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com" name="login" required autofocus>
                            </div>  
                            <div class="col-sm-12" style="color: grey; text-align: left; margin-top:10px">Senha</div>
                            <div class="col-sm-12">
                            <input type="password" id="inputPassword5" class="form-control" aria-describedby="passwordHelpBlock" name="senha" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark" style="margin-top:30px; width:150px">Login</button>
                    </form>
                    <!-- fim formulário -->
                <br/>
                 <?php
                if(isset($_GET['logado'])){
                    if($_GET['logado']=='falha'){
                        echo('<span style="color:red; font-size: 14px; margin-top:20px">Usuário ou senha incorretos</span><br/>');
                    }if($_GET['logado']=='semacesso'){
                        echo('<span style="color:red; font-size: 14px; margin-top:20px">Login necessário :)</span><br/>');
                    
                    }if($_GET['logado']=='saiu'){
                        echo('<span style="color:red; font-size: 14px; margin-top:20px">volte sempre :)</span><br/>');
                    }
                }
                ?>
                <span style="color: grey; font-size:10px; margin-top: 20px">*Caso perca seu login entre em contato com o suporte</span>
                
                
            </div>
            <div class="col-sm-4"></div>
    </div>
    </div>
</div>
</div>
</div>
<!-- footerzin -->
<!-- gradientzinho -->
</body>
</html>

<!-- vuejs e jquery para controle ajax e js -->  
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.js"></script>
  <script src="js/jquery.js"></script>