<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados enviados
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifique o que foi enviado
    var_dump($data); // Depuração: Exibe o que foi enviado
    die(); // Para a execução para não continuar no código

    $image = $data['image'];
    $path = $data['path'];

    // Decodifica a imagem base64
    $image_parts = explode(";base64,", $image);
    $image_base64 = base64_decode($image_parts[1]);

    // Salva a imagem no caminho original
    if (file_put_contents($path, $image_base64)) {
        echo json_encode(['message' => 'Imagem salva com sucesso!']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Erro ao salvar a imagem.']);
    }
}
