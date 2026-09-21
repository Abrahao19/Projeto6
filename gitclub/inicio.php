
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="inicio.css">
    <title>Git Club</title>
</head>

<body>
     <!-- Barra superior -->
    <div class="retangulo_inicio">
     <!-- Texto de inicio -->
        <div class="inicio">
            GIT CLUB
        </div> 

    </div>


    <?php if (isset($_SESSION["usuario_id"])): ?>

        <!-- usuario esta logado -->

        <div class="deslogar">
            <a href="logout.php">Sair</a>
        </div>

        <div class="perfil">
        <a href="perfil.php">Perfil</a>
        </div>

        <h1 id="nomeUsuario">
            Olá <?php echo htmlspecialchars($_SESSION["nome"]); ?>,
            você está conectado na sua conta!
        </h1>


    <?php else: ?>

        <!-- usuario n esta logado -->

        <a href="Criar_conta/criar_conta.html">
            <div class="criar_conta">
                Cadastra-se
            </div>
        </a>


        <a href="entrar_login/login.html">
            <div class="entrar">
                Entrar
            </div>
        </a>

    <?php endif; ?>


</body>
</html>

