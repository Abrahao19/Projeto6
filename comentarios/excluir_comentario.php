
<?php
session_start();

require_once __DIR__ . "/../CriarConta2/conexao.php";

// Verifica se o usuário está conectado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../inicio.php");
    exit;
}

// Só aceita o envio do formulário
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: comentarios.php");
    exit;
}

$usuarioId = (int) $_SESSION["usuario_id"];
$comentarioId = filter_input(
    INPUT_POST,
    "comentario_id",
    FILTER_VALIDATE_INT
);

if (!$comentarioId || $comentarioId <= 0) {
    header("Location: comentarios.php");
    exit;
}

// Exclui somente um comentário pertencente ao usuário conectado
$sql = "DELETE FROM comentario
        WHERE id = ? AND usuario_id = ?";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    exit("Erro ao preparar a exclusão.");
}

$stmt->bind_param("ii", $comentarioId, $usuarioId);
$stmt->execute();

$stmt->close();
$conexao->close();

// Volta para a página de comentários
$postId = filter_input(INPUT_POST, "post_id", FILTER_VALIDATE_INT);

if ($postId && $postId > 0) {
    header("Location: ver_comentarios.php?post_id=" . $postId);
} else {
    header("Location: comentarios.php");
}

exit;
?>
