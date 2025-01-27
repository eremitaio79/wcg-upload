<?php
session_start();
include_once "./config.php";

// Função para buscar as pastas e a contagem de arquivos do banco de dados
function getFoldersWithFileCount($conn)
{
    $query = "
        SELECT 
            d.*, 
            (SELECT COUNT(*) FROM wcg_upload_files f WHERE f.id_dir = d.id) AS file_count
        FROM wcg_upload_dir d
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$folders = getFoldersWithFileCount($conn);
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
        .badge {
            font-size: 0.8em;
        }

        .btn-with-badge {
            position: relative;
            padding-right: 30px;
            /* Espaço extra para o badge */
        }

        .btn-with-badge .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            transform: translate(50%, -50%);
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

                <h3>Gerenciamento de Pastas</h3>

                <!-- Exibe alerta se não houver pastas -->
                <?php if (empty($folders)): ?>
                    <div class="alert alert-info" role="alert">
                        Nenhuma pasta disponível.
                    </div>
                <?php else: ?>
                    <!-- Tabela de pastas -->
                    <table id="foldersTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nome da Pasta</th>
                                <th>Caminho</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th class="text-center">Ações</th> <!-- Centralizando o cabeçalho da coluna Ações -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($folders as $folder): ?>
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-folder"></i>&nbsp;
                                        <?= htmlspecialchars($folder['dir_name']); ?>
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-folder-tree"></i>&nbsp;
                                        <?= htmlspecialchars($folder['path']); ?>
                                    </td>
                                    <td><?= htmlspecialchars($folder['dir_type'] ?? 'Não definido'); ?></td>
                                    <td>
                                        <?= $folder['status'] == 1 ? 'Disponível' : ($folder['status'] == 0 ? 'Indisponível' : 'Não definido'); ?>
                                    </td>

                                    <td class="text-center"> <!-- Centralizando as ações -->
                                        <a href="view-folder.php?id=<?= $folder['id']; ?>" class="btn btn-primary btn-sm position-relative" data-bs-toggle="tooltip" data-bs-title="Visualizar Arquivos na Pasta">
                                            <i class="fa-regular fa-folder-open"></i>
                                            <?php if ($folder['file_count'] > 0): ?>
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                                    <?= $folder['file_count']; ?>
                                                    <span class="visually-hidden">arquivos não lidos</span>
                                                </span>
                                            <?php endif; ?>
                                        </a>
                                        <a href="edit-folder.php?id=<?= $folder['id']; ?>" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-title="Editar Pasta"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form action="delete-folder.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta pasta?\nTodos os arquivos contidos nesta pasta serão excluídos em cascata.\nEssa ação não pode ser desfeita.')">
                                            <input type="hidden" name="id" value="<?= $folder['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-title="Excluir Esta Pasta"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-12 text-end">
                            <hr />
                            <a href="./index.php" target="_self" type="button" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        $(document).ready(() => {
            // Inicializando o DataTable com a configuração padrão
            $('#foldersTable').dataTable();
        });

        var table = new DataTable('#foldersTable', {
            order: [
                [0, 'desc'] // Ordem de exibição da tabela (coluna 0 em ordem decrescente)
            ],
            language: {
                // Definindo o idioma para português (carregando o arquivo pt-BR.json)
                url: './js/datatables2.2.1/pt-BR.json',
            },
            pageLength: 25, // Exibe 25 linhas por padrão
        });
    </script>
</body>

</html>