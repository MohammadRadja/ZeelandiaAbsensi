<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <section class="forgot-form">
		<div class="container ">
			<div class="row justify-content-center">
				<div class="card-wrapper">
					<div class="brand text-center mt-5">
						<img src="../assets/img/logo_login.png" alt="logo" >
					</div>
                    <div class="information text-center mt-5" style="color:#000;">
                        <img src="../assets/img/lock.png" alt="logo" >
                        <p class="fs-5 fw-bold">Enter your email, phone, or username and we'll send you a link to change a new password</p>
                    </div>
                    <form method="POST" class="my-login-validation"  novalidate="">
                        <div class="form-group" style="margin-top: 100px;">
                            <input id="email" type="email" class="form-control" name="email" value="" placeholder="ID Karyawan" required autofocus>
                        </div>
                        <div class="form-group text-center mt-4">
                            <button style="width: 100%;" type="submit" class="btn btn-primary btn-block">
                                Log in
                            </button>
                        </div>
                    </form>

					<div class="footer m-2">
						<div class="mt-5 text-center">
                            Don't have an account? <a href="registerView.php">Sign Up</a>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>