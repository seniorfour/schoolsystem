<?php
require_once('../db.php');

// Get classes and students for filter
$classes = $conn->query("SELECT class_id, name, year FROM classes ORDER BY year, name");
$students = $conn->query("SELECT student_id, first_name, last_name FROM students ORDER BY first_name, last_name");

$class_id = $_GET['class_id'] ?? '';
$student_id = $_GET['student_id'] ?? '';
$date = $_GET['date'] ?? '';
$report = null;

if ($_GET) {
    $query = "
        SELECT a.*, s.first_name, s.last_name, c.name AS class_name, c.year AS class_year
        FROM attendance a
        JOIN students s ON a.student_id = s.student_id
        JOIN classes c ON a.class_id = c.class_id
        WHERE 1
    ";
    $params = [];
    $types = '';
    if ($class_id) { $query .= " AND a.class_id=?"; $params[] = $class_id; $types .= 'i'; }
    if ($student_id) { $query .= " AND a.student_id=?"; $params[] = $student_id; $types .= 'i'; }
    if ($date) { $query .= " AND a.date=?"; $params[] = $date; $types .= 's'; }
    $query .= " ORDER BY a.date DESC, s.first_name, s.last_name";

    $stmt = $conn->prepare($query);
    if ($params) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $report = $stmt->get_result();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
        .card-style {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        table th, table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<?php include '../header.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="content-wrapper">
    <div class="container-fluid">
        <h3 class="mb-3">Attendance Report</h3>
        <a href="markattendance.php" class="btn btn-primary mb-3">&larr; Mark Attendance</a>

        <form method="get" class="row g-3 mb-4 card-style">
            <div class="col-md-3">
                <label class="form-label">Class</label>
                <select name="class_id" class="form-select">
                    <option value="">-- All Classes --</option>
                    <?php while($c = $classes->fetch_assoc()): ?>
                        <option value="<?= $c['class_id'] ?>" <?= $c['class_id']==$class_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name'].' ('.$c['year'].')') ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Student</label>
                <select name="student_id" class="form-select">
                    <option value="">-- All Students --</option>
                    <?php while($s = $students->fetch_assoc()): ?>
                        <option value="<?= $s['student_id'] ?>" <?= $s['student_id']==$student_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>">
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-success">Filter</button>
            </div>
        </form>

        <?php if ($report): ?>
            <div class="card-style">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Class</th>
                            <th>Student</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($report->num_rows == 0): ?>
                        <tr><td colspan="4" class="text-center text-muted">No attendance records found.</td></tr>
                    <?php else: ?>
                        <?php while($row = $report->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                                <td><?= htmlspecialchars($row['class_name'].' ('.$row['class_year'].')') ?></td>
                                <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
                                <td><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
