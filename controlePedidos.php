<?php
session_start();

include_once("php/connect.php"); //conexão
include_once("funcoes.php"); //todas as funções

date_default_timezone_set('America/Manaus');
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

<!doctype html>
<html lang="pt-br">
<head>
<link rel="icon" href="img/icon.png">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Painel de Controle de Email</title>

<link rel="icon" href="img/favicon.png">
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script src="js/jquery.js"></script>

<script src="js/xepOnline.jqPlugin.js"></script>

<!-- bootstrap e css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

<!-- font awersome -->
<script src="https://kit.fontawesome.com/704200b128.js" crossorigin="anonymous"></script>
<!-- uma porrada de coisa q talvez precise -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" >
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 
</head>

<body>
<style type="text/css">
  @font-face {
  font-family: 'Montserrat', sans-serif;
  src: url("font/Montserrat-Light.ttf");
  }

  body{
    background-color:rgb(31, 31, 31);

  }
  /* Modify the background color */
         
        .navbar-custom {
            background-color: black;
        }
  /* Modify brand and text color */
         
        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-text {
            color: white;
        }


</style>
<?php include 'menu.php';

$setorAtual16 = "`setorAtual` != '16'";
// Pedidos dos clientes
$queryPedidos = "SELECT DISTINCT `idGeral`, `cliente`, `setorAtual` FROM `Esteira` WHERE $setorAtual16 GROUP BY `idGeral`, `cliente` ORDER BY `idGeral` DESC";
$resultPedidos = mysqli_query($conn, $queryPedidos);
?>

<div class="container-fluid mt-12">
    <table class="table" style="color: #dddddd; border-color: grey">
        <thead>
        <tr>
            <th scope="col" style='text-align: center;'>Pedido</th>
            <th scope="col">Cliente</th>
            <th scope="col">Status - OP</th>
            <th scope="col">Notificação</th>
            <th scope="col">Celular</th>
            <th scope="col">Ação</th>
        </tr>
        </thead>
        <tbody>

        <?php
        // Preencher a tabela usando um loop
        while ($rowPedidos = mysqli_fetch_assoc($resultPedidos)) {
            $itens = array();
            $status = array();
            
            // Obtém os setores relacionados ao pedido
            $queryItens = "SELECT `setorAtual` FROM `Esteira` WHERE `idGeral` = '{$rowPedidos['idGeral']}'";
            $resultItens = mysqli_query($conn, $queryItens);
            while ($rowItens = mysqli_fetch_assoc($resultItens)) {
                $itens[] = $rowItens['setorAtual'];
            }

            // Verifica o valor de setorAtual para determinar o status
            $todosSetoresValidos = true;
            foreach ($itens as $setor) {
                if ($setor != 9 && $setor != 16) {
                    $todosSetoresValidos = false;
                    break;
                }
            }
            $status = $todosSetoresValidos ? 'Concluído' : 'Em produção';

            if($status == 'Concluído') {
                // Obtém a situação da notificação
                $querySituacao = "SELECT `situacao` FROM `notificacao` WHERE `op` = '{$rowPedidos['idGeral']}'";
                $resultSituacao = mysqli_query($conn, $querySituacao);

                // Obtém a situação da telefone
                $queryTelefone = "SELECT `telefone` FROM `notificacao` WHERE `op` = '{$rowPedidos['idGeral']}'";
                $resultTelefone = mysqli_query($conn, $queryTelefone);
            
                $situacao = ($resultSituacao && $rowSituacao = mysqli_fetch_assoc($resultSituacao)) ? $rowSituacao['situacao'] : 'Pendente';
                $telefone = ($resultTelefone && $rowTelefone = mysqli_fetch_assoc($resultTelefone)) ? $rowTelefone['telefone'] : '';

                if($situacao == 'Pendente') {$cor1 = 'yellow';}
                if($situacao == 'Notificado') {$cor1 = 'lime';}
                if($situacao == 'Sem email') {$cor1 = 'red';}
                
                if($status == 'Em produção') {$cor2 = 'red';}
                if($status == 'Concluído') {$cor2 = 'lime';}

                echo "<tr class='align-middle'>";
                echo "<td style='text-align: center;'><a href='movimentacao.php?idOp={$rowPedidos['idGeral']}' target='_blank' style='color:#dddddd;text-decoration:none;'>" . $rowPedidos['idGeral'] . "</a></td>";            echo '<td>' . $rowPedidos['cliente'] . '</td>';
                echo "<td style='color: $cor2'>" . $status . "</td>";
                echo "<td style='color: $cor1'>" . $situacao . "</td>";

                echo "<td>".$telefone."</td>";

                echo "<td>";
                if ($_SESSION['usuarioNivelAcesso']==0 || $_SESSION['setor'] == 9) { 
                echo '<a href="ajax/enviaEmail.php?idGeral=' . $rowPedidos['idGeral'] . '" class="btn btn-primary">Notificar</a>';
                }
                echo "</td>";
                echo '</tr>';
            }
        }

        // Limpa a referência do último elemento
        unset($rowPedidos);
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
