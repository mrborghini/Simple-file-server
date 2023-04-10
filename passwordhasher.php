<?php

$unhashed = readline("String to encrypt: ");

$hashed = password_hash($unhashed, PASSWORD_DEFAULT);

echo $hashed;

?>