<?php
include_once("php/connect.php");
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    //var_dump($resultadot);
    $token = $resultadot["valor"];
    
$tecidotratado = "";
$malhatratado = "";
    
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
echo('<select required="required" class="form-select" aria-label="Default select example" style="color:grey; font-size:12px" name="tipo_tecido" >');
         foreach($jsonde->data as $tecidos){
//print_r($tecidos)
         print ('<option value="'.$tecidos->id.'">'.ucfirst(strtolower($tecidos->nome." - ").$tecidos->codigo).'</option>');
         }
    $json2 = $response2;
        $jsonde2 = json_decode($json2);
//print_r($jsonde)
        foreach($jsonde2->data as $malhas){
        // $malhatratado =  {$malhatratado.$malhas->nome .":".$malhas->codigo};
        print ('<option value="'.$malhas->id.'">'.ucfirst(strtolower($malhas->nome." - ").$malhas->codigo).'</option>');
         }
          echo('</select>');
$tecidosemalhas = $tecidotratado.$malhatratado;

print_r($tecidosemalhas."<BR>")
//print_r($tecidos)
    //echo $malhas->nome ." - ".$malhas->codigo."<BR>";
?>