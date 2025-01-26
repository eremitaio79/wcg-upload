<!-- Bootstrap 5.3.0 -->
<link href="./css/bootstrap5.3.0/bootstrap.min.css" rel="stylesheet">
<script src="./js/bootstrap5.3.0/bootstrap.bundle.min.js"></script>
<script src="./js/bootstrap5.3.0/popper.min.js"></script>

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