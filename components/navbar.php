<?php

$url = $_SERVER['REQUEST_URI']; // Check current page

?>

<nav class="navbar">

    <div class="navaction">
        <form method="post" enctype="multipart/form-data">
            <?php
            if ($url !== "/trash.php") { // If current page is not trash then it can upload files
                echo '<input type="file" onchange="submit()" id="uploadfile" class="uploadfile" name="fileupload[]" multiple>
        <label class="uploadfilelabel" for="uploadfile">
            <img class="blackicons" src="/images/upload.svg" alt="Upload files">
        </label>';
            } else { // If the current page is trash then it can delete all files
                echo '<button class="uploadfile" id="Warn" onclick="return WarnUser()" name="ConfirmDelete" value="deleteall"></button>
            <label class="uploadfilelabel" for="Warn"><img src="/images/deleteall.svg" class="blackicons" alt="Delete all"></label>';
            }
            ?>
        </form>
    </div>
    <div class="navbarlinks">
        <ul>
            <li class="searchexception">
                <form method="post">
                    <span>
                        <input type="text" name="search" value="<?php
                                                                if (isset($_SESSION['search'])) { // prints the search from a session variable if it's not empty
                                                                    echo $_SESSION['search'];
                                                                }
                                                                ?>" id="search" placeholder="Search and press enter">
                    </span>
                </form>
            </li>
            <li>
                <?php
                if ($url !== "/trash.php") { // If page is not trash
                    echo '<a href="trash.php">Trash</a>'; // Hyperlink to trash
                } else {
                    echo '<a href="/">Home</a>'; // Hyperlink to home
                }
                ?>
            </li>
            <li onclick="storage()"> <!-- Call storage function (Javascript) -->
                <span><?php echo StorageLeft(); ?></span>
            </li>
            <li>
                <a href="logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <span class="hamburger">
        <span class="hamburgerbar"></span>
        <span class="hamburgerbar"></span>
        <span class="hamburgerbar"></span>
    </span>
</nav>