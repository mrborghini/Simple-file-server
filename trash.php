<?php

require_once('database/dbconnect.php');
require_once('components/functions.php');

checkLogin();

if (isset($_POST['deletefile'])) {
    deletefiles($pdo);
}

if (isset($_POST['restore'])) {
    $trashitemquery = "UPDATE uploads SET trash = :trash WHERE userid = :userid AND fileid = :fileid";
    $stmttrash = $pdo->prepare($trashitemquery);
    $stmttrash->execute([
        'trash' => 0,
        'userid' => $_SESSION['userid'],
        'fileid' => $_POST['restore']
    ]);
    header('location: trash.php');
    exit();
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
    <form method="post">
    <nav class="navbar">
        <ul class="navcontent">
            <li>
                <input type="text" name="search" id="search" placeholder="Search">
            </li>
            <li>
                <a class="navigation" href="logout.php">Logout</a>
            </li>
            <li>
                    <a class="navigation" href="/">Home</a>
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
                SortData(GetData($pdo, 1));
            } else {
                SortData(SearchData($pdo, 1, $_POST['search']));
            }
            ?>
        </div>
        <button id="Warn" onclick="return WarnUser()" name="ConfirmDelete" value="deleteall">Delete all forever items</button>
    </form>

    <script src="/script/script.js"></script>
</body>

</html>