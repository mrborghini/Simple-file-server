<?php

require_once('database/dbconnect.php'); // Import dbconnect.php
require_once('components/functions.php'); // Import functions.php

checkLogin(); // Check if user is logged in with the function

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
    <title>Preferences</title>
</head>

<body>

    <?php

    require_once('components/navbar.php'); // Import navbar

    if (isset($_POST['themeselector'])) {
        changetheme($pdo, $_POST['themeselector']); // Theme changer
    }

    ?>
    <form method="post" onchange="submit()">
        <div class="themes">
            <p></p>
            <span>Theme selector</span>
            <p></p>
            <select class="themeselector" selected="selected" name="themeselector">

                <?php

                $themes = [
                    'Default dark theme' => 0,
                    'Light theme' => 1,
                    'Pink theme' => 2,
                    'Red theme' => 3,
                    'Extra dark theme' => 4,
                    'Purple theme' => 5,
                    'Lambo socials theme' => 6
                ];

                foreach ($themes as $theme => $value) {
                    if ($currenttheme === $value) {
                        echo "<option selected value='{$value}'>{$theme}</option>";
                    } else {
                        echo "<option value='{$value}'>{$theme}</option>";
                    }
                }

                ?>
            </select>
            <p></p>

            <div class='filecard'>
                <span class='filename'>Preview</span>
                <img class='blackicons fileimg' src='/images/example.svg' alt='Filename'>
                <a>Download file</a>
                <a>Trash</a>
            </div>
        </div>
    </form>
</body>

</html>