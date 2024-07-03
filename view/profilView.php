<?php include('../template/header.php'); ?>
<?php include('../controllers/profilController.php')?>
<?php
// Periksa apakah sesi login ada
if (!isset($_SESSION['IDKaryawan'])) {
// Jika tidak ada sesi login, arahkan ke halaman login
header("Location: ../view/loginView.php");
exit();
}
?>
<div class="container pt-5">
<section class="profil">
        <h2 class="bg-warning p-1 text-center">PROFIL</h2>
        <div class="foto-profil text-center">
            <img src="<?php echo htmlspecialchars($currentFoto); ?>" alt="Profile Picture" class="rounded-circle" style="width: 150px; height: 150px;">
        </div>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger mt-3">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        } elseif (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success mt-3">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        ?>
        <form class="edit-profil mt-3" action="../controllers/profilController.php" method="POST">
            <div class="mb-3 row">
                <label for="inputNama" class="col-sm-2 col-form-label">Nama :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputNama" name="inputNama" placeholder="Nama" value="<?php echo $currentNama; ?>">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputJabatan" class="col-sm-2 col-form-label">Jabatan :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputJabatan" name="inputJabatan" value="<?php echo htmlspecialchars($currentJabatan); ?>" placeholder="Jabatan" readonly>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputNIK" class="col-sm-2 col-form-label">NIK :</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="inputNIK" name="inputNIK" value="<?php echo htmlspecialchars($currentNIK); ?>" placeholder="NIK">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputAlamat" class="col-sm-2 col-form-label">Alamat :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputAlamat" name="inputAlamat" value="<?php echo htmlspecialchars($currentAlamat); ?>" placeholder="Alamat">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputEmail" class="col-sm-2 col-form-label">Email :</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="inputEmail" name="inputEmail" value="<?php echo htmlspecialchars($currentEmail); ?>" placeholder="Email">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputNoTelp" class="col-sm-2 col-form-label">Nomer HP :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputNoTelp" name="inputNoTelp" value="<?php echo htmlspecialchars($currentNoTelp); ?>" placeholder="Nomer HP">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputTanggalBergabung" class="col-sm-2 col-form-label">Tanggal Bergabung :</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" id="inputTanggalBergabung" name="inputTanggalBergabung" value="<?php echo htmlspecialchars($currentTanggalBergabung); ?>">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputMasaKerja" class="col-sm-2 col-form-label">Masa Kerja :</label>
                <div class="col-sm-2">
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputMasaKerja" name="inputMasaKerja" placeholder="Masa Kerja" value="<?php echo htmlspecialchars($currentMasaKerja); ?>">
                        <span class="input-group-text">Tahun</span>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label">Ubah sandi :</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Ubah sandi">
                </div>
            </div>
            <!-- <div class="mb-3 row">
                <label for="inputFotoProfil" class="col-sm-2 col-form-label">Foto Profil :</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" id="inputFotoProfil" name="inputFotoProfil" accept="image/*">
                </div>
            </div> -->
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-warning">Edit Profil</button>
            </div>
        </form>
    </div>
</section>
<?php include('../template/footer.php'); ?> 