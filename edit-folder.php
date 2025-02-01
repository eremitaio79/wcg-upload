<?php
include_once "./config.php";
session_start();

// Captura os parâmetros do CKEditor
$ckeditorParams = http_build_query([
    'CKEditor' => $_GET['CKEditor'] ?? '',
    'CKEditorFuncNum' => $_GET['CKEditorFuncNum'] ?? '',
    'langCode' => $_GET['langCode'] ?? '',
]);

// Captura e sanitiza a variável GET
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $newName = trim($_POST['newName']);
    $status = $_POST['status'];

    // Substitui espaços por "_" e remove caracteres especiais
    $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '', str_replace(' ', '_', $newName));

    // Busca os dados atuais da pasta no banco de dados
    $stmt = $conn->prepare("SELECT path, dir_name FROM wcg_upload_dir WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $folder = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$folder) {
        $_SESSION['message'] = "Pasta não encontrada.";
        header("Location: manage-dir.php?$ckeditorParams");
        exit();
    }

    $oldPath = str_replace('\\', '/', $folder['path']); // Converte para barras normais
    $oldName = $folder['dir_name'];

    // Novo caminho da pasta
    $newPath = str_replace($oldName, $safeName, $oldPath);

    try {
        $conn->beginTransaction();

        // Verifica se o nome mudou e renomeia a pasta
        if ($oldPath !== $newPath) {
            if (!rename($oldPath, $newPath)) {
                throw new Exception("Erro ao renomear a pasta física.");
            }
        }

        // Atualiza a tabela wcg_upload_dir
        $updateStmt = $conn->prepare("UPDATE wcg_upload_dir SET dir_name = :newName, path = :newPath, status = :status WHERE id = :id");
        $updateStmt->bindParam(':newName', $safeName);
        $updateStmt->bindParam(':newPath', $newPath);
        $updateStmt->bindParam(':status', $status);
        $updateStmt->bindParam(':id', $id);
        $updateStmt->execute();

        // Atualiza os caminhos das imagens na tabela wcg_upload_files
        $updateFilesStmt = $conn->prepare("
                            UPDATE wcg_upload_files 
                            SET path = REPLACE(path, :oldPath, :newPath)
                            WHERE id_dir = :id
                            ");
        $updateFilesStmt->bindParam(':oldPath', $oldPath);
        $updateFilesStmt->bindParam(':newPath', $newPath);
        $updateFilesStmt->bindParam(':id', $id);
        $updateFilesStmt->execute();

        $conn->commit();
        $_SESSION['message'] = "Pasta e arquivos atualizados com sucesso.";
    } catch (Exception $e) {
        $conn->rollBack();
        $_SESSION['message'] = "Erro: " . $e->getMessage();
    }

    header("Location: manage-dir.php?$ckeditorParams");
    exit();
}

// Validação do ID na URL
$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['message'] = "ID inválido.";
    header("Location: manage-dir.php?$ckeditorParams");
    exit();
}

// Busca os dados da pasta no banco de dados
$stmt = $conn->prepare("SELECT id, dir_name, status FROM wcg_upload_dir WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$folder = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$folder) {
    $_SESSION['message'] = "Pasta não encontrada.";
    header("Location: manage-dir.php?$ckeditorParams");
    exit();
}
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

    <main class="container my-5">
        <div class="row">
            <div class="col-12">
                <h1>Editar Pasta</h1>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $folder['id']; ?>">
                    <div class="mb-3">
                        <label for="newName" class="form-label">Novo Nome:</label>
                        <input type="text" class="form-control" name="newName" id="newName" value="<?= $folder['dir_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1" <?= $folder['status'] == 1 ? 'selected' : ''; ?>>Disponível</option>
                            <option value="0" <?= $folder['status'] == 0 ? 'selected' : ''; ?>>Indisponível</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    <a href="manage-dir.php?<?= $ckeditorParams ?>" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </main>
</body>

</html>