<?php
session_start();
include_once './config.php';

// Captura os parâmetros do CKEditor
$ckeditorParams = http_build_query([
    'CKEditor' => $_GET['CKEditor'] ?? '',
    'CKEditorFuncNum' => $_GET['CKEditorFuncNum'] ?? '',
    'langCode' => $_GET['langCode'] ?? '',
]);

// Função para buscar arquivos e pastas associadas
function getFilesWithFolders($conn)
{
    $query = '
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
    ';
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$files = getFilesWithFolders($conn);

// Paginação
$itemsPerPage = 6;
$totalItems = count($files);
$totalPages = ceil($totalItems / $itemsPerPage);
$currentPage = isset($_GET['page']) ? max(1, min($totalPages, intval($_GET['page']))) : 1;

// Determinar o índice de início e fim
$startIndex = ($currentPage - 1) * $itemsPerPage;
$paginatedFiles = array_slice($files, $startIndex, $itemsPerPage);


$type = isset($_GET['type']) ? $_GET['type'] : 'default';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SYSTEM_TITLE; ?></title>

    <?php include_once './dependences.php'; ?>

    <style>
        .card-container {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 15px;
        }

        .card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 100%;
            height: 250px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 100%;
            height: auto;
            object-fit: cover;
            flex-shrink: 0;
            max-height: calc(100% - 50px);
        }

        .card-footer {
            height: 50px;
            background-color: #fff;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .card-footer .btn {
            font-size: 12px;
            padding: 4px 8px;
        }

        .pagination-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>
    <header>
        <?php include_once './layout/navbar.php'; ?>
    </header>

    <main class="container my-5">
        <h3>Gerenciador de Arquivos - Visualização em Cards</h3>
        <hr />

        <!-- Exibe alerta se não houver arquivos -->
        <?php if (empty($files)): ?>
            <div class="alert alert-info" role="alert">
                Nenhum arquivo disponível.
            </div>
        <?php else: ?>
            <!-- Contêiner de cards -->
            <div class="card-container">
                <?php foreach ($paginatedFiles as $file): ?>
                    <?php
                    $isImage = preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)$/i', $file['filename']);
                    ?>
                    <div class="card"
                        onclick="selectImage('<?= htmlspecialchars($file['path']); ?>')"
                        data-bs-toggle="tooltip"
                        data-bs-html="true"
                        title="<?= htmlspecialchars($file['filename']); ?><br />Pasta: <?= htmlspecialchars($file['folder_name']); ?>">

                        <?php if ($isImage): ?>
                            <img src="./files/img/<?= htmlspecialchars($file['folder_name']); ?>/<?= htmlspecialchars($file['filename']); ?>"
                                class="card-img-top img-fluid"
                                style="object-fit: cover; height: 200px;"
                                alt="<?= htmlspecialchars($file['filename']); ?>" />
                        <?php else: ?>
                            <i class="fas fa-file-alt fa-5x"
                                style="display: flex; justify-content: center; align-items: center; height: 100px; margin-top: 50px;"></i>
                        <?php endif; ?>

                        <!-- Rodapé do Card com Botões -->
                        <div class="card-footer bg-dark" onclick="event.stopPropagation();">
                            <a href="view-file.php?id=<?= $file['file_id']; ?>&<?= $ckeditorParams ?>" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Visualizar">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <?php if ($isImage): ?>
                                <!-- <a href="edit-file.php?id=<?= $file['file_id']; ?>&<?= $ckeditorParams ?>" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Editar">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a> -->
                            <?php endif; ?>
                            <form action="delete-file.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este arquivo?')">
                                <input type="hidden" name="id" value="<?= $file['file_id']; ?>">
                                <input type="hidden" name="ckedit" value="<?= $ckeditorParams; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Excluir">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>

                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Paginação -->
            <div class="pagination-container">
                <nav>
                    <ul class="pagination">
                        <li class="page-item <?= $currentPage == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=1&<?= $ckeditorParams ?>">Primeiro</a>
                        </li>
                        <li class="page-item <?= $currentPage == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $currentPage - 1; ?>&<?= $ckeditorParams ?>">Anterior</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?= $i; ?>&<?= $ckeditorParams ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $currentPage + 1; ?>&<?= $ckeditorParams ?>">Próximo</a>
                        </li>
                        <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $totalPages; ?>&<?= $ckeditorParams ?>">Último</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="row">
                <div class="col-12">
                    <?= "Tipo recebido: " . htmlspecialchars($type); ?>
                </div>
            </div>


        <?php endif; ?>
    </main>

    <script>
        function selectImage(imagePath) {
            // Salva o caminho da imagem no localStorage
            localStorage.setItem('selectedImage', imagePath);
            // Fecha o popup
            window.close();
        }
    </script>



</body>

</html>