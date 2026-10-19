

<?php
session_start();

include "conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    $stmt = $conexao->prepare(
    "SELECT id, nome, email, senha
     FROM usuario
     WHERE email = ?"
);

if ($stmt === false) {
    die("Erro ao preparar a consulta: " . $conexao->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();

    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    if ($usuario && password_verify($senha, $usuario["senha"])) {

        session_regenerate_id(true);

        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["email"] = $usuario["email"];

        $stmt->close();
        $conexao->close();

        header("Location: ../perfil/perfil.php");
        exit();

    } else {
        $erro = "E-mail ou senha incorretos.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar — Git Club</title>

    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="pagina-autenticacao">
        <div class="cartao-autenticacao">

            <a href="../feed.php" class="link-voltar link-voltar--destaque">
                &larr; Voltar
            </a>

            <h1>Entrar</h1>

            <?php if ($erro !== ""): ?>
                <p class="mensagem-erro">
                    <?= htmlspecialchars($erro, ENT_QUOTES, "UTF-8") ?>
                </p>
            <?php endif; ?>

            <form action="login.php" method="POST"
                  class="formulario-autenticacao">

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>

                <button type="submit"
                        class="botao-login botao-login--destaque">
                    Entrar
                </button>

            </form>

        </div>
    </div>
</body>
</html>
