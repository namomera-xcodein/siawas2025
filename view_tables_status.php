<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "monitoring_belanja";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
$query = "SELECT * FROM status_permohonan";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID Level</th><th>Level Jabatan</th><th>Folder Name</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id_status'] . "</td>";
        echo "<td>" . $row['nama_status'] . "</td>";
        echo "<td>" . $row['deskripsi_status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data pada tabel level.";
}