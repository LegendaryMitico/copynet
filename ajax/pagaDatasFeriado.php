<?php
include_once("../php/connect.php");
ini_set('default_charset','UTF-8'); //encoding
mysqli_set_charset($conn, "utf8");
session_start();                            
                            $queryP = "SELECT feriados FROM sistema WHERE id='1'";
                            $resultado_ped = mysqli_query($conn, $queryP);
                            $venda = mysqli_fetch_assoc($resultado_ped);
                            print_r($venda['feriados']);
?>