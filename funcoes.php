<?php
//echo('hehe');
//função que pega o estoque diretamente do bling
function pegaEstoque($token,$idProduto){
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.bling.com.br/Api/v3/estoques/saldos?idsProdutos[]='.$idProduto,
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
    
    $response = curl_exec($curl);
    $json = json_decode($response);
    //echo($response);
    foreach ($json as $key => $value) {
        if($key=='data'){
            foreach ($value[0] as $key2 =>$value2){
                if($key2 == 'saldoFisicoTotal'){
                    return($value2);
                }
            }
        }
    }
    curl_close($curl);
}

function baixaEstoque($token,$idProduto,$quantidade,$deposito){
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.bling.com.br/Api/v3/estoques',
    CURLOPT_RETURNTRANSFER=>true,
    CURLOPT_ENCODING=>'',
    CURLOPT_MAXREDIRS=>10,
    CURLOPT_TIMEOUT=>0,
    CURLOPT_FOLLOWLOCATION=>true,
    CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST=>'POST',
    CURLOPT_POSTFIELDS=>'{
    "deposito":{
    "id":'.$deposito.'
    },
    "operacao":"S",
    "produto":{
    "id":'.$idProduto.'
    },
    "quantidade":'.$quantidade.',
    "preco":"<float>",
    "custo":"<float>",
    "observacoes":"<string>"
    }',
    CURLOPT_HTTPHEADER=>array(
    'Content-Type:application/json',
    'Accept:application/json',
    'Authorization: Bearer '.$token.''
    ),
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);
    //echo $response;
}



//função que calcula o gasto de tecido (dados vindo do banco de dados local)
function calculaTecido($qtdTotal,$gastoUnitario,$tipoCalculo){
    if($tipoCalculo=='masculina' || $tipoCalculo=='feminino' || $tipoCalculo=='infantojuvenil' || $tipoCalculo=='plussize'){
        //ECHO('debug: '.$qtdTotal.','.$gastoUnitario.','.$tipoCalculo);
        return $qtdTotal*$gastoUnitario;
}
    //else return 0
}

function trataZero($numero){
    if($numero==0){
        return $numero;
    }else{
        return ltrim($numero, "0");
    }
}
function pegaToken($conn){
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    //var_dump($resultadot);
    return($resultadot["valor"]);
}

function pegaPai($token){
    //pai de tecidos
        $curl = curl_init();
                                
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/produtos?criterio=2&tipo=C&idCategoria=7649892&&nome=%20',
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
                                
        curl_close($curl);
        
        //pai de malhas                      
        $curl = curl_init();
                                
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/produtos?criterio=2&tipo=C&idCategoria=7847532&&nome=%20',
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
        $response2 = curl_exec($curl);
                                
        $json = $response;
        $jsonde = json_decode($json);
        //print_r($jsonde)
        
        //gera select
        echo('<select required="required" class="form-select" aria-label="Default select example" style="color:grey; font-size:12px" name="tipo_tecido" >');
        foreach($jsonde->data as $tecidos){
            //print_r($tecidos)
            print ('<option value="'.$tecidos->nome.','.$tecidos->id.'">'.ucfirst(strtolower($tecidos->nome." - ").$tecidos->codigo).'</option>');
            }
            $json2 = $response2;
            $jsonde2 = json_decode($json2);
            //print_r($jsonde)
            foreach($jsonde2->data as $malhas){
            // $malhatratado =  {$malhatratado.$malhas->nome .":".$malhas->codigo};
                    print ('<option value="'.$malhas->nome.','.$malhas->id.'">'.ucfirst(strtolower($malhas->nome." - ").$malhas->codigo).'</option>');
            }
            echo('</select>');
}

function pegaFilho($token,$idPai){
   
    $curl = curl_init();
                                    
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.bling.com.br/Api/v3/produtos/variacoes/'.$idPai,
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
    $json = $response;
    $jsonde = json_decode($json);
    //print_r($jsonde)
    echo('<select required="required" class="form-select" aria-label="Default select example" style="color:grey; font-size:12px" name="cor_tecido" >');
    foreach ($jsonde as $key => $value) {
    if($key=='data'){
        foreach ($value as $key2 =>$value2){
        if($key2 == 'variacoes'){
                //print_r($value2);
                //echo('<br>');
                foreach($value2 as $key3=> $value3){
                //if($key3 == 0){
                //print_r($value2[0]->nome);
                //print_r($value2[$key3]->id);
                print ('<option value="'.$value2[$key3]->id.','.$value2[$key3]->nome.'">'.ucfirst(strtolower($value2[$key3]->nome)).'</option>');
                //echo('<br>'); 
                }
                                                  
            }
        }
    }
}
    echo('</select>');
}

function atualizaCustos($conn,$dados){
    $custoquery = "UPDATE `custos` SET `atelier`=".($dados->atelier).",`corte`=".($dados->corte).",`impressao`=".($dados->impressao).",`serigrafia`=".($dados->serigrafia).",`bordado`=".($dados->bordado).",`expedicao`=".($dados->expedicao).",`estamparia`=".($dados->estamparia).",`pcp`=".($dados->pcp).",`impostos`=".($dados->impostos).",`fabrica`=".($dados->fabrica);
    //var_dump($custoquery);
    $roda_query = mysqli_query($conn, $custoquery);
}

function pegaCustos($conn){
    $custoquery = "SELECT * FROM `custos`";  
    $roda_query = mysqli_query($conn, $custoquery);
    $resultado = mysqli_fetch_assoc($roda_query);
    return($resultado);
}

function pegaAplicacoes($token,$idCategoria){
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.bling.com.br/Api/v3/produtos?idCategoria='.$idCategoria,
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
    $response = curl_exec($curl);
    return($response);
}

function pegaContatos($token,$string){
    $pagina = 1;
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.bling.com.br/Api/v3/contatos?pesquisa='.$string.'&pagina='.$pagina.'&limite=1000',
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
    $response = curl_exec($curl);
    return(json_decode($response));
}

function geraLista($lista){
    $nomes = [];
foreach ($lista as $key => $value) {
    if($key=='data'){
        foreach ($value as $key2 =>$value2){
            array_push($nomes,$value2->nome);
        }
    }
    }
    return($nomes);
}

function pegaCatPai($token){
    $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.bling.com.br/Api/v3/categorias/produtos?pagina=1&limite=100',
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
        return($response);
        curl_close($curl);
}
    
?>