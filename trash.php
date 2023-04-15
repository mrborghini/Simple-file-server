<?php

require_once('database/dbconnect.php');
require_once('components/functions.php');

checkLogin();

if (isset($_POST['deletefile'])) {
    $querygetfile = "SELECT * FROM uploads WHERE trash = :trash AND userid = :userid AND fileid = :fileid";
    $stmtgetfile = $pdo->prepare($querygetfile);
    $stmtgetfile->execute([
        'trash' => 1,
        'userid' => $_SESSION['userid'],
        'fileid' => $_POST['deletefile']
    ]);
    $filesindatabasegetfile = $stmtgetfile->fetch(PDO::FETCH_ASSOC);

    $trashitemquery = "DELETE FROM uploads WHERE trash = :trash AND userid = :userid AND fileid = :fileid";
    $stmttrash = $pdo->prepare($trashitemquery);
    $stmttrash->execute([
        'trash' => 1,
        'userid' => $_SESSION['userid'],
        'fileid' => $_POST['deletefile']
    ]);
    unlink('.' . $filesindatabasegetfile['filesrc']);
    header('location: trash.php');
    exit();
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

        <a href="logout.php">Logout</a>
    </nav>
    <form method="post">
        <div class="files">
            <?php

            $query = "SELECT * FROM uploads WHERE userid = :userid AND trash = :trash";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'userid' => $_SESSION['userid'],
                'trash' => 1
            ]);
            $filesindatabase = $stmt->fetchAll(PDO::FETCH_ASSOC);


            for ($i = 0; $i < count($filesindatabase); $i++) {
                $filename = $filesindatabase[$i]['filename'];
                $filesrc = $filesindatabase[$i]['filelocation'];
                $fileid = $filesindatabase[$i]['fileid'];
                $trash =  $filesindatabase[$i]['trash'];

                $fileseperation = explode('.', $filename);
                $extension = end($fileseperation);
                switch ($extension) {
                    case in_array($extension, ImageExtensions):
                        ShowImg($filesrc, $filename, $fileid, $trash);
                        break;
                    case in_array($extension, VideoExtensions):
                        ShowVideo($filesrc, $filename, $fileid, $trash);
                        break;
                    case in_array($extension, Windows):
                        WinExecutables($filesrc, $filename, $fileid, $trash);
                        break;
                    default:
                        break;
                }
            }

            echo (StorageLeft());

            ?>
        </div>
    </form>

</body>

</html>