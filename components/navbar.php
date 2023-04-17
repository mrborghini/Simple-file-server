<?php

$url = $_SERVER['REQUEST_URI'];

?>

<nav class="navbar">

    <div class="navaction">
        <?php
        if ($url !== "/trash.php") {
            echo '<input type="file" onchange="submit()" id="uploadfile" class="uploadfile" name="fileupload[]" multiple>
        <label class="uploadfilelabel" for="uploadfile">
            <img class="blackicons" src="/images/upload.svg" alt="Upload files">
        </label>';
        } else {
            echo '<form method="post">
            <button class="uploadfile" id="Warn" onclick="return WarnUser()" name="ConfirmDelete" value="deleteall"></button>
            <label class="uploadfilelabel" for="Warn"><img src="/images/deleteall.svg" class="blackicons" alt="Delete all"></label>
        </form>';
        }
        ?>
    </div>
    <div class="navbarlinks">
        <ul>
            <li class="searchexception">
                <form method="post">
                    <span>
                        <input type="text" name="search" value="<?php if (isset($_SESSION['search'])) {
                                                                    echo $_SESSION['search'];
                                                                } ?>" id="search" placeholder="Search and press enter">
                    </span>
            </li>
            <li>
                <?php
                if ($url !== "/trash.php") {
                    echo '<a href="trash.php">Trash</a>';
                } else {
                    echo '<a href="/">Home</a>';
                }
                ?>
            </li>
            <li onclick="storage()">
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