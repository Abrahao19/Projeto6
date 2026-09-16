<?php

include "../Criar_conta/conexao.php";

$email = $_POST["email"];
$senha = $_POST["senha"];

$sql = "SELECT * FROM usuario 
        WHERE email = '$email' 
        AND senha = '$senha'";

$resultado = $conexao->query($sql);

if ($resultado->num_rows > 0) {

    $usuario = $resultado->fetch_assoc();

    echo "
    <script>
        localStorage.setItem('nomeUsuario', " . json_encode($usuario["nome"]) . ");
        window.location.href = '../inicio.html';
    </script>
    ";

} else {

    echo "E-mail ou senha incorretos.";

}

$conexao->close();

?>