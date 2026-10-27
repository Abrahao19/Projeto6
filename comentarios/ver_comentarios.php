

<?php
session_start();

require_once __DIR__ . "/../CriarConta2/conexao.php";

// Verifica se o usuário está logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../inicio.php");
    exit;
}

// Recebe o ID da publicação
$postId = filter_input(INPUT_GET, "post_id", FILTER_VALIDATE_INT);

if (!$postId) {
    exit("Publicação inválida.");
}

// Busca a publicação
$sqlPost = "SELECT
                post.id,
                post.conteudo,
                usuario.nome
            FROM post
            INNER JOIN usuario
                ON post.usuario_id = usuario.id
            WHERE post.id = ?";

$stmtPost = $conexao->prepare($sqlPost);

if (!$stmtPost) {
    exit("Erro ao buscar publicação: " . $conexao->error);
}

$stmtPost->bind_param("i", $postId);
$stmtPost->execute();

$post = $stmtPost->get_result()->fetch_assoc();
$stmtPost->close();

if (!$post) {
    http_response_code(404);
    exit("Publicação não encontrada.");
}

// Busca os comentários da publicação
$sql = "SELECT
            c.id,
            c.usuario_id,
            c.conteudo,
            c.data_criacao,
            u.nome AS nome_usuario
        FROM comentarios_post c
        INNER JOIN usuario u
            ON c.usuario_id = u.id
        WHERE c.post_id = ?
        ORDER BY c.data_criacao ASC";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    exit("Erro na consulta de comentários: " . $conexao->error);
}

$stmt->bind_param("i", $postId);
$stmt->execute();

$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>GitClub - Comentários</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>

<header class="cabecalho-heroi">

    <a href="../publicacoes.php" class="logo-heroi">
        Git <span>Club</span>
    </a>

    <a href="../publicacoes.php" class="botao-voltar">
        ← Voltar ao feed
    </a>

</header>

<main class="conteudo">

    <section class="comentarios-secao">

        <h1>Comentários</h1>

        <h2>
            Publicação de
            <?= htmlspecialchars($post["nome"], ENT_QUOTES, "UTF-8") ?>
        </h2>

        <p>
            <?= nl2br(htmlspecialchars(
                $post["conteudo"],
                ENT_QUOTES,
                "UTF-8"
            )) ?>
        </p>

    </section>

    <section class="comentarios-secao">

        <h2>Escreva um comentário</h2>

        <form action="comentar.php" method="POST">

            <input
                type="hidden"
                name="post_id"
                value="<?= (int) $postId ?>"
            >

            <textarea
                name="conteudo"
                placeholder="Escreva seu comentário..."
                maxlength="2000"
                required
            ></textarea>

            <button type="submit">
                Enviar comentário
            </button>

        </form>

    </section>

    <section class="comentarios-secao">

        <h2>Comentários da comunidade</h2>

        <?php if ($resultado->num_rows > 0): ?>

            <?php while ($comentario = $resultado->fetch_assoc()): ?>

                <?php if (
                    (int) $comentario["usuario_id"] ===
                    (int) $_SESSION["usuario_id"]
                ): ?>

                    <form
                        action="excluir_comentario.php"
                        method="POST"
                        onsubmit="return confirm('Deseja realmente excluir este comentário?');"
                    >

                        <input
                            type="hidden"
                            name="comentario_id"
                            value="<?= (int) $comentario["id"] ?>"
                        >

                        <input
                            type="hidden"
                            name="post_id"
                            value="<?= (int) $postId ?>"
                        >

                        <button type="submit" class="botao-excluir">
                            🗑️ Excluir
                        </button>

                    </form>

                <?php endif; ?>

                <article class="comentario-item">

                    <strong>
                        <?= htmlspecialchars(
                            $comentario["nome_usuario"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </strong>

                    <p>
                        <?= nl2br(htmlspecialchars(
                            $comentario["conteudo"],
                            ENT_QUOTES,
                            "UTF-8"
                        )) ?>
                    </p>

                    <small>
                        <?= htmlspecialchars(
                            $comentario["data_criacao"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </small>

                </article>

                <hr>

            <?php endwhile; ?>

        <?php else: ?>

            <p>Essa publicação ainda não tem comentários.</p>

        <?php endif; ?>

    </section>

</main>

</body>
</html>

<?php
$stmt->close();
$conexao->close();
?>
