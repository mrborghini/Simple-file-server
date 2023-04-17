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
    <link rel="stylesheet" href="style/style.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your files</title>
</head>

<body>
    <nav>
        <?php echo (StorageLeft()); ?>
        <a class="navigation" href="logout.php">Logout</a>
        <a class="navigation" href="/">Home</a>
    </nav>
    <form method="post">
        <div class="files">
            <?php

            SortData(GetData($pdo, 1));

            ?>
        </div>
        <button name="deletefile" value="deleteall">Delete all forever items</button>
    </form>

</body>

</html>