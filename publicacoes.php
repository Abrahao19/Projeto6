


<?php
session_start();

// CONEXÃO COM O MYSQL
require_once __DIR__ . "/CriarConta2/conexao.php";
// BUSCAR O USUÁRIO LOGADO
$nomeUsuario = "Usuário";
$inicialUsuario = "?";

if (isset($_SESSION["usuario_id"])) {
    $usuarioId = (int) $_SESSION["usuario_id"];
    // BUSCAR FOTO DO PERFIL
$fotoPerfil = "";

$sqlFoto = "SELECT foto FROM perfil WHERE usuario_id = ?";
$stmtFoto = $conexao->prepare($sqlFoto);

if ($stmtFoto) {
    $stmtFoto->bind_param("i", $usuarioId);
    $stmtFoto->execute();

    $resultadoFoto = $stmtFoto->get_result();
    $perfilUsuario = $resultadoFoto->fetch_assoc();

    $fotoPerfil = $perfilUsuario["foto"] ?? "";

    $stmtFoto->close();
}

    $sqlUsuario = "SELECT nome FROM usuario WHERE id = ?";
    $stmtUsuario = $conexao->prepare($sqlUsuario);

    if ($stmtUsuario) {
        $stmtUsuario->bind_param("i", $usuarioId);
        $stmtUsuario->execute();

        $resultadoUsuario = $stmtUsuario->get_result();
        $dadosUsuario = $resultadoUsuario->fetch_assoc();

        if ($dadosUsuario && !empty(trim($dadosUsuario["nome"]))) {
            $nomeUsuario = trim($dadosUsuario["nome"]);

            $inicialUsuario = mb_strtoupper(
                mb_substr($nomeUsuario, 0, 1, "UTF-8"),
                "UTF-8"
            );
        }

        $stmtUsuario->close();
    }
}
// BUSCAR PUBLICAÇÕES DO FEED
$sql = "SELECT
            post.id,
            post.usuario_id,
            post.conteudo,
            post.data_criacao,
            usuario.nome AS nome_usuario
        FROM post
        INNER JOIN usuario
            ON post.usuario_id = usuario.id
        ORDER BY post.data_criacao DESC";

$resultado = $conexao->query($sql);

if ($resultado === false) {
    error_log("Erro ao buscar publicações: " . $conexao->error);
    http_response_code(500);
    $erroBanco = true;
} else {
    $erroBanco = false;
    $totalPostagens = $resultado->num_rows;
}

// PREPARAR DADOS DE EXIBIÇÃO
function escapar($valor) {
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );
}

function formatarData($data) {
    if (empty($data)) {
        return "";
    }

    try {
        $dataObjeto = new DateTime($data);
        return $dataObjeto->format("d/m/Y \à\s H:i");
    } catch (Exception $e) {
        return escapar($data);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Git Club - Feed</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- CABEÇALHO -->
    <header class="cabecalho-heroi feed-header">

        <!-- LOGO -->
        <a href="posts/postagens.php" class="logo-heroi">
            Git <span>Club</span>
        </a>
         
        <!-- MEU PERFIL -->
<div class="feed-header-acoes">
    <a href="perfil/perfil.php" class="feed-perfil-botao">

    <span class="feed-mini-avatar">
    <?php if (!empty($fotoPerfil)): ?>
       <img
    src="<?= escapar(preg_replace('#^\.\./#', '', $fotoPerfil)) ?>"
    alt="Foto de perfil"
    width="40"
    height="40"
    style="width: 40px; height: 40px; object-fit: cover; border-radius: 40%; display: block;"
>
    <?php else: ?>
        <?= escapar($inicialUsuario) ?>
    <?php endif; ?>
</span>
<span>Meu perfil</span>
    </a>
</div>
 
    </header>

    <main class="feed-container">

        <!-- APRESENTAÇÃO -->
        <section class="feed-boas-vindas">

            <div>
                <span class="feed-sobretitulo">
                    ✦ COMUNIDADE DE DESENVOLVEDORES
                </span>

                <h1>Explore o <span>Feed.</span></h1>

                <p>
                    Para publicar, vá para Meu Perfil -> postagens.
                </p>
            </div>

            <div class="feed-resumo">
                <span class="feed-resumo-icone">🚀</span>

                <div>
                    <strong>Ideias em movimento</strong>
                    <small>Conheça projetos da comunidade</small>
                </div>
            </div>

        </section>

        <!-- PUBLICAÇÕES -->
        <section class="feed-secao">

            <div class="feed-secao-titulo">

                <div>
                    <h2>Publicações recentes</h2>
                    <p>Publicações compartilhadas pela comunidade</p>
                </div>

                <span class="feed-contador">
                    <?php if (!$erroBanco): ?>
                        <?= $totalPostagens ?>
                        <?= $totalPostagens === 1
                            ? "publicação"
                            : "publicações" ?>
                    <?php else: ?>
                        Feed indisponível
                    <?php endif; ?>
                </span>

            </div>

            <!-- LISTA DINÂMICA DE PUBLICAÇÕES -->
            
            <div class="feed-lista">


                <?php if ($erroBanco): ?>

                    <div class="postagens-vazias">
                        <div class="postagens-vazias-icone">⚠️</div>

                        <h3>Não foi possível carregar o feed</h3>

                        <p>
                            Ocorreu um erro ao consultar as publicações.
                            Tente novamente mais tarde.
                        </p>
                    </div>

                <?php elseif ($totalPostagens > 0): ?>

                    <?php while ($post = $resultado->fetch_assoc()): ?>

                        <?php
                        $idPost = (int) $post["id"];
                        $conteudo = (string) $post["conteudo"];
                        $nomeAutor = trim($post["nome_usuario"]);

                        if ($nomeAutor === "") {
                        $nomeAutor = "Usuário";
                        }

                        $inicialAutor = mb_strtoupper(
                        mb_substr($nomeAutor, 0, 1, "UTF-8"),
                        "UTF-8"
                         );
                         ?>
                        <article class="feed-card">

                            <!-- CABEÇALHO DO CARTÃO -->
                            <div class="feed-card-topo">

                            <div class="feed-avatar feed-avatar-roxo">
                            <?= escapar($inicialAutor) ?>
                            </div>
                            <div class="feed-autor">
                            <strong><?= escapar($nomeAutor) ?></strong>

                                    <span>
                                        Publicado em
                                        <?= escapar(
                                            formatarData(
                                                $post["data_criacao"]
                                            )
                                        ) ?>
                                    </span>
                                </div>

                                <span class="feed-tag">
                                    💻 Publicação
                                </span>

                            </div>
                            
                           <!-- CONTEÚDO -->
                            <div class="feed-card-conteudo">

                            <h3>
                                Publicação #<?= $idPost ?>
                            </h3>

                            <p class="feed-texto-publicacao"><?=
                            nl2br(escapar($conteudo))
                            ?></p>

                            </div>
                            

                            <!-- RODAPÉ -->
<div class="feed-card-rodape">

    <span class="feed-card-categoria">
        Publicação da comunidade
    </span>

    <a
        href="comentarios/ver_comentarios.php?post_id=<?= $idPost ?>"
        class="feed-comentar"
    >
        💬 Comentar
    </a>

</div>

</article>
<br>
                    <?php endwhile; ?>

                <?php else: ?>

                    <!-- ESTADO VAZIO -->
                    <div class="postagens-vazias">

                        <div class="postagens-vazias-icone">
                            📝
                        </div>

                        <h3>Ainda não há publicações</h3>

                        <p>
                            Quando os membros publicarem algo no Git Club,
                            as publicações aparecerão aqui automaticamente.
                        </p>

                        <p>
                            Você pode começar compartilhando suas ideias
                            na página de postagens.
                        </p>

                        <a
                            href="posts/postagens.php"
                            class="feed-comentar"
                            style="margin-top: 18px;"
                        >
                            ＋ Criar publicação
                        </a>

                    </div>

                <?php endif; ?>

            </div>

        </section>

        <!-- RODAPÉ -->
        <footer class="feed-footer">

            <span class="feed-footer-logo">
                Git <span>Club</span>
            </span>

            <p>
                Conectando pessoas por meio de ideias e projetos.
            </p>

        </footer>

    </main>

  

</body>
</html>

<?php
if (isset($resultado) && $resultado instanceof mysqli_result) {
    $resultado->free();
}

$conexao->close();
?>
