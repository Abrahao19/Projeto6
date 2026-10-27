

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

    <title>Git Club - Conversa</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>

<header class="cabecalho-heroi">

    <a href="../publicacoes.php" class="logo-heroi">
        <span>Git</span> Club
    </a>

    <a href="../publicacoes.php" class="botao-voltar">
        ← Voltar ao feed
    </a>

</header>

<main class="conteudo pagina-conversa">

    <!-- CABEÇALHO DA PÁGINA -->

    <div class="titulo-pagina conversa-titulo">

        <span class="icone">💬</span>

        <div>
            <h1>Conversa da publicação</h1>
            <p>Compartilhe ideias e interaja com a comunidade.</p>
        </div>

    </div>

    <!-- PUBLICAÇÃO ORIGINAL -->

    <section class="conversa-publicacao">

        <div class="conversa-secao-titulo">
            <span>📌</span>
            <h2>Publicação original</h2>
        </div>

        <div class="conversa-autor">
            <div class="conversa-avatar">
                <?= htmlspecialchars(
                    strtoupper(substr($post["nome"], 0, 1)),
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </div>

            <div>
                <strong>
                    <?= htmlspecialchars(
                        $post["nome"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>
                </strong>

                <small>Autor da publicação</small>
            </div>
        </div>

        <div class="conversa-texto-publicacao">
            <?= nl2br(htmlspecialchars(
                $post["conteudo"],
                ENT_QUOTES,
                "UTF-8"
            )) ?>
        </div>

    </section>

    <!-- FORMULÁRIO DE COMENTÁRIO -->

    <section class="conversa-formulario">

        <div class="conversa-secao-titulo">
            <span>✍️</span>
            <h2>Deixe seu comentário</h2>
        </div>

        <form action="comentar.php" method="POST">

            <input
                type="hidden"
                name="post_id"
                value="<?= (int) $postId ?>"
            >

            <label for="conteudo-comentario">
                Sua mensagem
            </label>

            <textarea
                id="conteudo-comentario"
                name="conteudo"
                placeholder="O que você achou desta publicação?"
                maxlength="2000"
                required
            ></textarea>

            <div class="conversa-formulario-rodape">
                <small>Compartilhe sua opinião com respeito.</small>

                <button type="submit" class="conversa-botao-enviar">
                    Enviar comentário →
                </button>
            </div>

        </form>

    </section>

    <!-- COMENTÁRIOS DA COMUNIDADE -->

    <section class="conversa-lista">

        <div class="conversa-lista-cabecalho">
            <div class="conversa-secao-titulo">
                <span>🗨️</span>
                <h2>Comentários da comunidade</h2>
            </div>
        </div>

        <?php if ($resultado->num_rows > 0): ?>

            <div class="conversa-comentarios">

                <?php while ($comentario = $resultado->fetch_assoc()): ?>

                    <article class="conversa-comentario">

                        <div class="conversa-comentario-avatar">
                            <?= htmlspecialchars(
                                strtoupper(substr(
                                    $comentario["nome_usuario"],
                                    0,
                                    1
                                )),
                                ENT_QUOTES,
                                "UTF-8"
                            ) ?>
                        </div>

                        <div class="conversa-comentario-corpo">

                            <div class="conversa-comentario-cabecalho">

                                <div class="conversa-comentario-autor">
                                    <strong>
                                        <?= htmlspecialchars(
                                            $comentario["nome_usuario"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>
                                    </strong>

                                    <small>
                                        <?= htmlspecialchars(
                                            $comentario["data_criacao"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>
                                    </small>
                                </div>

                                <?php if (
                                    (int) $comentario["usuario_id"] ===
                                    (int) $_SESSION["usuario_id"]
                                ): ?>

                                    <form
                                        class="conversa-form-excluir"
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

                                        <button
                                            type="submit"
                                            class="conversa-botao-excluir"
                                            aria-label="Excluir seu comentário"
                                            title="Excluir comentário"
                                        >
                                            🗑 Excluir
                                        </button>

                                    </form>

                                <?php endif; ?>

                            </div>

                            <p class="conversa-comentario-texto">
                                <?= nl2br(htmlspecialchars(
                                    $comentario["conteudo"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                )) ?>
                            </p>

                        </div>

                    </article>

                <?php endwhile; ?>

            </div>

        <?php else: ?>

            <div class="conversa-vazia">
                <span>💬</span>

                <h3>Nenhum comentário por aqui ainda</h3>

                <p>
                    Seja a primeira pessoa a comentar nesta publicação
                    e comece a conversa!
                </p>
            </div>

        <?php endif; ?>

    </section>

</main>

</body>
</html>

<?php
$stmt->close();
$conexao->close();
?>
