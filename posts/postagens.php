
<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../CriarConta2/login.php");
    exit;
}

include "../CriarConta2/conexao.php";

$usuario_id = $_SESSION["usuario_id"];
$mensagem = "";

// SALVAR UMA NOVA PUBLICAÇÃO
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conteudo = trim($_POST["conteudo"] ?? "");

    if ($conteudo === "") {
        $mensagem = "Escreva alguma coisa antes de publicar.";
    } else {
        $sql = "INSERT INTO post (usuario_id, conteudo)
                VALUES (?, ?)";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("is", $usuario_id, $conteudo);

        if ($stmt->execute()) {
            $stmt->close();

            header("Location: postagens.php");
            exit;
        } else {
            $mensagem = "Não foi possível publicar. Tente novamente.";
        }

        $stmt->close();
    }
}

// BUSCAR SOMENTE AS PUBLICAÇÕES DO USUÁRIO LOGADO
$sql = "SELECT id, conteudo, data_criacao
        FROM post
        WHERE usuario_id = ?
        ORDER BY data_criacao DESC";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Git Club - Postagens</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <!-- CABEÇALHO -->
    <header class="cabecalho-heroi">

        <div class="logo-heroi">
            <span>Git</span> Club
        </div>

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

            <a href="../posts/postagens.php" class="ativo">
                Postagens
            </a>

            <a href="../perfil/perfil.php">
                Perfil
            </a>

        </nav>

        <div class="acoes-header">
            <a href="../publicacoes.php" class="botao-voltar">
                ← Voltar
            </a>
        </div>

    </header>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="conteudo">

        <!-- TÍTULO -->
        <div class="titulo-pagina">

            <span class="icone">📝</span>

            <div>
                <h1>Postagens</h1>

                <p>
                    Crie e acompanhe suas próprias publicações.
                </p>
            </div>

        </div>

        <!-- FORMULÁRIO DE PUBLICAÇÃO -->
        <section class="formulario-postagem">

            <h2>Criar publicação</h2>

            <?php if ($mensagem !== ""): ?>
                <p>
                    <?= htmlspecialchars(
                        $mensagem,
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </p>
            <?php endif; ?>

            <form action="postagens.php" method="POST">

                <label for="conteudo">
                    O que você quer compartilhar?
                </label>

                <textarea
                    id="conteudo"
                    name="conteudo"
                    rows="5"
                    maxlength="5000"
                    placeholder="Compartilhe algo com a comunidade..."
                    required
                ></textarea>

                <button type="submit">
                    Publicar
                </button>

            </form>

        </section>

        <!-- LISTA DAS PRÓPRIAS PUBLICAÇÕES -->
        <section class="minhas-postagens">

            <h2>Minhas publicações</h2>

            <?php if ($resultado->num_rows > 0): ?>

                <?php while ($post = $resultado->fetch_assoc()): ?>

                    <article class="postagem-card">

                        <p>
                            <?= nl2br(htmlspecialchars(
                                $post["conteudo"],
                                ENT_QUOTES,
                                "UTF-8"
                            )) ?>
                        </p>

                        <small>
                            Publicado em:
                            <?= htmlspecialchars(
                                $post["data_criacao"],
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </small>

                    </article>

                <?php endwhile; ?>

            <?php else: ?>

                <p>Você ainda não publicou nada.</p>

            <?php endif; ?>

        </section>

    </main>

</body>
</html>

<?php
$stmt->close();
$conexao->close();
?>
