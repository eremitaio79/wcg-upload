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

// Corrige a URL para evitar múltiplas barras
$image_url = rtrim(URL_SISTEMA2, '/') . '/' . ltrim($file['path'], './');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar Imagem</title>

    <?php include_once "./dependences.php"; ?>

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
                <p>Você está editando o arquivo: <strong><?= htmlspecialchars($file['filename']) ?></strong></p>
                <div id="tui-image-editor"></div>
                <div class="row">
                    <div class="col-12">
                        <button id="save-image" class="btn btn-primary mt-3">Salvar Alterações</button>
                        <a href="./thumbnail.php?<?= $ckeditorParams ?>" target="_self" class="btn btn-secondary">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Inicializa o editor TUI
        const imageEditor = new tui.ImageEditor('#tui-image-editor', {
            includeUI: {
                loadImage: {
                    path: "<?= $image_url; ?>",
                    name: "<?= htmlspecialchars($file['filename']); ?>"
                },
                theme: {},
                menu: ['crop', 'resize', 'filter'],
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
            const dataURL = imageEditor.toDataURL();
            console.log("Imagem editada:", dataURL);

            const formData = new FormData();
            formData.append("image", dataURL);
            formData.append("path", "<?= htmlspecialchars($file['path']); ?>");

            fetch('save-image.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                // window.location.href = 'index.php';
            })
            .catch(error => {
                console.error('Erro ao salvar a imagem:', error);
                alert('Ocorreu um erro ao salvar a imagem. Verifique o console.');
            });
        });
    </script>
</body>

</html>
