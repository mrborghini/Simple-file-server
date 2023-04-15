<?php

define('ImageExtensions', array('jpg', 'JPG', 'png', 'PNG', 'gif', 'GIF', 'jpeg', 'JPEG', 'svg', 'SVG', 'webp', 'WEBP'));
define('VideoExtensions', array('mp4', 'webm', 'ogg'));
define('Windows', array('msi', 'exe'));

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
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_';
    $randomString = '';
    $bytes = random_bytes($length);
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[ord($bytes[$i]) % strlen($characters)];
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

function ShowImg($imgsrc, $imgname, $imgid, $trash)
{
    $safeimgid = htmlspecialchars($imgid);
    echo ("<div class='filecard'>
            <p class='filename'>{$imgname}</p>
            <img class='fileimg' src='{$imgsrc}' alt='{$imgname}'>
            <a href='$imgsrc'download='{$imgname}'>Download file</a>");

    if ($trash == 0) {
        echo ("<button name='deletefile' value='{$imgid}'>Trash</button>
              </div>");
    } else {
        echo ("<button name='deletefile' value='{$imgid}'>Delete forever</button>
              </div>");
    }
}

function ShowVideo($videosrc, $videoname, $videoid, $trash)
{
    echo ("<div class='filecard'>
            <p class='filename'>{$videoname}</p>
            <video class='fileimg' src='{$videosrc}' alt='{$videoname}' controls></video>
            <a href='$videosrc'download='{$videoname}'>Download file</a>");
    if ($trash == 0) {
        echo ("<button name='deletefile' value='{$videoid}'>Trash</button>
                      </div>");
    } else {
        echo ("<button name='deletefile' value='{$videoid}'>Delete forever</button>
                      </div>");
    }
}

function WinExecutables($exesrc, $exename, $exeid, $trash)
{
    echo ("<div class='filecard'>
            <p class='filename'>{$exename}</p>
            <img class='blackicons' src='/images/exe.svg' alt='{$exename}'>
            <a href='$exesrc'download='{$exename}'>Download file</a>");
    if ($trash == 0) {
        echo ("<button name='deletefile' value='{$exeid}'>Trash</button>
              </div>");
    } else {
        echo ("<button name='deletefile' value='{$exeid}'>Delete forever</button>
              </div>");
    }
}

?>