<?php
include_once "./config.php";

// Captura os parâmetros do CKEditor
$ckeditorParams = http_build_query([
    'CKEditor' => $_GET['CKEditor'] ?? '',
    'CKEditorFuncNum' => $_GET['CKEditorFuncNum'] ?? '',
    'langCode' => $_GET['langCode'] ?? '',
]);

// Obter as pastas com status=1
$stmt = $conn->prepare("SELECT id, dir_name, path FROM wcg_upload_dir WHERE status = 1");
$stmt->execute();
$folders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?= SYSTEM_TITLE; ?></title>

    <?php include_once "./dependences.php"; ?>

</head>

<body>
    <header>
        <?php include_once "./layout/navbar.php"; ?>
    </header>

    <div class="container mt-5">
        <h2 class="mb-4">Upload de Arquivos</h2>
        <form action="upload-exec.php?<?= $ckeditorParams ?>" method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <!-- Select de Pastas -->
                <div class="col-md-4">
                    <label for="folderSelect" class="form-label">Selecione uma Pasta</label>
                    <select id="folderSelect" name="folder_id" class="form-select select2" required>
                        <option value="" disabled selected>Escolha uma pasta...</option>
                        <?php foreach ($folders as $folder): ?>
                            <option value="<?= htmlspecialchars($folder['id']); ?>">
                                <?= htmlspecialchars($folder['dir_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <!-- Campo de Arquivos -->
                <label for="fileUpload" class="form-label">Selecione Arquivos</label>
                <input type="file" id="fileUpload" name="files[]" class="form-control" multiple required>
                <small class="text-muted">Tipos permitidos: Imagens (JPG, PNG, GIF, CDR, PSD) e Documentos (PDF, DOC, DOCX, XLS, XLSX, TXT, ZIP, RAR).</small>
            </div>
            <button type="submit" class="btn btn-primary">Fazer Upload</button>
            <a href="./thumbnail.php?<?= $ckeditorParams ?>" target="_self" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>


</body>

</html>