<?php
    require('../php/connect.php');
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    $token = $resultadot["valor"];
//--------------------------------//só pegando o token do data base

//obtém os produtos dentro de uma categoria
        $curl = curl_init();
        $idCategoriaPai = '';
        if(isset($_POST['idCatPai'])){
            $idCategoriaPai = $_POST['idCatPai'];
        }
        $codPro = $_POST['codProd'];
        $nome = $_POST['codProd'];
        $tipo = 'P';
        $url = 'codigo='.$codPro;
        if($_POST['tipoBusca']=='Nome'){
            $url = 'nome='.str_replace(' ', '%20',$nome);
        }
        
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/produtos?limite=100&'.$url.'&pagina='.$_POST["paginaAtual"].'&tipo='.$tipo.'&idCategoria='.$idCategoriaPai,
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
        ));
        $response = curl_exec($curl);
        print_r($response);                        
        curl_close($curl);
?>
