<?php
require '../app/Init.php';

$obj = new Init();
echo json_encode($obj->newUserValidate());

