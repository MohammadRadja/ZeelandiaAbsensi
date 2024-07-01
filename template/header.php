<?php
session_start();
$userID = isset($_SESSION['IDKaryawan']) ? $_SESSION['IDKaryawan'] : '';
$jabatan = isset($_SESSION['jabatan']) ? $_SESSION['jabatan'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zeelandia</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/img/logo.png" type="image/png"> <!-- Favicon -->
</head>
<body class="profil">
    <nav class="navbar navbar-expand-lg navbar-light bg-warning">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../assets/img/logoNavbar.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarNav">
                <ul class="navbar-nav ms-auto">
                <?php if ($jabatan == 'karyawan' || $jabatan == 'admin') { ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="dashboardView.php">DASHBOARD</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profilView.php">PROFILE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pengajuanView.php">PENGAJUAN CUTI</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="statusView.php">STATUS PENGAJUAN</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">LOG OUT</a>
                        </li>
                    <?php } elseif ($jabatan == 'hrd' || $jabatan == 'manager') { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profilView.php">PROFILE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="statusView.php">STATUS PENGAJUAN</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="laporanView.php">LAPORAN CUTI</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">LOG OUT</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="../controllers/authController.php?action=logout" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap and Popper.js at the end of the body -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-Jr59q2G8BYV5lYNUq3n+scLBU5pr2LlY8A7Vr9+I4U8WeBQFq1wePfmFk5XsyCI4" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGfrmHz6kPFEJ+zB91T74HUb5sB2mrSw1xczPz0iG6OCE5g0lSCk2K0yfkF" crossorigin="anonymous"></script>
</body>
</html>
