

<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../CriarConta2/login.php");
    exit();
}

include "../CriarConta2/conexao.php";

$usuario_id = $_SESSION["usuario_id"];
$erro = "";

// Buscar a foto atual
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
    $removerFoto = isset($_POST["remover_foto"])
    && $_POST["remover_foto"] === "1";

    if ($removerFoto) {
    $foto = "";
    }
    $visibilidade = $_POST["visibilidade"] ?? "publico";

    if (!in_array($visibilidade, ["publico", "privado"], true)) {
        $visibilidade = "publico";
    }

    // Verificar envio de uma nova foto
        if (
        !$removerFoto &&
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

    // Salvar somente se não houver erros
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
    <link rel="stylesheet" href="editar_perfil.css">
</head>

<body>

<main class="editar-perfil-container">

    <header class="editar-perfil-titulo">
        <div class="editar-perfil-icone">👤</div>

        <div>
            <h1>Editar perfil</h1>
            <p>Personalize suas informações e deixe seu perfil com a sua cara.</p>
        </div>
    </header>

    <?php if ($erro !== ""): ?>
        <div class="alerta-erro">
            <?= htmlspecialchars($erro, ENT_QUOTES, "UTF-8") ?>
        </div>
    <?php endif; ?>

    <section class="editar-perfil-card">

        <div class="editar-perfil-card-titulo">
            <h2>Informações do perfil</h2>
            <p>Atualize sua biografia, seu GitHub e suas preferências.</p>
        </div>

        <form
            class="form-editar-perfil"
            action="editar_perfil.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <div class="foto-perfil-area">

                <div class="foto-perfil-preview">
                    <?php if (!empty($foto)): ?>
                        <img
                            src="<?= htmlspecialchars($foto, ENT_QUOTES, "UTF-8") ?>"
                            alt="Foto atual do perfil"
                        >
                    <?php else: ?>
                        <span>👤</span>
                    <?php endif; ?>
                </div>

                <div class="foto-perfil-info">
                    <h3>Foto de perfil</h3>
                    <p>Escolha uma imagem para personalizar seu perfil.</p>

                    <input
                        type="file"
                        id="foto"
                        name="foto"
                        accept="image/jpeg,image/png,image/webp"
                    >

                    <label for="foto" class="botao-escolher-foto">
                        Escolher imagem
                    </label>
                    <?php if (!empty($foto)): ?>
                    <label class="botao-remover-foto">
                    <input
                    type="checkbox"
                    name="remover_foto"
                    value="1"
                    >
                     Remover foto atual
                    </label>
                    <?php endif; ?>
                    <small>JPG, PNG ou WebP. Tamanho máximo: 2 MB.</small>
                </div>

            </div>

            <div class="campo-editar-perfil">
                <label for="bio">Biografia</label>

                <textarea
                    id="bio"
                    name="bio"
                    maxlength="2000"
                    placeholder="Conte um pouco sobre você..."
                ><?= htmlspecialchars($bio, ENT_QUOTES, "UTF-8") ?></textarea>

                <small>Conte à comunidade quem você é e quais são seus interesses.</small>
            </div>

            <div class="campo-editar-perfil">
                <label for="github">Perfil do GitHub</label>

                <input
                    type="text"
                    id="github"
                    name="github"
                    maxlength="255"
                    value="<?= htmlspecialchars($github, ENT_QUOTES, "UTF-8") ?>"
                    placeholder="https://github.com/seu-usuario"
                >

                <small>Informe o endereço do seu perfil no GitHub.</small>
            </div>

            <div class="campo-editar-perfil">
                <label for="visibilidade">Visibilidade do perfil</label>

                <select id="visibilidade" name="visibilidade">
                    <option
                        value="publico"
                        <?= $visibilidade === "publico" ? "selected" : "" ?>
                    >
                        Público
                    </option>

                    <option
                        value="privado"
                        <?= $visibilidade === "privado" ? "selected" : "" ?>
                    >
                        Privado
                    </option>
                </select>

                <small>Escolha se seu perfil ficará público ou privado.</small>
            </div>

            <div class="acoes-editar-perfil">
                <button type="submit" class="botao-salvar-perfil">
                    Salvar alterações
                </button>

                <a href="perfil.php" class="botao-cancelar-perfil">
                    Cancelar
                </a>
            </div>

        </form>
    </section>

</main>

<script>
const campoFoto = document.getElementById("foto");
const preview = document.querySelector(".foto-perfil-preview");

campoFoto.addEventListener("change", function () {
    const arquivo = this.files[0];

    if (!arquivo) return;

    if (!["image/jpeg", "image/png", "image/webp"].includes(arquivo.type)) {
        alert("Escolha uma imagem JPG, PNG ou WebP.");
        this.value = "";
        return;
    }

    if (arquivo.size > 2 * 1024 * 1024) {
        alert("A imagem deve ter no máximo 2 MB.");
        this.value = "";
        return;
    }

    const leitor = new FileReader();

    leitor.onload = function (evento) {
        preview.innerHTML = "";

        const imagem = document.createElement("img");
        imagem.src = evento.target.result;
        imagem.alt = "Pré-visualização da nova foto";

        preview.appendChild(imagem);
    };

    leitor.readAsDataURL(arquivo);
});
</script>

</body>
</html>

<?php
$conexao->close();
?>
