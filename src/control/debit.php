<?php 
session_start();
$racine_path = "../../";

include $racine_path."src/model/User.php";
// refuse l'accès au utilisateur non connecté
User::checkIfConnected();

$sizeMiB = 100;
$ftpServer = "10.10.10.1";
$ftpUser   = "stud";
$ftpPass   = "stud";
$remoteFile = "/tmp/".$sizeMiB."MiB.test";
$localFile = $racine_path."src/tmp/".$sizeMiB."MiB.test";

shell_exec("/var/www/html/src/scripts/create-dummy-file.sh ".$localFile." ".$sizeMiB);

$conn = ftp_connect($ftpServer, 21, 10);
if (!$conn) {
    die("FTP connection failed");
}

if (!ftp_login($conn, $ftpUser, $ftpPass)) {
    die("FTP login failed");
}

ftp_pasv($conn, true);
set_time_limit(0); // prevent timeout

$times = [];
for($i = 0; $i <= 5; $i++){
  $start = microtime(true);

  if (!ftp_put($conn, $remoteFile, $localFile, FTP_BINARY)) {
      die("FTP upload failed");
  }

  $end = microtime(true);
  array_push($times, $end - $start);
}

$speedMiBps = $sizeMiB / (array_sum($times) / count($times));

$upSpeed = round($speedMiBps,3);

$times = [];
for($i = 0; $i <= 5; $i++){
  $start = microtime(true);

  if (!ftp_get($conn, $localFile, $remoteFile, FTP_BINARY)) {
      die("FTP download failed");
  }

  $end = microtime(true);
  array_push($times, $end - $start);
}

ftp_close($conn);

$speedMiBps = $sizeMiB / (array_sum($times) / count($times));

$dlSpeed = round($speedMiBps,2);
$result = ["dlSpeed" => $dlSpeed, "upSpeed" => $upSpeed];

echo json_encode($result);
