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

</head>

<body>

</body>

</html>