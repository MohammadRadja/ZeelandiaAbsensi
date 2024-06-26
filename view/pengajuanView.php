<?php include('../template/header.php'); ?>
    <section class="profil">
        <div class="container pt-5">
            <h2 class="bg-warning p-1">PENGAJUAN CUTI</h2>
            <form class="edit-profil mt-3">
                <div class="mb-3 row">
                    <label for="inputName" class="col-sm-2 col-form-label">Nama :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputName" placeholder="Nama">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPosition" class="col-sm-2 col-form-label">Jabatan :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputPosition" placeholder="Jabatan">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputNIK" class="col-sm-2 col-form-label">NIK :</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="inputNIK" placeholder="NIK">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputAddress" class="col-sm-2 col-form-label">Jenis Cuti :</label>
                    <div class="col-sm-10">
                        <select class="form-select" id="inputGroupSelect01">
                            <option selected>Choose...</option>
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
                    <label for="inputEmail" class="col-sm-2 col-form-label">Tanggal Awal :</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="inputEmail">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPhone" class="col-sm-2 col-form-label">Tanggal Akhir :</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="inputPhone">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputJoinDate" class="col-sm-2 col-form-label">Alasan Cuti :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputJoinDate">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputJoinDate" class="col-sm-2 col-form-label">lampiran :</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" id="inputJoinDate">
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