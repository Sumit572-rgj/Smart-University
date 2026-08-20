<?php
echo "Connecting to AlwaysData Secure FTP...\n";

$ftp_server = "ftp-citums.alwaysdata.net";
$ftp_username = "citums";
$ftp_password = "9812361098abc";

$conn = ftp_ssl_connect($ftp_server) or die("Could not connect to $ftp_server");
if (@ftp_login($conn, $ftp_username, $ftp_password)) {
    echo "Connected as $ftp_username\n";
} else {
    die("Could not login\n");
}

ftp_pasv($conn, true);

$local_dir = "C:/xampp/htdocs/cit_ums";
$remote_dir = "/www";

echo "Navigating to /www...\n";
if (!ftp_chdir($conn, $remote_dir)) {
    die("Could not change to $remote_dir\n");
}

// Delete default index.html if exists
@ftp_delete($conn, "index.html");

function ftp_sync($conn, $local, $remote) {
    $dir = opendir($local);
    while ($file = readdir($dir)) {
        if ($file == '.' || $file == '..' || $file == '.git' || strpos($file, '.zip') !== false) {
            continue;
        }
        
        $localFile = $local . '/' . $file;
        $remoteFile = $remote . '/' . $file;
        
        if (is_dir($localFile)) {
            @ftp_mkdir($conn, $remoteFile);
            ftp_sync($conn, $localFile, $remoteFile);
        } else {
            echo "Uploading: $file\n";
            ftp_put($conn, $remoteFile, $localFile, FTP_BINARY);
        }
    }
    closedir($dir);
}

echo "Uploading files, this will take about a minute...\n";
ftp_sync($conn, $local_dir, ".");

echo "Upload completely finished!\n";
ftp_close($conn);
