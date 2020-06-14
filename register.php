<?php
include 'includes/header.php';
if ($auth) header('location: /');
?>

<div class="text-success text-center success-message"></div>
<form action="handlers/register.php" method="post" class="offset-md-4 col-md-4 p-4" id="register-form">
    <div class="form-group">
        <label for="email">Введите email</label>
        <div class="email-notice invalid-feedback"></div>
        <input name="email" type="email" class="form-control" id="email">
    </div>
    <div class="form-group">
        <label for="password">Введите пароль</label>
        <div class="password-notice invalid-feedback"></div>
        <input name="password" type="password" class="form-control" id="password">
    </div>
    <div class="form-group">
        <label for="password_confirm">Подтвердите пароль</label>
        <div class="password-confirm-notice invalid-feedback"></div>
        <input name="password_confirm" type="password" class="form-control" id="password_confirm">
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>
</form>

<?php include 'includes/footer.php'; ?>
