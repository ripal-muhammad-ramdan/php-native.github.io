<?php
session_start();
include 'koneksi.php';
$conn;
$result;
// openKoneksi();
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
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/sweetalert/sweetalert.css">
    <script src="assets/sweetalert/sweetalert.min.js"></script>
    <script type="text/javascript" src="js/sweetalert.min.js"></script>
    <title>FORM</title>
</head>
<html>

<body>

</body>

</html>

<?php


function updateData($datapost, $dataget)
{
    global $conn;
    $id_prod = $dataget["products_id"];
    $ck = $_SESSION["username"];
    try {
        $stm = $conn->prepare("UPDATE products 
                                    SET nama_produk= :nama_produk,
                                        exp_date=:exp_date,
                                        update_date=current_date,
                                        update_by='$ck'
                                        
                                    WHERE products_id= '$id_prod'
                                ");

        $stm->bindParam('nama_produk', $datapost["nama_produk"], PDO::PARAM_STR);
        $stm->bindParam('exp_date', $datapost["exp_date"], PDO::PARAM_STR);
        $stm->execute();
        echo "<script type='text/javascript'>setTimeout(function(){
            swal({
                title: 'Berhasil! :)',
                text: 'Data Berhasil Di Update !',
                type: 'success',
                button: 'Ok',
                showConfirmButton: true
            });
        });window.setTimeout(function(){
            window.location.replace('manage.php');
        },3000);</script>";
    } catch (PDOException $e) {
        echo "<script>swal('Error!!', 'Data Gagal Diupdate!', 'error');</script>";
        // echo "Error : " . $e->getMessage();
    }
}

function getListData($data)
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
if (isset($_GET["products_id"])) {
    // echo $_GET["npm"];

    openKoneksi();

    getListData($_GET);

    if (isset($_POST["submit"])) {
        updateData($_POST, $_GET);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>FORM</title>
    <style>
        fieldset {
            margin-top: 100px;
        }
    </style>
</head>

<body>
    <fieldset>
        <h1 class="judul">Update Data</h1>
        <div class="container1">
            <form action="" method="POST">
                <table>
                    <tr>
                    <tr>
                        <td>Produk ID : </td>
                        <td><input type="text" name="products_id" value="<?= $row["products_id"]; ?>" id="nama" readonly></td>
                    </tr>
                    <td>Nama Produk : </td>
                    <td><input type="text" name="nama_produk" value="<?= $row["nama_produk"]; ?>" id="nama"></td>
                    </tr>
                    <tr>
                        <td>Exp Date : </td>
                        <td><input type="date" name="exp_date" value="<?= $row["exp_date"]; ?>" id="photo"></td>
                    </tr>
                </table>
                <input type="submit" class="btn btn-primary cariIn" name="submit" value="Update">
            </form>
        </div>

    </fieldset>
</body>

</html>