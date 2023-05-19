<?php

$url = $_SERVER['REQUEST_URI']; // Check current page

if ($url == '/components/navbar.php') { // Check if current page is functions.php
    header('location: ../'); // Redirect to index
    exit(); // exit so the rest of page is not loaded
}

?>

<nav class="navbar">

    <div class="navaction">
        <form method="post" enctype="multipart/form-data">
            <?php
            switch ($url) {
                case "/trash.php":
                    echo '<button class="uploadfile" id="Warn" onclick="return WarnUser()" name="ConfirmDelete" value="deleteall"></button>
                          <label class="uploadfilelabel" for="Warn"><img src="/images/deleteall.svg" class="blackicons" alt="Delete all"></label>';
                    break;
                case "/settings.php":
                    echo '<button class="uploadfile"></button>
                          <label class="uploadfilelabel" for="Warn"><img src="/images/example.svg" class="blackicons" alt="Example"></label>';
                    break;
                default:
                    echo '<input type="file" onchange="submit()" id="uploadfile" class="uploadfile" name="fileupload[]" multiple>
                          <label class="uploadfilelabel" for="uploadfile">
                          <img class="blackicons" src="/images/upload.svg" alt="Upload files">
                          </label>';
                    break;
            }
            ?>
        </form>
    </div>
    <div class="navbarlinks">
        <ul>
            <li class="searchexception">
                <form method="post">
                    <span class="searchexception">
                        <input type="text" name="search" value="<?php if (isset($_SESSION['search'])) echo $_SESSION['search']; ?>" id="search" placeholder="Search and press enter"> <!-- prints the search from a session variable if it's not empty -->
                    </span>
                </form>
            </li>
            <?php
            if ($url !== "/") {
                echo '<li>
                <a href="/">Home</a>
            </li>';
            } else {
                echo '
            <li>
                <a href="trash.php">Trash</a>
            </li>';
            }

            ?>
            <li onclick="storage()"> <!-- Call storage function (Javascript) -->
                <span><?php echo StorageLeft(); ?></span>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
            <li>
                <a href="settings.php">⚙</a>
            </li>
        </ul>
    </div>

    <span class="hamburger">
        <span class="hamburgerbar"></span>
        <span class="hamburgerbar"></span>
        <span class="hamburgerbar"></span>
    </span>
</nav>