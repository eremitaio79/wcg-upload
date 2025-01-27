<?php
include_once "./config.php";

// Obtém o ID do arquivo a partir da URL
$file_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Busca informações do arquivo no banco de dados
$query = "SELECT filename, path FROM wcg_upload_files WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $file_id, PDO::PARAM_INT);
$stmt->execute();
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$file) {
    die("Arquivo não encontrado.");
}

// Transforme o caminho absoluto em um URL acessível publicamente
$image_url = URL_SISTEMA . ltrim($file['path'], '/');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar Imagem</title>

    <?php include_once "./dependences.php"; ?>

    <!-- TUI Image Editor CSS -->
    <!-- <link rel="stylesheet" href="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.css"> -->
    <style>
        #tui-image-editor {
            height: 700px;
            margin: auto;
        }
    </style>
</head>

<body>
    <header>
        <?php include_once "./layout/navbar.php"; ?>
    </header>

    <main class="container my-5">
        <div class="row">
            <div class="col-12">
                <h3>Editar Imagem</h3>
                <p>Você está editando o arquivo: <strong><?= htmlspecialchars($file['filename']); ?></strong></p>

                <!-- Editor de Imagem -->
                <div id="tui-image-editor"></div>

                <!-- Botão para salvar alterações -->
                <button id="save-image" class="btn btn-primary mt-3">Salvar Alterações</button>
            </div>
        </div>
    </main>

    <!-- TUI Image Editor JS -->
    <!-- <script src="https://uicdn.toast.com/tui-code-snippet/latest/tui-code-snippet.js"></script>
    <script src="https://uicdn.toast.com/tui-color-picker/latest/tui-color-picker.js"></script>
    <script src="https://uicdn.toast.com/tui-image-editor/latest/tui-image-editor.js"></script> -->

    <script>
        // Inicializa o editor TUI
        const imageEditor = new tui.ImageEditor('#tui-image-editor', {
            includeUI: {
                loadImage: {
                    path: "<?= $image_url; ?>", // Caminho da imagem
                    name: "<?= htmlspecialchars($file['filename']); ?>"
                },
                theme: {}, // Tema padrão
                menu: ['crop', 'resize', 'filter'], // Ferramentas disponíveis
                initMenu: 'filter',
                uiSize: {
                    width: '100%',
                    height: '600px'
                },
                menuBarPosition: 'bottom'
            },
            cssMaxWidth: 700,
            cssMaxHeight: 500,
            usageStatistics: false
        });

        // Evento para salvar a imagem editada
        document.getElementById('save-image').addEventListener('click', () => {
            const dataURL = imageEditor.toDataURL(); // Obtém os dados da imagem editada

            // Envia a imagem modificada para o servidor
            fetch('save-image.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        image: dataURL,
                        path: "<?= addslashes($file['path']); ?>" // Caminho do arquivo original
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    window.location.href = 'index.php';
                })
                .catch(error => console.error('Erro ao salvar a imagem:', error));
        });
    </script>
</body>

</html>