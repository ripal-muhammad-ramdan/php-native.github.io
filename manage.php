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
    <title>Manage Product</title>

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
$tampil;
openKoneksi();
if (!isset($_SESSION["username"])) {
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'login.php';
    header("Location: http://$host$uri/$extra");
    exit;
}

function tampilKategori()
{
    global $conn;

    global $tampil;
    try {
        $stm = $conn->prepare("SELECT * FROM category ORDER BY category_id ASC");
        $stm->execute();

        $tampil = $stm->fetchAll();
        // var_dump($result);
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
}

function tampilDataProduct($search)
{
    global $conn;

    global $result;
    try {
        $stm = $conn->prepare("SELECT * FROM products WHERE LOWER(nama_produk) LIKE '%$search%' ORDER BY products_id ASC");
        $stm->execute();
        $result = $stm->fetchAll();
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
}



function image()
{
    $listEXT = ["jpg", "png", "jpeg"];
    $fname = $_FILES["upload"]["name"];
    $fileEXP = explode(".", $fname);
    $fileEXP = strtolower(end($fileEXP));
    //print_r($fileEXP); 
    $target_dir = "assets/images/";
    $target_file = $target_dir . basename($_FILES["upload"]["name"]);

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // die($imageFileType);
    if (in_array($fileEXP, $listEXT)) {
        if (file_exists($target_file)) {
            echo "Sorry, File already exist!";
            exit;
        } else {
            if (move_uploaded_file($_FILES["upload"]["tmp_name"], $target_file)) {
                //echo " The File " . basename($_FILES["upload"]["name"]) . " Has Been Uploaded!.";
            } else {
                echo "sorry, There Was An Error Uploading Your File!";
            }
        }
    }
    return $fname;
}

function insertData($data)
{
    global $conn;
    global $result;
    $upload = image();
    $se = $_SESSION["username"];

    try {
        $stm = $conn->prepare("INSERT INTO products(products_id,category_id,nama_produk,entry_date,exp_date,create_date,create_by,update_date,update_by,image)
        VALUES((select
   case
	when max(products_id)is null then 1
	else max(products_id)+1
	end from products
   ),:category_id,:nama_produk,:entry_date,:exp_date,current_date,'$se',current_date,null,'$upload')");


        $stm->bindParam('category_id', $data["category_id"], PDO::PARAM_STR);
        $stm->bindParam('nama_produk', $data["nama_produk"], PDO::PARAM_STR);
        $stm->bindParam('entry_date', $data["entry_date"], PDO::PARAM_STR);
        $stm->bindParam('exp_date', $data["exp_date"], PDO::PARAM_STR);
        //$stm->bindParam('foto', $data["foto"], PDO::PARAM_STR);

        $stm->execute();
        echo "<script type='text/javascript'>setTimeout(function(){
            swal({
                title: 'Berhasil! :)',
                text: 'Selamat, Produk Telah Ditambahkan!',
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
                text: 'Maaf, Produk Gagal Ditambahkan!',
                type: 'error',
                button: 'Ok',
                showConfirmButton: true
            });
        });</script>";
    }
}
openKoneksi();
tampilKategori($_POST);

$retVal = (isset($_POST["ser"])) ? $_POST["ser"] : NULL;
tampilDataProduct($retVal);

if (isset($_POST["submit"])) {
    openKoneksi();
    insertData($_POST);
    closeKoneksi();
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
                        <a class="dropdown-item" href="manage.php">Product</a>
                        <a class="dropdown-item" href="category.php">Category</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <main role="main" class="container ">

        <div class="my-5">
            <!-- <form action=""></form> -->
            <br>
            <h4>Manage Products</h4>
            <br>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link" href="category.php">Category</a>
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Products</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Products Details</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <br>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Produk</label>
                                <input type="text" name="nama_produk" class="form-control" placeholder="Nama Produk" id="inputEmail4">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Kategori</label>
                                <select id="inputState" name="category_id" class="form-control">
                                    <option selected>- Pilih Kategori - </option>
                                    <?php


                                    foreach ($tampil as $row) : ?>

                                        <option value="<?= $row["category_id"]; ?>"><?= $row["nama_kategori"]; ?></option>

                                    <?php endforeach; ?>

                                </select>
                                <!-- <input type="password" class="form-control" id="inputPassword4"> -->
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputCity">entry date</label>
                                <input type="date" name="entry_date" class="form-control" id="inputCity">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputState">exp date</label>
                                <input type="date" name="exp_date" class="form-control" id="inputCity">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">

                                <label for="exampleFormControlFile1">Gambar Produk</label>
                                <input type="file" name="upload" class="form-control-file" id="exampleFormControlFile1">

                            </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>

                    <!-- <br> -->
                    <hr>
                    <!-- <br> -->

                    <form action="" method="POST">
                        <div class="form-row ">
                            <div class="form-group col-md-4">
                                <input type="text" name="ser" class="form-control " placeholder="Search dengan huruf kecil" id="inputEmail4">
                                <input type="submit" class="btn btn-primary" name="btnSer" value="Cari">
                            </div>
                        </div>
                    </form>

                    <table class="table table-sm ">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Kategori ID</th>
                                <th scope="col">Nama_produk</th>
                                <th scope="col">Entry Date</th>
                                <th scope="col">Exp Date</th>
                                <th scope="col">Create_by</th>
                                <th scope="col">Update_by</th>
                                <th scope="col">Image</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($result as $row) : ?>
                                <tr>
                                    <td><?= $row["products_id"]; ?></td>
                                    <td><?= $row["category_id"]; ?></td>
                                    <td><?= $row["nama_produk"]; ?></td>
                                    <td><?= $row["entry_date"]; ?></td>
                                    <td><?= $row["exp_date"]; ?></td>
                                    <td><?= $row["create_by"]; ?></td>
                                    <td><?= $row["update_by"]; ?></td>
                                    <td><img src="assets/images/<?= $row["image"]; ?>" alt="" width="50px" height="30px"></td>
                                    <td><a class="btn btn-warning" href="prodDetail.php?products_id=<?= $row["products_id"]; ?>"><i class="fa fa-upload"></i> Choose </a> | <a class="btn btn-warning" href="update.php?products_id=<?= $row["products_id"]; ?>"><i class="fa fa-upload"></i> Update</a> | <a class="btn btn-danger" href="delete.php?products_id=<?= $row["products_id"]; ?>"><i class="fa fa-eraser"></i> Delete</a> </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade show" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <br>
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <p class="text-danger">Silahkan Kembali ke Product Dan Pilih CHOOSE Untuk Memasukan Detail Produk !</p>
                                <label for="inputPassword4">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control" id="inputCity" readonly>
                                <!-- <input type="password" class="form-control" id="inputPassword4"> -->
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputCity">Valid From</label>
                                <input type="date" name="valid_form" class="form-control" id="inputCity" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputState">Valid Until</label>
                                <input type="date" name="valid_until" class="form-control" id="inputCity" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputState">Update_by</label>
                                <input type="text" name="update_by" class="form-control" id="inputCity" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputState">Price</label>
                                <input type="number" name="price" placeholder="Harga" class="form-control" id="inputCity" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <input type="submit" name="submit" value="Submit" class="btn btn-primary" id="inputCity">
                            </div>
                        </div>

                </div>
                </form>

                <!-- <br> -->
                <hr>
                <!-- <br> -->

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