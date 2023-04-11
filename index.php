<?php

require_once('database/dbconnect.php');
require_once('components/functions.php');

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
        <form method="post" enctype="multipart/form-data">
            <input type="file" id="uploadfile" class="uploadfile" name="fileupload" multiple>
            <label for="uploadfile">
                Upload files
            </label>
            <button type="submit">Confirm Upload</button>
        </form>

        <?php
        if (isset($_FILES['fileupload'])) {
            $targetdir = "uploads/";
            $fullpath = "/";
            $target_file = basename($_FILES["fileupload"]["name"]);
            $target_filelocation = $targetdir . RandomCharacters(128) . $target_file;
            move_uploaded_file($_FILES['fileupload']['tmp_name'], $target_filelocation);
            $uploadquery = "INSERT INTO uploads (filename, filelocation, userid) VALUES (:filename, :filelocation, :userid)";
            $uploadstmt = $pdo->prepare($uploadquery);
            $uploadstmt->execute([
                'filename' => $target_file,
                'filelocation' => $fullpath . $target_filelocation,
                'userid' => $_SESSION['userid']
            ]);
            header("location: ./");
            exit();
        }
        ?>

        <a href="logout.php">Logout</a>
    </nav>
    <div class="files">
        <?php

        $query = "SELECT * FROM uploads WHERE userid = :userid";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'userid' => $_SESSION['userid']
        ]);
        $filesindatabase = $stmt->fetchAll(PDO::FETCH_ASSOC);

        
        for ($i = 0; $i < count($filesindatabase); $i++) {
            $extension = explode('.', $filesindatabase[$i]['filename']);
            switch ($extension[1]) {
                case 'gif':
                    ShowImg($filesindatabase[$i]['filelocation'], $filesindatabase[$i]['filename']);
                    break;
                case 'png':
                    ShowImg($filesindatabase[$i]['filelocation'], $filesindatabase[$i]['filename']);
                    break;
                case 'jpg':
                    ShowImg($filesindatabase[$i]['filelocation'], $filesindatabase[$i]['filename']);
                    break;
                case 'jpeg':
                    ShowImg($filesindatabase[$i]['filelocation'], $filesindatabase[$i]['filename']);
                    break;
                case 'svg':
                    ShowImg($filesindatabase[$i]['filelocation'], $filesindatabase[$i]['filename']);
                    break;
                default:
                    break;
            }
        }
        echo (StorageLeft());
        ?>
    </div>

</body>

</html>