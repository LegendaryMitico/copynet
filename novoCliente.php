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
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
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
<div class="container" style="padding: 3px 0px 3px 0px; width: 820px; margin-top: 20px;">
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
    <div class="rounded" style="background-color: #27272a; width: 100%; padding: 10px;">
    <div class="row" style="color:white">
        <div class="col-sm-12">
            Cadastro cliente
            <div class="container" style="background-color: green;height: 1px;margin-bottom:10px;margin-top:2px"></div>
        </div>
        <div class="col-sm-6">
        Nome<span style="color:red">*</span>    
        </div>
        <div class="col-sm-6">
        Fantasia   
        </div>
        <div class="col-sm-6">
            <input type="text" id="nomeCliente" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="" required> 
            <script>
        $(document).ready(function(){
                      $("#btnCadastrar").click(function(e){
                        console.log($('#nomeCliente').val());
                        ($('#nomeCliente').val()=='')? console.log('não ta preen'): console.log('sim ta preen');
                                //alert($(this).attr('id'));
                                $.ajax({
                                    type: "POST",
                                    url: 'ajax/cadastraCliente.php',
                                    data: jQuery.param({nomeCliente: $("#nomeCliente").val(),
                                                        nomeFantasia: $("#nomeFantasia").val(),
                                                        tipoPessoa: $( "#tipoPessoa option:selected" ).val(),
                                                        clienteCpf: $("#clienteCpf").val(),
                                                        regimeTrib: $( "#regimeTrib option:selected" ).text(),
                                                        inputCont: $("#inputCont").val(),
                                                        inscEstadual: $("#inscEstadual").val(),
                                                        inputCep: $("#inputCep").val(),
                                                        inputUf: $( "#inputUf option:selected" ).text(),
                                                        inputCidade: $("#inputCidade").val(),
                                                        inputBairro: $("#inputBairro").val(),
                                                        endereco: $("#endereco").val(),
                                                        numero: $("#numero").val(),
                                                        complemento: $("#complemento").val(),
                                                        inputTelefone: $("#inputTelefone").val(),
                                                        inputCelular: $("#inputCelular").val(),
                                                        inputEmail: $("#inputEmail").val(),
                                                        
                                    }) ,
                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                    success: function(data)
                                    {
                                        alert(data);
                                    }
                                });
                      });
        });
        </script>
        </div>
        <div class="col-sm-6">
            <input type="text" id="nomeFantasia" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="">  
        </div>
        <div class="col-sm-4" style="margin-top:5px">
        Tipo de pessoa 
        </div>
        <div class="col-sm-8" style="margin-top:5px">
        CPF/CNPJ
        </div>
        <div class="col-sm-4">
            <select id='tipoPessoa' class="form-select form-select-sm" aria-label=".form-select-sm example" style="background-color:#3a3b3c;color:white">
              <option value="J">Pessoa Jurídica</option>
              <option value="F">Pessoa Física</option>
              <option value="E">Estrangeiro</option>
            </select>
        </div>
        <div class="col-sm-8">
            <input type="text" id="clienteCpf" class="cpf" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:30px" value="">  
        </div>
        <div class="col-sm-4" style="margin-top:5px">
            Contribuinte:
            <select id="inputCont" class="form-select form-select-sm" aria-label=".form-select-sm example" style="background-color:#3a3b3c;color:white">
              <option value="1">Contribuinte ICMS</option>
              <option value="9">Não contribuinte</option>
              <option value="2">constribuinte isento de de incrição no cadastro de Constribuintes</option>
            </select> 
        </div>
        <div class="col-sm-8" style="margin-top:5px" id="inscEstadualHide">
            Inscrição estadual:
             <input type="text" id="inscEstadual" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:30px" value="">
        </div>
        <div class="container" style="margin-top:5px"><div class="container" style="background-color: #4b4c4e;height: 1px;"></div></div>
        <div class="col-sm-2">
            CEP
        </div>
        <div class="col-sm-2">
            UF
        </div>
        <div class="col-sm-4">
            Cidade
        </div>
        <div class="col-sm-4">
            Bairro
        </div>
        <div class="col-sm-2">
            <input type="text" id="inputCep" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="">
            
        </div>
        <div class="col-sm-2">
            <select id="inputUf" class="form-select form-select-sm" aria-label=".form-select-sm example" style="background-color:#3a3b3c;color:white;height:26px">
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
        <div class="col-sm-4">
            <input id="inputCidade" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="">
        </div>
        <div class="col-sm-4">
            <input id="inputBairro" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="">
        </div>
        <div class="col-sm-8">
            Endereço
        </div>
        <div class="col-sm-1">
            N°
        </div>
        <div class="col-sm-3">
            <!-- Complemento -->
        </div>
        <div class="col-sm-8">
            <input id="endereco" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="">
        </div>
        <div class="col-sm-1">
            <input id="numero" type="text" style="width:130%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px;font-size:14px" value="">
        </div>
        <div class="col-sm-3">
            <!--<input id="complemento" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="">-->
        </div>
         <div class="container" style="margin-top:5px">
             <div style="background-color: #4b4c4e;height: 1px;"></div>
         </div>
        <div class="col-sm-4">
            Telefone
        </div>
        <div class="col-sm-4">
            Celular
        </div>
        <div class="col-sm-4">
            Email
        </div>
         <div class="col-sm-4">
            <input id="inputTelefone" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="" id="inputFixo" placeholder="Telefone" pattern="\([0-9]{2}\)[0-9]{5,7}-[0-9]{4,5}$" required>
        </div>
        <div class="col-sm-4">
            <input id="inputCelular" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="" id="inputFone" placeholder="Telefone" pattern="\([0-9]{2}\)[0-9]{5,7}-[0-9]{4,5}$" required>
        </div>
        <div class="col-sm-4">
            <input id="inputEmail" type="email" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="">
        </div>
        <div class="col-sm-12">
            Informações adicionais:
            <input id="complemento" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:55px" value="">
        </div>
        <div class="container" style="margin-top:5px">
             <div style="background-color: #4b4c4e;height: 1px;"></div>
        </div>
        <div>
            <button type="button" id="btnCadastrar" class="btn btn- btn-sm" onclick="window.location='#';" style="width:90px;margin-top:5px; float:right; background-color:green; color:white;line-height:2;">
                    Cadastrar
            <i class="fa fa-floppy"></i>    
            </button>
            <br/>
        </div>
    </div>    
    
    <!-- mascaras -->
            <script>
                $(document).ready(function(){
                  $('#inputCelular').mask("(99)99999-9999");
                  $('#inputTelefone').mask("(99)9999-9999");
                  $('#inputCep').mask("99999-999");
                });
                //ifs do formulário tipo de pessoa
                $('#tipoPessoa').change(function () { 
                        if($('option:selected',this).val()=='F' || $('option:selected',this).val()=='E'){
                            $('#inputCont option[value="9"]').prop('selected', true);
                            $('#inscEstadualHide').css("display", "none");
                        }else{
                            $('#inputCont option[value="9"]').prop('selected', false);
                            $('#inscEstadualHide').css("display", "block");
                        } 
                });
                 $('#inputCont').change(function () { 
                        if($('option:selected',this).val()=='9' || $('option:selected',this).val()=='2'){
                        $('#inscEstadualHide').css("display", "none");
                        }else{
                         $('#inscEstadualHide').css("display", "block");   
                        }
                });
            </script>
<!-- início 3°linha (hr) -->
        
<!-- inicío quinta linha -->
          <!-- inicio linha hr -->
        </div> <!-- fim do row pq tava bugado -->
        <!-- início dos dados reais+imagem -->
        <div class="container" style="margin-top: 8px;height: 1px;height:250px"></div>
        <!-- local que a janela do molde é inserida -->
                <!-- tela de resumo do pedido -->
            <div class='col-sm-12' style="font-size:14px;color:white;padding-top:5px;">
                <!-- fim dos dados do cliente -->
        </div
        <div class='row selected' id='janelaAtual_Resumo'>
        </div>
        
        </div>
      </div> 
      </div>
<!-- footerzin -->
<!-- gradientzinho -->
<div style="height: 3px; background-image: linear-gradient(to right, #FF0009, #EB1E13, #C41910, #FF0009); margin-top: 100px; width: 100%;"></div>
<!-- fim gradient -->
 <footer id="sticky-footer" class="flex-shrink-0 py-4 bg-dark text-white-50">
    <div class="container text-center" style="height:250px">
      <small>Copyright &copy; Personal confecções</small>
    </div>
  </footer>
</body>
</html>