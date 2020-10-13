<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.6">
    <link rel="stylesheet" href="assets/sweetalert/sweetalert.css">
    <link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="assets/sweetalert/sweetalert.min.js"></script>
    <title>Manage Product Detail</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/js/bootstrap.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* .content>div {
            display: inline-block;
            /* margin-left: 10px; */
        /* margin-bottom: 35px;
        margin-right: 25px; */
        /* } */

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <!-- <link href="jumbotron.css" rel="stylesheet"> -->
</head>
<?php
session_start();
include 'koneksi.php';
$conn;
$result;
$tampilProd;
$tampilProdDetail;
openKoneksi();
if (!isset($_SESSION["username"])) {
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'login.php';
    header("Location: http://$host$uri/$extra");
    exit;
}
?>

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
    <title>Manage Product Detail</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/js/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <!-- <link href="jumbotron.css" rel="stylesheet"> -->
</head>
<?php

function tampilProdDetail()
{
    global $conn;

    global $tampilProdDetail;
    try {
        $stm = $conn->prepare("SELECT * FROM products_detail");
        $stm->execute();

        $tampilProdDetail = $stm->fetchAll();
        // var_dump($result);
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
}


function tampilNamaProd()
{
    global $conn;

    global $tampilProdDetail;
    try {
        $stm = $conn->prepare("SELECT * FROM products");
        $stm->execute();

        $tampilProdDetail = $stm->fetch();
        // var_dump($result);
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
}

function getListDataProdKat($data)
{
    global $conn;
    global $row;
    $prod = $data["products_id"];

    try {
        $stm = $conn->prepare("SELECT * FROM products WHERE products_id = '$prod'");
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        // print_r($row);
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
}

function insertProdDetail($data)
{
    global $conn;
    global $result;
    $ses = $_SESSION["username"];

    try {
        $stm = $conn->prepare("INSERT INTO products_detail(products_detail_id,products_id,valid_form,valid_until,create_date,create_by,update_date,update_by,price)
        VALUES((select
   case
	when max(products_detail_id)is null then 1
	else max(products_detail_id)+1
	end from products_detail
   ),:products_id,:valid_form,:valid_until,current_date,'$ses',current_date,null,:price)");


        $stm->bindParam('products_id', $data["products_id"], PDO::PARAM_STR);
        $stm->bindParam('valid_form', $data["valid_form"], PDO::PARAM_STR);
        $stm->bindParam('valid_until', $data["valid_until"], PDO::PARAM_STR);
        $stm->bindParam('price', $data["price"], PDO::PARAM_STR);
        //$stm->bindParam('foto', $data["foto"], PDO::PARAM_STR);

        $stm->execute();
        echo "<script type='text/javascript'>setTimeout(function(){
            swal({
                title: 'Berhasil! :)',
                text: 'Selamat, Produk Detail Telah Ditambahkan!',
                type: 'success',
                button: 'Ok',
                showConfirmButton: true
            });
        });window.setTimeout(function(){
            window.location.replace('manage.php');
        },3000);</script>";
        exit;
    } catch (PDOException $e) {
        echo "<script type='text/javascript'>setTimeout(function(){
            swal({
                title: 'Gagal! :(',
                text: 'Maaf, Produk Detail Gagal Ditambahkan!',
                type: 'error',
                button: 'Ok',
                showConfirmButton: true
            });
        });</script>";
    }
}

openKoneksi();
tampilProdDetail();

if (isset($_GET["products_id"])) {
    // echo $_GET["npm"];

    openKoneksi();

    getListDataProdKat($_GET);

    if (isset($_POST["submit"])) {
        openKoneksi();
        insertProdDetail($_POST);
        //closeKoneksi();
    }
}



?>

<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="index.php">UTS Ripal</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                <li class="mt-2 ml-2"><a href="manage.php"><i class="fa fa-user mr-1"></i><? if (isset($_COOKIE["username"])) {
                                                                                                echo "$_COOKIE[username]";
                                                                                            } ?></a></li>
                <li class="mt-2 ml-3"><a class="text-danger" href="logout.php" onclick="return confirm('Yakin Mau Keluar?')"><i class="fa fa-sign-out text-danger"></i>keluar</a></li>

                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Manage</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">
                        <a class="dropdown-item" href="category.php">Category</a>
                        <a class="dropdown-item" href="manage.php">Product</a>
                    </div>
                </li>
            </ul>

            <!-- <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="text" placeholder="Search Products" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form> -->
        </div>
    </nav>

    <main role="main" class="container ">

        <div class="my-5">
            <!-- <form action=""></form> -->
            <br>
            <h4>Manage Products Details</h4>
            <br>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link" href="manage.php">Products</a>
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Products Details</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">

                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                    </div>
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <form method="POST">

                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputPassword4">Nama Produk</label>
                                    <select id="inputState" name="products_id" class="form-control">

                                        <option value="<?= $row["products_id"]; ?>"><?= $row["nama_produk"]; ?></option>


                                    </select>
                                    <!-- <input type="password" class="form-control" id="inputPassword4"> -->
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputCity">Valid From</label>
                                    <input type="date" name="valid_form" class="form-control" id="inputCity">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="inputState">Valid Until</label>
                                    <input type="date" name="valid_until" class="form-control" id="inputCity">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="inputState">Price</label>
                                    <input type="number" name="price" placeholder="Harga" class="form-control" id="inputCity">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <input type="submit" name="submit" value="Submit" class="btn btn-primary" id="inputCity">
                                </div>
                            </div>
                            <table class="table table-sm ">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Produk Detail Id</th>
                                        <th scope="col">Product ID</th>
                                        <th scope="col">Valid Form</th>
                                        <th scope="col">Valid Untill</th>
                                        <th scope="col">Create_Date</th>
                                        <th scope="col">Create by</th>
                                        <th scope="col">Update Date</th>
                                        <th scope="col">Update by</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    foreach ($tampilProdDetail as $row) : ?>
                                        <tr>
                                            <td><?= $row["products_detail_id"]; ?></td>
                                            <td><?= $row["products_id"]; ?></td>
                                            <td><?= $row["valid_form"]; ?></td>
                                            <td><?= $row["valid_until"]; ?></td>
                                            <td><?= $row["create_date"]; ?></td>
                                            <td><?= $row["create_by"]; ?></td>
                                            <td><?= $row["update_date"]; ?></td>
                                            <td><?= $row["update_by"]; ?></td>
                                            <td><?= $row["price"]; ?></td>
                                            <td><a class="btn btn-warning" href="updateProd.php?products_detail_id=<?= $row["products_detail_id"]; ?>"><i class="fa fa-upload"></i> Update</a> | <a class="btn btn-danger" href="deleteProd.php?products_detail_id=<?= $row["products_detail_id"]; ?>"><i class="fa fa-eraser"></i> Delete</a> </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                    </div>
                    <!-- <button type="submit" name="submit" class="btn btn-primary">Submit</button> -->
                    </form>
                </div>

            </div>
            <br>
        </div>

    </main><!-- /.container -->

    <footer class="container">
        <p>&copy; Company 2017-2019</p>
    </footer>
    <script src="assets/js/jquery-3.4.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>