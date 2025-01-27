<!-- <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top"> -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
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

            <div class="btn-group me-3">
                <a href="./thumbnail.php" target="_self" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Visualização em Miniaturas"><i class="fa-solid fa-image"></i></a>
                <a href="./index.php" target="_self" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Visualização em Lista"><i class="fa-solid fa-list"></i></a>
            </div>

            <div class="btn-group">
                <a href="./upload.php" target="_self" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Fazer Novo Upload">
                    <i class="fa-solid fa-image"></i>&nbsp;Upload
                </a>
                <a href="./create-dir.php" target="_self" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Criar Nova Pasta"><i class="fa-solid fa-folder-plus"></i></a>
                <a href="./manage-dir.php" target="_self" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Gerenciador de Pastas"><i class="fa-solid fa-folder-tree"></i></a>
            </div>
        </div>
    </div>
</nav>

<style>
    body {
        padding-top: 30px;
        /* Ajuste conforme a altura da navbar */
    }
</style>