<?php
include_once("../php/connect.php");
ini_set('default_charset','UTF-8'); //encoding
mysqli_set_charset($conn, "utf8");
session_start();                            
                        
                            $datasTemp = json_encode($_POST['datasTemp'],JSON_UNESCAPED_UNICODE);
                            $queryAt = "UPDATE `sistema` SET `feriados`='$datasTemp' WHERE id='1'";
                            mysqli_query($conn, $queryAt);
                            print('dados inseridos');
?>