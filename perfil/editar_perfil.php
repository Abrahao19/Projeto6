

<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../CriarConta2/login.php");
    exit();
}

include "../CriarConta2/conexao.php";

$usuario_id = $_SESSION["usuario_id"];
$erro = "";

// Buscar a foto atual para não apagá-la ao editar outros campos
$stmt = $conexao->prepare(
    "SELECT foto FROM perfil WHERE usuario_id = ?"
);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
$perfilAtual = $resultado->fetch_assoc() ?? [];
$fotoAtual = $perfilAtual["foto"] ?? "";

$stmt->close();

// Salvar o formulário
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bio = trim($_POST["bio"] ?? "");
    $github = trim($_POST["github"] ?? "");
    $foto = $fotoAtual;
    $visibilidade = $_POST["visibilidade"] ?? "publico";

    if (!in_array($visibilidade, ["publico", "privado"], true)) {
        $visibilidade = "publico";
    }

    // Verificar se uma nova foto foi enviada
    if (
        isset($_FILES["foto"]) &&
        $_FILES["foto"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {
        if ($_FILES["foto"]["error"] !== UPLOAD_ERR_OK) {
            $erro = "Erro ao enviar a imagem.";
        } elseif ($_FILES["foto"]["size"] > 2 * 1024 * 1024) {
            $erro = "A imagem deve ter no máximo 2 MB.";
        } else {
            $temporario = $_FILES["foto"]["tmp_name"];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $temporario);
            finfo_close($finfo);

            $formatos = [
                "image/jpeg" => "jpg",
                "image/png" => "png",
                "image/webp" => "webp"
            ];

            if (
                !isset($formatos[$mime]) ||
                getimagesize($temporario) === false
            ) {
                $erro = "Escolha uma imagem JPG, PNG ou WebP válida.";
            } else {
                $pasta = __DIR__ . "/../uploads/perfis/";

                // Criar a pasta caso ela não exista
                if (!is_dir($pasta)) {
                    mkdir($pasta, 0755, true);
                }

                $nomeArquivo = bin2hex(random_bytes(16))
                    . "." . $formatos[$mime];

                if (move_uploaded_file(
                    $temporario,
                    $pasta . $nomeArquivo
                )) {
                    $foto = "../uploads/perfis/" . $nomeArquivo;
                } else {
                    $erro = "Não foi possível salvar a imagem.";
                }
            }
        }
    }

    // Só salvar os dados se não houver erros
    if ($erro === "") {
        $stmt = $conexao->prepare(
            "INSERT INTO perfil
                (usuario_id, bio, github, foto, visibilidade)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                bio = VALUES(bio),
                github = VALUES(github),
                foto = VALUES(foto),
                visibilidade = VALUES(visibilidade)"
        );

        $stmt->bind_param(
            "issss",
            $usuario_id,
            $bio,
            $github,
            $foto,
            $visibilidade
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: perfil.php");
            exit();
        }

        $erro = "Não foi possível salvar as alterações.";
        $stmt->close();
    }
}

// Buscar os dados para preencher o formulário
$stmt = $conexao->prepare(
    "SELECT bio, github, foto, visibilidade
     FROM perfil
     WHERE usuario_id = ?"
);

$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();
$perfil = $resultado->fetch_assoc() ?? [];

$stmt->close();

$bio = $perfil["bio"] ?? "";
$github = $perfil["github"] ?? "";
$foto = $perfil["foto"] ?? "";
$visibilidade = $perfil["visibilidade"] ?? "publico";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar perfil - Git Club</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <main class="conteudo">
        <h1>Editar perfil</h1>

        <?php if ($erro !== ""): ?>
            <p><?= htmlspecialchars($erro, ENT_QUOTES, "UTF-8") ?></p>
        <?php endif; ?>

      

<form action="editar_perfil.php" method="POST" enctype="multipart/form-data">


            <label for="bio">Biografia</label>
            <textarea id="bio" name="bio" maxlength="2000"><?= htmlspecialchars($bio, ENT_QUOTES, "UTF-8") ?></textarea>

            <br><br>

           
<label for="foto">Foto de perfil</label>
<input
    type="file"
    id="foto"
    name="foto"
    accept="image/jpeg,image/png,image/webp"
>
<p>Formatos aceitos: JPG, PNG e WebP. Máximo: 2 MB.</p>

           

          
            <br>

            <label for="visibilidade">Visibilidade</label>
            <select id="visibilidade" name="visibilidade">
                <option value="publico" <?= $visibilidade === "publico" ? "selected" : "" ?>>
                    Público
                </option>
                <option value="privado" <?= $visibilidade === "privado" ? "selected" : "" ?>>
                    Privado
                </option>
            </select>

            <br><br>

            <button type="submit">Salvar alterações</button>
            <a href="perfil.php">Cancelar</a>
        </form>
    </main>

</body>
</html>
