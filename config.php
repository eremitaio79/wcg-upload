<?php

include_once "./dependences.php";
/** 
 * Arquivo de configurações do sistema de uploads.
 * Author: Paulo Eremita
 * Created at 25/01/2025
 */

/** ----------------------------------------------------------------------------------------------------------------- */

// Arquivo de configuração de conexão com o banco de dados

// Variáveis de conexão
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'upload_tool');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '3306');

// Conexão PDO
try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<div class='alert alert-warning text-center' role='alert'>";
    echo "Erro na conexão: {$e->getMessage()}";
    echo "</div>";
    exit();
}

/** ----------------------------------------------------------------------------------------------------------------- */

// Parâmetros do sistema.
define('NAVBAR_TITLE', 'Upload Tool');
