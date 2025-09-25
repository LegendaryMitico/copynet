<?php
    session_start();
        //Incluindo a conexão com banco de dados   
    include_once("php/connect.php"); 
    //O campo usuário e senha preenchido entra no if para validar
    if((isset($_POST['login'])) && (isset($_POST['senha']))){
        //echo("</br>paxou<br/>".$_POST['login']."<br/>".$_POST['senha']."<br/>");
        $usuario = mysqli_real_escape_string($conn, $_POST['login']);//Escapar de caracteres especiais, como aspas, prevenindo SQL injection
        $senha = mysqli_real_escape_string($conn, $_POST['senha']);
        $senha = md5($senha);
            
        //Buscar na tabela usuario o usuário que corresponde com os dados digitado no formulário
        $result_usuario = "SELECT * FROM usuarios WHERE login = '$usuario' && senha = '$senha' LIMIT 1";
        $resultado_usuario = mysqli_query($conn, $result_usuario);
        $resultado = mysqli_fetch_assoc($resultado_usuario);
        //echo("<br/>achou:".$resultado['nome_usuario']);
        
        //Encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
        if(isset($resultado)){
            $_SESSION['usuarioId'] = $resultado['id_usuario'];
            $_SESSION['usuarioNome'] = $resultado['nome_usuario'];
            $_SESSION['usuarioNivelAcesso'] = $resultado['nivel_acesso_id'];
            $_SESSION['usuarioLogin'] = $resultado['login'];
            $_SESSION['setor'] = $resultado['setor'];
            $_SESSION['filial'] = $resultado['filial'];
            $_SESSION['idBling'] = $resultado['idBling'];
            if($_SESSION['usuarioNivelAcesso'] == "0"){
                header("Location:index.php?acesso=0");
            }elseif($_SESSION['usuarioNivelAcesso'] == "1"){
                header("Location:meusPedidos.php");
            }else{
                header("Location:movimentacao.php?setorAtual=10");
            }
        //Não foi encontrado um usuario na tabela usuário com os mesmos dados digitado no formulário
        //redireciona o usuario para a página de login
        }else{    
            //Váriavel global recebendo a mensagem de erro
            $_SESSION['loginErro'] = "Usuário ou senha Inválido";
            header("Location:login.php?logado=falha");
        }
    //O campo usuário e senha não preenchido entra no else e redireciona o usuário para a página de login
    }else{
        $_SESSION['loginErro'] = "Usuário ou senha inválido";
        header("Location:login.php");
    }
?>