<?php
    include 'includes/header.php';
    require 'handlers/allUsers.php'
?>
<main>
    <div class="album py-5 bg-light">
        <div class="container">
            <div class="row">
                <?php foreach ($users as $user) { ?>
                    <div class="col-md-4">
                        <div class="mb-4 shadow-sm text-center">
                            <img src="<?php echo $user->avatar ?>" alt="<?php echo $user->name ?> <?php echo $user->surname ?>">
                            <div class="card-body">
                                <h3 class="text-muted"><?php echo $user->name ?> <?php echo $user->surname ?></h3>
                                <small class="text-mutedbotobor.php"><?php echo $user->email ?></small>
                                <p class="card-text"><?php echo $user->about ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php';
