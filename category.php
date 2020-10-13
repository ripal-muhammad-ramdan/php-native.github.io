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
    <title>Manage Category</title>

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
$tmpl;
openKoneksi();
if (!isset($_SESSION["username"])) {
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'login.php';
    header("Location: http://$host$uri/$extra");
    exit;
}

function insertDataCat($data)
{
    global $conn;
    global $result;
    $on = $_SESSION["username"];

    try {
        $stm = $conn->prepare("INSERT INTO category(category_id,nama_kategori,create_date,create_by,update_date,update_by)
        VALUES((select
   case
	when max(category_id)is null then 1
	else max(category_id)+1
	end from category
   ),:nama_kategori,current_date,'$on',current_date,null)");


        $stm->bindParam('nama_kategori', $data["nama_kategori"], PDO::PARAM_STR);
        //$stm->bindParam('foto', $data["foto"], PDO::PARAM_STR);

        $stm->execute();
        echo "<script type='text/javascript'>setTimeout(function(){
            swal({
                title: 'Berhasil! :)',
                text: 'Selamat, Kategori Telah Ditambahkan!',
                type: 'success',
                button: 'Ok',
                showConfirmButton: true
            });
        });window.setTimeout(function(){
            window.location.replace('category.php');
        },3000);</script>";
        exit;
    } catch (PDOException $e) {
        echo "Error" . $e->getMessage();
    }
}

function tmpilCat()
{
    global $conn;
    global $tmpl;
    try {
        $stm = $conn->prepare("SELECT * FROM category");
        $stm->execute();
        $tmpl = $stm->fetchAll();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

openKoneksi();
tmpilCat($_POST);

if (isset($_POST["submit"])) {
    openKoneksi();
    insertDataCat($_POST);
}


?>


<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="index.php">UTSKU</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                <li class="mt-2 ml-2"><a href="manage.php"><i class="fa fa-user mr-1"></i>
                        <? if (isset($_COOKIE["username"])) {
                                                                                                echo "$_COOKIE[username]";
                                                                                            } ?></a></li>
                <li class="mt-2 ml-3"><a class="text-danger" href="logout.php" onclick="return confirm('Yakin Mau Keluar?')"><i class="fa fa-sign-out text-danger"></i>keluar</a></li>

                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Manage</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">
                        <a class="dropdown-item" href="category.php">Category</a>
                        <a class="dropdown-item" href="manage.php">Products</a>
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
            <h4>Manage Category</h4>
            <br>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Category</a>
                    <a class="nav-item nav-link" href="manage.php">Product</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <br>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Nama Kategori</label>
                                <input type="text" name="nama_kategori" class="form-control" placeholder="Nama Kategori" id="inputEmail4">
                            </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>

                    <!-- <br> -->
                    <hr>
                    <!-- <br> -->

                    <div class="form-row ">
                        <div class="form-group col-md-4">
                            <input type="text" name="key" id="live" class="form-control " placeholder="Search Keyword" id="inputEmail4">
                        </div>
                    </div>

                    <table class="table table-sm ">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Kategori ID</th>
                                <th scope="col">Nama Kategori</th>
                                <th scope="col">Create_Date</th>
                                <th scope="col">Create_by</th>
                                <th scope="col">Update_Date</th>
                                <th scope="col">Update_by</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($tmpl as $row) : ?>
                                <tr>
                                    <td><?= $row["category_id"]; ?></td>
                                    <td><?= $row["nama_kategori"]; ?></td>
                                    <td><?= $row["create_date"]; ?></td>
                                    <td><?= $row["create_by"]; ?></td>
                                    <td><?= $row["update_date"]; ?></td>
                                    <td><?= $row["update_by"]; ?></td>
                                    <td><a class="btn btn-warning" href="updateCat.php?category_id=<?= $row["category_id"]; ?>"><i class="fa fa-upload"></i> Update</a> | <a class="btn btn-danger" href="deleteCat.php?category_id=<?= $row["category_id"]; ?>"><i class="fa fa-eraser"></i> Delete</a> </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

                </div> -->

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