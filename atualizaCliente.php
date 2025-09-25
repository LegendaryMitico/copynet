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
        <script>
        //variáveis globais :)
        var idClienteAtual;
        var NomeClienteAtual;
        var codigoCliente;
        $(document).ready(function(){
                      $("#btnCadastrar").click(function(e){
                                //alert($(this).attr('id'));
                                $.ajax({
                                    type: "POST",
                                    url: 'ajax/atualizaCliente.php',
                                    data: jQuery.param({idCli: idClienteAtual,
                                                        nomeCliente: NomeClienteAtual,
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
                                                        codigoCl: codigoCliente
                                    }) ,
                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                    success: function(data)
                                    {
                                        alert(data+"contato atualizado");
                                    }
                                });
                      });
        });
        </script>
        
        <div class="row" style="color:white">
        <div class="col-sm-12">
            Atualizar dados do cliente
            <div class="container" style="background-color: yellow;height: 1px;margin-bottom:10px;margin-top:5px"></div>
        </div>
        <div class="col-sm-6">
        Nome<span style="color:red">*</span>
        <div>
        <button type="button" class="btn btn- btn-sm" id="buscaClienteBtn" style="height: 25px;width:32px;line-height: 1;border: 1px solid grey;display:inline-block;margin-top:1px;color:green;float: right;z-index:999" alt="pdf">
               <i class="fa fa-paper-plane" aria-hidden="true"></i>
        </button>
         <input type="text" id="myInput" style="width:340px;background-color:#3a3b3c;color:white;border-radius:5px;height:25px;position:absolute;">
        </div>
        </div>
        <div class="col-sm-6">
        Fantasia
        <input type="text" id="nomeFantasia" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value="">
        </div>
        <script>
                $("#myInput").bind("change paste keyup", function() {
                   var qtdLetras = $(this).val().length;
                   if(qtdLetras%4==1 && qtdLetras!=1){
                       var countries = [];
                        $.ajax({
                                    type: "POST",
                                    url: 'ajax/buscaContatosId.php',
                                    data: jQuery.param({nome: document.getElementById('myInput').value,
                                    }) ,
                                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                                    success: function(data)
                                    {
                                        console.log('data:'+data);
                                        countries = data.replace('[','');
                                        countries = countries.replace(']','');
                                        countries = countries.replaceAll('"','');
                                        countries = countries.split(',');
                                        console.log('countries:'+countries);
                                        autocomplete(document.getElementById("myInput"), countries); //update no array
                                    }
                        });
                   }
                });
            </script>
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
            <!-- <input id="complemento" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:25px" value=""> -->
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
        <div class="container" style="margin-top:5px">
             <div style="background-color: #4b4c4e;height: 1px;"></div>
        </div>
        <div class="col sm-12">
            Informações adicionais
            <input id="complemento" type="text" style="width:100%;background-color:#3a3b3c;color:white;border-radius:5px;height:65px" value="">
        </div>
        <div>
            <button type="submit" id="btnCadastrar" class="btn btn- btn-sm" onclick="window.location='#';" style="width:90px;margin-top:5px; float:right; background-color:yellow; color:black;line-height:2;">
                    Atualizar
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
                        $('#inscEstadual').val("");
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

<!---------------------------------------------------- !> PARTE QUE AUTO COMPLETA, NÃO MECHA :) -->
<script>
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
      a.style.marginTop = '25px';
      a.style.position = "absolute";
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
          b.style.width = '390px';
          b.style.marginLeft = '0px';
          
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
            if(idClienteAtual!=''){
            console.log(idClienteAtual);
            $("#myInput").val(NomeClienteAtual);
            $("#buscaClienteBtn").css("background-color", "green");
            $("#buscaClienteBtn").css("color", "white");
            
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
                    console.log(dadosCliente);
                    $('#nomeFantasia').val(dadosCliente[1]);
                    //preenchendo o tipo de pessoa (é um select)
                    $valorRecebido = dadosCliente[2];
                    $('#tipoPessoa option[value="'+$valorRecebido+'"]').prop('selected', true);
                    $('#clienteCpf').val(dadosCliente[3]);
                    //preenchendo o tipo de contribuinte
                    $valorContrib = dadosCliente[4];
                    $('#inputCont option[value="'+$valorContrib+'"]').prop('selected', true);
                    $('#inscEstadual').val(dadosCliente[5]);
                    $('#inputCep').val(dadosCliente[6]);
                    //prenchendo o UF
                    $valorUfRecebido = dadosCliente[8];
                    $('#inputUf option[value="'+$valorUfRecebido+'"]').prop('selected', true);
                    $('#inputCidade').val(dadosCliente[9]);
                    $('#inputBairro').val(dadosCliente[10]);
                    $('#endereco').val(dadosCliente[7]);
                    $('#numero').val(dadosCliente[11]);
                    $('#complemento').val(dadosCliente[12]);
                    $('#inputTelefone').val(dadosCliente[13]);
                    $('#inputCelular').val(dadosCliente[14]);
                    $('#inputEmail').val(dadosCliente[15]);
                    codigoCliente = dadosCliente[16];
                }
            });
            }
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
                                