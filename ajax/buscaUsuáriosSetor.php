<?php    
  
    require('../php/connect.php');
    session_start();
    //print_r($_SESSION[setor]);
    $query = "SELECT * FROM `usuarios` WHERE setor = $_SESSION[setor]";
    $resultado_ = mysqli_query($conn, $query);
    $vendedores = [];
    while (($data = mysqli_fetch_assoc($resultado_)))
        {
           array_push($vendedores,[$data['id_usuario'],$data['nome_usuario']]);
        }
    print_r(json_encode($vendedores));    
?>