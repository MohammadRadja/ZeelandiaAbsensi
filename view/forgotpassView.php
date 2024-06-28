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
                        <p class="fs-5 fw-bold">Enter your email, phone, or username and we'll send you a link to change a new password</p>
                    </div>
                    <?php
                    // Pastikan Anda memeriksa URL parameter yang sesuai
                    $success = isset($_GET['success']) ? $_GET['success'] : false;
                    $error_message = isset($_GET['error_message']) ? $_GET['error_message'] : '';
                    $newPasswordInfo = isset($_GET['newPasswordInfo']) ? json_decode($_GET['newPasswordInfo'], true) : null;

                    if ($success && $newPasswordInfo) {
                        // Tampilkan modal jika berhasil reset password dan ada informasi password baru
                        echo '<div id="successModal" class="modal fade" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">';
                        echo '<div class="modal-dialog modal-dialog-centered">';
                        echo '<div class="modal-content">';
                        echo '<div class="modal-header">';
                        echo '<h5 class="modal-title" id="successModalLabel">Password Reset Successful!</h5>';
                        echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                        echo '</div>';
                        echo '<div class="modal-body">';
                        echo '<p>New password generated and sent to your email.</p>';
                        // Tampilkan informasi password baru
                        echo '<p>New Password Details:</p>';
                        echo '<ul>';
                        echo '<li>ID Karyawan: ' . htmlspecialchars($newPasswordInfo['IDKaryawan']) . '</li>';
                        echo '<li>Username: ' . htmlspecialchars($newPasswordInfo['Username']) . '</li>';
                        echo '<li>New Password: ' . htmlspecialchars($newPasswordInfo['TemporaryPassword']) . '</li>';
                        echo '</ul>';
                        echo '</div>';
                        echo '<div class="modal-footer">';
                        echo '<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    } elseif (!empty($error_message)) {
                        // Tampilkan pesan error jika terdapat error_message
                        echo '<div class="alert alert-danger mt-3">' . $error_message . '</div>';
                    }
                    ?>

                    <form id="forgotPassForm" method="POST" action="../controllers/forgotpassController.php" class="my-login-validation" novalidate="">
                        <div class="form-group">
                            <input id="ForgotPass" type="text" class="form-control" name="ForgotPass" value="" placeholder="ID Karyawan, Email, or Username" required autofocus>
                        </div>
                        <div class="form-group text-center">
                            <button style="width: 100%;" type="submit" class="btn btn-primary btn-block" id="resetPasswordButton">
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

    <!-- Script to show modal on success -->
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
                    var myModal = new bootstrap.Modal(document.getElementById('successModal'));
                    myModal.show();
                    // Clear form after successful submission
                    form.reset();
                } else {
                    // Show error message if any
                    var errorMessage = data.error_message || 'Password reset failed.';
                    alert(errorMessage); // Replace with modal or better UI for error handling
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
