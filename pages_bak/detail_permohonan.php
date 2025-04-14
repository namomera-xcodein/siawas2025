<?php
//Sinclude 'config/config.php';

// Mengambil ID permohonan dari URL
$id_permohonan = $_GET['id'];

// Query untuk mengambil data permohonan
$query = mysqli_query($conn, "SELECT * FROM permohonan WHERE id='$id_permohonan'");
$data = mysqli_fetch_assoc($query);

// Mengambil data pemohon dari tabel users
$user_id = $data['user_id'];
$user_query = mysqli_query($conn, "SELECT NIP_NIK, name FROM users WHERE id='$user_id'");
$user_data = mysqli_fetch_assoc($user_query);

// Query untuk mengambil detail barang dari permohonan_detail
$detail_query = mysqli_query($conn, "SELECT * FROM permohonan_detail WHERE permohonan_id='$id_permohonan'");

// Handle form submission untuk update mata anggaran
if(isset($_POST['update_mata_anggaran']) && $_SESSION['role'] === 'pejabat_ppk') {
    $mata_anggaran = mysqli_real_escape_string($conn, $_POST['mata_anggaran']);
    $update_query = mysqli_query($conn, "UPDATE permohonan SET mata_anggaran='$mata_anggaran' WHERE id='$id_permohonan'");
    if($update_query) {
        // Refresh data after update
        $query = mysqli_query($conn, "SELECT * FROM permohonan WHERE id='$id_permohonan'");
        $data = mysqli_fetch_assoc($query);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Permohonan</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <h3>Detail Permohonan</h3>
    <p><strong>Nomor Permohonan:</strong> <?php echo $data['nomor_permohonan']; ?></p>
    <?php if($_SESSION['role'] === 'pejabat_ppk'): ?>
    <form method="POST" style="display: inline;">
        <p><strong>Mata Anggaran:</strong>
            <input type="text" name="mata_anggaran" value="<?php echo $data['mata_anggaran']; ?>" required>
            <button type="submit" name="update_mata_anggaran">Update</button>
        </p>
    </form>
    <?php else: ?>
    <p><strong>Mata Anggaran:</strong> <?php echo $data['mata_anggaran'] ?: 'Belum Diisi'; ?></p>
    <?php endif; ?>
    <p><strong>Detail Kegiatan:</strong> <?php echo $data['detail_kegiatan']; ?></p>
    <p><strong>Waktu Permohonan:</strong> <?php echo date('d F Y', strtotime($data['tanggal_permohonan'])); ?></p>
    <p><strong>Jumlah: Rp.</strong> <?php echo number_format($data['total_harga'], 0, ',', '.'); ?></p>

    <h4>Detail Barang</h4>
    <table id="detailTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($barang = mysqli_fetch_assoc($detail_query)): ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $barang['nama_barang']; ?></td>
                <td><?php echo $barang['satuan']; ?></td>
                <td>Rp. <?php echo number_format($barang['harga_satuan'], 0, ',', '.'); ?></td>
                <td><?php echo $barang['jumlah']; ?></td>
                <td>Rp. <?php echo number_format($barang['total_harga'], 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align:right">Total Harga</th>
                <th>Rp. <?php echo number_format($data['total_harga'], 0, ',', '.'); ?></th>
            </tr>
        </tfoot>
    </table>

    <h4>Informasi Tanda Tangan</h4>
    <p><strong>Ambon, <?php echo date('d F Y', strtotime($data['created_at'])); ?></strong></p>
    <p><strong>Pemohon:</strong> <?php echo $user_data['name']; ?></p>
    <p><strong>NIP:</strong> <?php echo $user_data['NIP_NIK']; ?></p>

    <!-- E-Sign Pemohon -->
    <h4>e-Sign Pemohon</h4>
    <img src="<?php echo $data['qr_code_pemohon']; ?>" alt="QR Code Pemohon" style="width:100px;height:100px;">

    <?php if (!empty($data['qr_code_spm'])): ?>
    <h4>e-Sign Pejabat SPM</h4>
    <img src="<?php echo $data['qr_code_spm']; ?>" alt="QR Code Pejabat SPM" style="width:100px;height:100px;">
    <?php endif; ?>

    <?php if (!empty($data['qr_code_ppk'])): ?>
    <h4>e-Sign Plt. Kasubbag Umum / PPK</h4>
    <img src="<?php echo $data['qr_code_ppk']; ?>" alt="QR Code Kasubbag Umum / PPK" style="width:100px;height:100px;">
    <?php endif; ?>

    <?php if (!empty($data['qr_code_kpa'])): ?>
    <h4>e-Sign KPA</h4>
    <img src="uploads/qrcode/<?php echo $data['qr_code_kpa']; ?>" alt="QR Code KPA" style="width:100px;height:100px;">
    <?php endif; ?>


    <p><strong>Status Permohonan:</strong> <?php echo $data['status']; ?></p>
    <?php if (!empty($data['keterangan'])): ?>
    <p><strong>Keterangan:</strong> <?php echo $data['keterangan']; ?></p>
    <?php endif; ?>

    <?php if ($_SESSION['role'] == 'pejabat_ppk'): ?>
    <a href="<?= $base_url ?>/index.php?page=permohonan_ppk" class="btn btn-primary">📑Kembali</a>
    <?php else: ?>
    <a href="javascript:history.back()" class="btn btn-primary">📑Kembali</a>
    <?php endif; ?>
    <script>
    $(document).ready(function() {
        $('#detailTable').DataTable({
            "responsive": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true
        });
    });
    </script>
</body>

</html>