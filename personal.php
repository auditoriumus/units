<?php
include 'includes/header.php';
if (!$auth) header('location: /');
require 'app/Init.php';
$init = new Init();
$params = $init->getParameters($_SESSION['email']);
?>


<form action="handlers/update.php" method="post" class="offset-md-4 col-md-4 p-4" id="update-form" enctype="multipart/form-data">
    <div class="form-group">Пользователь: <?php echo strtolower($_SESSION['email']) ?></div>
    <div class="text-success text-center success-message"></div>
    <div class="changes-notice invalid-feedback"></div>
    <div class="form-group">
        <label for="name">Ваше имя</label>
        <div class="name-notice invalid-feedback"></div>
        <input name="name" type="text" class="form-control" id="name" value="<?php echo $params['name'] ?>">
    </div>
    <div class="form-group">
        <label for="surname">Ваша фамилия</label>
        <div class="surname-notice invalid-feedback"></div>
        <input name="surname" type="text" class="form-control" id="surname" value="<?php echo $params['surname'] ?>">
    </div>
    <div class="form-group">
        <label for="about">О себе</label>
        <div class="about-notice invalid-feedback"></div>
        <textarea class="form-control" name="about" id="about"><?php echo $params['about'] ?></textarea>
    </div>
    <div class="form-group">
        <label for="avatar">Аватарка</label>
        <div class="avatar-notice invalid-feedback"></div>
        <input type="file" class="form-control" name="avatar" id="avatar">
    </div>
    <div class="form-group">
        <label for="password">Введите пароль</label>
        <div class="password-notice invalid-feedback"></div>
        <input name="password" type="password" class="form-control" id="password">
    </div>
    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>

<?php include 'includes/footer.php'; ?>
