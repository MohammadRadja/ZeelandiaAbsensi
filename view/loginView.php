<?php
session_start();

// Ambil pesan error dari session jika ada
$errors = isset($_SESSION['login_errors']) ? $_SESSION['login_errors'] : array();

// Hapus session errors setelah ditampilkan
unset($_SESSION['login_errors']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/img/logo.png" type="image/png"> <!-- Favicon -->
</head>
<body>
    <section class="login-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="card-wrapper">
                    <div class="brand text-center mt-5">
                        <img src="../assets/img/logo.png" alt="logo">
                    </div>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="../controllers/authController.php" class="my-login-validation" novalidate>
                        <div class="form-group m-4">
                            <input id="IDKaryawan" type="text" class="form-control <?php echo isset($errors['IDKaryawan']) ? 'is-invalid' : ''; ?>" name="IDKaryawan" value="" placeholder="ID Karyawan" required autofocus>
                            <?php if (isset($errors['IDKaryawan'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['IDKaryawan']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group m-4">
                            <input id="Password" type="password" class="form-control <?php echo isset($errors['Password']) ? 'is-invalid' : ''; ?>" name="Password" placeholder="Password" required data-eye>
                            <?php if (isset($errors['Password'])): ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['Password']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="form-group text-center m-4">
                            <button style="width: 100%;" type="submit" name="login" class="btn btn-primary btn-block">
                                Log in
                            </button>
                            <span><a href="../view/forgotpassView.php">Forgot Password ?</a></span>
                        </div>
                    </form>
                    <div class="footer">
                        <div class="mt-4 text-center">
                            Don't have an account? <a href="../view/registerView.php">Sign Up</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
