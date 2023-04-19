<?php

/*
These are all the functions that are being used for this webapp
If you have any questions don't hesitate contacting me on my discord server: https://discord.gg/z48t39mW27.
*/

define('ImageExtensions', array('jpg', 'JPG', 'png', 'PNG', 'gif', 'GIF', 'jpeg', 'JPEG', 'svg', 'SVG', 'webp', 'WEBP')); // Image formats
define('VideoExtensions', array('mp4', 'webm', 'ogg', 'mov', 'MOV')); // video formats
define('Windows', array('msi', 'MSI', 'exe', 'EXE')); // Windows executables formats
define('Audio', array('MP3', 'mp3', 'WAV', 'wav', 'acc', 'ACC')); // Audio formats


// Check if user is not logged. If user is not logged in redirect to login.php
function checkLogin()
{
    if (!isset($_SESSION['email']) && !isset($_SESSION['userid'])) { // If user is not logged in 
        header('location: login.php'); // redirect to login.php
        exit(); // exit to prevent user seeing the page before getting redirected
    }
}

// Check if user is logged in. If user is logged in redirect to index.php
function checkNotLogin()
{
    if (isset($_SESSION['email']) && isset($_SESSION['userid'])) { // If user is already logged in
        header('location: ./'); // Redirect to index.php
        exit(); // exit to prevent user seeing the page before getting redirected
    }
}

// Generate random strings for file uploads
function RandomCharacters($length) // Function must be called with a length
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_'; // All characters that can be generated
    $randomString = ''; // Defined a variable to put the string in
    $bytes = random_bytes($length); // Generate random bytes based on the length in the function
    for ($i = 0; $i < $length; $i++) { // Loop the amount of random charaters based on the length of the function
        $randomString .= $characters[ord($bytes[$i]) % strlen($characters)]; // The random bytes gets converted and gets assigned a character based on the byte
    }
    return $randomString; // This is the final string
}

// Get all files from specific user
function GetData($pdo, $IsTrash) // The variable must contain the connection of mysql via PDO and user must specify with 0 or 1 if it's trash. (0 is not trash) (1 is trash)
{
    $query = "SELECT * FROM uploads WHERE userid = :userid AND trash = :trash"; // Get everything from the table uploads based on the users id and if it's trash
    $stmt = $pdo->prepare($query); // Using prepared statements to prevent SQL injection attacks
    $stmt->execute([ // Exectuting the query and putting in all the data
        'userid' => $_SESSION['userid'],
        'trash' => $IsTrash
    ]);
    $filesindatabase = $stmt->fetchAll(PDO::FETCH_ASSOC); // All the file locations are in the variable
    return $filesindatabase; // This is the output for all the data
}


// Sort all data after receiving 
function SortData($filesindatabase) // That's convenient! We already have that because of the previous function :)
{
    for ($i = 0; $i < count($filesindatabase); $i++) { // Loop all the files and put them all the required information in variables
        $filename = $filesindatabase[$i]['filename'];
        $filesrc = $filesindatabase[$i]['filelocation'];
        $fileid = $filesindatabase[$i]['fileid'];
        $trash =  $filesindatabase[$i]['trash'];

        $fileseperation = explode('.', $filename); // Seperate the file based on where the periods are
        $extension = end($fileseperation); // Get everything after the last period
        switch ($extension) { // Remember this switch statement in the for loop so it will get all the files one at the time and it will check if the extensions can be determined.
            case in_array($extension, ImageExtensions): // If everything after the last period is an image format
                ShowImg($filesrc, $filename, $fileid, $trash); // Then call the image files function with all the required data
                break;
            case in_array($extension, VideoExtensions): // // If everything after the last period is an video format
                ShowVideo($filesrc, $filename, $fileid, $trash); // Then call the video files function with all the required data
                break;
            case in_array($extension, Windows): // If everything after the last period is a Windows executable format
                WinExecutables($filesrc, $filename, $fileid, $trash); // Then call the Windows executable function with all the required data
                break;
            case in_array($extension, Audio): // If everything after the last period is an audio format
                AudioFiles($filesrc, $filename, $fileid, $trash); // Then call the audio files function with all the required data
                break;
            default: // If the filetype cannot be determined
                unknownfiletype($filesrc, $filename, $fileid, $trash); // Then call the unknown filetype function with all the required data
                break;
        } // If you're using Visual Studio Code you can do CTRL + Left mouse button to see each function and you can also hover them
    }
}

// Get current storage in bytes and convert them to biggest unit
function StorageLeft()
{
    $currentdiskfreebytes = disk_free_space('/'); // Current free disk space in Bytes

    switch (true) {
        case $currentdiskfreebytes > 1000000000000000: // If current free disk space in Bytes is more than 1 quadrillion
            $currentdiskfree = round($currentdiskfreebytes / 1000000000000000, 2) . 'PB'; // Devide the amount of Bytes by 1 quadrillion and round them to 2 decimals to get PetaBytes
            break;
        case $currentdiskfreebytes > 1000000000000: // If current free disk space in Bytes is more than 1 trillion
            $currentdiskfree = round($currentdiskfreebytes / 1000000000000, 2) . 'TB'; // Devide the amount of Bytes by 1 trillion and round them to 2 decimals to get TerraBytes
            break;
        case $currentdiskfreebytes > 1000000000: // If current free disk space in Bytes is more than 1 billion
            $currentdiskfree = round($currentdiskfreebytes / 1000000000, 2) . 'GB'; // Devide the amount of Bytes by 1 billion and round them to 2 decimals to get GigaBytes
            break;
        case $currentdiskfreebytes > 1000000: // If current free disk space in Bytes is more than 1 million
            $currentdiskfree = round($currentdiskfreebytes / 1000000, 2) . 'MB'; // Devide the amount of Bytes by 1 million and round them to 2 decimals to get MegaBytes
            break;
        default: // If it's smaller than a million bytes it will default to KiloBytes
            $currentdiskfree = round($currentdiskfreebytes / 1000, 2) . 'KB'; // Devide the amount of Bytes by 1 thousand and round them to 2 decimals to get KiloBytes
            break;
    }

    return $currentdiskfree . ' free'; // This is the amount rounded
}

// Echo images
function ShowImg($imgsrc, $imgname, $imgid, $trash) // Get the required data for the function and if it's trash
{
    $safeimgid = htmlspecialchars($imgid); // Turn the id into text
    echo ("<div class='filecard'>
            <span class='filename'>{$imgname}</span>
            <img class='fileimg' src='{$imgsrc}' alt='{$imgname}'>
            <a href='$imgsrc' download='{$imgname}'>Download file</a>"); // Print out all the html

    if ($trash == 0) { // Check if the given trash variable is trash. If trash is false(0) then it will print a button with trash
        echo ("<button name='deletefile' value='{$safeimgid}'>Trash</button>
              </div>"); // 1 Trash button gets printed
    } else {
        echo ("<button name='restore' value='{$safeimgid}'>Restore item</button>

              <button name='deletefile' value='{$safeimgid}'>Delete forever</button>
              </div>"); // 2 buttons will get printed and 1 of them is to restore them from the trash and the other one to delete it forever
    }
}

// Echo videos
function ShowVideo($videosrc, $videoname, $videoid, $trash) // Get the required data for the function and if it's trash
{
    $safevideoid = htmlspecialchars($videoid); // Turn the id into text
    echo ("<div class='filecard'>
            <span class='filename'>{$videoname}</span>
            <video class='fileimg' src='{$videosrc}' alt='{$videoname}' controls></video>
            <a href='$videosrc' download='{$videoname}'>Download file</a>"); // Print out all the html
    if ($trash == 0) { // Check if the given trash variable is trash. If trash is false(0) then it will print a button with trash
        echo ("<button name='deletefile' value='{$safevideoid}'>Trash</button>
               </div>"); // 1 Trash button gets printed
    } else {
        echo ("<button name='restore' value='{$safevideoid}'>Restore item</button>
               <button name='deletefile' value='{$safevideoid}'>Delete forever</button>
               </div>"); // 2 buttons will get printed and 1 of them is to restore them from the trash and the other one to delete it forever
    }
}

// Echo Windows executables
function WinExecutables($exesrc, $exename, $exeid, $trash) // Get the required data for the function and if it's trash
{
    $safeexe = htmlspecialchars($exeid); // Turn the id into text
    echo ("<div class='filecard'>
            <span class='filename'>{$exename}</span>
            <img class='blackicons fileimg' src='/images/exe.svg' alt='{$exename}'>
            <a href='$exesrc' download='{$exename}'>Download file</a>"); // Print out all the html
    if ($trash == 0) { // Check if the given trash variable is trash. If trash is false(0) then it will print a button with trash
        echo ("<button name='deletefile' value='{$safeexe}'>Trash</button>
              </div>"); // 1 Trash button gets printed
    } else {
        echo ("<button name='restore' value='{$safeexe}'>Restore item</button>
              <button name='deletefile' value='{$safeexe}'>Delete forever</button>
              </div>"); // 2 buttons will get printed and 1 of them is to restore them from the trash and the other one to delete it forever
    }
}

//echo Audio files
function AudioFiles($audiosrc, $audioname, $audioid, $trash) // Get the required data for the function and if it's trash
{
    $audioid = htmlspecialchars($audioid);  // Turn the id into text
    echo ("<div class='filecard'>
            <span class='filename'>{$audioname}</span>
            <img class='fileimg blackicons' src='/images/audio.svg' alt='Audio icon'>
            <audio src='{$audiosrc}' alt='{$audioname}' controls></audio>
            <a href='$audiosrc' download='{$audioname}'>Download file</a>"); // Print out all the html
    if ($trash == 0) { // Check if the given trash variable is trash. If trash is false(0) then it will print a button with trash
        echo ("<button name='deletefile' value='{$audioid}'>Trash</button>
               </div>"); // 1 Trash button gets printed
    } else {
        echo ("<button name='restore' value='{$audioid}'>Restore item</button>
               <button name='deletefile' value='{$audioid}'>Delete forever</button>
               </div>"); // 2 buttons will get printed and 1 of them is to restore them from the trash and the other one to delete it forever
    }
}

//echo unknown file types
function unknownfiletype($filesrc, $filename, $fileid, $trash) // Get the required data for the function and if it's trash
{
    $safefileid = htmlspecialchars($fileid); // Turn the id into text
    echo ("<div class='filecard'>
            <span class='filename'>{$filename}</span>
            <img class='blackicons fileimg' src='/images/unknown.svg' alt='{$filename}'>
            <a href='$filesrc' download='{$filename}'>Download file</a>"); // Print out all the html
    if ($trash == 0) { // Check if the given trash variable is trash. If trash is false(0) then it will print a button with trash
        echo ("<button name='deletefile' value='{$safefileid}'>Trash</button>
              </div>"); // 1 Trash button gets printed
    } else {
        echo ("<button name='restore' value='{$safefileid}'>Restore item</button>
              <button name='deletefile' value='{$safefileid}'>Delete forever</button>
              </div>"); // 2 buttons will get printed and 1 of them is to restore them from the trash and the other one to delete it forever
    }
}

// Upload files and give them a random name and put insert all required information on the sql database
function Uploadfiles($pdo) // The variable must contain the connection of mysql via PDO
{
    $targetdir = "uploads/"; // Folder where the files are gonna be uploaded
    $fullpath = "/"; // The required slash for the database so html can get it from anywhere on the site
    $uploadquery = "INSERT INTO uploads (filename, filelocation, userid, trash) VALUES (:filename, :filelocation, :userid, :trash)"; // Add file name, file location where for only the user id
    $uploadstmt = $pdo->prepare($uploadquery); // Using prepared statements to prevent SQL injection attacks

    $countfiles = count($_FILES["fileupload"]["name"]); // count the amount of files

    for ($i = 0; $i < $countfiles; $i++) {
        $target_file = basename($_FILES["fileupload"]["name"][$i]); // Get the file name
        $target_filelocation = $targetdir . RandomCharacters(128) . $target_file; // Specify the file upload directory and the file location with randomly generated characters to make it hard to guess (AKA: File source)
        move_uploaded_file($_FILES['fileupload']['tmp_name'][$i], $target_filelocation); // Move the temporary file to the file source
        $uploadstmt->execute([ // execute the prepared statements
            'filename' => $target_file,
            'filelocation' => $fullpath . $target_filelocation, // The only thing worth mentioning here is that the full path on the database is gonna look like this: /uploads/randomcharactersFilename.extension
            'userid' => $_SESSION['userid'],
            'trash' => 0
        ]);
    }
    header("location: ./"); // Refresh the page / redirect to the same page so you cannot upload the same files by refreshing
    exit(); // Exit so the script stops for the user
}

// Delete all files and delete individual files
function deletefiles($pdo) // The variable must contain the connection of mysql via PDO
{
    if ($_POST['deletefile'] !== "deleteall") { // If the user clicks delete forever on individual files it fill run this
        $querygetfile = "SELECT * FROM uploads WHERE trash = :trash AND userid = :userid AND fileid = :fileid"; // get the specific file by the fileid and userid
        $stmtgetfile = $pdo->prepare($querygetfile); // Using prepared statements to prevent SQL injection attacks
        $stmtgetfile->execute([ // execute the prepared statements
            'trash' => 1, // This function only runs in trash.php so the trash is gonna be true(1) by default
            'userid' => $_SESSION['userid'],
            'fileid' => $_POST['deletefile']
        ]);
        $filesindatabasegetfile = $stmtgetfile->fetch(PDO::FETCH_ASSOC); // Put the specific file into a variable

        $trashitemquery = "DELETE FROM uploads WHERE trash = :trash AND userid = :userid AND fileid = :fileid"; // Delete the specific file using the file id and user id from the database (Not the server)
        $stmttrash = $pdo->prepare($trashitemquery);

        $stmttrash->execute([
            'trash' => 1, // This function only runs in trash.php so the trash is gonna be true(1) by default
            'userid' => $_SESSION['userid'],
            'fileid' => $_POST['deletefile']
        ]);
        unlink('.' . $filesindatabasegetfile['filelocation']); // Delete the file it self, because without this the file stays stored on the server, but not accessible by the user
    } else { // if the user clicks delete all files it fill run this
        $querygetfile = "SELECT * FROM uploads WHERE trash = :trash AND userid = :userid"; // Get all the files where they're from a specific users using the userid and where trash is true
        $stmtgetfile = $pdo->prepare($querygetfile); // Using prepared statements to prevent SQL injection attacks
        $stmtgetfile->execute([ // execute the prepared statements
            'trash' => 1, // This function only runs in trash.php so the trash is gonna be true(1) by default
            'userid' => $_SESSION['userid'],
        ]);
        $filesindatabasegetfile = $stmtgetfile->fetchAll(PDO::FETCH_ASSOC); // Put the specific file into a variable

        $trashitemquery = "DELETE FROM uploads WHERE trash = :trash AND userid = :userid"; // Delete the all files using the where is trash and user id from the database (Not the server)
        $stmttrash = $pdo->prepare($trashitemquery); // Using prepared statements to prevent SQL injection attacks
        $stmttrash->execute([ // execute the prepared statements
            'trash' => 1, // This function only runs in trash.php so the trash is gonna be true(1) by default
            'userid' => $_SESSION['userid'],
        ]);
        echo count($filesindatabasegetfile); // count the amount of files
        for ($i = 0; $i < count($filesindatabasegetfile); $i++) {
            unlink('.' . $filesindatabasegetfile[$i]['filelocation']); // Delete all files where there it's trash, because without this the file stays stored on the server, but not accessible by the user
        }
    }
    header('location: trash.php'); // Refresh the page / redirect to the same page so you cannot upload the same files by refreshing
    exit(); // Exit so the script stops for the user
}


// Put items in the trash
function trashfile($pdo) // The variable must contain the connection of mysql via PDO
{
    $trashitemquery = "UPDATE uploads SET trash = :trash WHERE userid = :userid AND fileid = :fileid"; // Change the specific file to trashed using user id and file id
    $stmttrash = $pdo->prepare($trashitemquery); // Using prepared statements to prevent SQL injection attacks
    $stmttrash->execute([ // execute the prepared statements
        'trash' => 1, // Set trash to true(1)
        'userid' => $_SESSION['userid'], // user id so it only changes for specific user
        'fileid' => $_POST['deletefile'] // file id given by the button that needs to be pressed to trash a file
    ]);
    header('location: ./'); // Refresh the page / redirect to the same page so you cannot upload the same files by refreshing
    exit(); // Exit so the script stops for the user
}

// Restore items from the trash
function RestoreItem($pdo) // The variable must contain the connection of mysql via PDO
{
    $trashitemquery = "UPDATE uploads SET trash = :trash WHERE userid = :userid AND fileid = :fileid"; // Change the specific file to not trashed using user id and file id
    $stmttrash = $pdo->prepare($trashitemquery); // Using prepared statements to prevent SQL injection attacks
    $stmttrash->execute([ // execute the prepared statements
        'trash' => 0, // Set trash to false(0)
        'userid' => $_SESSION['userid'], // user id so it only changes for specific user
        'fileid' => $_POST['restore'] // file id given by the button that needs to be pressed to trash a file
    ]);
    header('location: trash.php'); // Refresh the page / redirect to the same page so you cannot upload the same files by refreshing
    exit(); // Exit so the script stops for the user
}

// Search bar functionaility
function SearchData($pdo, $IsTrash, $search) // The variable must contain the connection of mysql via PDO and user must specify with 0 or 1 if it's trash. (0 is not trash) (1 is trash) and what you're searching for
{
    $query = "SELECT * FROM uploads WHERE userid = :userid AND trash = :trash AND filename LIKE :search"; // Select all from the table uploads where the user id is from the user and trash is trash given in the function and the thing the user is looking for
    $stmt = $pdo->prepare($query); // Using prepared statements to prevent SQL injection attacks
    $stmt->execute([ // execute the prepared statements
        'userid' => $_SESSION['userid'], // user id so it only finds for specific user
        'trash' => $IsTrash, // filter trashed items
        'search' => "%" . $search . "%" // Added percentages in front of the search and after the search so it can check any position of the string
    ]);
    $filesindatabase = $stmt->fetchAll(PDO::FETCH_ASSOC); // Put all the results that match in this variable
    return $filesindatabase; // This is the final result
}
