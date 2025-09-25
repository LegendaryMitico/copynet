<?php   
    require('../php/connect.php');
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    $token = $resultadot["valor"];
//--------------------------------//só pegando o token do data base

    ini_set('default_charset','UTF-8'); //encoding
    
    $string = $_POST['nome'];
    $string = str_replace(' ', '%20', $string);
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.bling.com.br/Api/v3/contatos?limite=100&pesquisa='.$string,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST=> 'GET',
    CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'Authorization: Bearer '.$token
    ),
    ));
    $response = json_decode(curl_exec($curl));
    curl_close($curl);
    //print_r($response);
    // ---------------------------------------------------------
    $nomes = [];
    foreach ($response as $key => $value) {
    if($key=='data'){
        foreach ($value as $key2 =>$value2){
            array_push($nomes,(str_replace(',','',$value2->nome).'|'.$value2->id));
        }
        }
    }
    print_r(json_encode($nomes,JSON_UNESCAPED_UNICODE));
?>