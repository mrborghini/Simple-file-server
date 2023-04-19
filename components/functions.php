<?php

define('ImageExtensions', array('jpg', 'JPG', 'png', 'PNG', 'gif', 'GIF', 'jpeg', 'JPEG', 'svg', 'SVG', 'webp', 'WEBP'));
define('VideoExtensions', array('mp4', 'webm', 'ogg', 'mov', 'MOV'));
define('Windows', array('msi', 'MSI', 'exe', 'EXE'));
define('Audio', array('MP3', 'mp3', 'WAV', 'wav', 'acc', 'ACC'));

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

function GetData($pdo, $iftrash)
{
    $query = "SELECT * FROM uploads WHERE userid = :userid AND trash = :trash";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'userid' => $_SESSION['userid'],
        'trash' => $iftrash
    ]);
    $filesindatabase = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $filesindatabase;
}

function SortData($filesindatabase)
{
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
            case in_array($extension, Audio):
                AudioFiles($filesrc, $filename, $fileid, $trash);
                break;
            default:
                break;
        }
    }
}

function StorageLeft()
{
    $currentdiskfreebytes = disk_free_space('/');

    switch (true) {
        case $currentdiskfreebytes > 1000000000000000:
            $currentdiskfree = round($currentdiskfreebytes / 1000000000000000, 2) . 'PB';
            break;
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
            <span class='filename'>{$imgname}</span>
            <img class='fileimg' src='{$imgsrc}' alt='{$imgname}'>
            <a href='$imgsrc'download='{$imgname}'>Download file</a>
            ");

    if ($trash == 0) {
        echo ("<button name='deletefile' value='{$safeimgid}'>Trash</button>
              </div>");
    } else {
        echo ("<button name='restore' value='{$safeimgid}'>Restore item</button>

              <button name='deletefile' value='{$safeimgid}'>Delete forever</button>
              </div>");
    }
}

function ShowVideo($videosrc, $videoname, $videoid, $trash)
{
    $safevideoid = htmlspecialchars($videoid);
    echo ("<div class='filecard'>
            <span class='filename'>{$videoname}</span>
            <video class='fileimg' src='{$videosrc}' alt='{$videoname}' controls></video>
            <a href='$videosrc'download='{$videoname}'>Download file</a>
        ");
    if ($trash == 0) {
        echo ("<button name='deletefile' value='{$safevideoid}'>Trash</button>
               </div>");
    } else {
        echo ("<button name='restore' value='{$safevideoid}'>Restore item</button>
               <button name='deletefile' value='{$safevideoid}'>Delete forever</button>
               </div>");
    }
}

function WinExecutables($exesrc, $exename, $exeid, $trash)
{
    $safeexe = htmlspecialchars($exeid);
    echo ("<div class='filecard'>
            <span class='filename'>{$exename}</span>
            <img class='blackicons fileimg' src='/images/exe.svg' alt='{$exename}'>
            <a href='$exesrc'download='{$exename}'>Download file</a>
            ");
    if ($trash == 0) {
        echo ("<button name='deletefile' value='{$safeexe}'>Trash</button>
              </div>");
    } else {
        echo ("<button name='restore' value='{$safeexe}'>Restore item</button>
              <button name='deletefile' value='{$safeexe}'>Delete forever</button>
              </div>");
    }
}

function AudioFiles($audiosrc, $audioname, $audioid, $trash){
    $audioid = htmlspecialchars($audioid);
    echo ("<div class='filecard'>
            <span class='filename'>{$audioname}</span>
            <img class='fileimg blackicons' src='/images/audio.svg' alt='Audio icon'>
            <audio src='{$audiosrc}' alt='{$audioname}' controls></audio>
            <a href='$audiosrc'download='{$audioname}'>Download file</a>
        ");
    if ($trash == 0) {
        echo ("<button name='deletefile' value='{$audioid}'>Trash</button>
               </div>");
    } else {
        echo ("<button name='restore' value='{$audioid}'>Restore item</button>
               <button name='deletefile' value='{$audioid}'>Delete forever</button>
               </div>");
    }
}

function Uploadfiles($pdo)
{
    $targetdir = "uploads/";
    $fullpath = "/";
    $uploadquery = "INSERT INTO uploads (filename, filelocation, userid, trash) VALUES (:filename, :filelocation, :userid, :trash)";
    $uploadstmt = $pdo->prepare($uploadquery);

    if (is_array($_FILES["fileupload"]["name"])) {

        $countfiles = count($_FILES["fileupload"]["name"]);

        for ($i = 0; $i < $countfiles; $i++) {
            $target_file = basename($_FILES["fileupload"]["name"][$i]);
            $target_filelocation = $targetdir . RandomCharacters(128) . $target_file;
            move_uploaded_file($_FILES['fileupload']['tmp_name'][$i], $target_filelocation);
            $uploadstmt->execute([
                'filename' => $target_file,
                'filelocation' => $fullpath . $target_filelocation,
                'userid' => $_SESSION['userid'],
                'trash' => 0
            ]);
        }
    } else {
        $target_file = basename($_FILES["fileupload"]["name"]);
        $target_filelocation = $targetdir . RandomCharacters(128) . $target_file;
        move_uploaded_file($_FILES['fileupload']['tmp_name'], $target_filelocation);
        $uploadstmt->execute([
            'filename' => $target_file,
            'filelocation' => $fullpath . $target_filelocation,
            'userid' => $_SESSION['userid'],
            'trash' => 0
        ]);
    }
    header("location: ./");
    exit();
}
function deletefiles($pdo)
{
    if ($_POST['deletefile'] !== "deleteall") {
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
        unlink('.' . $filesindatabasegetfile['filelocation']);
    } else {
        $querygetfile = "SELECT * FROM uploads WHERE trash = :trash AND userid = :userid";
        $stmtgetfile = $pdo->prepare($querygetfile);
        $stmtgetfile->execute([
            'trash' => 1,
            'userid' => $_SESSION['userid'],
        ]);
        $filesindatabasegetfile = $stmtgetfile->fetchAll(PDO::FETCH_ASSOC);

        $trashitemquery = "DELETE FROM uploads WHERE trash = :trash AND userid = :userid";
        $stmttrash = $pdo->prepare($trashitemquery);
        $stmttrash->execute([
            'trash' => 1,
            'userid' => $_SESSION['userid'],
        ]);
        echo count($filesindatabasegetfile);
        for ($i = 0; $i < count($filesindatabasegetfile); $i++) {
            unlink('.' . $filesindatabasegetfile[$i]['filelocation']);
        }
    }
    header('location: trash.php');
    exit();
}

function trashfile($pdo)
{
    $trashitemquery = "UPDATE uploads SET trash = :trash WHERE userid = :userid AND fileid = :fileid";
    $stmttrash = $pdo->prepare($trashitemquery);
    $stmttrash->execute([
        'trash' => 1,
        'userid' => $_SESSION['userid'],
        'fileid' => $_POST['deletefile']
    ]);
    header('location: ./');
    exit();
}

function SearchData($pdo, $iftrash, $search)
{
    $query = "SELECT * FROM uploads WHERE userid = :userid AND trash = :trash AND filename LIKE :search";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'userid' => $_SESSION['userid'],
        'trash' => $iftrash,
        'search' => "%" . $search . "%"
    ]);
    $filesindatabase = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $filesindatabase;
}

?>