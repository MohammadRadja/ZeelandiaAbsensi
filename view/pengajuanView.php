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
<section class="profil">
        <div class="container pt-5">
            <h2 class="bg-warning p-1">PENGAJUAN CUTI</h2>
            <form class="edit-profil mt-3" action="../controllers/pengajuanController.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3 row">
                    <label for="inputNama" class="col-sm-2 col-form-label">Nama :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputNama" name="inputNama" value="<?php echo $currentNama; ?>" placeholder="Nama" disabled>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputJabatan" class="col-sm-2 col-form-label">Jabatan :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputJabatan" name="inputJabatan" value="<?php echo $currentJabatan; ?>" placeholder="Jabatan" disabled>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputNIK" class="col-sm-2 col-form-label">NIK :</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="inputNIK" name="inputNIK" value="<?php echo $currentNIK; ?>" placeholder="NIK" disabled>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputJenisCuti" class="col-sm-2 col-form-label">Jenis Cuti :</label>
                    <div class="col-sm-10">
                        <select class="form-select" id="inputJenisCuti" name="inputJenisCuti">
                            <option selected>Pilih Jenis Cuti...</option>
                            <option value="1">Cuti Sakit</option>
                            <option value="2">Cuti Meninggal (Keluarga)</option>
                            <option value="3">Cuti Sidang Sarjana</option>
                            <option value="3">Cuti Wisuda</option>
                            <option value="3">Cuti Melahiran</option>
                            <option value="3">Cuti 1 hari area luar kota</option>
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
                <div class="mb-3 row">
                    <label for="inputLampiran" class="col-sm-2 col-form-label">Lampiran :</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" id="inputLampiran" name="inputLampiran">
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-warning">Ajukan Cuti</button>
                    <a class="btn btn-warning" href="#">Batal</a>
                </div>
            </form>
        </div>
    </section>

    <?php include('../template/footer.php'); ?>