<?php

$authorize_url = "https://www.bling.com.br/OAuth2/views/authorization.php"; //https://www.bling.com.br/Api/v3/oauth/authorize <-postman
$state = "7cdc041f9157ccbedba4f70a51d5de43"; //
$token_url = "https://www.bling.com.br/Api/v3/oauth/token"; //https://www.bling.com.br/Api/v3/oauth/token
$scopes = "";

//	callback URL specified when the application was defined--has to match what the application says
$callback_uri = "https://rksrodrigues.com";

//	client (application) credentials - located at apim.byu.edu
$client_id = "b60c2f3c9fe867b9c4251bf16e2cfedad88adbec";
$client_secret = "7b42a1a5029cb500ed0a2a9c981718f30fa9d2c6d729e66a6b9c133485d2";

if ($_POST["authorization_code"]) {
	//what to do if there's an authorization code
	$access_token = getAccessToken($_POST['authorization_code']);
} elseif ($_GET["code"]) {
	$access_token = getAccessToken($_GET["code"]);
    //salvando token no database 
	include_once("php/connect.php");
    $tokenquery2 = "UPDATE token SET valor='$access_token' WHERE id=1";
    $resultado_token2 = mysqli_query($conn, $tokenquery2);
	header("Location: " . $callback_uri."?token=".$access_token);
} else {
	//	what to do if there's no authorization code
	getAuthorizationCode();
}

//	step A - simulate a request from a browser on the authorize_url
//		will return an authorization code after the user is prompted for credentials
function getAuthorizationCode() {
	global $authorize_url, $client_id, $callback_uri,$state,$scopes;

	$authorization_redirect_url = $authorize_url . "?response_type=code&client_id=" . $client_id . "&state=". $state ."&scopes=". $scopes;
    echo($authorization_redirect_url);
	header("Location: " . $authorization_redirect_url);
	//if you don't want to redirect
//	echo "Go <a href='$authorization_redirect_url'>here</a>, copy the code, and paste it into the box below.<br /><form action=" .$_SERVER["PHP_SELF"]." method = 'post'><input type='text' name='authorization_code' /><br /><input type='submit'></form>";
}

//	step I, J - turn the authorization code into an access token, etc.
function getAccessToken($authorization_code) {
	global $token_url, $client_id, $client_secret, $callback_uri;

	$authorization = base64_encode("$client_id:$client_secret");
	$header = array("Authorization: Basic {$authorization}","Content-Type: application/x-www-form-urlencoded");
	$content = "grant_type=authorization_code&code=$authorization_code";

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $token_url,
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $content
	));
	$response = curl_exec($curl);
	curl_close($curl);

	if ($response === false) {
		echo "Failed";
		echo curl_error($curl);
		echo "Failed";
	} 
	//elseif (json_decode($response)->error) {
		//echo "Error:<br />";
		//echo $authorization_code;
	//	echo $response;
	//}

	return json_decode($response)->access_token;
}
?>
