

<?php
session_start();

// Verifica se o usuário está conectado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../CriarConta2/login.php");
    exit();
}

// Conexão com o banco
// Ajuste o caminho se necessário para o seu projeto.

include "../CriarConta2/conexao.php";

$usuario_id = (int) $_SESSION["usuario_id"];

// Busca os dados da conta
$stmt = $conexao->prepare(
    "SELECT nome, email
     FROM usuario
     WHERE id = ?"
);

if ($stmt === false) {
    die("Erro na consulta do usuário: " . $conexao->error);
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();

// Busca os dados do perfil
$stmt = $conexao->prepare(
    "SELECT foto, bio, github, visibilidade
     FROM perfil
     WHERE usuario_id = ?"
);

if ($stmt === false) {
    die("Erro na consulta do perfil: " . $conexao->error);
}

$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
$perfil = $resultado->fetch_assoc();
$stmt->close();

$nome = $usuario["nome"] ?? "Usuário";
$email = $usuario["email"] ?? "";
$bio = $perfil["bio"] ?? "Adicione uma biografia ao seu perfil.";
$foto = $perfil["foto"] ?? "";
$github = $perfil["github"] ?? "";
?>





<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Git Club - Perfil</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <!-- ==================================================
         CABEÇALHO
    =================================================== -->

    <header class="cabecalho-heroi">

        <!-- LOGO -->

        <div class="logo-heroi">
            <span>Git</span> Club
        </div>


        <!-- MENU -->

        <nav class="menu-navegacao">

            <a href="../projetos/projetos.php">
                Projetos
            </a>

            <a href="../comentarios/comentarios.php">
                Comentários
            </a>

            <a href="../curtidas/curtidas.php">
                Curtidas
            </a>

            <a href="../seguidores/seguidores.php">
                Seguidores
            </a>

            <a href="../seguindo/seguindo.php">
                Seguindo
            </a>

            <a href="../colab/Colaboradores.php">
                Colaboradores
            </a>

            <a href="../posts/postagens.php">
                Postagens
            </a>

            <!-- PÁGINA ATUAL -->

            <a href="../perfil/perfil.php" class="ativo">
                Perfil
            </a>

        </nav>


        <!-- VOLTAR -->

        <div class="acoes-header">

            <a href="../publicacoes.php" class="botao-voltar">
                ← Voltar
            </a>

        </div>

    </header>



    <!-- ==================================================
         CONTEÚDO
    =================================================== -->

    <main class="conteudo">


        <!-- TÍTULO -->

        <div class="titulo-pagina">

            <span class="icone">
                👤
            </span>

            <div>

                <h1>Meu Perfil</h1>

                <p>
                    Visualize suas informações e seus projetos.
                </p>

            </div>

        </div>



        <!-- ==================================================
             PERFIL
        =================================================== -->

        <section class="perfil-container">


            <!-- CARTÃO PRINCIPAL -->

            <div class="perfil-card">

<div class="perfil-avatar">
    <?php if (!empty($foto)): ?>
        <img
            src="<?= htmlspecialchars($foto, ENT_QUOTES, 'UTF-8') ?>?v=<?= time() ?>"
            alt="Foto de perfil"
        >
    <?php else: ?>
        <?= htmlspecialchars(strtoupper(substr($nome, 0, 2)), ENT_QUOTES, 'UTF-8') ?>
    <?php endif; ?>
</div>
                <!-- FOTO / AVATAR -->

                

                <!-- INFORMAÇÕES -->

                <div class="perfil-informacoes">

                    <h2>
                         <?= htmlspecialchars($nome, ENT_QUOTES, "UTF-8") ?>
                    </h2>

                    <span class="perfil-usuario">
                        <?= htmlspecialchars($_SESSION["email"], ENT_QUOTES, "UTF-8") ?>
                    </span>

                    <p class="perfil-bio">
                       <?= htmlspecialchars($bio, ENT_QUOTES, "UTF-8") ?>
                    </p>

                   <div class="perfil-detalhes">
    <span>
        ✉️ <?= htmlspecialchars($email, ENT_QUOTES, "UTF-8") ?>
    </span>

    <?php if ($github !== ""): ?>
        <span>
            GitHub:
            <a href="<?= htmlspecialchars($github, ENT_QUOTES, "UTF-8") ?>">
                <?= htmlspecialchars($github, ENT_QUOTES, "UTF-8") ?>
            </a>
        </span>
    <?php endif; ?>
</div>



<a href="editar_perfil.php" class="ver-projetos">
    ✏️ Editar perfil
</a>

                </div>

            </div>



            <!-- ==================================================
                 PROJETOS
            =================================================== -->

            <div class="perfil-projetos">


                <div class="perfil-projetos-titulo">

                    <h2>
                        Meus Projetos
                    </h2>

                    <span>
                        2 projetos
                    </span>

                </div>


                <div class="perfil-projetos-grid">


                    <!-- PROJETO GTA -->

                    <div class="perfil-projeto-card">

                        <div class="perfil-projeto-icone">
                            🚗
                        </div>

                        <div>

                            <h3>
                                GTA 6
                            </h3>

                            <p>
                                Projeto privado.
                            </p>

                            <span class="status privado">
                                🔒 Privado
                            </span>

                        </div>

                    </div>


                    <!-- PROJETO MINICRAFT -->

                    <div class="perfil-projeto-card">

                        <div class="perfil-projeto-icone">
                            🎮
                        </div>

                        <div>

                            <h3>
                                Minecraft 2
                            </h3>

                            <p>
                                Projeto público.
                            </p>

                            <span class="status publico">
                                🔓 Público
                            </span>

                        </div>

                    </div>


                </div>


                <!-- VER MAIS -->

                <a
                    href="../projetos/projetos.php"
                    class="ver-projetos"
                >
                    Ver todos os projetos →
                </a>


            </div>

        </section>

    </main>

</body>

</html>
