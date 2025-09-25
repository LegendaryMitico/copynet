<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!-- jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    </head>
    <body style='background-image:linear-gradient(to bottom right, #1c0e0e, black)'>
        <!-- menu -->
       <?php session_start();include 'menu.php';?>
       <div class='container'>
        <div class='row'>
                    <div class='col-sm-1' style='color:white'>
                        Tecido/Malha:
                        <select onchange="javascript:mudouTecido('selectPai')" id='selectPai' required="required" class="form-select" aria-label="Default select example" style="color:grey; font-size:12px;width:150px" name="tipo_tecido">
                                <option disabled selected>--TECIDOS/MALHAS--</option>
                                <option value="BRIM FLEX/RIP STOP,16104161271">Brim flex/rip stop - T27</option>
                                <option value="BRIM LEVE,16104161267">Brim leve - T25</option>
                                <option value="BRIM PESADO,16104161269">Brim pesado - T26</option>
                                <option value="CASIMIRA,16104161274">Casimira - T28</option>
                                <option value="CHIFFON,16104161343">Chiffon - T3</option>
                                <option value="CREPE,16104161342">Crepe - T29</option>
                                <option value="DENIM,16104161345">Denim - T5</option>
                                <option value="GABARDINE,16104161347">Gabardine - T6</option>
                                <option value="MICROFIBRA,16104161348">Microfibra - T7</option>
                                <option value="OXFORD,16104161350">Oxford - T9</option>
                                <option value="OXFORDINE,16104161258">Oxfordine - T10</option>
                                <option value="PANATEL,16104161259">Panatel - T11</option>
                                <option value="SARJA,16104161260">Sarja - T12</option>
                                <option value="TACTEL,16104161261">Tactel - T14</option>
                                <option value="TRICOLINE,16104161262">Tricoline - T15</option>
                                <option value="TRICOLINE FUSTÃO,16104161266">Tricoline fustÃo - T24</option>
                                <option value="TULE,16104161263">Tule - T16</option>
                                <option value="TWAY,16104161264">Tway - T17</option>
                                <option value="TWILL,16104161344">Twill - T30</option>
                                <option value="AERODRY,16104622242">Aerodry - M1</option>
                                <option value="CACHARREL,16104622246">Cacharrel - M3</option>
                                <option value="DRY PLUS,16104622248">Dry plus - M4</option>
                                <option value="DRYFIT,16104622256">Dryfit - M9</option>
                                <option value="HELANCA COLEGIAL,16104622250">Helanca colegial - M5</option>
                                <option value="MEIA MALHA INFINITY FIO 30,16104622244">Meia malha infinity fio 30 - M2</option>
                                <option value="MEIA MALHA PES,16104622252">Meia malha pes - M6</option>
                                <option value="MEIA MALHA PV,16104622253">Meia malha pv - M7</option>
                                <option value="MOLECOTTON,16104622265">Molecotton - M16</option>
                                <option value="MOLETINHO,16104622263">Moletinho - M15</option>
                                <option value="MOLETOM,16104622266">Moletom - T8</option>
                                <option value="PIQUET PA,16104622254">Piquet pa - M8</option>
                                <option value="SUPPLEX,16104161257">Supplex - M13</option>
                        </select>
                        
                        Base Flag:
                        <select onchange="javascript:mudouTecido('selectBase')" id='selectBase' required="required" class="form-select" aria-label="Default select example" style="color:grey; font-size:12px;width:150px" name="tipo_tecido">
                            <option value="" disabled selected>--SELECIONE--</option>
                            <option value="Polietileno,16091115197">Base polietileno - Cor Branca</option>
                        </select>
                        
                        Golas:
                        <select onchange="javascript:mudouTecido('golaPolo')" id='golaPolo' required="required" class="form-select" aria-label="Default select example" style="color:grey; font-size:12px;width:150px" name="tipo_tecido">
                            <option value="" disabled selected>--SELECIONE--</option>
                            <option value="Golas,16105740571">Golas Pólo</option>
                        </select>
                        
                        Ribanas:
                        <select onchange="javascript:mudouTecido('ribanas')" id='ribanas' required="required" class="form-select" aria-label="Default select example" style="color:grey; font-size:12px;width:150px" name="tipo_tecido">
                            <option value="" disabled selected>--SELECIONE--</option>
                            <option value="ribanas,16106371146">Ribanas PP/PV</option>
                            <option value="ribanas,16105740586">Ribanas</option>
                        </select>
                        
                        Punhos:
                        <select onchange="javascript:mudouTecido('punhos')" id='punhos' required="required" class="form-select" aria-label="Default select example" style="color:grey; font-size:12px;width:150px" name="tipo_tecido">
                            <option value="" disabled selected>--SELECIONE--</option>
                            <option value="punhos,16105740573">PUNHO POLIÉSTER</option>
                        </select>
                        
                        
                    </div>
                    <div class='col-sm-11' style='height:650px'>
                        <canvas id="pieChart" style="padding:15px;border-radius:12px"></canvas>
                    </div>
        </div>
    </div>
    </body>
</html>
<?php
    require('php/connect.php');
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    $token = $resultadot["valor"];
   
    $curl = curl_init();
    if(isset($_GET['idCategoriaPai'])){
       $idPai = $_GET['idCategoriaPai'];  
    }else{
       $idPai = '16104161271'; 
    }
    
    //$idPai = '16104161262';
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.bling.com.br/Api/v3/produtos/variacoes/'.$idPai,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'Authorization: Bearer '.$token
    ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
?>  

<script>
    function mudouTecido(idNome){
        var idSelectAtual = '#'+idNome;
        var novoTecidoId = ($('option:selected',idSelectAtual).val()).split(',')[1];
        console.log(novoTecidoId);
        location.href = "meuEstoque.php?idCategoriaPai="+novoTecidoId;
    }
</script>

<script>
     var dadosTecido = JSON.parse(<?php echo json_encode($response); ?>);
     var dadosNome = [];
     var dadosCodigos = "?";
     console.log(dadosTecido.data.variacoes);
     $.each(dadosTecido.data.variacoes, function(key, value){
        dadosNome.push(value.nome);
        dadosCodigos += "&idsProdutos[]="+value.id;
     });
     console.log(dadosNome);
     console.log(dadosCodigos);
     //e aqui fazendo o ajax para recuperar todas as quantidades do estoque
     var estoqueSaldo = [];
     $.ajax({
                type: "POST",
                url: 'ajax/puxaEstoqueFilhosProduto.php',
                data: jQuery.param({codigosProdutos: dadosCodigos
                }) ,
                contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                success: function(data)
                {
                    var RetornoEstoque = JSON.parse(JSON.parse(data));
                    console.log(RetornoEstoque); //saldoFisicoTotal
                    $.each(RetornoEstoque.data, function(key, value){
                        estoqueSaldo.push(value.saldoFisicoTotal);
                    });
                    console.log(estoqueSaldo);
                    
                    //gerando o gráfico
                    var canvasP = document.getElementById("pieChart");
                    var ctxP = canvasP.getContext('2d');
                    new Chart(ctxP, {
                    type: 'bar',
                    data: {
                      labels: dadosNome,
                      datasets: [{
                        label: '#Quantidade em estoque',
                        data: estoqueSaldo,
                        borderColor: 'red',
                        backgroundColor: 'red',
                        borderWidth: 1
                      }]
                    },
                    options: {
                      maintainAspectRatio: false,
                      responsive: true,
                      scales: {
                      y: {
                        grid: {
                          color: '#1c0e0e'
                        }
                      },
                      x: {
                        grid: {
                          color: '#1c0e0e'
                        }
                      }
                    }
                    }
                  });
                  //fim dados gráfico
                }
      });
      
</script>


