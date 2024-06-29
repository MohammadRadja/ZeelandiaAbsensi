<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zeelandia</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background: linear-gradient(to top, rgb(70, 130, 180), rgb(232, 218, 71));
            color: white;
        }
        .index {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .welcome-section {
            text-align: center;
            margin-bottom: 50px;
        }
        .welcome-section h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .welcome-section p {
            font-size: 1.2rem;
            margin-bottom: 40px;
        }
        .btn-login {
            font-size: 1.2rem;
            padding: 10px 30px;
            background-color: rgb(232, 218, 71); /* Warna kuning sesuai permintaan */
            border: none;
            color: black; /* Warna teks hitam agar kontras */
            border-radius: 5px;
            text-decoration: none; /* Menghapus garis bawah default pada link */
        }
        .btn-login:hover {
            background-color: rgba(232, 218, 71, 0.8); /* Efek transparansi saat hover */
        }
    </style>
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="icon" href="./assets/img/logo.png" type="image/png"> <!-- Favicon -->
</head>
<body class="index">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <img src="./assets/img/logo.png" alt="Zeelandia Logo" class="mb-4">
                <h1 class="mb-4">Selamat Datang di Zeelandia</h1>
                <p class="lead mb-4">Selamat datang di platform manajemen karyawan kami.</p>
                <a class="btn btn-login mb-3" href="view/loginView.php">Login</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
