<?php
include('../template/header.php');
include('../controllers/statusController.php');

// Periksa apakah sesi login ada
if (!isset($_SESSION['IDKaryawan'])) {
    // Jika tidak ada sesi login, arahkan ke halaman login
    header("Location: ../view/loginView.php");
    exit("Silahkan Login Terlebih Dahulu");
}
?>
<section class="lap-cuti mt-5">
    <div class="container">
        <h2 class="bg-warning p-1">STATUS CUTI</h2>
        <table class="table table-bordered table-bordered-black" style="color:black;">
            <thead>
                <tr>
                    <?php if (in_array($jabatan, ['Admin','HRD', 'Manager', 'SPV'])) { ?>
                        <th>Nama</th>
                    <?php } ?>
                    <th>Tanggal</th>
                    <th>Jenis Cuti</th>
                    <?php if (in_array($jabatan, ['Karyawan'])) { ?>
                    <th>Status</th>
                    <?php } ?>
                    <?php if (in_array($jabatan, ['HRD', 'Manager', 'SPV'])) { ?>
                        <th>Aksi</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php if (in_array($jabatan, ['Admin', 'HRD', 'Manager', 'SPV'])): ?>
                            <td><?php echo htmlspecialchars($row["NamaKaryawan"]); ?></td>
                        <?php endif; ?>
                        <td><?php echo htmlspecialchars($row["TanggalAwal"]); ?></td>
                        <td><?php echo htmlspecialchars($row["JenisCuti"]); ?></td>
                        <td>
                            <div>
                                <strong><?php echo htmlspecialchars($row["Status"]); ?></strong>
                            </div>
                            <?php if ($row["Status"] == 'Disetujui' && !empty($row["ApprovedBy"])): ?>
                                <ul>
                                    <?php
                                    $approvedBy = array_map('trim', explode(',', htmlspecialchars($row["ApprovedBy"])));
                                    foreach ($approvedBy as $approver): ?>
                                        <li><strong><?php echo htmlspecialchars($approver); ?></strong></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php elseif ($row["Status"] == 'Ditolak' && !empty($row["RejectedBy"])): ?>
                                <ul>
                                    <?php
                                    $rejectedBy = array_map('trim', explode(',', htmlspecialchars($row["RejectedBy"])));
                                    foreach ($rejectedBy as $rejector): ?>
                                        <li><strong><?php echo htmlspecialchars($rejector); ?></strong></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                        <?php if (in_array($jabatan, ['HRD', 'Manager', 'SPV'])): ?>
                            <td>
                                <button type='button' class='btn btn-success btn-sm' data-bs-toggle='modal' data-bs-target='#modalApprove<?php echo $row["IDPengajuan"]; ?>'>Setujui</button>
                                <button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#modalReject<?php echo $row["IDPengajuan"]; ?>'>Tolak</button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($data)): ?>
                    <tr><td colspan='<?php echo in_array($jabatan, ['HRD', 'Admin', 'Manager']) ? 5 : 4; ?>'>Belum ada data pengajuan cuti.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php
include('../template/footer.php');
?>
