<?php
include 'conexao.php';

// verifica se a requisiçao atual é um post
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    //limpa o email e armazena
    $email = htmlspecialchars($_POST['email']);
    $senha = $_POST['senha'];
    $nome = htmlspecialchars($_POST['nome']);

    try{
        //prepara a instrução SQL para Execução
        $stmt = $conn->prepare("SELECT id_cliente, senha FROM usuarios where email =:email");
        $stmt->bindParam(':email',$email);
        $stmt->execute();

        //obtem o resultado para trabalhar depois
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        //verefica se algum usuario foi retornada a consulta
        //se existir usuario
        if($usuario){
            //Verifica se a senha fornecida corresponde a senha armazenada
            if(password_verify($senha,$usuario['senha'])){
                //inicia  sessao para armazenar informaçoes do usuario
                session_start();
                //regenera o ID da sessao para prevenis sequestro de sessao
                session_regenerate_id();
                //define configuraçoes seguras para o cookie da sessao
                session_set_cookie_params(['secure'=>true,
                                         'httponly'=>true,
                                        'samesite'=>'Strict']);
                // armazena o ID do usuario e o estado de login
                $_SESSION['usuario_id'] =$usuario['id_cliente'];
                $_SESSION['logado'] = true;

                //redireciona o usuario para a pagina do painel apos login
                header("Location: painel.php");
                exit;
            }else{
                //caso a senha não esteja correta
                echo "Senha Incorreta";
            }
        }else{
            echo "Usuario Não encontrato";
        }
    }catch (PDOException $e){
        echo "Erro no Login" . $e->getMessage();
    }
}