<?php
session_start();

// Captura os parâmetros do CKEditor
$ckeditorParams = http_build_query([
    'CKEditor' => $_GET['CKEditor'] ?? '',
    'CKEditorFuncNum' => $_GET['CKEditorFuncNum'] ?? '',
    'langCode' => $_GET['langCode'] ?? '',
]);

include_once "./config.php";

// Captura e sanitiza a variável GET
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= SYSTEM_TITLE; ?></title>

    <?php include_once "./dependences.php"; ?>

    <!-- Adicionando um pouco de CSS para transição suave -->
    <style>
        .alert {
            transition: margin-bottom 0.5s ease;
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

                <!-- Exibe a mensagem de sucesso ou erro se houver -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div id="success-alert" class="alert alert-warning alert-dismissible fade show" role="alert">
                        <span class="text-start">
                            <strong><?= $_SESSION['message']; ?></strong>
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['message']); // Limpa a mensagem após exibição 
                    ?>
                <?php endif; ?>
                <script>
                    // JavaScript para fazer o alerta desaparecer após 5 segundos e liberar o espaço
                    document.addEventListener("DOMContentLoaded", function() {
                        const alertElement = document.getElementById("success-alert");
                        if (alertElement) {
                            setTimeout(() => {
                                alertElement.classList.remove("show"); // Remove a exibição
                                alertElement.classList.add("fade"); // Aplica a animação de saída

                                // Remove o elemento do DOM após a animação (500ms é o tempo padrão no Bootstrap)
                                setTimeout(() => {
                                    alertElement.remove();
                                }, 500); // Aguarda a animação terminar antes de remover
                            }, 5000); // 5000 ms = 5 segundos
                        }
                    });
                </script>

                <!-- Formulário de Criação de Pasta -->
                <?php if ($type === 'input') { ?>
                    <form action="create-dir-exec.php?type=input" method="POST">
                <?php } else { ?>
                    <form action="create-dir-exec.php?<?php echo $ckeditorParams; ?>" method="POST">
                <?php } ?>
                    <div class="mb-3">
                        <label for="folderName" class="form-label">Nome da nova pasta</label>
                        <input type="text" class="form-control" id="folderName" name="folderName" required>
                    </div>

                    <div class="mb-3">
                        <label for="folderType" class="form-label">Tipo de pasta</label>
                        <select class="form-select" id="folderType" name="folderType" required>
                            <option value="img">Imagem</option>
                            <option value="pdf">Documentos</option>
                        </select>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">Criar Pasta</button>

                            <?php if ($type === 'input') { ?>
                                <a href="./thumbnail-input.php?type=input" target="_self" type="button" class="btn btn-secondary">Cancelar</a>
                            <?php } else { ?>
                                <a href="./index.php?<?php echo $ckeditorParams; ?>" target="_self" class="btn btn-secondary">Cancelar</a>
                            <?php } ?>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Script para garantir que a animação de fade seja aplicada ao fechar o alerta
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.addEventListener('closed.bs.alert', function() {
                alert.style.opacity = 0; // Apaga o alerta suavemente
            });
        }
    </script>
</body>

</html>