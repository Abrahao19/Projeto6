
<?php
session_start();

require_once __DIR__ . "/../CriarConta2/conexao.php";

// Verifica se o usuário está logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../inicio.php");
    exit;
}

// Aceita somente formulários enviados por POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../publicacoes.php");
    exit;
}

$usuario_id = (int) $_SESSION["usuario_id"];
$post_id = filter_input(INPUT_POST, "post_id", FILTER_VALIDATE_INT);
$conteudo = trim($_POST["conteudo"] ?? "");

// Valida os dados recebidos
if (!$post_id || $conteudo === "" || mb_strlen($conteudo) > 2000) {
    header("Location: ../publicacoes.php");
    exit;
}

// Verifica se a publicação existe
$sqlPost = "SELECT id FROM post WHERE id = ?";

$stmtPost = $conexao->prepare($sqlPost);

if (!$stmtPost) {
    error_log("Erro ao preparar consulta da publicação: " . $conexao->error);
    exit("Não foi possível verificar a publicação.");
}

$stmtPost->bind_param("i", $post_id);
$stmtPost->execute();

$publicacaoExiste = $stmtPost->get_result()->num_rows > 0;
$stmtPost->close();

if (!$publicacaoExiste) {
    exit("Publicação não encontrada.");
}

// Salva o comentário na tabela correta
$sql = "INSERT INTO comentarios_post
        (usuario_id, post_id, conteudo)
        VALUES (?, ?, ?)";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    error_log("Erro ao preparar comentário: " . $conexao->error);
    exit("Não foi possível preparar o comentário. Verifique a configuração do banco.");
}

$stmt->bind_param("iis", $usuario_id, $post_id, $conteudo);

if ($stmt->execute()) {
    $stmt->close();
    $conexao->close();

    header("Location: ver_comentarios.php?post_id=" . $post_id);
    exit;
}

// Registra o erro no log do servidor
error_log("Erro ao salvar comentário: " . $stmt->error);

$stmt->close();
$conexao->close();

exit("Não foi possível salvar o comentário.");
?>
