<?php

require_once('database/dbconnect.php');
checkLogin();

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
        <form method="post">
            <input type="file" id="uploadfile" class="uploadfile" name="fileupload" multiple>
            <label for="uploadfile">
                Upload files
            </label>
        </form>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="files">
        <?php
        $query;
        ?>
    </div>
</body>

</html>