<?php  
    require('../php/connect.php');
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    $token = $resultadot["valor"];
//--------------------------------//só pegando o token do data base
    ini_set('default_charset','UTF-8'); //encoding
    //var_dump($resultadot);
    
    $string = $_POST['codigoCliente'];
    //$string = '16291696984';
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.bling.com.br/Api/v3/contatos/'.$string,
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
    //var_dump($response[0]);
    curl_close($curl);
    $properties = get_object_vars($response); //hehe converte valores do objeto em posições acessáveis
    //print_r($properties);
    //pegando o endereço
    $end = get_object_vars($properties['data']->endereco); 
    
    echo(str_replace(',','',$properties['data']->nome).','.str_replace(',','',$properties['data']->fantasia).','.$properties['data']->tipo.','.$properties['data']->numeroDocumento.','.$properties['data']->indicadorIe.','.$properties['data']->ie.','.$end['geral']->cep.','.str_replace(',','',$end['geral']->endereco).','.$end['geral']->uf.','.$end['geral']->municipio.','.$end['geral']->bairro.','.$end['geral']->numero.','.str_replace(',','',$end['geral']->complemento).','.$properties['data']->telefone.','.$properties['data']->celular.','.$properties['data']->email.','.$properties['data']->id.','.$properties['data']->codigo);
?>
