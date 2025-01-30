<?php
session_start();
include_once "./config.php";

// Captura os parâmetros do CKEditor
$ckeditorParams = http_build_query([
    'CKEditor' => $_GET['CKEditor'] ?? '',
    'CKEditorFuncNum' => $_GET['CKEditorFuncNum'] ?? '',
    'langCode' => $_GET['langCode'] ?? '',
]);

// Obtém o ID da pasta a partir da URL
$folder_id = $_GET['id'] ?? null;

if (!$folder_id) {
    $_SESSION['message'] = "Pasta não encontrada!";
    header("Location: manage-dir.php");
    exit;
}

// Busca a pasta pelo ID
$query_folder = "SELECT * FROM wcg_upload_dir WHERE id = :folder_id";
$stmt_folder = $conn->prepare($query_folder);
$stmt_folder->bindParam(':folder_id', $folder_id, PDO::PARAM_INT);
$stmt_folder->execute();
$folder = $stmt_folder->fetch(PDO::FETCH_ASSOC);

if (!$folder) {
    $_SESSION['message'] = "Pasta não encontrada!";
    header("Location: manage-dir.php");
    exit;
}

// Busca os arquivos da pasta
$query_files = "SELECT * FROM wcg_upload_files WHERE id_dir = :folder_id";
$stmt_files = $conn->prepare($query_files);
$stmt_files->bindParam(':folder_id', $folder_id, PDO::PARAM_INT);
$stmt_files->execute();
$files = $stmt_files->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SYSTEM_TITLE; ?> - Visualizar Pasta</title>

    <?php include_once "./dependences.php"; ?>
    <style>
        .card {
            width: 150px;
            height: 150px;
            cursor: pointer;
        }

        .card img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }

        .card-doc {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            font-size: 24px;
            color: #6c757d;
        }

        .card-doc i {
            font-size: 48px;
        }

        .modal-body img {
            max-width: 100%;
            max-height: 500px;
            display: block;
            margin: 0 auto;
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
                <h3>Arquivos da Pasta: <?= htmlspecialchars($folder['dir_name']); ?></h3>
                <a href="manage-dir.php?<?= $ckeditorParams ?>" class="btn btn-secondary btn-sm mb-3">Voltar</a>
                <hr />
                <div class="d-flex flex-wrap gap-3">
                    <?php if (empty($files)): ?>
                        <div class="alert alert-info" role="alert">
                            Nenhum arquivo encontrado na pasta.
                        </div>
                    <?php else: ?>
                        <?php foreach ($files as $file): ?>
                            <?php $isImage = preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)$/i', $file['filename']); ?>
                            <div class="card"
                                data-bs-toggle="<?= $isImage ? 'modal' : ''; ?>"
                                data-bs-target="<?= $isImage ? '#previewModal' : ''; ?>"
                                onclick="<?= $isImage ? "openModal('" . htmlspecialchars($file['path']) . "')" : "window.open('" . htmlspecialchars($file['path']) . "', '_blank')"; ?>">
                                <?php if ($isImage): ?>
                                    <img src="<?= htmlspecialchars($file['path']); ?>" alt="<?= htmlspecialchars($file['filename']); ?>" />
                                <?php else: ?>
                                    <div class="card-doc">
                                        <i class="fa-solid fa-file"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <hr />
                <a href="manage-dir.php?<?= $ckeditorParams ?>" class="btn btn-secondary btn-sm mb-3">Voltar</a>
            </div>
        </div>
    </main>

    <!-- Modal para exibir imagens -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Visualização da Imagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Preview da Imagem">
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(imagePath) {
            document.getElementById('modalImage').src = imagePath;
        }
    </script>
</body>

</html>