<?php

$path = realpath('./../'); ;
ini_set('include_path', $path . ':' . ini_get('include_path'));

require_once 'Crypt/MicroID.php';

?>
