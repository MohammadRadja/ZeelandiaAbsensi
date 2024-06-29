<?php include('../template/header.php'); ?>
<section class="lap-cuti mt-5">
    <div class="container ">
        <h2 class="bg-warning p-1">STATUS CUTI</h2>
        <table class="table table-bordered table-bordered-black" style="color:black;">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jenis Cuti</th>
                    <th>Status</th>
                    <?php if (in_array($_SESSION['jabatan'], ['admin', 'manager', 'HRD'])): ?>
                        <th>Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php include('../controllers/statusController.php'); ?>
            </tbody>
        </table>
    </div>
</section>
<?php include('../template/footer.php'); ?>
