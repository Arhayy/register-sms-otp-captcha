<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Verifikasi SMS OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- LINEARICONS -->
    <link rel="stylesheet" href="fonts/linearicons/style.css">

    <!-- STYLE CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="wrapper">
        <div class="inner">
            <img src="images/image-1.png" alt="" class="image-1">
            <form action="add-verifikasi.php" method="POST">
                <h3>Verifikasi SMS OTP</h3>
                <div class="form-holder">
                    <span class="lnr lnr-lock"></span>
                    <input type="text" class="form-control" name="kode" placeholder="Kode OTP">
                </div>
                <button type="submit" value="submit" name="submit">
                    <span>Send</span>
                </button>
            </form>
            <img src="images/image-2.png" alt="" class="image-2">
        </div>

    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/main.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</body>

</html>