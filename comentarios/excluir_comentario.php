
<?php
session_start();

require_once __DIR__ . "/../CriarConta2/conexao.php";

// Verifica se o usuário está conectado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../inicio.php");
    exit;
}

// Aceita somente requisições POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../publicacoes.php");
    exit;
}

$usuario_id = (int) $_SESSION["usuario_id"];
$comentario_id = filter_input(
    INPUT_POST,
    "comentario_id",
    FILTER_VALIDATE_INT
);

$post_id = filter_input(
    INPUT_POST,
    "post_id",
    FILTER_VALIDATE_INT
);

// Valida o ID do comentário
if (!$comentario_id || $comentario_id <= 0) {
    exit("Comentário inválido.");
}

// Exclui somente se o comentário pertencer ao usuário logado
$sql = "DELETE FROM comentarios_post
        WHERE id = ?
          AND usuario_id = ?";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    error_log("Erro ao preparar exclusão: " . $conexao->error);
    exit("Não foi possível preparar a exclusão do comentário.");
}

$stmt->bind_param("ii", $comentario_id, $usuario_id);

if (!$stmt->execute()) {
    error_log("Erro ao excluir comentário: " . $stmt->error);

    $stmt->close();
    $conexao->close();

    exit("Não foi possível excluir o comentário.");
}

// Verifica se algum comentário foi excluído
$excluido = $stmt->affected_rows > 0;

$stmt->close();
$conexao->close();

if (!$excluido) {
    exit("Comentário não encontrado ou você não tem permissão para excluí-lo.");
}

// Retorna à conversa, se o post_id tiver sido enviado
if ($post_id && $post_id > 0) {
    header("Location: ver_comentarios.php?post_id=" . $post_id);
    exit;
}

// Caso a exclusão tenha ocorrido na aba de comentários escritos
header("Location: comentarios.php");
exit;
?>
