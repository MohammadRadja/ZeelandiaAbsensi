<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/img/logo.png" type="image/png"> <!-- Favicon -->
</head>
<body>
    <section class="forgot-form">
        <div class="container">
            <div class="row justify-content-center">
                <div class="card-wrapper col-lg-6 col-md-8 col-10">
                    <div class="brand text-center mt-5">
                        <img src="../assets/img/logo_login.png" alt="logo">
                    </div>
                    <div class="information text-center mt-5" style="color:#000;">
                        <img src="../assets/img/lock.png" alt="logo">
                        <p class="fs-5 fw-bold">Masukkan email, telepon, atau nama pengguna Anda dan kami akan mengirimkan kata sandi baru</p>
                    </div>
                    <form id="forgotPassForm" method="POST" action="../controllers/forgotpassController.php" class="my-login-validation">
                        <div class="form-group">
                            <input id="ForgotPass" type="text" class="form-control" name="ForgotPass" value="" placeholder="ID Karyawan, Email, or Username" required>
                        </div>
                        <div class="form-group">
                            <input id="ForgotPass" type="password" class="form-control" name="newPassword" placeholder="Password Baru" required >
                        </div>
                        <div class="form-group text-center">
                            <button style="width: 100%;" type="submit" class="btn btn-primary" id="resetPasswordButton">
                                Reset Password
                            </button>
                        </div>
                    </form>
                    <div class="footer text-center">
                        <div class="mt-4">
                            Already have an account? <a href="../view/loginView.php">Log In</a>
                        </div>
                        <div class="mt-4">
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

    <!-- Script to handle form submission and response -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('forgotPassForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent form submission
            var form = this;
            var formData = new FormData(form);

            // Submit form using fetch
            fetch(form.action, {
                method: form.method,
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Show success message
                    form.reset();
                } else {
                    alert(data.error_message); // Show error message
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
    </script>
</body>
</html>
