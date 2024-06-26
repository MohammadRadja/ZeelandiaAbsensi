<?php include('../template/header.php'); ?>
    <section class="profil">
        <div class="container pt-5">
            <h2 class="bg-warning p-1 text-center">PROFIL</h2>
            <div class="foto-profil text-center">
                <img src="../assets/img/profile.png" alt="Profile Picture" class="rounded-circle" style="width: 150px; height: 150px;">
            </div>
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
                    <label for="inputAddress" class="col-sm-2 col-form-label">Alamat :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputAddress" placeholder="Alamat">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputEmail" class="col-sm-2 col-form-label">Email :</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPhone" class="col-sm-2 col-form-label">Nomer HP :</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="inputPhone" placeholder="Nomer HP">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputJoinDate" class="col-sm-2 col-form-label">Tanggal Bergabung :</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="inputJoinDate">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputWorkPeriod" class="col-sm-2 col-form-label">Masa Kerja :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputWorkPeriod" placeholder="Masa Kerja">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputPassword" class="col-sm-2 col-form-label">Ubah sandi :</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="inputPassword" placeholder="Ubah sandi">
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-warning">Edit Profil</button>
                </div>
            </form>
        </div>
    </section>
<?php include('../template/footer.php'); ?>