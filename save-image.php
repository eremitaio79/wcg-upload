<?php
include_once './config.php';

// Verifica se os dados foram passados via POST
var_dump($_POST);  // Exibe tudo o que foi enviado via POST

// Verifica se a imagem e o caminho foram passados
if (!isset($_POST['image']) || !isset($_POST['path'])) {
    echo 'Erro: Dados inválidos.';
    exit;
}

$imageData = $_POST['image'];
$folderPath = $_POST['path'];

// Verifica as variáveis
var_dump($imageData, $folderPath);

// Valida o formato da imagem
preg_match('/^data:image\/(.*?);base64,/', $imageData, $matches);
$fileExt = strtolower($matches[1] ?? '');
var_dump($fileExt); // Verifica a extensão da imagem

$allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($fileExt, $allowedImageTypes)) {
    echo 'Erro: Formato de arquivo não permitido.';
    exit;
}

// Remove o prefixo do base64
$imageData = preg_replace('/^data:image\/.*;base64,/', '', $imageData);
$imageData = base64_decode($imageData);

var_dump($imageData); // Verifica os dados da imagem após decodificação

if (!$imageData) {
    echo 'Erro: Não foi possível processar a imagem.';
    exit;
}

// Gera um nome de arquivo único
$filename = time() . '_' . uniqid() . '.' . $fileExt;
$targetFile = "$folderPath/$filename";

// Verifica o caminho do arquivo
var_dump($targetFile);

if (!is_dir($folderPath)) {
    mkdir($folderPath, 0777, true);
}

if (file_put_contents($targetFile, $imageData)) {
    $relativePath = './wcg-upload/' . ltrim($targetFile, './');

    // Verifica o caminho relativo
    var_dump($relativePath);

    // Salva no banco de dados
    $stmt = $conn->prepare("INSERT INTO wcg_upload_files (id_dir, filename, path, type, status, created_at) VALUES (:id_dir, :filename, :path, 'image', 1, NOW())");
    $stmt->execute([
        ':id_dir' => $folderId,
        ':filename' => $filename,
        ':path' => $relativePath
    ]);

    echo 'Imagem salva com sucesso.';
} else {
    echo 'Erro: Falha ao salvar a imagem.';
}
