
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
if (!$post_id || $conteudo === "") {
    header("Location: ../publicacoes.php");
    exit;
}

// Salva o comentário no banco
$sql = "INSERT INTO comentario
        (usuario_id, post_id, conteudo)
        VALUES (?, ?, ?)";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    exit("Erro ao preparar o comentário.");
}

$stmt->bind_param("iis", $usuario_id, $post_id, $conteudo);

if ($stmt->execute()) {
    $stmt->close();

    header(
        "Location: ver_comentarios.php?post_id=" . $post_id
    );
    exit;
}

$stmt->close();
exit("Não foi possível salvar o comentário.");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Git Club - Feed</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

  <header class="cabecalho-heroi">
    <span class="logo-heroi"></span>
    <h1><br>Comentarios</h1> 
    <p class="heroi-subtitulo"></p>

    <div class="heroi-botoes">
      <a href="../publicacoes.php" class="botao-login botao-login--destaque"> < voltar</a>
    </div>
  </header>