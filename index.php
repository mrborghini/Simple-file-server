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
    <link rel="stylesheet" href="style/style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your files</title>
</head>

<body>
    <nav>
        <form method="post" enctype="multipart/form-data">
            <input type="file" id="uploadfile" class="uploadfile" name="fileupload[]" multiple>
            <label for="uploadfile">
                Upload files
            </label>
            <input type="text" name="search" id="search" placeholder="Search">
            <button type="submit">Confirm search or upload</button>
        </form>

        <?php

        if (isset($_FILES["fileupload"]) && basename($_FILES["fileupload"]["name"][0]) !== '') {
            Uploadfiles($pdo);
        }
        echo (StorageLeft());
        ?>

        <a class="navigation" href="logout.php">Logout</a>
        <a class="navigation" href="trash.php">Trash</a>
    </nav>
    <form method="post">
        <div class="files">
            <?php
            SortData(GetData($pdo, 0));
            ?>
        </div>
    </form>

</body>

</html>