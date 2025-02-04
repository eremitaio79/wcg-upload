<?php
session_start();
include_once "./config.php";

// Captura os parâmetros do CKEditor
$ckeditorParams = http_build_query([
    'CKEditor' => $_GET['CKEditor'] ?? '',
    'CKEditorFuncNum' => $_GET['CKEditorFuncNum'] ?? '',
    'langCode' => $_GET['langCode'] ?? '',
]);

// Função para buscar arquivos e pastas associadas
function getFilesWithFolders($conn)
{
    $query = "
        SELECT 
            f.id AS file_id,
            f.filename,
            f.path,
            f.status AS file_status,
            d.dir_name AS folder_name
        FROM 
            wcg_upload_files f
        INNER JOIN 
            wcg_upload_dir d 
        ON 
            f.id_dir = d.id
        ORDER BY f.id DESC
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$files = getFilesWithFolders($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SYSTEM_TITLE; ?></title>

    <?php include_once "./dependences.php"; ?>

    <style>
        /* Contêiner para as miniaturas */
        .thumb-container {
            width: 100px;
            /* Largura fixa para a miniatura */
            height: 100px;
            /* Altura fixa para manter o formato quadrado */
            overflow: hidden;
            /* Esconde qualquer parte da imagem que ultrapasse o contêiner */
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            /* Cor de fundo neutra para documentos */
            border: 1px solid #ddd;
            /* Borda opcional */
            border-radius: 8px;
            /* Bordas arredondadas para o contêiner */
        }

        /* Imagem dentro do contêiner, para garantir que preencha o quadrado mantendo o centro */
        .thumb-img {
            cursor: pointer;
            /* Mostra a mão ao passar o mouse */
            object-fit: cover;
            /* Faz a imagem preencher o contêiner sem distorção */
            width: 100%;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* Efeito de borda e aumento ao passar o mouse */
        .thumb-container:hover .thumb-img {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* Ícone de arquivo para os documentos */
        .thumb-icon {
            font-size: 48px;
            /* Tamanho do ícone */
            color: #888;
            /* Cor do ícone */
        }

        .thumb-container i.thumb-icon {
            cursor: default;
            /* Mantenha o cursor padrão para ícones que não são clicáveis */
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
                <!-- Exibe mensagens de sessão, se houver -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div id="success-alert" class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><?= $_SESSION['message']; ?></strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const alertElement = document.getElementById("success-alert");
                        if (alertElement) {
                            setTimeout(() => {
                                alertElement.classList.remove("show");
                                alertElement.classList.add("fade");
                                setTimeout(() => alertElement.remove(), 500);
                            }, 5000);
                        }
                    });
                </script>

                <h3>Gerenciador de Arquivos</h3>

                <hr />

                <!-- Exibe alerta se não houver arquivos -->
                <?php if (empty($files)): ?>
                    <div class="alert alert-info" role="alert">
                        Nenhum arquivo disponível.
                    </div>
                <?php else: ?>
                    <!-- Tabela de arquivos -->
                    <table id="filesTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pasta</th>
                                <th>Preview</th>
                                <th>Caminho</th>
                                <!-- <th>Status</th> -->
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($files as $file): ?>
                                <?php
                                // Determina se o arquivo é uma imagem
                                $isImage = preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)$/i', $file['filename']);
                                $fileExtension = pathinfo($file['filename'], PATHINFO_EXTENSION);
                                ?>

                                <tr>
                                    <td><?= htmlspecialchars($file['file_id']); ?></td>
                                    <td><?= htmlspecialchars($file['folder_name']); ?></td>
                                    <td>
                                        <?php if ($isImage): ?>
                                            <div class="thumb-container">
                                                <img src="./files/img/<?= htmlspecialchars($file['folder_name']); ?>/<?= htmlspecialchars($file['filename']); ?>"
                                                     class="thumb-img"
                                                     data-filepath="<?= htmlspecialchars($file['path']); ?>"
                                                     alt="<?= htmlspecialchars($file['filename']); ?>" />
                                            </div>
                                        <?php else: ?>
                                            <div class="thumb-container">
                                                <i class="fas fa-file-alt thumb-icon"></i> <!-- Ícone de arquivo genérico -->
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($file['path']); ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="view-file.php?id=<?= $file['file_id']; ?>&<?= $ckeditorParams ?>" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Visualizar"><i class="fa-solid fa-eye"></i></a>
                                        <form action="delete-file.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este arquivo?')">
                                            <input type="hidden" name="id" value="<?= $file['file_id']; ?>">
                                            <input type="hidden" name="ckedit" value="<?= $ckeditorParams; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Excluir">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Modal para preview -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Preview do Arquivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewImage" src="" alt="Preview" class="img-fluid" style="max-height: 500px;">
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Inicializando o DataTable
            $('#filesTable').DataTable({
                order: [
                    [0, 'desc']
                ],
                language: {
                    url: './js/datatables2.2.1/pt-BR.json'
                },
                pageLength: 25
            });

            // Envia o caminho da imagem para o CKEditor ao clicar na miniatura
            $('.thumb-img').on('click', function() {
                const filepath = $(this).data('filepath');
                window.opener.CKEDITOR.tools.callFunction(
                    '<?= $_GET['CKEditorFuncNum'] ?>', 
                    filepath
                );
                window.close();
            });
        });
    </script>

</body>

</html>