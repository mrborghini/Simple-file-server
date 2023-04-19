<?php

require_once('database/dbconnect.php');
require_once('components/functions.php');

checkLogin();

if (isset($_POST['deletefile'])) {
    trashfile($pdo);
}

if (isset($_FILES["fileupload"]) && basename($_FILES["fileupload"]["name"][0]) !== '') {
    Uploadfiles($pdo);
}

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
    <title>Your files</title>
</head>

<body>
    
        <?php
        
        require_once('components/navbar.php');
        
        ?>

    

    <form method="post">
        <div class="files">
            <?php
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