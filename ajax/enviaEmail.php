<?php
//dados de data e hora
date_default_timezone_set('America/Manaus'); //horário
session_start(); //pelo nome do cliente

//esse script é usado para mover a op entre os setores, muita atenção aqui pois tem controle de visibilidade
include_once("../php/connect.php");
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$idOp = $_GET['idGeral'];
//$idOp = '999';

// Obtém a situação da notificação
$querySituacao = "SELECT `situacao` FROM `notificacao` WHERE `op` = '$idOp'";
$resultSituacao = mysqli_query($conn, $querySituacao);

//=========================IMAGEM=================================
$queryPedidos = "SELECT `idMolde`, `imagem` FROM `Esteira` WHERE `idGeral` = '$idOp'";
$resultPedidos = mysqli_query($conn, $queryPedidos);

$queryPedidos2 = "SELECT `pedidoJson`FROM `Esteira` WHERE `idGeral` = '$idOp'";
$resultPedidos2 = mysqli_query($conn, $queryPedidos2);
$jsonAtual = mysqli_fetch_assoc($resultPedidos2);
$idCliente = json_decode($jsonAtual['pedidoJson']);

//print_r($idCliente->contato->id);
$moldes = array();
while ($rowPedidos = mysqli_fetch_assoc($resultPedidos)) {
    $moldes[] = $rowPedidos;
}
function unicodeToChar($match) { return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE'); }

$tokenquery = "SELECT valor FROM token WHERE id=1";
$resultado_token = mysqli_query($conn, $tokenquery);
$resultadot = mysqli_fetch_assoc($resultado_token);
$token = $resultadot["valor"];

//========================EMAIL==================================
ini_set('default_charset', 'UTF-8'); //encoding

$string = $idCliente->contato->id;
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.bling.com.br/Api/v3/contatos/' . $string,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ),
));
$contatoCliente = json_decode(curl_exec($curl));
curl_close($curl);
$telefone = $contatoCliente->data->celular;

if ($contatoCliente->data->email != '') {
    //echo "Email: " . $contatoCliente->data->email;
    $mensagem = "
    <html>
    <head>
        <style>
            table { width: 80%; border: 1px solid grey; margin: auto; }
            img { width: 100%; }
            td { padding: 0px; text-align: center; }
            #header { background-color: black; color: white; font-size: 14px; }
            .black { background-color: #000; color: #fff; }
        </style>
    </head>
    <body>
        <center>
            <table>
                <tr>
                    <td id='header'>
                        <br>
                        <center>
                            <img src='https://personalconfeccoes.com.br/cdn/shop/files/logo_95x@2x.png?v=1695739095' style='margin-bottom: 10px; width: 100px;'>
                            <h2>O pedido #$idOp está pronto para retirada</h2>
                        </center>
                        <br>
                    </td>
                </tr>
            </table>
        </center>";

        $mensagem .= '<table><tr>';

$iTemp = 1;
foreach ($moldes as $index) 
{
    $idMoldes = $index['idMolde'];

    $idMoldes = is_array($idMoldes) ? $idMoldes : [$idMoldes];
    
    
        $imagem = $index['imagem'];
        $imagem = "https://rksrodrigues.com/" . ltrim($imagem);
        $imagem = str_replace(' ', '%20', $imagem);
        $imagem = preg_replace_callback("/u([0-9A-Fa-f]{4})/", "unicodeToChar", $imagem);

        

        $mensagem .= "
            <td>
                <center>
                    <div class='black' style='font-size: 20px;'>#{$index['idMolde']}</div><br>
                    <img src=\"$imagem\"  style='width:100%;'>
                </center>
            </td>"; 
            if ($iTemp==3) { 
                $mensagem .= "</tr><tr>"; 
                $iTemp = 0;
            }
    $iTemp+=1;
}

$mensagem .= '</tr></table>';
        
        $mensagem .= "
                    <center>
                    <table>
                        <tr>
                            <td>
                            <div style='border: 1px solid black; text-align: center; font-size: 14px; padding: 20px 5px'>
                            Nosso horário de funcionamento é de segunda a sexta de 8h às 12h/14h às 18h e sábado de 08h às 12h.<br />
                            - É necessário a apresentação da ORDEM DE PEDIDO para retirada do produto.<br />
                            - A Personal Confecções garante a qualidade do seu pedido no prazo de até 90 dias.<br />
                            - Peças que se desgastam naturalmente com seu uso regular estão excluídas desta garantia.<br />
                            - Os produtos não retirados no período de 60 dias serão dispensados para doação.<br />
                            - Pedidos com pagamento em aberto após 30 dias, mesmo que não tenha sido feita a retirada serão encaminhados para protesto.
                        </div>
                        <div style='color:red'>ENDEREÇO DE RETIRADA: Av. Via da Flores, 2077 - Pricumã</div>
                            </td>
                        </tr>
                    </table>
                </center>
            </body>
            </html>
        ";
        
        if (mysqli_num_rows($resultSituacao) > 0){
            // Já existe uma notificação, então atualiza a situação
            $queryInsercao = "UPDATE `notificacao` SET `situacao` = 'Notificado', `telefone` = '$telefone' WHERE `op` = '$idOp'";
        } else {
            $queryInsercao = "INSERT INTO `notificacao`(`id`, `op`, `situacao`, `telefone`) VALUES ('',$idOp,'Notificado','$telefone')";
        }
        //echo('<br>Notificado:'.$queryInsercao);;
} else {
    $contatoCliente->data->email = 'expedicao@rksrodrigues.com';
    $mensagem = "<html><body>
            <center>
                <table width='50%' border='0' cellpadding='15px' style='border:1px solid grey;border-radius:5px'>
                    <tr>
                        <td style='background-color:black;font-size:14px;color:white;text-align:center;border-radius:5px'><b>
                            <center><img src='https://personalconfeccoes.com.br/cdn/shop/files/logo_95x@2x.png?v=1695739095' style='width:75px;'></center><br>
                            <span>Pedido #".$idOp."</span></b>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style='border:1px solid red;text-align:center;font-size:18px; color: red'>
                        Cliente não tem email cadastrado.<br>
                        Telefone: " .$telefone. "
                        </td>
                    </tr>
                </table>
            </center>
        </body></html>";
        if (mysqli_num_rows($resultSituacao) > 0){
            $queryInsercao = "UPDATE `notificacao` SET `situacao` = 'Sem email', `telefone` = '$telefone' WHERE `op` = '$idOp'";
        } else {
            $queryInsercao = "INSERT INTO `notificacao`(`id`, `op`, `situacao`, `telefone`) VALUES ('',$idOp,'Sem email', '$telefone')";   
        }
        //echo('<br>Sem email:'.$queryInsercao);
}


$executaNotificado = mysqli_query($conn, $queryInsercao);

//parte que pega dados do cliente para enviar email, atenção aqui pois ocorre disparos de emai
ini_set( 'display_errors', 1 );
//error_reporting( E_ALL );
//dados recebidos dinamicamente de acordo com o pedido
$idPedido = $idOp;
$to = $contatoCliente->data->email; //quem recebe

//==============================================================================================================
$mail = new PHPMailer;
$mail->isSMTP();
//$mail->SMTPDebug = 2;
$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'expedicao@rksrodrigues.com';
$mail->Password = 'Personal_2@2@';
$mail->setFrom('expedicao@rksrodrigues.com', 'Personal Confecções');
$mail->addReplyTo('expedicao@rksrodrigues.com', 'Personal Confecções');
$mail->addAddress($contatoCliente->data->email, 'Recebe a mensagem');
$mail->Subject = 'O pedido #'.$idPedido.' está pronto para retirada 🚀';
$mail->msgHTML($mensagem);
$mail->Body = $mensagem;
$mail->CharSet = "UTF-8";

if (!$mail->send()) {
    //echo 'Email Erro: ' . $mail->ErrorInfo;
} else {
    // echo 'A mensagem de e-mail foi enviada.';
    $path = '{imap.hostinger.com:993/imap/ssl}INBOX.Sent';

    // Informe ao seu servidor para abrir uma conexão IMAP usando o mesmo nome de usuário e senha que você usou para o SMTP
    $imapStream = imap_open($path, $mail->Username, $mail->Password);

    if (!$imapStream) {
        //echo 'Erro na conexão IMAP: ' . imap_last_error();
    } else {
        // Adicione a mensagem à pasta de enviados via IMAP
        $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
        
        if (!$result) {
            //echo 'Erro ao salvar o e-mail na pasta "Enviados": ' . imap_last_error();
        } else {
            //echo 'O e-mail enviado foi salvo na pasta "Enviados".';
        }
        imap_close($imapStream);
    }
}

?>
<script>
var email = <?php echo json_encode($to); ?>;
if(email != '' && email != 'expedicao@rksrodrigues.com'){
    confirm("Email enviado com sucesso para " + email);
} else {
    confirm("O cliente informado não possui email");
}
window.open('../controlePedidos.php','_self')
</script>
