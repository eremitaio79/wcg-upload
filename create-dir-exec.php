<?php

// Incluir a configuração e a conexão com o banco de dados
include_once "./config.php";

// Função para limpar o nome da pasta (remove espaços, caracteres especiais, coloca tudo em minúsculas e substitui espaços por underscores)
function cleanFolderName($folderName)
{
    // Substitui espaços por underscores
    $folderName = str_replace(' ', '_', $folderName);

    // Converte para minúsculas
    $folderName = strtolower($folderName);

    // Remove caracteres especiais, mantendo apenas letras, números, _ e -
    $folderName = preg_replace('/[^a-z0-9-_]/', '', $folderName);

    // Remove espaços extras no início e no fim
    $folderName = trim($folderName);

    return $folderName;
}

// Função para criar a nova pasta
function createFolder($folderName, $folderType)
{
    // Verifica se $basePath está definido no arquivo config.php
    global $basePath;
    if (!isset($basePath) || empty($basePath)) {
        return "Caminho base não está definido!";
    }

    $targetPath = '';

    // Limpeza do nome da pasta
    $cleanedName = cleanFolderName($folderName);

    // Verifica se a escolha do tipo é para 'img' ou 'docs'
    if ($folderType == 'pdf') {
        $targetPath = $basePath . 'docs/' . $cleanedName;
    } elseif ($folderType == 'img') {
        $targetPath = $basePath . 'img/' . $cleanedName;
    } else {
        return "Tipo de pasta inválido!";
    }

    // Verifica se a pasta já existe
    if (file_exists($targetPath)) {
        return "A pasta já existe!";
    }

    // Cria a pasta
    if (mkdir($targetPath, 0777, true)) {
        return $targetPath; // Retorna o caminho da pasta criada
    } else {
        return "Erro ao criar a pasta!";
    }
}

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $folderName = trim($_POST['folderName']);
    $folderType = $_POST['folderType'];

    // Chama a função para criar a pasta
    $message = createFolder($folderName, $folderType);

    // Verifica se a pasta foi criada com sucesso
    if (strpos($message, 'Erro') === false && strpos($message, 'A pasta já existe') === false) {
        // Ajusta o nome da pasta para ser o mesmo no banco de dados
        $cleanedFolderName = cleanFolderName($folderName);

        // Se a pasta foi criada, registra no banco de dados
        $stmt = $conn->prepare("INSERT INTO wcg_upload_dir (path, dir_name, dir_type, status, created_at) VALUES (:path, :dir_name, :dir_type, :status, :created_at)");
        $stmt->bindParam(':path', $message); // O caminho completo da pasta
        $stmt->bindParam(':dir_name', $cleanedFolderName);
        $stmt->bindParam(':dir_type', $folderType); // Inserção do tipo de pasta
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':created_at', $created_at);

        // Definindo o status como 1 (ativo) e a data de criação
        $status = 1;
        $created_at = date('Y-m-d H:i:s');

        if ($stmt->execute()) {
            // Redireciona para index.php com a mensagem de sucesso
            session_start();
            $_SESSION['message'] = "Pasta criada com sucesso!";
            header("Location: manage-dir.php");
            exit();
        } else {
            // Caso não consiga inserir no banco de dados
            session_start();
            $_SESSION['message'] = "Erro ao registrar a pasta no banco de dados.";
            header("Location: manage-dir.php");
            exit();
        }
    } else {
        // Se houve erro ao criar a pasta ou se já existe
        session_start();
        $_SESSION['message'] = $message; // Mensagem de erro
        header("Location: create-dir.php"); // Redireciona de volta para create-dir.php
        exit();
    }
}
