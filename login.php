<?php

require_once('database/dbconnect.php');
require_once('components/functions.php');

checkNotLogin();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="images/SimpleFileServer.png">
    <link rel="stylesheet" href="style/style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <form method="post" class="loginlayout">
        <img onclick="LoginFirst()" class="loginicon" src="/images/SimpleFileServer.png" alt="Login first">
        <p></p>
        <label for="email">Email</label>
        <input class="credentials" type="email" name="email" id="email">
        <p></p>
        <label for="password">Password</label>
        <input class="credentials" type="password" name="password" id="password">
        <p></p>
        <button class="credentials" type="submit">Confirm login</button>
        <?php

        if (isset($_POST['email']) && $_POST['password']) {
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'email' => $_POST['email']
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && password_verify($_POST['password'], $result['password'])) {
                $_SESSION['userid'] = $result['userid'];
                $_SESSION['email'] = $result['email'];
                header('location: ./');
                exit();
            } else {
                echo "Incorrect login";
            }
        }

        ?>
    </form>
    <script src="/script/script.js"></script>
</body>

</html>