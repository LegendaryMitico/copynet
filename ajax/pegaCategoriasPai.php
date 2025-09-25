<?php
    require('../php/connect.php');
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    $apiToken = $resultadot["valor"];
    //--------------------------------//só pegando o token do data base
    
    // Configurações da API
    $apiUrlBase = "https://www.bling.com.br/Api/v3/categorias/produtos";
    $limite = 100;
    $pagina = 1;
    $todasCategorias = []; // Array consolidado para armazenar todas as categorias

    // Função para fazer a requisição para uma página específica
    function fetchPaginaCategorias($url, $pagina, $limite, $token) {
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => "$url?pagina=$pagina&limite=$limite",
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
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return null;
        } else {
            return json_decode($response, true);
        }
    }

    // Loop para buscar todas as páginas até que a resposta seja vazia
    while (true) {
        $response = fetchPaginaCategorias($apiUrlBase, $pagina, $limite, $apiToken);
        
        if (!$response || empty($response['data'])) {
            break; // Sai do loop se não houver mais dados
        }

        // Adiciona os dados da página atual ao array consolidado
        $todasCategorias = array_merge($todasCategorias, $response['data']);
        $pagina++; // Incrementa para a próxima página
    }

    // Retorna o JSON consolidado com todas as categorias em um único "data"
    header('Content-Type: application/json');
    echo json_encode(['data' => $todasCategorias], JSON_PRETTY_PRINT);
?>
