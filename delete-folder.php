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

    // Remove a pasta física
    function deleteFolder($path)
    {
        if (is_dir($path)) {
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file) {
                $fullPath = "$path/$file";
                if (is_dir($fullPath)) {
                    deleteFolder($fullPath);
                } else {
                    unlink($fullPath);
                }
            }
            return rmdir($path);
        }
        return false;
    }

    if (deleteFolder($path)) {
        // Exclui o registro do banco de dados
        $deleteStmt = $conn->prepare("DELETE FROM wcg_upload_dir WHERE id = :id");
        $deleteStmt->bindParam(':id', $id);

        if ($deleteStmt->execute()) {
            $_SESSION['message'] = "Pasta excluída com sucesso.";
        } else {
            $_SESSION['message'] = "Erro ao excluir do banco de dados.";
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
