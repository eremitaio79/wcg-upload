<?php
include_once "./config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Busca a pasta no banco de dados
    $stmt = $conn->prepare("SELECT path FROM wcg_upload_dir WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $folder = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$folder) {
        $_SESSION['message'] = "Pasta não encontrada.";
        header("Location: manage-dir.php");
        exit();
    }

    $path = $folder['path'];

    // Remove a pasta física e seus arquivos
    function deleteFolder($path)
    {
        if (is_dir($path)) {
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file) {
                $fullPath = "$path/$file";
                if (is_dir($fullPath)) {
                    deleteFolder($fullPath);
                } else {
                    unlink($fullPath); // Exclui o arquivo
                }
            }
            return rmdir($path); // Remove o diretório vazio
        }
        return false;
    }

    if (deleteFolder($path)) {
        try {
            // Inicia uma transação para garantir consistência
            $conn->beginTransaction();

            // Exclui todos os arquivos associados à pasta
            $deleteFilesStmt = $conn->prepare("DELETE FROM wcg_upload_files WHERE id_dir = :id");
            $deleteFilesStmt->bindParam(':id', $id);
            $deleteFilesStmt->execute();

            // Exclui o registro da pasta no banco de dados
            $deleteFolderStmt = $conn->prepare("DELETE FROM wcg_upload_dir WHERE id = :id");
            $deleteFolderStmt->bindParam(':id', $id);
            $deleteFolderStmt->execute();

            // Confirma a transação
            $conn->commit();
            $_SESSION['message'] = "Pasta e arquivos excluídos com sucesso.";
        } catch (Exception $e) {
            $conn->rollBack();
            $_SESSION['message'] = "Erro ao excluir do banco de dados: " . $e->getMessage();
        }
    } else {
        $_SESSION['message'] = "Erro ao excluir a pasta física.";
    }

    // Redireciona para a página de gerenciamento de diretórios
    header("Location: manage-dir.php");
    exit();
} else {
    $_SESSION['message'] = "Método de requisição inválido.";
    header("Location: manage-dir.php");
    exit();
}
