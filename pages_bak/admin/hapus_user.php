<?php
//include "../../config/koneksi.php"; // Sesuaikan dengan koneksi database

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($action === "delete" && !empty($id)) {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(["status" => "success", "message" => "User berhasil dihapus."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menghapus user."]);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["status" => "error", "message" => "ID tidak valid."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Permintaan tidak valid."]);
}
?>