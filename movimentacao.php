<?php
//essa parte é quem garante a segurança do sistema, e define o que pode ou não ser visto,
//atenção às alterações aqui, copie e cole em TODAS as páginas
session_start();
date_default_timezone_set('America/Manaus');
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
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <!-- bootstrap e css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <!-- chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- font awersome -->
    <script src="https://kit.fontawesome.com/704200b128.js" crossorigin="anonymous"></script>

    <style type="text/css">
      @font-face {
        font-family: 'Montserrat', sans-serif;
        src: url("font/Montserrat-Light.ttf");
      }

      body {
        background-color: white;
        /* <!-- #606060 --> */


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

      .upscaleMouse:hover {
        transform: scale(1.025);
        /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        -webkit-transition: transform 0.20s ease-in-out;
        box-shadow: 3px 4px 6px 0px #3a3b3c;
      }

      .btn {
        --bs-btn-padding-y: 2;
      }

      .btn-exibe-historico, .btn-obs-historico {
    background-color: white;
    height: 22px;
    border: 1px solid grey;
    line-height: 2.2;
}

.setor-select {
    height: 22px;
    font-size: 12px;
    width: 66%;
    text-align: center;
    line-height: 1.25;
    margin-right: 5px;
    margin-top: 18px;
}

.btn-obs-historico {
  margin-right: 5px
}

.paginacao {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: center;
    padding: 40px 0;
}

.pagination {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
}

    </style>
    <!-- página -->
    <?php include 'menu.php'; ?>
    <!--fim nav -->

    <div class="container-fluid" style="padding: 3px 0px 3px 0px;background-color: white;border-radius:12px;">
      <div class="row" style="background-color: #18191a; margin-top: -3px; height: 50px;">
        <div class="col-sm-1"></div>
        <div class="col-sm-1">
          <a href="/movimentacao.php?setorAtual=19&pedidoExpresso=PEDIDO%20EXPRESSO" style="
              color: black;
              background: white;
              text-decoration: none;
              font-size: 12px;
              padding: 2px 23px;
              display: block;
              margin-top: 13px;
              border: solid 1px #6e6e6e;
              border-radius: 3px;
          ">EXPRESSO</a>
      </div>

        <div class="col-sm-1">
          <?php
                  $limite = 50; // Número de registros por página
                  $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; 
                  $offset = ($pagina - 1) * $limite; // Calcula o deslocamento (OFFSET)


          echo ("<select id='selectAplicacao' onchange='alteraSetor()' style='height: 25px; font-size: 12px;width: 100%;text-align:center;margin-top:3px;line-height:1.25;margin-right:5px;float:right;border-radius:3px;margin-top:12px;'>");
          echo ("<option value='' disabled>--APLICAÇÕES--</option>");
          $aplicacoes = [['TODOS', ''], ['BORDADO', 'Aplicação bordado'], ['VINILPERS', 'Aplicação vinilpers'], ['DTF', 'Aplicação DTF']];
          foreach ($aplicacoes as $valorLista) {
            $atributoApp = '';
            if ($_GET['aplicacaoAtual'] == $valorLista[1]) {
              $atributoApp = 'selected';
            }
            echo ("<option value='$valorLista[1]' $atributoApp>$valorLista[0]</option>");
          }

          echo ("</select>");
          ?>
        </div>
        <div class="col-sm-1">
          <?php
          echo ("<select id='selectSetor' onChange='alteraSetor()' style='height: 25px; font-size: 12px;width: 100%;text-align:center;margin-top:3px;line-height:1.25;margin-right:5px;float:right;border-radius:3px;margin-top:12px;'><option value='19' >--- DEPARTAMENTOS ---</option>");
          //gerando o select ======================================================
          $setores = [['10.FASE INICIAL', 10], ['20.PRODUÇÃO', 20], ['22.PRONTA ENTREGA', 22], ['15.MODELAGEM', 15], ['2.SUBLIMAÇÃO', 2], ['3.CORTE MALHARIA', 3], ['24.CORTE TECIDO', 25], ['11.ESTAMPARIA', 11], ['12.PEQUENOS FORMATOS', 12], ['13.BORDADO', 13], ['14.VINILPERS', 14], ['23.DTF', 23], ['5.ATELIÊ', 5], ['6.PASSADEIRAS', 6], ['7.REVISÃO', 7], ['8.EMBALAGEM', 8], ['24.TERCEIRIZADO', 24], ['9.CONCLUÍDO', 9], ['--16.ENTREGUE--', 16], ['--PARADOS--', 0]];
          print_r([2]);
          foreach ($setores as $valor) {
            $atributo = '';
            if ($_GET['setorAtual'] == $valor[1]) {
              $atributo = 'selected';
            }
            echo ("<option value='$valor[1]' $atributo>$valor[0]</option>");
          }
          echo ("</select>");
          ?>
        </div>

        <div class="col-sm-1">
          <select id="produto" name="produto" 
                  style="height: 25px; font-size: 12px; width: 100%; text-align:center; margin-top:12px; line-height:1.25; margin-right:5px; float:right; border-radius:3px;"
                  onchange="if(this.value) window.location.href='?nomeProduto=' + encodeURIComponent(this.value)">
            <option selected disabled>Escolha o produto</option>
            <option value="Camisa">Camisa</option>
            <option value="Camiseta">Camiseta</option>
            <option value="Regata">Regata</option>
            <option value="Macacão">Macacão</option>
            <option value="Saia">Saia</option>
            <option value="Short">Short</option>
            <option value="Calça">Calça</option>
            <option value="Calção">Calção</option>
            <option value="Bermuda">Bermuda</option>
            <option value="Vestido">Vestido</option>
            <option value="Bolsa">Bolsa</option>
            <option value="Wind Flag">Wind Flag</option>
            <option value="Bandeira">Bandeira</option>
            <option value="Painel">Painel</option>
          </select>
        </div>

        <div class="col-sm-7" style='color:white;padding-top:-15px;margin-left:-25px'>

          <!-- data para filtragem -->
          | Data de entrega:
          <input type="date" id="dataFiltroInput" onChange='buscaDataEntrega()' style="color: white; width: 105px;background-color:#3a3b3c;line-height:1.85;border:1px solid black;border-radius:5px;font-size:14px" value='<?php echo ($_GET["dataEntrega"]); ?>'> |

          <script>
            function buscaDataEntrega() {
              console.log($("#dataFiltroInput").val());
              location.href = "movimentacao.php?dataEntrega=" + String($("#dataFiltroInput").val());
            }
          </script>

          <input type="radio" id="css" name="tipoBusca" value="idOp">
          <span style='margin-top:5px'>Op</span>

          <input type="radio" id="css" name="tipoBusca" value="Nome" checked>
          <span style='margin-top:5px'>Nome cliente</span>

          <!-- botão de busca -->
          <input type="search" class="form-control rounded" placeholder="Pesquisar..." id='inputDadosBusca' style='width:150px;height:25px;display:inline-block;line-height:1.5' />
          <button type="button" class="btn btn-dark" onClick='buscaPedido()' style="width:80px; height: 30px; margin-bottom: 15px; margin-top: 10px; line-height: 1;display:inline-block">
            Buscar
          </button>
        </div>
      </div>

      <div class="container-fluid" style='display:inline-block'>
        <div class="row" style='border:1px solid grey;border-bottom-left-radius:12px;border-bottom-right-radius:12px;padding:10px'>
          <!-- printando todos os pedidos aqui -->
          <?php
          include_once("php/connect.php");

          //parte importante que define o que deve ser ou não buscao, muito importante ===================
          if (isset($_GET['setorAtual'])) 
          { //se for pelo select
            $query = "SELECT * FROM Esteira WHERE `setorAtual` = '$_GET[setorAtual]' ORDER BY DATE_FORMAT(dataEntrega,'%Y-%m-%d') ASC LIMIT $limite OFFSET $offset";
            if (isset($_GET['aplicacaoAtual'])) {
              $query = "SELECT * FROM Esteira WHERE `setorAtual` = '$_GET[setorAtual]' AND `pedidoJson` LIKE '%$_GET[aplicacaoAtual]%' ORDER BY DATE_FORMAT(dataEntrega,'%Y-%m-%d') ASC LIMIT $limite OFFSET $offset";
            }
            
            //echo($query);
            if ($_GET['setorAtual'] == 19) {
              $query = "SELECT * FROM Esteira WHERE `setorAtual` != '16' AND `setorAtual` != '9' ORDER BY DATE_FORMAT(dataEntrega,'%Y-%m-%d') ASC LIMIT $limite OFFSET $offset";
              if (isset($_GET['aplicacaoAtual'])) {
                $query = "SELECT * FROM Esteira WHERE `setorAtual` != '16' AND `setorAtual` != '9' AND `pedidoJson` LIKE '%$_GET[aplicacaoAtual]%' ORDER BY DATE_FORMAT(dataEntrega,'%Y-%m-%d') ASC";
              }
            }
            if ($_GET['setorAtual'] == 0) {
              $query = "SELECT * FROM Esteira WHERE `setorAtual` != '16' AND `setorAtual` != '9' AND `status` = 'parado' ORDER BY DATE_FORMAT(dataEntrega,'%Y-%m-%d') ASC";
            }

            if (isset($_GET['pedidoExpresso'])) {
              $query = "SELECT * FROM Esteira WHERE `setorAtual` != '16' AND `setorAtual` != '9' 
              AND `pedidoJson` LIKE '%\"pedidoExpresso\":\"$_GET[pedidoExpresso]\"%' 
              ORDER BY DATE_FORMAT(dataEntrega, '%Y-%m-%d') ASC";
          }
            //echo($query);
          } else {
            $query = "SELECT * FROM Esteira WHERE `setorAtual` != '16' AND `setorAtual` != '9' ORDER BY DATE_FORMAT(dataEntrega,'%Y-%m-%d') ASC LIMIT $limite OFFSET $offset";
          }

          //e aqu ise for pela caixa de pesquisa:=========================================================================================
          if (isset($_GET['idOp'])) { //se for pelo select
            $query = "SELECT * FROM Esteira WHERE `idGeral` = $_GET[idOp]";
          } else if (isset($_GET['nome'])) {
            $query = "SELECT * FROM Esteira WHERE `cliente` LIKE '%$_GET[nome]%' ORDER BY DATE_FORMAT(dataEntrega,'%Y-%m-%d') ASC";

          // GAMBIARA PARA BUSCAR AMOSTRA
          } else if (isset($_GET['obs'])) {
            $query = "SELECT * FROM Esteira WHERE `pedidoJson` LIKE '%$_GET[obs]%' ORDER BY DATE_FORMAT(dataEntrega,'%Y-%m-%d') ASC";
          } else if (isset($_GET['dataEntrega'])) {
            $query = "SELECT * FROM Esteira WHERE `dataEntrega` LIKE '%$_GET[dataEntrega]%' ORDER BY DATE_FORMAT(dataEntrega,'%Y-%m-%d') ASC";
          
          // FAZ A BUSCA POR CATEGORIA DO PRODUTO
          } else if (isset($_GET['nomeProduto'])) {
            $query = "SELECT * FROM Esteira WHERE `setorAtual` != '16' AND `setorAtual` != '9' && `nomeProduto` LIKE '%$_GET[nomeProduto]%' ORDER BY DATE_FORMAT(dataEntrega,'%Y-%m-%d') ASC LIMIT $limite OFFSET $offset";
          } else if (isset($_GET['dataEntrega'])) {
            $query = "SELECT * FROM Esteira WHERE `dataEntrega` LIKE '%$_GET[dataEntrega]%' ORDER BY DATE_FORMAT(dataEntrega,'%Y-%m-%d') ASC  ASC LIMIT $limite OFFSET $offset";
          }

          // Construção dinâmica da consulta de contagem total
          $query_count = "SELECT COUNT(*) AS total FROM Esteira WHERE `setorAtual` != '16' AND `setorAtual` != '9'";

          if (isset($_GET['setorAtual'])) {
              $setorAtual = $_GET['setorAtual'];
              $query_count = "SELECT COUNT(*) AS total FROM Esteira WHERE `setorAtual` = '$setorAtual'";
          }

          if (isset($_GET['nomeProduto'])) {
              $nomeProduto = mysqli_real_escape_string($conn, $_GET['nomeProduto']);
              $query_count = "SELECT COUNT(*) AS total FROM Esteira WHERE `nomeProduto` LIKE '%$nomeProduto%'";
          }

          if (isset($_GET['aplicacaoAtual'])) {
              $aplicacaoAtual = $_GET['aplicacaoAtual'];
              $query_count .= " AND `pedidoJson` LIKE '%$aplicacaoAtual%'";
          }

          // Executa a contagem total
          $total_result = mysqli_query($conn, $query_count);
          $total_pedidos = mysqli_fetch_assoc($total_result)['total'];
          $total_paginas = ceil($total_pedidos / $limite);

          //echo($query);
          $resultado = mysqli_query($conn, $query);
          if (mysqli_num_rows($resultado) == 0) {
            echo ("<center><div style='height: 300px; width: 645px; display: flex; justify-content: center; align-items: center;'>Os pedidos serão exibidos aqui.</div></center>");
          } else {
            function unicodeToChar($match)
            {
              return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
            }
            while (($data = mysqli_fetch_assoc($resultado))) {
              $imagem = "https://rksrodrigues.com/" . ltrim($data['imagem']);
              $imagem = str_replace(' ', '%20', $imagem);
              $imagem = preg_replace_callback("/u([0-9A-Fa-f]{4})/", "unicodeToChar", $imagem);


              $cor = 'white';

              if ($data['setorAtual'] == 9) {
                $cor = '#8dff8d';
              }
              if ($data['setorAtual'] == 16) {
                $cor = '#b1b1b1';
              }
              if ($data['status'] != 'ativo') {
                $cor = '#ff7878';
              }

              // DIV DOS PEDIDOS

              $idPedidoPr = explode('item_', $data['idMolde'])[1]; //coletando o id da op

              echo ("<div class='col-sm-3 upscaleMouse' style='border: 1px solid grey;text-align: center; border-radius: 0px;padding: 8px;font-size:10px;line-height:0.95;width:200px;margin-left:5px;height:380px;background-color:$cor;margin-top:10px;border-radius:9px'>");

              //menu superior (apenas admin)
              if ($_SESSION['usuarioNivelAcesso'] == 0) {
                echo ("<div style='width:100%;height:15px;padding:0px'>");
                if ($data['status'] == 'ativo') {
                  echo ("<a href='javascript:stopOp(" . $data['idGeral'] . ',' . $idPedidoPr . ")'><div class='botoesControleOp'>PARAR</div></a>");
                } else {
                  echo ("<a href='javascript:restartOp(" . $data['idGeral'] . ',' . $idPedidoPr . ")'><div class='botoesControleOp'>RETORMAR</div></a>");
                }
                echo ("<a id='abateBtn_" . $data['idGeral'] . '_' . $idPedidoPr . "' href='javascript:showAbate(" . $data['idGeral'] . ',' . $idPedidoPr . ")'><div class='botoesControleOp'>ABATER</div></a>");
                echo ("<a href='javascript:deletaOpEsteira(" . $data['idGeral'] . ',' . $idPedidoPr . ")'><div class='botoesControleOp'>DELETAR</div></a>");
                //echo ("<a href='javascript:editarEsteira(" . $data['idGeral'] . ',' . $idPedidoPr . ")'><div class='botoesControleOp'>EDITAR</div></a>");
                echo ("</div>");
              }

              //fim menu admin
              echo ("<div><span style='font-size: 10px;line-height:2;padding-bottom:3px'>N° OP: </span><span style='font-size:10px'>#" . $data['idGeral'] . "-" . $idPedidoPr . "</span> | <span style='font-size:10px;COLOR:RED'>" . date("d/m/Y", strtotime($data['dataEntrega'])) . " às " . $data['horaEntrega'] . "h</span><br>Cliente: <span style='text-transform: uppercase;'>" . $data['cliente'] . "</span></div>");
              echo ("<a  href='javascript:exibeResumoPedido()' id='fotoMolde_" . $data['idGeral'] . "_" . $idPedidoPr . "_" . $data['idVendedor'] . "' data-toggle='modal' data-target='#exampleModal' id='butExibePedido_X'><img src='" . $imagem . "' width='100%' style='margin-top:5px'></a>");


              $tamanhos = json_decode(json_decode(json_encode($data, JSON_UNESCAPED_UNICODE))->Quantidades); //pegando os tamanhos
              if (isset($tamanhos[0]->largura)) {
                echo ("<div id='tamanhos' style='width:100%;border-radius: 0px 0px 10px 10px;margin-bottom:5px'>");
                echo ("<table class='table table-bordered text-center' style='border:1px solid #4b4c4e;margin-bottom:0px;height:10px'>");
                echo ("<thead style='background-color:#2e2f30;height:15px;text-align:center;color:white'>");
                echo ("<tr>");
                echo ("<th scope='col' style='width:200px;padding:0px;'>ALTURA<input id='alturaLabel' type='number' style='color:black;width:100%;height:35px;text-align:center;BACKGROUND-COLOR:WHITE;font-size:16px' value='" . $tamanhos[0]->altura . "' disabled></th>");
                echo ("<th scope='col' style='width:200px;padding:0px;'>LARGURA<input id='alturaLabel' type='number' style='color:black;width:100%;height:35px;text-align:center;BACKGROUND-COLOR:WHITE;font-size:16px' value='" . $tamanhos[0]->largura . "' disabled></th>");
                echo ("<th scope='col' style='width:200px;padding:0px;'>QUANTIDADE<input id='alturaLabel' type='number' style='color:black;width:100%;height:35px;text-align:center;BACKGROUND-COLOR:WHITE;font-size:16px' value='" . $tamanhos[0]->quantidade . "' disabled></th>");
                echo ("</tr></thead></table></div>");
              } else {
                echo ("<div id='tamanhos' style='width:100%;border-radius: 0px 0px 10px 10px;margin-bottom:5px'>");
                echo ("<table class='table table-bordered text-center' style='border:1px solid #4b4c4e;margin-bottom:0px;height:10px'>");
                echo ("<thead style='background-color:#2e2f30;height:15px;text-align:center;color:white'>");
                echo ("<tr>");
                foreach ($tamanhos as $key => $value) {
                  if ($value->quantidade == 0) {
                    $value->quantidade = '';
                  }
                  echo ("<th scope='col' style='width:200px;padding:0px;'>" . $value->nome . "<input id='alturaLabel' type='number' style='color:black;width:100%;height:35px;text-align:center;BACKGROUND-COLOR:WHITE;font-size:16px' value='" . $value->quantidade . "' disabled></th>");
                }
                echo ("</tr></thead></table></div>");
              }

              echo ("<div style='height:30px'><span style='font-size:10px;line-height:0.25;margin-top:5px'>Produto:" . $data['nomeProduto'] . "</span></div>");

              //AQUI MEU PARCEIRO
              if ($data['status'] != 'ativo' or $data['setorAtual'] == 16) {
                if ($data['status'] != 'ativo') {
                  echo ("<button id='btnExibeHi_" . $data['idGeral'] . "_" . $idPedidoPr . "' 
                              onClick='javascript:observacao(" . $data['idGeral'] . ',' . $idPedidoPr . ")' 
                              class='btn-obs-historico'>
                              <i class='fa fa-pencil'></i>
                          </button>");
                  echo ("<select id='select_" . $data['idGeral'] . "_" . $idPedidoPr . "' onChange='mudaSetor(" . $data['idGeral'] . ',' . $idPedidoPr . ")' style=''><option value=''>-- PARADOS--</option>");
                  echo ("</select>");
                  echo ("<button id='btnExibeHist_" . $data['idGeral'] . "_" . $idPedidoPr . "' onClick='javascript:exibeHistorico(" . $data['idGeral'] . ',' . $idPedidoPr . ")' style=''><i class='fa fa-list'></i></buton>");
                } else if ($data['setorAtual'] == 16) {
                  echo ("<button id='btnExibeHi_" . $data['idGeral'] . "_" . $idPedidoPr . "' 
                              onClick='javascript:observacao(" . $data['idGeral'] . ',' . $idPedidoPr . ")' 
                              class='btn-obs-historico'>
                              <i class='fa fa-pencil'></i>
                          </button>");
                  echo ("<select id='select_" . $data['idGeral'] . "_" . $idPedidoPr . "' onChange='mudaSetor(" . $data['idGeral'] . ',' . $idPedidoPr . ")' style=''><option value=''>-- ENTREGUE--</option>");
                  echo ("</select>");
                  echo ("<button id='btnExibeHist_" . $data['idGeral'] . "_" . $idPedidoPr . "' onClick='javascript:exibeHistorico(" . $data['idGeral'] . ',' . $idPedidoPr . ")' style='padding: 6px;'><i class='fa fa-list'></i></buton>");
                }
              } else {
                if ($_SESSION['usuarioNivelAcesso'] == 5 || $_SESSION['usuarioNivelAcesso'] == 0) {
                    echo ("<button id='btnExibeHi_" . $data['idGeral'] . "_" . $idPedidoPr . "' 
                              onClick='javascript:observacao(" . $data['idGeral'] . ',' . $idPedidoPr . ")' 
                              class='btn-obs-historico'>
                              <i class='fa fa-pencil'></i>
                          </button>");
                    
                    echo ("<select id='select_" . $data['idGeral'] . "_" . $idPedidoPr . "' 
                                  onChange='mudaSetor(" . $data['idGeral'] . ',' . $idPedidoPr . ")' 
                                  class='setor-select'>
                                  <option value='' disabled>---PRÓXIMO SETOR---</option>");
                    
                    // Gerando o select
                    $setores = [
                        ['10.FASE INICIAL', 10], ['20.PRODUÇÃO', 20], ['22.PRONTA ENTREGA', 22], 
                        ['15.MODELAGEM', 15], ['2.SUBLIMAÇÃO', 2], ['3.CORTE MALHARIA', 3], 
                        ['25.CORTE TECIDO', 25], ['11.ESTAMPARIA', 11], ['12.PEQUENOS FORMATOS', 12], ['13.BORDADO', 13], 
                        ['14.VINILPERS', 14], ['23.DTF', 23], ['5.ATELIÊ', 5], 
                        ['6.PASSADEIRAS', 6], ['7.REVISÃO', 7], ['8.EMBALAGEM', 8], 
                        ['24.TERCEIRIZADO', 24], ['9.CONCLUÍDO', 9], ['--16.ENTREGUE--', 16]
                    ];

                    foreach ($setores as $valor) {
                        $atributo = ($data['setorAtual'] == $valor[1]) ? 'selected' : '';
                        echo ("<option value='$valor[1]' $atributo>$valor[0]</option>");
                    }
                    echo ("</select>");
                    
                    echo ("<button id='btnExibeHist_" . $data['idGeral'] . "_" . $idPedidoPr . "' 
                              onClick='javascript:exibeHistorico(" . $data['idGeral'] . ',' . $idPedidoPr . ")' 
                              class='btn-exibe-historico'>
                              <i class='fa fa-list'></i>
                          </button>");
                } else {
                  echo ("<button id='btnExibeHi_" . $data['idGeral'] . "_" . $idPedidoPr . "' 
                              onClick='javascript:observacao(" . $data['idGeral'] . ',' . $idPedidoPr . ")' 
                              class='btn-obs-historico'>
                              <i class='fa fa-pencil'></i>
                          </button>");
                  echo ("<select id='select_" . $data['idGeral'] . "_" . $idPedidoPr . "' onChange='mudaSetor(" . $data['idGeral'] . ',' . $idPedidoPr . ")' style=';' disabled='disabled'><option value='' disabled>---PRÓXIMO SETOR---</option>");
                  //gerando o select ======================================================
                  $setores = [['10.FASE INICIAL', 10], ['20.PRODUÇÃO', 20], ['22.PRONTA ENTREGA', 22], ['15.MODELAGEM', 15], ['2.SUBLIMAÇÃO', 2], ['3.CORTE MALHARIA', 3], ['25.CORTE TECIDO', 25], ['11.ESTAMPARIA', 11], ['12.PEQUENOS FORMATOS', 12], ['13.BORDADO', 13], ['14.VINILPERS', 14], ['23.DTF', 23], ['5.ATELIÊ', 5], ['6.PASSADEIRAS', 6], ['7.REVISÃO', 7], ['8.EMBALAGEM', 8], ['24.TERCEIRIZADO', 24], ['9.CONCLUÍDO', 9], ['--16.ENTREGUE--', 16], ['--PARADOS--', 0]];
                  print_r([2]);
                  foreach ($setores as $valor) {
                    $atributo = '';
                    if ($data['setorAtual'] == $valor[1]) {
                      $atributo = 'selected';
                    }
                    echo ("<option value='$valor[1]' $atributo>$valor[0]</option>");
                  }
                  echo ("</select>");
                  echo ("<button id='btnExibeHist_" . $data['idGeral'] . "_" . $idPedidoPr . "' onClick='javascript:exibeHistorico(" . $data['idGeral'] . ',' . $idPedidoPr . ")' style='padding: 6px;'><i class='fa fa-list'></i></buton>");
                }
              }

              //echo("<button type='button' class='btn btn-dark' style='width:20%; height: 23px; margin-bottom: 15px; margin-top: 10px;line-height:0;font-size:10px' onclick='window.location.reload();'>OK</button> ");
              echo ('</div>');
              echo ("<span id='historicoSecret_" . $data['idGeral'] . "_" . $idPedidoPr . "' style='display:none'>" . $data['historico'] . "</span>");
            }
            ?>
            <?php
            $extra = '';
            if (isset($_GET['nomeProduto'])) {
                $extra .= '&nomeProduto=' . urlencode($_GET['nomeProduto']);
            }
            if (isset($_GET['aplicacaoAtual'])) {
                $extra .= '&aplicacaoAtual=' . urlencode($_GET['aplicacaoAtual']);
            }
            if (isset($_GET['setorAtual'])) {
                $extra .= '&setorAtual=' . urlencode($_GET['setorAtual']);
            }
            ?>

            <nav class="paginacao">
              <ul class="pagination">
                  <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
                      <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                          <a class="page-link" href="?pagina=<?= $i ?><?= $extra ?>">
                              <?= $i ?>
                          </a>
                      </li>
                  <?php endfor; ?>
              </ul>
            </nav>
          <?php } ?>
        </div>
      </div>
    </div>
    </div>



    </div>
    </div>
    </div>
    <style>
      table,
      th,
      td {
        border: 1px solid grey;

        font-weight: normal;
      }

      .center {
        text-align: center;
      }

      .left {
        text-align: left;
      }

      .right {
        text-align: right;
      }

      .fonte-pequena {
        font-size: 12px;
      }

      .espaco {
        height: 10px;
      }

      .texto-forte {
        font-weight: bold;
      }

      .fonte-grande {
        font-size: 12px;
        color: black;
      }

      .tamanhos {
        height: 55px;
        display: flex;
        justify-content: center;
      }

      #table {
        align-self: center;
      }

      .fonte-extra-grande {
        font-size: 16px;
      }

      .modal-content {
        width: 650px;
        margin-left: -70px;
      }

      /*botõezinhos superiores de admin*/
      .botoesControleOp {
        width: 25%;
        display: inline-block;
        border: 1px solid grey;
        background-color: #18191a;
        color: white;
      }

      .botoesControleOp:hover {
        width: 25%;
        display: inline-block;
        border: 1px solid grey;
        background-color: #4e5053;
        color: white;
      }
    </style>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" id='janelaTempPedidoModal'>
            <h5 class="modal-title" id="idPedidoTemp">Pedido <span id='tempMoldeId'></span></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id='tempPedidoBody'>

          </div>

        </div>
      </div>
    </div>


    <!-- historico -->
    <div class='col' id='janelaHistoricoSetores' style='width:420px;color:white;text-align:center;background-color:white;display:none;'>

    </div>
</body>

</html>

<style>
  .janelaAbate {
    border: 1px solid grey;
    width: 900px;
    background-color: white;
  }

  .btnSalvarAbate {
    background-color: #18191a;
  }

  .btnSalvarAbate:hover {
    background-color: #36383a;
  }

  .btnAbate {
    background-color: #18191a;
  }

  .btnAbate:hover {
    background-color: #36383a;
  }


  .estoque-menu {
    border: 1px solid grey;
    background-color: #36383a;
    color: white;
  }

  .estoque-menu2 {
    border: 1px solid grey;
    color: black;
    display: inline-block;
    text-align: center;
  }
</style>
<!-- janela de aplicações para abate-->
<div class='janelaAbate' id='janelaAbate' style='display:none'>
  <div style='background-color:#18191a;text-align:center;color:white'>Abater do estoque</div>
  <div class='row' style='padding-left:10px;padding-right:10px'>
    <div class='col-sm-4 estoque-menu'>Composição</div>
    <div class='col-sm-2 estoque-menu'>Formato</div>
    <div class='col-sm-1 estoque-menu'>Quant.</div>
    <div class='col-sm-1 estoque-menu'>Abatido</div>
    <div class='col-sm-3 estoque-menu'>Cód. Bling</div>
    <div class='col-sm-1 estoque-menu'>-</div>
  </div>
  <div id='viewAbates'>

  </div>
</div>

<script>
  let pedido = '';

  $('#exampleModal').on('show.bs.modal', function(e) {
    //butExibePedido_17
    $("#tempPedidoIdMolde").text('#' + '366-17');
    var nomeVendedorFinal = $(e).attr('relatedTarget').id.split('_')[3];
    var idMoldeAtual = $(e).attr('relatedTarget').id.split('_')[2];
    var idPedido = $(e).attr('relatedTarget').id.split('_')[1];
    var pedido = '';
    var value = '';
    //puxando os dados do pedido clicado
    $.ajax({
      type: "POST",
      url: 'ajax/retornaPedido.php',
      data: jQuery.param({
        numeroPedido: idPedido,
        numeroMolde: idMoldeAtual
      }),
      contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
      success: function(data) {
        pedido = JSON.parse(JSON.parse(String(data)));
        //console.log(pedido);
        $("#tempMoldeId").text(idMoldeAtual);

        var nomeProduto = '';
        var composicoes = '';
        var aplicacoes = '';
        var tamanhos = '';
        //gerando o pedido com todos os produtos
        value = pedido.produtos['item_' + idMoldeAtual];
        console.log(pedido);
        console.log('item_' + idMoldeAtual);
        var idMolde = idMoldeAtual;

        //verificando a forma de entrega
        var formaentrega = '';
        if (pedido.transporte.tipoFrete == "Balcão de entrega") {
          formaentrega = 'Av. Via da Flores, 2077 - Pricumã';
        } else {
          formaentrega = "<span style='color:black;font-weight:normal'><span style='color:red'>Forma: " + pedido.transporte.tipoFrete + "</span><br>Nome: " + pedido.transporte.etiqueta.nome + "<br>CEP: " + pedido.transporte.etiqueta.cep + " UF: " + pedido.transporte.etiqueta.uf + " Número: " + pedido.transporte.etiqueta.numero + "<br>Bairro: " + pedido.transporte.etiqueta.bairro + "<br>Município: " + pedido.transporte.etiqueta.municipio + "<br>Endereço: " + pedido.transporte.etiqueta.endereco + "</span>";
        }

        if(pedido.produtos['item_' + idMoldeAtual].pedidoExpresso != undefined) {
          var pedidoExpresso = "</td><td class='fonte-pequena' colspan='1'>"+pedido.produtos['item_' + idMoldeAtual].pedidoExpresso+"</td></tr>"
        } else {
          var pedidoExpresso = "</td><td class='fonte-pequena' colspan='1'>PEDIDO NORMAL</td></tr>"
        }

        $("#tempPedidoBody").empty();
        $("#tempPedidoBody").append("<table id='tabela_10' style='width:615px'></table>")
        $("#tabela_10").append("<tr><td rowspan='3' colspan='2' class='center'><img src='img/personal-logo.png' width='100px'></td></tr>"); //linha 1 imagem
        $("#tabela_10").append("<tr style='height:30px'><td class='left fonte-grande' colspan='11' valign='top'>Modelo: " + value.molde.nome + "</td></tr>"); //Modelo
        $("#tabela_10").append("<tr><td class='left fonte-grande' colspan='11'>Referência: " + value.molde.codigo + "</td></tr>"); //
        $("#tabela_10").append("<tr><td colspan='2' class='fonte-pequena'>Data do Pedido: <br>" + pedido.data.split("-").reverse().join("-") + "</td><td colspan='2' style='color:red' class='fonte-pequena'>Data/Hora entrega: <br>" + pedido.dataPrevista.split("-").reverse().join("-") + "," + pedido.observacoesInternas.horaEntrega + "h</td><td colspan='2' class='fonte-pequena'>Vendedor: <br>" + nomeVendedorFinal + "</td><td class='fonte-pequena' colspan='1'>Pedido: <br>#" + pedido.id + '-' + idMolde + pedidoExpresso);
        $("#tabela_10").append("<tr><td class='left fonte-grande' colspan='11'>Cliente: <span style='text-transform: uppercase;'>" + pedido.contato.nome + "<span></td></tr><tr><td colspan='11' class='fonte-grande center fonte-extra-grande texto-forte'>FICHA TÉCNICA DO PRODUTO</td></tr>");
        $("#tabela_10").append("<tr><td colspan='11'><img src='" + value.molde.imagem + "' width='100%'></td></tr>"); //imagem
        $("#tabela_10").append("<tr><td colspan='11' class='fonte-grande center texto-forte'>QUANTIDADE</td></tr>");
        $("#tabela_10").append("<tr class='center'><td colspan='11'><div class='texto-grande tamanhos' id='tamanhosTemp_" + idMolde + "'></div></td></tr>"); //tamanhos

        $("#tamanhosTemp_" + idMolde).append("");

        $("#tabela_10").append("<tr><td colspan='3' class='fonte-grande center texto-forte'>OBSERVAÇÕES</td><td colspan='5' class='fonte-grande center texto-forte'>COMPOSIÇÃO<span id='totalGeralProdutos' style='float:right;font-size:12px;font-weight:normal;line-height:2.5;border:1px solid grey'></span></td></tr>");
        $("#tabela_10").append("<tr class='left' valign='top' style='height:80px'><td colspan='3' class='fonte-grande' style='color:red;font-weight:bold'>" + value.molde.Observacoes + "<div style='border:1px solid grey;color:black;font-weight:bold;text-align:center;font-size:12px'>ENTREGA/RETIRADA</div>" + formaentrega + "</td><td colspan='5' style='height:25px;font-size:12px' id='composicao_" + idMolde + "'></td></tr>");
        $("#tabela_10").append("<tr><td colspan='11' style='font-weight:bold'>Arquivo: <input style='width:65%' type='text' value='" + pedido?.observacoesInternas?.pastaDrive + "' id='linkDrive" + idPedido + '_' + idMoldeAtual + "'><button onclick='copiaLinkDrive(" + '"' + String(pedido?.observacoesInternas?.pastaDrive) + '"' + ")'>Copiar</button><button style='margin-left:3px' onclick='geraPdfImpressao(" + pedido.id + "," + idMolde + ")'>Imprimir</button></td></tr>"); //link drive
        $("#tabela_10").append("<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></tr>"); //Última linha

        //aqui inserindo os tamanhos
        //nomeProduto = value.molde.nome;
        tamanhos += "<table width='100%'><tr class='center fonte-grande texto-forte'>";
        //primeiro os tamanhos
        if (pedido.produtos['item_' + idMolde]?.quantidades?.[0]?.altura != undefined) {
          tamanhos += "<td class='texto-forte'>" + 'Altura' + "</td>";
          tamanhos += "<td class='texto-forte'>" + 'largura' + "</td>";
          tamanhos += "<td class='texto-forte'>" + 'Quantidade' + "</td>";
          tamanhos += "</tr><tr class='center fonte-grande'>"; //linha debaixo
          tamanhos += "<td class='texto-forte'>" + value.quantidades[0].altura + "</td>";
          tamanhos += "<td class='texto-forte'>" + value.quantidades[0].largura + "</td>";
          tamanhos += "<td class='texto-forte'>" + value.quantidades[0].quantidade + "</td>";
          tamanhos += "</tr></table>";

        } else {
          var totalGeralProdutos = 0; //variável usada apenas para pegar o total de produtos do molde
          $.each(value.quantidades, function(key, value) {
            tamanhos += "<td class='texto-forte' style='font-weight:normal'>" + value.nome + "</td>";
          });
          tamanhos += "</tr><tr class='center fonte-grande'>";
          //depois as quantidades de cada
          $.each(value.quantidades, function(key, value) {
            if (value.quantidade == 0) {
              tamanhos += "<td class='texto-forte'> </td>";
            } else {
              tamanhos += "<td class='texto-forte'>" + value.quantidade + "</td>";
              totalGeralProdutos += parseInt(value.quantidade);
            }

          });
          tamanhos += "</tr></table>";
          $('#totalGeralProdutos').text('Total de peças: ' + totalGeralProdutos); //aqui só inserindo o textinho de total de peças
        }
        //e por ultimo inserindo na tabela    
        $("#tamanhosTemp_" + idMolde).append(tamanhos);

        //aqui inserindo as composições
        composicoes = value.molde.descricaoCurta.composicao;
        $.each(composicoes, function(key, value) {
          if (composicoes[key][((composicoes[key].length) - 1)].selecionado != '') {
            $("#composicao_" + idMolde).append("<b>" + key + ": </b>");
            $("#composicao_" + idMolde).append(composicoes[key][((composicoes[key].length) - 1)] + "<br>");
          }
        });

        //aqui as aplicações
        aplicacoes = value.aplicacoes;
        $.each(aplicacoes, function(key, value) {
          console.log(value);
          $("#composicao_" + idMolde).append("<b>" + value.nome + "</b><br>");
        });
      }
    });
  });

  function copiaLinkDrive(link) {
    navigator.clipboard.writeText(link);
    window.open(link, '_blank');
  }


function observacao(idPedido, idMolde){
    var opcao = $("#select_" + idPedido + "_" + idMolde + " :selected").val();
    console.log(idPedido + '//' + idMolde);
    var observacao = window.prompt('Por favor, insira uma observação:');
    if(observacao) {
      $.ajax({
        type: "POST",
        url: 'ajax/AdicionaComentario.php',
        data: jQuery.param({
          idOp: idPedido,
          idMolde_: idMolde,
          novoSetor: opcao,
          observacao: observacao  // Inclui a observação nos dados enviado
        }),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(data) {
          alert("Comentário adicionado com sucesso! 😀");
          location.reload();
        }
      });
    }
}

  function mudaSetor(idPedido, idMolde) {
    var opcao = $("#select_" + idPedido + "_" + idMolde + " :selected").val();
    console.log(idPedido + '//' + idMolde);
    //console.log($("#select_"+idPedido+"_"+idMolde+" :selected").val());
    //console.log($("#select_"+idPedido+"_"+idMolde+" :selected").text());
    if (window.confirm('Gostaria mesmo de mover essa op para ' + $("#select_" + idPedido + "_" + idMolde + " :selected").text() + '?')) {
      var observacao = window.prompt('Por favor, insira uma observação:');
      $.ajax({
        type: "POST",
        url: 'ajax/mudaSetorAtual.php',
        data: jQuery.param({
          idOp: idPedido,
          idMolde_: idMolde,
          novoSetor: opcao,
          observacao: observacao  // Inclui a observação nos dados enviado
        }),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(data) {
          location.reload();
        }
      });
    }
  }

  function alteraSetor() {
    var novoSetor = $('option:selected', '#selectSetor').val();
    var novaAplicacao = $('option:selected', '#selectAplicacao').val();
    console.log(novoSetor);
    location.href = "movimentacao.php?setorAtual=" + novoSetor + "&aplicacaoAtual=" + novaAplicacao;
  }

  function buscaPedido() {
    console.log($("#inputDadosBusca").val());
    console.log($("input[name='tipoBusca']:checked").val());

    if ($("input[name='tipoBusca']:checked").val() == 'idOp') {
      location.href = "movimentacao.php?idOp=" + $("#inputDadosBusca").val();
    } else if ($("input[name='tipoBusca']:checked").val() == 'Nome') {
      location.href = "movimentacao.php?nome=" + $("#inputDadosBusca").val();
    } else if ($("input[name='tipoBusca']:checked").val() == 'dataEntrega') {
      location.href = "movimentacao.php?dataEntrega=" + String($("#inputDadosBusca").val()).split("/").reverse().join("-");
    }
  }

  //funções relacionadas aos botõezinhos
  function deletaOpEsteira(idOp, idMolde) {
    console.log(idOp + "//" + idMolde);
    if (window.prompt('Informe a senha para DELETAR: ') === 'Personal_2@2@') {
      $.ajax({
        type: "POST",
        url: 'ajax/deletaOpEsteira.php',
        data: jQuery.param({
          idOp_: idOp,
          idMolde_: idMolde
        }),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(data) {
          location.reload();
        }
      });
    } else {
      window.alert('SENHA ERRADA! Não foi possivel DELETAR!');
    }
  }

  //stop pedido PARA  pedido
  function stopOp(idOp, idMolde) {
    var resultado = prompt("Informe o motivo de está parando a produção:");
    if (resultado === null) {
      alert("op cancelada ");
    } else {
      $.ajax({
        type: "POST",
        url: 'ajax/paraOpEsteira.php',
        data: jQuery.param({
          idOp_: idOp,
          idMolde_: idMolde,
          resultado_: resultado
        }),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(data) {
          console.log(data);
          location.reload();
        }
      });
    }
  }

  function restartOp(idOp, idMolde) {
    if (window.confirm('Gostaria mesmo de reativar essa op?')) {
      $.ajax({
        type: "POST",
        url: 'ajax/retomaEsteiraOp.php',
        data: jQuery.param({
          idOp_: idOp,
          idMolde_: idMolde
        }),
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        success: function(data) {
          location.reload();
        }
      });
    }
  }
  
  
  function editarEsteira(idOp,idMolde){
      location.href = "editarPedido.php?numeroPedido="+idOp+"&numeroMolde="+idMolde;
  }

  // e aqui, a parte que abate os itens do pedido ===============================================================
  function showAbate(idOp, idMolde) {
    //apenas para controlar a view
    if ($("#janelaAbate").is(':visible')) {

      $("#janelaAbate").hide();
    }
    $("#janelaAbate").toggle(250);
    var pos = $("#abateBtn_" + idOp + "_" + idMolde).offset();
    pos.left = pos.left;
    pos.top = pos.top + 18;
    $("#janelaAbate").offset(pos);

    //esvaziando
    $("#viewAbates").empty();

    //e aqui a parte que controla em si ========================================================================
    var idMoldeAtual = idMolde;
    var idPedido = idOp;
    var value = '';
    //puxando os dados do pedido clicado
    $.ajax({
      type: "POST",
      url: 'ajax/retornaPedido.php',
      data: jQuery.param({
        numeroPedido: idPedido,
        numeroMolde: idMoldeAtual
      }),
      contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
      success: function(data) {
        pedido = JSON.parse((JSON.parse(String(data))));
        var composicao = pedido.produtos['item_' + idMoldeAtual].molde.descricaoCurta.composicao;
        var quantidade = pedido.produtos['item_' + idMoldeAtual].quantidades;
        console.log(pedido);

        console.log(historico);
        //verifica o tipo de abate que vai ser realizado
        $.each(composicao, function(key, value) {
          console.log(value);
          if (value[0].quantidade[0].Altura != undefined) {
            console.log('entrou no primeiro');
            let optionsTemp = '';
            $("#viewAbates").append("<div class='col-sm-4 estoque-menu2' style='display:inline-block;font-size:10px;line-height:1'><img style='float:left' src='moldes/tecidos/" + String(value[value.length - 1]).split('_')[1] + ".jpg' width='25px'>" + key + ": " + String(value[value.length - 1]).split('_')[0] + "</div>"); //primeiro lista o nome da composição 
            //==================================================== acessando as opções do jquery
            $.each(value[0], function(key2, value2) { //depois lista as opções de abate
              $.each(value2[2].comprimentos, function(key3, value3) {
                optionsTemp += "<option value='" + value3 + "'>" + value3 + "</option>";
              });
            });
            //=============================================================================================================== 
            //verifica primeiro, quem é altura e quem é largura do pedido
            var altura = quantidade[0].altura;
            var largura = quantidade[0].largura;

            if (parseFloat(value[0].quantidade[0].Altura) < parseFloat(value[0].quantidade[1].Largura)) {
              var temp = altura;
              altura = largura;
              largura = temp;
            }

            var totalPecasT = 0;
            $.each(quantidade, function(key, value) {
              totalPecasT += parseInt(value.quantidade);
            });
            console.log('pecinhas::' + totalPecasT);
            //=================================================================================================================== 
            $("#viewAbates").append("<div class='col-sm-2 estoque-menu2' style='display:inline-block'><select id='selAbate_" + key + "_" + idPedido + "_" + idMoldeAtual + "' onChange='mudouOptionComp(" + '"' + key + '"' + "," + idPedido + "," + idMoldeAtual + ',' + altura.replace(',', '.') + "," + '"' + largura.replace(',', '.') + '"' + ',' + totalPecasT + ")'  style='width:100%'>" + optionsTemp + "</select></div>");
            $("#viewAbates").append("</div>");
            //=================================================================================================================

            $("#viewAbates").append("<div class='col-sm-1 estoque-menu2'>" + totalPecasT + "</div>"); //quantidades 

            $("#viewAbates").append("<div class='col-sm-1 estoque-menu2'><span style='color:red' id='totalAbatido_" + key + "_" + idPedido + "_" + idMoldeAtual + "'>" + '-' + "</span></div>"); //total abatido

            //e aqui a fórmula do cálculo 
            mudouOptionComp(key, idPedido, idMoldeAtual, altura.replace(',', '.'), largura.replace(',', '.'), totalPecasT);
            //====================================================================================================================
            var codParaAbate = (String(value.slice(-1))).split('_')[3]; //pegando o código de abate da comp atual
            var jaFoiAbatido = false;
            //aqui ele verifica se já tem algum código de abate, gerado antes, e preenche esse campo com o últim ogerado
            $.each(pedido?.abatidos, function(key4, value4) {
              if (value4[key] != undefined) {
                jaFoiAbatido = true;
                $("#viewAbates").append("<div id='codBling_" + key + "_" + idPedido + "_" + idMoldeAtual + "' class='col-sm-3 estoque-menu2' style='color:red'>" + value4[key] + "</div>"); //código bling
              }
            });

            if (!jaFoiAbatido) {
              $("#viewAbates").append("<div id='codBling_" + key + "_" + idPedido + "_" + idMoldeAtual + "' class='col-sm-3 estoque-menu2' style='color:red'>" + '-' + "</div>"); //código bling
            }
            //====================================================================================================================
            $("#viewAbates").append("<div class='col-sm-1 estoque-menu2'><button type='button' id='' onClick='javascript:abate(" + '"' + key + '"' + "," + idPedido + "," + idMoldeAtual + "," + codParaAbate + ")' class='btn btn- btn-sm btnAbate' style='height:100%;width:100%;margin-top:-3px;border-radius:0px;margin-top:-6px'><span style='color:white;'>Abater</span></button></div>"); //botão de abate
          } else {
            console.log('entrou no segundo');
            //console.log(value);--------------------------------------------------------------------------------------------------------
            let optionsTemp = '';
            //console.log(pedido);
            //$("#viewAbates").append("<div class='row'>"); 
            $("#viewAbates").append("<div class='col-sm-4 estoque-menu2' style='display:inline-block;font-size:11px;line-height:1'><img style='float:left' src='moldes/tecidos/" + String(value[value.length - 1]).split('_')[1] + ".jpg' width='25px'>" + key + ": " + String(value[value.length - 1]).split('_')[0] + "</div>"); //primeiro lista o nome da composição 
            //==================================================== acessando as opções do jquery
            $.each(value[0], function(key2, value2) { //depois lista as opções de abate
              $.each(value2, function(key3, value3) {
                $.each(value3, function(key4, value4) {
                  optionsTemp += "<option value='" + value3[key4] + "'>" + key4 + "</option>";
                });
              });
            });
            //==================================
            var totalPecasT = 0;
            $.each(quantidade, function(key, value) {
              totalPecasT += parseInt(value.quantidade);
            });
            //===================================

            $("#viewAbates").append("<div class='col-sm-2 estoque-menu2' style='display:inline-block'><select id='selAbate_" + key + "_" + idPedido + "_" + idMoldeAtual + "' onChange='mudouOptionCompVariantes(" + '"' + key + '"' + "," + idPedido + "," + idMoldeAtual + ',' + totalPecasT + ")' style='width:100%'>" + optionsTemp + "</select></div>");
            $("#viewAbates").append("</div>");
            //=================================================================================================================

            $("#viewAbates").append("<div class='col-sm-1 estoque-menu2'>" + totalPecasT + "</div>"); //quantidades 

            var selectAtual = $("#selAbate_" + key + "_" + idPedido + "_" + idMoldeAtual);
            var totalAbatido = totalPecasT * $('option:selected', selectAtual).val();
            $("#viewAbates").append("<div class='col-sm-1 estoque-menu2'><span style='color:red' id='totalAbatido_" + key + "_" + idPedido + "_" + idMoldeAtual + "'>" + totalAbatido + "</span></div>"); //total abatido


            var codParaAbate = (String(value.slice(-1))).split('_')[3]; //pegando o código de abate da comp atual
            var jaFoiAbatido = false;
            //aqui ele verifica se já tem algum código de abate, gerado antes, e preenche esse campo com o últim ogerado
            $.each(pedido?.abatidos, function(key4, value4) {
              if (value4[key] != undefined) {
                jaFoiAbatido = true;
                $("#viewAbates").append("<div id='codBling_" + key + "_" + idPedido + "_" + idMoldeAtual + "' class='col-sm-3 estoque-menu2' style='color:red'>" + value4[key] + "</div>"); //código bling
              }
            });

            if (!jaFoiAbatido) {
              $("#viewAbates").append("<div id='codBling_" + key + "_" + idPedido + "_" + idMoldeAtual + "' class='col-sm-3 estoque-menu2' style='color:red'>" + '-' + "</div>"); //código bling
            }

            $("#viewAbates").append("<div class='col-sm-1 estoque-menu2'><button type='button' id='' onClick='javascript:abate(" + '"' + key + '"' + "," + idPedido + "," + idMoldeAtual + "," + codParaAbate + ")' class='btn btn- btn-sm btnAbate' style='height:100%;width:100%;margin-top:-3px;border-radius:0px;margin-top:-6px'><span style='color:white;'>Abater</span></button></div>"); //botão de abate
          }

        });
        //printa o histórico da op
        //aqui, printando o histórico da OP
        $("#viewAbates").append("<div class='col-sm-12' style='text-align:center;color:white;background-color:#18191a;font-size:15px'>Histórico</div>");
        var historico = JSON.parse($("#historicoSecret_" + idPedido + "_" + idMoldeAtual).text());
        console.log(historico);
        $.each(historico.movimentacao, function(keyH, valueH) {
          $("#viewAbates").append("<div class='col-sm-12' style='text-align:center;color:black;border:1px solid black;font-size:15px'>" + valueH + "</div>");
        });


        $.each(historico.abate, function(keyA, valueA) {
          $("#codBling_" + valueA + "_" + idPedido + "_" + idMoldeAtual).text("-Abatido-");
          //console.log('alterado:'+);
        });
      }
    });
  }


  function mudouOptionComp(composicaoNome, idOp, idPedido, largura, altura, totaPecasT) {
    console.log("composicaoNome%:" + composicaoNome);
    console.log("idOp%:" + idOp);
    console.log("idPedido%:" + idPedido);
    console.log("altura%:" + altura);
    console.log("largura%:" + largura);
    //pegando a quantidade de peças para efeturar o cálculo
    var quantidade = pedido.produtos['item_' + idPedido].quantidades;
    var totalPecasT2 = 0;
    $.each(quantidade, function(key, value) {
      totalPecasT2 += parseInt(value.quantidade);
    });
    console.log('pecinhas::' + totalPecasT2);

    //e aqui a fórmula do cálculo 
    var selectAtual = $("#selAbate_" + composicaoNome + "_" + idOp + "_" + idPedido); //pegando a largura  do tecido da fábrica
    var larguraTecidoFabrica = $('option:selected', selectAtual).val();
    //console.log("larguraTecidoFábrica%:"+larguraTecidoFabrica);
    var totalAbatido = 0;

    console.log("base:" + larguraTecidoFabrica);

    var resto = parseInt(parseFloat(altura) / parseFloat(larguraTecidoFabrica));
    var resto2 = parseFloat(altura) % parseFloat(larguraTecidoFabrica);

    console.log("resto:" + resto);
    console.log("resto2:" + resto2);

    if (resto >= 1) {
      totalAbatido = parseFloat(resto) * parseFloat(largura);
    } else {
      totalAbatido = parseFloat(totalAbatido) + parseFloat(altura);
    }

    if (parseFloat(altura) < parseFloat(larguraTecidoFabrica)) {
      totalAbatido = parseFloat(largura);
    } else {
      if (resto2 == 0) {
        totalAbatido = parseFloat(totalAbatido);
      }
      if (resto2 > 0) {
        totalAbatido = parseFloat(totalAbatido) + parseFloat(largura);
      }
    }
    console.log("total peças%: " + totalPecasT2);
    console.log("total final:% " + parseFloat(totalAbatido));

    $("#totalAbatido_" + composicaoNome + '_' + idOp + '_' + idPedido).text(parseFloat(parseFloat(totalAbatido) * parseInt(totalPecasT2)).toFixed(2));
  }

  function geraPdfImpressao(pedidoA, idMoldeA) {
    window.open("geraPdfImpressao.php?numeroPedido=" + pedidoA + "&idMolde=" + idMoldeA, '_blank');
  }

  function mudouOptionCompVariantes(composicaoNome, idOp, idPedido, totaPecasT) {
    console.log("composicaoNome%:" + composicaoNome);
    console.log("idOp%:" + idOp);
    console.log("idPedido%:" + idPedido);

    //pegando a quantidade de peças para efeturar o cálculo
    var quantidade = pedido.produtos['item_' + idPedido].quantidades;
    var totalPecasT2 = 0;

    $.each(quantidade, function(key, value) {
      totalPecasT2 += parseInt(value.quantidade);
    });

    console.log('pecinhas::' + totalPecasT2);

    //e aqui a fórmula do cálculo 
    var selectAtual = $("#selAbate_" + composicaoNome + "_" + idOp + "_" + idPedido); //pegando a largura  do tecido da fábrica
    var larguraTecidoFabrica = $('option:selected', selectAtual).val();
    //console.log("larguraTecidoFábrica%:"+larguraTecidoFabrica);
    var totalAbatido = totalPecasT2 * larguraTecidoFabrica;

    console.log("base:" + larguraTecidoFabrica);

    console.log("total peças%: " + totalPecasT2);
    console.log("total final:% " + parseFloat(totalAbatido));

    $("#totalAbatido_" + composicaoNome + '_' + idOp + '_' + idPedido).text(parseFloat(parseFloat(totalAbatido)).toFixed(2));
  }


  //E aqui, só para caso clique em qualquer lugar que não a janela de abate, a esconda
  $(document).mouseup(function(e) {
    var container = $("#janelaAbate");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      container.hide();
    }
  });


  //aqui quando o usuário clicar no botão de abate
  function abate(nomeComp, idOp, idMolde, codigoAbate) {
    var quantidade = $("#totalAbatido_" + nomeComp + "_" + idOp + "_" + idMolde).text();
    console.log('nome Comp:' + nomeComp);
    $.ajax({
      type: "POST",
      url: 'ajax/baixaProdutoEstoque.php',
      data: jQuery.param({
        idProduto_: codigoAbate,
        quantidade_: quantidade,
        identificador_: 'pedido #' + idOp + '-' + idMolde,
        nomeCompAtual_: nomeComp
      }),
      contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
      success: function(data) {
        console.log(data);
        //console.log('abatido:'+quantidade);
        var retorno = JSON.parse(data);
        if (retorno?.data?.id != undefined) {
          //como teve retorno atualiza o json com o código retornado ===================================================
          $("#codBling_" + nomeComp + "_" + idOp + "_" + idMolde).text(retorno.data.id); //insere o código de retorno do bling no objeto
        } else {
          alert('ocorreu algum erro no bling :<' + JSON.parse(data) + "Provavelmente não foi selecionada a composição para abate :)");
        }
      }
    });
  }

  function exibeHistorico(idOp, idMolde) {
    $("#janelaHistoricoSetores").empty();
    $("#janelaHistoricoSetores").append("<div class='col-sm-12' style='background-color:black'>Histórico da Ficha Técnica #" + idOp + "-" + idMolde + "</div>");
    console.log(idOp + "//" + idMolde);
    //apenas para controlar a view
    if ($("#janelaHistoricoSetores").is(':visible')) {
      $("#janelaHistoricoSetores").hide();
    }
    $("#janelaHistoricoSetores").toggle(250);
    var pos = $("#btnExibeHist_" + idOp + "_" + idMolde).offset();
    pos.left = pos.left;
    pos.top = pos.top + 18;
    $("#janelaHistoricoSetores").offset(pos);

    var historico = JSON.parse($("#historicoSecret_" + idOp + "_" + idMolde).text());
    $.each(historico.movimentacao, function(keyH, valueH) {
      $("#janelaHistoricoSetores").append("<div class='col-sm-12' style='text-align:center;color:black;border:1px solid black;font-size:15px'>" + valueH + "</div>");
    });
  }

  //e aqui, só para caso clique em qualquer lugar que não a janela de aplicações, a esconda
  $(document).mouseup(function(e) {
    var container = $("#janelaHistoricoSetores");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      container.hide();
    }
  });

  //e aqui, quando pressionar enter para buscar algo
  $("#inputDadosBusca").on('keypress', function(e) {
    if (e.which == 13) {
      buscaPedido();
    }
  });
</script>
