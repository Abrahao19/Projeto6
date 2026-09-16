<?php

include "conexao.php";

$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha"];

$sql = "INSERT INTO usuario (nome, email, senha)
        VALUES ('$nome', '$email', '$senha')";

if ($conexao->query($sql) === TRUE) {

    echo "
    <script>
        localStorage.setItem('nomeUsuario', " . json_encode($nome) . ");
        window.location.href = '../inicio.html';
    </script>
    ";

} else {

    echo "Erro ao cadastrar: " . $conexao->error;

}

$conexao->close();

?>