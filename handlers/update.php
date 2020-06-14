<?php
require '../app/Init.php';
session_start();
$obj = new Init();
print_r(json_encode($obj->update($_SESSION['email'], $_POST)));
