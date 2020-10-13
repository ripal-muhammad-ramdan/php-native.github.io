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


function updateDataCat($datapost, $dataget)
{
    global $conn;
    $id_prod = $dataget["category_id"];
    $sc = $_SESSION["username"];
    try {
        $stm = $conn->prepare("UPDATE category
                                    SET nama_kategori= :nama_kategori,
                                        update_date=current_date,
                                        update_by='$sc'
                                        
                                    WHERE category_id= '$id_prod'
                                ");

        $stm->bindParam('nama_kategori', $datapost["nama_kategori"], PDO::PARAM_STR);
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
            window.location.replace('category.php');
        },3000);</script>";
    } catch (PDOException $e) {
        echo "<script>swal('Error!!', 'Data Gagal Diupdate!', 'error');</script>";
        // echo "Error : " . $e->getMessage();
    }
}

function getListDataCat($data)
{
    global $conn;
    global $row;
    $prod = $data["category_id"];

    try {
        $stm = $conn->prepare("SELECT * FROM category WHERE category_id = '$prod'");
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        // print_r($row);
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
}
if (isset($_GET["category_id"])) {
    // echo $_GET["npm"];

    openKoneksi();

    getListDataCat($_GET);

    if (isset($_POST["submit"])) {
        updateDataCat($_POST, $_GET);
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
        <h1 class="judul">Update Data Kategori</h1>
        <div class="container1">
            <form action="" method="POST">
                <table>
                    <tr>
                    <tr>
                        <td>Kategori ID : </td>
                        <td><input type="text" name="category_id" value="<?= $row["category_id"]; ?>" id="nama" readonly></td>
                    </tr>
                    <td>Nama Kategori : </td>
                    <td><input type="text" name="nama_kategori" value="<?= $row["nama_kategori"]; ?>" id="nama"></td>
                    </tr>
                </table>
                <input type="submit" class="btn btn-primary cariIn" name="submit" value="Update">
                <a href="category.php">Kembali</a>
            </form>
        </div>

    </fieldset>
</body>

</html>