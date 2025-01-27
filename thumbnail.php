<?php
session_start();
include_once "./config.php";

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
            /* Altura fixa para os cards */
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            /* Garante que nada ultrapasse os limites do card */
        }

        .card img {
            width: 100%;
            height: auto;
            object-fit: cover;
            /* Faz a imagem cobrir proporcionalmente o espaço */
            flex-shrink: 0;
            /* Evita que a imagem seja reduzida */
            max-height: calc(100% - 50px);
            /* Limita a altura da imagem para não invadir o footer */
        }

        .card-footer {
            height: 50px;
            /* Altura fixa para o footer */
            background-color: #fff;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }


        .card i {
            font-size: 48px;
            color: #888;
            flex: 1;
            display: flex;
            justify-content: center;
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
        <?php include_once "./layout/navbar.php"; ?>
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
                <?php foreach ($files as $file): ?>
                    <?php
                    $isImage = preg_match('/\.(jpg|jpeg|png|gif|bmp|webp)$/i', $file['filename']);
                    ?>
                    <div class="card">
                        <!-- Conteúdo do Card -->
                        <?php if ($isImage): ?>
                            <img src="<?= htmlspecialchars($file['path']); ?>" class="card-img-top img-fluid" style="object-fit: cover; height: 200px;" alt="<?= htmlspecialchars($file['filename']); ?>" />
                        <?php else: ?>
                            <i class="fas fa-file-alt"></i>
                        <?php endif; ?>

                        <!-- Rodapé do Card com Botões -->
                        <div class="card-footer bg-dark">
                            <a href="view-file.php?id=<?= $file['file_id']; ?>" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Visualizar">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="edit-file.php?id=<?= $file['file_id']; ?>" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Editar">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="delete-file.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir este arquivo?')">
                                <input type="hidden" name="id" value="<?= $file['file_id']; ?>">
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
                        <li class="page-item"><a class="page-link" href="#">Anterior</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Próximo</a></li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>