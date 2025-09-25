<?php
//===========================================================================================================================//
    //$token = "f5ea9aef92d1489105b8f092e6ffeed104be66e7";

    session_start();
    //Incluindo a conexão com banco de dados   
    include_once("php/connect.php");
    $tokenquery = "SELECT valor FROM token WHERE id=1";
    $resultado_token = mysqli_query($conn, $tokenquery);
    $resultadot = mysqli_fetch_assoc($resultado_token);
    //var_dump($resultadot);
    $token = $resultadot["valor"];
    //tratando o post do tecido pai 
    //var_dump('cor tecido:',$_POST['cor_tecido']);
    if(isset($_POST['tipo_tecido'])){
    $dado_molde = explode(",",$_POST['tipo_tecido']);
    $nome_tecido_pai = $dado_molde[0];
    $id_tecido_pai = $dado_molde[1];
    }
    //echo('ta aqui');
    //var_dump($dado_molde);
//===========================================================================================================================//
    //O campo usuário e senha preenchido entra no if para validar
    if((isset($_POST['molde']))){
        //echo('hehe1'.$_POST['molde']);
        $molde = mysqli_real_escape_string($conn, $_POST['molde']);//Escapar de caracteres especiais, como aspas, prevenindo SQL injection
        
        //Buscar na tabela molde o usuário que corresponde com os dados digitado no formulário
        $result_molde = "SELECT * FROM moldes WHERE referencia = '$molde' LIMIT 1";
        $resultado_molde = mysqli_query($conn, $result_molde);
        $resultado = mysqli_fetch_assoc($resultado_molde);
        //print_r($resultado);
        if($resultado==NULL){
            $resultado['tipo'] = 'NAOACHOU'; 
        }
        //var_dump("<br/>hehe2:".$resultado['tipo']);
        else if(isset($_POST['PP']) || isset($_POST['P']) || isset($_POST['M']) || isset($_POST['G']) || isset($_POST['GG']) || isset($_POST['EXG'])){
            require('funcoes.php');
            //salvando todos os dados em json 
            $json_str = '{"nome":"'.$_POST['molde'].'","PP":'.$resultado["PP"].', "P":'.$resultado["P"].', "M":'.$resultado["M"].', "G":'.$resultado["G"].', "GG":'.$resultado["GG"].', "EXG":'.$resultado["EXG"].', "qtdPP":'.trataZero($_POST["PP"]).', "qtdP":'.trataZero($_POST["P"]).', "qtdM":'.trataZero($_POST["M"]).', "qtdG":'.trataZero($_POST["G"]).', "qtdGG":'.trataZero($_POST["GG"]).', "qtdEXG":'.trataZero($_POST["EXG"]).', "tipoMolde":"'.$resultado["tipo"].'"}';
            //preenchendo o tecido e calculando estoque
            $dado_tecido = explode(",",$_POST['cor_tecido']);
            //var_dump($dado_tecido);
            //dado_tecido[0] = id no bling, e $dado_tecido[1] = nome da cor do tecido no data base local
            
            global $token;
            $_SESSION['qtd_tecido'] = pegaEstoque($token,trim($dado_tecido[0]));
            $_SESSION['molde_cor'] = trim($dado_tecido[1]);
            $_SESSION['id_tecido_cor'] = $dado_tecido[0];
            
            //calculando média de gasto obs: tam G = $resultado['G'],tipo de calculo = $resultado['tipo']
            $somaTotal = $_POST['PP']+$_POST['P']+$_POST['M']+$_POST['G']+$_POST['GG']+$_POST['EXG'];
            //debug
            //echo('deb:'.$resultado['tipo']);
            $_SESSION['gastoReal'] = calculaTecido($somaTotal,$resultado['G'],$resultado['tipo']);
            echo('gastinho:'.$_SESSION['gastoReal']);
        }
        else if(isset($_POST['X1'])|| isset($_POST['X2'])|| isset($_POST['X3'])|| isset($_POST['X4'])|| isset($_POST['X5'])|| isset($_POST['X6'])){
            require('funcoes.php');
            //$totalT = ($X1*floatval($resultado['X1']))+($X2*floatval($resultado['X2']))+($X3*floatval($resultado['X3']))+($X4*floatval($resultado['X4']))+($X5*floatval($resultado['X5']))+($X6*floatval($resultado['X6']));
            
            //salvando todos os dados em json 
            $json_str = '{"nome":"'.$_POST['molde'].'","X1":'.$resultado["X1"].', "X2":'.$resultado["X2"].', "X3":'.$resultado["X3"].', "X4":'.$resultado["X4"].', "X5":'.$resultado["X5"].', "X6":'.$resultado["X6"].', "qtdX1":'.trataZero($_POST["X1"]).', "qtdX2":'.trataZero($_POST["X2"]).', "qtdX3":'.trataZero($_POST["X3"]).', "qtdX4":'.trataZero($_POST["X4"]).', "qtdX5":'.trataZero($_POST["X5"]).', "qtdX6":'.trataZero($_POST["X6"]).', "tipoMolde":"'.$resultado["tipo"].'"}';
            //preenchendo o tecido e calculando estoque
            $dado_tecido = explode(",",$_POST['cor_tecido']);
            
            
            global $token;
            $_SESSION['qtd_tecido'] = pegaEstoque($token,trim($dado_tecido[0]));
            $_SESSION['molde_cor'] = trim($dado_tecido[1]);
            $_SESSION['id_tecido_cor'] = $dado_tecido[0];

            //calculando média de gasto obs: tam G = $resultado['G'],tipo de calculo = $resultado['tipo']
            $somaTotal = $_POST['x1']+$_POST['X1']+$_POST['X2']+$_POST['X3']+$_POST['X4']+$_POST['X5']+$_POST['X6'];
            $_SESSION['gastoReal'] = calculaTecido($somaTotal,$resultado['X5'],$resultado['tipo']);

        }
        else if(isset($_POST['2A'])|| isset($_POST['4A'])|| isset($_POST['6A'])|| isset($_POST['8A'])|| isset($_POST['10A'])|| isset($_POST['12A'])|| isset($_POST['14A'])|| isset($_POST['16A'])){
            require('funcoes.php');
            echo('tecidinho');
            print_r($_POST);
            //$totalT = ($X1*floatval($resultado['X1']))+($X2*floatval($resultado['X2']))+($X3*floatval($resultado['X3']))+($X4*floatval($resultado['X4']))+($X5*floatval($resultado['X5']))+($X6*floatval($resultado['X6']));
            
            //salvando todos os dados em json 
            $json_str = '{"nome":"'.$_POST['molde'].'","A2":'.$resultado["2A"].', "A4":'.$resultado["4A"].', "A6":'.$resultado["6A"].', "A8":'.$resultado["8A"].', "A10":'.$resultado["10A"].', "A12":'.$resultado["12A"].',"A14":'.$resultado["14A"].',"A16":'.$resultado["16A"].', "qtd2A":'.trataZero($_POST["2A"]).', "qtd4A":'.trataZero($_POST["4A"]).', "qtd6A":'.trataZero($_POST["6A"]).', "qtd8A":'.trataZero($_POST["8A"]).', "qtd10A":'.trataZero($_POST["10A"]).', "qtd12A":'.trataZero($_POST["12A"]).',"qtd14A":'.trataZero($_POST["14A"]).',"qtd16A":'.trataZero($_POST["16A"]).', "tipoMolde":"'.$resultado["tipo"].'"}';
            //preenchendo o tecido e calculando estoque
            $dado_tecido = explode(",",$_POST['cor_tecido']);
            var_dump($json_str);
            
            global $token;
            $_SESSION['qtd_tecido'] = pegaEstoque($token,trim($dado_tecido[0]));
            $_SESSION['molde_cor'] = trim($dado_tecido[1]);
            $_SESSION['id_tecido_cor'] = $dado_tecido[0];
            print_r($_SESSION);
            //calculando média de gasto obs: tam G = $resultado['G'],tipo de calculo = $resultado['tipo']
            $somaTotal = $_POST['2A']+$_POST['4A']+$_POST['6A']+$_POST['8A']+$_POST['10A']+$_POST['12A']+$_POST['14A']+$_POST['16A'];
            $_SESSION['gastoReal'] = calculaTecido($somaTotal,$resultado['G'],$resultado['tipo']);
        }
        else{
            $json_str = 'NADA';
            $_SESSION['nome_molde'] = $resultado['nome'];
            $_SESSION['tecido'] = $nome_tecido_pai;
            $_SESSION['idTecidoPai'] = $id_tecido_pai;
        }
        $_SESSION['molde'] = $json_str;
        //var_dump($_SESSION['molde']);
        header("Location:tecido.php?tamanhos=".$resultado['tipo']."&molde=".$resultado['referencia']."&tecido=ok"."&"); 
    }
    else{
        //echo('debug:'.$token.','.$_SESSION['id_tecido_cor'].','.$_SESSION['gastoReal'].','.'14886781811'.'nivela'.$_SESSION['usuarioNivelAcesso'] == 0);
        if(isset($_SESSION['id_tecido_cor']) && $_SESSION['usuarioNivelAcesso'] == 0 || $_SESSION['usuarioNivelAcesso'] == 1){
            require('funcoes.php');
            global $token;
            baixaEstoque($token,$_SESSION['id_tecido_cor'],$_SESSION['gastoReal'],'14886781811');
            header("Location:tecido.php?quantia=".$_SESSION['gastoReal'].'&tecido='.$_SESSION['molde_cor'].'&pai='.$_SESSION['id_tecido_cor']);
        }else{
            echo('kiki');
            header("Location:tecido.php");
        }
    }
     
?>