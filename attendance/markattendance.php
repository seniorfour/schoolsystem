<?php
require_once('../db.php');
require_once('../header.php'); // Correct path
require_once('../includes/sidebar.php'); // Correct path

$classes = $conn->query("SELECT class_id, name, year FROM classes ORDER BY year, name");

$step = 1;
$class_id = $_POST['class_id'] ?? '';
$date = $_POST['date'] ?? date('Y-m-d');
$students = null;
$success = isset($_GET['success']) ? "Attendance has been marked for this class and date." : '';

// Step 1 → Step 2: Fetch students
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $class_id && isset($_POST['step1'])) {
    $step = 2;
    $stmt = $conn->prepare("
        SELECT s.student_id, s.first_name, s.last_name
        FROM enrollments e
        JOIN students s ON e.student_id = s.student_id
        WHERE e.class_id = ?
        AND e.academic_year = YEAR(?)
        ORDER BY s.first_name, s.last_name
    ");
    $stmt->bind_param('is', $class_id, $date);
    $stmt->execute();
    $students = $stmt->get_result();
    $stmt->close();
}

// Final submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['step2'])) {
    $class_id = $_POST['class_id'];
    $date = $_POST['date'];

    foreach ($_POST['attendance'] as $student_id => $status) {
        $conn->query("DELETE FROM attendance WHERE student_id = '$student_id' AND class_id = '$class_id' AND date = '$date'");

        $stmt = $conn->prepare("INSERT INTO attendance (student_id, class_id, date, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiss', $student_id, $class_id, $date, $status);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: markattendance.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .sidebar {
            position: fixed;
            height: 100vh;
            width: 250px;
            background-color: #f1f1f1;
            padding: 20px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <?php include('../includes/sidebar.php'); ?>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2 class="mb-4">Mark Attendance</h2>
    <a href="attendancereport.php" class="btn btn-secondary mb-3">&larr; Attendance Report</a>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm">
        <?php if ($step == 1): ?>
        <form method="post" class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label">Class</label>
                <select name="class_id" class="form-select" required>
                    <option value="">-- Select Class --</option>
                    <?php while($c = $classes->fetch_assoc()): ?>
                    <option value="<?= $c['class_id'] ?>" <?= $c['class_id'] == $class_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name'].' ('.$c['year'].')') ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>" required>
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" name="step1" class="btn btn-primary">Next</button>
            </div>
        </form>
        <?php elseif ($step == 2): ?>
        <form method="post">
            <input type="hidden" name="class_id" value="<?= htmlspecialchars($class_id) ?>">
            <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($students->num_rows == 0): ?>
                    <tr>
                        <td colspan="2">No students enrolled in this class for this year.</td>
                    </tr>
                <?php else: ?>
                <?php while($row = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
                        <td>
                            <select name="attendance[<?= $row['student_id'] ?>]" class="form-select" required>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                            </select>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php endif; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-between">
                <button type="submit" name="step2" class="btn btn-success">Submit Attendance</button>
                <a href="markattendance.php" class="btn btn-secondary">Back</a>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php include('../footer.php'); ?>

</body>
</html>
