<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "monitoring_belanja";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
$query = "SELECT * FROM signatures";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>permohonan_id</th><th>pejabat_id</th><th>signature</th><th>created_at</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['permohonan_id'] . "</td>";
        echo "<td>" . $row['pejabat_id'] . "</td>";
        echo "<td>" . $row['signature'] . "</td>";
        echo "<td>" . $row['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data pada tabel level.";
}
