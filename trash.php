<?php

require_once('database/dbconnect.php'); // Import dbconnect.php
require_once('components/functions.php'); // Import functions.php

checkLogin(); // Check if user is logged in with the function

if (isset($_POST['deletefile'])) { // If any delete button is pressed
    deletefiles($pdo); // Delete files using the pdo variable from dbconnect.php
}

if (isset($_POST['restore'])) { // If any restore button is pressed
    RestoreItem($pdo); // Restore files using the pdo variable from dbconnect.php
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src="/script/script.js" defer></script>
    <link rel="icon" href="images/SimpleFileServer.png">
    <link rel="stylesheet" href="style/style.css">
    <?php

    $currenttheme = GetCurrentTheme($pdo);

    echo ApplyCurrentTheme($currenttheme);

    ?>
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
            if (isset($_POST['search'])) { // If search bar has new data
                $_SESSION['search'] = $_POST['search']; // it will change the session variable to the search
                header('location: trash.php'); // Refresh the page / redirect to the same page so you cannot upload the same files by refreshing
                exit(); // exit so the script stops for the user
            }
            if (!isset($_SESSION['search'])) { // if the searchbar doesn't have data
                SortData(GetData($pdo, 1)); // it will call functions sort data and get data. These functions just sorts the file extensions and puts them in their own elements and only trashed items
            } else { // If searchdoes have data
                SortData(SearchData($pdo, 1, $_SESSION['search'])); // It will sort the data and the print the data it has found
            }
            ?>
        </div>
    </form>

</body>

</html>