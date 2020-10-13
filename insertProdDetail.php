<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.6">
    <link rel="stylesheet" href="assets/sweetalert/sweetalert.css">
    <script src="assets/sweetalert/sweetalert.min.js"></script>
    <title>Manage Product</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/js/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <!-- <link href="jumbotron.css" rel="stylesheet"> -->
</head>
<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION["username"])) {
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'login.php';
    header("Location: http://$host$uri/$extra");
    exit;
}
$conn;
$result;
function insertProdDetail($data)
{
    global $conn;
    global $result;
    $ss = $_SESSION["username"];

    try {
        $stm = $conn->prepare("INSERT INTO products_detail(products_detail_id,products_id,valid_form,valid_until,create_date,create_by,update_date,update_by,price)
        VALUES((select
   case
	when max(products_detail_id)is null then 1
	else max(products_detail_id)+1
	end from products_detail
   ),:products_id,:valid_form,:valid_until,current_date,'$ss',current_date,null,:price)");


        $stm->bindParam('products_id', $data["products_id"], PDO::PARAM_STR);
        $stm->bindParam('valid_form', $data["valid_form"], PDO::PARAM_STR);
        $stm->bindParam('valid_until', $data["valid_until"], PDO::PARAM_STR);
        $stm->bindParam('price', $data["price"], PDO::PARAM_STR);
        //$stm->bindParam('foto', $data["foto"], PDO::PARAM_STR);

        $stm->execute();
        echo "<script type='text/javascript'>setTimeout(function(){
            swal({
                title: 'Berhasil! :)',
                text: 'Selamat, Data Telah Ditambahkan!',
                type: 'success',
                button: 'Ok',
                showConfirmButton: true
            });
        });window.setTimeout(function(){
            window.location.replace('manage.php');
        },3000);</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script>alert('Data Gagal Ditambahkan');</script>";
    }
}

if (isset($_POST["submit"])) {
    openKoneksi();
    insertProdDetail($_POST);
    closeKoneksi();
}
?>