<?php
include('../template/header.php'); 
include('../controllers/profilController.php');

// Periksa apakah sesi login ada
if (!isset($_SESSION['IDKaryawan'])) {
    // Jika tidak ada sesi login, arahkan ke halaman login
    header("Location: ../view/loginView.php");
    exit();
}
?>
<section class="profil">
    <div class="container pt-5">
        <h2 class="bg-warning p-1">PENGAJUAN CUTI</h2>
        <?php
        // Tampilkan pesan error jika ada
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        
        // Tampilkan pesan sukses jika ada
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        ?>
        <form class="edit-profil mt-3" id="cutiForm" action="../controllers/pengajuanController.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3 row">
                <label for="inputNama" class="col-sm-2 col-form-label">Nama :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputNama" name="inputNama" value="<?php echo htmlspecialchars($currentNama); ?>" placeholder="Nama">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputJabatan" class="col-sm-2 col-form-label">Jabatan :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputJabatan" name="inputJabatan" value="<?php echo htmlspecialchars($currentJabatan); ?>" placeholder="Jabatan">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputNIK" class="col-sm-2 col-form-label">NIK :</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="inputNIK" name="inputNIK" value="<?php echo htmlspecialchars($currentNIK); ?>" placeholder="NIK">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputJenisCuti" class="col-sm-2 col-form-label">Jenis Cuti :</label>
                <div class="col-sm-10">
                    <select class="form-select" id="inputJenisCuti" name="inputJenisCuti">
                        <option selected>Pilih Jenis Cuti...</option>
                        <option value="Cuti Sakit">Cuti Sakit</option>
                        <option value="Cuti Meninggal (Keluarga)">Cuti Meninggal (Keluarga)</option>
                        <option value="Cuti Sidang Sarjana">Cuti Sidang Sarjana</option>
                        <option value="Cuti Wisuda">Cuti Wisuda</option>
                        <option value="Cuti Melahiran">Cuti Melahiran</option>
                        <option value="Cuti 1 hari area luar kota">Cuti 1 hari area luar kota</option>
                    </select>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputTanggalAwal" class="col-sm-2 col-form-label">Tanggal Awal :</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" id="inputTanggalAwal" name="inputTanggalAwal">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputTanggalAkhir" class="col-sm-2 col-form-label">Tanggal Akhir :</label>
                <div class="col-sm-10">
                    <input type="date" class="form-control" id="inputTanggalAkhir" name="inputTanggalAkhir">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputAlasanCuti" class="col-sm-2 col-form-label">Alasan Cuti :</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputAlasanCuti" name="inputAlasanCuti">
                </div>
            </div>
            <div class="mb-3 row" id="lampiranDiv" style="display: none;">
                <label for="inputLampiran" class="col-sm-2 col-form-label">Lampiran :</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control" id="inputLampiran" name="inputLampiran">
                    <p class="text-muted">Allowed File Extensions: .pdf, .doc, .docx, .jpg, .jpeg, .png</p>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-warning">Ajukan Cuti</button>
                <a class="btn btn-warning" href="#">Batal</a>
            </div>
        </form>
    </div>
</section>

<script>
document.getElementById('inputJenisCuti').addEventListener('change', function() {
    var lampiranDiv = document.getElementById('lampiranDiv');
    var selectedValue = this.value;
    
    if (selectedValue === 'Cuti Sakit' || selectedValue === 'Cuti 1 hari area luar kota') {
        lampiranDiv.style.display = 'flex'; // Menampilkan lampiran
    } else {
        lampiranDiv.style.display = 'none'; // Menyembunyikan lampiran
    }
});
</script>
<?php include('../template/footer.php'); ?>
