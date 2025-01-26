<?php
session_start();
include_once "./config.php";

// Função para buscar as pastas do banco de dados
function getFolders($conn)
{
    $query = "SELECT * FROM wcg_upload_dir";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$folders = getFolders($conn);
// var_dump($folders);
// die();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SYSTEM_TITLE; ?></title>

    <?php include_once "./dependences.php"; ?>

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
                                        <a href="view-folder.php?id=<?= $folder['id']; ?>" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-title="Visualizar Arquivos na Pasta"><i class="fa-solid fa-eye"></i></a>
                                        <a href="edit-folder.php?id=<?= $folder['id']; ?>" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" data-bs-title="Editar Pasta"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <form action="delete-folder.php" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta pasta?')">
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

    <!-- <script>
        $(document).ready(function() {
            // Inicializando o DataTable para a tabela com id 'foldersTable'
            $('#foldersTable').DataTable();
        });
    </script> -->

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