<?php
//session_start();
//include_once '../config/config.php';
//require '../vendor/autoload.php';


// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// // Fungsi untuk membersihkan input
// function cleanInput($data) {
//     return htmlspecialchars(trim($data));
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $mata_anggaran = cleanInput($_POST['mata_anggaran']);
    $detail_kegiatan = cleanInput($_POST['detail_kegiatan']);
    $tanggal_permohonan = date('Y-m-d h:i');
    $total_harga = 0;
    
    // Simpan permohonan
    $stmt = $conn->prepare("INSERT INTO permohonan (user_id, mata_anggaran, detail_kegiatan, tanggal_permohonan, total_harga, status) VALUES (?, ?, ?, ?, ?, 'Menunggu Persetujuan SPM')");
    $stmt->bind_param("isssd", $user_id, $mata_anggaran, $detail_kegiatan, $tanggal_permohonan, $total_harga);
    $stmt->execute();
    $permohonan_id = $stmt->insert_id;
    $stmt->close();
    
    // Simpan detail permohonan
    if (!empty($_POST['nama_barang'])) {
        $detail_stmt = $conn->prepare("INSERT INTO permohonan_detail (permohonan_id, nama_barang, satuan, harga_satuan, jumlah, total_harga) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($_POST['nama_barang'] as $index => $nama_barang) {
            $nama_barang = cleanInput($nama_barang);
            $satuan = cleanInput($_POST['satuan'][$index]);
            $harga_satuan = (float) $_POST['harga_satuan'][$index];
            $jumlah = (int) $_POST['jumlah'][$index];
            $total_item = $harga_satuan * $jumlah;
            $total_harga += $total_item;
            
            $detail_stmt->bind_param("issddi", $permohonan_id, $nama_barang, $satuan, $harga_satuan, $jumlah, $total_item);
            $detail_stmt->execute();
        }
        $detail_stmt->close();
    }
    
    // Update total harga di tabel permohonan
    $update_stmt = $conn->prepare("UPDATE permohonan SET total_harga = ? WHERE id = ?");
    $update_stmt->bind_param("di", $total_harga, $permohonan_id);
    $update_stmt->execute();
    $update_stmt->close();
  
// Nama file QR Code
$qrFileName = "{$nomor_permohonan}_{$tanggal_permohonan}_{$nama_pemohon}.png";
    // Pastikan direktori penyimpanan QR Code ada
$qrDirectory = "../../uploads/qrcode/";
if (!is_dir($qrDirectory)) {
    mkdir($qrDirectory, 0777, true);
}
    // Generate QR Code
$result = Builder::create()
->writer(new PngWriter())
->data("Nomor: $nomor_permohonan\nPemohon: $nama_pemohon\nTanggal: $tanggal_permohonan")
->encoding(new Encoding('UTF-8'))
->errorCorrectionLevel(ErrorCorrectionLevel::High)
->size(300)
->margin(10)
->roundBlockSizeMode(RoundBlockSizeMode::Margin)
->logoPath($logoPath)
->logoResizeToWidth(80)
->logoPunchoutBackground(true)
->labelText('e-Sign SiPatra')
->labelFont(new OpenSans(20))
->labelAlignment(LabelAlignment::Center)
->build();

// Simpan QR Code ke file
$result->saveToFile($qrPath);
    
    $writer = new PngWriter();
    $qrPath = "../../qr_codes/permohonan_$permohonan_id.png";
    file_put_contents($qrPath, $writer->write($qrCode)->getString());
    
    
    // Redirect ke halaman sukses
    // header("Location: success.php?id=$permohonan_id");
    // exit();
    $success = "Permohonan berhasil diajukan!";
    } else {
        $error = "Terjadi kesalahan saat menyimpan data.";
    }
    $stmt->close();

?>
<?php
//session_start();
//include_once '../config/config.php';
//require '../vendor/autoload.php';
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;


// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fungsi untuk membersihkan input
function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $mata_anggaran = cleanInput($_POST['mata_anggaran']);
    $detail_kegiatan = cleanInput($_POST['detail_kegiatan']);
    $tanggal_permohonan = date('Y-m-d h:i');
    $total_harga = 0;
    
    // Simpan permohonan
    $stmt = $conn->prepare("INSERT INTO permohonan (user_id, mata_anggaran, detail_kegiatan, tanggal_permohonan, total_harga, status) VALUES (?, ?, ?, ?, ?, 'Menunggu Persetujuan SPM')");
    $stmt->bind_param("isssd", $user_id, $mata_anggaran, $detail_kegiatan, $tanggal_permohonan, $total_harga);
    $stmt->execute();
    $permohonan_id = $stmt->insert_id;
    $stmt->close();
    
    // Simpan detail permohonan
    if (!empty($_POST['nama_barang'])) {
        $detail_stmt = $conn->prepare("INSERT INTO permohonan_detail (permohonan_id, nama_barang, satuan, harga_satuan, jumlah, total_harga) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($_POST['nama_barang'] as $index => $nama_barang) {
            $nama_barang = cleanInput($nama_barang);
            $satuan = cleanInput($_POST['satuan'][$index]);
            $harga_satuan = (float) $_POST['harga_satuan'][$index];
            $jumlah = (int) $_POST['jumlah'][$index];
            $total_item = $harga_satuan * $jumlah;
            $total_harga += $total_item;
            
            $detail_stmt->bind_param("issddi", $permohonan_id, $nama_barang, $satuan, $harga_satuan, $jumlah, $total_item);
            $detail_stmt->execute();
        }
        $detail_stmt->close();
    }
    
    // Update total harga di tabel permohonan
    $update_stmt = $conn->prepare("UPDATE permohonan SET total_harga = ? WHERE id = ?");
    $update_stmt->bind_param("di", $total_harga, $permohonan_id);
    $update_stmt->execute();
    $update_stmt->close();
  
// Nama file QR Code
$qrFileName = "{$nomor_permohonan}_{$tanggal_permohonan}_{$nama_pemohon}.png";
    // Pastikan direktori penyimpanan QR Code ada
$qrDirectory = "../../uploads/qrcode/";
if (!is_dir($qrDirectory)) {
    mkdir($qrDirectory, 0777, true);
}
    // Generate QR Code
$result = Builder::create()
->writer(new PngWriter())
->data("Nomor: $nomor_permohonan\nPemohon: $nama_pemohon\nTanggal: $tanggal_permohonan")
->encoding(new Encoding('UTF-8'))
->errorCorrectionLevel(ErrorCorrectionLevel::High)
->size(300)
->margin(10)
->roundBlockSizeMode(RoundBlockSizeMode::Margin)
->logoPath($logoPath)
->logoResizeToWidth(80)
->logoPunchoutBackground(true)
->labelText('e-Sign SiPatra')
->labelFont(new OpenSans(20))
->labelAlignment(LabelAlignment::Center)
->build();

// Simpan QR Code ke file
$result->saveToFile($qrPath);
    
    $writer = new PngWriter();
    $qrPath = "../../qr_codes/permohonan_$permohonan_id.png";
    file_put_contents($qrPath, $writer->write($qrCode)->getString());
    
    
    // Redirect ke halaman sukses
    // header("Location: success.php?id=$permohonan_id");
    // exit();
    $success = "Permohonan berhasil diajukan!";
    } else {
        $error = "Terjadi kesalahan saat menyimpan data.";
    }
    $stmt->close();

?>