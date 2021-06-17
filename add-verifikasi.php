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
    $kode = $_POST['kode'];
    $telepon = $_COOKIE['telepon'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.thebigbox.id/sms-otp/1.0.0/otp/API-KEY-ANDA",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n  \"maxAttempt\" : \"3\",\n  \"otpstr\" : \"$kode\",\n  \"expireIn\" : \"300\",\n  \"digit\" : \"5\"\n\n}",
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
        setcookie("telepon", "", time() + (60 * 60 * 24 * 5), '/');
        $query = "UPDATE tb_login SET status = '1' WHERE no_hp = '$telepon'";
        $exe = mysqli_query($koneksi, $query);
        if ($exe) {
            echo "<script>
			Swal.fire({
				allowEnterKey: false,
				allowOutsideClick: false,
				icon: 'success',
				title: 'Pemberitahuan',
				text: 'Pendaftaran berhasil'
			}).then(function() {
				window.location.href='login.php'
			});
			</script>";
        } else {
            echo "<script>
			Swal.fire({
				allowEnterKey: false,
				allowOutsideClick: false,
				icon: 'error',
				title: 'Peringatan',
				text: 'Gagal melakukan pendaftaran, mohon untuk mengisi kembali'
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
			text: 'Kode OTP yang Anda masukkan salah'
		}).then(function() {
			window.location.href='add-verifikasi.php';
		});
		</script>";
    }
    ?>
</body>

</html>