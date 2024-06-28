<?php include('../template/header.php'); ?>
<section class="lap-cuti mt-5">
    <div class="container ">
        <h2 class="bg-warning p-1">LAPORAN CUTI</h2>
        <table class="table table-bordered table-bordered-black" style="color:black;">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jenis Cuti</th>
                    <th>Jumlah Hari</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if (!empty($laporanData)) {
                    $no = 1;
                    foreach ($laporanData as $data) {
                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . $data['TanggalAwal'] . "</td>";
                        echo "<td>" . $data['JenisCuti'] . "</td>";
                        echo "<td>" . $data['Jumlah hari'] . "</td>"; // Sesuaikan dengan nama kolom yang benar
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Belum ada data laporan cuti.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>
<section class="rekaputulasi mt-5">
    <div class="container ">
        <h2 class="bg-warning p-1">REKAPITULASI LAPORAN CUTI</h2>
        <table class="table table-bordered table-bordered-black" style="color:black;">
            <thead>
                <tr>
                    <th>Bulan<th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if (!empty($rekapData)) {
                    foreach ($rekapData as $rekap) {
                        echo "<tr>";
                        echo "<td>" . $rekap['Bulan'] . "</td>";
                        echo "<td>" . $rekap['Jumlah'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Belum ada data rekapitulasi laporan cuti.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>
<?php include('../template/footer.php'); ?>