<?php
//fungsi tanggal 

if (!function_exists('tanggal_indonesia')) {
    function tanggal_indonesia($tanggal)
    {
        // Isi fungsi
    }
}
function getAllPejabatByUser(mysqli $conn, int $user_id): array
{
    $pejabat = [];

    // 1. Ambil Katimja dari kolom atasan_id di tabel users
    $q_katimja = "
        SELECT u.id, u.name, u.nip_nik, l.level_jabatan, l.folder_name
        FROM users u
        JOIN set_pejabat sp ON u.id = sp.id_user
        JOIN level l ON sp.id_level = l.id_level
        WHERE u.id = (SELECT atasan_id FROM users WHERE id = ?)
        AND sp.id_level = 2
    ";
    $stmt1 = $conn->prepare($q_katimja);
    $stmt1->bind_param("i", $user_id);
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    if ($row = $res1->fetch_assoc()) {
        $pejabat['katimja'] = [
            'id_user' => $row['id'],
            'name' => $row['name'],
            'nip_nik' => $row['nip_nik'],
            'jabatan' => $row['level_jabatan'],
            'folder' => $row['folder_name'],
        ];
    }

    // 2. Ambil pejabat lain (PPK, KPA, dll) dari set_pejabat
    $q_pejabat = "
        SELECT u.id, u.name, u.nip_nik, l.level_jabatan, l.folder_name, sp.id_level
        FROM set_pejabat sp
        JOIN users u ON sp.id_user = u.id
        JOIN level l ON sp.id_level = l.id_level
        WHERE sp.id_level IN (3,4,5,6,7) -- level selain pemohon dan katimja
    ";
    $res2 = $conn->query($q_pejabat);
    while ($row = $res2->fetch_assoc()) {
        $key = strtolower($row['folder_name']);
        $pejabat[$key] = [
            'id_user' => $row['id'],
            'name' => $row['name'],
            'nip_nik' => $row['nip_nik'],
            'jabatan' => $row['level_jabatan'],
            'folder' => $row['folder_name'],
        ];
    }

    return $pejabat;
}
function getKatimjaPermohonan(mysqli $conn, int $id_permohonan): array
{
    $data = [];

    $query = "
        SELECT 
            p.qr_code_pejabat1,
            u1.atasan_id,
            u2.name AS katimja_name,
            u2.nip_nik AS katimja_nip
        FROM permohonan p
        JOIN users u1 ON p.user_id = u1.id
        LEFT JOIN users u2 ON u1.atasan_id = u2.id
        WHERE p.id = ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id_permohonan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $data['qr_code_pejabat1'] = $row['qr_code_pejabat1'] ?? null;
            $data['katimja_name'] = $row['katimja_name'] ?? '--Menunggu Persetujuan--';
            $data['katimja_nip'] = $row['katimja_nip'] ?? '--';
        }

        $stmt->close();
    }

    return $data;
}
function getDataPPK(mysqli $conn, int $id_permohonan): array
{
    // Ambil qr_code_ppk dan user_id dari permohonan
    $queryPermohonan = "SELECT qr_code_ppk FROM permohonan WHERE id = ?";
    $stmt1 = $conn->prepare($queryPermohonan);
    $stmt1->bind_param("i", $id_permohonan);
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    $qr_code_ppk = '';
    if ($row1 = $res1->fetch_assoc()) {
        $qr_code_ppk = $row1['qr_code_ppk'] ?? '';
    }

    // Ambil data PPK dari set_pejabat
    $queryPPK = "
        SELECT u.name, u.nip_nik, l.level_jabatan 
        FROM set_pejabat sp
        JOIN users u ON sp.id_user = u.id
        JOIN level l ON sp.id_level = l.id_level
        WHERE sp.id_level = 3 LIMIT 1
    ";
    $res2 = $conn->query($queryPPK);
    $ppk = [];

    if ($row2 = $res2->fetch_assoc()) {
        $ppk = [
            'name' => $row2['name'],
            'nip_nik' => $row2['nip_nik'],
            'jabatan' => $row2['level_jabatan'],
            'qr_code' => $qr_code_ppk ?: '--Menunggu Tanda Tangan--'
        ];
    }

    return $ppk;
}
function getDataKPA(mysqli $conn, int $id_permohonan): array
{
    // Ambil qr_code_kpa dari permohonan
    $queryPermohonan = "SELECT qr_code_kpa FROM permohonan WHERE id = ?";
    $stmt1 = $conn->prepare($queryPermohonan);
    $stmt1->bind_param("i", $id_permohonan);
    $stmt1->execute();
    $res1 = $stmt1->get_result();
    $qr_code_kpa = '';
    if ($row1 = $res1->fetch_assoc()) {
        $qr_code_kpa = $row1['qr_code_kpa'] ?? '';
    }

    // Ambil data KPA dari set_pejabat
    $queryKPA = "
        SELECT u.name, u.nip_nik, l.level_jabatan 
        FROM set_pejabat sp
        JOIN users u ON sp.id_user = u.id
        JOIN level l ON sp.id_level = l.id_level
        WHERE sp.id_level = 4 LIMIT 1
    ";
    $res2 = $conn->query($queryKPA);
    $kpa = [];

    if ($row2 = $res2->fetch_assoc()) {
        $kpa = [
            'name' => $row2['name'],
            'nip_nik' => $row2['nip_nik'],
            'jabatan' => $row2['level_jabatan'],
            'qr_code' => $qr_code_kpa ?: '--Menunggu Tanda Tangan--'
        ];
    }

    return $kpa;
}


use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

function generateQRCode($data, $logoPath)
{
    return Builder::create()
        ->writer(new PngWriter())
        ->data($data)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(ErrorCorrectionLevel::High)
        ->size(200)
        ->margin(10)
        ->roundBlockSizeMode(RoundBlockSizeMode::Margin)
        ->logoPath($logoPath)
        ->logoResizeToWidth(80)
        ->logoPunchoutBackground(true)
        ->build();
}




function logActivity($conn, $user_id, $aktivitas)
{
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

    $stmt = $conn->prepare("INSERT INTO log_activity (user_id, aktivitas, ip_address, user_agent) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $aktivitas, $ip_address, $user_agent);
    $stmt->execute();
}

function insertPermohonanDetail($conn, $permohonan_id, $postData)
{
    $stmt = $conn->prepare("INSERT INTO permohonan_detail (permohonan_id, nama_barang, satuan, harga_satuan, jumlah_barang, subtotal_harga) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($postData['nama_barang'] as $key => $nama_barang) {
        $nama_barang = htmlspecialchars($nama_barang, ENT_QUOTES, 'UTF-8');
        $satuan = htmlspecialchars($postData['satuan'][$key], ENT_QUOTES, 'UTF-8');
        $harga_satuan = floatval($postData['harga_satuan'][$key]);
        $jumlah_barang = floatval($postData['jumlah_barang'][$key]);
        $subtotal_harga = floatval(str_replace(',', '.', str_replace('.', '', $postData['total_harga_barang'][$key])));
        // Ubah nama variabel

        $stmt->bind_param("issddd", $permohonan_id, $nama_barang, $satuan, $harga_satuan, $jumlah_barang, $subtotal_harga);

        $stmt->execute();
    }
}

// Array untuk nama hari dan bulan dalam Bahasa Indonesia
// $nama_hari = array(
//     'Sunday' => 'Minggu',
//     'Monday' => 'Senin',
//     'Tuesday' => 'Selasa',
//     'Wednesday' => 'Rabu',
//     'Thursday' => 'Kamis',
//     'Friday' => 'Jumat',
//     'Saturday' => 'Sabtu'
// );

// $nama_bulan = array(
//     1 => 'Januari',
//     'Februari',
//     'Maret',
//     'April',
//     'Mei',
//     'Juni',
//     'Juli',
//     'Agustus',
//     'September',
//     'Oktober',
//     'November',
//     'Desember'
// );

// // Fungsi untuk format tanggal Indonesia
// function tanggal_indonesia($tanggal) {
//     global $nama_hari, $nama_bulan;

//     $hari = date('l', strtotime($tanggal));
//     $tgl = date('d', strtotime($tanggal));
//     $bulan = date('n', strtotime($tanggal));
//     $tahun = date('Y', strtotime($tanggal));

//     return $nama_hari[$hari] . ', ' . $tgl . ' ' . $nama_bulan[$bulan] . ' ' . $tahun;
// }

// Fungsi untuk format tanggal dan waktu Indonesia
function tanggal_waktu_indonesia($tanggal)
{
    $waktu = date('H:i', strtotime($tanggal));
    return tanggal_indonesia($tanggal) . ' ' . $waktu . ' WIT';
}
function getTotalPermohonan($conn)
{
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['level_user'];

    if ($role == 'Administrator') {
        // Hitung total permohonan untuk user yang sedang login
        $query = "SELECT COUNT(*) as total FROM permohonan WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    return 0; // Return 0 if not operator
}
function getTotalPermohonanPending($conn)
{
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['level_user'];

    if ($role == 'Pemohon') {
        // Hitung total permohonan yang pending untuk user yang sedang login
        $query = "SELECT COUNT(*) as total FROM permohonan 
                  WHERE user_id = ? 
                  AND (status = 'Menunggu Persetujuan Plt. Kasubbag Umum / PPK'
                  OR status = 'Menunggu Persetujuan KPA'
                  OR status = 'Menunggu Persetujuan Pejabat SPM')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    return 0; // Return 0 if not pemohon
}
function getTotalPermohonanDitolak($conn)
{
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['level_user'];

    if ($role == 'Pemohon') {
        // Hitung total permohonan yang ditolak untuk user yang sedang login
        $query = "SELECT COUNT(*) as total FROM permohonan 
                  WHERE user_id = ? 
                  AND status = 'Ditolak'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total'];
    }

    return 0; // Return 0 if not pemohon
}
function getTotalHargaPermohonanSelesai($conn)
{
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['level_user'];

    if ($role == 'Pemohon') {
        // Hitung total harga permohonan yang selesai untuk user yang sedang login
        $query = "SELECT SUM(grand_total_harga) as total FROM permohonan 
                  WHERE user_id = ? 
                  AND status = 'Selesai'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['grand'] ?? 0;
    }

    return 0; // Return 0 if not pemohon
}


function getNotificationCount($conn)
{
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['level_user'];

    if ($role == 'Administrator') {
        // For operators, count notifications for their own requests
        $query = "SELECT COUNT(*) as count FROM permohonan 
                  WHERE user_id = ? AND is_read = 0";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
    } else {
        // For admin/approvers, count all unread requests that need their approval
        $query = "SELECT COUNT(*) as count FROM permohonan 
                  WHERE (status = 'Menunggu Persetujuan Plt. Kasubbag Umum / PPK' 
                  OR status = 'Menunggu Persetujuan KPA'
                  OR status = 'Menunggu Persetujuan Pejabat SPM')";

        $stmt = $conn->prepare($query);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['count'];
}

function getAllReqAdmin($conn)
{
    $query = "SELECT p.*, u.name AS nama_pemohon, u.nip_nik 
              FROM permohonan p
              JOIN users u ON p.user_id = u.id
              ORDER BY p.created_at DESC";

    $result = $conn->query($query);
    return $result;
}

function getAllRequests($conn)
{
    $user_id = $_SESSION['user_id']; // Ambil user_id dari session

    // Ambil data permohonan yang sesuai dengan user_id
    $query = "SELECT p.id, p.nomor_permohonan, p.tanggal_permohonan, p.status, u.name AS nama_pemohon
               FROM permohonan p
               JOIN users u ON p.user_id = u.id
               WHERE p.user_id = ?
               ORDER BY p.created_at DESC
               LIMIT 5";
    // Filter berdasarkan user_id

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);  // Bind user_id ke parameter
    $stmt->execute();
    return $stmt->get_result();  // Kembalikan hasil query
}
function getApprovedRequestsBySPM($conn)
{
    $sql = "SELECT p.id, p.tanggal_permohonan, p.nomor_permohonan, u.name AS nama_pemohon, p.status, p.qr_code_SPM 
            FROM permohonan p
            JOIN users u ON p.user_id = u.id
            WHERE p.status = 'Menunggu Persetujuan Plt. Kasubbag Umum / PPK' 
               OR p.status = 'Menunggu Persetujuan KPA' 
               OR p.status = 'Disetujui'";

    return $conn->query($sql);
}



// Fungsi untuk menambah user
function addUser($name, $nip_nik, $jabatan, $email, $password, $conn)
{
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $created_at = date('Y-m-d H:i:s');

    $query = "INSERT INTO users (name, nip_nik, jabatan, email, password,  created_at) 
              VALUES (?, ?, ?, ?, ?, 'operator', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $name, $nip_nik, $jabatan, $email, $hashed_password, $created_at);
    return $stmt->execute();
}

// Fungsi untuk menghapus user berdasarkan ID
function deleteUser($id, $conn)
{
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Fungsi untuk mendapatkan semua users
function getAllUsers($conn)
{
    $query = "SELECT * FROM users";
    return $conn->query($query);
}