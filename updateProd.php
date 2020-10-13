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


function updateDataProd($datapost, $dataget)
{
    global $conn;
    $id_prod = $dataget["products_detail_id"];
    $cs = $_SESSION["username"];
    try {
        $stm = $conn->prepare("UPDATE products_detail
                                    SET valid_until= :valid_until,
                                        update_date=current_date,
                                        update_by='$cs',
                                        price=:price
                                        
                                    WHERE products_detail_id= '$id_prod'
                                ");

        $stm->bindParam('valid_until', $datapost["valid_until"], PDO::PARAM_STR);
        $stm->bindParam('price', $datapost["price"], PDO::PARAM_STR);
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

function getListDataProd($data)
{
    global $conn;
    global $row;
    $prod = $data["products_detail_id"];

    try {
        $stm = $conn->prepare("SELECT * FROM products_detail WHERE products_detail_id = '$prod'");
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        // print_r($row);
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
}
if (isset($_GET["products_detail_id"])) {
    // echo $_GET["npm"];

    openKoneksi();

    getListDataProd($_GET);

    if (isset($_POST["submit"])) {
        updateDataProd($_POST, $_GET);
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
                        <td><input type="text" name="products_detail_id" value="<?= $row["products_detail_id"]; ?>" id="nama" readonly></td>
                    </tr>
                    <td>valid until : </td>
                    <td><input type="text" name="valid_until" value="<?= $row["valid_until"]; ?>" id="nama"></td>
                    </tr>
                    <tr>
                        <td>Price : </td>
                        <td><input type="number" name="price" value="<?= $row["price"]; ?>" id="photo"></td>
                    </tr>
                </table>
                <input type="submit" class="btn btn-primary cariIn" name="submit" value="Update">
            </form>
        </div>

    </fieldset>
</body>

</html>