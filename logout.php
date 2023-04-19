<?php

session_start(); // Start session to access them
session_destroy(); // Basically resetting the session

header('location: /'); // Go back to root
exit(); // Exit so the script stops for the user

?>