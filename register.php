<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Register</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/fonts/linearicons/style.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/sweetalert/sweetalert.css">
	<script src="assets/sweetalert/sweetalert.min.js"></script>
</head>

<?php
include 'koneksi.php';

function register($data)
{
	global $conn;

	try {

		$uname = $data["uname"];
		$nope = $data["no_pe"];
		$email = $data["email"];
		$pwd = $data["pwd"];
		$name = $data["name"];

		$stm = $conn->prepare("INSERT INTO users (user_id,username,email,nope,password,create_date,name) 
		VALUES ((select case
		when max(user_id)is null then 1
		else max(user_id)+1 end from users),:username,:email,:nope,:password,current_date,:name)");

		$stm->bindParam('username', $uname, PDO::PARAM_STR);
		$stm->bindParam('email', $email, PDO::PARAM_STR);
		$stm->bindParam('nope', $nope, PDO::PARAM_STR);
		$stm->bindParam('password', $pwd, PDO::PARAM_STR);
		$stm->bindParam('name', $name, PDO::PARAM_STR);

		if ($name == '') {
			echo "<script type='text/javascript'>setTimeout(function(){
				swal({
					title: 'Gagal! :(',
					text: 'Name Tidak Boleh Kosong!',
					type: 'error',
					button: 'Ok',
					showConfirmButton: true
				});
			});</script>";
		} elseif ($uname == '') {
			echo "<script type='text/javascript'>setTimeout(function(){
				swal({
					title: 'Gagal! :(',
					text: 'Username Tidak Boleh Kosong!',
					type: 'error',
					button: 'Ok',
					showConfirmButton: true
				});
			});</script>";
		} elseif ($nope == '') {
			echo "<script type='text/javascript'>setTimeout(function(){
				swal({
					title: 'Gagal! :(',
					text: 'No HP Tidak Boleh Kosong!',
					type: 'error',
					button: 'Ok',
					showConfirmButton: true
				});
			});</script>";
		} elseif ($email == '') {
			echo "<script type='text/javascript'>setTimeout(function(){
				swal({
					title: 'Gagal! :(',
					text: 'Email Tidak Boleh Kosong!',
					type: 'error',
					button: 'Ok',
					showConfirmButton: true
				});
			});</script>";
		} elseif ($pwd == '') {
			echo "<script type='text/javascript'>setTimeout(function(){
				swal({
					title: 'Gagal! :(',
					text: 'Password Tidak Boleh Kosong!',
					type: 'error',
					button: 'Ok',
					showConfirmButton: true
				});
			});</script>";
		} else {
			$stm->execute();
			echo "<script type='text/javascript'>setTimeout(function(){
				swal({
					title: 'Berhasil! :)',
					text: 'Selamat, Anda Berhasil Mendaftar!',
					type: 'success',
					button: 'Ok',
					timer: 3000,
					showConfirmButton: true
				});
			},10);window.setTimeout(function(){
				window.location.replace('login.php');
			},3000);</script>";
		}
	} catch (PDOException $e) {
		echo "<script type='text/javascript'>setTimeout(function(){
			swal({
				title: Gagal Query!;,
				text: 'Gagal!',
				type: 'error',
				button: 'Ok',
				showConfirmButton: true
			});
		});</script>";
	}
}

if (isset($_POST["submit"])) {
	openKoneksi();
	register($_POST);
	closeKoneksi();
}
?>



<body>
	<div class="wrapper">
		<div class="inner">
			<img src="assets/images/image-1.png" alt="" class="image-1">
			<form action="" method="POST">
				<h3>Akun Baru?</h3>
				<div class="form-holder">
					<span class="lnr lnr-users"></span>
					<input type="text" class="form-control" name="name" placeholder="Name">
				</div>
				<div class="form-holder">
					<span class="lnr lnr-user"></span>
					<input type="text" class="form-control" name="uname" placeholder="Username">
				</div>
				<div class="form-holder">
					<span class="lnr lnr-envelope"></span>
					<input type="email" class="form-control" name="email" placeholder="Email">
				</div>
				<div class="form-holder">
					<span class="lnr lnr-phone-handset"></span>
					<input type="text" class="form-control" name="no_pe" placeholder="Nomor Telepon">
				</div>
				<div class="form-holder">
					<span class="lnr lnr-lock"></span>
					<input type="password" class="form-control" name="pwd" placeholder="Password">
				</div>

				<button type="submit" name="submit">
					<span>Daftar</span>
				</button>
			</form>
			<img src="assets/images/image-2.png" alt="" class="image-2">
		</div>

	</div>

	<script src="assets/js/jquery-3.3.1.min.js"></script>
	<script src="assets/js/main.js"></script>
</body>

</html>