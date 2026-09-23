```php
<?php

session_start();

include "../Criar_conta/conexao.php";

$email = $_POST["email"];
$senha = $_POST["senha"];

// Prepared Statement para evitar SQL Injection
$stmt = $conexao->prepare(
    "SELECT * FROM usuario WHERE email = ?"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    $usuario = $resultado->fetch_assoc();

    // Verifica a senha digitada contra o hash salvo no banco
    if (password_verify($senha, $usuario["senha"])) {

        // Cria uma nova sessão
        session_regenerate_id(true);

        // Salva os dados do usuário na sessão
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];

        // Vai para a página inicial
        header("Location: ../inicio.php");
        exit();

    } else {

        echo "E-mail ou senha incorretos.";

    }

} else {

    echo "E-mail ou senha incorretos.";

}

$stmt->close();
$conexao->close();

?>
```
