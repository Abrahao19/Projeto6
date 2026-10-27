

<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../CriarConta2/login.php");
    exit;
}

include "../CriarConta2/conexao.php";

$usuario_id = (int) $_SESSION["usuario_id"];
$mensagem = "";

// MENSAGEM DE SUCESSO: EXIBIDA APENAS UMA VEZ
$mensagemSucesso = $_SESSION["mensagem_sucesso"] ?? "";
unset($_SESSION["mensagem_sucesso"]);

// EXCLUIR PUBLICAÇÃO
if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["excluir_post_id"])) {

    $post_id = filter_var(
        $_POST["excluir_post_id"],
        FILTER_VALIDATE_INT
    );

    if ($post_id !== false && $post_id > 0) {

        // Exclui somente se a publicação pertencer ao usuário logado
        $sqlExcluir = "DELETE FROM post
                       WHERE id = ? AND usuario_id = ?";

        $stmtExcluir = $conexao->prepare($sqlExcluir);
        $stmtExcluir->bind_param(
            "ii",
            $post_id,
            $usuario_id
        );

        if ($stmtExcluir->execute()) {
            if ($stmtExcluir->affected_rows > 0) {
                $_SESSION["mensagem_sucesso"] =
                    "Publicação excluída com sucesso.";
            } else {
                $_SESSION["mensagem_sucesso"] =
                    "Publicação não encontrada ou você não tem permissão para excluí-la.";
            }

            $stmtExcluir->close();

            header("Location: postagens.php");
            exit;
        }

        $stmtExcluir->close();
        $mensagem = "Não foi possível excluir a publicação.";
    } else {
        $mensagem = "Publicação inválida.";
    }
}

// SALVAR UMA NOVA PUBLICAÇÃO
if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["conteudo"])) {

    $conteudo = trim($_POST["conteudo"] ?? "");

    if ($conteudo === "") {
        $mensagem = "Escreva alguma coisa antes de publicar.";
    } elseif (mb_strlen($conteudo) > 5000) {
        $mensagem = "A publicação deve ter no máximo 5000 caracteres.";
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
            $stmt->close();
        }
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
$totalPostagens = $resultado->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Git Club - Postagens</title>

    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="postagens.css">
</head>

<body>

    <!-- CABEÇALHO -->
    <header class="cabecalho-heroi">

        <div class="logo-heroi">
            <span>Git</span> Club
        </div>

        <nav class="menu-navegacao">

            <a href="../projetos/projetos.php">Mensagens</a>
            <a href="../comentarios/comentarios.php">Comentários</a>
            <a href="../curtidas/curtidas.php">Curtidas</a>
            <a href="../seguidores/seguidores.php">Seguidores</a>
            <a href="../seguindo/seguindo.php">Seguindo</a>
            <a href="../colab/Colaboradores.php">Colaboradores</a>
            <a href="postagens.php" class="ativo">Postagens</a>
            <a href="../perfil/perfil.php">Perfil</a>

        </nav>

        <div class="acoes-header">
            <a href="../publicacoes.php" class="botao-voltar">
                ← Ir para o feed
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
                <p>Crie e acompanhe suas próprias publicações.</p>
            </div>

        </div>

        <!-- MENSAGEM DE SUCESSO -->
        <?php if ($mensagemSucesso !== ""): ?>

            <div class="mensagem-sucesso" role="status">
                <?= htmlspecialchars(
                    $mensagemSucesso,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </div>

        <?php endif; ?>

        <!-- MENSAGEM DE ERRO -->
        <?php if ($mensagem !== ""): ?>

            <p class="mensagem-publicacao" role="alert">
                <?= htmlspecialchars(
                    $mensagem,
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </p>

        <?php endif; ?>

        <!-- ÁREA DE CRIAÇÃO -->
        <section class="criar-postagem-area">

            <div class="criar-postagem-cabecalho">

                <div class="criar-postagem-texto">

                    <div class="criar-postagem-icone">✍️</div>

                    <div>
                        <h2>Criar publicação</h2>
                        <p>Compartilhe suas ideias com a comunidade.</p>
                    </div>

                </div>

            </div>

            <!-- FORMULÁRIO DE PUBLICAÇÃO -->
            <form action="postagens.php" method="POST">

                <div class="campo-publicacao">

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

                    <div class="rodape-textarea">
                        <span>Compartilhe suas ideias e experiências.</span>
                        <span>Máximo de 5000 caracteres.</span>
                    </div>

                </div>

                <div class="acoes-publicacao">

                    <button
                        type="submit"
                        class="botao-enviar-publicacao"
                    >
                        <span>＋</span>
                        Publicar
                    </button>

                </div>

            </form>

        </section>

        <!-- MINHAS PUBLICAÇÕES -->
        <section class="minhas-postagens">

            <div class="minhas-postagens-cabecalho">

                <div>
                    <h2>Minhas publicações</h2>
                    <p>
                        Confira tudo o que você compartilhou na comunidade.
                    </p>
                </div>

                <span class="contador-postagens">
                    <?= $totalPostagens ?>
                    <?= $totalPostagens === 1
                        ? "publicação"
                        : "publicações" ?>
                </span>

            </div>

            <?php if ($totalPostagens > 0): ?>

                <div class="lista-postagens">

                    <?php while ($post = $resultado->fetch_assoc()): ?>

                        <article class="post-card">

                            <!-- CABEÇALHO DA PUBLICAÇÃO -->
                            <div class="post-cabecalho">

                                <div class="post-avatar">👤</div>

                                <div class="post-autor-info">

                                    <h2>Minha publicação</h2>

                                    <span class="post-data">
                                        Publicado em:
                                        <?= htmlspecialchars(
                                            $post["data_criacao"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        ) ?>
                                    </span>

                                </div>

                                <span class="post-etiqueta">
                                    Minha postagem
                                </span>

                            </div>

                            <!-- CONTEÚDO -->
                            <div class="post-conteudo">

                                <p class="post-texto"><?=
                                    nl2br(htmlspecialchars(
                                        $post["conteudo"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    ))
                                ?></p>

                            </div>

                            <!-- RODAPÉ -->
                            <div class="post-rodape">

                                <span>
                                    <span class="ponto-publicacao"></span>
                                    Publicação realizada
                                </span>

                                <span class="post-id">
                                    #<?= (int) $post["id"] ?>
                                </span>

                            </div>

                            <!-- BOTÃO EXCLUIR -->
                            <div class="acoes-postagem">

                                <form
                                    action="postagens.php"
                                    method="POST"
                                    onsubmit="return confirm('Tem certeza de que deseja excluir esta publicação? Esta ação não pode ser desfeita.');"
                                >

                                    <input
                                        type="hidden"
                                        name="excluir_post_id"
                                        value="<?= (int) $post["id"] ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="botao-excluir-publicacao"
                                    >
                                        🗑 Excluir publicação
                                    </button>

                                </form>

                            </div>

                        </article>

                    <?php endwhile; ?>

                </div>

            <?php else: ?>

                <!-- ESTADO VAZIO -->
                <div class="postagens-vazias">

                    <div class="postagens-vazias-icone">📝</div>

                    <h3>Você ainda não publicou nada</h3>

                    <p>
                        Que tal compartilhar sua primeira ideia?
                        Escreva algo no formulário acima e sua publicação
                        aparecerá aqui.
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
