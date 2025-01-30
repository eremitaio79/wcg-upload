<?php

/** 
 * ARQUIVO DE CONFIGURAÇÕES E PARAMETRIZAÇÕES DO SISTEMA DE UPLOAD
 * Author: Paulo Eremita
 * Created at: 25/01/2025
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

// Nível dos diretórios para o arquivo de dependências (dependence.php).
$dir_level = "./code-projects/wcg-upload/";

// URL padrão do sistema.
define('URL_SISTEMA', 'http://localhost/codes-project/wcg-upload/');
define('URL_SISTEMA2', 'http://localhost/codes-project/');


$baseDir = './wcg-upload/';

// Caminho base para os uploads.
$basePath = './files/';

/** ----------------------------------------------------------------------------------------------------------------- */

// Parametrizações do sistema.

define('NAVBAR_TITLE', 'Upload Tool');
define('SYSTEM_TITLE', 'Upload Tool Para Sites e Sistemas');

/** ----------------------------------------------------------------------------------------------------------------- */

/**
 * Visualização padrão quando o sistema é carregado.
 * 1 = Thumbnail
 * 2 = List
 */
define('DEFAULT_PAGE_VIEW', 1);
// define('DEFAULT_PAGE_VIEW', 2);
