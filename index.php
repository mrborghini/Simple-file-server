<?php

require_once('database/dbconnect.php'); // Import dbconnect.php
require_once('components/functions.php'); // Import functions.php

checkLogin(); // Check if user is logged in with the function

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/fonts/Minecraft.ttf">
    <script src="/script/script.js" defer></script>
    <link rel="icon" href="images/SimpleFileServer.png">
    <link rel="stylesheet" href="style/style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your files</title>
</head>

<body>

    <?php

    require_once('components/navbar.php'); // Import navbar

    ?>



    <form method="post">
        <div class="files">
            <?php

            if (isset($_FILES["fileupload"]) && basename($_FILES["fileupload"]["name"][0]) !== '') {
                Uploadfiles($pdo);
            }

            if (isset($_POST['deletefile'])) {
                trashfile($pdo);
            }

            if (isset($_POST['search'])) {
                $_SESSION['search'] = $_POST['search'];
                header('location: ./');
                exit();
            }
            if (!isset($_SESSION['search'])) {
                SortData(GetData($pdo, 0));
            } else {
                SortData(SearchData($pdo, 0, $_SESSION['search']));
            }
            ?>
        </div>
    </form>

</body>

</html>