<?php
//essa parte é quem garante a segurança do sistema, e define o que pode ou não ser visto,
//atenção às alterações aqui, copie e cole em TODAS as páginas
session_start();
ini_set('error_reporting', E_ALL); // mesmo resultado de: error_reporting(E_ALL);
ini_set('display_errors', 1);
if (
  isset($_SESSION['usuarioId']) && isset($_SESSION['usuarioNome']) && isset($_SESSION['usuarioNivelAcesso']) &&
  isset($_SESSION['usuarioLogin'])
) {
  //ta autenticado, só deixa continuar brother
} else {
  header("Location:login.php?logado=semacesso");
  echo ('<span>não autenticado</span>');
}
?>
<!-- fim security place -->

<!-- pegando os dados que vou precisar -->
<?php
include_once("php/connect.php");

// Variáveis de data
$query_date = date('Y-m-d');
$primeiroDiaMes = date('Y-m-01', strtotime($query_date));
$ultimoDiaMes = date('Y-m-t', strtotime($query_date));

// Consulta inicial para carregar todos os dados de uma vez
$query_base = "
    SELECT *
    FROM `Esteira`
    WHERE `setorAtual` NOT IN ('16', '9')
       OR (`setorAtual` IN ('16', '9') AND `dataEntrega` BETWEEN '$primeiroDiaMes' AND '$ultimoDiaMes')
";
$resultado_base = mysqli_query($conn, $query_base);
$data = []; // Aqui estou amarzenado todos meus dados
while ($row = mysqli_fetch_assoc($resultado_base)) {
    $data[] = $row;
}

// Processamento - Retângulo 2
$totalLinhas01 = count(array_filter($data, fn($row) => !in_array($row['setorAtual'], ['16', '9'])));

// Processamento - Retângulo 3
$totalLinhas03 = count(array_filter($data, fn($row) => in_array($row['setorAtual'], ['16', '9'])));

// Processamento - Retângulo 4
$totalGeraldeProdutosMês04 = array_reduce($data, function ($carry, $row) {
    if (in_array($row['setorAtual'], ['16', '9'])) {
        $quantidades = json_decode($row['Quantidades']);
        foreach ($quantidades as $q) {
            $carry += intval($q->quantidade);
        }
    }
    return $carry;
}, 0);

// Gráfico 1
$setores = [10, 20, 15, 2, 3, 25, 11, 12, 13, 14, 23, 5, 6, 7, 8, 9];
$grafico1 = array_map(
    fn($setor) => count(array_filter($data, fn($row) => $row['setorAtual'] == $setor)),
    $setores
);

// Gráfico de Aplicações
$dadoAppQtdPecas = array(); // Array para armazenar as quantidades totais por aplicação

// Para cada aplicação, vamos calcular o total de produtos e armazenar no array
$applications = ['APLICAÇÃO VINILPERS', 'APLICAÇÃO BORDADO', 'APLICAÇÃO DTF'];

foreach ($applications as $aplicacao) {
    $totalGeraldeProdutosApp = 0; // Cria a variável que vai receber o total de cada aplicação

    // Consulta para pegar as informações da aplicação atual
    $pegaIdQueryApp = "SELECT Quantidades, pedidoJson FROM `Esteira` 
                       WHERE `pedidoJson` LIKE '%$aplicacao%' 
                       AND `setorAtual` != '16' 
                       AND `setorAtual` != '9'";

    $resultado_pegaApp = mysqli_query($conn, $pegaIdQueryApp); // Faz a consulta SQL

    while ($rowApp = mysqli_fetch_assoc($resultado_pegaApp)) { // Percorre os resultados da consulta
        $totalProdutosAtualApp = 0; // Variável para armazenar o total de produtos da linha

        // Decodifica o JSON das quantidades
        $dadosAppQtd = json_decode($rowApp['Quantidades']);

        // Conta as repetições do termo da aplicação no 'pedidoJson'
        $totalRepeticoesSbtr = substr_count(mb_strtoupper($rowApp['pedidoJson']), strtoupper($aplicacao));

        // Se houver quantidades no JSON, soma os valores
        if (is_array($dadosAppQtd)) {
            foreach ($dadosAppQtd as &$linhasAppQtd) {
                $totalProdutosAtualApp += intval($linhasAppQtd->quantidade); // Soma a quantidade de cada linha
            }
        }

        // Multiplica pelo número de repetições e adiciona ao total geral
        $totalGeraldeProdutosApp += $totalProdutosAtualApp * $totalRepeticoesSbtr;
    }

    // Adiciona o total de produtos da aplicação ao array final
    array_push($dadoAppQtdPecas, $totalGeraldeProdutosApp);
}

// Gráfico 5
$grafico05 = array_map(function ($setor) use ($data) {
    return array_reduce(
        array_filter($data, fn($row) => $row['setorAtual'] == $setor),
        function ($carry, $row) {
            $quantidades = json_decode($row['Quantidades']);
            foreach ($quantidades as $q) {
                $carry += intval($q->quantidade);
            }
            return $carry;
        },
        0
    );
}, $setores);

// Processamento - Gráfico 8
$diasParaBuscar8 = array_map(fn($i) => date('Y-m-d', strtotime("$query_date +$i days")), range(0, 25));
$dadosGrafico8 = [];
foreach ($diasParaBuscar8 as $dia) {
    $totalProdutosAtual8 = array_reduce(
        array_filter($data, fn($row) => $row['dataEntrega'] == $dia),
        function ($carry, $row) {
            $quantidades = json_decode($row['Quantidades']);
            foreach ($quantidades as $q) {
                $carry += intval($q->quantidade);
            }
            return $carry;
        },
        0
    );
    $dadosGrafico8[] = $totalProdutosAtual8;
}

// Gráfico 3 (quantidade de produtos)
$produtosGrafico2 = array();  // Array para armazenar os nomes dos produtos únicos
$dadosGrafico2 = array();    // Array para armazenar as quantidades de cada produto

// Iterando sobre os dados já carregados em $data
foreach ($data as $row) {
    // Extrair o nome do produto, remover "S " no início e converter para maiúsculo
    $produto = mb_strtoupper(explode(' ', str_replace('S ', '', $row['nomeProduto']))[0]);

    // Verifica se o produto já foi adicionado ao array de produtos
    if (!in_array($produto, $produtosGrafico2)) {
        array_push($produtosGrafico2, $produto);  // Adiciona o produto ao array de produtos únicos
        $dadosGrafico2[] = 0;  // Inicializa a quantidade do produto como zero (sem chave associada)
    }

    // Decodificando o JSON de Quantidades
    $quantidades = json_decode($row['Quantidades']);

    // Se o JSON de Quantidades foi decodificado com sucesso
    if ($quantidades) {
        // Somando as quantidades de cada produto
        foreach ($quantidades as $linha) {
            // Verifique se $linha->quantidade existe para evitar erros
            if (isset($linha->quantidade)) {
                // Encontra o índice do produto no array de produtos
                $indiceProduto = array_search($produto, $produtosGrafico2);
                if ($indiceProduto !== false) {
                    // Somando a quantidade no índice correto
                    $dadosGrafico2[$indiceProduto] += intval($linha->quantidade);
                }
            }
        }
    }
}

// Agora, $produtosGrafico2 contém os nomes dos produtos únicos e $dadosGrafico2 contém as quantidades correspondentes (sem os nomes dos produtos)

// Gráfico 9 - Atrasados
$diasParaBuscar9 = array_map(fn($i) => date('Y-m-d', strtotime("$query_date -$i days")), range(0, 10));

// Ordenar as datas em ordem crescente
sort($diasParaBuscar9);

$qtdTotalFinal9 = array_map(
    fn($dia) => count(array_filter($data, fn($row) => 
        $row['dataEntrega'] == $dia && $row['setorAtual'] != '9' && $row['setorAtual'] != '16'
    )),
    $diasParaBuscar9
);

// Gráfico 7 - Quantidade de produtos por cliente ==============================================================================================================================

$produtosGrafico7 = array();
$dadosGrafico7 = array();

// Pegando os nomes dos clientes únicos no array $data
foreach ($data as $row) {
    // Ignora setores 9 e 16
    if ($row['setorAtual'] != '9' && $row['setorAtual'] != '16') {
        $clienteNome = strtoupper($row['cliente']);
        if (!in_array($clienteNome, $produtosGrafico7)) {
            array_push($produtosGrafico7, $clienteNome);
        }
    }
}

$produtosGrafico7 = array_values($produtosGrafico7); // Remove duplicatas e reindexa o array

// Calculando as quantidades de produtos por cliente
foreach ($produtosGrafico7 as $cliente) {
    $totalProdutosAtual7 = 0;
    
    // Iterando sobre os dados do array $data para calcular as quantidades por cliente
    foreach ($data as $row) {
        // Verifica se o cliente corresponde e se o setor não é 9 ou 16
        if (strtoupper($row['cliente']) === $cliente && $row['setorAtual'] != '9' && $row['setorAtual'] != '16') {
            $quantidades = json_decode($row['Quantidades']);
            // Somando as quantidades de produtos para o cliente
            foreach ($quantidades as $produto) {
                $totalProdutosAtual7 += (int) $produto->quantidade;
            }
        }
    }

    // Armazenando o total de produtos para o cliente no array
    array_push($dadosGrafico7, $totalProdutosAtual7);
}

// Outputs (exemplo)
//echo json_encode([
    //'retangulo2' => $totalLinhas01,
    //'retangulo3' => $totalLinhas03,
    //'retangulo4' => $totalGeraldeProdutosMês04,
    //'grafico1' => $grafico1,
    //'aplicacoes' => $dadoAppQtdPecas,
    //'grafico5' => $grafico05,
    //'grafico8' => $dadosGrafico8,
    //'produtos' => $produtosGrafico2,
    //'qtdProdutos' => $dadosGrafico2,
    //'clientes' => $clientesGrafico7,
    //'atrasados' => $qtdTotalFinal9,
//]);
?>
<!doctype html>
<html lang="pt-br">
<head>
<link rel="icon" href="img/icon.png">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Personal Produção</title>
<link rel="icon" href="img/favicon.png">
<!-- bootstrap e css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<!-- menu -->
<?php include 'menu.php';?>

<div class="" style="padding: 3px 0px 3px 0px;background-image: url('img/wall1.jpg');background-position: center top;background-size: 100% auto;">
<div class='row'>
    <div class='col-sm-3' style='color:white;text-align:center;padding:8px'>
        <div style='background-color:#00000085;border-radius:10px'>
            PEÇAS EM PRODUÇÃO <br>
            <span style='color:#00FF00;font-size:70px;font-weight:bold'><?php echo(array_sum($grafico05));?></span>
        </div>
    </div>
    <div class='col-sm-3' style='color:white;text-align:center;padding:8px'>
        <div style='background-color:#00000085;border-radius:10px'>
            PEDIDOS EM PRODUÇÃO <br>
            <span style='color:#00FF00;font-size:70px;font-weight:bold'><?php echo($totalLinhas01); ?></span>
        </div>
    </div>
    <div class='col-sm-3' style='color:white;text-align:center;padding:8px'>
        <div style='background-color:#00000085;border-radius:10px'>
            TOTAL DE PEDIDOS DO MÊS <br>
            <span style='color:#00FF00;font-size:70px;font-weight:bold'><?php echo($totalLinhas03);?></span>
        </div>
    </div>
    <div class='col-sm-3' style='color:white;text-align:center;padding:8px'>
        <div style='background-color:#00000085;border-radius:10px'>
            PEÇAS FABRICADAS NO MÊS <br>
            <span style='color:#00FF00;font-size:70px;font-weight:bold'><?php echo($totalGeraldeProdutosMês04);?></span>
        </div>
    </div>
    <!-- gráfico 1 -->
    <div class='col-sm-4'>
        <div class="child" >
            <canvas id="pieChart" style="background:#00000085;padding:15px;border-radius:12px" width="100%" height="100%"></canvas>
        </div>
    </div>
    <!-- gráfico de aplicações -->    
    <div class='col-sm-4'>
        <canvas id="doughnut-chart-aplicacoes" width="150" height="150" style="background:#00000085;padding:15px;border-radius:12px"></canvas>
    </div>
    <!-- gráfico 2-->    
    <div class='col-sm-4'>
        <canvas id="doughnut-chart" height='350' style="background:#00000085;padding:15px;border-radius:12px"></canvas>
    </div>

    <div class='col-sm-4' style="padding:15px;border-radius:12px;">
        <div class="child2" style='background-color:#0000002e;border-radius:12px'>
            <canvas id="pie-chart" width="900" height="550" style="background:#00000085;padding:15px;border-radius:12px"></canvas>
        </div>
    </div>
    
    <!-- gráfico de produtos atrasados -->
    <div class='col-sm-4' style="padding:15px; border-radius:12px;">
        <canvas id="grafico-atrasados" width="600" height="350" style="background:#00000085;padding:15px;border-radius:12px"></canvas>
    </div>
    
    <div class='col-sm-4' style="padding:15px;border-radius:12px;">
        <div class="child3" style='background-color:#0000002e;border-radius:12px'>
            <canvas id="line-chart" width="600" height="550" style="background:#00000085;padding:15px;border-radius:12px"></canvas>
        </div>
    </div>
<!-- gráfico de qtd de produto por cliente -->
    <div class='col-sm-12'>
        <div class="child4" style='background-color:#0000002e;border-radius:12px;margin-left:-155px'>
            <canvas id="curve-chart" width="600" height="550" style="background:#00000085;padding:15px;border-radius:12px"></canvas>
        </div>
    </div>
    
</div>   
</div>
</div></div></div>
<!-- fim teste -->
<!-- footer -->
<div style="height: 3px; background-image: linear-gradient(to right, #FF0009, #EB1E13, #C41910, #FF0009); margin-top: 0px; width: 100%;"></div>
<footer class="bg-body-tertiary text-center text-lg-start">
  <!-- Copyright -->
  <div class="text-center p-3" style="background-color: black">
    © 2024 Copyright PersonalConfeccoes
  </div>
  <!-- Copyright -->
</footer>

<!-- vuejs e jquery para controle ajax e js -->  
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.js"></script>
  <script src="js/jquery.js"></script>


<!-- chart js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<!-- script de click no gráfico 1 -->
<script>
var dadosGrafico1 = <?php echo json_encode($grafico1); ?>;
//criando as posições chaveadas
let setores = new Object();
//Populate data
setores["Fase inicial"] = 10;
setores["Produção"] = 20;
setores["Modelagem"] = 15;
setores["Sublimação"] = 2;
setores["Corte malharia"] = 3;
setores["Corte tecido"] = 25;
setores["Estamparia"] = 11;
setores["Serigrafia"] = 12;
setores["Bordado"] = 13;
setores["Vinilpers"] = 14;
setores["DTF"] = 23;
setores["Atelier"] = 5;
setores["Passadeiras"] = 6;
setores["Revisão"] = 7;
setores["Embalagem"] = 8;
setores["Concluído"] = 9;

var canvasP = document.getElementById("pieChart");
var ctxP = canvasP.getContext('2d');
  new Chart(ctxP, {
    type: 'bar',
    data: {
      labels: ['Fase inicial','Produção','Modelagem','Sublimação', 'Corte malharia','Corte tecido','Estamparia','Serigrafia','Bordado','Vinilpers','DTF','Atelier', 'Passadeiras','Revisão','Embalagem','Concluído'],
      datasets: [{
        label: '#Ordens de produção',
        data: dadosGrafico1,
        borderColor: '#1fd4f3',
        backgroundColor: ["#0093FF", "#0B00E2","#005AFF","#00B7FF","#00DCFF","#0093FF","#00B7FF","#005AFF","#00B7FF","#005AFF"],
        borderWidth: 1
      }]
    },
    options: {
      maintainAspectRatio: false,
      responsive: true,
      onClick: (event, elements, chart) => {
          if (elements[0]){
             const i = elements[0].index;
             //alert(chart.data.labels[i] + ': ' + chart.data.datasets[0].data[i]); 
             window.location.href = 'movimentacao.php?setorAtual='+setores[chart.data.labels[i]];
          }
      },
      scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
    }
  });
</script>

<!-- gráfico de aplicações -->
<script>
var dadosGraficoApp = <?php echo json_encode($dadoAppQtdPecas); ?>;
console.log(dadosGraficoApp);
new Chart(document.getElementById("doughnut-chart-aplicacoes"),{
    type: 'doughnut',
    data: {
      labels: ["Vinilpers", "Bordado","DTF"],
      datasets: [
        {
          label: "Aplicações (por unidade)",
          backgroundColor:  ["#0093FF", "#0B00E2","#005AFF","#00B7FF","#00DCFF","#0093FF","#00B7FF","#005AFF","#00B7FF","#005AFF"],
          data: dadosGraficoApp
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      responsive: true,
      title: {
        display: true,
        text: 'Tipos de Aplicações (por peça)'
      },
      scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
    }
});
</script>

<!-- gráfico 2 -->
<script>
    var dadosGrafico05 = <?php echo json_encode($grafico05);?>;
    //console.log(dadosGrafico05);
    new Chart(document.getElementById("doughnut-chart"),{
    type: 'bar',
    data: {
        labels: ['Fase inicial','Produção','Modelagem','Sublimação','Corte malharia','Corte tecido','Estamparia','Serigrafia','Bordado','Vinilpers','DTF','Atelier', 'Passadeiras','Revisão','Embalagem','Concluído'],
        datasets: [{
          label: 'Peças(unidades)',
          backgroundColor: ["#0093FF", "#0B00E2","#005AFF","#00B7FF","#00DCFF","#0093FF","#00B7FF","#FF6166","#00B7FF","#005AFF"],
          data: dadosGrafico05,
        }],
      },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        indexAxis: 'y', //<-- here
        responsive: true,
        scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
      }
    });
</script>
<!-- gráfico 3 -->
<script>
var Grafico2Prd = <?php echo json_encode($produtosGrafico2); ?>;
var Grafico2Qtd = <?php echo json_encode($dadosGrafico2); ?>;

console.log(Grafico2Qtd);
console.log(Grafico2Prd);
new Chart(document.getElementById("pie-chart"),{
    type: 'pie',
    data: {
      labels: Grafico2Prd,
      datasets: [{
        label: "Em produção (unidades)",
        backgroundColor: ["#FF69B4", "#00FF00","#7FFFD4","#00FFFF","#00BFFF","#000080","#808080","#DC143C","#FF8C00","#FFFF00","#87CEEB","#00BFFF","#F0F8FF","#F0E68C"],
        data: Grafico2Qtd
      }]
    },
    options: {
      maintainAspectRatio: false,
      responsive: true,
      title: {
        display: true,
        text: 'QUANTIDADE POR PRODUTO'
      },
      scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
    }
});
</script>

<!-- gráfico 4 -->
<script>
  var diasGrafico3 = <?php echo json_encode($diasParaBuscar8); ?>;
  var dadosGrafico3 = <?php echo json_encode($dadosGrafico8); ?>;
  
  new Chart(document.getElementById("line-chart"), {
  type: 'line',
  data: {
    labels: diasGrafico3,
    datasets: [{ 
        data: dadosGrafico3,
        label: "Qtd. de itens para entregar",
        borderColor: "#00FF00",
        fill: false
      }
    ]
  },
  options: {
    maintainAspectRatio: false,
    responsive: true,
    onClick: (event, elements, chart) => {
          if (elements[0]){
             const i = elements[0].index;
             //alert(chart.data.labels[i] + ': ' + chart.data.datasets[0].data[i]); 
             window.location.href = 'movimentacao.php?dataEntrega='+chart.data.labels[i];
          }
    },
    title: {
      display: true,
      text: 'World population per region (in millions)'
    },
    scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
  }
});
</script>

<!-- gráfico de qtd de produtos por cliente --> 
<script>
  var diasGrafico7 = <?php echo json_encode($produtosGrafico7); ?>;
  var dadosGrafico7 = <?php echo json_encode($dadosGrafico7); ?>;
 
  new Chart(document.getElementById("curve-chart"), {
  type: 'line',
  data: {
    labels: diasGrafico7,
    datasets: [{ 
        data: dadosGrafico7,
        label: "Qtd. de itens por cliente",
        borderColor: "#00FFFF",
        fill: false
      }
    ]
  },
  options: {
    maintainAspectRatio: false,
    responsive: true,
    onClick: (event, elements, chart) => {
          if (elements[0]){
             const i = elements[0].index;
             //alert(chart.data.labels[i] + ': ' + chart.data.datasets[0].data[i]); 
             window.location.href = 'movimentacao.php?nome='+chart.data.labels[i];
          }
    },
    title: {
      display: true,
      text: 'World population per region (in millions)'
    },
    scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    },
    elements: {
        line: {
            tension: 0.25
        }
    }
  }
});
</script>

<!-- atrasados -->
<script>
  var dadosGrafico9 = <?php echo json_encode($diasParaBuscar9); ?>;
  var dadosQtd9 = <?php echo json_encode($qtdTotalFinal9); ?>;
  
    
  new Chart(document.getElementById("grafico-atrasados"), {
  type: 'line',
  data: {
    labels: dadosGrafico9,
    datasets: [{ 
        data: dadosQtd9,
        label: "Qtd. de op's não concluídas",
        borderColor: "#00FF00",
        fill: false
      }
    ]
  },
  options: {
    maintainAspectRatio: false,
    responsive: true,
    onClick: (event, elements, chart) => {
          if (elements[0]){
             const i = elements[0].index;
             //alert(chart.data.labels[i] + ': ' + chart.data.datasets[0].data[i]); 
             window.location.href = 'movimentacao.php?dataEntrega='+chart.data.labels[i];
          }
    },
    title: {
      display: true,
      text: 'World population per region (in millions)'
    },
    scales: {
      y: {
        grid: {
          color: '#001A1A'
        }
      },
      x: {
        grid: {
          color: '#001A1A'
        }
      }
    }
  }
});
</script>

</body>
</html>

<style type="text/css">
  @font-face {
  font-family: "Helvetica-bold";
  src: url("font/Helvetica-Bold-Font.ttf");
  }

  body{
    background-color: #18191a; /* <!-- #606060 --> */
    /*background: linear-gradient(-40deg, black 18%,#18191a 48%);
    font-family: 'Helvetica-bold';

  }
  /* Modify the background color */
         
        .navbar-custom {
            background-color: black;
        }
  /* Modify brand and text color a */
         
        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-text {
            color: White;
        }

  input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
  .child {
  height: 100%;
  }
  }
</style>