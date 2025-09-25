<?php
    require('../php/connect.php');
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    $token = $resultadot["valor"];
//--------------------------------//só pegando o token do data base
$idContato = $_POST['idCli'];

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://www.bling.com.br/Api/v3/contatos/'.$idContato.'',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'PUT',
  CURLOPT_POSTFIELDS =>'{
  "id": "",
  "nome":"'.$_POST['nomeCliente'].'",
  "codigo": "'.$_POST['codigoCl'].'",
  "situacao": "A", 
  "numeroDocumento":"'.$_POST['clienteCpf'].'",
  "telefone":"'.$_POST['inputTelefone'].'",
  "celular":"'.$_POST['inputCelular'].'",
  "fantasia":"'.$_POST['nomeFantasia'].'",
  "tipo": "'.$_POST['tipoPessoa'].'",
  "indicadorIe":"'.$_POST['inputCont'].'",
  "ie":"'.$_POST['inscEstadual'].'",
  "rg": "",
  "orgaoEmissor": "",
  "email":"'.$_POST['inputEmail'].'",
  "endereco": {
    "geral": {
      "endereco":"'.$_POST['endereco'].'",
      "cep":"'.$_POST['inputCep'].'",
      "bairro":"'.$_POST['inputBairro'].'",
      "municipio":"'.$_POST['inputCidade'].'",
      "uf":"'.$_POST['inputUf'].'",
      "numero":"'.$_POST['numero'].'",
      "complemento":"'.$_POST['complemento'].'"
    }
  }
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer '.$token.''
  ),
));

$response = curl_exec($curl);
print_r($response);
curl_close($curl);


?>
