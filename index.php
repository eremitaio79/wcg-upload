<?php
session_start();
include_once "./config.php";

// Redirecionamento antes de qualquer saída
if (DEFAULT_PAGE_VIEW == 1) {
    header("Location: thumbnail.php");
    exit();
} elseif (DEFAULT_PAGE_VIEW == 2) {
    header("Location: list.php");
    exit();
}
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

            </div>
        </div>
    </main>

    <!-- Modal para preview -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Preview do Arquivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewImage" src="" alt="Preview" class="img-fluid" style="max-height: 500px;">
                </div>
            </div>
        </div>
    </div>

</body>

</html>