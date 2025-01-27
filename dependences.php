<!-- JQuery 3.7.1 -->
<script src="./js/jquery3.7.1/jquery-3.7.1.js"></script>

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Bootstrap 5.3.0 -->
<script src="./js/bootstrap5.3.0/bootstrap.bundle.min.js"></script>
<link href="./css/bootstrap5.3.0/bootstrap.min.css" rel="stylesheet">
<script src="./js/bootstrap5.3.0/popper.min.js"></script>

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Datatables 2.2.1 -->
<script src="./js/datatables2.2.1/dataTables.js"></script>
<script src="./js/datatables2.2.1/dataTables.bootstrap5.js"></script>
<link href="./css/datatables2.2.1/dataTables.bootstrap5.css" rel="stylesheet">

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Fontawesome 6.7.2 -->
<link href="./css/fontawesome6.7.2/all.css" rel="stylesheet">
<script src="./js/fontawesome6.7.2/all.js"></script>

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Script para inicialização dos tooltips do bootstrap -->
<script>
    // Inicializa os tooltips do Bootstrap
    document.addEventListener('DOMContentLoaded', () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl); // Referência explícita ao objeto bootstrap
        });
    });
    /* Bootstrap framework is imported here END. */
</script>

<!-- --------------------------------------------------------------------------------------------------------------- -->

<!-- Toast UI Image Editor -->

<script src="./js/tui-codes/tui-code-snippet.js"></script>
<script src="./js/tui-codes/tui-color-picker.js"></script>
<script src="./js/tui-codes/tui-image-editor.js"></script>
<link href="./css/tui-image-editor/tui-image-editor.css" rel="stylesheet">