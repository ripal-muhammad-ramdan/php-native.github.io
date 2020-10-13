<?php
session_start();
include 'koneksi.php';
$tampilProd;
openKoneksi();
if (!isset($_SESSION["username"])) {
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'login.php';
    header("Location: http://$host$uri/$extra");
    exit;
}

function tampilProduct($src)
{
    global $conn;

    global $tampilProd;
    try {
        $stm = $conn->prepare("SELECT a.*, b.* FROM products as a, products_detail as b 
                                WHERE a.products_id = b.products_id and 
                                LOWER(a.nama_produk) LIKE '%$src%' and 
                                to_char(current_date, 'dd/mm/yyyy') < to_char(a.exp_date,'dd/mm/yyyy')");
        $stm->execute();

        $tampilProd = $stm->fetchAll();
        // var_dump($result);
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
}


$retVal = (isset($_POST["ser3"])) ? $_POST["ser3"] : NULL;
tampilProduct($retVal);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.6">
    <title>Product</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .content>div {
            display: inline-block;
            /* margin-left: 10px; */
            margin-bottom: 35px;
            margin-right: 25px;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-primary">
        <a class="navbar-brand" href="index.php">UTS Ripal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="mt-2 ml-2"><a href="manage.php" class="text-warning"><i class="fa fa-user mr-1"></i><? if (isset($_COOKIE["username"])) {
                                                                                                                    echo "$_COOKIE[username]";
                                                                                                                } ?></a></li>
                <li class="mt-2 ml-3"><a class="text-danger" href="logout.php" onclick="return confirm('Yakin Mau Keluar?')"><i class="fa fa-sign-out text-danger"></i>keluar</a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="manage.php" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Manage</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">
                        <a class="dropdown-item" href="category.php">Category</a>
                        <a class="dropdown-item" href="manage.php">Products</a>
                    </div>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" method="POST">
                <input class="form-control mr-sm-2" name="ser3" type="text" placeholder="Search Products" aria-label="Search">
                <button class="btn btn-outline-light my-2 my-sm-0" name="serc" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <main role="main">

        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="jumbotron">
            <div class="container mt-3">

                <p><a class="btn btn-danger mt-3 btn-lg" onclick="return confirm('Yakin Logout?')" href="logout.php" role="button"><i class="fa fa-sign-out"></i>Logout</a>
                    <h3 class="text-primary">UTS Ripal CRUDS PHP</h3>
                </p>
            </div>
        </div>

        <div class="container">
            <h4>List Products</h4>

            <br>
            <!-- <hr class="mb-3"> -->
            <div class="content">
                <!-- Example row of columns -->
                <?php

                foreach ($tampilProd as $row) : ?>

                    <div class="card col-md-3" style="width: 15rem;">
                        <div class="text-center mt-3">
                            <img src="assets/images/<?= $row["image"]; ?>" alt="" width="200" height="200">
                        </div>
                        <!-- <img src="..." class="card-img-top" alt="..."> -->
                        <div class="card-body">
                            <h6 class="card-title">Nama Produk : <?= $row["nama_produk"]; ?></h6>
                            <p class="card-text">Harga : <?= $row["price"]; ?></p>
                            <p class="card-text">Exp Date : <?= $row["exp_date"]; ?></p>
                            <!-- <a href="#" class="btn btn-primary">Go somewhere</a> -->
                        </div>
                    </div>

                <?php endforeach; ?>


            </div> <!-- /container -->
        </div>

    </main>

    <footer class="container">
        <p>&copy; Company 2017-2019</p>
    </footer>
    <script src="assets/js/jquery-3.4.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>