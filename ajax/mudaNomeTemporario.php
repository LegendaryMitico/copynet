<?php
include_once("../php/connect.php");
ini_set('default_charset','UTF-8'); //encoding
mysqli_set_charset($conn, "utf8");
session_start();                            
                            $queryP = "SELECT * FROM pedidos WHERE idPedido='$_POST[idTemp]'";
                            $resultado_ped = mysqli_query($conn, $queryP);
                            $venda = mysqli_fetch_assoc($resultado_ped);
                            $pedidoTemp = json_decode($venda['pedidoJson']);
                            $pedidoTemp->contato->nome = $_POST['nomeTemp'];
                            $pedidoTemp = json_encode($pedidoTemp,JSON_UNESCAPED_UNICODE);
                            //print_r($pedidoTemp);
                            
    
                            $queryAt = "UPDATE `pedidos` SET `pedidoJson`='$pedidoTemp' WHERE idPedido='".$_POST['idTemp']."'";
                            mysqli_query($conn, $queryAt);
?>