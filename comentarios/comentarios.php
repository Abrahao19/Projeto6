
<?php
session_start();

require_once __DIR__ . "/../CriarConta2/conexao.php";

// Verifica se o usuário está logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../inicio.php");
    exit;
}

$usuarioId = (int) $_SESSION["usuario_id"];

// Busca os comentários escritos pelo usuário logado
$sql = "SELECT
            c.id,
            c.conteudo,
            c.data_criacao,
            p.id AS post_id,
            p.conteudo AS conteudo_publicacao,
            u.nome AS autor_publicacao
        FROM comentarios_post c
        INNER JOIN post p
            ON c.post_id = p.id
        INNER JOIN usuario u
            ON p.usuario_id = u.id
        WHERE c.usuario_id = ?
        ORDER BY c.data_criacao DESC";

$stmtComentarios = $conexao->prepare($sql);

if (!$stmtComentarios) {
    exit("Erro ao buscar comentários: " . $conexao->error);
}

$stmtComentarios->bind_param("i", $usuarioId);
$stmtComentarios->execute();

$resultadoMeusComentarios = $stmtComentarios->get_result();
?>



<?php
/*
//session_start();

require_once __DIR__ . "/../CriarConta2/conexao.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../inicio.php");
    exit;
}

$usuarioId = (int) $_SESSION["usuario_id"];

$sql = "SELECT c.conteudo, c.data_criacao,
               p.id AS post_id,
               p.conteudo AS conteudo_publicacao,
               u.nome AS autor_publicacao
        FROM comentario c
        INNER JOIN post p ON c.post_id = p.id
        INNER JOIN usuario u ON p.usuario_id = u.id
        WHERE c.usuario_id = ?
        ORDER BY c.data_criacao DESC";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $usuarioId);
$stmt->execute();

$resultadoMeusComentarios = $stmt->get_result()
*/
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Git Club - Comentários</title>

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

            <!-- PÁGINA ATUAL -->

            <a href="../comentarios/comentarios.php" class="ativo">
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

            <a href="../perfil/perfil.php">
                Perfil
            </a>

        </nav>


        <!-- BOTÃO VOLTAR -->

        <div class="acoes-header">

            <a href="../publicacoes.php" class="botao-voltar">
                ← Ir para o feed
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
                💬
            </span>

            <div>

                <h1>Comentários</h1>

                <p>
                    Veja os comentários feitos nas suas publicações.
                </p>

            </div>

        </div>


        

<section class="comentarios-secao comentarios-meus">

    <div class="comentarios-cabecalho">
        <div>
            <span class="comentarios-etiqueta">SUA ATIVIDADE</span>
            <h2>Meus comentários</h2>
            <p>Acompanhe suas conversas na comunidade.</p>
        </div>

        <span class="comentarios-icone">💬</span>
    </div>

    <?php if ($resultadoMeusComentarios->num_rows > 0): ?>

        <div class="comentarios-lista">

            <?php while ($comentario = $resultadoMeusComentarios->fetch_assoc()): ?>

                <article class="comentario-item">

                    <div class="comentario-topo">
                        <div class="comentario-avatar">
                            💬
                        </div>

                        <div class="comentario-identificacao">
                            <span class="comentario-titulo">
                                Seu comentário
                            </span>

                            <small>
                                <?= htmlspecialchars(
                                    $comentario["data_criacao"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </small>
                        </div>
                    </div>

                    <div class="comentario-texto">
                        <?= nl2br(htmlspecialchars(
                            $comentario["conteudo"],
                            ENT_QUOTES,
                            "UTF-8"
                        )) ?>
                    </div>

                    <div class="comentario-publicacao">
                        <span class="publicacao-etiqueta">
                            PUBLICAÇÃO ORIGINAL
                        </span>

                        <p class="comentario-autor">
                            Publicação de
                            <strong>
                                <?= htmlspecialchars(
                                    $comentario["autor_publicacao"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                ) ?>
                            </strong>
                        </p>

                        <p class="comentario-conteudo-original">
                            <?= nl2br(htmlspecialchars(
                                $comentario["conteudo_publicacao"],
                                ENT_QUOTES,
                                "UTF-8"
                            )) ?>
                        </p>
                    </div>

                    <div class="comentario-acoes">

                        <a
                            href="ver_comentarios.php?post_id=<?= (int) $comentario["post_id"] ?>"
                            class="botao-ver-conversa"
                        >
                            Ver conversa <span aria-hidden="true">→</span>
                        </a>

                        <form
                            action="excluir_comentario.php"
                            method="POST"
                            onsubmit="return confirm('Tem certeza que deseja excluir este comentário?');"
                        >
                            <input
                                type="hidden"
                                name="comentario_id"
                                value="<?= (int) $comentario["id"] ?>"
                            >

                            <button
                                type="submit"
                                class="botao-excluir"
                            >
                                🗑️ Excluir comentário
                            </button>
                        </form>

                    </div>

                </article>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="comentarios-vazio">
            <span>💭</span>

            <h3>Nenhum comentário por aqui</h3>

            <p>
                Você ainda não escreveu comentários.
                Participe das conversas da comunidade!
            </p>

            <a href="../publicacoes.php">
                Explorar publicações →
            </a>
        </div>

    <?php endif; ?>

</section>
