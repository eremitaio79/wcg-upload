<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <?= NAVBAR_TITLE; ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="./index.php" target="_self">Home</a>
                </li>
            </ul>
            <div class="btn-group">
                <a href="./upload.php" target="_self" class="btn btn-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Fazer Novo Upload">
                    <i class="fa-solid fa-image"></i>&nbsp;Upload
                </a>
                <a href="./create-dir.php" target="_self" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Criar Nova Pasta"><i class="fa-solid fa-folder-plus"></i></a>
                <a href="./manage-dir.php" target="_self" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Gerenciador de Pastas"><i class="fa-solid fa-folder-tree"></i></a>
                <div class="btn-group">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-folder-open"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                        <?php
                        // Buscar as 8 primeiras pastas no banco de dados
                        include_once "./config.php";

                        $queryNavbar = "SELECT * FROM wcg_upload_dir ORDER BY created_at ASC LIMIT 8";
                        $stmtNavbar = $conn->prepare($queryNavbar);
                        $stmtNavbar->execute();
                        $foldersNavbar = $stmtNavbar->fetchAll(PDO::FETCH_ASSOC);

                        // Exibe as pastas no dropdown
                        foreach ($foldersNavbar as $folderNavbar):
                        ?>
                            <li><a class="dropdown-item" href="#"><i class="fa-solid fa-folder"></i>&nbsp;<?= htmlspecialchars($folderNavbar['dir_name']); ?>...</a></li>
                        <?php endforeach; ?>
                        <!-- Item adicional para abrir o gerenciador de pastas -->
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="./manage-dir.php">Gerenciar Pastas</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>