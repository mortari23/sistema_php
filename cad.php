<?php
// codigo para receber as informaçoes do html e fazer algo 
//captura o que o usuario digitou e cadastra no bd

//chamar arquivo de conexão 
include 'conexao.php';

//verifica se existe alguma informação chegando pela rede
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    //recebe o email, filtra e armazena na variavel
    $email = htmlspecialchars($_POST['email']);

    // recebe a senha, criptografa e armazena em uma variavel
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    //exebe a variavel para testar
    //var_dump($senha);

    //bloco tente para cadastrar no banco de dados
    try{
        //preara o comando SQL para inserir no banco de dados
        //utilizar Prepared para prevenir injetar SQL
    $stmt=$conn->prepare("insert into usuarios (email,senha) values(:email, :senha)");
        //associa os valores das variaveis :email e :senha
        $stmt->bindParam(":email",$email); //vincula o email e limpa 
        $stmt->bindParam(":senha",$senha);
        
        //executa o codigo
        $stmt->execute();
        echo "cadastrodo com sucesso";
    }catch(PDOException $e){
        echo "Erro ao cadastrar o usuario :".$e->getMessage();
    }
}
