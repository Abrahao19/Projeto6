<?php

session_start();

include "conexao.php";

$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha"];

// Cria um hash da senha (vai "embaralhar" a senha)
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);


$stmt = $conexao->prepare(
    "INSERT INTO usuario (nome, email, senha)
     VALUES (?, ?, ?)"
);

$stmt->bind_param("sss", $nome, $email, $senha_hash);

if ($stmt->execute()) {

    // pega o id do usuario que acabou de ser criado
    $usuario_id = $conexao->insert_id;

    // Cria a sessão do usuário
    session_regenerate_id(true);

    $_SESSION["usuario_id"] = $usuario_id;
    $_SESSION["nome"] = $nome;
    $_SESSION["email"] = $email;

    // Vai direto para o início
    header("Location: ../inicio.php");
    exit();

} else {

    echo "Erro ao cadastrar: " . $stmt->error;

}

$stmt->close();
$conexao->close();

?>