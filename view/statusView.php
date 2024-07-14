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
                    <th>Status</th>
                    <?php if (in_array($jabatan, ['Karyawan'])) { ?>
                    <th>Jumlah Sisa Cuti</th>
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
                            <?php if (isset($row["ApprovedBy"]) && !empty($row["ApprovedBy"])): ?>
                                <?php
                                $approvedBy = htmlspecialchars($row["ApprovedBy"]);
                                $approvers = array_map('trim', explode(',', $approvedBy));
                                ?>
                                <div>
                                    <?php if ($row["Status"] == 'Disetujui'): ?>
                                        <?php if (in_array($jabatan, ['Admin', 'HRD', 'Manager', 'SPV'])): ?>
                                        <ul>
                                            <?php foreach ($approvers as $approver): ?>
                                                <li><strong><?php echo htmlspecialchars($approver); ?></strong></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($row["RejectedBy"]) && !empty($row["RejectedBy"])): ?>
                                <?php
                                $rejectedBy = htmlspecialchars($row["RejectedBy"]);
                                $rejectors = array_map('trim', explode(',', $rejectedBy));
                                ?>
                                <div>
                                    <?php if ($row["Status"] == 'Ditolak'): ?>
                                        <?php if (in_array($jabatan, ['Admin', 'HRD', 'Manager', 'SPV'])): ?>
                                        <ul>
                                            <?php foreach ($rejectors as $rejector): ?>
                                                <li><strong><?php echo htmlspecialchars($rejector); ?></strong></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <?php if (in_array($jabatan, ['Karyawan'])): ?>
                        <td><?php echo htmlspecialchars($row["JumlahSisaCuti"]); ?></td>
                        <?php endif; ?>
                        <?php if (in_array($jabatan, ['HRD', 'Manager', 'SPV'])): ?>
                            <td>
                                <button type='button' class='btn btn-success btn-sm' data-bs-toggle='modal' data-bs-target='#modalApprove<?php echo $row["IDPengajuan"]; ?>'>Setujui</button>
                                <button type='button' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#modalReject<?php echo $row["IDPengajuan"]; ?>'>Tolak</button>
                            </td>
                        <?php endif; ?>
                    </tr>

                    <!-- Modal Approve -->
                    <div class='modal fade' id='modalApprove<?php echo $row["IDPengajuan"]; ?>' tabindex='-1' aria-labelledby='modalApproveLabel<?php echo $row["IDPengajuan"]; ?>' aria-hidden='true'>
                        <div class='modal-dialog'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='modalApproveLabel<?php echo $row["IDPengajuan"]; ?>'>Konfirmasi Setujui</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                    Apakah Anda yakin ingin menyetujui pengajuan cuti ini?
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                                    <form method='post'>
                                        <input type='hidden' name='IDPengajuan' value='<?php echo htmlspecialchars($row["IDPengajuan"]); ?>'>
                                        <button type='submit' name='approveStatus' class='btn btn-success'>Setujui</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Reject -->
                    <div class='modal fade' id='modalReject<?php echo $row["IDPengajuan"]; ?>' tabindex='-1' aria-labelledby='modalRejectLabel<?php echo $row["IDPengajuan"]; ?>' aria-hidden='true'>
                        <div class='modal-dialog'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='modalRejectLabel<?php echo $row["IDPengajuan"]; ?>'>Konfirmasi Tolak</h5>
                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                    Apakah Anda yakin ingin menolak pengajuan cuti ini?
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                                    <form method='post'>
                                        <input type='hidden' name='IDPengajuan' value='<?php echo htmlspecialchars($row["IDPengajuan"]); ?>'>
                                        <button type='submit' name='rejectStatus' class='btn btn-danger'>Tolak</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($data)): ?>
                    <tr><td colspan='<?php echo in_array($jabatan, ['hrd', 'admin', 'manager']) ? 5 : 4; ?>'>Belum ada data pengajuan cuti.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php
include('../template/footer.php');
?>
