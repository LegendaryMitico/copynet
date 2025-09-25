<?php
session_start();
ini_set('error_reporting', E_ALL);
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
<?php
include_once("php/connect.php"); //conexão

// ===== Entrada do usuário =====
$dataIniStr = isset($_REQUEST['data_ini']) ? trim($_REQUEST['data_ini']) : '';
$dataFimStr = isset($_REQUEST['data_fim']) ? trim($_REQUEST['data_fim']) : '';

// Se não houver dados na requisição, define data atual automaticamente
$buscarAutomaticamente = false;
if (empty($dataIniStr) && empty($dataFimStr)) {
    $dataAtual = date('d/m/Y');
    $dataIniStr = $dataAtual;
    $dataFimStr = $dataAtual;
    $buscarAutomaticamente = true;
}

function parseBrDate($d)
{
    $dt = DateTime::createFromFormat('d/m/Y', $d);
    return ($dt && $dt->format('d/m/Y') === $d) ? $dt : null;
}

// Função para calcular total de peças de um JSON de quantidades
function calcularTotalPecas($quantidadesJson) {
    if (empty($quantidadesJson)) return 0;
    
    $quantidades = json_decode($quantidadesJson, true);
    if (!is_array($quantidades)) return 0;
    
    $total = 0;
    foreach ($quantidades as $item) {
        if (isset($item['quantidade']) && is_numeric($item['quantidade'])) {
            $total += (int)$item['quantidade'];
        }
    }
    return $total;
}

$totalItensConcluidos = 0;
$totalPedidosEntraram = 0;
$totalPedidosParados = 0;
$totalPecasConcluidos = 0;
$totalPecasEntraram = 0;
$totalPecasParados = 0;
$detalhes = [];
$detalhesEntraram = [];
$detalhesParados = [];
$erro = '';
$mostrarResultados = false;

// Processa se houver dados via GET/POST OU se for busca automática
if ((!empty($dataIniStr) && !empty($dataFimStr)) || $buscarAutomaticamente) {
    $dtIni = parseBrDate($dataIniStr);
    $dtFim = parseBrDate($dataFimStr);

    if (!$dtIni || !$dtFim) {
        $erro = "Informe data_ini e data_fim no formato DD/MM/YYYY (ex.: 01/08/2025).";
    } else {
        // Normaliza limites (00:00 e 23:59:59)
        $dtIni->setTime(0, 0, 0);
        $dtFim->setTime(23, 59, 59);

        // ===== Conexão =====
        $conn = mysqli_connect($servidor, $usuario, $senha, $dbname);
        if (!$conn) {
            $erro = "Falha na conexão: " . mysqli_connect_error();
        } else {
            // ===== Query SQL Simplificada (compatível com MariaDB/MySQL) =====
            $sql = "
                SELECT 
                    contador, 
                    idGeral, 
                    idMolde, 
                    historicoPedido, 
                    historico,
                    Quantidades
                FROM Esteira
                WHERE (
                    (historicoPedido <> '' AND historicoPedido LIKE '%\"novoSetor\":\"9.CONCLUÍDO\"%')
                    OR 
                    (historico <> '' AND (
                        historico LIKE '%20.PRODUÇÃO%' OR 
                        historico LIKE '%parou%'
                    ))
                )
            ";
            
            $res = mysqli_query($conn, $sql);
            if (!$res) {
                $erro = "Erro na consulta: " . mysqli_error($conn);
            } else {
                // ===== Processamento dos Resultados =====
                while ($row = mysqli_fetch_assoc($res)) {
                    $contador        = $row['contador'];
                    $idGeral         = $row['idGeral'];
                    $idMolde         = $row['idMolde'];
                    $historicoPedido = $row['historicoPedido'];
                    $historico       = $row['historico'];
                    $quantidades     = $row['Quantidades'];
                    
                    // Calcula total de peças usando PHP
                    $totalPecasPedido = calcularTotalPecas($quantidades);

                    // ===== PROCESSAMENTO DE CONCLUÍDOS =====
                    if (!empty($historicoPedido)) {
                        $json = json_decode($historicoPedido, true);
                        if (is_array($json) && isset($json['historicoSetor']) && is_array($json['historicoSetor'])) {
                            foreach ($json['historicoSetor'] as $etapa) {
                                if (!isset($etapa['novoSetor'], $etapa['data'])) continue;

                                if ($etapa['novoSetor'] === '9.CONCLUÍDO') {
                                    $dtConc = DateTime::createFromFormat('d/m/Y', $etapa['data']);
                                    if (!$dtConc) continue;
                                    $dtConc->setTime(12, 0, 0);

                                    if ($dtConc >= $dtIni && $dtConc <= $dtFim) {
                                        $totalItensConcluidos++;
                                        $totalPecasConcluidos += $totalPecasPedido;
                                        $detalhes[] = [
                                            'contador' => $contador,
                                            'idGeral'  => $idGeral,
                                            'idMolde'  => $idMolde,
                                            'data'     => $dtConc->format('d/m/Y'),
                                            'pecas'    => $totalPecasPedido,
                                        ];
                                    }
                                    break;
                                }
                            }
                        }
                    }

                    // ===== PROCESSAMENTO DE HISTÓRICO (ENTRARAM E PARADOS) =====
                    if (!empty($historico)) {
                        $jsonHistorico = json_decode($historico, true);
                        if (is_array($jsonHistorico) && isset($jsonHistorico['movimentacao']) && is_array($jsonHistorico['movimentacao'])) {
                            
                            $jaContabilizouEntrada = false;
                            $jaContabilizouParada = false;
                            
                            foreach ($jsonHistorico['movimentacao'] as $movimento) {
                                // Regex para extrair data no formato DD/MM/YYYY
                                if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $movimento, $matches)) {
                                    $dataStr = $matches[1];
                                    $dtMov = DateTime::createFromFormat('d/m/Y', $dataStr);
                                    
                                    if ($dtMov) {
                                        $dtMov->setTime(12, 0, 0);
                                        
                                        if ($dtMov >= $dtIni && $dtMov <= $dtFim) {
                                            
                                            // Verifica se contém "20.PRODUÇÃO" e não foi contabilizado ainda
                                            if (strpos($movimento, '20.PRODUÇÃO') !== false && !$jaContabilizouEntrada) {
                                                $totalPedidosEntraram++;
                                                $totalPecasEntraram += $totalPecasPedido;
                                                $detalhesEntraram[] = [
                                                    'contador' => $contador,
                                                    'idGeral'  => $idGeral,
                                                    'idMolde'  => $idMolde,
                                                    'data'     => $dtMov->format('d/m/Y'),
                                                    'movimento' => $movimento,
                                                    'pecas'    => $totalPecasPedido,
                                                ];
                                                $jaContabilizouEntrada = true;
                                            }
                                            
                                            // Verifica se contém "parou" e não foi contabilizado ainda
                                            if (strpos($movimento, 'parou') !== false && !$jaContabilizouParada) {
                                                $totalPedidosParados++;
                                                $totalPecasParados += $totalPecasPedido;
                                                $detalhesParados[] = [
                                                    'contador' => $contador,
                                                    'idGeral'  => $idGeral,
                                                    'idMolde'  => $idMolde,
                                                    'data'     => $dtMov->format('d/m/Y'),
                                                    'movimento' => $movimento,
                                                    'pecas'    => $totalPecasPedido,
                                                ];
                                                $jaContabilizouParada = true;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                mysqli_free_result($res);
                $mostrarResultados = true;
            }
            mysqli_close($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Itens Concluídos</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="teste/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- bootstrap e css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <?php include 'menu.php';?>
    <main>
        <div class="caixa">
            <form method="POST" action="" id="formRelatorio">
                <div class="data">
                    <div class="form-grup">
                        <label for="data_ini"><i class="fa-regular fa-calendar i-menor"></i> Data de Início:</label>
                        <input type="date" id="data_ini" name="data_ini_html" value="<?= htmlspecialchars($dataIniStr ? date('Y-m-d', strtotime(str_replace('/', '-', $dataIniStr))) : date('Y-m-d')) ?>" required>
                        <input type="hidden" name="data_ini" id="data_ini_hidden">
                    </div>
        
                    <div class="form-grup">
                        <label for="data_fim"><i class="fa-regular fa-calendar i-menor"></i> Data de Fim:</label>
                        <input type="date" id="data_fim" name="data_fim_html" value="<?= htmlspecialchars($dataFimStr ? date('Y-m-d', strtotime(str_replace('/', '-', $dataFimStr))) : date('Y-m-d')) ?>" required>
                        <input type="hidden" name="data_fim" id="data_fim_hidden">
                    </div>
                </div>
        
                <button type="submit" class="botao btn2"><i class="fa-solid fa-magnifying-glass i"></i> Buscar</button>
            </form>
            <?php if ($erro): ?>
                <div class="erro">⚠ <?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>
            <div class="colu-3">
                <?php if ($mostrarResultados): ?>
       
                    <!-- Card Pedidos que Entraram -->
                    <div class="resultado-card entraram">
                        <h2>Entrada</h2>
                        <div class="resultado-numero"><?= $totalPedidosEntraram ?> Pedidos</div>
                        <div class="resultado-numero2"><?= number_format($totalPecasEntraram, 0, ',', '.') ?> Peças</div>
                        <div>
                            <?php if (!empty($detalhesEntraram)): ?>
                                <button type="button" class="botao btn-secundaria" id="btnVerTabelaEntraram">
                                    <i class="fa-solid fa-align-justify"></i> Ver Detalhes
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
        
                    <!-- Card Pedidos Parados -->
                    <div class="resultado-card parados">
                        <h2>Parados</h2>
                        <div class="resultado-numero"><?= $totalPedidosParados ?> Pedidos</div>
                        <div class="resultado-numero2"><?= number_format($totalPecasParados, 0, ',', '.') ?> Peças</div>
                        <div>
                            <?php if (!empty($detalhesParados)): ?>
                                <button type="button" class="botao btn-secundaria" id="btnVerTabelaParados">
                                    <i class="fa-solid fa-align-justify"></i> Ver Detalhes
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Itens Concluídos -->
                    <div class="resultado-card">
                        <h2>Concluídos</h2>
                        <div class="resultado-numero"><?= $totalItensConcluidos ?> Pedidos</div>
                        <div class="resultado-numero2"><?= number_format($totalPecasConcluidos, 0, ',', '.') ?> Peças</div>
                        <div>
                            <?php if (!empty($detalhes)): ?>
                                <button type="button" class="botao btn-secundaria" id="btnVerTabela">
                                    <i class="fa-solid fa-align-justify"></i> Ver Detalhes
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
        
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="modalTabela" class="modal">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">
                <h3 id="modalTitulo"><i class="fa-solid fa-align-justify"></i> Detalhes dos Itens</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div id="conteudoModal">
                    <!-- Conteúdo será inserido via JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Dados PHP para JavaScript
            var detalhes = <?= json_encode($detalhes) ?>;
            var detalhesEntraram = <?= json_encode($detalhesEntraram) ?>;
            var detalhesParados = <?= json_encode($detalhesParados) ?>;

            // Função para gerar tabela
            function gerarTabela(dados, tipo) {
                if (!dados || dados.length === 0) {
                    return '<div class="sem-dados">🔭 Nenhum item encontrado no período selecionado.</div>';
                }

                var html = '<div class="tabela-caixa"><table>';
                
                // Cabeçalho
                html += '<thead><tr>';
                
                var thClass = '';
                if (tipo === 'entraram') thClass = 'entraram';
                if (tipo === 'parados') thClass = 'parados';
                
                html += '<th class="' + thClass + '">Contador</th>';
                html += '<th class="' + thClass + '">OP</th>';
                html += '<th class="' + thClass + '">Item da OP</th>';
                html += '<th class="' + thClass + '">Peças</th>';
                html += '<th class="' + thClass + '">Data</th>';
                
                if (tipo === 'entraram' || tipo === 'parados') {
                    html += '<th class="' + thClass + '">Movimento</th>';
                }
                
                html += '</tr></thead>';

                // Corpo da tabela
                html += '<tbody>';
                dados.forEach(function(item) {
                    html += '<tr>';
                    html += '<td>' + escapeHtml(item.contador) + '</td>';
                    html += '<td><a href="/movimentacao.php?idOp=' + escapeHtml(item.idGeral) + '" target="_blank">' + escapeHtml(item.idGeral) + '</a></td>';
                    html += '<td>' + escapeHtml(item.idMolde) + '</td>';
                    html += '<td style="text-align: center; font-weight: bold;">' + (item.pecas || 0) + '</td>';
                    html += '<td>' + escapeHtml(item.data) + '</td>';
                    
                    if (tipo === 'entraram' || tipo === 'parados') {
                        html += '<td style="max-width: 300px; word-wrap: break-word; font-size: 0.8rem;">' + escapeHtml(item.movimento) + '</td>';
                    }
                    
                    html += '</tr>';
                });
                html += '</tbody></table></div>';

                return html;
            }

            // Função para escape HTML
            function escapeHtml(text) {
                var div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Abrir modal para concluídos
            $('#btnVerTabela').click(function() {
                $('#modalTitulo').text('Detalhes dos Pedidos Concluídos');
                $('#modalHeader').removeClass('entraram parados');
                $('#conteudoModal').html(gerarTabela(detalhes, 'concluidos'));
                $('#modalTabela').fadeIn(300);
                $('body').css('overflow', 'hidden');
            });

            // Abrir modal para pedidos que entraram
            $('#btnVerTabelaEntraram').click(function() {
                $('#modalTitulo').text('Detalhes dos Pedidos que Entraram');
                $('#modalHeader').removeClass('parados').addClass('entraram');
                $('#conteudoModal').html(gerarTabela(detalhesEntraram, 'entraram'));
                $('#modalTabela').fadeIn(300);
                $('body').css('overflow', 'hidden');
            });

            // Abrir modal para pedidos parados
            $('#btnVerTabelaParados').click(function() {
                $('#modalTitulo').text('Detalhes dos Pedidos Parados');
                $('#modalHeader').removeClass('entraram').addClass('parados');
                $('#conteudoModal').html(gerarTabela(detalhesParados, 'parados'));
                $('#modalTabela').fadeIn(300);
                $('body').css('overflow', 'hidden');
            });

            // Fechar modal
            $('.close, .modal').click(function(e) {
                if (e.target === this) {
                    $('#modalTabela').fadeOut(300);
                    $('body').css('overflow', 'auto');
                }
            });

            // Não fechar modal ao clicar no conteúdo
            $('.modal-content').click(function(e) {
                e.stopPropagation();
            });

            // Fechar modal com ESC
            $(document).keydown(function(e) {
                if (e.keyCode === 27) {
                    $('#modalTabela').fadeOut(300);
                    $('body').css('overflow', 'auto');
                }
            });

            // Validação de formulário
            $('#data_ini').change(function() {
                var dataIni = $(this).val();
                var dataFim = $('#data_fim').val();
                
                if (dataIni && dataFim && new Date(dataIni) > new Date(dataFim)) {
                    alert('A data de início não pode ser maior que a data de fim.');
                    $(this).focus();
                }
            });
            
            $('#data_fim').change(function() {
                var dataIni = $('#data_ini').val();
                var dataFim = $(this).val();
                
                if (dataIni && dataFim && new Date(dataIni) > new Date(dataFim)) {
                    alert('A data de fim não pode ser menor que a data de início.');
                    $(this).focus();
                }
            });

            // Validação e conversão de formulário
            $('#formRelatorio').submit(function(e) {
                var dataIniHtml = $('#data_ini').val(); // YYYY-MM-DD
                var dataFimHtml = $('#data_fim').val(); // YYYY-MM-DD
                
                if (!dataIniHtml || !dataFimHtml) {
                    alert('Por favor, preencha ambas as datas.');
                    e.preventDefault();
                    return false;
                }
                
                if (new Date(dataIniHtml) > new Date(dataFimHtml)) {
                    alert('A data de início não pode ser maior que a data de fim.');
                    e.preventDefault();
                    return false;
                }
                
                // Converter YYYY-MM-DD para DD/MM/YYYY
                function converterParaBR(dataHtml) {
                    if (!dataHtml) return '';
                    var partes = dataHtml.split('-');
                    return partes[2] + '/' + partes[1] + '/' + partes[0];
                }
                
                // Definir valores nos campos hidden
                $('#data_ini_hidden').val(converterParaBR(dataIniHtml));
                $('#data_fim_hidden').val(converterParaBR(dataFimHtml));
                
                return true;
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
