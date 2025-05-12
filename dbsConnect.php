<?php
$host = "mariadb";
$user = "frantaVomacka";
$password = "kekw";
$dbname = "tarkov_wiki";
try {

    $conn = new pdo("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo ":)";
}
catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

