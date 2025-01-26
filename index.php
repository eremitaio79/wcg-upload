<?php
session_start();

// Configurações do sistema.
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

                <!-- Exibe a mensagem de sucesso ou erro se houver -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
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


                <!-- Conteúdo da página -->
                <h2>Bem-vindo ao Gerenciador de Pastas</h2>
                <!-- Outras informações ou conteúdos da página -->

            </div>
        </div>
    </main>


</body>

</html>