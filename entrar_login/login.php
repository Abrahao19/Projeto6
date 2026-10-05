<?php
session_start();

$email_correto = "josney@etc.com";
$senha_correta = "123";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $senha = $_POST["senha"];

    if ($email === $email_correto && $senha === $senha_correta) {

        $_SESSION["logado"] = true;
        $_SESSION["email"] = $email;

        header("Location: ../perfil/perfil.php");
        exit;

    } else {
        $erro = "E-mail ou senha incorretos.";
    }
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

            <?php if ($erro): ?>
                <p class="mensagem-erro">
                    <?php echo $erro; ?>
                </p>
            <?php endif; ?>

            <form action="login.php" method="POST" class="formulario-autenticacao">

                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" required>

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>

                <button type="submit" class="botao-login botao-login--destaque">
                    Entrar
                </button>

            </form>

        </div>
    </div>

</body>
</html>