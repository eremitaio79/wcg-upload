<?php
include_once "./config.php";

// Captura os parâmetros do CKEditor
$ckeditorParams = http_build_query([
    'CKEditor' => $_GET['CKEditor'] ?? '',
    'CKEditorFuncNum' => $_GET['CKEditorFuncNum'] ?? '',
    'langCode' => $_GET['langCode'] ?? '',
]);

// Captura e sanitiza a variável GET
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';

// Obtém o ID do arquivo da URL
$file_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Busca informações do arquivo no banco de dados
$query = "SELECT filename, path FROM wcg_upload_files WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $file_id, PDO::PARAM_INT);
$stmt->execute();
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$file) {
    die("Arquivo não encontrado.");
}

// Transforme o caminho absoluto em um URL acessível publicamente
$file_path = str_replace($baseDir, '', $file['path']);
$file_url = URL_SISTEMA . ltrim($file_path, '/');

// Determina o tipo de arquivo
$file_extension = strtolower(pathinfo($file['filename'], PATHINFO_EXTENSION));
$is_image = in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Visualizar Arquivo</title>

    <?php include_once "./dependences.php"; ?>

    <style>
        .file-container {
            text-align: center;
            padding: 20px;
        }

        .file-container img {
            max-width: 100%;
            height: auto;
        }

        .file-container a {
            text-decoration: none;
            /* color: #007bff; */
        }
    </style>
</head>

<body>
    <header>
        <?php include_once "./layout/navbar.php"; ?>
    </header>

    <main class="container my-5">
        <div class="row">
            <div class="col-12">
                <div class="file-container">
                    <div class="row">
                        <div class="col-12">
                            <?php if ($type === 'input') { ?>
                                <a href="./thumbnail-input.php?type=input" target="_self" class="btn btn-primary">Voltar</a>
                            <?php } else { ?>
                                <a href="./thumbnail.php?<?= $ckeditorParams ?>" target="_self" class="btn btn-primary">Voltar</a>
                            <?php } ?>
                            <hr />
                        </div>
                    </div>
                    <h4>Arquivo: <?= htmlspecialchars($file['filename']); ?></h4>

                    <?php if ($is_image): ?>
                        <!-- Exibe a imagem -->
                        <img src="<?= $file_url; ?>" alt="<?= htmlspecialchars($file['filename']); ?>" class="rounded">
                    <?php else: ?>
                        <!-- Exibe link para download ou visualização -->
                        <p>O arquivo não pode ser exibido diretamente no navegador. Use o link abaixo para acessar:</p>
                        <a href="<?= $file_url; ?>" target="_blank" class="btn btn-primary">Abrir Arquivo</a>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <hr />
                            <?php if ($type === 'input') { ?>
                                <a href="./thumbnail-input.php?type=input" target="_self" class="btn btn-primary">Voltar</a>
                            <?php } else { ?>
                                <a href="./thumbnail.php?<?= $ckeditorParams ?>" target="_self" class="btn btn-primary">Voltar</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>