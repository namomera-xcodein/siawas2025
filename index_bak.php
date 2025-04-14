<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!function_exists('session_start')) {
    function session_start() {
        // Implementation of session_start
    }
}

if (!function_exists('header')) {
    function header($string) {
        // Implementation of header
    }
}

if (!function_exists('in_array')) {
    function in_array($needle, $haystack) {
        return array_search($needle, $haystack) !== false;
    }
}

if (!function_exists('file_exists')) {
    function file_exists($filename) {
        return is_file($filename);
    }
}

$logo_path = __DIR__ . '/assets/kkp.png';




if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php'); // Ganti index.php dengan login.php
    exit();
}
$user_id = $_SESSION['user_id']; // Ambil id_user dari session

include(__DIR__ . '/config/config.php');
include(__DIR__ . '/config/functions.php');
include(__DIR__ . '/vendor/autoload.php');
// Ambil role dari session, jika belum ada, default ke 'pemohon'
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'pemohon'; // Default 'pemohon'
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; // Default 'dashboard'

// Pastikan hanya role yang diizinkan
$allowed_roles = ['pemohon', 'admin', 'operator', 'pejabat_spm', 'pejabat_ppk', 'pejabat_kpa'];
if (!in_array($role, $allowed_roles)) {
    die("Akses tidak diizinkan!");
}

// Path file yang akan dimuat
$file_path = __DIR__ . "/pages/$role/$page.php";
?>

<?php include(__DIR__ . '/includes/header.php'); ?>

<!-- Main Content -->
<main class="main-content">
    <div class="container mt-4">
        <?php
        if (file_exists($file_path)) {
            include($file_path);
        } else {
            echo "<h4>Halaman tidak ditemukan!</h4>";
        }
        ?>
    </div>
</main>

<?php
// Load sidebar sesuai role
if ($role === 'pemohon') {
    include(__DIR__ . '/includes/sidebar.php');
} elseif ($role === 'pejabat_spm' || $role === 'pejabat_ppk' || $role === 'pejabat_kpa') {
    include(__DIR__ . '/includes/sidebar2.php');
} elseif ($role === 'admin') {
    include(__DIR__ . '/includes/sidebar3.php');
}
?>

<?php include(__DIR__ . '/includes/footer.php'); ?>