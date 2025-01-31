<?php
// Captura os parâmetros do CKEditor
$ckeditorParams = http_build_query([
    'CKEditor' => $_GET['CKEditor'] ?? '',
    'CKEditorFuncNum' => $_GET['CKEditorFuncNum'] ?? '',
    'langCode' => $_GET['langCode'] ?? '',
]);

// Captura e sanitiza a variável GET
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';
?>

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
                    <a class="nav-link active" aria-current="page" href="./index.php?<?= $ckeditorParams ?>" target="_self">Home</a>
                </li>
            </ul>

            <div class="btn-group me-3">
                <?php
                // Condição para exibir o link correto
                if ($type === 'input') {
                    echo "<a href='./thumbnail-input.php?type=input' target='_self' class='btn btn-secondary' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-title='Visualização em Miniaturas'><i class='fa-solid fa-image'></i></a>";
                } else {
                    echo "<a href='./thumbnail.php?<?= $ckeditorParams ?>' target='_self' class='btn btn-secondary' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-title='Visualização em Miniaturas'><i class='fa-solid fa-image'></i></a>";
                }
                ?>

                <?php
                // Condição para exibir o link correto
                if ($type === 'input') {
                } else {
                    echo "<a href='./list.php?<?= $ckeditorParams ?>' target='_self' class='btn btn-secondary' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-title='Visualização em Lista'><i class='fa-solid fa-list'></i></a>";
                }
                ?>
            </div>

            <div class="btn-group">
                <?php
                // Condição para exibir o link correto
                if ($type === 'input') {
                    echo "<a href='./upload.php?type=input' target='_self' class='btn btn-primary' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-title='Fazer Novo Upload'>
                    <i class='fa-solid fa-image'></i>&nbsp;Upload</a>";
                } else {
                    echo "<a href='./thumbnail.php?<?= $ckeditorParams ?>' target='_self' class='btn btn-primary' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-title='Fazer Novo Upload'>
                    <i class='fa-solid fa-image'></i>&nbsp;Upload</a>";
                }
                ?>

                <?php
                // Condição para exibir o link correto
                if ($type === 'input') {
                    echo "<a href='./create-dir.php?type=input' target='_self' class='btn btn-success' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-title='Criar Nova Pasta'><i class='fa-solid fa-folder-plus'></i></a>";
                } else {
                    echo "<a href='./create-dir.php?<?= $ckeditorParams ?>' target='_self' class='btn btn-success' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-title='Criar Nova Pasta'><i class='fa-solid fa-folder-plus'></i></a>";
                }
                ?>

<?php
                // Condição para exibir o link correto
                if ($type === 'input') {
                    echo "<a href='./manage-dir.php?type=input' target='_self' class='btn btn-secondary' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-title='Gerenciador de Pastas'><i class='fa-solid fa-folder-tree'></i></a>";
                } else {
                    echo "<a href='./manage-dir.php?<?= $ckeditorParams ?>' target='_self' class='btn btn-secondary' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-title='Gerenciador de Pastas'><i class='fa-solid fa-folder-tree'></i></a>";
                }
                ?>
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