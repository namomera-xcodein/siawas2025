if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$permohonan_id = $_POST['id'];
$status = $_POST['status'];

$stmt = $conn->prepare("UPDATE permohonan SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $permohonan_id);

if ($stmt->execute()) {
logActivity($conn, $_SESSION['user_id'], "Mengubah status permohonan ID $permohonan_id menjadi $status");
$_SESSION['success'] = "Status berhasil diperbarui.";
} else {
$_SESSION['error'] = "Gagal memperbarui status.";
}

header("Location: riwayat_permohonan.php");
exit();
}