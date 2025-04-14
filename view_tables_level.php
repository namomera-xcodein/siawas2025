<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "monitoring_belanja";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
$query = "SELECT * FROM level";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID Level</th><th>Level Jabatan</th><th>Folder Name</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id_level'] . "</td>";
        echo "<td>" . $row['level_jabatan'] . "</td>";
        echo "<td>" . $row['folder_name'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data pada tabel level.";
}
