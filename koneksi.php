<?php

$conn;
$result;
function openKoneksi()
{
    global $host, $uname, $pwd, $db;

    global $conn;

    $host = "localhost";
    $uname = "postgres";
    $pwd = "ripal";
    $db = "php";
    $dsn = "pgsql:host=$host; dbname=$db";

    try {
        $conn = new PDO($dsn, $uname, $pwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Berhasil";
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
}


function closeKoneksi()
{
    global $conn;
    $conn = null;
    //echo "<br>Koneksi terputus<br>";
}
