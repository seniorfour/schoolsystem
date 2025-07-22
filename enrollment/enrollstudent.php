<?php
require_once('../db.php');
require_once('../header.php'); // Include header
require_once('../includes/sidebar.php'); // Include sidebar

// Get students and classes
$students = $conn->query("SELECT student_id, first_name, last_name FROM students ORDER BY first_name, last_name");
$classes = $conn->query("SELECT class_id, name, year FROM classes ORDER BY year, name");

// Handle submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'] ?? '';
    $class_id = $_POST['class_id'] ?? '';
    $academic_year = $_POST['academic_year'] ?? '';

    if ($student_id && $class_id && $academic_year) {
        $stmt = $conn->prepare("INSERT INTO enrollments (student_id, class_id, academic_year) VALUES (?, ?, ?)");
        $stmt->bind_param('iis', $student_id, $class_id, $academic_year);
        $stmt->execute();
        $stmt->close();
        $success = "Enrollment successful!";
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Enroll Student</title>
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
    <h2 class="mb-4">Enroll Student</h2>
    <a href="viewenrollments.php" class="btn btn-secondary mb-3">&larr; View Enrollments</a>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Student</label>
            <select name="student_id" class="form-select" required>
                <option value="">-- Select Student --</option>
                <?php while($s = $students->fetch_assoc()): ?>
                    <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Class</label>
            <select name="class_id" class="form-select" required>
                <option value="">-- Select Class --</option>
                <?php while($c = $classes->fetch_assoc()): ?>
                    <option value="<?= $c['class_id'] ?>"><?= htmlspecialchars($c['name'].' ('.$c['year'].')') ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Academic Year</label>
            <input type="number" name="academic_year" class="form-control" min="2000" max="2100" required placeholder="YYYY">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Enroll</button>
        </div>
    </form>
</div>

<?php include('../footer.php'); ?>
</body>
</html>
