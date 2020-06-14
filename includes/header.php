<?php
 session_start();
 $auth = isset($_SESSION['email']) ? true : false;
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <title>Тестовое задание</title>
</head>
<body>
<header>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <h5 class="my-0 mr-md-auto font-weight-normal">
            <a class="logotype_link" href="/">Каталог пользователей</a>
        </h5>
        <?php
        if ($auth) { ?>
            <a class="btn btn-outline-primary m-1" href="../handlers/logout.php">Выйти</a>
            <a class="btn btn-outline-primary" href="../personal.php">Изменить данные</a>
        <?php } else { ?>
            <a class="btn btn-outline-primary m-1" href="auth.php">Войти</a>
            <a class="btn btn-outline-primary" href="register.php">Регистрация</a>
        <?php } ?>
    </div>
</header>
