<?php 
ini_set('error_reporting', E_ALL);// mesmo resultado de: error_reporting(E_ALL);
ini_set('display_errors', 1);
    include_once("../php/connect.php");
    $query = "SELECT * FROM Esteira";
    $resultado = mysqli_query($conn, $query);

    while (($data = mysqli_fetch_assoc($resultado))){
     $pedido = $data;
    }
    echo($pedido);
?>

<script>
    var pedido = JSON.parse(JSON.stringify(<?php echo(json_encode($pedido)); ?>));
    //pedido.produtos  = {};
    console.log(pedido);
</script>