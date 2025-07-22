<?php
require_once('../db.php');
$students = $conn->query("SELECT COUNT(*) AS c FROM students")->fetch_assoc()['c'];
$staff = $conn->query("SELECT COUNT(*) AS c FROM staff")->fetch_assoc()['c'];
$classes = $conn->query("SELECT COUNT(*) AS c FROM classes")->fetch_assoc()['c'];
$fees_collected = $conn->query("SELECT SUM(amount_paid) AS total FROM payments")->fetch_assoc()['total'] ?? 0;

session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .content-wrapper {
            margin-left: 250px; /* space for sidebar */
            padding: 20px;
        }
    </style>
</head>
<body>

<?php include '../header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid py-4">
        <h2 class="mb-4">Admin Dashboard</h2>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-bg-success mb-3">
                    <div class="card-body">
                        <h4><?= $students ?></h4>
                        <p>Students</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-info mb-3">
                    <div class="card-body">
                        <h4><?= $staff ?></h4>
                        <p>Staff</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-primary mb-3">
                    <div class="card-body">
                        <h4><?= $classes ?></h4>
                        <p>Classes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-warning mb-3">
                    <div class="card-body">
                        <h4><?= number_format($fees_collected, 2) ?></h4>
                        <p>Total Fees Collected</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="mb-3">
            <a href="activity_log.php" class="btn btn-outline-dark">View Activity Logs</a>
            <a href="attendance_summary.php" class="btn btn-outline-secondary">Attendance Summary</a>
            <a href="fee_summary.php" class="btn btn-outline-secondary">Fee Summary</a>
            <a href="performance_reports.php" class="btn btn-outline-secondary">Performance Reports</a>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
