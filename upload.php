<?php
include_once "./config.php";
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

                <!-- Upload INI -->

                <!-- Componente de upload de múltiplos arquivos -->
                <div class="mt-3">
                    <label for="fileUpload" class="form-label">Selecione os arquivos para upload</label>
                    <input class="form-control" type="file" id="fileUpload" name="files[]" multiple>
                </div>

                <!-- Upload END -->

            </div>
        </div>
    </main>


</body>

</html>