<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="assets/images/icons/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="assets/vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="assets/css/main.css">
	<link rel="stylesheet" href="assets/sweetalert/sweetalert.css">
	<script src="assets/sweetalert/sweetalert.min.js"></script>
</head>

<?php
session_start();
include 'koneksi.php';
$conn;
$result;
function login($email, $pass)
{
	global $conn;
	global $result;
	try {
		$stm = $conn->prepare("SELECT COUNT(1) as login 
		FROM users WHERE username = '$email' and 
		password = '$pass'");
		$stm->execute();
		$result = $stm->fetch();
	} catch (PDOException $e) {
		echo "Error : " . $e->getMessage();
	}
	return $result["login"];
}

openKoneksi();

if (isset($_COOKIE["username"]) && isset($_COOKIE["pwd"])) {

	$username = $_COOKIE["username"];
	$password = $_COOKIE["pwd"];
	$counter = 0;

	$counter = login($username, $password);
	if ($counter > 0) {
		$_SESSION["username"] = $_COOKIE["username"];
		echo "<script type='text/javascript'>setTimeout(function(){
			swal({
				title: 'Session Masih Ada! :)',
				text: 'Silahkan Logout Dulu :)',
				type: 'success',
				button: 'Ok',
				showConfirmButton: true
			});
		});window.setTimeout(function(){
			window.location.replace('index.php');
		},3000);</script>";
		exit;
	} else {
		echo "<script>document.location.href ='login.php'</script>";
	}
}

if (isset($_POST["submit"])) {
	openKoneksi();
	$counter = 0;
	$username = $_POST["username"];
	$password = $_POST["pwd"];

	if (isset($_POST["cek"])) {
		setcookie("username", $username, time() + (86400 * 30));
		setcookie("pwd", $password, time() + (86400 * 30));

		$counter = login($username, $password);
		if ($counter > 0) {
			$_SESSION["username"] = $username;
			$_SESSION["pwd"] = $password;

			echo "<script type='text/javascript'>setTimeout(function(){
				swal({
					title: 'Berhasil! :)',
					text: 'Selamat, Anda Berhasil Masuk Dengan Cookie & Session!',
					type: 'success',
					button: 'Ok',
					showConfirmButton: true
				});
			});window.setTimeout(function(){
				window.location.replace('index.php');
			},3000);</script>";
		} else {
			echo "<script type='text/javascript'>setTimeout(function(){
				swal({
					title: 'Gagal! :(',
					text: 'Username Atau Password Salah!',
					type: 'error',
					button: 'Ok',
					showConfirmButton: true
				});
			});</script>";
		}
	} else {
		echo "<script type='text/javascript'>setTimeout(function(){
				swal({
					title: 'Gagal! :(',
					text: 'Pastikan Checkbox Di Centang!',
					type: 'error',
					button: 'Ok',
					showConfirmButton: true
				});
			});window.setTimeout(function(){
				window.location.replace('index.php');
			},3000);</script>";
	}
}

?>


<body>

	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="POST">
					<span class="login100-form-title p-b-26">
						Selamat Datang
					</span>
					<span class="login100-form-title p-b-48">
						<i class="fa fa-sign-in"></i>
					</span>

					<div class="wrap-input100 validate-input" data-validate="Contoh : abc@gmail.com">
						<input class="input100" type="text" name="username">
						<span class="focus-input100" data-placeholder="Username"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Masukan password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="pwd">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>
					<input type="checkbox" name="cek" id="cek"> Ingat Saya
					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" type="submit" name="submit">
								Masuk
							</button>
						</div>
					</div>

					<div class="text-center p-t-115">
						<span class="txt1">
							Belum Punya Akun?
						</span>
						<a class="txt2" href="register.php">
							Daftar
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div id="dropDownSelect1"></div>

	<script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="assets/vendor/animsition/js/animsition.min.js"></script>
	<script src="assets/vendor/bootstrap/js/popper.js"></script>
	<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/vendor/select2/select2.min.js"></script>
	<script src="assets/vendor/daterangepicker/moment.min.js"></script>
	<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>
	<script src="assets/vendor/countdowntime/countdowntime.js"></script>
	<script src="assets/js/main.js"></script>

</body>

</html>