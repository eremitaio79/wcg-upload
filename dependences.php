<?php
$dir_level = "./wcg-upload/";
?>

<!-- JQuery 3.7.1 -->
<script src="<?= $dir_level . 'js/jquery3.7.1/jquery-3.7.1.js' ?>"></script>

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Bootstrap 5.3.0 -->
<script src="<?= $dir_level . 'js/bootstrap5.3.0/bootstrap.bundle.min.js' ?>"></script>
<link href="<?= $dir_level . 'css/bootstrap5.3.0/bootstrap.min.css' ?>" rel="stylesheet">
<script src="<?= $dir_level . 'js/bootstrap5.3.0/popper.min.js' ?>"></script>

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Datatables 2.2.1 -->
<script src="<?= $dir_level . 'js/datatables2.2.1/dataTables.js' ?>"></script>
<script src="<?= $dir_level . 'js/datatables2.2.1/dataTables.bootstrap5.js' ?>"></script>
<link href="<?= $dir_level . 'css/datatables2.2.1/dataTables.bootstrap5.css' ?>" rel="stylesheet">

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Fontawesome 6.7.2 -->
<link href="<?= $dir_level . 'css/fontawesome6.7.2/all.css' ?>" rel="stylesheet">
<script src="<?= $dir_level . 'js/fontawesome6.7.2/all.js' ?>"></script>

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Inicialização do CKEditor -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (document.querySelector("#editor")) {
            CKEDITOR.replace("editor");
        }
    });
</script>

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Script para inicialização dos tooltips do Bootstrap -->
<script>
    // Inicializa os tooltips do Bootstrap
    document.addEventListener('DOMContentLoaded', () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Toast UI Image Editor -->
<script src="<?= $dir_level . 'js/tui-codes/tui-code-snippet.js' ?>"></script>
<script src="<?= $dir_level . 'js/tui-codes/tui-color-picker.js' ?>"></script>
<script src="<?= $dir_level . 'js/tui-codes/tui-image-editor.js' ?>"></script>
<link href="<?= $dir_level . 'css/tui-image-editor/tui-image-editor.css' ?>" rel="stylesheet">
