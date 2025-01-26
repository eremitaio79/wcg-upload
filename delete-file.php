<?php
session_start();
include_once "./config.php";

// Verifica se o ID foi enviado via POST
if (isset($_POST['id'])) {
    $file_id = intval($_POST['id']);

    // Busca informações do arquivo no banco de dados
    $query = "SELECT path FROM wcg_upload_files WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $file_id, PDO::PARAM_INT);
    $stmt->execute();
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($file) {
        $file_path = $file['path'];

        // Tenta excluir o arquivo físico
        if (file_exists($file_path) && unlink($file_path)) {
            // Remove o registro do banco de dados
            $delete_query = "DELETE FROM wcg_upload_files WHERE id = :id";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bindParam(':id', $file_id, PDO::PARAM_INT);

            if ($delete_stmt->execute()) {
                $_SESSION['message'] = "Arquivo excluído com sucesso.";
            } else {
                $_SESSION['message'] = "Erro ao excluir o registro do arquivo no banco de dados.";
            }
        } else {
            $_SESSION['message'] = "Erro ao excluir o arquivo físico. Verifique se o arquivo existe e as permissões de acesso.";
        }
    } else {
        $_SESSION['message'] = "Arquivo não encontrado.";
    }
} else {
    $_SESSION['message'] = "ID do arquivo não fornecido.";
}

// Redireciona para a página principal
header("Location: index.php");
exit;
