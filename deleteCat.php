<?php
session_start();
include 'koneksi.php';
$conn;
$result;
openKoneksi();
if (!isset($_SESSION["username"])) {
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'login.php';
    header("Location: http://$host$uri/$extra");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/sweetalert/sweetalert.css" type="text/css" />
    <script type="text/javascript" src="assets/sweetalert/sweetalert.min.js"></script>
    <title>FORM</title>
</head>
<html>

<body>

</body>

</html>
<?php

function deleteDataCat($datapost, $dataget)
{
    global $conn;
    global $result;
    $del_id = $dataget["category_id"];
    try {
        $stm = $conn->prepare("DELETE FROM category WHERE category_id = '$del_id'");

        $stm->execute();

        echo "<script type='text/javascript'>setTimeout(function(){
            swal({
                title: 'Berhasil! :)',
                text: 'Data Telah Dihapus!',
                type: 'success',
                button: 'Ok',
                showConfirmButton: true
            });
        });window.setTimeout(function(){
            window.location.replace('category.php');
        },3000);</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>swal('Error!!', 'Data Gagal Dihapus!', 'error');</script>";
        echo "Error : " . $e->getMessage();
    }
}

openKoneksi();


deleteDataCat($_POST, $_GET);


closeKoneksi();

        // print_r($result);
