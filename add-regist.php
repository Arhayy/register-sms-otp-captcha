<?php $koneksi = new mysqli("localhost", "root", "", "sms-otp") ?>
<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Verifikasi Regist</title>
	<script src="alert/dist/sweetalert2.all.min.js"></script>
	<script src="alert/dist/sweetalert2.min.js"></script>
	<link rel="stylesheet" href="alert/dist/sweetalert2.min.css">
</head>

<body>
	<?php
	date_default_timezone_set('Asia/Jakarta');

	$nama_lengkap = $_POST['nama_lengkap'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$password_konfirmasi = $_POST['password_konfirmasi'];
	$no_hp = $_POST['no_hp'];
	$status = '0';

	$cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_login WHERE username = '$username'"));
	$cek2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_login WHERE no_hp = '$no_hp'"));

	$password_acak = password_hash($password_konfirmasi, PASSWORD_DEFAULT);

	$secret_key = "SECRET-KEY-GOOGLE-CAPTCHA-ANDA";
	$verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $_POST['g-recaptcha-response']);
	$response = json_decode($verify);


	if ($response->success) {
		if ($cek > 0) {
			echo "<script>
			Swal.fire({
				allowEnterKey: false,
				allowOutsideClick: false,
				icon: 'error',
				title: 'Peringatan',
				text: 'Username sudah digunakan'
				}).then(function() {
					window.location.href='regist.php';
				});
				</script>";
		} else if ($cek2 > 0) {
			echo "<script>
			Swal.fire({
				allowEnterKey: false,
				allowOutsideClick: false,
				icon: 'error',
				title: 'Peringatan',
				text: 'Nomor Telepon sudah digunakan'
				}).then(function() {
					window.location.href='regist.php';
				});
				</script>";
		} else if ($password != $password_konfirmasi) {
			echo "<script>
			Swal.fire({
				allowEnterKey: false,
				allowOutsideClick: false,
				icon: 'error',
				title: 'Peringatan',
				text: 'Kata Sandi yang Anda masukkan tidak cocok'
			}).then(function() {
				window.location.href='regist.php';
			});
			</script>";
		} else {
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://api.thebigbox.id/sms-otp/1.0.0/otp/API-KEY-ANDA",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "PUT",
				CURLOPT_POSTFIELDS => "{\n  \"maxAttempt\" : \"3\",\n  \"phoneNum\" : \"$no_hp\",\n  \"expireIn\" : \"300\",\n  \"digit\" : \"5\"\n\n}",
				CURLOPT_HTTPHEADER => array(
					"content-type: application/json",
					"x-api-key: API-KEY-ANDA"
				),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			$response2 = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
			if ($response2 == 200) {
				$query = "INSERT INTO tb_login(nama_lengkap, username, password, no_hp, status) VALUES ('$nama_lengkap', '$username', '$password_acak', '$no_hp', '$status')";
				$exe = mysqli_query($koneksi, $query);
				if ($exe) {
					setcookie('telepon', $no_hp, time() + (60 * 60 * 24 * 5), '/');
					echo "<script>
					Swal.fire({
						allowEnterKey: false,
						allowOutsideClick: false,
						icon: 'success',
						title: 'Pemberitahuan',
						text: 'Kode verifikasi berhasil terkirim ke nomor' + ' $no_hp'
					}).then(function() {
						window.location.href='verifikasi-regist.php'
					});
					</script>";
				} else {
					echo "<script>
					Swal.fire({
						allowEnterKey: false,
						allowOutsideClick: false,
						icon: 'error',
						title: 'Peringatan',
						text: 'Gagal melakukan pendaftaran'
					}).then(function() {
						window.location.href='regist.php';
					});
					</script>";
				}
			} else {
				echo "<script>
				Swal.fire({
					allowEnterKey: false,
					allowOutsideClick: false,
					icon: 'error',
					title: 'Peringatan',
					text: 'Gagal melakukan pendaftaran'
				}).then(function() {
					window.location.href='regist.php';
				});
				</script>";
			}
		}
	} else {
		echo "<script>
		Swal.fire({
			allowEnterKey: false,
			allowOutsideClick: false,
			icon: 'error',
			title: 'Peringatan',
			text: 'Anda bukan manusia'
		}).then(function() {
			window.location.href='regist.php';
		});
		</script>";
	}

	?>
</body>

</html>