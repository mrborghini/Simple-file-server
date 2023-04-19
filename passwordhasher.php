<?php

$unhashed = readline("String to encrypt: "); // String to encrypt

$hashed = password_hash($unhashed, PASSWORD_DEFAULT); // Encrypt the passsword

echo $hashed; // Print out the encrypted password

?>