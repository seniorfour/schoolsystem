<?php
require_once('../db.php');

// Fetch activity logs
$query = "SELECT * FROM activity_logs ORDER BY timestamp DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Activity Log</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            padding: 30px;
        }
        h2 {
            margin-bottom: 20px;
        }
        table th, table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <h2 class="text-primary">System Activity Log</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User ID</th>
                        <th>Action</th>
                        <th>Table</th>
                        <th>Record ID</th>
                        <th>Timestamp</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($log = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $log['log_id'] ?></td>
                            <td><?= $log['user_id'] ?></td>
                            <td><?= htmlspecialchars($log['action_type']) ?></td>
                            <td><?= htmlspecialchars($log['table_affected']) ?></td>
                            <td><?= $log['record_id'] ?? '-' ?></td>
                            <td><?= date('Y-m-d H:i:s', strtotime($log['timestamp'])) ?></td>
                            <td><?= htmlspecialchars($log['description']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No activity logs found.</div>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">← Back to Dashboard</a>
</body>
</html>
