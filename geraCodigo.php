<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.13/vue.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    function cadastrado(nome){
        console.log('foi');
        console.log(nome);
        alert("✔Código e produto armazenado com sucesso");
    }
    
    function falhaCadastro(nome){
        console.log('n foi');
        alert("❌Código já cadastrado");
        console.log(nome);
    }
</script>

<?php
session_start();
//Incluindo a conexão com banco de dados   
include_once("php/connect.php");

// Função para gerar código aleatório de 8 caracteres (letras maiúsculas e números)
function gerarCodigo() {
    // Caracteres permitidos (letras maiúsculas e números)
    $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    // Gerar o código com 8 caracteres
    for ($i = 0; $i < 8; $i++) {
        $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $codigo;
}
// Inicializar variável para armazenar o código
$codigo = "";
$produto = '';
$cor = 'black';
// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Se o botão "Criar" for clicado, gerar um novo código
    if (isset($_POST["criar"])) {
        $codigo = gerarCodigo();
        $produto = '';
    }
    // Se o botão "Salvar" for clicado, obter o código do campo de entrada
    elseif (isset($_POST["salvar"])){
        $codigo = $_POST["codigo"];
        $produto = $_POST["nomeProduto"];
        //verificando se já tem no db
        
        $tokenquery = "SELECT * FROM `codigos` WHERE codigo = '$codigo'";
        $resultado_token = mysqli_query($conn, $tokenquery);
        if(mysqli_num_rows($resultado_token)<=0){
             $cor = 'green';
            $tokenquery2 = "INSERT INTO `codigos`(`codigo`, `descricao`) VALUES ('$codigo','$produto')";
            $resultado_token2 = mysqli_query($conn, $tokenquery2);
            
            echo '<script type="text/javascript">',
                 'cadastrado("'.$produto.'");',
                 '</script>';
        }else{
             $cor = 'red';
            echo '<script type="text/javascript">',
                 'falhaCadastro("'.$produto.'");',
                 '</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Criar e Salvar Código</title>
    <style>
        body {
           
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            flex-direction:column;
        }
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        input[type=text] {
            width: 400px;
            padding: 10px;
            margin: 10px;
            display: block;
            margin: 0 auto;
        }
        button {
            padding: 10px 20px;
            margin: 10px;
            cursor: pointer;
            border-radius:12px;
        }
        table, th, tr, td{
            border: 2px solid black;
            border-collapse: collapse;
            text-color:white;
        }
    </style>
</head>
<body>

<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <center><img src='img/icone.png' width='335px' style='margin-bOttom:25px'></center>
    <!-- <input type='text' name="nomeProduto" value="<?php echo $produto;?>" style='color:<? echo $cor;?>' placeholder="Nome do produto"><br> -->
    <?php echo("<input type='text' name='nomeProduto' value='".$produto."' style='color:".$cor.";border-radius:10px' placeholder='Nome do produto'><br>");?>
    
    <?php echo("<input class='codiguin' type='text' name='codigo' value='".$codigo."' style='color:".$cor.";border-radius:10px' placeholder='Código gerado' readonly>");?>
    <br>
    <button type="submit" name="criar" id='' style='background-color:#4B4B4D;color:white'>Criar</button>
    <button type='submit' name="salvar" id='btnSalvar' style='background-color:#4B4B4D;color:white' onclick="javascript:teste();">Salvar</button>
</form>
<br>
<div style='width:50%;display:flex;color:white;flex-direction:column;'>

<?php
    echo("<table><tr><th>SKU</th><th>Descrição</th></tr>");
    
    $tokenquery3 = "SELECT * FROM `codigos`";
    $resultado_token3 = mysqli_query($conn, $tokenquery3);
    while($row = mysqli_fetch_assoc($resultado_token3)){
        echo("<tr><td>".$row['codigo']."</td><td>".$row['descricao']."</td></tr>");
    }
    echo("</table>");
?>
</div>
</body>
</html>





