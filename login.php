<?php

require_once('database/dbconnect.php'); // Import dbconnect.php
require_once('components/functions.php'); // Import functions.php

checkNotLogin(); // Check if user is already logged into their account

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="/script/script.js" defer></script>
    <link rel="icon" href="images/SimpleFileServer.png">
    <link rel="stylesheet" href="style/style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <form method="post" class="loginlayout">
        <img onclick="LoginFirst()" class="loginicon" src="/images/SimpleFileServer.png" alt="Login first"> <!-- Call loginfirst function (Javascript) -->
        <p></p>
        <label for="email">Email</label>
        <input class="credentials" type="email" name="email" id="email">
        <p></p>
        <label for="password">Password</label>
        <input class="credentials" type="password" name="password" id="password">
        <p></p>
        <button class="credentials" type="submit">Confirm login</button>
        <?php

        if (isset($_POST['email']) && $_POST['password']) { // Check if the input fields are set to login
            $query = "SELECT * FROM users WHERE email = :email"; // Check if email exists in database
            $stmt = $pdo->prepare($query); // Using prepared statements to prevent SQL injection attacks
            $stmt->execute([ // execute the prepared statements
                'email' => $_POST['email'] // Email from input field gets put in
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC); // The user's account gets put into this variable

            if ($result && password_verify($_POST['password'], $result['password'])) { // If email exists and password input matches the encrypted password on the database
                $_SESSION['userid'] = $result['userid']; // Put userid into session
                $_SESSION['email'] = $result['email']; // put email into session
                header('location: ./'); // redirect to root
                exit(); // exit so the script stops for the user
            } else { // If it does not match
                echo "Incorrect login"; // Echo the message
            }
        }

        ?>
    </form>
</body>

</html>