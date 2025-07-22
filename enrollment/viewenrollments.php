<?php
require_once('../db.php');
require_once('../header.php');
require_once('../includes/sidebar.php');

// Get classes and years for filters
$classes = $conn->query("SELECT class_id, name, year FROM classes ORDER BY year, name");
$years = $conn->query("SELECT DISTINCT academic_year FROM enrollments ORDER BY academic_year DESC");

// Handle filter selection
$class_id = $_GET['class_id'] ?? '';
$academic_year = $_GET['academic_year'] ?? '';

// Fetch enrollments if filters selected
$enrollments = null;
if ($class_id && $academic_year) {
    $stmt = $conn->prepare(
        "SELECT e.*, s.first_name, s.last_name, s.gender, s.email
         FROM enrollments e
         JOIN students s ON e.student_id = s.student_id
         WHERE e.class_id = ? AND e.academic_year = ?
         ORDER BY s.first_name, s.last_name"
    );
    $stmt->bind_param('is', $class_id, $academic_year);
    $stmt->execute();
    $enrollments = $stmt->get_result();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Enrollments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
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
    <h2 class="mb-4">View Enrollments by Class/Year</h2>
    <a href="enrollstudent.php" class="btn btn-primary mb-3">Enroll a Student</a>

    <form method="get" class="row g-3 mb-4">
        <div class="col-md-5">
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
        <div class="col-md-3">
            <label class="form-label">Academic Year</label>
            <select name="academic_year" class="form-select" required>
                <option value="">-- Select Year --</option>
                <?php while($y = $years->fetch_assoc()): ?>
                    <option value="<?= $y['academic_year'] ?>" <?= $y['academic_year'] == $academic_year ? 'selected' : '' ?>>
                        <?= $y['academic_year'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-success">View</button>
        </div>
    </form>

    <?php if ($class_id && $academic_year): ?>
        <h4>Enrolled Students</h4>
        <?php if ($enrollments && $enrollments->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i = 1; while($row = $enrollments->fetch_assoc()): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['first_name'].' '.$row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">No students enrolled in this class for the selected year.</div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include('../footer.php'); ?>
</body>
</html>
