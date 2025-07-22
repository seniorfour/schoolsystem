<?php
require_once('../db.php');
$result = $conn->query("SELECT l.*, s.first_name, s.last_name FROM activitylog l LEFT JOIN staff s ON l.user_id = s.staff_id ORDER BY timestamp DESC LIMIT 200");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Activity Logs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Activity Logs</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">&larr; Back to Dashboard</a>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Time</th><th>User</th><th>Action</th><th>Table</th><th>Record ID</th><th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['timestamp']) ?></td>
                <td><?= htmlspecialchars(trim($row['first_name'].' '.$row['last_name'])) ?></td>
                <td><?= htmlspecialchars($row['action_type']) ?></td>
                <td><?= htmlspecialchars($row['table_affected']) ?></td>
                <td><?= htmlspecialchars($row['record_id']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>