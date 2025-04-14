<?php

// session_start();
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

$result = $conn->query("SELECT log_activity.*, users.name FROM log_activity 
    JOIN users ON log_activity.user_id = users.id 
    ORDER BY log_activity.created_at DESC 
    LIMIT 50");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Recent Activity</title>
</head>

<body>
    <h2>Recent Activity</h2>
    <table border="1">
        <thead>
            <tr>
                <th>User</th>
                <th>Aktivitas</th>
                <th>IP Address</th>
                <th>User Agent</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['aktivitas']) ?></td>
                <td><?= $row['ip_address'] ?></td>
                <td><?= substr($row['user_agent'], 0, 30) ?>...</td>
                <td><?= $row['created_at'] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>