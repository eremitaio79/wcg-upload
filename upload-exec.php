<?php
include_once './config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $folderId = $_POST['folder_id'] ?? null;

    // Verifica se uma pasta foi selecionada
    if (!$folderId) {
        die("Selecione uma pasta para fazer o upload.");
    }

    // Obtém o caminho da pasta selecionada
    $stmt = $conn->prepare("SELECT path FROM wcg_upload_dir WHERE id = :id AND status = 1");
    $stmt->bindParam(':id', $folderId, PDO::PARAM_INT);
    $stmt->execute();
    $folder = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$folder) {
        die("Pasta selecionada inválida ou inativa.");
    }

    $folderPath = rtrim($folder['path'], '/');

    // Tipos de arquivos permitidos
    $allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif', 'cdr', 'psd'];
    $allowedDocTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip', 'rar'];

    // Verifica e processa os arquivos enviados
    foreach ($_FILES['files']['name'] as $key => $originalName) {
        $fileTmp = $_FILES['files']['tmp_name'][$key];
        $fileSize = $_FILES['files']['size'][$key];
        $fileError = $_FILES['files']['error'][$key];

        // Normaliza o nome do arquivo
        $fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);

        // Substitui espaços por '_' e remove caracteres inválidos
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '_', $baseName));

        // Gera o novo nome do arquivo com a extensão original
        $sanitizedFileName = $baseName . '.' . $fileExt;

        // Verifica se o arquivo é válido
        if ($fileError === 0) {
            if (in_array($fileExt, $allowedImageTypes) || in_array($fileExt, $allowedDocTypes)) {
                $targetFile = $folderPath . '/' . $sanitizedFileName;

                // Garante que o nome do arquivo seja único
                $i = 1;
                while (file_exists($targetFile)) {
                    $sanitizedFileName = $baseName . "_{$i}." . $fileExt;
                    $targetFile = $folderPath . '/' . $sanitizedFileName;
                    $i++;
                }

                // Move o arquivo para o destino final
                if (move_uploaded_file($fileTmp, $targetFile)) {
                    // Insere os dados do arquivo na tabela
                    $fileType = in_array($fileExt, $allowedImageTypes) ? 'image' : 'document';
                    $dimensions = '';

                    // Se for uma imagem, tenta obter as dimensões
                    if ($fileType === 'image') {
                        $imageSize = @getimagesize($targetFile);
                        if ($imageSize) {
                            $dimensions = $imageSize[0] . 'x' . $imageSize[1];
                        }
                    }

                    $relativePath = './wcg-upload/' . ltrim($targetFile, './'); // Adiciona o prefixo corretamente

                    $stmt = $conn->prepare("
                        INSERT INTO wcg_upload_files (id_dir, filename, path, type, dimensions, status, created_at) 
                        VALUES (:id_dir, :filename, :path, :type, :dimensions, :status, NOW())
                    ");
                    $stmt->execute([
                        ':id_dir' => $folderId,
                        ':filename' => $sanitizedFileName,
                        ':path' => $relativePath,
                        ':type' => $fileType,
                        ':dimensions' => $dimensions,
                        ':status' => 1
                    ]);
                } else {
                    echo "Erro ao mover o arquivo '{$originalName}'.<br>";
                }
            } else {
                echo "Arquivo '{$originalName}' não permitido. Tipos aceitos: Imagens e Documentos.<br>";
            }
        } else {
            echo "Erro ao fazer upload do arquivo '{$originalName}'.<br>";
        }
    }

    // Redireciona para index.php após o upload
    header("Location: thumbnail.php");
    exit;
}
