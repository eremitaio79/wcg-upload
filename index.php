<?php
session_start();
include_once "./config.php";

// Captura os parâmetros do CKEditor
$ckeditorParams = http_build_query([
    'CKEditor' => $_GET['CKEditor'] ?? '',
    'CKEditorFuncNum' => $_GET['CKEditorFuncNum'] ?? '',
    'langCode' => $_GET['langCode'] ?? '',
]);


// Redirecionamento antes de qualquer saída
if (DEFAULT_PAGE_VIEW == 1) {
    header("Location: thumbnail.php?$ckeditorParams");
    exit();
} elseif (DEFAULT_PAGE_VIEW == 2) {
    header("Location: list.php?$ckeditorParams");
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

</head>

<body>

</body>

</html>