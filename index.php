<?php

require_once('database/dbconnect.php');
require_once('components/functions.php');

checkLogin();

if (isset($_POST['deletefile'])) {
    trashfile($pdo);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="images/SimpleFileServer.png">
    <link rel="stylesheet" href="style/style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your files</title>
</head>

<body>
    <form method="post" enctype="multipart/form-data">
        <nav class="navbar">
            <ul class="navcontent">
                <li>
                    <input type="file" onchange="submit()" id="uploadfile" class="uploadfile" name="fileupload[]" multiple>
                    <label class="uploadfilelabel" for="uploadfile">
                        <img class="blackicons" src="/images/upload.svg" alt="Upload files">
                    </label>
                </li>
                <li>
                    <input type="text" name="search" id="search" placeholder="Search">
                </li>

                <?php

                if (isset($_FILES["fileupload"]) && basename($_FILES["fileupload"]["name"][0]) !== '') {
                    Uploadfiles($pdo);
                }
                ?>

                <li>
                    <a class="navigation" href="logout.php">Logout</a>
                </li>
                <li>
                    <a class="navigation" href="trash.php">Trash</a>
                </li>
                <li>
                    <?php echo StorageLeft(); ?>
                </li>
            </ul>
        </nav>
    </form>

    <form method="post">
        <div class="files">
            <?php
            if (!isset($_POST['search'])) {
                SortData(GetData($pdo, 0));
            } else {
                SortData(SearchData($pdo, 0, $_POST['search']));
            }
            ?>
        </div>
    </form>

</body>

</html>