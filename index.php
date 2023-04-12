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
            <input type="file" id="uploadfile" class="uploadfile" name="fileupload[]" multiple>
            <label for="uploadfile">
                Upload files
            </label>
            <input type="text" name="search" id="search" placeholder="Search">
            <button type="submit">Confirm search or upload</button>
        </form>

        <?php
        if (isset($_FILES["fileupload"]) && basename($_FILES["fileupload"]["name"][0]) !== '') {
            $targetdir = "uploads/";
            $fullpath = "/";
            $uploadquery = "INSERT INTO uploads (filename, filelocation, userid, trash) VALUES (:filename, :filelocation, :userid, :trash)";
            $uploadstmt = $pdo->prepare($uploadquery);

            if (is_array($_FILES["fileupload"]["name"])) {

                $countfiles = count($_FILES["fileupload"]["name"]);

                for ($i = 0; $i < $countfiles; $i++) {
                    $target_file = basename($_FILES["fileupload"]["name"][$i]);
                    $target_filelocation = $targetdir . RandomCharacters(128) . $target_file;
                    move_uploaded_file($_FILES['fileupload']['tmp_name'][$i], $target_filelocation);
                    $uploadstmt->execute([
                        'filename' => $target_file,
                        'filelocation' => $fullpath . $target_filelocation,
                        'userid' => $_SESSION['userid'],
                        'trash' => 0
                    ]);
                }
            } else {
                $target_file = basename($_FILES["fileupload"]["name"]);
                $target_filelocation = $targetdir . RandomCharacters(128) . $target_file;
                move_uploaded_file($_FILES['fileupload']['tmp_name'], $target_filelocation);
                $uploadstmt->execute([
                    'filename' => $target_file,
                    'filelocation' => $fullpath . $target_filelocation,
                    'userid' => $_SESSION['userid'],
                    'trash' => 0
                ]);
            }
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
            $filename = $filesindatabase[$i]['filename'];
            $filesrc = $filesindatabase[$i]['filelocation'];

            $extension = explode('.', $filename);
            switch ($extension[1]) {
                case 'gif':
                    ShowImg($filesrc, $filename);
                    break;
                case 'png':
                    ShowImg($filesrc, $filename);
                    break;
                case 'jpg':
                    ShowImg($filesrc, $filename);
                    break;
                case 'jpeg':
                    ShowImg($filesrc, $filename);
                    break;
                case 'svg':
                    ShowImg($filesrc, $filename);
                    break;
                case '':
                default:
                    break;
            }
        }
        echo (StorageLeft());
        ?>
    </div>

</body>

</html>