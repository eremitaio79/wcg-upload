<?php
session_start();
include_once "./config.php";

// Captura os dados enviados pelo formulário
$formDataPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
// var_dump($formDataPost); // Exibe os dados do formulário para depuração
extract($formDataPost);

// Captura os parâmetros do CKEditor
$ckeditorParams = http_build_query([
    'CKEditor' => $_POST['CKEditor'] ?? '',
    'CKEditorFuncNum' => $_POST['CKEditorFuncNum'] ?? '',
    'langCode' => $_POST['langCode'] ?? '',
]);

// Captura e sanitiza a variável POST
$type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : '';

// Verifica se o ID foi passado no formulário
if (isset($id)) {
    $file_id = intval($id); // Captura o ID do arquivo

    // Busca o caminho do arquivo no banco de dados
    $query = "SELECT path FROM wcg_upload_files WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $file_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        // Concatena a URL base (URL_SISTEMA2) com o caminho armazenado no banco de dados
        $file_url = URL_SISTEMA2 . ltrim($file['path'], './');
        // var_dump($file_url); // Exibe a URL para depuração

        // Converte a URL para o caminho físico no servidor
        // Supondo que URL_SISTEMA2 seja 'http://localhost/codes-project/'
        // Vamos substituir 'http://localhost' pela raiz do servidor no DOCUMENT_ROOT
        $file_completo = str_replace('http://localhost', $_SERVER['DOCUMENT_ROOT'], $file_url);
        var_dump($file_completo); // Exibe o caminho físico completo do arquivo

        // Verifica se o arquivo físico existe
        if (file_exists($file_completo)) {
            // Tenta excluir o arquivo físico
            if (unlink($file_completo)) {
                // Exclui o registro no banco de dados
                $queryDel = "DELETE FROM wcg_upload_files WHERE id = :id LIMIT 1";
                $returnDel = $conn->prepare($queryDel);
                $returnDel->bindParam(':id', $file_id, PDO::PARAM_INT);

                if ($returnDel->execute()) {
                    $_SESSION['message'] = "Arquivo excluído com sucesso.";
                } else {
                    $_SESSION['message'] = "Erro ao excluir o registro do arquivo no banco de dados.";
                }
            } else {
                $_SESSION['message'] = "Erro ao excluir o arquivo físico. Verifique as permissões de acesso.";
            }
        } else {
            $_SESSION['message'] = "Arquivo físico não encontrado: " . $file_completo;
        }
    } else {
        $_SESSION['message'] = "Erro ao buscar o arquivo no banco de dados.";
    }
} else {
    $_SESSION['message'] = "ID do arquivo não fornecido.";
}

// Redireciona para a página principal ou de gerenciamento de arquivos
if ($type === 'input') {
    header("Location: thumbnail-input.php?$type=input");
    exit;
} else {
    header("Location: thumbnail.php?$ckeditorParams");
    exit;
}
exit;
