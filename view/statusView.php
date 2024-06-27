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
                </tr>
            </thead>
            <tbody>
            <?php include('../controllers/statusController.php'); ?>
            </tbody>
        </table>
    </div>
</section>
<?php include('../template/footer.php'); ?>