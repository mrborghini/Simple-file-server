<?php

function checkLogin()
{
    if (!isset($_SESSION['email']) && !isset($_SESSION['userid'])) {
        header('location: login.php');
        exit();
    }
}

function checkNotLogin()
{
    if (isset($_SESSION['email']) && isset($_SESSION['userid'])) {
        header('location: ./');
        exit();
    }
}

function RandomCharacters($length)
{
    $characters = 'qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM_';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function StorageLeft()
{
    $currentdiskfreebytes = disk_free_space('/');

    switch (true) {
        case $currentdiskfreebytes > 1000000000000:
            $currentdiskfree = round($currentdiskfreebytes / 1000000000000, 2) . 'TB';
            break;
        case $currentdiskfreebytes > 1000000000:
            $currentdiskfree = round($currentdiskfreebytes / 1000000000, 2) . 'GB';
            break;
        case $currentdiskfreebytes > 1000000:
            $currentdiskfree = round($currentdiskfreebytes / 1000000, 2) . 'MB';
            break;
        default:
            $currentdiskfree = round($currentdiskfreebytes / 1000, 2) . 'KB';
            break;
    }

    return $currentdiskfree . ' free';
}

function ShowImg ($imgsrc, $imgname){

    echo ("<a href='$imgsrc' download='{$imgname}'><img src='{$imgsrc}' alt='{$imgname}'></a>");

}

?>
